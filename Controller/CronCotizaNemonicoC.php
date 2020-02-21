<?php
//http://www.forosdelweb.com/f18/extraer-datos-tabla-html-710504/
//Cron: 15 22 * * * /usr/local/zend/bin/php -f /var/www/html/AppChecnes/proyectBVL/Controller/CotizaGrupoC.php
//El cron se ejecuta a las 10:15pm de cada dia
//Se esta registrando desde el dia 22/05/2017
//CONFIGURAR CRON CON ESTA LINEA
# /opt/php56/bin/php /home3/rchecnes/public_html/domains/bvl.worldapu.com/Controller/CotizaGrupoC.php

$ruta = 'public_html/analisisdevalor.com';
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
    $link       = getConexion();
    $fec_inicio = date('Ymd');
    $fec_fin    = date('Ymd');

    $date      = date('Y-m-d');
    $sqlemp    = "SELECT ne.nemonico FROM nemonico ne
                  WHERE ne.estado='1'
                  AND ne.nemonico IN(SELECT s_cd.cd_nemo FROM cotizacion_del_dia s_cd WHERE s_cd.cd_cod='$fec_inicio' AND s_cd.cd_ng_nop > 0)";
                
    $respemp   = mysqli_query($link, $sqlemp);

    $c = 0;
    while ($e = mysqli_fetch_array($respemp)) {
        
        $nemonico = $e['nemonico'];
        $new_codigo = $e['cod_emp_bvl'];

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

        //Actualizamos datos de ultimos beneficios
        if($new_codigo != ''){
            $url2  = "https://loadbalancerprod.bvl.com.pe/static/company/$new_codigo/value";
            $html2 = file_get_contents_curl($url2);
            $data2 = ($html2 != '')?json_decode($html2, true):array();
            if(count($data2)>0){
                foreach($data2 as $grupo_ne){
                    
                    if(strtoupper($grupo_ne['nemonico']) == strtoupper($new_nemonico)){
                        if(isset($grupo_ne['listStock'])){
                            $data_reg = $grupo_ne['listStock'];
                            foreach($data_reg as $row){
                                $ub_date = $row['date'];
                                $ub_acc_cir = (isset($row['quantity']))?$row['quantity']:0;
                                $ub_val_mon = (isset($row['coin']))?$row['coin']:0;
                                $ub_val_nom = (isset($row['nominalValue']))?$row['nominalValue']:0;
                                $ub_val_cap = (isset($row['capital']))?$row['capital']:0;

                                $sqlup = "UPDATE cotizacion SET ub_acc_cir='$ub_acc_cir', ,ub_val_mon='$ub_val_mon', ub_val_nom='$ub_val_nom', ub_val_cap='$ub_val_cap' WHERE cz_nemo='$nemonico' AND cz_fecha='$ub_date'";
                                mysqli_query($link, $sqlup);
                            }
                        }
                    }
                }
            }
        }
    }

    mysqli_free_result($respemp);
    
    
    echo ":".$c." Empresas actualizadas de $fec_inicio al  $fec_fin";   
}

//http://www.bvl.com.pe/includes/cotizaciones_busca.dat
getCotizacionGrupoAntiguo();
?>