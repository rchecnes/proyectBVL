<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
    <tr>
        <th>Empresa</th>
        <th>Nemonico</th>
        <th>Derechos</th>
        <th>Fecha de Acuerdo</th>
        <th>Fecha de Corte</th>
        <th>Fecha de Registro</th>
        <th>Fecha de Entrega</th>
        <th>Acciones</th>
    </tr>
    <?php
    while ($ub = mysqli_fetch_array($res)) {

        $nombre  = $ub['emp_nomb'];
        $ub_nemonico  = $ub['ub_nemonico'];
        $ub_der_mon  = $ub['ub_der_mon'];
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
        <td><?=$nombre?></td>
        <td><?=$ub_nemonico?></td>
        <td><?=$derecho?></td>
        <td><?=$ub_fech_acu?></td>
        <td><?=$ub_fech_cor?></td>
        <td><?=$ub_fech_reg?></td>
        <td><?=$ub_fech_ent?></td>
        <td>
            <a href="../Controller/UltimosBeneficiosC.php?accion=edit&ub_cod=<?=$ub['ub_cod']?>" class="" role="button"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a>&nbsp;
		    <a href="../Controller/UltimosBeneficiosC.php?accion=delete&ub_cod=<?=$ub['ub_cod']?>" class="color-red" role="button" onclick="return confirm('Esta seguro de eliminar el registro seleccionado?')"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a>
        </td>
    </tr>
    <?php
    }
    ?>
</table>