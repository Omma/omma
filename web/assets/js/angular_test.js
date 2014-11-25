'use strict';

// Declare app level module which depends on views, and components
var app = angular.module('ommaApp', [
    'restangular'
]);

app.controller('testCtrl', function ($scope, Restangular) {
    Restangular.one('meetings', 2).get().then(function(meeting) {
        $scope.meeting = meeting;

        return meeting;
    }).then(function(meeting) {
        meeting.all('agendas').getList().then(function(agendas) {
            console.log(agendas)
        });
    });

    $scope.save = function() {
        $scope.meeting.put();
    };
});

