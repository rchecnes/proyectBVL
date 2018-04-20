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

			 getHistorico = function(){

		        var fecha   = $("#fecha").val();
		        var acciones_hoy = 0;

		        if ($("#acciones_hoy").is(":checked")==true) {
		        	acciones_hoy = 1;
		        }
		        
		        $("#loading").show();

		        $.ajax({
		            type:'GET',
		            url: '../Controller/CierreDelDiaC.php?accion=listar',
		            data:{fecha:fecha,acciones_hoy:acciones_hoy},
		            success:function(data){

		                $("#divHistorico").html(data);
		                $("#loading").hide();
		            }
		        });
		    }

		    $("#acciones_hoy").on("click", function(){
		    	getHistorico();
		    });
		    
		    getHistorico();

		});

	</script>
	<div class="container">
		<div class="tabbable">
	        <ul class="nav nav-tabs" id="tabs">
	          <li class="active"><a data-toggle="tab" href="#cierre_del_dia"><h4>Cierre Del Día</h4></a></li>
	        </ul>
	        <div class="tab-content">
	            <div id="cierre_del_dia" class="tab-pane fade in active">
	            	<div class="row">
	            		<div class="col-lg-3">
	                        <label>Fecha:</label>
	                        <div class="form-group">
	                        	<?php
	                        	$fecha      = date('Y-m-d');
                            	$fecha_new  = strtotime ( '-0 day' , strtotime ( $fecha ) ) ;
                            	$fecha_new  = date ( 'Y-m-d' , $fecha_new );
                            	?>
	                            <input type="date" id="fecha" name="fecha" class="form-control" placeholder="Fecha Fin" value="<?=$fecha_new?>">
	                        </div>
	                    </div>
	                    <div class="col-lg-3">
	                    	<label>&nbsp;</label>
	                    	<div class="form-group">
	                    		<label><input type="checkbox" checked="checked" name="acciones_hoy" id="acciones_hoy">Solo acciones cotizadas hoy</label>
	                    	</div>
	                    </div>
	                    <div class="col-lg-3">
	                    	<label>&nbsp;</label>
	                    	<div class="form-group">
		                        <input name="button" type="button" class="btn btn-default" id="button" value="Buscar Importado" onclick="getHistorico()">
		                        <img src="../Assets/img/load.gif" id="loading" style="display: none">
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