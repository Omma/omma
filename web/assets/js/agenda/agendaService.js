/**
 * @ngdoc service
 * @name ommaApp.agenda:agendaService
 * @requires Restangular
 * @requires $http
 *
 * @description
 * Service for loading and saving the agenda
 *
 * Author: Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').factory('agendaService', ['Restangular', '$http', function(Restangular, $http) {
    Restangular.extendModel('agendas', function(model) {
        model.editing = false;

        return model;
    });
    return {
        /**
         * @ngdoc method
         * @name create
         * @methodOf ommaApp.agenda:agendaService
         * @description create new angeda element and restangularize it
         *
         * @param {Object} parent Parent Restangular resource (Agenda collection)
         * @returns {Object} a new agenda object
         */
        create: function(parent) {
            var element = {};

            return Restangular.restangularizeElement(parent.parentResource, element, 'agendas');
        },
        /**
         * @ngdoc method
         * @name restangularize
         * @methodOf ommaApp.agenda:agendaService
         * @description restangularize agenda children.
         *
         * @param {Object} agenda object
         * @param {Object} parent Retangular parent resource (Agenda collection)
         */
        restangularize: function(agenda, parent) {
            var self = this;
            Restangular.restangularizeCollection(parent, agenda.children, 'agendas');
            angular.forEach(agenda.children, function(subItem) {
                subItem.parent = agenda;
                self.restangularize(subItem, parent);
            });

        },

        /**
         * @ngdoc method
         * @name getAll
         * @methodOf ommaApp.agenda:agendaService
         * @description
         * get all agenda items for a meeting
         *
         * @param {Object} meeting meeting
         * @returns {HttpPromise} promise which will pass agendas from server
         */
        getAll: function(meeting) {
            return $http.get('/meetings/' + meeting.id + '/agendas.json').then(function(data) {
                return data.data;
            });
        },

        /**
         * @ngdoc method
         * @name _setSortingOrder
         * @methodOf ommaApp.agenda:agendaService
         * @description
         * update sorting_order field of all agenda items
         *
         * @param {Array} tree agenda tree nodes
         * @private
         */
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
         * @ngdoc method
         * @name saveTree
         * @methodOf ommaApp.agenda:agendaService
         * @description
         * Save whole agenda tree
         *
         * @param {Object} meeting Meeting object
         * @param {Object} rootNode agenda root node
         * @return {HttpPromise} promise from http request
         */
        saveTree: function(meeting, rootNode) {
            var root = this.filterNode(rootNode);
            root.sorting_order = 1;
            this._setSortingOrder(root.children);
            return $http.put('/meetings/' + meeting.id + '/agendas.json', root).then(function(data) {
                return data.data;
            });
        },

        /**
         * @ngdoc method
         * @name filterNode
         * @methodOf ommaApp.agenda:agendaService
         * @description
         * Remove properites from node that are not used for comparisson and transmission
         *
         * @param {Object} node agenda object
         * @returns {Object} node clean angenda object
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
