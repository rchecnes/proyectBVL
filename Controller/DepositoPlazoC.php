<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	include('../Model/DepositoPlazoM.php');
	$link = getConexion();

	//include('../Control/Combobox/Combobox.php');
	include('../View/Deposito/index.php');

	//$data = importaEmpresaDepositoPlazo();
    //var_dump($data);
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	default:
		# code...
		break;
}