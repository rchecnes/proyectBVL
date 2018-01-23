<?php
$ruta = 'public_html/domains/bvl.worldapu.com';
//$ruta = '..';
require_once($ruta."/Libraries/PHPMailer/class.phpmailer.php");
require_once($ruta.'/Config/Conexion.php');
require_once($ruta.'/Model/RecomendacionM.php');


function enviarCorreoUsuario($remitente, $receptor, $copia, $asunto, $contenido){

	#Enviar correo a clientes
	#========================
	$mail = new PHPMailer(); // defaults to using php "mail()"


	$mail->SetFrom($remitente['correo'], $remitente['nombre']); //DE
	
	//$mail->AddReplyTo($copia['correo'], $copia['nombre']);

	$mail->AddAddress($receptor['correo'], $receptor['nombre']);

	$mail->Subject  = $asunto;

	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	$mail->MsgHTML($contenido);

	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	if(!$mail->Send()) {
	  return $mail->ErrorInfo;
	} else {
	  return true;
	}
}

function getContenidoCorreo($link, $cod_user){

	$sqlfa = "SELECT * FROM empresa_favorito ef
			  INNER JOIN empresa e ON(ef.cod_emp=e.cod_emp)
			  INNER JOIN user_grupo  ug ON(ef.cod_grupo=ug.cod_grupo)
			  WHERE ef.cod_user='$cod_user'
			  AND ef.est_fab='1'
			  AND ug.cod_user='1'
			  ORDER BY e.cz_fe_fin DESC";
	$resfa = mysqli_query($link, $sqlfa);

	$body = "<table border='1' cellpadding='0' cellspacing='0'>";
	$body .= "<tr><th colspan='2' align='center'>Empresa</th><th colspan='2' align='center'>Última Cotización</th><th>&nbsp;</th></tr>";
	$body .= "<tr><th bgcolor='#e8bd19' align='center'>Nemónico</th><th bgcolor='#e8bd19' align='center'>Nombre</th><th bgcolor='#e8bd19' align='center'>Fecha</th><th bgcolor='#e8bd19' align='center'>Precio</th><th bgcolor='#e8bd19' align='center'>Recomendación</th></tr>";

	while ($rf = mysqli_fetch_array($resfa)) {
	    //echo $rf['cod_emp']."<br>";
		$nemonico   = $rf['nemonico'];
		$empresa    = $rf['nombre'];
		$cz_fe_fin  = $rf['cz_fe_fin'];
		$prec_unit  = $rf['cz_ci_fin'];

	    $recomendacion = getRecomendacionFinal($link, $nemonico, $prec_unit);

	    $body .= "<tr>";
	    	$body .= "<td align='center'>$nemonico</td>";
	    	$body .= "<td align='left'>$empresa</td>";
	    	$body .= "<td align='center'>$cz_fe_fin</td>";
	    	$body .= "<td align='right'>".number_format($prec_unit,3,'.',',')."</td>";
	    	$body .= "<td align='center'>$recomendacion</td>";

	    $body .= "</tr>";

	}

	$body .= "</table>";

	return $body;
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

		//echo $contenido;
		$rptacorreo = enviarCorreoUsuario($remitente, $receptor, $copia, $asunto, $contenido);

		echo "Envio a ".$wu['email_user'].":".$rptacorreo."<br>";
	}
}

NotificarCotizacion();
?>
