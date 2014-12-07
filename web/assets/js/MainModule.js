var app = angular.module('ommaApp', [
        'app.date_time_ctrl',
        'app.todo_ctrl',
        'app.agenda_ctrl',
        'app.protocol_ctrl',
        'app.file_ctrl',
        'app.attendee_ctrl'

    ])


        .run ( function ( $log, agenda_model ) {
            $log.log ( "app started");

            $log.log (agenda_model.getTest());

            agenda_model.setTest( "test erfolgreich" );
            $log.log (agenda_model.getTest());

    })
    ;