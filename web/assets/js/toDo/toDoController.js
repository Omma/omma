/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('toDoController', ['$scope', 'toDoService', function ($scope, toDoService) {

        toDoService.load($scope.$parent.meeting).then(function(todos) {
            $scope.todos = todos;
            angular.forEach($scope.todos, function(todo) {
                watchTodo(todo);
            });
        });

        function watchTodo(todo) {

            $scope.$watch(
                function() {
                    // don't watch id

                    return _.omit(todo, ['id']);
                },
                _.after(
                    3,
                    _.debounce(
                        function() {
                            save(todo);
                        },
                        800
                    )
                ),
                true
            );

        }

        $scope.status = 'saved';


        //Add new todo
        $scope.addNewTodo = function() {

            $scope.status = 'saved';

            var todo = {
                task: undefined,
                description: undefined,
                date: moment().format(),
                type: 1,
                priority: 0,
                status: 'open',
                user:undefined
            };

            $scope.todos.push(todo);
            watchTodo(todo);
        };

        function save(todo) {
            $scope.status = 'saving';
            toDoService.save($scope.$parent.meeting, todo).then(function() {
                $scope.status = 'saved';
            });

        }


        //Delete Modal
        var tempTodoToDelete;
        $scope.setTempTodoToDelete = function(todo) {
            tempTodoToDelete = todo;
        };
        $scope.deleteModal = function () {
            $scope.status = 'saving';
            _.pull($scope.todos, tempTodoToDelete);
            toDoService.remove($scope.$parent.meeting, tempTodoToDelete).then(function() {
                $scope.status = 'saved';
            });
        };
}]);
