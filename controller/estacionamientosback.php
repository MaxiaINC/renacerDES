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
		case "getdatossolicitud":
			getdatossolicitud();
			break;
		case "aprobarSolicitud":
			aprobarSolicitud();
			break;
        default:
            echo "{failure:true}";
            break;
    }

	function cargar(){
		global $mysqli; 

		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		$orderType 			 = (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   			 = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);

		$query = " 	SELECT a.id, CONCAT(b.nombre,' ',b.apellidopaterno,' ',b.apellidomaterno) AS nombre, b.cedula, 
                    a.fecha_solicitud, c.nombre AS regional, d.descripcion AS estado, idestados
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
			
			$idestados = $row['idestados'];
			$idestadosImprimirPermiso = 33;

			//Opciones
			$botones = '';
			$boton_imprimirpermiso = '';
			$boton_modificar = '';
			$boton_eliminar = '';

			if($idestados == $idestadosImprimirPermiso )
			$boton_imprimirpermiso = '<a class="dropdown-item text-info boton-imprimirpermiso" data-id="'.$row['id'].'" href="imprimirpermiso.php?id='.$row['id'].'"><i class="fas fa-id-card mr-2 editar"></i>Imprimir permiso</a>';		
			$boton_modificar = '<a class="dropdown-item text-info boton-modificar" data-id="'.$row['id'].'" href="estacionamiento.php?id='.$row['id'].'"><i class="fas fa-pen mr-2 editar"></i>Editar</a>';
			$boton_eliminar = '<a class="dropdown-item text-danger font-w600 boton-eliminar-fisico" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>';
			
			$botones .= "
						$boton_imprimirpermiso
						$boton_modificar
						$boton_eliminar
						";

			$acciones = '<td>
							<div class="dropdown ml-auto">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu droptable dropdown-menu-right">';
									
			$acciones .= $botones;						
			$acciones .= '		</div>
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
	
	function guardar_solicitud(){ //Guardar o editar solicitud
		global $mysqli;		
		
		$idestadosNuevo = 32;

		//SOLICITUD
		$idsolicitud = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data = (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		$regional = (!empty($data['lugarsolicitud']) ? $data['lugarsolicitud'] : '');
		$tipodiscapacidad = (!empty($data['tipodiscapacidad']) ? $data['tipodiscapacidad'] : 0);
		$tiposolicitud = (!empty($data['tiposolicitud']) ? $data['tiposolicitud'] : 0);
		//$estado = (!empty($data['estadosolicitud']) ? $data['estadosolicitud'] : 32);
		$fecha_solicitud = (!empty($data['fecha_sol']) ? $data['fecha_sol'] : ''); 
		$idacompanante = (!empty($data['idacompanante']) ? $data['idacompanante'] : 0); 
		$requiereacompanante = (!empty($data['requiereacompanante']) ? $data['requiereacompanante'] : 0); 
		$cedula = (!empty($data['cedula']) ? $data['cedula'] : '');
		$caracteristicavehiculo = (!empty($data['caracteristicavehiculo']) ? $data['caracteristicavehiculo'] : 0);
		$adaptado = (!empty($data['adaptado']) ? $data['adaptado'] : 0);
		$placa = (!empty($data['placa']) ? $data['placa'] : '');
		$marca = (!empty($data['marca']) ? $data['marca'] : '');
		$modelo = (!empty($data['modelo']) ? $data['modelo'] : '');
		$nromotor = (!empty($data['nromotor']) ? $data['nromotor'] : '');
		
		//BENEFICIARIO
		$idbeneficiario	= (!empty($_REQUEST['idbeneficiario']) ? $_REQUEST['idbeneficiario'] : '');
		$correo = getValor('correo','beneficiariosestacionamiento',$idbeneficiario); 
		   
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
			$query 	= "	INSERT INTO	estacionamientos (idbeneficiarios, fecha_solicitud, idregionales, requiereacompanante, idacompanante, iddiscapacidad, tipo,
						idestados,caracteristica_vehiculo,adaptado,placa,marca,modelo,nro_motor,cedula,creation_time)
						VALUES ('".$idbeneficiario."','".$fecha_solicitud."','".$regional."','".$requiereacompanante."',".$idacompanante.",'".$tipodiscapacidad."','".$tiposolicitud."',
						".$idestadosNuevo.",".$caracteristicavehiculo.",'".$adaptado."','".$placa."','".$marca."','".$modelo."','".$nromotor."','',NOW()) ";
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
				
				$response = array( "success" => true, "idsolicitud" => $idsolicitud, "msj" => 'Solicitud de permiso de estacionamiento creada satisfactoriamente');
			} else {
				$response = array( "success" => false, "idsolicitud" => '', "msj" => 'Error al crear la solicitud de permiso de estacionamiento' );			
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
				
			$query 	= "	UPDATE 
							estacionamientos
						SET 
							idbeneficiarios = '".$idbeneficiario."',
							fecha_solicitud = '".$fecha_solicitud."', 
							idregionales = '".$regional."', 
							idacompanante = '".$idacompanante."',
							iddiscapacidad = '".$tipodiscapacidad."', 
							tipo = ".$tiposolicitud.", 
							caracteristica_vehiculo = '".$caracteristicavehiculo."',
							adaptado = '".$adaptado."', 
							placa = '".$placa."', 
							marca = '".$marca."', 
							modelo = '".$modelo."',
							nro_motor = '".$nromotor."' ";
			
			if($estadoold != $estado){
				$query 	.= ", fechacambioestado = NOW()";
			} 
			
			$query 	.= " WHERE id = '".$idsolicitud."' ";
			//echo $query;
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
				//actualizarRegistro('Solicitudes','Solicitudes',$idsolicitud,$camposold,$camposnew,$query);
				$response = array( "success" => true, "idsolicitud" => $idsolicitud, "msj" => 'Solicitud de permiso de estacionamiento actualizada satisfactoriamente' );
			} else { 
				$response = array( "success" => false, "idsolicitud" => '', "msj" => 'Error al actualizar la solicitud de permiso de estacionamiento' );			
			}
		}
		
		echo json_encode($response);
	}  
 
	function getdatossolicitud(){
		global  $mysqli;
		$idsolicitud = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$_SESSION['idsolicitud']=$id;
		$query = "  SELECT s.idregionales,  s.fecha_solicitud, s.idestados,
					g.descripcion AS estado, s.idacompanante, s.idbeneficiarios, 
					d.nombre AS discapacidad, s.iddiscapacidad, s.tipo AS tiposolicitud,
					s.placa, s.marca, s.modelo, s.caracteristica_vehiculo, s.adaptado, s.nro_motor
					FROM estacionamientos s 
					LEFT JOIN discapacidades d ON d.id = s.iddiscapacidad
					INNER JOIN estados g ON s.idestados = g.id
					WHERE s.id = '".$idsolicitud."' ";
					//echo $query;
		$result = $mysqli->query($query);
		$data= '';
		while($row = $result->fetch_assoc()){			
			$queryP = " SELECT direccion FROM pacientes WHERE id = '".$row['idpaciente']."' ";
			$resultP = $mysqli->query($queryP); 
			if($rowP = $resultP->fetch_assoc()){
				$query_direccion = "SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero
									FROM direccionestacionamiento d LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
									WHERE d.id = '".$rowP['direccion']."'";
			
				$result_d = $mysqli->query($query_direccion);
				$row_d    = $result_d->fetch_assoc();
				
				$urbanizacion = 'Urbanización '.$row_d['urbanizacion'].',';
				$calle 		  = 'Calle '.$row_d['calle'].',';
				$casa 		  = 'Casa '.$row_d['numero'].',';
				$corregimiento= 'Corregimiento '.$row_d['corregimiento'].',';
				$distrito 	  = 'Distrito '.$row_d['distrito'].',';
				$provincia 	  = 'Provincia '.$row_d['provincia'].'.';
				
				$direccion = "";
				
				if($row_d['urbanizacion'] != ""){
					$direccion .= $urbanizacion; 
				}
				if($row_d['calle'] != ""){
					$direccion .= $calle; 
				}
				if($row_d['numero'] != ""){
					$direccion .= $casa; 
				}
				if($row_d['corregimiento'] != ""){
					$direccion .= $corregimiento; 
				}
				if($row_d['distrito'] != ""){
					$direccion .= $distrito; 
				}
				if($row_d['provincia'] != ""){
					$direccion .= $provincia; 
				} 
			}
			$data = array(
				'regional'	=> $row['idregionales'],
				'fecha_solicitud' => $row['fecha_solicitud'],
				'iddiscapacidad' => $row['iddiscapacidad'],
				'tiposolicitud' => $row['tiposolicitud'],
				'discapacidad' => $row['discapacidad'],
				'idacompanante' => $row['idacompanante'], 
				'idestatus' => $row['idestados'], 
				'caracteristicavehiculo' => $row['caracteristica_vehiculo'], 
				'adaptado' => $row['adaptado'], 
				'placa' => $row['placa'], 
				'marca' => $row['marca'], 
				'modelo' => $row['modelo'], 
				'nromotor' => $row['nro_motor'], 
				'direccion'	=> $direccion 
			);
		}
		echo json_encode($data);
		//echo $direccion;
	}

	function aprobarSolicitud() {
		global  $mysqli;

		$idestadosPermisoEmitido = 33;

		$idsolicitud = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$duracion = (!empty($_REQUEST['duracion']) ? $_REQUEST['duracion'] : '');
		$tipoduracion = (!empty($_REQUEST['tipoduracion']) ? $_REQUEST['tipoduracion'] : '');

		$sql = "UPDATE
					estacionamientos
				SET 
					duracion = ".$duracion.",
					tipoduracion = '".$tipoduracion."',
					idestados = ".$idestadosPermisoEmitido."
				WHERE 
				id = ".$idsolicitud;

		$rta = $mysqli->query($sql);

		if($rta == true){
			$response = array( "success" => true, "idsolicitud" => $idsolicitud, "msj" => 'Solicitud de permiso de estacionamiento fue aprobada satisfactoriamente' );
		}else{
			$response = array( "success" => false, "idsolicitud" => $idsolicitud, "msj" => 'Error al aprobar la solicitud del permiso de estacionamiento' );
		}
		echo json_encode($response);
	}

?>