jQuery(document).ready(function() {
    jQuery('.input-daterange-timepicker').daterangepicker({
        showDropdowns: true,
        timePicker: true,
        timePickerIncrement: 15,
        todayHighlight: true,
        locale: {
            format: 'DD-MM-YYYY h:mm A',
            separator: ' TO ',
        },
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-success',
        cancelClass: 'btn-default',
    });
    jQuery('#datepicker_start,#datepicker_end,#datepicker_start_edit,#datepicker_end_edit').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "yyyy-mm-dd"
    });
    // Time Picker
    jQuery('#timepicker_start,#timepicker_end,#timepicker_edit,#timepicker_edit').timepicker({
        defaultTIme: true,
        minuteStep: 15,
        showMeridian: false
    });
    // create event
    jQuery('#create_event').click(function() {
        jQuery('#new-event-modal').modal('hide');
        jQuery("body").mask("Please wait while we prepare the calendar...");
        var data = jQuery('#new_event').serialize();
        jQuery.post('inc/service.php?controller=CALENDAR', data, function(info) {
            if (info != 0) {
                jQuery(".notification_count").show().html(info);
            }
            $('#calendar').fullCalendar('refetchEvents');
            setTimeout(function() {
                jQuery("body").unmask();
            }, 2000);
        });
    });
    // edit-event-modal event
    jQuery('#edit_event').click(function() {
        jQuery('#edit-event-modal').modal('hide');
        jQuery("body").mask("Please wait while we prepare the calendar...");
        var data = jQuery('#form_edit_event').serialize() + '&action=edit_event&controller=CALENDAR';
        jQuery.post('inc/service.php', data, function(info) {
            $('#calendar').fullCalendar('refetchEvents');
            setTimeout(function() {
                jQuery("body").unmask();
            }, 2000);
        });
    });
    // delete event
    jQuery('#delete_event').click(function() {
        jQuery('#edit-event-modal').modal('hide');
        jQuery("body").mask("Please wait while we prepare the calendar...");
        var data = jQuery('#form_edit_event').serialize() + '&action=delete_event&controller=CALENDAR';
        jQuery.post('inc/service.php', data, function(info) {
            if (info != 0) {
                jQuery(".notification_count").show().html(info);
            }
            $('#calendar').fullCalendar('refetchEvents');
            setTimeout(function() {
                jQuery("body").unmask();
            }, 2000);
        });
    });

});
