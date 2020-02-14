<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<?php include('../Include/Header.php');	?>
</head>
<body>
	<?php include('../Include/Menu.php');?>
	
	<div class="container">
		<h3 class="title">LISTA DE NEMONICOS</h3>
		<p class="align-right">
			<a href="../Controller/NemonicoC.php?accion=new" class="btn btn-default" role="button">Nuevo Nemonico</a>
			<button type="button" class="btn btn-success" onclick="inportarNemonico('new')">Importar Nuevos Nemonicos</button>
			<button type="button" class="btn btn-success" onclick="inportarNemonico('update')">Actualizar Información</button>
			<img src="../Assets/img/load.gif" id="loading" style="display: none">
		</p>
		<div id="empresas_import"></div>
		<table class="table table-bordered">
			<?php
			$date_ha = date("Y-m-d",strtotime(date('Y-m-d')."- 1 days"));
			$date_de = date("Y-m-d",strtotime($date_ha."- 1 year"));
			$date_de = date("Y-m-d",strtotime($date_de."- 1 days"));
			?>
			<tr>
				<th colspan="4">&nbsp;</th>
				<th colspan="2" width="140">Cotización</th>
				<th>&nbsp;</th>
				<th colspan="3"><?=$date_de.' a '.$date_ha?></th>
				<th colspan="2">&nbsp;</th>
			</tr>
		    <tr>
				<th>Empresa</th>
		        <th>Nemónico</th>
				<th>Codigo BVL</th>	        
		        <th>Sector</th>
		        <th>Mon.</th>
		        <th width="90">Fecha</th>
		        <th width="50">Cierre</th>
				<th width="50" align="center">Frec. Nego.</th>
				<th width="50" align="center">Prom. Nego.</th>
				<th width="50" align="center">Prom. Opr.</th>
		        <th>Estado</th>
		        <th>Acciones</th>
		    </tr>
		    <?php
			while ($em = mysqli_fetch_array($empresas)) {

				$nemonico = $em['nemonico'];
				//Frecuencia
				$sql_fre = "SELECT COUNT(cz.cz_cod)AS cantidad, SUM(cz_monto_neg_ori)AS suma, SUM(cd_ng_nop)AS suma_2 FROM cotizacion cz
				LEFT JOIN cotizacion_del_dia cd ON (cz.cz_cod=cd.cd_cod AND cz.cz_nemo=cd.cd_nemo)
				WHERE cz_nemo='$nemonico' AND cz.cz_fecha BETWEEN '$date_de' AND '$date_ha'";
				$res_fre = mysqli_query($link, $sql_fre);
				$row_fre = mysqli_fetch_array($res_fre);
				$frec_nego = ($row_fre['cantidad']/252)*100;
				$prom_nego = ($row_fre['cantidad']>0)?$row_fre['suma']/$row_fre['cantidad']:0;
				$prom_opr = ($row_fre['cantidad']>0)?$row_fre['suma_2']/$row_fre['cantidad']:0;
		    ?>
		    <tr>
				<td><?=$em['nom_empresa']?></td>
		        <td title="<?='Empresa:'.$em['nombre']?>"><?=$em['nemonico']?></td>
				<td><?=$em['cod_emp_bvl']?></td>		        
		        <td><?=$em['nom_sector']?></td>
		        <td><?=$em['moneda']?></td>
		        <td align="center"><?=$em['cz_fe_fin']?></td>
		        <td align="right"><?=$em['cz_ci_fin']?></td>
				<td align="right"><?=number_format($frec_nego,0).'%'?></td>
				<td align="right"><?=number_format($prom_nego,0)?></td>
				<td align="right"><?=number_format($prom_opr,2)?></td>
		        <td><?=($em['estado']=='1')?'Habilitado':'Deshabilitado'?></td>
		        <td width="50" align="center">
		        	<a href="../Controller/NemonicoC.php?accion=edit&ne_cod=<?=$em['ne_cod']?>" class="" role="button"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a>&nbsp;
		        	<a href="../Controller/NemonicoC.php?accion=delete&ne_cod=<?=$em['ne_cod']?>" class="color-red" role="button"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a>
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

		    inportarNemonico = function(tipo){

		        //var url = "http://www.bvl.com.pe/includes/cotizaciones_todas.dat";

		        $("#loading").show();

		        $.ajax({
		            type:'GET',
		            url: '../Controller/NemonicoC.php?accion=importarmanual&tipo='+tipo,
					dataType: 'json',
		            success:function(data){

						$("#loading").hide();

						window.location.assign("../Controller/NemonicoC.php?accion=index");	
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