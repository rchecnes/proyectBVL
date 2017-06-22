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
                        'defect' => '',
                        'edit'   => '',
                        'enable' => 'enable'
                    );
                    Combobox($link, $params);
                 ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Empresa:</label>
                <?php
                  $params = array(
                        'select' => array('id'=>'empresa', 'name'=>'empresa', 'class'=>'form-control'),
                        'sql'    => "SELECT DISTINCT(e.nemonico), e.nemonico,e.nombre FROM empresa_favorito ef INNER JOIN empresa e ON(ef.cod_emp=e.cod_emp) WHERE e.estado=1 AND ef.est_fab AND ef.cod_user='$cod_user'",
                        'attrib' => array('value'=>'nemonico','desc'=>'nemonico,nombre', 'concat'=>' - ','descextra'=>''),
                        'empty'  => false,
                        'defect' => 'ENGEPEC1',
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
                    <div class="col-lg-12" id="resultadomontoporprecio"><!--contenido--></div>
                </div>
            </div>
            <div id="analisis_de_precio" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Rango: 1-100</label>
                            <input type="text" name="rango" id="rango" class="form-control" value="2" size="5" onkeyup='validaNum(this.value,0,100)'>
                        </div>
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


    buscar = function(){

        var grafico = $("#tabs li.active>a").attr('href');

        if (grafico == '#monto_por_precio') {
            resultadomontoporprecio();
        }else if(grafico == '#analisis_de_precio'){
            resultadoanalisisdeprecio();
        }else if (grafico == '#cotizacion') {
            resultadocotizacion();
        }

    }

    resultadomontoporprecio = function(){

        if ($("#fecha_inicio").val()!='' && $("#fecha_final").val() !='') {

            
            $("#loading").show();

            $.ajax({
                type:'GET',
                url: '../Controller/GraficoC.php?accion=grafico1',
                data:{fecha_inicio:$("#fecha_inicio").val(),fecha_final:$("#fecha_final").val(),empresa:$("#empresa").val()},

                success:function(data){

                    $("#resultadomontoporprecio").html(data);
                    $("#loading").hide();
                }
            });
        }else{
            alert("Debe ingresar Fecha Inicio y Fecha Final");
        }
    }

    resultadoanalisisdeprecio = function(){

        if ($("#fecha_inicio").val()!='' && $("#fecha_final").val() !='' && $("#rango").val() !='') {

            n = parseInt($("#rango").val());
            if (n>0 && n<=100){

                $("#loading").show();

                $.ajax({
                    type:'GET',
                    url: '../Controller/GraficoC.php?accion=grafico2',
                    data:{fecha_inicio:$("#fecha_inicio").val(),fecha_final:$("#fecha_final").val(),empresa:$("#empresa").val(), rango:$("#rango").val()},

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
                data:{fecha_inicio:$("#fecha_inicio").val(),fecha_final:$("#fecha_final").val(),empresa:$("#empresa").val(), rango:$("#rango").val()},

                success:function(data){

                    $("#resultadocotizacion").html(data);
                    $("#loading").hide();
                }
            });
            
        }else{
            alert("Debe ingresar Fecha Inicio y Fecha Final");
        }
    }

    resultadomontoporprecio();

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
                buscar();
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

    //$(".mostrar_graf").on("click", function(){
    //    resultadomontoporprecio();
    //});

    $(".nav-tabs li a").on("click", function(){

        if ($(this).attr('href')=='#monto_por_precio') {

            resultadomontoporprecio();

        }else if($(this).attr('href')=='#analisis_de_precio'){
            resultadoanalisisdeprecio();
            
        }else if($(this).attr('href')=='#cotizacion'){
            resultadocotizacion();
        }
    });

    $("#cod_grupo").change(function(){

        $("#empresa").attr('disabled','disabled');
        $.ajax({
            type:'GET',
            url: '../Controller/GraficoC.php?accion=listfavorito',
            data:{cod_grupo:$(this).val()},

            success:function(data){

                $("#empresa").html(data);
                $("#empresa").removeAttr('disabled');
            }
        });
    });

    
 });
 </script>
</body>
</html>