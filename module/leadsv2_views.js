//$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});

$('#date-range').datepicker({
    toggleActive: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    todayHighlight: true
});
$('body').on('change', '#checkAll', function (e) {
    //$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});


/* Custom filtering function which will search data in column four between two values */
$.fn.dataTableExt.afnFiltering.push(
        function (oSettings, aData, iDataIndex) {
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
                columns: [1, 2, 3, 4, 5, 6, 7]
            }
        }, {
            className: "btn-sm btn-danger lead_delete",
            text: '<i class="fa fa-trash"></i> ',
            action: function (e, dt, node, config) {
                var results = $('#newform').serialize();
                if (results == '') {
                    var md = '<div id="dialog" class="modal-block"><section class="panel panel-info panel-color"><header class="panel-heading"><h2 class="panel-title">Error!</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Please select the checkboxes from the data to delete records.</p></div></div><div class="row m-t-20"><div class="col-md-12 text-center"><button id="dialogCancel" class="btn btn-primary waves-effect popup-modal-dismiss">OK</button></div></div></div></section></div>';
                    $.magnificPopup.open({
                        items: {
                            src: md,
                            type: 'inline'
                        },
                        preloader: false,
                        modal: true
                    });
                } else {
                    var md = '<div id="dialog" class="modal-block"><section class="panel panel-danger panel-color"><header class="panel-heading"><h2 class="panel-title">Are you sure?</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Are you sure that you want to delete this leads?</p></div></div><div class="row m-t-20"><div class="col-md-12 text-right"><button id="dialogConfirm" class="btn btn-danger waves-effect waves-light" onclick="leads.deleteLeads()">Delete</button> <button id="dialogCancel" class="btn btn-default waves-effect popup-modal-dismiss">Cancel</button></div></div></div></section></div>';
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
            className: "btn-sm btn-purple campaign_email hidden",
            text: '<i class="fa fa-envelope"></i>',
            action: function (e, dt, node, config) {
                $("body").mask("Please wait while we prepare the data...");
                var results = $('#newform').serialize();
                if (results == '') {
                    $("body").unmask();
                    var md = '<div id="dialog" class="modal-block"><section class="panel panel-info panel-color"><header class="panel-heading"><h2 class="panel-title">Error!</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Please select the checkboxes from the data to send emails.</p></div></div><div class="row m-t-20"><div class="col-md-12 text-center"><button id="dialogCancel" class="btn btn-primary waves-effect popup-modal-dismiss">OK</button></div></div></div></section></div>';
                    $.magnificPopup.open({
                        items: {
                            src: md,
                            type: 'inline'
                        },
                        preloader: false,
                        modal: true
                    });
                } else {
                    setTimeout(function () {
                        $('#newform').submit();
                        $("body").unmask();
                    }, 2000);
                }
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
    "ajax": "inc/service.php?action=get_leads",
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
            "className": 'cmt'
        }, {
            "title": 'Phone',
            "data": "lead_data.lead_json.phone"
        }, {
            "title": 'Source',
            "data": "lead_data.lead_json.source"
        }, {
            "title": 'Status',
            "data": "lead_data.lead_json.status"
        }, {
            "title": 'Comment',
            "data": "comment",
            "className": 'cmt'
        }, {
            "title": 'Date',
            "data": "lead_data.created_at"
        }],

    "createdRow": function (row, data, dataIndex) {
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


        var comment_block = '<a href="" title="'+ data['comment'] + '">'+ data['comment'] + '</a>';


        $('td:eq(6)', row).html(comment_block);

    },
    "columnDefs": [{
            "targets": [0],
            "bSortable": false
        }],
    "order": [
        [7, "desc"]
    ]
});
var inputs = '<tr id="filterInputs" ><td></td>';
inputs += '<td><input type="text" placeholder="Name" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Email" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Phone" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Source" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Status" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Comment" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Date" class="form-control input-sm"></td><tr>';
$('#datatable-buttons').find('thead').append(inputs);
$("#datatable-buttons thead #filterInputs input").on('keyup change', function () {
    table
            .column($(this).parent().index() + ':visible')
            .search(this.value)
            .draw();
    this.focus();
});
function stopPropagation(evt) {
    if (evt.stopPropagation !== undefined) {
        evt.stopPropagation();
    } else {
        evt.cancelBubble = true;
    }
}
if (import_button == 0) {
    $('.import_button').remove();
}


$('.close-right-bar').on('click', function (event) {
    $(".right-bar").hide();
    $('#wrapper').removeClass('right-bar-enabled');
});
// Event listener to the two range filtering inputs to redraw on input
// Add event listeners to the two range filtering inputs
$('#filter_dates').click(function () {
    table.draw();
});

$('#clear_filters').click(function () {
    $("#start").val('');
    $("#end").val('');
    table.draw();
});

$('.reload_data').click(function () {
    $("body").mask("Please wait while we reload the data...");
    //table.draw();
    table.ajax.reload();
    setTimeout(function () {
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

$('body').on('click', '#dialogCancel', function (e) {
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
    init: function () {
        //this.prepareDetailForm();
    },
    prepareDetailForm: function () {
        $.getJSON("inc/service.php?action=getFormAndDetails", function (result) {
            //console.log(result);
        });
    },
    viewLead: function (id) {
        $("#genLeadForm").empty();
        $(".right-bar").show();
        $('#wrapper').addClass('right-bar-enabled');
        $("#update_lead input, #update_lead textarea, #update_lead select").prop('disabled', true);
        $('.right-bar').mask('Please wait...');
        $.getJSON("inc/service.php?action=getFormAndDetails&leadId=" + id, function (result) {
            $.each(result, function (i, field) {
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
                        form += '<textarea id="' + field['name'] + '" class="form-control" name="' + field['name'] + '"readonly>'+mappVal+'</textarea>';
                        break;
                    case 'select':
                        var options = '';
                        $.each(field['values'], function (i, option) {
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
        setTimeout(function () {
            $('.right-bar').unmask();
        }, 1000);
    },
    editLead: function () {
        $('.right-bar').mask('Please wait...');
        var lead_json = ge.serializeObject($("#update_lead").serializeArray());
        var data = {
            action: 'edit_lead',
            geid: $("#leadId").val(),
            lead_json: JSON.stringify(lead_json)
        };
        $.post('inc/service.php', data, function (info) {
            table.ajax.reload();
            setTimeout(function () {
                $('.right-bar').unmask();
            }, 1000);
        });
    },
    showCommentBox: function () {
        $("#new_comment").toggle();
    },
    addComment: function () {
        $('.right-bar').mask('Please wait..');
        var id = $('#leadId').val();
        var data = {};
        data['action'] = 'add_comment';
        data['comment'] = $('#comment_box').val();
        data['id'] = id;
        $.post('inc/service.php', data, function (info) {
            setTimeout(function () {
                $('#comment_box').val("");
                $('.right-bar').unmask();
            }, 1000);

        });
    },
    getRemarks: function () {
        $("#history").empty();
        $('.right-bar').mask('Please wait..');
        var id = $('#leadId').val();
        $.getJSON("inc/service.php?action=getRemarks&leadId=" + id, function (logs) {
            if (logs != 0) {
                $.each(logs, function (i, log) {
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
    addReminder: function () {
        $(".right-bar").mask("Please wait...");
        var data = {};
        data['action'] = 'add_reminder';
        data['id'] = $('#leadId').val();
        data['daterange'] = $('#input-datepicker').val() + ' ' + $('#input-timepicker').val() + ':00TO' + $('#input-datepicker').val() + ' ' + $('#input-timepicker').val() + ':00';
        data['event_title'] = $('#event_title').val();
        data['alert_before'] = $('#alert_before').val();
        $.post('inc/service.php', data, function (info) {
            setTimeout(function () {
                $('#event_title').val("");
                $(".right-bar").unmask();
            }, 1000);
        });
    },
    deleteLeads: function () {
        var results = $('#newform').serialize();
        var data = "action=delete_leads&" + results;
        $.post('inc/service.php', data, function (info) {
            table.ajax.reload(null, false);
            $.magnificPopup.close();
        });
    },
    getFacebookLeads: function () {
        //$("body").mask("Please wait...");
        var graphURL = 'https://graph.facebook.com/v2.8/' + facebook_page_id + '?fields=id,name,leadgen_forms{id,name,leads.limit(200)}&access_token=EAAQMRkcZCyEgBALF7ahWlaMggyRXtfyWXDEors0U7oM0zPmb48GNggm5duge8ZBMtQ0ZCnCR6w4GSjcImBPuHc9QZBf26BuKAkGF7E9wlZAJIpZB2GVUYZB2hZBzlhRZBXntaD61JP5ZACmgMh10wXwQvKVTBRmWgvrFKkN2RlTeSHgwZDZD';
        $.getJSON(graphURL, function (result) {
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
                    success: function (info) {
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

    getFacebookLeads1: function (fbPageId) {
        var graphURL = 'https://graph.facebook.com/v2.8/' + fbPageId + '?fields=leadgen_forms{id,name}&access_token=EAAQMRkcZCyEgBALF7ahWlaMggyRXtfyWXDEors0U7oM0zPmb48GNggm5duge8ZBMtQ0ZCnCR6w4GSjcImBPuHc9QZBf26BuKAkGF7E9wlZAJIpZB2GVUYZB2hZBzlhRZBXntaD61JP5ZACmgMh10wXwQvKVTBRmWgvrFKkN2RlTeSHgwZDZD';
        $.getJSON(graphURL, function (result) {
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

    getFacebookLeadsv2: function (formName, formId) {
        $("body").mask("Please wait...");
        var graphURL = 'https://graph.facebook.com/v2.8/' + formId + '?fields=leads.limit(30)&access_token=EAAQMRkcZCyEgBALF7ahWlaMggyRXtfyWXDEors0U7oM0zPmb48GNggm5duge8ZBMtQ0ZCnCR6w4GSjcImBPuHc9QZBf26BuKAkGF7E9wlZAJIpZB2GVUYZB2hZBzlhRZBXntaD61JP5ZACmgMh10wXwQvKVTBRmWgvrFKkN2RlTeSHgwZDZD';
        $.getJSON(graphURL, function (result) {
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
                    success: function (info) {
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
