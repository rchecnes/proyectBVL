<!DOCTYPE>
<html>
<head>
<?php include('../Include/Header.php'); ?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
</head>
<body>
<?php include('../Include/Menu.php');?>
<div class="container">
    <h3 class="title">GRÁFICOS</h3>
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-112">
            <div class="form-group">
                <?php
                $fecha_fin    = date('Y-m-d');
                $fecha_final    = strtotime ( '-1 year' , strtotime ( $fecha_fin ) ) ;
                $fecha_inicio = date ( 'Y-m-d' , $fecha_final );
                ?>
                <label>Fecha Ini.(dd/mm/aaaa)</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?=$fecha_inicio?>" class="form-control" placeholder="<?=$fecha_inicio?>">
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Fecha Fin.(dd/mm/aaaa)</label>
                <input type="date" id="fecha_final" name="fecha_final" value="<?=$fecha_fin?>" class="form-control" placeholder="<?=$fecha_fin?>">
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="inputPassword" class="control-label">Grupo:</label>
                <?php
                    $params = array(
                        'select' => array('id'=>'cod_grupo', 'name'=>'cod_grupo', 'class'=>'form-control'),
                        'sql'    => "SELECT * FROM user_grupo WHERE est_grupo=1 AND cod_user='$cod_user'",
                        'attrib' => array('value'=>'cod_grupo','desc'=>'nom_grupo', 'concat'=>' - ','descextra'=>''),
                        'empty'  => 'Todos',
                        'defect' => ($cod_grupo!='')?$cod_grupo:'',
                        'edit'   => '',
                        'enable' => 'enable'
                    );
                    Combobox($link, $params);
                 ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Nemonico:</label>
                <?php
                  $andwhere = ($cod_grupo !='')?" AND ug.cod_grupo='$cod_grupo'":"";
                  $params = array(
                        'select' => array('id'=>'nemonico', 'name'=>'nemonico', 'class'=>'form-control'),
                        'sql'    => "SELECT DISTINCT(ne.ne_cod), ne.nemonico,em.emp_nomb FROM empresa_favorito nf 
                                    INNER JOIN nemonico ne ON(nf.ne_cod=ne.ne_cod)
                                    LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod)
                                    INNER JOIN user_grupo ug ON(nf.cod_grupo=ug.cod_grupo)
                                    WHERE ne.estado=1 AND nf.est_fab=1
                                    AND nf.cod_user='$cod_user' $andwhere",
                        'attrib' => array('value'=>'nemonico','desc'=>'nemonico,emp_nomb', 'concat'=>' - ','descextra'=>''),
                        'empty'  => false,
                        'defect' => ($simu_cod_emp!='')?$simu_cod_emp:'ENGEPEC1',
                        'edit'   => '',
                        'enable' => 'enable'
                    );
                    Combobox($link, $params);
                 ?>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Rango:</label><br>
                <div class="btn-group" role="group" aria-label="Basic example">
                  <button type="button" class="btn btn-secondary mostrar_graf" value="3">3M</button>
                  <button type="button" class="btn btn-secondary mostrar_graf" value="6">6M</button>
                  <button type="button" class="btn btn-secondary mostrar_graf active" value="12">12M</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button type="button" name="buscar" id="buscar" class="btn btn-success" onclick="buscar()">Buscar</button>
            <img src="../Assets/img/load.gif" id="loading" style="display: none">
        </div>
    </div><br>

    

    <div class="tabbable">
        <ul class="nav nav-tabs" id="tabs">
          <li class="active"><a data-toggle="tab" href="#monto_por_precio">1. Montos Por Precio</a></li>
          <li><a data-toggle="tab" href="#analisis_de_precio">2. Análisis De Precio</a></li>
          <li><a data-toggle="tab" href="#cotizacion">3. Cotizaciones</a></li>
        </ul>
        <div class="tab-content">
            <div id="monto_por_precio" class="tab-pane fade in active">
                
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <table class="table table-bordered grafico">
                            <tr><td style="width: 98px!important;">P. Actual</td><td align="center"><input type="text" name="prec_unit" id="prec_unit" class="form-control" style="text-align: center" value="<?=$simu_prec_unit?>"></td></tr></th>
                            <tr><td>Max</td><td align="center"><input type="text" name="max" id="max" class="align-center" readonly="readonly"></td></tr>
                            <tr><td>Min</td><td align="center"><input type="text" name="min" id="min" class="align-center" readonly="readonly"></td></tr>
                            <tr><td>Med</td><td align="center"><input type="text" name="med" id="med" class="align-center" readonly="readonly"></td></tr>
                            <tr><td>Long</td><td align="center"><input type="text" name="long" id="long" class="align-center" readonly="readonly"></td></tr>
                        </table>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="recomendacion"><!--recomendacion--></div>
                </div>
                <div class="row">                    
                    <div class="col-lg-12" id="resultadomontoporprecio"><!--contenido--></div>
                </div>
            </div>
            <div id="analisis_de_precio" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Rango: 1-100</label>
                            <input type="text" name="rango" id="rango" class="form-control" value="2" size="5" style="max-width: 100px" onkeyup='validaNum(this.value,0,100)'>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <table class="table table-bordered grafico">
                            <tr><td style="width: 98px!important;">P. Actual</td><td align="center"><input type="text" name="ap_prec_unit" id="ap_prec_unit" class="form-control" style="text-align: center" value="<?=$simu_prec_unit?>"></td></tr></th>
                            <tr><td>Max</td><td align="center"><input type="text" name="ap_max" id="ap_max" class="align-center" readonly="readonly"></td></tr>
                            <tr><td>Min</td><td align="center"><input type="text" name="ap_min" id="ap_min" class="align-center" readonly="readonly"></td></tr>
                            <tr><td>Med</td><td align="center"><input type="text" name="ap_med" id="ap_med" class="align-center" readonly="readonly"></td></tr>
                            <tr><td>Long</td><td align="center"><input type="text" name="ap_long" id="ap_long" class="align-center" readonly="readonly"></td></tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="resultadoanalisisdeprecio"><!--contenido--></div>
                </div>
            </div>
            <div id="cotizacion" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="resultadocotizacion"><!--contenido--></div>
                </div>
            </div>
        </div>
    </div>    
</div>

<script type="text/javascript">
//http://www.eyecon.ro/bootstrap-datepicker/
 $(document).ready(function(){

    validaNum = function(n,mini,maxi)
    {
        n = parseInt(n);
        if ( n<mini || n>maxi ) alert("El valor debe ser mayor a "+mini+" y menor igual a "+maxi);
    }

    $("#prec_unit").click(function(){
        $(this).select();
    });

    buscar = function(origen){

        var grafico = $("#tabs li.active>a").attr('href');

        if (grafico == '#monto_por_precio') {
            //resultadomontoporprecio();
            getPromedioMontoPorPrecio(origen);
        }else if(grafico == '#analisis_de_precio'){
            getPromedioAnalisiDePrecio();
        }else if (grafico == '#cotizacion') {
            resultadocotizacion();
        }

    }

    getPromedioMontoPorPrecio = function(origen){

        //console.log("Hola:<?=$simu_prec_unit?>");

        if ($("#fecha_inicio").val()!='' && $("#fecha_final").val() !='') {

            
            $("#loading").show();

            $.ajax({
                type:'GET',
                dataType: 'json',
                url: '../Controller/GraficoC.php?accion=promedio',
                data:{fecha_inicio:$("#fecha_inicio").val(),fecha_final:$("#fecha_final").val(),nemonico:$("#nemonico").val()},

                success:function(data){

                    $("#max").val(data.max);
                    $("#min").val(data.min);
                    $("#long").val(data.long);
                    $("#med").val(data.med);
                    if ("<?=$simu_prec_unit?>"=="" && origen!='RMES') {
                        $("#prec_unit").val(data.cz_ci_fin);
                    }
                    
                    resultadomontoporprecio();
                }
            });
        }else{
            alert("Debe ingresar Fecha Inicio y Fecha Final");
        }
    }

    resultadomontoporprecio = function(){

        if ($("#fecha_inicio").val()!='' && $("#fecha_final").val() !='') {

            var mes          = $(".mostrar_graf.active").val();
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_final  = $("#fecha_final").val();
            var nemonico      = $("#nemonico").val();
            var max          = $("#max").val();
            var min          = $("#min").val();
            var long         = $("#long").val();
            var med          = $("#med").val();
            var prec_unit    = $("#prec_unit").val();
            
            $("#loading").show();

            $.ajax({
                type:'GET',
                url: '../Controller/GraficoC.php?accion=grafico1',
                data:{fecha_inicio:fecha_inicio, fecha_final:fecha_final, nemonico:nemonico, prec_unit:prec_unit, mes:mes, max:max, min:min, long:long},

                success:function(data){

                    $("#resultadomontoporprecio").html(data);
                    $("#loading").hide();
                    getRecomendacion(nemonico, prec_unit);
                }
            });
        }else{
            alert("Debe ingresar Fecha Inicio y Fecha Final");
        }
    }

    getRecomendacion = function(nemonico, prec_unit){

        $("#loading").show();
        $.ajax({
            type:'GET',
            url: '../Controller/GraficoC.php?accion=crearcuadrorec',
            data:{nemonico:nemonico,prec_unit:prec_unit},

            success:function(data){
                $("#recomendacion").html(data);
                $("#loading").hide();
            }
        });
    }

    getPromedioAnalisiDePrecio = function(origen){

        //console.log("Hola:<?=$simu_prec_unit?>");

        if ($("#fecha_inicio").val()!='' && $("#fecha_final").val() !='') {

            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_final = $("#fecha_final").val();
            var nemonico = $("#nemonico").val();
            
            $("#loading").show();

            $.ajax({
                type:'GET',
                dataType: 'json',
                url: '../Controller/GraficoC.php?accion=promedio',
                data:{fecha_inicio:fecha_inicio, fecha_final:fecha_final, nemonico:nemonico},

                success:function(data){

                    $("#ap_max").val(data.max);
                    $("#ap_min").val(data.min);
                    $("#ap_long").val(data.long);
                    $("#ap_med").val(data.med);
                    if ("<?=$simu_prec_unit?>"=="" && origen!='RMES') {
                        $("#ap_prec_unit").val(data.cz_ci_fin);
                    }
                    
                    resultadoanalisisdeprecio();
                }
            });
        }else{
            alert("Debe ingresar Fecha Inicio y Fecha Final");
        }
    }
    resultadoanalisisdeprecio = function(){

        if ($("#fecha_inicio").val()!='' && $("#fecha_final").val() !='' && $("#rango").val() !='') {

            var rango = parseInt($("#rango").val());
            if (rango>0 && rango<=100){

                var fecha_inicio = $("#fecha_inicio").val();
                var fecha_final  = $("#fecha_final").val();
                var nemonico      = $("#nemonico").val();
                var max          = $("#ap_max").val();
                var min          = $("#ap_min").val();
                var long         = $("#ap_long").val();
                var med          = $("#ap_med").val();
                var precio = $("#ap_prec_unit").val();

                $("#loading").show();

                $.ajax({
                    type:'GET',
                    url: '../Controller/GraficoC.php?accion=grafico2',
                    data:{fecha_inicio:fecha_inicio, fecha_final:fecha_final, nemonico:nemonico, max:max, min:min, long:long, med:med, precio:precio, rango:rango},

                    success:function(data){

                        $("#resultadoanalisisdeprecio").html(data);
                        $("#loading").hide();
                    }
                });
            }else{
                alert("El valor debe ser mayor a 0 y menor igual a 100");
                $("#rango").focus();
            }
        }else{
            alert("Debe ingresar Fecha Inicio, Fecha Final y Rango");
        }
    }

    resultadocotizacion = function(){

        if ($("#fecha_inicio").val()!='' && $("#fecha_final").val() !='' && $("#rango").val() !='') {

            $("#loading").show();

            $.ajax({
                type:'GET',
                url: '../Controller/GraficoC.php?accion=grafico3',
                data:{fecha_inicio:$("#fecha_inicio").val(),fecha_final:$("#fecha_final").val(),nemonico:$("#nemonico").val(), rango:$("#rango").val()},

                success:function(data){

                    $("#resultadocotizacion").html(data);
                    $("#loading").hide();
                }
            });
            
        }else{
            alert("Debe ingresar Fecha Inicio y Fecha Final");
        }
    }

    getPromedioMontoPorPrecio('');

    function restarFecha(fecha, cantidad){
        /*var d    = Date.parse(fecha);
        var date = new Date(d);
      
        date.setDate(date.getDate() - cantidad);

        return date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();*/

        $.ajax({
            url: '../Controller/GraficoC.php?accion=restarFecha',
            type: 'GET',
            data: {fecha:fecha,cantidad:cantidad},
            success: function(data){
                $("#fecha_inicio").val(data);
                //Buscamos
                buscar('RMES');
            }
        });
        
    }
    
    $(".mostrar_graf").click(function(){

        var value = $(this).val();
        var node  = $(this);

        $('.mostrar_graf').removeClass('active');
        node.addClass('active');

        var fecha_final = $("#fecha_final").val()

        if (value == '12') {
            //dias 1 anio: 365
            //$("#fecha_inicio").val(restarFecha($("#fecha_final").val(),365));
            restarFecha(fecha_final,12);

        }else if(value == '6'){
            //dias 6 mese: 183
            //$("#fecha_inicio").val(restarFecha($("#fecha_final").val(),183));
            restarFecha(fecha_final,6);

        }else if (value == '3') {
            //dias 6 mese: 91
            //$("#fecha_inicio").val(restarFecha($("#fecha_final").val(),91));
            restarFecha(fecha_final,3);
        }
        
    });

    //CLICK EN LAS PESTAÑAS
    $(".nav-tabs li a").on("click", function(){

        if ($(this).attr('href')=='#monto_por_precio') {

            getPromedioMontoPorPrecio('');

        }else if($(this).attr('href')=='#analisis_de_precio'){
            getPromedioAnalisiDePrecio();
            
        }else if($(this).attr('href')=='#cotizacion'){
            resultadocotizacion();
        }
    });

    //CLICK EN EL COMBO EMPRESA
    buscarClikEmpresa = function(){

        var pestana = $("#tabs li.active a").attr('href');

        if (pestana == '#monto_por_precio') {
            getPromedioMontoPorPrecio('');
        }else if(pestana == '#analisis_de_precio'){
            getPromedioAnalisiDePrecio();
        }else if(pestana == '#cotizacion'){
            resultadocotizacion();
        }
    }
    $("#nemonico").on("change", function(){
        buscarClikEmpresa()        
    });


    $("#cod_grupo").change(function(){

        $("#nemonico").attr('disabled','disabled');
        $.ajax({
            type:'GET',
            url: '../Controller/GraficoC.php?accion=listfavorito',
            data:{cod_grupo:$(this).val()},

            success:function(data){

                $("#nemonico").html(data);
                $("#nemonico").removeAttr('disabled');

                if ($("#nemonico").val()!='') {
                    buscarClikEmpresa();
                }
            }
        });
    });

    /*$("#prec_unit").keypess(function(event) {
        resultadomontoporprecio();
    });*/

    var timer;
    $('#prec_unit').keyup(function () {
        clearTimeout(timer);
        timer = setTimeout(function (event) {
            resultadomontoporprecio();
        }, 500);
    });

    var timer2;
    $('#ap_prec_unit').keyup(function () {
        clearTimeout(timer2);
        timer2 = setTimeout(function (event) {
            resultadoanalisisdeprecio();
        }, 500);
    });



    
 });
 </script>
</body>
</html>