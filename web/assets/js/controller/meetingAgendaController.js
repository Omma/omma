
function meetingAgendaController() {

    console.log("agenda ctrl");


}

var p = meetingAgendaController.prototype;

p.onClick = function(){

}



var agenda_ctrl = angular.module('app.agenda_ctrl', ['app.agenda_model'])
        .controller('meetingAgendaController', meetingAgendaController)
    ;