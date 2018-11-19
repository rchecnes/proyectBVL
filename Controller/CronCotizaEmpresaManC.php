<?php
$ruta = '..';
include($ruta.'/Util/simple_html_dom_php5.6.php');
require_once($ruta.'/Config/Conexion.php');
require_once($ruta."/Model/CotizaGrupoM.php");
require_once($ruta."/Model/ImportarAntiguoM.php");

function getCotizacionGrupoAntiguo(){

    //global $ruta;
    $link       = getConexion();
    $fec_inicio = date('Ymd');
    $fec_fin    = date('Ymd');

    $date      = date('Y-m-d');
    $sqlemp    = "SELECT em.nemonico FROM empresa em
                  LEFT JOIN sector se ON(em.cod_sector=se.cod_sector)
                  WHERE se.estado='1'
                  AND em.estado='1'
                  AND em.nemonico IN(SELECT s_cd.cd_cod_emp FROM cotizacion_del_dia s_cd WHERE s_cd.cd_cod='$fec_inicio' AND s_cd.cd_ng_nop > 0)";
                
    $respemp   = mysqli_query($link, $sqlemp);

    $c = 0;
    while ($e = mysqli_fetch_array($respemp)) {
        
        $nemonico = $e['nemonico'];

        $url = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$fec_inicio&fec_fin=$fec_fin&nemonico=$nemonico";
        //$html = file_get_html($url);
        $html = file_get_contents_curl($url);

        $new_data = getPrepareDataAntiguo($nemonico, $html);

        //$new_data = ordenarArray($new_data,'f','ASC');

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