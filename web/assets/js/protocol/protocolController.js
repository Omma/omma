/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('protocolController', ['$scope', function ($scope) {

        $scope.orightml = 'hallo das ist ein test';
        $scope.htmlcontent = $scope.orightml;
        $scope.disabled = false; //Protokoll editieren aktivieren oder deaktivieren
        $scope.saving = false;


        $scope.getSaving = function() {
            return $scope.saving;
        };

        $scope.getButtonClass = function() {
            if($scope.disabled===false) {
                return '';
            }
            else {
                return 'disabled';
            }
        };


        $scope.deleteModal = function() {
            console.log('protokoll in db als final markieren');

            $scope.disabled = true;
        };


        $scope.$watch('orightml', function(newValue, oldValue) {

            if (newValue !== oldValue) {
                $scope.saving = true;
            }

        });

        $scope.$watch('orightml', _.debounce(function (newValue, oldValue) {

            if (newValue !== oldValue) {

                $scope.$apply(function () {
                    console.log('update db protocol text mit var newValue');
                    $scope.saving = false;
                });
            }
        }, 700));
}]);