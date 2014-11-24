

$(document).ready(function() {

    /************************************************************
     LEFT-COL
     ************************************************************/


    /* Kalender */

    $.getJSON("/web/temp_jsons/calendar-left-col.json", function (data) {

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
            onClick: function(target, cell, date, data) {
                //console.log($(this));
                //alert...
            }
        });

    });





    /* Nächste Events */

    //wird neuerdings von kalender auf startseie erledigt

    $.getJSON( "/web/temp_jsons/next-events-left-col.json", function( data ) {
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
    $.getJSON( "/web/temp_jsons/todos-left-col.json", function( data ) {
        $.each( data, function( key,value ) {
            $('.left-col ul.todos').append("<li><a href=\""+value.url+"\">"+value.title+"</li>");
        });
    });







    /************************************************************
     Typeahead Suche
     ************************************************************/

    var matching_exp = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
        'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
        'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
        'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
        'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
        'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
        'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
        'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
        'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
    ];



    var substringMatcher = function(strs) {
        return function findMatches(q, cb) {


            $.getJSON( "temp_jsons/typeahead.json", function( data ) {
                matching_exp = data;
            });


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



    $('input.typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'matching_exp',
            displayKey: 'value',
            source: substringMatcher(matching_exp)
        });

})



