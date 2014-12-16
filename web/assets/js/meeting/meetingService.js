/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').factory('meetingService', ['$http', function($http) {
    return {
        /**
         *
         * @param meeting base meeting
         * @param date
         * @returns {*}
         * @private
         */
        _createVirtualMeeting: function (meeting, date) {
            var recurring = meeting.meeting_recurring;

            var newMeeting = _.clone(meeting);
            newMeeting.isVirtual = true;
            newMeeting.base = meeting.id;

            var start  = moment(meeting.date_start)
                .date(date.date())
                .month(date.month())
                .year(date.year());
            var end = moment(meeting.date_end)
                .date(date.date())
                .month(date.month())
                .year(date.year());

            newMeeting.date_start = start.format();
            newMeeting.date_end = end.format();

            newMeeting.identifier = 'rec-' + recurring.id + '_' + start.format('YY-M-D');
            newMeeting.url = '/meetings/create?recurring=' + recurring.id + '&date=' + encodeURIComponent(end.format());

            return newMeeting;
        },
        _getRecurrings: function(meeting, start, end) {
            var self = this;
            var meetings = [];
            meeting.isVirtual = false;
            meeting.identifier = meeting.id;
            if (undefined === meeting.meeting_recurring) {
                return meetings;
            }

            var recurring = meeting.meeting_recurring;
            recurring.date_start = moment(recurring.date_start);
            recurring.date_end = undefined !== recurring.date_end ? moment(recurring.date_end) : undefined;
            var config = recurring.config;
            console.log('config', config);

            meeting.identifier = 'rec-' + recurring.id + '_' + moment(meeting.date_start).format('YY-M-D');

            function isDateInRange(date) {
                return date.isAfter(recurring.date_start) && (
                    undefined === recurring.date_end || date.isBefore(recurring.date_end)
                );
            }
            var current = start;
            switch (meeting.meeting_recurring.type) {
                case 'week':
                    while(current.isBefore(end)) {
                        _.forIn(config.week_weekdays, function(value, weekday) {
                            if ('1' !== value) {
                                return;
                            }
                            current.day(weekday);
                            if (isDateInRange(current)) {
                                meetings.push(self._createVirtualMeeting(meeting, current));
                            }
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
                        meeting.url = '/meetings/' + meeting.id + '/details';
                        newMeetings.push(meeting);
                        newMeetings = newMeetings.concat(self._getRecurrings(meeting, start, end));
                    });
                    return newMeetings;
                })
                .then(function(meetings) {
                    // remove duplicates, added by recurrings
                    var meetingsById = {};
                    angular.forEach(meetings, function(meeting) {
                        if (undefined !== (meeting2 = meetingsById[meeting.identifier])) {
                            // override virtual meeting with real
                            if (meeting2.isVirtual && !meeting.isVirtual) {
                                _.pull(meetings, meeting2);
                            } else {
                                _.pull(meetings, meeting);
                                return;
                            }
                        }
                        meetingsById[meeting.identifier] = meeting;
                    });

                    return meetings;
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
