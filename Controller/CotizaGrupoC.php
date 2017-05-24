<?php

//http://www.forosdelweb.com/f18/extraer-datos-tabla-html-710504/

function getCotizacionGrupo(){

	include('../Config/Conexion.php');
	$link      = getConexion();

	$sqlemp    = "SELECT em.nemonico FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) WHERE se.estado='1' AND em.estado='1'";
	$respemp   = mysqli_query($link, $sqlemp);
	$emp_array = array();

	$p_Ini = '20170522';//date('Ymd');
	$P_Fin = '20170522';//date('Ymd');

    $c = 1;
	while ($e = mysqli_fetch_array($respemp)) {
		$empresa = $e['nemonico'];
		$url     = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$p_Ini&fec_fin=$P_Fin&nemonico=$empresa";
		$data    = file_get_contents($url);
		$newdata = getPrepareData($data);

        $resp = savCatiza($empresa, $newdata);

        echo $c."=>".$resp."<br>";

        $c++;
	}
}

function getPrepareData($data){
	
	include('../Util/simple_html_dom.php');

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

        $cotiza[] = array(
                        'f'  => str_replace(" ","",$cols->item(0)->nodeValue),
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

    return $cotiza;
}

function savCatiza($empresa, $data){

    include('../Config/Conexion.php');
    $link = getConexion();

    $del = "";
    $sql = "";

    foreach ($data as $key => $f) {

        list($dia, $mes, $ano) = explode('/', $f['f']);

        $cod             = $ano.$mes.$dia;
        $fecha           = $ano.'-'.$mes.'-'.$dia;
        $apertura        = $f['a'];
        $cierre          = $f['c'];
        $maxima          = $f['max'];
        $minima          = $f['min'];
        $promedio        = $f['prd'];
        $cant_negociado  = $f['cn'];
        $monto_negociado = $f['mn'];
        list($dia, $mes, $ano) = explode('/', $f['fa']);
        $fecha_anterior  = $ano.'-'.$mes.'-'.$dia;;
        $cierre_anterior = $f['ca'];

        $del .= "'".$cod."',";
        
        $sql .= "('$cod','$empresa','$fecha','$apertura','$cierre','$maxima','$minima','$promedio','$cant_negociado','$monto_negociado','$fecha_anterior','$cierre_anterior'),";
    }

    if ($del !='' && $sql !='') {

        $delete = "DELETE FROM cotizacion WHERE cz_cod IN(".trim($del,',').") AND cz_codemp='$empresa'";
        
        $respdel = mysqli_query($link,$delete);

        $insert = "INSERT INTO cotizacion (cz_cod,cz_codemp,cz_fecha,cz_apertura,cz_cierre,cz_maxima,cz_minima,cz_promedio,cz_cantnegda,cz_montnegd,cz_fechant,cz_cierreant) VALUES ".trim($sql,',').";";
        
        $resp    = mysqli_query($link,$insert);
    }
    
    return "ok";
}

getCotizacionGrupo();

?>