/**
 * @ngdoc controller
 * @name ommaApp.toDo:toDoController
 * @requires $scope
 * @requires toDoService
 * @description
 * Controller for toDos of an event
 *
 * Author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('toDoController', ['$scope', 'toDoService', function ($scope, toDoService) {


        /**
         * @ngdoc property
         * @name status
         * @propertyOf ommaApp.toDo:toDoController
         * @return {string} current status ('saved', 'saving', 'not_saved')
         */
        $scope.status = 'saved';


        /**
         * @ngdoc method
         * @name load
         * @methodOf ommaApp.toDo:toDoService
         * @param {Object} meeting load todos using toDoService
         */
        toDoService.load($scope.$parent.meeting).then(function(todos) {
            $scope.todos = todos;
            angular.forEach($scope.todos, function(todo) {
                watchTodo(todo);
            });
        });


        /**
         * @ngdoc method
         * @name watchTodo
         * @methodOf ommaApp.toDo:toDoController
         * @param {Object} todo watch toDos for change, if changed it will be saved into database
         */
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



        /**
         * @ngdoc method
         * @name watchTodo
         * @methodOf ommaApp.toDo:toDoController
         * @description watch toDos for change, if changed it will be saved into database
         */
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


        /**
         * @ngdoc method
         * @name save
         * @methodOf ommaApp.toDo:toDoController
         * @param {Object} todo save incoming object
         */
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

        /**
         * @ngdoc method
         * @name deleteModal
         * @methodOf ommaApp.toDo:toDoController
         * @description remove marked todo
         */
        $scope.deleteModal = function () {
            $scope.status = 'saving';
            _.pull($scope.todos, tempTodoToDelete);
            toDoService.remove($scope.$parent.meeting, tempTodoToDelete).then(function() {
                $scope.status = 'saved';
            });
        };
}]);
