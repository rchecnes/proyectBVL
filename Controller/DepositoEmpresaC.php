<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	//Buscamos la ultima fecha de actualizacion
	$sqlfa = "SELECT dh_last_update FROM historico_deposito_plazo WHERE dh_stat=1 GROUP BY dh_last_update ORDER BY dh_last_update DESC";
	$resfa = mysqli_query($link, $sqlfa);

	include('../View/DepositoEmpresa/index.php');
}

function listarAction(){
	include('../Config/Conexion.php');
	$link = getConexion();

	$sql = "SELECT * FROM empresa_deposito_plazo WHERE dp_stat='1' ORDER BY dp_nomb_emp ASC";
	$dp_empresa = mysqli_query($link, $sql);
	$nro_reg = 0;
	if ($dp_empresa){
		$nro_reg = mysqli_num_rows($dp_empresa);
	}

	include('../View/DepositoEmpresa/listar.php');
}

function importarEmpresaAction(){

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Model/DepositoEmpresaM.php');

	$dp_moneda    = $_GET['dp_moneda'];
	$dp_valor     = $_GET['dp_valor'];
	$dp_plaza     = $_GET['dp_plaza'];
	$dp_ubicacion = $_GET['dp_ubicacion'];
	$dp_correo    = $_GET['dp_correo'];

	$data = importaEmpresaDepositoPlazo($dp_moneda, $dp_valor, $dp_plaza, $dp_ubicacion, $dp_correo);

	if($data['status']=='success'){

		foreach ($data['data']['aaData'] as $key => $fila) {

			$sqlin = "INSERT INTO empresa_deposito_plazo(dp_emp_id,dp_nodo,dp_nomb_emp,dp_nomb_prod,dp_logo,dp_ubig,dp_moneda,dp_fsd)VALUES('".$fila[0]."','".$fila[1]."','".$fila[4]."','".$fila[13]."','".$fila[2]."','$dp_ubicacion','$dp_moneda','".$fila[16]."')";
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