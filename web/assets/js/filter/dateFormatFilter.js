/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
angular.module('ommaApp').filter('moment', [function() {
    return function(input, format) {
        if (!moment.isMoment(input)) {
            input = moment(input);
        }
        if (undefined === format) {
            format = 'LLLL';
        }

        return input.format(format);
    };
}]);
