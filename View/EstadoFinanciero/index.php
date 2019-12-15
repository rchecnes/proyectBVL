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

			importarInformacionEstadoFinanciero = function(){

		        $("#loading").show();

				var cef_nemonico = $("#cef_nemonico").val();
				var cef_tipo = $("#cef_tipo").val();
				var cef_anio = $("#cef_anio").val();
				var cef_trim = $("#cef_trim").val();
				var cef_peri = $("#cef_peri").val();

		        $.ajax({
		            type:'GET',
		            url: '../Controller/EstadoFinancieroC.php?accion=importarmanual',
		            data:{cef_nemonico:cef_nemonico,cef_tipo:cef_tipo,cef_anio:cef_anio,cef_trim:cef_trim,cef_peri:cef_peri},
		            success:function(data){
		                $("#loading").hide();
						buscarImportadoEstadoFinanciero();
		            }
		        });
		    }

			buscarImportadoEstadoFinanciero = function(){

				$("#loading").show();

				var cef_nemonico = $("#cef_nemonico").val();
				var cef_tipo = $("#cef_tipo").val();
				var cef_anio = $("#cef_anio").val();
				var cef_trim = $("#cef_trim").val();
				var cef_peri = $("#cef_peri").val();

				$.ajax({
					type:'GET',
					url: '../Controller/EstadoFinancieroC.php?accion=listar',
					data:{cef_nemonico:cef_nemonico,cef_tipo:cef_tipo,cef_anio:cef_anio,cef_trim:cef_trim,cef_peri:cef_peri},
					success:function(data){

						$("#divHistorico").html(data);
						$("#loading").hide();
					}
				});
			}

			buscarImportadoEstadoFinanciero();

			importarInformacionEstadoResultado = function(){

				$("#loading_cer").show();

				var cer_nemonico = $("#cer_nemonico").val();
				var cer_tipo = $("#cer_tipo").val();
				var cer_anio = $("#cer_anio").val();
				var cer_trim = $("#cer_trim").val();
				var cer_peri = $("#cer_peri").val();

				$.ajax({
					type:'GET',
					url: '../Controller/EstadoResultadoC.php?accion=importarmanual',
					data:{cer_nemonico:cer_nemonico,cer_tipo:cer_tipo,cer_anio:cer_anio,cer_trim:cer_trim,cer_peri:cer_peri},
					success:function(data){
						$("#loading_cer").hide();
						buscarImportadoEstadoResultado();
					}
				});
			}

			buscarImportadoEstadoResultado = function(){

				$("#loading_cer").show();

				var cer_nemonico = $("#cer_nemonico").val();
				var cer_tipo = $("#cer_tipo").val();
				var cer_anio = $("#cer_anio").val();
				var cer_trim = $("#cer_trim").val();
				var cer_peri = $("#cer_peri").val();

				$.ajax({
					type:'GET',
					url: '../Controller/EstadoResultadoC.php?accion=listar',
					data:{cer_nemonico:cer_nemonico,cer_tipo:cer_tipo,cer_anio:cer_anio,cer_trim:cer_trim,cer_peri:cer_peri},
					success:function(data){

						$("#divHistorico").html(data);
						$("#loading_cer").hide();
					}
				});
			}

			$("ul#tabs li a").click(function(){
				//alert($(this).attr('href'));
				if($(this).attr('href') == '#tab_estado_financiero'){buscarImportadoEstadoFinanciero();}
				else if($(this).attr('href') == '#tab_estado_resultado'){buscarImportadoEstadoResultado();}
			});
		});

	</script>
	<div class="container">
		<div class="tabbable">
	        <ul class="nav nav-tabs" id="tabs">
	          <li class="active"><a data-toggle="tab" href="#tab_estado_financiero"><h4>Balance General</h4></a></li>
			  <li><a data-toggle="tab" href="#tab_estado_resultado"><h4>Estado De Resultado</h4></a></li>
	        </ul>
	        <div class="tab-content">
	            <div id="tab_estado_financiero" class="tab-pane fade in active">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label>Empresa:</label>
								<?php   
									$params = array(
										'select' => array('id'=>'cef_nemonico', 'name'=>'cef_nemonico', 'class'=>'form-control'),
										'sql'    => "SELECT nemonico,nombre,moneda FROM empresa WHERE estado=1 AND imp_sit_fin!=''",
										'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre,moneda', 'concat'=>' - ','descextra'=>''),
										'empty'  => false,
										'defect' => 'GRAMONC1',
										'edit'   => '',
										'enable' => 'enable'
									);

									Combobox($link, $params);
								?>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Tipo:</label>
								<select id="cef_tipo" name="cef_tipo" class="form-control">
									<option value="I">Individual</option>
									<option value="C" selected="selected">Consolidada</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Año:</label>
								<select id="cef_anio" name="cef_anio" class="form-control">
								<?php
								for($a=2000;$a<=2050;$a++){
									$selected = ($a == date('Y'))?'selected="selected"':'';
									echo '<option value="'.$a.'" '.$selected.'>'.$a.'</option>';
								}
								?>
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Periodo:</label>
								<select id="cef_peri" name="cef_peri" class="form-control">
									<option value="T" selected="selected">Trimestral No Auditada</option>
									<option value="A">Auditada Anual</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Trimestre:</label>
								<select id="cef_trim" name="cef_trim" class="form-control">
									<option value="1">Primer Trimestre</option>
									<option value="2">Segundo Trimestre</option>
									<option value="3" selected="selected">Tercer Trimestre</option>
									<option value="4">Cuarto Trimestre</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label>&nbsp;</label>
							<div class="form-group">
								<input name="button" type="button" class="btn btn-default" id="button" value="Buscar Importado" onclick="buscarImportadoEstadoFinanciero()">
								<input name="button" type="button" class="btn btn-success" id="button" value="Importar Informacion" onclick="importarInformacionEstadoFinanciero()">
								<img src="../Assets/img/load.gif" id="loading" style="display: none">
							</div>
						</div>
					</div>
	            </div>
				<div id="tab_estado_resultado" class="tab-pane fade in">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label>Empresa:</label>
								<?php   
									$params = array(
										'select' => array('id'=>'cer_nemonico', 'name'=>'cer_nemonico', 'class'=>'form-control'),
										'sql'    => "SELECT nemonico,nombre,moneda FROM empresa WHERE estado=1 AND imp_sit_fin!=''",
										'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre,moneda', 'concat'=>' - ','descextra'=>''),
										'empty'  => false,
										'defect' => 'GRAMONC1',
										'edit'   => '',
										'enable' => 'enable'
									);

									Combobox($link, $params);
								?>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Tipo:</label>
								<select id="cer_tipo" name="cer_tipo" class="form-control">
									<option value="I">Individual</option>
									<option value="C" selected="selected">Consolidada</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Año:</label>
								<select id="cer_anio" name="cer_anio" class="form-control">
								<?php
								for($a=2000;$a<=2050;$a++){
									$selected = ($a == date('Y'))?'selected="selected"':'';
									echo '<option value="'.$a.'" '.$selected.'>'.$a.'</option>';
								}
								?>
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Periodo:</label>
								<select id="cer_peri" name="cer_peri" class="form-control">
									<option value="T" selected="selected">Trimestral No Auditada</option>
									<option value="A">Auditada Anual</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Trimestre:</label>
								<select id="cer_trim" name="cer_trim" class="form-control">
									<option value="1">Primer Trimestre</option>
									<option value="2">Segundo Trimestre</option>
									<option value="3" selected="selected">Tercer Trimestre</option>
									<option value="4">Cuarto Trimestre</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label>&nbsp;</label>
							<div class="form-group">
								<input name="button" type="button" class="btn btn-default" id="button" value="Buscar Importado" onclick="buscarImportadoEstadoResultado()">
								<input name="button" type="button" class="btn btn-success" id="button" value="Importar Informacion" onclick="importarInformacionEstadoResultado()">
								<img src="../Assets/img/load.gif" id="loading_cer" style="display: none">
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