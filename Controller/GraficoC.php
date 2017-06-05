<?php
session_start();

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user = $_SESSION['cod_user'];
	
	include('../Control/Combobox/Combobox.php');
	$url = str_replace('/AppChecnes/proyectblv', '', $_SERVER['REQUEST_URI']);
	

	include('../View/Grafico/index.php');
}

function getSumaMonto($data){
	$suma = 0;

	foreach ($data as $key => $f) {
		$suma += $f['monto'];
	}

	return $suma;
}


function grafico1Action(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$fecha_final  = $_GET['fecha_final'];
	$fecha_inicio = $_GET['fecha_inicio'];
	$empresa      = " AND cz_codemp='".$_GET['empresa']."'";

	/*$fecha_fin    = date($fecha_final);
	$fecha_fin    = strtotime ( '-1 year' , strtotime ( $fecha_fin ) ) ;
	$fecha_inicio = date ( 'Y-m-j' , $fecha_fin );*/

	//Obtener Max
	$sqlmax = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";
	$resmax = mysqli_query($link, $sqlmax);
	$rowmax = mysqli_fetch_array($resmax);
	$max    = ($rowmax['max'] !='')?$rowmax['max']:0;
	//Obtener Min
	$sqlmin = "SELECT MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";
	$resmin = mysqli_query($link, $sqlmin);
	$rowmin = mysqli_fetch_array($resmin);
	$min    = ($rowmin['min'] !='')?$rowmin['min']:0;
	//Obtener Long
	$long = $max - $min;

	//Tabla Grafica
	$porcen  = array('0.100','0.225','0.350','0.225','0.100');
	$tabla = array();

	$rango_fin = 0;
	$rango_ini = 0; 
	for ($i=0; $i < count($porcen) ; $i++) {

		if ($i==0) {

			$rango_ini = $max;
			$rango_fin = $rango_ini-($long*$porcen[$i]);
		}else{

			$rango_ini = $rango_fin;
			$rango_fin = $rango_ini-($long*$porcen[$i]);
		}

		$sqlc = $sqlm = "";

		if ($i == 0) {

			//Get Dias col cierre
			$sqlc = "SELECT COUNT(cz_cierre)AS cant FROM cotizacion WHERE cz_cierre >= '$rango_fin' AND cz_cierre <= '$rango_ini' AND cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_cierre > 0 $empresa";

			//Get Monto
			$sqlm = "SELECT SUM(cz_montnegd)AS suma FROM cotizacion WHERE cz_cierre >= '$rango_fin' AND cz_cierre <= '$rango_ini' AND cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_cierre > 0 $empresa";

		}else{

			//Get Dias col cierre
			$sqlc = "SELECT COUNT(cz_cierre)AS cant FROM cotizacion WHERE cz_cierre >= '$rango_fin' AND cz_cierre < '$rango_ini' AND cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_cierre > 0 $empresa";
	
			//Get Monto
			$sqlm = "SELECT SUM(cz_montnegd)AS suma FROM cotizacion WHERE cz_cierre >= '$rango_fin' AND cz_cierre < '$rango_ini' AND cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_cierre > 0 $empresa";
			
		}
		

		//Get Dias col cierre
		$resc = mysqli_query($link, $sqlc);
		$rowc = mysqli_fetch_array($resc);
		//Get Monto
		$resm = mysqli_query($link, $sqlm);
		$rowm = mysqli_fetch_array($resm);

		//Cuadro en tabla
		$tabla[] = array('porcen'=>$porcen[$i],'rango_fin'=>$rango_fin,'rango_ini'=>$rango_ini,'dias'=>$rowc['cant'],'monto'=>$rowm['suma']);

		
	}

	//Grafica
	$categoria  = array();
	$series     = array();
	$suma_monto = getSumaMonto($tabla);
	foreach ($tabla as $key => $f) {
		//Categorias
		$fecha_ini = 0;
		$fecha_fin    = 0;

		if ($f['rango_ini'] >=10){
			$fecha_ini = number_format($f['rango_ini'],2,'.',',');
		}elseif($f['rango_ini'] >= 1 && $f['rango_ini'] < 10){
			$fecha_ini = number_format($f['rango_ini'],3,'.',',');
		}elseif($f['rango_ini'] < 1){
			$fecha_ini = number_format($f['rango_ini'],4,'.',',');
		}

		if ($f['rango_fin'] >=10){
			$fecha_fin = number_format($f['rango_fin'],2,'.',',');
		}elseif($f['rango_fin'] >= 1 && $f['rango_fin'] < 10){
			$fecha_fin = number_format($f['rango_fin'],3,'.',',');
		}elseif($f['rango_fin'] < 1){
			$fecha_fin = number_format($f['rango_fin'],4,'.',',');
		}

			
		$roud_serie = round((($f['monto']/$suma_monto)*100),0);

		if ($roud_serie>0) {

			$categoria[] = '['.$fecha_ini.' - '.$fecha_fin.']';
			$series[]    = $roud_serie;
		}
		
	}

	$categoria = json_encode($categoria);
	$series    = json_encode($series);

	include('../View/Grafico/grafico1.php');
}


function grafico2Action(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$fecha_final  = $_GET['fecha_final'];
	$fecha_inicio = $_GET['fecha_inicio'];
	$empresa      = " AND cz_codemp='".$_GET['empresa']."'";
	$rango        = ($_GET['rango']!='')?$_GET['rango']:1;

	//Obtener Max
	$sqlmax = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";
	$resmax = mysqli_query($link, $sqlmax);
	$rowmax = mysqli_fetch_array($resmax);
	$max    = ($rowmax['max'] !='')?$rowmax['max']:0;
	//Obtener Min
	$sqlmin = "SELECT MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";
	$resmin = mysqli_query($link, $sqlmin);
	$rowmin = mysqli_fetch_array($resmin);
	$min    = ($rowmin['min'] !='')?$rowmin['min']:0;
	//Obtener Long
	$long = $max - $min;

	//Fabricar los rangos segun rango de ingreso (caja de texto)
	//$porcen  = array('0.100','0.225','0.350','0.225','0.100');
	$porcen  = array();
	$c = $rango;
	$p = 0;
	while ($c <= 100) {
		$porcen[] = $rango/100;
		$c+=$rango;
		$p++;
	}

	//Tabla Grafica
	$tabla = array();

	$rango_fin = 0;
	$rango_ini = 0; 
	for ($i=0; $i < count($porcen) ; $i++) {

		if ($i==0) {

			$rango_ini = $max;
			$rango_fin = $rango_ini-($long*$porcen[$i]);
		}else{

			$rango_ini = $rango_fin;
			$rango_fin = $rango_ini-($long*$porcen[$i]);
		}

		$sqlc = $sqlm = "";

		if ($i == 0) {

			//Get Dias col cierre
			$sqlc = "SELECT COUNT(cz_cierre)AS cant FROM cotizacion WHERE cz_cierre >= '$rango_fin' AND cz_cierre <= '$rango_ini' AND cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_cierre > 0 $empresa";

			//Get Monto
			$sqlm = "SELECT SUM(cz_montnegd)AS suma FROM cotizacion WHERE cz_cierre >= '$rango_fin' AND cz_cierre <= '$rango_ini' AND cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_cierre > 0 $empresa";

		}else{

			//Get Dias col cierre
			$sqlc = "SELECT COUNT(cz_cierre)AS cant FROM cotizacion WHERE cz_cierre >= '$rango_fin' AND cz_cierre < '$rango_ini' AND cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_cierre > 0 $empresa";
	
			//Get Monto
			$sqlm = "SELECT SUM(cz_montnegd)AS suma FROM cotizacion WHERE cz_cierre >= '$rango_fin' AND cz_cierre < '$rango_ini' AND cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_cierre > 0 $empresa";
			
		}
		

		//Get Dias col cierre
		$resc = mysqli_query($link, $sqlc);
		$rowc = mysqli_fetch_array($resc);
		//Get Monto
		$resm = mysqli_query($link, $sqlm);
		$rowm = mysqli_fetch_array($resm);

		//Cuadro en tabla
		$tabla[] = array('porcen'=>$porcen[$i],'rango_fin'=>$rango_fin,'rango_ini'=>$rango_ini,'dias'=>$rowc['cant'],'monto'=>$rowm['suma']);

		
	}

	//Grafica
	$categoria  = array();
	$series     = array();
	$suma_monto = getSumaMonto($tabla);
	foreach ($tabla as $key => $f) {
		//Categorias
		$fecha_ini = 0;
		$fecha_fin    = 0;

		if ($f['rango_ini'] >=10){
			$fecha_ini = number_format($f['rango_ini'],2,'.',',');
		}elseif($f['rango_ini'] >= 1 && $f['rango_ini'] < 10){
			$fecha_ini = number_format($f['rango_ini'],3,'.',',');
		}elseif($f['rango_ini'] < 1){
			$fecha_ini = number_format($f['rango_ini'],4,'.',',');
		}

		if ($f['rango_fin'] >=10){
			$fecha_fin = number_format($f['rango_fin'],2,'.',',');
		}elseif($f['rango_fin'] >= 1 && $f['rango_fin'] < 10){
			$fecha_fin = number_format($f['rango_fin'],3,'.',',');
		}elseif($f['rango_fin'] < 1){
			$fecha_fin = number_format($f['rango_fin'],4,'.',',');
		}

		$round_serie = round((($f['monto']/$suma_monto)*100),0);

		if ($round_serie > 0) {

			$categoria[] = '['.$fecha_ini.' - '.$fecha_fin.']';
			$series[]    = $round_serie;
		}else{
			$categoria[] = '['.$fecha_ini.' - '.$fecha_fin.']';
			$series[]    = '';
		}
		
	}

	$tabla_ult_rf = $tabla[$p-1]['rango_fin'];
	$categoria    = json_encode($categoria);
	$series       = json_encode($series);

	include('../View/Grafico/grafico2.php');
}


function getFechaMedio($fecha_i,$fecha_f){

	$dias	  = (strtotime($fecha_f)-strtotime($fecha_i))/86400;
	$dias 	  = abs($dias);
	$dias     = floor($dias);
	$mid      = floor(abs(round($dias/2)));//floor(abs($dias/2));
	$date_mid = strtotime($fecha_f."- $mid days");//
	$date_mid = date("Y-m-d",$date_mid);

	return $date_mid;
}

function grafico3Action(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$fecha_final  = $_GET['fecha_final'];
	$fecha_inicio = $_GET['fecha_inicio'];

	//$visible12m = $_GET['visible12m'];
	//$visible6m  = $_GET['visible6m'];
	//$visible3m  = $_GET['visible3m'];

	$empresa      = " AND cz_codemp='".$_GET['empresa']."'";


	//Cotizacion
	$sqlcot  = "SELECT
				IF(cz_cierre!=0,cz_cierre,cz_cierreant)AS cierre,
				DATE_FORMAT(IF(cz_fecha ='',cz_fechant,cz_fecha),'%d/%m/%Y')AS fecha,
				cz_fecha
				FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa ORDER BY cz_fecha ASC";
	$respcot = mysqli_query($link, $sqlcot);

	//Max en un año
	$sqlmax12  = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max,MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";
	$respmax12 = mysqli_query($link, $sqlmax12);
	$rmax12    = mysqli_fetch_array($respmax12);
	$max12     = $rmax12['max'];
	$min12     = $rmax12['min'];

	//Obtenemos el maximo y minimo 6M
	$fecha_6m = getFechaMedio($fecha_inicio,$fecha_final);
	$sql6max    = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max, MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_6m' AND '$fecha_final' $empresa";
	$resp6max   = mysqli_query($link, $sql6max);
	$r6nmax     = mysqli_fetch_array($resp6max);
	$max6m      = $r6nmax['max'];
	$min6m      = $r6nmax['min'];

	//Obtenemos el maximo y minimo 3M
	$fecha_3m = getFechaMedio($fecha_6m,$fecha_final);
	$sql3m    = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max, MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_3m' AND '$fecha_final' $empresa";
	$resp3m   = mysqli_query($link, $sql3m);
	$r3m      = mysqli_fetch_array($resp3m);
	$max3m    = $r3m['max'];
	$min3m    = $r3m['min'];

	//Grafica
	$categoria    = array();
	$serie_lineal = array();
	$serie_max12  = array();
	$serie_min12  = array();
	$serie_max6   = array();
	$serie_min6   = array();
	$serie_max3   = array();
	$serie_min3   = array();

	if ($max12 !='') {

		while ($f = mysqli_fetch_array($respcot)) {
			$categoria[]    = $f['fecha'];
			$serie_lineal[] = $f['cierre'];
			$serie_max12[]  = $max12;
			$serie_min12[]  = $min12;
			$serie_max6[]   = ($f['cz_fecha']>=$fecha_6m)?$max6m:"''";
			$serie_min6[]   = ($f['cz_fecha']>=$fecha_6m)?$min6m:"''";
			$serie_max3[]   = ($f['cz_fecha']>=$fecha_3m)?$max3m:"''";
			$serie_min3[]   = ($f['cz_fecha']>=$fecha_3m)?$min3m:"''";
			
		}
	}else{
		$categoria[] = 'Sin Reg.';
		$serie_lineal[] = 0;
		$serie_max12[]  = 0;
		$serie_min12[]  = 0;
		$serie_max6[]   = 0;
		$serie_min6[]   = 0;
		$serie_max3[]   = 0;
		$serie_min3[]   = 0;
	}
	
	$maxy = max($serie_lineal);
	$miny = min($serie_lineal)-0.02;

	$categoria    = json_encode($categoria);
	$serie_lineal = json_encode($serie_lineal);
	$serie_max12  = json_encode($serie_max12);
	$serie_min12  = json_encode($serie_min12);
	$serie_max6   = json_encode($serie_max6);
	$serie_min6   = json_encode($serie_min6);
	$serie_max3   = json_encode($serie_max3);
	$serie_min3   = json_encode($serie_min3);

	include('../View/Grafico/grafico3.php');
}

function restarFecha(){

	$fecha    = $_GET['fecha'];
	$cantidad = $_GET['cantidad'];

	$fecha     = date($fecha);
    $new_fecha = strtotime ( "-$cantidad months" , strtotime ( $fecha ) ) ;
    $new_fecha = date ( 'Y-m-d' , $new_fecha );

	echo $new_fecha;
}

function listfavoritoAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user  = $_SESSION['cod_user'];

	$cod_grupo = $_GET['cod_grupo'];

	$sql = "SELECT DISTINCT(e.nemonico), e.nemonico,e.nombre FROM empresa_favorito ef
			INNER JOIN empresa e ON(ef.cod_emp=e.cod_emp)
			WHERE e.estado=1
			AND ef.est_fab
			AND ef.cod_user='$cod_user'";
	if ($cod_grupo !='') {
		$sql .= " AND ef.cod_grupo='$cod_grupo'";
	}
	
	$resp = mysqli_query($link,$sql);

	$html = '';
	while ($r=mysqli_fetch_array($resp)) {
		$html .= '<option value="'.$r['nemonico'].'">'.$r['nemonico'].' - '.$r['nombre'].'</option>';
	}

	echo $html;
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'grafico1':
		grafico1Action();
		break;
	case 'grafico2':
		grafico2Action();
		break;
	case 'grafico3':
		grafico3Action();
		break;
	case 'restarFecha':
		restarFecha();
		break;
	case 'listfavorito':
		listfavoritoAction();
		break;	
	default:
		# code...
		break;
}