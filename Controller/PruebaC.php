<?php
//$url = "https://www.bvl.com.pe/informacion-general-empresa/-/informacion/VOLCABC1/64801/4";
$url = "https://www.bvl.com.pe/web/guest/informacion-general-empresa?p_p_id=informaciongeneral_WAR_servicesbvlportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-2&p_p_col_count=1&_informaciongeneral_WAR_servicesbvlportlet_cmd=getListaHistoricoCotizaciones&_informaciongeneral_WAR_servicesbvlportlet_codigoempresa=60800&_informaciongeneral_WAR_servicesbvlportlet_nemonico=ATACOBC1&_informaciongeneral_WAR_servicesbvlportlet_tabindex=4&_informaciongeneral_WAR_servicesbvlportlet_jspPage=%2Fhtml%2Finformaciongeneral%2Fview.jsp";

# url_get_contents function by Andy Langton: https://andylangton.co.uk/
function getHeaders(){

	$curlHeaders = array (
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Encoding: gzip, deflate',
                    'Accept-Language: en-US,en;q=0.5',
                    'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0.1',
                    'Connection: Keep-Alive',
                    'Pragma: no-cache',
                    'Referer: http://example.com/',
                    'Host: hostname',
                    'Cache-Control: no-cache',
                    'Cookie: visid_incap_185989=9v1q8Ar0ToSOja48BRmb8nn1GFUAAAAAQUIPAAAAAABCRWagbDIfmlN9NTrcvrct; incap_ses_108_185989=Z1orY6Bd0z3nGYE2lbJ/AXn1GFUAAAAAmb41m+jMLFCJB1rTIF28Mg==; _ga=GA1.3.637468927.1427699070; _gat=1; frontend=rqg7g9hp2ht788l309m7gk8qi7; _gat_UA-1279175-12=1; __utma=233911437.637468927.1427699070.1427699078.1427699078.1; __utmb=233911437.2.10.1427699078; __utmc=233911437; __utmz=233911437.1427699078.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmt_UA-1279175-1=1; _cb_ls=1; _chartbeat2=S0WVXDwMWnCFBgQp.1427699081322.1427699232786.1; PRUM_EPISODES=s=1427699568560&r=http%3A//example.com/');
    return $curlHeaders;
}

function url_get_contents($url,$useragent='cURL',$headers=false,$follow_redirects=false,$debug=false) {
	 
	# initialise the CURL library
	$ch = curl_init();
	 
	# specify the URL to be retrieved
	curl_setopt($ch, CURLOPT_URL,$url);
	 
	# we want to get the contents of the URL and store it in a variable
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	 
	# specify the useragent: this is a required courtesy to site owners
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	
	#Set headers
	//curl_setopt ($ch, CURLOPT_HTTPHEADER, getHeaders());

	# ignore SSL errors
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
	# return headers as requested
	if ($headers==true){
	curl_setopt($ch, CURLOPT_HEADER,1);
	}
	 
	# only return headers
	if ($headers=='headers only') {
	curl_setopt($ch, CURLOPT_NOBODY ,1);
	}
	 
	# follow redirects - note this is disabled by default in most PHP installs from 4.4.4 up
	if ($follow_redirects==true) {
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	}
	 
	# if debugging, return an array with CURL's debug info and the URL contents
	if ($debug==true) {
	$result['contents']=curl_exec($ch);
	$result['info']=curl_getinfo($ch);
	}
	 
	# otherwise just return the contents as a variable
	else $result=curl_exec($ch);
	 
	# free resources
	curl_close($ch);
	 
	# send back the data
	return $result;
}
//echo url_get_contents($url);

function wget($url, $follow = true) {

    $host = parse_url($url);

    $agent       = 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0.1';
    $curlHeaders = array(
        'Accept: application/json, text/javascript, */*',
        'Accept-Encoding: gzip, deflate, br',
        'Accept-Language: es-ES,es;q=0.8',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Connection: keep-alive',
        'Pragma: no-cache',
        'Referer: https://www.bvl.com.pe/informacion-general-empresa/-/informacion/ATACOBC1/60800/4',
        'Host: www.bvl.com.pe', // building host header
        'Cache-Control: max-age=0',
        'Cookie: JSESSIONID=7F030E65963427DE15B2823979E2158E; COOKIE_SUPPORT=true; GUEST_LANGUAGE_ID=es_ES; _ga=GA1.3.1899256373.1508162172; _gid=GA1.3.2040075662.1508162172; LFR_SESSION_STATE_20159=1508165823695'
    );
    $ch          = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow); // following redirects or not
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result      = curl_exec($ch);
    return $result;
}

echo(wget('https://www.bvl.com.pe'));

?>