/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').factory('agendaService', ['Restangular', '$http', function(Restangular, $http) {
    Restangular.extendModel('agendas', function(model) {
        model.editing = false;
        console.log(model.name);

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
            return $http.get('/meetings/' + meeting.id + '/agendas.json').then(function(data) {
                return data.data;
            });
        },
        _setSortingOrder: function(tree) {
            var self = this;
            var order = 1;
            angular.forEach(tree, function(agenda) {
                agenda.sorting_order = order;
                order++;
                self._setSortingOrder(agenda.children);
            });
        },
        /**
         * Save whole agenda tree
         * @param meeting Meeting object
         * @param rootNode
         */
        saveTree: function(meeting, rootNode) {
            var root = this.filterNode(rootNode);
            root.sorting_order = 1;
            this._setSortingOrder(root.children);
            return $http.put('/meetings/' + meeting.id + '/agendas.json', root).then(function(data) {
                console.log(data);
                return data.data;
            });
        },
        /**
         * Remove properites from node that are not used for comparisson and transmission
         * @param node
         * @returns {*}
         */
        filterNode: function(node) {
            var newNode = _.omit(node, ['parent', 'editing', 'oldName']);
            if (newNode.children) {
                newNode.children = _.filter(node.children, function(child) {
                    return !child.editing;
                }).map(_.bind(this.filterNode, this));
            }
            return newNode;
        }
    };
}]);
