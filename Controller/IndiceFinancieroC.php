<?php
function indexAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();
	$cod_user = $_SESSION['cod_user'];

	include('../Control/Combobox/Combobox.php');
	include('../View/IndiceFinanciero/index.php');
}

function newAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');

	$accion = "create";

	$titulo = "Nuevo Indice Financiero";
	$inf_detcod = "";
	$inf_codigo = "";
	$inf_nemonico = "";
	$inf_anio = "";

	include('../View/IndiceFinanciero/edit.php');
}

function editAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');

	$accion = "update";

	$inf_nemonico = $_GET['inf_nemonico'];
	$inf_anio = $_GET['inf_anio'];
	$titulo = "Editar Indice Financiero";
	//Consultamos registro
	$sql = "SELECT * FROM cab_indice_financiero c
	INNER JOIN det_indice_financiero d ON(c.inf_codigo=d.inf_codigo) WHERE d.inf_nemonico='$inf_nemonico' AND d.inf_anio='$inf_anio'";
	$res = mysqli_query($link, $sql);
	$inf_array = array();
	while($row = mysqli_fetch_array($res)){
		$inf_array[$row['inf_codigo']] = array('inf_valor'=>$row['inf_valor']);
	}

	/*$inf_detcod = $row['inf_detcod'];
	$inf_codigo = $row['inf_codigo'];
	$inf_nemonico = $row['inf_nemonico'];
	$inf_anio = $row['inf_anio'];
	$inf_valor = $row['inf_valor'];*/

	include('../View/IndiceFinanciero/edit.php');
}

function createAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	$inf_nemonico = $_POST['inf_nemonico'];
	$inf_anio = $_POST['inf_anio'];
	$inf_contador = $_POST['inf_contador'];

	//Obtenemos el codigo empresa
	$sqlemp = "SELECT nemonico FROM nemonico WHERE nemonico='$ub_nemonico'";
	$resemp = mysqli_query($link, $sqlemp);
	$rowemp = mysqli_fetch_array($resemp);
	$ub_cod_emp_bvl = $rowemp['ub_cod_emp_bvl'];

	for($i = 1;$i<=$inf_contador;$i++){

		if(isset($_POST['inf_valor_'.$i]) && $_POST['inf_valor_'.$i]!='' && $_POST['inf_valor_'.$i]>0){

			$inf_codigo = $_POST['inf_codigo_'.$i];
			$inf_valor = $_POST['inf_valor_'.$i];

			//Insertar a BD
			$sqlin = "INSERT INTO det_indice_financiero(inf_codigo,inf_nemonico,inf_anio,inf_valor)VALUES('$inf_codigo','$inf_nemonico','$inf_anio','$inf_valor')";
			$resin = mysqli_query($link, $sqlin);
		}
	}

	mysqli_close($link);
	header("location:../Controller/IndiceFinancieroC.php?accion=index");
}

function updateAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	//$inf_detcod = $_POST['inf_detcod'];
	$inf_nemonico = $_POST['inf_nemonico'];
	//$inf_codigo = $_POST['inf_codigo'];
	$inf_anio = $_POST['inf_anio'];
	$inf_contador = $_POST['inf_contador'];

	for($i = 1;$i<=$inf_contador;$i++){

		if(isset($_POST['inf_valor_'.$i])){

			$inf_codigo = $_POST['inf_codigo_'.$i];
			$inf_valor = $_POST['inf_valor_'.$i];

			//Consultamos si ya se registro el indice
			$sqlv = "SELECT * FROM det_indice_financiero WHERE inf_codigo='$inf_codigo' AND inf_nemonico='$inf_nemonico' AND inf_anio='$inf_anio' LIMIT 1";
			$resv = mysqli_query($link, $sqlv);
			$rowv = mysqli_fetch_array($resv);

			if($rowv['inf_codigo'] == ''){
				if($_POST['inf_valor_'.$i]!=''){
					$sqlin = "INSERT INTO det_indice_financiero(inf_codigo,inf_nemonico,inf_anio,inf_valor)VALUES('$inf_codigo','$inf_nemonico','$inf_anio','$inf_valor')";
					$resin = mysqli_query($link, $sqlin);
				}
			}else{

				$sqlup = "UPDATE det_indice_financiero SET inf_valor='$inf_valor' WHERE inf_nemonico='$inf_nemonico' AND inf_codigo='$inf_codigo' AND inf_anio='$inf_anio'";
				$resup = mysqli_query($link, $sqlup);
			}
		}
	}

	mysqli_close($link);
	header("location:../Controller/IndiceFinancieroC.php?accion=index");
}

function deleteAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	$inf_nemonico = $_GET['inf_nemonico'];
	$inf_anio = $_GET['inf_anio'];

	$sql = "DELETE FROM det_indice_financiero WHERE inf_nemonico='$inf_nemonico' AND inf_anio='$inf_anio'";
	$res = mysqli_query($link, $sql);

	mysqli_close($link);
	header("location:../Controller/IndiceFinancieroC.php?accion=index");
}

function listarAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$inf_nemonico = $_GET['inf_nemonico'];
	$inf_codigo = $_GET['inf_codigo'];
	$cod_sector = $_GET['cod_sector'];
	$cod_grupo = $_GET['cod_grupo'];

	$sql = "SELECT c.*,d.*,em.emp_nomb FROM det_indice_financiero d 
			INNER JOIN cab_indice_financiero c ON(d.inf_codigo=c.inf_codigo) 
			INNER JOIN nemonico ne ON(ne.nemonico COLLATE utf8_general_ci=d.inf_nemonico COLLATE utf8_general_ci)
			LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod)
			LEFT JOIN empresa_favorito fa ON(ne.ne_cod=fa.ne_cod)
			WHERE c.inf_stat ='10'";

	if ($inf_nemonico != '') { $sql .= " AND d.inf_nemonico='$inf_nemonico'";}
	if ($inf_codigo != '') { $sql .= " AND d.inf_codigo='$inf_codigo'";}
	if ($cod_sector != '') { $sql .= " AND em.sec_cod='$cod_sector'";}
	if ($cod_grupo != '') { $sql .= " AND fa.cod_grupo='$cod_grupo'";}

	$sql .= " GROUP BY c.inf_codigo,d.inf_nemonico ORDER BY d.inf_nemonico ASC, c.inf_codigo ASC";
	$res = mysqli_query($link, $sql);
	$nro_reg = mysqli_num_rows($res);

	//Obtenemos solos años
	$sqla = "SELECT inf_anio FROM det_indice_financiero WHERE inf_codigo<>''";
	if ($inf_nemonico != '') { $sqla .= " AND inf_nemonico='$inf_nemonico'";}
	$sqla .= " GROUP BY inf_anio ORDER BY inf_anio ASC";
	$resa = mysqli_query($link, $sqla);

	$array_anio = array();
	while($rowa = mysqli_fetch_array($resa)){
		$array_anio[$rowa['inf_anio']] = $rowa['inf_anio'];
	}

	//print_r($array_anio);
	include('../View/IndiceFinanciero/listar.php');
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

function importarIndiceFinanciero($ruta, $condicion){

	include($ruta.'/Util/simple_html_dom_php5.6.php');
	include($ruta.'/Config/Conexion.php');
	$link = getConexion();

	$sql = "SELECT * FROM nemonico WHERE imp_ind_fin!='' AND cod_emp_bvl!='' $condicion";
	$res = mysqli_query($link, $sql);

	while($row = mysqli_fetch_array($res)){
		
		$new_codigo = $row['cod_emp_bvl'];
        $new_nemonico = $row['nemonico'];
        $imp_ind_fin = $row['imp_ind_fin'];

        $url = "https://www.bvl.com.pe/inf_financiera$new_codigo"."_"."$imp_ind_fin.html";
        $html = file_get_contents_curl($url);
       
		if (!empty($html)) {

			$div_0 = $html->find("div[class='divBloque']",0);
			$table_1 = ($div_0->find('table',1)!=null)?$div_0->find('table',1):$div_0->find('table',0);

			if(isset($table_1->find('tr',0)->plaintext)){

				$anio = array();
				//$inf_codigo = 10000;
				foreach($table_1->find("tr") as $tr){

					if (isset($tr->find('th',0)->plaintext)) {
						foreach($tr->find('th') as $th){
							$th_new = strtoupper(str_replace(' ','',$th->plaintext));
							$anio[] = $th_new;
						}
					}

					if (isset($tr->find('td',0)->plaintext) && $tr->find('td',0)->plaintext !='' && $tr->find('td',0)->plaintext !='&nbsp;') {
						
						//Insertamos cabecera el texto
						$inf_nombre = trim($tr->find('td',0)->plaintext);
						$inf_fech_crea = date('Y-m-d');
						$inf_hora_crea = date('H:i:s');

						//Consultamos si ya se registro
						$sqlvalc = "SELECT inf_codigo FROM cab_indice_financiero WHERE inf_nombre='$inf_nombre' LIMIT 1";
						$resvalc = mysqli_query($link, $sqlvalc);
						$rowvalc = mysqli_fetch_array($resvalc);
						$inf_codigo = $rowvalc['inf_codigo'];

						if($inf_codigo == ''){

							//Autogeneramos el inf_codigo
							$sqlmax = "SELECT MAX(inf_codigo)AS inf_codigo FROM cab_indice_financiero";
							$resmax = mysqli_query($link, $sqlmax);
							$rowmax = mysqli_fetch_array($resmax);
							$inf_codigo = ($rowmax['inf_codigo']!='')?(int)$rowmax['inf_codigo']+1:'10000';

							$sqlinc = "INSERT INTO cab_indice_financiero(inf_codigo, inf_nombre, inf_fech_crea, inf_hora_crea, inf_stat)VALUES('$inf_codigo','$inf_nombre','$inf_fech_crea','$inf_hora_crea','10')";
							$resinc = mysqli_query($link, $sqlinc);
							unset($sqlinc);
						}

						$c_td = 0;
						foreach($tr->find('td') as $td){
							
							if($c_td > 0){

								$anio_reg = $anio[$c_td];
								$anio_val = trim($td->plaintext);
								$anio_val = str_replace(' ','',$td->plaintext);
								$anio_val = str_replace(',','',$td->plaintext);

								//Consultamos si para ese año ya se registro
								$sqlvald = "SELECT inf_codigo FROM det_indice_financiero WHERE inf_nemonico='$new_nemonico' AND inf_codigo='$inf_codigo' AND  inf_anio='$anio_reg' LIMIT 1";
								$resvald = mysqli_query($link, $sqlvald);
								$rowvald = mysqli_fetch_array($resvald);

								if($rowvald['inf_codigo'] == ''){

									$sqlind = "INSERT INTO det_indice_financiero(inf_codigo, inf_nemonico, inf_anio, inf_valor)VALUES('$inf_codigo','$new_nemonico','$anio_reg','$anio_val')";
									$resind = mysqli_query($link, $sqlind);
									unset($sqlind);
								}
								unset($sqlvald);
								unset($resvald);
							}							

							$c_td ++;
						}

						//$inf_codigo ++;					
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

	$inf_nemonico = $_GET['inf_nemonico'];
	$ruta = "..";

	$condicion = "";
	if($inf_nemonico !=''){
		$condicion .= " AND nemonico='$inf_nemonico'";
	}

	importarIndiceFinanciero($ruta, $condicion);
}

function importarAutomaticolAction(){

	$ruta = "/var/www/html/analisisdevalor";
	$condicion = "";
	
	importarIndiceFinanciero($ruta, $condicion);
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