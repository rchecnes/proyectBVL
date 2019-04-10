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

	$dh_fecha = "2019-03-30";
	
	//CONDICION
	$sqlwhere = " WHERE dh.dh_stat='1'";
	$sqlwhere .= " AND de.dp_stat='1'";
	$sqlwhere .= " AND dh.dh_fsd='S'";
	if($dp_plazo!=''){
		$sqlwhere .= " AND $dp_plazo>=dh.dh_plazo_d AND $dp_plazo<=dh.dh_plazo_h";
	}
	if($dp_moneda!=''){
		$sqlwhere .= " AND de.dp_moneda='$dp_moneda'";
	}
	if($dp_valor!=''){
		$sqlwhere .= " AND $dp_valor>=dh.dh_sal_prom_d AND $dp_valor<=dh.dh_sal_prom_h";
	}

	//Los x primeros empresas
	$sqlxx = "SELECT *,MAX(dh.dh_tea)AS max_tea FROM historico_deposito_plazo dh 
	INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id AND dh.dh_fecha='$dh_fecha')";//de.dp_fecha_imcs
	$sqlxx .= $sqlwhere." GROUP BY dh.dh_emp_id";
	$sqlxx .= " ORDER BY max_tea DESC";
	$sqlxx .= " LIMIT 0,$dp_empresa";
	$resx = mysqli_query($link, $sqlxx);

	$dh_emp_id = "";
	while($x = mysqli_fetch_array($resx)){
		$dh_emp_id .= $x['dh_emp_id'].",";
	}
	$dh_emp_id = trim($dh_emp_id,',');
	
	//Ahora obtenemos informacion de x empresa filtradas anteriormente
	$sqlhx = "SELECT * FROM historico_deposito_plazo dh 
	INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id AND dh.dh_fecha='$dh_fecha')";//de.dp_fecha_imcs
	$sqlhx .= $sqlwhere." AND dh.dh_emp_id IN($dh_emp_id)";
	$sqlhx .= " ORDER BY dh.dh_emp_id ASC";
	$reshx = mysqli_query($link, $sqlhx);
	
	$emp_tasa = array();
	$contador = 0;
	$cod_emp = "";
	while($h = mysqli_fetch_array($reshx)){
		if($contador == 0){
			$cod_emp = $h['dh_emp_id'];
		}

		/*if($h['dh_emp_id'] != $cod_emp){
			$emp_tasa[$h['dh_emp_id']] = 
		}else{

		}*/

		$cod_emp = $h['dh_emp_id'];
	}

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