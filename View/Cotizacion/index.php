
<!DOCTYPE>
    <html lang="en">
        <head>
        <?php include('../Include/Header.php'); ?>
        </head>
        
    <body>
    <?php include('../Include/Menu.php');?>
    <div class="container">
        <h3 class="title">IMPORTAR INFORMACION DESDE BOLSA DE VALORES DE LIMA</h3>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label>Sector:</label>
                    <?php   
                        $params = array(
                            'select' => array('id'=>'sector', 'name'=>'sector', 'class'=>'form-control'),
                            'sql'    => 'SELECT * FROM sector WHERE estado=1',
                            'attrib' => array('value'=>'cod_sector','desc'=>'nombre', 'concat'=>'','descextra'=>''),
                            'empty'  => 'Todos',
                            'defect' => '',
                            'edit'   => '',
                            'enable' => 'enable'
                        );

                        Combobox($link, $params);
                      ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>Moneda:</label>
                    <select id="moneda" name="moneda" class="form-control">
                        <option value="">Todos</option>
                        <option value="US$">US$</option>
                        <option value="S/">S/</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>Empresa:</label>
                    <?php   
                        $params = array(
                            'select' => array('id'=>'empresa', 'name'=>'empresa', 'class'=>'form-control'),
                            'sql'    => 'SELECT * FROM empresa WHERE estado=1',
                            'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre,moneda', 'concat'=>' - ','descextra'=>''),
                            'empty'  => false,
                            'defect' => 'RELAPAC1',
                            'edit'   => '',
                            'enable' => 'enable'
                        );

                        Combobox($link, $params);
                      ?>
                </div>

                <!--<label>Buscar Empresa(Busca del tercer caracter):</label>
                <div class="form-group">
                    <input type="hidden" id="empresa" name="empresa">
                    <input type="hidden" id="cod_empresa" name="cod_empresa">
                    <input type="text" id="bus_empresa" name="bus_empresa" class="form-control ui-autocomplete-input" placeholder="Buscar Empresa">
                </div>-->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php
                    $fecha_fin    = date('Y-m-d');
                    $fecha_final  = strtotime ( '-1 year' , strtotime ( $fecha_fin ) ) ;
                    $fecha_inicio = date ( 'Y-m-d' , $fecha_final );
                   
                ?>
                <label>Fecha Inicio (dd/mm/aaaa):</label>
                <div class="form-group">
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" placeholder="Fecha Inicio" value="<?=$fecha_inicio?>">
                </div>
            </div>
            <div class="col-lg-6">
                <label>Fecha Final (dd/mm/aaaa):</label>
                <div class="form-group">
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" placeholder="Fecha Fin" value="<?=$fecha_fin?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <input name="button" type="button" class="btn btn-success" id="button" value="Importar Desde BVL" onclick="getCotizacion()">
                <input name="button" type="button" class="btn btn-default" id="button" value="Buscar Importado" onclick="getHistorico()">
                <img src="../Assets/img/load.gif" id="loading" style="display: none">
            </div>
        </div>
    <br>
    <div id="divCotizaciones"></div>
    <div id="divHistorico"></div>
</div>
<script type="text/javascript">
//http://www.eyecon.ro/bootstrap-datepicker/
 $(document).ready(function(){

    $.ajaxPrefilter( function (options) {
      if (options.crossDomain && jQuery.support.cors) {
        var http = (window.location.protocol === 'http:' ? 'http:' : 'https:');
        options.url = http + '//cors-anywhere.herokuapp.com/' + options.url;
      }
    });

    getHistorico = function(){

        var p_Nemonico   = $("#empresa").val();
        var fecha_inicio = $("#fecha_inicio").val().split('-');
        var p_Ini        = fecha_inicio[0]+'-'+fecha_inicio[1]+'-'+fecha_inicio[2];
        var fecha_fin    = $("#fecha_fin").val().split('-');
        var P_Fin        = fecha_fin[0]+'-'+fecha_fin[1]+'-'+fecha_fin[2];

        $("#loading").show();

        $.ajax({
            type:'GET',
            url: '../Controller/CotizacionC.php?accion=listar',
            data:{empresa:p_Nemonico,fec_inicio:p_Ini,fec_fin:P_Fin, sector: $("#sector option:selected").text(),moneda: $("#moneda").val()},
            success:function(data){

                $("#divHistorico").html(data);
                $("#loading").hide();
            }
        });
    }

    getCotizacion = function(){

        var p_Nemonico    = $("#empresa").val();
        var fecha_inicio  = $("#fecha_inicio").val().split('-');
        var p_Ini         = fecha_inicio[0]+fecha_inicio[1]+fecha_inicio[2];
        var fecha_fin     = $("#fecha_fin").val().split('-');
        var P_Fin         = fecha_fin[0]+fecha_fin[1]+fecha_fin[2];

        var url = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio="+p_Ini+"&fec_fin="+P_Fin+"&nemonico="+p_Nemonico;

        $("#loading").show();

        $.ajax({
            type:'GET',
            url: url,
            success:function(data){

                $("#divCotizaciones").html(data).hide();
                var HBVL = prepararData(data);
                guardarData(HBVL,p_Nemonico);
                
            }
        });
    }

    guardarData = function(data,p_Nemonico){
        //console.log(data);
        $.ajax({
            type: 'POST',
            url: '../Controller/CotizacionC.php?accion=sav',
            data: {info:JSON.stringify(data),cz_codemp:p_Nemonico},
            success: function(data){
                $("#loading").hide();
                getHistorico();
            }
        })
        //console.log(data);
    }

    prepararData = function(data){
        
        var dataFila = [];

        $("#divCotizaciones table tbody tr").each(function (index){

            var dataCols = {};

            if (index > 1) {
                var valor_c = '';
                var apertura = '';
                $(this).children("td").each(function (index2){   
                    var campo = '';
                    var valor = '';
                    //console.log(index2);
                    switch (index2){

                        case 0: campo = 'f';valor=$(this).text();break;//fecha
                        case 1: campo = 'a';valor=$(this).text();apertura=$(this).text();break;//apertura
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


    getHistorico();

    /*$( "#bus_empresa" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
            type: 'GET',
            url: "../Controller/CotizacionC.php?accion=busemp",
            data: {term: request.term,sector: $("#sector").val(),moneda: $("#moneda").val()},
            dataType: "json",
            success: function( data ) {
                response(data);
            }
        });
      },
      minLength: 1,
      select: function(event, ui) {
          $("#empresa").val(ui.item.nemonico);
          $("#cod_empresa").val(ui.item.cod_emp);
      }
    });*/

    buscarEmpresa = function(){
        $("#empresa").attr('disabled','disabled');
        $.ajax({
            type: 'GET',
            url: "../Controller/CotizacionC.php?accion=busemp",
            data: {sector: $("#sector").val(),moneda: $("#moneda").val()},
            success: function( data ) {
                $("#empresa").html(data);
                $("#empresa").removeAttr('disabled');
            }
        });
    }

    $("#sector, #moneda").on("change", function(){
        buscarEmpresa();
    })

 });
 </script>
</body>
</html>