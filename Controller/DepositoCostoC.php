<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../View/DepositoCosto/index.php');
}

function listarAction(){
	include('../Config/Conexion.php');
	$link = getConexion();

	$dp_moneda = $_GET['dp_moneda'];
	$dp_valor = $_GET['dp_valor'];
	$dp_plazo_d = $_GET['dp_plazo_d'];
	$dp_plazo_h = $_GET['dp_plazo_h'];
	$dp_ubicacion = $_GET['dp_ubicacion'];
	$dp_last_update = $_GET['dp_last_update'];

	$sql = "SELECT * FROM historico_deposito_plazo dh
			INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id)
			WHERE dh.dh_stat='1' AND de.dp_stat='1'";
	if($dp_plazo_d!=''){
		//$sql .= " AND $dp_plazo>=dh.dh_plazo_d AND $dp_plazo<=dh.dh_plazo_h";
		$sql .= " AND dh.dh_plazo_d>=$dp_plazo_d";
	}
	if($dp_plazo_h!=''){
		$sql .= " AND dh.dh_plazo_h<=$dp_plazo_h";
	}
	if($dp_moneda!=''){
		$sql .= " AND de.dp_moneda='$dp_moneda'";
	}
	if($dp_ubicacion!=''){
		$sql .= " AND de.dp_ubig='$dp_ubicacion'";
	}
	if($dp_valor!=''){
		$sql .= " AND $dp_valor>=dh.dh_sal_prom_d AND $dp_valor<=dh.dh_sal_prom_h";
	}
	if($dp_last_update!=''){
		$sql .= " AND dh.dh_last_update='$dp_last_update'";
	}

	
	$sql .= " ORDER BY dh.dh_tea DESC";

	//echo $sql;
	$dp_historico = mysqli_query($link, $sql);
	$nro_reg = 0;
	if ($dp_historico){
		$nro_reg = mysqli_num_rows($dp_historico);
	}

	include('../View/DepositoCosto/listar.php');
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'listar':
		listarAction();
		break;
	default:
		# code...
		break;
}