<?php 
	include("conexion.php");
	sessionrestore();
    $oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}

    switch($oper)
    {
        case "cargar":
            cargar();
            break; 
        case "guardar_solicitud":
            guardar_solicitud();
            break; 
        case "eliminarSolicitud":
            eliminarSolicitud();
            break;
        case "getSolicitud":
            getSolicitud();
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
        default:
            echo "{failure:true}";
            break;
    }

	function cargar(){
		global $mysqli;
		/*--CONFIG-DATATABLE--------------------------------------------------*/
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC*/
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/

		$query = " 	SELECT a.id, CONCAT(b.nombre,' ',b.apellidopaterno,' ',b.apellidomaterno) AS nombre, b.cedula, 
                    a.fecha_solicitud, c.nombre AS regional, d.descripcion AS estado
		            FROM estacionamientos a
                    INNER JOIN beneficiariosestacionamiento b ON b.id = a.idbeneficiarios
                    INNER JOIN regionales c ON c.id = a.idregionales
                    INNER JOIN estados d ON d.id = a.idestados
		            WHERE 1 ";
		$query  .= " GROUP BY id ";
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu droptable dropdown-menu-right">
									<a class="dropdown-item text-info boton-modificar" data-id="'.$row['id'].'" href="auditor.php?id='.$row['id'].'"><i class="fas fa-pen mr-2 editar"></i>Editar</a>
									<a class="dropdown-item text-danger font-w600 boton-eliminar-fisico" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			
			$resultado[] = array
			(
				'acciones' 		=> $acciones,
				'id' 			=> $row['id'],
				'nombre' 		=>	$row['nombre'],
                'cedula' 		=>	$row['cedula'],
                'fecha_solicitud'=>	$row['fecha_solicitud'],
                'regional' 		=>	$row['regional'],
                'estado' 		=>	$row['estado'],
			);
		}
		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsTotal),
			"data" => $resultado
		  );
		echo json_encode($response);
	}

	function get(){
		global $mysqli;
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$resultado 	= array();
		
		$query 	= " SELECT * FROM auditores a WHERE a.id = '".$id."' ";
		$result = $mysqli->query($query);
		if(!$result){
			die($mysqli->error);  
		}
		if($row = mysqli_fetch_array($result)){
			$resultado[] = array(
				'id' 				=> $row['id'],
				'nombre' 			=>	$row['nombre']
			);
		}
		$jsonString = json_encode($resultado[0]);
		echo $jsonString;
	}
	
	function guardar_solicitud(){ //Guardar o editar solicitud
		global $mysqli;		
		
		//SOLICITUD
		$idsolicitud = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data = (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		$regional = (!empty($data['lugarsolicitud']) ? $data['lugarsolicitud'] : '');
		$tipodiscapacidad = (!empty($data['tipodiscapacidad']) ? $data['tipodiscapacidad'] : 0);
		$tiposolicitud = (!empty($data['tiposolicitud']) ? $data['tiposolicitud'] : 0);
		$estado = (!empty($data['estadosolicitud']) ? $data['estadosolicitud'] : '1');
		$fecha_solicitud = (!empty($data['fecha_sol']) ? $data['fecha_sol'] : ''); 
		$cedula = (!empty($data['cedula']) ? $data['cedula'] : '');
		$caracteristicavehiculo = (!empty($data['caracteristicavehiculo']) ? $data['caracteristicavehiculo'] : '');
		$adaptado = (!empty($data['adaptado']) ? $data['adaptado'] : '');
		$placa = (!empty($data['placa']) ? $data['placa'] : '');
		$marca = (!empty($data['marca']) ? $data['marca'] : '');
		$modelo = (!empty($data['modelo']) ? $data['modelo'] : '');
		$nromotor = (!empty($data['nromotor']) ? $data['nromotor'] : '');
		//ACOMPAÑANTE
		/* $dataSolAc			= (!empty($_REQUEST['datosSolAc']) ? $_REQUEST['datosSolAc'] : '');
		$idacompananteSA	= (!empty($dataSolAc['idacompanante']) ? $dataSolAc['idacompanante'] : 0);
		$tipoacompananteSA	= (!empty($dataSolAc['tipoacompanante']) ? $dataSolAc['tipoacompanante'] : 0);
		$requiereacSA		= (!empty($dataSolAc['requiere_acompanante']) ? $dataSolAc['requiere_acompanante'] : ''); */
		
		//BENEFICIARIO
		$idbeneficiario		= (!empty($_REQUEST['idbeneficiario']) ? $_REQUEST['idbeneficiario'] : '');
		$correo 			= getValor('correo','beneficiariosestacionamiento',$idbeneficiario); 
		//ACOMPAÑANTE
		/* $dataAc				= (!empty($_REQUEST['datosAc']) ? $_REQUEST['datosAc'] : '');
		$idacompanante		= (!empty($dataAc['idacompanante']) ? $dataAc['idacompanante'] : 0);
		$tipoacompanante	= (!empty($dataAc['tipoacompanante']) ? $dataAc['tipoacompanante'] : 0); */
		   
		if($idsolicitud == '' || $idsolicitud == 'false'){
			
			$campos = array(
				'Beneficiario' 		=> getValor('cedula','beneficiariosestacionamiento',$idbeneficiario,''),
				'Fecha solicitud' 	=> $fecha_solicitud,
				'Regional' 			=> getValor('nombre','regionales',$regional,''), 
				//'Acompañante' 		=> getValor('cedula','acompanantes',$idacompananteSA,''),
				//'Estatus' 			=> getValor('descripcion','estados',$estado,''),
				//'Tipo discapacidad' => getValor('nombre','discapacidades',$tipodiscapacidad,''),
				'Tipo de solicitud' => $ntiposolicitud 
			);
			$query 	= "	INSERT INTO	estacionamientos (idbeneficiarios, fecha_solicitud, idregionales, idacompanante, idestados, iddiscapacidad, tipo,
						caracteristica_vehiculo,adaptado,placa,marca,modelo,nro_motor,cedula,fechacambioestado,creation_time)
						VALUES ('".$idbeneficiario."','".$fecha_solicitud."','".$regional."',0,1,'".$tipodiscapacidad."','".$tiposolicitud."',
						'".$caracteristicavehiculo."','".$adaptado."','".$placa."','".$marca."','".$modelo."','".$nromotor."','',NOW(),NOW()) ";
			//echo $query;
			if($result = $mysqli->query($query)){
				$idsolicitud = $mysqli->insert_id;
				$myPath = '../solicitudes/';
					if (!file_exists($myPath))
						mkdir($myPath, 0777);
					$myPath = '../solicitudes/'.$idsolicitud.'/';
					$target_path2 = utf8_decode($myPath);
					if (!file_exists($target_path2))
						mkdir($target_path2, 0777);
				$tipo = 'registro solicitud';
				
				//Guardar en bitácora
				nuevoRegistro('Solicitudes','Solicitudes',$idsolicitud,$campos,$query);
				
				//enviar_correo($correo,$tipo,$idsolicitud);
				
				//Crear registro en solicitudes_estados
				/* $queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
							VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), '".$estado."', '".$estado."') ";
				$mysqli->query($queryE); */
				
				$response = array( "success" => true, "idsolicitud" => $idsolicitud );
			} else {
				$response = array( "success" => false, "idsolicitud" => '' );			
			}
		}else{
			
			$camposold = getRegistroSQL("	SELECT b.cedula AS 'Paciente',a.fecha_solicitud AS 'Fecha solicitud',
												c.nombre AS 'Regional',d.cedula AS 'Acompañante',e.descripcion AS 'Estatus', 
												f.nombre AS 'Tipo discapacidad', 
												CASE 
												WHEN a.tipo = 1 THEN 'Nueva'
												WHEN a.tipo = 2 THEN 'Reconsideración'
												WHEN a.tipo = 3 THEN 'Reevaluación'
												WHEN a.tipo = 0 THEN 'Sin especificar'
												END AS 'Tipo de solicitud',
												a.condicionsalud AS 'Condición de salud',
												a.observacionesestados AS 'Observaciones' 
											FROM solicitudes a 
											INNER JOIN pacientes b ON b.id = a.idpaciente 
											INNER JOIN regionales c ON c.id = a.regional 
											LEFT JOIN acompanantes d ON d.id = a.idacompanante 
											INNER JOIN estados e ON e.id = a.estatus 
											INNER JOIN discapacidades f ON f.id = a.iddiscapacidad 
											WHERE a.id = ".$idsolicitud." ");
						
			$estadoold = getValor('estatus','solicitudes',$idsolicitud,'');
			$requiereacSA == 'NO' ? $idacompananteSA = 0 : $idacompananteSA = $idacompananteSA;
			$requiereacSA == 'NO' ? $tipoacompananteSA = 0 : $tipoacompananteSA =  $tipoacompananteSA;
			
			//Verificar si el beneficiario ha sido modificado
			//Buscar paciente actual de la solicitud antes del UPDATE
			$sqlIdpacienteOld = "SELECT idpaciente, reconsideracion, apelacion FROM solicitudes WHERE id = ".$idsolicitud."";
			$rtaIdpacOld = $mysqli->query($sqlIdpacienteOld);
			if($rowIdpacOld = $rtaIdpacOld->fetch_assoc()){
				$idpacienteOld = $rowIdpacOld['idpaciente'];	
				$reconsideracionOld = $rowIdpacOld['reconsideracion'];
				$apelacionOld = $rowIdpacOld['apelacion'];
			}
				
			$query 	= "	UPDATE solicitudes SET idpaciente = '".$idbeneficiario."', fecha_solicitud = '".$fecha_solicitud."', 
						regional = '".$regional."', idacompanante = '".$idacompananteSA."', estatus = '".$estado."', 
						tipoacompanante = '".$tipoacompananteSA."', iddiscapacidad = '".$tipodiscapacidad."', 
						condicionsalud = '".$condicionsalud."', observacionesestados = '".$observaciones."', cedula = '".$cedula."',
						tipo = ".$tiposolicitud." ";
			
			if($estadoold != $estado){
				$query 	.= ", fechacambioestado = NOW()";

				//Reconsideración
				if($estado == $estadoReconsideracion && $reconsideracionOld == 0){
					$query .= ", reconsideracion = 1";	
				}

				//Apelación
				if($estado == $estadoApelacion && $apelacionOld == 0){
					$query .= ", apelacion = 1";	
				}
			} 
			
			
			$query 	.= " WHERE id = '".$idsolicitud."' ";
			//debugL($query);
			if($result = $mysqli->query($query)){
				
				if($estadoold != $estado){
					//Crear registro en solicitudes_estados
					$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
							VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), '".$estadoold."', '".$estado."') ";
					$mysqli->query($queryE);
				} 
				
				$camposnew = array(
					'Paciente' 			=> getValor('cedula','pacientes',$idbeneficiario,''),
					'Fecha solicitud' 	=> $fecha_solicitud,
					'Regional' 			=> getValor('nombre','regionales',$regional,''), 
					'Acompañante' 		=> getValor('cedula','acompanantes',$idacompananteSA,''),
					'Estatus' 			=> getValor('descripcion','estados',$estado,''),
					'Tipo discapacidad' => getValor('nombre','discapacidades',$tipodiscapacidad,''),
					'Tipo de solicitud' => $ntiposolicitud,
					'Condición de salud'=> $condicionsalud,
					'Observaciones'		=> $observaciones  
				);
				//Guardar en bitácora
				actualizarRegistro('Solicitudes','Solicitudes',$idsolicitud,$camposold,$camposnew,$query);
				
				//Si el paciente ha sido modificado en la solicitud, 
				//actualizar el paciente nuevo en la evaluación de la solicitud
				if($idbeneficiario != $idpacienteOld){
					
					$sqlEvalSol = " SELECT id FROM evaluacion
									WHERE idsolicitud = ".$idsolicitud." 
									AND idpaciente = ".$idpacienteOld."";
					$rtaEvalSol = $mysqli->query($sqlEvalSol);
					if($rowEvalSol = $rtaEvalSol->fetch_assoc()){
						
						$idevalSol = $rowEvalSol['id'];
						
						$updEval = "UPDATE evaluacion SET idpaciente = ".$idbeneficiario." 
						WHERE id = ".$idevalSol."";
						$mysqli->query($updEval);
						
						$camposOldEval = array(
							'Paciente' 			=> getValor('cedula','pacientes',$idpacienteOld,'')
						);
						$camposNewEval = array(
							'Paciente' 			=> getValor('cedula','pacientes',$idbeneficiario,'')
						);
				
						//Guardar en bitácora cambio del paciente en la evaluación
						actualizarRegistro('Evaluación','Evaluación',$idevalSol,$camposOldEval,$camposNewEval,$updEval);	
					}						
				}
				
				$response = array( "success" => true, "idsolicitud" => $idsolicitud );
			} else {
				$response = array( "success" => false, "idsolicitud" => '' );			
			}
		}
		
		echo json_encode($response);
	}
    

    function editar(){
        global $mysqli;
    
        $id                         = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
        $lugarsolicitud             = (!empty($_REQUEST['lugarsolicitud']) ? $_REQUEST['lugarsolicitud'] : '');
        $tipodiscapacidad           = (!empty($_REQUEST['tipodiscapacidad']) ? $_REQUEST['tipodiscapacidad'] : '');
        $tiposolicitud              = (!empty($_REQUEST['tiposolicitud']) ? $_REQUEST['tiposolicitud'] : '');
        $estadosolicitud            = (!empty($_REQUEST['estadosolicitud']) ? $_REQUEST['estadosolicitud'] : '');
        $fecha_sol                  = (!empty($_REQUEST['fecha_sol']) ? $_REQUEST['fecha_sol'] : '');
        $idbeneficiario             = (!empty($_REQUEST['idbeneficiario']) ? $_REQUEST['idbeneficiario'] : '');
        $ayudastecnicas             = (!empty($_REQUEST['ayudastecnicas']) ? $_REQUEST['ayudastecnicas'] : '');
        $caracteristicavehiculo     = (!empty($_REQUEST['caracteristicavehiculo']) ? $_REQUEST['caracteristicavehiculo'] : '');
        $adaptado                   = (!empty($_REQUEST['adaptado']) ? $_REQUEST['adaptado'] : '');
        $placa                      = (!empty($_REQUEST['placa']) ? $_REQUEST['placa'] : '');
        $marca                      = (!empty($_REQUEST['marca']) ? $_REQUEST['marca'] : '');
        $modelo                     = (!empty($_REQUEST['modelo']) ? $_REQUEST['modelo'] : '');
        $nromotor                   = (!empty($_REQUEST['nromotor']) ? $_REQUEST['nromotor'] : '');
        $tiempovigencia_duracion    = (!empty($_REQUEST['tiempovigencia_duracion']) ? $_REQUEST['tiempovigencia_duracion'] : '');
        $tiempovigencia_tipduracion = (!empty($_REQUEST['tiempovigencia_tipduracion']) ? $_REQUEST['tiempovigencia_tipduracion'] : '');
        $fecharetiro                = (!empty($_REQUEST['fecharetiro']) ? $_REQUEST['fecharetiro'] : '');
        $horaretiro                 = (!empty($_REQUEST['horaretiro']) ? $_REQUEST['horaretiro'] : '');
        $fechaexpedicion            = (!empty($_REQUEST['fechaexpedicion']) ? $_REQUEST['fechaexpedicion'] : '');
        $expiracion                 = (!empty($_REQUEST['expiracion']) ? $_REQUEST['expiracion'] : '');
    
        $valoresold = getRegistroSQL("   SELECT 
                                    lugarsolicitud, 
                                    tipodiscapacidad, 
                                    tiposolicitud, 
                                    estadosolicitud, 
                                    fecha_sol, 
                                    idbeneficiario, 
                                    ayudastecnicas, 
                                    caracteristicavehiculo, 
                                    adaptado, 
                                    placa, 
                                    marca, 
                                    modelo, 
                                    nromotor, 
                                    tiempovigencia_duracion, 
                                    tiempovigencia_tipduracion, 
                                    fecharetiro, 
                                    horaretiro, 
                                    fechaexpedicion, 
                                    expiracion 
                                FROM solicitudesestacionamiento  
                                WHERE id = '".$id."' ");
     
     $query = "  UPDATE solicitudesestacionamiento SET 
                    lugarsolicitud = '".$lugarsolicitud."',
                    tipodiscapacidad = '".$tipodiscapacidad."',
                    tiposolicitud = '".$tiposolicitud."',
                    estadosolicitud = '".$estadosolicitud."',
                    fecha_sol = '".$fecha_sol."',
                    idbeneficiario = '".$idbeneficiario."',
                    caracteristica = '".$caracteristica."',
                    adaptado = '".$adaptado."',
                    placa = '".$placa."',
                    marca = '".$marca."',
                    modelo = '".$modelo."',
                    nromotor = '".$nromotor."',
                    ayudastecnicas = '".$ayudastecnicas."',
                    caracteristicavehiculo = '".$caracteristicavehiculo."',
                    tiempovigencia_duracion = '".$tiempovigencia_duracion."',
                    tiempovigencia_tipduracion = '".$tiempovigencia_tipduracion."',
                    fecharetiro = '".$fecharetiro."',
                    horaretiro = '".$horaretiro."',
                    fechaexpedicion = '".$fechaexpedicion."',
                    expiracion = '".$expiracion."'
                WHERE id = '".$id."'";
    } 

	function eliminar(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query 	= "DELETE FROM solicitudesestacionamiento WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			//BITACORA
			eliminarRegistro('Solicitud de estacionamiento','Solicitud de estacionamiento',$nombre,$id,$query);
		    echo 1;		    
		}else{
			echo 0;
		}	
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
			actualizarRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$valoresoldD,$valoresnewD,$query_direccion);
			actualizarRegistro('Beneficiarios estacionamiento','Beneficiario estacionamiento',$idpaciente,$valoresoldP,$valoresnewP,$query_paciente);
			
			$response = array( "success" => true, "idpaciente" => $idpaciente, "msj" => 'Beneficiario actualizado satisfactoriamente' );
		}else{
			$response = array( "success" => false, "idpaciente" => '', "msj" => 'Error al actualizar el beneficiario, por favor intente más tarde' );			
		}
		echo json_encode($response);
	}
?>