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
        alert("K");
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





           /* $('input.typeahead').typeahead({
                source: function (query, process) {
                    source: substringMatcher(states)
                }

            });*/

           // $('input.typeahead').typeahead().source = s;
            //$('input.typeahead').data('ttTypeahead').dropdown.datasets[0].source = s


            var matches, substringRegex;

            matches = [];

            var substrRegex = new RegExp(q, 'i');

            $.each(strs, function(i, str) {
                if (substrRegex.test(str)) {
                    matches.push({ value: str });
                }
            });

            cb(matches);
        };
    };

    var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
        'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
        'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
        'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
        'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
        'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
        'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
        'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
        'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
    ];

    var s = ['North Dakota',
        'Ohio', 'Oklahoma', 'Ocregon', 'Pennsylvania', 'Rhode Island',
        'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
        'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
    ];

    //var source = $.getJSON( "/web/temp_jsons/todos-left-col.json?start="+get_current_month().start, function( data ) {
    function typeahead_get_events() {
        return "";
    }





    $('input.typeahead').typeahead(
        {
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'states',
        displayKey: 'value',
        source: substringMatcher(states)
    });




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