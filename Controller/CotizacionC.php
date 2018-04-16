<?php
require_once('TiempoC.php');

function indexAction(){

	//header('location:../View/ObtenerCotizaHisV.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');
	include('../View/Cotizacion/index.php');
}

function importarManualAction(){

	require_once("../Model/CotizaGrupoM.php");
	require_once("../Model/ImportarAntiguoM.php");
	include("../Util/simple_html_dom_php5.6.php");

	include('../Config/Conexion.php');
	$link = getConexion();

	$nemonico_ori = $_POST['p_Nemonico'];
	$fecha_inicio = str_replace("-","",$_POST['fecha_inicio']);
	$fecha_fin    = str_replace("-","",$_POST['fecha_fin']);
	$sector       = $_POST['sector'];
	$moneda       = $_POST['moneda'];

	$sql = "SELECT em.nemonico FROM empresa em
            LEFT JOIN sector se ON(em.cod_sector=se.cod_sector)
            WHERE se.estado='1'
            AND em.estado='1'";

    if ($nemonico_ori !='') {
    	$sql .= " AND em.nemonico='$nemonico_ori'";
    }
    if ($sector !='') {
    	$sql .= " AND em.cod_sector='$sector'";
    }
    if ($nemonico_ori !='') {
    	$sql .= " AND em.moneda='$moneda'";
    }

    $res = mysqli_query($link, $sql);

    while ($r = mysqli_fetch_array($res)) {
    	
    	$nemonico = $r['nemonico'];

    	$url  = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$fecha_inicio&fec_fin=$fecha_fin&nemonico=$nemonico";

    	$html = file_get_html($url);

    	$new_data = getPrepareDataAntiguo($nemonico, $html);

    	if (count($new_data)>0) {

	        $res = savCatizaAntiguo($link, $new_data, $nemonico);
	    }

	    unset($new_data);
    }
    
}

function listarAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$empresa    = $_GET['empresa'];
	$fec_inicio = $_GET['fec_inicio'];
	$fec_fin    = $_GET['fec_fin'];
	$sector     = $_GET['sector'];
	$moneda     = $_GET['moneda'];
	$origen     = $_GET['origen'];


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

	if ($origen=='one') {
		
		$sql .= " ORDER BY cz.cz_fecha DESC";
	}elseif ($origen=='two') {

		$sql .= " ORDER BY cz.cz_codemp ASC";
	}
	

	//echo $sql;
	$cotizacion = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($cotizacion);

	include('../View/Cotizacion/listar.php');
}

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
function buscarEmpresaTodosAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$sector = ($_GET['sector']!='')?" AND cod_sector ='".$_GET['sector']."'":"";
	$moneda = ($_GET['moneda']!='')?" AND moneda LIKE '%".$_GET['moneda']."%'":"";
	//$term   = $_GET['term'];

	$sql = "SELECT * FROM empresa WHERE cod_emp!='' $sector $moneda";
	$resp = mysqli_query($link,$sql);

	//$empresa = "<option value=''>[Ninguno]</option>";
	$empresa = "<option value=''>Todos</option>";

	while ($row = mysqli_fetch_array($resp)) {
		$empresa .= "<option value='".$row['nemonico']."'>".$row['nemonico'].' - '.$row['nombre'].' - '.$row['moneda']."</option>";

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
	case 'busemptodos':
		buscarEmpresaTodosAction();
		break;
	case 'importarmanual':
		importarManualAction();
		break;
	default:
		# code...
		break;
}