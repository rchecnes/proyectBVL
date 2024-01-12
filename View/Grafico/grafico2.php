<br>
<!--<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <table class="table table-bordered">
            <tr>
                <td style="width: 98px!important;">P. Actual</td><td align="center"><input type="text" name="prec_unit" id="prec_unit" class="form-control" style="text-align: center" value="<?=$simu_prec_unit?>"></td>
            </tr>
            <tr><td>Max</td><td><?=number_format($max,3,'.',',')?></td></tr>
            <tr><td>Min</td><td><?=number_format($min,3,'.',',')?></td></tr>
            <tr><td>Med</td><td><?=number_format($med,3,'.',',')?></td></tr>
            <tr><td>Long</td><td><?=number_format($long,3,'.',',')?></td></tr>
        </table>
    </div>
</div>-->
<div class="row">
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
        <table class="table table-bordered" id="tabla_grafico2">
            <tr>
                <!--<th>&nbsp;</th>-->
                <th class="align-center">Rango Inicio</th>
                <th class="align-center">Rango Final</th>
                <th class="align-center">Días</th>
                <th class="align-center">Cant. Negda.</th>
            </tr>

            <?php if($precio>$max):?>
            <tr bgcolor="<?=($precio>$max)?'#ff9966':''?>">
                <td colspan="2" align="center">> <?=number_format($max,3,'.','')?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php endif; ?>

            <?php 
            $suma_dias  = 0;
            $suma_monto = 0;
            foreach ($tabla as $key => $c):

                $selected = ($precio<=$c['rango_ini'] && $precio>=$c['rango_fin'])?"#ff9966":"";
            ?>
                <tr bgcolor="<?=$selected?>">
                    <?php
                    $style = '';
                    $tabla_ult_rf = number_format($tabla_ult_rf,3,'.',',');
                    $fin = number_format($c['rango_fin'],3,'.',',');
                    $min = number_format($min,3,'.',',');
                    if ($tabla_ult_rf == $fin && $fin !=$min) {
                        echo $min ."-".$c['rango_fin'];
                         $style = "style='color:red'" ;
                    }
                    ?>
                    <!--<td><?=$c['porcen']?></td>-->
                    <td class="align-center" <?= $style?>><?php if($c['rango_ini']>=10):echo number_format($c['rango_ini'],2,'.',',');
                        elseif($c['rango_ini']>=1 && $c['rango_ini']<10):echo number_format($c['rango_ini'],3,'.',',');
                        elseif($c['rango_ini']<1): echo number_format($c['rango_ini'],4,'.',',');endif;?> 
                    </td>
                    
                    <td class="align-center" <?= $style?>>
                       <?php
                       if($c['rango_fin']>=10):echo number_format($c['rango_fin'],2,'.',',');
                        elseif($c['rango_fin']>=1 && $c['rango_fin']<10):echo number_format($c['rango_fin'],3,'.',',');
                        elseif($c['rango_fin']<1): echo number_format($c['rango_fin'],4,'.',',');endif;
                        ?>  
                    </td>
                    <td class="align-center" <?= $style?>><?=$c['dias']?></td>
                    <td class="align-center" <?= $style?>><?=($c['monto'] !='' && $c['monto']>=0)?number_format($c['monto'],0,'.',','):0?></td>
                </tr>
            <?php
                $suma_dias += $c['dias'];
                $suma_monto += $c['monto'];
            endforeach;
            ?>
            <?php if($precio<$min):?>
            <tr bgcolor="<?=($precio<$min)?'#ff9966':''?>">
                <td colspan="2" align="center">< <?=number_format($min,3,'.','')?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php endif; ?>

            <tr>
                <th colspan="2">&nbsp;</th>
                <th class="align-center"><?=$suma_dias?></th>
                <th class="align-center"><?=number_format($suma_monto,0,'.',',')?></th>
            </tr>
        </table>
    </div>
    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12" >
        <div id="container2" style="height: 600px; margin: 0px auto; border: 1px solid #ddd">
            
        </div>
    </div>
</div>
<br>
<br>
<?php
//echo "Medio:".$serie_selected;
?>

<script type="text/javascript">
    $(document).ready(function(){
        //Tamaño de la grafica
        var heightc = ($("#tabla_grafico2").height() > 300)?$("#tabla_grafico2").height():300;
        $("#container2").css({height:heightc+'px'});
        //console.log();
    });

	Highcharts.chart('container2', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'CANTIDAD NEGOCIADA POR RANGO DE PRECIO'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: <?=$categoria?>,
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        //max:100,
        title: {
            text: 'rchecnes',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        enabled: false,
        valueSuffix: ' %'
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    }/*,
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
        shadow: true
    }*/,
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.0f} %'
            }
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'CANTIDAD NEGOCIADA (%)',
        valueSuffix: ' %',
        groupPadding: 0,
        data: <?=$series?>
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