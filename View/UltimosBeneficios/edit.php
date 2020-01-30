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
				<form class="form-horizontal" method="POST" action="../Controller/UltimosBeneficiosC.php?accion=<?=$accion?>">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Empresa:</label>
							<?php   
								$params = array(
									'select' => array('id'=>'ub_nemonico', 'name'=>'ub_nemonico', 'class'=>'form-control'),
									'sql'    => "SELECT ne.nemonico,em.emp_nomb,ne.moneda FROM nemonico ne LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod) WHERE ne.estado='1' AND ne.imp_ind_fin!='' AND ne.cod_emp_bvl!=''",
									'attrib' => array('value'=>'nemonico','desc'=>'nemonico,emp_nomb,moneda', 'concat'=>' - ','descextra'=>''),
									'empty'  => false,
									'defect' => '',
									'edit'   => $ub_nemonico,
									'enable' => 'enable'
								);
								Combobox($link, $params);
							?>
							<input type="hidden" id="ub_cod" name="ub_cod" class="form-control" value="<?=$ub_cod?>" required>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Derecho  (Texto Completo):</label>
							<input type="text" id="ub_der_comp" name="ub_der_comp" class="form-control" value="<?=$ub_der_comp?>" disabled required>					
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Derecho  (Moneda):</label>
							<select class="form-control" id="ub_der_mon" name="ub_der_mon">
								<option value="">Ninguno</option>
								<option value="S/." <?=($ub_der_mon == 'S/.')?"selected='selected'":""?>>S/.</option>
								<option value="US$" <?=($ub_der_mon == 'US$')?"selected='selected'":""?>>US$</option>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Derecho  (Importe):</label>
							<input type="text" id="ub_der_imp" name="ub_der_imp" class="form-control" value="<?=$ub_der_imp?>" required>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Derecho  (Porcentaje):</label>
							<input type="text" id="ub_der_por" name="ub_der_por" class="form-control" value="<?=$ub_der_por?>">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Derecho  (Tipo):</label>
							<select class="form-control" id="ub_der_tip" name="ub_der_tip">
								<option value="Efe." <?=($ub_der_tip == 'Efe.')?"selected='selected'":""?>>Efe.</option>
								<option value="Accs." <?=($ub_der_tip == 'Accs.')?"selected='selected'":""?>>Accs.</option>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Fecha Acuerdo:</label>
							<input type="text" id="ub_fech_acu" name="ub_fech_acu" class="form-control datepicker" readonly="readonly" value="<?=$ub_fech_acu?>" required>
						</div>		  
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Fecha De Corte:</label>
							<input type="text" id="ub_fech_cor" name="ub_fech_cor" class="form-control datepicker" readonly="readonly" value="<?=$ub_fech_cor?>" required>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Fecha De Registro:</label>
							<input type="text" id="ub_fech_reg" name="ub_fech_reg" class="form-control datepicker" readonly="readonly" value="<?=$ub_fech_reg?>" required>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>Fecha De Entrega:</label>
							<input type="text" id="ub_fech_ent" name="ub_fech_ent" class="form-control datepicker" readonly="readonly" value="<?=$ub_fech_ent?>" required>
						</div>
					</div><br>
					<div class="row">
						<div class="col-lg-12">
							<button type="submit" class="btn btn-success">Guardar</button>
							<a href="../Controller/UltimosBeneficiosC.php?accion=index" class="btn btn-default" role="button">Cancelar</a>
						</div>
					</div>
				</form>
			</div>
		</div>
		
		
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker({
			autoclose:true,
			format:'yyyy-mm-dd'
		});
    });
	</script>
</body>
</html>