/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingRecurringController', ['$scope', 'meetingRecurringService', function($scope, meetingRecurringService) {
    $scope.recurring = _.cloneDeep($scope.$parent.meeting.meeting_recurring || {});
    $scope.recurring = _.assign({
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
    }, $scope.recurring);
    console.log($scope.recurring.config);

    function getValue() {
        var recurring = $scope.recurring;
        console.log(recurring);
        if (recurring.type === 'none') {
            return {};
        }

        return recurring;
    }

    $scope.$watch('recurring', function() {
        $scope.$parent.meeting.meeting_recurring = getValue();
    }, true);

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
