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
	<h1>SE ESTA DESARROLLANDO EN ESTE MODULO</h1>
	<div class="container">
		<h3 class="title">Portafolio</h3>
		<table class="table table-bordered">
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
		
			<?php
			$nemonico = '';
			$sum_mont_est = $sum_cant = $sum_gan_net = $sum_gan_net_act = 0;
			$c = 0;
			while ($p = mysqli_fetch_array($portafolio)):
			?>
				
				<tr>
			        <td class=""><?=$p['nemonico']?></td>
			        <td class=""><?=$p['nombre']?></td>
			        <td class=""><?=$p['por_fech_new'].' '.$p['por_hora']?></td>
			        <td class="">S/. <?=number_format($p['por_mont_est'],2,'.',',')?></td>
			        <td class=""><?=number_format($p['por_cant'],2,'.',',')?></td>
			        <td class=""><?=($p['por_prec']>=1)?number_format($p['por_prec'],2,'.',','):number_format($p['por_prec'],4,'.',',')?></td>
			        <td class=""><?=($p['cz_ci_fin']>=1)?number_format($p['cz_ci_fin'],2,'.',','):number_format($p['cz_ci_fin'],4,'.',',')?></td>
			        <td class=""><?=number_format($gan_net_act,2,'.',',')?></td>
			        <td class=""><?=($p['por_prec_obj']>=1)?number_format($p['por_prec_obj'],2,'.',','):number_format($p['por_prec_obj'],3,'.',',')?></td>
			        <td class=""><?=number_format($p['por_gan_net'],2,'.',',')?></td>
			        <td class="">
			        	<a href="#" title="Eliminar" class="ver-detalle button" data="<?=$p['cod_emp']?>">
				            <i class="fa fa-plus-square fa-2x color-red" aria-hidden="true"></i>
				        </a>
			        	<a href="../Controller/PortafolioC.php?accion=delete&cod_emp=<?=$p['cod_emp']?>&cod_user=<?=$p['cod_user']?>&por_fech=<?=$p['por_fech']?>" title="Eliminar">
				            <i class="fa fa-trash-o fa-2x color-red" aria-hidden="true"></i> 
				        </a>&nbsp;&nbsp;&nbsp;&nbsp;
				        <a href="../Controller/SimuladorC.php?accion=index&por_cod=<?=$p['por_cod']?>&oper=ver_simu&cod_emp=<?=$p['cod_emp']?>&cod_grupo=<?=$p['cod_grupo']?>&mont_est=<?=$p['por_mont_est']?>&prec=<?=$p['por_prec']?>&cant=<?=$p['por_cant']?>&rent_obj=<?=$p['por_rent_obj']?>&prec_act=<?=$p['cz_ci_fin']?>" title="Ver en simulador">
				            <i class="fa fa-share fa-2x color-blue" aria-hidden="true"></i> 
				        </a>

			        </td>
			    </tr>
				
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
            
			$(".ver-detalle").on("click", function(){

				var cod_emp = $(this).attr('data');

				$.ajax({
					url: '../Controller/PortafolioC.php?accion=ver_detalle',
					type: 'GET',
					dataType: 'html',
					data: {cod_emp:cod_emp},
					success: function(data){
						console.log(data);
					}
				});
				

			});
		    
        });
	</script>
</body>
</html>