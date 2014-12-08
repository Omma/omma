// Declare app level module which depends on views, and components
var app = angular.module('ommaApp', [
    'restangular',
    'angular-loading-bar',
    'ui.tree'
]);

app.config(['RestangularProvider', function(RestangularProvider) {
    RestangularProvider.setRequestSuffix('.json');
}]);



app.controller('meetingController', ['$scope', 'Restangular', function($scope, Restangular) {
    $scope.init = function(id) {
        $scope.meeting = Restangular.one('meetings', id).get().then(function(meeting) {
            return meeting;
        });
    };
}]);

app.directive('autosave', ['agendaManager', function(agendaManager) {

    function autosaveController($scope, $emelent, $attrs) {

    }

    return {
        restrict: 'A',
        link: autosaveController
    };
}]);

