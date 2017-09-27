<?php
require_once('TiempoC.php');

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

function getPromedioPrecio(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$fecha_final  = $_GET['fecha_final'];
	$fecha_inicio = $_GET['fecha_inicio'];
	$empresa      = " AND cz_codemp='".$_GET['empresa']."'";

	$sql = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max,MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' $empresa";

	$resp = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($resp);
	$max    = ($row['max'] !='')?$row['max']:0;
	$min    = ($row['min'] !='')?$row['min']:0;
	$long = $max - $min;
	//Obtener media
	$med = ($max + $min)/2;

	//Obtener el ultimo precio de la empresa
	$sqlpre = "SELECT cz_ci_fin FROM empresa WHERE nemonico='".$_GET['empresa']."'";
	$respre = mysqli_query($link, $sqlpre);
	$rpre   = mysqli_fetch_array($respre);

	mysqli_close($link);

	echo json_encode(array('max'=>number_format($max,3,'.',','),'min'=>number_format($min,3,'.',','),'long'=>number_format($long,3,'.',','),'med'=>number_format($med,3,'.',','),'cz_ci_fin'=>number_format($rpre['cz_ci_fin'],3,'.',',')));
}

function insertaRecomend($cx, $empresa, $cod_rec, $mes){

	$tp_fecha = date('Y-m-d');
	$tp_hora = date('h:i:s');

	$ps_cod = 1;
	if ($mes == '12') { $ps_cod =1;}
	if ($mes == '6') { $ps_cod =2;}
	if ($mes == '3') { $ps_cod =3;}

	$cod_user = $_SESSION['cod_user'];

	//Emores
	$sqlemp = "SELECT cod_emp FROM empresa WHERE nemonico='$empresa'";
	$resemp = mysqli_query($cx, $sqlemp);
	$rowemp = mysqli_fetch_array($resemp);
	$cod_emp = $rowemp['cod_emp'];

	//Validamos si existe registro en es fecha
	$sqlcon = "SELECT rc_cod FROM temp_recomendacion WHERE ps_cod='$ps_cod' AND cod_emp='$cod_emp' AND cod_user='$cod_user' AND tp_fecha='$tp_fecha'";
	$rescon = mysqli_query($cx, $sqlcon);
	$rowcon = mysqli_fetch_array($rescon);

	if ($rowcon['rc_cod'] !='') {

		$sqlup = "UPDATE temp_recomendacion SET rc_cod='$cod_rec' WHERE ps_cod='$ps_cod' AND cod_emp='$cod_emp' AND cod_user='$cod_user' AND tp_fecha='$tp_fecha'";
		mysqli_query($cx, $sqlup);
	}else{
		$sqlin = "INSERT INTO temp_recomendacion(ps_cod,cod_emp,cod_user,tp_fecha,tp_hora,rc_cod)VALUES('$ps_cod','$cod_emp','$cod_user','$tp_fecha','$tp_hora','$cod_rec' )";
		mysqli_query($cx, $sqlin);
	}
}

function grafico1Action(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$fecha_final  = $_GET['fecha_final'];
	$fecha_inicio = $_GET['fecha_inicio'];
	$nemonico     = $_GET['empresa'];//Empresa
	$prec_unit    = ($_GET['prec_unit']>0 && $_GET['prec_unit']!='')?$_GET['prec_unit']:0;
	$mes          = (isset($_GET['mes']))?$_GET['mes']:'';

	//Obtener Max
	$sql = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max,MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_codemp='$nemonico'";
	$resp = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($resp);

	$max    = ($row['max'] !='')?$row['max']:0;
	$min    = ($row['min'] !='')?$row['min']:0;
	//Obtener Long
	$long = $max - $min;
	//Obtener media
	$med = ($max + $min)/2;

	//Tabla Grafica
	$porcen   = array('0.100','0.225','0.350','0.225','0.100');
	//Consultamos recomendacion
	$recomen  = array(array('cod'=>2,'nom'=>'Vender +'),array('cod'=>3,'nom'=>'Vender'),array('cod'=>4,'nom'=>'Mantener'),array('cod'=>5,'nom'=>'Comprar'),array('cod'=>6,'nom'=>'Comprar +'));

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
		$tabla[] = array('porcen'=>$porcen[$i],'rango_fin'=>$rango_fin,'rango_ini'=>$rango_ini,'dias'=>$rowc['cant'],'monto'=>$rowm['suma'],'rec_nom'=>$recomen[$i]['nom']);
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

			
		$roud_serie = ($suma_monto>0)?round((($f['monto']/$suma_monto)*100),0):0;

		if ($roud_serie>0) {

			$categoria[] = '['.$fecha_ini.' - '.$fecha_fin.']';
			$series[]    = $roud_serie;
		}	
	}

	$categoria = json_encode($categoria);
	$series    = json_encode($series);

	include('../View/Grafico/grafico1.php');
}

function calcularRecomendacion($fecha_final, $nemonico, $prec_unit, $mes){

	//Restamos mese a la fecha final
	$fecha    = $fecha_final;
	$cantidad = $mes;

	$fecha        = date($fecha);
    $new_fecha    = strtotime ( "-$cantidad months" , strtotime ( $fecha ) ) ;
    $fecha_inicio = date ( 'Y-m-d' , $new_fecha );
    //Fin  restar fecha


	$sql = "SELECT MAX(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS max,MIN(IF(cz_cierre!=0,cz_cierre,cz_cierreant)) AS min FROM cotizacion WHERE cz_fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND cz_codemp='$nemonico'";
	$resp = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($resp);
	
	$max    = ($row['max'] !='')?$row['max']:0;
	$min    = ($row['min'] !='')?$row['min']:0;
	//Obtener Long
	$long = $max - $min;
	//Obtener media
	$med = ($max + $min)/2;

	//Tabla Grafica
	$porcen   = array('0.100','0.225','0.350','0.225','0.100');
	//Consultamos recomendacion
	$recomen  = array(array('cod'=>2,'nom'=>'Vender +'),array('cod'=>3,'nom'=>'Vender'),array('cod'=>4,'nom'=>'Mantener'),array('cod'=>5,'nom'=>'Comprar'),array('cod'=>6,'nom'=>'Comprar +'));

	$rango_fin = 0;
	$rango_ini = 0;
	$cod_rec   = '';//Recomendacion Final por mes
	$nom_rec   = '';//Recomendacion Final por mes
	for ($i=0; $i < count($porcen) ; $i++) {

		if ($i==0) {

			$rango_ini = $max;
			$rango_fin = $rango_ini-($long*$porcen[$i]);
		}else{

			$rango_ini = $rango_fin;
			$rango_fin = $rango_ini-($long*$porcen[$i]);
		}

		//Recomendación: El precio debe estar entre un rango y ese se debe pintar de un color
		if ($i != 4 && round($prec_unit,3)<=round($rango_ini,3) && round($prec_unit,3)>round($rango_fin,3)) {
			$cod_rec   = $recomen[$i]['cod'];
			$nom_rec   = $recomen[$i]['nom'];
		}elseif ($i == 4 && round($prec_unit,3)<=round($rango_ini,3) && round($prec_unit,3)>=round($rango_fin,3)) {
			$cod_rec   = $recomen[$i]['cod'];
			$nom_rec   = $recomen[$i]['nom'];
		}
	}

	//Registramos la recomendacion del cliente
	if($cod_rec ==''){
		if (round($prec_unit,3)>round($max,3) && $exist_rec=='NO') {$cod_rec = 1;$nom_rec='Mantener +';}
		if (round($prec_unit,3)<round($min,3) && $exist_rec=='NO') {$cod_rec = 7;$nom_rec='Mantener -';}
	}else{
		$cod_fin_rec = $cod_rec;
	}
}

function crearcuadrorecAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_emp  = $_GET['empresa'];
	$cod_user = $_SESSION['cod_user'];
	$tp_fecha = date('Y-m-d');

	//TEMP RECOMENDACION
	$sql = "SELECT * FROM temp_recomendacion tr
			INNER JOIN empresa em ON(tr.cod_emp=em.cod_emp)
			INNER JOIN porce_recomendacion pr ON(tr.ps_cod=pr.ps_cod)
			INNER JOIN recomendacion r ON(tr.rc_cod=r.rc_cod)
			WHERE em.nemonico='$cod_emp' AND tr.cod_user='$cod_user' AND tr.tp_fecha='$tp_fecha'";
	$res = mysqli_query($link, $sql);
	$rctp = array();
	while ($tr = mysqli_fetch_array($res)) {
		$rctp[$tr['ps_cod']] = array('rc_cod'=>$tr['rc_cod'],'rc_nom'=>$tr['rc_nom'],'rc_valor'=>$tr['rc_valor']);
	}
	
	//PORCENTAJE RECOMENDACION
	$sqlre = "SELECT * FROM porce_recomendacion";
	$resre = mysqli_query($link,$sqlre);
	$prec   = array();
	while ($ps = mysqli_fetch_array($resre)) {
		$prec[$ps['ps_cod']] = array('ps_peso'=>$ps['ps_peso'],'ps_mes'=>$ps['ps_mes']);
	}

	//RECOMENDACION
	//PORCENTAJE RECOMENDACION
	$sqlre = "SELECT * FROM recomendacion";
	$resre = mysqli_query($link,$sqlre);
	$rec   = array();
	while ($rc = mysqli_fetch_array($resre)) {
		$rec[$rc['rc_cod']] = array('rc_cod'=>$rc['rc_cod'],'rc_nom'=>$rc['rc_nom'],'rc_valor'=>$rc['rc_valor']);
	}

	$rec12m  = ($rctp[1]['rc_nom']!='')?$rctp[1]['rc_nom']:"-";
	$rec6m   = ($rctp[2]['rc_nom']!='')?$rctp[2]['rc_nom']:"-";
	$rec3m   = ($rctp[3]['rc_nom']!='')?$rctp[3]['rc_nom']:"-";

	$recV12m = ($rctp[1]['rc_valor']!='')?$rctp[1]['rc_valor']:"-";
	$recV6m  = ($rctp[2]['rc_valor']!='')?$rctp[2]['rc_valor']:"-";
	$recV3m  = ($rctp[3]['rc_valor']!='')?$rctp[3]['rc_valor']:"-";

	$recfinaltxt = '¿?';
	if ($rec12m !='-' && $rec6m!='-' && $rec3m!='-' && $recV12m !='-' && $recV6m!='-' && $recV3m!='-') {
		$recfinal = (($prec[1]['ps_peso']/100*($recV12m))+($prec[2]['ps_peso']/100*($recV6m))+($prec[3]['ps_peso']/100*($recV3m)));
		$recfinal = round($recfinal,0);
		foreach ($rec as $key => $v) {
			if ($recfinal == $v['rc_valor']) {
				$recfinaltxt = $v['rc_nom'];
			}
		}
	}

	echo '<table class="table table-bordered grafico">
            <tr><th colspan="4" class="align-center">RECOMENDACIÓN</th></tr></th>
            <tr>
                <th class="align-center" style="width:50px">PESO</th>
                <th class="align-center" style="width:50px">MES</th>
                <th class="align-center" style="width:50px">REC.</th>
                <th class="align-center" style="width:50px">&nbsp;</th>
            </tr>
            <tr>
                <td class="align-center">'.$prec[1]['ps_peso'].'%</td>
                <td class="align-center">'.$prec[1]['ps_mes'].'</td>
                <td class="align-center">'.$rec12m.'</td>
               <td class="align-center" rowspan="3" style="vertical-align:middle">'.$recfinaltxt.'</td>
            </tr>
            <tr>
                <td class="align-center">'.$prec[2]['ps_peso'].'%</td>
                <td class="align-center">'.$prec[2]['ps_mes'].'</td>
                <td class="align-center">'.$rec6m.'</td>
            </tr>
            <tr>
                <td class="align-center">'.$prec[3]['ps_peso'].'%</td>
                <td class="align-center">'.$prec[3]['ps_mes'].'</td>
                <td class="align-center">'.$rec3m.'</td>
            </tr>
         </table>';

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
			INNER JOIN user_grupo ug ON(ef.cod_grupo=ug.cod_grupo)
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

function getFinalRecomend(){

	$tp_fecha = date('Y-m-d');
	$cod_user = $_SESSION['cod_user'];

	$sql = "SELECT * FROM temp_recomendacion t
			INNER JOIN porce_recomendacion p ON(t.ps_cod=p.ps_cod)
			INNER JOIN recomendacion r ON(t.rc_cod=r.rc_cod)
			WHERE t.tp_fecha='$tp_fecha' AND t.cod_user='$cod_user' AND ";
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'promedio':
		getPromedioPrecio();
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
	case 'finalrecomen':
		getFinalRecomend();
		break;
	case 'crearcuadrorec':
		crearcuadrorecAction();
		break;	
	default:
		# code...
		break;
}