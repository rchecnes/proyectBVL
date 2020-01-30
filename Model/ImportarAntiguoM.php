<?php
function savCatizaAntiguo($link, $cotiza, $nemonico){

    $del_x_cod = "";
    $sql = "";

    //$upd_x_emp = "";

    $cant_data = count($cotiza);
    $contador  = 0;

    foreach ($cotiza as $key => $f) {

        $contador ++;

        $cz_nemo = $f['nem'];

        if ($cz_nemo !='') {
        
            list($ano, $mes, $dia) = explode('-', $f['f']);
            
            $cod             = $ano.$mes.$dia;
            $fecha           = ($ano!='' && $mes!='' && $dia!='')?$ano.'-'.$mes.'-'.$dia:"";
            $apertura        = $f['a'];
            $cierre          = $f['c'];
            $maxima          = $f['max'];
            $minima          = $f['min'];
            $promedio        = $f['prd'];
            $cant_negociado  = $f['cn'];
            $monto_negociado = $f['mn'];
            list($ano, $mes, $dia) = explode('-', $f['fa']);
            $fecha_anterior  = ($ano!='' && $mes !='' && $dia !='')?$ano.'-'.$mes.'-'.$dia:"";
            $cierre_anterior = $f['ca'];

            $del_x_cod .= "'".$cod."',";
            
            $sql .= "('$cod','$cz_nemo','$fecha','$apertura','$cierre','$maxima','$minima','$promedio','$cant_negociado','$monto_negociado','$fecha_anterior','$cierre_anterior'),";

            if ($cant_data == $contador) {

                $upd_x_emp = "UPDATE nemonico ne SET ne.cz_fe_fin='$fecha',ne.cz_ci_fin='$cierre',ne.cz_cn_fin='$cant_negociado',ne.cz_mn_fin='$monto_negociado' WHERE ne.nemonico='$cz_nemo'";

                $respup    = mysqli_query($link, $upd_x_emp);
            }

        }
    }

    unset($cotiza);

    if ($del_x_cod !='' && $sql !='') {

        $delete = "DELETE FROM cotizacion WHERE cz_cod IN(".trim($del_x_cod,',').") AND cz_nemo IN('$nemonico')";
        $respdel = mysqli_query($link,$delete);
        //echo $delete."<br>";
        unset($delete);

        $insert = "INSERT INTO cotizacion (cz_cod,cz_nemo,cz_fecha,cz_apertura,cz_cierre,cz_maxima,cz_minima,cz_promedio,cz_cantnegda,cz_monto_neg_ori,cz_fechant,cz_cierreant) VALUES ".trim($sql,',').";";
        echo $insert;
        $resp    = mysqli_query($link,$insert);
        //echo $insert;
        unset($insert);
    }

    unset($del_x_cod);
    unset($sql);
    
    return "ok";
}

function file_get_contents_curl($url) {

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$data = curl_exec( $ch );
	$data = str_get_html($data);
	curl_close( $ch );
	return ($data!='')?$data:"";
 }
 
function getPrepareDataAntiguo($nemonico, $html){

    $cotiza = array();

    if (!empty($html)) {

        foreach($html->find('tr') as $e){
        	
        	if (isset($e->find('td',0)->plaintext)) {

                $fecha    = str_replace(" ","",$e->find('td',0)->plaintext);
                //echo $fecha."<br>";
    	        $apertura = (double)str_replace(" ","",$e->find('td',1)->plaintext);

                $fecha_ant = str_replace(" ","",$e->find('td',8)->plaintext);
    	        
    	        if ($fecha !='' && $apertura !='' && $apertura > 0) {

                    $fecha_cotiza = "";
                    if ($fecha !='') {
                        $list = count(explode('/', $fecha));
                        if ($list == 3) {
                            list($dia, $mes, $ano) = explode('/', $fecha);
                            $fecha_cotiza = $ano.'-'.$mes.'-'.$dia;
                        }
                    }
                
                    $fecha_anterior = "";
                    if ($fecha_ant !='') {
                        $list = count(explode('/', $fecha_ant));
                        if ($list == 3) {
                            list($dia, $mes, $ano) = explode('/', $fecha_ant);
                            $fecha_anterior  = $ano.'-'.$mes.'-'.$dia;
                        }
                    }

    	            $cotiza[] = array(
    	                    'nem'=> $nemonico,
    	                    'f'  => ($fecha_cotiza!='')?$fecha_cotiza:"0000-00-00",
    	                    'a'  => $apertura,
    	                    'c'  => (double)str_replace(" ","",$e->find('td',2)->plaintext),
    	                    'max'=> (double)str_replace(" ","",$e->find('td',3)->plaintext),
    	                    'min'=> (double)str_replace(" ","",$e->find('td',4)->plaintext),
    	                    'prd'=> (double)str_replace(" ","",$e->find('td',5)->plaintext),
    	                    'cn' => (double)str_replace(",","",$e->find('td',6)->plaintext),
    	                    'mn' => (double)str_replace(",","",$e->find('td',7)->plaintext),
    	                    'fa' => ($fecha_anterior!='')?$fecha_anterior:"0000-00-00",
    	                    'ca' => (double)str_replace(" ","",$e->find('td',9)->plaintext)
    	                    );
    	        }
    	    }
        }
    }

    unset($html);

    return $cotiza;
}
?>