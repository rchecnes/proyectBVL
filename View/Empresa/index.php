<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php include('../Include/Header.php');	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	
	<div class="container">
		<h3 class="title">LISTA DE EMPRESAS</h3>
		<p class="align-right">
			<a href="../Controller/EmpresaC.php?accion=new" class="btn btn-default" role="button">Nueva Empresa</a>
		</p>
		<div id="empresas_import"></div>
		<table class="table table-bordered">
		    <tr>
				<th>Codigo</th>
		        <th>Nombre</th>		        
		        <th>Sector</th>
				<th>Cod. Bolsa (Imp. Ind. Fin.)</th>
				<th>Cod. (Imp. Ind. Fin.)</th>
				<th>Cod. RPJ (Imp. Est.)</th>
		        <th>Estado</th>
		        <th>Acciones</th>
		    </tr>
		    <?php
			while ($em = mysqli_fetch_array($empresas)) {
		    ?>
		    <tr>
				<td><?=$em['emp_cod']?></td>
		        <td><?=$em['emp_nomb']?></td>		        
		        <td><?=$em['nom_sector']?></td>
				<td><?=$em['emp_cod_bvl']?></td>
				<td><?=$em['emp_imp_inf']?></td>
				<td><?=$em['emp_cod_rpj']?></td>
		        <td><?=($em['emp_stdo']=='1')?'Habilitado':'Deshabilitado'?></td>
		        <td width="50" align="center">
		        	<a href="../Controller/EmpresaC.php?accion=edit&emp_cod=<?=$em['emp_cod']?>" class="" role="button"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a>&nbsp;
		        	<a href="../Controller/EmpresaC.php?accion=delete&emp_cod=<?=$em['emp_cod']?>" class="color-red" role="button"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a>
		        </td>
		    </tr>
		    <?php
		    }
		    ?>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
            

            $.ajaxPrefilter( function (options) {
		      if (options.crossDomain && jQuery.support.cors) {
		        var http = (window.location.protocol === 'http:' ? 'http:' : 'https:');
		        options.url = http + '//cors-anywhere.herokuapp.com/' + options.url;
		      }
		    });

		    inportarEmpresa = function(tipo){

		        //var url = "http://www.bvl.com.pe/includes/cotizaciones_todas.dat";

		        $("#loading").show();

		        $.ajax({
		            type:'GET',
		            url: '../Controller/EmpresaC.php?accion=importarmanual&tipo='+tipo,
					dataType: 'json',
		            success:function(data){

						$("#loading").hide();

						window.location.assign("../Controller/EmpresaC.php?accion=index");	
		            }
		        });
		    }

			/*prepararData = function(data){
			        
		        var dataFila = [];

		        $("#empresas_import table tbody tr").each(function (index){

		            var dataCols = {};

		            if (index > 1) {
		                $(this).children("td").each(function (index2){   
		                    var campo = '';
		                    var valor = '';

		                    switch (index2){

		                        case 1: campo = 'emp';valor=$(this).text();break;//fecha
		                        case 2: campo = 'nem';valor=$(this).text();break;//apertura
		                        case 3: campo = 'sec';valor=$(this).text();break;//cierre
		                        case 4: campo = 'seg';valor=$(this).text();break;//maxima
		                        case 5: campo = 'mon';valor=$(this).text();break;//minima
		                       
		                    }

		                    if (campo !='') {
		                        dataCols[campo]=valor;
		                    }
		                    
		                });

		                dataFila.push(dataCols);
		                
		            }
		            
		        });

		        return dataFila;
		    }*/
         });
	</script>
</body>
</html>