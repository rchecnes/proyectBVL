<?php
function getComision($link, $tipo){
	//include('../Config/Conexion.php');
	//$link = getConexion();

	$sql  = "SELECT * FROM comision WHERE concep='$tipo' LIMIT 1";
	$resp = mysqli_query($link,$sql);
	$r    = mysqli_fetch_array($resp);

	return array(
		'COM_SAB'    =>$r['comis_neta_sab'],
		'BASE_SAB'   =>5000,
		'MIN_SAB'    =>50,
		'COM_BVL'    =>$r['retrib_bvl'],
		'COM_SMV'    =>$r['contrib_smv'],
		'F_GARANT'   =>$r['fondo_garant'],
		'VAL_IGV'    =>$r['igv'],
		'F_LIQUI'    =>$r['fondo_liq'],
		'MIN_CAVAL'  =>5,
		'BASE_CAVAL' =>12210.01,
		'COM_CAVAL' =>$r['retrib_caval_iclv']
	);
}

function getGananciaNeta($link, $mont_est, $prec, $cant,  $rent_obj, $prec_act){

	$com = getComision($link, 'contado');

	$cz_cn_fin = $mont_est;//5000.00; //Monto estimado
	$cz_ci_fin = $prec; //precio unitario
	
	$cant_acc = $cant;//($cz_cn_fin > 0 && $cz_ci_fin>0)?ceil($cz_cn_fin/$cz_ci_fin):0;
	$mont_neg = $cz_ci_fin*$cant_acc;

	//VARIABLES COMPRAS
	//:::::::::::::::::
	$c_comision_sab = ($mont_neg>$com['BASE_SAB'])?$mont_neg*($com['COM_SAB']/100):$com['MIN_SAB'];
	$c_cuota_bvl    = $mont_neg*($com['COM_BVL']/100);
	$c_f_garantia   = $mont_neg*($com['F_GARANT']/100);
	$c_cavali       = 0;
	if ($mont_neg <= $com['BASE_CAVAL']) {
		if ($mont_neg*($com['COM_CAVAL']/100)<$com['MIN_CAVAL']) {$c_cavali=$com['MIN_CAVAL'];}else{$c_cavali=$mont_neg*($com['COM_CAVAL']/100);}
	}
	$c_f_liquidacion = ($mont_neg*($com['F_LIQUI']/100)<1)?0:$mont_neg*($com['F_LIQUI']/100);
	$c_compra_total  = $c_comision_sab +$c_cuota_bvl+$c_f_garantia+$c_cavali+$c_f_liquidacion;
	$c_igv           = $c_compra_total*($com['VAL_IGV']/100);
	$c_compra_smv    = ($mont_neg+$c_compra_total)*($com['COM_SMV']/100);
	$c_costo_compra  = $c_compra_total+$c_igv+$c_compra_smv;
	$c_poliza_compra = $c_costo_compra+$mont_neg;

	//VARIABLES GANANCIA
	//::::::::::::::::::
	$gan_pre_min = round(($mont_neg+($c_costo_compra*2.12850))/$cant_acc,2,PHP_ROUND_HALF_ODD);
	$gan_pre_obj = $prec_act;//precio objetivo
	
	$gan_var_pre = $gan_pre_obj - $cz_ci_fin;
	$gan_val_vent = $gan_pre_obj * $cant_acc;

	//VARIABLES DE VENTA
	//::::::::::::::::::
	$v_comision_sab  = ($gan_val_vent>$com['BASE_SAB'])?$gan_val_vent*($com['COM_SAB']/100):$com['MIN_SAB'];
	$v_cuota_bvl     = $gan_val_vent*($com['COM_BVL']/100);
	$v_f_garantia    = $gan_val_vent*($com['F_GARANT']/100);
	$v_cavali        = 0;
	if ($gan_val_vent <= $com['BASE_CAVAL']) {
		if ($gan_val_vent*($com['COM_CAVAL']/100)<$com['MIN_CAVAL']) {$v_cavali=$com['MIN_CAVAL'];}else{$v_cavali=$gan_val_vent*($com['COM_CAVAL']/100);}
	}
	$v_f_liquidacion = ($gan_val_vent*($com['F_LIQUI']/100)<1)?0:$gan_val_vent*($com['F_LIQUI']/100);
	$v_com_total     = $v_comision_sab +$v_cuota_bvl+$v_f_garantia+$v_cavali+$v_f_liquidacion;
	$v_igv           = $v_com_total*($com['VAL_IGV']/100);
	$v_com_smv       = ($gan_val_vent+$v_com_total)*($com['COM_SMV']/100);
	$v_costo_venta   = $v_com_total+$v_igv+$v_com_smv;
	$v_poliza_venta  = $gan_val_vent-$v_costo_venta;

	//VARIABLES RESUMEN
	//:::::::::::::::::
	$res_gan_neta   = $v_poliza_venta - $c_poliza_compra;
	//$res_cost_total = $c_costo_compra + $v_costo_venta;
	//$res_var_total  = $res_gan_neta + $res_cost_total;

	//$porc_gan_neta   = ($res_gan_neta / $mont_neg)*100;
	//$porc_cost_total = ($res_cost_total / $mont_neg)*100;
	//$por_var_total   = ($res_var_total / $mont_neg)*100;

	return $res_gan_neta;
}
?>