<?php

/*params array(
select => ('id'=>'', 'name'=>'', 'class'=>'', 'etc'=>'etc')
sql => sql nativo / nombre del procedimiento
attrib => array('value'=>'campo value', 'desc'=>'campo1, campo2, etc', 'concat'=>' - ')
empty => valor al sin value
defect => valor defecto del combo
edit => valor para la edición
enable => 1/0; habilitado/deshabilitado
)*/

function Combobox($link, $params){

	$select      = $params['select'];
	$sql         = $params['sql']; //sql nativo / nombre del procedimiento
	$campo_value = $params['attrib']['value']; //valor del combo
	$campo_desc  = $params['attrib']['desc']; //descripcion del combo
	$campo_contact  = $params['attrib']['concat'];//este campo indica que puedes concatenar las descripciones pasadas
	$empty       = $params['empty']; // '': indica que no habla valor vacio
	$defect      = $params['defect']; //valor defecto del combo
	$edit        = $params['edit']; //valor para la edición
	$enable      = $params['enable']; // 1/0; habilitado/deshabilitado
	$descextra   = (isset($params['attrib']['descextra']))?$params['attrib']['descextra']:''; //descripvion extra que puede servir para agrgara cualquier valor al combo como descripcion

	//$function    = $params['function']; //nombre de la funcion al hacer change
	//$trigger     = $params['trigger']; //1/0; 1:se ejecuta la accion al cargar la página

	$resp_cb = mysqli_query($link, $sql) or die (mysqli_error($link));
	
	//mysqli_close($link);

	//select
	$propiedades_select = '';

	foreach ($select as $key => $value) {
		
		$propiedades_select .= ' '.$key.'="'.$value.'"';
	}
	//evaluamos disabled
	$disabled = '';
	if($enable =='disabled'){
		$disabled = ' disabled="disabled"'; 
	}

	$select = '<select'.$propiedades_select.$disabled.'>';

	if ($empty !='') {
		$select .= '<option value="">'.$empty.'</option>';
	}
	

	while($row = mysqli_fetch_array($resp_cb)){

		$selected = '';
		

		if($edit !=''){
			if($row[$campo_value] == $edit){
				$selected = " selected";
			}
		}else{
			if ($defect !='' && $row[$campo_value] == $defect) {
				$selected = " selected"; 
			}
		}

		//manejmaos la descripcion del combo
		$descipcion = '';
		$explode_desc = explode(',', $campo_desc);
		//echo "Cantidad:".count($explode_desc)."<br>";
		for ($c=0; $c < count($explode_desc); $c++) {
			if (count($explode_desc) == $c+1) {
				$descipcion .= $row[$explode_desc[$c]];
			}else{
				$descipcion .= $row[$explode_desc[$c]].$campo_contact;
			}
		}
		//fin contact descripcion

		$description = '';
		if ($descextra !='') {
			$description = $descextra.$campo_contact.$descipcion;
		}else{
			$description = $descipcion;
		}

		$select .= '<option value="'.$row[$campo_value].'"'.$selected.'>'.$description.'</option>';
	}

	$select .= '</select>';

	print($select);

	
}

