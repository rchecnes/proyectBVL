<!DOCTYPE html>
<html>
<head>
	<head>
    <?php include('../Include/Header.php'); ?>
    </head>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<script type="text/javascript">
		$(document).ready(function(){

			importarInformacion = function(){

		        $("#loading").show();

		        $.ajax({
		            type:'GET',
		            url: '../Controller/UltimosBeneficiosC.php?accion=importarmanual',
		            data:{nemonico:$("#empresa").val()},
		            success:function(data){
		                $("#loading").hide();
						buscarImportado();
		            }
		        });
		    }

			buscarImportado = function(){

				$("#loading").show();

				$.ajax({
					type:'GET',
					url: '../Controller/UltimosBeneficiosC.php?accion=listar',
					data:{nemonico:$("#empresa").val()},
					success:function(data){

						$("#divHistorico").html(data);
						$("#loading").hide();
					}
				});
			}

			buscarImportado();

		});

	</script>
	<div class="container">
		<div class="tabbable">
	        <ul class="nav nav-tabs" id="tabs">
	          <li class="active"><a data-toggle="tab" href="#cierre_del_dia"><h4>Últimos Beneficios</h4></a></li>
	        </ul>
	        <div class="tab-content">
	            <div id="cierre_del_dia" class="tab-pane fade in active">
	            	<div class="row">
						<div class="col-lg-4">
							<div class="form-group">
								<label>Empresa:</label>
								<?php   
									$params = array(
										'select' => array('id'=>'empresa', 'name'=>'empresa', 'class'=>'form-control'),
										'sql'    => "SELECT nemonico,nombre,moneda FROM empresa WHERE estado=1 AND cod_emp_bvl!=''",
										'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre,moneda', 'concat'=>' - ','descextra'=>''),
										'empty'  => 'Todos',
										'defect' => '',
										'edit'   => '',
										'enable' => 'enable'
									);

									Combobox($link, $params);
								?>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Tipo Derecho:</label>
								<select id="ub_der_tip" name="ub_der_tip" class="form-control">
									<option value="">Todos</option>
									<option value="Accs.">Acciones</option>
									<option value="Efe.">Efectivo</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
	                    <div class="col-lg-12">
	                    	<label>&nbsp;</label>
	                    	<div class="form-group">
								<input name="button" type="button" class="btn btn-default" id="button" value="Buscar Importado" onclick="buscarImportado()">
		                        <input name="button" type="button" class="btn btn-success" id="button" value="Importar Informacion" onclick="importarInformacion()">
								<a href="../Controller/UltimosBeneficiosC.php?accion=new" class="btn btn-danger" role="button">Nuevo Registro</a>
		                        <img src="../Assets/img/load.gif" id="loading" style="display: none">
		                    </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	    <br>
		<div id="divHistorico"></div>
			
	</div>

</body>
</html>