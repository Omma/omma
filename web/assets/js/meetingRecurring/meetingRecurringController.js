/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingRecurringController', ['$scope', 'meetingRecurringService', function($scope, meetingRecurringService) {
    $scope.recurring = {};
    meetingRecurringService.load($scope.$parent.meeting).then(function(recurring) {
        _.assign(recurring, {
            type: 'none',
            config: {
                every: 1,
                month_type: 'relative',
                month_weekdays: {},
                rel_month: 'first',
                rel_month_day: moment().format('E'),
                abs_month_day: parseInt(moment().format('D')),
                end_type: 'absolute',
                end_date: undefined,
                end_after: undefined
            }
        });
        $scope.recurring = recurring;
        $scope.config = recurring.config;
    });

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
}]);
