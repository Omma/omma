/**
 * @ngdoc directive
 * @name ommaApp.directive:datePicker
 * @restrict A
 * @element div
 * @scope
 * @description
 * This directive displays a datepicker for a single date.
 * The datepicker is based on the daterangepicker plugin, but configured to show a single datepicker.
 * Changes to the date will be applied to the datepicker and vise versa.
 *
 * @param {(string|Date|moment)} date date object for the datepicker
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').directive('datePicker', [function() {
    var format = 'DD. MMMM YYYY';

    return {
        scope: {
            date: '='
        },
        restrict: 'A',
        link: function($scope, element) {
            var $element = $(element);
            $element.daterangepicker({
                format: format,
                singleDatePicker: true
            });

            // allow emptying by user: listen for change of input element and set date to undefined
            $element.change(function() {
                if (0 === $element.val().length) {
                    $scope.date = undefined;
                    $scope.$apply();
                }
            });

            // watch for date changes and set it to
            $scope.$watch('date', function(newDate) {
                if (undefined === newDate) {
                    $element.val('');
                    return;
                }
                // convert to moment-js date
                if (!moment.isMoment(newDate)) {
                    $scope.date = newDate = moment(newDate);
                }
                // set date for datepicker
                $element.data('daterangepicker').setStartDate(newDate);
                $element.data('daterangepicker').setEndDate(newDate);
            });

            // sync changes from datepicker to moment
            $element.on('apply.daterangepicker', function(ev, picker) {
                // enforce moment
                if (undefined === $scope.date || !moment.isMoment($scope.date)) {
                    $scope.date = moment($scope.date);
                }
                // set date, month and year of moment from selected date
                $scope.date.set('date', picker.startDate.get('date'));
                $scope.date.set('month', picker.startDate.get('month'));
                $scope.date.set('year', picker.startDate.get('year'));
                $scope.$apply();
            });
        }
    };
}]);
