// Declare app level module which depends on views, and components
var app = angular.module('ommaApp', [
    'restangular',
    'angular-loading-bar',
    'ui.tree'
]);

app.config(['RestangularProvider', function(RestangularProvider) {
    RestangularProvider.setRequestSuffix('.json');
}]);

app.factory('agendaManager', ['Restangular', '$http', function(Restangular, $http) {
    Restangular.extendModel('agendas', function(model) {
        model.editing = false;
        console.log(model.name);
        /*$rootScope.watch(function() {
         return model.name;
         }, function() {
         console.log('changed', model);
         });*/

        return model;
    });
    return {
        create: function(parent) {
            var element = {};

            return Restangular.restangularizeElement(parent.parentResource, element, 'agendas');
        },
        /**
         * restangularize children
         * @param agenda
         * @param parent
         */
        restangularize: function(agenda, parent) {
            var self = this;
            Restangular.restangularizeCollection(parent, agenda.children, 'agendas');
            angular.forEach(agenda.children, function(subItem) {
                subItem.parent = agenda;
                self.restangularize(subItem, parent);
            });

        },
        getAll: function(meeting) {
            var self = this;
            return $http.get('/meetings/' + meeting.id + '/agendas.json').then(function(data) {
                return data.data;
            });
        },
        saveTree: function(meeting, tree) {
            $http.put('/meetings/' + meeting.id + '/agendas/tree', tree).success(function(data) {
                console.log(data);
            })
        }
    };
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

app.controller('meetingAgendaController', ['$scope', 'Restangular', 'agendaManager', function($scope, Restangular, agendaManager) {
    $scope.agendas = [];

    $scope.$parent.meeting.then(function(meeting) {
        watchAgendas(meeting);
        agendaManager.getAll(meeting).then(function(agendas) {
            console.log(agendas);
            $scope.agendas = agendas;
        });
    });

    $scope.newNode = function(parent) {
        var node = {
            parent: parent,
            editing: true
        };
        if (undefined !== parent) {
            if (parent.children === undefined) {
                parent.children = [];
            }
            parent.children.push(node);

            return;
        }

        $scope.agendas.push(node);
    };

    $scope.edit = function(node) {
        node.editing = true;
        node.oldName = node.name;
    };

    $scope.save = function(node) {
        node.editing = false;
    };

    $scope.cancelEditing = function(node) {
        node.editing = false;
        node.name = node.oldName;
        // remove from parent if not saved before
        if (node.oldName === undefined) {
            var parent = node.parent;
            if (undefined !== parent) {
                _.pull(parent.children, node);
            } else {
                _.pull($scope.agendas, node);
            }
        }
    };

    function watchAgendas(meeting) {
        var first = true;
        function saveModel(newModel, oldModel) {
            // first change is from angular ui tree
            if (first) {
                first = false;
                return;
            }
            var save = true;
            angular.forEach(newModel, function(item) {
                // new not saved item
                if (item.name === undefined) {
                    save = false;
                }
            });

            agendaManager.saveTree(meeting, newModel);
        }

        function watch() {
            return $scope.agendas.map(nodeValue);
        }

        function nodeValue(node) {
            var newNode = _.omit(node, ['parent', 'editing', 'oldName']);
            if (newNode.children) {
                newNode.children = _.filter(node.children, function(child) {
                    return !child.editing;
                }).map(nodeValue);
            }
            return newNode;
        }

        $scope.$watch(watch, _.debounce(saveModel, 1000), true);
    }
}]);
