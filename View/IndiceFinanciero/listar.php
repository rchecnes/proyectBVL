<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
    <tr>
        <th>Nemonico</th>
        <th>Nombre Empresa</th>
        <th>Indices Financieros</th>
        <?php 
        foreach($array_anio as $anio){
            echo '<th align="center">'.$anio.'</th>';
        }
        ?>
    </tr>
    <?php
    while ($inf = mysqli_fetch_array($res)) {

        $inf_codigo  = $inf['inf_codigo'];
        $inf_nemonico  = $inf['inf_nemonico'];
        $inf_nombre  = $inf['inf_nombre'];
        $emp_nombre  = $inf['nombre'];
        
    
        
        $sqla = "SELECT * FROM det_indice_financiero WHERE inf_codigo='$inf_codigo' AND inf_nemonico='$inf_nemonico' ORDER BY inf_anio ASC";
        $resa = mysqli_query($link, $sqla);
        $det_arr_anio = array();
        while($rowa = mysqli_fetch_array($resa)){
            $det_arr_anio[$rowa['inf_anio']] = array('inf_anio'=>$rowa['inf_anio'],'inf_valor'=>$rowa['inf_valor']);
        }
    ?>
    <tr>
        <td><?=$inf_nemonico?></td>
        <td><?=$emp_nombre?></td>
        <td><?=$inf_nombre?></td>
        <?php
        foreach($array_anio as $anio){

            if($anio == $det_arr_anio[$anio]['inf_anio']){
                $inf_val_new = ($det_arr_anio[$anio]['inf_valor'] > 0)?number_format($det_arr_anio[$anio]['inf_valor'],4,'.',''):'-.-';
                echo '<td align="right">'.$inf_val_new.'</td>';
            }else{
                echo '<td>&nbsp;</td>';
            }
            
        }
        ?>
        
    </tr>
    <?php
    }
    ?>
</table>