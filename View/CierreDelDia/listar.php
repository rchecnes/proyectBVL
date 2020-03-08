<p>Cantidad Registros:<b><?=$nro_reg?></b></p>
<table class="table table-bordered">
	<thead>
		<tr>
			<th colspan="4" class="align-center bg-yellow">Acciones</th>
			<th rowspan="2" class="align-center bg-yellow" style="vertical-align: middle;">Moneda</th>
			<th colspan="5" class="align-center bg-yellow">Cotizaciones</th>
			<th colspan="2" class="align-center bg-yellow">Propuestas</th>
			<th colspan="3" class="align-center bg-yellow">Negociación</th>
			<th colspan="2" class="align-center bg-yellow">En Circulación</th>
		</tr>
		<tr>
			<th class="align-center bg-gray-dark" style="width:120px">Empresa</th>
			<th class="align-center bg-gray-dark">Nemonico</th>
			<th class="align-center bg-gray-dark">Sector</th>
			<th class="align-center bg-gray-dark">Segm.</th>
			<th class="align-center bg-gray-dark">Ant.</th>
			<th class="align-center bg-gray-dark">Fecha Ant.</th>
			<th class="align-center bg-gray-dark">Apert.</th>
			<th class="align-center bg-gray-dark">Ultima</th>
			<th class="align-center bg-gray-dark">Var %</th>
			<th class="align-center bg-gray-dark">Compra</th>
			<th class="align-center bg-gray-dark">Venta</th>
			<th class="align-center bg-gray-dark">Nro. Acc.</th>
			<th class="align-center bg-gray-dark">Nro. Oper.</th>
			<th class="align-center bg-gray-dark">Monto. Neg.</th>
			<th class="align-center bg-gray-dark">Acc. Cir.</th>
			<th class="align-center bg-gray-dark">Val. Nom.</th>
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
			<td align="right"><?=($cd['cd_cz_ant']>0)?number_format($cd['cd_cz_ant'],3):''?></td>
			<td><?=($cd['cz_fant']!='00/00/0000')?$cd['cz_fant']:''?></td>
			<td align="right"><?=($cd['cd_cz_aper']>0)?number_format($cd['cd_cz_aper'],3):''?></td>
			<td align="right"><?=($cd['cd_cz_ult']>0)?number_format($cd['cd_cz_ult'],3):''?></td>
			<td align="right"><?=($cd['cd_cz_var']>0)?number_format($cd['cd_cz_var'],3):''?></td>
			<td align="right"><?=($cd['cd_pr_com']>0)?number_format($cd['cd_pr_com'],3):''?></td>
			<td align="right"><?=($cd['cd_pr_ven']>0)?number_format($cd['cd_pr_ven'],3):''?></td>
			<td align="right"><?=($cd['cd_ng_nac']>0)?number_format($cd['cd_ng_nac'],3):''?></td>
			<td align="right"><?=($cd['cd_ng_nop']>0)?number_format($cd['cd_ng_nop'],0):''?></td>
			<td align="right"><?=($cd['cd_ng_mng']>0)?number_format($cd['cd_ng_mng'],2,'.',','):''?></td>
			<td align="right"><?=number_format($cd['ub_acc_cir'],0,'.',',')?></td>
			<td align="right"><?=number_format($cd['ub_val_nom'],2,'.',',')?></td>
		</tr>
		<?php endwhile; ?>
	</tbody>
	
	
</table>