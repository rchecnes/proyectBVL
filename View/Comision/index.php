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
		<h3 class="title">Comisiones</h3>
		<table class="table table-bordered">
		    <tr>
		        <th class="">Concepto</th>
		        <th class="">RETRIBUCION BVL</th>
		        <th class="">FONDO DE GARANTIA(11)</th>
		        <th class="">FONDO DE LIQUIDACION</th>
		        <th class="">RETRIBUCIONES CAVALI S.A.CLV(1)(7)</th>
		        <th class="">CONTRIBUCION SMV (2)</th>
		        <th class="">COMISION NETA SAB (3)</th>
		        <th class="">I.G.V.</th>
		        <th class="">OBJETO DEL COBRO (aplicable a la BVL)</th>
		    </tr>			    
		
			<?php while ($co = mysqli_fetch_array($comisiones)):?>
			<tr>
		        <td class=""><?=$co['concep']?></td>
		        <td class=""><?=$co['retrib_bvl']."%"?></td>
		        <td class=""><?=$co['fondo_garant']."%"?></td>
		        <td class=""><?=$co['fondo_liq']."%"?></td>
		        <td class=""><?=$co['retrib_caval_iclv']."%"?></td>
		        <td class=""><?=$co['contrib_smv']."%"?></td>
		        <td class=""><?=$co['comis_neta_sab']."%"?></td>
		        <td class=""><?=$co['igv']."%"?></td>
		        <td class=""><?=$co['obj_cobro']?></td>
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