angular.module('ommaApp').controller('meetingAgendaController', ['$scope', 'Restangular', 'agendaService', function($scope, Restangular, agendaService) {
    $scope.agendas = [];

    $scope.$parent.meeting.then(function(meeting) {
        watchAgendas(meeting);
        agendaService.getAll(meeting).then(function(agendas) {
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

            agendaService.saveTree(meeting, newModel);
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
