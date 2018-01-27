<?php

function limpiarCadena($valor)
{
	$valor = str_ireplace("SELECT","",$valor);
	$valor = str_ireplace("UPDATE","",$valor);
	$valor = str_ireplace("COPY","",$valor);
	$valor = str_ireplace("DELETE","",$valor);
	$valor = str_ireplace("DROP","",$valor);
	$valor = str_ireplace("DUMP","",$valor);
	$valor = str_ireplace(" OR ","",$valor);
	$valor = str_ireplace("%","",$valor);
	$valor = str_ireplace("LIKE","",$valor);
	$valor = str_ireplace("--","",$valor);
	$valor = str_ireplace("^","",$valor);
	$valor = str_ireplace("[","",$valor);
	$valor = str_ireplace("]","",$valor);
	//$valor = str_ireplace('\',"",$valor);
	$valor = str_ireplace("!","",$valor);
	$valor = str_ireplace("¡","",$valor);
	$valor = str_ireplace("?","",$valor);
	$valor = str_ireplace("=","",$valor);
	$valor = str_ireplace("&","",$valor);

	return $valor;
}

function round_out ($value, $places=0) {
  if ($places < 0) { $places = 0; }
  $mult = pow(10, $places);
  return ($value >= 0 ? ceil($value * $mult):floor($value * $mult)) / $mult;
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

function quitar_tildes($cadena) {
	$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
	return $texto;
}
?>