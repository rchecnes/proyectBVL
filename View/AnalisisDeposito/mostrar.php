
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <table class="table table-bordered">
    <?php
    echo '<tr>';
        echo '<td>&nbsp;</td>';
    foreach($categorie as $cat){
        echo '<td>'.$cat.'</td>';
    }
    echo '</tr>';
    foreach($emp_tasa as $key => $emp){
        echo '<tr>';
            echo '<td>'.$emp['dh_emp_id']."-".$emp['dp_nomb_emp']." - ".$emp['dp_nomb_prod'].'</td>';
        foreach($emp['detalle'] as $d => $val){

            //$detalle[] = (double)number_format($val['dh_tea'],2,'.','');
            //if(!in_array($val['dh_plazo'], $categorie, true)){
                //$categorie[] = ($val['dh_plazo']=="9999999999")?"A más":(String)$val['dh_plazo'];
            //}
            echo '<td>'.$val['dh_tea'].'</td>';
        }

        //$serie[] = array("name"=>$emp['dh_emp_id']."-".$emp['dp_nomb_emp']." - ".$emp['dp_nomb_prod'],"data"=>$detalle);
        echo '</tr>';
    }
    ?>
    </table>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
    <div id="container" style="margin: 0px auto; border: 1px solid #ddd"></div>
</div>

<script>
    Highcharts.chart('container', {
        chart: {
            height: <?=($dp_empresa<=3)?$dp_empresa*100:$dp_empresa*80?>,
            type: 'line',
            //marginBottom: 5
            // Edit chart spacing
            spacingBottom: 15,
            spacingTop: 10,
            spacingLeft: 10,
            spacingRight: 10,

            // Explicitly tell the width and height of a chart
            width: null,
            //height: null
        },
        title: {
            text: 'Análisis - Depósito Plazo'
        },

        subtitle: {
            text: ''//Source: thesolarfoundation.com
        },

        yAxis: {
            title: {
                text: 'Tasa (TEA)'
            },
            offset:2
        },
        xAxis: {
            categories: <?=$json_categorie?>,
            title: {
                text: ""
            }
        },
        legend: {
            //layout: 'vertical',
            align: 'center',
            verticalAlign: 'bottom',
            //x: 0,
            //y: 0

        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: true
                },
                pointStart: false,
                enableMouseTracking: true
            },
            line: {
                dataLabels: {
                    enabled: false
                },
                enableMouseTracking: false
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
        credits: {
            enabled: false
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
</script>