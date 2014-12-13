/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('toDoController', ['$scope', function ($scope) {




        function fetchTodosFromJSON() {
            var todos = [
                {
                    id: 5,
                    status: 0,
                    task: 'test2 bla',
                    user_id: 1,
                    description: 'test3 bla',
                    date: '2014-213-123-123-132321',
                    priority: 0
                },
                {
                    id: 7,
                    status: 1,
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

        function setTodos() {
            $scope.todos = fetchTodosFromJSON();
        }

        //Add new todo
        $scope.addNewTodo = function() {
            console.log('add empty task in db');
            setTodos();
        };




        //Edit todo
        $scope.checkboxDoneChanged = function (todo) {  //done
            console.log('change into db');
            console.log(todo);
        };
        $scope.checkboxPrioChanged = function (todo) {  //high priority
            console.log('change into db');
            console.log(todo);
        };



        //Priority
        $scope.getPriorityOfTodo = function(todo){
            if(todo.priority === 1){
                return true;
            }
            else{
                return false;
            }
        };



        //Attendees



        $scope.status = {
            isFirstOpen: true,
            isFirstDisabled: false
        };



        //Delete Modal
        var tempIdToDelete;
        $scope.setTempIdToDelete = function(id) {
            tempIdToDelete = id;
        };
        $scope.deleteModal = function () {
            console.log('delete id: '+tempIdToDelete+'in db');
        };


        /**************************************
         * Datepicker
         */

        $scope.today = function() {
            $scope.dt = new Date();
        };
        $scope.today();

        $scope.clear = function () {
            $scope.dt = null;
        };

        // Disable weekend selection
        $scope.disabled = function(date, mode) {
            return ( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
        };

        $scope.toggleMin = function() {
            $scope.minDate = $scope.minDate ? null : new Date();
        };
        $scope.toggleMin();

        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();

            $scope.opened = true;
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };

        $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[0];


}]);
