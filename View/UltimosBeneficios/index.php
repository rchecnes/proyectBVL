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

			$(".datepicker").datepicker({
				autoclose:true,
				format:'yyyy-mm-dd'
			});

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
					data:$("#form_ultimos_beneficios").serialize(),
					success:function(data){

						$("#divHistorico").html(data);
						$("#loading").hide();
					}
				});
			}

			$("#form_ultimos_beneficios").submit(function(env){
				env.preventDefault();
				buscarImportado();
			});

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
					<form method="POST" id="form_ultimos_beneficios">
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label>Empresa:</label>
									<?php   
										$params = array(
											'select' => array('id'=>'nemonico', 'name'=>'nemonico', 'class'=>'form-control'),
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
							<div class="col-lg-4">
								<div class="form-group">
									<label>Tipo Moneda:</label>
									<select id="ub_der_mon" name="ub_der_mon" class="form-control">
										<option value="">Todos</option>
										<option value="US$">US$</option>
										<option value="S/.">S/.</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
								<label>Fecha De Entrega (Desde - Hasta):</label>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
										<input type="text" id="ub_fech_ent_de" name="ub_fech_ent_de" class="form-control datepicker" readonly="readonly" value="<?=date('Y-m').'-01'?>" >	
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
										<input type="text" id="ub_fech_ent_ha" name="ub_fech_ent_ha" class="form-control datepicker" readonly="readonly" value="<?=date('Y-m-d')?>">
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
								<label>Sector:</label>
								<?php   
								$params = array(
									'select' => array('id'=>'cod_ector', 'name'=>'cod_ector', 'class'=>'form-control'),
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
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
									<a href="../Controller/UltimosBeneficiosC.php?accion=new" class="btn btn-danger" role="button">Nuevo Registro</a>
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