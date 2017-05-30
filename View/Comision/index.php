<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php
	//session_start();
	//http://glyphicons.bootstrapcheatsheets.com/
	include('../Include/Header.php');

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<br>
	
	<div class="container">
		<h3>Comisiones</h3>
		<br>
		
		<?php

		while ($co = mysqli_fetch_array($comisiones)):
		?>
			<table class="table table-bordered">
				<tr>
			        <th colspan="7" class="table-header">
			        	  	
			        </th>
			    </tr>
			    <tr>
			        <th class="td-header">Concepto</th>
			        <th class="td-header">RETRIBUCION BVL</th>
			        <th class="td-header">FONDO DE GARANTIA(11)</th>
			        <th class="td-header">FONDO DE LIQUIDACION</th>
			        <th class="td-header">RETRIBUCIONES CAVALI S.A.CLV(1)(7)</th>
			        <th class="td-header">CONTRIBUCION SMV (2)</th>
			        <th class="td-header">COMISION NETA SAB (3)</th>
			        <th class="td-header">I.G.V.</th>
			        <th class="td-header">OBJETO DEL COBRO (aplicable a la BVL)</th>
			    </tr>			    
			</table>
		<?php endwhile ?>
		</div>
		
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
            var url = '<?php echo $url; ?>';
		    $(".navbar-nav li a").each(function(){
		        if ($(this).attr('href').indexOf(url)!=-1) {
		            $(this).parent().addClass('active');
		        }else{
		            $(this).parent().removeClass('active');
		        }
		    });

		    
        });
	</script>
</body>
</html>