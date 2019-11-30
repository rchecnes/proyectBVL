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
					data:{nemonico:$("#empresa").val()},
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
							<div class="col-lg-6">
								<div class="form-group">
									<label>Empresa:</label>
									<?php   
										$params = array(
											'select' => array('id'=>'empresa', 'name'=>'empresa', 'class'=>'form-control'),
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
							<div class="col-lg-6">
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