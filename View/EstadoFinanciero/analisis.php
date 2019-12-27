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
        <?php foreach($util_pas_arr as $pas){ echo '<td align="right">'.number_format($pas['impo'],0,'',',').'</td>';} ?>
    </tr>
    <tr>
        <th>Total Patrimonio</th>
        <?php foreach($util_pat_arr as $pat){ echo '<td align="right">'.number_format($pat['impo'],0,'',',').'</td>';} ?>
    </tr>
    <tr>
        <th>Total Activo</th>
        <?php foreach($util_act_arr as $act){ echo '<td align="right">'.number_format($act['impo'],0,'',',').'</td>';} ?>
    </tr>

    


</table>