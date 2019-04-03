<br>
<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
    <tr>
        <th style="width: 80px">Empresa</th>
        <th>Ultima Actualizacion</th>
        <th>Saldo Promedio Mensual</th>
        <th>Plazo</th>
        <th>Tasa Efectiva - TEA</th>
    </tr>
    <?php
    while ($his = mysqli_fetch_array($dp_historico)) {
    ?>
    <tr>
        <td align="center">
            <span><b><?=$his['dp_nomb_prod']?></b></span><br>
            <input type="image" src="https://cdn-pe.comparabien.com/<?=$his['dp_logo']?>">        
        </td>
        <td align="left"><?=$his['dh_last_update']?></td>
        <td align="left">
            <?php
                $mon = ($his['dp_moneda']=='MN')?"S/":"$";
                echo $mon." ".number_format($his['dh_sal_prom_d'],0,'.',',');
                echo " a ";
                if($his['dh_sal_prom_h']=='9999999999'){echo " más";}else{echo $mon." ".number_format($his['dh_sal_prom_h'],0,'.',',');}
            ?>
        </td>
        <td align="left">
            <?php
                echo $his['dh_plazo_d']." días";
                echo " a ";
                if($his['dh_plazo_h']=='9999999999'){echo "más";}else{echo $his['dh_plazo_h']." días";}
            ?>
        </td>
        <td align="center">
            <?=$his['dh_tea']."%"?>
            <?=(strtoupper($his['dh_fsd'])=='S')?"<br><span><b>FSD</b></span>":""?>
        </td>
    </tr>
    <?php
    }
    ?>
</table>