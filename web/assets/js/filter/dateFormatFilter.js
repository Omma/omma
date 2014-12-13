/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').filter('moment', [function() {
    return function(input, format) {
        if (!moment.isMoment(input)) {
            input = moment(input);
        }

        return input.format('DD. MMMM. YYYY HH:mm');
    };
}]);
