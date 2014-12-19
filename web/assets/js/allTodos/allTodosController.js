/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('allTodosController', ['$scope', 'allTodosService', function ($scope, allTodosService) {


         allTodosService.load().then(function (todos) {
            $scope.todos = todos;


             console.log($scope.todos);
            angular.forEach($scope.todos, function (todo) {
                $scope.$watch(function () {
                    return todo;
                }, _.debounce(function () {
                    save(todo);
                }, 800), true);
            });
        });

        $scope.status = 'saved';



        function save(todo) {
            $scope.status = 'saving';
            allTodosService.save(todo).then(function () {
                $scope.status = 'saved';
            });

        }


        //Delete Modal
        var tempTodoToDelete;
        $scope.setTempTodoToDelete = function (todo) {
            tempTodoToDelete = todo;
        };
        $scope.deleteModal = function () {
            $scope.status = 'saving';
            allTodosService.delete(tempTodoToDelete).then(function () {
                allTodosService.load().then(function (todo) {
                    $scope.todos = todo;
                    $scope.status = 'saved';
                });
            });
        };

        //Render Output
        $scope.todoIsDone = function (todo) {
            if (todo.status) {
                return true;
            }
            else {
                return false;
            }
        };
        $scope.todoIsImportant = function (todo) {
            if (todo.priority) {
                return true;
            }
            else {
                return false;
            }
        };


    }]);
