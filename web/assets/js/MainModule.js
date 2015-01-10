// Declare app level module which depends on views, and components
/**
 * @ngdoc overview
 * @name ommaApp
 */
var app = angular.module('ommaApp', [
    'restangular',
    'angular-loading-bar',
    'ui.tree',
    'textAngular',
    'xeditable',
    'ui.bootstrap',
    'mwl.calendar',
    'ngSanitize',
    'ui.select',
    'daterangepicker'
]);

app.run(['editableOptions', function(editableOptions) {
    editableOptions.theme = 'bs3';
}]);

app.config(['RestangularProvider', function(RestangularProvider) {
    // append .json to all Restangular requests
    RestangularProvider.setRequestSuffix('.json');
}]);

app.value('dateRangePickerConfig', {
    separator: ' - ',
    format: 'DD. MMMM YYYY HH:mm'
});
