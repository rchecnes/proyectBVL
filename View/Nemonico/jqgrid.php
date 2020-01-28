<!DOCTYPE html>
<html>
<head>
    <title>Empresa</title>
    <script type="text/ecmascript" src="../Assets/jqgrid/js/jquery-1.11.0.min.js"></script> 
    <script type="text/ecmascript" src="../Assets/jqgrid/js/i18n/grid.locale-en.js"></script>   
    <script type="text/ecmascript" src="../Assets/jqgrid/js/jquery.jqGrid.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../Assets/jqgrid/css/ui.jqgrid-bootstrap.css" />
    <script>
        $.jgrid.defaults.width = 780;
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <meta charset="utf-8" />
    <title>jqGrid Loading Data - Million Rows from a REST service</title>
</head>
<body>
<?php include('../Include/Menu.php');?>
<br>
<div class="container">
    <h3>LISTA DE EMPRESAS</h3><br>
    <p class="align-right">
        <a href="../Controller/EmpresaC.php?accion=new" class="btn btn-default" role="button">Nueva Empresa</a>
        <button type="button" class="btn btn-success" onclick="inportarEmpresa()">Importar Empresa</button>
        <img src="../Assets/img/load.gif" id="loading" style="display: none">
    </p>
    <div style="display: none" id="empresas_import"></div>
    <table id="jqGrid" style="width: 100%"></table>
    <div id="jqGridPager"></div>
    <script type="text/javascript">
        $(document).ready(function () {

            var url = '<?php echo $url; ?>';
            $(".navbar-nav li a").each(function(){
                if ($(this).attr('href').indexOf(url)!=-1) {
                    $(this).parent().addClass('active');
                }else{
                    $(this).parent().removeClass('active');
                }
            });
            //http://www.guriddo.net/
            
            $("#jqGrid").jqGrid({
                url: '../Controller/EmpresaC.php?accion=jegjson',
                //url: 'http://trirand.com/blog/phpjqgrid/examples/jsonp/getjsonp.php?callback=?&qwery=longorders',
                mtype: "GET",
                styleUI : 'Bootstrap',
                datatype: "json",
                colModel: [
                    { label: 'Código', name: 'cod_empresa', key: true, width: '' },
                    { label: 'Nombre', name: 'nom_empresa', width: '' },
                    { label: 'Nemonico', name: 'nemonico', width: '' },
                    { label: 'Sector', name: 'nom_sector', width: '' },
                    { label:'Segmento', name: 'segmento', width: '' },
                    { label:'Moneda', name: 'moneda', width: '' },
                    { label:'Acciones', name: 'acciones', width: '200' }
                ],
                viewrecords: true,
                height: 400,
                width: 1000,
                rowNum: 10,
                pager: "#jqGridPager"
            });

            $.ajaxPrefilter( function (options) {
              if (options.crossDomain && jQuery.support.cors) {
                var http = (window.location.protocol === 'http:' ? 'http:' : 'https:');
                options.url = http + '//cors-anywhere.herokuapp.com/' + options.url;
              }
            });

            guardarData = function(data){
                $.ajax({
                    type: 'POST',
                    url: '../Controller/EmpresaC.php?accion=savimported',
                    data: {info:JSON.stringify(data)},
                    success: function(data){
                        $("#loading").hide();
                        window.location.assign("../Controller/EmpresaC.php?accion=index");
                    }
                });
            }

            inportarEmpresa = function(){

                var url = "http://www.bvl.com.pe/includes/cotizaciones_todas.dat";

                $("#loading").show();

                $.ajax({
                    type:'GET',
                    url: url,
                    success:function(data){

                        $("#empresas_import").html(data).hide();
                        var empresas = prepararData(data);                  
                        guardarData(empresas);
                        //getHistorico();
                    }
                });
            }

            prepararData = function(data){
                    
                var dataFila = [];

                $("#empresas_import table tbody tr").each(function (index){

                    var dataCols = {};

                    if (index > 1) {
                        $(this).children("td").each(function (index2){   
                            var campo = '';
                            var valor = '';

                            switch (index2){

                                case 1: campo = 'emp';valor=$(this).text();break;//fecha
                                case 2: campo = 'nem';valor=$(this).text();break;//apertura
                                case 3: campo = 'sec';valor=$(this).text();break;//cierre
                                case 4: campo = 'seg';valor=$(this).text();break;//maxima
                                case 5: campo = 'mon';valor=$(this).text();break;//minima
                               
                            }

                            if (campo !='') {
                                dataCols[campo]=valor;
                            }
                            
                        });

                        dataFila.push(dataCols);
                        
                    }
                    
                });

                return dataFila;
            }

        });
 
   </script>
</div>
</body>
</html>