
function meetingProtocolController() {

    console.log("protocol ctrl");


}

var p = meetingProtocolController.prototype;

p.onClick = function(){

}



var protocol_ctrl = angular.module('app.protocol_ctrl', ['app.protocol_model'])
        .controller('meetingProtocolController', meetingProtocolController)
    ;