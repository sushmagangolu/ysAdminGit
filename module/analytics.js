//initializing
!(function($) {
    "use strict";

    $("body").mask("Please wait while we prepare the dashboard..");
    $.post('analytics/HelloAnalytics.php', function(data) {
        var analytics_data = JSON.parse(data);
        $("#sessions").html(analytics_data['sessions']);
        $("#users").html(analytics_data['users']);
        $("#pageviews").html(analytics_data['pageviews']);
        $("#pageviewsPerSession").html(analytics_data['pageviewsPerSession']);
        $("#bounceRate").html(analytics_data['bounceRate']);
        $("#percentNewSessions").html(analytics_data['percentNewSessions']);
        $("body").unmask();
    });

})(window.jQuery);

gapi.analytics.ready(function() {

    /**
     * Authorize the user immediately if the user has already granted access.
     * If no access has been created, render an authorize button inside the
     * element with the ID "embed-api-auth-container".
     */
    gapi.analytics.auth.authorize({
        container: 'embed-api-auth-container',
        clientid: '776613676583-0kgnbg78129sc3o3riolj57r1h6ddpu2.apps.googleusercontent.com'
    });


    /**
     * Create a new ViewSelector instance to be rendered inside of an
     * element with the id "view-selector-container".
     */
    var viewSelector = new gapi.analytics.ViewSelector({
        container: 'view-selector-container'
    });

    // Render the view selector to the page.
    viewSelector.execute();


    /**
     * Create a new DataChart instance with the given query parameters
     * and Google chart options. It will be rendered inside an element
     * with the id "chart-container".
     */
    var dataChart = new gapi.analytics.googleCharts.DataChart({
        query: {
            metrics: 'ga:sessions',
            dimensions: 'ga:date',
            'start-date': '30daysAgo',
            'end-date': 'yesterday'
        },
        chart: {
            container: 'chart-container',
            type: 'LINE',
            options: {
                width: '100%'
            }
        }
    });


    /**
     * Render the dataChart on the page whenever a new view is selected.
     */
    viewSelector.on('change', function(ids) {
        dataChart.set({
            query: {
                ids: ids
            }
        }).execute();
    });

    /**
     * Create a ViewSelector for the first view to be rendered inside of an
     * element with the id "view-selector-1-container".
     */
    var viewSelector1 = new gapi.analytics.ViewSelector({
        container: 'view-selector-1-container'
    });

    /**
     * Create a ViewSelector for the second view to be rendered inside of an
     * element with the id "view-selector-2-container".
     */
    var viewSelector2 = new gapi.analytics.ViewSelector({
        container: 'view-selector-2-container'
    });

    // Render both view selectors to the page.
    viewSelector1.execute();
    viewSelector2.execute();


    /**
     * Create the first DataChart for top countries over the past 30 days.
     * It will be rendered inside an element with the id "chart-1-container".
     */
    var dataChart1 = new gapi.analytics.googleCharts.DataChart({
        query: {
            metrics: 'ga:sessions',
            dimensions: 'ga:source',
            'start-date': '30daysAgo',
            'end-date': 'yesterday',
            'max-results': 6,
            sort: '-ga:sessions'
        },
        chart: {
            container: 'chart-1-container',
            type: 'PIE',
            options: {
                width: '100%',
                pieHole: 4 / 9
            }
        }
    });


    /**
     * Create the second DataChart for top countries over the past 30 days.
     * It will be rendered inside an element with the id "chart-2-container".
     */
    var dataChart2 = new gapi.analytics.googleCharts.DataChart({
        query: {
            metrics: 'ga:sessions',
            dimensions: 'ga:country',
            'start-date': '30daysAgo',
            'end-date': 'yesterday',
            'max-results': 6,
            sort: '-ga:sessions'
        },
        chart: {
            container: 'chart-2-container',
            type: 'PIE',
            options: {
                width: '100%',
                pieHole: 4 / 9
            }
        }
    });

    /**
     * Update the first dataChart when the first view selecter is changed.
     */
    viewSelector1.on('change', function(ids) {
        dataChart1.set({
            query: {
                ids: ids
            }
        }).execute();
    });

    /**
     * Update the second dataChart when the second view selecter is changed.
     */
    viewSelector2.on('change', function(ids) {
        dataChart2.set({
            query: {
                ids: ids
            }
        }).execute();
    });


});
