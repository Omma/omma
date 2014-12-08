angular.module('ommaApp').controller('sidebarCalendarController', ['$scope', 'meetingService', function ($scope, meetingService) {
    var current = utils.getCurrentMonth('');
    $scope.currentEvents = [];

    meetingService.getByDate(current.start, current.end).then(function(events) {
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
                var clickedDate = moment(date);
                $.each(events, function (index, value) {
                    var date = value.dateOrig;

                    if (date.isSame(clickedDate, 'day')) {
                        $scope.currentEvents.splice(0, 0, value);
                    }
                });
                $scope.$apply();
            }
        }).glDatePicker(true);
        $.extend(ommaDatepicker.options, {
            nextPrevCallback: function () {
                var self = this;
                var date = moment(this.firstDate);

                meetingService.getByDate(date.startOf('month'), date.endOf('month')).then(function(events) {
                    self.specialDates = events;
                    ommaDatepicker.render();
                });
            }
        });
    });
}]);
