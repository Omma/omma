/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingController', ['$scope', 'Restangular', 'editableThemes', function($scope, Restangular, editableThemes) {
    // change inline editing input and button size
    editableThemes.bs3.inputClass = 'input-lg';
    editableThemes.bs3.buttonsClass = 'btn-lg';

    $scope.meeting = {};
    $scope.init = function(data) {
        console.log(data);
        $scope.meeting = data;

        $scope.meetingRequest = Restangular.one('meetings', data.id).get().then(function(meeting) {
            $scope.meeting = meeting;

            $scope.$watch('meeting', _.after(2, _.debounce($scope.saveMeeting, 1000)), true);
            return meeting;
        });

    };

    $scope.saveMeeting = function() {
        $scope.meeting.put();
    };
}]);
