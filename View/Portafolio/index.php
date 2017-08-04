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
		        <th colspan="3">Compra</th>
		        <th colspan="3">Simulador</th>
		    </tr>
		    <tr>
		        <th class="">Nemonico</th>
		        <th class="">Nombre</th>
		        <th class="">Fecha</th>
		        <th class="">Cant.</th>
		        <th class="">Precio</th>
		        <th class="">P. Actual</th>
		        <th class="">Gan. Neta</th>
		        <th class="">Acciones</th>
		    </tr>			    
		
			<?php while ($p = mysqli_fetch_array($portafolio)):?>
			<tr>
		        <td class=""><?=$p['nemonico']?></td>
		        <td class=""><?=$p['nombre']?></td>
		        <td class=""><?=$p['por_fech']?></td>
		        <td class=""><?=$p['por_cant']?></td>
		        <td class=""><?=$p['por_prec']?></td>
		        <td class=""><?=$p['cz_ci_fin']?></td>
		        <td class=""></td>
		        <td class="">
		        	<a href="../Controller/PortafolioC.php?accion=delete&cod_emp=<?=$p['cod_emp']?>&cod_user=<?=$p['cod_user']?>&por_fech=<?=$p['por_fech']?>" title="Eliminar">
			            <i class="fa fa-trash-o fa-2x color-red" aria-hidden="true"></i> 
			        </a>&nbsp;&nbsp;&nbsp;&nbsp;
			        <a href="../Controller/PortafolioC.php?accion=delete&cod_emp=<?=$p['cod_emp']?>&cod_user=<?=$p['cod_user']?>&por_fech=<?=$p['por_fech']?>" title="Ver Simulador">
			            <i class="fa fa-share fa-2x color-blue" aria-hidden="true"></i> 
			        </a>
		        </td>
		    </tr>	
			<?php endwhile; ?>
		</table>
		</div>
		
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
            

		    
        });
	</script>
</body>
</html>