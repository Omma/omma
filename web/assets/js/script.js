$(document).ready(function() {

    'use strict';


    //Get Language
    var language;
    if (navigator.language.indexOf('de') > -1) {
        language = 'de';
    }


    /************************************************************
     LEFT-COL
     ************************************************************/


    /* Kalender */
/*
    $.getJSON('/temp_jsons/calendar-left-col.json?start='+getCurrentMonth('').start+'&end='+getCurrentMonth('').end, function (data) {

        var events = [];

        //define events

        $.each(data, function () {

            var element = $(this)[0];

            var event = formatIncomingJsonDate(element);

            events.push(event);
        });

        //Vorselektiertes Datum
        var date = {
            year: new Date().getFullYear(),
            month: new Date().getMonth(),
            day: new Date().getDate()
        };

        var monthNames;
        var dowNames;
        var dowOffset;

        if(language === 'de') {
            monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
            dowNames= ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];
            dowOffset= 1;
        }


        var ommaDatepicker = $('input#calendar-left-col').glDatePicker({

            showAlways: true,
            selectedDate: new Date(date.year, date.month, date.day),
            monthNames: monthNames,
            dowNames: dowNames,
            dowOffset: dowOffset,
            specialDates: events,



            onClick: function(target, cell, date, data) {


                $('.day-events').slideDown();

                if(data !== null) {

                    $('.day-events #insert-events .no-events').slideUp();
                    var clickedDate = date.getFullYear()+'-'+date.getMonth()+'-'+date.getDate();
                    var list = '';

                    $.each(events, function( index, value ) {
                        var date = value.date;
                        var objDate = date.getFullYear()+'-'+date.getMonth()+'-'+date.getDate();

                        if(objDate === clickedDate) {
                            list += '<li><a href=\''+value.data.url+'\'>'+value.data.title+'</a></li>';
                        }

                    });

                    $('.day-events #insert-events ul').slideUp();
                    $('.day-events #insert-events ul').html(list);
                    $('.day-events #insert-events ul').slideDown();

                }
                else {
                    $('.day-events #insert-events ul').slideUp();
                    $('.day-events #insert-events .no-events').slideDown();
                }
            }
        }).glDatePicker(true);
        $.extend(ommaDatepicker.options, {

            nextPrevCallback: function(){

                var month = $('.core.border.monyear.title div span').first().html();
                var year = $('.core.border.monyear.title div span').last().html();

                var date = getCurrentMonth(year+'-'+month);


                $.getJSON('/temp_jsons/calendar-left-col-2.json?start='+date.start+'&end='+date.end, function (data) {

                    console.log('vorher: ' + this.specialDates);



                    var events = [
                        {
                            date: new Date(2013, 0, 8),
                            data: {message: 'Meeting every day 8 of the month'}
                        },
                        {
                            date: new Date(0, 0, 1),
                            data: {message: 'Happy New Year!'}
                        }
                    ];


                    this.specialDates = events;

                    console.log('nachher: ' + this.specialDates);
                    console.log('-----------------------');
                });
            }
        });


    });*/

  /*
    function formatIncomingJsonDate(element) {


        var formatDate = element.date;

        var day = moment(formatDate).format('DD');
        var month = moment(formatDate).format('MM');
        var year = moment(formatDate).format('YYYY');

        var dateTime = new Date(year, month, day);

        var event = {
            date: dateTime,
            data: {title: element.title, url: element.url}
        };

        return event;

    }
*/

    /* Nächste Events */
    $.getJSON( '/temp_jsons/next-events-left-col.json?start='+getCurrentMonth('').start, function( data ) {

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
    $.getJSON( '/temp_jsons/todos-left-col.json?start='+getCurrentMonth('').start, function( data ) {
        $.each( data, function( key,value ) {
            $('.left-col ul.todos').append('<li><a href=\''+value.url+'\'>'+value.title+'</li>');
        });
    });


    //incoming: string, 2014-Januar e.g.
    function getCurrentMonth(month) {

        var start;
        var end;

        if(month==='') {
            start = moment().startOf('month').toISOString();
            end = moment().endOf('month').toISOString();
        }
        else {
            start = moment(month, 'YYYY MM').startOf('month').toISOString();
            end = moment(month, 'YYYY MM').endOf('month').toISOString();
        }

        var returnMonth = {
            'start':start,
            'end': end
        };

        return returnMonth;
    }



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
        tmpl_path: '/assets/js/libs/bs_calendar/tmpls/',
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
