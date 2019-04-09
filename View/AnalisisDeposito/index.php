<!DOCTYPE>
<html lang="en">
    <head>
    <?php include('../Include/Header.php'); ?>
    </head>
    
    <body>
        <?php include('../Include/Menu.php');?>
        <div class="container">
            <h3 class="title">Anásis - Depósito Plazo</h3>
            <div class="tabbable">
                <form methos="POST" id="form_deposito" name="form_deposito">
                    <div class="row">                                
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Depósito:</label>
                                <input type="number" id="deposito" id="deposito" class="form-control" min="0" value="1000">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Plazo:</label>
                                <input type="number" id="plazo" id="plazo" class="form-control" min="0" value="360">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Empresas:</label>
                                <select id="empresa" name="empresa" class="form-control">
                                    <option value="3">3 Primeras</option>                              
                                    <option value="5" selected="selected">5 Primeras</option>                              
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
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                
                mostrar = function(){
            
                    $.blockUI();

                    $.ajax({
                        type:'GET',
                        url: '../Controller/AnalisisDepositoC.php?accion=mostrar',
                        data:{deposito:$("#deposito").val(),plazo:$("#plazo").val(),empresa:$("#empresa").val()},
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