    var fbuild = {
        init: function() {
            this.getForm();
            //this.getLeadData();
        },
        getForm: function() {
            $.getJSON("inc/service.php?action=getForm", function(result) {
                fbuild.showForm(result);
            });
        },
        getLeadData: function() {
            $.getJSON("inc/service.php?action=getLeadData", function(result) {
                console.log(result);
            });
        },
        showForm: function(formData) {
            var $fbEditor = $(document.getElementById('fb-editor'));
            var fbOptions = {
                dataType: 'json',
                controlPosition: 'left',
                disableFields: [
                    'autocomplete',
                    'button',
                    'header',
                    'paragraph',
                    'number',
                    'hidden',
                    'checkbox-group',
                    'checkbox',
                    'radio-group',
                    'file'
                ],
                controlOrder: [
                    'text',
                    'textarea',
                    'select'
                ],
                typeUserEvents: {
                    text: {
                        onadd: function(fld) {
                            fld.querySelector('.fld-label').onchange = function(e) {
                                var name_value = fld.querySelector('.fld-label').value;
                                fld.querySelector(".fld-name").value = name_value.replace(/ /g, "-").toLowerCase();
                            };
                        }
                    },
                    select: {
                        onadd: function(fld) {
                            fld.querySelector('.fld-label').onchange = function(e) {
                                var name_value = fld.querySelector('.fld-label').value;
                                fld.querySelector(".fld-name").value = name_value.replace(/ /g, "-").toLowerCase();
                            };
                        }
                    },
                    textarea: {
                        onadd: function(fld) {
                            fld.querySelector('.fld-label').onchange = function(e) {
                                var name_value = fld.querySelector('.fld-label').value;
                                fld.querySelector(".fld-name").value = name_value.replace(/ /g, "-").toLowerCase();
                            };
                        }
                    }
                }
            };
            if (formData != 0) {
                fbOptions.formData = JSON.stringify(formData);
            }
            $fbEditor.formBuilder(fbOptions);
            var saveBtn = document.querySelector('.form-builder-save');
            saveBtn.onclick = function() {
                //console.log('Form Saved');
                // save form in database //
                var data = {
                    action: 'saveForm',
                    lead_json: $fbEditor.data('formBuilder').formData
                };
                $.post('inc/service.php', data, function(info) {
                    location.reload();
                });
            };
        }
    }
    fbuild.init();
