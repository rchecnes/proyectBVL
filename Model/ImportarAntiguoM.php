<?php
function savCatizaAntiguo($link, $cotiza, $nemonico){

    $del_x_cod = "";
    $sql = "";

    //$upd_x_emp = "";

    $cant_data = count($cotiza);
    $contador  = 0;

    foreach ($cotiza as $key => $f) {

        $contador ++;

        $empresa = $f['emp'];

        if ($empresa !='') {
        
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
            
            $sql .= "('$cod','$empresa','$fecha','$apertura','$cierre','$maxima','$minima','$promedio','$cant_negociado','$monto_negociado','$fecha_anterior','$cierre_anterior',0,0,0),";

            if ($cant_data == $contador) {

                $upd_x_emp = "UPDATE empresa em SET em.cz_fe_fin='$fecha',em.cz_ci_fin='$cierre',em.cz_cn_fin='$cant_negociado',em.cz_mn_fin='$monto_negociado' WHERE em.nemonico='$empresa'";

                $respup    = mysqli_query($link, $upd_x_emp);
            }

        }
    }

    unset($cotiza);

    if ($del_x_cod !='' && $sql !='') {

        $delete = "DELETE FROM cotizacion WHERE cz_cod IN(".trim($del_x_cod,',').") AND cz_codemp IN('$nemonico')";
        $respdel = mysqli_query($link,$delete);
        //echo $delete."<br>";
        unset($delete);

        $insert = "INSERT INTO cotizacion (cz_cod,cz_codemp,cz_fecha,cz_apertura,cz_cierre,cz_maxima,cz_minima,cz_promedio,cz_cantnegda,cz_monto_neg_ori,cz_fechant,cz_cierreant,cz_num_oper,
            cz_num_compra,cz_num_venta) VALUES ".trim($sql,',').";";
        $resp    = mysqli_query($link,$insert);
        //echo $insert;
        unset($insert);
    }

    unset($del_x_cod);
    unset($sql);
    
    return "ok";
}

function getPrepareDataAntiguo($empresa, $html){

    $cotiza = array();

    foreach($html->find('tr') as $e){
    	
    	if (isset($e->find('td',0)->plaintext)) {

	        $fecha    = str_replace(" ","",$e->find('td',0)->plaintext);
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
	                    'emp'=> $empresa,
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

    unset($html);

    return $cotiza;
}
?>