/**
 * @ngdoc directive
 * @name ommaApp.directive:userSelect
 * @restrict E
 * @scope
 * @requres $http
 * @description
 * Creates a select2 select box for choosing a user.
 *
 * @param {string} selected data-binding for the currently selected user
 * @param {string=} [placeholder=Select User] placeholder string that is displayed, if no user is selected
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').directive('userSelect', ['$http', function ($http) {
    return {
        scope: {
            selected: '=',
            placeholder: '@'
        },
        restrict: 'E',
        templateUrl: 'assets/templates/user_select.html',
        controller: function($scope) {
            $scope.users = [];
            $scope.placeholder = $scope.placeholder || 'Select User';

            $scope.search = function(term) {
                $http.get('/users.json?search=' + term).success(function(users) {
                    $scope.users = users;
                });
            };
        }
    };
}]);
