
function meetingDateTimeController() {

    console.log("datetime ctrl");


}

var p = meetingDateTimeController.prototype;

p.onClick = function(){

}



var date_time_ctrl = angular.module('app.date_time_ctrl', ['app.date_time_model'])
    .controller('meetingDateTimeController', meetingDateTimeController)
;