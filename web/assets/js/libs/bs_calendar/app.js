(function($) {

	"use strict";

	var options = {
		events_source: 'temp_jsons/events.json.php',
		view: 'month',
		tmpl_path: 'assets/js/libs/bs_calendar/tmpls/',
		tmpl_cache: false,
		day: '2013-03-20',
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


}(jQuery));