<?php

$contador = 0;
while ($w = mysqli_fetch_array($res)) {
	$cod_emp      = $w['cod_emp'];
	$cod_user     = $w['cod_user'];
	$por_fech     = $w['por_fech'];
	$por_cod      = $w['por_cod'];
	$cod_grupo    = $w['cod_grupo'];
	$cod_grupo    = $w['cod_grupo'];

	$por_prec     = ($w['por_prec']>=1)?number_format($w['por_prec'],2,'.',','):number_format($w['por_prec'],4,'.',',');
	$cz_ci_fin    = ($w['cz_ci_fin']>=1)?number_format($w['cz_ci_fin'],2,'.',','):number_format($w['cz_ci_fin'],4,'.',',');
	$gan_net_act  = getGananciaNeta($link, $w['por_mont_est'], $w['por_prec'], $w['por_cant'], $w['por_rent_obj'], $w['cz_ci_fin']);
	$gan_net_act  = number_format($gan_net_act,2,'.',',');
	$por_prec_obj = ($w['por_prec_obj']>=1)?number_format($w['por_prec_obj'],2,'.',','):number_format($w['por_prec_obj'],3,'.',',');
	$por_gan_net  = number_format($w['por_gan_net'],2,'.',',');

	$acciones = "";

	$acciones .= "";
	?>

	<tr class='port_detalle_<?=$cod_emp?>'>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?=$w['por_fech'].' '.$w['por_hora']?></td>
		<td align="right">S/. <?=number_format($w['por_mont_est'],2,'.',',')?></td>
		<td align="right"><?=number_format($w['por_cant'],2,'.',',')?></td>
		<td align="right"><?=$por_prec?></td>
		<td align="right"><?=$cz_ci_fin?></td>
		<td align="right"><?=$gan_net_act?></td>
		<td align="right"><?=$por_prec_obj?></td>
		<td align="right"><?=$por_gan_net?></td>
		<td>
			<a href='../Controller/PortafolioC.php?accion=delete&por_cod=<?=$por_cod?>&cod_emp=<?=$cod_emp?>&cod_user=<?=$cod_user?>&por_fech=<?=$por_fech?>&todo=no' title='Eliminar'><i class='fa fa-trash-o fa-2x color-red' aria-hidden='true'></i>
			</a>&nbsp;&nbsp;
			<a href='../Controller/SimuladorC.php?accion=index&por_cod=<?=$por_cod?>&oper=ver_simu&cod_emp=<?=$cod_emp?>&cod_grupo=<?=$cod_grupo?>&mont_est=<?=$w['por_mont_est']?>&prec=<?=$w['por_prec']?>&cant=<?=$w['por_cant']?>&rent_obj=<?=$w['por_rent_obj']?>&prec_act=<?=$w['por_prec_obj']?>' title='Ver en simulador'>
			    <i class='fa fa-share fa-2x color-black' aria-hidden='true'></i> 
			</a>
		</td>
	</tr>

<?php
}
?>