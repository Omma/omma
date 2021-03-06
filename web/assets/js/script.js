//incoming: string, 2014-Januar e.g.
window.utils = {
    getCurrentMonth:        function (month) {
        if (month === '') {
            return {
                start: moment().startOf('month'),
                end:   moment().endOf('month')
            };
        }

        return {
            start: moment(month, 'YYYY MM').startOf('month'),
            end:   moment(month, 'YYYY MM').endOf('month')
        };
    }
};


$(document).ready(function() {

    'use strict';




    /************************************************************
     Basics
     ************************************************************/

    //Get Language
    var language;
    if (navigator.language.indexOf('de') > -1) {
        language = 'de';
    }

    //todo trigger click
    /*$('body').on( 'click', '.panel-heading', function() {

        var element = $(this).find('a');
        $(element).trigger('click');
    });*/





    /************************************************************
     LEFT-COL
     ************************************************************/


    // initalize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
