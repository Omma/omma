var agenda_model = function ( $log, test )
{
    $log.log ("new Instance of agenda Model");

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
    .value("test", "test...")

    .factory ( "agenda_model", agenda_model );
//.service ( "Model", Model );
;
