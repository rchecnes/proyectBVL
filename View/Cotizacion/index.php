
<!DOCTYPE>
<html lang="en">
    <head>
    <?php include('../Include/Header.php'); ?>
    </head>
    
<body>
<?php include('../Include/Menu.php');?>
<div class="container">
    <h3 class="title">IMPORTAR INFORMACION DESDE BOLSA DE VALORES DE LIMA</h3>
    
    <div class="tabbable">
        <ul class="nav nav-tabs" id="tabs">
          <li class="active"><a data-toggle="tab" href="#importar_opcion_1">Operación Por Empresa</a></li>
          <li><a data-toggle="tab" href="#importar_opcion_2">Operación Por Todas Las Empresas</a></li>
        </ul>
        <div class="tab-content">
            <div id="importar_opcion_1" class="tab-pane fade in active">
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
                            <label>Nemonico:</label>
                            <?php   
                                $params = array(
                                    'select' => array('id'=>'nemonico', 'name'=>'nemonico', 'class'=>'form-control'),
                                    'sql'    => 'SELECT ne.nemonico, em.emp_nomb, ne.moneda FROM nemonico ne LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod) WHERE ne.estado=1',
                                    'attrib' => array('value'=>'nemonico','desc'=>'nemonico,emp_nomb,moneda', 'concat'=>' - ','descextra'=>''),
                                    'empty'  => false,
                                    'defect' => 'RELAPAC1',
                                    'edit'   => '',
                                    'enable' => 'enable'
                                );

                                Combobox($link, $params);
                              ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                            $fecha_fin    = date('Y-m-d');
                            //$fecha_final  = strtotime ( '-1 month' , strtotime ( $fecha_fin ) ) ;
                            //$fecha_inicio = date ( 'Y-m-d' , $fecha_final );
                            $fecha_inicio = date('Y').'-'.date('m').'-01';
                           
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
            </div>
            <div id="importar_opcion_2" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Sector:</label>
                            <?php   
                                $params = array(
                                    'select' => array('id'=>'sector_two', 'name'=>'sector_two', 'class'=>'form-control'),
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
                            <select id="moneda_two" name="moneda_two" class="form-control">
                                <option value="">Todos</option>
                                <option value="US$">US$</option>
                                <option value="S/">S/</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Nemonico:</label>
                            <?php   
                                $params = array(
                                    'select' => array('id'=>'nemonico_two', 'name'=>'nemonico_two', 'class'=>'form-control'),
                                    'sql'    => 'SELECT ne.nemonico, em.emp_nomb, ne.moneda FROM nemonico ne LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod) WHERE ne.estado=1',
                                    'attrib' => array('value'=>'nemonico','desc'=>'nemonico,emp_nomb,moneda', 'concat'=>' - ','descextra'=>''),
                                    'empty'  => 'Todos',
                                    'defect' => '',
                                    'edit'   => '',
                                    'enable' => 'enable'
                                );

                                Combobox($link, $params);
                              ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <?php
                            $fecha_fin_two    = date('Y-m-d');
                            $fecha_final_two  = strtotime ( '-1 day' , strtotime ( $fecha_fin_two ) ) ;
                            $fecha_inicio_two = date ( 'Y-m-d' , $fecha_final_two );
                           
                        ?>
                        <label>Fecha Inicio (dd/mm/aaaa):</label>
                        <div class="form-group">
                            <input type="date" id="fecha_inicio_two" name="fecha_inicio_two" class="form-control" placeholder="Fecha Inicio" value="<?=$fecha_inicio_two?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label>&nbsp;</label>
                        <div class="form-group">
                            <label><input type="checkbox" id="empresa_cotiza_dia" name="empresa_cotiza_dia" checked="checked"> Solo Empresas Que Cotizaron</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <input name="button" type="button" class="btn btn-success" id="button" value="Importar Desde BVL" onclick="getCotizacionTwo()">
                        <input name="button" type="button" class="btn btn-default" id="button" value="Buscar Importado" onclick="getHistoricoTwo()">
                        <img src="../Assets/img/load.gif" id="loading_two" style="display: none">
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <br>
    <div id="divCotizaciones"></div>
    <div id="divHistorico"></div>
</div>
<script type="text/javascript">
//http://www.eyecon.ro/bootstrap-datepicker/
 $(document).ready(function(){

    bloquear = function(){
        $("select, input").attr("disabled","disabled");
    }
    desbloquear = function(){
        $("select, input").removeAttr("disabled");
    }
   
    getHistorico = function(){

        var p_Nemonico   = $("#nemonico").val();
        var fecha_inicio = $("#fecha_inicio").val().split('-');
        var p_Ini        = fecha_inicio[0]+'-'+fecha_inicio[1]+'-'+fecha_inicio[2];
        var fecha_fin    = $("#fecha_fin").val().split('-');
        var P_Fin        = fecha_fin[0]+'-'+fecha_fin[1]+'-'+fecha_fin[2];

        $.blockUI();

        $.ajax({
            type:'GET',
            url: '../Controller/CotizacionC.php?accion=listar',
            data:{nemonico:p_Nemonico,fec_inicio:p_Ini,fec_fin:P_Fin, sector: $("#sector option:selected").val(),moneda: $("#moneda option:selected").val(), origen:'one'},
            success:function(data){

                $("#divHistorico").html(data);
                
                $.unblockUI();
            }
        });
        
    }

    getCotizacion = function(){

        var p_Nemonico    = $("#nemonico").val();
        var fecha_inicio  = $("#fecha_inicio").val().split('-');
        var anio_ini      = fecha_inicio[0];
        var mes_ini       = fecha_inicio[1];
        var fecha_fin     = $("#fecha_fin").val().split('-');
        var anio_fin      = fecha_fin[0];
        var mes_fin       = fecha_fin[1];
        var sector        = $("#sector_two option:selected").val();
        var moneda        = $("#moneda_two option:selected").val();

        if (confirm("¿Esta seguro de realizar la importación?.\n Al realizar la importaciòn, reescribira si ya hay información el sistema")) {

            $.blockUI();

            $.ajax({
                type:'POST',
                data:{p_Nemonico:p_Nemonico, anio_ini:anio_ini, mes_ini:mes_ini, anio_fin:anio_fin, mes_fin:mes_fin,fecha_inicio:$("#fecha_inicio").val(),fecha_fin:$("#fecha_fin").val(),sector:sector,moneda:moneda, acc_cotizado:0},
                url: '../Controller/CotizacionC.php?accion=importarmanual',
                success:function(data){

                    $.unblockUI();

                    getHistorico();                
                }
            });
        }
    }

    getHistoricoTwo = function(){

        var p_Nemonico   = $("#nemonico_two").val();
        var fecha_inicio = $("#fecha_inicio_two").val().split('-');
        var p_Ini        = fecha_inicio[0]+'-'+fecha_inicio[1]+'-'+fecha_inicio[2];
        var fecha_fin    = $("#fecha_inicio_two").val().split('-');
        var P_Fin        = fecha_fin[0]+'-'+fecha_fin[1]+'-'+fecha_fin[2];

        $.blockUI();

        $.ajax({
            type:'GET',
            url: '../Controller/CotizacionC.php?accion=listar',
            data:{nemonico:p_Nemonico,fec_inicio:p_Ini,fec_fin:P_Fin, sector: $("#sector_two option:selected").val(),moneda: $("#moneda_two option:selected").val(), origen:'two'},
            dataType: 'html',
            success:function(data){

                $("#divHistorico").html(data);

                $.unblockUI();
            }
        });
    }

    getCotizacionTwo = function(){

        var p_Nemonico    = $("#nemonico_two").val();
        var fecha_inicio  = $("#fecha_inicio_two").val();
        var fecha_fin     = $("#fecha_inicio_two").val();
        var sector        = $("#sector_two option:selected").val();
        var moneda        = $("#moneda_two option:selected").val();
        var acc_cotizado  = ($("#empresa_cotiza_dia").is(":checked")==true)?1:0;

        if (confirm("¿Esta seguro de realizar la importación?.\n Al realizar la importaciòn, reescribira si ya hay información el sistema")) {

            $.blockUI();

            $.ajax({
                type:'POST',
                data:{p_Nemonico:p_Nemonico, fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,sector:sector,moneda:moneda,acc_cotizado:acc_cotizado},
                dataType: 'json',
                url: '../Controller/CotizacionC.php?accion=importarmanual',
                success:function(data){

                    $.unblockUI();

                    getHistoricoTwo();

                    alert('Cantidad de empresa actualizada: '+data.cant_imp);
                }
            });
        }
    }

    getHistorico();

    buscarNemonico = function(){
        $("#nemonico").attr('disabled','disabled');
        $.ajax({
            type: 'GET',
            url: "../Controller/CotizacionC.php?accion=bunemo",
            data: {sector: $("#sector").val(),moneda: $("#moneda").val()},
            success: function( data ) {
                $("#nemonico").html(data);
                $("#nemonico").removeAttr('disabled');
            }
        });
    }

    buscarNemonicoTwo = function(){
        $("#nemonico_two").attr('disabled','disabled');
        $.ajax({
            type: 'GET',
            url: "../Controller/CotizacionC.php?accion=bunemotodos",
            data: {sector: $("#sector_two").val(),moneda: $("#moneda_two").val()},
            success: function( data ) {
                $("#nemonico_two").html(data);
                $("#nemonico_two").removeAttr('disabled');
            }
        });
    }

    $("#sector, #moneda").on("change", function(){
        buscarNemonico();
    });

    $("#sector_two, #moneda_two").on("change", function(){
        buscarNemonicoTwo();
    })

 });
 </script>
</body>
</html>