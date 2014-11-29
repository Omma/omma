$(document).ready(function() {

    "use strict";

    /************************************************************
     LEFT-COL
     ************************************************************/


    /* Kalender */

    $.getJSON("/web/temp_jsons/calendar-left-col.json?start="+get_current_month().start+"&end="+get_current_month().end, function (data) {

        var events = new Array();

        $.each(data, function () {

            var element = $(this)[0];

            var formatDate = element.date;

            var day = moment(formatDate).format('DD');
            var month = moment(formatDate).format('MM');
            var year = moment(formatDate).format('YYYY');

            var date_time = new Date(year, month, day);

            var event = {
                date: date_time,
                title: element.title,
                url: element.url
            }

            events.push(event);
        });


        //Vorselektiertes Datum
        var date = {
            year: new Date().getFullYear(),
            month: new Date().getMonth(),
            day: new Date().getDate(),
        }

        $('input#calendar-left-col').glDatePicker(
            {
                showAlways: true,
                selectedDate: new Date(date.year, date.month, date.day),
                specialDates: events,
                onClick: function() {

                    alert("K");
                }
            });

    });





    /* Nächste Events */
    $.getJSON( "/web/temp_jsons/next-events-left-col.json?start="+get_current_month().start, function( data ) {
        var i=0;
        $.each( data, function( key,value ) {
            if(key==0)
                var active = "active";


            var formatDate = value.date;

            var date = moment(formatDate).format("DD.MM.YYYY [um] HH:mm");

            $('.left-col div.naechste-events .list-group').append(
                "<a href=\""+value.url+"\" class=\"list-group-item "+active+"\">" +
                "<p>"+value.title+"</p>" +
                "<p class=\"list-group-item-text\"><small>"+date+"</small></p>" +
                "</a>"
            );
        });
    });





    /* Todos */
    $.getJSON( "/web/temp_jsons/todos-left-col.json?start="+get_current_month().start, function( data ) {
        $.each( data, function( key,value ) {
            $('.left-col ul.todos').append("<li><a href=\""+value.url+"\">"+value.title+"</li>");
        });
    });


    function get_current_month() {

        var start = moment().startOf("month").toISOString();
        var end = moment().endOf("month").toISOString();

        var month = {
            "start":start,
            "end": end
        };

        return month;
    }



    /************************************************************
     Typeahead Suche
     ************************************************************/

    var substringMatcher = function(strs) {
        return function findMatches(q, cb) {

            if(q != '') {
                $.getJSON("/web/temp_jsons/typeahead.json?q=" + q, function (data) {

                    var matches = [];

                    var substrRegex = new RegExp(q, 'i');

                    $.each(data, function (i, str) {

                        if (substrRegex.test(str.title)) {
                            var value = "<a href=\"" + str.url + "\">";
                            value += str.title + "<br />";
                            value += "<small>";
                            value += moment(str.date).format("DD.MM.YYYY [um] HH:mm");
                            value += "</small></a>";
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


    var day = moment().format("YYYY-MM-DD");

    var options = {
        events_source: function(start, end) {

            var events = [];
            $.ajax("temp_jsons/events_dt.json.php?from=" + moment(start).format() + "&to=" + moment(end).format(), {
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
                        }
                        events.push(event);
                    })
                }
            });
            return events;
        },
        view: 'month',
        tmpl_path: 'assets/js/libs/bs_calendar/tmpls/',
        tmpl_cache: false,
        day: day,
        onAfterEventsLoad: function(events) {
            if(!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');

            //$('.left-col div.naechste-events .list-group').html('');
            $.each(events, function(key, val) {


                var date = "datum von event";

                if(key==0)
                    var active = "active";


                /*$('.left-col div.naechste-events .list-group').append(
                 "<a href=\""+val.url+"\" class=\"list-group-item "+active+"\">" +
                 "<p>"+val.title+"</p>" +
                 "<p class=\"list-group-item-text\"><small>"+date+"</small></p>" +
                 "</a>"
                 );*/

            });
        },
        onAfterViewLoad: function(view) {
            $('.page-header h2').text(this.getTitle());
            $('.btn-group button').removeClass('active');
            $('button[data-calendar-view="' + view + '"]').addClass('active');
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





    calendar.setLanguage("de-DE");
    /* alternativen
     default: en-US)</option>
     <option value="fr-FR">French</option>
     <option value="de-DE">German</option>
     */
    calendar.view();


})