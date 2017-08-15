<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php include('../Include/Header.php');?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
		
	<div class="container">
		<h3 class="title">Simulador</h3>
		<div style="border-bottom: 1px solid #aba8a8;">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="inputPassword" class="control-label">Grupo:</label>
					    <?php
					      	$params = array(
			                    'select' => array('id'=>'cod_grupo', 'name'=>'cod_grupo', 'class'=>'form-control'),
			                    'sql'    => "SELECT * FROM user_grupo WHERE est_grupo=1 AND cod_user='$cod_user'",
			                    'attrib' => array('value'=>'cod_grupo','desc'=>'nom_grupo', 'concat'=>' - ','descextra'=>''),
			                    'empty'  => 'Todos',
			                    'defect' => $cod_grupo,
			                    'edit'   => '',
			                    'enable' => 'enable'
			                );
					      	Combobox($link, $params);
					     ?>
					    
				  	</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="inputPassword" class="control-label">Empresa:</label>
					    <?php
					    	$andwhere = ($cod_grupo !='')?" AND ug.cod_grupo='$cod_grupo'":"";
					    	$params = array(
			                    'select' => array('id'=>'cod_emp', 'name'=>'cod_emp', 'class'=>'form-control'),
			                    'sql'    => "SELECT DISTINCT(e.cod_emp), e.nemonico,e.nombre FROM empresa_favorito ef 
			                    			 INNER JOIN empresa e ON(ef.cod_emp=e.cod_emp)
			                    			 INNER JOIN user_grupo ug ON(ef.cod_grupo=ug.cod_grupo)
			                    			 WHERE e.estado=1 AND ef.est_fab AND ef.cod_user='$cod_user' $andwhere",
			                    'attrib' => array('value'=>'cod_emp','desc'=>'nemonico,nombre', 'concat'=>' - ','descextra'=>''),
			                    'empty'  => false,
			                    'defect' => $cod_emp,
			                    'edit'   => '',
			                    'enable' => 'enable'
			                );
					      	Combobox($link, $params);
					     ?>
					</div>
				</div>
				<!--<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
					<div class="form-group align-right ">
						<label>&nbsp;</label><br>
						<button type="button" id="buscar" class="btn btn-success">Buscar</button>
					</div>
				</div>-->
			</div>
		</div><br>
		<div id="detalle">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4">
					<table class="table table-bordered excel" id="inversion">
						<tr>
							<th>Inversión:</th>
						</tr>
						<tr>
							<td style="width: 50%">Monto Estimado (S/.)</td>
							<td><input type="text" id="monto_estimado" class="form-control align-center" value="<?=$mont_est?>" onkeyup="buscar('dos','')"></input>
							<input type="hidden" name="por_cod" id="por_cod" value="<?=$por_cod?>">
							</td>
						</tr>
						<tr>
							<td>Precio Unit.</td>
							<td><input type="text" id="precio_unitario" class="form-control align-center" value="<?=$prec?>" onkeyup="buscar('dos','')"></input></td>
						</tr>
						<tr>
							<td>Cant. Acciones</td>
							<td><input type="text" id="cantidad_acciones" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>Monto Negociado</td>
							<td><input type="text" id="monto_negociado" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
					</table>
					<table class="table table-bordered excel" id="ganancia">
						<tr>
							<th>Ganancia:</th>
						</tr>
						<tr>
							<td style="width: 50%">Renta. Objetivo</td>
							<td><input type="text" id="gan_rent_obj" class="form-control align-center" onkeyup="buscar('dos','renta_obj')" value="<?=$rent_obj?>"></input></td>
						</tr>
						<tr>
							<td>Precio Min.</td>
							<td><input type="text" id="gan_pre_min" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>Precio Objetivo</td>
							<td><input type="text" id="gan_pre_obj" class="form-control align-center" value="<?=$prec_act?>" onkeyup="buscar('dos','precio_obj')"></input></td>
						</tr>
						<tr>
							<td>Var. Precio</td>
							<td><input type="text" id="gan_var_pre" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>Valor De Venta</td>
							<td><input type="text" id="gan_val_vent" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
					</table>
					<table class="table table-bordered excel" id="resumen">
						<tr>
							<th>Resumen:</th>
						</tr>
						<tr>
							<td style="width: 50%">Ganancia Neta (S/.)</td>
							<td style="width: 30%"><input type="text" id="res_gan_neta" class="form-control align-center" readonly="readonly"></input></td>
							<td><input type="text" id="porc_gan_neta" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td >Costo Total (S/.)</td>
							<td><input type="text" id="res_cost_total" class="form-control align-center" readonly="readonly"></input></td>
							<td><input type="text" id="porc_cost_total" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>Variación Total (S/.)</td>
							<td><input type="text" id="res_var_total" class="form-control align-center" readonly="readonly"></input></td>
							<td><input type="text" id="por_var_total" class="form-control align-center" readonly="readonly"></input></td>
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
							<td><input type="text" id="c_comision_sab" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>Cuota BVL</td>
							<td><input type="text" id="c_cuota_bvl" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>F. Garantía</td>
							<td><input type="text" id="c_f_garantia" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>CAVALI</td>
							<td><input type="text" id="c_cavali" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>F. Liquidación</td>
							<td><input type="text" id="c_f_liquidacion" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<th>Compra Total</th>
							<th><input type="text" id="c_compra_total" class="form-control align-center" readonly="readonly"></input></th>
						</tr>
						<tr>
							<td>IGV</td>
							<td><input type="text" id="c_igv" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>Com SMV</td>
							<td><input type="text" id="c_compra_smv" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<th>Costo Compra</th>
							<th><input type="text" id="c_costo_compra" class="form-control align-center" readonly="readonly"></input></th>
						</tr>
						<tr>
							<td>Poliza Compra</td>
							<td><input type="text" id="c_poliza_compra" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<table class="table table-bordered excel">
						<tr>
							<th>Venta:</th>
						</tr>
						<tr>
							<td style="width: 50%">Comisión SAB</td>
							<td><input type="text" id="v_comision_sab" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>Cuota BVL</td>
							<td><input type="text" id="v_cuota_bvl" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>F. Garantía</td>
							<td><input type="text" id="v_f_garantia" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>CAVALI</td>
							<td><input type="text" id="v_cavali" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>F. Liquidación</td>
							<td><input type="text" id="v_f_liquidacion" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<th>Venta Total</th>
							<th><input type="text" id="v_com_total" class="form-control align-center" readonly="readonly"></input></th>
						</tr>
						<tr>
							<td>IGV</td>
							<td><input type="text" id="v_igv" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<td>Com SMV</td>
							<td><input type="text" id="v_com_smv" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
						<tr>
							<th>Costo Venta</th>
							<th><input type="text" id="v_costo_venta" class="form-control align-center" readonly="readonly"></input></th>
						</tr>
						<tr>
							<td>Poliza Venta</td>
							<td><input type="text" id="v_poliza_venta" class="form-control align-center" readonly="readonly"></input></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<p class="align-right">
			<button type="button" id="update_portafolio" class="btn btn-danger">Actualizar</button>		
			<button type="button" id="add_portafolio" class="btn btn-danger">Guardar</button>
			<button type="button" id="new_simulacion" class="btn btn-success">Nuevo</button>
			<button type="button" id="ver_portafolio" class="btn btn-warning">Portafolio</button>
		</p>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){

            var oper = '<?=$oper?>';
            if (oper == 'ver_simu') {
            	$("#cod_emp").attr('disabled','disabled');
				$("#cod_grupo").attr('disabled','disabled');
				$("#add_portafolio").hide();
				$("#update_portafolio").show();

				$("#monto_estimado").attr('disabled','disabled');
				$("#precio_unitario").attr('disabled','disabled');
				//$("#gan_rent_obj").attr('disabled','disabled');
				//$("#gan_pre_obj").attr('disabled','disabled');
            }else{
            	$("#new_simulacion").hide();
            	$("#update_portafolio").hide();
            }

			$("#cod_grupo").change(function(){

				$("#cod_emp").attr('disabled','disabled');
				$.ajax({
				    type:'GET',
				    url: '../Controller/SimuladorC.php?accion=empresaporgrupo',
				    data:{cod_grupo:$(this).val()},
				    success:function(data){

				        $("#cod_emp").html(data);
				        $("#cod_emp").removeAttr('disabled');
				    }
				});
			});

			buscar = function(tipo,tipo_two){

				var monto_estimado  = '';
				var precio_unitario = '';
				var gan_renta_obj   = 0;
				var gan_pre_obj     = 0;
				var oper            = '';

				if (tipo == 'uno') {

					$("#monto_estimado").attr('readonly','readonly');
					$("#precio_unitario").attr('readonly','readonly');
					monto_estimado  = $("#monto_estimado").val();
					precio_unitario = $("#precio_unitario").val();
					oper = '<?=$oper?>';
				}else if (tipo == 'dos') {

					monto_estimado  = $("#monto_estimado").val();
					precio_unitario = $("#precio_unitario").val();
				}

				//GANANCIA
				gan_renta_obj = $("#gan_rent_obj").val();
				gan_pre_obj   =  $("#gan_pre_obj").val();

				$("#buscar").attr('disabled','disabled');
				$("#add_portafolio").attr('disabled','disabled');

				$.ajax({
				    type:'GET',
				    url: '../Controller/SimuladorC.php?accion=datoscab',
				    data:{oper:oper,cod_emp:$("#cod_emp").val(),tipo:tipo,tipo_two:tipo_two,monto_estimado:monto_estimado,precio_unitario:precio_unitario,gan_renta_obj:gan_renta_obj,gan_pre_obj:gan_pre_obj},
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
						//GANANCIA
						//$("#gan_rent_obj").val(data.gan_rent_obj);
						$("#gan_pre_min").val(data.gan_pre_min);
						if (tipo_two != 'precio_obj') {
							$("#gan_pre_obj").val(data.gan_pre_obj);
						}
						$("#gan_var_pre").val(data.gan_var_pre);
						$("#gan_val_vent").val(data.gan_val_vent);
						//VENTA
						$("#v_comision_sab").val(data.v_comision_sab);
						$("#v_cuota_bvl").val(data.v_cuota_bvl);
						$("#v_f_garantia").val(data.v_f_garantia);
						$("#v_cavali").val(data.v_cavali);
						$("#v_f_liquidacion").val(data.v_f_liquidacion);
						$("#v_com_total").val(data.v_com_total);
						$("#v_igv").val(data.v_igv);
						$("#v_com_smv").val(data.v_com_smv);
						$("#v_costo_venta").val(data.v_costo_venta);
						$("#v_poliza_venta").val(data.v_poliza_venta);
						//RESUMEN
						$("#res_gan_neta").val(data.res_gan_neta);
						$("#res_cost_total").val(data.res_cost_total);
						$("#res_var_total").val(data.res_var_total);
						$("#porc_gan_neta").val(data.porc_gan_neta);
						$("#porc_cost_total").val(data.porc_cost_total);
						$("#por_var_total").val(data.por_var_total);

						$("#buscar").removeAttr('disabled');
						$("#add_portafolio").removeAttr('disabled');
				    }
				});
			}

			buscar('uno','');

			//$("#buscar").on("click",function(){
			//	buscar('uno','');
			//});

			$("#cod_emp").on('change',function(){
				buscar('uno','');
			});

			verPortafolio = function(){
				window.location.href = "./PortafolioC.php?accion=index";
			}

			$("#new_simulacion").on("click",function(){
				window.location.href = "./SimuladorC.php?accion=index";
			});

			$("#ver_portafolio").on("click",function(){
				verPortafolio();
			});

			$("#add_portafolio").on("click",function(){

				var cod_emp  = $("#cod_emp").val();
				var cod_grupo= $("#cod_grupo").val();
				var mont_est = $("#monto_estimado").val();
				var cantidad = $("#cantidad_acciones").val(); 
				var precio   = $("#precio_unitario").val();
				var rent_obj = $("#gan_rent_obj").val();
				var prec_act = $("#gan_pre_obj").val();
				var gan_neta = $("#res_gan_neta").val();
				var mont_neg = $("#monto_negociado").val();

				$("#buscar").attr('disabled','disabled');
				$("#add_portafolio").attr('disabled','disabled');

				$.ajax({
				    type:'POST',
				    url: '../Controller/PortafolioC.php?accion=add_portafolio',
				    data:{cod_emp:cod_emp,cantidad:cantidad,precio:precio,mont_est:mont_est,rent_obj:rent_obj,prec_act:prec_act,gan_neta:gan_neta,cod_grupo:cod_grupo,mont_neg:mont_neg},
				    success:function(data){

				    	$("#buscar").removeAttr('disabled');
						$("#add_portafolio").removeAttr('disabled');

						verPortafolio();
				    }
				});
			});

			$("#update_portafolio").on("click",function(){

				var por_cod  = $("#por_cod").val();
				var cod_emp  = $("#cod_emp").val();
				var cod_grupo= $("#cod_grupo").val();
				var mont_est = $("#monto_estimado").val();
				var cantidad = $("#cantidad_acciones").val(); 
				var precio   = $("#precio_unitario").val();
				var rent_obj = $("#gan_rent_obj").val();
				var prec_act = $("#gan_pre_obj").val();
				var gan_neta = $("#res_gan_neta").val();
				var mont_neg = $("#monto_negociado").val();

				$("#buscar").attr('disabled','disabled');
				$("#add_portafolio").attr('disabled','disabled');

				$.ajax({
				    type:'POST',
				    url: '../Controller/PortafolioC.php?accion=update_portafolio',
				    data:{por_cod:por_cod,cod_emp:cod_emp,cantidad:cantidad,precio:precio,mont_est:mont_est,rent_obj:rent_obj,prec_act:prec_act,gan_neta:gan_neta,cod_grupo:cod_grupo,mont_neg:mont_neg},
				    success:function(data){

				    	$("#buscar").removeAttr('disabled');
						$("#add_portafolio").removeAttr('disabled');

						verPortafolio();
				    }
				});
			});


        });
	</script>
</body>
</html>