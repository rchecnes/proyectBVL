<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../View/DepositoPlazo/index.php');
}

function listarAction(){
	include('../Config/Conexion.php');
	$link = getConexion();

	$sql = "SELECT * FROM empresa_deposito_plazo WHERE dp_stat='1'";
	$dp_empresa = mysqli_query($link, $sql);
	$nro_reg = 0;
	if ($dp_empresa){
		$nro_reg = mysqli_num_rows($dp_empresa);
	}

	include('../View/DepositoPlazo/listar.php');
}

function importarEmpresaAction(){

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Model/DepositoPlazoM.php');

	$dp_moneda = $_GET['dp_moneda'];
	$dp_valor = $_GET['dp_valor'];
	$dp_plaza = $_GET['dp_plaza'];
	$dp_ubicacion = $_GET['dp_ubicacion'];
	$dp_correo = $_GET['dp_correo'];

	$data = importaEmpresaDepositoPlazo($dp_moneda, $dp_valor, $dp_plaza, $dp_ubicacion, $dp_correo);

	if($data['status']=='success'){

		foreach ($data['data']['aaData'] as $key => $fila) {
			
			$sqlin = "INSERT INTO empresa_deposito_plazo(pd_emp_id,pd_nodo,pd_nomb,pd_logo)VALUES('".$fila[0]."','".$fila[1]."','".$fila[4]."','".$fila[2]."')";
			mysqli_query($link, $sqlin);
		}
	}
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