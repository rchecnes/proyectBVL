<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="expires" content="Sun, 19 Nov 1978 05:00:00 GMT">
<meta http-equiv="expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<title>Bolsa de Valores</title>
<title>Informacion de la BOLSA</title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link href="http://www.bvl.com.pe/css/estilos.css" rel="stylesheet" type="text/css">
<style>
    body{
        background: none !important;
    }
    #divCotizaciones{
        width: 90%;
        margin: 0 auto;
    }
</style>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td>Desde</td>
    <td><select name="mesIni" id="mesIni">
        <option value="01">Enero</option>
        <option value="02">Febrero</option>
        <option value="03" selected="">Marzo</option>
        <option value="04">Abril</option>
        <option value="05">Mayo</option>
        <option value="06">Junio</option>
        <option value="07">Julio</option>
        <option value="08">Agosto</option>
        <option value="09">Setiembre</option>
        <option value="10">Octubre</option>
        <option value="11">Noviembre</option>
        <option value="12">Diciembre</option>
      </select>
      <select name="anoIni" id="anoIni">
        <option value="2013">2013</option>
        <option value="2014">2014</option>
        <option value="2015">2015</option>
        <option value="2016">2016</option>
        <option value="2017" selected="">2017</option>
      </select></td>
    <td>Hasta</td>
    <td><select name="mesFin" id="mesFin">
        <option value="01">Enero</option>
        <option value="02">Febrero</option>
        <option value="03" selected="">Marzo</option>
        <option value="04">Abril</option>
        <option value="05">Mayo</option>
        <option value="06">Junio</option>
        <option value="07">Julio</option>
        <option value="08">Agosto</option>
        <option value="09">Setiembre</option>
        <option value="10">Octubre</option>
        <option value="11">Noviembre</option>
        <option value="12">Diciembre</option>
      </select>
      <select name="anoFin" id="anoFin">
        <option value="2013">2013</option>
        <option value="2014">2014</option>
        <option value="2015">2015</option>
        <option value="2016">2016</option>
        <option value="2017" selected="">2017</option>
        <option value="2017" selected="">2018</option>
      </select></td>
    <td><input name="button" type="button" class="boton" id="button" value="Buscar" onclick="getCotizacion('UNACEMC1',document.getElementById('anoIni').value+document.getElementById('mesIni').value+'01',document.getElementById('anoFin').value+document.getElementById('mesFin').value+'01')"></td>
  </tr>
</table>
<div id="divCotizaciones"></div>
<script type="text/javascript">
 $(document).ready(function(){
    $.ajaxPrefilter( function (options) {
      if (options.crossDomain && jQuery.support.cors) {
        var http = (window.location.protocol === 'http:' ? 'http:' : 'https:');
        options.url = http + '//cors-anywhere.herokuapp.com/' + options.url;
      }
    });

    getCotizacion = function(p_Nemonico,p_Ini,P_Fin){        
        var url = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio="+p_Ini+"&fec_fin="+P_Fin+"&nemonico="+p_Nemonico;

        $.ajax({
            type:'GET',
            url: url,
            success:function(data){
                $("#divCotizaciones").html(data);
                //$("#divCotizaciones").hide();
                /*var new_data = data.split("\n").join("");
                new_data=new_data.replace(/^\s+/g,'');
                new_data=new_data.replace(/\s+$/g,'');
                new_data=new_data.replace(/<[^>]*>?/g, '***');
                
                

                new_data = new_data.split('***').join("|");
                new_data = new_data.split('|');*/

                prepararData(data);

            }
        });
    }

    prepararData = function(data){
        
        var dataFila = new Array();

        $("#divCotizaciones table tbody tr").each(function (index){

            var dataCols = new Array();

            if (index > 1) {
                $(this).children("td").each(function (index2){   
                    var campo = '';
                    var valor = '';

                    switch (index2){

                        case 0: campo = 'fecha';valor=$(this).text();break;
                        case 1: campo = 'apertura';valor=$(this).text();break;
                        case 2: campo = 'cierre';valor=$(this).text();break;
                        case 3: campo = 'maxima';valor=$(this).text();break;
                        case 4: campo = 'minima';valor=$(this).text();break;
                        case 5: campo = 'promedio';valor=$(this).text();break;
                        case 6: campo = 'cant_negociada';valor=$(this).text();break;
                        case 7: campo = 'mont_negociado';valor=$(this).text();break;
                        case 8: campo = 'fecha_anterior';valor=$(this).text();break;
                        case 9: campo = 'cierre_anterior';valor=$(this).text();break;
                    }
                    
                    dataCols[campo]=valor;
                });

                //if (dataCols.length > 0) {
                    dataFila.push(dataCols);
                //}
                

            }
            
        });

        console.log(dataFila);
    }

 });
 </script>
</body>
</html>