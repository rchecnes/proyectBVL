<?php
require_once('TiempoC.php');

function indexAction(){

	//header('location:../View/ObtenerCotizaHisV.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');
	include('../View/Cotizacion/index.php');
}

function savAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$dataBVL = json_decode($_POST['info'], true);

	$cz_codemp = $_POST['cz_codemp'];

	$del = "";
	$sql = "";

	foreach ($dataBVL as $key => $f) {

		list($dia, $mes, $ano) = explode('/', $f['f']);

		$cod             = $ano.$mes.$dia;
		$fecha           = $ano.'-'.$mes.'-'.$dia;
		$apertura        = $f['a'];
		$cierre          = $f['c'];
		$maxima          = $f['max'];
		$minima          = $f['min'];
		$promedio        = $f['prd'];
		$cant_negociado  = (int)str_replace(',', '', $f['cn']);
		$monto_negociado = (float)str_replace(',','',$f['mn']);
		list($dia, $mes, $ano) = explode('/', $f['fa']);
		$fecha_anterior  = $ano.'-'.$mes.'-'.$dia;;
		$cierre_anterior = $f['ca'];

		$del .= "'".$cod."',";
		
		$sql .= "('$cod','$cz_codemp','$fecha','$apertura','$cierre','$maxima','$minima','$promedio','$cant_negociado','$monto_negociado','$fecha_anterior','$cierre_anterior'),";
	}

	if ($del !='' && $sql !='') {

		$delete = "DELETE FROM cotizacion WHERE cz_cod IN(".trim($del,',').") AND cz_codemp='$cz_codemp'";
		
		$respdel = mysqli_query($link,$delete);

		$insert = "INSERT INTO cotizacion (cz_cod,cz_codemp,cz_fecha,cz_apertura,cz_cierre,cz_maxima,cz_minima,cz_promedio,cz_cantnegda,cz_montnegd,cz_fechant,cz_cierreant) VALUES ".trim($sql,',').";";
		
		$resp    = mysqli_query($link,$insert);
	}
	
	echo "ok";
}

function listarAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$empresa    = $_GET['empresa'];
	$fec_inicio = $_GET['fec_inicio'];
	$fec_fin    = $_GET['fec_fin'];
	$sector     = $_GET['sector'];
	$moneda    = $_GET['moneda'];


	$sql = "SELECT *, DATE_FORMAT(cz_fecha,'%d/%m/%Y')AS fecha_forma, DATE_FORMAT(cz_fechant,'%d/%m/%Y')AS fecha_formant
			FROM cotizacion cz
			INNER JOIN empresa e ON(cz.cz_codemp=e.nemonico)
			WHERE cz.cz_fecha BETWEEN '$fec_inicio' AND '$fec_fin'";
	
	if ($empresa !='') {
		$sql .= " AND cz.cz_codemp='$empresa'";
	}
	if ($sector !='' && $sector !='Todos') {
		$sql .= " AND e.cod_sector='$sector'";
	}
	if ($moneda !='') {
		$sql .= " AND e.moneda LIKE '%$moneda%'";
	}

	$sql .= " ORDER BY cz_fecha DESC";

	//echo $sql;
	$cotizacion = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($cotizacion);

	include('../View/Cotizacion/listar.php');
}

/*function buscarEmpresaAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$sector = '';//($_GET['sector']!='')?" AND cod_sector= '".$_GET['sector']."'":"";
	$moneda = '';//($_GET['moneda']!='')?" AND moneda LIKE '%".$_GET['moneda']."%'":"";
	$term   = $_GET['term'];

	$sql = "SELECT *, CONCAT(nemonico,' - ',nombre,' - ', moneda)AS label, CONCAT(nemonico,' - ',nombre)AS value FROM empresa WHERE (nombre LIKE '%$term%' OR nemonico LIKE '%$term%') $sector $moneda";
	//echo $sql;
	$resp = mysqli_query($link,$sql);

	$empresa = array();

	while ($row = mysqli_fetch_array($resp)) {
		$empresa[] = $row;

	}

	echo json_encode($empresa);
}*/

function buscarEmpresaAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$sector = ($_GET['sector']!='')?" AND cod_sector ='".$_GET['sector']."'":"";
	$moneda = ($_GET['moneda']!='')?" AND moneda LIKE '%".$_GET['moneda']."%'":"";
	//$term   = $_GET['term'];

	$sql = "SELECT * FROM empresa WHERE cod_emp!='' $sector $moneda";
	$resp = mysqli_query($link,$sql);

	//$empresa = "<option value=''>[Ninguno]</option>";
	$empresa = "";

	while ($row = mysqli_fetch_array($resp)) {
		$empresa .= "<option value='".$row['nemonico']."'>".$row['nemonico'].' - '.$row['nombre'].' - '.$row['moneda']."</option>";

	}

	if ($empresa=='') {
		$empresa = "<option value=''>[Sin Resultado]</option>";
	}

	echo $empresa;
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'sav':
		savAction();
		break;
	case 'listar':
		listarAction();
		break;
	case 'busemp':
		buscarEmpresaAction();
		break;
	
	default:
		# code...
		break;
}