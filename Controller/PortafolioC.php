<?php
session_start();

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user  = $_SESSION['cod_user'];

	$sql = "SELECT * FROM empresa_portafolio ep
			INNER JOIN empresa em ON(ep.cod_emp=em.cod_emp)
			WHERE ep.cod_user='$cod_user'";

	$portafolio= mysqli_query($link, $sql);

	include('../View/Portafolio/index.php');
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

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user  = $_GET['cod_user'];
	$cod_emp   = $_GET['cod_emp'];
	$por_fech  = $_GET['por_fech'];

	$sql  = "DELETE FROM empresa_portafolio WHERE cod_emp='$cod_emp' AND cod_user='$cod_user' AND DATE_FORMAT(por_fech,'%Y-%m-%d')='$por_fech'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/PortafolioC.php?accion=index");
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'delete':
		deleteAction();
		break;
}
?>
