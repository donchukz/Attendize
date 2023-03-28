<div role="dialog"  class="modal fade " style="display: none;">
   {!! Form::open(array('url' => route('postSpeaker', array('event_id' => $event->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-user"></i>
                    @lang("Speaker.add_title")</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                {!! Form::label('first_name', trans("Speaker.name"), array('class'=>'control-label required')) !!}

                                {!!  Form::text('name', old('name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                {!! Form::label('email', trans("Speaker.email"), array('class'=>'control-label required')) !!}

                                {!!  Form::text('email', old('email'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('bio', trans("Speaker.bio"), array('class'=>'control-label')) !!}

                            {!!  Form::textarea('bio', old('bio'),
                                                array(
                                                'class'=>'form-control'
                                                ))  !!}
                        </div>

                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit(trans("Speaker.add_title"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
