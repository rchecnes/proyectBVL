<?php

//http://www.forosdelweb.com/f18/extraer-datos-tabla-html-710504/

function getCotizacionGrupo(){

	include('../Config/Conexion.php');
	$link      = getConexion();

	$sqlemp    = "SELECT em.nemonico FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) WHERE se.estado='1' AND em.estado='1' LIMIT 4";
	$respemp   = mysqli_query($link, $sqlemp);
	$emp_array = array();

	$p_Ini = '20170522';//date('Ymd');
	$P_Fin = '20170522';//date('Ymd');

	while ($e = mysqli_fetch_array($respemp)) {
		$empresa = $e['nemonico'];
		$url     = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$p_Ini&fec_fin=$P_Fin&nemonico=$empresa";
		$data    = file_get_contents($url);
		$newdata = getPrepareData($data);

	}
}

function getPrepareData($data){
	echo $data;
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

    // loop over the table rows
    foreach ($rows as $row) 
    { 
       
        $cols = $row->getElementsByTagName('td'); 
       
        echo $cols->item(0)->nodeValue."="; 
        echo $cols->item(1)->nodeValue.'='; 
        echo $cols->item(2)->nodeValue.'='; 
        echo $cols->item(3)->nodeValue.'='; 
        echo $cols->item(4)->nodeValue."="; 
        echo $cols->item(5)->nodeValue.'='; 
        echo $cols->item(6)->nodeValue.'='; 
        echo $cols->item(7)->nodeValue.'=';
        echo $cols->item(8)->nodeValue."="; 
        echo $cols->item(9)->nodeValue.'=';
        
        echo '<br />'; 
    } 
}

getCotizacionGrupo();

?>