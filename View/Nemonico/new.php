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
		<br>
		<form class="form-horizontal" method="POST" action="../Controller/NemonicoC.php?accion=create" id="form_empresa">
			<div class="form-group">
				<label for="inputPassword" class="col-sm-2 control-label">Empresa:</label>
				<div class="col-sm-10">
				<?php
				$params = array(
						'select' => array('id'=>'emp_cod', 'name'=>'emp_cod', 'class'=>'form-control'),
						'sql'    => 'SELECT * FROM empresa WHERE emp_stdo=1',
						'attrib' => array('value'=>'emp_cod','desc'=>'emp_nomb', 'concat'=>'','descextra'=>''),
						'empty'  => '[SELECCIONAR]',
						'defect' => '',
						'edit'   => '',
						'enable' => 'enable'
					);
					Combobox($link, $params);
				?>
				</div>
			</div>  
			<div class="form-group">
				<label for="inputPassword" class="col-sm-2 control-label">Nemónico:</label>
				<div class="col-sm-10">
				<input type="text" class="form-control" id="nemonico" name="nemonico" placeholder="nemonico">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Codigo BVL:</label>
				<div class="col-sm-10">
				<input type="text" class="form-control" id="cod_emp_bvl" name="cod_emp_bvl" value="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword" class="col-sm-2 control-label">Segmento:</label>
				<div class="col-sm-10">
				<input type="text" class="form-control" id="segmento" name="segmento" placeholder="segmento">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword" class="col-sm-2 control-label">Moneda:</label>
				<div class="col-sm-10">
					<select id="moneda" name="moneda" class="form-control">
						<option value="US$">US$</option>
						<option value="S/">S/</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword" class="col-sm-2 control-label">Habilitado:</label>
				<div class="col-sm-10">
				<input type="checkbox" class="form-control" id="estado" name="estado">
				</div>
			</div>
			<p>
				<button type="submit" class="btn btn-success">Guardar</button>
				<a href="../Controller/NemonicoC.php?accion=index" class="btn btn-default" role="button">Cancelar</a>
			</p>
		</form>
	</div>
	<script type="text/javascript">
	//http://twitterbootstrap.org/bootstrap-form-validation/
	$(document).ready(function(){
		$('#form_empresa').bootstrapValidator({
	        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
			
	        fields: {
				cod_emp_bvl: {
	                validators: {
	                        notEmpty: {
	                        message: 'Campo requerido'
	                    }
	                }
	            },
	            emp_cod: {
	                validators: {
	                        notEmpty: {
	                        message: 'Campo requerido'
	                    }
	                }
	            },
	            nemonico: {
	                validators: {
	                    notEmpty: {
	                        message: 'Campo requerido'
	                    }
	                }
	            },
	            sector: {
	                validators: {
	                    notEmpty: {
	                        message: 'Campo requerido'
	                    }
	                }
	            },
	            moneda: {
	                validators: {
	                    notEmpty: {
	                        message: 'Campo requerido'
	                    }
	                }
	            }
	        }
            
        });
        
    });
	</script>
</body>
</html>