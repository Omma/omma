/**
 * @ngdoc service
 * @name ommaApp.toDo:toDoService
 * @requires $http
 * @description
 * Service for persisting toDos into database
 *
 * Author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp').factory('toDoService', ['$http', function($http) {

    return {
        /**
         * @ngdoc method
         * @name _prepareTodo
         * @methodOf ommaApp.toDo:toDoService
         * @description change todo format from JavaScript format into database format
         *
         * @param {Object} todo todo in JavaScript format
         * @returns {Object} new todo in database format
         */
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
        /**
         * @ngdoc method
         * @name _getUrl
         * @methodOf ommaApp.toDo:toDoService
         * @description prepare url for getting global todos or todos relating to a specific meeting
         *
         * @param {Object} meeting id of meeting needed
         * @param {Object} todo check if meeting is set and get id of todo
         * @returns {Object} url to get right json
         */
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

        /**
         * @ngdoc method
         * @name load
         * @methodOf ommaApp.toDo:toDoService
         * @description fetch todos of a meeting
         *
         * @param {Object} meeting get todos of a meeting
         * @returns {Object} todos of meeting
         */
        //e.g. http://localhost/meetings/2/tasks.json
        load: function(meeting) {
            return $http.get(this._getUrl(meeting)).then(function(data) {
                return data.data;
            });
        },

        /**
         * @ngdoc method
         * @name add
         * @methodOf ommaApp.toDo:toDoService
         * @description add todo to a meeting
         *
         * @param {Object} meeting related meeting is needed
         * @param {Object} todo object to add
         * @returns {HttpPromise} Future todo-Objects
         */
        add: function(meeting, todo) {
            return $http.post(this._getUrl(meeting, todo), this._prepareTodo(todo)).then(function(data) {
                return data.data;
            });
        },

        /**
         * @ngdoc method
         * @name save
         * @methodOf ommaApp.toDo:toDoService
         * @description update todo in database
         *
         * @param {Object} meeting get todos of a meeting
         * @param {Object} todo object of todo to persist
         * @returns {HttpPromise} Future todo-Objects
         */
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

        /**
         * @ngdoc method
         * @name remove
         * @methodOf ommaApp.toDo:toDoService
         * @description remove todo of meeting in database
         *
         * @param {Object} meeting related meeting id needed
         * @param {Object} todo todos of a meeting
         * @returns {HttpPromise} Future todo-Objects
         */
        remove: function(meeting, todo) {
            return $http.delete(this._getUrl(meeting, todo));
        }
    };

}]);