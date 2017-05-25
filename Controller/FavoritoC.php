<?php
session_start();

function getFavoritoGrupo($cod_user,$cod_grupo){

	$sql = "SELECT e.cod_emp,e.nombre AS nom_empresa, s.nombre AS nom_sector,e.nemonico,e.moneda,ef.cod_user,
			(SELECT DATE_FORMAT(cz_fecha,'%d/%m/%Y') FROM cotizacion c WHERE c.cz_codemp=e.nemonico ORDER BY c.cz_cod DESC LIMIT 1) AS fe_ult_cotiza,
			(SELECT cz_cierre FROM cotizacion c WHERE c.cz_codemp=e.nemonico ORDER BY c.cz_cod DESC LIMIT 1) AS cz_ult_cierre
			FROM empresa_favorito ef
			INNER JOIN empresa e ON (ef.cod_emp=e.cod_emp)
			INNER JOIN sector s ON(e.cod_sector=s.cod_sector)
			WHERE ef.cod_user='$cod_user' AND ef.cod_grupo='$cod_grupo'";

	$favoritos= mysqli_query($link, $sql);

	return $favoritos;
}

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');

	$cod_user = $_SESSION['cod_user'];

	include('../View/Favorito/index.php');
}

function createAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user  = $_SESSION['cod_user'];
	$cod_emp   = $_POST['cod_emp'];
	$cod_grupo = $_POST['cod_grupo'];

	$resp = mysqli_query($link, "INSERT INTO empresa_favorito(cod_user,cod_emp,cod_grupo,est_fab,ord_fab)VALUES('$cod_user','$cod_emp','$cod_grupo',1,1)");

	header("location:../Controller/FavoritoC.php?accion=index");
}

function deleteAction(){
	
	$cod_user  = $_GET['cod_user'];
	$cod_emp   = $_GET['cod_emp'];
	$cod_grupo = $_GET['cod_grupo'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "DELETE FROM empresa_favorito WHERE cod_emp='$cod_emp' AND cod_user='$cod_user' AND cod_grupo='$cod_grupo'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/FavoritoC.php?accion=index");
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'create':
		createAction();
		break;
	case 'delete':
		deleteAction();
		break;
}
?>
