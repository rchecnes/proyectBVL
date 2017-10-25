<?php

error_reporting(E_ALL);

function get_remote_data($url, $post_paramtrs = false) {
    
    $directorio = "Temp/";

    if(!is_dir("$directorio")) 
        mkdir("$directorio", 0777);
    
    //$tmpFile_=$directorio.$tmpFile;
    //$tmpFileText=$directorio.$tmpFile.".txt";
    //$image_file = $directorio."captcha3.jpg";
    $cookie=$directorio."cookie.txt";

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    if ($post_paramtrs) {
        curl_setopt($c, CURLOPT_POST, TRUE);
        curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs);
    } curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
    curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
    curl_setopt($c, CURLOPT_MAXREDIRS, 10);
    $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
    if ($follow_allowed) {
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    }
    curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_TIMEOUT, 60);
    curl_setopt($c, CURLOPT_AUTOREFERER, true);
    curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($c, CURLOPT_COOKIEJAR, $cookie);
    $data = curl_exec($c);
    $status = curl_getinfo($c);
    curl_close($c);
    preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si', $status['url'], $link);
    $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si', '$1=$2' . $link[0] . '$3$4$5', $data);
    $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si', '$1=$2' . $link[1] . '://' . $link[3] . '$3$4$5', $data);
    if ($status['http_code'] == 200) {
        return $data;
    } elseif ($status['http_code'] == 301 || $status['http_code'] == 302) {
        if (!$follow_allowed) {
            if (empty($redirURL)) {
                if (!empty($status['redirect_url'])) {
                    $redirURL = $status['redirect_url'];
                }
            } if (empty($redirURL)) {
                preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
                if (!empty($m[2])) {
                    $redirURL = $m[2];
                }
            } if (empty($redirURL)) {
                preg_match('/href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m);
                if (!empty($m[1])) {
                    $redirURL = $m[1];
                }
            } if (!empty($redirURL)) {
                $t = debug_backtrace();
                return call_user_func($t[0]["function"], trim($redirURL), $post_paramtrs);
            }
        }
    } 
    return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode($status) . "<br/><br/>Last data got<br/>:$data";
}

/*function getCotizaGrupo(an){

    $xxx=get_remote_data("https://www.bvl.com.pe/web/guest/informacion-general-empresa?p_p_id=informaciongeneral_WAR_servicesbvlportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-2&p_p_col_count=1&_informaciongeneral_WAR_servicesbvlportlet_cmd=getListaHistoricoCotizaciones&_informaciongeneral_WAR_servicesbvlportlet_codigoempresa=60800&_informaciongeneral_WAR_servicesbvlportlet_nemonico=ATACOBC1&_informaciongeneral_WAR_servicesbvlportlet_tabindex=4&_informaciongeneral_WAR_servicesbvlportlet_jspPage=%2Fhtml%2Finformaciongeneral%2Fview.jsp","_informaciongeneral_WAR_servicesbvlportlet_anoini=2017&_informaciongeneral_WAR_servicesbvlportlet_mesini=10&_informaciongeneral_WAR_servicesbvlportlet_anofin=2017&_informaciongeneral_WAR_servicesbvlportlet_mesfin=10&_informaciongeneral_WAR_servicesbvlportlet_nemonicoselect=ATACOBC1");

    return $xxx;
}*/

function prepararData($data){

    $data     = json_decode($data, true);

    if (isset($data['data'])) {

        $new_data = array();
        
        foreach ($data['data'] as $key => $v) {

            list($dia, $mes, $ano) = explode('/', $v['fecDt']);
            $fecha = $ano.'-'.$mes.'-'.$dia;

            list($dia, $mes, $ano) = explode('/', $v['fecTimp']);
            $fecha_anterior  = $ano.'-'.$mes.'-'.$dia;;   

            $apertura        = (double)$v['valOpen'];

            if ($apertura !='' && $apertura>0){

                $new_data[] = array(
                                'f'=>$fecha,
                                'a'=>$v['valOpen'],
                                'c'=>$v['valLasts'],
                                'max'=>$v['valHighs'],
                                'min'=>$v['valLows'],
                                'prd'=>($v['valVol']>0 && $v['valVol']!='')?$v['valAmt']/$v['valVol']:0,
                                'cn'=>$v['valVol'],
                                'mn'=>$v['valAmtSol'],
                                'fa'=>$fecha_anterior,
                                'ca'=>$v['valPts']
                            );
            }
        }

        return $new_data;

    }else{
        return "";
    }
}

function formatDate($datetime,$format){
  $date=date_create($datetime);
  return date_format($date,$format);
}

function ordenarArray($array, $campo, $tipo){

    //Creamos un arrelgo con lo que se va a ordenar
    $camp_ord = array();
    foreach ($array as $key => $r) {
        //list($dia,$mes,$anio) = $r['f'];
        $camp_ord[$key] = $r[$campo];
    }

    //Ordenamos el arreglo
    if ($tipo =='ASC') {
        array_multisort($camp_ord, SORT_ASC, $array);
    }else{
        array_multisort($camp_ord, SORT_DESC, $array);
    }
    
    return $array;
}

function savAction($link,$data, $cz_codemp){

    $del = "";
    $sql = "";

    $data = ordenarArray($data,'f','ASC');

  
    $cant_data = count($data);
    $contador  = 0;

    foreach ($data as $key => $f) {

        $contador ++;

        list($ano, $mes, $dia) = explode('-', $f['f']);

        $cod             = $ano.$mes.$dia;
        $fecha           = $f['f'];
        $apertura        = (double)$f['a'];  
        $cierre          = $f['c'];
        $maxima          = $f['max'];
        $minima          = $f['min'];
        $promedio        = $f['prd'];
        $cant_negociado  = (int)str_replace(',', '', $f['cn']);
        $monto_negociado = (float)str_replace(',','',$f['mn']);
        //list($dia, $mes, $ano) = explode('/', $f['fa']);
        $fecha_anterior  = $f['fa'];
        $cierre_anterior = $f['ca'];

        //Actualizamos empres con la ultima cotizacion

        if ($cant_data == $contador) {
            $upd_x_emp = "UPDATE empresa em SET em.cz_fe_fin='$fecha',em.cz_ci_fin='$cierre',em.cz_cn_fin='$cant_negociado',em.cz_mn_fin='$monto_negociado' WHERE em.nemonico='$cz_codemp'";
            //echo $upd_x_emp;
            $respup    = mysqli_query($link,$upd_x_emp);
        }
        //Fin actualizar

        $del .= "'".$cod."',";
        
        $sql .= "('$cod','$cz_codemp','$fecha','$apertura','$cierre','$maxima','$minima','$promedio','$cant_negociado','$monto_negociado','$fecha_anterior','$cierre_anterior'),";

    }

    if ($del !='' && $sql !='') {

        $delete = "DELETE FROM cotizacion WHERE cz_cod IN(".trim($del,',').") AND cz_codemp='$cz_codemp'";
        
        $respdel = mysqli_query($link,$delete);

        $insert = "INSERT INTO cotizacion (cz_cod,cz_codemp,cz_fecha,cz_apertura,cz_cierre,cz_maxima,cz_minima,cz_promedio,cz_cantnegda,cz_montnegd,cz_fechant,cz_cierreant) VALUES ".trim($sql,',').";";
        
        $resp    = mysqli_query($link,$insert);
    }

    unset($data);
    
    return "ok";
}
?>