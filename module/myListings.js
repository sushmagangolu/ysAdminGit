var myListings = {
    get_data: function () {
        var params = {
            controller: 'LIST',
            list: 'MYLISTINGS'
        }
        var wish='';
        $.getJSON('inc/service.php', params, function (result) {
            $.each(result.data, function (i, item) {
                wish += '<li>';
                wish += '<div class="list-box-listing">';
                wish += '<div class="list-box-listing-img"><a href="#"><img src="images/listing-item-02.jpg" alt=""></a></div>';
                wish += '<div class="list-box-listing-content">';
                wish += '<div class="inner">';
                wish += '<h3>'+item.name+'</h3>';
                wish += '<span>'+item.address+'</span>';
                wish += '</div>';
                wish += '</div>';
                wish += '</div>';
                wish += '<div class="buttons-to-right">';
                wish += '<a href="#" class="button gray"><i class="sl sl-icon-close"></i> Edit</a>';
                wish += '<a href="#" class="button gray"><i class="sl sl-icon-close"></i> Delete</a>';
                wish += '</div>';
                wish += '</li>';
            });
            $('#myListings').append(wish);
        });
    }
}
myListings.get_data();