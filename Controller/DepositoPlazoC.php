<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	include('../Model/DepositoPlazoM.php');
	$link = getConexion();

	//include('../Control/Combobox/Combobox.php');
	include('../View/DepositoPlazo/index.php');

	//$data = importaEmpresaDepositoPlazo();
    
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	default:
		# code...
		break;
}