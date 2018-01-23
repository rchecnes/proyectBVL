<?php

function getRecomendacioPorMes($link, $fecha_final, $nemonico, $prec_unit, $mes){

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
		if (round($prec_unit,3)>round($max,3)) {$cod_rec = 1;$nom_rec='Mantener +';}
		if (round($prec_unit,3)<round($min,3)) {$cod_rec = 7;$nom_rec='Mantener -';}
	}else{
		$cod_fin_rec = $cod_rec;
	}

	return array($cod_rec, $nom_rec);
}

function getRecomendacionFinal($link, $nemonico, $prec_unit){

	$tp_fecha  = date('Y-m-d');

	//PORCENTAJE RECOMENDACION
	$sqlpre = "SELECT * FROM porce_recomendacion";
	$respre = mysqli_query($link,$sqlpre);
	$prec  = array();
	while ($pr = mysqli_fetch_array($respre)) {
		$prec[$pr['ps_cod']] = array('ps_peso'=>$pr['ps_peso'],'ps_mes'=>$pr['ps_mes']);
	}

	//PORCENTAJE RECOMENDACION
	$sqlre = "SELECT * FROM recomendacion";
	$resre = mysqli_query($link,$sqlre);
	$rec   = array();
	while ($re = mysqli_fetch_array($resre)) {
		$rec[$re['rc_cod']] = array('rc_cod'=>$re['rc_cod'],'rc_nom'=>$re['rc_nom'],'rc_valor'=>$re['rc_valor'],'rc_ord_email'=>$re['rc_ord_email']);
	}

	//RECOMENDACION
	list($codRec12m, $valRec12) = getRecomendacioPorMes($link, $tp_fecha, $nemonico, $prec_unit, 12);
	list($codRec6m, $valRec6 )  = getRecomendacioPorMes($link, $tp_fecha, $nemonico, $prec_unit, 6);
	list($codRec3m, $valRec3 )  = getRecomendacioPorMes($link, $tp_fecha, $nemonico, $prec_unit, 3);

	$valRec12m = (isset($rec[$codRec12m]['rc_valor']))?$rec[$codRec12m]['rc_valor']:0;
	$valRec6m  = (isset($rec[$codRec6m]['rc_valor']))?$rec[$codRec6m]['rc_valor']:0;
	$valRec3m  = (isset($rec[$codRec3m]['rc_valor']))?$rec[$codRec3m]['rc_valor']:0;

	$recfinaltxt = '';
	$recfinalord = '';
	$recfinal = (($prec[1]['ps_peso']/100*($valRec12m))+($prec[2]['ps_peso']/100*($valRec6m))+($prec[3]['ps_peso']/100*($valRec3m)));
	$recfinal = round($recfinal,0);
	foreach ($rec as $key => $v) {
		if ($recfinal == $v['rc_valor']) {
			$recfinalord = $v['rc_ord_email'];
			$recfinaltxt = $v['rc_nom'];
		}
	}

	return  array('rec_ord_email'=>$recfinalord, 'rec_nombre'=>$recfinaltxt);
}