<div id="container3" style="height: 600px; width: 100%; margin: 0px auto; border: 1px solid #ddd">
    
</div>


<script type="text/javascript">
$(document).ready(function(){
    //Tamaño de la grafica
    //var heightc = ($("#tabla_grafico2").height() > 300)?$("#tabla_grafico2").height():300;
    //$("#container2").css({height:heightc+'px'});
    //console.log();
});
Highcharts.chart('container3', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Evolución De Precio'
    },
    subtitle: {
        text: '',
        enabled: false
    },
    xAxis: {
        categories: <?=$categoria?>,
            labels:{
            rotation: -80
        },
        style: {
            color: 'red',
            fontSize:'10px',
        }
    },
    yAxis: {
        min: <?=$miny?>,
        max: <?=$maxy?>,
        title: {
            text: 'Temperature (°C)',
            enabled: false
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: false
            },
            enableMouseTracking: false
        },
        series: {
            cursor: 'pointer',
            events: {
                /*hide: function (e) {
                    var chart = $("#container3").highcharts();
                    var series = chart.series[2];
                    series.hide();
                    //series.hide();
                    //console.log(series);
                },
                show: function (e) {
                    console.log(e);
                },*/
                legendItemClick: function(event) {
                    var seriesName = this.name;
                    var series      = this.chart.series;
                    //console.log(seriesName);
                    //console.log(series);
                    
                    if (seriesName == 'Max 12M' || seriesName == 'Min 12M') {
                        for (var i = 0; i < series.length; i++)
                        {
                            if (series[i].name != seriesName && (series[i].name == 'Max 12M' || series[i].name == 'Min 12M')){
                                
                                series[i].visible ? series[i].hide() : series[i].show();
                            } 
                        }
                    }else if (seriesName == 'Max 6M' || seriesName == 'Min 6M') {
                        for (var i = 0; i < series.length; i++)
                        {
                            if (series[i].name != seriesName && (series[i].name == 'Max 6M' || series[i].name == 'Min 6M')){
                                
                                series[i].visible ? series[i].hide() : series[i].show();
                            } 
                        }
                    }else if (seriesName == 'Max 3M' || seriesName == 'Min 3M') {
                        for (var i = 0; i < series.length; i++)
                        {
                            if (series[i].name != seriesName && (series[i].name == 'Max 3M' || series[i].name == 'Min 3M')){
                                
                                series[i].visible ? series[i].hide() : series[i].show();
                            } 
                        }
                    }
                    
                    //return false;
                }
            }
        }
    },
    series: [{
        name: 'Precio',
        visible: true,
        data: <?=str_replace('"','',$serie_lineal)?>
    },{
        name: 'Max 12M',
        color:'#61ab61',
        data: <?=str_replace('"','',$serie_max12)?>
    },{
        name: 'Min 12M',
        color:'#61ab61',
        data: <?=str_replace('"','',$serie_min12)?>
    },{
        name: 'Max 6M',
        color: '#8085e9',
        data: <?=str_replace('"','',$serie_max6)?>
    },{
        name: 'Min 6M',
        color: '#8085e9',
        data: <?=str_replace('"','',$serie_min6)?>
    },{
        name: 'Max 3M',
        color: '#f15c80',
        data: <?=str_replace('"','',$serie_max3)?>
    },{
        name: 'Min 3M',
        color: '#f15c80',
        data: <?=str_replace('"','',$serie_min3)?>
    }]
});
</script>