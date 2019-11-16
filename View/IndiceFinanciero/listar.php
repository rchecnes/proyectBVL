<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
    <tr>
        <th>Nemonico</th>
        <th>Indices Financieros</th>
        <?php 
        
        ?>
    </tr>
    <?php
    while ($ub = mysqli_fetch_array($res)) {

        $inf_nemonico  = $ub['inf_nemonico'];
        $inf_nombre  = $ub['inf_nombre'];
        $ub_der_imp  = $ub['ub_der_imp'];
        $ub_der_por  = $ub['ub_der_por'];
        $ub_der_tip  = $ub['ub_der_tip'];
        $ub_fech_acu  = $ub['ub_fech_acu'];
        $ub_fech_cor  = $ub['ub_fech_cor'];
        $ub_fech_reg  = $ub['ub_fech_reg'];
        $ub_fech_ent  = $ub['ub_fech_ent'];
    
        //$derecho = $ub_der_mon.' '.$ub_der_imp.' '.$ub_der_por.' '.$ub_der_tip;
        $derecho = $ub['ub_der_comp'];
    ?>
    <tr>
        <td><?=$inf_nemonico?></td>
        <td><?=$inf_nombre?></td>
        <td><?=$ub_fech_acu?></td>
        <td><?=$ub_fech_cor?></td>
        <td><?=$ub_fech_reg?></td>
        <td><?=$ub_fech_ent?></td>
        
    </tr>
    <?php
    }
    ?>
</table>