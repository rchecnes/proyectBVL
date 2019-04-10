<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../View/AnalisisDeposito/index.php');
}

function mostrarAction(){
    include('../Config/Conexion.php');
	$link = getConexion();
	$dp_moneda = $_GET['dp_moneda'];
	$dp_valor = $_GET['dp_valor'];
	$dp_plazo = $_GET['dp_plazo'];
	$dp_empresa = $_GET['dp_empresa'];
	
	//Los x primeros empresas
	$sqlx = "SELECT * FROM historico_deposito_plazo dh 
	INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id AND de.dp_fecha_imcs=dh.dh_fecha) 
	WHERE dh.dh_stat='1' 
	AND de.dp_stat='1'
	AND dh.dh_fsd='S'";

	if($dp_plazo!=''){
		$sqlx .= " AND $dp_plazo>=dh.dh_plazo_d AND $dp_plazo<=dh.dh_plazo_h";
	}
	if($dp_moneda!=''){
		$sqlx .= " AND de.dp_moneda='$dp_moneda'";
	}
	if($dp_valor!=''){
		$sqlx .= " AND $dp_valor>=dh.dh_sal_prom_d AND $dp_valor<=dh.dh_sal_prom_h";
	}
	$sqlx .= " GROUP BY dh.dh_emp_id";
	$sqlx .= " ORDER BY dh.dh_tea DESC";
	$sqlx .= " LIMIT 1,$dp_empresa";

	

	/*$sqlx = "SELECT * FROM historico_deposito_plazo dh 
	INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id AND de.dp_fecha_imcs=dh.dh_fecha) 
	WHERE dh.dh_stat='1' 
	AND de.dp_stat='1'
	AND dh.dh_fsd='S'";

	if($dp_plazo!=''){
		$sqlx .= " AND $dp_plazo>=dh.dh_plazo_d AND $dp_plazo<=dh.dh_plazo_h";
	}
	if($dp_moneda!=''){
		$sqlx .= " AND de.dp_moneda='$dp_moneda'";
	}
	if($dp_valor!=''){
		$sqlx .= " AND $dp_valor>=dh.dh_sal_prom_d AND $dp_valor<=dh.dh_sal_prom_h";
	}

	$sqlx .= " ORDER BY dh.dh_tea DESC";*/

	echo $sqlx;

	include('../View/AnalisisDeposito/mostrar.php');
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
        break;
    case 'mostrar':
        mostrarAction();
	default:
		# code...
		break;
}