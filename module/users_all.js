//$(document).ready(function() {

$('body').on('change', '#checkAll', function(e) {
    //$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});

var table = $("#datatable-buttons").DataTable({
    dom: "Bfrtip",
    buttons: [{
        extend: "csv",
        className: "btn-sm",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
        }
    }, {
        extend: "excel",
        className: "btn-sm",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
        }
    }, {
        extend: "pdf",
        className: "btn-sm",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
        }
    }, {
        extend: "print",
        className: "btn-sm",
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
        }
    }, {
        className: "btn-sm btn-info reload_data",
        text: '<i class="fa fa-refresh"></i>'
    }],
    "processing": true,
    "fixedHeader": true,
    "orderCellsTop": true,
    "bSortCellsTop": true,
    "iDisplayLength": 10,

    "ajax": "inc/service.php?act=get_all_users&controller=USER",
    "columns": [{
        "title": 'Name',
        "data": 'user_data.userName',
        "defaultContent": "<i>N/A</i>"
    }, {
        "title": 'Email',
        "data": "user_data.userEmail",
        "defaultContent": "<i>N/A</i>"
    }, {
        "title": 'Phone',
        "data": "user_data.phone",
        "defaultContent": "<i>N/A</i>"
    }, {
        "title": 'Client Name',
        "data": "user_data.client_name",
        "defaultContent": "<i>N/A</i>"
    }, {
        "title": 'Status',
        "data": "user_data.user_active"
    }, {
        "title": 'Emails',
        "data": "user_data.email_notification"
    }, {
        "title": 'SMS',
        "data": "user_data.sms_notification",
    }, {
        "title": 'Reminders',
        "data": "user_data.email_reminders",
    }, {
        "title": 'Actions',
        "data": "user_data.email_reminders"
    }],

    "createdRow": function(row, data, dataIndex) {
      
      var dd = '<a href="profile.php?user_id=' + data['user_data']['userId'] + '" class="on-default edit-row" style="padding:5px;"><i class="fa fa-pencil"></i></a>';
        dd += '<a onclick="delete_user(' + data['user_data']['userId'] + ')" href="javascript:void(0);" class="on-default remove-row" style="padding:5px;color:red"><i class="fa fa-trash-o"></i></a>';
        $('td:eq(8)', row).html(dd);
    }
});
var inputs = '<tr>';
inputs += '<td><input type="text" placeholder="Name" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Email" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Phone" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Client Name" class="form-control input-sm"></td>';
inputs += '<td></td><td></td><td></td><td></td><td></td>';
inputs += '</tr>';
$('.datatable-buttons').find('thead').append(inputs);
// Apply the search
table.columns().every(function(index) {
    $('.datatable-buttons thead tr:eq(1) td:eq(' + index + ') input').on('keyup', function() {
        table.column($(this).parent().index() + ':visible')
            .search(this.value)
            .draw();
        $(this).focus();
        return false;
    });
});

if (import_button == 0) {
    $('.import_button').remove();
}


$('.close-right-bar').on('click', function(event) {
    $(".right-bar").hide();
    $('#wrapper').removeClass('right-bar-enabled');
});
// Event listener to the two range filtering inputs to redraw on input
// Add event listeners to the two range filtering inputs
$('#filter_dates').click(function() {
    table.draw();
});

$('#clear_filters').click(function() {
    $("#start").val('');
    $("#end").val('');
    table.draw();
});

$('.reload_data').click(function() {
    $("body").mask("Please wait while we reload the data...");
    //table.draw();
    table.ajax.reload();
    setTimeout(function() {
        $("body").unmask();
    }, 2000);
});

function delete_lead(id) {
    var md = '<div id="dialog" class="modal-block"><section class="panel panel-danger panel-color"><header class="panel-heading"><h2 class="panel-title">Are you sure?</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Are you sure that you want to delete this lead?</p></div></div><div class="row m-t-20"><div class="col-md-12 text-right"><button id="dialogConfirm" class="btn btn-danger waves-effect waves-light" onclick="leads.deleteLead(' + id + ')">Delete</button> <button id="dialogCancel" class="btn btn-default waves-effect popup-modal-dismiss">Cancel</button></div></div></div></section></div>';
    $.magnificPopup.open({
        items: {
            src: md,
            type: 'inline'
        },
        preloader: false,
        modal: true
    });
}

$('body').on('click', '#dialogCancel', function(e) {
    e.preventDefault();
    $.magnificPopup.close();
});

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
var fbleads = [];
leads = {
    init: function() {
        //this.prepareDetailForm();
    },
    prepareDetailForm: function() {
        $.getJSON("inc/service.php?action=getFormAndDetails", function(result) {
            //console.log(result);
        });
    },
    viewLead: function(id) {
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
                        form += '<textarea id="' + field['name'] + '" class="form-control" name="' + field['name'] + '">' + mappVal + '</textarea>';
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
            table.ajax.reload();
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
            table.ajax.reload(null, false);
            $.magnificPopup.close();
        });
    },
    getFacebookLeads: function() {
        //$("body").mask("Please wait...");
        var graphURL = 'https://graph.facebook.com/v2.8/' + facebook_page_id + '?fields=id,name,leadgen_forms{id,name,leads.limit(200)}&access_token=EAAQMRkcZCyEgBALF7ahWlaMggyRXtfyWXDEors0U7oM0zPmb48GNggm5duge8ZBMtQ0ZCnCR6w4GSjcImBPuHc9QZBf26BuKAkGF7E9wlZAJIpZB2GVUYZB2hZBzlhRZBXntaD61JP5ZACmgMh10wXwQvKVTBRmWgvrFKkN2RlTeSHgwZDZD';
        $.getJSON(graphURL, function(result) {
            var leads = [];
            if (result.hasOwnProperty('leadgen_forms')) {
                //console.log(result.leadgen_forms);
                if (result.leadgen_forms.hasOwnProperty('data')) {
                    //console.log(result.leadgen_forms.data.length);
                    for (var i = 0; i < result.leadgen_forms.data.length; i++) {
                        //console.log(result.leadgen_forms.data[i]);
                        if (result.leadgen_forms.data[i].hasOwnProperty('leads')) {
                            //console.log(result.leadgen_forms.data[i].leads);
                            if (result.leadgen_forms.data[i].leads.hasOwnProperty('data')) {
                                if (result.leadgen_forms.data[i].leads.data.length > 0) {
                                    for (var j = 0; j < result.leadgen_forms.data[i].leads.data.length; j++) {
                                        //leads.push(result.leadgen_forms.data[i].leads.data[j]);
                                        var obj = result.leadgen_forms.data[i].leads.data[j];
                                        var formName = result.leadgen_forms.data[i].name;
                                        var formId = result.leadgen_forms.data[i].id;
                                        if (obj.hasOwnProperty('field_data')) {
                                            obj.formName = formName;
                                            obj.formId = formId;
                                            leads.push(obj);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (leads.length > 0) {
                $.ajax({
                    url: "datalist/fb_leads.php?action=fb_leads",
                    type: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    data: JSON.stringify(leads),
                    success: function(info) {
                        //console.log(info);
                        table.ajax.reload();
                        $("body").unmask();
                    }
                });
            } else {
                $("body").unmask();
            }
        });
    },

    getFacebookLeads1: function(fbPageId) {
        var graphURL = 'https://graph.facebook.com/v2.8/' + fbPageId + '?fields=leadgen_forms{id,name}&access_token=EAAQMRkcZCyEgBALF7ahWlaMggyRXtfyWXDEors0U7oM0zPmb48GNggm5duge8ZBMtQ0ZCnCR6w4GSjcImBPuHc9QZBf26BuKAkGF7E9wlZAJIpZB2GVUYZB2hZBzlhRZBXntaD61JP5ZACmgMh10wXwQvKVTBRmWgvrFKkN2RlTeSHgwZDZD';
        $.getJSON(graphURL, function(result) {
            if (result.hasOwnProperty('leadgen_forms')) {
                if (result.leadgen_forms.hasOwnProperty('data') && result.leadgen_forms.data.length > 0) {
                    for (var i = 0; i < result.leadgen_forms.data.length; i++) {
                        var formName = result.leadgen_forms.data[i].name;
                        var formId = result.leadgen_forms.data[i].id;
                        leads.getFacebookLeadsv2(formName, formId);
                    }
                }
            }
        });
    },

    getFacebookLeadsv2: function(formName, formId) {
        $("body").mask("Please wait...");
        var graphURL = 'https://graph.facebook.com/v2.8/' + formId + '?fields=leads.limit(30)&access_token=EAAQMRkcZCyEgBALF7ahWlaMggyRXtfyWXDEors0U7oM0zPmb48GNggm5duge8ZBMtQ0ZCnCR6w4GSjcImBPuHc9QZBf26BuKAkGF7E9wlZAJIpZB2GVUYZB2hZBzlhRZBXntaD61JP5ZACmgMh10wXwQvKVTBRmWgvrFKkN2RlTeSHgwZDZD';
        $.getJSON(graphURL, function(result) {
            var leads = [];
            if (result.hasOwnProperty('leads')) {
                if (result.leads.hasOwnProperty('data') && result.leads.data.length > 0) {
                    for (var j = 0; j < result.leads.data.length; j++) {
                        var obj = result.leads.data[j];
                        if (obj.hasOwnProperty('field_data')) {
                            obj.formName = formName;
                            obj.formId = formId;
                            leads.push(obj);
                        }
                    }
                }
            }
            if (leads.length > 0) {
                $.ajax({
                    url: "datalist/fb_leads.php?action=fb_leads",
                    type: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    data: JSON.stringify(leads),
                    success: function(info) {
                        //table.ajax.reload();
                        $("body").unmask();
                    }
                });
            } else {
                $("body").unmask();
            }
        });

    }
}
if (lead_id != '') {
    leads.viewLead(lead_id);
}
/*if (facebook_page_id != 0) {
    leads.getFacebookLeads(facebook_page_id);
    if (client_id == 8) {
        leads.getFacebookLeads('114014655342806');
    }
}*/
//});
