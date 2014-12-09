




angular.module('ommaApp')
    .controller('protocolController', ['$scope', function ($scope) {




        $scope.orightml = 'hallo das ist ein test';
        $scope.htmlcontent = $scope.orightml;
        $scope.disabled = false; //Protokoll editieren aktivieren oder deaktivieren
        $scope.status = '';


        $scope.getButtonClass = function() {
            if($scope.disabled===false) {
                return '';
            }
            else {
                return 'disabled';
            }
        };



        $scope.$watch('orightml', function(val){
            console.log(val);
            $scope.status = 'Änderungen speichern...';

            setTimeout(function() {

                console.log('saved');
                $scope.status = 'Alle Änderungen sind gespeichert.';

            }, 500);


        });



}]);