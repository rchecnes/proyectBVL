<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<style>
.cab{
    background-color: #a7a9ac;
    color:#FFF;
}
.cab_gen{
    background:#434343;
    color:#FFF;
    text-align: center;
}
</style>
<table class="table table-bordered">
    <tr>
        <th colspan="5" style="background:#b11b21;color:#FFF;text-align: center;"><?=$nombre_empresa?></th>
    </tr>
    <tr>
        <th colspan="3" class="cab_gen">ESTADO DE SITUACION FINANCIERA / STATEMENT OF FINANCIAL POSITION</th>
        <th class="cab_gen"><?=$cab_fe_ini?></th>
        <th class="cab_gen"><?=$cab_fe_fin?></th>
    </tr>
    
    <?php
    while ($ub = mysqli_fetch_array($res)) {

        $cef_cod  = $ub['cef_cod'];
        $cef_cod_bvl  = $ub['cef_cod_bvl'];
        $cef_nomb  = $ub['cef_nomb'];
        $def_nemonico  = $ub['def_nemonico'];
        $def_val_de  = $ub['def_val_de'];
        $def_val_ha  = $ub['def_val_ha'];
        $cef_cab_det  = $ub['cef_cab_det'];

        echo "<tr>";
        if($cef_cab_det == 'CAB'){
            $def_val_de_new = ($def_val_de!=0)?number_format($def_val_de,0,'',','):'';
            $def_val_ha_new = ($def_val_ha!=0)?number_format($def_val_ha,0,'',','):'';
            echo "<th class='cab'>$cef_cod_bvl</th><th colspan='2' class='cab'>$cef_nomb</th><th class='cab' align='right'>$def_val_de_new</th><th class='cab' align='right'>$def_val_ha_new</th>";
        }else{
            $def_val_de_new = ($def_val_de!='')?number_format($def_val_de,0,'',','):'';
            $def_val_ha_new = ($def_val_ha!='')?number_format($def_val_ha,0,'',','):'';
            
            if($cef_cab_det == 'DET'){
                echo "<td>$cef_cod_bvl</td><td>&nbsp;</td><td>$cef_nomb</td><td align='right'>$def_val_de_new</td><td align='right'>$def_val_ha_new</td>";
            }else{
                echo "<td>$cef_cod_bvl</td><td>$cef_nomb</td><td align='right'>$def_val_de_new</td><td align='right'>$def_val_ha_new</td>";
            }
        }
        echo "</tr>";
    
    }
    ?>
</table>