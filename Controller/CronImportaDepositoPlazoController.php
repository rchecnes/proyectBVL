<?php
//$ruta = 'public_html/analisisdevalor.com';
$ruta = '..';
include($ruta.'/Util/simple_html_dom_php5.6.php');
require_once($ruta.'/Config/Conexion.php');
require_once($ruta."/Model/CotizaGrupoM.php");
require_once($ruta."/Model/ImportarAntiguoM.php");


function getDepositoPlazo(){

    //global $ruta;
	$link      = getConexion();

    $date      = date('Y-m-d');
	$sqlemp    = "SELECT * FROM empresa_deposito_plazo WHERE dp_stat='1'";
	$respemp   = mysqli_query($link, $sqlemp);

    $c = 0;
	while ($e = mysqli_fetch_array($respemp)) {
		
        $nemonico = $e['nemonico'];
        $codemp = '';

        $data = get_remote_data("https://www.bvl.com.pe/web/guest/informacion-general-empresa?p_p_id=informaciongeneral_WAR_servicesbvlportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-2&p_p_col_count=1&_informaciongeneral_WAR_servicesbvlportlet_cmd=getListaHistoricoCotizaciones&_informaciongeneral_WAR_servicesbvlportlet_codigoempresa=$codemp&_informaciongeneral_WAR_servicesbvlportlet_nemonico=$nemonico&_informaciongeneral_WAR_servicesbvlportlet_tabindex=4&_informaciongeneral_WAR_servicesbvlportlet_jspPage=%2Fhtml%2Finformaciongeneral%2Fview.jsp","_informaciongeneral_WAR_servicesbvlportlet_anoini=$anioini&_informaciongeneral_WAR_servicesbvlportlet_mesini=$mesini&_informaciongeneral_WAR_servicesbvlportlet_anofin=$aniofin&_informaciongeneral_WAR_servicesbvlportlet_mesfin=$mesfin&_informaciongeneral_WAR_servicesbvlportlet_nemonicoselect=$nemonico");

        $new_data = prepararData($data);

        if ($new_data != '') {

            $res = savAction($link,$new_data, $nemonico);

            //Actualizamos la fecha de la ultima importacion
            $upimp = "UPDATE empresa em SET em.last_feem_imp_cz='$date' WHERE em.nemonico='$nemonico'"; 
            mysqli_query($link, $upimp);

            $c ++;
        }

        unset($new_data);
	}
    
    echo $c." Empresas actualizadas";
}

?>