/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('toDoController', ['$scope', function ($scope) {


        function persistTodo(id, row, value) {
            console.log('update todo where id = '+id+': '+row+' => '+value);
            setTodos();
        }

        function fetchTodosFromJSON() {
            var todos = [
                {
                    id: 5,
                    status: 1, //int
                    task: 'test2 bla',
                    user_id: 1,
                    description: 'test3 bla',
                    date: '2014-213-123-123-132321',
                    priority: 0
                },
                {
                    id: 7,
                    status: 0, //int
                    task: 'test3 bla',
                    user_id: 1,
                    description: 'test3 bla',
                    date: '2014-213-123-123-132321',
                    priority: 1
                }
            ];
            return todos;
        }
        $scope.selectedUser = '';


        //priority: 1(high) or 0(normal)
        $scope.todos = fetchTodosFromJSON();

        function setTodos() {
            $scope.todos = fetchTodosFromJSON();
        }

        //Add new todo
        $scope.addNewTodo = function() {
            console.log('add empty task in db');

            //leeres Objekt wird in DB geschrieben (neue ID wird erzeugt)
            //und anschließend DB neu auslesen (fetchTodosFromJSON())

            setTodos();
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



        //Edit todo
        $scope.persistStatus = function(todo) {

            var id = todo.id;
            var row = 'status';
            var value = todo.status;
            persistTodo(id, row, value);
        };
        $scope.persistPriority = function(todo) {

            var id = todo.id;
            var row = 'priority';
            var value = todo.priority;
            persistTodo(id, row, value);
        };

        $scope.temp = function() {
            console.log('K');
        };








        //Attendees

        $scope.addUser = function() {
            var selectedUser = $scope.selectedUser;
            console.log(selectedUser);

            //addUser(selectedUser);
        };




        //Datepicker
        $scope.timepicker;


        $scope.timeChange = function() {

            console.log('K');

        };






        //Delete Modal
        var tempIdToDelete;
        $scope.setTempIdToDelete = function(id) {
            tempIdToDelete = id;
        };
        $scope.deleteModal = function () {
            console.log('delete id: '+tempIdToDelete+'in db');
            setTodos();
        };






}]);
