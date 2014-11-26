'use strict';

// Declare app level module which depends on views, and components
var app = angular.module('ommaApp', [
    'restangular'
]);

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

app.controller('testCtrl', function ($scope, Restangular, agendaManager) {
    Restangular.one('meetings', 2).get().then(function(meeting) {
        $scope.meeting = meeting;

        return meeting;
    }).then(function(meeting) {
        agendaManager.getAll(meeting).then(function(agendas) {
            console.log(agendas);
        });
    });

    $scope.save = function() {
        $scope.meeting.put();
    };
});

