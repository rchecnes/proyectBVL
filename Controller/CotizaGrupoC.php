<?php
//http://www.forosdelweb.com/f18/extraer-datos-tabla-html-710504/
//Cron: 15 22 * * * /usr/local/zend/bin/php -f /var/www/html/AppChecnes/proyectBVL/Controller/CotizaGrupoC.php
//El cron se ejecuta a las 10:15pm de cada dia
//Se esta registrando desde el dia 22/05/2017

$ruta = 'home/rchecnes/public_html/domains/bvl.worldapu.com';
include('../Util/simple_html_dom.php');
function getConexion(){

    $DB_SERVER = '108.167.189.18';//Publico
    $DB_USER   = 'rchecnes_apu';
    $DB_PASS   = 'raisa6242016apu';
    $DB_NAME   = 'rchecnes_bvl';

    $link = mysqli_connect($DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME);

    return $link;
}

function getCotizacionGrupo(){

    //global $ruta;
	$link      = getConexion();

	$sqlemp    = "SELECT em.nemonico FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) WHERE se.estado='1' AND em.estado='1' ORDER BY em.nemonico ASC limit 10";
	$respemp   = mysqli_query($link, $sqlemp);
	$emp_array = array();

	$p_Ini = '20170904';//date('Ymd');
	$P_Fin = '20170904';//date('Ymd');

    $c = 1;
    $cotiza = array();
	while ($e = mysqli_fetch_array($respemp)) {
		$empresa = $e['nemonico'];
		$url     = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$p_Ini&fec_fin=$P_Fin&nemonico=$empresa";
		$data    = file_get_contents($url);
        
        $cotiza_row = getPrepareData($empresa,$data);
        
        if (count($cotiza_row)>0) {
            $cotiza[] = $cotiza_row;
        }
		
        unset($data);
        unset($cotiza_row);

        $c++;
	}
    //echo "</table>";
    $resp = savCatiza($cotiza);

    echo $resp;

    unset($cotiza);
}

function getPrepareData($empresa, $data){
    //echo $data;exit();
	//global $ruta;

	//include('../Util/simple_html_dom.php');

	$dom = new domDocument; 

    // load the html into the object
    //$dom->loadHTML($data); 
    $dom->loadHTML(mb_convert_encoding($data, 'HTML-ENTITIES', 'UTF-8'));

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
                    'a'  => (double)str_replace(" ","",$cols->item(1)->nodeValue),
                    'c'  => (double)str_replace(" ","",$cols->item(2)->nodeValue),
                    'max'=> (double)str_replace(" ","",$cols->item(3)->nodeValue),
                    'min'=> (double)str_replace(" ","",$cols->item(4)->nodeValue),
                    'prd'=> (double)str_replace(" ","",$cols->item(5)->nodeValue),
                    'cn' => (double)str_replace(" ","",$cols->item(6)->nodeValue),
                    'mn' => (double)str_replace(" ","",$cols->item(7)->nodeValue),
                    'fa' => str_replace(" ","",$cols->item(8)->nodeValue),
                    'ca' => (double)str_replace(" ","",$cols->item(9)->nodeValue)
                    );
        }
    }

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