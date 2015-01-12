/**
 * @ngdoc controller
 * @name ommaApp.protocol:protocolController
 * @requires $scope
 * @requires ommaApp.meeting:protocolService
 * @description
 * Controller for handling the protocol of an event
 *
 * Author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp')
    .controller('protocolController', ['$scope', 'protocolService', function ($scope, protocolService) {

        /**
         * @ngdoc property
         * @name status
         * @propertyOf ommaApp.protocol:protocolController
         * @return {string} current status ('saved', 'saving', 'not_saved')
         */
        $scope.status = 'saved';

        /**
         * @ngdoc property
         * @name protocol
         * @propertyOf ommaApp.protocol:protocolController
         * @return {Object} current protocol object
         */
        $scope.protocol = {};

        /**
         * @ngdoc method
         * @name load
         * @methodOf ommaApp.protocol:protocolService
         * @description initialize protocol
         *
         * @param {Object} meeting
         * @returns {Object} current protocol
         */
        protocolService.load($scope.$parent.meeting).then(function(protocol) {
            $scope.protocol = protocol;
        });

        /**
         * @ngdoc method
         * @name $scope_getButtonClass
         * @methodOf ommaApp.protocol:protocolController
         * @description get button class, if protocol is marked as final, button is disabled
         *
         * @returns {string} empty or string 'disabled'
         */
        $scope.getButtonClass = function() {
            if($scope.protocol.final === false) {
                return '';
            } else {
                return 'disabled';
            }
        };

        /**
         * @ngdoc method
         * @name $scope_deleteModal
         * @methodOf ommaApp.protocol:protocolController
         * @description mark protocol as final
         */
        $scope.deleteModal = function() {
            $scope.protocol.final = true;
        };

        /**
         * @ngdoc method
         * @name save
         * @methodOf ommaApp.protocol:protocolController
         * @description autosave the protocol, max. once per second
         */
        var save = _.debounce(function() {
            $scope.status = 'saving';
            protocolService.save($scope.$parent.meeting, $scope.protocol).then(function() {
                $scope.status = 'saved';
            });

        }, 1000);

        /**
         * @ngdoc property
         * @name $scope_$watch
         * @methodOf ommaApp.protocol:protocolController
         * @param {string} protocol
         * @param {function} function()
         * @description execute save() function after 3 calls
         */
        $scope.$watch('protocol', _.after(3, function() {
            $scope.status = 'not_saved';
            save();
        }), true);
}]);
