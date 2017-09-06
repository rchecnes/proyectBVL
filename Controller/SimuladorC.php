<?php
require_once('TiempoC.php');

function indexAction(){

	include('../Config/Conexion.php');
	$link = getConexion();
	include('../Control/Combobox/Combobox.php');

	//Estas variable bienen del simulador
	$por_cod   = isset($_GET['por_cod'])?$_GET['por_cod']:'';
	$oper      = isset($_GET['oper'])?$_GET['oper']:'';
	$mont_est  = isset($_GET['mont_est'])?$_GET['mont_est']:'5000.00';
	$prec      = isset($_GET['prec'])?$_GET['prec']:'';
	$cant      = isset($_GET['cant'])?$_GET['cant']:'';
	$rent_obj  = isset($_GET['rent_obj'])?$_GET['rent_obj']:'500.00';
	$prec_act  = isset($_GET['prec_act'])?$_GET['prec_act']:'';
	$cod_emp   = isset($_GET['cod_emp'])?$_GET['cod_emp']:'';
	$cod_grupo = (isset($_GET['cod_grupo']) && $_GET['cod_grupo']!=0)?$_GET['cod_grupo']:'';

	$cod_user = $_SESSION['cod_user'];

	include('../View/Simulador/index.php');
}

function getComision($link, $tipo){

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

function round_out ($value, $places=0) {
  if ($places < 0) { $places = 0; }
  $mult = pow(10, $places);
  return ($value >= 0 ? ceil($value * $mult):floor($value * $mult)) / $mult;
}

function datoscabAction(){
	
	include('../Config/Conexion.php');
	$link = getConexion();

	$com = getComision($link, 'contado');

	$oper            = $_GET['oper'];
	$cod_emp         = $_GET['cod_emp'];
	$tipo            = $_GET['tipo'];
	$tipo_two        = $_GET['tipo_two'];
	$monto_estimado  = ($_GET['monto_estimado']!='' && (float)$_GET['monto_estimado']>0)?(float)$_GET['monto_estimado']:0;
	$precio_unitario = ($_GET['precio_unitario']!='' && (float)$_GET['precio_unitario']>0)?(float)$_GET['precio_unitario']:0;
	$gan_renta_obj   = ($_GET['gan_renta_obj']!='' && (float)$_GET['gan_renta_obj']>0)?(float)$_GET['gan_renta_obj']:0;
	$gan_pre_obj     = ($_GET['gan_pre_obj']!='' && (float)$_GET['gan_pre_obj']>0)?(float)$_GET['gan_pre_obj']:0;
	
	

	$cz_cn_fin = 5000.00;
	$cz_ci_fin = 0;

	if ($tipo == 'uno') {

		if ($oper != 'ver_simu') {

			$sql  = "SELECT * FROM empresa WHERE cod_emp='$cod_emp' LIMIT 1";
			$resp = mysqli_query($link,$sql);
			$r    = mysqli_fetch_array($resp);

			//$cz_cn_fin = ($r['cz_cn_fin'] > 0)?$r['cz_cn_fin']:0;//Monto estimado
			$cz_ci_fin = ($r['cz_ci_fin'] > 0)?$r['cz_ci_fin']:0;//Precio unitario ultima cotizacion
		}else{
			$cz_cn_fin = $monto_estimado;
			$cz_ci_fin = $precio_unitario;
		}
		
	}elseif ($tipo == 'dos') {

		$cz_cn_fin = $monto_estimado;
		$cz_ci_fin = $precio_unitario;
		
	}
	
	$cant_acc = ($cz_cn_fin > 0 && $cz_ci_fin>0)?ceil($cz_cn_fin/$cz_ci_fin):0;
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
	$gan_pre_min = round_out(($mont_neg+($c_costo_compra*2.12850))/$cant_acc,2);
	
	if ($tipo_two =='precio_obj') {
		$gan_pre_obj = $gan_pre_obj;
	}else{
		if ($oper =='ver_simu') {
			$gan_pre_obj = $gan_pre_obj;
		}else{
			$gan_pre_obj = round_out(($mont_neg+$gan_renta_obj+($c_costo_compra*2.1285))/$cant_acc,2);
		}
		
	}
	
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
	$res_cost_total = $c_costo_compra + $v_costo_venta;
	$res_var_total  = $res_gan_neta + $res_cost_total;

	$porc_gan_neta   = ($res_gan_neta / $mont_neg)*100;
	$porc_cost_total = ($res_cost_total / $mont_neg)*100;
	$por_var_total   = ($res_var_total / $mont_neg)*100;

	$info = array(
				//CABECERA
				'mont_est'=>number_format($cz_cn_fin,2,'.',''),
				'pre_unit'=>($cz_ci_fin>=1)?number_format($cz_ci_fin,2,'.',''):number_format($cz_ci_fin,3,'.',''),
				'cant_acc'=>number_format($cant_acc,0,'.',','),				
				'mont_neg'=>number_format($mont_neg,2,'.',','),
				//COMPRA
				'c_comision_sab' =>number_format($c_comision_sab,2,'.',''),
				'c_cuota_bvl'    =>number_format($c_cuota_bvl,2,'.',','),
				'c_f_garantia'   =>number_format($c_f_garantia,2,'.',','),
				'c_cavali'       =>number_format($c_cavali,2,'.',','),
				'c_f_liquidacion'=>number_format($c_f_liquidacion,2,'.',','),
				'c_compra_total' =>number_format($c_compra_total,2,'.',','),
				'c_igv'          =>number_format($c_igv,2,'.',','),
				'c_compra_smv'   =>number_format($c_compra_smv,2,'.',','),
				'c_costo_compra' =>number_format($c_costo_compra,2,'.',','),
				'c_poliza_compra'=>number_format($c_poliza_compra,2,'.',','),
				//GANANCIA
				'gan_pre_min' => ($gan_pre_min>=1)?number_format($gan_pre_min,2,'.',''):number_format($gan_pre_min,3,'.',''),
				'gan_pre_obj' => ($gan_pre_obj>=1)?number_format($gan_pre_obj,2,'.',''):number_format($gan_pre_obj,3,'.',''),
				'gan_var_pre' => ($gan_var_pre>=1)?number_format($gan_var_pre,2,'.',''):number_format($gan_var_pre,3,'.',''),
				'gan_val_vent' => number_format($gan_val_vent,2,'.',''),
				//VENTA
				'v_comision_sab' => number_format($v_comision_sab,2,'.',''),
				'v_cuota_bvl' => number_format($v_cuota_bvl,2,'.',''),
				'v_f_garantia' => number_format($v_f_garantia,2,'.',''),
				'v_cavali' => number_format($v_cavali,2,'.',''),
				'v_f_liquidacion' => number_format($v_f_liquidacion,2,'.',''),
				'v_com_total' => number_format($v_com_total,2,'.',''),
				'v_igv' => number_format($v_igv,2,'.',''),
				'v_com_smv' => number_format($v_com_smv,2,'.',''),
				'v_costo_venta' => number_format($v_costo_venta,2,'.',''),
				'v_poliza_venta' => number_format($v_poliza_venta,2,'.',''),
				//RESUMEN
				'res_gan_neta' => number_format($res_gan_neta,2,'.',''),
				'res_cost_total' => number_format($res_cost_total,2,'.',''),
				'res_var_total' => number_format($res_var_total,2,'.',''),
				'porc_gan_neta' => number_format($porc_gan_neta,2,'.','').'%',
				'porc_cost_total' => number_format($porc_cost_total,2,'.','').'%',
				'por_var_total' => number_format($por_var_total,2,'.','').'%'
			);

	echo json_encode($info);
}

function getEmpresaPorGrupoAction(){

	include('../Config/Conexion.php');
	$link = getConexion();

	$cod_user  = $_SESSION['cod_user'];

	$cod_grupo = $_GET['cod_grupo'];

	$sql = "SELECT DISTINCT(e.cod_emp), e.nemonico,e.nombre FROM empresa_favorito ef
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
		$html .= '<option value="'.$r['cod_emp'].'">'.$r['nemonico'].' - '.$r['nombre'].'</option>';
	}

	echo $html;
}


switch ($_GET['accion']) {
	case 'index':
		indexAction();
		break;
	case 'datoscab':
		datoscabAction();
		break;
	case 'add_portafolio':
		addPortafolioAction();
		break;
	case 'empresaporgrupo':
		getEmpresaPorGrupoAction();
		break;
}
?>
