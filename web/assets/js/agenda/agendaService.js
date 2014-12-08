angular.module('ommaApp').factory('agendaService', ['Restangular', '$http', function(Restangular, $http) {
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
            return $http.get('/meetings/' + meeting.id + '/agendas.json').then(function(data) {
                return data.data;
            });
        },
        saveTree: function(meeting, tree) {
            $http.put('/meetings/' + meeting.id + '/agendas/tree', tree).success(function(data) {
                console.log(data);
            });
        }
    };
}]);
