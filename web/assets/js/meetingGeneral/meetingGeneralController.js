/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingGeneralController', ['$scope', function($scope) {
    $scope.date = {
        startDate: undefined,
        endDate: undefined
    };

    $scope.$parent.meetingRequest.then(function(meeting) {
        $scope.date = {
            startDate: moment(meeting.date_start),
            endDate: moment(meeting.date_end)
        };
    });

    $scope.$watch('date', function(newValue, oldValue) {
        var start = $scope.date.startDate = moment(newValue.startDate);
        var end = $scope.date.endDate = moment(newValue.endDate);
        if (end.isBefore(start)) {
            $scope.date.endDate = start.clone();
        }
        if (start.isAfter(end)) {
            var oldStart = moment(oldValue.startDate);
            var oldEnd = moment(oldValue.endDate);

            var diff = oldEnd.diff(oldStart, 'seconds');
            $scope.date.endDate = start.clone();
            $scope.date.endDate.add(diff, 'seconds');
        }
        $scope.$parent.meeting.date_start = $scope.date.startDate.format();
        $scope.$parent.meeting.date_end = $scope.date.endDate.format();
    }, true);

}]);
