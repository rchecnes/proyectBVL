<?php
function indexAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	//Datos por defecto analisis - Fundamentalista
	$anio_min = 2000;
	$anio_max = date('Y')-1;
	$anio_def = $anio_max - 10;

	//Datos por defecto analisis - EEFF
	list($tri_def, $trim_arr) = getTrimestres(14,14);
	
	include('../Control/Combobox/Combobox.php');
	include('../View/EstadoFinanciero/index.php');
}

function listarAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cef_emp_cod = $_GET['cef_emp_cod'];
	$cef_anio = $_GET['cef_anio'];
	$cef_peri = $_GET['cef_peri'];
	$cef_tipo = $_GET['cef_tipo'];
	$cef_trim = $_GET['cef_trim'];
	if($cef_peri == 'A'){$cef_trim = 'A';}

	$sql = "SELECT * FROM cab_estado_financiero c
			INNER JOIN det_estado_financiero d ON(c.cef_cod=d.cef_cod AND c.cef_cod_bvl=d.cef_cod_bvl)
			WHERE c.cef_stat='10'";

	if ($cef_emp_cod != '') { $sql .= " AND d.emp_cod='$cef_emp_cod'";}
	if ($cef_anio != '') { $sql .= " AND d.def_anio='$cef_anio'";}
	if ($cef_peri != '') { $sql .= " AND d.def_peri='$cef_peri'";}
	if ($cef_tipo != '') { $sql .= " AND d.def_tipo='$cef_tipo'";}
	if ($cef_trim != '') { $sql .= " AND d.def_trim='$cef_trim'";}

	$sql .= " ORDER BY c.cef_cod ASC";
	$res = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($res);

	//Nombre de la empresa sola
	$sqlem = "SELECT em.emp_nomb FROM empresa em WHERE em.emp_cod='$cef_emp_cod'";
	$resem = mysqli_query($link, $sqlem);
	$rowem = mysqli_fetch_array($resem);
	$nombre_empresa = $rowem['emp_nomb'];

	$cab_fe_ini = '';
	if($cef_trim == 4){$cab_fe_ini = '31/12/'.($cef_anio);}
	if($cef_trim == 3){$cab_fe_ini = '30/09/'.($cef_anio);}
	if($cef_trim == 2){$cab_fe_ini = '30/06/'.($cef_anio);}
	if($cef_trim == 1){$cab_fe_ini = '31/03/'.($cef_anio);}
	if($cef_trim == 'A'){$cab_fe_ini = '31/12/'.($cef_anio);}
	$cab_fe_fin = '31/12/'.($cef_anio-1);

	include('../View/EstadoFinanciero/listar.php');
}

function getTrimestres($cant_tri, $def){

	$mes = is_null($mes) ? date('m') : $mes;
	$trim_act = floor(($mes-1) / 3)+1;
	//return $trim;

	$tri_min=$tri_max=$tri_def='';
	$trim_arr = array();

	for($i=1; $i<=$trim_act; $i++){
		$trim_arr[] = date('Y').'-'.$i;
	}

	$cont_e = 0;
	for($a=1; $a<=$cant_tri; $a++){

		$cont_e ++;

		for($e=4; $e>=1; $e--){
			if(count($trim_arr) < $cant_tri){
				$trim_arr[] = (date('Y')-$cont_e).'-'.$e;
			}
		}
		if(count($trim_arr) == $cant_tri){
			break;
		}	
	}

	//mayor a menor
	rsort($trim_arr);

	for($t=0; $t<count($trim_arr); $t++){
		if($t == $def-1){
			$tri_def = $trim_arr[$t];
		}
	}

	//mayor a menor
	sort($trim_arr);

	return array($tri_def, $trim_arr);
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

	$sql = "SELECT * FROM empresa WHERE emp_cod_bvl!='' AND emp_cod_rpj!='' $condicion";
	$res = mysqli_query($link, $sql);

	$tri_auto = 1;
	$mes_auto = (int)date('m');
	if($mes_auto==1 || $mes_auto==2 || $mes_auto==3){$tri_auto = 4; $cef_anio = date('Y')-1;}
	if($mes_auto==4 || $mes_auto==5 || $mes_auto==6){$tri_auto = 1; $cef_anio = date('Y');}
	if($mes_auto==7 || $mes_auto==8 || $mes_auto==9){$tri_auto = 2; $cef_anio = date('Y');}
	if($mes_auto==10 || $mes_auto==11 || $mes_auto==12){$tri_auto = 3; $cef_anio = date('Y');}

	if($modo == 'manual'){
		$cef_anio = $_GET['cef_anio'];
		$cef_peri = $_GET['cef_peri'];
		$cef_tipo = $_GET['cef_tipo'];
		$cef_trim = $_GET['cef_trim'];
	}else{
		//$cef_anio = '2019';//date('Y');
		$cef_peri = 'T';
		$cef_tipo = 'C';
		$cef_trim = $tri_auto;
	}

	if($cef_peri == 'A'){$cef_trim = 'A';}

	$cef_form = "BAL";
	$cef_stat ='10';
	
	while($row = mysqli_fetch_array($res)){
		
		$emp_cod_bvl = $row['emp_cod_bvl'];
		$emp_cod = $row['emp_cod'];
		$emp_cod_rpj = $row['emp_cod_rpj'];
		$razon_social = "";
		$cef_fech_crea = date('Y-m-d');
		$cef_hora_crea = date('H:i:s');

		$url = "https://www.bvl.com.pe/jsp/ShowEEFF_new.jsp?Ano=$cef_anio&Trimestre=$cef_trim&Rpj=$emp_cod_rpj&RazoSoci=$razon_social&TipoEEFF=$cef_form&Tipo1=$cef_peri&Tipo2=$cef_tipo&Dsc_Correlativo=0000&Secuencia=0";
		$html = file_get_contents_curl($url);

		if (!empty($html)) {

			$table_1 = $html->find('table',3);
			if(isset($table_1) && $table_1 !='' && $table_1 !=null && isset($table_1->find('tr',0)->plaintext) && $table_1->find('tr',0)->plaintext !=''){

				$cont_tr = 1;
				foreach($table_1->find("tr") as $tr){
					
					//Detalles
					if ($cont_tr>3 && (isset($tr->find('td',0)->plaintext) || isset($tr->find('th',0)->plaintext))) {

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
							//$cef_cod_bvl = trim($tr->find('td',0)->plaintext);
							//$cef_nomb = trim($tr->find('td',2)->plaintext);
							//$cef_cab_det = 'DET';

							$cef_cod_bvl = trim($tr->find('td',0)->plaintext);
							$cef_td_01 = trim(str_replace('&nbsp;','',$tr->find('td',1)->plaintext));
							$cef_td_02 = trim(str_replace('&nbsp;','',$tr->find('td',2)->plaintext));
							$cef_nomb = ($cef_td_01 != '')?$cef_td_01:$cef_td_02;
							$cef_cab_det = ($cef_td_01 !='')?'DETG':'DET';

							if($cef_cab_det == 'DETG'){

								$def_val_de = trim($tr->find('td',3)->plaintext);$def_val_de = str_replace(',','',str_replace('&nbsp;','',$def_val_de));
								$def_val_ha = trim($tr->find('td',4)->plaintext);$def_val_ha = str_replace(',','',str_replace('&nbsp;','',$def_val_ha));
							}else{
								
								$def_val_de = trim($tr->find('td',4)->plaintext);$def_val_de = str_replace(',','',$def_val_de);
								$def_val_ha = trim($tr->find('td',5)->plaintext);$def_val_ha = str_replace(',','',$def_val_ha);
							}
						}
						//echo $cef_cod_bvl."-".$def_val_de."-".$def_val_ha."-".$cef_cab_det."<br>";
						
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
						$sqlvd = "SELECT cef_cod, cef_cod_bvl FROM det_estado_financiero WHERE cef_cod='$cef_cod' AND cef_cod_bvl='$cef_cod_bvl' AND emp_cod='$emp_cod' AND def_peri='$cef_peri' AND def_trim='$cef_trim' AND def_anio='$cef_anio' AND def_tipo='$cef_tipo' AND def_form='$cef_form' LIMIT 1";
						$resvd = mysqli_query($link, $sqlvd);
						$rowvd = mysqli_fetch_array($resvd);
						$cef_cod_det = $rowvd['cef_cod'];

						if($cef_cod_det == ''){
							$sqlinc = "INSERT INTO det_estado_financiero(cef_cod,cef_cod_bvl,emp_cod,def_cab_det,def_val_de, def_val_ha,def_peri,def_trim,def_anio,def_tipo,def_form,def_fech_crea,def_hora_crea,emp_cod_rpj)VALUES
							('$cef_cod','$cef_cod_bvl','$emp_cod','$cef_cab_det','$def_val_de','$def_val_ha','$cef_peri','$cef_trim','$cef_anio','$cef_tipo','$cef_form','$cef_fech_crea','$cef_hora_crea','$emp_cod_rpj')";
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

	$cef_emp_cod = $_GET['cef_emp_cod'];
	$ruta = "..";

	$condicion = "";
	if($cef_emp_cod !=''){
		$condicion .= " AND emp_cod='$cef_emp_cod'";
	}

	importarEstadoFinanciero($ruta, $condicion, 'manual');
}

function importarAutomaticolAction(){

	$ruta = "public_html/analisisdevalor.com";
	$condicion = "";
	
	importarEstadoFinanciero($ruta, $condicion, 'automatico');
}

function getImpoEstadoResAnual($link, $emp_cod, $cod_bvl, $anio, $periodo, $tipo){

	$new_cod_bvl = '';
	$exp_cod_bvl = explode('@',$cod_bvl);
	for($c=0;$c<count($exp_cod_bvl);$c++){
		if($exp_cod_bvl[$c] != ''){$new_cod_bvl.="'".$exp_cod_bvl[$c]."',";}
	}
	$new_cod_bvl = trim($new_cod_bvl,',');

	$sql1 = "SELECT der_val_tr1 FROM det_estado_resultado WHERE emp_cod='$emp_cod' AND der_cod_bvl IN($new_cod_bvl) AND der_anio='$anio' AND der_peri='$periodo' AND der_tipo='$tipo'";
	$res1 = mysqli_query($link, $sql1);
	$row1 = mysqli_fetch_array($res1);

	$new_anio = $anio + 1;
	$sql2 = "SELECT der_val_tr2 FROM det_estado_resultado WHERE emp_cod='$emp_cod' AND der_cod_bvl IN($new_cod_bvl) AND der_anio='$new_anio' AND der_peri='$periodo' AND der_tipo='$tipo'";
	$res2 = mysqli_query($link, $sql2);
	$row2 = mysqli_fetch_array($res2);

	$impo_ret = ($row2['der_val_tr2'] !='' && $row2['der_val_tr2'] != 0)?$row2['der_val_tr2']:$row1['der_val_tr1'];

	return ($impo_ret != '' && $impo_ret != 0)?$impo_ret:0;
}

function getImpoEstadoFinAnual($link, $emp_cod, $cod_bvl, $anio, $periodo, $tipo){

	$new_cod_bvl = '';
	$exp_cod_bvl = explode('@',$cod_bvl);
	for($c=0;$c<count($exp_cod_bvl);$c++){
		if($exp_cod_bvl[$c] != ''){$new_cod_bvl.="'".$exp_cod_bvl[$c]."',";}
	}
	$new_cod_bvl = trim($new_cod_bvl,',');

	$sql1 = "SELECT def_val_de FROM det_estado_financiero WHERE emp_cod='$emp_cod' AND cef_cod_bvl IN($new_cod_bvl) AND def_anio='$anio' AND def_peri='$periodo' AND def_tipo='$tipo'";
	$res1 = mysqli_query($link, $sql1);
	$row1 = mysqli_fetch_array($res1);

	$new_anio = $anio + 1;
	$sql2 = "SELECT def_val_ha FROM det_estado_financiero WHERE emp_cod='$emp_cod' AND cef_cod_bvl IN($new_cod_bvl) AND def_anio='$new_anio' AND def_peri='$periodo' AND def_tipo='$tipo'";
	$res2 = mysqli_query($link, $sql2);
	$row2 = mysqli_fetch_array($res2);

	$impo_ret = ($row2['def_val_ha'] !='' && $row2['def_val_ha'] != 0)?$row2['def_val_ha']:$row1['def_val_de'];

	return ($impo_ret != '' && $impo_ret != 0)?$impo_ret:0;
}

function analisisAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cefa_emp_cod = $_GET['cefa_emp_cod'];
	$cefa_anio = $_GET['cefa_anio'];
	$cefa_tipo = $_GET['cefa_tipo'];
	$cant_coslpan = 0;//(date('Y')-1)-$cefa_anio;

	$anio_arr = array();
	for($a=$cefa_anio; $a<=date('Y')-1; $a++){$anio_arr[] = $a;}
	
	//Array General Cuadro
	$ventas_arr = $util_bru_arr = $util_ope_arr = $util_net_arr = $tot_pas_arr = $tot_pat_arr = $tot_act_arr = $end_arr = $mar_bru_arr = $mar_ope_arr = $mar_net_arr = $rot_act_arr = $roa_arr = $roe_arr = array();
	$ventas_grfco = $util_bru_grfco = $util_ope_grfco = $util_net_grfco = $tot_pas_grfco = $tot_pat_grfco = $tot_act_grfco = $end_grfco = $mar_bru_grfco = $mar_ope_grfco = $mar_net_grfco = $rot_act_grfco = $roa_grfco = $roe_grfco = array();

	$new_anio_arr = array();
	foreach($anio_arr as $anio){

		//Ventas
		$impo_ventas = getImpoEstadoResAnual($link, $cefa_emp_cod, '2D01ST@2F01ST@2E0201@2A01ST', $anio, 'A',$cefa_tipo);
		if($impo_ventas > 0){

			$cant_coslpan ++;

			$new_anio_arr[] = $anio;
			
			//Ventas
			$ventas_arr[$anio] = array('anio'=>$anio,'impo'=>$impo_ventas);
			$ventas_grfco[] = (double)number_format($impo_ventas,0,'','');

			//Utilidad Bruta
			$impo_util_bru = getImpoEstadoResAnual($link, $cefa_emp_cod, '2D02ST@2F2401@2E0901', $anio, 'A',$cefa_tipo);
			$util_bru_arr[$anio] = array('anio'=>$anio,'impo'=>$impo_util_bru);
			$util_bru_grfco[] = (double)number_format($impo_util_bru,0,'','');

			//Utilidad Operativa
			$impo_util_ope = getImpoEstadoResAnual($link, $cefa_emp_cod, '2D03ST@2F2801@2E1501@2A03ST', $anio, 'A',$cefa_tipo);
			$util_ope_arr[$anio] = array('anio'=>$anio,'impo'=>$impo_util_ope);
			$util_ope_grfco[] = (double)number_format($impo_util_ope,0,'','');

			//Utilidad Neta
			$impo_util_net = getImpoEstadoResAnual($link, $cefa_emp_cod, '2D07ST@2F1901@2E1509@2A07ST', $anio, 'A',$cefa_tipo);
			$util_net_arr[$anio] = array('anio'=>$anio,'impo'=>$impo_util_net);
			$util_net_grfco[] = (double)number_format($impo_util_net,0,'','');

			//Total Pasivo
			$impo_pasi = getImpoEstadoFinAnual($link, $cefa_emp_cod, '1D040T@1F3101@1E0501@1A040T', $anio, 'A',$cefa_tipo);
			$tot_pas_arr[$anio] = array('anio'=>$anio,'impo'=>$impo_pasi);
			$tot_pas_grfco[] = (double)number_format($impo_pasi,0,'','');

			//Total Patrimonio
			$impo_pat = getImpoEstadoFinAnual($link, $cefa_emp_cod, '1D07ST@1F3306@1E0901@1A07ST', $anio, 'A',$cefa_tipo);
			$tot_pat_arr[$anio] = array('anio'=>$anio,'impo'=>$impo_pat);
			$tot_pat_grfco[] = (double)number_format($impo_pat,0,'','');

			//Total Activo
			$impo_act = getImpoEstadoFinAnual($link, $cefa_emp_cod, '1D020T@1F2001@1E02ST@1A020T', $anio, 'A',$cefa_tipo);
			$tot_act_arr[$anio] = array('anio'=>$anio,'impo'=>$impo_act);
			$tot_act_grfco[] = (double)number_format($impo_act,0,'','');

			//Endeudamiento
			$impo_end = ($impo_act!=0)?($impo_pasi/$impo_act)*100:0;
			$end_arr[$anio] =  array('anio'=>$anio,'impo'=>$impo_end);
			$end_grfco[] = (double)number_format($impo_end,0,'','');

			//Margen Bruto
			$impo_mgbt = ($impo_ventas!=0)?($impo_util_bru/$impo_ventas)*100:0;
			$mar_bru_arr[$anio] =  array('anio'=>$anio,'impo'=>$impo_mgbt);
			$mar_bru_grfco[] = (double)number_format($impo_mgbt,0,'','');

			//Margen Operativo
			$impo_mgop = ($impo_ventas !=0)? ($impo_util_ope/$impo_ventas)*100:0;
			$mar_ope_arr[$anio] =  array('anio'=>$anio,'impo'=>$impo_mgop);
			$mar_ope_grfco[] = (double)number_format($impo_mgop,0,'','');

			//Margen Neto
			$impo_mgnt = ($impo_ventas!=0)?($impo_util_net/$impo_ventas)*100:0;
			$mar_net_arr[$anio] =  array('anio'=>$anio,'impo'=>$impo_mgnt);
			$mar_net_grfco[] = (double)number_format($impo_mgnt,0,'','');

			//Rotación del Activo
			$impo_rtac = ($impo_act != 0)?($impo_ventas/$impo_act):0;
			$rot_act_arr[$anio] =  array('anio'=>$anio,'impo'=>$impo_rtac);
			$rot_act_grfco[] = (double)number_format($impo_rtac,2,'','');

			//ROA
			$impo_roa = ($impo_act != 0)?($impo_util_ope/$impo_act)*100:0;
			$roa_arr[$anio] =  array('anio'=>$anio,'impo'=>$impo_roa);
			$roa_grfco[] = (double)number_format($impo_roa,0,'','');

			//ROE
			$impo_roe = ($impo_pat != 0)?($impo_util_net/$impo_pat)*100:0;
			$roe_arr[$anio] =  array('anio'=>$anio,'impo'=>$impo_roe);
			$roe_grfco[] = (double)number_format($impo_roe,0,'','');
		}
	}

	//var_dump($ventas_arr);
	include('../View/EstadoFinanciero/analisis.php');
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
	case 'analisis':
		analisisAction();
		break;
	default:
		# code...
		break;
}