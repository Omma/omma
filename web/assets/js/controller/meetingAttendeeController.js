
function meetingAttendeeController() {

    console.log("attendee ctrl");


}

var p = meetingAttendeeController.prototype;

p.onClick = function(){

}



var attendee_ctrl = angular.module('app.attendee_ctrl', ['app.attendee_model'])
        .controller('meetingAttendeeController', meetingAttendeeController)
    ;