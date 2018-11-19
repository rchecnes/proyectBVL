<?php
$ruta = '..';
include($ruta.'/Util/simple_html_dom_php5.6.php');
require_once($ruta.'/Config/Conexion.php');
require_once($ruta."/Model/CotizaGrupoM.php");
require_once($ruta."/Model/ImportarAntiguoM.php");

function getCotizacionGrupoAntiguo(){
    //http://analisisdevalor.com/Controller/CronCotizaEmpresaManC.php?fec_inicio=20180922&fec_fin=20181115&abc_nemonico=A

    $link       = getConexion();
    $fec_inicio = date('Ymd');
    $fec_fin    = date('Ymd');
    $abc_nemonico = ($_GET['abc_nemonico']!='')?" AND em.nemonico LIKE '".$_GET['abc_nemonico']."%'":"";
    $fec_inicio   = $_GET['fec_inicio'];
    $fec_fin      = $_GET['fec_fin'];
    if($fec_inicio=='' || $fec_fin ==''){
        echo "Debe pasar como parametro la fecha de inicio y fin";
    }

    $date      = date('Y-m-d');
    $sqlemp    = "SELECT em.nemonico FROM empresa em
                  LEFT JOIN sector se ON(em.cod_sector=se.cod_sector)
                  WHERE se.estado='1'
                  AND em.estado='1'
                  $abc_nemonico";
                          ;
    $respemp   = mysqli_query($link, $sqlemp);

    $c = 0;
    while ($e = mysqli_fetch_array($respemp)) {
        
        $nemonico = $e['nemonico'];
        $url = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$fec_inicio&fec_fin=$fec_fin&nemonico=$nemonico";
        //$html = file_get_html($url);
        $html = file_get_contents_curl($url);

        $new_data = getPrepareDataAntiguo($nemonico, $html);

        if (count($new_data)>0) {

            $res = savCatizaAntiguo($link, $new_data, $nemonico);

            $c ++;
        }

        unset($url);
        unset($html);
        unset($new_data);
    }

    mysqli_free_result($respemp);
    
    
    echo ":".$c." Empresas actualizadas de $fec_inicio al  $fec_fin";   
}

//http://www.bvl.com.pe/includes/cotizaciones_busca.dat
getCotizacionGrupoAntiguo();
?>