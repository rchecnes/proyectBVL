<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
    <tr>
        <th>Codigo Producto</th>
        <th>Logo</th>
        <th>Empresa</th>
    </tr>
    <?php
    while ($emp = mysqli_fetch_array($dp_empresa)) {
    ?>
    <tr>
        <td><?=$emp['pd_emp_id']?></td>
        <td align="center">
            
            <input type="image" src="https://cdn-pe.comparabien.com/<?=$emp['pd_logo']?>">
        </td>
        <td align="left"><?=$emp['pd_nomb']?></td>
    </tr>
    <?php
    }
    ?>
</table>