<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php include('../Include/Header.php');	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	<br>
	
	<div class="container">
		<h3>LISTA DE EMPRESAS</h3>
		<br>
		<p class="align-right">
			<a href="../Controller/EmpresaC.php?accion=new" class="btn btn-default" role="button">Nueva Empresa</a>
			<button type="button" class="btn btn-success" onclick="inportarEmpresa()">Importar Empresa</button>
			<img src="../Assets/img/load.gif" id="loading" style="display: none">
		</p>
		<div id="empresas_import"></div>
		<table class="table table-bordered">
		    <tr>
		        <th>Código</th>
		        <th>Nombre</th>
		        <th>Nemónico</th>
		        <th>Sector</th>
		        <th>Segmento</th>
		        <th>Moneda</th>
		        <th>Estado</th>
		        <th>Acciones</th>
		    </tr>
		    <?php
		    while ($em = mysqli_fetch_array($empresas)) {
		    ?>
		    <tr>
		        <td><?=$em['cod_emp']?></td>
		        <td><?=$em['nom_empresa']?></td>
		        <td><?=$em['nemonico']?></td>
		        <td><?=$em['nom_sector']?></td>
		        <td><?=$em['segmento']?></td>
		        <td><?=$em['moneda']?></td>
		        <td><?=($em['estado']=='1')?'Habilitado':'Deshabilitado'?></td>
		        <td width="200">
		        	<a href="../Controller/EmpresaC.php?accion=edit&codigo=<?=$em['cod_emp']?>" class="btn btn-default" role="button">Editar</a>
		        	<a href="../Controller/EmpresaC.php?accion=delete&codigo=<?=$em['cod_emp']?>" class="btn btn-danger" role="button">Eliminar</a>
		        </td>
		    </tr>
		    <?php
		    }
		    ?>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
            var url = '<?php echo $url; ?>';
            $(".navbar-nav li a").each(function(){
                if ($(this).attr('href').indexOf(url)!=-1) {
                    $(this).parent().addClass('active');
                }else{
                    $(this).parent().removeClass('active');
                }
            });

            $.ajaxPrefilter( function (options) {
		      if (options.crossDomain && jQuery.support.cors) {
		        var http = (window.location.protocol === 'http:' ? 'http:' : 'https:');
		        options.url = http + '//cors-anywhere.herokuapp.com/' + options.url;
		      }
		    });

		    guardarData = function(data){
		        $.ajax({
		            type: 'POST',
		            url: '../Controller/EmpresaC.php?accion=savimported',
		            data: {info:JSON.stringify(data)},
		            success: function(data){
		                $("#loading").hide();
		                window.location.assign("../Controller/EmpresaC.php?accion=index");
		            }
		        });
		    }

		    inportarEmpresa = function(){

		        var url = "http://www.bvl.com.pe/includes/cotizaciones_todas.dat";

		        $("#loading").show();

		        $.ajax({
		            type:'GET',
		            url: url,
		            success:function(data){

		                $("#empresas_import").html(data).hide();
		                var empresas = prepararData(data);	                
		                guardarData(empresas);
		                //getHistorico();
		            }
		        });
		    }

			prepararData = function(data){
			        
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
		    }
         });
	</script>
</body>
</html>