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
			<thead>
				<tr>
			        <th colspan="2">&nbsp;</th>
			        <th colspan="4">Compra</th>
			        <th colspan="2">Actual</th>
			        <th colspan="2">Objetivo</th>
			        <th>&nbsp;</th>
			    </tr>
			    <tr>
			        <th class="">Nemonico</th>
			        <th class="">Empresa</th>
			        <th class="">Fecha</th>
			        <th class="">Inversión.</th>
			        <th class="">Cant.</th>
			        <th class="">Precio</th>
			        <th class="">Precio</th>
			        <th class="">G/P Neta</th>
			        <th class="">Precio</th>
			        <th class="">G/P Neta</th>
			        <th class="">Acciones</th>
			    </tr>
			</thead>			    
			<tbody>
			<?php
			$nemonico = '';
			$sum_mont_est = $sum_cant = $sum_gan_net = $sum_gan_net_act = 0;
			$c = 0;
			while ($p = mysqli_fetch_array($portafolio)):

				$por_mont_est = $p['por_mont_est_new'];
				$por_cant     = $p['por_cant_new'];
				$por_pre       = $por_mont_est / $por_cant;
				$gan_net_act      = getGananciaNetaPorEmpresa($link, $p['cod_user'], $p['ne_cod']);
				$por_gan_net_obj  = $p['por_gan_net_obj'];

				//VARIABLES COMPRAS PARA OBTENER PRECIO OBJETIVO
				//::::::::::::::::::::::::::::::::::::::::::::::
				$com = getComision($link, 'contado');
				$mont_neg       = $por_pre*$por_cant;
				$c_comision_sab = ($mont_neg>$com['BASE_SAB'])?$mont_neg*($com['COM_SAB']/100):$com['MIN_SAB'];
				$c_cuota_bvl    = $mont_neg*($com['COM_BVL']/100);
				$c_f_garantia   = $mont_neg*($com['F_GARANT']/100);
				$c_cavali       = 0;
				if ($mont_neg <= $com['BASE_CAVAL']) {
					if ($mont_neg*($com['COM_CAVAL']/100)<$com['MIN_CAVAL']) {$c_cavali=$com['MIN_CAVAL'];}else{$c_cavali=$mont_neg*($com['COM_CAVAL']/100);}
				}
				$c_f_liquidacion = ($mont_neg*($com['F_LIQUI']/100)<1)?0:$mont_neg*($com['F_LIQUI']/100);
				$c_compra_total  = $c_comision_sab +$c_cuota_bvl+$c_f_garantia+$c_cavali+$c_f_liquidacion;
				$c_igv           = $c_compra_total*($com['VAL_IGV']/100);
				$c_compra_smv    = ($mont_neg+$c_compra_total)*($com['COM_SMV']/100);
				$c_costo_compra  = $c_compra_total+$c_igv+$c_compra_smv;
				$c_poliza_compra = $c_costo_compra+$mont_neg;
				$gan_pre_obj     = ($por_cant>0)?round_out(($mont_neg+$por_gan_net_obj+($c_costo_compra*2.1285))/$por_cant,4):0;
			?>
				
				<tr id="port_cabecera_<?=$p['ne_cod']?>" bgcolor="#f9f9f9">
			        <td class=""><?=$p['nemonico']?></td>
			        <td class=""><?=$p['emp_nomb']?></td>
			        <td class="">--</td>
			        <td class="" align="right">S/. <?=number_format($por_mont_est,2,'.',',')?></td>
			        <td class="" align="right"><?=number_format($por_cant,2,'.',',')?></td>
			        <td class="" align="right"><?=number_format($por_mont_est / $por_cant,4,'.',',')?></td>
			        <td class="" align="right"><?=number_format($p['cz_ci_fin'],4,'.',',')?></td>
			        <td class="" align="right"><?=number_format($gan_net_act,2,'.',',')?></td>
			        <td class="" align="right"><?=number_format($gan_pre_obj,4,'.',',')?></td>
			        <td class="" align="right"><?=number_format($por_gan_net_obj,2,'.',',')?></td>
			        <td class="" align="right">
			        	<span title="Ver Detalle" class="ver-detalle icon-button" data="<?=$p['ne_cod']?>">
				            <i class="fa fa-plus-square-o fa-2x" aria-hidden="true"></i>
				        </span>
			        	<a href="../Controller/PortafolioC.php?accion=delete&por_cod=''&ne_cod=<?=$p['ne_cod']?>&cod_user=<?=$p['cod_user']?>&por_fech=<?=$p['por_fech']?>&todo=si" title="Eliminar Todo El historial">
				            <i class="fa fa-trash-o fa-2x color-red" aria-hidden="true"></i> 
				        </a>&nbsp;&nbsp;
				        <a href="../Controller/SimuladorC.php?accion=index&por_cod=''&oper=ver_simu&origen=por_cab&ne_cod=<?=$p['ne_cod']?>&cod_grupo=<?=$p['cod_grupo']?>&mont_est=<?=$por_mont_est?>&prec=<?=$por_pre?>&cant=<?=$por_cant?>&rent_obj=<?=$por_gan_net_obj?>&prec_act=<?=$gan_pre_obj?>" title="Ver en simulador">
				            <i class="fa fa-share fa-2x color-blue" aria-hidden="true"></i> 
				        </a>
			        </td>
			    </tr>
				
			<?php
			$c++;
			$nemonico = strtoupper($p['nemonico']);
			endwhile;
			?>
			</tbody>
		</table>
		</div>
		
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
            
			$(".ver-detalle").on("click", function(){

				node = $(this);
				var ne_cod = $(this).attr('data');
//console.log();
				if ($(".port_detalle_"+ne_cod).is(":visible") == false) {

					//fa fa-minus-square-o
					//fa-minus-square-o
					//fa-plus-square-o

					node.children('i').removeClass('fa-plus-square-o');
					node.children('i').addClass('fa-refresh fa-spin');
					
					$.ajax({
						url: '../Controller/PortafolioC.php?accion=ver_detalle',
						type: 'GET',
						dataType: 'html',
						data: {ne_cod:ne_cod},
						success: function(data){
							node.children('i').removeClass('fa-refresh fa-spin');
							node.children('i').addClass('fa-minus-square-o');
							$(data).insertAfter("#port_cabecera_"+ne_cod);
						}
					});

				}else{

					$(".port_detalle_"+ne_cod).remove();
					node.children('i').removeClass('fa-minus-square-o');
					node.children('i').addClass('fa-plus-square-o');
				}

				
				

			});
		    
        });
	</script>
</body>
</html>