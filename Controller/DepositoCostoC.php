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

	$sql = "SELECT * FROM historico_deposito_plazo dh
			INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id) WHERE de.dp_stat='1' AND dh.dh_stat='1'";
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