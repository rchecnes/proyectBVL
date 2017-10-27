<?php
//http://www.forosdelweb.com/f18/extraer-datos-tabla-html-710504/
//Cron: 15 22 * * * /usr/local/zend/bin/php -f /var/www/html/AppChecnes/proyectBVL/Controller/CotizaGrupoC.php
//El cron se ejecuta a las 10:15pm de cada dia
//Se esta registrando desde el dia 22/05/2017
//CONFIGURAR CRON CON ESTA LINEA
//opt/php56/bin/php /home3/rchecnes/public_html/domains/bvl.worldapu.com/Controller/CotizaGrupoC.php

$ruta = 'public_html/domains/bvl.worldapu.com';
//$ruta = '..';
//include($ruta.'/Util/simple_html_dom_php5.6.php');
require_once($ruta.'/Config/Conexion.php');
require_once($ruta."/Model/CotizaGrupoM.php");

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

/*function getPrepareDataTwo($empresa, $data){

    $cotiza = array();

    $html = file_get_html($data);

    foreach($html->find('table') as $e){

        $fecha    = str_replace(" ","",$e->find('td',0)->plaintext);
        $apertura = (double)str_replace(" ","",$e->find('td',1)->plaintext);

        if ($fecha !='' && $apertura !='' && $apertura > 0) {

            $cotiza = array(
                    'emp'=> $empresa,
                    'f'  => $fecha,
                    'a'  => $apertura,
                    'c'  => (double)str_replace(" ","",$e->find('td',2)->plaintext),
                    'max'=> (double)str_replace(" ","",$e->find('td',3)->plaintext),
                    'min'=> (double)str_replace(" ","",$e->find('td',4)->plaintext),
                    'prd'=> (double)str_replace(" ","",$e->find('td',5)->plaintext),
                    'cn' => (double)str_replace(" ","",$e->find('td',6)->plaintext),
                    'mn' => (double)str_replace(" ","",$e->find('td',7)->plaintext),
                    'fa' => str_replace(" ","",$e->find('td',8)->plaintext),
                    'ca' => (double)str_replace(" ","",$e->find('td',9)->plaintext)
                    );
        }
    }
    unset($html);

    return $cotiza;
}*/

/*function savCatiza($cotiza){

    //global $ruta;

    $link = getConexion();

    $del_x_cod = "";
    $del_x_emp = "";
    $sql = "";

    foreach ($cotiza as $key => $f) {

        $empresa = $f['emp'];

        if ($empresa !='') {
        
            list($dia, $mes, $ano) = explode('/', $f['f']);
            
            $cod             = $ano.$mes.$dia;
            $fecha           = ($ano!='' && $mes!='' && $dia!='')?$ano.'-'.$mes.'-'.$dia:"";
            $apertura        = $f['a'];
            $cierre          = $f['c'];
            $maxima          = $f['max'];
            $minima          = $f['min'];
            $promedio        = $f['prd'];
            $cant_negociado  = $f['cn'];
            $monto_negociado = $f['mn'];
            list($dia, $mes, $ano) = explode('/', $f['fa']);
            $fecha_anterior  = ($ano!='' && $mes !='' && $dia !='')?$ano.'-'.$mes.'-'.$dia:"";
            $cierre_anterior = $f['ca'];

            $del_x_cod = "'".$cod."',";
            $del_x_emp .= "'".$empresa."',";

            //Actualizamos ultima cotizacion: Si solo si hay cotización en la ultima fecha
            if ($fecha !='' && $cierre!='' && $cierre > 0) {
                $upd_x_emp = "UPDATE empresa em SET em.cz_fe_fin='$fecha',em.cz_ci_fin='$cierre',em.cz_cn_fin='$cant_negociado',em.cz_mn_fin='$monto_negociado' WHERE em.nemonico='$empresa'";
                $respup    = mysqli_query($link,$upd_x_emp);
                unset($upd_x_emp);
            }
            
            $sql .= "('$cod','$empresa','$fecha','$apertura','$cierre','$maxima','$minima','$promedio','$cant_negociado','$monto_negociado','$fecha_anterior','$cierre_anterior'),";

        }
    }

    unset($cotiza);

    if ($del_x_cod !='' && $sql !='') {

        $delete = "DELETE FROM cotizacion WHERE cz_cod IN(".trim($del_x_cod,',').") AND cz_codemp IN(".trim($del_x_emp,',').")";
        $respdel = mysqli_query($link,$delete);
        unset($delete);

        $insert = "INSERT INTO cotizacion (cz_cod,cz_codemp,cz_fecha,cz_apertura,cz_cierre,cz_maxima,cz_minima,cz_promedio,cz_cantnegda,cz_montnegd,cz_fechant,cz_cierreant) VALUES ".trim($sql,',').";";
        $resp    = mysqli_query($link,$insert);
        unset($insert);
        
    }

    unset($del_x_cod);
    unset($del_x_emp);
    unset($sql);
    
    return "ok";
}*/

getCotizacionGrupo();

?>