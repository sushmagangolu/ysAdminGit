var table = $("#datatable-buttons").DataTable({
    "processing": true,
    "iDisplayLength": 50,
    "ajax": "inc/service.php?controller=LIST&list=USERS",
    "columns": [{
            "title": 'Name',
            "data": 'user_name'
        }, {
            "title": 'Email',
            "data": "user_email"
        }, {
            "title": 'Phone',
            "data": "phone"
        }, {
            "title": 'Type',
            "data": "user_type"
        }],
    "createdRow": function (row, data, dataIndex) {
        $('td:eq(3)', row).html((data['user_type'] == 1) ? 'Parent' : 'School');
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