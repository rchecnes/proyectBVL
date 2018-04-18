<?php
$ruta = 'public_html/analisisdevalor.com';
//$ruta = '..';
require_once($ruta."/Libraries/PHPMailer/class.phpmailer.php");
require_once($ruta.'/Config/Conexion.php');
require_once($ruta.'/Model/RecomendacionM.php');
require_once($ruta.'/Util/util.php');

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

function enviarCorreoUsuario($remitente, $receptor, $copia, $asunto, $contenido){

	#Enviar correo a clientes
	#========================
	$mail = new PHPMailer(); // defaults to using php "mail()"


	$mail->SetFrom($remitente['correo'], $remitente['nombre']); //DE
	
	//$mail->AddReplyTo($copia['correo'], $copia['nombre']);

	$mail->AddAddress($receptor['correo'], $receptor['nombre']);

	$mail->Subject  = $asunto;

	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->IsHTML(true);

	$mail->MsgHTML($contenido);

	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	if(!$mail->Send()) {
	  return $mail->ErrorInfo;
	} else {
	  return true;
	}
}

function getDatosCotiza($link, $fecha, $nemonico){

	$cd_cod   = str_replace('-', '', $fecha);
	$nemonico = strtoupper($nemonico);

	$sql = "SELECT cd_pr_com, cd_pr_ven FROM cotizacion_del_dia WHERE cd_cod='$cd_cod' AND cd_cod_emp='$nemonico'";
	$res = mysqli_query($link, $sql);
	$w   = mysqli_fetch_array($res);

	$compra = ($w['cd_pr_com']!='')?$w['cd_pr_com']:0;
	$venta  = ($w['cd_pr_ven']!='')?$w['cd_pr_ven']:0;

	return array($compra, $venta);
}

function updateEmpresa($link, $nemonico, $compra, $venta, $recomendacion){

	$nemonico = strtoupper($nemonico);

	$sql = "UPDATE empresa SET cz_compra='$compra', cz_venta='$venta', cz_recomen='$recomendacion' WHERE nemonico='$nemonico'";
	$res = mysqli_query($link, $sql);
}

function getContenidoCorreo($link, $cod_user){

	$sqlfa = "SELECT * FROM empresa_favorito ef
			  INNER JOIN empresa e ON(ef.cod_emp=e.cod_emp)
			  INNER JOIN user_grupo  ug ON(ef.cod_grupo=ug.cod_grupo)
			  WHERE ef.cod_user='$cod_user'
			  AND ef.est_fab='1'
			  AND ug.cod_user='$cod_user'";
	$resfa = mysqli_query($link, $sqlfa);

	//Armamos el arreglo para ordenar
	$array_emp = array();
	while ($rf = mysqli_fetch_array($resfa)) {

		$arr_rec = getRecomendacionFinal($link, $rf['nemonico'], $rf['cz_ci_fin']);

		$array_emp[]         = array(
										'grupo'        => $rf['nom_grupo'],
										'nemonico'     => $rf['nemonico'],
										'precio'       => $rf['cz_ci_fin'],
										'empresa'      => $rf['nombre'],
										'fecha'        => $rf['cz_fe_fin'],
										'orden'        => $arr_rec['rec_ord_email'],
										'recomendacion'=> $arr_rec['rec_nombre']
									);
		
	}

	$new_array_emp = ordenarArray($array_emp,'orden','ASC');

	//Creamos la cabecera del correo
	$html_cab = "<table border='1' cellpadding='0' cellspacing='0'>";
	$html_cab .= "<tr><th>&nbsp;</th><th colspan='2' align='center'>Empresa</th><th colspan='4' align='center'>Ultima Cotizacion</th><th>&nbsp;</th></tr>";
	$html_cab .= "<tr><th bgcolor='#e8bd19' align='center'>Grupo</th><th bgcolor='#e8bd19' align='center'>Nemonico</th><th bgcolor='#e8bd19' align='center'>Nombre</th><th bgcolor='#e8bd19' align='center'>Fecha</th><th bgcolor='#e8bd19' align='center'>Precio</th><th bgcolor='#e8bd19' align='center'>Compra</th><th bgcolor='#e8bd19' align='center'>Venta</th><th bgcolor='#e8bd19' align='center'>Recomendacion</th></tr>";

	//Creamos el contenido del correo
	$html_det = "";
	foreach ($new_array_emp as $key => $v) {
		
		$grupo             = quitar_tildes($v['grupo']);
		$nemonico          = quitar_tildes($v['nemonico']);
		$empresa           = quitar_tildes($v['empresa']);
		$fecha             = $v['fecha'];
		$precio            = $v['precio'];
		$recomendacion     = $v['recomendacion'];

		list($compra, $venta) = getDatosCotiza($link, $fecha, $v['nemonico']);

		//Actualizar tabla empresa el campo: compra, venta y recomendacion
		updateEmpresa($link, $nemonico, $compra, $venta, $recomendacion);

	    $html_det .= "<tr>";
	    	$html_det .= "<td align='left'>$grupo</td>";
	    	$html_det .= "<td align='center'>$nemonico</td>";
	    	$html_det .= "<td align='left'>$empresa</td>";
	    	$html_det .= "<td align='center'>$fecha</td>";
	    	$html_det .= "<td align='right'>".number_format($precio,3,'.',',')."</td>";
	    	$html_det .= "<td align='right'>".number_format($compra,3,'.',',')."</td>";
	    	$html_det .= "<td align='right'>".number_format($venta,3,'.',',')."</td>";

	    	$html_det .= "<td align='center'>$recomendacion</td>";
	    $html_det .= "</tr>";

	}

	return ($html_det !='')?$html_cab.$html_det."</table>":"";
	//return $new_array_emp;
}

function NotificarCotizacion(){
	
	$link  = getConexion();

	$sqluser = "SELECT * FROM user";
	$resuser = mysqli_query($link, $sqluser);
	
	$remitente['correo'] = "rchecnes@acuario.com.pe";
	$remitente['nombre'] = "Robot";

	while ($wu = mysqli_fetch_array($resuser)) {
		
		$receptor['correo'] = $wu['email_user'];
		$receptor['nombre'] = $wu['nomb_user'];

		$copia['correo'] = "rchecnes@gmail.com";
		$copia['nombre'] = "Richard Checnes";

		$asunto = utf8_decode("Notificación BVL - Cotización");

		$contenido  = getContenidoCorreo($link, $wu['cod_user']);
		//echo $contenido."<br><br>";

		if($contenido !=''){
			$rptacorreo = enviarCorreoUsuario($remitente, $receptor, $copia, $asunto, $contenido);
		  	echo "Envio a ".$wu['email_user'].":".$rptacorreo."<br>";
		  	//echo $contenido."<br>";
		}
		
	}
}

NotificarCotizacion();
?>
