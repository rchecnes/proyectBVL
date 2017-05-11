
<div id="container3" style="height: 600px; margin: 0px auto; border: 1px solid #ddd">
    
</div>


<script type="text/javascript">
$(document).ready(function(){
    //Tamaño de la grafica
    //var heightc = ($("#tabla_grafico2").height() > 300)?$("#tabla_grafico2").height():300;
    //$("#container2").css({height:heightc+'px'});
    //console.log();
});

Highcharts.chart('container3', {
    /*chart: {
        type: 'line'
    },*/
    title: {
        text: 'Evolución De Precio'
    },
    subtitle: {
        text: '',
        enabled: false
    },
    xAxis: {
        categories: <?=$categoria?>
    },
    yAxis: {
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
        }
    },
    series: [{
        name: 'Precio',
        data: <?=str_replace('"','',$serie_lineal)?>
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