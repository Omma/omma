/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').controller('dashboarCalendarController', ['$scope', '$element', 'meetingService', function($scope, $element, meetingService) {
    $scope.events = [];
    var eventsById = {};

    $scope.eventClicked = function(event) {
        window.location.href = '/meetings/' + event.id + '/details';
    };

    $scope.setCalendarToToday = function() {
        $scope.calendarDay = new Date();
    };

    $scope.$watchGroup(['calendarView', 'calendarDay'], function() {
        var date = moment($scope.calendarDay);
        var view = $scope.calendarView;
        if ('week' === view) {
            view = 'isoWeek';
        }
        var start = moment(date).startOf(view);
        var end = moment(date).endOf(view);
        meetingService.getByDate(start, end).then(function(meetings) {
            angular.forEach(meetings, function(meeting) {
                if (undefined !== eventsById[meeting.id]) {
                    return;
                }
                var event = {
                    id: meeting.id,
                    title: meeting.name,
                    type: 'info',
                    starts_at: moment(meeting.date_start).toDate(),
                    ends_at: moment(meeting.date_end).toDate(),
                    editable: false,
                    deletable: false
                };

                eventsById[meeting.id] = event;
                $scope.events.push(event);
            });
        });
    });
    $scope.calendarView = 'month';
    $scope.calendarDay = new Date();
}]);
