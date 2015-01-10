/**
 * @ngdoc function
 * @name ommaApp.config:$httpProvider
 * @requires $httpPrrovider
 * @description
 * intercept http queries  for global error handling.
 * Error handling is done by {@link ommaApp.controller:errorController errorController}.
 * Will broadcast a `error.http` event on the `$rootScope`.
 *
 * Author: Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').config(['$httpProvider', function($httpProvider) {
    //
    /**
     * @ngdoc event
     * @name error.http
     * @description
     * fired, when a http error occurs
     *
     * @eventOf ommaApp.config:$httpProvider
     */
    $httpProvider.interceptors.push(function($q, $rootScope) {
        return {
            responseError: function(rejection) {
                $rootScope.$broadcast('error.http', rejection);

                return $q.reject(rejection);
            }
        };
    });
}]);

/**
 * @ngdoc controller
 * @name ommaApp.controller:errorController
 * @requires $scope
 * @description
 * Displays http errors to the user
 *
 * Author: Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('errorController', ['$scope', function($scope) {
    $scope.show = false;
    $scope.$on('error.http', function(event, error) {
        $scope.message = error.config.method + ' ' + error.config.url + '<br />';
        if (_.isObject(error.data) && undefined !== error.data.message) {
            $scope.message += error.data.message + ' (' + error.data.code + ')';
        } else {
            $scope.message += error.statusText + ' (' + error.status + ')';
        }
        $scope.show = true;
    });
    $scope.reload = function() {
        window.location.reload();
    };
}]);
