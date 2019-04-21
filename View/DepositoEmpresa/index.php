<!DOCTYPE>
<html lang="en">
    <head>
    <?php include('../Include/Header.php'); ?>
    </head>
    
    <body>
        <?php include('../Include/Menu.php');?>
        <div class="container">
            <h3 class="title">Depósito Plazo</h3>
            <div class="tabbable">
                <ul class="nav nav-tabs" id="tabs">
                    <li class=""><a data-toggle="tab" href="#importar_empresa_plazo">Importar Empresa</a></li>
                    <li class="active"><a data-toggle="tab" href="#importar_tasas_costos">Historico - Tasas y Costos</a></li>
                </ul>
                <div class="tab-content">
                    <div id="importar_empresa_plazo" class="tab-pane fade in">
                        <form methos="POST" id="form_deposito" name="form_deposito">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Moneda:</label>
                                        <select id="dp_moneda" name="dp_moneda" class="form-control">
                                            <option value="MN">SOLES</option>
                                            <option value="ME">DOLARES</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Valor del depósito:</label>
                                        <input type="number" id="dp_valor" id="dp_valor" class="form-control" min="0" value="100">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Plaza:</label>
                                        <input type="number" id="dp_plazo" id="dp_plazo" class="form-control" min="0" value="360">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Ubicación:</label>
                                        <select id="dp_ubicacion" name="dp_ubicacion" class="form-control">
                                            <option value="LI" selected="selected">Lima y Callao</option>                              
                                            <option value="AM">Amazonas</option>                              
                                            <option value="AN">Ancash</option>                              
                                            <option value="AP">Apurímac</option>                              
                                            <option value="AR">Arequipa</option>                              
                                            <option value="AY">Ayacucho</option>                              
                                            <option value="CA">Cajamarca</option>                              
                                            <option value="CU">Cusco</option>                              
                                            <option value="HV">Huancavelica</option>                              
                                            <option value="HU">Huánuco</option>                              
                                            <option value="IC">Ica</option>                              
                                            <option value="JU">Junín</option>                              
                                            <option value="LL">La Libertad</option>                              
                                            <option value="LA">Lambayeque</option>                              
                                            <option value="LO">Loreto</option>                              
                                            <option value="MD">Madre de Dios</option>                              
                                            <option value="MO">Moquegua</option>                              
                                            <option value="PA">Pasco</option>                              
                                            <option value="PI">Piura</option>                              
                                            <option value="PU">Puno</option>                              
                                            <option value="SM">San Martín</option>                              
                                            <option value="TA">Tacna</option>                              
                                            <option value="TU">Tumbes</option>                              
                                            <option value="UC">Ucayali</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Correo:</label>
                                        <input type="text" id="dp_correo" id="dp_correo" class="form-control" min="0" value="ananimo<?=rand(100,1000)?>@gmail.com">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input name="button" type="button" class="btn btn-success" id="importar_empresa" value="Importar Empresa" onclick="importarEmpresa()">
                                    <!--<input name="button" type="button" class="btn btn-default" id="buscar_historico" value="Buscar Importado" onclick="">-->
                                    <!--<img src="../Assets/img/load.gif" id="loading" style="display: none">-->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="importar_tasas_costos" class="tab-pane fade in active">
                        <form methos="POST" id="form_deposito" name="form_deposito">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Moneda:</label>
                                        <select id="dp_moneda_2" name="dp_moneda_2" class="form-control">
                                            <option value="">Todos</option>
                                            <option value="MN" selected="selected">SOLES</option>
                                            <option value="ME">DOLARES</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Plazo Desde(Vacio = Todos):</label>
                                        <input type="number" id="dp_plazo_d_2" id="dp_plazo_d_2" class="form-control" min="0" value="360" placeholder="TODOS">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Plazo Hasta(Vacio = Todos):</label>
                                        <input type="number" id="dp_plazo_h_2" id="dp_plazo_h_2" class="form-control" min="0" value="1080" placeholder="TODOS">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Valor del depósito(Vacio = Todos):</label>
                                        <input type="number" id="dp_valor_2" id="dp_valor_2" class="form-control" min="0" value="1000" placeholder="TODOS">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Ubicación:</label>
                                        <select id="dp_ubicacion_2" name="dp_ubicacion_2" class="form-control">
                                            <option value="LI" seleceted="selected">Lima y Callao</option>                              
                                            <option value="AM">Amazonas</option>                              
                                            <option value="AN">Ancash</option>                              
                                            <option value="AP">Apurímac</option>                              
                                            <option value="AR">Arequipa</option>                              
                                            <option value="AY">Ayacucho</option>                              
                                            <option value="CA">Cajamarca</option>                              
                                            <option value="CU">Cusco</option>                              
                                            <option value="HV">Huancavelica</option>                              
                                            <option value="HU">Huánuco</option>                              
                                            <option value="IC">Ica</option>                              
                                            <option value="JU">Junín</option>                              
                                            <option value="LL">La Libertad</option>                              
                                            <option value="LA">Lambayeque</option>                              
                                            <option value="LO">Loreto</option>                              
                                            <option value="MD">Madre de Dios</option>                              
                                            <option value="MO">Moquegua</option>                              
                                            <option value="PA">Pasco</option>                              
                                            <option value="PI">Piura</option>                              
                                            <option value="PU">Puno</option>                              
                                            <option value="SM">San Martín</option>                              
                                            <option value="TA">Tacna</option>                              
                                            <option value="TU">Tumbes</option>                              
                                            <option value="UC">Ucayali</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Última Actualización:</label>
                                        <select id="dp_last_update" name="dp_last_update" class="form-control">
                                            <?php
                                            while($d = mysqli_fetch_array($resfa)){
                                                echo '<option value="'.$d['dh_last_update'].'">'.$d['dh_last_update'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input name="button" type="button" class="btn btn-success" id="buscar_historico" value="Buscar" onclick="getHistoricoCosto()">
                                    <!--<img src="../Assets/img/load.gif" id="loading" style="display: none">-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="divHistorico">
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){

                getListadoEmpresa = function(){
            
                    $.blockUI();

                    $.ajax({
                        type:'GET',
                        url: '../Controller/DepositoEmpresaC.php?accion=listar',
                        data:{},
                        success:function(data){

                            $("#divHistorico").html(data);
                            
                            $.unblockUI();
                        }
                    });

                }

                //getListadoEmpresa();

                importarEmpresa = function(){

                    $.blockUI();

                    $.ajax({
                        type:'GET',
                        url: '../Controller/DepositoEmpresaC.php?accion=importarEmpresa',
                        data:{dp_moneda:$("#dp_moneda").val(),dp_valor:$("#dp_valor").val(),dp_plazo:$("#dp_plazo").val(),dp_ubicacion:$("#dp_ubicacion").val(),dp_correo:$("#dp_correo").val()},
                        success:function(data){

                            getListadoEmpresa();
                            
                            $.unblockUI();
                        }
                    });
                }

                getHistoricoCosto = function(){
            
                    $.blockUI();

                    $.ajax({
                        type:'GET',
                        url: '../Controller/DepositoCostoC.php?accion=listar',
                        data:{dp_moneda:$("#dp_moneda_2").val(),dp_valor:$("#dp_valor_2").val(),dp_plazo_d:$("#dp_plazo_d_2").val(),dp_plazo_h:$("#dp_plazo_h_2").val(),dp_ubicacion:$("#dp_ubicacion_2").val(),dp_last_update:$("#dp_last_update").val()},
                        success:function(data){

                            $("#divHistorico").html(data);
                            
                            $.unblockUI();
                        }
                    });

                }

                $("#tabs li a").on("click", function(){

                    if($(this).attr('href')=='#importar_empresa_plazo'){
                        getListadoEmpresa();
                    }else if($(this).attr('href')=='#importar_tasas_costos'){
                        getHistoricoCosto();
                    }
                });

                getHistoricoCosto();

                
            });
        </script>
    </body>
</html>