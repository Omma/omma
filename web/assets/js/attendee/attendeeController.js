/**
 * @class meetingAttendeeController
 * @ngdoc controller
 * @name ommaApp.attendee:meetingAttendeeController
 * @requires $scope
 * @requires ommaApp.attendee:attendeeService
 * @description
 * Controller for displaying the attendee tab in the meeting overview.
 *
 *
 * Author: Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingAttendeeController', ['$scope', 'attendeeService', function($scope, attendeeService) {
    /**
     * @ngdoc property
     * @name $scope_selectedUser
     * @propertyOf ommaApp.attendee:meetingAttendeeController
     * @returns {Object} currently selected user
     */
    $scope.selectedUser = null;
    /**
     * @ngdoc property
     * @name $scope_attendees
     * @propertyOf ommaApp.attendee:meetingAttendeeController
     * @returns {Array} array with attendees for this meeting
     */
    $scope.attendees = [];

    function sort() {
        $scope.attendees = _.sortBy($scope.attendees, function(attendee) {
            return attendee.user.username;
        });
    }

    /**
     * @ngdoc method
     * @name alreadExists
     * @methodOf ommaApp.attendee:meetingAttendeeController
     * @description check if user is already added to the current meeting
     * @param {int} userId id of the user
     * @return {boolean} result
     */
    function alreadExists(userId) {
        var result = _.find($scope.attendees, function(attendee) {
            return attendee.user.id === userId;
        });
        return undefined !== result;
    }

    /**
     * @ngdoc method
     * @name doAddUser
     * @methodOf ommaApp.attendee:meetingAttendeeController
     * @description
     * Add new user to the meething.
     * This function will add a placeholder for the user, send the data to the
     * backend and replace the placeholder with the real data.
     *
     * @param {Object} user user to be added
     */
    function doAddUser(user) {
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

    // load attendees for this meeting
    attendeeService.getAll($scope.$parent.meeting).then(function(attendees) {
        $scope.attendees = attendees;
        sort();
    });

    /**
     * @ngdoc method
     * @name $scope_addUser
     * @methodOf ommaApp.attendee:meetingAttendeeController
     * @description
     * Add currently selected user to attendee list
     */
    $scope.addUser = function() {
        var selectedUser = $scope.selectedUser;
        $scope.selectedUser = null;
        if (alreadExists(selectedUser.id)) {
            return;
        }

        doAddUser(selectedUser);
    };

    /**
     * @ngdoc method
     * @name $scope_removeAttendee
     * @methodOf ommaApp.attendee:meetingAttendeeController
     * @description
     * removes a attendee from the list
     *
     * @param {Object} attendee attendee object
     */
    $scope.removeAttendee = function(attendee) {
        _.pull($scope.attendees, attendee);
        attendeeService.remove(attendee);
    };

    // listen for attendee.copy events to copy me
    $scope.$on('attendee.copy', function(event, args) {
        $scope.copyFromMeeting(args.meeting);
    });

    $scope.copyFromMeeting = function(meeting) {
        attendeeService.getAll(meeting).then(function(attendees) {
            angular.forEach(attendees, function (attendee) {
                if (alreadExists(attendee.user.id)) {
                    return;
                }
                doAddUser(attendee.user);
            });
        });
    };
}]);
