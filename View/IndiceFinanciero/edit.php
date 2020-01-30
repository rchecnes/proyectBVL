<!DOCTYPE html>
<html>
<head>
	<title>Ultimos Beneficios</title>
	<?php include('../Include/Header.php');
	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<br>
	<div class="container">
		<div class="panel panel-success">
			<div class="panel-heading"><?=$titulo?></div>
			<div class="panel-body">
				<form class="form-horizontal" method="POST" action="../Controller/IndiceFinancieroC.php?accion=<?=$accion?>">
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<label>Empresa:</label>
							<?php   
								$params = array(
									'select' => array('id'=>'inf_nemonico', 'name'=>'inf_nemonico', 'class'=>'form-control','required'=>'required'),
									'sql'    => "SELECT ne.nemonico,em.emp_nomb,ne.moneda FROM nemonico ne LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod) WHERE ne.estado='1' AND ne.imp_ind_fin!='' AND ne.cod_emp_bvl!=''",
									'attrib' => array('value'=>'nemonico','desc'=>'nemonico,emp_nomb,moneda', 'concat'=>' - ','descextra'=>''),
									'empty'  => false,
									'defect' => '',
									'edit'   => $inf_nemonico,
									'enable' => ($accion == 'update')?'disabled':'enable'
								);
								Combobox($link, $params);
								if($accion == 'update'){
									echo '<input type="hidden" id="inf_nemonico" name="inf_nemonico" value="'.$inf_nemonico.'">';
								}
							?>
							<input type="hidden" id="inf_detcod" name="inf_detcod" class="form-control" value="<?=$inf_detcod?>">
						</div>                        
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<label>Año:</label>
							<select id="inf_anio" name="inf_anio" class="form-control" <?=($accion == 'update')?'disabled':''?> required>
							<?php
								for($a=2010;$a<=2050;$a++){
									
									if($accion=='update'){
										$selected = ($a == $inf_anio)?"selected='selected'":"";
									}else{
										$selected_1 = ($a==date('Y'))?"selected='selected'":"";
									}
									echo '<option value="'.$a.'" '.$selected.'>'.$a.'</option>';
								}
							?>
							</select>
							<?php
							if($accion == 'update'){
								echo '<input type="hidden" id="inf_anio" name="inf_anio" value="'.$inf_anio.'">';
							}
							?>
						</div>						
					</div><br>
					<hr>
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
							<h4>Indice Financiero:</h4>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
							<h4>Valor Indice:</h4>
						</div>		
					</div>
					<?php
					$sqlif = "SELECT * FROM cab_indice_financiero WHERE inf_stat='10'";
					$resif = mysqli_query($link, $sqlif);
					
					$contador = 0;
					while($rowif = mysqli_fetch_array($resif)){

						$contador ++;

						$inf_valor = '';
						if($accion == 'update'){
							$inf_valor = $inf_array[$rowif['inf_codigo']]['inf_valor'];
						}
					?>
						<div class="row" style="padding-bottom: 5px;">
							<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
								<select id="inf_codigo_<?=$contador?>" name="inf_codigo_<?=$contador?>" class="form-control" required>
									<option value="<?=$rowif['inf_codigo']?>"><?=$rowif['inf_nombre']?></option>
								</select>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<input type="text" id="inf_valor_<?=$contador?>" name="inf_valor_<?=$contador?>" class="form-control" value="<?=$inf_valor?>" style="text-align:center"autocomplete="off">
							</div>
						</div>
					<?php						
					}
					?>
					<div class="row">
						<div class="col-lg-12">
							<button type="submit" class="btn btn-success">Guardar</button>
							<input type="hidden" value="<?=$contador?>" id="inf_contador" name="inf_contador">
							<a href="../Controller/IndiceFinancieroC.php?accion=index" class="btn btn-default" role="button">Cancelar</a>
						</div>
					</div>
				</form>
			</div>
		</div>
		
		
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		/*$(".datepicker").datepicker({
			autoclose:true,
			format:'yyyy-mm-dd'
		});*/
    });
	</script>
</body>
</html>