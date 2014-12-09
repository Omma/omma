/**
 * Controller for displaying agenda tree
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingAgendaController', ['$scope', 'Restangular', 'agendaService', function($scope, Restangular, agendaService) {
    $scope.rootAgenda = {
        children: []
    };
    $scope.saving = false;

    $scope.$parent.meeting.then(function(meeting) {
        agendaService.getAll(meeting).then(function(agendas) {
            console.log(agendas);
            $scope.rootAgenda = agendas;
            watchAgendas(meeting);
        });
    });

    $scope.newNode = function(parent) {
        var node = {
            parent: parent,
            editing: true
        };
        if (parent.children === undefined) {
            parent.children = [];
        }
        parent.children.push(node);
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
            _.pull(parent.children, node);
        }
    };

    $scope.saveTree = function() {
        $scope.saving = true;
        agendaService.saveTree(meeting, newModel).then(function() {
            $scope.saving = false;
        });
    };


    function watchAgendas(meeting) {
        var first = true;
        function saveModel(newModel) {
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

            $scope.saveTree();
        }

        function watch() {
            return nodeValue($scope.rootAgenda);
        }

        function nodeValue(node) {
            var newNode = _.omit(node, ['parent', 'editing', 'oldName', 'sorting_order']);
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
