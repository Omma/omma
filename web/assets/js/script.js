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
    $('body').on( 'click', '.panel-heading', function() {

        var element = $(this).find('a');
        $(element).trigger('click');
    });





    /************************************************************
     LEFT-COL
     ************************************************************/



    /* Nächste Events */
    $.getJSON( '/temp_jsons/next-events-left-col.json?start='+utils.getCurrentMonth('').start, function( data ) {

        $.each( data, function( key,value ) {
            var active;
            if(key===0) {
                active = 'active';
            }

            var formatDate = value.date;

            var date = moment(formatDate).format('DD.MM.YYYY [um] HH:mm');

            $('.left-col div.naechste-events .list-group').append(
                '<a href=\''+value.url+'\' class=\'list-group-item '+active+'\'>' +
                '<p>'+value.title+'</p>' +
                '<p class=\'list-group-item-text\'><small>'+date+'</small></p>' +
                '</a>'
            );
        });
    });





    /* Todos */
    $.getJSON( '/temp_jsons/todos-left-col.json?start='+utils.getCurrentMonth('').start, function( data ) {
        $.each( data, function( key,value ) {
            //$('.left-col ul.todos').append('<li><a href=\''+value.url+'\'>'+value.title+'</a></li>');
            $('.left-col #insert-todos').append('<a href="'+value.url+'" class="list-group-item">'+value.title+'</a>');
        });
    });

    /************************************************************
     Typeahead Suche
     ************************************************************/

    //var substringMatcher = function(strs) {
    var substringMatcher = function() {
        return function findMatches(q, cb) {

            if(q !== '') {
                $.getJSON('/temp_jsons/typeahead.json?q=' + q, function (data) {

                    var matches = [];

                    var substrRegex = new RegExp(q, 'i');

                    $.each(data, function (i, str) {

                        if (substrRegex.test(str.title)) {
                            var value = '<a href=\'' + str.url + '\'>';
                            value += str.title + '<br />';
                            value += '<small>';
                            value += moment(str.date).format('DD.MM.YYYY [um] HH:mm');
                            value += '</small></a>';
                            matches.push({value: value});
                        }
                    });

                    cb(matches);

                });
            }

        };
    };

    var events = [];

    $('input.typeahead').typeahead(
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

    // initalize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
