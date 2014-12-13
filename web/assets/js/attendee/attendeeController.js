/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingAttendeeController', ['$scope', 'attendeeService', function($scope, attendeeService) {
    $scope.selectedUser = null;
    $scope.attendees = [];

    function sort() {
        $scope.attendees = _.sortBy($scope.attendees, function(attendee) {
            return attendee.user.username;
        });
    }

    attendeeService.getAll($scope.$parent.meeting).then(function(attendees) {
        $scope.attendees = attendees;
        sort();
    });

    $scope.addUser = function() {
        var selectedUser = $scope.selectedUser;
        $scope.selectedUser = null;
        // check if attendee already exists
        var attendee = _.find($scope.attendees, function(attendee) {
            return attendee.user.id === selectedUser.id;
        });
        if (undefined !== attendee) {
            return;
        }
        var placeholder = {
            status: 'invited',
            user: selectedUser
        };
        $scope.attendees.push(placeholder);
        attendeeService.add($scope.$parent.meeting, selectedUser.id).then(function(attendee) {
            _.pull($scope.attendees, placeholder);
            $scope.attendees.push(attendee);
            sort();
        });
    };

    $scope.removeAttendee = function(attendee) {
        _.pull($scope.attendees, attendee);
        attendeeService.remove(attendee);
    };

    $scope.search = function(term) {
        console.log(term);
    };
}]);
