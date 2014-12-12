/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').directive('userSelect', ['$http', function ($http) {
    return {
        scope: {
            selected: '='
        },
        restrict: 'E',
        templateUrl: 'user_select.html',
        controller: function($scope) {
            $scope.users = [];

            $scope.search = function(term) {
                $http.get('/users.json?search=' + term).success(function(users) {
                    $scope.users = users;
                });
            };
        }
    };
}]);
