<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
    <tr>
        <th>Empresa</th>
        <th>Tasa de Interés (TEA / TREA)</th>
        <th>Ganancia Total (aprox)</th>
        <th>Ganancia Mensual (aprox)</th>
    </tr>
    <?php
    while ($his = mysqli_fetch_array($dp_historico)) {
    ?>
    <tr>
        <td>
            <input type="image" src="https://cdn-pe.comparabien.com/<?=$his['dp_logo']?>">        
        </td>
        <td align="center">
            <?=$his['dh_tea']."%"?>
        </td>
        <td align="left"><?=number_format($his['dh_sal_prom_d'],2,".",",")?></td>
    </tr>
    <?php
    }
    ?>
</table>