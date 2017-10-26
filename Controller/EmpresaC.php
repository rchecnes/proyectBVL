<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);

	$sql = "SELECT em.*, se.nombre AS nom_sector, em.nombre AS nom_empresa FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) WHERE se.estado='1' AND em.estado='1' ORDER BY em.cz_fe_fin DESC, em.nemonico ASC";
	$empresas = mysqli_query($link, $sql);

	include('../View/Empresa/index.php');
}

function jqgridAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);

	$sql = "SELECT *, se.nombre AS nom_sector, em.nombre AS nom_empresa FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector)";
	$empresas = mysqli_query($link, $sql);

	include('../View/Empresa/jqgrid.php');
}

function newAction(){
	
	$titulo = "Nueva Empresa";

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Control/Combobox/Combobox.php');

	include('../View/Empresa/new.php');
}

function createAction(){
	
	include('../Config/Conexion.php');
	$link = getConexion();

	$max = "SELECT max(cod_emp)+1 AS max FROM empresa";
	$respmax = mysqli_query($link,$max);
	$rowmax = mysqli_fetch_array($respmax);

	//Obtenemos los datos
	$codigo   = $rowmax['max'];
	$nombre   = $_POST['nombre'];
	$nemonico = $_POST['nemonico'];
	$sector   = $_POST['sector'];
	$segmento = $_POST['segmento'];
	$moneda   = $_POST['moneda'];
	$estado   = (isset($_POST['estado']))?1:0;

	$sql  = "INSERT INTO empresa(cod_emp,nombre,nemonico,sector,segmento,moneda, estado) VALUES('$codigo','$nombre','$nemonico','$sector','$segmento','$moneda','$estado')";
	$resp = mysqli_query($link, $sql);

	//echo $insert;
	header("location:../Controller/EmpresaC.php?accion=index");
}


function editAction(){
	
	$titulo = "Editar Empresa";

	$codigo = $_GET['codigo'];

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Control/Combobox/Combobox.php');

	$sql = "SELECT *, em.nombre AS nom_empresa FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) WHERE cod_emp='$codigo'";
	//echo $sql;
	$resp = mysqli_query($link,$sql);
	$em = mysqli_fetch_array($resp);

	include('../View/Empresa/edit.php');
}

function updateAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$codigo   = $_POST['codigo'];
	$nombre   = $_POST['nombre'];
	$nemonico = $_POST['nemonico'];
	$sector   = $_POST['sector'];
	$segmento = $_POST['segmento'];
	$moneda   = $_POST['moneda'];
	$estado   = (isset($_POST['estado']))?1:0;

	$sql  = "UPDATE empresa SET nombre='$nombre', nemonico='$nemonico', cod_sector='$sector', segmento='$segmento', moneda='$moneda',estado='$estado' WHERE cod_emp='$codigo'";
	$resp = mysqli_query($link, $sql);

	//echo $insert;
	header("location:../Controller/EmpresaC.php?accion=index");
	
}


function deleteAction(){
	
	$codigo = $_GET['codigo'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "DELETE FROM empresa WHERE cod_emp='$codigo'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/EmpresaC.php?accion=index");
}

function setGetSector($sector){

	include('../Config/Conexion.php');
	$link   = getConexion();
	$sector = (str_replace(' ','', $sector)!='')?strtoupper($sector):'NO ESPECIFICA';

	$sql  = "SELECT * FROM sector WHERE UPPER(nombre)='$sector' LIMIT 1";
	$resp = mysqli_query($link, $sql);
	$wr   = mysqli_fetch_array($resp);

	if ($wr['cod_sector'] !='') {
		//Devolvemos el codigo sector
		return $wr['cod_sector'];
	}else{
		//Insertamos el sector
		$sqli    = "INSERT INTO sector(nombre,estado) VALUES('$sector',1)";
		$respi   = mysqli_query($link, $sqli);
		$new_cod = mysqli_insert_id($link);
		return $new_cod;
	}
}

function savImportedAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$dataBVL = json_decode($_POST['info'], true);

	$max = "SELECT max(cod_emp) AS max FROM empresa";
	$respmax = mysqli_query($link,$max);
	$rowmax = mysqli_fetch_array($respmax);
	$codigo = $rowmax['max'];

	if ($codigo =='') {
		$codigo = '999';
	}

	$del = "";
	$sql = "";

	foreach ($dataBVL as $key => $f) {


		$codigo    += 1 ;
		$nombre    = $f['emp'];
		$nemonico  = $f['nem'];
		$sector    = setGetSector($f['sec']);
		$segmento  = $f['seg'];
		$moneda    = $f['mon'];
		$estado    = 1;

		$del .= "'".$nemonimo."',";
		
		$sql .= "('$codigo','$nombre','$nemonico','$sector','$segmento','$moneda',$estado),";
	}

	if ($del !='' && $sql !='') {

		$delete = "DELETE FROM empresa WHERE nemonimo IN(".trim($del,',').")";
		//echo $delete."<br>";
		$respdel = mysqli_query($link,$delete);

		$insert = "INSERT INTO empresa (cod_emp,nombre,nemonico,cod_sector,segmento,moneda,estado) VALUES ".trim($sql,',').";";
		//echo $insert;
		//echo $insert."<br>";
		$resp    = mysqli_query($link,$insert);
	}
	
	echo "ok";
}

function showJqgrid(){

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
	
	include('../View/Empresa/jqgrid.php');
}

function getDataJqgridction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$rowsJq = $_GET['rows'];
	$pageJq = $_GET['page'];
	$sidxJq = $_GET['sidx'];
	$sordJq = $_GET['sord'];
	
	//Get record
	$sqlc   = "SELECT COUNT(*)AS record FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) LIMIT 1";
	$respc  = mysqli_query($link, $sqlc);
	$rowr   = mysqli_fetch_array($respc);
	$record = $rowr['record'];

	$limit = 1;
	if ($pageJq > 1) {
		$limit = ($pageJq-1)*$rowsJq;
	}

	//Registros
	$sql = "SELECT *, se.nombre AS nom_sector, em.nombre AS nom_empresa FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector) LIMIT $limit,10";
	$resp = mysqli_query($link, $sql);

	$group    = array();
	$empresas = array();

	while ($w = mysqli_fetch_array($resp)) {
		$empresas[]      = array(
			'cod_empresa'=> str_pad($w['cod_emp'], 8, '0', STR_PAD_LEFT),
			'nom_empresa'=> $w['nom_empresa'],
			'nemonico'  => $w['nemonico'],
			'nom_sector' => $w['nom_sector'],
			'segmento'   => $w['segmento'],
			'moneda'     => $w['moneda'],
			'acciones' => '<a href="../Controller/EmpresaC.php?accion=edit&codigo='.$w['cod_emp'].'" class="btn btn-default" role="button">Editar</a>&nbsp;<a href="../Controller/EmpresaC.php?accion=delete&codigo='.$w['cod_emp'].'" class="btn btn-danger" role="button">Eliminar</a>'
			);
			
	}

	$group['records'] = $record;
	$group['page']    = $pageJq;
	$group['total']   = $record;
	$group['rows']    = $empresas;
	

	echo json_encode($group);
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'new':
		newAction();
		break;
	case 'edit':
		editAction();
		break;
	case 'create':
		createAction();
		break;
	case 'delete':
		deleteAction();
		break;
	case 'update':
		updateAction();
		break;
	case 'savimported':
		savImportedAction();
		break;
	case 'jqgrid':
		showJqgrid();
		break;
	case 'jegjson':
		getDataJqgridction();
		break;
}
?>
