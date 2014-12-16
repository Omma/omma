/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('toDoController', ['$scope', 'toDoService', function ($scope, toDoService) {

        toDoService.load($scope.$parent.meeting).then(function(todos) {
            $scope.todos = todos;
            angular.forEach($scope.todos, function(todo) {
                $scope.$watch(function() {
                    return todo;
                }, _.debounce(function() {
                    save(todo);
                }, 800), true);
            });
        });

        $scope.status = 'saved';


        //Add new todo
        $scope.addNewTodo = function() {

            $scope.status = 'saved';

            var newTodo = {
                task:'[Bezeichnung des Todo-Punktes hier eingeben]',
                description:'[Hier ist Platz für die Beschreibung des Todo-Punktes...]',
                date: moment().format(),
                type: 1,
                priority: 0,
                status: 0,
                user:{id:null}
            };

            toDoService.add($scope.$parent.meeting, newTodo).then(function(todo) {
                $scope.todos.push(todo);
                $scope.status = 'saved';
            });
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
            toDoService.delete($scope.$parent.meeting, tempTodoToDelete).then(function(todo) {
                toDoService.load($scope.$parent.meeting).then(function(todo) {
                    $scope.todos = todo;
                    $scope.status = 'saved';
                });
            });
        };

        //Render Output
        $scope.todoIsDone = function(todo) {
            if(todo.status) {
                return true;
            }
            else {
                return false;
            }
        };
        $scope.todoIsImportant = function(todo) {
            if(todo.priority) {
                return true;
            }
            else {
                return false;
            }
        };
}]);
