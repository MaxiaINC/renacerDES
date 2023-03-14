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
		case "getPacientes":
            getPacientes();
            break;
        case "existeBeneficiario":
            existeBeneficiario();
            break;
        case "getbeneficiario":
            getbeneficiario();
            break;
        case "guardarBeneficiario":
            guardarBeneficiario();
            break;
        case "editarBeneficiario":
            editarBeneficiario();
            break;
		case "get_pacienteporsolicitud":
			get_pacienteporsolicitud();
			break;
        default:
            echo "{failure:true}";
            break;
    }
  
    function existeBeneficiario(){
		global $mysqli;
		$tipo_documento = $_REQUEST['tipo_documento'];
		$cedula 		= $_REQUEST['cedula'];
		$tipodocumento	= $_REQUEST['tipo_documento'];
		$expediente 	= $_REQUEST['expediente'];
		$valor 			= 0;
		
		$query = "  SELECT id,cedula,nombre,apellidopaterno,'estacionamientos' AS tipo FROM beneficiariosestacionamiento
                    WHERE tipo_documento = '".$tipodocumento."' AND cedula = '".$cedula."'
                    UNION 
                    SELECT id,cedula,nombre,apellidopaterno,'certificaciones' AS tipo FROM pacientes
                    WHERE tipo_documento = '".$tipodocumento."' AND cedula = '".$cedula."'"; 
                    //echo $query;
		$result = $mysqli->query($query);
        $num_rows = $result->num_rows;
        //echo $num_rows;
        if ($num_rows >= 2) {
            while($row = $result->fetch_assoc()){
                if($row['tipo']=='estacionamientos'){
                    $tipo = 'estacionamientos';
                    $id = $row['id'];
                }
            } 
        } else if ($num_rows == 1) {
            // Si retorna un registro, verificar el tipo y retornar según sea el caso
            $row = $result->fetch_assoc();
            if ($row['tipo'] == 'certificaciones') {
                $tipo = 'certificaciones';
                $id = $row['id'];
            } else if ($row['tipo'] == 'estacionamientos') {
                $tipo = 'estacionamientos';
                $id = $row['id'];
            }
        } 
		
		if($num_rows !== 0){		
			$resultado = array(
				'success' 	=> true,
				'id'		=> $id, 
                'tipo'		=> $tipo, 
			);
		}else{
			$resultado = array( 'success' => false, 'id' => '', 'nombre' => '', 'query' => $query );
		}
		echo json_encode($resultado);
	}

    
	function getbeneficiario(){
		global $mysqli;
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "	SELECT a.nombre, CONCAT(a.apellidopaterno,' ',a.apellidomaterno) as apellido, a.apellidopaterno, a.apellidomaterno,
					a.cedula, a.tipo_documento, a.fecha_nac, a.sexo, a.telefono, a.celular, a.correo, a.nacionalidad, a.estado_civil, 
					a.condicion_actividad, a.categoria_actividad, a.cobertura_medica, a.beneficios, a.beneficios_des, 
					a.discapacidades, a.direccion, a.expediente, a.status
					FROM beneficiariosestacionamiento a  
					WHERE a.id = '".$id."' ";
		 //echo $query;
		$result = $mysqli->query($query);
		if($row = $result->fetch_assoc()){	
			 
			
			$resultado['paciente'] = array(
				'tipo_documento' 	=> $row['tipo_documento'],
				'cedula'   			=> $row['cedula'],
				'nombre'   			=> $row['nombre'],
				'apellidopaterno' 	=> $row['apellidopaterno'],
				'apellidomaterno' 	=> $row['apellidomaterno'],
				'apellido' 			=> $row['apellido'],
				'expediente' 		=> $row['expediente'],
				'correo' 			=> $row['correo'],
				'telefono' 			=> $row['telefono'],
				'celular' 			=> $row['celular'],				
				'fecha_nac' 		=> $row['fecha_nac'],
				'fecha_vcto_cm' 	=> $row['fecha_vcto_cm'],
				'nacionalidad' 		=> $row['nacionalidad'],
				'sexo' 				=> $row['sexo'],				
				'estado_civil' 		=> $row['estado_civil'],
				'status' 			=> $row['status'],		 
				'idacompanante' 	=> $row['idacompanante'], 
			);
			$query_direccion = "SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero 
								FROM direccion_estacionamiento d 
								LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
								WHERE d.id = '".$row['direccion']."'";
			$result_d = $mysqli->query($query_direccion);
			if ($row_d = $result_d->fetch_assoc()) {
				$resultado['direccion'] = array(
					'provincia' 	=> $row_d['provincia'],
					'distrito' 		=> $row_d['distrito'],
					'corregimiento' => $row_d['corregimiento'],
					'area' 			=> $row_d['area'],
					'urbanizacion' 	=> $row_d['urbanizacion'],
					'calle' 		=> $row_d['calle'],
					'edificio' 		=> $row_d['edificio'],
					'numero' 		=> $row_d['numero']
				);			
			} 
			
		}
		echo json_encode($resultado);
	}

	function crear(){
		global $mysqli;

		$data = (!empty($_REQUEST['datosBen']) ? $_REQUEST['datosBen'] : '');

		//DATOS PERSONALES  
	    $tipodocumento = (!empty($data['tipodocumento']) ? $data['tipodocumento'] : '');
		$lugarsolicitud = (!empty($data['lugarsolicitud']) ? $data['lugarsolicitud'] : '');
		$cedula = (!empty($data['cedula']) ? $data['cedula'] : '');
		$nombre = (!empty($data['nombre']) ? $data['nombre'] : '');
		$apellidopaterno = (!empty($data['apellidopaterno']) ? $data['apellidopaterno'] : '');
		$apellidomaterno = (!empty($data['apellidomaterno']) ? $data['apellidomaterno'] : '');
		$correo = (!empty($data['correo']) ? $data['correo'] : '');
		$celular = (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
		$telefono = (!empty($data['telefonootro']) ? $data['telefonootro'] : '');
		$fecha_nac = (!empty($data['fecha_nac']) ? $data['fecha_nac'] : '');		 
		$sexo = (!empty($data['sexo']) ? $data['sexo'] : ''); 
				
		//DIRECCIÓN
		$iddireccion = getValor('direccion','pacientes',$idpaciente);
		$urbanizacion = (!empty($data['urbanizacion']) ? $data['urbanizacion'] : '');
		$calle = (!empty($data['calle']) ? $data['calle'] : '');
		$edificio = (!empty($data['edificio']) ? $data['edificio'] : '');
		$numero = (!empty($data['numerocasa']) ? $data['numerocasa'] : '');
		$provincia = (!empty($data['idprovincias']) ? $data['idprovincias'] : '');
		$distrito = (!empty($data['iddistritos']) ? $data['iddistritos'] : '');
		$corregimiento = (!empty($data['idcorregimientos']) ? $data['idcorregimientos'] : ''); 

		$nombreregional	= getValor('nombre','regionales',$lugarsolicitud);
		$siglasregional = strtoupper(substr($nombreregional, 0, 3));

		//8PAN1215803
		$expediente = crearCodigoIdentificacion($tipodocumento, $cedula, $siglasregional);

		//VALIDAR CEDULA
		$bced = "SELECT cedula FROM beneficiariosestacionamiento where cedula = '".$cedula."' ";
		$resultced = $mysqli->query($bced);
		$totced = $resultced->num_rows;
		if($totced == 0){ 
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
				$query_paciente = "INSERT INTO beneficiariosestacionamiento 
                (
                    nombre, 
                    apellidopaterno, 
                    apellidomaterno, 
                    cedula, 
                    celular, 
                    telefono, 
                    correo, 
                    fecha_nac, 
                    tipo_documento, 
                    sexo, 
                    direccion
                ";
				if($idbeneficiario != '' && $tipobeneficiario == 'certificaciones'){
					$query_paciente .= ",idpacientescertificaciones ";			
				} 
				
				$query_paciente .= ") 
                    VALUES (
                        '".$nombre."', 
                        '".$apellidopaterno."', 
                        '".$apellidomaterno."', 
                        '".$cedula."', 
                        '".$celular."',
                        '".$telefono."', 
                        '".$correo."',	
                        '".$fecha_nac."', 
                        '".$tipodocumento."', 
                        '".$sexo."', 
                        '".$iddireccion."'
                    ";
				
				if($idbeneficiario != '' && $tipobeneficiario == 'certificaciones'){
					$query_paciente .= ", '".$idbeneficiario."' ";					
				} 		
				
				$query_paciente .= " ) ";
				//echo $query_paciente;

				if($mysqli->query($query_paciente)){
					$idpaciente = $mysqli->insert_id;   
									
					//nuevoRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$iddireccion,$camposD,$query_direccion);
					//nuevoRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$camposP,$query_paciente);
					
					$response = array( "success" => true, "idbeneficiario" => $idpaciente, "expediente" => $expediente, "msj" => 'Beneficiario almacenado satisfactoriamente' );			
				}else{
					$response = array( "success" => false, "idbeneficiario" => '', "expediente" => '', "msj" => 'Error al guardar el beneficiario, por favor intente más tarde' );
				} 
		}else{
			$response = array( "success" => false, "idbeneficiario" => '', "msj" => 'El Nº de documento ya esta registrado' );
		}

		echo json_encode($response);

	}

	function editar(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datosBen']) ? $_REQUEST['datosBen'] : '');		
		//DATOS PERSONALES
		$idpaciente		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
	    $tipobeneficiario = (!empty($data['tipobeneficiario']) ? $data['tipobeneficiario'] : '');	
		$tipodocumento  = (!empty($data['tipodocumento']) ? $data['tipodocumento'] : '');
		$cedula  		= (!empty($data['cedula']) ? $data['cedula'] : '');
		$nombre  		= (!empty($data['nombre']) ? $data['nombre'] : '');
		$apellidopaterno= (!empty($data['apellidopaterno']) ? $data['apellidopaterno'] : '');
		$apellidomaterno= (!empty($data['apellidomaterno']) ? $data['apellidomaterno'] : '');		
		$correo  		= (!empty($data['correo']) ? $data['correo'] : '');
		$celular		= (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
		$telefono		= (!empty($data['telefonootro']) ? $data['telefonootro'] : '');
		$fecha_nac		= (!empty($data['fecha_nac']) ? $data['fecha_nac'] : '');		
		$nacionalidad  	= (!empty($data['nacionalidad']) ? $data['nacionalidad'] : '');
		$sexo			= (!empty($data['sexo']) ? $data['sexo'] : '');
		$estado_civil	= (!empty($data['estado_civil']) ? $data['estado_civil'] : '');
		$status			= (!empty($data['status']) ? $data['status'] : 0); 
		//DIRECCIÓN
		$iddireccion 	= getValor('direccion','pacientes',$idpaciente);
		$urbanizacion  	= (!empty($data['urbanizacion']) ? $data['urbanizacion'] : '');
		$calle  		= (!empty($data['calle']) ? $data['calle'] : '');
		$edificio  		= (!empty($data['edificio']) ? $data['edificio'] : '');
		$numero  		= (!empty($data['numerocasa']) ? $data['numerocasa'] : '');
		$provincia  	= (!empty($data['idprovincias']) ? $data['idprovincias'] : '');
		$distrito  		= (!empty($data['iddistritos']) ? $data['iddistritos'] : '');
		$corregimiento	= (!empty($data['idcorregimientos']) ? $data['idcorregimientos'] : '');
		
		//ACOMPAÑANTE
		$idacompanante		= (!empty($data['idacompanante']) ? $data['idacompanante'] : 0);
		
		//DIRECCIÓN
		$queryD = " SELECT id 
					FROM direcciones 
					WHERE provincia = '".$provincia."' 
					AND distrito = '".$distrito."' 
					AND corregimiento = '".$corregimiento."'";
					$resD = getRegistroSQL($queryD);
					$idD = $resD['id'];

					$query_direccion = "UPDATE 
										direccion_estacionamiento 
										SET urbanizacion = '".$urbanizacion."', 
											calle = '".$calle."', 
											edificio = '".$edificio."', 
											numero = '".$numero."', 
											iddireccion ='".$idD."' 
										WHERE id = '".$iddireccion."'; ";
					if(!$mysqli->query($query_direccion)){
						echo $query_direccion;
					}
		//BITACORA		
		$valoresoldD = getRegistroSQL("	SELECT a.urbanizacion AS 'Urbanización', a.calle AS 'Calle', a.edificio AS 'Edificio', 
										a.numero AS 'Número de casa', b.provincia AS 'Provincia', b.distrito AS 'Distrito',
										b.corregimiento AS 'Corregimiento'
										FROM direccion a
										INNER JOIN direcciones b ON a.iddireccion = b.id
										WHERE a.id = '".$iddireccion."' ");
		$valoresoldP = getRegistroSQL("	SELECT p.nombre AS 'Nombre', p.apellidopaterno AS 'Apellido paterno', 
										p.apellidomaterno AS 'Apellido materno', p.cedula AS 'Cedula', p.celular AS 'Celular', 
										p.telefono AS 'Teléfono', p.correo AS 'Correo', p.fecha_nac AS 'Fecha nac.', 
										p.tipo_documento AS 'Tipo de documento', p.nacionalidad AS 'Nacionalidad', p.sexo AS 'Sexo', 
										p.estado_civil AS 'Estado civil', p.condicion_actividad AS 'Condición de actividad', 
										p.categoria_actividad AS 'Categoria de la actividad', p.cobertura_medica AS 'Cobertura médica', 
										p.beneficios AS 'Beneficios', p.beneficios_des AS 'Beneficios detalle', 
										p.expediente AS 'Expediente', p.idacompanante AS 'ID acompañante', p.status AS 'Status',
										p.fecha_vcto_cm AS 'Fecha Vcto. Carnet Migratorio'
										FROM pacientes p 
										WHERE p.id = '".$idpaciente."' ");		
		//PACIENTE
		$query_paciente = " UPDATE
								 beneficiariosestacionamiento 
							SET
								 nombre = '".$nombre."', 
								 apellidopaterno = '".$apellidopaterno."',
								apellidomaterno = '".$apellidomaterno."', 
								cedula = '".$cedula."', 
								celular = '".$celular."',
								telefono = '".$telefono."',
								correo = '".$correo."',
								fecha_nac = '".$fecha_nac."',
								tipo_documento = '".$tipodocumento."',
								sexo = '".$sexo."',
								idacompanante = '".$idacompanante."'
							";
		
		$query_paciente .= " WHERE id = '".$idpaciente."' ";
		//echo $query_paciente;	
		if($mysqli->query($query_paciente)){
			$valoresnewD = array(
				'Urbanización' 		=> $urbanizacion,
				'Calle' 			=> $calle,
				'Edificio' 			=> $edificio,
				'Número de casa' 	=> $numero,
				'Provincia' 		=> $provincia,
				'Distrito' 			=> $distrito,
				'Corregimiento' 	=> $corregimiento
			);
			$valoresnewP = array(
				'Nombre' 			=> $nombre,
				'Apellido paterno' 	=> $apellidopaterno,
				'Apellido materno' 	=> $apellidomaterno,
				'Cedula' 			=> $cedula,
				'Celular' 			=> $celular,
				'Teléfono' 			=> $telefono,
				'Correo' 			=> $correo, 
				'Fecha nac.' 		=> $fecha_nac,
				'Tipo de documento' => $tipodocumento,
				'Sexo' 				=> $sexo,
				'ID acompañante' 	=> $idacompanante
			);
			//actualizarRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$valoresoldD,$valoresnewD,$query_direccion);
			//actualizarRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$valoresoldP,$valoresnewP,$query_paciente);
			
			$response = array( "success" => true, "idbeneficiario" => $idpaciente, "msj" => 'Beneficiario actualizado satisfactoriamente' );
		}else{
			$response = array( "success" => false, "idbeneficiario" => '', "msj" => 'Error al actualizar el beneficiario, por favor intente más tarde' );			
		}
		echo json_encode($response);
	}

    function guardarBeneficiario(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		
        //DATOS PERSONALES
	    $idbeneficiario = (!empty($data['idbeneficiario']) ? $data['idbeneficiario'] : '');
	    $tipobeneficiario = (!empty($data['tipobeneficiario']) ? $data['tipobeneficiario'] : '');	
	    $tipodocumento = (!empty($data['tipodocumento']) ? $data['tipodocumento'] : '');
		$cedula = (!empty($data['cedula']) ? $data['cedula'] : '');
		$nombre = (!empty($data['nombre']) ? $data['nombre'] : '');
		$apellidopaterno = (!empty($data['apellidopaterno']) ? $data['apellidopaterno'] : '');
		$apellidomaterno = (!empty($data['apellidomaterno']) ? $data['apellidomaterno'] : '');
		$correo = (!empty($data['correo']) ? $data['correo'] : '');
		$celular = (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
		$telefono = (!empty($data['telefonootro']) ? $data['telefonootro'] : '');
		$fecha_nac = (!empty($data['fecha_nac']) ? $data['fecha_nac'] : '');		 
		$sexo = (!empty($data['sexo']) ? $data['sexo'] : ''); 
				
		//DIRECCIÓN
		$iddireccion = getValor('direccion','pacientes',$idpaciente);
		$urbanizacion = (!empty($data['urbanizacion']) ? $data['urbanizacion'] : '');
		$calle = (!empty($data['calle']) ? $data['calle'] : '');
		$edificio = (!empty($data['edificio']) ? $data['edificio'] : '');
		$numero = (!empty($data['numerocasa']) ? $data['numerocasa'] : '');
		$provincia = (!empty($data['idprovincias']) ? $data['idprovincias'] : '');
		$distrito = (!empty($data['iddistritos']) ? $data['iddistritos'] : '');
		$corregimiento = (!empty($data['idcorregimientos']) ? $data['idcorregimientos'] : ''); 


		//ACOMPAÑANTE
		$idacompanante		= (!empty($data['idacompanante']) ? $data['idacompanante'] : 0); 
		
		//BITACORA 
		$camposD = array(
			'Urbanización' 		=> $urbanizacion,
			'Calle' 			=> $calle,
			'Edificio' 			=> $edificio,
			'Número de casa' 	=> $numero,
			'Provincia' 		=> $provincia,
			'Distrito' 			=> $distrito,
			'Corregimiento' 	=> $corregimiento
		);
		$camposP = array(
			'Nombre' 			=> $nombre,
			'Apellido paterno' 	=> $apellidopaterno,
			'Apellido materno' 	=> $apellidomaterno,
			'Cedula' 			=> $cedula,
			'Celular' 			=> $celular,
			'Teléfono' 			=> $telefono,
			'Correo' 			=> $correo, 
			'Fecha nac.' 		=> $fecha_nac,
			'Tipo de documento' => $tipodocumento, 
			'Sexo' 				=> $sexo, 
			'ID acompañante' 	=> $idacompanante 
		);
		
		//VALIDAR CEDULA
		$bced = "SELECT cedula FROM beneficiariosestacionamiento where cedula = '".$cedula."' ";
		$resultced = $mysqli->query($bced);
		$totced = $resultced->num_rows;
		if($totced == 0){ 
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
				$query_paciente = "	INSERT INTO beneficiariosestacionamiento (nombre, apellidopaterno, apellidomaterno, cedula, celular, telefono, correo, 
									fecha_nac, tipo_documento, nacionalidad, sexo, estado_civil, condicion_actividad, categoria_actividad,
									cobertura_medica, beneficios, beneficios_des, idacompanante, direccion, expediente,status,latitud,longitud
									";
				 if($idbeneficiario != '' && $tipobeneficiario == 'certificaciones'){
					$query_paciente .= ",idpacientescertificaciones ";			
				}
				/* if($fecha_vcto_cm != ''){
					$query_paciente .= ",fecha_vcto_cm ";			
				} */
				
				$query_paciente .= ") VALUES(
									'".$nombre."', '".$apellidopaterno."', '".$apellidomaterno."', '".$cedula."', '".$celular."',
									'".$telefono."', '".$correo."',	'".$fecha_nac."', '".$tipodocumento."', 0,
									'".$sexo."', 0, 0, 0, 0, 0, 0, '".$idacompanante."', 
									'".$iddireccion."', 0, 0, 0, 0";
				
				if($idbeneficiario != '' && $tipobeneficiario == 'certificaciones'){
					$query_paciente .= ", '".$idbeneficiario."' ";					
				}
				/* if($fecha_vcto_cm != ''){
					$query_paciente .= ", '".$fecha_vcto_cm."' ";					
				}	 */			
				
				$query_paciente .= " ) ";
				//echo $query_paciente;
				//debugL('GUARDAR PACIENTE ES: '.$query_paciente);
				if($mysqli->query($query_paciente)){
					$idpaciente = $mysqli->insert_id;   
					 				
					nuevoRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$iddireccion,$camposD,$query_direccion);
					nuevoRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$camposP,$query_paciente);
					
					$response = array( "success" => true, "idpaciente" => $idpaciente, "msj" => 'Beneficiario almacenado satisfactoriamente' );			
				}else{
					$response = array( "success" => false, "idpaciente" => '', "msj" => 'Error al guardar el beneficiario, por favor intente más tarde' );
				} 
		}else{
			$response = array( "success" => false, "idbeneficiario" => '', "msj" => 'El Nº de documento ya esta registrado' );
		}
		
		echo json_encode($response);
	}

	function editarBeneficiario(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');		
		//DATOS PERSONALES
		$idpaciente		= (!empty($data['idbeneficiario']) ? $data['idbeneficiario'] : ''); 
	    $tipobeneficiario = (!empty($data['tipobeneficiario']) ? $data['tipobeneficiario'] : '');	
		$tipodocumento  = (!empty($data['tipodocumento']) ? $data['tipodocumento'] : '');
		$cedula  		= (!empty($data['cedula']) ? $data['cedula'] : '');
		$nombre  		= (!empty($data['nombre']) ? $data['nombre'] : '');
		$apellidopaterno= (!empty($data['apellidopaterno']) ? $data['apellidopaterno'] : '');
		$apellidomaterno= (!empty($data['apellidomaterno']) ? $data['apellidomaterno'] : '');		
		$correo  		= (!empty($data['correo']) ? $data['correo'] : '');
		$celular		= (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
		$telefono		= (!empty($data['telefonootro']) ? $data['telefonootro'] : '');
		$fecha_nac		= (!empty($data['fecha_nac']) ? $data['fecha_nac'] : '');		
		$nacionalidad  	= (!empty($data['nacionalidad']) ? $data['nacionalidad'] : '');
		$sexo			= (!empty($data['sexo']) ? $data['sexo'] : '');
		$estado_civil	= (!empty($data['estado_civil']) ? $data['estado_civil'] : '');
		$status			= (!empty($data['status']) ? $data['status'] : 0); 
		//DIRECCIÓN
		$iddireccion 	= getValor('direccion','pacientes',$idpaciente);
		$urbanizacion  	= (!empty($data['urbanizacion']) ? $data['urbanizacion'] : '');
		$calle  		= (!empty($data['calle']) ? $data['calle'] : '');
		$edificio  		= (!empty($data['edificio']) ? $data['edificio'] : '');
		$numero  		= (!empty($data['numerocasa']) ? $data['numerocasa'] : '');
		$provincia  	= (!empty($data['idprovincias']) ? $data['idprovincias'] : '');
		$distrito  		= (!empty($data['iddistritos']) ? $data['iddistritos'] : '');
		$corregimiento	= (!empty($data['idcorregimientos']) ? $data['idcorregimientos'] : '');
		
		//ACOMPAÑANTE
		$idacompanante		= (!empty($data['idacompanante']) ? $data['idacompanante'] : 0);
		
		//DIRECCIÓN
		$queryD = "	SELECT id FROM direcciones WHERE provincia = '".$provincia."' AND distrito = '".$distrito."' 
					AND corregimiento = '".$corregimiento."' ";
		$resD 	= getRegistroSQL($queryD);
		$idD 	= $resD['id'];
	
		$query_direccion = "UPDATE direccion_estacionamiento SET urbanizacion = '".$urbanizacion."', calle = '".$calle."',
							edificio = '".$edificio."', numero = '".$numero."', iddireccion ='".$idD."'
							WHERE id = '".$iddireccion."'; ";
		if(!$mysqli->query($query_direccion	)){
			echo $query_direccion;
		}
		//BITACORA		
		$valoresoldD = getRegistroSQL("	SELECT a.urbanizacion AS 'Urbanización', a.calle AS 'Calle', a.edificio AS 'Edificio', 
										a.numero AS 'Número de casa', b.provincia AS 'Provincia', b.distrito AS 'Distrito',
										b.corregimiento AS 'Corregimiento'
										FROM direccion a
										INNER JOIN direcciones b ON a.iddireccion = b.id
										WHERE a.id = '".$iddireccion."' ");
		$valoresoldP = getRegistroSQL("	SELECT p.nombre AS 'Nombre', p.apellidopaterno AS 'Apellido paterno', 
										p.apellidomaterno AS 'Apellido materno', p.cedula AS 'Cedula', p.celular AS 'Celular', 
										p.telefono AS 'Teléfono', p.correo AS 'Correo', p.fecha_nac AS 'Fecha nac.', 
										p.tipo_documento AS 'Tipo de documento', p.nacionalidad AS 'Nacionalidad', p.sexo AS 'Sexo', 
										p.estado_civil AS 'Estado civil', p.condicion_actividad AS 'Condición de actividad', 
										p.categoria_actividad AS 'Categoria de la actividad', p.cobertura_medica AS 'Cobertura médica', 
										p.beneficios AS 'Beneficios', p.beneficios_des AS 'Beneficios detalle', 
										p.expediente AS 'Expediente', p.idacompanante AS 'ID acompañante', p.status AS 'Status',
										p.fecha_vcto_cm AS 'Fecha Vcto. Carnet Migratorio'
										FROM pacientes p 
										WHERE p.id = '".$idpaciente."' ");		
		//PACIENTE
		$query_paciente = " UPDATE beneficiariosestacionamiento SET nombre = '".$nombre."', apellidopaterno = '".$apellidopaterno."',
							apellidomaterno = '".$apellidomaterno."', cedula = '".$cedula."', celular = '".$celular."',
							telefono = '".$telefono."',	correo = '".$correo."',	fecha_nac = '".$fecha_nac."',
							tipo_documento = '".$tipodocumento."', sexo = '".$sexo."',
							idacompanante = '".$idacompanante."'
							";
		/* if($fecha_vcto_cm != ''){
			$query_paciente .= ", fecha_vcto_cm = '".$fecha_vcto_cm."'";
		} */
		
		$query_paciente .= " WHERE id = '".$idpaciente."' ";	
		//debugL("paciente: ".$idpaciente.", query es:".$query_paciente,"query_paciente");
		//echo $query_paciente;
		if($mysqli->query($query_paciente)){
			$valoresnewD = array(
				'Urbanización' 		=> $urbanizacion,
				'Calle' 			=> $calle,
				'Edificio' 			=> $edificio,
				'Número de casa' 	=> $numero,
				'Provincia' 		=> $provincia,
				'Distrito' 			=> $distrito,
				'Corregimiento' 	=> $corregimiento
			);
			$valoresnewP = array(
				'Nombre' 			=> $nombre,
				'Apellido paterno' 	=> $apellidopaterno,
				'Apellido materno' 	=> $apellidomaterno,
				'Cedula' 			=> $cedula,
				'Celular' 			=> $celular,
				'Teléfono' 			=> $telefono,
				'Correo' 			=> $correo, 
				'Fecha nac.' 		=> $fecha_nac,
				'Tipo de documento' => $tipodocumento,
				'Sexo' 				=> $sexo,
				'ID acompañante' 	=> $idacompanante
			);
			//actualizarRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$valoresoldD,$valoresnewD,$query_direccion);
			//actualizarRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$valoresoldP,$valoresnewP,$query_paciente);
			
			$response = array( "success" => true, "idpaciente" => $idpaciente, "msj" => 'Beneficiario actualizado satisfactoriamente' );
		}else{
			$response = array( "success" => false, "idpaciente" => '', "msj" => 'Error al actualizar el beneficiario, por favor intente más tarde' );			
		}
		echo json_encode($response);
	}

	function get_pacienteporsolicitud(){
		global $mysqli;
		$idsolicitud   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		
		$query = "	SELECT p.id
					FROM estacionamientos s
					INNER JOIN beneficiariosestacionamiento p on p.id = s.idbeneficiarios
					WHERE s.id = '".$idsolicitud."' ";
					//echo $query;
		$result = $mysqli->query($query);
		if($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' => $row['id'],
			);
		}
		//BITACORA
		//bitacora('Solicitudes', 'Obtener datos del usuario', $idsolicitud, $query);
		echo json_encode($resultado);
	}  

	function crearCodigoIdentificacion($tipodocumento, $cedula, $lugarsolicitud) {
		$primer_digito_cedula = substr($cedula, 0, 1);
		$codigo = $primer_digito_cedula;
		if ($tipodocumento == 2) {
		  $codigo .= 'E';
		} elseif ($tipodocumento == 3) {
		  $codigo .= 'P';
		} else {
		  $codigo .= $lugarsolicitud;
		}
		$codigo .= str_replace('-', '', substr($cedula, 1));
		return $codigo;
	  }

?>