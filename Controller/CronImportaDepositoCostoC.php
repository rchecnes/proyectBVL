<?php
$ruta = 'public_html/analisisdevalor.com';
//$ruta = '..';
include($ruta.'/Util/simple_html_dom_php5.6.php');
require_once($ruta.'/Config/Conexion.php');

function fileGetContentDepositoCurl($url) {

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

function prepararData($data_html){

    $deposito = array();

    if (!empty($data_html)) {

        $fecha  = date('Y-m-d');
        $dh_fsd = "no";// Si=Inversion seguro
        $dh_cost_mant = 0;
        $dh_min_aper  = 0;

        foreach($data_html->find("div[@id='block-detalleproductodedepositosaplazo']") as $div){

            $dh_last_update = $div->find("div[@class='update-date']",0)->plaintext;
            $dh_last_update = preg_replace("[ultima|actualización| |,|:]", "", strtolower($dh_last_update));

            //FSD:Inversion seguro
            foreach ($div->find("div[@class='prod-detail-med']") as $divext) {
                foreach ($divext->find("div[@class='prod-detail-col right']") as $divcol) {

                   $dh_min_aper = $divcol->find('div',3)->plaintext;
                   $dh_min_aper = preg_replace("[s/| |,|$]", "", strtolower($dh_min_aper));

                   $dh_fsd = $divcol->find('div',5)->plaintext;
                   $dh_fsd = preg_replace("[s/| |,]", "", strtolower($dh_fsd));
                }
            }

            //Costo de manenimiento
            foreach ($div->find("div[@class='prod-detail-extra']") as $divcm) {
                $dh_cost_mant = $divcm->find("div[@class='extra-desc']",0)->plaintext;
                $dh_cost_mant = preg_replace("[s/| |,|$]", "", strtolower($dh_cost_mant));
            }
            
            foreach($div->find("table[@class='table']") as $table){

                $contador = 1;
                foreach($table->find("tr") as $e){
                    //var_dump($e);
                    if (isset($e->find('td',0)->plaintext) && isset($e->find('td',1)->plaintext) && isset($e->find('td',2)->plaintext)) {

                        //Saldo Promedio Mensual
                        $prom = preg_replace("[s/|s/.| |,]", "", strtolower($e->find('td',0)->plaintext));
                        $prom = preg_replace("[más|mas]", "9999999999", $prom);
                        list($dh_sal_prom_d, $dh_sal_prom_h) = explode("a",$prom);

                        //Plazo
                        $plazo = preg_replace("[días| |,]", "", strtolower($e->find('td',1)->plaintext));
                        $plazo = preg_replace("[más|mas]", "9999999999", $plazo);
                        list($dh_plazo_d, $dh_plazo_h) = explode("a",$plazo);

                        //TEA
                        $dh_tea = preg_replace("[%| |,]", "", strtolower($e->find('td',2)->plaintext));

                        $deposito[] = array(
                            'dh_pono'      => $contador,
                            'dh_fecha'      => $fecha,
                            'dh_sal_prom_d' => (float)$dh_sal_prom_d,
                            'dh_sal_prom_h' => (float)$dh_sal_prom_h,
                            'dh_plazo_d'    => (float)$dh_plazo_d,
                            'dh_plazo_h'    => (float)$dh_plazo_h,
                            'dh_tea'        => (float)$dh_tea,
                            'dh_last_update'=> $dh_last_update,
                            'dh_fsd'        => ($dh_fsd=='si')?'S':'',
                            'dh_cost_mant'  => (double)$dh_cost_mant,
                            'dh_min_aper'   => (double)$dh_min_aper,
                            'dh_time'       => date('H:i:s')
                        );

                        $contador ++;
                    }
                }
            }
        }
    }

    return $deposito;
}

function savDepositoPlazo($link, $data, $dp_emp_id){

    $sql = "";
    $upd = "";

    foreach ($data as $d) {
        
        $dh_pono        = $d['dh_pono'];
        $dh_fecha       = $d['dh_fecha'];
        $dh_sal_prom_d  = $d['dh_sal_prom_d'];
        $dh_sal_prom_h  = $d['dh_sal_prom_h'];
        $dh_plazo_d     = $d['dh_plazo_d'];
        $dh_plazo_h     = $d['dh_plazo_h'];
        $dh_tea         = $d['dh_tea'];
        $dh_last_update = $d['dh_last_update'];
        $dh_time        = $d['dh_time'];
        $dh_fsd         = $d['dh_fsd'];
        $dh_cost_mant   = $d['dh_cost_mant'];
        $dh_min_aper    = $d['dh_min_aper'];

        $sql .= "('$dp_emp_id','$dh_pono','$dh_fecha','$dh_sal_prom_d','$dh_sal_prom_h','$dh_plazo_d','$dh_plazo_h','$dh_tea','$dh_last_update','$dh_time','$dh_fsd','$dh_cost_mant','$dh_min_aper','1'),";
    }

    $resp = false;

    if ($sql !='') {

        $date = date('Y-m-d');

        $delete = "DELETE FROM historico_deposito_plazo WHERE dh_emp_id='$dp_emp_id' AND dh_fecha='$date'";
        $respdel = mysqli_query($link, $delete);

        $insert = "INSERT INTO historico_deposito_plazo (dh_emp_id, dh_pono, dh_fecha, dh_sal_prom_d, dh_sal_prom_h, dh_plazo_d, dh_plazo_h, dh_tea, dh_last_update, dh_time, dh_fsd, dh_cost_mant, dh_min_aper, dh_stat) VALUES ".trim($sql,',').";";
        $resp    = mysqli_query($link, $insert);

        if($resp){

            $sqlup = "UPDATE empresa_deposito_plazo SET dp_fecha_imcs='$date' WHERE dp_emp_id='$dp_emp_id'";
            $resup = mysqli_query($link, $sqlup);
        }
    }

    unset($sql);

    return $resp;
}

function getDepositoPlazo(){

    //global $ruta;
	$link      = getConexion();

	$sqlemp    = "SELECT * FROM empresa_deposito_plazo WHERE dp_stat='1'";
	$respemp   = mysqli_query($link, $sqlemp);

    $c = 0;
	while ($e = mysqli_fetch_array($respemp)) {
        
        $dp_emp_id = $e['dp_emp_id'];
        $dp_nodo = $e['dp_nodo'];

        //$url = "https://comparabien.com.pe/producto/depositos-plazo/fondesurco-plazo-fijo-mn?prod_id=$dp_emp_id&type=DEPOSITOS";
        $url = "https://comparabien.com.pe//node/$dp_nodo?prod_id=$dp_emp_id&amp;type=DEPOSITOS";
        $data_html = fileGetContentDepositoCurl($url);

        $data_sav = prepararData($data_html);
		
        if (count($data_sav)>0) {

            $res = savDepositoPlazo($link, $data_sav, $dp_emp_id);

            if($res){
                $c ++;
            }
        }

        unset($data_html);
        unset($data_sav);
	}

    echo "Se importo deposito plazo para ".$c." empresas";
}

getDepositoPlazo();

//http://analisisdevalor.com/Controller/CronImportaDepositoCostoC.php
?>

