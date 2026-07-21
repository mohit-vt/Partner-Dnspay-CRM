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
    publishing_behavior_init(data_filter);
    post_stats_init(data_filter);
    active_users_by_hours_init(data_filter);
    active_users_by_days_init(data_filter);
    instagram_impressions_init(data_filter);

    $('#impressions_stats').html('<div class="loader-action"></div>');
    data_filter['type'] = 'impressions_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        $('#impressions_stats').html(response);
    });

    instagram_engagement_init(data_filter);

    $('#engagement_stats').html('<div class="loader-action"></div>');
    data_filter['type'] = 'engagement_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#engagement_stats').html(response.content_html);
    });

    instagram_stories_performance_init(data_filter);
    stories_performance_init(data_filter);

    $('#stories_stats').html('<div class="loader-action"></div>');
    data_filter['type'] = 'stories_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        $('#stories_stats').html(response);
    });

    follower_by_age(data_filter);
    follower_by_gender(data_filter);
    $('#follower_stats').html('<div class="loader-action"></div>');
    data_filter['type'] = 'follower_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        $('#follower_stats').html(response);
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
            text: '<?php echo _l('instagram_audience_growth_note') ;?>',
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


function publishing_behavior_init(data_filter){
    "use strict";
    $('#publishing_behavior').html('<div class="loader-action"></div>');
    data_filter['type'] = 'publishing_behavior';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('publishing_behavior', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('instagram_publishing_behavior'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('instagram_publishing_behavior_note') ;?>',
            },
            xAxis: {
                categories: response.publishing_behavior.header
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
                data: response.publishing_behavior.data_total.video
            }, {
                name: 'Photos',
                data: response.publishing_behavior.data_total.photo
            }, {
                name: 'Carousels',
                data: response.publishing_behavior.data_total.carousel
            },
            ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function post_stats_init(data_filter){
    "use strict";
    $('#post_stats').html('<div class="loader-action"></div>');

    data_filter['type'] = 'post_stats';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        $('#post_stats').html(response.content_html);
    });
}

function active_users_by_hours_init(data_filter){
    "use strict";
    $('#active_users_by_hours').html('<div class="loader-action"></div>');

    data_filter['type'] = 'active_users_by_hours';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('active_users_by_hours', {
        colors: [ '#84c529','#ef370dc7'],

        title: {
            text: '<?php echo _l("active_users_by_hours"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('instagram_active_users_by_hours_note') ;?>',
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
            categories: response.active_users_by_hours.categories
        },

        series: response.active_users_by_hours.data,
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

function active_users_by_days_init(data_filter){
    "use strict";
    $('#active_users_by_days').html('<div class="loader-action"></div>');

    data_filter['type'] = 'active_users_by_days';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('active_users_by_days', {
        colors: ['#ef370dc7'],

        title: {
            text: '<?php echo _l("active_users_by_days"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('instagram_active_users_by_days_note') ;?>',
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
            categories: response.active_users_by_days.categories
        },

        series: response.active_users_by_days.data,
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

function instagram_impressions_init(data_filter){
    "use strict";
    $('#instagram_impressions').html('<div class="loader-action"></div>');

    data_filter['type'] = 'instagram_impressions';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('instagram_impressions', {
        colors: [ '#ffcc99'],

        title: {
            text: '<?php echo _l("instagram_impressions"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('instagram_impressions_note') ;?>',
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
            categories: response.instagram_impressions.categories
        },

        series: response.instagram_impressions.data,
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

function instagram_engagement_init(data_filter){
    "use strict";
    $('#instagram_engagement').html('<div class="loader-action"></div>');

    data_filter['type'] = 'instagram_engagement';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

    Highcharts.chart('instagram_engagement', {
        colors: [ '#ffcc99'],

        title: {
            text: '<?php echo _l("instagram_engagement"); ?>'
        },
        subtitle: {
            text: '<?php echo _l('instagram_engagement_note') ;?>',
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
            categories: response.instagram_engagement.categories
        },

        series: response.instagram_engagement.data,
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

function instagram_stories_performance_init(data_filter){
    "use strict";
    $('#instagram_stories_performance').html('<div class="loader-action"></div>');
    data_filter['type'] = 'instagram_stories_performance';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('instagram_stories_performance', {
            chart: {
                type: 'column'
            },
          colors: ['#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('instagram_stories_performance'); ?>',
            },
            subtitle: {
            text: '<?php echo _l('instagram_stories_performance_note') ;?>',
        },
            xAxis: {
                categories: response.instagram_stories_performance.header
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
                name: 'Stories Published',
                data: response.instagram_stories_performance.data_total.published_stories
            },
            ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}


function stories_performance_init(data_filter){
    "use strict";
    $('#stories_performance').html('<div class="loader-action"></div>');
    data_filter['type'] = 'stories_performance';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('stories_performance', {
            chart: {
                type: 'column'
            },
          colors: ['#ef370dc7', '#119EFA', '#DDDF00', '#15f34f', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B'],
            title: {
                text: '<?php echo _l('stories_performance'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('stories_performance_note') ;?>',
            },
            xAxis: {
                categories: response.stories_performance.header
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
                name: '<?php echo _l('story_replies'); ?>',
                data: response.stories_performance.data_total.story_replies
            }, {
                name: '<?php echo _l('story_tap_backs'); ?>',
                data: response.stories_performance.data_total.story_tap_backs
            }, {
                name: '<?php echo _l('story_taps_forward'); ?>',
                data: response.stories_performance.data_total.story_taps_forward
            }, {
                name: '<?php echo _l('story_exits'); ?>',
                data: response.stories_performance.data_total.story_exits
            },

            ]
        });

    //hide boxloading
    $('#box-loading').html('');
    });
}

function follower_by_age(data_filter){
    "use strict";
    $('#follower_by_age').html('<div class="loader-action"></div>');
    data_filter['type'] = 'follower_by_age';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);
        Highcharts.chart('follower_by_age', {
        chart: {
            type: 'bar'
        },
          colors: ['#119EFA','#ff3399'],

        title: {
            text: '<?php echo _l('follower_by_age'); ?>',
            align: 'left'
        },
        subtitle: {
                text: '<?php echo _l('instagram_follower_by_age_note') ;?>',
            align: 'left'
            },
        credits: {
          enabled: false
        },
        xAxis: [{
            categories: response.follower_by_age.categories,
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
            categories: response.follower_by_age.categories,
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
        
        series: response.follower_by_age.data
    });
});
}

function follower_by_gender(data_filter){
    "use strict";
    $('#follower_by_gender').html('<div class="loader-action"></div>');
    data_filter['type'] = 'follower_by_gender';
    $.post(site_url + 'social_analytic/social_analytic_client/get_data_analytics', data_filter).done(function(response){
        response = JSON.parse(response);

        Highcharts.chart('follower_by_gender', {
            chart: {
                type: 'pie'
            },
            colors: ['#119EFA','#ff3399'],

            title: {
                text: '<?php echo _l('follower_by_gender'); ?>',
            },
            subtitle: {
                text: '<?php echo _l('instagram_follower_by_gender_note') ;?>',
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
                    data: response.follower_by_gender.data
                }
            ]
        });
    });
}
</script>