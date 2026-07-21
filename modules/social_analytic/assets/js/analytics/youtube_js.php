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

    gained_lost_chart_init(data_filter);
    daily_video_views_graph_init(data_filter);
    daily_video_views_chart_init(data_filter);
    estimated_minutes_watched_init(data_filter);
    videos_published_chart(data_filter);
    like_vs_dislike_chart(data_filter);
    comments_chart_init(data_filter);
    shares_chart_init(data_filter);
    subscribers_by_age(data_filter);
    subscribers_by_gender(data_filter);
    $('#subscriber_stats').html('<div class="loader-action"></div>');
    data_filter['type'] = 'subscriber_stats';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        $('#subscriber_stats').html(response);
    });

    $('#viewer_stats').html('<div class="loader-action"></div>');
    data_filter['type'] = 'viewer_stats';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        $('#viewer_stats').html(response);
    });

    viewer_by_age(data_filter);
    viewer_by_gender(data_filter);
    viewer_by_device(data_filter);
}

function estimated_minutes_watched_init(data_filter){
    "use strict";
    $('#estimated_minutes_watched').html('<div class="loader-action"></div>');

    data_filter['type'] = 'estimated_minutes_watched';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('estimated_minutes_watched', {
        colors: [ '#ef370dc7','#84c529','#ffcc99','#ef370dc7'],

        title: {
            text: '<?php echo _l("estimated_minutes_watched"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('estimated_minutes_watched_note'); ?>',
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
            categories: response.estimated_minutes_watched.categories
        },

        series: response.estimated_minutes_watched.data,
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


function shares_chart_init(data_filter){
    "use strict";
    $('#shares_chart').html('<div class="loader-action"></div>');

    data_filter['type'] = 'shares_chart';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('shares_chart', {
        colors: [ '#ffcc99','#ef370dc7'],

        title: {
            text: '<?php echo _l("shares_chart"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('shares_chart_note'); ?>',
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
            categories: response.shares_chart.categories
        },

        series: response.shares_chart.data,
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

function comments_chart_init(data_filter){
    "use strict";
    $('#comments_chart').html('<div class="loader-action"></div>');

    data_filter['type'] = 'comments_chart';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('comments_chart', {
        colors: [ '#84c529'],

        title: {
            text: '<?php echo _l("comments_chart"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('comments_chart_note'); ?>',
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
            categories: response.comments_chart.categories
        },

        series: response.comments_chart.data,
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

function daily_video_views_graph_init(data_filter){
    "use strict";
    $('#daily_video_views_graph').html('<div class="loader-action"></div>');

    data_filter['type'] = 'daily_video_views_graph';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('daily_video_views_graph', {
        colors: [ '#99ff66','#84c529','#ffcc99','#ef370dc7'],

        title: {
            text: '<?php echo _l("daily_video_views_graph"); ?>'
        },

        subtitle: {
            text: '<?php echo _l('daily_video_views_graph_note'); ?>',
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
            categories: response.daily_video_views_graph.categories
        },

        series: response.daily_video_views_graph.data,
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

function gained_lost_chart_init(data_filter){
    "use strict";
    $('#gained_lost_chart').html('<div class="loader-action"></div>');
    data_filter['type'] = 'gained_lost_chart';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('gained_lost_chart', {
            chart: {
                type: 'column'
            },
          colors: ['#15f34f', '#ef370dc7',  '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('gained_lost_subscribers'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('gained_lost_subscribers_note') ;?>',
            },
            xAxis: {
                categories: response.gained_lost_chart.header
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
                name: 'Gained',
                data: response.gained_lost_chart.data_total.gained
            },{
                name: 'Lost',
                data: response.gained_lost_chart.data_total.lost
            }, ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function like_vs_dislike_chart(data_filter){
    "use strict";
    $('#like_vs_dislike_chart').html('<div class="loader-action"></div>');
    data_filter['type'] = 'like_vs_dislike_chart';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('like_vs_dislike_chart', {
            chart: {
                type: 'column'
            },
          colors: ['#15f34f', '#ef370dc7',  '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('like_vs_dislike_chart'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('like_vs_dislike_chart_note') ;?>',
            },
            xAxis: {
                categories: response.like_vs_dislike_chart.header
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
                name: 'Like',
                data: response.like_vs_dislike_chart.data_total.like
            },{
                name: 'Dislike',
                data: response.like_vs_dislike_chart.data_total.dislike
            }, ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function daily_video_views_chart_init(data_filter){
    "use strict";
    $('#daily_video_views_chart').html('<div class="loader-action"></div>');
    data_filter['type'] = 'daily_video_views_chart';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('daily_video_views_chart', {
            chart: {
                type: 'column'
            },
          colors: ['#24CBE5','#ef370dc7',  '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('daily_video_views_chart'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('daily_video_views_chart_note') ;?>',
            },
            xAxis: {
                categories: response.daily_video_views_chart.header
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
                name: '<?php echo _l('subscriber'); ?>',
                data: response.daily_video_views_chart.data_total.subscriber
            },{
                name: '<?php echo _l('non_subscriber'); ?>',
                data: response.daily_video_views_chart.data_total.non_subscriber
            }, ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}
function videos_published_chart(data_filter){
    "use strict";
    $('#videos_published').html('<div class="loader-action"></div>');
    data_filter['type'] = 'videos_published';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
    Highcharts.chart('videos_published', {
            chart: {
                type: 'column'
            },
          colors: ['#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('sa_videos_published'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('videos_published_note') ;?>',
            },
            xAxis: {
                categories: response.videos_published.header
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
                name: 'Videos Published',
                data: response.videos_published.data_total.published_stories
            },
            ]
        });
    });
}


function subscribers_by_age(data_filter){
    "use strict";
    $('#subscribers_by_age').html('<div class="loader-action"></div>');
    data_filter['type'] = 'subscribers_by_age';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        Highcharts.chart('subscribers_by_age', {
        chart: {
            type: 'bar'
        },
          colors: ['#119EFA','#ff3399'],

        title: {
            text: '<?php echo _l('subscribers_by_age'); ?>',
            align: 'left'
        },
        subtitle: {
                text: '<?php echo _l('subscribers_by_age_note') ;?>',
            align: 'left'
            },
        credits: {
          enabled: false
        },
        xAxis: [{
            categories: response.subscribers_by_age.categories,
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
            categories: response.subscribers_by_age.categories,
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
        
        series: response.subscribers_by_age.data
    });
});
}

function subscribers_by_gender(data_filter){
    "use strict";
    $('#subscribers_by_gender').html('<div class="loader-action"></div>');
    data_filter['type'] = 'subscribers_by_gender';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('subscribers_by_gender', {
            chart: {
                type: 'pie'
            },
            colors: ['#119EFA','#ff3399'],

            title: {
                text: '<?php echo _l('subscribers_by_gender'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('subscribers_by_gender_note') ;?>',
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
                    data: response.subscribers_by_gender.data
                }
            ]
        });
    });
}

function viewer_by_age(data_filter){
    "use strict";
    $('#viewer_by_age').html('<div class="loader-action"></div>');
    data_filter['type'] = 'viewer_by_age';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        Highcharts.chart('viewer_by_age', {
        chart: {
            type: 'bar'
        },
          colors: ['#119EFA','#ff3399'],

        title: {
            text: '<?php echo _l('viewer_by_age'); ?>',
            align: 'left'
        },
        subtitle: {
                text: '<?php echo _l('viewer_by_age_note') ;?>',
            align: 'left'
            },
        credits: {
          enabled: false
        },
        xAxis: [{
            categories: response.viewer_by_age.categories,
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
            categories: response.viewer_by_age.categories,
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
        
        series: response.viewer_by_age.data
    });
});
}

function viewer_by_gender(data_filter){
    "use strict";
    $('#viewer_by_gender').html('<div class="loader-action"></div>');
    data_filter['type'] = 'viewer_by_gender';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('viewer_by_gender', {
            chart: {
                type: 'pie'
            },
            colors: ['#119EFA','#ff3399'],

            title: {
                text: '<?php echo _l('viewer_by_gender'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('viewer_by_gender_note') ;?>',
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
                    data: response.viewer_by_gender.data
                }
            ]
        });
    });
}


function viewer_by_device(data_filter){
    "use strict";
    $('#viewer_by_device').html('<div class="loader-action"></div>');
    data_filter['type'] = 'viewer_by_device';
    $.post(admin_url + 'social_analytic/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('viewer_by_device', {
            chart: {
                type: 'pie'
            },
            colors: ['#ef370dc7','#84c529','#ffcc99',],

            title: {
                text: '<?php echo _l('viewer_by_device'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('viewer_by_device_note') ;?>',
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
                    data: response.viewer_by_device.data
                }
            ]
        });
    });
}

</script>