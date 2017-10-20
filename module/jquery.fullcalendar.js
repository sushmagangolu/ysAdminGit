! function($) {
    "use strict";

    var CalendarApp = function() {
        this.$body = $("body")
        this.$modal = $('#event-modal'),
            this.$newEventModal = $('#new-event-modal'),
            this.$editEventModal = $('#edit-event-modal'),
            this.$event = ('#external-events div.external-event'),
            this.$calendar = $('#calendar'),
            this.$saveCategoryBtn = $('.save-category'),
            this.$categoryForm = $('#add-category form'),
            this.$extEvents = $('#external-events'),
            this.$calendarObj = null
    };


    /* on drop */
    CalendarApp.prototype.onDrop = function(eventObj, date) {
            var $this = this;
            // retrieve the dropped element's stored Event Object
            var originalEventObject = eventObj.data('eventObject');
            var $categoryClass = eventObj.attr('data-class');
            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);
            // assign it the date that was reported
            copiedEventObject.start = date;
            if ($categoryClass)
                copiedEventObject['className'] = [$categoryClass];
            // render the event on the calendar
            $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                eventObj.remove();
            }
        },
        /* on click on event */
        CalendarApp.prototype.onEventClick = function(calEvent, jsEvent, view) {
            var $this = this;
            var form = $("<form></form>");
            this.$editEventModal.modal({
                backdrop: 'static'
            });
            var start_date_time = moment(calEvent.start).format("DD-MM-YYYY hh:mm A");
            var end_date_time = moment(calEvent.end).format("DD-MM-YYYY hh:mm A");

            var date_range = start_date_time + ' TO ' + end_date_time;

            this.$editEventModal.find("input[name='daterange_edit']").val(date_range);
            this.$editEventModal.find("input[name='event_id']").val(calEvent.id);
            this.$editEventModal.find("input[name='event_lead_id']").val(calEvent.lead_id);
            this.$editEventModal.find("input[name='event_title']").val(calEvent.title);
            this.$editEventModal.find("select option[value='" + calEvent.alert_before + "']").attr("selected", true);
            if (calEvent.lead_id != 0) {
                this.$editEventModal.find("button[id='lead_button']").show();
            } else {
                this.$editEventModal.find("button[id='lead_button']").hide();
            }


            /*this.$editEventModal.find('.delete-event').show().end().find('.save-event').hide().end().find('.delete-event').unbind('click').click(function() {
                $this.$calendarObj.fullCalendar('removeEvents', function(ev) {
                    return (ev._id == calEvent._id);
                });
                this.$editEventModal.modal('hide');
            });
            this.$editEventModal.find('form').on('submit', function() {
                calEvent.title = form.find("input[type=text]").val();
                $this.$calendarObj.fullCalendar('updateEvent', calEvent);
                this.$editEventModal.modal('hide');
                return false;
            }); */
        },
        /* on select */
        CalendarApp.prototype.onSelect = function(start, end, allDay) {
            var $this = this;
            this.$newEventModal.modal({
                backdrop: 'static'
            });
            var form = $("<form></form>");

            this.$newEventModal.find('.delete-event').hide().end().find('.save-event').show().end().find('.save-event').unbind('click').click(function() {
                form.submit();
            });

            var start_date_time = moment(start).format("DD-MM-YYYY hh:mm A");
            var end_date_time = moment(end).format("DD-MM-YYYY hh:mm A");

            var date_range = start_date_time + ' TO ' + end_date_time;
            this.$newEventModal.find("input[name='daterange']").val(date_range);
            //this.$newEventModal.find("input[name='start_time']").val(start_date);

            /*  this.$newEventModal.find('form').on('submit', function () {
                  var title = form.find("input[name='title']").val();
                  var beginning = form.find("input[name='beginning']").val();
                  var ending = form.find("input[name='ending']").val();
                  var categoryClass = form.find("select[name='category'] option:checked").val();
                  if (title !== null && title.length != 0) {
                      $this.$calendarObj.fullCalendar('renderEvent', {
                          title: title,
                          start:start,
                          end: end,
                          allDay: false,
                          className: categoryClass
                      }, true);
                      this.$newEventModal.modal('hide');
                  }
                  else{
                      alert('You have to give a title to your event');
                  }
                  return false;

              }); */
            $this.$calendarObj.fullCalendar('unselect');
        },
        /* on click on event */
        CalendarApp.prototype.eventMouseover = function(calEvent, jsEvent, view) {
            var start_date_time = moment(calEvent.start).format("DD-MM-YYYY hh:mm A");
            var end_date_time = moment(calEvent.end).format("DD-MM-YYYY hh:mm A");
            var title = calEvent.title;
            $('.tooltip-html').tooltipster({
                content: $('<p style="text-align:left;"><strong>' + title + '</strong> <br>From: ' + start_date_time + '<br>To: ' + end_date_time + '</p>'),
                minWidth: 300,
                maxWidth: 300,
                position: 'right'
            });
        },
        CalendarApp.prototype.enableDrag = function() {
            //init events
            $(this.$event).each(function() {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                };
                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);
                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            });
        }
    /* Initializing */
    CalendarApp.prototype.init = function() {

            //this.enableDrag();
            /*  Initialize the calendar  */
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            var form = '';
            var today = new Date($.now());
            var defaultEvents;

            var $this = this;
            $this.$calendarObj = $this.$calendar.fullCalendar({
                slotDuration: '00:15:00',
                /* If we want to split day time each 15minutes */
                minTime: '05:00:00',
                maxTime: '22:00:00',
                defaultView: 'month',
                handleWindowResize: true,
                height: $(window).height() - 200,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: {
                    url: 'inc/service.php?controller=CALENDAR&action=get_events',
                    type: 'POST', // Send post data
                    error: function() {
                        console.log('There was an error while fetching events.');
                    }
                },
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                eventLimit: true, // allow "more" link when too many events
                selectable: true,
                draggable: false,
                drop: function(date) {
                    $this.onDrop($(this), date);
                },
                select: function(start, end, allDay) {
                    $this.onSelect(start, end, allDay);
                },
                eventClick: function(calEvent, jsEvent, view) {
                    $this.onEventClick(calEvent, jsEvent, view);
                },
                eventMouseover: function(calEvent, jsEvent, view) {
                    //$this.eventMouseover(calEvent, jsEvent, view);

                }
            });



            //on new event
            this.$saveCategoryBtn.on('click', function() {
                var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
                var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
                if (categoryName !== null && categoryName.length != 0) {
                    $this.$extEvents.append('<div class="external-event bg-' + categoryColor + '" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="mdi mdi-checkbox-blank-circle m-r-10 vertical-middle"></i>' + categoryName + '</div>')
                    $this.enableDrag();
                }

            });
        },

        //init CalendarApp
        $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

}(window.jQuery),

//initializing CalendarApp
function($) {
    "use strict";
    $.CalendarApp.init()
}(window.jQuery);
