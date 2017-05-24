<?php

//http://www.forosdelweb.com/f18/extraer-datos-tabla-html-710504/

$ruta = 'var/www/html/AppChecnes/proyectBVL';

function getCotizacionGrupo(){

    global $ruta;

	include($ruta.'/Config/Conexion.php');
	$link      = getConexion();

	$sqlemp    = "SELECT em.nemonico FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) WHERE se.estado='1' AND em.estado='1'";
	$respemp   = mysqli_query($link, $sqlemp);
	$emp_array = array();

	$p_Ini = '20170522';//date('Ymd');
	$P_Fin = '20170522';//date('Ymd');

    $c = 1;
    $cotiza = array();

	while ($e = mysqli_fetch_array($respemp)) {
		$empresa = $e['nemonico'];
		$url     = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$p_Ini&fec_fin=$P_Fin&nemonico=$empresa";
		$data    = file_get_contents($url);
		$cotiza[]= getPrepareData($empresa,$data);

        $c++;
	}

    $resp = savCatiza($cotiza);
}

function getPrepareData($empresa, $data){

	global $ruta;

	include($ruta.'/Util/simple_html_dom.php');

	$dom = new domDocument; 

    // load the html into the object
    $dom->loadHTML($data); 

    // discard white space 
    $dom->preserveWhiteSpace = false;

    // the table by its tag name 
    $tables = $dom->getElementsByTagName('table'); 

    // get all rows from the table 
    $rows = $tables->item(0)->getElementsByTagName('tr'); 

    $cotiza = array();
    // loop over the table rows
    foreach ($rows as $row) 
    { 
       
        $cols = $row->getElementsByTagName('td');

        $fecha = str_replace(" ","",$cols->item(0)->nodeValue);

        if ($fecha !='') {
            
            $cotiza = array(
                    'emp'=> $empresa,
                    'f'  => $fecha,
                    'a'  => (float)str_replace(" ","",$cols->item(1)->nodeValue),
                    'c'  => (float)str_replace(" ","",$cols->item(2)->nodeValue),
                    'max'=> (float)str_replace(" ","",$cols->item(3)->nodeValue),
                    'min'=> (float)str_replace(" ","",$cols->item(4)->nodeValue),
                    'prd'=> (float)str_replace(" ","",$cols->item(5)->nodeValue),
                    'cn' => (float)str_replace(" ","",$cols->item(6)->nodeValue),
                    'mn' => (float)str_replace(" ","",$cols->item(7)->nodeValue),
                    'fa' => str_replace(" ","",$cols->item(8)->nodeValue),
                    'ca' => (float)str_replace(" ","",$cols->item(9)->nodeValue)
                    );
        }
    }

    return $cotiza;
}

function savCatiza($cotiza){

    global $ruta;

    include($ruta.'/Config/Conexion.php');
    $link = getConexion();

    $del_x_cod = "";
    $del_x_emp = "";
    $sql = "";

    foreach ($cotiza as $key => $f) {

        list($dia, $mes, $ano) = explode('/', $f['f']);
        
        $cod             = $ano.$mes.$dia;
        $empresa         = $f['emp'];
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
        
        $sql .= "('$cod','$empresa','$fecha','$apertura','$cierre','$maxima','$minima','$promedio','$cant_negociado','$monto_negociado','$fecha_anterior','$cierre_anterior'),";
    }

    if ($del_x_cod !='' && $sql !='') {

        $delete = "DELETE FROM cotizacion WHERE cz_cod IN(".trim($del_x_cod,',').") AND cz_codemp IN(".trim($del_x_emp,',').")";
        
        $respdel = mysqli_query($link,$delete);

        $insert = "INSERT INTO cotizacion (cz_cod,cz_codemp,cz_fecha,cz_apertura,cz_cierre,cz_maxima,cz_minima,cz_promedio,cz_cantnegda,cz_montnegd,cz_fechant,cz_cierreant) VALUES ".trim($sql,',').";";
        
        $resp    = mysqli_query($link,$insert);
    }
    
    return "ok";
}

getCotizacionGrupo();

?>