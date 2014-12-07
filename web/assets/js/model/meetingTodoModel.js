var todo_model = function ( $log )
{
    $log.log ("new Instance of todo Model");

    return {

    }
}

var todo_model = angular.module('app.todo_model', [])

    .factory ( "todo_model", todo_model );
//.service ( "Model", Model );
;
