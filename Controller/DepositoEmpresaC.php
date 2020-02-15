<?php
require_once('TiempoC.php');

function indexAction(){

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

function importaEmpresaDepositoPlazo($dp_moneda, $dp_valor, $dp_plaza, $dp_ubicacion, $dp_correo){

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

function importarEmpresaAction(){

	include('../Config/Conexion.php');
	$link = getConexion();
	//include('../Model/DepositoEmpresaM.php');

	$dp_moneda    = $_GET['dp_moneda'];
	$dp_valor     = $_GET['dp_valor'];
	$dp_plaza     = $_GET['dp_plaza'];
	$dp_ubicacion = $_GET['dp_ubicacion'];
	$dp_correo    = $_GET['dp_correo'];

	$data = importaEmpresaDepositoPlazo($dp_moneda, $dp_valor, $dp_plaza, $dp_ubicacion, $dp_correo);

	if($data['status']=='success'){

		foreach ($data['data']['aaData'] as $key => $fila) {

			$sqlin = "INSERT INTO entidad_financiera(dp_emp_id,dp_nodo,dp_nomb_emp,dp_nomb_prod,dp_logo,dp_ubig,dp_moneda,dp_fsd)VALUES('".$fila[0]."','".$fila[1]."','".$fila[4]."','".$fila[13]."','".$fila[2]."','$dp_ubicacion','$dp_moneda','".$fila[16]."')";
			mysqli_query($link, $sqlin);
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
	case 'importarEmpresa':
		importarEmpresaAction();
		break;
	default:
		# code...
		break;
}