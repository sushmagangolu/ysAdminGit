//$(document).ready(function() {
$('#date-range').datepicker({
    toggleActive: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    todayHighlight: true
});

$("#checkAll").change(function() {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});

/* Custom filtering function which will search data in column four between two values */
$.fn.dataTableExt.afnFiltering.push(
    function(oSettings, aData, iDataIndex) {
        var iFini = document.getElementById('start').value;
        var iFfin = document.getElementById('end').value;
        var created_at = 6;
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
            columns: [2, 3, 4, 5, 6, 7, 8, 10]
        }
    }, {
        extend: "excel",
        className: "btn-sm",
        exportOptions: {
            columns: [2, 3, 4, 5, 6, 7, 8, 10]
        }
    }, {
        extend: "pdf",
        className: "btn-sm",
        exportOptions: {
            columns: [2, 3, 4, 5, 6, 7, 8, 10]
        }
    }, {
        extend: "print",
        className: "btn-sm",
        exportOptions: {
            columns: [2, 3, 4, 5, 6, 7, 8, 10]
        }
    }, {
        className: "btn-sm btn-danger",
        text: 'Restore Records',
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
                var md = '<div id="dialog" class="modal-block"><section class="panel panel-danger panel-color"><header class="panel-heading"><h2 class="panel-title">Are you sure?</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Are you sure that you want to restore this leads?</p></div></div><div class="row m-t-20"><div class="col-md-12 text-right"><button id="dialogConfirm" class="btn btn-danger waves-effect waves-light" onclick="restore_leads()">Restore</button> <button id="dialogCancel" class="btn btn-default waves-effect popup-modal-dismiss">Cancel</button></div></div></div></section></div>';
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
        className: "btn-sm btn-purple",
        text: 'Send <i class="fa fa-envelope"></i>',
        action: function(e, dt, node, config) {
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
                setTimeout(function() {
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
    "bSortCellsTop": true,
    "iDisplayLength": 99,
    "ajax": "datalist/list.php?module=trash",
    "columns": [{
        "orderable": false,
        "data": null,
        "defaultContent": '',
        "className": 'centered'
    }, {
        "data": null,
        "className": 'centered'
    }, {
        "data": 'parent_name',
        "className": 'parent_name'
    }, {
        "data": "phone"
    }, {
        "data": "status"
    }, {
        "data": "source"
    }, {
        "data": 'created_at'
    }, {
        "data": "student_name"
    }, {
        "data": "class"
    }, {
        "data": null
    }, {
        "data": "email"
    }],
    "createdRow": function(row, data, dataIndex) {
        $('td:eq(0)', row).html('<span><i class="mdi mdi-plus"></i></span>');
        $('td:eq(1)', row).html('<input name="prd[]" type="checkbox" value="' + data['lead_id'] + '">');
        var moment_date = moment(data['created_at']).format("DD-MM-YYYY");
        $('td:eq(6)', row).html(moment_date);
        var dd='';
        
            dd += '<a onclick="restore_lead(' + data['lead_id'] + ')" href="javascript:void(0);" class="on-default remove-row" style="padding:5px;color:red"><i class="fa fa-repeat"></i></a>';
            dd += '<a href="viewlead.php?frompage=trash&leadid=' + data['lead_id'] + '" class="on-default detail-row" style="padding:5px;"><i class="fa  fa-eye"></i></a>';
        $('td:eq(8)', row).html(dd);

        var parent_name = '<a href="viewlead.php?leadid=' + data['lead_id'] + '" >' + data['parent_name'] + '</a>';
        $('td:eq(2)', row).html(parent_name);
    },
    columnDefs: [{
        targets: [0, 1, 9],
        "bSortable": false
    }, {
        "targets": [7, 10],
        "visible": false,
        "searchable": false
    }],
    "order": [
        [6, "desc"]
    ]

});

$('body').on('click', '#datatable-buttons td span i', function(e) {
    var tr = $(this).closest('tr');
    var row = table.row(tr);
    var icon = $(this);
    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        //this.src = "assets/images/plus1.png";
        icon.removeClass('mdi-minus').addClass('mdi-plus');
        //this.html('<i class="mdi mdi-plus"></i>');
        tr.removeClass('shown');
    } else {
        // Open this row
        //this.src = "assets/images/minus1.png";
        //this.html('<i class="mdi mdi-minus"></i>');
        icon.removeClass('mdi-plus').addClass('mdi-minus');
        row.child(leads.fnFormatDetails(row.data())).show();
        tr.addClass('shown');
    }
});
leads = {
        fnFormatDetails: function(d) {
            var sOut = '<table style="margin-bottom:0px;" class="table table-condensed table-bordered">';
            sOut += '<tr><td width="100px;">Parent Name:</td><td>' + d.parent_name + '</td></tr>';
            sOut += '<tr><td>Student Name:</td><td>' + d.student_name + '</td></tr>';
            sOut += '<tr><td>Class:</td><td>' + d.class + '</td></tr>';
            sOut += '<tr><td>Phone:</td><td>' + d.phone + '</td></tr>';
            sOut += '<tr><td>Email:</td><td>' + d.email + '</td></tr>';
            sOut += '<tr><td>Status:</td><td>' + d.status + '</td></tr>';
            sOut += '<tr><td>Source:</td><td>' + d.source + '</td></tr>';
            sOut += '<tr><td>Created Date:</td><td>' + moment(d.created_at).format("DD-MM-YYYY"); + '</td></tr>';
            sOut += '</table>';
            return sOut;
        }
    }
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
    table.ajax.reload();
    setTimeout(function() {
        $("body").unmask();
    }, 2000);
});

function restore_lead(id) {
    var md = '<div id="dialog" class="modal-block"><section class="panel panel-info panel-color"><header class="panel-heading"><h2 class="panel-title">Are you sure?</h2></header><div class="panel-body"><div class="modal-wrapper"><div class="modal-text"><p>Are you sure that you want to restore this lead?</p></div></div><div class="row m-t-20"><div class="col-md-12 text-right"><button id="dialogConfirm" class="btn btn-primary waves-effect waves-light" onclick="restore(' + id + ')">Confirm</button> <button id="dialogCancel" class="btn btn-default waves-effect popup-modal-dismiss">Cancel</button></div></div></div></section></div>';
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

function restore(leadid) {
    var data = "act=restore_lead&id=" + leadid;
    $.post('datalist/trash.php', data, function(info) {
        table.ajax.reload(null, false);
        $.magnificPopup.close();
    });
}
function restore_leads() {
    var results = $('#newform').serialize();
    var data = "act=restore_leads&" + results;
    $.post('datalist/trash.php', data, function(info) {
        table.ajax.reload(null, false);
        $.magnificPopup.close();
    });
}

//});
