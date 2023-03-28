@extends('Shared.Layouts.BlankSlate')


@section('blankslate-icon-class')
    ico-users
@stop

@section('blankslate-title')
    @lang("ManageEvent.no_speakers_yet")
@stop

@section('blankslate-text')
    @lang("ManageEvent.no_speakers_yet_text")
@stop

@section('blankslate-body')
<button data-invoke="modal" data-modal-id='InviteSpeaker' data-href="{{route('showAddNewSpeaker', array('event_id'=>$event->id))}}" href='javascript:void(0);'  class=' btn btn-success mt5 btn-lg' type="button" >
    <i class="ico-user-plus"></i>
    @lang("Speaker.add_title")
</button>
@stop


