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

	$der_nemonico = $_GET['cer_nemonico'];
	$der_anio = $_GET['cer_anio'];
	$der_peri = $_GET['cer_peri'];
	$der_tipo = $_GET['cer_tipo'];
	$der_trim = $_GET['cer_trim'];

	$sql = "SELECT * FROM cab_estado_resultado c
			INNER JOIN det_estado_resultado d ON(c.cer_cod=d.der_cod AND c.cer_cod_bvl=d.der_cod_bvl)
			WHERE c.cer_stat='10'";

	if ($der_nemonico != '') { $sql .= " AND d.der_nemonico='$der_nemonico'";}
	if ($der_anio != '') { $sql .= " AND d.der_anio='$der_anio'";}
	if ($der_peri != '') { $sql .= " AND d.der_peri='$der_peri'";}
	if ($der_tipo != '') { $sql .= " AND d.der_tipo='$der_tipo'";}
	if ($der_trim != '') { $sql .= " AND d.der_trim='$der_trim'";}

	$sql .= " ORDER BY c.cer_cod, d.der_nemonico ASC";
	//echo $sql;
	$res = mysqli_query($link, $sql);
	$nro_reg = mysqli_num_rows($res);

	//Nombre de la empresa sola
	$sqlem = "SELECT * FROM empresa WHERE nemonico='$der_nemonico'";
	$resem = mysqli_query($link, $sqlem);
	$rowem = mysqli_fetch_array($resem);
	$nombre_empresa = $rowem['nombre'];


	include('../View/EstadoResultado/listar.php');
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

function importarEstadoResultado($ruta, $condicion, $modo){

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
		$der_anio = $_GET['cer_anio'];
		$der_peri = $_GET['cer_peri'];
		$der_tipo = $_GET['cer_tipo'];
		$der_trim = $_GET['cer_trim'];
	}else{
		$der_anio = date('Y');
		$der_peri = 'T';
		$der_tipo = 'C';
		$der_trim = $mes_auto;
	}

	if($der_peri == 'A'){$der_trim = 'A';}

	$der_form = "GYP";
	$cer_stat ='10';
	
	while($row = mysqli_fetch_array($res)){
		
		$new_codigo = $row['cod_emp_bvl'];
		$new_nemonico = $row['nemonico'];
		$imp_sit_fin = $row['imp_sit_fin'];
		$razon_social = "";
		$cer_fech_crea = date('Y-m-d');
		$cer_hora_crea = date('H:i:s');

		//$url  = "https://www.bvl.com.pe/jsp/ShowEEFF_new.jsp?Ano=2019&Trimestre=3&Rpj=023106&RazoSoci=GRANA%20Y%20MONTERO%20SAA&TipoEEFF=GYP&Tipo1=T&Tipo2=I&Dsc_Correlativo=0000&Secuencia=0";
		$url = "https://www.bvl.com.pe/jsp/ShowEEFF_new.jsp?Ano=$der_anio&Trimestre=$der_trim&Rpj=$imp_sit_fin&RazoSoci=$razon_social&TipoEEFF=$der_form&Tipo1=$der_peri&Tipo2=$der_tipo&Dsc_Correlativo=0000&Secuencia=0";
		$html = file_get_contents_curl($url);

		if (!empty($html)) {

			$table_1 = $html->find('table',3);

			if(isset($table_1) && $table_1 !='' && $table_1 !=null && isset($table_1->find('tr',0)->plaintext) && $table_1->find('tr',0)->plaintext !=''){

				$cont_tr = 1;
				foreach($table_1->find("tr") as $tr){
					
					//Detalles
					if ($cont_tr>3 && (isset($tr->find('td',0)->plaintext) || isset($tr->find('th',0)->plaintext))) {
						//echo trim($tr->find('td',0)->plaintext)."<br>";
						$cer_cod_bvl = $cer_nomb = "";
						$cer_cab_det = 'DET';
						if(isset($tr->find('th',0)->plaintext)){ 
							$cer_cod_bvl = trim($tr->find('th',0)->plaintext);
							$cer_nomb = trim($tr->find('th',1)->plaintext);
							$cer_cab_det = 'CAB';
							$der_val_tr1 = trim($tr->find('th',3)->plaintext);$der_val_tr1 = str_replace(',','',$der_val_tr1);$der_val_tr1 = str_replace('&nbsp;','',$der_val_tr1);
							$der_val_tr2 = trim($tr->find('th',4)->plaintext);$der_val_tr2 = str_replace(',','',$der_val_tr2);$der_val_tr2 = str_replace('&nbsp;','',$der_val_tr2);
							$der_val_tr3 = trim($tr->find('th',5)->plaintext);$der_val_tr3 = str_replace(',','',$der_val_tr3);$der_val_tr3 = str_replace('&nbsp;','',$der_val_tr3);
							$der_val_tr4 = trim($tr->find('th',6)->plaintext);$der_val_tr4 = str_replace(',','',$der_val_tr4);$der_val_tr4 = str_replace('&nbsp;','',$der_val_tr4);
							$der_val1_vac = ($der_val_tr1!='')?0:1;
							$der_val2_vac = ($der_val_tr2!='')?0:1;
							$der_val3_vac = ($der_val_tr3!='')?0:1;
							$der_val4_vac = ($der_val_tr4!='')?0:1;
						}
						if(isset($tr->find('td',0)->plaintext)){ 
							$cer_cod_bvl = trim($tr->find('td',0)->plaintext);
							$cer_td_01 = trim(str_replace('&nbsp;','',$tr->find('td',1)->plaintext));
							$cer_td_02 = trim(str_replace('&nbsp;','',$tr->find('td',2)->plaintext));
							$cer_nomb = ($cer_td_01 != '')?$cer_td_01:$cer_td_02;
							$cer_cab_det = ($cer_td_01 !='')?'DETG':'DET';

							if($cer_cab_det == 'DET'){
								$der_val_tr1 = trim($tr->find('td',4)->plaintext);$der_val_tr1 = str_replace(',','',$der_val_tr1);$der_val_tr1 = str_replace('&nbsp;','',$der_val_tr1);
								$der_val_tr2 = trim($tr->find('td',5)->plaintext);$der_val_tr2 = str_replace(',','',$der_val_tr2);$der_val_tr2 = str_replace('&nbsp;','',$der_val_tr2);
								$der_val_tr3 = trim($tr->find('td',6)->plaintext);$der_val_tr3 = str_replace(',','',$der_val_tr3);$der_val_tr3 = str_replace('&nbsp;','',$der_val_tr3);
								$der_val_tr4 = trim($tr->find('td',7)->plaintext);$der_val_tr4 = str_replace(',','',$der_val_tr4);$der_val_tr4 = str_replace('&nbsp;','',$der_val_tr4);
							}else{
								$der_val_tr1 = trim($tr->find('td',3)->plaintext);$der_val_tr1 = str_replace(',','',$der_val_tr1);$der_val_tr1 = str_replace('&nbsp;','',$der_val_tr1);
								$der_val_tr2 = trim($tr->find('td',4)->plaintext);$der_val_tr2 = str_replace(',','',$der_val_tr2);$der_val_tr2 = str_replace('&nbsp;','',$der_val_tr2);
								$der_val_tr3 = trim($tr->find('td',5)->plaintext);$der_val_tr3 = str_replace(',','',$der_val_tr3);$der_val_tr3 = str_replace('&nbsp;','',$der_val_tr3);
								$der_val_tr4 = trim($tr->find('td',6)->plaintext);$der_val_tr4 = str_replace(',','',$der_val_tr4);$der_val_tr4 = str_replace('&nbsp;','',$der_val_tr4);
							}
							$der_val1_vac = ($der_val_tr1!='')?0:1;
							$der_val2_vac = ($der_val_tr2!='')?0:1;
							$der_val3_vac = ($der_val_tr3!='')?0:1;
							$der_val4_vac = ($der_val_tr4!='')?0:1;						
						}

						//Validamos si ya se registro la linea
						$sqlvc = "SELECT cer_cod, cer_cod_bvl FROM cab_estado_resultado WHERE cer_cod_bvl='$cer_cod_bvl' LIMIT 1";
						$resvc = mysqli_query($link, $sqlvc);
						$rowvc = mysqli_fetch_array($resvc);
						$cer_cod = $rowvc['cer_cod'];

						if($cer_cod==''){
							//Obtenemos el nuevo codigo
							$sqlnc = "SELECT MAX(cer_cod)AS cer_cod FROM cab_estado_resultado";
							$resnc = mysqli_query($link, $sqlnc);
							$rownc = mysqli_fetch_array($resnc);
							$cer_cod = ($rownc['cer_cod']!='')?$rownc['cer_cod']+1:'1000';

							//Insertamos cabecera
							$sqlinc = "INSERT INTO cab_estado_resultado(cer_cod,cer_cod_bvl,cer_nomb,cer_cab_det,cer_stat,cer_fech_crea,cer_hora_crea)VALUES
							('$cer_cod','$cer_cod_bvl','$cer_nomb','$cer_cab_det','$cer_stat','$cer_fech_crea','$cer_hora_crea')";
							$resinc = mysqli_query($link, $sqlinc);
						}

						//Insertamos detalle
						$sqlvd = "SELECT der_cod, der_cod_bvl FROM det_estado_resultado WHERE der_cod='$cer_cod' AND der_cod_bvl='$cer_cod_bvl' AND der_nemonico='$new_nemonico' AND der_peri='$der_peri' AND der_trim='$der_trim' AND der_anio='$der_anio' AND der_tipo='$der_tipo' AND der_form='$der_form' LIMIT 1";
						$resvd = mysqli_query($link, $sqlvd);
						$rowvd = mysqli_fetch_array($resvd);
						$der_cod_det = $rowvd['der_cod'];

						if($der_cod_det == ''){
							$sqlinc = "INSERT INTO det_estado_resultado(der_cod,der_cod_bvl,der_nemonico,der_cab_det,der_val_tr1,der_val_tr2,der_val_tr3,der_val_tr4,der_peri,der_trim,der_anio,der_tipo,der_form,der_fech_crea,der_hora_crea,der_val1_vac,der_val2_vac,der_val3_vac,der_val4_vac)VALUES
							('$cer_cod','$cer_cod_bvl','$new_nemonico','$cer_cab_det','$der_val_tr1','$der_val_tr2','$der_val_tr3','$der_val_tr4','$der_peri','$der_trim','$der_anio','$der_tipo','$der_form','$cer_fech_crea','$cer_hora_crea','$der_val1_vac','$der_val2_vac','$der_val3_vac','$der_val4_vac')";
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

	$cer_nemonico = $_GET['cer_nemonico'];
	$ruta = "..";

	$condicion = "";
	if($cer_nemonico !=''){
		$condicion .= " AND nemonico='$cer_nemonico'";
	}

	importarEstadoResultado($ruta, $condicion, 'manual');
}

function importarAutomaticolAction(){

	$ruta = "public_html/analisisdevalor.com";
	$condicion = "";
	
	importarEstadoResultado($ruta, $condicion, 'automatico');
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