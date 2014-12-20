/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').factory('toDoService', ['$http', function($http) {

    return {
        _prepareTodo: function(todo) {
            var newTodo = _.clone(todo);

            if(newTodo.user !== undefined) {
                newTodo.user = todo.user.id;
            }
            if(newTodo.meeting !== undefined) {
                delete newTodo.meeting;
            }


            return newTodo;
        },
        _getUrl: function(meeting, todo) {
            var url = '/tasks';
            if (undefined !== meeting) {
                url = '/meetings/' + meeting.id + '/tasks';
            }
            if (undefined !== todo && undefined !== todo.id) {
                url += '/' + todo.id;
            }

            url += '.json';

            return url;
        },

        //e.g. http://localhost/meetings/2/tasks.json
        load: function(meeting) {
            return $http.get(this._getUrl(meeting)).then(function(data) {
                return data.data;
            });
        },

        add: function(meeting, todo) {
            return $http.post(this._getUrl(meeting, todo), this._prepareTodo(todo)).then(function(data) {
                return data.data;
            });
        },

        save: function(meeting, todo) {
            if (undefined === todo.task) {
                return;
            }

            if (undefined !== todo.id) {
                return $http.put(this._getUrl(meeting, todo), this._prepareTodo(todo));
            } else {
                return $http.post(this._getUrl(meeting, todo), this._prepareTodo(todo)).then(function(data) {
                   todo.id = data.data.id;
                });
            }
        },

        remove: function(meeting, todo) {
            return $http.delete(this._getUrl(meeting, todo));
        }
    };

}]);
