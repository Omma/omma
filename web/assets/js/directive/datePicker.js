/**
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

            $scope.$watch('date', function(newDate) {
                $element.data('daterangepicker').setStartDate(newDate);
                $element.data('daterangepicker').setEndDate(newDate);
            });

            $element.on('apply.daterangepicker', function(ev, picker) {
                if (undefined === $scope.date || !moment.isMoment($scope.date)) {
                    $scope.date = moment($scope.date);
                }
                $scope.date.set('date', picker.startDate.get('date'));
                $scope.date.set('month', picker.startDate.get('month'));
                $scope.date.set('year', picker.startDate.get('year'));
                $scope.$apply();
            });
        }
    };
}]);
