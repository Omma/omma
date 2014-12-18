/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').controller('sidebarCalendarController', ['$scope', 'meetingService', function ($scope, meetingService) {
    var current = utils.getCurrentMonth('');
    $scope.currentEvents = [];

    function formatDate(element) {
        var date = moment(element.date_start);

        return {
            date:     date.toDate(),
            dateOrig: date,
            data:     element
        };
    }


    meetingService.getByDate(current.start, current.end).then(function(events) {
        events = _.map(events, formatDate);

        // Vorselektiertes Datum
        var date = {
            year:  new Date().getFullYear(),
            month: new Date().getMonth(),
            day:   new Date().getDate()
        };

        var monthNames;
        var dowNames;
        var dowOffset;

        if (window.language === 'de_DE') {
            monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
            dowNames = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];
            dowOffset = 1;
        }

        var $element = $('input#calendar-left-col');

        var ommaDatepicker = $element.glDatePicker({
            showAlways:   true,
            selectedDate: new Date(date.year, date.month, date.day),
            monthNames:   monthNames,
            dowNames:     dowNames,
            dowOffset:    dowOffset,
            specialDates: events,

            onClick: function (target, cell, date, data) {
                $scope.currentEvents = [];
                if (data === null) {
                    $scope.$apply();
                    return;
                }
                setCurrentEvents(moment(date));
                $scope.$apply();
            }
        }).glDatePicker(true);
        $.extend(ommaDatepicker.options, {
            nextPrevCallback: function () {
                var self = this;
                var date = moment(this.firstDate);

                meetingService.getByDate(moment(date).startOf('month'), moment(date).endOf('month')).then(function(events) {
                    events = _.map(events, formatDate);

                    self.specialDates = events;
                    ommaDatepicker.render();
                });
            }
        });

        function setCurrentEvents(currentDate) {
            $.each(ommaDatepicker.options.specialDates, function (index, value) {
                var date = value.dateOrig;

                if (date.isSame(currentDate, 'day')) {
                    $scope.currentEvents.splice(0, 0, value);
                }
            });
        }
        setCurrentEvents(moment());
    });
}]);
