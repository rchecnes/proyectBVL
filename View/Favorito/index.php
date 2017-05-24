<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php
	//session_start();
	include('../Include/Header.php');

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<br>
	
	<div class="container">
		<h3>MIS FAVORITOS</h3>
		<br>
		<div style="border-bottom: 1px solid #aba8a8;">
			<form method="POST" action="../Controller/FavoritoC.php?accion=create">
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
						<div class="form-group">
						    <label for="inputPassword" class="control-label">Empresa:</label>
						    <?php

						    	//$cod_user = $_SESSION['cod_user'];

						      	$params = array(
				                    'select' => array('id'=>'cod_emp', 'name'=>'cod_emp', 'class'=>'form-control'),
				                    'sql'    => "SELECT * FROM empresa WHERE estado=1 AND cod_emp NOT IN(SELECT ef.cod_emp FROM empresa_favorito ef WHERE ef.cod_user='$cod_user')",
				                    'attrib' => array('value'=>'cod_emp','desc'=>'nemonico,nombre', 'concat'=>' - ','descextra'=>''),
				                    'empty'  => 'Todos',
				                    'defect' => '',
				                    'edit'   => '',
				                    'enable' => 'enable'
				                );
						      	Combobox($link, $params);
						     ?>
					  	</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<p class="align-right">
							<label>&nbsp;</label><br>
							<button type="submit" class="btn btn-success">Agregar Favorito</button>
						</p>
						
					</div>
				</div>
				
			</form>
		</div>
		<br>
		<div>
			
			<table class="table table-bordered">
			    <tr>
			        <th>Nemónico</th>
			        <th>Nombre</th>
			        <th>Sector</th>
			        <th align="center">Moneda</th>
			        <th colspan="2" style="text-align:center">Ult. Cotización</th>
			        <th align="center">Acciones</th>
			    </tr>
			    <?php
			    while ($em = mysqli_fetch_array($favoritos)) {
			    ?>
			    <tr>
			        <td><?=utf8_encode($em['nemonico'])?></td>
			        <td><?=utf8_encode($em['nom_empresa'])?></td>
			        <td><?=utf8_encode($em['nom_sector'])?></td>
			        <td><?=$em['moneda']?></td>
			        <td><?=$em['fe_ult_cotiza']?></td>
			        <td align="right"><?=number_format($em['cz_ult_cierre'],2,'.',',')?></td>
			        			        
			        <td width="200" align="center">
			        	<a href="../Controller/FavoritoC.php?accion=delete&cod_emp=<?=$em['cod_emp']?>&cod_user=<?=$em['cod_user']?>" class="btn btn-danger" role="button">Eliminar</a>
			        </td>
			    </tr>
			    <?php
			    }
			    ?>
			</table>
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