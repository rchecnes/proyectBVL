<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Model/PortafolioM.php');

	$cod_user  = $_SESSION['cod_user'];

	
	/*$sql = "SELECT *,DATE_FORMAT(por_fech,'%d/%m/%Y')AS por_fech_new FROM empresa_portafolio ep
			INNER JOIN empresa em ON(ep.cod_emp=em.cod_emp)
			WHERE ep.cod_user='$cod_user' ORDER BY em.nemonico ASC";*/

	$sql = "SELECT *,
			DATE_FORMAT(por_fech,'%d/%m/%Y')AS por_fech_new,
			SUM(ep.por_mont_est)AS por_mont_est_new,
			SUM(ep.por_cant)AS por_cant_new
			FROM empresa_portafolio ep
			INNER JOIN empresa em ON(ep.cod_emp=em.cod_emp)
			WHERE ep.cod_user='$cod_user' GROUP BY em.nemonico ORDER BY em.nemonico ASC";

	$portafolio = mysqli_query($link, $sql);

	$cant_reg_port = mysqli_num_rows($portafolio);

	include('../View/Portafolio/indexTwo.php');
}

function verDetalleAction(){

	include('../Config/Conexion.php');
	include('../Model/PortafolioM.php');
	$link = getConexion();
	
	$cod_emp = $_GET['cod_emp'];//numero

	$sql = "SELECT * FROM empresa_portafolio ep
			INNER JOIN empresa em ON(ep.cod_emp=em.cod_emp)
			WHERE ep.cod_emp='$cod_emp' ORDER BY ep.por_fech,ep.por_hora ASC";
	$res = mysqli_query($link, $sql);

	include('../View/Portafolio/detalle.php');
}

function addPortafolioAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_emp   = $_POST['cod_emp'];
	$cant      = (int)str_replace(',', '', $_POST['cantidad']);
	$prec      = (double)str_replace(',', '', $_POST['precio']);
	$mont_neg  = (double)str_replace(',', '', $_POST['mont_neg']);
	$fecha     = date('Y-m-d');
	$hora      = date('H:i:s');
	$cod_user  = $_SESSION['cod_user'];
	$cod_grupo = $_POST['cod_grupo'];
	$mont_est  = (double)str_replace(',', '', $_POST['mont_est']);
	$rent_obj  = (double)str_replace(',', '', $_POST['rent_obj']);
	$prec_act_obj = (double)str_replace(',', '', $_POST['prec_act']);
	$gan_neta = (double)str_replace(',', '', $_POST['gan_neta']);


	$insert  = "INSERT INTO empresa_portafolio(cod_emp,cod_user,por_fech,por_hora,por_cant,por_prec,por_mont_est,por_rent_obj,por_prec_obj,por_gan_net,cod_grupo,por_mont_neg)VALUES('$cod_emp','$cod_user','$fecha','$hora','$cant','$prec','$mont_est','$rent_obj','$prec_act_obj','$gan_neta','$cod_grupo','$mont_neg')";
		$resp = mysqli_query($link,$insert);

	echo 'ok;';
}

function updatePortafolioAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$por_cod   = $_POST['por_cod'];
	$cod_emp   = $_POST['cod_emp'];
	$cant      = (int)str_replace(',', '', $_POST['cantidad']);
	$prec      = (double)str_replace(',', '', $_POST['precio']);
	$mont_neg  = (double)str_replace(',', '', $_POST['mont_neg']);
	$fecha     = date('Y-m-d');
	$hora      = date('H:i:s');
	$cod_user  = $_SESSION['cod_user'];
	$cod_grupo = $_POST['cod_grupo'];
	$mont_est  = (double)str_replace(',', '', $_POST['mont_est']);
	$rent_obj  = (double)str_replace(',', '', $_POST['rent_obj']);
	$por_prec_obj = (double)str_replace(',', '', $_POST['prec_act']);
	$gan_neta = (double)str_replace(',', '', $_POST['gan_neta']);

	$update = "UPDATE empresa_portafolio SET por_hora='$hora',por_cant='$cant',por_prec='$prec',por_mont_est='$mont_est',por_rent_obj='$rent_obj',por_prec_obj='$por_prec_obj',por_gan_net='$gan_neta', por_mont_neg='$mont_neg'  WHERE por_cod='$por_cod'";
	//echo $update;
	$resp = mysqli_query($link,$update);

	echo 'ok';
}



function deleteAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user  = $_GET['cod_user'];
	$cod_emp   = $_GET['cod_emp'];
	$por_fech  = $_GET['por_fech'];
	$por_cod  = $_GET['por_cod'];
	$todo      = $_GET['todo'];

	$AND_WHERE = ($todo!='si')?" por_cod='$por_cod' AND":"";

	$sql  = "DELETE FROM empresa_portafolio WHERE $AND_WHERE cod_emp='$cod_emp' AND cod_user='$cod_user' AND DATE_FORMAT(por_fech,'%Y-%m-%d')='$por_fech'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/PortafolioC.php?accion=index");
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'ver_detalle':
		verDetalleAction();
		break;
	case 'delete':
		deleteAction();
		break;
	case 'add_portafolio':
		addPortafolioAction();
		break;
	case 'update_portafolio':
		updatePortafolioAction();
		break;
}
?>
