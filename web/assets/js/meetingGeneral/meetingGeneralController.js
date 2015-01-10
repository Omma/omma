/**
 * @ngdoc controller
 * @name ommaApp.meetingGeneral:meetingGeneralController
 * @requires $scope
 * @requires meetingService
 * @description
 * Controller for the meeting tab 'general'
 *
 * Aauthor: Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingGeneralController', ['$scope', 'meetingService', function($scope, meetingService) {
    /**
     * @ngdoc property
     * @name $scope_date
     * @propertyOf ommaApp.meetingGeneral:meetingGeneralController
     * @returns {Object} object with default start and end date
     */
    $scope.date = {
        startDate: undefined,
        endDate: undefined
    };

    var meeting = $scope.$parent.meeting;


    $scope.date = {
        startDate: moment(meeting.date_start),
        endDate: moment(meeting.date_end)
    };

    // watch for changes of date and adjust start or end date accordingly
    // - if new end date is before the start, reset the end to the current start
    // - if new start is after end, reschedule the end by the same difference (e.g 1 Day later)
    $scope.$watch('date', function(newValue, oldValue) {
        var start = $scope.date.startDate = moment(newValue.startDate);
        var end = $scope.date.endDate = moment(newValue.endDate);
        if (end.isBefore(start)) {
            $scope.date.endDate = start.clone();
        }
        if (start.isAfter(end)) {
            var oldStart = moment(oldValue.startDate);
            var oldEnd = moment(oldValue.endDate);

            var diff = oldEnd.diff(oldStart, 'seconds');
            $scope.date.endDate = start.clone();
            $scope.date.endDate.add(diff, 'seconds');
        }
        $scope.$parent.meeting.date_start = $scope.date.startDate.format();
        $scope.$parent.meeting.date_end = $scope.date.endDate.format();
    }, true);


    // previous meeting linking
    $scope.prevMeeting = meeting.prev;
    $scope.nextMeeting = meeting.next;
    $scope.prevMeetings = [];

    /**
     * @ngdoc method
     * @name $scope_editPrevMeeting
     * @methodOf ommaApp.meetingGeneral:meetingGeneralController
     * @description
     * Show the edit dialog for the previous meeting
     */
    $scope.editPrevMeeting = function() {
        $scope.editingPrevMeeting = true;
        if (0 !== $scope.prevMeetings.length) {
            return;
        }
        meetingService.search().then(function(meetings) {
            $scope.prevMeetings = meetings;
        });
    };

    /**
     * @ngdoc method
     * @name $scope_cancelEditPrevMeeting
     * @methodOf ommaApp.meetingGeneral:meetingGeneralController
     * @description
     * Cancel editing the previous meeting
     */
    $scope.cancelEditPrevMeeting = function() {
        $scope.prevMeeting = null;
        $scope.editingPrevMeeting = false;
    };

    /**
     * @ngdoc method
     * @name $scope_linkMeeting
     * @methodOf ommaApp.meetingGeneral:meetingGeneralController
     * @description
     * Finish editing the previous meeting and persist it.
     */
    $scope.linkMeeting = function() {
        if (null === $scope.prevMeeting) {
            return;
        }
        $scope.editingPrevMeeting = false;
        meeting.prev = $scope.prevMeeting;
    };

    /**
     * @ngdoc method
     * @name $scope_copyAttendees
     * @methodOf ommaApp.meetingGeneral:meetingGeneralController
     * @description
     * Copy attendees from the previous meeting to the current.
     * Broadcasts an `attendee.copy` event to the {@link ommaApp.agenda:meetingAgendaController meetingAgendaController}
     */
    $scope.copyAttendees = function() {
        if (null === $scope.prevMeeting) {
            return;
        }
        /**
         * @ngdoc event
         * @name attendee_copy
         * @eventOf ommaApp.meetingGeneral:meetingGeneralController
         * @description
         * broadcast copy event to {@link ommaApp.agenda:meetingAgendaController meetingAgendaController}
         */
        $scope.$parent.$broadcast('attendee.copy', {meeting: $scope.prevMeeting});
    };

    /**
     * @ngdoc method
     * @name $scope_copyAgenda
     * @methodOf ommaApp.meetingGeneral:meetingGeneralController
     * @description
     * Copy the agenda from the previous meeting to the current.
     * Broadcasts an `agenda.copy` event to the {@link ommaApp.agenda:meetingAgendaController meetingAgendaController}
     */
    $scope.copyAgenda = function() {
        if (null === $scope.prevMeeting) {
            return;
        }
        /**
         * @ngdoc event
         * @name attendee_copy
         * @eventOf ommaApp.meetingGeneral:meetingGeneralController
         * @description
         * broadcast copy event to {@link ommaApp.agenda:meetingAgendaController meetingAgendaController}
         */
        $scope.$parent.$broadcast('agenda.copy', {meeting: $scope.prevMeeting});
    };
}]);
