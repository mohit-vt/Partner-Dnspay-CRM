<script type="text/javascript">
var date_filter;
var account_filter;
var social;

(function($) {
	"use strict";

    social = $('input[name=social]').val();
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });

    $(document).ready(function() {
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
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        $('#top_stats').html(response);
    });

    published_posts_with_engagement_init(data_filter);

    engagement_rate_init(data_filter);
    engagement_stats_init(data_filter);
    post_density_daily_init(data_filter);
    audience_growth_init(data_filter);
    follow_stats_init(data_filter);
    awareness_through_mention_init(data_filter);
}

function post_density_daily_init(data_filter){
    "use strict";
    $('#post_density_daily').html('<div class="loader-action"></div>');
    data_filter['type'] = 'post_density_daily';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('post_density_daily', {
            chart: {
                type: 'column'
            },
          colors: ['#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('post_density_daily') ;?>',
            },
            subtitle: {
                text: '<?php echo _l('twitter_post_density_daily_note') ;?>',
            },
            xAxis: {
                categories: response.post_density_daily.header
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total'
                },
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
                name: 'Total Post',
                data: response.post_density_daily.data_total
            }]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function engagement_rate_init(data_filter){
    "use strict";
    $('#engagement_rate').html('<div class="loader-action"></div>');
    data_filter['type'] = 'twitter_engagement_rate';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('engagement_rate', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('engagement') ;?>',
            },
            subtitle: {
                text: '<?php echo _l('twitter_engagement_note') ;?>',
            },
            xAxis: {
                categories: response.twitter_engagement_rate.header
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
                name: 'Reweets',
                data: response.twitter_engagement_rate.data_total.retweet
            }, {
                name: 'Likes',
                data: response.twitter_engagement_rate.data_total.like
            },]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function follow_stats_init(data_filter){
    "use strict";
    $('#follow_stats').html('<div class="loader-action"></div>');

    data_filter['type'] = 'follow_stats';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#follow_stats').html(response.content_html);
        Highcharts.chart('follow_rate_pie_chart', {
            chart: {
                type: 'pie'
            },
            colors: ['#84c529','#ef370dc7'],

            title: {
                text: '',
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
                    data: response.follow_rate_pie_chart.data
                }
            ]
        });
    });
}

function engagement_stats_init(data_filter){
    "use strict";
    $('#engagement_stats').html('<div class="loader-action"></div>');

    data_filter['type'] = 'engagement_stats';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#engagement_stats').html(response.content_html);
        Highcharts.chart('engagement_rate_pie_chart', {
            chart: {
                type: 'pie'
            },
            colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f',],

            title: {
                text: '',
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
                    data: response.engagement_rate_pie_chart.data
                }
            ]
        });
    });
}

function published_posts_with_engagement_init(data_filter){
    "use strict";
    $('#published_posts_with_engagement').html('<div class="loader-action"></div>');

    data_filter['type'] = 'published_posts_with_engagement';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('published_posts_with_engagement', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('published_posts_with_engagement') ;?>',
            },
            subtitle: {
                text: '<?php echo _l('twitter_published_posts_with_engagement_note') ;?>',
            },
            xAxis: [{
                categories: response.published_posts_with_engagement.header,
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Number of Post',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Avg Engagement (Like + RTs/Post)',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            legend: {
                align: 'left',
                x: 80,
                verticalAlign: 'top',
                y: 60,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}'
            },
          
            credits: {
                      enabled: false
                    },
            series: [{
                name: 'Number of Post',
                type: 'column',
                yAxis: 1,
                data: response.published_posts_with_engagement.data_total.post
            },{
                name: 'Avg Engagement (Like + RTs/Post)',
                type: 'spline',
                data: response.published_posts_with_engagement.data_total.engagement
            },  ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function audience_growth_init(data_filter){
    "use strict";
    $('#audience_growth').html('<div class="loader-action"></div>');

    data_filter['type'] = 'twitter_audience_growth';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('twitter_audience_growth', {
        colors: [ '#84c529','#ef370dc7'],

        title: {
            text: '<?php echo _l("audience_growth"); ?>'
        },
        subtitle: {
                text: '<?php echo _l('twitter_audience_growth_note') ;?>',
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
            categories: response.twitter_audience_growth.categories
        },

        series: response.twitter_audience_growth.data,
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

function awareness_through_mention_init(data_filter){
    "use strict";
    $('#awareness_through_mention').html('<div class="loader-action"></div>');

    data_filter['type'] = 'awareness_through_mention';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('awareness_through_mention', {
        colors: [ '#84c529','#ef370dc7'],

        title: {
            text: '<?php echo _l("sa_awareness_through_mentions"); ?>'
        },
        subtitle: {
                text: '<?php echo _l('awareness_through_mention_note') ;?>',
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
            categories: response.awareness_through_mention.categories
        },

        series: response.awareness_through_mention.data,
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
</script>