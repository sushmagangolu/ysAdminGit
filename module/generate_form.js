jQuery(document).ready(function($) {
    var lead_form = {
        init: function() {
            this.getForm();
        },
        getForm: function() {
            $.getJSON("inc/service.php?action=getForm", function(result) {
                //console.log(JSON.stringify(result));
                $.each(result, function(i, field) {
                    var form = '',
                        colClass = "col-md-6";
                    if (field['type'] == 'textarea') {
                        colClass = "col-md-12";
                    }
                    form += '<div class="' + colClass + '"><div class="form-group">';
                    form += '<label for="' + field['label'] + '" class="form-label">' + field['label'] + '</label>';
                    switch (field['type']) {
                        case 'text':
                            form += '<input type="text" class="form-control" name="' + field['name'] + '" id="' + field['name'] + '">';
                            break;
                        case 'textarea':
                            form += '<textarea id="' + field['name'] + '" class="form-control" name="' + field['name'] + '"></textarea>';
                            break;
                        case 'select':
                            var options = '';
                            $.each(field['values'], function(i, option) {
                                options += '<option value="' + option['label'] + '">' + option['label'] + '</option>';
                            });
                            form += '<select class="form-control" name="' + field['name'] + '" id="' + field['name'] + '">';
                            form += options;
                            form += '</select>';
                            break;
                    }
                    form += '</div></div>';
                    $("#gen_form").append(form);
                });
            });
        }
    }
    lead_form.init();
});
