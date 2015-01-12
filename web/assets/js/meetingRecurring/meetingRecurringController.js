/**
 * @ngdoc controller
 * @name ommaApp.meetingRecurring:meetingRecurringController
 * @requires $scope
 * @description
 * Controller for handling the recurring meeting settings in the meeting 'general' tab
 *
 * Author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingRecurringController', ['$scope', function($scope) {
    /**
     * @ngdoc property
     * @name $scope_recurring
     * @propertyOf ommaApp.meetingRecurring:meetingRecurringController
     * @returns {Object} Copy of the meeting_recurring data from the meeting.
     *                   We populate the object with default values and only save the needed values for the selected type.
     */
    $scope.recurring = _.cloneDeep($scope.$parent.meeting.meeting_recurring || {});
    // set default values
    $scope.recurring = _.assign({
        type: 'none',
        date_start: moment($scope.$parent.meeting.date_start)
    }, $scope.recurring);

    // set default config values
    $scope.recurring.config = _.assign({
        every: 1,
        week_weekdays: {},
        month_type: 'relative',
        rel_month: 'first',
        rel_month_day: moment().format('E'),
        abs_month_day: parseInt(moment().format('D'))
    }, $scope.recurring.config);
    console.log($scope.recurring);

    /**
     * @ngdoc method
     * @name getValue
     * @methodOf ommaApp.meetingRecurring:meetingRecurringController
     * @description
     * Remove irrelvant properties, which are not needed for the selected type
     *
     * @returns {Object} clean recurring object
     */
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

    // watch for changes an save only relevant properties
    $scope.$watch('recurring', function() {
        $scope.$parent.meeting.meeting_recurring = getValue();
    }, true);

    /**
     * @ngdoc property
     * @name weekdays
     * @propertyOf ommaApp.meetingRecurring:meetingRecurringController
     * @description weekdays in user locale
     * @returns {Array} array with weekday object (`{value: (int) Weekdaynumber, name: (string) Name in user locale}`)
     */
    $scope.weekdays = [];
    var date = moment().startOf('week');
    for (var i = 0; i < 7; i++) {
        $scope.weekdays.push({
            value: parseInt(date.format('E')),
            name: date.format('dddd')
        });
        date.add(1, 'day');
    }
}]);
