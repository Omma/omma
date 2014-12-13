/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').factory('protocolService', ['$http', function($http) {

    return {
        load: function(meeting) {
            return $http.get('/meetings/' + meeting.id + '/protocol.json').then(function(data) {
                return data.data;
            });
        },


        save: function(meeting, protocol) {
            return $http.put('/meetings/' + meeting.id + '/protocol.json', protocol);
        }
    };

}]);
