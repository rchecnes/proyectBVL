<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php
	//session_start();
	//http://glyphicons.bootstrapcheatsheets.com/
	include('../Include/Header.php');
	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<br>
	
	<div class="container">
		<h3>MIS FAVORITOS</h3>
		<br>
		<div style="border-bottom: 1px solid #aba8a8;">
			<form method="POST" action="../Controller/GrupoC.php?accion=create" id="form_grupo">
				<div class="row">
					<div class="col-lg-10 col-md-10 col-sm-9 col-xs-8">
						<div class="form-group">
							<label for="nombre_grupo" class="control-label">Nombre Del Grupo:</label>
							<input type="text" class="form-control" id="nom_grupo" name="nom_grupo"></input>
							<input type="hidden" class="form-control" id="cod_user" name="cod_user" value="<?=$cod_user?>"></input>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
						<p class="align-right">
							<label>&nbsp;</label><br>
							<button type="submit" class="btn btn-danger">Crear Grupo</button>
						</p>
					</div>
				</div>
			</form>
			<form method="POST" action="../Controller/FavoritoC.php?accion=create" id="form_favorito">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
						<div class="form-group">
						    <label for="inputPassword" class="control-label">Empresa:</label>
						    <?php
						    	$params = array(
				                    'select' => array('id'=>'cod_emp', 'name'=>'cod_emp', 'class'=>'form-control'),
				                    'sql'    => "SELECT * FROM empresa WHERE estado=1",
				                    'attrib' => array('value'=>'cod_emp','desc'=>'nemonico,nombre', 'concat'=>' - ','descextra'=>''),
				                    'empty'  => 'Todos',
				                    'defect' => '',
				                    'edit'   => '',
				                    'enable' => 'enable'
				                );
						      	Combobox($link, $params);
						     ?>
					  	</div>
					</div>
					<div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
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

					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
						<p class="align-right">
							<label>&nbsp;</label><br>
							<button type="submit" class="btn btn-success">Agregar Favorito</button>
						</p>
						
					</div>
				</div>
				
			</form>
		</div>
		<br>
		<div class="">
		<?php

		$sqlg  = "SELECT * FROM user_grupo WHERE est_grupo=1 AND cod_user='$cod_user' ORDER BY nom_grupo ASC";
		$respg = mysqli_query($link, $sqlg);

		while ($rg = mysqli_fetch_array($respg)):
		?>
			<table class="table table-bordered">
				<tr>
			        <th colspan="7" class="table-header">
			        		
			        			
			        	<div class="row">
			        		<div class="col-lg-10 col-md-10 col-sm-9 col-xs-8">
			        			<span id="labelgrupo_<?=$rg['cod_grupo']?>"><?=$rg['nom_grupo']?></span>
					        	<a id="showhide_<?=$rg['cod_grupo']?>" title="Editar" style=";cursor:pointer; " onclick="havilitarEdicion(<?=$rg['cod_grupo']?>)">
					              <i class="fa fa-pencil fa-2x color-blue" aria-hidden="true"></i> 
					            </a>

			        			<input type="hidden" id="grupo_<?=$rg['cod_grupo']?>" value="<?=$rg['cod_grupo']?>" class="form-control"></input>
			        			<input type="text" id="gruponame_<?=$rg['cod_grupo']?>" value="<?=$rg['nom_grupo']?>" style="display: none;" class="form-control"></input>
			        		</div>
			        		<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 align-right">
			        			<img src="../Assets/img/load.gif" id="loading_<?=$rg['cod_grupo']?>" style="display: none;">
			        			<a  id="savgrupo_<?=$rg['cod_grupo']?>" title="Guardar" style="display: none;cursor:pointer; " onclick="savEditGrupo(<?=$rg['cod_grupo']?>)">
					              <i class="fa fa-floppy-o fa-2x color-blue" aria-hidden="true"></i> 
					            </a>
					            <a  id="cancelsav_<?=$rg['cod_grupo']?>" title="Cancelar" style="display: none;cursor:pointer; " onclick="cancelSav(<?=$rg['cod_grupo']?>)">
					              <i class="fa fa-times fa-2x color-blue" aria-hidden="true"></i> 
					            </a>

					            <a href="../Controller/GrupoC.php?accion=delete&cod_grupo=<?=$rg['cod_grupo']?>&cod_user=<?=$rg['cod_user']?>" title="Eliminar Grupo">
					              <i class="fa fa-trash-o fa-2x color-red" aria-hidden="true"></i> 
					            </a>
			        		</div>
			        	</div>	        	
			        </th>
			    </tr>
			    <tr>
			        <th class="td-header">Nemónico</th>
			        <th class="td-header">Nombre</th>
			        <th class="td-header">Sector</th>
			        <th align="center" class="td-header">Moneda</th>
			        <th colspan="2" class="td-header" style="text-align:center">Ult. Cotización</th>
			        <th align="center" class="td-header">Acciones</th>
			    </tr>
			    <?php

			    /*$sql = "SELECT e.cod_emp,e.nombre AS nom_empresa, s.nombre AS nom_sector,e.nemonico,e.moneda,ef.cod_user,
						(SELECT DATE_FORMAT(cz_fecha,'%d/%m/%Y') FROM cotizacion c WHERE c.cz_codemp=e.nemonico ORDER BY c.cz_cod DESC LIMIT 1) AS fe_ult_cotiza,
						(SELECT cz_cierre FROM cotizacion c WHERE c.cz_codemp=e.nemonico ORDER BY c.cz_cod DESC LIMIT 1) AS cz_ult_cierre
						FROM empresa_favorito ef
						INNER JOIN empresa e ON (ef.cod_emp=e.cod_emp)
						INNER JOIN sector s ON(e.cod_sector=s.cod_sector)
						WHERE ef.cod_user='$cod_user' AND ef.cod_grupo='".$rg['cod_grupo']."'";*/
				$sql = "SELECT e.cod_emp,e.nombre AS nom_empresa, s.nombre AS nom_sector,e.nemonico,e.moneda,ef.cod_user, ef.cod_grupo,
						DATE_FORMAT(e.cz_fe_fin,'%d/%m/%Y') AS fe_ult_cotiza,
						e.cz_ci_fin AS cz_ult_cierre
						FROM empresa_favorito ef
						INNER JOIN empresa e ON (ef.cod_emp=e.cod_emp)
						INNER JOIN sector s ON(e.cod_sector=s.cod_sector)
						WHERE ef.cod_user='$cod_user' AND ef.cod_grupo='".$rg['cod_grupo']."'
						ORDER BY e.nemonico ASC";
				$favoritos= mysqli_query($link, $sql);

			    while ($em = mysqli_fetch_array($favoritos)):
			    ?>
			    <tr>
			        <td><?=utf8_encode($em['nemonico'])?></td>
			        <td><?=$em['nom_empresa']?></td>
			        <td><?=utf8_encode($em['nom_sector'])?></td>
			        <td><?=$em['moneda']?></td>
			        <td><?=($em['fe_ult_cotiza']!='' && $em['fe_ult_cotiza']!='00/00/0000')?$em['fe_ult_cotiza']:""?></td>
			        <td align="right"><?=($em['cz_ult_cierre']>0)?number_format($em['cz_ult_cierre'],2,'.',','):""?></td>
			        			        
			        <td width="200" align="center">
			        	<a href="../Controller/FavoritoC.php?accion=delete&cod_emp=<?=$em['cod_emp']?>&cod_user=<?=$em['cod_user']?>&cod_grupo=<?=$em['cod_grupo']?>" title="Eliminar">
			              <i class="fa fa-trash-o fa-2x color-red" aria-hidden="true"></i> 
			            </a>
			        </td>
			    </tr>
			    <?php endwhile ?>
			</table>
		<?php endwhile ?>
		</div>
		
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
            
		    havilitarEdicion = function(grupo_id){
		    	$("#labelgrupo_"+grupo_id).hide();
		    	$("#gruponame_"+grupo_id).show().select();
		    	$("#showhide_"+grupo_id).hide();
		    	$("#savgrupo_"+grupo_id).show();
		    	$("#cancelsav_"+grupo_id).show();
		    }

		    cancelSav = function(grupo_id){
		    	$("#labelgrupo_"+grupo_id).show();
		    	$("#gruponame_"+grupo_id).hide();
		    	$("#showhide_"+grupo_id).show();
		    	$("#savgrupo_"+grupo_id).hide();
		    	$("#cancelsav_"+grupo_id).hide();
		    }

		    getGrupoUsuario = function(){

		    	$("#cod_grupo").attr('disabled','disabled');
	    		$.ajax({
		    		type:'GET',
		    		url:'../Controller/GrupoC.php?accion=listar',
		    		data:{},
		    		success: function(data){
		    			$("#cod_grupo").html(data);
		    			$("#cod_grupo").removeAttr('disabled');

		    		}
		    	});

		    }

		    savEditGrupo = function(grupo_id){
		    	
		    	if ($("#gruponame_"+grupo_id).val()!='') {
		    		$("#loading_"+grupo_id).show();
		    		$.ajax({
			    		type:'POST',
			    		url:'../Controller/GrupoC.php?accion=update',
			    		data:{cod_grupo:$("#grupo_"+grupo_id).val(),nom_grupo:$("#gruponame_"+grupo_id).val()},
			    		success: function(data){
			    			$("#labelgrupo_"+grupo_id).html(data);
			    			cancelSav(grupo_id);
			    			$("#loading_"+grupo_id).hide();
			    			getGrupoUsuario();
			    		}
			    	});
		    	}else{
		    		$("#gruponame_"+grupo_id).select();
		    	}
		    	
		    }


			$('#form_grupo').bootstrapValidator({
		        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
		        feedbackIcons: {
		            valid: 'glyphicon glyphicon-ok',
		            invalid: 'glyphicon glyphicon-remove',
		            validating: 'glyphicon glyphicon-refresh'
		        },
		        fields: {
		            nom_grupo: {
		                validators: {
		                        notEmpty: {
		                        message: 'Ingrese nombre al grupo'
		                    }
		                }
		            }		            
		        }
	        });

	        $('#form_favorito').bootstrapValidator({
		        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
		        feedbackIcons: {
		            valid: 'glyphicon glyphicon-ok',
		            invalid: 'glyphicon glyphicon-remove',
		            validating: 'glyphicon glyphicon-refresh'
		        },
		        fields: {
		            cod_emp: {
		                validators: {
		                        notEmpty: {
		                        message: 'Seleccione Empresa'
		                    }
		                }
		            },
		            cod_grupo: {
		                validators: {
		                    notEmpty: {
		                        message: 'Seleccione Grupo'
		                    }
		                }
		            }		            
		        }
	        });
        });
	</script>
</body>
</html>