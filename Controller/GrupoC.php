<?php
function createAction(){

	include('../Config/Conexion.php');
	$link = getConexion();


	$cod_user   = $_POST['cod_user'];
	$nom_grupo  = $_POST['nom_grupo'];

	$sqlmax = mysqli_query($link, "SELECT MAX(cod_grupo) AS new_cod FROM user_grupo");
	$rowmax = mysqli_fetch_array($sqlmax);
	$new_cod = ($rowmax['new_cod']!='')?$rowmax['new_cod']+1:1;

	$resp = mysqli_query($link, "INSERT INTO user_grupo(cod_grupo,cod_user,nom_grupo, est_grupo, ord_grupo)VALUES('$new_cod','$cod_user','$nom_grupo',1,1)");

	header("location:../Controller/FavoritoC.php?accion=index");
}

/*function deleteAction(){
	
	$cod_user = $_GET['cod_user'];
	$cod_emp  = $_GET['cod_emp'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "DELETE FROM empresa_favorito WHERE cod_emp='$cod_emp' AND cod_user='$cod_user'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/FavoritoC.php?accion=index");
}*/


switch ($_GET['accion']) {
	case 'index':
		//indexAction();
		break;
	case 'create':
		createAction();
		break;
	case 'delete':
		//deleteAction();
		break;
}
?>
