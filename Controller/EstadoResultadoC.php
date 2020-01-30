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
	if($der_peri == 'A'){$der_trim = 'A';}

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
	$sqlem = "SELECT em.emp_nomb FROM nemonico ne LEFT JOIN empresa em ON(ne.emp_cod=em.emp_cod) WHERE ne.nemonico='$der_nemonico'";
	$resem = mysqli_query($link, $sqlem);
	$rowem = mysqli_fetch_array($resem);
	$nombre_empresa = $rowem['emp_nomb'];

	$tot_info_1 = $tot_info_2= $tot_info_3 = $tot_info_4='';
	if($der_trim == 4 || $der_trim == 3 || $der_trim == 2 || $der_trim == 1){

		$trimestre = "";
		if($der_trim == 1){$trimestre="Primer";}
		if($der_trim == 2){$trimestre="Segundo";}
		if($der_trim == 3){$trimestre="Tercero";}
		if($der_trim == 4){$trimestre="Cuarto";}

		$tot_info_1 = "$trimestre Trimestre $der_anio";
		$tot_info_2 = "$trimestre Trimestre ".($der_anio-1);
		$tot_info_3 = "Por el Periodo acumulado del 1 de Enero de 2019 al 30 de Setiembre de 2019";
		$tot_info_4 = "Por el Periodo acumulado del 1 de Enero de 2018 al 30 de Setiembre de 2018";
	}
	if($der_trim == A){
		$tot_info_1 = '31/12/'.$der_anio;
		$tot_info_2 = '31/12/'.($der_anio-1);
	}

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

function getImpoEstadoResAnual($link, $nemonico, $cod_bvl, $anio, $trimestre, $periodo, $tipo){

	$new_cod_bvl = '';
	$exp_cod_bvl = explode('@',$cod_bvl);
	for($c=0;$c<count($exp_cod_bvl);$c++){
		if($exp_cod_bvl[$c] != ''){$new_cod_bvl.="'".$exp_cod_bvl[$c]."',";}
	}
	$new_cod_bvl = trim($new_cod_bvl,',');

	$sql1 = "SELECT der_val_tr1 FROM det_estado_resultado WHERE der_nemonico='$nemonico' AND der_cod_bvl IN($new_cod_bvl) AND der_anio='$anio' AND der_trim='$trimestre' AND der_peri='$periodo' AND der_tipo='$tipo'";
	$res1 = mysqli_query($link, $sql1);
	$row1 = mysqli_fetch_array($res1);

	$new_anio = $anio + 1;
	$sql2 = "SELECT der_val_tr2 FROM det_estado_resultado WHERE der_nemonico='$nemonico' AND der_cod_bvl IN($new_cod_bvl) AND der_anio='$new_anio' AND der_trim='$trimestre' AND der_peri='$periodo' AND der_tipo='$tipo'";
	$res2 = mysqli_query($link, $sql2);
	$row2 = mysqli_fetch_array($res2);

	$impo_ret = ($row2['der_val_tr2'] !='' && $row2['der_val_tr2'] != 0)?$row2['der_val_tr2']:$row1['der_val_tr1'];

	//$impo_ret = $row1['der_val_tr1'];

	return ($impo_ret != '' && $impo_ret != 0)?$impo_ret:0;
}

function getImpoEstadoFinAnual($link, $nemonico, $cod_bvl, $anio, $trimestre, $periodo, $tipo){

	$new_cod_bvl = '';
	$exp_cod_bvl = explode('@',$cod_bvl);
	for($c=0;$c<count($exp_cod_bvl);$c++){
		if($exp_cod_bvl[$c] != ''){$new_cod_bvl.="'".$exp_cod_bvl[$c]."',";}
	}
	$new_cod_bvl = trim($new_cod_bvl,',');

	$sql1 = "SELECT def_val_de FROM det_estado_financiero WHERE def_nemonico='$nemonico' AND cef_cod_bvl IN($new_cod_bvl) AND def_anio='$anio' AND def_trim='$trimestre' AND def_peri='$periodo' AND def_tipo='$tipo'";
	$res1 = mysqli_query($link, $sql1);
	$row1 = mysqli_fetch_array($res1);

	$new_anio = $anio + 1;
	$sql2 = "SELECT def_val_ha FROM det_estado_financiero WHERE def_nemonico='$nemonico' AND cef_cod_bvl IN($new_cod_bvl) AND def_anio='$new_anio'  AND def_trim='$trimestre' AND def_peri='$periodo' AND def_tipo='$tipo'";
	$res2 = mysqli_query($link, $sql2);
	$row2 = mysqli_fetch_array($res2);

	$impo_ret = ($row2['def_val_ha'] !='' && $row2['def_val_ha'] != 0)?$row2['def_val_ha']:$row1['def_val_de'];

	return ($impo_ret != '' && $impo_ret != 0)?$impo_ret:0;
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

function orderArrayMultiDim ($toOrderArray, $field, $inverse = false) {  
    $position = array();  
    $newRow = array();  
    foreach ($toOrderArray as $key => $row) {  
            $position[$key]  = $row[$field];  
            $newRow[$key] = $row;  
    }  
    if ($inverse) {  
        arsort($position);  
    }  
    else {  
        asort($position);  
    }  
    $returnArray = array();  
    foreach ($position as $key => $pos) {       
        $returnArray[] = $newRow[$key];  
    }  
    return $returnArray;  
}

function analisisAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cafa_nemonico = $_GET['cara_nemonico'];
	$cara_tri = $_GET['cara_tri'];
	$cara_tipo = $_GET['cara_tipo'];
	$cant_coslpan = 0;//(date('Y')-1)-$cefa_anio;
	//echo $cara_tri.'<br>';

	list($tri_def_b, $tri_arr_b) = getTrimestres(17,12);
	
	$tri_arr = array();
	rsort($tri_arr_b);
	//var_dump($tri_arr_b);
	//echo '<br>';
	$cont_x=0;
	$cont_y=0;
	for($t=0; $t<count($tri_arr_b); $t++){
		
		$tri_arr[] = $tri_arr_b[$t];
		if($tri_arr_b[$t] == $cara_tri){
			$cont_x = 0;
			$cont_y ++;
		}else{
			if($cont_x > 0 || $cont_y > 0){
				$cont_x++;
			}
		}
		if($cont_x == 3){
			break;
		}
	
	}
	//var_dump($tri_arr);
	sort($tri_arr);
	
	//Array General Cuadro
	$ventas_arr = $util_bru_arr = $util_ope_arr = $util_net_arr = $tot_pas_arr = $tot_pat_arr = $tot_act_arr = $end_arr = $mar_bru_arr = $mar_ope_arr = $mar_net_arr = $rot_act_arr = $roa_arr = $roe_arr = array();
	$ventas_grfco = $util_bru_grfco = $util_ope_grfco = $util_net_grfco = $tot_pas_grfco = $tot_pat_grfco = $tot_act_grfco = $end_grfco = $mar_bru_grfco = $mar_ope_grfco = $mar_net_grfco = $rot_act_grfco = $roa_grfco = $roe_grfco = array();

	$cont_tri = 0;
	$new_tri_arr = array();
	foreach($tri_arr as $tri){

		$exp_tri = explode('-',$tri);
		$anio = $exp_tri[0];
		$trim = $exp_tri[1];

		//Ventas
		$impo_ventas = getImpoEstadoResAnual($link, $cafa_nemonico, '2D01ST@2F01ST@2E0201@2A01ST', $anio, $trim, 'T',$cara_tipo);

		if($impo_ventas > 0){
			
			$cont_tri ++;

			if($cont_tri <= 3){
				$new_tri_arr[$tri] = array('tri'=>$tri, 'hide'=>'SI');
			}else{
				$new_tri_arr[$tri] = array('tri'=>$tri, 'hide'=>'NO');
				$cant_coslpan ++;
			}
			//$new_tri_arr[$tri] = array('tri'=>$tri, 'hide'=>'NO');
			//Ventas
			$ventas_arr[$tri] = array('tri'=>$tri,'impo'=>$impo_ventas, 'hide'=>($cont_tri <= 3)?'SI':'NO');
			if($cont_tri > 3){
				$ventas_grfco[] = (double)number_format($impo_ventas,0,'','');
			}

			//Utilidad Bruta
			$impo_util_bru = getImpoEstadoResAnual($link, $cafa_nemonico, '2D02ST@2F2401@2E0901', $anio, $trim, 'T',$cara_tipo);
			$util_bru_arr[$tri] = array('tri'=>$tri,'impo'=>$impo_util_bru, 'hide'=>($cont_tri <= 3)?'SI':'NO');
			if($cont_tri > 3){
				$util_bru_grfco[] = (double)number_format($impo_util_bru,0,'','');
			}

			//Utilidad Operativa
			$impo_util_ope = getImpoEstadoResAnual($link, $cafa_nemonico, '2D03ST@2F2801@2E1501@2A03ST', $anio, $trim, 'T',$cara_tipo);
			$util_ope_arr[$tri] = array('tri'=>$tri,'impo'=>$impo_util_ope, 'hide'=>($cont_tri <= 3)?'SI':'NO');
			if($cont_tri > 3){
				$util_ope_grfco[] = (double)number_format($impo_util_ope,0,'','');
			}
			//Utilidad Neta
			$impo_util_net = getImpoEstadoResAnual($link, $cafa_nemonico, '2D07ST@2F1901@2E1509@2A07ST', $anio, $trim, 'T',$cara_tipo);
			$util_net_arr[$tri] = array('tri'=>$tri,'impo'=>$impo_util_net, 'hide'=>($cont_tri <= 3)?'SI':'NO');
			if($cont_tri > 3){
				$util_net_grfco[] = (double)number_format($impo_util_net,0,'','');
			}

			$suma_impo_ventas = $suma_impo_util_ope = $suma_impo_util_net = 0;
			if($cont_tri >= 4){
				$new_ventas_arr = orderArrayMultiDim ($ventas_arr, 'tri', $inverse = true);
				$new_util_ope_arr = orderArrayMultiDim ($util_ope_arr, 'tri', $inverse = true);
				$new_util_net_arr = orderArrayMultiDim ($util_net_arr, 'tri', $inverse = true);

				$con_ventas_arr = 0;
				foreach ($new_ventas_arr as $key => $value) {
					$con_ventas_arr ++;
					if($con_ventas_arr <= 4){$suma_impo_ventas += $value['impo'];}else{break;}
				}
				$con_util_ope_arr = 0;
				foreach ($new_util_ope_arr as $key => $value) {
					$con_util_ope_arr ++;
					if($con_util_ope_arr <= 4){$suma_impo_util_ope += $value['impo'];}else{break;}
				}
				$con_util_net_arr = 0;
				foreach ($new_util_net_arr as $key => $value) {
					$con_util_net_arr ++;
					if($con_util_net_arr <= 4){$suma_impo_util_net += $value['impo'];}else{break;}
				}
			}

			//Total Pasivo
			$impo_pasi = getImpoEstadoFinAnual($link, $cafa_nemonico, '1D040T@1F3101@1E0501@1A040T', $anio, $trim, 'T',$cara_tipo);
			$tot_pas_arr[$tri] = array('tri'=>$tri,'impo'=>$impo_pasi, 'hide'=>($cont_tri <= 3)?'SI':'NO');
			if($cont_tri > 3){
				$tot_pas_grfco[] = (double)number_format($impo_pasi,0,'','');
			}

			//Total Patrimonio
			$impo_pat = getImpoEstadoFinAnual($link, $cafa_nemonico, '1D07ST@1F3306@1E0901@1A07ST', $anio, $trim, 'T',$cara_tipo);
			$tot_pat_arr[$tri] = array('tri'=>$tri,'impo'=>$impo_pat, 'hide'=>($cont_tri <= 3)?'SI':'NO');
			if($cont_tri > 3){
				$tot_pat_grfco[] = (double)number_format($impo_pat,0,'','');
			}

			//Total Activo
			$impo_act = getImpoEstadoFinAnual($link, $cafa_nemonico, '1D020T@1F2001@1E02ST@1A020T', $anio, $trim, 'T',$cara_tipo);
			$tot_act_arr[$tri] = array('tri'=>$tri,'impo'=>$impo_act, 'hide'=>($cont_tri <= 3)?'SI':'NO');
			if($cont_tri > 3){
				$tot_act_grfco[] = (double)number_format($impo_act,0,'','');
			}

			//Margen Bruto
			$impo_mgbt = ($impo_ventas!=0 && $cont_tri > 3)?($impo_util_bru/$impo_ventas)*100:0;
			$mar_bru_arr[$tri] =  array('tri'=>$tri,'impo'=>$impo_mgbt, 'hide'=>($cont_tri<=3)?'SI':'NO');
			if($cont_tri > 3){
				$mar_bru_grfco[] = (double)number_format($impo_mgbt,0,'','');
			}

			//Margen Operativo
			$impo_mgop = ($impo_ventas !=0 && $cont_tri > 3)? ($impo_util_ope/$impo_ventas)*100:0;
			$mar_ope_arr[$tri] =  array('tri'=>$tri,'impo'=>$impo_mgop, 'hide'=>($cont_tri<=3)?'SI':'NO');
			if($cont_tri > 3){
				$mar_ope_grfco[] = (double)number_format($impo_mgop,0,'','');
			}

			//Margen Neto
			$impo_mgnt = ($impo_ventas!=0 && $cont_tri > 3)?($impo_util_net/$impo_ventas)*100:0;
			$mar_net_arr[$tri] =  array('tri'=>$tri,'impo'=>$impo_mgnt, 'hide'=>($cont_tri<=3)?'SI':'NO');
			if($cont_tri > 3){
				$mar_net_grfco[] = (double)number_format($impo_mgnt,0,'','');
			}

			//Rotación del Activo
			$impo_rtac = ($impo_act != 0 && $cont_tri > 3)?($suma_impo_ventas/$impo_act):0;
			$rot_act_arr[$tri] =  array('tri'=>$tri,'impo'=>$impo_rtac, 'hide'=>($cont_tri<=3)?'SI':'NO');
			if($cont_tri > 3){
				$rot_act_grfco[] = (double)number_format($impo_rtac,2,'','');
			}

			//Endeudamiento
			$impo_end = ($impo_act!=0 && $cont_tri > 3)?($impo_pasi/$impo_act)*100:0;
			$end_arr[$tri] =  array('tri'=>$tri,'impo'=>$impo_end, 'hide'=>($cont_tri<=3)?'SI':'NO');
			if($cont_tri > 3){
				$end_grfco[] = (double)number_format($impo_end,0,'','');
			}

			//ROA
			$impo_roa = ($impo_act != 0 && $cont_tri > 3)?($suma_impo_util_ope/$impo_act)*100:0;
			$roa_arr[$tri] =  array('tri'=>$tri,'impo'=>$impo_roa, 'hide'=>($cont_tri<=3)?'SI':'NO');
			if($cont_tri > 3){
				$roa_grfco[] = (double)number_format($impo_roa,0,'','');
			}

			//ROE
			$impo_roe = ($impo_pat != 0 && $cont_tri > 3)?($suma_impo_util_net/$impo_pat)*100:0;
			$roe_arr[$tri] =  array('tri'=>$tri,'impo'=>$impo_roe, 'hide'=>($cont_tri<=3)?'SI':'NO');
			if($cont_tri > 3){
				$roe_grfco[] = (double)number_format($impo_roe,0,'','');
			}
		}
	}

	include('../View/EstadoResultado/analisis.php');
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