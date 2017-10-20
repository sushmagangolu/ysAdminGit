var table = $("#datatable-buttons").DataTable({
  "processing": true,
  "iDisplayLength": 50,
  "ajax": "inc/service.php?controller=LIST&list=ARTICLES",
  "columns": [{
    "title": "Title",
    "data": null,
    "defaultContent": "---",
    "className": "article_title",
    "render": function(data, type, row, meta) {
      var parent_name = 'N/A';
      if (data['article_title']) {
        parent_name = '<a href="add-article.php?id=' + data['article_id'] + '">' + data['article_title'] + '</a>';
      }
      return parent_name;
    }
  },
  {
    "title": 'Date',
    "data": "article_created_date"
  }],
  "createdRow": function(row, data, dataIndex) {
    //  parent_name ="";
    //   $('td:eq(1)', row).html('<a href="">'+ article_title +'</a>');
    //   return parent_name;
  },
  initComplete: function() {
    var thead2 = '<tr class="filters">';
    $('.datatable-buttons  thead tr:eq(0) th').each(function() {
      thead2 += '<td></td>';
    });
    thead2 += '</tr>';
    $('.datatable-buttons').find('thead').append(thead2);
    this.api().columns().every(function(index) {
      var column = this;
      var title = $('.datatable-buttons thead tr:eq(0) th').eq(index).text();
      $('<input type="text" placeholder="' + title + '" class="form-control input-sm">')
        .appendTo($('.datatable-buttons thead tr:eq(1) td').eq(index))
        .on('keyup', function() {
          if (column.search() !== this.value) {
            column.search(this.value).draw();
          }
        });
    });
  }
});
