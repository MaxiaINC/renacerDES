<?php

function bitacora($modulo, $accion, $identificador='', $sentencia='') {
	global $mysqli;
	$usuario = $_SESSION['usuario_sen'];
	$sentencia = str_replace("'"," ",$sentencia);
	$query = "INSERT INTO bitacora VALUES(null, '$usuario', now(), '$modulo', '$accion', '$identificador', '$sentencia')";

	$consulta = $mysqli->query($query); 	
} 

function nuevoRegistro($modulo,$tiporegistro,$id,$campos,$query){

	$acciones = "Fue creado un registro en $tiporegistro con el id #$id. <br/>";    
	$acciones .= "<ul>";
	foreach($campos as $campo => $valor){
		if($valor != ''){
			$acciones .= "<li><b>".ucfirst($campo)."</b>: ".json_encode($valor,JSON_UNESCAPED_UNICODE).".</li>";
		}
	}
	$acciones .= "</ul>";
	
	bitacora($modulo, $acciones, $id, $query);
}

function actualizarRegistro($modulo,$tiporegistro,$id,$valoresold,$valoresnew,$query){
	global $mysqli;
	$test = 0;
	$acciones = "Fue actualizado un registro en $tiporegistro con el id #$id. <br/>";
    
	$acciones .= "<ul>";
	//debugL(json_encode($valoresold));
	foreach($valoresold as $campo => $valor){
		if(is_array($valoresnew[$campo])){
			$valoresn = implode(',',$valoresnew[$campo]);
		}else{
			$valoresn = $valoresnew[$campo];
		}
		debugL('VALOR:'.$valor.'-VALORESN:'.$valoresn.'-','DEBUGLBITACORA');
		if($valor != $valoresn || !array_key_exists ($campo, $valoresnew )){
			if( !array_key_exists ($campo, $valoresnew )  ){
				if($valor != ''){
					$acciones .= "<li><b>".ucfirst($campo)."</b>: $valor.</li>";
					$test++;
				}
			} else {
				$acciones .= "<li>El campo <b>$campo</b> fue modificado. Valor anterior: ".$valor." / Valor nuevo: ".$valoresn.".</li>";
					debugL('$acciones:'.$acciones,'DEBUGLBITACORA');
				$test++;
			}
		}
	}
	$acciones .= "</ul>";
	
	if($test>0){
		bitacora($modulo, $acciones, $id, $query);
	}
}

function eliminarRegistro($modulo,$tiporegistro,$nombre,$id,$query){
	$acciones = "Fue eliminado un registro en $modulo de nombre \"$nombre\", con el id #$id";
	bitacora($modulo, $acciones, $id, $query);
}

function guardarRegistroG($modulo, $accion, $identificador='', $sentencia=''){	
	bitacora($modulo, $accion, $identificador, $sentencia);
}

function getId($campo,$tabla,$valor,$regla){
	global $mysqli;
	
	if($valor != ''){
		$q = "SELECT $campo FROM $tabla WHERE $regla = '$valor' LIMIT 1";
		$r = $mysqli->query($q);
		$val = $r->fetch_assoc();
		$valor = $val[$campo];
	}else{
		$valor = '';
	}	
	return $valor;
}

function getValor($campo,$tabla,$id){
	global $mysqli;
	
	if($id != '' || $id >= '0'){
		$q = "SELECT $campo FROM $tabla WHERE id IN ($id) LIMIT 1";		
			debugL('q'.$q);
		$r = $mysqli->query($q);
		$val = $r->fetch_assoc();
		//evalua para evitar que un resultado null de error
		if($val){
		$valor = $val[$campo];
	}else{
		$valor = '';
	}
	}else{
		//debugL('id 2: '.$id,'proyectos');
		$valor = '';
	}	
	return $valor;
}

function getValorCorreo($campo,$tabla,$id){
	global $mysqli;
	$id = str_replace('[', '', $id);
	$id = str_replace(']', '', $id);
		$q = "SELECT $campo FROM $tabla WHERE correo IN ($id)";		
		//debugL('id 1: '.$q);		
		$r = $mysqli->query($q);
		$val = $r->fetch_assoc();
		$valor = $val[$campo];
		
	return $valor;
}

function getValorEx($campo,$tabla,$id,$regla){
	global $mysqli;
	if( $tabla != 'tipoplan' ){
		$q = "	SELECT $campo FROM $tabla WHERE $regla = '$id' LIMIT 1";	
		//debugL($q);
		if($r = $mysqli->query($q)){
			$val = $r->fetch_assoc();
		} else {
			die($mysqli->error);
		}
		
		$valor = $val[$campo];
	}else{
		switch($id ){
			case 'A':
				$valor = 'Automático';
			break;
			case 'M':
				$valor = 'Manual';
			break;
			case 'D':
				$valor = 'Desactivar';
			break;
			default:
				$valor = '';
			break;
		}
	}
	
	return $valor;
}

function getValorJoin($campo,$tabla,$tablajoin,$on,$id){
	global $mysqli;
	
	$q = "SELECT b.$campo FROM $tabla a LEFT JOIN $tablajoin b ON b.id = a.$on WHERE a.id = $id LIMIT 1";
	//debugL($q);
	if($r = $mysqli->query($q)){
		$val = $r->fetch_assoc();
	} else {
		die($mysqli->error);
	}
	
	$valor = $val[$campo];
	
	return $valor;
}

function getValores($campo,$tabla,$ids){
	global $mysqli;
	
	$q = "SELECT GROUP_CONCAT($campo) as $campo FROM $tabla WHERE FIND_IN_SET(id,'$ids') ";
	if($r = $mysqli->query($q)){
		$val = $r->fetch_assoc();
	} else {
		die($mysqli->error);
	}	
	$valor = $val[$campo];
	
	return $valor;
}

function getRegistro($tabla,$id){
	global $mysqli;
	
	$q = "SELECT * FROM $tabla WHERE id = $id LIMIT 1";
//	debugL($q);
	$r = $mysqli->query($q);
	$val = $r->fetch_assoc();
	$valor[] = $val;
	
	return $valor;
}

function getRegistroSQL($query){
	global $mysqli;
	
	if($r = $mysqli->query($query)){
		$val = $r->fetch_assoc();
	} else {
		die($mysqli->error);
	}
	
	return $val;
}

function getRegistroJoin($tabla,$id,$joins){
	global $mysqli;
	
	$select = '';
	$rjoins = '';
	$i = 2;
	
	foreach($joins as $join){
		$letra = toAlpha($i);
		$select .= $join['campos'];
		$rjoins .= ' LEFT JOIN '.$join['tabla'].' '.$letra.' ON '.$letra.'.id = a.'.$join['on'].' ';		
		$i++;
	}
	
	$q = "	SELECT a.*,$select FROM $tabla a 
			$rjoins
			WHERE a.id = $id 
			LIMIT 1";
	$r = $mysqli->query($q);
	$val = $r->fetch_assoc();
	$valor[] = $val;
	
	return $valor;
}

function toAlpha($data){
    $alphabet =   array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    $alpha_flip = array_flip($alphabet);
	
	if($data <= 25){
	  return $alphabet[$data];
	}
	elseif($data > 25){
	  $dividend = ($data + 1);
	  $alpha = '';
	  $modulo;
	  while ($dividend > 0){
		$modulo = ($dividend - 1) % 26;
		$alpha = $alphabet[$modulo] . $alpha;
		$dividend = floor((($dividend - $modulo) / 26));
	  } 
	  return $alpha;
	}
}

function getNumRegistroSQL($query){
	global $mysqli;
	$num = 0;
	//debugL($query);
	if($r = $mysqli->query($query)){
		$val = $r->fetch_assoc();
		$num = $val['count'];
		//$num = intval($r->num_rows);
	} else {
		die($mysqli->error);
	}
	
	return $num;
}

function actcorreo($valor, $campo, $tabla, $valoractual){
	global $mysqli;
	
	if ($campo == 'asignadoa' && $tabla == 'proyectosganttdet') {
		$valorn = '"'.$valor.'"';
		$queryBU = " SELECT id, ".$campo." FROM ".$tabla." WHERE ".$campo." != '' AND JSON_CONTAINS(".$campo.", '[".$valorn."]' ) ";
		//debugL($queryBU);
		$resultBU = $mysqli->query($queryBU);
		if($resultBU->num_rows > 0 ){
			while($rowBU = $resultBU->fetch_assoc()){
				$id = $rowBU['id'];
				//ANT
				$valorUP = $rowBU[$campo];
				$search   = array('[', ']', '"');
				$replace  = array('');
				$valorant = str_replace($search, $replace, $valorUP);				
				$arreglo = explode(',',$valorant);
				//BUSCAR
				if (($key = array_search($valor, $arreglo)) !== false){
					unset($arreglo[$key]);
				}
				$arreglo[] = $valoractual;
				//LIMPIAR
				$valorlimpio = array_unique(array_filter(array_map('trim', $arreglo)));
				$valorfinal = implode('","',$valorlimpio);
				$valorfinal = '["'.$valorfinal.'"]';
				
				//GUARDAR
				$queryU = " UPDATE ".$tabla." SET ".$campo." = '".$valorfinal."' WHERE id = '".$id."' ";
				//debugL($queryU);
				$result = $mysqli->query($queryU);
			}
		}		
	}else{
		$queryBU = " SELECT id, ".$campo." FROM ".$tabla." WHERE FIND_IN_SET('".$valor."',".$campo.") ";
		//debugL('queryBU: '.$queryBU);
		$resultBU = $mysqli->query($queryBU);
		if($resultBU->num_rows > 0 ){
			//debugL('num_rows');
			while($rowBU = $resultBU->fetch_assoc()){
				$id = $rowBU['id'];
				//ANT			
				$valorant = $rowBU[$campo];
				$arreglo = explode(',',$valorant);
				//BUSCAR
				if (($key = array_search($valor, $arreglo)) !== false){
					unset($arreglo[$key]);
				}
				$arreglo[] = $valoractual;
				//LIMPIAR
				$valorlimpio = array_unique(array_filter(array_map('trim', $arreglo)));
				$valorfinal = implode(',',$valorlimpio);
				
				//GUARDAR
				$queryU = " UPDATE ".$tabla." SET ".$campo." = '".$valorfinal."' WHERE id = '".$id."' ";
				//debugL($queryU);
				$result = $mysqli->query($queryU);
			}
		}
	}	
}

function eliminarreg($valor, $campo, $tabla){
	global $mysqli;
	
	$queryBU = " SELECT id, ".$campo." FROM ".$tabla." WHERE FIND_IN_SET('".$valor."',".$campo.") ";
	//debugL('queryBU: '.$queryBU);
	$resultBU = $mysqli->query($queryBU);
	if($resultBU->num_rows > 0 ){
		while($rowBU = $resultBU->fetch_assoc()){
			$id = $rowBU['id'];
			//ANT			
			$valorant = $rowBU[$campo];
			$arreglo = explode(',',$valorant);
			//BUSCAR
			if (($key = array_search($valor, $arreglo)) !== false){
				unset($arreglo[$key]);
			}
			//LIMPIAR
			$valorlimpio = array_unique(array_filter(array_map('trim', $arreglo)));
			$valorfinal = implode(',',$valorlimpio);
			
			//GUARDAR
			$queryU = " UPDATE ".$tabla." SET ".$campo." = '".$valorfinal."' WHERE id = '".$id."' ";
			//debugL($queryU);
			$result = $mysqli->query($queryU);
		}
	}	
}

function getValoresA($campo,$tabla,$ids,$alias){
	global $mysqli;
	
	$q = "SELECT GROUP_CONCAT($campo) as $alias FROM $tabla WHERE FIND_IN_SET(id,'$ids') ";
	if($r = $mysqli->query($q)){
		$val = $r->fetch_assoc();
	} else {
		die($mysqli->error);
	}	
	$valor = $val[$alias];
	debugL("query: ".$q.",valor:".$valor);
	return $valor;
}

?>