<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php include('../Include/Header.php');?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<br>
	
	<div class="container">
		<h3>Simulador</h3><br>
		<div style="border-bottom: 1px solid #aba8a8;">
			<div class="row">
				<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="inputPassword" class="control-label">Grupo:</label>
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
				<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="inputPassword" class="control-label">Empresa:</label>
					    <?php
					    	$params = array(
			                    'select' => array('id'=>'cod_emp', 'name'=>'cod_emp', 'class'=>'form-control'),
			                    'sql'    => "SELECT DISTINCT(e.nemonico), e.nemonico,e.nombre FROM empresa_favorito ef INNER JOIN empresa e ON(ef.cod_emp=e.cod_emp) WHERE e.estado=1 AND ef.est_fab AND ef.cod_user='$cod_user'",
			                    'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre', 'concat'=>' - ','descextra'=>''),
			                    'empty'  => false,
			                    'defect' => 'ATACOBC1',
			                    'edit'   => '',
			                    'enable' => 'enable'
			                );
					      	Combobox($link, $params);
					     ?>
					</div>
				</div>
				<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group align-right ">
						<label>&nbsp;</label><br>
						<button type="button" id="buscar" class="btn btn-success">Buscar</button>
					</div>
				</div>
			</div>
		</div><br>
		<div id="detalle">
			<div class="row">
				<div class="col-lg-4">
					<table class="table table-bordered">
						<tr>
							<td style="width: 170px">Monto Estimado (S/.)</td>
							<td><input type="number" id="monto_estimado" class="form-control align-center" min="0" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>Precio Unit.</td>
							<td><input type="number" id="precio_unitario" class="form-control align-center" min="0" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>Cant. Acciones</td>
							<td><input type="number" id="cantidad_acciones" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>Monto Negociado</td>
							<td><input type="number" id="monto_negociado" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
            
			$("#cod_grupo").change(function(){

				$("#cod_emp").attr('disabled','disabled');
				$.ajax({
				    type:'GET',
				    url: '../Controller/GraficoC.php?accion=listfavorito',
				    data:{cod_grupo:$(this).val()},
				    success:function(data){

				        $("#cod_emp").html(data);
				        $("#cod_emp").removeAttr('disabled');
				    }
				});
			});


			buscar = function(){

				$("#monto_estimado").attr('readonly','readonly');
				$("#precio_unitario").attr('readonly','readonly');

				$.ajax({
				    type:'GET',
				    url: '../Controller/SimuladorC.php?accion=datoscab',
				    data:{cod_emp:$("#cod_emp").val()},
				    dataType: "json",
				    success:function(data){
				        //console.log(data.mont_est);
				        $("#monto_estimado").val(data.mont_est).removeAttr('readonly');
				        $("#precio_unitario").val(data.pre_unit).removeAttr('readonly');
				        $("#cantidad_acciones").val(data.cant_acc);
				        $("#monto_negociado").val(data.mont_neg);

				    }
				});
			}

			buscar();

			$("#buscar").on("click",function(){
				buscar();
			});

			


        });
	</script>
</body>
</html>