//$(document).ready(function() {

$('#date-range').datepicker({
    toggleActive: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    todayHighlight: true
});
$('body').on('change', '#checkAll', function(e) {
    //$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});


/* Custom filtering function which will search data in column four between two values */
$.fn.dataTableExt.afnFiltering.push(
    function(oSettings, aData, iDataIndex) {
        var iFini = document.getElementById('start').value;
        var iFfin = document.getElementById('end').value;
        var created_at = 7;
        if (iFini != '' && iFfin != '') {
            iFini = moment(iFini, 'DD-MM-YYYY').format("YYYY-MM-DD");
            iFfin = moment(iFfin, 'DD-MM-YYYY').format("YYYY-MM-DD");
        }

        iFini = iFini.substring(0, 5) + iFini.substring(5, 8) + iFini.substring(8, 10);
        iFfin = iFfin.substring(0, 5) + iFfin.substring(5, 8) + iFfin.substring(8, 10);

        var datofini = aData[created_at].substring(0, 5) + aData[created_at].substring(5, 8) + aData[created_at].substring(8, 10);
        var datoffin = aData[created_at].substring(0, 5) + aData[created_at].substring(5, 8) + aData[created_at].substring(8, 10);

        if (iFini === "" && iFfin === "") {
            return true;
        } else if (iFini <= datofini && iFfin === "") {
            return true;
        } else if (iFfin >= datoffin && iFini === "") {
            return true;
        } else if (iFini <= datofini && iFfin >= datoffin) {
            return true;
        }
        return false;
    }
);

var table = $("#datatable-buttons").DataTable({
    dom: "Bfrtip",
    buttons: [{
        extend: "csv",
        className: "btn-sm",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
        }
    }, {
        extend: "excel",
        className: "btn-sm",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
        }
    }, {
        extend: "pdf",
        className: "btn-sm",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
        }
    }, {
        extend: "print",
        className: "btn-sm",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 7]
        }
    }, {
        className: "btn-sm btn-danger lead_delete",
        text: 'Restore Data ',
        action: function(e, dt, node, config) {
            var results = $('#newform').serialize();
            if (results == '') {
                var md = '<div id="dialog" class="modal-block"><section class="panel panel-info panel-color"><header class="panel-heading"><h2 class="panel-title">Error!</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Please select the checkboxes from the data to restore records.</p></div></div><div class="row m-t-20"><div class="col-md-12 text-center"><button id="dialogCancel" class="btn btn-primary waves-effect popup-modal-dismiss">OK</button></div></div></div></section></div>';
                $.magnificPopup.open({
                    items: {
                        src: md,
                        type: 'inline'
                    },
                    preloader: false,
                    modal: true
                });
            } else {
                var md = '<div id="dialog" class="modal-block"><section class="panel panel-danger panel-color"><header class="panel-heading"><h2 class="panel-title">Are you sure?</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Are you sure that you want to restore this leads?</p></div></div><div class="row m-t-20"><div class="col-md-12 text-right"><button id="dialogConfirm" class="btn btn-danger waves-effect waves-light" onclick="leads.restoreLeads()">Restore</button> <button id="dialogCancel" class="btn btn-default waves-effect popup-modal-dismiss">Cancel</button></div></div></div></section></div>';
                $.magnificPopup.open({
                    items: {
                        src: md,
                        type: 'inline'
                    },
                    preloader: false,
                    modal: true
                });
            }
        }
    }, {
        className: "btn-sm btn-info reload_data",
        text: '<i class="fa fa-refresh"></i>'
    }],

    "processing": true,
    "fixedHeader": true,
    "bSortCellsTop": true,
    "iDisplayLength": 99,
    "ajax": "inc/service.php?action=get_trash",
    "columns": [{
        "title": '<input type="checkbox" value="CheckAll" name="chkall" id="checkAll" />',
        "data": null,
        "className": ''
    }, {
        "title": 'Name',
        "data": 'lead_data.lead_json.name',
        "className": 'parent_name cmt',
    }, {
        "title": 'Email',
        "data": "lead_data.lead_json.email",
        "className": 'cmt',
        "defaultContent": "<i>N/A</i>"
    }, {
        "title": 'Phone',
        "data": "lead_data.lead_json.phone",
        "defaultContent": "<i>N/A</i>"
    }, {
        "title": 'Source',
        "data": "lead_data.lead_json.source",
        "defaultContent": "<i>N/A</i>"
    }, {
        "title": 'Status',
        "data": "lead_data.lead_json.status",
        "defaultContent": "New Lead"
    }, {
        "title": 'Comment',
        "data": "comment.remark",
        "className": 'cmt parent_name',
        "defaultContent": "---"
    }, {
        "title": 'Date',
        "data": "lead_data.created_at"
    }],

    "createdRow": function(row, data, dataIndex) {
        $('td:eq(0)', row).html('<input name="prd[]" type="checkbox" value="' + data['lead_data']['form_id'] + '">');
        var moment_date = moment(data['lead_data']['created_at']).format("DD-MM-YYYY h:mm A");
        $('td:eq(7)', row).html(moment_date);
        var parent_name = '<a class="right-bar-toggle" onclick="leads.viewLead(' + data['lead_data']['form_id'] + ')" href="javascript:void(0)" >' + data['lead_data']['lead_json']['name'] + '</a>';
        $('td:eq(1)', row).html(parent_name);
        var source = data['lead_data']['lead_json']['source'];
        if (source == 'Facebook') {
            source = data['lead_data']['lead_json']['source'] + ' (' + data['lead_data']['fb_form_name'] + ')';
        }
        $('td:eq(4)', row).html(source);
        var cmt = data['comment']['remark'];
        var audio = '<audio src="' + data['lead_data']['lead_json']['message'] + '" controls=false>';
        //var audio = '<audio src="http://www.stephaniequinn.com/Music/Allegro%20from%20Duet%20in%20C%20Major.mp3" controls>';
        if (data['comment']['remark_type'] == 'audio') {
            $('td:eq(6)', row).html(audio);
        }
    },
    "columnDefs": [{
        "targets": [0],
        "bSortable": false
    }],
    "order": [
        [7, "desc"]
    ]
});

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
    table.ajax.reload(null, false);
    setTimeout(function() {
        $("body").unmask();
    }, 2000);
});

function restore_lead(id) {
    var md = '<div id="dialog" class="modal-block"><section class="panel panel-danger panel-color"><header class="panel-heading"><h2 class="panel-title">Are you sure?</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Are you sure that you want to delete this lead?</p></div></div><div class="row m-t-20"><div class="col-md-12 text-right"><button id="dialogConfirm" class="btn btn-danger waves-effect waves-light" onclick="leads.restoreLead(' + id + ')">Delete</button> <button id="dialogCancel" class="btn btn-default waves-effect popup-modal-dismiss">Cancel</button></div></div></div></section></div>';
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
leads = {
    init: function() {
        //this.prepareDetailForm();
    },
    prepareDetailForm: function() {
        $.getJSON("inc/service.php?action=getFormAndDetails", function(result) {
            console.log(result);
        });
    },
    viewLead: function(id) {
        $("#genLeadForm").empty();
        $(".right-bar").show();
        $('#wrapper').addClass('right-bar-enabled');
        $("#update_lead input, #update_lead textarea, #update_lead select").prop('disabled', true);
        $('.right-bar').mask('Please wait...');
        $.getJSON("inc/service.php?action=getFormAndDetails&leadId=" + id, function(result) {            var audio = '';
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
                        form += '<textarea id="' + field['name'] + '" class="form-control" name="' + field['name'] + '"readonly>' + mappVal + '</textarea>';                        if(mappVal.search("kservices.knowlarity.com") != -1) {
                            audio = '<tr><td>Audio Clip</td><td><audio src="' + mappVal+ '" controls=false></p></td></tr>';
                        }
                        break;
                    case 'date':
                        form += '<input type="datetime-local" class="form-control" value="' + mappVal + '" name="' + field['name'] + '" id="' + field['name'] + '" readonly>';
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
                form += '</td></tr>';                form += audio;
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
            table.ajax.reload(null, false);
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
    restoreLeads: function() {
        var results = $('#newform').serialize();
        var data = "action=restore_leads&" + results;
        $.post('inc/service.php', data, function(info) {
            table.ajax.reload(null, false);
            $.magnificPopup.close();
        });
    }
}
//leads.init();
//});
