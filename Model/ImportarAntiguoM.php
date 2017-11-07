<?php
function savCatizaActiguo($cotiza, $link){

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

function getPrepareDataTwo($empresa, $data){

    $cotiza = array();

    $html = file_get_html($data);

    foreach($html->find('tr') as $e){
    	
    	if (isset($e->find('td',0)->plaintext)) {

	        $fecha    = str_replace(" ","",$e->find('td',0)->plaintext);
	        $apertura = (double)str_replace(" ","",$e->find('td',1)->plaintext);
	        
	        if ($fecha !='' && $apertura !='' && $apertura > 0) {

	            $cotiza[] = array(
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
    }

    unset($html);

    return $cotiza;
}
?>