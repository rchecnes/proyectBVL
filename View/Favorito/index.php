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
			<form method="POST" action="../Controller/GrupoC.php?accion=create" id="form_grupo">
				<div class="row">
					<div class="col-lg-8">
						<div class="form-group">
							<label for="nombre_grupo" class="control-label">Grupo:</label>
							<input type="text" class="form-control" id="nom_grupo" name="nom_grupo"></input>
							<input type="hidden" class="form-control" id="cod_user" name="cod_user" value="<?=$cod_user?>"></input>
						</div>
					</div>
					<div class="col-lg-4">
						<p class="align-right">
							<label>&nbsp;</label><br>
							<button type="submit" class="btn btn-danger">Crear Grupo</button>
						</p>
					</div>
				</div>
			</form>
			<form method="POST" action="../Controller/FavoritoC.php?accion=create" id="form_favorito">
				<div class="row">
					<div class="col-lg-4 col-md-8 col-sm-6 col-xs-12">
						<div class="form-group">
						    <label for="inputPassword" class="control-label">Empresa:</label>
						    <?php
						    	$params = array(
				                    'select' => array('id'=>'cod_emp', 'name'=>'cod_emp', 'class'=>'form-control'),
				                    'sql'    => "SELECT * FROM empresa WHERE estado=1",
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
					<div class="col-lg-4 col-md-8 col-sm-6 col-xs-12">
						<div class="form-group">
						    <label for="inputPassword" class="control-label">Grupo:</label>
						    <?php
						      	$params = array(
				                    'select' => array('id'=>'cod_grupo', 'name'=>'cod_grupo', 'class'=>'form-control'),
				                    'sql'    => "SELECT * FROM user_grupo WHERE est_grupo=1 AND cod_user='$cod_user'",
				                    'attrib' => array('value'=>'cod_grupo','desc'=>'nom_grupo', 'concat'=>' - ','descextra'=>''),
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
		<div class="table-responsive">
		<?php

		$sqlg  = "SELECT * FROM user_grupo WHERE est_grupo=1 AND cod_user='$cod_user'";
		$respg = mysqli_query($link, $sqlg);

		while ($rg = mysqli_fetch_array($respg)):
		?>
			<table class="table table-bordered">
				<tr>
			        <th colspan="7" class="table-header">Grupo: <?=$rg['nom_grupo']?></th>
			    </tr>
			    <tr>
			        <th class="td-header">Nemónico</th>
			        <th class="td-header">Nombre</th>
			        <th class="td-header">Sector</th>
			        <th align="center" class="td-header">Moneda</th>
			        <th colspan="2" class="td-header" style="text-align:center">Ult. Cotización</th>
			        <th align="center" class="td-header">Acciones</th>
			    </tr>
			    <?php

			    /*$sql = "SELECT e.cod_emp,e.nombre AS nom_empresa, s.nombre AS nom_sector,e.nemonico,e.moneda,ef.cod_user,
						(SELECT DATE_FORMAT(cz_fecha,'%d/%m/%Y') FROM cotizacion c WHERE c.cz_codemp=e.nemonico ORDER BY c.cz_cod DESC LIMIT 1) AS fe_ult_cotiza,
						(SELECT cz_cierre FROM cotizacion c WHERE c.cz_codemp=e.nemonico ORDER BY c.cz_cod DESC LIMIT 1) AS cz_ult_cierre
						FROM empresa_favorito ef
						INNER JOIN empresa e ON (ef.cod_emp=e.cod_emp)
						INNER JOIN sector s ON(e.cod_sector=s.cod_sector)
						WHERE ef.cod_user='$cod_user' AND ef.cod_grupo='".$rg['cod_grupo']."'";*/
				$sql = "SELECT e.cod_emp,e.nombre AS nom_empresa, s.nombre AS nom_sector,e.nemonico,e.moneda,ef.cod_user, ef.cod_grupo,
						'00/00/000' AS fe_ult_cotiza,
						'0.00' AS cz_ult_cierre
						FROM empresa_favorito ef
						INNER JOIN empresa e ON (ef.cod_emp=e.cod_emp)
						INNER JOIN sector s ON(e.cod_sector=s.cod_sector)
						WHERE ef.cod_user='$cod_user' AND ef.cod_grupo='".$rg['cod_grupo']."'";
				$favoritos= mysqli_query($link, $sql);

			    while ($em = mysqli_fetch_array($favoritos)):
			    ?>
			    <tr>
			        <td><?=utf8_encode($em['nemonico'])?></td>
			        <td><?=$em['nom_empresa']?></td>
			        <td><?=utf8_encode($em['nom_sector'])?></td>
			        <td><?=$em['moneda']?></td>
			        <td><?=$em['fe_ult_cotiza']?></td>
			        <td align="right"><?=number_format($em['cz_ult_cierre'],2,'.',',')?></td>
			        			        
			        <td width="200" align="center">
			        	<a href="../Controller/FavoritoC.php?accion=delete&cod_emp=<?=$em['cod_emp']?>&cod_user=<?=$em['cod_user']?>&cod_grupo=<?=$em['cod_grupo']?>" class="btn btn-danger" role="button">Eliminar</a>
			        </td>
			    </tr>
			    <?php endwhile ?>
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

			$('#form_grupo').bootstrapValidator({
		        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
		        feedbackIcons: {
		            valid: 'glyphicon glyphicon-ok',
		            invalid: 'glyphicon glyphicon-remove',
		            validating: 'glyphicon glyphicon-refresh'
		        },
		        fields: {
		            nom_grupo: {
		                validators: {
		                        notEmpty: {
		                        message: 'Ingrese nombre al grupo'
		                    }
		                }
		            }		            
		        }
	        });

	        $('#form_favorito').bootstrapValidator({
		        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
		        feedbackIcons: {
		            valid: 'glyphicon glyphicon-ok',
		            invalid: 'glyphicon glyphicon-remove',
		            validating: 'glyphicon glyphicon-refresh'
		        },
		        fields: {
		            cod_emp: {
		                validators: {
		                        notEmpty: {
		                        message: 'Seleccione Empresa'
		                    }
		                }
		            },
		            cod_grupo: {
		                validators: {
		                    notEmpty: {
		                        message: 'Seleccione Grupo'
		                    }
		                }
		            }		            
		        }
	        });
        });
	</script>
</body>
</html>