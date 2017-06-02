<?php
session_start();

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	/*$sqlmax = mysqli_query($link, "SELECT MAX(cod_grupo) AS new_cod FROM user_grupo");
	$rowmax = mysqli_fetch_array($sqlmax);
	$new_cod = ($rowmax['new_cod']!='')?$rowmax['new_cod']+1:1;*/

	include('../View/Simulador/index.php');
}

/*unction updateAction(){
	
	$cod_user   = $_SESSION['cod_user'];
	$cod_grupo  = $_POST['cod_grupo'];
	$nom_grupo  = $_POST['nom_grupo'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "UPDATE user_grupo SET nom_grupo='$nom_grupo' WHERE cod_grupo='$cod_grupo' AND cod_user='$cod_user'";
	//echo $sql;
	$resp = mysqli_query($link,$sql);

	echo $nom_grupo;
}*/

/*function listarAction(){
	
	$cod_user   = $_SESSION['cod_user'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "SELECT * FROM user_grupo WHERE est_grupo=1 AND cod_user='$cod_user'";
	$resp = mysqli_query($link,$sql);

	$html = '';
	while ($r = mysqli_fetch_array($resp)) {
		$html .= '<option value="'.$r['cod_grupo'].'">'.$r['nom_grupo'].'</option>';
	}

	echo $html;
}*/

/*function deleteAction(){
	
	$cod_user   = $_GET['cod_user'];
	$cod_grupo  = $_GET['cod_grupo'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "DELETE FROM user_grupo WHERE cod_grupo='$cod_grupo' AND cod_user='$cod_user'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/FavoritoC.php?accion=index");
}*/


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
}
?>
