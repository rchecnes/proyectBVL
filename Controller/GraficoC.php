<?php

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();
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
	$empresa      = ($_GET['empresa']!='')?" AND cz_codemp='".$_GET['empresa']."'":"";

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

			

		$categoria[] = '['.$fecha_ini.' - '.$fecha_fin.']';
		$series[]    = round((($f['monto']/$suma_monto)*100),0);
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
	$empresa      = ($_GET['empresa']!='')?" AND cz_codemp='".$_GET['empresa']."'":"";
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

			

		$categoria[] = '['.$fecha_ini.' - '.$fecha_fin.']';
		$series[]    = round((($f['monto']/$suma_monto)*100),0);
	}

	$tabla_ult_rf = $tabla[$p-1]['rango_fin'];
	$categoria    = json_encode($categoria);
	$series       = json_encode($series);

	include('../View/Grafico/grafico2.php');
}

function grafico3Action(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$fecha_final  = $_GET['fecha_final'];
	$fecha_inicio = $_GET['fecha_inicio'];
	$empresa      = ($_GET['empresa']!='')?" AND cz_codemp='".$_GET['empresa']."'":"";

	//Cotizacion
	$sqlcot  = "SELECT IF(cz_cierre!=0,cz_cierre,cz_cierreant)AS cierre,DATE_FORMAT(IF(cz_fecha ='',cz_fechant,cz_fecha),'%d/%m/%Y')AS fecha FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";
	$respcot = mysqli_query($link, $sqlcot);

	//Max en un año
	$sqlmax12  = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";
	$respmax12 = mysqli_query($link, $sqlmax12);
	$rmax12    = mysqli_fetch_array($respmax12);
	$max12     = $rmax12['max'];

	//Min en un año
	$sqlmin12  = "SELECT MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";
	$respmin12 = mysqli_query($link, $sqlmin12);
	$rmin12    = mysqli_fetch_array($respmin12);
	$min12     = $rmin12['min'];

	//Grafica
	$categoria    = array();
	$serie_lineal = array();
	$serie_max12  = array();
	$serie_min12  = array();

	if ($max12 !='') {

		while ($f = mysqli_fetch_array($respcot)) {
			$categoria[]    = $f['fecha'];
			$serie_lineal[] = $f['cierre'];
			$serie_max12[]  = $max12;
			$serie_min12[]  = $min12;
		}
	}else{
		$categoria[] = 'Sin Reg.';
		$serie_lineal[] = 0;
		$serie_max12[]  = 0;
		$serie_min12[]  = 0;
	}
	
	$maxy = max($serie_lineal);
	$miny = min($serie_lineal)-0.02;

	$categoria    = json_encode($categoria);
	$serie_lineal = json_encode($serie_lineal);
	$serie_max12  = json_encode($serie_max12);
	$serie_min12  = json_encode($serie_min12);

	include('../View/Grafico/grafico3.php');
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
	
	default:
		# code...
		break;
}