<div role="dialog"  class="modal fade " style="display: none;">
   {!! Form::model($speaker, array('url' => route('postDeleteSpeaker', array('event_id' => $event->id, 'speaker_id' => $speaker->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-cancel"></i>
                    {{ @trans("ManageEvent.delete_speaker_title", ["cancel" => $speaker->name]) }}</h3>
            </div>
            <div class="modal-body">
                <p>
                    {{ @trans("ManageEvent.delete_speaker_description") }}
                </p>

                <br>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::hidden('speaker_id', $speaker->id) !!}
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit(trans("ManageEvent.confirm_speaker_delete"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>

