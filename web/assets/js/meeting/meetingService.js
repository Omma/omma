app.factory('meetingService', ['$http', function($http) {
    return {
        /**
         * Get Meetings for date range
         * @param {Date|moment} start date
         * @param {Date|moment} end date
         * @returns {*}
         */
        getByDate: function(start, end) {
            if (!moment.isMoment(start)) {
                start = moment(start);
            }
            if (!moment.isMoment(end)) {
                end = moment(end);
            }
            return $http.get('/temp_jsons/calendar-left-col.json?start=' + start.format() + '&end=' + end.format())
                .then(function (data) {
                    return _.map(data.data, formatIncomingJsonDate);
                })
            ;
        }
    };
}]);
