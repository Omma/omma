'use strict';

// Declare app level module which depends on views, and components
var app = angular.module('ommaApp', [
    'restangular',
    'angular-loading-bar',
    'ui.tree'
]);

app.config(function(RestangularProvider) {
    RestangularProvider.setRequestSuffix('.json');
});

app.factory('agendaManager', function(Restangular) {
    return {
        /**
         * restangularize sub_items
         * @param agenda
         * @param parent
         */
        restangularize: function(agenda, parent) {
            var self = this;
            Restangular.restangularizeCollection(parent, agenda.sub_items, "agendas");
            angular.forEach(agenda.sub_items, function(subItem) {
                self.restangularize(subItem, parent);
            });
        },
        getAll: function(meeting) {
            var self = this;
            return meeting.all('agendas').getList().then(function(agendas) {
                angular.forEach(agendas, function(agenda) {
                    self.restangularize(agenda, agenda.parentResource);
                });

                return agendas;
            });
        }
    };
});

app.controller('meetingController', function($scope, Restangular) {
    $scope.init = function(id) {
        $scope.meeting = Restangular.one('meetings', id).get().then(function(meeting) {
            return meeting;
        });
    };
});

app.directive('autosave',[ function () {
    'use strict';

    function autosaveController($scope, $emelent, $attrs) {

        function saveModel(newModel, oldModel) {
            console.log("save", newModel);
        }

        console.log($attrs.ngModel);
        $scope.$watch($attrs.ngModel, _.debounce(saveModel, 1000), true);
    }

    return {
        restrict: 'A',
        link: autosaveController
    }
}]);

app.controller('meetingAgendaController', function($scope, Restangular, agendaManager) {
    $scope.agendas = [];
    $scope.$parent.meeting.then(function(meeting) {
        agendaManager.getAll(meeting).then(function(agendas) {
            console.log(agendas);
            $scope.agendas = agendas;
        });
    });
});
