var userProfile = {

    init: function() {

        var data = {

            "controller": "PROFILE",

            "action": "getProfileDetails",

            "client_id": $('#client_id').val()

        }

        $.getJSON(baseURL + 'inc/service.php', data, function(info) {

            var clientData = info.client;

            $('#client_name').val(clientData.client_name);

            $('#client_website').val(clientData.client_website);

            $('#client_phone').val(clientData.client_phone);

            $('#client_email').val(clientData.client_email);

            $('#primary_contact_name').val(clientData.primary_contact_name);

            $('#primary_contact_phone').val(clientData.primary_contact_phone);

            $('#facebook_page_id').val(clientData.facebook_page_id);

            $('#social_facebook').val(clientData.social_facebook);

            $('#social_gplus').val(clientData.social_gplus);

            $('#social_twitter').val(clientData.social_twitter);

            $('#client_address').val(clientData.client_address);

            $('#client_image').attr('src', 'assets/uploads/' + clientData.client_image);

            //Emails//

            var emails = info.emails;

            $("#m_emails, .m_email").html('');

            if (emails.length > 0) {

                $.each(emails, function(i, v) {

                    $(".m_email").append('<option value="' + v.em_id + '">' + v.email_market + '</option>');

                    $("#m_emails").append('<tr><td>' + v.email_market + '</td><td><a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="userProfile.deleteEmail(' + v.em_id + ');">Delete</a></td></tr>');

                });

            }

            // FollowUps List

            var follow = info.follow_up;

            $("#follow_ups").html('');



            if (follow.length > 0) {

                $.each(follow, function(i, v) {

                    var fp = '<tr>';

                    fp += '<td>' + v.template_name + '</td>';

                    fp += '<td>' + v.follow_up_after + ' days</td>';

                    fp += '<td>' + v.follow_up_at + ':00</td>';

                    if (v.lead_status == '0') {

                        fp += '<td>All Leads</td>';

                    } else {

                        fp += '<td>' + v.lead_status + '</td>';

                    }

                    fp += '<td>' + v.template_name + '</td>';

                    if (v.follow_up_send == 0) {

                        fp += '<td><a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="userProfile.manageFP(' + v.follow_up_id + ', 1);">Start</a></td>';

                    } else {

                        fp += '<td><a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="userProfile.manageFP(' + v.follow_up_id + ', 0);">Stop</a></td>';

                    }

                    fp += '<td><a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="userProfile.manageFP(' + v.follow_up_id + ', 2);">Delete</a></td>';

                    fp += '</tr>';

                    $("#follow_ups").append(fp);

                });

            }



            //SMS

            if (clientData.promotional_sms == 1) {

                $('#promo_sms').prop('checked', true);

            }

            $('#sender_id').val(clientData.sender_id);

            $('#promo_text').val(clientData.promotional_sms_text);

            // EMAIL templates

            var email_templates = info.email_templates;

            $(".email_templates").html('');

            if (email_templates.length > 0) {

                $.each(email_templates, function(i, v) {

                    $(".email_templates").append('<option value="' + v.template_id + '">' + v.template_name + '</option>');

                });

            }



            // Status

            var lead_status = JSON.parse(clientData.status_json);

            $(".lead_status").html('');

            $(".lead_status").append('<option value="0">All Leads</option>');

            $.each(lead_status, function(i, v) {

                $(".lead_status").append('<option value="' + v.status + '">' + v.status + '</option>');

            });

            //General Settings

            if (clientData.ack_email == 1) {

                $('#ack_email').prop('checked', true);

            }

            if (clientData.email_automation == 1) {

                $('#email_automation').prop('checked', true);

            }

            if (clientData.sms_notifications == 1) {

                $('#sms_notifications').prop('checked', true);

            }

            if (clientData.round_robin == 1) {

                $('#round_robin').prop('checked', true);

            }

            if (clientData.email_notifications == 1) {

                $('#email_notifications').prop('checked', true);

            }

            $('#marketing_email').val(clientData.marketing_email_fk);

            $("#ack_email_tpl").val(clientData.ack_email_tpl);



            //Reports

            if (clientData.daily_report == 1) {

                $('#daily_reports').prop('checked', true);

            }

            if (clientData.weekly_report == 1) {

                $('#weekly_reports').prop('checked', true);

            }

            if (clientData.monthly_report == 1) {

                $('#monthly_reports').prop('checked', true);

            }

            $("#report_email").val(clientData.report_email_fk);



            $("body").unmask();

        });

    },

    manageFP: function(id, upd) {

        var data = {

            'action': 'manageFP',

            'follow_up_id': id,

            'upd': upd

        }

        $.post(baseURL + 'inc/service.php?controller=PROFILE', data, function(info) {

            userProfile.init();

        });

    },

    edit: function() {

        if ($('#editForm').parsley().validate()) {

            $("body").mask("Please wait ...");

            var data = $("#editForm").serialize();

            $.post(baseURL + 'inc/service.php?controller=PROFILE', data, function(info) {

                userProfile.init();

            });

        }

    },

    update_sms_settings: function() {

        if ($('#smsForm').parsley().validate()) {

            $("body").mask("Please wait while we update ...");

            var data = $("#smsForm").serialize();

            $.post('inc/service.php?controller=PROFILE', data, function(info) {

                userProfile.init();

            });

        }

    },

    update_general_settings: function() {

        if ($('#generalForm').parsley().validate()) {

            $("body").mask("Please wait while we update ...");

            var data = $("#generalForm").serialize();

            $.post('inc/service.php?controller=PROFILE', data, function(info) {

                userProfile.init();

            });

        }

    },

    EA: function() {

        if ($('#f1').parsley().validate()) {

            $("body").mask("Please wait while we update ...");

            var data = $("#f1").serialize();

            $.post('inc/service.php?controller=PROFILE', data, function(info) {

                userProfile.init();

            });

        }

    },

    addEmails: function() {

        if ($('#miscForm').parsley().validate()) {

            $("body").mask("Please wait while we update ...");

            var data = $("#miscForm").serialize();

            $.post('inc/service.php?controller=PROFILE', data, function(info) {

                userProfile.init();

            });

        }

    },

    deleteEmail: function(id) {

        $("body").mask("Please wait while we update ...");

        var data = {

            action: 'delete_email',

            em_id: id

        };

        $.post('inc/service.php?controller=PROFILE', data, function(info) {

            userProfile.init();

        });

    },

    update_report_settings: function() {

        if ($('#reportForm').parsley().validate()) {

            $("body").mask("Please wait while we update ...");

            var data = $("#reportForm").serialize();

            $.post('inc/service.php?controller=PROFILE', data, function(info) {

                userProfile.init();

            });

        }

    },



};

userProfile.init();

$(document).ready(function() {

    $('#c_image').filer({

        limit: 1,

        maxSize: 1,

        extensions: ['jpg', 'jpeg', 'png', 'gif'],

        changeInput: true,

        showThumbs: false,

        addMore: false,

        uploadFile: {

            url: "assets/jquery.filer/php/upload.php",

            data: null,

            type: 'POST',

            enctype: 'multipart/form-data',

            beforeSend: function() {},

            success: function(data, el) {

                var imageData = $.parseJSON(data);

                $('#image').val(imageData['metas'][0]['name']);

            },

            error: function(el) {

                console.log('Error');

            },

            statusCode: null,

            onProgress: null,

            onComplete: null

        }

    });

});
