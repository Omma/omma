/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingRecurringController', ['$scope', function($scope) {
    $scope.recurring = _.cloneDeep($scope.$parent.meeting.meeting_recurring || {});
    $scope.recurring = _.assign({
        type: 'none',
        date_start: moment($scope.$parent.meeting.date_start)
    }, $scope.recurring);
    $scope.recurring.config = _.assign({
        every: 1,
        week_weekdays: {},
        month_type: 'relative',
        rel_month: 'first',
        rel_month_day: moment().format('E'),
        abs_month_day: parseInt(moment().format('D'))
    }, $scope.recurring.config);
    console.log($scope.recurring);

    function getValue() {
        var recurring = $scope.recurring;
        switch (recurring.type) {
            case 'none':
                return {};
            case 'day':
                recurring.config = _.pick(recurring.config, ['every']);
                break;
            case 'week':
                recurring.config = _.pick(recurring.config, ['every', 'week_weekdays']);
                break;
            case 'month':
                recurring.config = _.pick(recurring.config, ['every', 'month_type', 'rel_month', 'rel_month_day', 'abs_month_day']);
                break;
            case 'year':
                recurring.config = _.pick(recurring.config, ['every']);
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
