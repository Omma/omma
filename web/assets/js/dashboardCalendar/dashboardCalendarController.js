/**
 * @ngdoc controller
 * @name ommaApp.dashboardCalendar:dashboarCalendarController
 * @requires $scope
 * @requires $element
 * @requires ommaApp.meeting:meetingService
 * @description
 * Controller for setting up the dashboard calendar
 *
 * Author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').controller('dashboarCalendarController', ['$scope', '$element', 'meetingService', function($scope, $element, meetingService) {

    /**
     * @ngdoc property
     * @name $scope_events
     * @propertyOf ommaApp.dashboardCalendar:dashboarCalendarController
     * @return {array} Array of displayed events in calendar
     */
    $scope.events = [];

    var eventsById = {};

    /**
     * @ngdoc method
     * @name $scope_eventClicked
     * @methodOf ommaApp.dashboardCalendar:dashboarCalendarController
     * @param {Object} event redirect to clicked event
     */
    $scope.eventClicked = function(event) {
        window.location.href = event.url;
    };

    /**
     * @ngdoc method
     * @name $scope_setCalendarToToday
     * @methodOf ommaApp.dashboardCalendar:dashboarCalendarController
     */
    $scope.setCalendarToToday = function() {
        $scope.calendarDay = new Date();
    };

    /**
     * @ngdoc property
     * @name $scope_calendarView
     * @propertyOf ommaApp.dashboardCalendar:dashboarCalendarController
     * @return {string} initial value of calendar scale
     */
    $scope.calendarView = 'month';

    /**
     * @ngdoc property
     * @name $scope_calendarDay
     * @propertyOf ommaApp.dashboardCalendar:dashboarCalendarController
     * @return {Date} current date for initializing the calendar
     */
    $scope.calendarDay = new Date();

    //watch the scale of calendar and selected day for changes
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
                if (undefined !== eventsById[meeting.identifier]) {
                    return;
                }
                var event = {
                    id: meeting.id,
                    title: meeting.name,
                    type: 'info',
                    url: meeting.url,
                    starts_at: moment(meeting.date_start).toDate(),
                    ends_at: moment(meeting.date_end).toDate(),
                    editable: false,
                    deletable: false
                };
                eventsById[meeting.identifier] = event;
                $scope.events.push(event);
            });
        });
    });



}]);
