<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php include('../Include/Header.php');
	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<br>
	<div class="container">
		<h3><?=$titulo?></h3>
		<form class="form-horizontal" method="POST" action="../Controller/UltimosBeneficios.php?accion=<?=$accion?>">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Empresa:</label>
					<?php   
						$params = array(
							'select' => array('id'=>'ub_nemonico', 'name'=>'ub_nemonico', 'class'=>'form-control'),
							'sql'    => "SELECT nemonico,nombre,moneda FROM empresa WHERE estado='1' AND cod_emp_bvl!=''",
							'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre,moneda', 'concat'=>' - ','descextra'=>''),
							'empty'  => false,
							'defect' => '',
							'edit'   => '',
							'enable' => 'enable'
						);
						Combobox($link, $params);
					?>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Derecho  (Texto Completo):</label>
					<input type="text" id="ub_der_comp" name="ub_der_comp" class="form-control" required>					
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Derecho  (Moneda):</label>
					<input type="text" id="ub_der_mon" name="ub_der_mon" class="form-control" required>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Derecho  (Importe):</label>
					<input type="text" id="ub_der_imp" name="ub_der_imp" class="form-control" required>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Derecho  (Porcentaje):</label>
					<input type="text" id="ub_der_por" name="ub_der_por" class="form-control" required>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Derecho  (Tipo):</label>
					<input type="text" id="ub_der_tip" name="ub_der_tip" class="form-control" required>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Fecha Acuerdo:</label>
					<input type="text" id="ub_fech_acu" name="ub_fech_acu" class="form-control" required>
				</div>		  
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Fecha De Corte:</label>
					<input type="text" id="ub_fech_cor" name="ub_fech_cor" class="form-control" required>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Fecha De Registro:</label>
					<input type="text" id="ub_fech_reg" name="ub_fech_reg" class="form-control" required>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<label>Fecha De Entrega:</label>
					<input type="text" id="ub_fech_ent" name="ub_fech_ent" class="form-control" required>
				</div>
			</div><br>
			<div class="row">
				<div class="col-lg-12">
					<button type="submit" class="btn btn-success">Guardar</button>
					<a href="../Controller/EmpresaC.php?accion=index" class="btn btn-default" role="button">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		
    });
	</script>
</body>
</html>