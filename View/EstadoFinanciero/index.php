<!DOCTYPE html>
<html>
<head>
	<head>
    <?php include('../Include/Header.php'); ?>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<style>
	#div_detalle table td, #div_detalle table th{
		font-size:12px;
		padding: 4px;
		padding-top:2px;
		padding-bottom:2px;
		vertical-align: middle;
	}
	</style>
    </head>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<script type="text/javascript">
		$(document).ready(function(){

			importarInformacionEstadoFinanciero = function(){

				var cef_nemonico = $("#cef_nemonico").val();
				var cef_tipo = $("#cef_tipo").val();
				var cef_anio = $("#cef_anio").val();
				var cef_trim = $("#cef_trim").val();
				var cef_peri = $("#cef_peri").val();

				var estados = ['EstadoFinancieroC', 'EstadoResultadoC'];
				var cant_estado = estados.length;

				for(es_fi = 0; es_fi<estados.length; es_fi++){
					//console.log(cant_estado+'=='+(e+1));
					$('#importar_estado').attr('disabled','disabled');

					$("#loading").show();

					$.ajax({
						type:'GET',
						url: '../Controller/'+estados[es_fi]+'.php?accion=importarmanual',
						data:{cef_nemonico:cef_nemonico,cef_tipo:cef_tipo,cef_anio:cef_anio,cef_trim:cef_trim,cef_peri:cef_peri},
						success:function(data){
							//$("#loading").hide();
							//$('#importar_estado').removeAttr('disabled');						
						}
					});

					//console.log(cant_estado+'==='+(es_fi));
					if(cant_estado == (es_fi+1)){
						setTimeout(function(){
							$("#loading").hide();
							$('#importar_estado').removeAttr('disabled');
							buscarImportadoEstadoFinanciero(); 
						}, 3000);				
					}
				}
		    }

			buscarImportadoEstadoFinanciero = function(){

				$("#loading").show();
				$('#importar_estado').attr('disabled','disabled');

				var cef_nemonico = $("#cef_nemonico").val();
				var cef_tipo = $("#cef_tipo").val();
				var cef_anio = $("#cef_anio").val();
				var cef_trim = $("#cef_trim").val();
				var cef_peri = $("#cef_peri").val();

				var cef_est_fin = $("#cef_est_fin").val();
				var ruta = '';
				if(cef_est_fin == 'BAL_GEN'){ruta = 'EstadoFinancieroC';}
				else if(cef_est_fin == 'EST_RES'){ruta = 'EstadoResultadoC';}

				$.ajax({
					type:'GET',
					url: '../Controller/'+ruta+'.php?accion=listar',
					data:{cef_nemonico:cef_nemonico,cef_tipo:cef_tipo,cef_anio:cef_anio,cef_trim:cef_trim,cef_peri:cef_peri},
					success:function(data){

						$("#div_detalle").html(data);
						$("#loading").hide();
						$('#importar_estado').removeAttr('disabled');
					}
				});
			}

			mostrarocultartrimestre = function(){
				if($('#cef_peri').val() == 'T'){$('#grupo_cef_trim').show();}
				else if($('#cef_peri').val() == 'A'){$('#grupo_cef_trim').hide();}
			}
			mostrarocultartrimestre();

			$('#cef_peri').on('change', function(){
				mostrarocultartrimestre();
			});

			buscarImportadoEstadoFinanciero();

			analisisEstado1 = function(){

				$("#loading_cefa").show();

				var cefa_nemonico = $("#cefa_nemonico").val();
				var cefa_anio = $("#cefa_anio").bootstrapSlider('getValue');
				var cefa_tipo = $("#cefa_tipo").val();

				$.ajax({
					type:'GET',
					url: '../Controller/EstadoFinancieroC.php?accion=analisis',
					data:{cefa_nemonico:cefa_nemonico, cefa_anio:cefa_anio, cefa_tipo:cefa_tipo},
					success:function(data){

						$("#div_detalle").html(data);
						$("#loading_cefa").hide();
					}
				});
			}

			analisisEstado2 = function(){
				
				$("#loading_cera").show();

				var cara_nemonico = $("#cara_nemonico").val();
				var cara_tri = $("input:radio[name='cara_tri']:checked").val();
				var cara_tipo = $("#cara_tipo").val();
				//input:radio[name=edad]:checked
				$.ajax({
					type:'GET',
					url: '../Controller/EstadoResultadoC.php?accion=analisis',
					data:{cara_nemonico:cara_nemonico, cara_tri:cara_tri, cara_tipo:cara_tipo},
					success:function(data){

						$("#div_detalle").html(data);
						$("#loading_cera").hide();
					}
				});
			}

			$("ul#tabs li a").click(function(){
				//alert($(this).attr('href'));
				if($(this).attr('href') == '#tab_estado_financiero'){buscarImportadoEstadoFinanciero();}
				else if($(this).attr('href') == '#tab_analisis_estado_1'){analisisEstado1();}
				else if($(this).attr('href') == '#tab_analisis_estado_2'){analisisEstado2();}
			});

			//Slider
			$("input.slider").bootstrapSlider({
				tooltip: 'always'
			});
		});

		

		//$("#cafa_anio").slider({ id: "slider12a", min: 0, max: 10, value: 5 });
		//var sliderA = new Slider("#cafa_anio", { id: "slider12a", min: 0, max: 10, value: 5 });

	</script>
	<div class="container">
		<div class="tabbable">
	        <ul class="nav nav-tabs" id="tabs">
	          <li class="active"><a data-toggle="tab" href="#tab_estado_financiero"><h4>Estado Financiero</h4></a></li>
			  <li><a data-toggle="tab" href="#tab_analisis_estado_1"><h4>Analisis Anual</h4></a></li>
			  <li><a data-toggle="tab" href="#tab_analisis_estado_2"><h4>Analisis Trimestral</h4></a></li>
	        </ul>
	        <div class="tab-content">
	            <div id="tab_estado_financiero" class="tab-pane fade in active">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label>Estado Financiero:</label>
								<select id="cef_est_fin" name="cef_est_fin" class="form-control">
									<option value="BAL_GEN">Balance General</option>
									<option value="EST_RES">Estado De Resultados</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Nemonico:</label>
								<?php   
									$params = array(
										'select' => array('id'=>'cef_nemonico', 'name'=>'cef_nemonico', 'class'=>'form-control'),
										'sql'    => "SELECT ne.nemonico,em.emp_nomb,ne.moneda FROM nemonico ne LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod) WHERE ne.estado=1 AND ne.imp_sit_fin!=''",
										'attrib' => array('value'=>'nemonico','desc'=>'nemonico,emp_nomb,moneda', 'concat'=>' - ','descextra'=>''),
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
									$selected = ($a == (date('Y')-1))?'selected="selected"':'';
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
									<option value="T">Trimestral No Auditada</option>
									<option value="A" selected="selected">Auditada Anual</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3" id="grupo_cef_trim">
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
								<input name="button" type="button" class="btn btn-default" id="buscar_importado" value="Buscar Importado" onclick="buscarImportadoEstadoFinanciero()">
								<input name="button" type="button" class="btn btn-success" id="importar_estado" value="Importar Información (Todos Los Estados)" onclick="importarInformacionEstadoFinanciero()">
								<img src="../Assets/img/load.gif" id="loading" style="display: none">
							</div>
						</div>
					</div>
	            </div>
				<div id="tab_analisis_estado_1" class="tab-pane fade in">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label>Nemonico:</label>
								<?php   
									$params = array(
										'select' => array('id'=>'cefa_nemonico', 'name'=>'cefa_nemonico', 'class'=>'form-control'),
										'sql'    => "SELECT ne.nemonico,em.emp_cod,em.emp_nomb,ne.moneda FROM nemonico ne LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod) WHERE ne.estado=1 AND ne.imp_sit_fin!=''",
										'attrib' => array('value'=>'nemonico','desc'=>'nemonico,emp_nomb,moneda', 'concat'=>' - ','descextra'=>''),
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
								<select id="cefa_tipo" name="cefa_tipo" class="form-control">
									<option value="I">Individual</option>
									<option value="C" selected="selected">Consolidada</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Año:</label><br>
								<?=$anio_min?>&nbsp;&nbsp;
								<input id="cefa_anio" name="cefa_anio" class="slider" type="text" data-slider-min="<?=$anio_min?>" data-slider-max="<?=$anio_max?>" data-slider-value="<?=$anio_def?>" data-slider-step="1" style="width:82%"/>
								&nbsp;&nbsp;<?=$anio_max?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label>&nbsp;</label>
							<div class="form-group">
								<input name="button" type="button" class="btn btn-success" value="Buscar" onclick="analisisEstado1()">
								<img src="../Assets/img/load.gif" id="loading_cefa" style="display: none">
							</div>
						</div>
					</div>
				</div>
				<div id="tab_analisis_estado_2" class="tab-pane fade in">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label>Nemonico:</label>
								<?php   
									$params = array(
										'select' => array('id'=>'cara_nemonico', 'name'=>'cara_nemonico', 'class'=>'form-control'),
										'sql'    => "SELECT ne.nemonico,em.emp_cod,em.emp_nomb,ne.moneda FROM nemonico ne LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod) WHERE ne.estado=1 AND ne.imp_sit_fin!=''",
										'attrib' => array('value'=>'nemonico','desc'=>'nemonico,emp_nomb,moneda', 'concat'=>' - ','descextra'=>''),
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
								<select id="cara_tipo" name="cara_tipo" class="form-control">
									<option value="I">Individual</option>
									<option value="C" selected="selected">Consolidada</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
							<label>Trimestre:</label><br>
								<?php
								for($t=0; $t<count($trim_arr); $t++){
									$selected = ($trim_arr[$t] == $tri_def)?"checked='checked'":"";
									echo '<label><input type="radio" id="cara_tri" name="cara_tri" value="'.$trim_arr[$t].'"'.$selected.'>'.$trim_arr[$t].'</label>&nbsp;&nbsp;';
								}
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label>&nbsp;</label>
							<div class="form-group">
								<input name="button" type="button" class="btn btn-success" value="Buscar" onclick="analisisEstado2()">
								<img src="../Assets/img/load.gif" id="loading_cera" style="display: none">
							</div>
						</div>
					</div>
				</div>
	        </div>
	    </div>
	    <br>
		<div class="panel panel-default">
			<div class="panel-body">
				<div id="div_detalle"></div>
			</div>
		</div>			
	</div>

</body>
</html>