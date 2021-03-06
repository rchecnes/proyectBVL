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
		<form class="form-horizontal" method="POST" action="../Controller/EmpresaC.php?accion=create" id="form_empresa">
		
		  <div class="form-group">
		    <label class="col-sm-2 control-label">Nombre:</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="emp_nomb" name="emp_nomb" placeholder="Nombre">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="inputPassword" class="col-sm-2 control-label">Sector:</label>
		    <div class="col-sm-10">
		    <?php
		      $params = array(
                    'select' => array('id'=>'sec_cod', 'name'=>'sec_cod', 'class'=>'form-control'),
                    'sql'    => 'SELECT * FROM sector WHERE estado=1',
                    'attrib' => array('value'=>'cod_sector','desc'=>'nombre', 'concat'=>'','descextra'=>''),
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
		    <label for="inputPassword" class="col-sm-2 control-label">Habilitado:</label>
		    <div class="col-sm-10">
		      <input type="checkbox" class="form-control" id="emp_stdo" name="emp_stdo" checked="checked">
		    </div>
		  </div>
		  <p>
		  	<button type="submit" class="btn btn-success">Guardar</button>
		  	<a href="../Controller/EmpresaC.php?accion=index" class="btn btn-default" role="button">Cancelar</a>
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
				emp_nomb: {
	                validators: {
	                        notEmpty: {
	                        message: 'Campo requerido'
	                    }
	                }
	            },
				sec_cod: {
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