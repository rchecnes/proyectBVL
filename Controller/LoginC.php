<?php


function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../View/Login/index.php');
}

function validAction(){

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Util/util.php');

	$email_login  = addslashes(limpiarCadena($_POST['email']));
	$password     = addslashes(limpiarCadena($_POST['password']));
	$password_md5 = md5($password);

	$sql = "SELECT * FROM user WHERE pass_user='$password_md5' AND (email_user='$email_login' OR login_user='$email_login') ";
	$resp = mysqli_query($link, $sql);
	$r    = mysqli_fetch_array($resp);

	if ($r['nomb_user'] !='') {

		session_start();
		
		$_SESSION["cod_user"]   = $r['cod_user'];
		$_SESSION["nomb_user"]  = $r['nomb_user'];
		$_SESSION["apepa_user"] = $r['apepa_user'];
		$_SESSION["apema_user"] = $r['apema_user'];
		$_SESSION["email_user"] = $r['email_user'];
		$_SESSION["login_user"] = $r['login_user'];
		
		header("location:../Controller/CotizacionC.php?accion=index");

	}else{

		$error = 'si';
		$msj   = "Datos incorrectos";

		include('../View/Login/index.php');
	}

}

function logoutAction(){

	session_destroy();

	header("location:../Controller/LoginC.php?accion=index");
}



switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'valid':
		validAction();
		break;
	case 'logout':
		logoutAction();
		break;
	
}
?>
