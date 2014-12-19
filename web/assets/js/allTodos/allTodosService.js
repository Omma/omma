/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').factory('allTodosService', ['$http', function ($http) {

    return {
        _prepareTodo: function (todo) {
            var newTodo = _.clone(todo);
            newTodo.user = todo.user.id;

            return newTodo;
        },

        load: function () {
            return $http.get('/tasks.json').then(function (data) {
                return data.data;
            });
        },

        save: function (todo) {
            return $http.put('/tasks/' + todo.id + '.json', this._prepareTodo(todo));
        },

        delete: function (todo) {
            return $http.delete('tasks/' + todo.id + '.json');
        }
    };

}]);
