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
			        <th colspan="2">Empresa</th>
			        <th colspan="4">Compra</th>
			        <th colspan="2">Actual</th>
			        <th colspan="2">Objetivo</th>
			        <th>&nbsp;</th>
			    </tr>
			    <tr>
			        <th class="">Nemonico</th>
			        <th class="">Nombre</th>
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

				$por_mont_est_new = $p['por_mont_est_new'];
				$por_cant_new     = $p['por_cant_new'];
				$pre_compra = ($por_mont_est_new / $por_cant_new>=1)?number_format($por_mont_est_new / $por_cant_new,2,'.',','):number_format($por_mont_est_new / $por_cant_new,3,'.',',');
			?>
				
				<tr id="port_cabecera_<?=$p['cod_emp']?>" bgcolor="#f9f9f9">
			        <td class=""><?=$p['nemonico']?></td>
			        <td class=""><?=$p['nombre']?></td>
			        <td class="">--</td>
			        <td class="">S/. <?=number_format($por_mont_est_new,2,'.',',')?></td>
			        <td class=""><?=number_format($por_cant_new,2,'.',',')?></td>
			        <td class=""><?=$pre_compra?></td>
			        <td class=""></td>
			        <td class=""></td>
			        <td class=""></td>
			        <td class=""></td>
			        <td class="">
			        	<span href="#" title="Ver Detalle" class="ver-detalle" data="<?=$p['cod_emp']?>">
				            <i class="fa fa-plus-square-o fa-2x" aria-hidden="true"></i>
				        </span>
			        	<a href="../Controller/PortafolioC.php?accion=delete&por_cod=''&cod_emp=<?=$p['cod_emp']?>&cod_user=<?=$p['cod_user']?>&por_fech=<?=$p['por_fech']?>&todo=si" title="Eliminar Todo El historial">
				            <i class="fa fa-trash-o fa-2x color-red" aria-hidden="true"></i> 
				        </a>&nbsp;&nbsp;
				        <a href="../Controller/SimuladorC.php?accion=index&por_cod='<?=$p['por_cod']?>'&oper=ver_simu&cod_emp=<?=$p['cod_emp']?>&cod_grupo=<?=$p['cod_grupo']?>&mont_est=<?=$p['por_mont_est']?>&prec=<?=$p['por_prec']?>&cant=<?=$p['por_cant']?>&rent_obj=<?=$p['por_rent_obj']?>&prec_act=<?=$p['cz_ci_fin']?>" title="Ver en simulador">
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
				var cod_emp = $(this).attr('data');
//console.log();
				if ($(".port_detalle_"+cod_emp).is(":visible") == false) {

					//fa fa-minus-square-o
					//fa-minus-square-o
					//fa-plus-square-o
					
					$.ajax({
						url: '../Controller/PortafolioC.php?accion=ver_detalle',
						type: 'GET',
						dataType: 'html',
						data: {cod_emp:cod_emp},
						success: function(data){
							node.children('i').removeClass('fa-plus-square-o');
							node.children('i').addClass('fa-minus-square-o');
							$(data).insertAfter("#port_cabecera_"+cod_emp);
						}
					});

				}else{

					$(".port_detalle_"+cod_emp).remove();
					node.children('i').removeClass('fa-minus-square-o');
					node.children('i').addClass('fa-plus-square-o');
				}

				
				

			});
		    
        });
	</script>
</body>
</html>