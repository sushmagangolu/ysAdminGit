var enquiries = {
    get_data: function () {
        var params = {
            controller: 'LIST',
            list: 'ENQ'
        }
        var wish = '';
        $.getJSON('inc/service.php', params, function (result) {
            $.each(result.data, function (i, item) {
                wish += '<li>';
                wish += '<div class="list-box-listing">';
                wish += '<div class="list-box-listing-img"><a href="https://growtheye.com/yellowslate/#!/schools/' + item.slate_slug + '"><img src="images/listing-item-02.jpg" alt=""></a></div>';
                wish += '<div class="list-box-listing-content">';
                wish += '<div class="inner">';
                wish += '<h3><a href="https://growtheye.com/yellowslate/#!/schools/' + item.slate_slug + '">' + item.slate_name + '</a></h3>';
                wish += '<span>' + item.lead_created_at + '</span>';
                wish += '</div>';
                wish += '</div>';
                wish += '</div>';
                wish += '<div class="buttons-to-right">';
                wish += '<a href="#" class="button gray"><i class="sl sl-icon-close"></i> Edit</a>';
                wish += '<a href="#" class="button gray"><i class="sl sl-icon-close"></i> Delete</a>';
                wish += '</div>';
                wish += '</li>';
            });
            $('.enquiries').append(wish);
        });
    }
}
enquiries.get_data();
