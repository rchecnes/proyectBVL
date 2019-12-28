<?php

?>
<table class="table table-bordered">
    <tr>
        <th>AÑO</th>
        <?php foreach($anio_arr as $anio){ echo '<th>'.$anio.'</th>';} ?>
    </tr>
    <tr>
        <th>Ventas</th>
        <?php foreach($ventas_arr as $venta){ echo '<td align="right">'.number_format($venta['impo'],0,'',',').'</td>';} ?>
        <td style="padding: 0px;">
            <div id="grfco_venta" style="height: 40px!important;width:150px"></div>
        </td>
    </tr>
    <tr>
        <th>Utilidad Bruta</th>
        <?php foreach($util_bru_arr as $bruta){ echo '<td align="right">'.number_format($bruta['impo'],0,'',',').'</td>';} ?>
    </tr>
    <tr>
        <th>Utilidad Operativa</th>
        <?php foreach($util_ope_arr as $oper){ echo '<td align="right">'.number_format($oper['impo'],0,'',',').'</td>';} ?>
    </tr>
    <tr>
        <th>Utilidad Neta</th>
        <?php foreach($util_net_arr as $neta){ echo '<td align="right">'.number_format($neta['impo'],0,'',',').'</td>';} ?>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+1?>">&nbsp;</th>
    </tr>
    <tr>
        <th>Total Pasivo</th>
        <?php foreach($tot_pas_arr as $pas){ echo '<td align="right">'.number_format($pas['impo'],0,'',',').'</td>';} ?>
    </tr>
    <tr>
        <th>Total Patrimonio</th>
        <?php foreach($tot_pat_arr as $pat){ echo '<td align="right">'.number_format($pat['impo'],0,'',',').'</td>';} ?>
    </tr>
    <tr>
        <th>Total Activo</th>
        <?php foreach($tot_act_arr as $act){ echo '<td align="right">'.number_format($act['impo'],0,'',',').'</td>';} ?>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+1?>">Ratios Financieros</th>
    </tr>
    <tr>
        <th>Endeudamiento</th>
        <?php foreach($end_arr as $end){ echo '<td align="right">'.number_format($end['impo'],0,'.',',').'%</td>';} ?>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+1?>">&nbsp;</th>
    </tr>
    <tr>
        <th>Margen Bruto</th>
        <?php foreach($mar_bru_arr as $mgbt){ echo '<td align="right">'.number_format($mgbt['impo'],0,'.',',').'%</td>';} ?>
    </tr>
    <tr>
        <th>Margen Neto</th>
        <?php foreach($mar_net_arr as $mgnt){ echo '<td align="right">'.number_format($mgnt['impo'],0,'.',',').'%</td>';} ?>
    </tr>
    <tr>
        <th>Margen Bruto</th>
        <?php foreach($mar_bru_arr as $margen){ echo '<td align="right">'.number_format($margen['impo'],0,'.',',').'%</td>';} ?>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+1?>">&nbsp;</th>
    </tr>
    <tr>
        <th>Rotación del Activo</th>
        <?php foreach($rot_act_arr as $roact){ echo '<td align="right">'.number_format($roact['impo'],2,'.',',').'%</td>';} ?>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+1?>">&nbsp;</th>
    </tr>
    <tr>
        <th>ROA</th>
        <?php foreach($roa_arr as $roa){ echo '<td align="right">'.number_format($roa['impo'],0,'.',',').'%</td>';} ?>
    </tr>
    <tr>
        <th>ROE</th>
        <?php foreach($roe_arr as $roe){ echo '<td align="right">'.number_format($roe['impo'],0,'.',',').'%</td>';} ?>
    </tr>
</table>
<script>
Highcharts.chart('grfco_venta', {
    chart: {
        type: 'line',
    },
    legend: {
        enabled: false
    },
    exporting: {
        enabled: false
    },
    tooltip: {
        enabled: false
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    yAxis: {
        visible: false,
    },
    xAxis: {
        visible: false,
    },
    plotOptions: {
        series: {
            marker: {
                enabled: true,
                //fillColor: '#FFFFFF',
                //lineWidth: 0,
                radius: 3
                //lineColor: null // inherit from series
            }
        }
    },
    series: [{ 
        name: '',
        data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
    }],
    credits: {
        enabled: false
    }
});
</script>