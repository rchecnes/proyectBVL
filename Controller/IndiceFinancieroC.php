<?php
function indexAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');
	include('../View/IndiceFinanciero/index.php');
}

function listarAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$nemonico = $_GET['nemonico'];

	$sql = "SELECT c.*,e.nombre FROM cab_indice_financiero c 
			INNER JOIN empresa e ON(c.inf_nemonico=e.nemonico)
			WHERE c.inf_stat ='10'";

	if ($nemonico != '') {
		$sql .= " AND c.inf_nemonico='$nemonico'";
	}

	$sql .= " ORDER BY c.inf_nemonico ASC, c.inf_codigo ASC";
	$res = mysqli_query($link, $sql);
	$nro_reg = mysqli_num_rows($res);

	//Obtenemos solos años
	$sqla = "SELECT inf_anio FROM det_indice_financiero WHERE inf_codigo<>''";
	if ($nemonico != '') { $sqla .= " AND inf_nemonico='$nemonico'";}
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

	$sql = "SELECT * FROM empresa WHERE imp_ind_fin!='' AND cod_emp_bvl!='' $condicion";
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
				$inf_codigo = 10000;
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
						$sqlvalc = "SELECT inf_codigo FROM cab_indice_financiero WHERE inf_nemonico='$new_nemonico' AND inf_nombre='$inf_nombre' LIMIT 1";
						$resvalc = mysqli_query($link, $sqlvalc);
						$rowvalc = mysqli_fetch_array($resvalc);

						if($rowvalc['inf_codigo'] == ''){

							$sqlinc = "INSERT INTO cab_indice_financiero(inf_codigo, inf_nemonico, inf_nombre, inf_fech_crea, inf_hora_crea, inf_stat)VALUES('$inf_codigo','$new_nemonico','$inf_nombre','$inf_fech_crea','$inf_hora_crea','10')";
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

						$inf_codigo ++;					
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

	importarIndiceFinanciero($ruta, $condicion);
}

function importarAutomaticolAction(){

	$ruta = "public_html/analisisdevalor.com";
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