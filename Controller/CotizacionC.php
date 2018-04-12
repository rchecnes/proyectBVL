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

	$nemonico = $_POST['p_Nemonico'];
	$codemp = '';
	$anioini   = $_POST['anio_ini'];
	$mesini    = $_POST['mes_ini'];
	$aniofin   = $_POST['anio_fin'];
	$mesfin    = $_POST['mes_fin'];

	/*$data = get_remote_data("https://www.bvl.com.pe/web/guest/informacion-general-empresa?p_p_id=informaciongeneral_WAR_servicesbvlportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-2&p_p_col_count=1&_informaciongeneral_WAR_servicesbvlportlet_cmd=getListaHistoricoCotizaciones&_informaciongeneral_WAR_servicesbvlportlet_codigoempresa=$codemp&_informaciongeneral_WAR_servicesbvlportlet_nemonico=$nemonico&_informaciongeneral_WAR_servicesbvlportlet_tabindex=4&_informaciongeneral_WAR_servicesbvlportlet_jspPage=%2Fhtml%2Finformaciongeneral%2Fview.jsp","_informaciongeneral_WAR_servicesbvlportlet_anoini=$anioini&_informaciongeneral_WAR_servicesbvlportlet_mesini=$mesini&_informaciongeneral_WAR_servicesbvlportlet_anofin=$aniofin&_informaciongeneral_WAR_servicesbvlportlet_mesfin=$mesfin&_informaciongeneral_WAR_servicesbvlportlet_nemonicoselect=$nemonico");

	$new_data = prepararData($data);

	if ($new_data == '') {
		echo "No se puede acceder";
	}else{

		$res = savAction($link,$new_data, $nemonico);
		
		echo $res;
	}*/

	$fecha_inicio = str_replace("-","",$_POST['fecha_inicio']);
	$fecha_fin = str_replace("-","",$_POST['fecha_fin']);
	$url = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$fecha_inicio&fec_fin=$fecha_fin&nemonico=$nemonico";


    $html = file_get_html($url);

    $new_data = getPrepareDataAntiguo($nemonico, $html);

    //$new_data = ordenarArray($new_data,'f','ASC');
    //var_dump($new_data);
    //exit();

    if (count($new_data)>0) {

        $res = savCatizaAntiguo($link, $new_data, $nemonico);
    }

    unset($new_data);
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