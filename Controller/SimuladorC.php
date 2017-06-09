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

	$cod_emp         = $_GET['cod_emp'];
	$tipo            = $_GET['tipo'];
	$monto_estimado  = ($_GET['monto_estimado']!='' && $_GET['monto_estimado']>0)?$_GET['monto_estimado']:0;
	$precio_unitario = ($_GET['precio_unitario']!='' && $_GET['precio_unitario']>0)?$_GET['precio_unitario']:0;

	$cz_cn_fin = 0;
	$cz_mn_fin = 0;

	if ($tipo == 'uno') {

		$sql  = "SELECT * FROM empresa WHERE nemonico='$cod_emp' LIMIT 1";
		$resp = mysqli_query($link,$sql);
		$r    = mysqli_fetch_array($resp);

		$cz_cn_fin = ($r['cz_cn_fin'] > 0)?$r['cz_cn_fin']:0;//Monto estimado
		$cz_mn_fin = ($r['cz_mn_fin'] > 0)?$r['cz_mn_fin']:0;//Precio unitario
	}elseif ($tipo == 'dos') {

		$cz_cn_fin = $monto_estimado;
		$cz_mn_fin = $precio_unitario;
	}
	
	$cant_acc = ($cz_cn_fin > 0 && $cz_mn_fin>0)?$cz_cn_fin/$cz_mn_fin:0;
	$mont_neg = $cz_mn_fin*$cant_acc;
	
	$info = array(
				//CABECERA
				'mont_est'=>number_format($cz_cn_fin,3,'.',','),
				'cant_acc'=>number_format($cant_acc,3,'.',','),
				'pre_unit'=>number_format($cz_mn_fin,3,'.',','),
				'mont_neg'=>number_format($mont_neg,3,'.',','),
				//COMPRA
				'c_comision_sab' =>number_format(0,2,'.',','),
				'c_cuota_bvl'    =>number_format(0,2,'.',','),
				'c_f_garantia'   =>number_format(0,2,'.',','),
				'c_cavali'       =>number_format(0,2,'.',','),
				'c_f_liquidacion'=>number_format(0,2,'.',','),
				'c_compra_total' =>number_format(0,2,'.',','),
				'c_igv'          =>number_format(0,2,'.',','),
				'c_compra_smv'   =>number_format(0,2,'.',','),
				'c_costo_compra' =>number_format(0,2,'.',','),
				'c_poliza_compra'=>number_format(0,2,'.',',')
			);

	echo json_encode($info);
}

function infocompraIndex(){
	
	/*$cod_user   = $_GET['cod_user'];
	$cod_grupo  = $_GET['cod_grupo'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "DELETE FROM user_grupo WHERE cod_grupo='$cod_grupo' AND cod_user='$cod_user'";
	$resp = mysqli_query($link,$sql);*/

	/*$cab = array(
				'c_comision_sab' =>number_format(0,2,'.',','),
				'c_cuota_bvl'    =>number_format(0,2,'.',','),
				'c_f_garantia'   =>number_format(0,2,'.',','),
				'c_cavali'       =>number_format(0,2,'.',','),
				'c_f_liquidacion'=>number_format(0,2,'.',','),
				'c_compra_total' =>number_format(0,2,'.',','),
				'c_igv'          =>number_format(0,2,'.',','),
				'c_compra_smv'   =>number_format(0,2,'.',','),
				'c_costo_compra' =>number_format(0,2,'.',','),
				'c_poliza_compra'=>number_format(0,2,'.',',')
			);

	echo json_encode($cab);*/
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'datoscab':
		datoscabAction();
		break;
	case 'infocompra':
		infocompraIndex();
		break;
}
?>
