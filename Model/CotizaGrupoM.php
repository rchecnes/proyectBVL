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
?>