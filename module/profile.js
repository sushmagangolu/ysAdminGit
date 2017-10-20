var user = {

    init: function () {
        var data = {
            "controller": "USER",
            "act": "getUserDetails",
            "userId": $('#userId').val()
        }

        $.getJSON(baseURL + 'inc/service.php', data, function (info) {
            var userData = info;
            $("body").mask("Please wait ...");
            $('#userName').val(userData.user.userName);
            $('#userEmail').val(userData.user.userEmail);
            $('#roles_ge').val(userData.user.role_id_fk);
            $('#phone').val(userData.user.phone);
            $('#uName').html(userData.user.userName);
            $('#fName').html(userData.user.userName);
            $('#fmobile').html(userData.user.phone);
            $('#femail').html(userData.user.userEmail);
            $('#profile-image').attr('src', 'assets/uploads/' + userData.user.profile_pic);

            if (userData.user.email_reminders == 1) {
                $('#email_reminders').prop('checked', true);
            }
            if (userData.user.email_notification == 1) {
                $('#email_notification').prop('checked', true);
            }
            if (userData.user.sms_notification == 1) {
                $('#sms_notification').prop('checked', true);
            }
            if (userData.branches.length > 0) {
                $.each(userData.branches, function (i, v) {
                    $('input[name="branches_ge"][value="' + v.branch_id_fk + '"]').prop("checked", true);
                });
            }

            $("body").unmask();
        });
    },

    editUser: function () {
        if ($('#editProfileForm').parsley().validate()) {
            $("body").mask("Please wait..");
            var myArray = [];
            $("#braches_ge input[type='checkbox']:checked").each(function () {
                myArray.push(this.value);
            });
            var form = $('#editProfileForm')[0];
            var formData = new FormData(form);
            formData.append('br_ge', myArray.join(","));
            formData.append('controller', 'USER');
            $.ajax({
                url: 'inc/service.php',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    user.init();
                    $("body").unmask();
                }
            });
        }
    },

    saveGeneralSettings: function () {
        var data = $("#user_general_settings").serialize();
        $.post(baseURL + 'inc/service.php?controller=USER', data, function (info) {
            user.init();
        });
    },

    saveLeadColumns: function () {
        $("#leads").mask("Please wait...");
        var data = $("#user_leads_settings").serialize();
        $.post(baseURL + 'inc/service.php?controller=USER', data, function (info) {
            setTimeout(function () {
                $("#leads").unmask();
            }, 2000);
            //user.init();
        });
    }
};
$("body").mask("Please wait...");
setTimeout(function () {
    user.init();
    $("body").unmask();
}, 2000);


