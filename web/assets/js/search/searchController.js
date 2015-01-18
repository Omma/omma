/**
 * @ngdoc controller
 * @name ommaApp.search:searchController
 * @requires $scope
 * @requires $element
 * @requires meetingService
 * @description
 * Controller for the typeahead search to find events
 *
 * Author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
angular.module('ommaApp').controller('searchController', ['$scope', '$element', 'meetingService', function ($scope, $element, meetingService) {

    /**
     * @ngdoc property
     * @name events
     * @propertyOf ommaApp.search:searchController
     * @return {Array} matching events
     */
    var events = [];

    /**
     * @ngdoc method
     * @name substringMatcher
     * @methodOf ommaApp.search:searchController
     * @description find matching eventtitles with input
     */
    //var substringMatcher = function(strs) {
    var substringMatcher = function() {
        return function findMatches(q, cb) {

            if(q !== '') {
                meetingService.search(q).then(function(data) {
                    var matches = [];

                    $.each(data, function (i, meeting) {
                        var value = '<a href=\'/meetings/' + meeting.id + '/details\'>';
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

    $('body').on('click', '.tt-suggestion', function() {
        window.location = $(this).find('a').attr('href');
    });

}]);
