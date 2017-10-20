// Data
var table = $("#datatable-buttons").DataTable({
    dom: "Bfrtip",
    buttons: [{
            className: "btn-sm btn-info btn-purple add_trigger",
            text: 'Add SMS Trigger'
        }],
    "processing": true,
    "iDisplayLength": 100,
    ajax: "datalist/list.php?module=smstriggers",
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
        var trigger_name = '<a class="add_trigger" onclick="smsTrigger.getDetails(' + data['trigger_id'] + ')" href="javascript:void(0)" >' + data['trigger_name'] + '</a>';
        $('td:eq(0)', row).html(trigger_name);
        $('td:eq(3)', row).html((data['send_to_customer'] == 1) ? 'Yes' : 'No');
        var moment_date = moment(data['branch_created']).format("DD-MM-YYYY h:mm A");
        $('td:eq(4)', row).html(moment_date);
        var dd = '<a onclick="smsTrigger.del(' + data['trigger_id'] + ')" href="javascript:void(0);" class="on-default remove-row" style="padding:5px;color:red"><i class="fa fa-trash-o"></i></a>';
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
    $("#smsTriggerForm")[0].reset();
    $("#smsTriggerForm").find("#tid").remove();
    $(".smsright-bar").show();
    $('#wrapper').addClass('smsright-bar-enabled');
});

$('body').on('click', '#bclose', function (e) {
    e.preventDefault();
    $("#smsTriggerForm").find("#tid").remove();
    $("#smsTriggerForm")[0].reset();
    $(".smsright-bar").hide();
    $('#wrapper').removeClass('smsright-bar-enabled');
});
$('input[type="checkbox"]').click(function(){
     $('#csms').toggle();
});
ge.getUsers('trigger_users');
ge.getStatus('trigger_status');

var smsTrigger = {
    manage: function () {
        if ($('#smsTriggerForm').parsley().validate()) {
            $(".smsright-bar").mask("Please wait..");
            var data = $("#smsTriggerForm").serialize();
            $.post('inc/service.php', data, function (info) {
                table.ajax.reload(null, false);
                setTimeout(function () {
                    if ($('#smsTriggerForm #tid').val() == 'undefined') {
                        $('#smsTriggerForm')[0].reset();
                    }
                    $(".smsright-bar").unmask();
                }, 1000);
            });
        }
    },
    getDetails: function (id) {
        $("#smsTriggerForm").find("#tid").remove();
        var params = {
            'controller': 'TRIGGER',
            'tid': id,
            'action': 'get_details'
        }
        $.getJSON("inc/service.php", params, function (data) {
            $('#smsTriggerForm #trigger_name').val(data.trigger_name);
            $('#smsTriggerForm #status_ge').val(data.trigger_status);
            $('#smsTriggerForm #users_ge').val(data.trigger_send_to);
            $('#smsTriggerForm #trigger_content').val(data.trigger_content);
            $('#smsTriggerForm #trigger_content_customer').val(data.trigger_content_customer);
            $('#smsTriggerForm #send_to_customer').prop('checked', data.send_to_customer == 1 ? true : false);
            var tid = '<input type="hidden" value="' + data['trigger_id'] + '" name="tid" id="tid">';
            $("#smsTriggerForm").append(tid);
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
