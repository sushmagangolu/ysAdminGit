var table = $("#datatable-buttons").DataTable({
    dom: "Bfrtip",
    buttons: [{
            extend: "csv",
            className: "btn-sm",
            exportOptions: {
                columns: [1, 2, 3, 4]
            }
        }, {
            extend: "excel",
            className: "btn-sm",
            exportOptions: {
                columns: [1, 2, 3, 4]
            }
        }, {
            extend: "pdf",
            className: "btn-sm",
            exportOptions: {
                columns: [1, 2, 3, 4]
            }
        }, {
            extend: "print",
            className: "btn-sm",
            exportOptions: {
                columns: [1, 2, 3, 4]
            }
        }, {
            className: "btn-sm btn-info btn-purple add_client",
            text: 'Add Client'
        }, {
            className: "btn-sm btn-info reload_data",
            text: 'Reload Results'
        }],
    "processing": true,
    "iDisplayLength": 100,
    ajax: "datalist/list.php?module=clients",
    "columns": [{
            "orderable": false,
            "data": null,
            "defaultContent": '',
            "className": 'centered'
        }, {
            "data": 'client_name'
        }, {
            "data": "client_email"
        }, {
            "data": "client_phone"
        }, {
            "data": "date_created"
        }, {
            "data": "sms_notifications"
        }, {
            "data": "email_notifications"
        }, {
            "data": "client_active"
        }, {
            "data": null,
            "width": "15%"
        }],
    "createdRow": function (row, data, dataIndex) {
        $('td:eq(0)', row).html('<span><i class="mdi mdi-plus"></i></span>');
        var dd = '<a href="profile_admin.php?client_id=' + data['client_id'] + '" class="on-default edit-row" style="padding:5px;"><i class="fa fa-pencil"></i></a>';
        dd += '<a onclick="delete_client(' + data['client_id'] + ')" href="javascript:void(0);" class="on-default remove-row" style="padding:5px;color:red"><i class="fa fa-trash-o"></i></a>';
        dd += '<a href="build_client_form.php?client_name=' + data['client_name'] + '&client_id=' + data['client_id'] + '" class="on-default edit-row" style="padding:5px;"><i class="fa fa-industry"></i></a>';
        dd += "<a onclick=\"integration_code('" + data['access_code'] + "')\" href=\"javascript:void(0);\" class=\"on-default remove-row\" style=\"padding:5px;color:green\"><i class=\"fa fa-cog\"></i></a>";
        $('td:eq(8)', row).html(dd);
        var moment_date = moment(data['date_created']).format("DD-MM-YYYY");
        $('td:eq(4)', row).html(moment_date);
    },
    columnDefs: [{
            targets: [0, 8],
            "bSortable": false
        }],
    "order": [
        [4, "desc"]
    ]

});
var inputs = '<tr>';
inputs += '<td></td>';
inputs += '<td><input type="text" placeholder="Name" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Email" class="form-control input-sm"></td>';
inputs += '<td><input type="text" placeholder="Phone" class="form-control input-sm"></td>';
inputs += '<td></td>';
inputs += '<td></td><td></td><td></td><td></td>';
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
$('body').on('click', '#datatable-buttons td span i', function (e) {
    var tr = $(this).closest('tr');
    var row = table.row(tr);
    var icon = $(this);
    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        icon.removeClass('mdi-minus').addClass('mdi-plus');
        tr.removeClass('shown');
    } else {
        // Open this row
        icon.removeClass('mdi-plus').addClass('mdi-minus');
        row.child(leads.fnFormatDetails(row.data())).show();
        tr.addClass('shown');
    }
});
leads = {
    fnFormatDetails: function (d) {
        var sOut = '<table style="margin-bottom:0px;" class="table table-condensed table-bordered">';
        sOut += '<tr><td width="200px;">Name:</td><td>' + d.client_name + '</td></tr>';
        sOut += '<tr><td>Phone:</td><td>' + d.client_phone + '</td></tr>';
        sOut += '<tr><td>Email:</td><td>' + d.client_email + '</td></tr>';
        sOut += '<tr><td>Website:</td><td>' + d.client_website + '</td></tr>';
        sOut += '<tr><td>Primary Contact Name:</td><td>' + d.primary_contact_name + '</td></tr>';
        sOut += '<tr><td>Primary Contact Phone:</td><td>' + d.primary_contact_phone + '</td></tr>';
        sOut += '<tr><td>Address:</td><td>' + d.client_address + '</td></tr>';
        sOut += '<tr><td>Created Date:</td><td>' + d.date_created + '</td></tr>';
        sOut += '<tr><td>Access Code:</td><td>' + d.access_code + '</td></tr>';
        sOut += '</table>';
        return sOut;
    }
}
$('.reload_data').click(function () {
    $("body").mask("Please wait while we reload the data...");
    table.draw();
    setTimeout(function () {
        $("body").unmask();
    }, 2000);
});

function delete_client(id) {
    var md = '<div id="dialog" class="modal-block"><section class="panel panel-danger panel-color"><header class="panel-heading"><h2 class="panel-title">Are you sure?</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Are you sure that you want to delete this client?</p></div></div><div class="row m-t-20"><div class="col-md-12 text-right"><button id="dialogConfirm" class="btn btn-danger waves-effect waves-light" onclick="del(' + id + ')">Delete</button><button id="dialogCancel" class="btn btn-default waves-effect popup-modal-dismiss">Cancel</button></div></div></div></section></div>';
    $.magnificPopup.open({
        items: {
            src: md,
            type: 'inline'
        },
        preloader: false,
        modal: true
    });
}
function integration_code(id) {
    var md = '<div id="dialog" class="modal-block"><section class="panel panel-danger panel-color"><header class="panel-heading"><h2 class="panel-title">Client Integration Code</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text">';
        md +='<p><pre>$(".sign_up_button").click(function(){\n';
    md +='_8vData = {};\n';
	md +='_8vData["name"] = $("#PARENTNAME").val();\n';
	md +='_8vData["email"] = $("#EMAIL").val();\n';
	md +='_8vData["phone"] = $("#PHONENUMBER").val();\n';
	md +='_8vData["access_code"] = "'+id+'";\n';
	md +='_8vData["source"] = "Website";\n';
    md +='$.ajax({\n';
        md +=   '   type: "GET",\n';
        md +=   '   data: _8vData,\n';
        md +=   '   dataType: "jsonp",\n';
        md +=   '   url: "https://growtheye.com/lead_api.php"\n';
    md +='  });\n';
md +='});</pre></p>\n';
md +='</div></div><div class="row m-t-20"><div class="col-md-12 text-right"><button id="dialogCancel" class="btn btn-default waves-effect popup-modal-dismiss">Close</button></div></div></div></section></div>';
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

$('body').on('click', '.add_client', function (e) {
    e.preventDefault();
    $('#add_client').modal('show');
});

function del(client_id) {
    var data = "act=delete_client&client_id=" + client_id;
    $.post('inc/service.php?controller=CLIENT', data, function (info) {
        table.ajax.reload(null, false);
        $.magnificPopup.close();
    });
}
$(function () {
    $("#insertClient").click(function () {
        if ($('#newclient').parsley().validate()) {
            var data = $("#newclient").serialize();
            $.post('inc/service.php?controller=CLIENT', data, function (info) {
                if(info==0) {
                    $('#emailExists').show();
                    return;
                } else {
                    table.ajax.reload(null, false);
                    $('#add_client').modal('hide');
                }

            });
        }
    });
    $('#c_image').filer({
        limit: 1,
        maxSize: 3,
        extensions: ['jpg', 'jpeg', 'png', 'gif'],
        changeInput: true,
        showThumbs: true,
        addMore: false,
        uploadFile: {
            url: "assets/jquery.filer/php/upload.php",
            data: null,
            type: 'POST',
            enctype: 'multipart/form-data',
            beforeSend: function () {},
            success: function (data, el) {
                var imageData = $.parseJSON(data);
                $('#client_image').val(imageData['metas'][0]['name']);
            },
            error: function (el) {
                console.log('Error');
            },
            statusCode: null,
            onProgress: null,
            onComplete: null
        },
    });
});
