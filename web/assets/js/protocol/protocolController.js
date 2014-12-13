/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp')
    .controller('protocolController', ['$scope', 'protocolService', function ($scope, protocolService) {
        $scope.status = 'saved';
        $scope.protocol = {};

        protocolService.load($scope.$parent.meeting).then(function(protocol) {
            $scope.protocol = protocol;
        });

        $scope.getButtonClass = function() {
            if($scope.protocol.final === false) {
                return '';
            } else {
                return 'disabled';
            }
        };

        $scope.deleteModal = function() {
            $scope.protocol.final = true;
        };

        var save = _.debounce(function() {
            $scope.status = 'saving';
            protocolService.save($scope.$parent.meeting, $scope.protocol).then(function() {
                $scope.status = 'saved';
            });

        }, 1000);

        $scope.$watch('protocol', _.after(3, function() {
            $scope.status = 'not_saved';
            save();
        }), true);
}]);
