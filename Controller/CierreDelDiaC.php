<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Control/Combobox/Combobox.php');
	include('../View/CierreDelDia/index.php');
}

function listarAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$fecha        = $_GET['fecha'];
	$acciones_hoy = $_GET['acciones_hoy'];

	$sql = "SELECT
			e.nombre AS empresa,
			e.nemonico,s.nombre AS sector,
			e.segmento,e.moneda,
			cd.*,
			DATE_FORMAT(cd.cd_cz_fant,'%d/%m/%Y')AS cz_fant
			FROM empresa e
			INNER JOIN sector s ON(e.cod_sector=s.cod_sector)
			LEFT JOIN cotizacion_del_dia cd ON(e.cod_emp=cd.cd_cod_emp)
			WHERE s.estado='1'
			AND cd.cd_fecha='$fecha'
			AND e.estado='1'";

	if ($acciones_hoy == 1) {
		$sql .= " AND (cd.cd_ng_nop > 1 OR cd.cd_pr_com > 0 OR cd.cd_pr_ven > 0)";
	}

	$sql .= " ORDER BY e.nombre ASC";

	$res = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($res);

	include('../View/CierreDelDia/listar.php');
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'listar':
		listarAction();
		break;
	default:
		# code...
		break;
}