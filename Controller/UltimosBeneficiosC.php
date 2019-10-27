<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../Control/Combobox/Combobox.php');
	include('../View/UltimosBeneficios/index.php');
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

function importarManualAction(){

	include('../Util/simple_html_dom_php5.6.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	$nemonico = $_GET['nemonico'];

	$sql = "SELECT * FROM empresa WHERE cod_emp_bvl!=''";
	if($nemonico !=''){
		$sql .= " AND nemonico='$nemonico'";
	}
	$res = mysqli_query($link, $sql);

	while($row = mysqli_fetch_array($res)){
		
		$new_codigo = $row['cod_emp_bvl'];
		$new_nemonico = $row['nemonico'];

		$url  = "https://www.bvl.com.pe/jsp/Inf_EstadisticaGrafica.jsp?Cod_Empresa=$new_codigo&Nemonico=$new_nemonico&Listado=|$new_nemonico";
		$html = file_get_contents_curl($url);

		if (!empty($html)) {

			$div_0 = $html->find("div[class='divBloque']",0);
			$table_1 = $div_0->find('table',1);
			
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

					//Insertar a BD
					$sqlin = "INSERT INTO ultimos_beneficios(ub_nemonico,ub_der_comp,ub_der_mon,ub_der_imp,ub_der_por,ub_der_tip,ub_fech_acu,ub_fech_cor,ub_fech_reg,ub_fech_ent,ub_cod_emp_bvl)
					VALUES('$new_nemonico','$derecho','$ub_der_mon','$ub_der_imp','$ub_der_por','$ub_der_tip','$ub_fech_acu','$ub_fech_cor','$ub_fech_reg','$ub_fech_ent','$new_codigo')";
					$resin = mysqli_query($link, $sqlin);
				}
			}
		}
	}
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'listar':
		listarAction();
		break;
	case 'importarmanual':
		importarManualAction();
		break;
	default:
		# code...
		break;
}