<?php
session_start();

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql = "SELECT * FROM comision";

	$comisiones= mysqli_query($link, $sql);

	include('../View/Comision/index.php');
}

function createAction(){

	/*include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user  = $_SESSION['cod_user'];
	$cod_emp   = $_POST['cod_emp'];
	$cod_grupo = $_POST['cod_grupo'];

	$resp = mysqli_query($link, "INSERT INTO empresa_favorito(cod_user,cod_emp,cod_grupo,est_fab,ord_fab)VALUES('$cod_user','$cod_emp','$cod_grupo',1,1)");

	header("location:../Controller/FavoritoC.php?accion=index");*/
}

function deleteAction(){
	
	/*$cod_user  = $_GET['cod_user'];
	$cod_emp   = $_GET['cod_emp'];
	$cod_grupo = $_GET['cod_grupo'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "DELETE FROM empresa_favorito WHERE cod_emp='$cod_emp' AND cod_user='$cod_user' AND cod_grupo='$cod_grupo'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/FavoritoC.php?accion=index");*/
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
}
?>
