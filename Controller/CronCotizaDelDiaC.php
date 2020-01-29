<?php
//http://www.forosdelweb.com/f18/extraer-datos-tabla-html-710504/
//Cron: 15 22 * * * /usr/local/zend/bin/php -f /var/www/html/AppChecnes/proyectBVL/Controller/CotizaGrupoC.php
//El cron se ejecuta a las 10:15pm de cada dia
//Se esta registrando desde el dia 22/05/2017
//CONFIGURAR CRON CON ESTA LINEA
# /opt/php56/bin/php /home3/rchecnes/public_html/domains/bvl.worldapu.com/Controller/CotizaGrupoC.php
$ruta = 'public_html/analisisdevalor.com';
//$ruta = '..';
include($ruta.'/Util/simple_html_dom_php5.6.php');
require_once($ruta.'/Config/Conexion.php');
require_once($ruta."/Model/CotizaGrupoM.php");
require_once($ruta."/Model/ImportarAntiguoM.php");


function getCotizacionDelDiaActiguo(){

    $link = getConexion();

    //$url  = "http://www.bvl.com.pe/includes/cotizaciones_busca.dat";//solo cotizados
    $url   = "http://www.bvl.com.pe/includes/cotizaciones_todas.dat";
    //$html = file_get_html($url);
    $html = file_get_contents_curl($url);

    $cotiza = array();

    $cd_cod = date('Ymd');
    
    $sql_ins = $sql_del = "";

    foreach($html->find('tr') as $e){
        
        if (isset($e->find('td',1)->plaintext)) {

            $nemonico = $e->find('td',2)->plaintext;

            if ($nemonico !='') {

                $cd_nemo  = $nemonico;
                
                $cd_fecha    = date('Y-m-d');

                $cd_cz_ant  = (isset($e->find('td',6)->plaintext)==true && $e->find('td',6)->plaintext!='')?$e->find('td',6)->plaintext:'';
                $fant       = (isset($e->find('td',7)->plaintext)==true && $e->find('td',7)->plaintext!='')?$e->find('td',7)->plaintext:'';
                
               

                if(str_replace(' ','',$fant)!='' && strlen($fant)>=8){
                    list($fad, $fam, $faa) = explode('/', $fant);
                    $cd_cz_fant = $faa.'-'.$fam.'-'.$fad;
                }else{
                    $cd_cz_fant = '';
                }
                
                $cd_cz_aper = (isset($e->find('td',8)->plaintext)==true && $e->find('td',8)->plaintext!='')?$e->find('td',8)->plaintext:'';
                $cd_cz_ult  = (isset($e->find('td',9)->plaintext)==true && $e->find('td',9)->plaintext!='')?$e->find('td',9)->plaintext:'';
                $cd_cz_var  = (isset($e->find('td',10)->plaintext)==true && $e->find('td',10)->plaintext!='')?$e->find('td',10)->plaintext:'';
                $cd_pr_com  = (isset($e->find('td',11)->plaintext)==true && $e->find('td',11)->plaintext!='')?$e->find('td',11)->plaintext:'';
                $cd_pr_ven  = (isset($e->find('td',12)->plaintext)==true && $e->find('td',12)->plaintext!='')?$e->find('td',12)->plaintext:'';
                $cd_ng_nac  = (isset($e->find('td',13)->plaintext)==true && $e->find('td',13)->plaintext!='')?$e->find('td',13)->plaintext:'';
                $cd_ng_nop  = (isset($e->find('td',14)->plaintext)==true && $e->find('td',14)->plaintext!='')?$e->find('td',14)->plaintext:'';
                $cd_ng_mng  = (isset($e->find('td',15)->plaintext)==true && $e->find('td',15)->plaintext!='')?$e->find('td',15)->plaintext:'';


                $cd_cz_ant   = str_replace(",","",str_replace(" ","",$cd_cz_ant));
                $cd_cz_aper  = str_replace(",","",str_replace(" ","",$cd_cz_aper));
                $cd_cz_ult   = str_replace(",","",str_replace(" ","",$cd_cz_ult));
                $cd_cz_var   = str_replace(",","",str_replace(" ","",$cd_cz_var));
                $cd_pr_com   = str_replace(",","",str_replace(" ","",$cd_pr_com));
                $cd_pr_ven   = str_replace(",","",str_replace(" ","",$cd_pr_ven));
                $cd_ng_nac   = str_replace(",","",str_replace(" ","",$cd_ng_nac));
                $cd_ng_nop   = str_replace(",","",str_replace(" ","",$cd_ng_nop));
                $cd_ng_mng   = str_replace(",","",str_replace(" ","",$cd_ng_mng));

                //Creamos query insert
                $sql_ins .= "('$cd_cod', '$cd_nemo','$cd_fecha','$cd_cz_ant','$cd_cz_fant','$cd_cz_aper','$cd_cz_ult','$cd_cz_var','$cd_pr_com','$cd_pr_ven','$cd_ng_nac','$cd_ng_nop','$cd_ng_mng'),";

                //Creamos sql para eliminar
                $sql_del .= "'".$cd_nemo."',";

            }
   
        }
    }

    $sql_ins = trim($sql_ins,',');
    $sql_del = trim($sql_del,',');

    if($sql_ins!='' && $sql_del!=''){

        //Eliminamos
        $delete = "DELETE FROM cotizacion_del_dia WHERE cd_cod='$cd_cod' AND cd_nemo IN(".$sql_del.")";
        mysqli_query($link, $delete);

        //Insertamos
        $insert = "INSERT INTO cotizacion_del_dia (cd_cod,cd_nemo,cd_fecha,cd_cz_ant,cd_cz_fant,cd_cz_aper,cd_cz_ult,cd_cz_var,cd_pr_com,cd_pr_ven,cd_ng_nac,cd_ng_nop,cd_ng_mng)VALUES ".$sql_ins.";";
        $resp    = mysqli_query($link, $insert);

        unset($sql_del);
        unset($delete);

        unset($sql_ins);
        unset($insert);
    }

    echo "Se importo correctamente los datos padres";
}

getCotizacionDelDiaActiguo();

?>