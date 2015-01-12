/**
 * @ngdoc service
 * @name ommaApp.toDo:toDoController
 * @requires $scope
 * @requires toDoService
 * @description
 * Controller for toDos of an event
 *
 * Author Florian Pfitzer <pfitzer@w3p.cc>
 * Author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').factory('meetingService', ['$http', function($http) {
    return {
        /**
         * Create virtual meeting
         *
         * @author Florian Pfitzer <pfitzer@w3p.cc>
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
                .year(date.year())
            ;
            var end = moment(meeting.date_end)
                .date(date.date())
                .month(date.month())
                .year(date.year())
            ;

            newMeeting.date_start = start.format();
            newMeeting.date_end = end.format();

            newMeeting.identifier = 'rec-' + recurring.id + '_' + start.format('YY-M-D');
            newMeeting.url = '/meetings/create?recurring=' + recurring.id + '&date=' + encodeURIComponent(end.format());

            return newMeeting;
        },
        /**
         * check if date is a recurring range
         *
         * @author Florian Pfitzer <pfitzer@w3p.cc>
         * @param recurring
         * @param date
         * @returns {*|boolean}
         * @private
         */
        _isDateInRange: function (recurring, date) {
            return date.isAfter(recurring.date_start) &&
                (
                    undefined === recurring.date_end || date.isBefore(recurring.date_end)
                );
        },
        /**
         * calculate recurrings for a meeting
         *
         * @author Florian Pfitzer <pfitzer@w3p.cc>
         * @param meeting
         * @param start
         * @param end
         * @returns {Array}
         * @private
         */
        _getRecurrings: function(meeting, start, end) {
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

            meeting.identifier = 'rec-' + recurring.id + '_' + moment(meeting.date_start).format('YY-M-D');

            var current = start;
            switch (meeting.meeting_recurring.type) {
                case 'day':
                    while (current.isBefore(end)) {
                        meetings = meetings.concat(this._getDayRecurrings(current, recurring, meeting));
                        current.add(config.every, 'day');
                    }
                    break;
                case 'week':
                    while (current.isBefore(end)) {
                        meetings = meetings.concat(this._getWeekRecurrings(current, recurring, meeting));
                        current.add(config.every, 'week');
                    }
                    break;
                case 'month':
                    while (current.isBefore(end)) {
                        meetings = meetings.concat(this._getMonthRecurrings(current, recurring, meeting));
                        // +1 day otherwise a endless recursion can occur
                        current
                            .add(config.every, 'month')
                            .add(1, 'day')
                        ;
                    }
                    break;
                case 'year':
                    current = moment(meeting.date_start);
                    while (current.isBefore(end)) {
                        meetings = meetings.concat(this._getYearRecurrings(current, recurring, meeting));
                        current.add(config.every, 'year');
                    }
                    break;
            }

            return meetings;
        },
        /**
         * Get virtual meetings for daily recurrings
         *
         * @author Florian Pfitzer <pfitzer@w3p.cc>
         * @param date
         * @param recurring
         * @param meeting
         * @returns {Array}
         * @private
         */
        _getDayRecurrings: function(date, recurring, meeting) {
            var meetings = [];
            if (this._isDateInRange(recurring, date)) {
                meetings.push(this._createVirtualMeeting(meeting, date));
            }

            return meetings;
        },
        /**
         * Get virtual meetings for weekly recurrings
         *
         * @author Florian Pfitzer <pfitzer@w3p.cc>
         * @param date
         * @param recurring
         * @param meeting
         * @private
         */
        _getWeekRecurrings: function(date, recurring, meeting) {
            var meetings = [];
            _.forIn(recurring.config.week_weekdays, function(value, weekday) {
                if ('1' !== value) {
                    return;
                }
                date.day(weekday);
                if (this._isDateInRange(recurring, date)) {
                    meetings.push(this._createVirtualMeeting(meeting, date));
                }
            }, this);

            return meetings;
        },
        /**
         * Get virtual meetings for weekly recurrings
         *
         * @author Florian Pfitzer <pfitzer@w3p.cc>
         * @param date
         * @param recurring
         * @param meeting
         * @returns {Array}
         * @private
         */
        _getMonthRecurrings: function(date, recurring, meeting) {
            var meetings = [];
            var config = recurring.config;

            date.startOf('month');
            // get first monday (1)
            while (1 !== date.isoWeekday()) {
                date.add(1, 'day');
            }
            if ('relative' === config.month_type) {
                switch(config.rel_month) {
                    case 'first':
                        break;
                    case 'second':
                        date.add(1, 'week');
                        break;
                    case 'third':
                        date.add(2, 'week');
                        break;
                    case 'fourth':
                        date.add(3, 'week');
                        break;
                    case 'last':
                        date.endOf('month');
                        while (config.rel_month_day !== date.isoWeekday()) {
                            date.subtract(1, 'day');
                        }
                        break;
                }
                if ('last' !== config.rel_month) {
                    date.isoWeekday(config.rel_month_day);
                }
            } else {
                date.date(config.abs_month_day);
            }
            if (this._isDateInRange(recurring, date)) {
                meetings.push(this._createVirtualMeeting(meeting, date));
            }

            return meetings;
        },
        /**
         * Get virtual meetings for weekly recurrings
         *
         * @author Florian Pfitzer <pfitzer@w3p.cc>
         * @param date
         * @param recurring
         * @param meeting
         * @returns {Array}
         * @private
         */
        _getYearRecurrings: function(date, recurring, meeting) {
            var meetings = [];
            if (this._isDateInRange(recurring, date)) {
                meetings.push(this._createVirtualMeeting(meeting, date));
            }

            return meetings;
        },
        /**
         * Get Meetings for date range
         *
         * @author Johannes Höhn <johannes.hoehn@hof-university.de>
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
                    // add recurrings
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

                    // can't modify array in foreach -> second array
                    var newMeetings = [];

                    angular.forEach(meetings, function(meeting) {
                        var meeting2 = meetingsById[meeting.identifier];
                        if (undefined !== meeting2) {
                            // override virtual meeting with real
                            if (meeting2.isVirtual && !meeting.isVirtual) {
                                _.pull(newMeetings, meeting2);
                            } else {
                                _.pull(newMeetings, meeting);
                                return;
                            }
                        }
                        meetingsById[meeting.identifier] = meeting;
                        newMeetings.push(meeting);
                    });

                    return newMeetings;
                })
            ;
        },
        /**
         * @author Johannes Höhn <johannes.hoehn@hof-university.de>
         * @param term
         * @returns {*}
         */
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
