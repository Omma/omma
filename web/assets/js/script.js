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

    //Nachfrage bei wichtigen Buttons
    $('.btn.omma-cancel, .btn.omma-do').hide();
    $('.btn.omma-ask').click(function() {
        $(this).hide();
        $('.btn.omma-cancel, .btn.omma-do').show();
    });
    $('.btn.omma-cancel').click(function() {
        $('.btn.omma-cancel, .btn.omma-do').hide();
        $('.btn.omma-ask').show();
    });
    $('.btn.omma-do').click(function() {
        alert('als final markieren');
        $('.btn.omma-cancel, .btn.omma-do').hide();
        $('.btn.omma-ask').show();
        $('.btn.omma-ask').addClass('disabled');
    });








    /************************************************************
     LEFT-COL
     ************************************************************/


    // initalize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
