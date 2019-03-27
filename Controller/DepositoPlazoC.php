<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	include('../Model/DepositoPlazoM.php');
	$link = getConexion();

	//include('../Control/Combobox/Combobox.php');
	include('../View/DepositoPlazo/index.php');

	//$data = importaEmpresaDepositoPlazo();
    //var_dump($data);
}

function listarAction(){
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../View/DepositoPlazo/listar.php');
}

function importarEmpresaAction(){

}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'listar':
		listarAction();
		break;
	case 'importarEmpresa':
		importarEmpresaAction();
		break;
	default:
		# code...
		break;
}