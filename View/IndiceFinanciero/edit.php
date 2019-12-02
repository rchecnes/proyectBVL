<!DOCTYPE html>
<html>
<head>
	<title>Ultimos Beneficios</title>
	<?php include('../Include/Header.php');
	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<br>
	<div class="container">
		<div class="panel panel-success">
			<div class="panel-heading"><?=$titulo?></div>
			<div class="panel-body">
				<form class="form-horizontal" method="POST" action="../Controller/IndiceFinancieroC.php?accion=<?=$accion?>">
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<label>Empresa:</label>
							<?php   
								$params = array(
									'select' => array('id'=>'inf_nemonico', 'name'=>'inf_nemonico', 'class'=>'form-control','required'=>'required'),
									'sql'    => "SELECT nemonico,nombre,moneda FROM empresa WHERE estado='1' AND imp_ind_fin!='' AND cod_emp_bvl!=''",
									'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre,moneda', 'concat'=>' - ','descextra'=>''),
									'empty'  => false,
									'defect' => '',
									'edit'   => $inf_nemonico,
									'enable' => 'enable'
								);
								Combobox($link, $params);
							?>
							<input type="hidden" id="inf_detcod" name="inf_detcod" class="form-control" value="<?=$inf_detcod?>">
						</div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<label>Indice Financiero:</label>
							<?php   
								$params = array(
									'select' => array('id'=>'inf_codigo', 'name'=>'inf_codigo', 'class'=>'form-control','required'=>'required'),
									'sql'    => "SELECT * FROM cab_indice_financiero WHERE inf_stat='10'",
									'attrib' => array('value'=>'inf_codigo','desc'=>'inf_codigo,inf_nombre', 'concat'=>' - ','descextra'=>''),
									'empty'  => false,
									'defect' => '',
									'edit'   => $inf_codigo,
									'enable' => 'enable'
								);
								Combobox($link, $params);
							?>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<label>Año:</label>
							<select id="inf_anio" name="inf_anio" class="form-control" required>
							<?php
								for($a=2010;$a<=2050;$a++){
									
									if($accion=='update'){
										$selected = ($a == $inf_anio)?"selected='selected'":"";
									}else{
										$selected_1 = ($a==date('Y'))?"selected='selected'":"";
									}
									echo '<option value="'.$a.'" '.$selected.'>'.$a.'</option>';
								}
							?>
							</select>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<label>Valor:</label>
							<input type="text" id="inf_valor" name="inf_valor" class="form-control" value="<?=$inf_valor?>" required>
							
						</div>
					</div><br>
					<div class="row">
						<div class="col-lg-12">
							<button type="submit" class="btn btn-success">Guardar</button>
							<a href="../Controller/IndiceFinancieroC.php?accion=index" class="btn btn-default" role="button">Cancelar</a>
						</div>
					</div>
				</form>
			</div>
		</div>
		
		
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		/*$(".datepicker").datepicker({
			autoclose:true,
			format:'yyyy-mm-dd'
		});*/
    });
	</script>
</body>
</html>