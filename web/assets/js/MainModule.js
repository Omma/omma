// Declare app level module which depends on views, and components
var app = angular.module('ommaApp', [
    'restangular',
    'angular-loading-bar',
    'ui.tree',
    'textAngular'
]);

app.config(['RestangularProvider', function(RestangularProvider) {
    // append .json to all Restangular requests
    RestangularProvider.setRequestSuffix('.json');
}]);