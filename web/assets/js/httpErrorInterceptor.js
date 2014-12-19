/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').config(['$httpProvider', function($httpProvider) {
    // intercept queries for global error handling
    $httpProvider.interceptors.push(function($q, $rootScope) {
        return {
            responseError: function(rejection) {
                $rootScope.$broadcast('error.http', rejection);

                return $q.reject(rejection);
            }
        };
    });
}]);

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
