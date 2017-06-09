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
			                    'sql'    => "SELECT DISTINCT(e.nemonico), e.nemonico,e.nombre FROM empresa_favorito ef 
			                    			 INNER JOIN empresa e ON(ef.cod_emp=e.cod_emp)
			                    			 INNER JOIN user_grupo ug ON(ef.cod_grupo=ug.cod_grupo)
			                    			 WHERE e.estado=1 AND ef.est_fab AND ef.cod_user='$cod_user'",
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
				<div class="col-lg-4 col-md-4 col-sm-4">
					<table class="table table-bordered excel">
						<tr>
							<td style="width: 50%">Monto Estimado (S/.)</td>
							<td><input type="text" id="monto_estimado" class="form-control align-center" min="0" step="0.0001" onkeyup="buscar('dos')"></input></td>
						</tr>
						<tr>
							<td>Precio Unit.</td>
							<td><input type="text" id="precio_unitario" class="form-control align-center" min="0" step="0.0001" onkeyup="buscar('dos')"></input></td>
						</tr>
						<tr>
							<td>Cant. Acciones</td>
							<td><input type="text" id="cantidad_acciones" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>Monto Negociado</td>
							<td><input type="text" id="monto_negociado" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<table class="table table-bordered excel">
						<tr>
							<th>Compra:</th>
						</tr>
						<tr>
							<td style="width: 50%">Comisión SAB</td>
							<td><input type="text" id="c_comision_sab" class="form-control align-center" min="0" step="0.0001" readonly="readonly" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>Cuota BVL</td>
							<td><input type="text" id="c_cuota_bvl" class="form-control align-center" min="0" step="0.0001" readonly="readonly" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>F. Garantía</td>
							<td><input type="text" id="c_f_garantia" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>CAVALI</td>
							<td><input type="text" id="c_cavali" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>F. Liquidación</td>
							<td><input type="text" id="c_f_liquidacion" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<th>Compra Total</th>
							<th><input type="text" id="c_compra_total" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></th>
						</tr>
						<tr>
							<td>IGV</td>
							<td><input type="text" id="c_igv" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<td>Com SMV</td>
							<td><input type="text" id="c_compra_smv" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
						</tr>
						<tr>
							<th>Costo Compra</th>
							<th><input type="text" id="c_costo_compra" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></th>
						</tr>
						<tr>
							<td>Poliza Compra</td>
							<td><input type="text" id="c_poliza_compra" class="form-control align-center" readonly="readonly" step="0.0001" pattern="[0-9]+([\.,][0-9]+)?"></input></td>
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

			/*infoCompra = function(){

				$.ajax({
				    type:'GET',
				    url: '../Controller/SimuladorC.php?accion=infocompra',
				    data:{cod_emp:$("#cod_emp").val()},
				    dataType: "json",
				    success:function(data){
				        $("#c_comision_sab").val(data.c_comision_sab);
						$("#c_cuota_bvl").val(data.c_cuota_bvl);
						$("#c_f_garantia").val(data.c_f_garantia);
						$("#c_cavali").val(data.c_cavali);
						$("#c_f_liquidacion").val(data.c_f_liquidacion);
						$("#c_compra_total").val(data.c_compra_total);
						$("#c_igv").val(data.c_igv);
						$("#c_compra_smv").val(data.c_compra_smv);
						$("#c_costo_compra").val(data.c_costo_compra);
						$("#c_poliza_compra").val(data.c_poliza_compra);
				    }
				});
			}*/

			buscar = function(tipo){

				var monto_estimado  = '';
				var precio_unitario = '';

				if (tipo == 'uno') {

					$("#monto_estimado").attr('readonly','readonly');
					$("#precio_unitario").attr('readonly','readonly');
				}else if (tipo == 'dos') {

					monto_estimado  = $("#monto_estimado").val();
					precio_unitario = $("#precio_unitario").val();
				}				

				$.ajax({
				    type:'GET',
				    url: '../Controller/SimuladorC.php?accion=datoscab',
				    data:{cod_emp:$("#cod_emp").val(),tipo:tipo,monto_estimado:monto_estimado,precio_unitario:precio_unitario},
				    dataType: "json",
				    success:function(data){
						//CABECERA
						if (tipo == 'uno') {
							$("#monto_estimado").val(data.mont_est).removeAttr('readonly');
				        	$("#precio_unitario").val(data.pre_unit).removeAttr('readonly');
						}
				        
				        $("#cantidad_acciones").val(data.cant_acc);
				        $("#monto_negociado").val(data.mont_neg);
				        //COMPRA
				        $("#c_comision_sab").val(data.c_comision_sab);
						$("#c_cuota_bvl").val(data.c_cuota_bvl);
						$("#c_f_garantia").val(data.c_f_garantia);
						$("#c_cavali").val(data.c_cavali);
						$("#c_f_liquidacion").val(data.c_f_liquidacion);
						$("#c_compra_total").val(data.c_compra_total);
						$("#c_igv").val(data.c_igv);
						$("#c_compra_smv").val(data.c_compra_smv);
						$("#c_costo_compra").val(data.c_costo_compra);
						$("#c_poliza_compra").val(data.c_poliza_compra);

				    }
				});
			}

			buscar('uno');

			$("#buscar").on("click",function(){
				buscar('uno');
			});


			


        });
	</script>
</body>
</html>