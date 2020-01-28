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
	$acc_cotizado = $_POST['acc_cotizado'];

	$sql = "SELECT ne.nemonico FROM nemonico ne
			LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod)
            WHERE ne.estado='1'";

    if ($nemonico_ori !='') {
    	$sql .= " AND ne.nemonico='$nemonico_ori'";
    }
    if ($sector !='') {
    	$sql .= " AND em.sec_cod='$sector'";
    }
    if ($moneda !='') {
    	$sql .= " AND ne.moneda='$moneda'";
    }
    if ($acc_cotizado ==1) {
    	$sql .= " AND ne.nemonico IN(SELECT s_cd.cd_nemo FROM cotizacion_del_dia s_cd WHERE s_cd.cd_cod='$fecha_inicio' AND s_cd.cd_ng_nop > 0)";
    }
	
    $rescotiza = mysqli_query($link, $sql);

	$c = 0;
    while ($r = mysqli_fetch_assoc($rescotiza)) {
    	
    	$nemonico = $r['nemonico'];

		//$arrContextOptions=array(
		//	"ssl"=>array(
		//		 "verify_peer"=>false,
		//		 "verify_peer_name"=>false,
		//	),
		//); 
		//$url  = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$fecha_inicio&fec_fin=$fecha_fin&nemonico=$nemonico";	 
	  	//$html = file_get_contents($url, false, stream_context_create($arrContextOptions));

		$url  = "http://www.bvl.com.pe/jsp/cotizacion.jsp?fec_inicio=$fecha_inicio&fec_fin=$fecha_fin&nemonico=$nemonico";	 
		$html = file_get_contents_curl($url);

    	$new_data = getPrepareDataAntiguo($nemonico, $html);

    	if (count($new_data)>1) { //Cuando se quiere importar varios dias
    		$new_data = ordenarArray($new_data,'f','ASC');
    	}
    	
    	if (count($new_data)>0) {

	        $res = savCatizaAntiguo($link, $new_data, $nemonico);
	        $c ++;
	    }

	    unset($new_data);
    }

    echo json_encode(array('cant_imp'=>$c));
    
}

function listarAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$nemonico    = $_GET['nemonico'];
	$fec_inicio = $_GET['fec_inicio'];
	$fec_fin    = $_GET['fec_fin'];
	$sector     = $_GET['sector'];
	$moneda     = $_GET['moneda'];
	$origen     = $_GET['origen'];


	/*$sql = "SELECT *, DATE_FORMAT(cz_fecha,'%d/%m/%Y')AS fecha_forma, DATE_FORMAT(cz_fechant,'%d/%m/%Y')AS fecha_formant
			FROM cotizacion cz
			INNER JOIN empresa e ON(cz.cz_codemp=e.nemonico)
			WHERE cz.cz_fecha BETWEEN '$fec_inicio' AND '$fec_fin'";*/
	$sql = "SELECT cz.*,e.*, DATE_FORMAT(cz_fecha,'%d/%m/%Y')AS fecha_forma, DATE_FORMAT(cz_fechant,'%d/%m/%Y')AS fecha_formant, cd.cd_ng_nop, cd.cd_pr_com,cd.cd_pr_ven
			FROM cotizacion cz
			LEFT JOIN nemonico ne ON(cz.cz_nemo=ne.nemonico)
			LEFT JOIN empresa e ON(ne.emp_cod=e.emp_cod)
			LEFT JOIN cotizacion_del_dia cd ON (cz.cz_cod=cd.cd_cod AND cz.cz_nemo=cd.cd_nemo)
			WHERE cz.cz_fecha BETWEEN '$fec_inicio' AND '$fec_fin'";
	
	if ($nemonico !='') {
		$sql .= " AND cz.cz_nemo='$nemonico'";
	}
	if ($sector !='' && $sector !='Todos') {
		$sql .= " AND e.sec_cod='$sector'";
	}
	if ($moneda !='') {
		$sql .= " AND ne.moneda LIKE '%$moneda%'";
	}

	if ($origen=='one') {
		
		$sql .= " ORDER BY cz.cz_fecha DESC";
	}elseif ($origen=='two') {

		$sql .= " ORDER BY cz.cz_nemo ASC";
	}
	

	//echo $sql;
	$cotizacion = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($cotizacion);

	include('../View/Cotizacion/listar.php');
}

function buscarNemonicoAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$sector = ($_GET['sector']!='')?" AND em.sec_cod ='".$_GET['sector']."'":"";
	$moneda = ($_GET['moneda']!='')?" AND ne.moneda LIKE '%".$_GET['moneda']."%'":"";
	//$term   = $_GET['term'];

	$sql = "SELECT * FROM nemonico ne
			LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod)
			WHERE ne.ne_cod!='' $sector $moneda";
	$resp = mysqli_query($link,$sql);

	//$empresa = "<option value=''>[Ninguno]</option>";
	$nemonico = "";

	while ($row = mysqli_fetch_array($resp)) {
		$nemonico .= "<option value='".$row['nemonico']."'>".$row['nemonico'].' - '.$row['emp_nomb'].' - '.$row['moneda']."</option>";

	}

	if ($nemonico == '') {
		$nemonico = "<option value=''>[Sin Resultado]</option>";
	}

	echo $nemonico;
}
function buscarEmpresaTodosAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$sector = ($_GET['sector']!='')?" AND em.sec_cod ='".$_GET['sector']."'":"";
	$moneda = ($_GET['moneda']!='')?" AND ne.moneda LIKE '%".$_GET['moneda']."%'":"";
	//$term   = $_GET['term'];

	$sql = "SELECT * FROM nemonico ne
			LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod)
			WHERE ne.ne_cod!='' $sector $moneda";
	$resp = mysqli_query($link,$sql);

	//$empresa = "<option value=''>[Ninguno]</option>";
	$empresa = "<option value=''>Todos</option>";

	while ($row = mysqli_fetch_array($resp)) {
		$empresa .= "<option value='".$row['nemonico']."'>".$row['nemonico'].' - '.$row['emp_nomb'].' - '.$row['moneda']."</option>";

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
	case 'bunemo':
		buscarNemonicoAction();
		break;
	case 'bunemotodos':
		buscarEmpresaTodosAction();
		break;
	case 'importarmanual':
		importarManualAction();
		break;
	default:
		# code...
		break;
}