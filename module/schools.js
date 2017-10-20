var school = {
    init: function () {

    },
    save: function () {
        $("body").mask("Please wait ...");
        var form = $('#addForm')[0];
        var formData = new FormData(form);
        console.log(formData);
        $.ajax({
            url: 'inc/service.php?controller=SCHOOL',
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                $('.message').hide();
                //document.getElementById("trainer_form").reset();
                $("body").unmask();
            }
        });
    }
}
if ($('#datatable-buttons').length > 0) {
    var table = $("#datatable-buttons").DataTable({
        "processing": true,
        "iDisplayLength": 10,
        "ajax": "inc/service.php?controller=LIST&list=SCHOOLS",
        "columns": [{
                "title": 'School Name',
                "data": 'name'
            }, {
                "title": 'City',
                "data": "city"
            }, {
                "title": 'Verified',
                "data": "verified"
            }],
        "createdRow": function (row, data, dataIndex) {
            var role_name = '<a class="" href="edit_school.php?school_id=' + data['id'] + '" >' + data['name'] + '</a>';
            $('td:eq(0)', row).html(role_name);
            $('td:eq(2)', row).html((data['verified'] == 1) ? 'Yes' : 'No');
        },
        initComplete: function () {
            var thead2 = '<tr class="filters">';
            $('.datatable-buttons  thead tr:eq(0) th').each(function () {
                thead2 += '<td></td>';
            });
            thead2 += '</tr>';
            $('.datatable-buttons').find('thead').append(thead2);
            this.api().columns().every(function (index) {
                var column = this;
                var title = $('.datatable-buttons thead tr:eq(0) th').eq(index).text();
                $('<input type="text" placeholder="' + title + '" class="form-control input-sm">')
                        .appendTo($('.datatable-buttons thead tr:eq(1) td').eq(index))
                        .on('keyup', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
            });
        }
    });
}
