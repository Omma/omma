/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */

angular.module('ommaApp')
    .controller('toDoController', ['$scope', function ($scope) {






        $scope.todos = [
            {
                done: false,
                content: 'test bla',
                finishDate: '2014-213-123-123-132321'
            },
            {
                done: false,
                content: 'test2 bla',
                finishDate: '2014-213-123-123-132321'
            },
            {
                done: false,
                content: 'test3 bla',
                finishDate: '2014-213-123-123-132321'
            }
        ];


        //Checkbox changed

        $scope.checkboxChanged = function () {

            console.log('change into db');

        };



        //Add new ToDo
        $scope.newToDoContent = '';


        $scope.persistNewToDo = function () {

            //alert($scope.newToDoContent);


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



    }]);