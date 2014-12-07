var attendee_model = function ( $log )
{
    $log.log ("new Instance of attendee Model");

    return {

    }
}

var date_time_model = angular.module('app.attendee_model', [])

    .factory ( "attendee_model", attendee_model );
//.service ( "Model", Model );
;
