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

    /**
     * check if user is already added
     * @param userId
     */
    function alreadExists(userId) {
        var result = _.find($scope.attendees, function(attendee) {
            return attendee.user.id === userId;
        });
        return undefined !== result;
    }

    function addUser(user) {
        var placeholder = {
            status: 'invited',
            user: user
        };
        $scope.attendees.push(placeholder);
        attendeeService.add($scope.$parent.meeting, user.id).then(function(attendee) {
            _.pull($scope.attendees, placeholder);
            $scope.attendees.push(attendee);
            sort();
        });
    }

    attendeeService.getAll($scope.$parent.meeting).then(function(attendees) {
        $scope.attendees = attendees;
        sort();
    });

    $scope.addUser = function() {
        var selectedUser = $scope.selectedUser;
        $scope.selectedUser = null;
        if (alreadExists(selectedUser.id)) {
            return;
        }

        addUser(selectedUser);
    };

    $scope.removeAttendee = function(attendee) {
        _.pull($scope.attendees, attendee);
        attendeeService.remove(attendee);
    };

    $scope.$on('attendee.copy', function(event, args) {
        $scope.copyFromMeeting(args.meeting);
    });

    $scope.copyFromMeeting = function(meeting) {
        attendeeService.getAll(meeting).then(function(attendees) {
            angular.forEach(attendees, function (attendee) {
                if (alreadExists(attendee.user.id)) {
                    return;
                }
                addUser(attendee.user);
            });
        });
    };
}]);
