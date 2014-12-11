/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingAttendeeController', ['$scope', 'attendeeService', function($scope, attendeeService) {
    $scope.userId = null;
    $scope.attendees = [];

    function sort() {
        $scope.attendees = _.sortBy($scope.attendees, function(attendee) {
            return attendee.user.username;
        });
    }

    $scope.$parent.meetingRequest.then(function(meeting) {
        attendeeService.getAll(meeting).then(function(attendees) {
            $scope.attendees = attendees;
            sort();
        });
    });

    $scope.addUser = function() {
        var userId = $scope.userId;
        $scope.userId = null;
        if (userId.length <= 0) {
            return;
        }
        // check if attendee already exists
        var attendee = _.find($scope.attendees, function(attendee) {
            return attendee.user.id == userId;
        });
        if (undefined !== attendee) {
            return;
        }
        var placeholder = {
            status: 'invited',
            user: {
                id: userId,
                username: '...'
            }
        };
        $scope.attendees.push(placeholder);
        attendeeService.add($scope.$parent.meeting, userId).then(function(attendee) {
            _.pull($scope.attendees, placeholder);
            $scope.attendees.push(attendee);
            sort();
        });
    };

    $scope.removeAttendee = function(attendee) {
        _.pull($scope.attendees, attendee);
        attendeeService.remove(attendee);
    };
}]);
