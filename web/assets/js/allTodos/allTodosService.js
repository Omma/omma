/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').factory('allTodosService', ['$http', function($http) {

    return {
        _prepareTodo: function(todo) {
            var newTodo = _.clone(todo);
            newTodo.user = todo.user.id;

            return newTodo;
        },

        //e.g. http://localhost/meetings/2/tasks.json
        load: function(meeting) {
            return $http.get('/meetings/' + meeting.id + '/tasks.json').then(function(data) {
                return data.data;
            });
        },

        add: function(meeting, todo) {
            return $http.post('/meetings/' + meeting.id + '/tasks.json', this._prepareTodo(todo)).then(function(data) {
                return data.data;
            });
        },

        save: function(meeting, todo) {
            return $http.put('/meetings/' + meeting.id + '/tasks/' + todo.id + '.json', this._prepareTodo(todo));
        },

        delete: function(meeting, todo) {
            return $http.delete('/meetings/' + meeting.id + '/tasks/' + todo.id + '.json');
        }
    };

}]);
