<?php
session_start();
             
if ($_SESSION["autenticado"] == "SI") { 
	//sino, calculamos el tiempo transcurrido   
	$fecha_guardada = $_SESSION["ultimo_acceso"];  
	$ahora          = date("Y-m-d H:i:s");
	$tiempo_trans   = (strtotime($ahora)-strtotime($fecha_guardada));    

	//comparamos el tiempo transcurrido   
	if($tiempo_trans >= 100) {   
		//si pasaron 10 minutos o más   
		session_destroy(); // destruyo la sesión 
		$msj = "Su sesión a caducado, intenten autenticarse nuevamente" ;
		header("Location: ../Include/expira.php?error=si&msj=$msj");   
		  
	}else{   
		$_SESSION["ultimo_acceso"] = $ahora;   
	}    
}else{

	//Guardamos la pagina y en la que se quedo el usuario

	$msj = "Debe autenticarse primero" ;
	header("Location: LoginC.php?accion=index&error=si&msj=$msj");
}