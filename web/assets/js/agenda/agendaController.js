/**
 * Controller for displaying agenda tree
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingAgendaController', ['$scope', 'Restangular', 'agendaService', function($scope, Restangular, agendaService) {
    $scope.rootAgenda = {
        children: []
    };
    $scope.status = 'saved';
    $scope.meeting = null;

    $scope.$parent.meeting.then(function(meeting) {
        $scope.meeting = meeting;
        agendaService.getAll(meeting).then(function(agendas) {
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
        if (node.oldName === undefined) {
            // remove from parent if not saved before
            _.pull(node.parent.children, node);
        }
    };

    $scope.saveTree = function() {
        if (!$scope.meeting) {
            return;
        }
        $scope.status = 'saving';
        agendaService.saveTree($scope.meeting, $scope.rootAgenda).then(function() {
            $scope.status = 'saved';
        });
    };

    /**
     * Watch agenda for changes
     */
    function watchAgendas() {

        // max save every 2 seconds
        var save = _.debounce(function(newModel) {

            var save = true;
            angular.forEach(newModel, function(item) {
                // new not saved item
                if (item.name === undefined) {
                    save = false;
                }
            });
            if (!save) {
                $scope.status = 'saved';

                return false;
            }

            $scope.saveTree();

            return true;
        }, 2000);

        function watch() {
            return agendaService.filterNode($scope.rootAgenda);
        }
        var first = true;
        $scope.$watch(watch, function() {
            // first change is from angular ui tree
            if (first) {
                first = false;

                return false;
            }
            $scope.status = 'not_saved';
            save();
        }, true);
    }
}]);
