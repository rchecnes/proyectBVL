<?php

?>
<table class="table table-bordered">
    <tr>
        <th style="text-align:right">AÑO - TRIMESTRE</th>
        <?php foreach($new_tri_arr as $tri){ if($tri['hide'] == 'NO'){echo '<th style="text-align:center">'.$tri['tri'].'</th>';}} ?>
    </tr>
    <tr>
        <th style="width: 170px;!important">Ventas</th>
        <?php foreach($ventas_arr as $venta){ if($venta['hide'] == 'NO'){echo '<td align="right">'.number_format($venta['impo'],0,'',',').'</td>';}} ?>
        <td id="grfco_venta"></td>
    </tr>
    <tr>
        <th>Utilidad Bruta</th>
        <?php foreach($util_bru_arr as $bruta){ if($bruta['hide'] == 'NO'){echo '<td align="right">'.number_format($bruta['impo'],0,'',',').'</td>';}} ?>
        <td id="grfco_util_bru"></td>
    </tr>
    <tr>
        <th>Utilidad Operativa</th>
        <?php foreach($util_ope_arr as $oper){ if($oper['hide'] == 'NO'){echo '<td align="right">'.number_format($oper['impo'],0,'',',').'</td>';}} ?>
        <td id="grfco_util_ope"></td>
    </tr>
    <tr>
        <th>Utilidad Neta</th>
        <?php foreach($util_net_arr as $neta){ if($neta['hide'] == 'NO'){echo '<td align="right">'.number_format($neta['impo'],0,'',',').'</td>';}} ?>
        <td id="grfco_util_net"></td>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+2?>">&nbsp;</th>
    </tr>
    <tr>
        <th>Total Activo</th>
        <?php foreach($tot_act_arr as $act){ if($act['hide'] == 'NO'){echo '<td align="right">'.number_format($act['impo'],0,'',',').'</td>';}} ?>
        <td id="grfco_tot_act"></td>
    </tr>
    <tr>
        <th>Total Pasivo</th>
        <?php foreach($tot_pas_arr as $pas){ if($pas['hide'] == 'NO'){echo '<td align="right">'.number_format($pas['impo'],0,'',',').'</td>';}} ?>
        <td id="grfco_tot_pas"></td>
    </tr>
    <tr>
        <th>Total Patrimonio</th>
        <?php foreach($tot_pat_arr as $pat){ if($pat['hide'] == 'NO'){echo '<td align="right">'.number_format($pat['impo'],0,'',',').'</td>';}} ?>
        <td id="grfco_tot_pat"></td>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+2?>">&nbsp;</th>
    </tr>
    <tr>
        <th>Margen Bruto</th>
        <?php foreach($mar_bru_arr as $mgbt){ if($mgbt['hide']=='NO'){echo '<td align="right">'.number_format($mgbt['impo'],0,'.',',').'%</td>';}} ?>
        <td id="grfco_mar_bru"></td>
    </tr>
    <tr>
        <th>Margen Operativo</th>
        <?php foreach($mar_ope_arr as $mgop){ if($mgop['hide']=='NO'){echo '<td align="right">'.number_format($mgop['impo'],0,'.',',').'%</td>';}} ?>
        <td id="grfco_mar_ope"></td>
    </tr>
    <tr>
        <th>Margen Neto</th>
        <?php foreach($mar_net_arr as $mgnt){ if($mgnt['hide']=='NO'){echo '<td align="right">'.number_format($mgnt['impo'],0,'.',',').'%</td>';}} ?>
        <td id="grfco_mar_net"></td>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+2?>">&nbsp;</th>
    </tr>
    <tr>
        <th>Rotación del Activo</th>
        <?php foreach($rot_act_arr as $roact){ if($roact['hide']=='NO'){echo '<td align="right">'.number_format($roact['impo'],2,'.',',').'</td>';}} ?>
        <td id="grfco_rot_act"></td>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+2?>">&nbsp;<!--<u>Ratios Financieros</u>--></th>
    </tr>
    <tr>
        <th>Endeudamiento</th>
        <?php foreach($end_arr as $end){ if($end['hide']=='NO'){echo '<td align="right">'.number_format($end['impo'],0,'.',',').'%</td>';}} ?>
        <td id="grfco_end"></td>
    </tr>
    <tr>
        <th colspan="<?=$cant_coslpan+2?>">&nbsp;</th>
    </tr>
    <tr>
        <th>ROA</th>
        <?php foreach($roa_arr as $roa){ if($roa['hide']=='NO'){echo '<td align="right">'.number_format($roa['impo'],0,'.',',').'%</td>';}} ?>
        <td id="grfco_roa"></td>
    </tr>
    <tr>
        <th>ROE</th>
        <?php foreach($roe_arr as $roe){ if($roe['hide']=='NO'){echo '<td align="right">'.number_format($roe['impo'],0,'.',',').'%</td>';}} ?>
        <td id="grfco_roe"></td>
    </tr>
</table>
<script>

Highcharts.chart('grfco_venta', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($ventas_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_util_bru', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($util_bru_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_util_ope', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($util_ope_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_util_net', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($util_net_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_tot_pas', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($tot_pas_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_tot_pat', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($tot_pat_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_tot_act', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($tot_act_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_end', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($end_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_mar_bru', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($mar_bru_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_mar_ope', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($mar_ope_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_mar_net', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($mar_net_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_rot_act', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($rot_act_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_roa', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($roa_grfco)?>
    }],
    credits: {
        enabled: false
    }
});
Highcharts.chart('grfco_roe', {
    chart: {
        type: 'line',
        height: 45,
        width: 110
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
                fillColor: '#3072ab',
                radius: 1.8
            }
        }
    },
    series: [{ 
        name: '',
        data: <?=json_encode($roe_grfco)?>
    }],
    credits: {
        enabled: false
    }
});

</script>