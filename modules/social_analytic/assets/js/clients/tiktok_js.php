<script type="text/javascript">
var date_filter;
var account_filter;
var social;

(function($) {
    "use strict";

    social = $('input[name=social]').val();

    $(document).ready(function() {
        Highcharts.setOptions({
            lang: {
                thousandsSep: ','
            }
        });
        dashboard_custom_view('last_30_days',"<?php echo _l('last_30_days'); ?>",'date_filter');
    });
})(jQuery);

function dashboard_do_filter_active(value) {
    "use strict";
  if (value !== "" && typeof value != "undefined") {
    $('[data-cview="all"]').parents("li").removeClass("active");
    var selector = $('[data-cview="' + value + '"]');

    var parent = selector.parents("li");
    if (parent.hasClass("filter-group")) {
      var group = parent.data("filter-group");
      $('[data-filter-group="' + group + '"]')
        .not(parent)
        .removeClass("active");
      $.each($('[data-filter-group="' + group + '"]').not(parent), function () {
        $('input[name="' + $(this).find("a").attr("data-cview") + '"]').val("");
      });
    }
    if (!parent.not(".dropdown-submenu").hasClass("active")) {
      parent.addClass("active");
    } else {
      parent.not(".dropdown-submenu").removeClass("active");
      parent.find("a").blur();
      // Remove active class from the parent dropdown if nothing selected in the child dropdown
      var parents_sub = selector.parents("li.dropdown-submenu");
      if (parents_sub.length > 0) {
        if (parents_sub.find("li.active").length === 0) {
          parents_sub.removeClass("active");
        }
      }
      value = "";
    }
    return value;
  } else {
    $("._filters input").val("");
    $("._filter_data li.active").removeClass("active");
    $('[data-cview="all"]').parents("li").addClass("active");
    return "";
  }
}

// Datatables custom view will fill input with the value
function dashboard_custom_view(value, $lang, custom_input_name, clear_other_filters) {
    "use strict";

    var name =
    typeof custom_input_name == "undefined" ? "custom_view" : custom_input_name;
      if (typeof clear_other_filters != "undefined") {
        var filters = $("._filter_data li.active").not(".clear-all-prevent");
        filters.removeClass("active");
        $.each(filters, function () {
          var input_name = $(this).find("a").attr("data-cview");
          $('._filters input[name="' + input_name + '"]').val("");
        });
      }

      if (isNaN(value)) {
        var _cinput = dashboard_do_filter_active(value);
      }else{
        var _cinput = dashboard_do_filter_active(name);
        if (_cinput != name) {
            value = "";
        }
      }

      $('input[name="' + name + '"]').val(value);

    //boxloading
    

    var Dashboard_Filters = $("._hidden_inputs._filters._tasks_filters input");
    var data_filter = {};
    data_filter['social'] = social;
    
    $.each(Dashboard_Filters, function () {
        if($('[name="' + $(this).attr("name") + '"]').val() != ''){

      data_filter[$(this).attr("name")] =
        $('[name="' + $(this).attr("name") + '"]').val();
        }
    });
    
    $('#top_stats').html('<div class="loader-action"></div>');
    data_filter['type'] = 'top_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        $('#top_stats').html(response);
    });

    follower_chart_init(data_filter);
    video_views_chart_init(data_filter);
    follow_stats_init(data_filter);
    engagement_rate_init(data_filter);
    posting_pattern_engagement_analysis_init(data_filter);
}


function video_views_chart_init(data_filter){
    "use strict";
    $('#video_views_chart').html('<div class="loader-action"></div>');

    data_filter['type'] = 'video_views_chart';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('video_views_chart', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('sa_video_views'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('video_views_note')?>'
            },
            xAxis: {
                categories: response.video_views_chart.header,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
              enabled: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}'
            },
          
            credits: {
                      enabled: false
                    },
            series: [{
                name: '<?php echo _l('sa_video_views'); ?>',
                data: response.video_views_chart.data_total.video_view
            }]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function follow_stats_init(data_filter){
    "use strict";
    $('#follow_stats').html('<div class="loader-action"></div>');

    data_filter['type'] = 'tiktok_follow_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#follow_stats').html(response.content_html);
        Highcharts.chart('follower_gender_pie_chart', {
            chart: {
                type: 'pie'
            },
            colors: ['#119EFA','#ff3399'],

            title: {
                text: '<?php echo _l('followers_by_gender'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('followers_by_gender_note')?>'
            },
            tooltip: {
                valueSuffix: '%'
            },
            credits: {
              enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [
                {
                    name: 'Percentage',
                    colorByPoint: true,
                    data: response.follower_gender_pie_chart.data
                }
            ]
        });
    });
}

function follower_chart_init(data_filter){
    "use strict";
    $('#follower_chart').html('<div class="loader-action"></div>');

    data_filter['type'] = 'follower_chart';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('follower_chart', {
        colors: [ '#84c529','#ef370dc7'],

        title: {
            text: '<?php echo _l("net_followers"); ?>'
        },
        subtitle: {
                text: '<?php echo _l('net_followers_note')?>'
            },
        legend: {
                align: 'left',
                x: 70,
                verticalAlign: 'top',
                y: 70,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
        credits: {
              enabled: false
            },
        yAxis: {
            title: {
                text: ''
            }
        },
        xAxis: {
            categories: response.follower_chart.categories
        },

        series: response.follower_chart.data,
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
            }
        },
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function engagement_rate_init(data_filter){
    "use strict";
    $('#engagement_rate').html('<div class="loader-action"></div>');
    data_filter['type'] = 'tiktok_engagement_rate';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('engagement_rate', {
            chart: {
                type: 'column'
            },
          colors: ['#119EFA', '#DDDF00', '#ef370dc7',  '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l("engagement_activity"); ?>',
            },
            subtitle: {
                text: '<?php echo _l('engagement_activity_note')?>'
            },
            xAxis: {
                categories: response.tiktok_engagement_rate.header
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total'
                },
                stackLabels: {
                    enabled: true
                }
            },
            legend: {
                align: 'left',
                x: 70,
                verticalAlign: 'top',
                y: 70,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            credits: {
                      enabled: false
                    },
            series: [{
                name: 'Likes',
                data: response.tiktok_engagement_rate.data_total.like
            },{
                name: 'Shares',
                data: response.tiktok_engagement_rate.data_total.share
            }, {
                name: 'Comments',
                data: response.tiktok_engagement_rate.data_total.comment
            }, ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function posting_pattern_engagement_analysis_init(data_filter){
    "use strict";
    $('#posting_pattern_engagement_analysis').html('<div class="loader-action"></div>');
    data_filter['type'] = 'posting_pattern_engagement_analysis';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('posting_pattern_engagement_analysis', {
            chart: {
                type: 'column',
            },
          colors: ['#119EFA','#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],

            title: {
                text: '<?php echo _l('posting_pattern_engagement_analysis'); ?>',
                align: 'left'
            },

            subtitle: {
                text: '<?php echo _l('posting_pattern_engagement_analysis_note')?>',
                align: 'left'
            },

            xAxis: {
                categories: response.posting_pattern_engagement_analysis.header
            },
            credits: {
                      enabled: false
                    },
            yAxis: [{ // Primary axis
                className: 'highcharts-color-0',
                title: {
                    text: '<?php echo _l('sa_videos'); ?>'
                }
            }, { // Secondary axis
                className: 'highcharts-color-1',
                opposite: true,
                title: {
                    text: '<?php echo _l('engagement'); ?>'
                }
            }],

            plotOptions: {
                column: {
                    borderRadius: 5
                }
            },

            series: [{
                name: '<?php echo _l('sa_videos'); ?>',
                data: response.posting_pattern_engagement_analysis.data_total.video,
            }, {
                name: '<?php echo _l('engagement'); ?>',
                data: response.posting_pattern_engagement_analysis.data_total.engagement,
                yAxis: 1
            }]

        });
    });
}
</script>