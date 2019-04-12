
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
    <div id="container" style="height: 300px; margin: 0px auto; border: 1px solid #ddd"></div>
</div>

<script>
    Highcharts.chart('container', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Análisis - Depósito Plazo'
        },

        subtitle: {
            text: ''//Source: thesolarfoundation.com
        },

        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },
        xAxis: {
            categories: <?=$json_categorie?>,
            title: {
                text: "fff"
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: false,
                enableMouseTracking: true
            }
        },

        series: <?=$json_serie?>/*[{
            name: 'Installation',
            data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
        }, {
            name: 'Manufacturing',
            data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
        }, {
            name: 'Sales & Distribution',
            data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
        }, {
            name: 'Project Development',
            data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
        }, {
            name: 'Other',
            data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
        }]*/,

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
</script>