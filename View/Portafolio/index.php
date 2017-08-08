<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php
	//session_start();
	//http://glyphicons.bootstrapcheatsheets.com/
	include('../Include/Header.php');

	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	
	<div class="container">
		<h3 class="title">Portafolio</h3>
		<table class="table table-bordered">
			<tr>
		        <th colspan="2">Empresa</th>
		        <th colspan="4">Compra</th>
		        <th colspan="3">Simulador</th>
		    </tr>
		    <tr>
		        <th class="">Nemonico</th>
		        <th class="">Nombre</th>
		        <th class="">Fecha</th>
		        <th class="">Inversión.</th>
		        <th class="">Cant.</th>
		        <th class="">Precio</th>
		        <th class="">P. Actual</th>
		        <th class="">Gan. Neta</th>
		        <th class="">Acciones</th>
		    </tr>			    
		
			<?php
			$nemonico = '';
			$sum_mont_neg = $sum_cant = $sum_gan_net = 0;
			$c = 0;
			while ($p = mysqli_fetch_array($portafolio)):

				if ($c==0) {$nemonico = strtoupper($p['nemonico']);}
				
				if ($nemonico == strtoupper($p['nemonico'])) {
					
		        	$prec_act = $gan_net = 0;
		        	if(date('Y-m-d')>$p['por_fech']):
		        		$prec_act = $p['cz_ci_fin'];
		        		$gan_net  = getGananciaNeta($p['por_mont_est'], $p['por_prec'], $p['por_cant'], $p['por_rent_obj'], $p['cz_ci_fin']);
		        	else:
		        		$prec_act = $p['por_prec_act'];
		        		$gan_net  = $p['por_gan_net'];
		        	endif;

					$sum_mont_neg += $p['por_mont_neg'];
					$sum_cant     += $p['por_cant'];
					$sum_gan_net  += $gan_net;
				?>
					<tr>
				        <td class=""><?=($c==0)?$p['nemonico']:'&nbsp;'?></td>
				        <td class=""><?=($c==0)?$p['nombre']:'&nbsp;'?></td>
				        <td class=""><?=$p['por_fech_new'].' '.$p['por_hora']?></td>
				        <td class="">S/. <?=number_format($p['por_mont_neg'],2,'.',',')?></td>
				        <td class=""><?=number_format($p['por_cant'],2,'.',',')?></td>
				        <td class=""><?=number_format($p['por_prec'],2,'.',',')?></td>
				        <td class=""><?=number_format($prec_act,2,'.',',')?></td>
				        <td class=""><?=number_format($gan_net,2,'.',',')?></td>
				        <td class="">
				        	<a href="../Controller/PortafolioC.php?accion=delete&cod_emp=<?=$p['cod_emp']?>&cod_user=<?=$p['cod_user']?>&por_fech=<?=$p['por_fech']?>" title="Eliminar">
					            <i class="fa fa-trash-o fa-2x color-red" aria-hidden="true"></i> 
					        </a>&nbsp;&nbsp;&nbsp;&nbsp;
					        <a href="../Controller/SimuladorC.php?accion=index&oper=ver_simu&cod_emp=<?=$p['cod_emp']?>&cod_grupo=<?=$p['cod_grupo']?>&mont_est=<?=$p['por_mont_est']?>&prec=<?=$p['por_prec']?>&cant=<?=$p['por_cant']?>&rent_obj=<?=$p['por_rent_obj']?>&prec_act=<?=$prec_act?>" title="Ver en simulador">
					            <i class="fa fa-share fa-2x color-blue" aria-hidden="true"></i> 
					        </a>

				        </td>
				    </tr>
				<?php
					if (($c+1)==$cant_reg_port) {
				?>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<th>Total</th>
						<td>S/. <?=number_format($sum_mont_neg,2,'.',',')?></td>
						<td><?=number_format($sum_cant,2,'.',',')?></td>
						<td><?=number_format($sum_mont_neg / $sum_cant,2,'.',',')?></td>
						<td>&nbsp;</td>
						<td><?=number_format($sum_gan_net,2,'.',',')?></td>
						<td>&nbsp;</td>
					</tr>
				<?php
					}
				}else{
				?>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<th>Total</th>
						<td>S/. <?=number_format($sum_mont_neg,2,'.',',')?></td>
						<td><?=number_format($sum_cant,2,'.',',')?></td>
						<td><?=number_format($sum_mont_neg / $sum_cant,2,'.',',')?></td>
						<td>&nbsp;</td>
						<td><?=number_format($sum_gan_net,2,'.',',')?></td>
						<td>&nbsp;</td>
					</tr>
					<?php 
					$prec_act = $gan_net = 0;
		        	if(date('Y-m-d')>$p['por_fech']):
		        		$prec_act = $p['cz_ci_fin'];
		        		$gan_net  = getGananciaNeta($p['por_mont_est'], $p['por_prec'], $p['por_cant'], $p['por_rent_obj'], $p['cz_ci_fin']);
		        	else:
		        		$prec_act = $p['por_prec_act'];
		        		$gan_net  = $p['por_gan_net'];
		        	endif;

					$sum_mont_neg = $p['por_mont_neg'];
					$sum_cant     = $p['por_cant'];
					$sum_gan_net  = $gan_net;
					?>
					<tr>
				        <td class=""><?=$p['nemonico']?></td>
				        <td class=""><?=$p['nombre']?></td>
				        <td class=""><?=$p['por_fech_new'].' '.$p['por_hora']?></td>
				        <td class="">S/. <?=number_format($p['por_mont_neg'],2,'.',',')?></td>
				        <td class=""><?=number_format($p['por_cant'],2,'.',',')?></td>
				        <td class=""><?=number_format($p['por_prec'],2,'.',',')?></td>
				        <td class=""><?=number_format($prec_act,2,'.',',')?></td>
				        <td class=""><?=number_format($gan_net,2,'.',',')?></td>
				        <td class="">
				        	<a href="../Controller/PortafolioC.php?accion=delete&cod_emp=<?=$p['cod_emp']?>&cod_user=<?=$p['cod_user']?>&por_fech=<?=$p['por_fech']?>" title="Eliminar">
					            <i class="fa fa-trash-o fa-2x color-red" aria-hidden="true"></i> 
					        </a>&nbsp;&nbsp;&nbsp;&nbsp;
					        <a href="../Controller/SimuladorC.php?accion=index&oper=ver_simu&cod_emp=<?=$p['cod_emp']?>&cod_grupo=<?=$p['cod_grupo']?>&mont_est=<?=$p['por_mont_est']?>&prec=<?=$p['por_prec']?>&cant=<?=$p['por_cant']?>&rent_obj=<?=$p['por_rent_obj']?>&prec_act=<?=$prec_act?>" title="Ver en simulador">
					            <i class="fa fa-share fa-2x color-blue" aria-hidden="true"></i> 
					        </a>
				        </td>
				    </tr>
				    <?php
				    if (($c+1)==$cant_reg_port) {
					?>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<th>Total</th>
						<td>S/. <?=number_format($sum_mont_neg,2,'.',',')?></td>
						<td><?=number_format($sum_cant,2,'.',',')?></td>
						<td><?=number_format($sum_mont_neg / $sum_cant,2,'.',',')?></td>
						<td>&nbsp;</td>
						<td><?=number_format($sum_gan_net,2,'.',',')?></td>
						<td>&nbsp;</td>
					</tr>
					<?php
					}
				}
				?>
				
			<?php
			$c++;
			$nemonico = strtoupper($p['nemonico']);
			endwhile;
			?>
		</table>
		</div>
		
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
            

		    
        });
	</script>
</body>
</html>