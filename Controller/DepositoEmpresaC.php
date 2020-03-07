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

function getDataJson($dp_moneda, $dp_valor, $dp_plaza, $dp_ubicacion, $dp_correo){

	$jquery_rand = rand(1,1000000000);
    $url ="https://comparabien.com/services/pe/ws-depositos-plazo.php?callback=jQuery$jquery_rand&sEcho=2&sWhere=&ipaddr=&userid=&username=&geo=$dp_ubicacion&balance=$dp_valor&days=$dp_plaza&currency=$dp_moneda&exclude=off&email=$dp_correo&source=Compara&iSortingCols=1&iSortCol_0=6&sSortDir_0=desc&bSortable_6=true";
    $post_data = "";

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-type: application/json",
        "origin: https://comparabien.com.pe",
        "Referer: https://comparabien.com.pe/depositos-plazo/result",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36",
        "cache-control: no-cache"
    ));
    
    $response = curl_exec( $ch );
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
		$dp_plaza     = $_GET['dp_plaza'];
		$dp_ubicacion = $_GET['dp_ubicacion'];
		$dp_correo    = $_GET['dp_correo'];
	}else{
		$dp_moneda    = 'MN';
		$dp_valor     = 100;
		$dp_plaza     = 360;
		$dp_ubicacion = 'LI';
		$dp_correo    = 'ananimo426@gmail.com';
	}
	
	$data = getDataJson($dp_moneda, $dp_valor, $dp_plaza, $dp_ubicacion, $dp_correo);

	if($data['status']=='success'){

		foreach ($data['data']['aaData'] as $key => $fila) {

			$dp_nodo = $fila[1];
			$sqlval = "SELECT dp_nodo FROM entidad_financiera WHERE dp_nodo='$dp_nodo'";
			//echo $sqlval."<br>";
			$resval = mysqli_query($link, $sqlval);
			$rowval = mysqli_fetch_array($resval);

			if($rowval['dp_nodo']){

				$sqlin = "INSERT INTO entidad_financiera(dp_emp_id,dp_nodo,dp_nomb_emp,dp_nomb_prod,dp_logo,dp_ubig,dp_moneda,dp_fsd)VALUES('".$fila[0]."','".$fila[1]."','".$fila[4]."','".$fila[13]."','".$fila[2]."','$dp_ubicacion','$dp_moneda','".$fila[16]."')";
				//echo $sqlin;
				mysqli_query($link, $sqlin);
			}
		}
	}
}

function importarManualAction(){

	$ruta = "..";
	$condicion = "";
	
	importarEmpresaAction($ruta, 'manual');
}

function importarAutomaticoAction(){

	$ruta = "public_html/analisisdevalor.com";
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