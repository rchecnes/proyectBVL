
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
            enableMouseTracking: false,
            marker: {
                enabled: true
            },
        }
    },
    series: [{
        name: 'Precio',
        visible: true,
        data: <?=str_replace('"','',$serie_lineal)?>
    },{
        name: 'Max 12M',
        visible: true,
        data: <?=str_replace('"','',$serie_max12)?>
    },{
        name: 'Min 12M',
        visible: true,
        data: <?=str_replace('"','',$serie_min12)?>
    },{
        name: 'Max 6M',
        visible: true,
        data: <?=str_replace('"','',$serie_max6)?>
    },{
        name: 'Min 6M',
        visible: true,
        data: <?=str_replace('"','',$serie_min6)?>
    },{
        name: 'Max 3M',
        visible: true,
        data: <?=str_replace('"','',$serie_max3)?>
    },{
        name: 'Min 3M',
        visible: true,
        data: <?=str_replace('"','',$serie_min3)?>
    }]


});
</script>

<!--{
        name: 'Histogram',
        type: 'column',
        data: histogram(data, 10),
        pointPadding: 0,
        groupPadding: 0,
        pointPlacement: 'between'
    }-->