/**
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').controller('searchController', ['$scope', '$element', 'meetingService', function ($scope, $element, meetingService) {
    //var substringMatcher = function(strs) {
    var substringMatcher = function() {
        return function findMatches(q, cb) {

            if(q !== '') {
                meetingService.search(q).then(function(data) {
                    var matches = [];

                    $.each(data, function (i, meeting) {

                        var value = '<a href=\'' + meeting.url + '\'>';
                        value += meeting.name + '<br />';
                        value += '<small>';
                        value += moment(meeting.date_start).format('DD.MM.YYYY [um] HH:mm');
                        value += '</small></a>';
                        matches.push({value: value});
                    });

                    cb(matches);
                });
            }

        };
    };

    var events = [];

    $element.find('input').typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'events',
            displayKey: 'value',
            source: substringMatcher(events)
        }
    );

}]);
