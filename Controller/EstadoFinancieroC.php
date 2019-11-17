<?php
function indexAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');
	include('../View/EstadoFinanciero/index.php');
}

function listarAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$nemonico = $_GET['nemonico'];

	$sql = "SELECT * FROM cab_estado_financiero c
			INNER JOIN det_estado_financiero d ON(c.cef_cod=d.cef_cod AND c.cef_cod_bvl=d.cef_cod_bvl)
			WHERE c.cef_stat='10'";

	if ($nemonico != '') {
		$sql .= " AND d.def_nemonico='$nemonico'";
	}

	$sql .= " ORDER BY c.cef_cod, def_nemonico ASC";

	$res = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($res);

	//Nombre de la empresa sola
	$sqlem = "SELECT * FROM empresa WHERE nemonico='$nemonico'";
	$resem = mysqli_query($link, $sqlem);
	$rowem = mysqli_fetch_array($resem);
	$nombre_empresa = $rowem['nombre'];


	include('../View/EstadoFinanciero/listar.php');
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

function importarEstadoFinanciero($ruta, $condicion){

	include($ruta.'/Util/simple_html_dom_php5.6.php');
	include($ruta.'/Config/Conexion.php');
	$link = getConexion();

	$sql = "SELECT * FROM empresa WHERE cod_emp_bvl!='' AND imp_sit_fin!='' $condicion";
	$res = mysqli_query($link, $sql);

	$cef_anio = "2019";
	$def_peri = "3";
	$cef_tipo = "BAL";
	$cef_stat ='10';
	
	while($row = mysqli_fetch_array($res)){
		
		$new_codigo = $row['cod_emp_bvl'];
		$new_nemonico = $row['nemonico'];
		$imp_sit_fin = $row['imp_sit_fin'];
		$razon_social = "";
		$cef_fech_crea = date('Y-m-d');
		$cef_hora_crea = date('H:i:s');

		//$url  = "https://www.bvl.com.pe/jsp/Inf_EstadisticaGrafica.jsp?Cod_Empresa=$new_codigo&Nemonico=$new_nemonico&Listado=|$new_nemonico";
		$url = "https://www.bvl.com.pe/jsp/ShowEEFF_new.jsp?Ano=$cef_anio&Trimestre=$def_peri&Rpj=$imp_sit_fin&RazoSoci=$razon_social&TipoEEFF=$cef_tipo&Tipo1=T&Tipo2=C&Dsc_Correlativo=0000&Secuencia=0";
		$html = file_get_contents_curl($url);
		
		if (!empty($html)) {

			$table_1 = $html->find('table',3);
			//echo $table_1;
			if(isset($table_1) && $table_1 !='' && $table_1 !=null && isset($table_1->find('tr',0)->plaintext) && $table_1->find('tr',0)->plaintext !=''){

				$cont_tr = 1;
				foreach($table_1->find("tr") as $tr){
					
					//Detalles
					if ($cont_tr>3 && (isset($tr->find('td',0)->plaintext) || isset($tr->find('th',0)->plaintext))) {
						//echo trim($tr->find('td',0)->plaintext)."<br>";
						$cef_cod_bvl = $cef_nomb = "";
						$cef_cab_det = 'DET';
						if(isset($tr->find('th',0)->plaintext)){ 
							$cef_cod_bvl = trim($tr->find('th',0)->plaintext);
							$cef_nomb = trim($tr->find('th',1)->plaintext);
							$cef_cab_det = 'CAB';
							$def_val_de = trim($tr->find('th',3)->plaintext);$def_val_de = str_replace(',','',$def_val_de);
							$def_val_ha = trim($tr->find('th',4)->plaintext);$def_val_ha = str_replace(',','',$def_val_ha);
						}
						if(isset($tr->find('td',0)->plaintext)){ 
							$cef_cod_bvl = trim($tr->find('td',0)->plaintext);
							$cef_nomb = trim($tr->find('td',2)->plaintext);
							$cef_cab_det = 'DET';
							$def_val_de = trim($tr->find('td',4)->plaintext);$def_val_de = str_replace(',','',$def_val_de);
							$def_val_ha = trim($tr->find('td',5)->plaintext);$def_val_ha = str_replace(',','',$def_val_ha);
						}
						
						//Validamos si ya se registro la linea
						$sqlvc = "SELECT cef_cod, cef_cod_bvl FROM cab_estado_financiero WHERE cef_cod_bvl='$cef_cod_bvl' LIMIT 1";
						$resvc = mysqli_query($link, $sqlvc);
						$rowvc = mysqli_fetch_array($resvc);
						$cef_cod = $rowvc['cef_cod'];

						if($cef_cod==''){
							//Obtenemos el nuevo codigo
							$sqlnc = "SELECT MAX(cef_cod)AS cef_cod FROM cab_estado_financiero";
							$resnc = mysqli_query($link, $sqlnc);
							$rownc = mysqli_fetch_array($resnc);
							$cef_cod = ($rownc['cef_cod']!='')?$rownc['cef_cod']+1:'1000';

							//Insertamos cabecera
							$sqlinc = "INSERT INTO cab_estado_financiero(cef_cod,cef_cod_bvl,cef_nomb,cef_fech_crea,cef_hora_crea,cef_anio,cef_tipo,cef_cab_det,cef_stat)VALUES
							('$cef_cod','$cef_cod_bvl','$cef_nomb','$cef_fech_crea','$cef_hora_crea','$cef_anio','$cef_tipo','$cef_cab_det','$cef_stat')";
							$resinc = mysqli_query($link, $sqlinc);
						}

						//Insertamos detalle
						$sqlvd = "SELECT cef_cod, cef_cod_bvl FROM det_estado_financiero WHERE cef_cod='$cef_cod' AND cef_cod_bvl='$cef_cod_bvl' AND def_nemonico='$new_nemonico' LIMIT 1";
						$resvd = mysqli_query($link, $sqlvd);
						$rowvd = mysqli_fetch_array($resvd);
						$cef_cod_det = $rowvd['cef_cod'];

						if($cef_cod_det == ''){
							$sqlinc = "INSERT INTO det_estado_financiero(cef_cod,cef_cod_bvl,def_nemonico,def_val_de, def_val_ha,def_peri,def_fech_crea,def_hora_crea,def_cab_det)VALUES
							('$cef_cod','$cef_cod_bvl','$new_nemonico','$def_val_de','$def_val_ha','$def_peri','$cef_fech_crea','$cef_hora_crea','$cef_cab_det')";
							$resinc = mysqli_query($link, $sqlinc);
						}

					}
					$cont_tr ++;
				}
			}
		}
		unset($url);
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

	importarEstadoFinanciero($ruta, $condicion);
}

function importarAutomaticolAction(){

	$ruta = "public_html/analisisdevalor.com";
	$condicion = "";
	
	importarEstadoFinanciero($ruta, $condicion);
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