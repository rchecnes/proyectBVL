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

	$cef_nemonico = $_GET['cef_nemonico'];
	$cef_anio = $_GET['cef_anio'];
	$cef_peri = $_GET['cef_peri'];
	$cef_tipo = $_GET['cef_tipo'];
	$cef_trim = $_GET['cef_trim'];

	$sql = "SELECT * FROM cab_estado_financiero c
			INNER JOIN det_estado_financiero d ON(c.cef_cod=d.cef_cod AND c.cef_cod_bvl=d.cef_cod_bvl)
			WHERE c.cef_stat='10'";

	if ($cef_nemonico != '') { $sql .= " AND d.def_nemonico='$cef_nemonico'";}
	if ($cef_anio != '') { $sql .= " AND d.def_anio='$cef_anio'";}
	if ($cef_peri != '') { $sql .= " AND d.def_peri='$cef_peri'";}
	if ($cef_tipo != '') { $sql .= " AND d.def_tipo='$cef_tipo'";}
	if ($cef_trim != '') { $sql .= " AND d.def_trim='$cef_trim'";}

	$sql .= " ORDER BY c.cef_cod, def_nemonico ASC";
	$res = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($res);

	//Nombre de la empresa sola
	$sqlem = "SELECT * FROM empresa WHERE nemonico='$cef_nemonico'";
	$resem = mysqli_query($link, $sqlem);
	$rowem = mysqli_fetch_array($resem);
	$nombre_empresa = $rowem['nombre'];

	$cab_fe_ini = '';
	if($cef_trim == 4){$cab_fe_ini = '31/12/'.($cef_anio);}
	if($cef_trim == 3){$cab_fe_ini = '30/09/'.($cef_anio);}
	if($cef_trim == 2){$cab_fe_ini = '30/06/'.($cef_anio);}
	if($cef_trim == 1){$cab_fe_ini = '31/03/'.($cef_anio);}
	$cab_fe_fin = '31/12/'.($cef_anio-1);

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

function importarEstadoFinanciero($ruta, $condicion, $modo){

	include($ruta.'/Util/simple_html_dom_php5.6.php');
	include($ruta.'/Config/Conexion.php');
	$link = getConexion();

	$sql = "SELECT * FROM empresa WHERE cod_emp_bvl!='' AND imp_sit_fin!='' $condicion";
	$res = mysqli_query($link, $sql);

	$tri_auto = 1;
	$mes_auto = (int)date('m');
	if($mes_auto==1 || $mes_auto==2 || $mes_auto==3){$tri_auto = 1;}
	if($mes_auto==4 || $mes_auto==5 || $mes_auto==6){$tri_auto = 2;}
	if($mes_auto==7 || $mes_auto==8 || $mes_auto==9){$tri_auto = 3;}
	if($mes_auto==10 || $mes_auto==11 || $mes_auto==12){$tri_auto = 4;}

	if($modo == 'manual'){
		$cef_anio = $_GET['cef_anio'];
		$cef_peri = $_GET['cef_peri'];
		$cef_tipo = $_GET['cef_tipo'];
		$cef_trim = $_GET['cef_trim'];
	}else{
		$cef_anio = date('Y');
		$cef_peri = 'T';
		$cef_tipo = 'C';
		$cef_trim = $mes_auto;
	}

	if($cef_peri == 'A'){$cef_trim = 'A';}

	$cef_form = "BAL";
	$cef_stat ='10';
	
	while($row = mysqli_fetch_array($res)){
		
		$new_codigo = $row['cod_emp_bvl'];
		$new_nemonico = $row['nemonico'];
		$imp_sit_fin = $row['imp_sit_fin'];
		$razon_social = "";
		$cef_fech_crea = date('Y-m-d');
		$cef_hora_crea = date('H:i:s');

		//$url  = "https://www.bvl.com.pe/jsp/Inf_EstadisticaGrafica.jsp?Cod_Empresa=$new_codigo&Nemonico=$new_nemonico&Listado=|$new_nemonico";
		$url = "https://www.bvl.com.pe/jsp/ShowEEFF_new.jsp?Ano=$cef_anio&Trimestre=$cef_trim&Rpj=$imp_sit_fin&RazoSoci=$razon_social&TipoEEFF=$cef_form&Tipo1=$cef_peri&Tipo2=$cef_tipo&Dsc_Correlativo=0000&Secuencia=0";
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
							$sqlinc = "INSERT INTO cab_estado_financiero(cef_cod,cef_cod_bvl,cef_nomb,cef_cab_det,cef_stat,cef_fech_crea,cef_hora_crea)VALUES
							('$cef_cod','$cef_cod_bvl','$cef_nomb','$cef_cab_det','$cef_stat','$cef_fech_crea','$cef_hora_crea')";
							$resinc = mysqli_query($link, $sqlinc);
						}

						//Insertamos detalle
						$sqlvd = "SELECT cef_cod, cef_cod_bvl FROM det_estado_financiero WHERE cef_cod='$cef_cod' AND cef_cod_bvl='$cef_cod_bvl' AND def_nemonico='$new_nemonico' AND def_peri='$cef_peri' AND def_trim='$cef_trim' AND def_anio='$cef_anio' AND def_tipo='$cef_tipo' AND def_form='$cef_form' LIMIT 1";
						$resvd = mysqli_query($link, $sqlvd);
						$rowvd = mysqli_fetch_array($resvd);
						$cef_cod_det = $rowvd['cef_cod'];

						if($cef_cod_det == ''){
							$sqlinc = "INSERT INTO det_estado_financiero(cef_cod,cef_cod_bvl,def_nemonico,def_cab_det,def_val_de, def_val_ha,def_peri,def_trim,def_anio,def_tipo,def_form,def_fech_crea,def_hora_crea)VALUES
							('$cef_cod','$cef_cod_bvl','$new_nemonico','$cef_cab_det','$def_val_de','$def_val_ha','$cef_peri','$cef_trim','$cef_anio','$cef_tipo','$cef_form','$cef_fech_crea','$cef_hora_crea')";
							$resinc = mysqli_query($link, $sqlinc) or die(mysqli_error($link));
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

	$cef_nemonico = $_GET['cef_nemonico'];
	$ruta = "..";

	$condicion = "";
	if($cef_nemonico !=''){
		$condicion .= " AND nemonico='$cef_nemonico'";
	}

	importarEstadoFinanciero($ruta, $condicion, 'manual');
}

function importarAutomaticolAction(){

	$ruta = "public_html/analisisdevalor.com";
	$condicion = "";
	
	importarEstadoFinanciero($ruta, $condicion, 'automatico');
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