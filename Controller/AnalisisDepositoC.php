<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../View/AnalisisDeposito/index.php');
}

function searchForId($id, $array) {
	foreach ($array as $key => $val) {
		if ($val['dh_plazo'] === $id) {
			return $key;
		}
	}
	return null;
 }

function mostrarAction(){
    include('../Config/Conexion.php');
	$link = getConexion();
	$dp_moneda = $_GET['dp_moneda'];
	$dp_valor = $_GET['dp_valor'];
	$dp_plazo = $_GET['dp_plazo'];
	$dp_empresa = $_GET['dp_empresa'];

	$dh_fecha = "2019-03-30";
	
	//CONDICION
	$sqlwhere = " WHERE dh.dh_stat='1'";
	$sqlwhere .= " AND de.dp_stat='1'";
	$sqlwhere .= " AND dh.dh_fsd='S'";
	if($dp_plazo!=''){
		$sqlwhere .= " AND $dp_plazo>=dh.dh_plazo_d AND $dp_plazo<=dh.dh_plazo_h";
	}
	if($dp_moneda!=''){
		$sqlwhere .= " AND de.dp_moneda='$dp_moneda'";
	}
	if($dp_valor!=''){
		$sqlwhere .= " AND $dp_valor>=dh.dh_sal_prom_d AND $dp_valor<=dh.dh_sal_prom_h";
	}

	//Los x primeros empresas
	$sqlxx = "SELECT dh.dh_emp_id,MAX(dh.dh_tea)AS max_tea,MIN(dh.dh_tea)AS min_tea FROM historico_deposito_plazo dh 
	INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id AND dh.dh_fecha=de.dp_fecha_imcs)";//de.dp_fecha_imcs
	$sqlxx .= $sqlwhere." GROUP BY dh.dh_emp_id";
	$sqlxx .= " ORDER BY max_tea DESC, min_tea DESC";
	$sqlxx .= " LIMIT 0,$dp_empresa";
	$resx = mysqli_query($link, $sqlxx);

	$dh_emp_id = "";
	while($x = mysqli_fetch_array($resx)){
		$dh_emp_id .= $x['dh_emp_id'].",";
	}
	$dh_emp_id = trim($dh_emp_id,',');
	
	//Ahora obtenemos informacion de x empresa filtradas anteriormente
	$sqlhx = "SELECT * FROM historico_deposito_plazo dh 
	INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id AND dh.dh_fecha=de.dp_fecha_imcs)";//de.dp_fecha_imcs
	$sqlhx .= $sqlwhere." AND dh.dh_emp_id IN($dh_emp_id)";
	$sqlhx .= " ORDER BY dh.dh_emp_id ASC, dh.dh_tea ASC";
	//echo $sqlhx;
	$reshx = mysqli_query($link, $sqlhx);
	$cant_hx = mysqli_num_rows($reshx);
	//echo "Cantidad Des:".$cant_hx."<br><br>";

	$emp_tasa = array();
	$detalle  = array();
	$categorie= array();
	$contador = 1;
	$cod_emp  = $dp_nomb_prod = $dp_nomb_emp = $dp_moneda = "";
	while($h = mysqli_fetch_array($reshx)){

		$dh_pz_d = trim($h['dh_plazo_d']);
		$dh_pz_dd = substr($dh_pz_d,strlen($dh_pz_d)-1,strlen($dh_pz_d));
		
		$plazo_tem = 0;
		if($dh_pz_dd == 0){
			$plazo_tem = $h['dh_plazo_d'];
		}else{
			$plazo_tem = $h['dh_plazo_h'];
		}

		//Nuevamente validamos los impares
		if(substr($plazo_tem,strlen($plazo_tem)-1,strlen($plazo_tem)) == 0){
			$dh_plazo = $plazo_tem;
		}else{
			$dh_plazo = ($plazo_tem!='9999999999')?$plazo_tem - $dh_pz_dd:$plazo_tem;
		}

		//echo $h['dh_emp_id']."<br>";
		if($contador == 1){
			$cod_emp = $h['dh_emp_id'];
			$dp_nomb_prod = $h['dp_nomb_prod'];
			$dp_nomb_emp = $h['dp_nomb_emp'];
			$dp_moneda = $h['dp_moneda'];
		}
		
		if($h['dh_emp_id'] != $cod_emp){
	
			$emp_tasa[] = array('dh_emp_id'=>$cod_emp,'dp_nomb_prod'=>$dp_nomb_prod,'dp_nomb_emp'=>$dp_nomb_emp,'dp_moneda'=>$dp_moneda,'detalle'=>$detalle);
			$detalle 		= array();
			$detalle[] 		= array('dh_tea'=>$h['dh_tea'],'dh_plazo'=>(String)$dh_plazo);
		}else{

			$detalle[] = array('dh_tea'=>$h['dh_tea'],'dh_plazo'=>(String)$dh_plazo);

			if($contador == $cant_hx){

				$emp_tasa[] = array('dh_emp_id'=>$cod_emp,'dp_nomb_prod'=>$dp_nomb_prod,'dp_nomb_emp'=>$dp_nomb_emp,'dp_moneda'=>$dp_moneda,'detalle'=>$detalle);
			}		
		}

		//Inicio - Plazo unico
		if(!in_array($dh_plazo, $categorie, true)){
			$categorie[] = (String)$dh_plazo;
			//array_push($plazo,$dh_plazo);
		}
		//Fin - plazo unico

		$cod_emp = $h['dh_emp_id'];
		$dp_nomb_prod = $h['dp_nomb_prod'];
		$dp_nomb_emp = $h['dp_nomb_emp'];
		$dp_moneda = $h['dp_moneda'];

		$contador ++;
	}
	//var_dump($emp_tasa[0]);

	//Ordenamos el arreglo plazo de menor a mayor
	sort($categorie);
	//var_dump($categorie);
	//echo json_encode($categorie);
	$serie = array();
	foreach($emp_tasa as $key => $emp){

		$detalle = array();

		$new_tea = null;
		foreach($categorie as $c){

			$key = (String)array_search($c, array_column($emp['detalle'], 'dh_plazo'));
			//$key = searchForId($c,$emp['detalle']);
			//echo var_dump($key);
			if($key!=''){
				$new_tea = $emp['detalle'][$key]['dh_tea'];
			}		

			//echo "<br>".$emp['dh_emp_id']."-Plazo Cab:".$c."-KEY:".$key."=>".$new_tea;
			//echo "<br>".$emp['dh_emp_id']."-Plazo Cab:".$c."-TEAT:".$new_tea;
			$detalle[] = ($new_tea!=null)?(double)$new_tea:$new_tea;

			//$new_tea_temp = $new_tea;
		}
		
		$mon = ($dp_moneda=='')?"(".$emp['dp_moneda'].")":"";
		$serie[] = array("name"=>$emp['dp_nomb_emp'].$mon,"data"=>$detalle);
	}

	$new_categorie = array();
	foreach($categorie as $c){
		$new_categorie[] = ($c=='9999999999')?"A más":$c;
	}
	$json_serie = json_encode($serie);
	$json_categorie = json_encode($new_categorie);

	include('../View/AnalisisDeposito/mostrar.php');
}

switch ($_GET['accion']) {
	case 'index':
		indexAction();
        break;
    case 'mostrar':
        mostrarAction();
	default:
		# code...
		break;
}