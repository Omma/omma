
function meetingFileController() {

    console.log("file ctrl");


}

var p = meetingFileController.prototype;

p.onClick = function(){

}



var file_ctrl = angular.module('app.file_ctrl', ['app.file_model'])
        .controller('meetingFileController', meetingFileController)
    ;