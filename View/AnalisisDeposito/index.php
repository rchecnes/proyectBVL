<!DOCTYPE>
<html lang="en">
    <head>
    <?php include('../Include/Header.php'); ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    </head>
    
    <body>
        <?php include('../Include/Menu.php');?>
        <div class="container">
            <h3 class="title">Análisis - Depósito Plazo</h3>
            <div class="tabbable">
                <form methos="POST" id="form_deposito" name="form_deposito">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Moneda:</label>
                                <select id="dp_moneda" name="dp_moneda" class="form-control">
                                    <option value="">TODOS</option>
                                    <option value="MN">SOLES</option>
                                    <option value="ME">DOLARES</option>
                                </select>
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Plazo Desde:</label>
                                <input type="number" id="dp_plazo_d" id="dp_plazo_d" class="form-control" min="0" value="">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Plazo Hasta:</label>
                                <input type="number" id="dp_plazo_h" id="dp_plazo_h" class="form-control" min="0" value="">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Depósito:</label>
                                <input type="number" id="dp_valor" name="dp_valor" class="form-control" min="0" value="10000">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Entidad Financiera:</label>
                                <select id="dp_empresa" name="dp_empresa" class="form-control">
                                    <option value="3">3 Primeras</option> 
                                    <option value="4">4 Primeras</option>                            
                                    <option value="5">5 Primeras</option>                              
                                    <option value="6" selected="selected">6 Primeras</option>
                                    <option value="7">7 Primeras</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input name="button" type="button" class="btn btn-success" id="buscar" value="Buscar">
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" id="info_html_grafico">
            </div>
            <br>
            <br>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                
                mostrar = function(){
            
                    $.blockUI();

                    $.ajax({
                        type:'GET',
                        url: '../Controller/AnalisisDepositoC.php?accion=mostrar',
                        data:{dp_moneda:$("#dp_moneda").val(),dp_valor:$("#dp_valor").val(),dp_plazo_d:$("#dp_plazo_d").val(),dp_plazo_h:$("#dp_plazo_h").val(),dp_empresa:$("#dp_empresa").val()},
                        success:function(data){

                            $("#info_html_grafico").html(data);
                            
                            $.unblockUI();
                        }
                    });

                }

                mostrar();

                $("#buscar").on("click", function(){
                    mostrar();
                })

                
            });
        </script>
    </body>
</html>