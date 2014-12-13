/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('toDoController', ['$scope', function ($scope) {




        //priority: 1(high) or 0(normal)

        $scope.todos = [
            {
                id: 5,
                status: 0,
                task: 'test2 bla',
                description: 'test3 bla',
                date: '2014-213-123-123-132321',
                priority: 0
            },
            {
                id: 7,
                status: 1,
                task: 'test3 bla',
                description: 'test3 bla',
                date: '2014-213-123-123-132321',
                priority: 1
            }
        ];





        //Checkbox changed

        $scope.checkboxDoneChanged = function (todo) {
            console.log('change into db');
            console.log(todo);
        };
        $scope.checkboxPrioChanged = function () {
            console.log('change into db');
            console.log($scope.todos);
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




        $scope.status = {
            isFirstOpen: true,
            isFirstDisabled: false
        };


        //Add new ToDo
        $scope.newToDoContent = '';
        $scope.persistNewToDo = function () {

            var done = false;
            var content = $scope.newToDoContent;
            var finishDate = '2014-213-123-123-132321';

            console.log('add new todo into db');

            $scope.todos.push({done:done, content: content, finishDate: finishDate});
            $scope.newToDoContent = '';
        };



        //Delete Modal
        $scope.deleteModal = function () {
            console.log('delete in db');
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
