<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<style>
.cab_det{
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
        <th colspan="7" style="background:#b11b21;color:#FFF;text-align: center;"><?=$nombre_empresa?></th>
    </tr>
    <tr>
        <th colspan="3" class="cab_gen">ESTADO DE RESULTADOS / INCOME STATEMENT</th>
        <th class="cab_gen"><?=$tot_info_1?></th>
        <th class="cab_gen"><?=$tot_info_2?></th>
        <?php if($der_peri != 'A'): ?>
        <th class="cab_gen"><?=$tot_info_3?></th>
        <th class="cab_gen"><?=$tot_info_4?></th>
        <?php endif; ?>
    </tr>
    
    <?php
    while ($ub = mysqli_fetch_array($res)) {

        $cer_cod  = $ub['cer_cod'];
        $cer_cod_bvl  = $ub['cer_cod_bvl'];
        $cer_nomb  = $ub['cer_nomb'];
        $der_nemonico  = $ub['der_nemonico'];
        $cer_cab_det  = $ub['cer_cab_det'];

        $der_val_tr1  = $ub['der_val_tr1'];
        $der_val_tr2  = $ub['der_val_tr2'];
        $der_val_tr3  = $ub['der_val_tr3'];
        $der_val_tr4  = $ub['der_val_tr4'];
        
        $der_val1_vac  = $ub['der_val1_vac'];
        $der_val2_vac  = $ub['der_val2_vac'];
        $der_val3_vac  = $ub['der_val3_vac'];
        $der_val4_vac  = $ub['der_val4_vac'];

        echo "<tr>";
        if($cer_cab_det == 'CAB'){

            $der_val_tr1_new = ($der_val1_vac != 1)?number_format($der_val_tr1,0,'',','):'';
            $der_val_tr2_new = ($der_val2_vac != 1)?number_format($der_val_tr2,0,'',','):'';
            $der_val_tr3_new = ($der_val3_vac != 1)?number_format($der_val_tr3,0,'',','):'';
            $der_val_tr4_new = ($der_val4_vac != 1)?number_format($der_val_tr4,0,'',','):'';

            echo "<th class='cab_det'>$cer_cod_bvl</th>";
            echo "<th colspan='2' class='cab_det'>$cer_nomb</th>";
            echo "<th class='cab_det' style='text-align:right'>$der_val_tr1_new</th>";
            echo "<th class='cab_det' style='text-align:right'>$der_val_tr2_new</th>";
            
            if($der_peri != 'A'):
                echo "<th class='cab_det' style='text-align:right'>$der_val_tr3_new</th>";
                echo "<th class='cab_det' style='text-align:right'>$der_val_tr4_new</th>";
            endif;

        }else{
            
            $der_val_tr1_new = ($der_val1_vac != 1)?number_format($der_val_tr1,0,'.',','):'';
            $der_val_tr2_new = ($der_val2_vac != 1)?number_format($der_val_tr2,0,'.',','):'';
            $der_val_tr3_new = ($der_val3_vac != 1)?number_format($der_val_tr3,0,'.',','):'';
            $der_val_tr4_new = ($der_val4_vac != 1)?number_format($der_val_tr4,0,'.',','):'';
            
            if($cer_cab_det == 'DET'){
                echo "<td>$cer_cod_bvl</td>";
                echo "<td>&nbsp;</td>";
                echo "<td>$cer_nomb</td>";
                echo "<td align='right'>$der_val_tr1_new</td>";
                echo "<td align='right'>$der_val_tr2_new</td>";
                
                if($der_peri != 'A'):
                    echo "<td align='right'>$der_val_tr3_new</td>";
                    echo "<td align='right'>$der_val_tr4_new</td>";
                endif;
            }else{
                echo "<td>$cer_cod_bvl</td>";
                echo "<td colspan='2'>$cer_nomb</td>";
                echo "<td align='right'>$der_val_tr1_new</td>";
                echo "<td align='right'>$der_val_tr2_new</td>";

                if($der_peri != 'A'):
                    echo "<td align='right'>$der_val_tr3_new</td>";
                    echo "<td align='right'>$der_val_tr4_new</td>";
                endif;
            }
            
        }
        echo "</tr>";
    
    }
    ?>
</table>