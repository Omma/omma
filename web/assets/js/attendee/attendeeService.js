/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').factory('attendeeService', [function() {
    return {
        /**
         * Retrieve all attendees for a meeting
         * @param meeting
         * @returns {promise}
         */
        getAll: function(meeting) {
            return meeting.getList('attendees');
        },
        /**
         * Adds user by id to a meeting
         * @param meeting
         * @param id
         * @returns {promise}
         */
        add: function(meeting, id) {
            return meeting.post('attendees', {
                user: id,
                mandatory: true
            });
        },
        /**
         * Remove attendee from meeting
         * @param attendee
         * @returns {promise}
         */
        remove: function(attendee) {
            return attendee.remove();
        }
    };
}]);
