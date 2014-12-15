/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('toDoController', ['$scope', function ($scope) {




        function fetchTodosFromJSON() {
            var todos = [
                {
                    id: 5,
                    status: 1, //int
                    task: 'test2 bla',
                    user_id: 1,
                    description: 'test3 bla',
                    date: '2014-213-123-123-132321',
                    priority: 0,
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


        //priority: 1(high) or 0(normal)
        $scope.todos = fetchTodosFromJSON();

        $scope.user;


        //Add new todo
        $scope.addNewTodo = function() {
            console.log('add empty task in db');

            //leeres Objekt wird in DB geschrieben (neue ID wird erzeugt)
            //und anschließend DB neu auslesen (fetchTodosFromJSON())

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





        $scope.temp = function() {
            alert('test erfolgreich');
        };





        //Attendees
        $scope.addUser = function(todo) {


            todo.user_id = todo.user.id;
            delete todo.user;

            console.log('add'+todo);

            //addUser(selectedUser);
        };




        //Datepicker






        //Delete Modal
        var tempIdToDelete;
        $scope.setTempIdToDelete = function(id) {
            tempIdToDelete = id;
        };
        $scope.deleteModal = function () {
            console.log('delete id: '+tempIdToDelete+'in db');
            //setTodos();

            //remove todo
        };






}]);
