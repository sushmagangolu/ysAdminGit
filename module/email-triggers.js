// Data
var table = $("#datatable-buttons").DataTable({
    dom: "Bfrtip",
    buttons: [{
            className: "btn-sm btn-info btn-purple add_trigger",
            text: 'Add Email Trigger'
        }],
    "processing": true,
    "iDisplayLength": 100,
    ajax: "datalist/list.php?module=emailtriggers",
    "columns": [{
            "title": 'Name',
            "data": 'trigger_name'
        }, {
            "title": 'Status Change',
            "data": "trigger_status"
        }, {
            'title': 'Send To',
            "data": "userEmail"
        }, {
            'title': 'Send To Customer',
            "data": "send_to_customer"
        }, {
            'title': 'Created On',
            "data": "trigger_created_at"
        }, {
            'title': 'Actions',
            "data": null
        }],
    "createdRow": function (row, data, dataIndex) {
        var trigger_name = '<a class="add_trigger" onclick="emailTrigger.getDetails(' + data['trigger_id'] + ')" href="javascript:void(0)" >' + data['trigger_name'] + '</a>';
        $('td:eq(0)', row).html(trigger_name);
        $('td:eq(3)', row).html((data['send_to_customer'] == 1) ? 'Yes' : 'No');
        var moment_date = moment(data['branch_created']).format("DD-MM-YYYY h:mm A");
        $('td:eq(4)', row).html(moment_date);
        var dd = '<a onclick="emailTrigger.del(' + data['trigger_id'] + ')" href="javascript:void(0);" class="on-default remove-row" style="padding:5px;color:red"><i class="fa fa-trash-o"></i></a>';
        $('td:eq(5)', row).html(dd);
    },
    columnDefs: [{
            targets: [5],
            "bSortable": false
        }],
    "order": [
        [0, "asc"]
    ]
});

$('body').on('click', '.add_trigger', function (e) {
    e.preventDefault();
    $("#emailTriggerForm")[0].reset();
    $("#emailTriggerForm").find("#tid").remove();
    $(".emailright-bar").show();
    $('#wrapper').addClass('emailright-bar-enabled');
});

$('body').on('click', '#bclose', function (e) {
    e.preventDefault();
    $("#emailTriggerForm").find("#tid").remove();
    $("#emailTriggerForm")[0].reset();
    $(".emailright-bar").hide();
    $('#wrapper').removeClass('emailright-bar-enabled');
});
$('input[type="checkbox"]').click(function(){
     $('#cemail').toggle();
});
ge.getUsers('email_trigger_users');
ge.getStatus('email_trigger_status');
$('#emailTriggerForm #trigger_content, #emailTriggerForm #trigger_content_customer').summernote({
    height: 250
});
var emailTrigger = {
    manage: function () {
        if ($('#emailTriggerForm').parsley().validate()) {
            $(".emailright-bar").mask("Please wait..");
            var data = $("#emailTriggerForm").serialize();
            $.post('inc/service.php', data, function (info) {
                table.ajax.reload(null, false);
                setTimeout(function () {
                    if ($('#emailTriggerForm #tid').val() == 'undefined') {
                        $('#emailTriggerForm')[0].reset();
                    }
                    $(".emailright-bar").unmask();
                }, 1000);
            });
        }
    },
    getDetails: function (id) {
        $("#emailTriggerForm").find("#tid").remove();
        var params = {
            'controller': 'TRIGGER',
            'tid': id,
            'action': 'get_details'
        }
        $.getJSON("inc/service.php", params, function (data) {
            $('#emailTriggerForm #trigger_name').val(data.trigger_name);
            $('#emailTriggerForm #status_ge').val(data.trigger_status);
            $('#emailTriggerForm #users_ge').val(data.trigger_send_to);
            $('#emailTriggerForm #trigger_subject').val(data.trigger_subject);
            $("#emailTriggerForm #trigger_content").summernote("code", data.trigger_content);
            $("#emailTriggerForm #trigger_content_customer").summernote("code", data.trigger_content_customer);
            $('#emailTriggerForm #send_to_customer').prop('checked', data.send_to_customer == 1 ? true : false);
            var tid = '<input type="hidden" value="' + data['trigger_id'] + '" name="tid" id="tid">';
            $("#emailTriggerForm").append(tid);
        });
    },
    del: function (id) {
        var params = {
            'controller': 'TRIGGER',
            'tid': id,
            'action': 'delete'
        }
        $.post("inc/service.php", params, function (data) {
            table.ajax.reload(null, false);
        });
    }
}
