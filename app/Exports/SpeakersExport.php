<?php

namespace App\Exports;

use App\Models\Speaker;
use Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\BeforeExport;

class SpeakersExport implements FromQuery, WithHeadings, WithEvents
{
    use Exportable;

    public function __construct(int $event_id)
    {
        $this->event_id = $event_id;
    }

    /**
     * @return \Illuminate\Support\Query
     */
    public function query()
    {
        $yes = strtoupper(trans("basic.yes"));
        $no = strtoupper(trans("basic.no"));
        $query = Speaker::query()->select([
            'speakers.name',
            'speakers.email',
            'speakers.bio'
        ])->join('events', 'events.id', '=', 'speakers.event_id')
            ->where('speakers.event_id', $this->event_id)
            ->where('speakers.account_id', Auth::user()->account_id);
        return $query;
    }

    public function headings(): array
    {
        return [
            trans("Speaker.name"),
            trans("Speaker.email"),
            trans("Speaker.bio"),
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                $event->writer->getProperties()->setCreator(config('attendize.app_name'));
                $event->writer->getProperties()->setCompany(config('attendize.app_name'));
            },
        ];
    }
}
