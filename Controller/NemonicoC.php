<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);

	$sql = "SELECT ne.*, se.nombre AS nom_sector, em.emp_nomb AS nom_empresa FROM nemonico ne
	LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod)
	LEFT JOIN sector se ON(em.sec_cod=se.cod_sector)
	WHERE ne.estado='1' ORDER BY ne.cz_fe_fin DESC, ne.nemonico ASC";
	$empresas = mysqli_query($link, $sql);

	include('../View/Nemonico/index.php');
}

function jqgridAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);

	$sql = "SELECT *, se.nombre AS nom_sector, em.nombre AS nom_empresa FROM empresa em LEFT JOIN sector se ON(em.cod_sector=se.cod_sector)";
	$empresas = mysqli_query($link, $sql);

	include('../View/Nemonico/jqgrid.php');
}

function newAction(){
	
	$titulo = "Nueva Empresa";

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Control/Combobox/Combobox.php');

	include('../View/Nemonico/new.php');
}

function createAction(){
	
	include('../Config/Conexion.php');
	$link = getConexion();

	$max = "SELECT max(ne_cod) AS max FROM nemonico";
	$respmax = mysqli_query($link,$max);
	$rowmax = mysqli_fetch_array($respmax);

	//Obtenemos los datos
	$ne_cod   = ($rowmax['max']!='')?$rowmax['max']+1:1;
	$emp_cod = $_POST['emp_cod'];
	$nemonico = $_POST['nemonico'];
	$segmento = $_POST['segmento'];
	$moneda   = $_POST['moneda'];
	$cod_emp_bvl   = $_POST['cod_emp_bvl'];
	$estado   = (isset($_POST['estado']))?1:0;

	$sql  = "INSERT INTO nemonico(ne_cod,emp_cod,nemonico,segmento,moneda, estado, cod_emp_bvl) VALUES('$ne_cod','$emp_cod','$nemonico','$segmento','$moneda','$estado','$cod_emp_bvl')";
	$resp = mysqli_query($link, $sql) or die(mysqli_error($link));

	//echo $insert;
	header("location:../Controller/NemonicoC.php?accion=index");
}


function editAction(){
	
	$titulo = "Editar Empresa";

	$ne_cod = $_GET['ne_cod'];

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Control/Combobox/Combobox.php');

	$sql = "SELECT * FROM nemonico
	WHERE ne_cod='$ne_cod'";
	//echo $sql;
	$resp = mysqli_query($link,$sql);
	$em = mysqli_fetch_array($resp);

	include('../View/Nemonico/edit.php');
}

function updateAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$ne_cod   = $_POST['ne_cod'];
	$emp_cod   = $_POST['emp_cod'];
	$nemonico = $_POST['nemonico'];
	$segmento = $_POST['segmento'];
	$moneda   = $_POST['moneda'];
	$estado   = (isset($_POST['estado']))?1:0;
	$cod_emp_bvl   = $_POST['cod_emp_bvl'];

	$sql  = "UPDATE nemonico SET emp_cod='$emp_cod', nemonico='$nemonico', segmento='$segmento', moneda='$moneda',estado='$estado', cod_emp_bvl='$cod_emp_bvl' WHERE ne_cod='$ne_cod'";
	$resp = mysqli_query($link, $sql);

	//echo $insert;
	header("location:../Controller/NemonicoC.php?accion=index");
}


function deleteAction(){
	
	$ne_cod = $_GET['ne_cod'];

	include('../Config/Conexion.php');
	$link = getConexion();

	$sql  = "DELETE FROM nemonico WHERE ne_cod='$ne_cod'";
	$resp = mysqli_query($link,$sql);

	header("location:../Controller/NemonicoC.php?accion=index");
}

function showJqgrid(){

	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
	
	include('../View/Nemonico/jqgrid.php');
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
			'acciones' => '<a href="../Controller/NemonicoC.php?accion=edit&codigo='.$w['cod_emp'].'" class="btn btn-default" role="button">Editar</a>&nbsp;<a href="../Controller/EmpresaC.php?accion=delete&codigo='.$w['cod_emp'].'" class="btn btn-danger" role="button">Eliminar</a>'
			);
			
	}

	$group['records'] = $record;
	$group['page']    = $pageJq;
	$group['total']   = $record;
	$group['rows']    = $empresas;
	

	echo json_encode($group);
}

function file_get_contents_curl($url){

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$data = curl_exec( $ch );
	$data = str_get_html($data);
	curl_close( $ch );
	return ($data!='')?$data:"";
}

function setGetSector($sector, $link){

	//include('../Config/Conexion.php');
	//$link   = getConexion();
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

function savImportedAction($data){

	include('../Config/Conexion.php');
	$link = getConexion();
	$dataBVL = $data;

	$max = "SELECT max(ne_cod) AS max FROM nemonico";
	$respmax = mysqli_query($link,$max);
	$rowmax = mysqli_fetch_array($respmax);
	$codigo = $rowmax['max'];

	if ($codigo =='') {
		$codigo = '999';
	}

	$del = "";
	$sql = "";
	$fe_impo = date('Y-m-d H:i:s');

	foreach ($dataBVL as $key => $f) {

		$nemonico = strtoupper($f['nem']);

		if($nemonico!=''){

			$sqlval = "SELECT COUNT(ne_cod)AS cantidad FROM nemonico WHERE UPPER(nemonico)='$nemonico'";
			$resval = mysqli_query($link, $sqlval);
			$rowval = mysqli_fetch_array($resval);
			if($rowval['cantidad']<=0){
				
				$codigo    += 1 ;
				$nombre    = $f['emp'];
				$nemonico  = $f['nem'];
				$sector    = setGetSector($f['sec'], $link);
				$segmento  = $f['seg'];
				$moneda    = $f['mon'];
				$estado    = 1;

				$sql .= "('$codigo','$nombre','$nemonico','$sector','$segmento','$moneda','$estado', '$fe_impo'),";
				
				//Creamos nuevas empresas
				$maxemp = "SELECT max(emp_cod) AS maxemp FROM empresa";
				$respmax = mysqli_query($link,$maxemp);
				$rowmax = mysqli_fetch_array($respmax);
				$new_emp_cod   	= ($rowmax['maxemp']!='')?$rowmax['maxemp']+1:1000;

				//Insertamos empresa
				$sqlem  = "INSERT INTO empresa(emp_cod,emp_nomb,sec_cod,emp_stdo) VALUES('$new_emp_cod','$nombre','$sector','1')";
				$resem = mysqli_query($link, $sqlem);
			}
		}
		
	}

	$resp = true;
	if ($sql !='') {

		//$delete = "DELETE FROM empresa WHERE nemonimo IN(".trim($del,',').")";
		//echo $delete."<br>";
		//$respdel = mysqli_query($link,$delete);

		$insert = "INSERT INTO nemonico (ne_cod,nombre,nemonico,cod_sector,segmento,moneda,estado,fe_impo) VALUES ".trim($sql,',').";";
		$resp    = mysqli_query($link,$insert);
	}
	
	return $resp;
}

function updateImportedAction($data){

	include('../Config/Conexion.php');
	$link = getConexion();
	$dataBVL = $data;

	$del = "";
	$sql = "";

	foreach ($dataBVL as $key => $f) {

		$nemonico = strtoupper($f['nem']);

		if($nemonico!='' && $f['cod']!=''){
			
			$cod_emp_bvl = $f['cod'];

			$sqlval = "SELECT cod_emp_bvl FROM nemonico WHERE UPPER(nemonico)='$nemonico'";
			$resval = mysqli_query($link, $sqlval);
			$rowval = mysqli_fetch_array($resval);

			if($rowval['cod_emp_bvl']==''){

				$update = "UPDATE nemonico SET cod_emp_bvl='$cod_emp_bvl' WHERE UPPER(nemonico)='$nemonico'";
				$resp   = mysqli_query($link,$update);

				$emp_cod = $rowval['emp_cod'];
				$update = "UPDATE empresa SET emp_cod_bvl='$cod_emp_bvl' WHERE emp_cod='$emp_cod'";
				$resp   = mysqli_query($link,$update);

			}
			
		}
		
	}

	return true;
}

function importarManualAction(){

	include('../Util/simple_html_dom_php5.6.php');
	$url = "https://www.bvl.com.pe/includes/cotizaciones_todas.dat";

	$html = file_get_contents_curl($url);
	
	$tipo = $_GET['tipo'];

	$data = array();

	if (!empty($html)) {

        foreach($html->find('tr') as $e){
			
			$Cod_Empresa = '';
			foreach($e->find('td') as $td){
				$href = $td->find('a',0)->href;
				$pos_inicio = strpos($href,'Cod_Empresa=')+12;
				$pos_final = strpos($href,'&Nemonico');
				$Cod_Empresa = substr($href, $pos_inicio, ($pos_final-$pos_inicio));
				if($Cod_Empresa!=''){break;}
			}

			if (isset($e->find('td',1)->plaintext)) {

				$data[] = array(
								'cod'=>$Cod_Empresa,
								'emp'=>$e->find('td',1)->plaintext,
								'nem'=>$e->find('td',2)->plaintext,
								'sec'=>$e->find('td',3)->plaintext,
								'seg'=>$e->find('td',4)->plaintext,
								'mon'=>$e->find('td',5)->plaintext);
			}
			
		}
	}

	$val = true;
	if($tipo == 'new'){
		$val = savImportedAction($data);
	}
	if($tipo == 'update'){
		$val = updateImportedAction($data);
	}

	echo json_encode(array('res'=>$val));
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
	case 'importarmanual':
		importarManualAction();
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
