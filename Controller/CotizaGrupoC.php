<?php
//http://www.forosdelweb.com/f18/extraer-datos-tabla-html-710504/
//Cron: 15 22 * * * /usr/local/zend/bin/php -f /var/www/html/AppChecnes/proyectBVL/Controller/CotizaGrupoC.php
//El cron se ejecuta a las 10:15pm de cada dia
//Se esta registrando desde el dia 22/05/2017
//CONFIGURAR CRON CON ESTA LINEA
# /opt/php56/bin/php /home3/rchecnes/public_html/domains/bvl.worldapu.com/Controller/CotizaGrupoC.php

$ruta = 'public_html/domains/bvl.worldapu.com';
//$ruta = '..';
include($ruta.'/Util/simple_html_dom_php5.6.php');
require_once($ruta.'/Config/Conexion.php');
require_once($ruta."/Model/CotizaGrupoM.php");
require_once($ruta."/Model/ImportarAntiguoM.php");


function getCotizacionGrupo(){

    //global $ruta;
	$link      = getConexion();

    $date      = date('Y-m-d');
	$sqlemp    = "SELECT em.nemonico FROM empresa em
                LEFT JOIN sector se ON(em.cod_sector=se.cod_sector)
                WHERE se.estado='1'
                AND em.estado='1'
                AND em.last_feem_imp_cz !='$date'
                LIMIT 32";

	$respemp   = mysqli_query($link, $sqlemp);

    $anioini   = date('Y');
    $mesini    = date('m');
    $aniofin   = date('Y');
    $mesfin    = date('m');

    $c = 0;
	while ($e = mysqli_fetch_array($respemp)) {
		
        $nemonico = $e['nemonico'];
        $codemp = '';

        $data = get_remote_data("https://www.bvl.com.pe/web/guest/informacion-general-empresa?p_p_id=informaciongeneral_WAR_servicesbvlportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-2&p_p_col_count=1&_informaciongeneral_WAR_servicesbvlportlet_cmd=getListaHistoricoCotizaciones&_informaciongeneral_WAR_servicesbvlportlet_codigoempresa=$codemp&_informaciongeneral_WAR_servicesbvlportlet_nemonico=$nemonico&_informaciongeneral_WAR_servicesbvlportlet_tabindex=4&_informaciongeneral_WAR_servicesbvlportlet_jspPage=%2Fhtml%2Finformaciongeneral%2Fview.jsp","_informaciongeneral_WAR_servicesbvlportlet_anoini=$anioini&_informaciongeneral_WAR_servicesbvlportlet_mesini=$mesini&_informaciongeneral_WAR_servicesbvlportlet_anofin=$aniofin&_informaciongeneral_WAR_servicesbvlportlet_mesfin=$mesfin&_informaciongeneral_WAR_servicesbvlportlet_nemonicoselect=$nemonico");

        $new_data = prepararData($data);

        if ($new_data != '') {

            $res = savAction($link,$new_data, $nemonico);

            //Actualizamos la fecha de la ultima importacion
            $upimp = "UPDATE empresa em SET em.last_feem_imp_cz='$date' WHERE em.nemonico='$nemonico'"; 
            mysqli_query($link, $upimp);

            $c ++;
        }

        unset($new_data);
	}
    
    echo $c." Empresas actualizadas";
}

function getCotizacionGrupoAntiguo(){

    //global $ruta;
    $link      = getConexion();

    $date      = date('Y-m-d');
    $sqlemp    = "SELECT em.nemonico FROM empresa em
                LEFT JOIN sector se ON(em.cod_sector=se.cod_sector)
                WHERE se.estado='1'
                AND em.estado='1'";
                //AND (em.nemonico LIKE 'T%' OR em.nemonico LIKE 'U%' OR em.nemonico LIKE 'V%' OR em.nemonico LIKE 'X%' OR em.nemonico LIKE 'Y%')
    $respemp   = mysqli_query($link, $sqlemp);

    $fec_inicio   = date('Ymd');
    $fec_fin      = date('Ymd');

    $c = 0;
    while ($e = mysqli_fetch_array($respemp)) {
        
        $nemonico = $e['nemonico'];

        $url = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$fec_inicio&fec_fin=$fec_fin&nemonico=$nemonico";
        $html = file_get_html($url);

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
//getCotizacionGrupo();

?>