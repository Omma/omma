

$(document).ready(function() {

    /************************************************************
            LEFT-COL
     ************************************************************/



    /* Kalender */

    /*

    so kommt json rein: 

    var arr = [
     {
     "date": "2014-11-19 12:14:09",
     "title": "test1",
     "url": "http://www.teasdst.de"
     },
     {
     "date": "2014-11-19 12:14:09",
     "title": "test1",
     "url": "http://www.teasdst.de"
     },
     ];*/

    var events = [
        {
            date: new Date(2014, 10, 15),
            title: 'test1',
            url: 'http://www.teasdst.de'
        },
        {
            date: new Date(2014, 10, 13),
            title: 'test2',
            url: 'http://www.test.de'
        },
    ];

    $.getJSON( "/web/temp_jsons/calendar-left-col.json", function( data ) {

        $.each(data, function() {

            var date_time = new Date(2014, 10, 15);

            var event = {
                date: date_time,
                title: data.title,
                url: data.url
            }
            events.push(event);
        });
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
        onClick: function(target, cell, date, data) {
            //console.log($(this));
            //alert...
        }
    });









    /* Nächste Events */
    $.getJSON( "/web/temp_jsons/next-events-left-col.json", function( data ) {
        var i=0;
        $.each( data, function( key,value ) {
            if(key==0)
                var active = "active";

            $('.left-col div.naechste-events .list-group').append(
                "<a href=\""+value.url+"\" class=\"list-group-item "+active+"\">" +
                    "<p>"+value.title+"</p>" +
                    "<p class=\"list-group-item-text\"><small>"+value.date+"</small></p>" +
                "</a>"

            );
            console.log(value);
        });
    });





    /* Todos */
    $.getJSON( "/web/temp_jsons/todos-left-col.json", function( data ) {
        $.each( data, function( key,value ) {
            $('.left-col ul.todos').append("<li>"+value+"</li>");
        });
    });







    /************************************************************
     Typeahead Suche
     ************************************************************/

    var substringMatcher = function(strs) {
        return function findMatches(q, cb) {
            var matches, substringRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function(i, str) {
                if (substrRegex.test(str)) {
                    // the typeahead jQuery plugin expects suggestions to a
                    // JavaScript object, refer to typeahead docs for more info
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

    $('input.typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'states',
            displayKey: 'value',
            source: substringMatcher(states)
        });

})