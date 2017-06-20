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

function getComision($tipo){

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "SELECT * FROM comision WHERE concep='$tipo' LIMIT 1";
	$resp = mysqli_query($link,$sql);
	$r    = mysqli_fetch_array($resp);

	return array(
		'COM_SAB'    =>$r['comis_neta_sab'],
		'BASE_SAB'   =>5000,
		'MIN_SAB'    =>50,
		'COM_BVL'    =>$r['retrib_bvl'],
		'COM_SMV'    =>$r['contrib_smv'],
		'F_GARANT'   =>$r['fondo_garant'],
		'VAL_IGV'    =>$r['igv'],
		'F_LIQUI'    =>$r['fondo_liq'],
		'MIN_CAVAL'  =>5,
		'BASE_CAVAL' =>12210.01,
		'COM_CAVAL' =>$r['retrib_caval_iclv']
	);
}

function datoscabAction(){
	
	include('../Config/Conexion.php');
	$link = getConexion();

	$com = getComision('contado');

	$cod_emp         = $_GET['cod_emp'];
	$tipo            = $_GET['tipo'];
	$monto_estimado  = ($_GET['monto_estimado']!='' && (float)$_GET['monto_estimado']>0)?(float)$_GET['monto_estimado']:0;
	$precio_unitario = ($_GET['precio_unitario']!='' && (float)$_GET['precio_unitario']>0)?(float)$_GET['precio_unitario']:0;

	$cz_cn_fin = 5000.00;
	$cz_ci_fin = 0;

	if ($tipo == 'uno') {

		$sql  = "SELECT * FROM empresa WHERE nemonico='$cod_emp' LIMIT 1";
		$resp = mysqli_query($link,$sql);
		$r    = mysqli_fetch_array($resp);

		//$cz_cn_fin = ($r['cz_cn_fin'] > 0)?$r['cz_cn_fin']:0;//Monto estimado
		$cz_ci_fin = ($r['cz_ci_fin'] > 0)?$r['cz_ci_fin']:0;//Precio unitario ultima cotizacion
	}elseif ($tipo == 'dos') {

		$cz_cn_fin = $monto_estimado;
		$cz_ci_fin = $precio_unitario;
		
	}
	
	$cant_acc = ($cz_cn_fin > 0 && $cz_ci_fin>0)?ceil($cz_cn_fin/$cz_ci_fin):0;
	$mont_neg = $cz_ci_fin*$cant_acc;

	//VARIABLES COMPRAS
	//:::::::::::::::::
	$c_comision_sab = ($mont_neg>$com['BASE_SAB'])?$mont_neg*($com['COM_SAB']/100):$com['MIN_SAB'];
	$c_cuota_bvl    = $mont_neg*($com['COM_BVL']/100);
	$c_f_garantia   = $mont_neg*($com['F_GARANT']/100);
	$c_cavali       = 0;
	if ($mont_neg <= $com['BASE_CAVAL']) {
		if ($mont_neg*($com['COM_CAVAL']/100)<$com['MIN_CAVAL']) {$c_cavali=$com['MIN_CAVAL'];}else{$c_cavali=$mont_neg*($com['COM_CAVAL']/100);}
	}
	$c_f_liquidacion = ($mont_neg*($com['F_LIQUI']/100)<1)?0:$mont_neg*($com['F_LIQUI']/100);
	$c_compra_total  = $c_comision_sab +$c_cuota_bvl+$c_f_garantia+$c_cavali+$c_f_liquidacion;
	$c_igv           = $c_compra_total*($com['VAL_IGV']/100);
	$c_compra_smv    = ($mont_neg+$c_compra_total+$c_igv)*($com['COM_SMV']/100);
	$c_costo_compra  = $c_compra_total+$c_igv+$c_compra_smv;
	$c_poliza_compra = $c_costo_compra+$mont_neg;

	$info = array(
				//CABECERA
				'mont_est'=>number_format($cz_cn_fin,2,'.',''),
				'pre_unit'=>number_format($cz_ci_fin,2,'.',''),
				'cant_acc'=>number_format($cant_acc,0,'.',','),				
				'mont_neg'=>number_format($mont_neg,2,'.',','),
				//COMPRA
				'c_comision_sab' =>number_format($c_comision_sab,2,'.',''),
				'c_cuota_bvl'    =>number_format($c_cuota_bvl,2,'.',','),
				'c_f_garantia'   =>number_format($c_f_garantia,2,'.',','),
				'c_cavali'       =>number_format($c_cavali,2,'.',','),
				'c_f_liquidacion'=>number_format($c_f_liquidacion,2,'.',','),
				'c_compra_total' =>number_format($c_compra_total,2,'.',','),
				'c_igv'          =>number_format($c_igv,2,'.',','),
				'c_compra_smv'   =>number_format($c_compra_smv,2,'.',','),
				'c_costo_compra' =>number_format($c_costo_compra,2,'.',','),
				'c_poliza_compra'=>number_format($c_poliza_compra,2,'.',',')
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
