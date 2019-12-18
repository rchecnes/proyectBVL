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
		            url: '../Controller/IndiceFinancieroC.php?accion=importarmanual',
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
					url: '../Controller/IndiceFinancieroC.php?accion=listar',
					data:$("#form_indice_financiero").serialize(),
					success:function(data){

						$("#divHistorico").html(data);
						$("#loading").hide();
					}
				});
			}

			$("#form_indice_financiero").submit(function(env){
				env.preventDefault();
				buscarImportado();
			});

			buscarImportado();
		});

	</script>
	<div class="container">
		<div class="tabbable">
	        <ul class="nav nav-tabs" id="tabs">
	          <li class="active"><a data-toggle="tab" href="#cierre_del_dia"><h4>Indices Financieros</h4></a></li>
	        </ul>
	        <div class="tab-content">
	            <div id="cierre_del_dia" class="tab-pane fade in active">
					<form method="POST" id="form_indice_financiero">
						<div class="row">
							<div class="col-lg-3">
								<div class="form-group">
									<label>Empresa:</label>
									<?php   
										$params = array(
											'select' => array('id'=>'inf_nemonico', 'name'=>'inf_nemonico', 'class'=>'form-control'),
											'sql'    => "SELECT nemonico,nombre,moneda FROM empresa WHERE estado=1 AND imp_ind_fin!='' AND cod_emp_bvl!=''",
											'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre,moneda', 'concat'=>' - ','descextra'=>''),
											'empty'  => 'Todos',
											'defect' => 'GRAMONC1',
											'edit'   => '',
											'enable' => 'enable'
										);

										Combobox($link, $params);
									?>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<label>Indice Financiero:</label>
								<?php   
									$params = array(
										'select' => array('id'=>'inf_codigo', 'name'=>'inf_codigo', 'class'=>'form-control'),
										'sql'    => "SELECT * FROM cab_indice_financiero WHERE inf_stat='10'",
										'attrib' => array('value'=>'inf_codigo','desc'=>'inf_codigo,inf_nombre', 'concat'=>' - ','descextra'=>''),
										'empty'  => 'Todos',
										'empty_value'  => '',
										'defect' => '',
										'edit'   => $inf_codigo,
										'enable' => 'enable'
									);
									Combobox($link, $params);
								?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<label>Sector:</label>
								<?php   
								$params = array(
									'select' => array('id'=>'cod_sector', 'name'=>'cod_sector', 'class'=>'form-control'),
									'sql'    => 'SELECT * FROM sector WHERE estado=1',
									'attrib' => array('value'=>'cod_sector','desc'=>'nombre', 'concat'=>'','descextra'=>''),
									'empty'  => 'Todos',
									'defect' => '',
									'edit'   => '',
									'enable' => 'enable'
								);

								Combobox($link, $params);
								?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<label>Grupo:</label>
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
						<div class="row">
							<div class="col-lg-12">
								<label>&nbsp;</label>
								<div class="form-group">
									<input name="button" type="submit" class="btn btn-default" id="button" value="Buscar Importado">
									<input name="button" type="button" class="btn btn-success" id="button" value="Importar Informacion" onclick="importarInformacion()">
									<a href="../Controller/IndiceFinancieroC.php?accion=new" class="btn btn-danger" role="button">Nuevo Registro</a>
									<img src="../Assets/img/load.gif" id="loading" style="display: none">
								</div>
							</div>
						</div>
					</form>
	            </div>
	        </div>
	    </div>
	    <br>
		<div id="divHistorico"></div>
			
	</div>

</body>
</html>