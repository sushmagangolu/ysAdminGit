    // Data
    var table = $("#datatable-buttons").DataTable({
        dom: "Bfrtip",
        buttons: [{
                className: "btn-sm btn-info btn-purple add_branch",
                text: 'Add Branch'
            }],
        "processing": true,
        "iDisplayLength": 100,
        ajax: "datalist/list.php?module=branches",
        "columns": [{
                "title": 'Name',
                "data": 'branch_name'
            }, {
                "title": 'Location',
                "data": "branch_location"
            }, {
                'title': 'Status',
                "data": "branch_status"
            }, {
                'title': 'Created On',
                "data": "branch_created"
            }],
        "createdRow": function (row, data, dataIndex) {
            var branch_name = '<a class="add_branch" onclick="branches.getDetails(' + data['branch_id'] + ')" href="javascript:void(0)" >' + data['branch_name'] + '</a>';
            $('td:eq(0)', row).html(branch_name);
            $('td:eq(2)', row).html((data['branch_status'] == 1) ? 'Active' : 'In Active');
            var moment_date = moment(data['branch_created']).format("DD-MM-YYYY h:mm A");
            $('td:eq(3)', row).html(moment_date);
        },
        columnDefs: [{
                targets: [3],
                "bSortable": false
            }],
        "order": [
            [0, "asc"]
        ]
    });

    $('body').on('click', '.add_branch', function (e) {
        e.preventDefault();
        $("#branchForm")[0].reset();
        $("#branchForm").find("#bid").remove();
        $(".bright-bar").show();
        $('#wrapper').addClass('bright-bar-enabled');
    });

    $('body').on('click', '#bclose', function (e) {
        e.preventDefault();
        $("#branchForm").find("#bid").remove();
        $("#branchForm")[0].reset();
        $('#branch_name,#branch_location,#branch_address,#bid').val('');
        $(".bright-bar").hide();
        $('#wrapper').removeClass('bright-bar-enabled');
    });


var branches = {
    manage: function () {
        if ($('#branchForm').parsley().validate()) {
            $(".bright-bar").mask("Please wait..");
            var data = $("#branchForm").serialize();
            $.post('inc/service.php', data, function (info) {
                table.ajax.reload(null, false);
                setTimeout(function () {
                    $('#branchForm')[0].reset();
                    $(".bright-bar").unmask();
                }, 1000);
            });
        }
    },
    getDetails: function (id) {
        $("#branchForm").find("#bid").remove();
        var params = {
            'controller': 'BRANCH',
            'bid': id,
            'action': 'get_branch_details'
        }
        $.getJSON("inc/service.php", params, function (data) {
            $('#branch_name').val(data.branch_name);
            $('#branch_location').val(data.branch_location);
            $('#branch_address').val(data.branch_address);
            $('#branch_status').prop('checked', data.branch_status == 1 ? true : false);
            var bid = '<input type="hidden" value="' + data['branch_id'] + '" name="bid" id="bid">';
            $("#branchForm").append(bid);
        });
    }
}
