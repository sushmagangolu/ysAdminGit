$('#input-datepicker').datepicker({
    toggleActive: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    todayHighlight: true,
    setDate: new Date(),
    defaultDate: new Date()
});
$('#input-timepicker').timepicker({
    defaultTIme: true,
    minuteStep: 15,
    showMeridian: false
});
$('.close-right-bar').on('click', function(event) {
    $(".right-bar").hide();
    $('#wrapper').removeClass('right-bar-enabled');
});
leads = {
    init: function() {
        //this.prepareDetailForm();
    },
    closeRightBar: function() {
        $(".right-bar").hide();
        $('#wrapper').removeClass('right-bar-enabled');
    },
    prepareDetailForm: function() {
        $.getJSON("inc/service.php?action=getFormAndDetails", function(result) {
            //console.log(result);
        });
    },
    viewLead: function() {
        var id = $("#event_lead_id").val();
        $("#genLeadForm").empty();
        $(".right-bar").show();
        $('#wrapper').addClass('right-bar-enabled');
        $("#update_lead input, #update_lead textarea, #update_lead select").prop('disabled', true);
        $('.right-bar').mask('Please wait...');
        $.getJSON("inc/service.php?action=getFormAndDetails&leadId=" + id, function(result) {
            $.each(result, function(i, field) {
                var mappVal = '';
                if (field.hasOwnProperty('mapped_value')) {
                    mappVal = field['mapped_value'];
                }
                var form = '';
                form += '<tr><td>' + field['label'] + '</td><td>';
                switch (field['type']) {
                    case 'text':
                        form += '<input type="text" class="form-control" value="' + mappVal + '" name="' + field['name'] + '" id="' + field['name'] + '">';
                        break;
                    case 'textarea':
                        form += '<textarea id="' + field['name'] + '" class="form-control" name="' + field['name'] + '"readonly>' + mappVal + '</textarea>';
                        break;
                    case 'date':
                        form += '<div class="' + field['className'] + ' input-group date  ' + field['name'] + '">';
                        form += '<input type="text" id="' + field['name'] + '" class="form-control" value="" name="' + field['name'] + '" />';
                        form += '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
                        break;
                    case 'select':
                        var options = '';
                        $.each(field['values'], function(i, option) {
                            var selected;
                            if (field['mapped_value'] == option['label']) {
                                selected = 'selected';
                            }
                            options += '<option value="' + option['label'] + '" ' + selected + ' >' + option['label'] + '</option>';
                        });
                        form += '<select class="form-control" name="' + field['name'] + '" id="' + field['name'] + '">';
                        form += options;
                        form += '</select>';
                        break;
                }
                form += '</td></tr>';
                $("#genLeadForm").append(form);
                if (field['type'] == 'date') {
                    if (mappVal != '') {
                        $('.' + field['name']).datetimepicker({
                            showClose: true
                        }).data("DateTimePicker").date(moment(mappVal));
                    } else {
                        $('.' + field['name']).datetimepicker({
                            showClose: true
                        });
                    }
                }
            });
            var leadId = '<input type="hidden" value="' + id + '" name="leadId" id="leadId">';
            $("#genLeadForm").append(leadId);
            leads.getRemarks();
        });
        setTimeout(function() {
            $('.right-bar').unmask();
        }, 1000);
    },
    editLead: function() {
        $('.right-bar').mask('Please wait...');
        var lead_json = ge.serializeObject($("#update_lead").serializeArray());
        var data = {
            action: 'edit_lead',
            geid: $("#leadId").val(),
            lead_json: JSON.stringify(lead_json)
        };
        $.post('inc/service.php', data, function(info) {
            setTimeout(function() {
                $('.right-bar').unmask();
            }, 1000);
        });
    },
    showCommentBox: function() {
        $("#new_comment").toggle();
    },
    addComment: function() {
        $('.right-bar').mask('Please wait..');
        var id = $('#leadId').val();
        var data = {};
        data['action'] = 'add_comment';
        data['comment'] = $('#comment_box').val();
        data['id'] = id;
        $.post('inc/service.php', data, function(info) {

            setTimeout(function() {
                $('#comment_box').val("");
                $('.right-bar').unmask();
            }, 1000);

        });
    },
    getRemarks: function() {
        $("#history").empty();
        $('.right-bar').mask('Please wait..');
        var id = $('#leadId').val();
        $.getJSON("inc/service.php?action=getRemarks&leadId=" + id, function(logs) {
            if (logs != 0) {
                $.each(logs, function(i, log) {
                    var taskList = '<tr><td style="line-height:1">';
                    taskList += '<p>' + log.remark + '</p>';
                    taskList += '<p style="text-align:right"><small>On ' + log.date_remark + '</small><small> By ' + log.userName + '</small></p>';
                    taskList += '</td></tr>';
                    $("#history").append(taskList + " ");
                });
            }
            $('.right-bar').unmask();
        });
    },
    addReminder: function() {
        $(".right-bar").mask("Please wait...");
        var data = {};
        data['action'] = 'add_reminder';
        data['id'] = $('#leadId').val();
        data['daterange'] = $('#input-datepicker').val() + ' ' + $('#input-timepicker').val() + ':00TO' + $('#input-datepicker').val() + ' ' + $('#input-timepicker').val() + ':00';
        data['event_title'] = $('#event_title').val();
        data['alert_before'] = $('#alert_before').val();
        $.post('inc/service.php', data, function(info) {
            $('#calendar').fullCalendar('refetchEvents');
            ge.notificationsCount();
            setTimeout(function() {
                $('#event_title').val("");
                $(".right-bar").unmask();
            }, 1000);
        });
    },
    deleteLeads: function() {
        var results = $('#newform').serialize();
        var data = "action=delete_leads&" + results;
        $.post('inc/service.php', data, function(info) {
            $.magnificPopup.close();
        });
    }
}
