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
    dashboard_custom_view('last_30_days',"<?php echo _l('last_30_days'); ?>",'date_filter');
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

    engagement_vs_total_posts_published_init(data_filter);
    daily_post_impression_trend_init(data_filter);
    account_performance(data_filter);
    facebook_engagement_init(data_filter);
    twitter_engagement_init(data_filter);
    tiktok_engagement_init(data_filter);
    youtube_engagement_init(data_filter);
    instagram_engagement_init(data_filter);
}

function dashboard_account_custom_view(value, $lang, custom_input_name, clear_other_filters) {
    "use strict";

    account_filter = value;

    $('#btn_account_filter').html('<i class="fa fa-filter" aria-hidden="true"></i> '+$lang);

    dashboard_account_custom_view('last_30_days',"<?php echo _l('last_30_days'); ?>",'last_30_days');
}

function engagement_vs_total_posts_published_init(data_filter){
    "use strict";
    $('#engagement_vs_total_posts_published').html('<div class="loader-action"></div>');

    data_filter['type'] = 'engagement_vs_total_posts_published';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('engagement_vs_total_posts_published', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l("engagement_vs_total_posts_published"); ?>',
            },
            subtitle: {
                text: '<?php echo _l('engagement_vs_total_posts_published_note')?>'
            },
            xAxis: {
                categories: response.engagement_vs_total_posts_published.header
            },

            yAxis: [{
                min: 0,
                title: {
                    text: '<?php echo _l('engagement')?>'
                },
                stackLabels: {
                    enabled: true
                }
            }, { // Secondary yAxis
                title: {
                    text: '<?php echo _l('sa_number_of_posts')?>',
                    style: {
                        color: '#15f34f'
                    }
                },
                labels: {
                    style: {
                        color: '#15f34f'
                    }
                },
                opposite: true
            }],
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
            series: [
                {
                name: 'Comment',
                 type: 'column',
                data: response.engagement_vs_total_posts_published.data_total.comment
            }, {
                name: 'Like',
                 type: 'column',
                data: response.engagement_vs_total_posts_published.data_total.like
            }, {
                name: 'Share',
                 type: 'column',
                data: response.engagement_vs_total_posts_published.data_total.report
            }, {
                name: 'Post',
                    yAxis: 1,
                    type: 'spline',
                data: response.engagement_vs_total_posts_published.data_total.post
            }, ]
        });

    });
}


function daily_post_impression_trend_init(data_filter){
    "use strict";
    $('#daily_post_impression_trend').html('<div class="loader-action"></div>');

    data_filter['type'] = 'daily_post_impression_trend';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

   Highcharts.chart('daily_post_impression_trend', {
        colors: [ '#1877F2','#E1306C','#69C9D0','#000000', '#FF0000'],

        title: {
            text: '<?php echo _l("daily_post_impression_trend"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('daily_post_impression_trend_note')?>'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
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
            categories: response.daily_post_impression_trend.categories
        },

        series: response.daily_post_impression_trend.data,
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
    });
}
function twitter_engagement_init(data_filter){
    "use strict";
    $('#twitter_engagement').html('<div class="loader-action"></div>');

    data_filter['type'] = 'twitter_engagement';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#twitter_engagement').html(response.content_html);
        Highcharts.chart('twitter_engagement', {
            chart: {
                type: 'pie'
            },
            colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f',],

            title: {
                text: '<?php echo _l('twitter_engagement'); ?>',
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
                    data: response.twitter_engagement.data
                }
            ]
        });
    });
}

function facebook_engagement_init(data_filter){
    "use strict";
    $('#facebook_engagement').html('<div class="loader-action"></div>');

    data_filter['type'] = 'facebook_engagement';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#facebook_engagement').html(response.content_html);
        Highcharts.chart('facebook_engagement', {
            chart: {
                type: 'pie'
            },
            colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f',],

            title: {
                text: '<?php echo _l('facebook_engagement'); ?>',
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
                    data: response.facebook_engagement.data
                }
            ]
        });
    });
}

function account_performance(data_filter){
    "use strict";
    $('#account_performance').html('<div class="loader-action"></div>');

    data_filter['type'] = 'account_performance';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
         Highcharts.chart('account_performance', {
        chart: {
            type: 'bar',
            inverted: true
        },
          colors: ['#119EFA','#ff3399'],

        title: {
            text: '<?php echo _l('account_performance'); ?>',
            align: 'left'
        },
        subtitle: {
            text: '<?php echo _l('account_performance_note')?>',
            align: 'left'
        },
        credits: {
          enabled: false
        },
        xAxis: {
            categories: response.account_performance.categories,
            title: {
                text: null
            },
            labels: {
                useHTML: true,
                formatter: function () {
                    if (this.value === 'Facebook') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/facebook_icon.png" width="30" height="30" alt="like"/>';
                    } else if (this.value === 'Instagram') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/instagram_icon.png" width="30" height="30" alt="like"/>';
                        return '<img src="https://path/to/love_icon.png" width="30" height="30" alt="love"/>';
                    } else if (this.value === 'Tiktok') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/tiktok_icon.png" width="30" height="30" alt="like"/>';
                    } else if (this.value === 'Twitter') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/twitter_icon.png" width="30" height="30" alt="like"/>';
                        return '<img src="https://path/to/love_icon.png" width="30" height="30" alt="love"/>';
                    } else if (this.value === 'Youtube') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/youtube_icon.png" width="30" height="30" alt="like"/>';
                    }
                    return this.value;
                }
            }
        },
        yAxis: {
            min: 0,
            allowDecimals: false,
            title: {
                text: '<?php echo _l('account_performance'); ?>',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            },
        },
        legend: {
            enabled: false 
        },
        plotOptions: {
            
            series: {
                stacking: 'normal',
                borderRadius: '50%'
            }
        },

        tooltip: {
            
        },
        
        series: [{
            name: '<?php echo _l('account_performance'); ?>',
            data: response.account_performance.data
        }]
    });
    });
}

function tiktok_engagement_init(data_filter){
    "use strict";
    $('#tiktok_engagement').html('<div class="loader-action"></div>');

    data_filter['type'] = 'tiktok_engagement';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#tiktok_engagement').html(response.content_html);
        Highcharts.chart('tiktok_engagement', {
            chart: {
                type: 'pie'
            },
            colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f',],

            title: {
                text: '<?php echo _l('tiktok_engagement'); ?>',
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
                    data: response.tiktok_engagement.data
                }
            ]
        });
    });
}

function youtube_engagement_init(data_filter){
    "use strict";
    $('#youtube_engagement').html('<div class="loader-action"></div>');

    data_filter['type'] = 'youtube_engagement';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#youtube_engagement').html(response.content_html);
        Highcharts.chart('youtube_engagement', {
            chart: {
                type: 'pie'
            },
            colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f',],

            title: {
                text: '<?php echo _l('youtube_engagement'); ?>',
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
                    data: response.youtube_engagement.data
                }
            ]
        });
    });
}

function instagram_engagement_init(data_filter){
    "use strict";
    $('#instagram_engagement').html('<div class="loader-action"></div>');

    data_filter['type'] = 'instagram_engagement_pie_chart';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#instagram_engagement').html(response.content_html);
        Highcharts.chart('instagram_engagement', {
            chart: {
                type: 'pie'
            },
            colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f',],

            title: {
                text: '<?php echo _l('instagram_engagement'); ?>',
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
                    data: response.instagram_engagement.data
                }
            ]
        });
    });
}
</script>