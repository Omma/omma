/**
 * @ngdoc filter
 * @name ommaApp.moment
 * @function
 * @param {String} [format='LLLL'] date format
 * @description
 * Filter for formatting a date or string with momentjs
 *
 * Author Florian Pfitzer <pfitzer@w3p.cc>
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
