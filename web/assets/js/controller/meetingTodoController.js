
function meetingTodoController() {

    console.log("todo ctrl");


}

var p = meetingTodoController.prototype;

p.onClick = function(){

}



var todo_ctrl = angular.module('app.todo_ctrl', ['app.todo_model'])
        .controller('meetingTodoController', meetingTodoController)
    ;