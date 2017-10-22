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

	$nemonico = $_POST['p_Nemonico'];
	$codemp = '';
	$anioini   = $_POST['anio_ini'];
	$mesini    = $_POST['mes_ini'];
	$aniofin   = $_POST['anio_fin'];
	$mesfin    = $_POST['mes_fin'];

	$data = get_remote_data("https://www.bvl.com.pe/web/guest/informacion-general-empresa?p_p_id=informaciongeneral_WAR_servicesbvlportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-2&p_p_col_count=1&_informaciongeneral_WAR_servicesbvlportlet_cmd=getListaHistoricoCotizaciones&_informaciongeneral_WAR_servicesbvlportlet_codigoempresa=$codemp&_informaciongeneral_WAR_servicesbvlportlet_nemonico=$nemonico&_informaciongeneral_WAR_servicesbvlportlet_tabindex=4&_informaciongeneral_WAR_servicesbvlportlet_jspPage=%2Fhtml%2Finformaciongeneral%2Fview.jsp","_informaciongeneral_WAR_servicesbvlportlet_anoini=$anioini&_informaciongeneral_WAR_servicesbvlportlet_mesini=$mesini &_informaciongeneral_WAR_servicesbvlportlet_anofin=$aniofin&_informaciongeneral_WAR_servicesbvlportlet_mesfin=$mesfin&_informaciongeneral_WAR_servicesbvlportlet_nemonicoselect=$nemonico");

	//$data = json_decode($data, true);
	var_dump($data);
	$sav_data = array();

	/*foreach ($data['data'] as $key => $v) {

		$sav_data[] = array(
						'f'=>$v['fecDt'],
						'a'=>$v['valOpen'],
						'c'=>'',
						'max'=>'',
						'min'=>'',
						'prd'=>'',
						'cn'=>$v['valVol'],
						'mn'=>$v['valAmt'],
						'fa'=>$v['fecTimp'],
						'ca'=>$v['valPts']
					);
	}*/

	//print_r($data['data']);
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
		$apertura        = (double)$f['a'];
		if ($apertura !='' && $apertura>0) {
			
			$cierre          = $f['c'];
			$maxima          = $f['max'];
			$minima          = $f['min'];
			$promedio        = $f['prd'];
			$cant_negociado  = (int)str_replace(',', '', $f['cn']);
			$monto_negociado = (float)str_replace(',','',$f['mn']);
			list($dia, $mes, $ano) = explode('/', $f['fa']);
			$fecha_anterior  = $ano.'-'.$mes.'-'.$dia;;
			$cierre_anterior = $f['ca'];

			//Actualizamos empres con la ultima cotizacion
			$upd_x_emp = "UPDATE empresa em SET em.cz_fe_fin='$fecha',em.cz_ci_fin='$cierre',em.cz_cn_fin='$cant_negociado',em.cz_mn_fin='$monto_negociado' WHERE em.nemonico='$cz_codemp'";
	        $respup    = mysqli_query($link,$upd_x_emp);
	        //Fin actualizar

			$del .= "'".$cod."',";
			
			$sql .= "('$cod','$cz_codemp','$fecha','$apertura','$cierre','$maxima','$minima','$promedio','$cant_negociado','$monto_negociado','$fecha_anterior','$cierre_anterior'),";
		}
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
	case 'importarmanual':
		importarManualAction();
		break;
	default:
		# code...
		break;
}