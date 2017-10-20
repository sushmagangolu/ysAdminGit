! function($) {

    "use strict";



    var MorrisCharts = function() {};

    //creates Stacked chart

    MorrisCharts.prototype.createStackedChart = function(element, data, xkey, ykeys, labels, lineColors) {

            Morris.Bar({

                element: element,

                data: data,

                xkey: xkey,

                ykeys: ykeys,

                stacked: true,

                labels: labels,

                xLabelAngle: 35,

                barSizeRatio: 0.4,

                hideHover: 'auto',

                resize: true, //defaulted to true

                gridLineColor: '#eeeeee',

                barColors: lineColors

            });

        },

        MorrisCharts.prototype.init = function(data, source) {

            this.createStackedChart('morris-bar-stacked', data, 'y', source, source, ['#2196F3', '#42A5F5', '#64B5F6', '#90CAF9', '#BBDEFB']);

            //this.createStackedChart('morris-bar-stacked_month', mdata, 'y', source, source, ['#2196F3', '#42A5F5', '#64B5F6', '#90CAF9', '#BBDEFB']);

        },

        //init



        $.MorrisCharts = new MorrisCharts, $.MorrisCharts.Constructor = MorrisCharts

}

(window.jQuery),

//initializing

function($) {

    "use strict";

    var dashboard = {

        init: function() {

            this.dashboard_data();

            this.notif_dashboard();

        },

        dashboard_data: function() {

            $("body").mask("Please wait while we prepare the dashboard..");

            $.post('datalist/dashboard.php', function(data) {

                var dashboard_data = JSON.parse(data);

                //creating Stacked chart

                var stckedData = dashboard_data['bar_chart_data'];

                //var stckedMData = dashboard_data['mbar_chart_data'];

                var source = dashboard_data['source'];

                $.MorrisCharts.init(stckedData, source);

                $("#total_leads").html(dashboard_data['total_leads']);

                $("#total_month_leads").html(dashboard_data['total_month_leads']);

                $("#total_daily_leads").html(dashboard_data['total_daily_leads']);

                $("#total_pmonth_leads").html(dashboard_data['total_pmonth_leads']);



                var follow = dashboard_data['monthwise_leads'];

                $("#month_wise_data").html('');

                if (follow != 0) {

                    var mName;

                    $.each(follow, function(i, v) {

                        switch (v.MTH) {

                            case '1':

                                mName = 'January';

                                break;

                            case '2':

                                mName = 'February';

                                break;

                            case '3':

                                mName = 'March';

                                break;

                            case '4':

                                mName = 'April';

                                break;

                            case '5':

                                mName = 'May';

                                break;

                            case '6':

                                mName = 'June';

                                break;

                            case '7':

                                mName = 'July';

                                break;

                            case '8':

                                mName = 'August';

                                break;

                            case '9':

                                mName = 'September';

                                break;

                            case '10':

                                mName = 'October';

                                break;

                            case '11':

                                mName = 'November';

                                break;

                            case '12':

                                mName = 'December';

                                break;

                        }

                        var fp = '<tr>';

                        fp += '<td>' + v.YR + '</td>';

                        fp += '<td>' + mName + '</td>';

                        fp += '<td>' + v.LT + '</td>';

                        fp += '</tr>';

                        $("#month_wise_data").append(fp);

                    });

                } else {

                    $("#month_wise_data").html('No Data To Display');

                }

                //Daywise

                var day_follow = dashboard_data['daywise_leads'];

                $("#day_wise_data").html('');

                if (day_follow != 0) {

                    $.each(day_follow, function(i, v) {

                        var moment_date = moment(v.DT).format("DD-MM-YYYY, dddd");

                        var fp = '<tr>';

                        fp += '<td>' + moment_date + '</td>';

                        fp += '<td>' + v.LT + '</td>';

                        fp += '</tr>';

                        $("#day_wise_data").append(fp);

                    });

                } else {

                    $("#day_wise_data").html('No Data To Display');

                }

                $("body").unmask();

            });

        },



        notif_dashboard: function() {

            var data = {

                'controller': 'CALENDAR',

                'action': 'get_notifs'

            }

            $.getJSON("inc/service.php", data, function(result) {

                var today = result.today;

                if (today.length > 0) {

                    $.each(today, function(i, v) {

                        var fp = '<li>';

                        if (v.lead_id_fk != 0) {

                            fp += '<a href="leadsv2.php?lead_id=' + v.lead_id_fk + '" class="user-list-item">';

                            fp += '<div class="icon bg-danger">';

                            fp += '<i class="mdi mdi-bell"></i>';

                        } else {

                            fp += '<a href="calendar.php" class="user-list-item">';

                            fp += '<div class="icon bg-info">';

                            fp += '<i class="mdi mdi-calendar"></i>';

                        }

                        fp += '</div>';

                        fp += '<div class="user-desc">';

                        fp += '  <span class="name">' + v.event_title + '</span>';

                        fp += '  <span class="time">' + moment(v.db_start).format("DD-MM-YYYY h:mm A"); +

                        '</span>';

                        fp += '  </div>';

                        fp += '</a>';

                        fp += '  </li>';

                        $("#today_dashboard").append(fp);

                    });



                } else {

                    $("#today_dashboard").append('<li><h5>No Events/Reminders</h5></li>');

                }

            });

        }

    }

    dashboard.init();

}(window.jQuery);

//initializing
