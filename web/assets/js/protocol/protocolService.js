/**
 * @ngdoc service
 * @name ommaApp.protocol:protocolService
 * @requires $http
 * @description
 * Service for persisting the protocol interactions
 *
 * Author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').factory('protocolService', ['$http', function($http) {

    return {

        /**
         * @ngdoc method
         * @name load
         * @methodOf ommaApp.protocol:protocolService
         * @description get current protocol of a meeting persisted in database
         *
         * @param {Object} meeting id of current meeting needed
         * @returns {Object} current protocol
         */
        load: function(meeting) {
            return $http.get('/meetings/' + meeting.id + '/protocol.json').then(function(data) {
                return data.data;
            });
        },

        /**
         * @ngdoc method
         * @name save
         * @methodOf ommaApp.protocol:protocolService
         * @description save content of textarea in database
         *
         * @param {Object} meeting id of current meeting needed
         * @param {Object} protocol input to save
         * @returns {HttpPromise} Future protocol
         */
        save: function(meeting, protocol) {
            return $http.put('/meetings/' + meeting.id + '/protocol.json', protocol);
        }
    };

}]);