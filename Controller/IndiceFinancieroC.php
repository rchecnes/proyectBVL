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

	$sql = "SELECT * FROM ultimos_beneficios ub
			INNER JOIN empresa em ON(ub.ub_nemonico=em.nemonico)";

	if ($nemonico != '') {
		$sql .= " AND em.nemonico='$nemonico'";
	}

	$sql .= " ORDER BY ub.ub_fech_acu DESC";

	$res = mysqli_query($link, $sql);

	$nro_reg = mysqli_num_rows($res);

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

        //$url  = "https://www.bvl.com.pe/jsp/Inf_EstadisticaGrafica.jsp?Cod_Empresa=$new_codigo&Nemonico=$new_nemonico&Listado=|$new_nemonico";
        $url = "https://www.bvl.com.pe/inf_financiera$new_codigo"."_"."$imp_ind_fin.html";
        $html = file_get_contents_curl($url);
        //echo $url;
		//echo $html."<br>";
		//echo $new_nemonico."<br>";
		if (!empty($html)) {

			$div_0 = $html->find("div[class='divBloque']",0);
			$table_1 = ($div_0->find('table',1)!=null)?$div_0->find('table',1):$div_0->find('table',0);
			//$tr_0 = $table_1->find('tr',0);
			echo $table_1."<br>";
			/*if(isset($table_1->find('tr',0)->plaintext)){

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
						$sqlval = "SELECT ub_nemonico FROM ultimos_beneficios WHERE ub_nemonico='$new_nemonico' AND ub_fech_acu='$ub_fech_acu' AND ub_fech_cor='$ub_fech_cor' AND ub_fech_reg='$ub_fech_reg' AND ub_fech_ent='$ub_fech_ent'";
						$resval = mysqli_query($link, $sqlval);
						$rowval = mysqli_fetch_array($resval);

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
			unset($table_1);*/
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