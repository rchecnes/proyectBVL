<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
    <tr>
        <th>Producto</th>
        <th>Empresa</th>
        <th>Moneda</th>
        <th>Ubicación</th>
    </tr>
    <?php
    while ($emp = mysqli_fetch_array($dp_empresa)) {
    ?>
    <tr>
        <td align="center">
            <b><?=$emp['dp_nomb_prod']?></b><br>
            <input type="image" src="https://cdn-pe.comparabien.com/<?=$emp['dp_logo']?>">
        </td>
        <td align="left"><?=$emp['dp_nomb_emp']?></td>
        <td align="left"><?=($emp['dp_moneda']=='MN')?"Soles":"Dólares"?></td>
        <td align="left"><?=($emp['dp_ubig']=='LI')?"Lima y Callao":"Otros"?></td>
    </tr>
    <?php
    }
    ?>
</table>