$(function() {
    $('#importcsv').filer({
        limit: 1,
        maxSize: 2,
        extensions: ['csv'],
        changeInput: true,
        showThumbs: false,
        addMore: false,
        uploadFile: {
            url: "assets/jquery.filer/php/upload.php?dir=csvimports",
            data: null,
            type: 'POST',
            enctype: 'multipart/form-data',
            beforeSend: function() {
                jQuery("body").mask("Please wait while we try to import the data...");
            },
            success: function(data, el) {
                var imageData = $.parseJSON(data);
                //importData(imageData['metas'][0]['name']);
                var data = 'action=import&fileName=' + imageData['metas'][0]['name'];
                $.post('datalist/importcsv.php', data, function(info) {
                    $('#import_modal').modal('hide');
                    table.ajax.reload();
                    setTimeout(function() {
                        jQuery("body").unmask();
                    }, 2000);
                });
            },
            error: function(el) {
                console.log('Error');
            },
            statusCode: null,
            onProgress: null,
            onComplete: null
        },
        captions: {
            button: "Choose CSV Files",
            feedback: "Choose files To Upload",
            feedback2: "files were chosen",
            drop: "Drop file here to Upload",
            removeConfirmation: "Are you sure you want to remove this file?",
            errors: {
                filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
                filesType: "Only CSV files are allowed to be uploaded.",
                filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-maxSize}} MB.",
                filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
            }
        }
    });

    function importData(fileName) {
        $.ajax({
            url: "datalist/importcsv.php",
            method: "POST",
            data: 'action=import&fileName=' + fileName,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false, // To send DOMDocument or non processed data file it is set to false
            success: function(data) {
                console.log(data);
                setTimeout(function() {
                    jQuery("body").unmask();
                }, 2000);
            }
        })

    }
});
