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
    },
    formatIncomingJsonDate: function (element) {
        var date = moment(element.date);

        return {
            date:     date.toDate(),
            dateOrig: date,
            data:     {title: element.title, url: element.url}
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
            $('.left-col ul.todos').append('<li><a href=\''+value.url+'\'>'+value.title+'</li>');
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




    /************************************************************
     Kalender Startseite
     ************************************************************/


    var day = moment().format('YYYY-MM-DD');

    var options = {
        events_source: function(start, end) {

            var events = [];
            $.ajax('/temp_jsons/events_dt.json.php?from=' + moment(start).format() + '&to=' + moment(end).format(), {
                dataType: 'json',
                async: false,
                success: function(data) {
                    events = [];


                    //parse date
                    $.each(data.result, function() {
                        var obj = $(this)[0];

                        var start = (moment(obj.start).unix())*1000;
                        var end = moment(obj.end).unix()*1000;

                        var event = {
                            id: obj.id,
                            title: obj.title,
                            url: obj.url,
                            start: start,
                            end: end
                        };
                        events.push(event);
                    });
                }
            });

            return events;
        },
        view: 'month',
        tmpl_path: '/assets/components/bootstrap-calendar/tmpls/',
        tmpl_cache: false,
        day: day,
        onAfterEventsLoad: function(events) {
            if(!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');
        },
        onAfterViewLoad: function(view) {
            $('.page-header h2').text(this.getTitle());
            $('.btn-group button').removeClass('active');
            $('button[data-calendar-view=' + view + ']').addClass('active');
        },
        classes: {
            months: {
                general: 'label'
            }
        }
    };

    var calendar = $('#calendar').calendar(options);

    $('.btn-group button[data-calendar-nav]').each(function() {
        var $this = $(this);
        $this.click(function() {
            calendar.navigate($this.data('calendar-nav'));
        });
    });

    $('.btn-group button[data-calendar-view]').each(function() {
        var $this = $(this);
        $this.click(function() {
            calendar.view($this.data('calendar-view'));
        });
    });




    if(language === 'de') {
        calendar.setLanguage('de-DE');
    }
    else {
        calendar.setLanguage('en-US');
    }

    calendar.view();



});
