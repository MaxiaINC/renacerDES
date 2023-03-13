<?php 
	include("conexion.php");
	sessionrestore();
    $oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}

    switch($oper)
    {
		case "crear":
			crear();
			break; 
		case "editar":
			editar();
			break;
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

	function crear(){
		global $mysqli;

		$data = (!empty($_REQUEST['datosAco']) ? $_REQUEST['datosAco'] : '');

		//DATOS PERSONALES  
	    $tipodocumento = (!empty($data['tipodocumento_ac']) ? $data['tipodocumento_ac'] : '');
		$cedula = (!empty($data['cedula_ac']) ? $data['cedula_ac'] : '');
		$nombre = (!empty($data['nombre_ac']) ? $data['nombre_ac'] : '');
		$apellidopaterno = (!empty($data['apellidopaterno_ac']) ? $data['apellidopaterno_ac'] : '');
		$apellidomaterno = (!empty($data['apellidomaterno_ac']) ? $data['apellidomaterno_ac'] : '');
		$correo = (!empty($data['correo_ac']) ? $data['correo_ac'] : '');
		$celular = (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
		$telefono = (!empty($data['telefonootro_ac']) ? $data['telefonootro_ac'] : '');
		$fecha_nac = (!empty($data['fecha_nac_ac']) ? $data['fecha_nac_ac'] : '');		 
		$sexo = (!empty($data['sexo_ac']) ? $data['sexo_ac'] : ''); 
				
		//DIRECCIÓN
		//$iddireccion = getValor('direccion','pacientes',$idpaciente);
		$urbanizacion = (!empty($data['urbanizacion_ac']) ? $data['urbanizacion_ac'] : '');
		$calle = (!empty($data['calle_ac']) ? $data['calle_ac'] : '');
		$edificio = (!empty($data['edificio_ac']) ? $data['edificio_ac'] : '');
		$numero = (!empty($data['numerocasa_ac']) ? $data['numerocasa_ac'] : '');
		$provincia = (!empty($data['idprovincias_ac']) ? $data['idprovincias_ac'] : '');
		$distrito = (!empty($data['iddistritos_ac']) ? $data['iddistritos_ac'] : '');
		$corregimiento = (!empty($data['idcorregimientos_ac']) ? $data['idcorregimientos_ac'] : ''); 

		//DIRECCIÓN
		$queryD = "	SELECT id FROM direcciones WHERE provincia = '".$provincia."' AND distrito = '".$distrito."' 
					AND corregimiento = '".$corregimiento."' ";
		$resD 	= getRegistroSQL($queryD);
		$idD 	= $resD['id'];
		
		$query_direccion = " INSERT INTO direccion_estacionamiento (id, urbanizacion, calle, edificio, numero, iddireccion) VALUES (
							NULL, '".$urbanizacion."', '".$calle."', '".$edificio."', '".$numero."', '".$idD."' );";
		if($mysqli->query($query_direccion	)){
			$iddireccion = $mysqli->insert_id;
		}else{
			echo $query_direccion;
		}
		$query_acompanante = "INSERT INTO acompanantesestacionamiento 
		(
			nombre, 
			apellido,  
			cedula, 
			celular, 
			telefono, 
			correo, 
			fecha_nac, 
			tipo_documento, 
			sexo, 
			direccion
		"; 
		
		$query_acompanante .= ") 
			VALUES (
				'".$nombre."', 
				'".$apellido."',  
				'".$cedula."', 
				'".$celular."',
				'".$telefono."', 
				'".$correo."',	
				'".$fecha_nac."', 
				'".$tipodocumento."', 
				'".$sexo."', 
				'".$iddireccion."'
				)
			";

		//debugL($query_acompanante);		
		if($mysqli->query($query_acompanante)){							
			$idacompanante = $mysqli->insert_id;   
							
			//nuevoRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$iddireccion,$camposD,$query_direccion);
			//nuevoRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$camposP,$query_paciente);
			
			$response = array( "success" => true, "idacompanante" => $idacompanante, "msj" => 'Acompañante almacenado satisfactoriamente' );
			echo json_encode($response);
		}else{
			echo $query_acompanante;
		}		 
	}

	function editar(){
		global $mysqli;
		
		$data = (!empty($_REQUEST['datosAco']) ? $_REQUEST['datosAco'] : '');
		$idacompanante = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');

		//DATOS PERSONALES  
	    $tipodocumento = (!empty($data['tipodocumento_ac']) ? $data['tipodocumento_ac'] : '');
		$cedula = (!empty($data['cedula_ac']) ? $data['cedula_ac'] : '');
		$nombre = (!empty($data['nombre_ac']) ? $data['nombre_ac'] : '');
		$apellido = (!empty($data['apellido_ac']) ? $data['apellido_ac'] : ''); 
		$correo = (!empty($data['correo_ac']) ? $data['correo_ac'] : '');
		$celular = (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
		$telefono = (!empty($data['telefonootro_ac']) ? $data['telefonootro_ac'] : '');
		$fecha_nac = (!empty($data['fecha_nac_ac']) ? $data['fecha_nac_ac'] : '');		 
		$sexo = (!empty($data['sexo_ac']) ? $data['sexo_ac'] : ''); 
		$iddireccion = (!empty($data['iddireccion']) ? $data['iddireccion'] : ''); //Id en la tabla direcciones

		//DIRECCIÓN
		$urbanizacion = (!empty($data['urbanizacion_ac']) ? $data['urbanizacion_ac'] : '');
		$calle = (!empty($data['calle_ac']) ? $data['calle_ac'] : '');
		$edificio = (!empty($data['edificio_ac']) ? $data['edificio_ac'] : '');
		$numero = (!empty($data['numerocasa_ac']) ? $data['numerocasa_ac'] : '');
		$provincia = (!empty($data['idprovincias_ac']) ? $data['idprovincias_ac'] : '');
		$distrito = (!empty($data['iddistritos_ac']) ? $data['iddistritos_ac'] : '');
		$corregimiento = (!empty($data['idcorregimientos_ac']) ? $data['idcorregimientos_ac'] : '');
		$direccion 	= (!empty($data['direccion']) ? $data['direccion'] : ''); //Id en la tabla direccion_estacionamiento

		$query_direccion= "UPDATE direccion_estacionamiento SET 
								urbanizacion = '".$calle."',
								calle = '".$calle."',
								edificio = '".$edificio."',
								numero = '".$numero."',
								iddireccion = '".$iddireccion."'
							WHERE id = '$direccion';";
		if($mysqli->query($query_direccion)){
			
		}else{
			echo $query_direccion;die();
		} 
			
		$query_acompanante ="UPDATE 
								acompanantesestacionamiento 
							SET
								nombre = '".$nombre."',
								apellido = '".$apellido."',
								cedula = '".$cedula."',
								celular = '".$celular."',
								telefono = '".$telefono."',
								correo = '".$correo."',
								fecha_nac = '".$fecha_nac."',
								tipo_documento = '".$tipodocumento."', 
								sexo = '".$sexo."', 
								direccion = '".$iddireccion."' 
							WHERE 
								id = '$idacompanante'";				
								//echo $query_acompanante;
		if($mysqli->query($query_acompanante)){							
			 $resultado = array(
				'idacompanante' => $idacompanante,
				'nombre'=>$nombre." ".$apellido,
				'cedula' => $cedula
			);
			echo json_encode($resultado);
		}else{
			echo $query_acompanante; die();
		}		
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