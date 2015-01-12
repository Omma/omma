/**
 * @ngdoc controller
 * @name ommaApp.meeting:meetingController
 * @requires $scope
 * @requires Restangular
 * @requires editableThemes
 * @description
 * Controller for the meeting detail page
 *
 * Author: Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').controller('meetingController', ['$scope', 'Restangular', 'editableThemes', function($scope, Restangular, editableThemes) {
    // change inline editing input and button size
    editableThemes.bs3.inputClass = 'input-lg';
    editableThemes.bs3.buttonsClass = 'btn-lg';

    /**
     * @ngdoc property
     * @name $scope_meeting
     * @propertyOf ommaApp.meeting:meetingController
     * @return {Object} displayed meeting
     */
    $scope.meeting = {};

    /**
     * @ngdoc method
     * @name $scope_init
     * @methodOf ommaApp.meeting:meetingController
     * @param {Object} data meeting object
     * @description
     * called when initializing the controller.
     */
    $scope.init = function(data) {
        $scope.meeting = data;
        Restangular.restangularizeElement(null, data, 'meetings');
        if (undefined !== data.prev) {
            Restangular.restangularizeElement(null, data.prev, 'meetings');
        }
        // watch for changes
        $scope.$watch('meeting', _.after(3, _.debounce($scope.saveMeeting, 1000)), true);
    };

    /**
     * @ngdoc method
     * @name $scope_saveMeeting
     * @methodOf ommaApp.meeting:meetingController
     * @description
     * Save meeting to backend
     */
    $scope.saveMeeting = function() {
        var meeting = $scope.meeting.clone();

        // replace prev meeting object with id
        if (undefined !== meeting.prev) {
            meeting.prev = meeting.prev.id;
        }
        // replace next meeting object with id
        if (undefined !== meeting.next) {
            delete meeting.next;
        }
        meeting.put();
    };

    /**
     * @ngdoc method
     * @name $scope_deleteMeeting
     * @methodOf ommaApp.meeting:meetingController
     * @description
     * Delete meeting and return to dashboard
     */
    $scope.deleteMeeting = function() {
        $scope.meeting.remove().then(function() {
            window.location.href = '/dashboard';
        });
    };
}]);
