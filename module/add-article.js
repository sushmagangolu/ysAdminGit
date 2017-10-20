$(document).ready(function () {
    $('.notice').hide();
    $('#article_content').summernote({
        height: 300
    });
});
var article = {
    add: function () {
        var loaderOptions = {
            imgPath: 'scripts/jqueryLoader/assets/img/default.svg',
            text: 'Please wait ...',
            style: {
                position: 'fixed',
                width: '100%',
                height: '100%',
                background: 'rgba(0, 0, 0, .8)',
                left: 0,
                top: 0,
                zIndex: 10000
            }
        }
        $.loadingBlockShow(loaderOptions);
        var form = $('#addForm')[0];
        var formData = new FormData(form);
        formData.append('article_content', $('#article_content').summernote('code'));
        $.ajax({
            url: 'inc/service.php',
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                setTimeout($.loadingBlockHide, 3000);
                $('.notice').show();
            }
        });
    }
}
