/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').service('meetingRecurringSerive', ['$http', function($http) {
    return {
        load: function(meeting) {
            return $http.get('/meetings/' + meeting.id + '/recurrings.json').then(function(data) {
                return data.data;
            });
        },
        save: function(meeting, recurring) {
            
        }
    }
}]);
