var user = {
    auth: function (id) {
        var params = {
            controller: 'LIST',
            list: 'AUTH',
            userId: id
        }
        $.getJSON('inc/service.php', params, function (result) {});
    }
}

var userId = $.cookie('ysUserId');
if (typeof (userId) == 'undefined') {
    // Redirect to home page
} else {
    user.auth(userId);
}
