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

    audience_growth_init(data_filter);
    published_posts_with_engagement_init(data_filter);
    post_rate_init(data_filter);
    post_stats_init(data_filter);
    post_density_daily_init(data_filter);
    fan_by_age(data_filter);
    fan_by_gender(data_filter);

    $('#fan_stats').html('<div class="loader-action"></div>');
    data_filter['type'] = 'fan_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        $('#fan_stats').html(response);
    });

    engagement_rate_init(data_filter);
    engagement_stats_init(data_filter);
    reactions_overview(data_filter);
    engagement_by_day_time(data_filter);
    active_users_by_day(data_filter);
}

function fan_by_age(data_filter){
    "use strict";

    $('#fan_by_age').html('<div class="loader-action"></div>');
    data_filter['type'] = 'fan_by_age';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        Highcharts.chart('fan_by_age', {
        chart: {
            type: 'bar'
        },
          colors: ['#119EFA','#ff3399'],

        title: {
            text: '<?php echo _l('fan_by_age'); ?>',
            align: 'left'
        },
        subtitle: {
                text: '<?php echo _l('fan_by_age_note') ;?>',
            align: 'left'
            },
        credits: {
          enabled: false
        },
        xAxis: [{
            categories: response.fan_by_age.categories,
            reversed: false,
            labels: {
                step: 1
            },
            accessibility: {
                description: 'Age (male)'
            }
        }, { // mirror axis on right side
            opposite: true,
            reversed: false,
            categories: response.fan_by_age.categories,
            linkedTo: 0,
            labels: {
                step: 1
            },
            accessibility: {
                description: 'Age (female)'
            }
        }],
        yAxis: {
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                return (
                  `${Math.abs(this.value)}`
                );
              },
            },
            
        },

        plotOptions: {
            series: {
                stacking: 'normal',
                borderRadius: '50%'
            }
        },

        tooltip: {
            formatter: function () {
                const point = this;
                return (
                  `<b>${this.series.name}, age ${this.point.category}</b><br/>` +
                  `Total: `+ Highcharts.numberFormat(Math.abs(this.point.y), 0, '', ',')
                );
              },
        },
        
        series: response.fan_by_age.data
    });
});
}

function fan_by_gender(data_filter){
    "use strict";

    $('#fan_by_gender').html('<div class="loader-action"></div>');
    data_filter['type'] = 'fan_by_gender';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('fan_by_gender', {
            chart: {
                type: 'pie'
            },
            colors: ['#119EFA','#ff3399'],

            title: {
                text: '<?php echo _l('fan_by_gender'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('fan_by_gender_note') ;?>',
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
                    data: response.fan_by_gender.data
                }
            ]
        });
    });
}

function reactions_overview(data_filter){
    "use strict";

    $('#reactions_overview').html('<div class="loader-action"></div>');

    data_filter['type'] = 'reactions_overview';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
         Highcharts.chart('reactions_overview', {
        chart: {
            type: 'bar',
            inverted: true
        },
          colors: ['#119EFA','#ff3399'],

        title: {
            text: '<?php echo _l('reactions_overview'); ?>',
            align: 'left'
        },
        subtitle: {
                text: '<?php echo _l('reactions_overview_note') ;?>',
            align: 'left'
            },
        credits: {
          enabled: false
        },
        xAxis: {
            categories: response.reactions_overview.categories,
            title: {
                text: null
            },
            labels: {
                useHTML: true,
                formatter: function () {
                    if (this.value === 'Like') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/like_icon.png" width="30" height="30" alt="like"/>';
                    } else if (this.value === 'Love') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/love_icon.png" width="30" height="30" alt="like"/>';
                        return '<img src="https://path/to/love_icon.png" width="30" height="30" alt="love"/>';
                    } else if (this.value === 'Wow') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/wow_icon.png" width="30" height="30" alt="like"/>';
                    } else if (this.value === 'Haha') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/haha_icon.png" width="30" height="30" alt="like"/>';
                        return '<img src="https://path/to/love_icon.png" width="30" height="30" alt="love"/>';
                    } else if (this.value === 'Sad') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/sad_icon.png" width="30" height="30" alt="like"/>';
                    } else if (this.value === 'Angry') {
                        return '<img src="'+site_url+'/modules/social_analytic/assets/images/angry_icon.png" width="30" height="30" alt="like"/>';
                    }
                    return this.value;
                }
            }
        },
        yAxis: {
            min: 0,
            allowDecimals: false,
            title: {
                text: '<?php echo _l('sa_number_of_reactions'); ?>',
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
            valueSuffix: ' reactions'
        },
        
        series: [{
            name: '<?php echo _l('sa_reactions'); ?>',
            data: response.reactions_overview.data
        }]
    });
    });
}

function engagement_by_day_time(data_filter){
    "use strict";

    $('#engagement_by_day_time').html('<div class="loader-action"></div>');
    data_filter['type'] = 'engagement_by_day_time';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('engagement_by_day_time', {
        chart: {
            type: 'heatmap',
            plotBorderWidth: 1
        },
        title: {
            text: '<?php echo _l('engagement_by_day_time'); ?>'
        },
        credits: {
          enabled: false
        },
        xAxis: {
            categories: [
                '00:00', '01:00', '02:00', '03:00', '04:00',
                '05:00', '06:00', '07:00', '08:00', '09:00',
                '10:00', '11:00', '12:00', '13:00', '14:00',
                '15:00', '16:00', '17:00', '18:00', '19:00',
                '20:00', '21:00', '22:00', '23:00'
            ],
            gridLineColor: '#E0E0E0',
        },
        yAxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], // Các ngày trong tuần
            title: null,
            gridLineColor: '#E0E0E0',
        },
        colorAxis: {
            min: 0,
            minColor: '#FFFFFF',
            maxColor: '#32CD32'
        },
        legend: {
            align: 'right',
            layout: 'vertical',
            margin: 0,
            verticalAlign: 'top',
            y: 25,
            symbolHeight: 280
        },
        tooltip: {
            formatter: function () {
                return 'Engagement: ' + this.point.value + '<br>' + 
                       'Hour: ' + this.series.xAxis.categories[this.point.x] + '<br>' +
                       'Day: ' + this.series.yAxis.categories[this.point.y];
            }
        },
        series: [{
            name: 'Engagement',
            borderWidth: 1,
            data: response.engagement_by_day_time,
            dataLabels: {
                color: '#000000'
            }
        }]
    });
});
}

function post_stats_init(data_filter){
    "use strict";

    $('#post_stats').html('<div class="loader-action"></div>');

    data_filter['type'] = 'post_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#post_stats').html(response.content_html);
        Highcharts.chart('post_by_type_chart', {
            chart: {
                type: 'pie'
            },
            colors: ['#ef370dc7', '#119EFA', '#DDDF00',],

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
                    data: response.post_by_type_chart.data
                }
            ]
        });
    });
}
function engagement_stats_init(data_filter){
    "use strict";

    $('#engagement_stats').html('<div class="loader-action"></div>');

    data_filter['type'] = 'engagement_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
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
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('published_posts_with_engagement', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('published_posts_with_engagement')?>',
            },
            subtitle: {
                text: '<?php echo _l('published_posts_with_engagement_note')?>',
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
                    text: 'Avg Engagement (Reactions + Shares + Comments)',
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
                name: 'Avg Engagement (Reactions + Shares + Comments)',
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

    data_filter['type'] = 'audience_growth';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('audience_growth', {
        colors: [ '#99ff66','#84c529','#ffcc99','#ef370dc7'],

        title: {
            text: '<?php echo _l("audience_growth"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('audience_growth_note')?>',
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
            categories: response.audience_growth.categories
        },

        series: response.audience_growth.data,
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

function post_rate_init(data_filter){
    "use strict";
    $('#post_rate').html('<div class="loader-action"></div>');
    
    data_filter['type'] = 'post_rate';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('post_rate', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('post_rate')?>',
            },
            subtitle: {
                text: '<?php echo _l('post_rate_note')?>',
            },
            xAxis: {
                categories: response.post_rate.header
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
                name: 'Videos',
                data: response.post_rate.data_total.video
            }, {
                name: 'Photos',
                data: response.post_rate.data_total.photo
            }, {
                name: 'Links',
                data: response.post_rate.data_total.link
            }, ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function post_density_daily_init(data_filter){
    "use strict";
    $('#post_density_daily').html('<div class="loader-action"></div>');
    data_filter['type'] = 'post_density_daily';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('post_density_daily', {
            chart: {
                type: 'column'
            },
          colors: ['#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('post_density_daily');?>',
            },
            subtitle: {
                text: '<?php echo _l('post_density_daily_note') ;?>',
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
    data_filter['type'] = 'engagement_rate';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('engagement_rate', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('engagement_rate') ;?>',
            },
            subtitle: {
                text: '<?php echo _l('engagement_rate_note') ;?>',
            },
            xAxis: {
                categories: response.engagement_rate.header
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
                name: 'Comments',
                data: response.engagement_rate.data_total.comment
            }, {
                name: 'Reactions',
                data: response.engagement_rate.data_total.reaction
            }, {
                name: 'Shares',
                data: response.engagement_rate.data_total.share
            },
            {
                name: 'Clicks',
                data: response.engagement_rate.data_total.click
            }, ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function active_users_by_day(data_filter){
    "use strict";
    $('#active_users_by_day').html('<div class="loader-action"></div>');

    data_filter['type'] = 'active_users_by_day';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('active_users_by_day', {
        colors: [ '#84c529'],

        title: {
            text: '<?php echo _l("active_users_by_day"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('active_users_by_day_note') ;?>',
        },
        legend: {
            
              enabled: false
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
            categories: response.active_users_by_day.categories
        },

        series: response.active_users_by_day.data,
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