/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').factory('meetingService', ['$http', function($http) {
    return {
        _getRecurrings: function(meeting, start, end) {
            var meetings = [];
            meeting.isVirtual = false;
            meeting.identifier = meeting.id;
            if (undefined === meeting.meeting_recurring) {
                return meetings;
            }
            meeting.identifier = 'rec-' + meeting.meeting_recurring.id + '_' + moment(meeting.date_start).format('YY-M-D');

            function createMeeting(date) {
                var newMeeting = _.clone(meeting);
                newMeeting.isVirtual = true;
                newMeeting.base = meeting.id;
                newMeeting.date_start = moment(meeting.date_start)
                    .date(date.date())
                    .month(date.month())
                    .year(date.year())
                    .format()
                ;
                newMeeting.date_end = moment(meeting.date_end)
                    .date(date.date())
                    .month(date.month())
                    .year(date.year())
                    .format()
                ;
                newMeeting.identifier = 'rec-' + meeting.meeting_recurring.id + '_' + moment(newMeeting.date_start).format('YY-M-D');

                return newMeeting;
            }

            var config = meeting.meeting_recurring.config;
            console.log(config);
            switch (meeting.meeting_recurring.type) {
                case 'week':
                    var current = start;
                    while(current.isBefore(end)) {
                        _.forIn(config.month_weekdays, function(enable, weekday) {
                            if (!enable) {
                                return;
                            }
                            current.day(weekday);
                            meetings.push(createMeeting(current));
                        });
                        current.add(config.every, 'week');
                    }
                    break;
            }

            return meetings;
        },
        /**
         * Get Meetings for date range
         * @param {Date|moment} start date
         * @param {Date|moment} end date
         * @returns {*}
         */
        getByDate: function(start, end) {
            var self = this;
            if (!moment.isMoment(start)) {
                start = moment(start);
            }
            if (!moment.isMoment(end)) {
                end = moment(end);
            }
            return $http.get('/meetings/' + start.format() + '/ranges/' + end.format() + '.json')
                .then(function (data) {
                    return data.data;
                })
                .then(function(meetings) {
                    var newMeetings = [];
                    angular.forEach(meetings, function(meeting) {
                        newMeetings.push(meeting);
                        newMeetings = newMeetings.concat(self._getRecurrings(meeting, start, end));
                    });
                    console.log(newMeetings);
                    return newMeetings;
                })
                .then(function(meetings) {
                    // remove duplicates, added by recurrings
                    return _.uniq(meetings, false, function(meeting) {
                        return meeting.identifier;
                    })
                });
            ;
        },
        search: function(term) {
            return $http.get('/meetings.json', {
                params: {
                    search: term
                }
            }).then(function(data) {
                return data.data;
            });
        }
    };
}]);
