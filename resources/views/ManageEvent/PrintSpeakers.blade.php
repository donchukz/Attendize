<html>
    <head>
        <title>
            @lang('Event.print_speakers_title')
        </title>

        <!--Style-->
       {!!Html::style('assets/stylesheet/application.css')!!}
        <!--/Style-->

        <style type="text/css">
            .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
                padding: 3px;
            }
            table {
                font-size: 13px;
            }
        </style>
    </head>
    <body style="background-color: #FFFFFF;" onload="window.print();">
        <div class="well" style="border:none; margin: 0;">
            {{ @trans("Event.n_speakers_for_event", ["num"=>$speakers->count(), "name"=>$event->title, "date"=>$event->start_date->format(config('attendize.default_datetime_format'))]) }}
            <br>
        </div>

        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>@lang("Speaker.name")</th>
                    <th>@lang("Speaker.email")</th>
                    <th>@lang("Speaker.bio")</th>
                </tr>
            </thead>
            <tbody>
                @foreach($speakers as $speaker)
                <tr>
                    <td>{{{$speaker->name}}}</td>
                    <td>{{{$speaker->email}}}</td>
                    <td>{{{$speaker->bio}}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
