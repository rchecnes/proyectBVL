
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
	<div id="divCotizaciones"></div>
</div>
<?php
$p_Ini = date('Ymd');
$P_Fin = date('Ymd');

include('../../Config/Conexion.php');
$link      = getConexion();
$sqlemp    = "SELECT em.nemonico FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) WHERE se.estado='1' AND em.estado='1' LIMIT 10";
$respemp   = mysqli_query($link, $sqlemp);
$emp_array = array();

while ($e = mysqli_fetch_array($respemp)) {
	$emp_array[] = array('nemonico'=>$e['nemonico']);
}

?>
<script type="text/javascript">

 $(document).ready(function(){

 	

    /*$.ajaxPrefilter( function (options) {
      if (options.crossDomain && jQuery.support.cors) {
        var http = (window.location.protocol === 'http:' ? 'http:' : 'https:');
        options.url = http + '//cors-anywhere.herokuapp.com/' + options.url;
      }
    });*/
    (function() {
	    var cors_api_host = 'cors-anywhere.herokuapp.com';
	    var cors_api_url = 'https://' + cors_api_host + '/';
	    var slice = [].slice;
	    var origin = window.location.protocol + '//' + window.location.host;
	    var open = XMLHttpRequest.prototype.open;
	    XMLHttpRequest.prototype.open = function() {
	        var args = slice.call(arguments);
	        var targetOrigin = /^https?:\/\/([^\/]+)/i.exec(args[1]);
	        if (targetOrigin && targetOrigin[0].toLowerCase() !== origin &&
	            targetOrigin[1] !== cors_api_host) {
	            args[1] = cors_api_url + args[1];
	        }
	        return open.apply(this, args);
	    };
	})();

	empresas = <?=json_encode($emp_array)?>;

    getCotizacion = function(){

        var p_Ini         = '20170522';//'<?=$p_Ini?>';
        var P_Fin         = '20170522';//'<?=$P_Fin?>';

        for (var i = 0; i < empresas.length; i++) {

        	var p_Nemonico = empresas[i].nemonico;
        	var url = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio="+p_Ini+"&fec_fin="+P_Fin+"&nemonico="+p_Nemonico;
        	
        	$.ajax({
	            type:'GET',
	            url: url,
	            success:function(data){
	            	$("#divCotizaciones").append('<div id="cotizacion_'+p_Nemonico+'"></div>');
	                $("#cotizacion_"+p_Nemonico).html(data);
	                var HBVL = prepararData(p_Nemonico);
	                guardarData(HBVL,p_Nemonico);
	            }
	        }); 
        }
    }

    getCotizacion();

    guardarData = function(data,p_Nemonico){

        $.ajax({
            type: 'POST',
            async: false,
            url: '../../Controller/CotizacionC.php?accion=sav',
            data: {info:JSON.stringify(data),cz_codemp:p_Nemonico},
            success: function(data){
                
            }
        });
    }

    prepararData = function(p_Nemonico){
        
        var dataFila = [];

        $("#cotizacion_"+p_Nemonico+" table tbody tr").each(function (index){

            var dataCols = {};

            if (index > 1) {
                var valor_c = '';
                $(this).children("td").each(function (index2){

                    var campo = '';
                    var valor = '';

                    switch (index2){

                        case 0: campo = 'f';valor=$(this).text();break;//fecha
                        case 1: campo = 'a';valor=$(this).text();break;//apertura
                        case 2: campo = 'c';valor=$(this).text();valor_c=$(this).text();break;//cierre
                        case 3: campo = 'max';valor=$(this).text();break;//maxima
                        case 4: campo = 'min';valor=$(this).text();break;//minima
                        case 5: campo = 'prd';valor=$(this).text();break;//promedio
                        case 6: campo = 'cn';valor=$(this).text();break;
                        case 7: campo = 'mn';valor=$(this).text();break;
                        case 8: campo = 'fa';valor=$(this).text();break;
                        case 9: campo = 'ca';valor=$(this).text();break;
                    }

                    if (campo !='') {
                        dataCols[campo]=valor;
                    }
                    
                });

                if (valor_c != '') {
                    dataFila.push(dataCols);
                }
            }
            
        });

        return dataFila;
    }
 });

 </script>
</body>
</html>