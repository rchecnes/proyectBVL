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
        <th>Monto Negociado ($)</th>
        <th>Fecha Anterior</th>
        <th>Cierre Anterior</th>
    </tr>
    <?php
    while ($cz = mysqli_fetch_array($cotizacion)) {
    ?>
    <tr>
        <td><?=$cz['cz_codemp']?></td>
        <td width="100"><?=$cz['fecha_forma']?></td>
        <td align="right"><?=($cz['cz_apertura']>1)?number_format($cz['cz_apertura'],2):number_format($cz['cz_apertura'],3)?></td>
        <td align="right"><?=($cz['cz_cierre']>1)?number_format($cz['cz_cierre'],2):number_format($cz['cz_cierre'],3)?></td>
        <td align="right"><?=($cz['cz_maxima']>1)?number_format($cz['cz_maxima'],2):number_format($cz['cz_maxima'],3)?></td>
        <td align="right"><?=($cz['cz_minima']>1)?number_format($cz['cz_minima'],2):number_format($cz['cz_minima'],3)?></td>
        <td align="right"><?=($cz['cz_promedio']>1)?number_format($cz['cz_promedio'],2):number_format($cz['cz_promedio'],3)?></td>
        <td align="right"><?=number_format($cz['cz_cantnegda'],2)?></td>
        <td align="right"><?=number_format($cz['cz_montnegd'],2)?></td>
        <td><?=$cz['fecha_formant']?></td>
        <td align="right"><?=($cz['cz_cierreant']>1)?number_format($cz['cz_cierreant'],2):number_format($cz['cz_cierreant'],3)?></td>
    </tr>
    <?php
    }
    ?>
</table>