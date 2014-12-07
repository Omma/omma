var file_model = function ( $log )
{
    $log.log ("new Instance of file Model");

    return {

    }
}

var file_model = angular.module('app.file_model', [])

    .factory ( "file_model", file_model );
//.service ( "Model", Model );
;
