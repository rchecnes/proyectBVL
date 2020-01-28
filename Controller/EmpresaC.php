<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);

	$sql = "SELECT *, se.nombre AS nom_sector FROM empresa em
	LEFT JOIN sector se ON(em.sec_cod=se.cod_sector)
	WHERE em.emp_stdo='1' ORDER BY em.emp_nomb ASC";
	$empresas = mysqli_query($link, $sql);

	include('../View/Empresa/index.php');
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

	$max = "SELECT max(emp_cod) AS max FROM empresa";
	$respmax = mysqli_query($link,$max);
	$rowmax = mysqli_fetch_array($respmax);

	//Obtenemos los datos
	$emp_cod   = ($rowmax['max']!='')?$rowmax['max']+1:1000;
	$emp_nomb   = $_POST['emp_nomb'];
	$sec_cod   = $_POST['sec_cod'];
	$emp_stdo   = (isset($_POST['emp_stdo']))?1:0;

	$sql  = "INSERT INTO empresa(emp_cod,emp_nomb,sec_cod,emp_stdo) VALUES('$emp_cod','$emp_nomb','$sec_cod','$emp_stdo')";
	$resp = mysqli_query($link, $sql) or die(mysqli_error($link));

	header("location:../Controller/EmpresaC.php?accion=index");
}


function editAction(){
	
	$titulo = "Editar Empresa";

	$emp_cod = $_GET['emp_cod'];

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Control/Combobox/Combobox.php');

	$sql = "SELECT * FROM empresa 
			WHERE emp_cod='$emp_cod'";
	$resp = mysqli_query($link,$sql);
	$em = mysqli_fetch_array($resp);

	include('../View/Empresa/edit.php');
}

function updateAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$emp_cod   = $_POST['emp_cod'];
	$emp_nomb   = $_POST['emp_nomb'];
	$sec_cod = $_POST['sec_cod'];
	$emp_stdo   = (isset($_POST['emp_stdo']))?1:0;

	$sql  = "UPDATE empresa SET emp_nomb='$emp_nomb', emp_stdo='$emp_stdo', sec_cod='$sec_cod' WHERE emp_cod='$emp_cod'";
	$resp = mysqli_query($link, $sql);

	//echo $insert;
	header("location:../Controller/EmpresaC.php?accion=index");
}


function deleteAction(){
	
	$emp_cod = $_GET['emp_cod'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "DELETE FROM empresa WHERE emp_cod='$emp_cod'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/EmpresaC.php?accion=index");
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
}
?>
