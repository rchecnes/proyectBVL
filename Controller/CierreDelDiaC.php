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
			em.emp_nomb AS empresa,
			ne.nemonico,
			se.nombre AS sector,
			ne.segmento,
			ne.moneda,
			cd.*,
			DATE_FORMAT(cd.cd_cz_fant,'%d/%m/%Y')AS cz_fant
			FROM nemonico ne
			LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod)
			LEFT JOIN sector se ON(em.sec_cod=se.cod_sector)
			INNER JOIN cotizacion_del_dia cd ON(ne.nemonico=cd.cd_nemo)
			WHERE cd.cd_fecha='$fecha'
			AND ne.estado='1'";

	if ($acciones_hoy == 1) {
		$sql .= " AND (cd.cd_ng_nop > 0 OR cd.cd_pr_com > 0 OR cd.cd_pr_ven > 0)";
	}

	$sql .= " ORDER BY ne.nombre ASC";

	$res = mysqli_query($link, $sql);
	//echo $sql;
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