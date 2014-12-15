/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingRecurringController', ['$scope', function($scope) {
    $scope.recurrings = [];
    var defaults = {
        every: 1,
        month_type: 'relative',
        month_weekdays: {},
        rel_month: 'first',
        rel_month_day: moment().format('E'),
        abs_month_day: parseInt(moment().format('D')),
        end_type: 'absolute',
        end_date: undefined,
        end_after: undefined
    };

    // get weekday names in user locale
    $scope.weekdays = [];
    var date = moment().startOf('week');
    for (var i = 0; i < 7; i++) {
        $scope.weekdays.push({
            value: date.format('E'),
            name: date.format('dddd')
        });
        date.add(1, 'day');
    }

    $scope.add = function() {
        var recurring = {
            type: 'day',
            config: _.assign({}, defaults)
        };
        var currentWeekday = moment().format('E');
        angular.forEach($scope.weekdays, function(weekday) {
            defaults.month_weekdays[weekday.value] = weekday.value === currentWeekday;
        });
        $scope.recurrings.unshift(recurring);
        $scope.$watch(function() {return recurring;}, function() {

        }, true);
    };

    $scope.remove = function(recurring) {
        _.pull($scope.recurrings, recurring);
    };
}]);
