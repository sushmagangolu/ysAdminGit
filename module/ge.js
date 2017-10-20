var ge = {
    init: function () {
        this.notificationsCount();
    },
    notificationsCount: function () {
        var data = {
            'controller': 'CALENDAR',
            'action': 'get_ncount'
        }
        $.getJSON("inc/service.php", data, function (info) {
            if (info != 0) {
                jQuery(".notification_count").show().html(info);
            }
        });
    },
    serializeObject: function (sa) {
        var o = {};
        var a = sa;
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    },
    prepareForm: function () {
        $("#gen_form").empty();
        $.getJSON("inc/service.php?action=getForm", function (result) {
            $.each(result, function (i, field) {
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
                    case 'date':
                        form += '<div class="' + field['className'] + ' input-group date  ' + field['name'] + '">';
                        form += '<input type="text" id="' + field['name'] + '" class="form-control" value="" name="' + field['name'] + '" />';
                        form += '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
                        break;
                    case 'textarea':
                        form += '<textarea id="' + field['name'] + '" class="form-control" name="' + field['name'] + '"></textarea>';
                        break;
                    case 'select':
                        var options = '';
                        $.each(field['values'], function (i, option) {
                            options += '<option value="' + option['label'] + '">' + option['label'] + '</option>';
                        });
                        form += '<select class="form-control" name="' + field['name'] + '" id="' + field['name'] + '"required>';
                        form += options;
                        form += '</select>';
                        break;
                }
                form += '</div></div>';
                $("#gen_form").append(form);
                if (field['type'] == 'date') {
                    $('.' + field['name']).datetimepicker({
                        showClose: true,
                        format: date_format
                    });
                }
                //$('#myModal').modal('show');
            });
        });
    },
    openNotificationPanel: function () {
        $(".nright-bar").show();
        $('#wrapper').addClass('nright-bar-enabled');
        $('.nright-bar').mask('Please wait...');
        var data = {
            'controller': 'CALENDAR',
            'action': 'get_notifs'
        }
        $.getJSON("inc/service.php", data, function (result) {
            $("#today").html('<li><h5>Today</h5></li>');
            var today = result.today;
            if (today.length > 0) {
                $.each(today, function (i, v) {
                    var fp = '<li>';
                    if (v.lead_id_fk != 0) {
                        fp += '<a href="leadsv2.php?lead_id=' + v.lead_id_fk + '" class="user-list-item">';
                        fp += '<div class="icon bg-danger">';
                        fp += '<i class="mdi mdi-comment"></i>';
                    } else {
                        fp += '<a href="calendar.php" class="user-list-item">';
                        fp += '<div class="icon bg-info">';
                        fp += '<i class="mdi mdi-calendar"></i>';
                    }
                    fp += '</div>';
                    fp += '<div class="user-desc">';
                    fp += '  <span class="name">' + v.event_title + '</span>';
                    fp += '  <span class="time">' + moment(v.db_start).format("DD-MM-YYYY h:mm A");
                    +'</span>';
                    fp += '  </div>';
                    fp += '</a>';
                    fp += '  </li>';
                    $("#today").append(fp);
                });
                $('.nright-bar').unmask();
            } else {
                $("#today").append('<li><h5>No Events/Reminders</h5></li>');
                $('.nright-bar').unmask();
            }
            $("#upcoming").html('<li><h5>Upcoming</h5></li>');
            var upcoming = result.upcoming;
            if (upcoming.length > 0) {
                $.each(upcoming, function (i, v) {
                    var fp = '<li>';
                    if (v.lead_id_fk != 0) {
                        fp += '<a href="leadsv2.php?lead_id=' + v.lead_id_fk + '" class="user-list-item">';
                        fp += '<div class="icon bg-danger">';
                        fp += '<i class="mdi mdi-comment"></i>';
                    } else {
                        fp += '<a href="calendar.php" class="user-list-item">';
                        fp += '<div class="icon bg-info">';
                        fp += '<i class="mdi mdi-calendar"></i>';
                    }
                    fp += '</div>';
                    fp += '<div class="user-desc">';
                    fp += '  <span class="name">' + v.event_title + '</span>';
                    fp += '  <span class="time">' + v.db_start + '</span>';
                    fp += '  </div>';
                    fp += '</a>';
                    fp += '  </li>';
                    $("#upcoming").append(fp);
                });
                $('.nright-bar').unmask();
            } else {
                $("#upcoming").append('<li><h5>No Upcoming Events/Reminders</h5></li>');
                $('.nright-bar').unmask();
            }
        });
    },
    closeNotificationPanel: function () {
        $(".nright-bar").hide();
        $('#wrapper').removeClass('nright-bar-enabled');
    },
    getUsers: function (id) {
        $.getJSON('datalist/list.php?module=users', '', function (result) {
            var user = '<select class="form-control" name="users_ge" id="users_ge">';
            $.each(result.data, function (i, v) {
                user += '<option value="' + v.userId + '">[ ' + v.userName + ' ] ' + v.userEmail + '</option>';
            });
            user += '</select>';
            $("#" + id).append(user);
        });
    },
    getStatus: function (id) {
        var status = '<select class="form-control" name="status_ge" id="status_ge">';
        $.each(status_global, function (i, v) {
            status += '<option value="' + v.status + '">' + v.status + '</option>';
        });
        status += '</select>';
        $("#" + id).append(status);
    },
    getBranches: function (id) {
        $.getJSON('datalist/list.php?module=branches', '', function (result) {
            var md = '<table class="table table-condensed table-striped" id="braches_ge">';
            if (result.data.length > 0) {
                $.each(result.data, function (i, v) {
                    md += '<tr><td>';
                    md += ' <input type="checkbox" name="branches_ge" value="' + v.branch_id + '"   /> [ ' + v.branch_location + ' ] ' + v.branch_name;
                    md += '</td></tr>';
                });
            } else {
                md += '<tr><td>No Branches</td></tr>';
            }
            md += '</table>';
            $("#" + id).append(md);
        });
    },
    getRoles: function (id) {
        $.getJSON('datalist/list.php?module=roles', '', function (result) {
            var user = '<select class="form-control" name="roles_ge" id="roles_ge">';
            $.each(result.data, function (i, v) {
                user += '<option value="' + v.role_id + '">' + v.role_name + '</option>';
            });
            user += '</select>';
            $("#" + id).append(user);
        });
    }
}
ge.init();

$(window).load(function () { // makes sure the whole site is loaded
    $("#status_loader").fadeOut(); // will first fade out the loading animation
    $("#preloader").delay(350).fadeOut("slow"); // will fade out the white DIV that covers the website.
})

$(function () {
    $("#insertLead").click(function () {
        if ($('#newlead').parsley().validate()) {
            $("body").mask("Please wait ...");
            var lead_json = ge.serializeObject($("#newlead").serializeArray());
            var data = {
                action: 'insert_lead',
                message: $("#newlead").find("textarea[name='message']").val(),
                lead_json: JSON.stringify(lead_json)
            };
            $.post('inc/service.php', data, function (info) {
                $("#newlead")[0].reset();
                $("body").unmask();
            });
        }
    });

});

function sweetAlert(params) {
    swal({
        title: params.title,
        text: params.text,
        type: params.type,
        confirmButtonClass: 'btn-success btn-md waves-effect waves-light',
        confirmButtonText: params.confirmButtonText,
    }, function (isConfirm) {
        if (isConfirm) {
            $(window).attr('location', params.redirect_url);
        }
    });
}

checkNotificationPermissions();

function checkNotificationPermissions() {
    Notification.requestPermission();
    if (Notification.permission != 'granted') {
        setTimeout('checkNotificationPermissions();', 1 * 60 * 1000);
    }
}
//notifyMe1('Remider', 'TEST');

function notifyMe(body, title, link) {
    var chck = Notification.permission;
    if (chck === 'granted') {
        $.notify("", {
            title: title,
            icon: "ge_notofication_logo.png",
            body: body,
        }).click(function () {
            parent.focus();
            location.href = link;
        });
    }
}

//getReminders();

function getReminders() {
    $.ajax({
        url: 'datalist/notifications.php?action=events',
        success: function (info) {
            if (info != 0) {
                var data = JSON.parse(info);
                $.each(data, function (index, val) {
                    var start_date_time = moment(val['start_date_time']).format("DD-MM-YYYY hh:mm A");
                    if (val['lead_id'] != 0) {
                        notifyMe('Reminder', val['title'] + ' at ' + start_date_time, 'leadsv2.php?lead_id=' + val['lead_id']);
                    } else {
                        notifyMe('Reminder', val['title'] + ' at ' + start_date_time, 'leadsv2.php');
                    }
                });
            }
        }
    });
}
//setInterval('getReminders();', 1 * 60 * 1000);

function logout() {
    $("body").mask("Please wait while you logout...");
    var data = $("#editProfileForm").serialize();
    $.post('datalist/user.php', data, function (info) {
        $("body").unmask();
        var params = {
            title: 'Success!',
            text: 'You have successfully logged out',
            type: 'success',
            confirmButtonText: 'ok',
            redirect_url: 'login.php'
        };
        sweetAlert(params);
    });
}
