<?php
function indexAction(){

	require_once('TiempoC.php');
	include('../Config/Conexion.php');
	$link = getConexion();

	//Buscamos la ultima fecha de actualizacion
	$sqlfa = "SELECT dh_last_update FROM historico_entidad_financiera WHERE dh_stat=1 GROUP BY dh_last_update ORDER BY dh_last_update DESC";
	$resfa = mysqli_query($link, $sqlfa);

	include('../View/DepositoEmpresa/index.php');
}

function listarAction(){
	include('../Config/Conexion.php');
	$link = getConexion();

	$sql = "SELECT * FROM entidad_financiera WHERE dp_stat='1' ORDER BY dp_nomb_emp ASC";
	$dp_empresa = mysqli_query($link, $sql);
	$nro_reg = 0;
	if ($dp_empresa){
		$nro_reg = mysqli_num_rows($dp_empresa);
	}

	include('../View/DepositoEmpresa/listar.php');
}

function getDataJson($url){

	$post_data = "";

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-type: application/json",
		"origin: https://comparabien.com.pe",
        "Referer: https://comparabien.com.pe/depositos-plazo/result",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36",
        "cache-control: no-cache"
    ));
    
	$response = curl_exec( $ch );
	//var_dump($response);
    $err = curl_error($ch);

    if ($err) {
        $return = array("status"=>"error","message"=>$err, "data"=>"");
    }else{

        $response = trim($response,");");
        $pos_ini = strrpos($response, "(");
        $pos_fin = strrpos($response, ");");
        
        $data = substr($response, $pos_ini+1,strlen($response));
        $data_arr = json_decode($data,true);//array

        if (is_array($data_arr)) {
        	$return = array("status"=>"success","message"=>"", "data"=>$data_arr);
        }else{
        	$return = array("status"=>"error","message"=>"ocurrio algo al transformar a ARREGLO", "data"=>"");
        }
    }

    curl_close( $ch );

    return $return;
}

function importarEmpresaAction($ruta, $tipo){

	include($ruta.'/Config/Conexion.php');
	$link = getConexion();

	if($tipo == 'manual'){
		$dp_moneda    = $_GET['dp_moneda'];
		$dp_valor     = $_GET['dp_valor'];
		$dp_plaza     = $_GET['dp_plazo'];
		$dp_ubicacion = $_GET['dp_ubicacion'];
		$dp_correo    = $_GET['dp_correo'];
	}else{
		$rand = rand(1,2);
		$dp_moneda    = ($rand==1)?'MN':'ME';

		$array_temp = array(
			array('valor'=>2000,'plazo'=>30),
			array('valor'=>500000,'plazo'=>1080),
			array('valor'=>800000,'plazo'=>1800),
			array('valor'=>100000,'plazo'=>360)
		);
		$opcion_valor = 0;
		$time = date('H:i');
		if($time>='18:00' && $time<'18:30'){ $opcion_valor = 0;}
		if($time>='18:30' && $time<'19:00'){ $opcion_valor = 1;}
		if($time>='19:00' && $time<'19:30'){ $opcion_valor = 2;}
		if($time>='19:30' && $time<'20:00'){ $opcion_valor = 3;}
		$data_temp = $array_temp[$opcion_valor];

		$dp_valor = $data_temp['valor'];
		$dp_plaza = $data_temp['plazo'];

		$dp_ubicacion = 'LI';
		$dp_correo    = 'prueba@gmail.com';
	}

	$jquery_rand = rand(1,1000000000);
	$num_rand = rand(1,100);
	$hash = "";
	$url = "https://comparabien.com/services/pe/ws-depositos-plazo.php?callback=jQuery$jquery_rand&sEcho=$num_rand&sWhere=&ipaddr=&userid=&username=&geo=$dp_ubicacion&balance=$dp_valor&days=$dp_plaza&currency=$dp_moneda&exclude=all&email=$dp_correo&source=Compara&hash=$hash&iSortingCols=1&iSortCol_0=6&sSortDir_0=desc&bSortable_6=true";
	//$url = "https://comparabien.com/services/pe/ws-depositos-plazo.php?callback=&sEcho=4&sWhere=&ipaddr=&userid=&username=&geo=LI&balance=2000&days=240&currency=MN&exclude=all&email=demo%40gmail.com&source=Compara&hash=&iSortingCols=1&iSortCol_0=6&sSortDir_0=desc&bSortable_6=true";
	
	$data = getDataJson($url);
	//var_dump($data);
	$contador = 0;

	$count_rows = count($data['data']['aaData']);
	if($data['status']=='success' && $count_rows>0){

		$fecha_imp = date('Y-m-d H:i:s');
		
		foreach ($data['data']['aaData'] as $key => $fila) {

			$dp_emp_id = $fila[0];
			$dp_nodo = $fila[1];
			$sqlval = "SELECT dp_nodo FROM entidad_financiera WHERE dp_emp_id='$dp_emp_id' AND dp_nodo='$dp_nodo'";
			$resval = mysqli_query($link, $sqlval);
			$rowval = mysqli_fetch_array($resval);

			if($rowval['dp_nodo'] == ''){

				$sqlin = "INSERT INTO entidad_financiera(dp_emp_id,dp_nodo,dp_nomb_emp,dp_nomb_prod,dp_logo,dp_ubig,dp_moneda,dp_fsd,dp_stat,dp_fcrea)VALUES('".$fila[0]."','".$fila[1]."','".$fila[4]."','".$fila[13]."','".$fila[2]."','$dp_ubicacion','$dp_moneda','".$fila[16]."','1','$fecha_imp')";
				$resreg = mysqli_query($link, $sqlin);

				if($resreg){
					$contador ++;
				}

			}
		}
		
	}

	echo "Se leo ".$count_rows." registros y se importo ".$contador." entidades financieras";
}

function importarManualAction(){

	$ruta = "..";
	$condicion = "";
	
	importarEmpresaAction($ruta, 'manual');
}

function importarAutomaticoAction(){

	$ruta = "/var/www/html/analisisdevalor";
	//$ruta = "..";
	$condicion = "";
	
	importarEmpresaAction($ruta, 'automatico');
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
		importarAutomaticoAction();
		break;
	default:
		# code...
		break;
}