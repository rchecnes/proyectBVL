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
include($ruta.'/Config/Conexion.php');

function getCotizacionGrupo(){

    //global $ruta;
	$link      = getConexion();

	$sqlemp    = "SELECT em.nemonico FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) WHERE se.estado='1' AND em.estado='1' ORDER BY em.nemonico ASC";
	$respemp   = mysqli_query($link, $sqlemp);
	$emp_array = array();

	$p_Ini = date('Ymd');
	$P_Fin = date('Ymd');

    $c = 1;
    $cotiza = array();
	while ($e = mysqli_fetch_array($respemp)) {
		$empresa = $e['nemonico'];
		$url     = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$p_Ini&fec_fin=$P_Fin&nemonico=$empresa";
		//$data    = file_get_contents($url);
        $cotiza_row = getPrepareDataTwo($empresa,$url);
        //var_dump($cotiza_row)."<br><br>";
        if (count($cotiza_row)>0) {
            $cotiza[] = $cotiza_row;
        }
		
        //unset($data);
        unset($cotiza_row);

        $c++;
	}
    
    $resp = savCatiza($cotiza);

    echo $resp;

    unset($cotiza);
}

function getPrepareDataTwo($empresa, $data){

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
}

function savCatiza($cotiza){

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
}

getCotizacionGrupo();

?>