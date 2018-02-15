<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
    <tr>
        <th>Empresa</th>
        <th>Fecha</th>
        <th>Apertuta</th>
        <th>Cierre</th>
        <th>Máxima</th>
        <th>Mínima</th>
        <th>Promedio</th>
        <th>Cantidad Negociada</th>
        <th>Monto Negociado</th>
        <th>Num. Oper.</th>
        <th>Compra</th>
        <th>Venta</th>
    </tr>
    <?php
    while ($cz = mysqli_fetch_array($cotizacion)) {

    $cz_apertura  = ($cz['cz_apertura']>1)?number_format($cz['cz_apertura'],2):number_format($cz['cz_apertura'],3);
    $cz_cierre    = ($cz['cz_cierre']>1)?number_format($cz['cz_cierre'],2):number_format($cz['cz_cierre'],3);;
    $cz_maxima    = ($cz['cz_maxima']>1)?number_format($cz['cz_maxima'],2):number_format($cz['cz_maxima'],3);
    $cz_minima    = ($cz['cz_minima']>1)?number_format($cz['cz_minima'],2):number_format($cz['cz_minima'],3);
    $cz_promedio  = ($cz['cz_promedio']>1)?number_format($cz['cz_promedio'],2):number_format($cz['cz_promedio'],3);
    $cz_cantnegda = number_format($cz['cz_cantnegda'],2);
    $cz_montnegd  = number_format($cz['cz_monto_neg_ori'],2);
    $cz_cierreant = ($cz['cz_cierreant']>1)?number_format($cz['cz_cierreant'],2):number_format($cz['cz_cierreant'],3);
    $cz_num_oper =  number_format($cz['cz_num_oper'],2);
    $cz_num_compra = ($cz['cz_num_compra']>1)?number_format($cz['cz_num_compra'],2):number_format($cz['cz_num_compra'],3);
    $cz_num_venta  = ($cz['cz_num_venta']>1)?number_format($cz['cz_num_venta'],2):number_format($cz['cz_num_venta'],3);

    ?>
    <tr>
        <td><?=$cz['cz_codemp']?></td>
        <td width="100"><?=($cz['fecha_forma'] !='00/00/0000')?$cz['fecha_forma']:""?></td>
        <td align="right"><?=($cz_apertura >0)?$cz_apertura:""?></td>
        <td align="right"><?=($cz_cierre >0)?$cz_cierre:""?></td>
        <td align="right"><?=($cz_maxima >0)?$cz_maxima:""?></td>
        <td align="right"><?=($cz_minima >0)?$cz_minima:""?></td>
        <td align="right"><?=($cz_promedio >0)?$cz_promedio:""?></td>
        <td align="right"><?=($cz_cantnegda >0)?$cz_cantnegda:""?></td>
        <td align="right"><?=($cz_montnegd >0)?$cz_montnegd:""?></td>
        <td align="right"><?=$cz_num_oper?></td>
        <td align="right"><?=$cz_num_compra?></td>
        <td align="right"><?=$cz_num_venta?></td>
    </tr>
    <?php
    }
    ?>
</table>