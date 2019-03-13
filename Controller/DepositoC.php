<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	//include('../Control/Combobox/Combobox.php');
	include('../View/Deposito/index.php');
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	default:
		# code...
		break;
}