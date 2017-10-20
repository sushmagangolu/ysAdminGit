$('#date-range').datepicker({
    toggleActive: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    todayHighlight: true
});
$('body').on('change', '#checkAll', function(e) {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});
var date_col = lead_cols.length - 1;
var comment_col = lead_cols.length - 2;
/* Custom filtering function which will search data in column four between two values */
$.fn.dataTableExt.afnFiltering.push(
    function(oSettings, aData, iDataIndex) {
        var iFini = document.getElementById('start').value;
        var iFfin = document.getElementById('end').value;
        var created_at = date_col;
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
var creports = {
  getData:function(){
    $("body").mask("Please wait ...");
        if ($.fn.DataTable.isDataTable("#datatable-buttons")) {
            $('#datatable-buttons').DataTable().clear().destroy();
        }
    var myArray = [];
    $(":checkbox:checked").each(function() {
        myArray.push(this.value);
    });
    var params = 'source='+ myArray.join(",")+'&status='+$('#status').val().join(",");
console.log(params);
    var table = $("#datatable-buttons").DataTable({
        "processing": true,
        "fixedHeader": true,
        "orderCellsTop": true,
        "bSortCellsTop": true,
        "iDisplayLength": 10,
        "ajax": "inc/service.php?action=get_leads_V3_creports&"+params,
        "destroy":true,
        "columns": lead_cols,
        "createdRow": function(row, data, dataIndex) {
            var parent_name = '<a class="right-bar-toggle" onclick="leads.viewLead(' + data['lead_data']['form_id'] + ')" href="javascript:void(0)" >' + data['lead_data']['lead_json']['name'] + '</a>';
            $('td:eq(0)', row).html(parent_name);
            var moment_date = moment(data['lead_data']['created_at']).format("DD-MM-YYYY h:mm A");
            $('td:eq(' + date_col + ')', row).html(moment_date);
        },
        "columnDefs": [{
            "targets": [0],
            "bSortable": false
        }],
        "order": [
            [date_col, "desc"]
        ]
    });
    $('#datatable-buttons').DataTable().draw();
    /*var inputs = '<tr id="filterInputs"><td></td>';
    for (i = 1; i < lead_cols.length; i++) {
        inputs += '<td><input type="text" placeholder="' + lead_cols[i]['title'] + '" class="form-control input-sm"></td>';
    }
    inputs += '</tr>';
    $('#datatable-buttons').find('thead').append(inputs);
    // Apply the search
    table.columns().every(function(index) {
        $('#datatable-buttons thead tr:eq(1) td:eq(' + index + ') input').on('keyup', function() {
            table.column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
            $(this).focus();
            return false;
        });
    });*/
    $("body").unmask();
  },



}
