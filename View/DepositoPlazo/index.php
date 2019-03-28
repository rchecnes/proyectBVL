<!DOCTYPE>
<html lang="en">
    <head>
    <?php include('../Include/Header.php'); ?>
    </head>
    
    <body>
        <?php include('../Include/Menu.php');?>
        <div class="container">
            <h3 class="title">Déposito Plazo</h3>
            <div class="tabbable">
                <ul class="nav nav-tabs" id="tabs">
                <li class="active"><a data-toggle="tab" href="#importar_empresa_plazo">Importar Empresa - Depósito Plazo</a></li>
                </ul>
                <div class="tab-content">
                    <div id="importar_empresa_plazo" class="tab-pane fade in active">
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
                                        <input type="number" id="dp_plaza" id="dp_plaza" class="form-control" min="0" value="360">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Ubicación:</label>
                                        <select id="dp_ubicacion" name="dp_ubicacion" class="form-control">
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
                        url: '../Controller/DepositoPlazoC.php?accion=listar',
                        data:{},
                        success:function(data){

                            $("#divHistorico").html(data);
                            
                            $.unblockUI();
                        }
                    });

                }

                getListadoEmpresa();

                importarEmpresa = function(){

                    $.blockUI();

                    $.ajax({
                        type:'GET',
                        url: '../Controller/DepositoPlazoC.php?accion=importarEmpresa',
                        data:{dp_moneda:$("#dp_moneda").val(),dp_valor:$("#dp_valor").val(),dp_plaza:$("#dp_plaza").val(),dp_ubicacion:$("#dp_ubicacion").val(),dp_correo:$("#dp_correo").val()},
                        success:function(data){

                            getListadoEmpresa();
                            
                            $.unblockUI();
                        }
                    });
                }
            });
        </script>
    </body>
</html>

