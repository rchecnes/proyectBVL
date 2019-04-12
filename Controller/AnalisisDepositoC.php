<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	include('../View/AnalisisDeposito/index.php');
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
	$sqlxx = "SELECT *,MAX(dh.dh_tea)AS max_tea FROM historico_deposito_plazo dh 
	INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id AND dh.dh_fecha='$dh_fecha')";//de.dp_fecha_imcs
	$sqlxx .= $sqlwhere." GROUP BY dh.dh_emp_id";
	$sqlxx .= " ORDER BY max_tea DESC";
	$sqlxx .= " LIMIT 0,$dp_empresa";
	$resx = mysqli_query($link, $sqlxx);

	$dh_emp_id = "";
	while($x = mysqli_fetch_array($resx)){
		$dh_emp_id .= $x['dh_emp_id'].",";
	}
	$dh_emp_id = trim($dh_emp_id,',');
	
	//Ahora obtenemos informacion de x empresa filtradas anteriormente
	$sqlhx = "SELECT * FROM historico_deposito_plazo dh 
	INNER JOIN empresa_deposito_plazo de ON(de.dp_emp_id=dh.dh_emp_id AND dh.dh_fecha='$dh_fecha')";//de.dp_fecha_imcs
	$sqlhx .= $sqlwhere." AND dh.dh_emp_id IN($dh_emp_id)";
	$sqlhx .= " ORDER BY dh.dh_emp_id, dh.dh_plazo_d ASC";
	$reshx = mysqli_query($link, $sqlhx);
	$cant_hx = mysqli_num_rows($reshx);
	//echo "Cantidad Des:".$cant_hx."<br><br>";

	$emp_tasa = array();
	$detalle  = array();
	$contador = 1;
	$cod_emp  = "";
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

		//echo $h['dh_plazo_d']."--".$h['dh_plazo_h']."=>".$dh_plazo."<br>";

		//$emp_tasa[$h['dh_emp_id']][] = array('dh_tea'=>$h['dh_tea'],'dh_plazo'=>$dh_plazo,'dh_nomb_prod'=>$h['dp_nomb_prod'],'dp_nomb_emp'=>$h['dp_nomb_emp']);
		
		
		//echo $h['dh_emp_id']."<br>";
		if($contador == 1){
			$cod_emp = $h['dh_emp_id'];
		}
		
		if($h['dh_emp_id'] != $cod_emp){
	
			$emp_tasa[] = array('dh_emp_id'=>$cod_emp,'dp_nomb_prod'=>$h['dp_nomb_prod'],'dp_nomb_emp'=>$h['dp_nomb_emp'],'detalle'=>$detalle);
			$detalle 		= array();
			$detalle[] 		= array('dh_tea'=>$h['dh_tea'],'dh_plazo'=>$dh_plazo);
		}else{

			$detalle[] = array('dh_tea'=>$h['dh_tea'],'dh_plazo'=>$dh_plazo);

			if($contador == $cant_hx){

				$emp_tasa[] = array('dh_emp_id'=>$cod_emp,'dp_nomb_prod'=>$h['dp_nomb_prod'],'dp_nomb_emp'=>$h['dp_nomb_emp'],'detalle'=>$detalle);
			}		
		}

		$cod_emp = $h['dh_emp_id'];
		$contador ++;
	}

	//Armamos La Grafica
	/*$serie = array();
	$categorie = array();
	foreach($emp_tasa as $key => $emp){

		$detalle = array();

		foreach($emp as $d => $val){
			//echo $key."-".$val['dh_tea']."<br>";
			$detalle[] = (double)$val['dh_tea'];
			$categorie[] = (int)$val['dh_plazo'];
		}
		
		$serie[] = array("name"=>rand(10,20),"data"=>$detalle);
		
	}
	$json_serie = json_encode($serie);
	$json_categorie = json_encode($categorie);
	*/

	$serie = array();
	$categorie = array();
	foreach($emp_tasa as $key => $emp){

		$detalle = array();

		foreach($emp['detalle'] as $d => $val){

			$detalle[] = (double)number_format($val['dh_tea'],2,'.','');
			if(!in_array($val['dh_plazo'], $categorie, true)){
				$categorie[] = ($val['dh_plazo']=="9999999999")?"A más":(String)$val['dh_plazo'];
			}
			
		}
		
		$serie[] = array("name"=>$emp['dh_emp_id']."-".$emp['dp_nomb_emp']." - ".$emp['dp_nomb_prod'],"data"=>$detalle);
		
	}
	$json_serie = json_encode($serie);
	$json_categorie = json_encode($categorie);

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