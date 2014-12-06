'use strict';

// Declare app level module which depends on views, and components
var app = angular.module('ommaApp', [
    'restangular',
    'angular-loading-bar',
    'ui.tree'
]);

app.config(function(RestangularProvider) {
    RestangularProvider.setRequestSuffix('.json');
});

app.factory('agendaManager', function(Restangular) {
    return {
        /**
         * restangularize sub_items
         * @param agenda
         * @param parent
         */
        restangularize: function(agenda, parent) {
            var self = this;
            Restangular.restangularizeCollection(parent, agenda.sub_items, "agendas");
            angular.forEach(agenda.sub_items, function(subItem) {
                self.restangularize(subItem, parent);
            });
        },
        getAll: function(meeting) {
            var self = this;
            return meeting.all('agendas').getList().then(function(agendas) {
                angular.forEach(agendas, function(agenda) {
                    self.restangularize(agenda, agenda.parentResource);
                });

                return agendas;
            });
        }
    };
});

app.controller('meetingController', function($scope, Restangular) {
    $scope.init = function(id) {
        $scope.meeting = Restangular.one('meetings', id).get().then(function(meeting) {
            return meeting;
        });
    };
});

app.directive('autosave',[ function () {
    'use strict';

    function autosaveController($scope, $emelent, $attrs) {

        function saveModel(newModel, oldModel) {
            console.log("save", newModel);
        }

        console.log($attrs.ngModel);
        $scope.$watch($attrs.ngModel, _.debounce(saveModel, 1000), true);
    }

    return {
        restrict: 'A',
        link: autosaveController
    }
}]);

app.controller('meetingAgendaController', function($scope, Restangular, agendaManager) {
    $scope.agendas = [];
    $scope.$parent.meeting.then(function(meeting) {
        agendaManager.getAll(meeting).then(function(agendas) {
            console.log(agendas);
            $scope.agendas = agendas;
        });
    });
});

app.factory('meetingManager', function($http) {
    return {
        /**
         * Get Meetings for date range
         * @param {Date|moment} start date
         * @param {Date|moment} end date
         * @returns {*}
         */
        getByDate: function(start, end) {
            if (!moment.isMoment(start)) {
                start = moment(start);
            }
            if (!moment.isMoment(end)) {
                end = moment(end);
            }
            return $http.get("/temp_jsons/calendar-left-col.json?start=" + start.format() + "&end=" + end.format())
                .then(function (data) {
                    return _.map(data.data, format_incoming_json_date);
                })
            ;
        }
    };
});

app.controller('sidebarCalendarController', function ($scope, meetingManager) {
    var current = get_current_month('');
    $scope.currentEvents = [];

    meetingManager.getByDate(current.start, current.end).then(function(events) {
        // Vorselektiertes Datum
        var date = {
            year:  new Date().getFullYear(),
            month: new Date().getMonth(),
            day:   new Date().getDate()
        };

        var monthNames;
        var dowNames;
        var dowOffset;

        if (window.language == "de_DE") {
            monthNames = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];
            dowNames = ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"];
            dowOffset = 1;
        }

        var $element = $('input#calendar-left-col');

        var omma_datepicker = $element.glDatePicker({
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
        $.extend(omma_datepicker.options, {
            nextPrevCallback: function () {
                var self = this;
                var date = moment(this.firstDate);

                meetingManager.getByDate(date.startOf('month'), date.endOf('month')).then(function(events) {
                    self.specialDates = events;
                    omma_datepicker.render();
                });
            }
        });
    });
});
