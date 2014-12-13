/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingController', ['$scope', 'Restangular', 'editableThemes', function($scope, Restangular, editableThemes) {
    // change inline editing input and button size
    editableThemes.bs3.inputClass = 'input-lg';
    editableThemes.bs3.buttonsClass = 'btn-lg';

    $scope.meeting = {};
    $scope.init = function(data) {
        $scope.meeting = data;
        Restangular.restangularizeElement(null, data, 'meetings');
        $scope.$watch('meeting', _.after(3, _.debounce($scope.saveMeeting, 1000)), true);
    };

    $scope.saveMeeting = function() {
        $scope.meeting.put();
    };
}]);
