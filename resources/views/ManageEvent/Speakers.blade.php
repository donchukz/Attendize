@extends('Shared.Layouts.Master')

@section('title')
@parent
@lang("Speaker.title")
@stop


@section('page_title')
<i class="ico-users"></i>
@lang("Speaker.title")
@stop

@section('top_nav')
@include('ManageEvent.Partials.TopNav')
@stop

@section('menu')
@include('ManageEvent.Partials.Sidebar')
@stop


@section('head')

@stop

@section('page_header')

<div class="col-md-9">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-responsive">
            <button data-modal-id="InviteAttendee" href="javascript:void(0);"  data-href="{{route('showAddNewSpeaker', ['event_id'=>$event->id])}}" class="loadModal btn btn-success" type="button"><i class="ico-user-plus"></i> @lang("Speaker.add_title")</button>
        </div>

        <div class="btn-group btn-group-responsive">
            <a class="btn btn-success" href="{{route('showPrintSpeakers', ['event_id'=>$event->id])}}" target="_blank" ><i class="ico-print"></i> @lang("ManageEvent.print_speakers_list")</a>
        </div>
        <div class="btn-group btn-group-responsive">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="ico-users"></i> @lang("ManageEvent.export") <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{route('showExportSpeakers', ['event_id'=>$event->id,'export_as'=>'xlsx'])}}">@lang("File_format.Excel_xlsx")</a></li>
                <li><a href="{{route('showExportSpeakers', ['event_id'=>$event->id,'export_as'=>'xls'])}}">@lang("File_format.Excel_xls")</a></li>
                <li><a href="{{route('showExportSpeakers', ['event_id'=>$event->id,'export_as'=>'csv'])}}">@lang("File_format.csv")</a></li>
                <li><a href="{{route('showExportSpeakers', ['event_id'=>$event->id,'export_as'=>'html'])}}">@lang("File_format.html")</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-3">
   {!! Form::open(array('url' => route('showEventSpeakers', ['event_id'=>$event->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
    <div class="input-group">
        <input name="q" value="{{$q}}" placeholder="@lang("Speaker.search_attendees")" type="text" class="form-control" />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
    </div>
   {!! Form::close() !!}
</div>
@stop


@section('content')

<!--Start Attendees table-->
<div class="row">
    <div class="col-md-12">
        @if($speakers->count())
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                               {!!Html::sortable_link(trans("Speaker.name"), $sort_by, 'first_name', $sort_order, ['q' => $q , 'page' => $speakers->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("Speaker.email"), $sort_by, 'email', $sort_order, ['q' => $q , 'page' => $speakers->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("Speaker.bio"), $sort_by, 'ticket_id', $sort_order, ['q' => $q , 'page' => $speakers->currentPage()])!!}
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($speakers as $speaker)
                        <tr class="speaker_{{$speaker->id}} {{$speaker->is_cancelled ? 'danger' : ''}}">
                            <td>{{{$speaker->name}}}</td>
                            <td>
                                {{$speaker->email}}
                            </td>
                            <td>
                                {{{$speaker->bio}}}
                            </td>

                            <td class="text-center">
                                <a
                                    data-modal-id="EditSpeaker"
                                    href="javascript:void(0);"
                                    data-href="{{route('showEditSpeaker', ['event_id'=>$event->id, 'speaker_id'=>$speaker->id])}}"
                                    class="loadModal btn btn-xs btn-primary"
                                > @lang("basic.edit")</a>

                                <a
                                    data-modal-id="CancelAttendee"
                                    href="javascript:void(0);"
                                    data-href="{{route('showDeleteSpeaker', ['event_id'=>$event->id, 'speaker_id'=>$speaker->id])}}"
                                    class="loadModal btn btn-xs btn-danger"
                                > @lang("basic.delete")</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else

        @if(!empty($q))
        @include('Shared.Partials.NoSearchResults')
        @else
        @include('ManageEvent.Partials.SpeakersBlankSlate')
        @endif

        @endif
    </div>
    <div class="col-md-12">
        {!!$speakers->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order, 'q' => $q])->render()!!}
    </div>
</div>    <!--/End attendees table-->

@stop


