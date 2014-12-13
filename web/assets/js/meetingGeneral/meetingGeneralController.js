/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingGeneralController', ['$scope', 'meetingService', function($scope, meetingService) {
    $scope.date = {
        startDate: undefined,
        endDate: undefined
    };

    var meeting = $scope.$parent.meeting;


    $scope.date = {
        startDate: moment(meeting.date_start),
        endDate: moment(meeting.date_end)
    };

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

    function formatMeetingDates(meeting) {
        if (undefined === meeting) {
            return meeting;
        }
        meeting.start = moment(meeting.date_start).format('DD. MMMM. YY HH:mm');
        meeting.end = moment(meeting.date_end).format('DD. MMMM. YY HH:mm');

        return meeting;
    }

    // previous meeting linking
    $scope.prevMeeting = formatMeetingDates(meeting.prev);
    $scope.prevMeetings = [];

    $scope.editPrevMeeting = function() {
        $scope.editingPrevMeeting = true;
        if (0 !== $scope.prevMeetings.length) {
            return;
        }
        meetingService.search().then(function(meetings) {
            _.map(meetings, formatMeetingDates);

            $scope.prevMeetings = meetings;
        });
    };

    $scope.cancelEditPrevMeeting = function() {
        $scope.prevMeeting = null;
        $scope.editingPrevMeeting = false;
    };

    $scope.linkMeeting = function() {
        if (null === $scope.prevMeeting) {
            return;
        }
        $scope.editingPrevMeeting = false;
        meeting.prev = $scope.prevMeeting;
    };
}]);
