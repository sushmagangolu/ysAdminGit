$(document).ready(function () {
    $("#checkall").change(function () {
        $(".list input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $('.list input[type="checkbox"]').on('change', function () {
        var allChecked = $('.list input:checked').length === $('.list input').length;
        $('#checkall').prop('checked', allChecked);
    });

    /*$("#is_sales").change(function() {
     $("#mod_leads,#view_leads,#trash,#calendar,#notifications,#email_notification,#sms_notification").prop('checked', $(this).prop("checked"));
     });*/

    $("#addRole").click(function () {
        if ($('#addForm').parsley().validate()) {
            $("body").mask("Please wait..");
            var data = $("#addForm").serialize();
            $.post('inc/service.php?controller=USER', data, function (info) {
                $("body").unmask();
                if (info == 1) {
                    var params = {
                        title: 'Success!',
                        text: 'Role Created',
                        type: 'success',
                        confirmButtonText: 'ok',
                        redirect_url: 'roles.php'
                    };
                } else {
                    var params = {
                        title: 'danger',
                        text: info,
                        type: 'error',
                        confirmButtonText: 'ok'
                    };
                }
                sweetAlert(params);
            });
        }
    });

    $("#editRole").click(function () {
        if ($('#addForm').parsley().validate()) {
            $("body").mask("Please wait..");
            var data = $("#addForm").serialize();
            $.post('inc/service.php?controller=USER', data, function (info) {
                $("body").unmask();
                swal({
                    title: "Success!",
                    text: "User updated",
                    type: "success",
                    confirmButtonClass: 'btn-success btn-md waves-effect waves-light',
                    confirmButtonText: 'ok'
                }, function (isConfirm) {
                    if (isConfirm) {
                        $(window).attr('location', 'roles.php');
                    }
                });
            });
        }
    });
    if ($('#datatable-buttons').length > 0) {


        var table = $("#datatable-buttons").DataTable({
            dom: "Bfrtip",
            buttons: [{
                    className: "btn-sm btn-info btn-purple add_role",
                    text: 'Add Role'
                }],
            "processing": true,
            "iDisplayLength": 100,
            ajax: "datalist/list.php?module=roles",
            "columns": [{
                    'title': 'Name',
                    "data": 'role_name'
                }, {
                    'title': 'Is Admin?',
                    "data": "is_admin"
                }, {
                    'title': 'Is Sales?',
                    "data": "is_sales"
                }],
            "createdRow": function (row, data, dataIndex) {
                var role_name = '<a class="" href="editrole.php?role_id=' + data['role_id'] + '" >' + data['role_name'] + '</a>';
                $('td:eq(0)', row).html(role_name);
                $('td:eq(1)', row).html((data['is_admin'] == 1) ? 'Yes' : 'No');
                $('td:eq(2)', row).html((data['is_sales'] == 1) ? 'Yes' : 'No');
            },
            columnDefs: [],
            "order": [
                [1, "asc"]
            ]
        });
    }
    $('body').on('click', '.add_role', function (e) {
        e.preventDefault();
        $(window).attr('location', 'addrole.php');
    });

});
