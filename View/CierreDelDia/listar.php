<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
	<thead>
		<tr>
			<th colspan="4" class="align-center">Acciones</th>
			<th rowspan="2" class="align-center" style="vertical-align: middle;">Moneda</th>
			<th colspan="5" class="align-center">Cotizaciones</th>
			<th colspan="2" class="align-center">Propuestas</th>
			<th colspan="3" class="align-center">Negociación</th>
		</tr>
		<tr>
			<th align="center">Empresa</th>
			<th align="center">Nemonico</th>
			<th align="center">Sector</th>
			<th align="center">Segm.</th>
			<th align="center">Ant.</th>
			<th align="center">Fecha Ant.</th>
			<th align="center">Apert.</th>
			<th align="center">Ultima</th>
			<th align="center">Var %</th>
			<th align="center">Compra</th>
			<th align="center">Venta</th>
			<th align="center">Nro. Acc.</th>
			<th align="center">Nro. Oper.</th>
			<th align="center">Monto. Neg.</th>
		</tr>
	</thead>
	
	<tbody>
		<?php while ($cd = mysqli_fetch_array($res)): ?>
		<tr>
			<td><?=$cd['empresa']?></td>
			<td><?=$cd['nemonico']?></td>
			<td><?=$cd['sector']?></td>
			<td><?=$cd['segmento']?></td>
			<td><?=$cd['moneda']?></td>
			<td align="right"><?=number_format($cd['cd_cz_ant'],2)?></td>
			<td><?=$cd['cz_fant']?></td>
			<td align="right"><?=number_format($cd['cd_cz_aper'],2)?></td>
			<td align="right"><?=number_format($cd['cd_cz_ult'],2)?></td>
			<td align="right"><?=number_format($cd['cd_cz_var'],2)?></td>
			<td align="right"><?=number_format($cd['cd_pr_com'],2)?></td>
			<td align="right"><?=number_format($cd['cd_pr_ven'],2)?></td>
			<td align="right"><?=number_format($cd['cd_ng_nac'],2)?></td>
			<td align="right"><?=number_format($cd['cd_ng_nop'],2)?></td>
			<td align="right"><?=number_format($cd['cd_ng_mng'],2,'.',',')?></td>
		</tr>
		<?php endwhile; ?>
	</tbody>
	
	
</table>