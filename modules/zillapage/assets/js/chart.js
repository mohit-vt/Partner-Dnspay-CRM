$(function () {
    "use strict";

    var myChart = Highcharts.chart('chart', {
        chart: {
            type: 'areaspline'
        },
        title: {
            text: ''
        },
        xAxis: {
            // categories: ['1', '2', '3','4', '5', '6']
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        series: [{
            name: 'Earning',
            data: [0,4,3,4,17,10,30,44,33]
        }]
    });


});
