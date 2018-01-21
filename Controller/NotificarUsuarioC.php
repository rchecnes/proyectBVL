<?php
//$ruta = 'public_html/domains/bvl.worldapu.com';
$ruta = '..';
require_once($ruta."/Libraries/PHPMailer/class.phpmailer.php");
require_once($ruta.'/Config/Conexion.php');


function enviarCorreoUsuario($remitente, $receptor, $copia, $asunto, $contenido){

	#Enviar correo a clientes
	#========================
	$mail = new PHPMailer(); // defaults to using php "mail()"


	$mail->SetFrom($remitente['correo'], $remitente['nombre']); //DE
	
	$mail->AddReplyTo($copia['correo'], $copia['nombre']);

	$mail->AddAddress($receptor['correo'], $receptor['nombre']);

	$mail->Subject  = $asunto;

	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	$mail->MsgHTML($contenido);

	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	  echo "Message sent!";
	}
}

function NotificarCotizacion(){

	$link      = getConexion();
	$sqlfa = "SELECT * FROM empresa_favorito";
	$resfa = mysqli_query($link, $sqlfa);

	$body = "";
	while ($rf = mysqli_fetch_array($resfa)) {
	    //echo $rf['cod_emp']."<br>";
	    $body .= $rf['cod_emp'];

	}
}
?>