<?php

namespace App\Http\Controllers;

use App\Exports\SpeakersExport;
use App\Models\Event;
use App\Models\Speaker;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Validator;

class EventSpeakersController extends MyBaseController
{

    /**
     * Show the speakers list
     *
     * @param Request $request
     * @param $event_id
     * @return View
     */
    public function showEventSpeakers(Request $request, $event_id)
    {
        $allowed_sorts = ['name', 'email'];

        $searchQuery = $request->get('q');
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'created_at');

        $event = Event::scope()->find($event_id);

        if ($searchQuery) {
            $speakers = $event->speakers()
                ->where(function ($query) use ($searchQuery) {
                    $query->where('speakers.name', 'like', $searchQuery . '%')
                        ->orWhere('speakers.email', 'like', $searchQuery . '%');
                })
                ->select('speakers.*')
                ->paginate();
        } else {
            $speakers = $event->speakers()
                ->select('speakers.*')
                ->paginate();
        }

        $data = [
            'speakers'  => $speakers,
            'event'      => $event,
            'sort_by'    => $sort_by,
            'sort_order' => $sort_order,
            'q'          => $searchQuery ? $searchQuery : '',
        ];

        return view('ManageEvent.Speakers', $data);
    }

    /**
     * Show the 'Add Speaker' modal
     *
     * @param Request $request
     * @param $event_id
     * @return string|View
     */
    public function showAddNewSpeaker(Request $request, $event_id)
    {
        $event = Event::scope()->find($event_id);

        return view('ManageEvent.Modals.AddSpeaker', [
            'event'   => $event
        ]);
    }

    /**
     * add an speakers
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function postSpeaker(Request $request, $event_id)
    {
        $rules = [
            'name' => 'required',
            'email'      => 'email|required',
            'bio' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $event = Event::findOrFail($event_id);
        $speaker_name = $request->get('name');
        $speaker_email = $request->get('email');
        $speaker_bio = $request->get('bio');

        DB::beginTransaction();

        try {

            /*
             * Create the speaker
             */
            $speaker = new Speaker();
            $speaker->name = $speaker_name;
            $speaker->email = $speaker_email;
            $speaker->bio = $speaker_bio;
            $speaker->event_id = $event_id;
            $speaker->account_id = auth()->user()->account_id;
            $speaker->save();

            session()->flash('message', trans("Controllers.speaker_successfully_invited"));

            DB::commit();

            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('showEventSpeakers', [
                    'event_id' => $event_id,
                ]),
            ]);

        } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error'  => trans("Controllers.speaker_exception")
            ]);
        }

    }


    /**
     * Show the 'Edit Speaker' modal
     *
     * @param Request $request
     * @param $event_id
     * @param $speaker_id
     * @return View
     */
    public function showEditSpeaker(Request $request, $event_id, $speaker_id)
    {
        $speaker = Speaker::scope()->findOrFail($speaker_id);

        $data = [
            'speaker' => $speaker,
            'event'    => $speaker->event
        ];

        return view('ManageEvent.Modals.EditSpeaker', $data);
    }

    /**
     * Updates speaker
     *
     * @param Request $request
     * @param $event_id
     * @param $speaker_id
     * @return mixed
     */
    public function postEditSpeaker(Request $request, $event_id, $speaker_id)
    {
        $rules = [
            'name' => 'required',
            'email'      => 'email|required',
            'bio' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $speaker = Speaker::scope()->findOrFail($speaker_id);
        $speaker->update($request->all());

        session()->flash('message',trans("Controllers.successfully_updated_speaker"));

        return response()->json([
            'status'      => 'success',
            'id'          => $speaker->id,
            'redirectUrl' => '',
        ]);
    }

    /**
     * Shows the 'Delete Speaker' modal
     *
     * @param Request $request
     * @param $event_id
     * @param $speaker_id
     * @return View
     */
    public function showDeleteSpeaker(Request $request, $event_id, $speaker_id)
    {
        $speaker = Speaker::scope()->findOrFail($speaker_id);

        $data = [
            'speaker' => $speaker,
            'event'    => $speaker->event
        ];

        return view('ManageEvent.Modals.DeleteSpeaker', $data);
    }

    /**
     * delete speaker
     *
     * @param Request $request
     * @param $event_id
     * @param $speaker_id
     * @return mixed
     */
    public function postDeleteSpeaker(Request $request, $event_id, $speaker_id)
    {
        $speaker = Speaker::scope()->findOrFail($speaker_id);
        $speaker->delete();

        session()->flash('message', trans("Controllers.successfully_deleted_speaker"));

        return response()->json([
            'status' => 'success',
            'id' => $speaker_id,
            'redirectUrl' => '',
        ]);
    }

    /**
     * Show the printable speakers list
     *
     * @param $event_id
     * @return View
     */
    public function showPrintSpeakers($event_id)
    {
        $data['event'] = Event::scope()->find($event_id);
        $data['speakers'] = $data['event']->speakers()->orderBy('name')->get();

        return view('ManageEvent.PrintSpeakers', $data);
    }

    /**
     * Downloads an export of speakers
     *
     * @param $event_id
     * @param string $export_as (xlsx, xls, csv, html)
     */
    public function showExportSpeakers($event_id, $export_as = 'xls')
    {
        $event = Event::scope()->findOrFail($event_id);
        $date = date('d-m-Y-g.i.a');
        return (new SpeakersExport($event->id))->download("speakers-as-of-{$date}.{$export_as}");
    }
}
