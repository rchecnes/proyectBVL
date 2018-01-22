<br>
<!--<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <table class="table table-bordered">
            <tr><th style="width: 98px!important;">P. Actual</th><th><input type="" name="" class="form-control" style="text-align: center"></th></tr></th>
            <tr><td>Max</td><td><?=number_format($max,3,'.',',')?></td></tr>
            <tr><td>Min</td><td><?=number_format($min,3,'.',',')?></td></tr>
            <tr><td>Med</td><td><?=number_format($med,3,'.',',')?></td></tr>
            <tr><td>Long</td><td><?=number_format($long,3,'.',',')?></td></tr>
        </table>
    </div>
</div>-->
<div class="row">
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
        <table class="table table-bordered">
            <tr>
                <!--<th>&nbsp;</th>-->
                <th class="align-center">Rango Inicio</th>
                <th class="align-center">Rango Final</th>
                <th class="align-center">Días</th>
                <th class="align-center">Monto</th>
                <th class="align-center">Recomendación</th>
            </tr>
            <?php 
            $suma_dias  = 0;
            $suma_monto = 0;
            ?>
            <?php if($prec_unit>$max && $rec_cod=='1'):?>
            <tr bgcolor="<?=($prec_unit>$max && $rec_cod=='1')?'#ff9966':''?>">
                <td colspan="2" align="center">> <?=number_format($max,3,'.','')?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center">Mantener <i class="glyphicon glyphicon-arrow-up"></i></td>
            </tr>
            <?php endif; ?>

            <?php
            foreach ($tabla as $key => $c):?>
                <tr bgcolor="<?=($c['rec_cod']==$rec_cod )?'#ff9966':''?>">
                    <!--<td><?=$c['porcen']?></td>-->
                    <td class="align-center"><?php if($c['rango_ini']>=10):echo number_format($c['rango_ini'],2,'.',',');
                        elseif($c['rango_ini']>=1 && $c['rango_ini']<10):echo number_format($c['rango_ini'],3,'.',',');
                        elseif($c['rango_ini']<1): echo number_format($c['rango_ini'],4,'.',',');endif;?> 
                    </td>
                    <td class="align-center">
                       <?php if($c['rango_fin']>=10):echo number_format($c['rango_fin'],2,'.',',');
                        elseif($c['rango_fin']>=1 && $c['rango_fin']<10):echo number_format($c['rango_fin'],3,'.',',');
                        elseif($c['rango_fin']<1): echo number_format($c['rango_fin'],4,'.',',');endif;?>  
                    </td>
                    <td class="align-center"><?=$c['dias']?></td>
                    <td class="align-center"><?=number_format($c['monto'],0,'.',',')?></td>
                    <td class="align-center"><?=$c['rec_nom']?></td>
                    
                </tr>
            <?php
                $suma_dias += $c['dias'];
                $suma_monto += $c['monto'];
            endforeach;
            ?>

            <?php if($prec_unit<$min && $rec_cod=='7'):?>
            <tr bgcolor="<?=($prec_unit<$min && $rec_cod=='7')?'#ff9966':''?>">
                <td colspan="2" align="center">< <?=number_format($min,3,'.','')?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center">Mantener <i class="glyphicon glyphicon-arrow-down"></i></i></td>
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
        <div id="container1" style="height: 300px; margin: 0px auto; border: 1px solid #ddd">
            
        </div>
    </div>
</div>
<br>
<br>

<script type="text/javascript">
    var colors = ['#3B97B2', '#67BC42', '#FF56DE', '#E6D605', '#BC36FE', '#000'];

	Highcharts.chart('container1', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'MONTO NEGOCIADO POR RANGO DE PRECIO'
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
        name: 'MONTO NEGOCIADO (%)',
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