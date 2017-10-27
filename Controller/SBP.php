<?php
require_once('simple_html_dom.php');
//V2. AGREGADO
function normalize_str($str)
{
$replace = array (
    '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
    '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
    '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
    'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
    'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
    'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
    'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
    'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
    'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
    'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
    'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
    'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
    'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
    'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
    'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
    '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
    'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
    'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
    'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
    'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
    'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
    'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
    'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
    'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
    'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
    'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
    'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
    'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
    '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
    'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
    'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
    'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
    'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
    'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
    'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
    'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
    'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
    'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
    'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
    'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
    'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
    'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
    'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
    'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
    'ю' => 'yu', 'я' => 'ya','&eacute;' => 'e','<br>'=>'','<br/>'=>''
);
	//'(' => '',')' => '',

$str = str_replace(array_keys($replace), array_values($replace), $str);

return $str;
}


 $tmpFile = uniqid();
 mb_internal_encoding("UTF-8");

 //$database = $_SESSION['xcom_comp'];
//Copyright (C) 2010  Jonathan Preece
//
//This program is free software: you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
//
//You should have received a copy of the GNU General Public License
//along with this program.  If not, see <http://www.gnu.org/licenses/>.



//REMOVER CARACETERES ESPECIALES
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   return strtoupper($string);
}

function cleanTxImg($string) {
   $replace = array (
    ' ' => '', '-' => '' 
    );

   $string = str_replace(array_keys($replace), array_values($replace), $string);

   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   return strtoupper($string);
}

function strrpos_count($haystack, $needle, $count)
{
    if($count <= 0)
        return false;

    $len = strlen($haystack);
    $pos = $len;

    for($i = 0; $i < $count && $pos; $i++)
        $pos = strrpos($haystack, $needle, $pos - $len - 1);

    return $pos;
}

function get_remote_dataIMG($url, $post_paramtrs = false) {
    $directorio = "/tmp/";
    if(!is_dir("$directorio")) 
        mkdir("$directorio", 0777);
    
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
    //curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
    curl_setopt($c, CURLOPT_MAXREDIRS, 10);
    $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
    if ($follow_allowed) {
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    }curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_TIMEOUT, 60);
    curl_setopt($c, CURLOPT_AUTOREFERER, true);
    curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookie);
    //curl_setopt($c, CURLOPT_COOKIEJAR, $cookie);
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
    } return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode($status) . "<br/><br/>Last data got<br/>:$data";
}

function get_remote_dataO($url, $post_paramtrs = false) {
    $directorio = "/tmp/";
    if(!is_dir("$directorio")) 
        mkdir("$directorio", 0777);
    
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
    //curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
    curl_setopt($c, CURLOPT_MAXREDIRS, 10);
    $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
    if ($follow_allowed) {
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    }curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_TIMEOUT, 60);
    curl_setopt($c, CURLOPT_AUTOREFERER, true);
    curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($c, CURLOPT_COOKIEJAR, $cookie);
    //curl_setopt($c, CURLOPT_COOKIEFILE, $cookie);    
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
    } return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode($status) . "<br/><br/>Last data got<br/>:$data";
}

function get_remote_data($url, $post_paramtrs = false) {
    $directorio = "/tmp/";
    if(!is_dir("$directorio")) 
        mkdir("$directorio", 0777);
    
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
    }curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_TIMEOUT, 60);
    curl_setopt($c, CURLOPT_AUTOREFERER, true);
    curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookie);
    //curl_setopt($c, CURLOPT_COOKIEJAR, $cookie);
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
    } return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode($status) . "<br/><br/>Last data got<br/>:$data";
}

//$es=get_remote_data("https://www.bvl.com.pe/informacion-general-empresa/-/informacion/FERREYC1/73600/4");


$ter_rucn = '45563752';



$directorio = "/tmp/";
if(!is_dir("$directorio")) 
    mkdir("$directorio", 0777);




$txtSunat=$directorio."Sbs.txt";
$tmpFile_=$directorio.$tmpFile;
$tmpFileText=$directorio.$tmpFile.".txt";
$image_file = $directorio."captcha3.jpg";
$cookie=$directorio."cookie.txt";






$res='';
$lines = array();
while (strlen($res)!=4) {

//while (count($lines)!=176) {

    //1. OBTENER LINK DE IMAGEN, TA QUE YUCAZA WEaN

    $data=get_remote_dataO("http://www.sbs.gob.pe/app/spp/afiliados_net/PagSS/afil_existe.aspx");

    //print_r($data);

    $__EVENTTARGET = '';
    $__EVENTARGUMENT = '';
    $__EVENTVALIDATION = '';
    preg_match('/<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*?)"/', $data, $__VIEWSTATE);
    preg_match('/<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="(.*?)"/', $data, $__VIEWSTATEGENERATOR);
    preg_match('/<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="(.*?)"/', $data, $__EVENTVALIDATION);
    $__VIEWSTATE=urlencode($__VIEWSTATE[1]);
    $__VIEWSTATEGENERATOR=urlencode($__VIEWSTATEGENERATOR[1]);
    $__EVENTVALIDATION=urlencode($__EVENTVALIDATION[1]);


    preg_match_all('/<img[^>]+>/i',$data, $matches); 
    $body = $matches[0][0] ;

    $array = array();
    preg_match('/src="([^"]*)"/i', $body, $result);
    $img_url = $result[1];


    //2. DESCARGAR LA IMAGEN AL SERVIDORSH

    $body=get_remote_dataIMG($img_url);
    file_put_contents($image_file, $body);  //Guardamos la iamgen

    $cmd = "/usr/bin/convert $image_file -blur 1x1 -level 20%,65% $image_file";
    exec($cmd);

    $cmd = "/usr/local/bin/tesseract $image_file $tmpFile_";
    exec($cmd);
    $res = file_get_contents($tmpFileText);
    @unlink($tmpFileText);
    $res=trim(cleanTxImg($res));


    /*
    if (strlen($res)==4) {
        $url="http://www.sbs.gob.pe/app/spp/afiliados_net/PagSS/afil_existe.aspx";
        $post="__EVENTTARGET=$__EVENTTARGET&__EVENTARGUMENT=$__EVENTARGUMENT&__VIEWSTATE=$__VIEWSTATE&__VIEWSTATEGENERATOR=$__VIEWSTATEGENERATOR&__EVENTVALIDATION=$__EVENTVALIDATION&DDLTip_Doc=00&num_doc=$ter_rucn&CaptchaControl1=$res&btnBuscarAfilExist=Buscar&txtPaterno=&txtMaterno=&txtPrimerNom=&txtSegundoNom=&CaptchaControl2=";

        $chx = curl_init();
        curl_setopt($chx, CURLOPT_URL,$url);
        curl_setopt($chx, CURLOPT_HEADER, 0);
        curl_setopt($chx, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chx, CURLOPT_POST, TRUE);
        curl_setopt($chx, CURLOPT_POSTFIELDS, "var1=bla&" . $post);
        curl_setopt($chx, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');

        curl_setopt($chx, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($chx, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($chx, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($chx, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($chx, CURLOPT_COOKIEFILE , $cookie);
        //curl_setopt($chx, CURLOPT_COOKIEJAR, $cookie);
        //curl_setopt($chx, CURLOPT_REFERER, $url);
        curl_setopt($chx, CURLOPT_AUTOREFERER, true);
        curl_setopt($chx, CURLOPT_MAXREDIRS, 10);
        curl_setopt($chx, CURLOPT_CONNECTTIMEOUT, 9);
        curl_setopt($chx, CURLOPT_ENCODING, 'gzip,deflate');
        $xxx = curl_exec($chx);

        //GUARDAR CONTENIDO EN UN TEXTO
        file_put_contents($txtSunat, $xxx);

        $lines = file($txtSunat);
        curl_close($chx);

        if (count($lines)==176) {
            echo "EUREKA";
        }
    }
    */

    
}
echo $res."<br>";

/*
$url="http://www.sbs.gob.pe/app/spp/afiliados_net/PagSS/afil_existe.aspx";
$post="__EVENTTARGET=$__EVENTTARGET&__EVENTARGUMENT=$__EVENTARGUMENT&__VIEWSTATE=$__VIEWSTATE&__VIEWSTATEGENERATOR=$__VIEWSTATEGENERATOR&__EVENTVALIDATION=$__EVENTVALIDATION&DDLTip_Doc=00&num_doc=$ter_rucn&CaptchaControl1=$res&btnBuscarAfilExist=Buscar&txtPaterno=&txtMaterno=&txtPrimerNom=&txtSegundoNom=&CaptchaControl2=";

$chx = curl_init();
curl_setopt($chx, CURLOPT_URL,$url);
curl_setopt($chx, CURLOPT_HEADER, 0);
curl_setopt($chx, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chx, CURLOPT_POST, TRUE);
curl_setopt($chx, CURLOPT_POSTFIELDS, "var1=bla&" . $post);
curl_setopt($chx, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');

curl_setopt($chx, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($chx, CURLOPT_AUTOREFERER, TRUE);
curl_setopt($chx, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($chx, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($chx, CURLOPT_COOKIEFILE , $cookie);
//curl_setopt($chx, CURLOPT_COOKIEJAR, $cookie);
//curl_setopt($chx, CURLOPT_REFERER, $url);
curl_setopt($chx, CURLOPT_AUTOREFERER, true);
curl_setopt($chx, CURLOPT_MAXREDIRS, 10);
curl_setopt($chx, CURLOPT_CONNECTTIMEOUT, 9);
curl_setopt($chx, CURLOPT_ENCODING, 'gzip,deflate');
$xxx = curl_exec($chx);

//GUARDAR CONTENIDO EN UN TEXTO
file_put_contents($txtSunat, $xxx);

//echo $url."&".$post."<br>";
$lines = file($txtSunat);
//$lines = file_get_contents($txtSunat);
//print_r($lines);
print_r($xxx);
curl_close($chx);


echo "URL: ".$url."?".$post;

*/



/*
$txtSunat=$directorio."Sbs2.txt";
$lines = file($txtSunat);

if (count($lines)==176) {
    var_dump($lines);

    echo "<br>";
    print_r($lines[66]);
}

*/





/*
for ($i=60; $i <100 ; $i++) { 
    print_r($i."-".$lines[$i]);
}
*/


?>