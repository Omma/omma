/*
function meetingAgendaController() {

    console.log('agenda ctrl');


}

var p = meetingAgendaController.prototype;

p.onClick = function(){

}



var agenda_ctrl = angular.module('app.agenda_ctrl', ['app.agenda_model'])
        .controller('meetingAgendaController', meetingAgendaController)
    ;

function meetingAttendeeController() {

    console.log('attendee ctrl');


}

var p = meetingAttendeeController.prototype;

p.onClick = function(){

}



var attendee_ctrl = angular.module('app.attendee_ctrl', ['app.attendee_model'])
        .controller('meetingAttendeeController', meetingAttendeeController)
    ;

function meetingDateTimeController() {

    console.log('datetime ctrl');


}

var p = meetingDateTimeController.prototype;

p.onClick = function(){

}



var date_time_ctrl = angular.module('app.date_time_ctrl', ['app.date_time_model'])
    .controller('meetingDateTimeController', meetingDateTimeController)
;

function meetingFileController() {

    console.log('file ctrl');


}

var p = meetingFileController.prototype;

p.onClick = function(){

}



var file_ctrl = angular.module('app.file_ctrl', ['app.file_model'])
        .controller('meetingFileController', meetingFileController)
    ;

function meetingProtocolController() {

    console.log('protocol ctrl');


}

var p = meetingProtocolController.prototype;

p.onClick = function(){

}



var protocol_ctrl = angular.module('app.protocol_ctrl', ['app.protocol_model'])
        .controller('meetingProtocolController', meetingProtocolController)
    ;

function meetingTodoController() {

    console.log('todo ctrl');


}

var p = meetingTodoController.prototype;

p.onClick = function(){

}



var todo_ctrl = angular.module('app.todo_ctrl', ['app.todo_model'])
        .controller('meetingTodoController', meetingTodoController)
    ;
var agenda_model = function ( $log, test )
{
    $log.log ('new Instance of agenda Model');

    return {
        getTest : function () {
            return test;
        },
        setTest : function ( val ){
            test = val;
        }
    }
}

var agenda_model = angular.module('app.agenda_model', [])
    .value('test', 'test...')

    .factory ( 'agenda_model', agenda_model );
//.service ( 'Model', Model );
;

var attendee_model = function ( $log )
{
    $log.log ('new Instance of attendee Model');

    return {

    }
}

var date_time_model = angular.module('app.attendee_model', [])

    .factory ( 'attendee_model', attendee_model );
//.service ( 'Model', Model );
;

var date_time_model = function ( $log )
{
    $log.log ('new Instance of datetime Model');

    return {



    }
}

var date_time_model = angular.module('app.date_time_model', [])

    .factory ( 'date_time_model', date_time_model );
//.service ( 'Model', Model );
;

var file_model = function ( $log )
{
    $log.log ('new Instance of file Model');

    return {

    }
}

var file_model = angular.module('app.file_model', [])

    .factory ( 'file_model', file_model );
//.service ( 'Model', Model );
;

var protocol_model = function ( $log )
{
    $log.log ('new Instance of protocol Model');

    return {
    }
}

var protocol_model = angular.module('app.protocol_model', [])

    .factory ( 'protocol_model', protocol_model );
//.service ( 'Model', Model );
;

var todo_model = function ( $log )
{
    $log.log ('new Instance of todo Model');

    return {

    }
}

var todo_model = angular.module('app.todo_model', [])

    .factory ( 'todo_model', todo_model );
//.service ( 'Model', Model );
;

var app = angular.module('ommaApp', [
        'app.date_time_ctrl',
        'app.todo_ctrl',
        'app.agenda_ctrl',
        'app.protocol_ctrl',
        'app.file_ctrl',
        'app.attendee_ctrl'

    ])


        .run ( function ( $log, agenda_model ) {
            $log.log ( 'app started');

            $log.log (agenda_model.getTest());

            agenda_model.setTest( 'test erfolgreich' );
            $log.log (agenda_model.getTest());

    })
    ;

    */