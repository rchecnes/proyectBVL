<?php
session_start();

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Control/Combobox/Combobox.php');

	$cod_user = $_SESSION['cod_user'];

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

function datoscabAction(){
	
	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_emp  = $_POST['cod_emp'];

	$sql  = "SELECT * FROM empresa WHERE nemonico='$cod_emp' LIMIT 1";
	$resp = mysqli_query($link,$sql);
	$r    = mysqli_fetch_array($resp);

	$cz_cn_fin = ($r['cz_cn_fin'] > 0)?$r['cz_cn_fin']:0;//Monto estimado
	$cz_mn_fin = ($r['cz_mn_fin'] > 0)?$r['cz_mn_fin']:0;//Precio unitario
	$cant_acc = ($cz_cn_fin > 0 && $cz_mn_fin>0)?$cz_cn_fin/$cz_mn_fin:0;
	$mont_neg = $cz_mn_fin*$cant_acc;

	$cab = array('mont_est'=>$cz_mn_fin,'pre_unit'=>$cz_mn_fin, 'cant_acc'=>$cant_acc,'mont_neg'=>$mont_neg);
	

	echo json_encode($cab);
}

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
	case 'datoscab':
		datoscabAction();
		break;
}
?>
