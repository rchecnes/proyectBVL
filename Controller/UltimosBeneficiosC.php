<?php
function indexAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user = $_SESSION['cod_user'];

	$fecha_actual = date("Y-m-d");
	//sumo 1 día
	$fecha_actual =  date("Y-m-d",strtotime($fecha_actual."- 12 month"));

	include('../Control/Combobox/Combobox.php');
	include('../View/UltimosBeneficios/index.php');
}

function newAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');

	$accion = "create";

	$titulo = "Nuevo Beneficio";
	$ub_cod = "";
	$ub_der_comp = "";
	$ub_der_mon = "";
	$ub_der_imp = "";
	$ub_der_por = "";
	$ub_der_tip = "";
	$ub_nemonico = "";
	$ub_fech_acu = $ub_fech_cor = $ub_fech_reg = $ub_fech_ent = date('Y-m-d');

	include('../View/UltimosBeneficios/edit.php');
}

function editAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');

	$accion = "update";

	$ub_cod = $_GET['ub_cod'];
	$titulo = "Editar Beneficio";
	//Consultamos registro
	$sql = "SELECT * FROM ultimos_beneficios WHERE ub_cod='$ub_cod'";
	$res = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($res);

	$ub_der_comp = $row['ub_der_comp'];
	$ub_der_mon = $row['ub_der_mon'];
	$ub_der_imp = $row['ub_der_imp'];
	$ub_der_por = $row['ub_der_por'];
	$ub_der_tip = $row['ub_der_tip'];
	$ub_nemonico = $row['ub_nemonico'];
	$ub_fech_acu = $row['ub_fech_acu'];
	$ub_fech_cor = $row['ub_fech_cor'];
	$ub_fech_reg = $row['ub_fech_reg'];
	$ub_fech_ent = $row['ub_fech_ent'];

	include('../View/UltimosBeneficios/edit.php');
}

function createAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	$ub_nemonico = $_POST['ub_nemonico'];
	$ub_der_mon = $_POST['ub_der_mon'];
	$ub_der_imp = $_POST['ub_der_imp'];
	$ub_der_por = $_POST['ub_der_por'];
	$ub_der_tip = $_POST['ub_der_tip'];

	$ub_der_comp = ($ub_der_mon=='')?$ub_der_imp.''.$ub_der_por.' '.$ub_der_tip:$ub_der_mon.' '.$ub_der_imp.' '.$ub_der_tip;

	$ub_fech_acu = $_POST['ub_fech_acu'];
	$ub_fech_cor = $_POST['ub_fech_cor'];
	$ub_fech_reg = $_POST['ub_fech_reg'];
	$ub_fech_ent = $_POST['ub_fech_ent'];


	//Obtenemos el codigo empresa
	$sqlemp = "SELECT nemonico FROM nemonico WHERE nemonico='$ub_nemonico'";
	$resemp = mysqli_query($link, $sqlemp);
	$rowemp = mysqli_fetch_array($resemp);
	$ub_cod_emp_bvl = $rowemp['ub_cod_emp_bvl'];

	//Consultamos si ya se registro
	$sqlval = "SELECT ub_nemonico FROM ultimos_beneficios WHERE ub_nemonico='$ub_nemonico' AND ub_fech_acu='$ub_fech_acu' AND ub_fech_cor='$ub_fech_cor' AND ub_fech_reg='$ub_fech_reg' AND ub_fech_ent='$ub_fech_ent'  AND ub_der_mon='$ub_der_mon' AND ub_der_tip='$ub_der_tip'";
	$resval = mysqli_query($link, $sqlval);
	$rowval = mysqli_fetch_array($resval);

	if($rowval['ub_nemonico']=='' || $rowval['ub_nemonico']==null){
		//Insertar a BD
		$sqlin = "INSERT INTO ultimos_beneficios(ub_nemonico,ub_der_comp,ub_der_mon,ub_der_imp,ub_der_por,ub_der_tip,ub_fech_acu,ub_fech_cor,ub_fech_reg,ub_fech_ent,ub_cod_emp_bvl)
		VALUES('$ub_nemonico','$ub_der_comp','$ub_der_mon','$ub_der_imp','$ub_der_por','$ub_der_tip','$ub_fech_acu','$ub_fech_cor','$ub_fech_reg','$ub_fech_ent','$ub_cod_emp_bvl')";
		$resin = mysqli_query($link, $sqlin);
	}

	mysqli_close($link);
	header("location:../Controller/UltimosBeneficiosC.php?accion=index");
}

function updateAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	$ub_cod = $_POST['ub_cod'];
	$ub_nemonico = $_POST['ub_nemonico'];
	$ub_der_comp = $_POST['ub_der_comp'];
	$ub_der_mon = $_POST['ub_der_mon'];
	$ub_der_imp = $_POST['ub_der_imp'];
	$ub_der_por = $_POST['ub_der_por'];
	$ub_der_tip = $_POST['ub_der_tip'];
	
	$ub_der_comp = ($ub_der_mon=='')?$ub_der_imp.''.$ub_der_por.' '.$ub_der_tip:$ub_der_mon.' '.$ub_der_imp.' '.$ub_der_tip;
	
	$ub_fech_acu = $_POST['ub_fech_acu'];
	$ub_fech_cor = $_POST['ub_fech_cor'];
	$ub_fech_reg = $_POST['ub_fech_reg'];
	$ub_fech_ent = $_POST['ub_fech_ent'];

	$sqlup = "UPDATE ultimos_beneficios SET ub_der_comp='$ub_der_comp',ub_der_mon='$ub_der_mon',ub_der_imp='$ub_der_imp',ub_der_por='$ub_der_por',ub_der_tip='$ub_der_tip',ub_fech_acu='$ub_fech_acu',ub_fech_cor='$ub_fech_cor',ub_fech_reg='$ub_fech_reg',ub_fech_ent='$ub_fech_ent' WHERE ub_cod='$ub_cod'";
	$resup = mysqli_query($link, $sqlup);

	mysqli_close($link);
	header("location:../Controller/UltimosBeneficiosC.php?accion=index");
}

function deleteAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	$ub_cod = $_GET['ub_cod'];

	$sql = "DELETE FROM ultimos_beneficios WHERE ub_cod='$ub_cod'";
	$res = mysqli_query($link, $sql);

	mysqli_close($link);
	header("location:../Controller/UltimosBeneficiosC.php?accion=index");
}

function listarAction(){

	session_start();
	include('../Config/Conexion.php');
	$link = getConexion();

	$nemonico = $_GET['nemonico'];
	$ub_der_tip = $_GET['ub_der_tip'];
	$ub_der_mon = $_GET['ub_der_mon'];
	$ub_fech_ent_de = $_GET['ub_fech_ent_de'];
	$ub_fech_ent_ha = $_GET['ub_fech_ent_ha'];
	$cod_ector = $_GET['cod_ector'];
	$cod_grupo = $_GET['cod_grupo'];
	$cod_user = $_SESSION['cod_user'];

	$sql = "SELECT *, DATE_FORMAT(ub_fech_acu,'%d/%m/%Y')AS ub_fech_acu, 
				DATE_FORMAT(ub_fech_cor,'%d/%m/%Y')AS ub_fech_cor,
				DATE_FORMAT(ub_fech_reg,'%d/%m/%Y')AS ub_fech_reg,
				DATE_FORMAT(ub_fech_ent,'%d/%m/%Y')AS ub_fech_ent
				 FROM ultimos_beneficios ub
			INNER JOIN nemonico ne ON(ub.ub_nemonico=ne.nemonico)
			LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod)
			INNER JOIN empresa_favorito fa ON(ne.ne_cod=fa.ne_cod)
			WHERE ub.ub_cod>0";

	if ($nemonico != '') { $sql .= " AND ne.nemonico='$nemonico'";}
	if ($ub_der_tip != '') { $sql .= " AND ub.ub_der_tip='$ub_der_tip'";}
	if ($ub_der_mon != '') { $sql .= " AND ub.ub_der_mon='$ub_der_mon'";}
	if ($ub_fech_ent_de != '' && $ub_fech_ent_ha!='') { $sql .= " AND ub.ub_fech_ent BETWEEN '$ub_fech_ent_de' AND '$ub_fech_ent_ha'";}
	if ($cod_ector != '') { $sql .= " AND em.sec_cod='$cod_ector'";}
	if ($cod_grupo != '') { $sql .= " AND fa.cod_grupo='$cod_grupo'";}

	$sql .= " ORDER BY ub.ub_fech_ent DESC";
	//echo $sql;
	$res = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($res);

	include('../View/UltimosBeneficios/listar.php');
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

function getFechaBD($date){

	list($dia, $mes, $ano) = explode('/',$date);

	return $ano.'-'.$mes.'-'.$dia;
}

function importarBeneficio($ruta, $condicion){

	include($ruta.'/Util/simple_html_dom_php5.6.php');
	include($ruta.'/Config/Conexion.php');
	$link = getConexion();

	$sql = "SELECT * FROM nemonico WHERE cod_emp_bvl!='' $condicion";
	$res = mysqli_query($link, $sql);

	while($row = mysqli_fetch_array($res)){
		
		$new_codigo = $row['cod_emp_bvl'];
		$new_nemonico = $row['nemonico'];

		$url  = "https://www.bvl.com.pe/jsp/Inf_EstadisticaGrafica.jsp?Cod_Empresa=$new_codigo&Nemonico=$new_nemonico&Listado=|$new_nemonico";
		$html = file_get_contents_curl($url);

		//echo $new_nemonico."<br>";
		if (!empty($html)) {

			$div_0 = $html->find("div[class='divBloque']",0);
			$table_1 = ($div_0->find('table',1)!=null)?$div_0->find('table',1):$div_0->find('table',0);
			//$tr_0 = $table_1->find('tr',0);
			//echo $div_0."<br>";
			if(isset($table_1->find('tr',0)->plaintext)){

				foreach($table_1->find("tr") as $tr){
					
					if (isset($tr->find('td',0)->plaintext)) {

						$ub_der_mon = '';
						$ub_der_imp = 0;
						$ub_der_por = '';
						$ub_der_tip = '';

						$derecho = $tr->find("td",0)->plaintext;
						if(strpos($derecho, 'S/.') !== false ){$ub_der_mon = 'S/.';}
						if(strpos($derecho, 'US$') !== false){$ub_der_mon = 'US$';}

						if(strpos($derecho, 'Efe.') !== false ){$ub_der_tip = 'Efe.';}
						if(strpos($derecho, 'Accs.') !== false){$ub_der_tip = 'Accs.';}

						if(strpos($derecho, '%') !== false ){$ub_der_por = '%';}

						$ub_der_imp = str_replace($ub_der_mon,'',$derecho);
						$ub_der_imp = str_replace($ub_der_tip,'',$ub_der_imp);
						$ub_der_imp = str_replace($ub_der_por,'',$ub_der_imp);
						$ub_der_imp = trim($ub_der_imp);

						$ub_fech_acu = getFechaBD($tr->find("td",1)->plaintext);
						$ub_fech_cor = getFechaBD($tr->find("td",2)->plaintext);
						$ub_fech_reg = getFechaBD($tr->find("td",3)->plaintext);
						$ub_fech_ent = getFechaBD($tr->find("td",4)->plaintext);

						//Consultamos si ya se registro
						$sqlval = "SELECT ub_nemonico FROM ultimos_beneficios WHERE ub_nemonico='$new_nemonico' AND ub_fech_acu='$ub_fech_acu' AND ub_fech_cor='$ub_fech_cor' AND ub_fech_reg='$ub_fech_reg' AND ub_fech_ent='$ub_fech_ent' AND ub_der_mon='$ub_der_mon' AND ub_der_tip='$ub_der_tip'";
						$resval = mysqli_query($link, $sqlval);
						$rowval = mysqli_fetch_array($resval);
						//echo $sqlval."<br>";

						if($rowval['ub_nemonico']=='' || $rowval['ub_nemonico']==null){
							//Insertar a BD
							$sqlin = "INSERT INTO ultimos_beneficios(ub_nemonico,ub_der_comp,ub_der_mon,ub_der_imp,ub_der_por,ub_der_tip,ub_fech_acu,ub_fech_cor,ub_fech_reg,ub_fech_ent,ub_cod_emp_bvl)
							VALUES('$new_nemonico','$derecho','$ub_der_mon','$ub_der_imp','$ub_der_por','$ub_der_tip','$ub_fech_acu','$ub_fech_cor','$ub_fech_reg','$ub_fech_ent','$new_codigo')";
							$resin = mysqli_query($link, $sqlin);
							unset($sqlin);
							unset($resin);
						}
						unset($sqlval);
					}
				}
			}
			unset($div_0);
			unset($table_1);
		}
		unset($html);
	}
}

function importarManualAction(){

	$nemonico = $_GET['nemonico'];
	$ruta = "..";

	$condicion = "";
	if($nemonico !=''){
		$condicion .= " AND nemonico='$nemonico'";
	}

	importarBeneficio($ruta, $condicion);
}

function importarAutomaticolAction(){

	$ruta = "public_html/analisisdevalor.com";
	$condicion = "";
	
	importarBeneficio($ruta, $condicion);
}

//Este parametro se obtiene desde la vista y crons
$accion = (isset($_GET['accion']))?$_GET['accion']:'';
if($accion == ''){
	$accion = (isset($argv[1]))?$argv[1]:'';
}

switch ($accion) {
	case 'index':
		indexAction();
		break;
	case 'listar':
		listarAction();
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
	case 'update':
		updateAction();
		break;
	case 'delete':
		deleteAction();
		break;	
	case 'importarmanual':
		importarManualAction();
		break;
	case 'importarautomatico':
		importarAutomaticolAction();
		break;
	default:
		# code...
		break;
}