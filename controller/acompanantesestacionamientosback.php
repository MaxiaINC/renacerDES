<?php 
	include("conexion.php");
	sessionrestore();
    $oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}

    switch($oper)
    {
		case "crear_acompanante":
			crear_acompanante();
			break; 
		case "getDatosAcompanantes":
			getDatosAcompanantes();
			break;
		case "update_acompanante":
			update_acompanante();
			break;
        default:
            echo "{failure:true}";
            break;
    } 

	function crear_acompanante(){
		global $mysqli;
		$arreglo 	 = (!empty($_REQUEST['arreglo']) ? $_REQUEST['arreglo'] : '');
		$iddirecciones= (!empty($_REQUEST['iddireccion']) ? $_REQUEST['iddireccion'] : '');
		$iddireccion = 0;
		$query_direccion = " INSERT INTO direccion_estacionamiento (urbanizacion,calle,edificio,numero,iddireccion) VALUES (
							 '".$arreglo['urbanizacion_ac']."',
							 '".$arreglo['calle_ac']."',
							 '".$arreglo['edificio_ac']."',
							 '".$arreglo['numero_ac']."',
							 '".$iddirecciones."'
							);";		
		if($mysqli->query($query_direccion	)){
			$iddireccion = $mysqli->insert_id;
		}else{
			echo $query_direccion;
		}
		$query_acompanante = "	INSERT INTO acompanantesestacionamiento (nombre, apellido, cedula, celular, telefono,	correo,	fecha_nac, tipo_documento, nacionalidad, sexo,
								 direccion )VALUES(
			'".$arreglo['nombre_ac']."',
			'".$arreglo['apellido_ac']."',
			'".$arreglo['cedula_ac']."',
			'".$arreglo['celular_ac']."',
			'".$arreglo['telefono_ac']."',
			'".$arreglo['correo_ac']."',
			'".$arreglo['fecha_nac_ac']."',
			'".$arreglo['tipodocumento_ac']."',
			'".$arreglo['nacionalidad_ac']."',
			'".$arreglo['sexo_ac']."',
			'".$iddireccion."'
		)";
		//debugL($query_acompanante);		
		if($mysqli->query($query_acompanante)){							
			$idacompanante	= $mysqli->insert_id;	
			 $resultado = array(
				'id' => $idacompanante,
				'nombre'=>$arreglo['nombre_ac']." ".$arreglo['apellido_ac'],
				'cedula' =>  $arreglo['cedula_ac'],
				'tipodocumento' =>  $arreglo['tipodocumento_ac'],
				'iddireccion' => $iddireccion
			);
			echo json_encode($resultado);
		}else{
			echo $query_acompanante;
		}		
	}

	function update_acompanante(){
		global $mysqli;
		$arreglo 		= $_REQUEST['arreglo'];
		$idacompanante 	= $_REQUEST['idacompanante'];
		$direccion 		= $_REQUEST['direccion'];
		$iddireccion 	= 0;
		$query_direccion= "UPDATE direccion_estacionamiento SET 
								urbanizacion = '".$arreglo['direccion']['urbanizacion']."',
								calle = '".$arreglo['direccion']['calle']."',
								edificio = '".$arreglo['direccion']['edificio']."',
								numero = '".$arreglo['direccion']['numero']."',
								iddireccion = '".$arreglo['direccion']['iddireccion']."'
							WHERE id = '$direccion';";
		if($mysqli->query($query_direccion)){
			
		}else{
			echo $query_direccion;die();
		}
			
		$acompanante_modotutor = (!empty($arreglo['acompanante']['modo_tutor']) ? $arreglo['acompanante']['modo_tutor'] : 0);	
			
		$query_acompanante ="UPDATE acompanantesestacionamiento SET
		nombre = '".$arreglo['acompanante']['nombre']."',
		apellido = '".$arreglo['acompanante']['apellido']."',
		cedula = '".$arreglo['acompanante']['cedula']."',
		celular = '".$arreglo['acompanante']['celular']."',
		telefono = '".$arreglo['acompanante']['telefono']."',
		correo = '".$arreglo['acompanante']['correo']."',
		fecha_nac = '".$arreglo['acompanante']['fecha_nac']."',
		tipo_documento = '".$arreglo['acompanante']['tipodocumento']."', 
		sexo = '".$arreglo['acompanante']['sexo']."', 
		direccion = '".$direccion."' 
         WHERE id = '$idacompanante';";				
		if($mysqli->query($query_acompanante)){							
			 $resultado = array(
				'id' => $idacompanante,
				'nombre'=>$arreglo['acompanante']['nombre']." ".$arreglo['acompanante']['apellido'],
				'cedula' =>  $arreglo['acompanante']['cedula']
			);
			echo json_encode($resultado);
		}else{
			echo $query_acompanante; die();
		}		
	}  

	function getDatosAcompanantes(){
		global $mysqli;
		$idacompanante = $_REQUEST['idacompanante'];
		$query = "	SELECT a.id, a.tipo_documento, a.cedula, CONCAT(a.nombre, ' ', a.apellido) AS nombre, a.nombre AS nombre_ac, 
					a.apellido AS apellido_ac, a.fecha_nac, a.sexo, a.estado_civil, a.telefono, a.celular, a.correo, a.nacionalidad, 
                    dir.provincia, dir.distrito, dir.corregimiento, dir.area, di.urbanizacion, di.calle, di.edificio, di.numero,
                    a.direccion
					FROM `acompanantesestacionamiento` a 
					LEFT JOIN direccion_estacionamiento di ON di.id = a.direccion 
					LEFT JOIN direcciones dir ON dir.id = di.iddireccion 
					WHERE a.id = '".$idacompanante."' ";
					//echo $query;
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' 			=> $row['id'],
				'nombre'   		=> $row['nombre'],
				'tipo_documento'=> $row['tipo_documento'],
				'cedula' 		=> $row['cedula'],
				'nombre_ac' 	=> $row['nombre_ac'],
				'apellido_ac' 	=> $row['apellido_ac'],
				'fecha_nac' 	=> $row['fecha_nac'],
				'sexo' 		    => $row['sexo'],
				'estado_civil' 	=> $row['estado_civil'],
				'telefono' 		=> $row['telefono'],
				'celular' 		=> $row['celular'],
				'correo' 		=> $row['correo'],
				'nacionalidad' 	=> $row['nacionalidad'], 
				'provincia' 	=> $row['provincia'], 
				'distrito' 		=> $row['distrito'], 
				'corregimiento' => $row['corregimiento'], 
                'direccion' 	=> $row['direccion'], 
				'area_ac' 		=> $row['area'], 
				'urbanizacion' 	=> $row['urbanizacion'], 
				'calle' 		=> $row['calle'], 
				'edificio' 		=> $row['edificio']
			);
		}
		echo json_encode($resultado);
	}

?>