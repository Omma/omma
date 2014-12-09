/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
app.controller('meetingController', ['$scope', 'Restangular', function($scope, Restangular) {
    $scope.init = function(id) {
        $scope.meeting = Restangular.one('meetings', id).get().then(function(meeting) {
            return meeting;
        });
    };
}]);
