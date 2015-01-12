/**
 * @ngdoc controller
 * @name ommaApp.agenda:meetingAgendaController
 * @requires $scope
 * @requires Restangular
 * @requires ommaApp.agenda:agendaService
 * @description
 * Controller for displaying agenda tree
 *
 * Author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingAgendaController', ['$scope', 'Restangular', 'agendaService', function($scope, Restangular, agendaService) {
    /**
     * @ngdoc property
     * @name $scope_rootAgenda
     * @propertyOf ommaApp.agenda:meetingAgendaController
     * @return {Object} root agenda node
     */
    $scope.rootAgenda = {
        children: []
    };
    /**
     * @ngdoc property
     * @name status
     * @propertyOf ommaApp.agenda:meetingAgendaController
     * @return {string} current status ('saved', 'saving', 'not_saved')
     */
    $scope.status = 'saved';

    // load agendas from backend
    agendaService.getAll($scope.$parent.meeting).then(function(agendas) {
        $scope.rootAgenda = agendas;
        watchAgendas();
    });

    /**
     * @ngdoc method
     * @name $scope_newNode
     * @methodOf ommaApp.agenda:meetingAgendaController
     * @param {Object} parent parent agendaNode
     */
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

    /**
     * @ngdoc method
     * @name $scope_edit
     * @methodOf ommaApp.agenda:meetingAgendaController
     * @param {Object} node node to be edited
     */
    $scope.edit = function(node) {
        node.editing = true;
        node.oldName = node.name;
    };

    /**
     * @ngdoc method
     * @name $scope_save
     * @methodOf ommaApp.agenda:meetingAgendaController
     * @description save currently edited node
     * @param {Object} node node
     */
    $scope.save = function(node) {
        node.editing = false;
    };

    /**
     * @ngdoc method
     * @name $scope_cancelEditing
     * @methodOf ommaApp.agenda:meetingAgendaController
     * @description cancel editing, will remove node if not saved before
     * @param {Object} node node
     */
    $scope.cancelEditing = function(node) {
        node.editing = false;
        node.name = node.oldName;
        if (node.oldName === undefined) {
            // remove from parent if not saved before
            _.pull(node.parent.children, node);
        }
    };

    /**
     * @ngdoc method
     * @name $scope_saveTree
     * @methodOf ommaApp.agenda:meetingAgendaController
     * @description save whole agenda tree to the backend
     */
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
     * @ngdoc event
     * @name agenda.copy
     * @eventOf ommaApp.agenda:meetingAgendaController
     */
    $scope.$on('agenda.copy', function(event, args){
        agendaService.getAll(args.meeting).then(function(root) {
            angular.forEach(root.children, function(agenda) {
                $scope.rootAgenda.children.push(agenda);
            });
        });
    });

    /**
     * @ngdoc method
     * @name watchAgendas
     * @methodOf ommaApp.agenda:meetingAgendaController
     * @description
     * Watch agenda items for changes
     */
    function watchAgendas() {

        // max save every 2 seconds
        var save = _.debounce(function(newModel) {

            var save = true;
            angular.forEach(newModel, function(item) {
                // skip new not saved item
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
