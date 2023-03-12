<?php
    include("conexion.php");
	include("funciones.php");
	include("../phpqrcode/qrlib.php");
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "listado": //
			  listado();
			  break;
		case "getpaciente": //
			  getpaciente();
			  break;
		case "guardar_paciente": //
			  guardar_paciente();
			  break;
		case "editar_paciente": //
			  editar_paciente();
			  break;
		case "crear_acompanante":
			  crear_acompanante();
			  break;
		case "update_acompanante":
			  update_acompanante();
			  break;		
		case "eliminar":
			  eliminar();
			  break;		
		case "getacompanante":
			  getacompanante();
			  break;
		case "existe":
			  existe();
			  break;
		case "existe_ac":
			  existe_ac();
			  break;
		case "validarcedula";
		      validarcedula();
		      break;	  
		case "existeExpediente":
			  existeExpediente();
			  break;
		case "getDatosPaciente":
			  getDatosPaciente();
			  break;
		case "getDatosPacienteid":
			  getDatosPacienteid();
			  break;		
		case "enviar_correo":
			  enviar_correo();
			  break;		
		case "exportarExcel":
			  exportarExcel();
			  break;
		case "getHistorialNrodoc":
			  getHistorialNrodoc();
			  break;
		case "existeNombreFecha":
			  existeNombreFecha();
			  break;
		case "subirFoto":
			  subirFoto();
			  break;
		case "obtenerValidacionDeDerecho":
			  obtenerValidacionDeDerecho();
			  break;
		case "guardarImpresionBitacora":
			  guardarImpresionBitacora();
			  break;
		case "verificarImpresionBitacora":
			  verificarImpresionBitacora();
			  break;
		case "validarCodigoAutorizacion":
			  validarCodigoAutorizacion();
			  break;
		case "guardarImpresionBitacoraReimp":
			  guardarImpresionBitacoraReimp();
			  break;
		case "getCarnetImpresos":
			  getCarnetImpresos();
			  break;
		case "marcarComoImpreso":
			  marcarComoImpreso();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}	

	function listado(){
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
		$rowperpage   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$vacio = array();
		$columns   = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);
		/*--------------------------------------------------------------------*/
		$user_id = $_SESSION['user_id_sen'];
		$regional_usu 	= getValor('regional','usuarios',$user_id);
		
		$query = "	SELECT p.id as id, CONCAT(p.nombre, ' ',p.apellidopaterno, ' ', p.apellidomaterno) as nombre, 
					p.cedula as cedula, (CASE p.celular WHEN '' THEN p.telefono ELSE p.celular END) as telefono, 
					(CASE p.sexo WHEN 'M' THEN 'Masculino' ELSE 'Femenino' END) as sexo, 
					IFNULL(CONCAT(a.nombre,' ',a.apellido),'No Aplica') as acompanante, 
					GROUP_CONCAT(d.nombre)as discapacidades, p.fecha_nac as fecha_nac, p.expediente
					FROM pacientes p 
					LEFT JOIN discapacidades d ON FIND_IN_SET(d.id,p.discapacidades) 
					LEFT JOIN direccion di ON di.id = p.direccion 
					LEFT JOIN direcciones dir ON dir.id = di.iddireccion
					LEFT JOIN acompanantes a ON a.id = p.idacompanante
					WHERE 1 = 1  " ;
		if($regional_usu != 'Todos' && $regional_usu != '' && $regional_usu != null){
			$query .= " AND dir.provincia IN ('".$regional_usu."') ";
		}
		
		$hayFiltros = 0;
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {

                
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'expediente') {
					$column = 'p.expediente';
					$where2[] = " $column = '".$campo."' ";
				}
				
				if ($column == 'nombre') {
					$column = "CONCAT(p.nombre, ' ',p.apellidopaterno, ' ', p.apellidomaterno)";
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'cedula') {
					$column = 'p.cedula';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'telefono') {
					$column = 'p.telefono';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'sexo') {
					$column = 'p.sexo';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'fecha_nac') {
					$column = 'p.fecha_nac';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'discapacidades') {
					$column = 'd.nombre';
					$where2[] = " $column like '%".$campo."%' ";
				}				
				$hayFiltros++;
			}
		}		
		
//		echo $hayFiltros;
		$where= "";
		if ($hayFiltros > 0){
			$where .= " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		}
		$query  .= " $where ";			
		$query  .= " GROUP BY p.id ";
		$query  .= " ORDER BY CAST(p.expediente AS UNSIGNED) DESC ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		$query  .= " LIMIT ".$start.",".$rowperpage;
		if(!$mysqli->query($query)){
		  die($mysqli->error);
		}else{
			$result = $mysqli->query($query); 
		}
		$resultado = array();
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			$acciones = '<td>
							<div class="dropdown ml-auto">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu droptable dropdown-menu-right">
									<a class="dropdown-item text-info boton-modificar-beneficiario" data-id="'.$row['id'].'" href="beneficiario.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
									<!--<a class="dropdown-item text-info boton-evaluacion-beneficiario" data-id="'.$row['id'].'" href="#"><i class="fas fa-pen mr-2"></i>Empezar evaluación</a>-->
									<a class="dropdown-item text-info boton-consultar" data-id="'.$row['id'].'" href="#"><i class="fas fa-eye mr-2"></i>Consultar evaluaciones</a>
									<a class="dropdown-item text-danger font-w600 boton-eliminar" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones,
				'expediente'	=>	$row['expediente'],
				'nombre'		=>  $row['nombre'],
				'cedula'		=>  $row['cedula'],				
				'sexo' 			=>	$row['sexo'],
				'telefono' 		=>	$row['telefono'],
				'acompanante' 	=>	$row['acompanante'],
				'fecha_nac' 	=>	$row['fecha_nac'],
				'discapacidades'=>	$row['discapacidades']
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

	function getpaciente(){
		global $mysqli;
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idsolicitud = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		
		$query = "	SELECT a.nombre, CONCAT(a.apellidopaterno,' ',a.apellidomaterno) as apellido, a.apellidopaterno, a.apellidomaterno,
					a.cedula, a.tipo_documento, a.fecha_nac, a.sexo, a.telefono, a.celular, a.correo, a.nacionalidad, a.estado_civil, 
					a.condicion_actividad, a.categoria_actividad, a.cobertura_medica, a.beneficios, a.beneficios_des, b.idacompanante, 
					a.discapacidades, a.direccion, c.nombre AS discapacidad, a.expediente, a.status,
					(SELECT COUNT(id) FROM historial_nrodoc WHERE idpaciente = ".$id.") AS totalcertificados, latitud, longitud,
					fecha_vcto_cm
					FROM pacientes a
					LEFT JOIN solicitudes b ON a.id = b.idpaciente
					LEFT JOIN discapacidades c ON c.id = b.iddiscapacidad
					WHERE a.id = '".$id."' ";
		if($idsolicitud != ''){
		    $query .= " AND b.id = '".$idsolicitud."' ";
		}
		$result = $mysqli->query($query);
		if($row = $result->fetch_assoc()){	
			$ruta = '../images/beneficiarios/'.$id.'/';	
			$imagen = fotoPaciente($id,$ruta);
			$qr = qrPaciente($id);
			if($qr == false){
				generarQrEditarPaciente($id,$row['expediente']);
			}
			
			//Verificar estado Pendiente por imprimir
			$query_carnet = "SELECT id FROM solicitudes WHERE idpaciente = ".$id." AND estatus IN (24,26) LIMIT 1";
			$result_c = $mysqli->query($query_carnet);
			$total_c = $result_c->num_rows;
			$total_c >0 ? $carnet = 1 : $carnet = 0;
			
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
				'cobertura_medica' 	=> $row['cobertura_medica'],
				'beneficios' 		=> $row['beneficios'],
				'beneficios_des' 	=> $row['beneficios_des'],
				'idacompanante' 	=> $row['idacompanante'],
				'discapacidades' 	=> $row['discapacidades'],				
				'discapacidad' 		=> strtoupper($row['discapacidad']),
				'condicion_actividad' => $row['condicion_actividad'],
				'categoria_actividad' => $row['categoria_actividad'],
				'totalcertificados' => $row['totalcertificados'],
				'latitud' 			=> $row['latitud'],
				'longitud' 			=> $row['longitud'],
				'imagen' 			=> $imagen,
				'qr' 				=> true,
				'carnet' 			=> $carnet
			);
			$query_direccion = "SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero 
								FROM direccion d 
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
	
	function guardar_paciente(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		//DATOS PERSONALES
		$idpaciente		= (!empty($data['idbeneficiario']) ? $data['idbeneficiario'] : '');
		$tipodocumento  = (!empty($data['tipodocumento']) ? $data['tipodocumento'] : '');
		$cedula  		= (!empty($data['cedula']) ? $data['cedula'] : '');
		$nombre  		= (!empty($data['nombre']) ? $data['nombre'] : '');
		$apellidopaterno= (!empty($data['apellidopaterno']) ? $data['apellidopaterno'] : '');
		$apellidomaterno= (!empty($data['apellidomaterno']) ? $data['apellidomaterno'] : '');
		$expediente  	= (!empty(trim($data['expediente'])) ? $data['expediente'] : '');
		$correo  		= (!empty($data['correo']) ? $data['correo'] : '');
		$celular		= (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
		$telefono		= (!empty($data['telefonootro']) ? $data['telefonootro'] : '');
		$fecha_nac		= (!empty($data['fecha_nac']) ? $data['fecha_nac'] : '');		
		$nacionalidad  	= (!empty($data['nacionalidad']) ? $data['nacionalidad'] : '');
		$sexo			= (!empty($data['sexo']) ? $data['sexo'] : '');
		$estado_civil	= (!empty($data['estado_civil']) ? $data['estado_civil'] : '');
		$status			= (!empty($data['status']) ? $data['status'] : 0);
		$fecha_vcto_cm  = (!empty($data['fecha_vcto_cm']) ? $data['fecha_vcto_cm'] : 'null');
		
		//DIRECCIÓN
		$iddireccion 	= getValor('direccion','pacientes',$idpaciente);
		$urbanizacion  	= (!empty($data['urbanizacion']) ? $data['urbanizacion'] : '');
		$calle  		= (!empty($data['calle']) ? $data['calle'] : '');
		$edificio  		= (!empty($data['edificio']) ? $data['edificio'] : '');
		$numero  		= (!empty($data['numerocasa']) ? $data['numerocasa'] : '');
		$provincia  	= (!empty($data['idprovincias']) ? $data['idprovincias'] : '');
		$distrito  		= (!empty($data['iddistritos']) ? $data['iddistritos'] : '');
		$corregimiento	= (!empty($data['idcorregimientos']) ? $data['idcorregimientos'] : '');
		$latitud		= (!empty($data['latitud']) ? $data['latitud'] : '');
		$longitud		= (!empty($data['longitud']) ? $data['longitud'] : '');
		//OTROS
		$condicion_actividad= (!empty($data['condicion_actividad']) ? $data['condicion_actividad'] : '');
		$categoria_actividad= (!empty($data['categoria_actividad']) ? $data['categoria_actividad'] : 0);
		$cobertura_medica	= (!empty($data['cobertura_medica']) ? $data['cobertura_medica'] : '');
		//$cobertura_medica 	= implode(',',$cobertura_medica); //COMBO MULTIPLE
		$beneficios			= (!empty($data['beneficios']) ? $data['beneficios'] : '');
		$beneficios_des  	= (!empty($data['beneficios_descripcion']) ? $data['beneficios_descripcion'] : '');
		//ACOMPAÑANTE
		$idacompanante		= (!empty($data['idacompanante']) ? $data['idacompanante'] : 0);
		
		//EXPEDIENTE
		if($expediente == ''){
			$resEx 	= getRegistroSQL(" SELECT MAX((expediente * 1)+1) AS expedienteN FROM pacientes ");
			$expediente = trim($resEx['expedienteN']);
		}
		
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
			'Nacionalidad' 		=> $nacionalidad,
			'Sexo' 				=> $sexo,
			'Estado civil' 		=> $estado_civil,
			'Condición de actividad' 	=> $condicion_actividad,
			'Categoria de la actividad' => $categoria_actividad,
			'Cobertura médica' 	=> $cobertura_medica, 
			'Beneficios' 		=> $beneficios,
			'Beneficios detalle'=> $beneficios_des,
			'Expediente' 		=> $expediente,
			'ID acompañante' 	=> $idacompanante, //getValor('nombre','niveles',$niveles,'')
			'Status'	 		=> $status,
			'Fecha Vcto. Carnet Migratorio'	=> $fecha_vcto_cm
		);
		
		//VALIDAR CEDULA
		$bced = "SELECT cedula FROM pacientes where cedula = '".$cedula."' ";
		$resultced = $mysqli->query($bced);
		$totced = $resultced->num_rows;
		if($totced == 0){
			//VALIDAR EXPEDIENTE
			$bexp = "SELECT expediente FROM pacientes where expediente = '".$expediente."' ";
			$resultexp = $mysqli->query($bexp);
			$totexp = $resultexp->num_rows;
			if($totexp == 0){
				//DIRECCIÓN
				$queryD = "	SELECT id FROM direcciones WHERE provincia = '".$provincia."' AND distrito = '".$distrito."' 
							AND corregimiento = '".$corregimiento."' ";
				$resD 	= getRegistroSQL($queryD);
				$idD 	= $resD['id'];
				
				$query_direccion = " INSERT INTO direccion (id, urbanizacion, calle, edificio, numero, iddireccion) VALUES (
									 NULL, '".$urbanizacion."', '".$calle."', '".$edificio."', '".$numero."', '".$idD."' );";
				if($mysqli->query($query_direccion	)){
					$iddireccion = $mysqli->insert_id;
				}else{
					echo $query_direccion;
				}
				$query_paciente = "	INSERT INTO pacientes (nombre, apellidopaterno, apellidomaterno, cedula, celular, telefono, correo, 
									fecha_nac, tipo_documento, nacionalidad, sexo, estado_civil, condicion_actividad, categoria_actividad,
									cobertura_medica, beneficios, beneficios_des, idacompanante, direccion, expediente,status,latitud,longitud ";
				
				if($fecha_vcto_cm != ''){
					$query_paciente .= ",fecha_vcto_cm ";			
				}
				
				$query_paciente .= ") VALUES(
									'".$nombre."', '".$apellidopaterno."', '".$apellidomaterno."', '".$cedula."', '".$celular."',
									'".$telefono."', '".$correo."',	'".$fecha_nac."', '".$tipodocumento."', '".$nacionalidad."',
									'".$sexo."', '".$estado_civil."', '".$condicion_actividad."', '".$categoria_actividad."',
									'".$cobertura_medica."', '".$beneficios."', '".$beneficios_des."', '".$idacompanante."', 
									'".$iddireccion."', '".$expediente."', '".$status."', '".$latitud."', '".$longitud."' ";
									
				if($fecha_vcto_cm != ''){
					$query_paciente .= ", '".$fecha_vcto_cm."' ";					
				}				
				
				$query_paciente .= " ) ";
				
				//debugL('GUARDAR PACIENTE ES: '.$query_paciente);
				if($mysqli->query($query_paciente)){
					$idpaciente = $mysqli->insert_id;
										
					//Foto
					$num 	= $_SESSION['user_id_sen'];
					$from 	= '../images/beneficiarios/temporal/'.$num;
					
					if (is_dir($from)) {
						//Escaneamos el directorio
						$carpeta = @scandir($from);
						//Miramos si existen archivos
						if (count($carpeta) > 2){
							$to 	= '../images/beneficiarios/'.$idpaciente.'/';
							$target_pathInc = utf8_decode($to);
							if (!file_exists($target_pathInc)) {
								mkdir($target_pathInc, 0777);
							}
							$verificarruta = '../images/beneficiarios/temporal/'.$num.'/';
						// 	//Abro el directorio que voy a leer
							$target_path2 = utf8_decode($verificarruta);
						// 	echo $target_path2;
							if (file_exists($target_path2)){
								// echo "paso por aqui";
								$dir = opendir($from);
								while(($file = readdir($dir)) !== false){
									//Leo todos los archivos excepto . y ..
									if(strpos($file, '.') !== 0){
										$extension = pathinfo($from.'/'.$file, PATHINFO_EXTENSION);
										
										//Copio el archivo manteniendo el mismo nombre en la nueva carpeta
										copy($from.'/'.$file, $to.'/'.$idpaciente.'.'.$extension);
										unlink($from.'/'.$file);
									}
								}
								bitacora('Beneficiarios','Fue agregada la foto del paciente',$idpaciente,'');
							}
						}
					}
					
					//QR
					generarQrEditarPaciente($idpaciente,$expediente);					
					nuevoRegistro('Beneficiarios','Beneficiario',$iddireccion,$camposD,$query_direccion);
					nuevoRegistro('Beneficiarios','Beneficiario',$idpaciente,$camposP,$query_paciente);
					//echo $idpaciente;
					
					$sqlC = "INSERT INTO historial_nrodoc (tipodoc,nrodoc,idpaciente,idsolicitud,fecha,usuario) VALUES
					 (".$tipodocumento.",'".$cedula."',".$idpaciente.",0, NOW(), '".$_SESSION['usuario_sen']."')";
					$sqlC = $mysqli->query($sqlC);
					
					$response = array( "success" => true, "idpaciente" => $idpaciente, "msj" => 'Beneficiario almacenado satisfactoriamente' );			
				}else{
					$response = array( "success" => false, "idpaciente" => '', "msj" => 'Error al guardar el beneficiario, por favor intente más tarde' );
				}
			}else{
				$response = array( "success" => false, "idpaciente" => '', "msj" => 'El Nº de expediente ya esta registrado' );
			}
		}else{
			$response = array( "success" => false, "idpaciente" => '', "msj" => 'El Nº de documento ya esta registrado' );
		}
		
		echo json_encode($response);
	} 

	function editar_paciente(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');		
		//DATOS PERSONALES
		$idpaciente	= (!empty($data['idbeneficiario']) ? $data['idbeneficiario'] : '');
		$tipodocumento  = (!empty($data['tipodocumento']) ? $data['tipodocumento'] : '');
		$cedula  		= (!empty($data['cedula']) ? $data['cedula'] : '');
		$nombre  		= (!empty($data['nombre']) ? $data['nombre'] : '');
		$apellidopaterno= (!empty($data['apellidopaterno']) ? $data['apellidopaterno'] : '');
		$apellidomaterno= (!empty($data['apellidomaterno']) ? $data['apellidomaterno'] : '');		
		$expediente  	= (!empty($data['expediente']) ? $data['expediente'] : '');
		$correo  		= (!empty($data['correo']) ? $data['correo'] : '');
		$celular		= (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
		$telefono		= (!empty($data['telefonootro']) ? $data['telefonootro'] : '');
		$fecha_nac		= (!empty($data['fecha_nac']) ? $data['fecha_nac'] : '');		
		$nacionalidad  	= (!empty($data['nacionalidad']) ? $data['nacionalidad'] : '');
		$sexo			= (!empty($data['sexo']) ? $data['sexo'] : '');
		$estado_civil	= (!empty($data['estado_civil']) ? $data['estado_civil'] : '');
		$status			= (!empty($data['status']) ? $data['status'] : 0);
		$fecha_vcto_cm	= (!empty($data['fecha_vcto_cm']) ? $data['fecha_vcto_cm'] : '');
		//DIRECCIÓN
		$iddireccion 	= getValor('direccion','pacientes',$idpaciente);
		$urbanizacion  	= (!empty($data['urbanizacion']) ? $data['urbanizacion'] : '');
		$calle  		= (!empty($data['calle']) ? $data['calle'] : '');
		$edificio  		= (!empty($data['edificio']) ? $data['edificio'] : '');
		$numero  		= (!empty($data['numerocasa']) ? $data['numerocasa'] : '');
		$provincia  	= (!empty($data['idprovincias']) ? $data['idprovincias'] : '');
		$distrito  		= (!empty($data['iddistritos']) ? $data['iddistritos'] : '');
		$corregimiento	= (!empty($data['idcorregimientos']) ? $data['idcorregimientos'] : '');
		$latitud		= (!empty($data['latitud']) ? $data['latitud'] : '');
		$longitud		= (!empty($data['longitud']) ? $data['longitud'] : '');
		//OTROS
		$condicion_actividad= (!empty($data['condicion_actividad']) ? $data['condicion_actividad'] : '');
		$categoria_actividad= (!empty($data['categoria_actividad']) ? $data['categoria_actividad'] : 0);
		$cobertura_medica	= (!empty($data['cobertura_medica']) ? $data['cobertura_medica'] : '');
		//$cobertura_medica 	= implode(',',$cobertura_medica); //COMBO MULTIPLE
		$beneficios			= (!empty($data['beneficios']) ? $data['beneficios'] : '');		
		$beneficios_des  	= (!empty($data['beneficios_descripcion']) ? $data['beneficios_descripcion'] : '');
		//ACOMPAÑANTE
		$idacompanante		= (!empty($data['idacompanante']) ? $data['idacompanante'] : 0);
		$cedulabd		 	= getValor('cedula','pacientes',$idpaciente);
		
		if($cedula != $cedulabd){
			$idsolicitud = 0;
			if($tipodocumento == 2){
				$fVctoCmHist = $fecha_vcto_cm;
			}else{
				$fVctoCmHist = '';
			}
			$sqlC = "INSERT INTO historial_nrodoc (tipodoc,nrodoc,idpaciente,idsolicitud,fecha,fecha_vcto_cm,usuario) VALUES
					 (".$tipodocumento.",'".$cedula."',".$idpaciente.",".$idsolicitud.", NOW(),'".$fVctoCmHist."','".$_SESSION['usuario_sen']."')";
			$sqlC = $mysqli->query($sqlC);
		}
		$bexp = "SELECT expediente FROM pacientes WHERE expediente = '".$expediente."' AND id != ".$idpaciente." ";
		$resultexp = $mysqli->query($bexp);
		$totexp = $resultexp->num_rows;
		if($totexp == 0){		
			//DIRECCIÓN
			$queryD = "	SELECT id FROM direcciones WHERE provincia = '".$provincia."' AND distrito = '".$distrito."' 
						AND corregimiento = '".$corregimiento."' ";
			$resD 	= getRegistroSQL($queryD);
			$idD 	= $resD['id'];
		
			$query_direccion = "UPDATE direccion SET urbanizacion = '".$urbanizacion."', calle = '".$calle."',
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
			$query_paciente = " UPDATE pacientes SET nombre = '".$nombre."', apellidopaterno = '".$apellidopaterno."',
								apellidomaterno = '".$apellidomaterno."', cedula = '".$cedula."', celular = '".$celular."',
								telefono = '".$telefono."',	correo = '".$correo."',	fecha_nac = '".$fecha_nac."',
								tipo_documento = '".$tipodocumento."', nacionalidad = '".$nacionalidad."', sexo = '".$sexo."',
								estado_civil = '".$estado_civil."',	condicion_actividad = '".$condicion_actividad."',
								categoria_actividad = '".$categoria_actividad."', cobertura_medica = '".$cobertura_medica."',
								beneficios = '".$beneficios."',	beneficios_des = '".$beneficios_des."', idacompanante = '".$idacompanante."',
								status = ".$status.", latitud = '".$latitud."', longitud = '".$longitud."'";
			if($fecha_vcto_cm != ''){
				$query_paciente .= ", fecha_vcto_cm = '".$fecha_vcto_cm."'";
			}
								
			if(($expediente != '' && $_SESSION['usuario_sen'] == 'dlombana') || ($expediente != '' && ($_SESSION['nivel_sen'] == 14 || $_SESSION['nivel_sen'] == 15))){
				$query_paciente .=" ,expediente = '".$expediente."' ";
			}
			
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
					'Nacionalidad' 		=> $nacionalidad,
					'Sexo' 				=> $sexo,
					'Estado civil' 		=> $estado_civil,
					'Condición de actividad' 	=> $condicion_actividad,
					'Categoria de la actividad' => $categoria_actividad,
					'Cobertura médica' 	=> $cobertura_medica, 
					'Beneficios' 		=> $beneficios,
					'Beneficios detalle'=> $beneficios_des,
					'Expediente' 		=> $expediente,
					'ID acompañante' 	=> $idacompanante, //getValor('nombre','niveles',$niveles,'')
					'Status'			=> $status,
					'Fecha Vcto. Carnet Migratorio'	=> $fecha_vcto_cm
				);
				actualizarRegistro('Beneficiarios','Beneficiario',$idpaciente,$valoresoldD,$valoresnewD,$query_direccion);
				actualizarRegistro('Beneficiarios','Beneficiario',$idpaciente,$valoresoldP,$valoresnewP,$query_paciente);
				
				$response = array( "success" => true, "idpaciente" => $idpaciente, "msj" => 'Beneficiario almacenado satisfactoriamente' );
			}else{
				$response = array( "success" => false, "idpaciente" => '', "msj" => 'Error al actualizar el beneficiario, por favor intente más tarde' );			
			}
		}else{
			$response = array( "success" => false, "idpaciente" => '', "msj" => 'El Nº de expediente ya esta registrado' );
		}
		echo json_encode($response);
	}
	
	function eliminar(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query = "DELETE FROM pacientes WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			$ruta = "../images/beneficiarios/".$id."/";	
			deleteDirectory($ruta);
			eliminarRegistro('Beneficiarios','Beneficiario',$nombre,$id,$query);
		    echo 1;
		}else{
			echo 0;
		}
	}
	
	function crear_acompanante(){
		global $mysqli;
		$arreglo 	 = (!empty($_REQUEST['arreglo']) ? $_REQUEST['arreglo'] : '');
		$iddirecciones= (!empty($_REQUEST['iddireccion']) ? $_REQUEST['iddireccion'] : '');
		$iddireccion = 0;
		$query_direccion = " INSERT INTO direccion (urbanizacion,calle,edificio,numero,iddireccion) VALUES (
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
		$query_acompanante = "	INSERT INTO acompanantes (nombre, apellido, cedula, celular, telefono,	correo,	fecha_nac, tipo_documento, nacionalidad, sexo,
								estado_civil, direccion, modo_tutor, sentencia,	juzgado, circuito_judicial,	distrito_judicial )VALUES(
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
			'".$arreglo['estado_civil_ac']."',
			'".$iddireccion."',
			'".$arreglo['tipotutor_ac']."',
			'".$arreglo['sentencia_ac']."',
			'".$arreglo['juzgado_ac']."',
			'".$arreglo['circuito_judicial_ac']."',
			'".$arreglo['distrito_judicial_ac']."'
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
		$query_direccion= "UPDATE direccion SET 
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
			
		$query_acompanante ="UPDATE acompanantes SET
		nombre = '".$arreglo['acompanante']['nombre']."',
		apellido = '".$arreglo['acompanante']['apellido']."',
		cedula = '".$arreglo['acompanante']['cedula']."',
		celular = '".$arreglo['acompanante']['celular']."',
		telefono = '".$arreglo['acompanante']['telefono']."',
		correo = '".$arreglo['acompanante']['correo']."',
		fecha_nac = '".$arreglo['acompanante']['fecha_nac']."',
		tipo_documento = '".$arreglo['acompanante']['tipodocumento']."',
		nacionalidad = '".$arreglo['acompanante']['nacionalidad']."',
		sexo = '".$arreglo['acompanante']['sexo']."',
		estado_civil = '".$arreglo['acompanante']['edocivil']."',
		direccion = '".$direccion."',
		modo_tutor = '".$acompanante_modotutor."',
		sentencia = '".$arreglo['acompanante']['sentencia']."',
		juzgado = '".$arreglo['acompanante']['juzgado']."',
		circuito_judicial = '".$arreglo['acompanante']['circuito_judicial']."',
		distrito_judicial = '".$arreglo['acompanante']['distrito_judicial']."' WHERE id = '$idacompanante';";				
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

	function getacompanante(){
		global $mysqli;
		$id = $_REQUEST['id'];
		$query = "	SELECT 
						nombre, apellido, 
						cedula, tipo_documento,
						fecha_nac, sexo, 
						estado_civil, telefono, 
						celular, correo, nacionalidad, 
						sentencia, juzgado, 
						circuito_judicial, distrito_judicial,
						direccion,modo_tutor
					FROM acompanantes
					where id = '$id'";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){		
			$resultado['acompanante'] = array(
				'nombre'   => $row['nombre'],
				'apellido' => $row['apellido'],
				'cedula'   => $row['cedula'],
				'tipo_documento' => $row['tipo_documento'],
				'fecha_nac' => $row['fecha_nac'],
				'sexo' => $row['sexo'],
				'estado_civil' => $row['estado_civil'],
				'telefono' => $row['telefono'],
				'celular' => $row['celular'],
				'correo' => $row['correo'],
				'nacionalidad' => $row['nacionalidad'],
				'sentencia' => $row['sentencia'],
				'juzgado' => $row['juzgado'],
				'circuito_judicial' => $row['circuito_judicial'],
				'distrito_judicial' => $row['distrito_judicial'],
				'modo_tutor' => $row['modo_tutor'],
				'direccion' => $row['direccion']
			);
			$query_direccion = "SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero 
								FROM direccion d 
								LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
								WHERE d.id = '".$row['direccion']."'";
			$result_d = $mysqli->query($query_direccion);
			while ($row_d = $result_d->fetch_assoc()) {
				$resultado['direccion'] = array(
					'provincia' => $row_d['provincia'],
					'distrito' => $row_d['distrito'],
					'corregimiento' => $row_d['corregimiento'],
					'area' => $row_d['area'],
					'urbanizacion' => $row_d['urbanizacion'],
					'calle' => $row_d['calle'],
					'edificio' => $row_d['edificio'],
					'numero' => $row_d['numero']
				);			
			}
		}
		echo json_encode($resultado);
	}	
	
	function getDatosPaciente(){
		global $mysqli;
		$tipo_documento = $_REQUEST['tipo_documento'];
		$cedula = $_REQUEST['cedula'];
		$query = "SELECT id, nombre, CONCAT(apellidopaterno, ' ', apellidomaterno) as apellido FROM pacientes WHERE tipo_documento = '$tipo_documento' AND cedula = '$cedula' LIMIT 1;";
		$result = $mysqli->query($query);
		$resultado = array(
				'id' => 0,
				'nombre'   => '',
				'apellido' => '',
			);
		while($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' => $row['id'],
				'nombre'   => $row['nombre'],
				'apellido' => $row['apellido'],
			);
		}
		echo json_encode($resultado);
	}

	function getDatosPacienteid(){
		global $mysqli;
		$idpaciente = $_REQUEST['idpaciente'];
		$query = "SELECT id, nombre, CONCAT(apellidopaterno, ' ', apellidomaterno) as apellido FROM pacientes WHERE id = '$idpaciente' LIMIT 1;";
		$result = $mysqli->query($query);
		$resultado = array(
				'id' => 0,
				'nombre'   => '',
				'apellido' => '',
			);
		while($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' => $row['id'],
				'nombre'   => $row['nombre'],
				'apellido' => $row['apellido'],
			);
		}
		echo json_encode($resultado);
	}

	function existe(){
		global $mysqli;
		$tipo_documento = $_REQUEST['tipo_documento'];
		$cedula 		= $_REQUEST['cedula'];
		$tipo 			= $_REQUEST['tipo'];
		$expediente 	= $_REQUEST['expediente'];
		$valor 			= 0;
		
		$query = "  SELECT * FROM pacientes WHERE tipo_documento = '".$tipo_documento."' AND cedula = '".$cedula."' LIMIT 1;"; // AND expediente = '$expediente'
		$result = $mysqli->query($query);
		
		if($row = $result->fetch_assoc()){		
			$resultado = array(
				'success' 	=> true,
				'id'		=> $row['id'],
				'nombre' 	=> $row['nombre']."  ".$row['apellidomaterno']."".$row['apellidopaterno']
			);
		}else{
			$resultado = array( 'success' => false, 'id' => '', 'nombre' => '', 'query' => $query );
		}
		echo json_encode($resultado);
	}
	
	function existe_ac(){
		global $mysqli;
		$tipo_documento = $_REQUEST['tipo_documento'];
		$cedula 		= $_REQUEST['cedula'];
		$tipo 			= $_REQUEST['tipo'];
		$expediente 	= $_REQUEST['expediente'];
		$valor 			= 0;
		
		$query = "  SELECT * FROM acompanantes WHERE tipo_documento = '".$tipo_documento."' AND cedula = '".$cedula."' LIMIT 1;"; // AND expediente = '$expediente'
		$result = $mysqli->query($query);
		
		if($row = $result->fetch_assoc()){		
			$resultado = array(
				'success' 	=> true,
				'id'		=> $row['id'],
				'nombre' 	=> $row['nombre']."  ".$row['apellidomaterno']."".$row['apellidopaterno']
			);
		}else{
			$resultado = array( 'success' => false, 'id' => '', 'nombre' => '', 'query' => $query );
		}
		echo json_encode($resultado);
	}
	
	function validarcedula(){
		global $mysqli;
		$cedula = $_REQUEST['cedula'];
		$tipo_documento = $_REQUEST['tipo_documento'];
		$tipo 			= $_REQUEST['tipo_documento'];
		$count = 0;
		$query = "SELECT cedula FROM pacientes WHERE cedula = '$cedula'";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}
	
	function existeExpediente(){
		global $mysqli;
		$expediente 	= $_REQUEST['expediente'];
		$query = " SELECT * FROM pacientes WHERE expediente = '$expediente' LIMIT 1;";
		$result = $mysqli->query($query);
		$resultado = array(
				'id' => 0
			);
			
		$valor = 0;
		while($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' => $row['id']
			);
			$valor = $row['id'];
		}
		echo $valor;
	}
	
	function getHistorialNrodoc(){
		global $mysqli;
		
		$idpaciente = (!empty($_REQUEST['idpac']) ? $_REQUEST['idpac'] : '');
		//$idsolicitud = (!empty($_REQUEST['idsol']) ? $_REQUEST['idsol'] : '');
		
		$sql = " SELECT id, CASE WHEN tipodoc = 1 THEN 'Cédula' WHEN tipodoc= 2 THEN 'Carnet migratorio' ELSE 'Sin especificar' END AS tipodoc, nrodoc, fecha_vcto_cm, fecha 
				 FROM historial_nrodoc WHERE 1";
				 
		if($idpaciente !== ''){
			$sql .=" AND idpaciente = ".$idpaciente." ";
		}
		/* if($idsolicitud !== ''){
			$sql .=" AND idsolicitud = ".$idsolicitud." ";
		} */
		$sql .=" ORDER BY fecha DESC";
		
		$rta = $mysqli->query($sql);
		$resultado = array();
		while($row = $rta->fetch_assoc()){		
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'tipodocumento'	=>	$row['tipodoc'],
				'nrodoc'		=>	$row['nrodoc'],
				'fecha_vcto_cm'	=>	$row['fecha_vcto_cm'],
				'fecha'			=>  $row['fecha'] 
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
	
	function existeNombreFecha(){
		global $mysqli;
		
		$cedula = (!empty($_REQUEST['cedula']) ? $_REQUEST['cedula'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$apellidopaterno = (!empty($_REQUEST['apellidopaterno']) ? $_REQUEST['apellidopaterno'] : '');
		$apellidomaterno = (!empty($_REQUEST['apellidomaterno']) ? $_REQUEST['apellidomaterno'] : '');
		$fechanac = (!empty($_REQUEST['fechanac']) ? $_REQUEST['fechanac'] : '');
		$idpaciente = 0;
		
		$sql = " SELECT id, cedula, CASE WHEN tipo_documento = 1 THEN 'Cédula' WHEN tipo_documento = 2 THEN 'Carnet Migratorio' END AS tipodoc 
				 FROM pacientes 
				 WHERE 
				 nombre LIKE '%".$nombre."%' 
				 AND apellidopaterno LIKE '%".$apellidopaterno."%' 
				 AND apellidomaterno LIKE '%".$apellidomaterno."%' 
				 AND fecha_nac = '".$fechanac."'";
		if($cedula != ""){
			$sql .= " AND cedula != '".$cedula."'";
		}
		
		$rta = $mysqli->query($sql);
		if($row = $rta->fetch_assoc()){
			$idpaciente = $row['id'];
			$documento = $row['tipodoc'].": ".$row['cedula'];
			$response = array( 
				"idpaciente" => $idpaciente,
				"documento" => $documento
			);
		}else{
			$response = array( 
				"idpaciente" => 0,
				"documento" => ''
			);
		}
		echo json_encode($response);
	}
	
	function subirFoto(){
		
		$idpaciente = (!empty($_REQUEST['idpaciente']) ? $_REQUEST['idpaciente'] : '');
		$id = $_SESSION['user_id_sen'];
		
		if(isset($_FILES['images'])){
			//echo json_encode($_FILES);
			//Parámetros optimización, resolución máxima permitida
			$max_ancho = 400;
			$max_alto = 400;
			
			if($idpaciente !== ''){
				
				$patch = "../images/beneficiarios/".$idpaciente."/";
				$nombrearchivo = $idpaciente;
			}else{
				createFolder("../images/beneficiarios/temporal/".$id."/");
				$patch = "../images/beneficiarios/temporal/".$id."/"; 
				$nombrearchivo = $id;
			}
				
			if($_FILES['images']['type']=='image/png' || $_FILES['images']['type']=='image/jpeg' || $_FILES['images']['type']=='image/gif'){
				
				$medidasimagen= getimagesize($_FILES['images']['tmp_name']);
				if($_FILES['images']['type']=='image/png')
					$extension = '.png';
				if($_FILES['images']['type']=='image/jpeg')
					$extension = '.jpg';	
				if($_FILES['images']['type']=='image/gif')
					$extension = '.gif';
				
				//Si las imagenes tienen una resolución y un peso aceptable se suben tal cual
				if($medidasimagen[0] < 400 && $_FILES['images']['size'] < 100000){
					
					//$nombrearchivo=$_FILES['images']['name'];
					move_uploaded_file($_FILES['images']['tmp_name'], $patch.'/'.$nombrearchivo.$extension);
					
				}else {
					
					//Si no, se generan nuevas imagenes optimizadas
					//$nombrearchivo=$_FILES['images']['name'];
					
					//Redimensionar
					$rtOriginal=$_FILES['images']['tmp_name'];
					
					if($_FILES['images']['type']=='image/jpeg'){
						$original = imagecreatefromjpeg($rtOriginal);
					}
					else if($_FILES['images']['type']=='image/png'){
						$original = imagecreatefrompng($rtOriginal);
					}
					else if($_FILES['images']['type']=='image/gif'){
						$original = imagecreatefromgif($rtOriginal);
					}

			 
					list($ancho,$alto)=getimagesize($rtOriginal);

					$x_ratio = $max_ancho / $ancho;
					$y_ratio = $max_alto / $alto;


					if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){
						$ancho_final = $ancho;
						$alto_final = $alto;
					}
					elseif (($x_ratio * $alto) < $max_alto){
						$alto_final = ceil($x_ratio * $alto);
						$ancho_final = $max_ancho;
					}
					else{
						$ancho_final = ceil($y_ratio * $ancho);
						$alto_final = $max_alto;
					}

					$lienzo=imagecreatetruecolor($ancho_final,$alto_final); 

					imagecopyresampled($lienzo,$original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
					 
					//imagedestroy($original);
					 
					$cal=8;

					if($_FILES['images']['type']=='image/jpeg'){
						imagejpeg($lienzo,$patch."/".$nombrearchivo.$extension);
					}
					else if($_FILES['images']['type']=='image/png'){
						imagepng($lienzo,$patch."/".$nombrearchivo.$extension);
					}
					else if($_FILES['images']['type']=='image/gif'){
						imagegif($lienzo,$patch."/".$nombrearchivo.$extension);
					}

				}
				
			} else echo 'fichero no soportado';
		}
		
	}	
	
	function marcarComoImpreso(){
		global $mysqli;
		
		$idpacientes = (!empty($_REQUEST['idpacientes']) ? $_REQUEST['idpacientes'] : '');
		
		$idEstadoPendientePorImprimir = 24;
		$idEstadoImpreso = 26;
		
		$sql = " SELECT 
					id,estatus 
				 FROM 
					solicitudes 
				 WHERE idpaciente = ".$idpacientes." 
				 AND (estatus = ".$idEstadoPendientePorImprimir."
				 OR estatus = ".$idEstadoImpreso.")
				 ORDER BY id DESC
				 LIMIT 1";
		$rta = $mysqli->query($sql);
		
		if($row = $rta->fetch_assoc()){
			$idsolicitud = $row['id'];
			$estatus = $row['estatus'];
			
			if($estatus != $idEstadoImpreso){
				$update = " UPDATE 
								solicitudes 
							SET 
								estatus = ".$idEstadoImpreso."
							WHERE 
								id = ".$idsolicitud."";
								
				$rtaUpd = $mysqli->query($update);
				if($rtaUpd == true){
					
					//Guardar en solicitudes estados
					$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
								VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), 24, 26) "; 
					$mysqli->query($queryE);
					
					//Guardar en bitácora cambio de estado de la solicitud
					$valoresoe = array('Estado' => getValor('descripcion','estados',$estatus,'') );
					$valoresne = array('Estado' => getValor('descripcion','estados',26,'') );
					actualizarRegistro('Solicitudes','Solicitud',$idsolicitud,$valoresoe,$valoresne,$update);
					echo 1;
				}else{
					echo 0;
				}
			}
		}
	}
	
	function guardarImpresionBitacora(){ 
		
		global $mysqli;
		
		$idpacientes = (!empty($_REQUEST['idpacientes']) ? $_REQUEST['idpacientes'] : '');
		$fechaemision = (!empty($_REQUEST['fechaemision']) ? $_REQUEST['fechaemision'] : '');
		$fechavencimiento = (!empty($_REQUEST['fechavencimiento']) ? $_REQUEST['fechavencimiento'] : '');
		$duplicado = (!empty($_REQUEST['duplicado']) ? $_REQUEST['duplicado'] : 0);
		$user_id = $_SESSION['user_id_sen'];
		
		$sqlReg = "SELECT regional FROM usuarios WHERE id = ".$user_id;
		$rtaSqlReg = $mysqli->query($sqlReg);
		
		if($regSqlReg = $rtaSqlReg->fetch_assoc()){
			$regionalUsuario = $regSqlReg['regional'];
		}
		
		$newFechaEmision = date("Y-m-d", strtotime($fechaemision));
		$newFechaVencimiento = date("Y-m-d", strtotime($fechavencimiento));
		
		//Guardar en bitácora impresión de carnet
		$txtduplicado = $duplicado == 1 ? ' - Duplicado' : '';
		$paciente = getValor("CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno)",'pacientes',$idpacientes);
		bitacora('Beneficiarios','Se ha realizado la impresión del carnet del beneficiario ' . $paciente . '' .$txtduplicado, $idpacientes,'');
		
		
		//Guardar en tabla carnet_impresos
		$sql_carnet = "INSERT INTO carnet_impresos (idpacientes,fechaemision,fechavencimiento,idusuarios,regional,duplicado)
		VALUES(".$idpacientes.",'".$newFechaEmision."','".$newFechaVencimiento."',".$user_id.",'".$regionalUsuario."','".$duplicado."')";
		$rtacarnet = $mysqli->query($sql_carnet); 
		$response = $rtacarnet == true ? 1 : 0;
		echo $response;
	}	
	
	function guardarImpresionBitacoraReimp(){
		
		global $mysqli;
		
		$idpacientes = (!empty($_REQUEST['idpacientes']) ? $_REQUEST['idpacientes'] : '');
		$codigoautorizacion = (!empty($_REQUEST['codigoautorizacion']) ? $_REQUEST['codigoautorizacion'] : '');
		$fechaemision = (!empty($_REQUEST['fechaemision']) ? $_REQUEST['fechaemision'] : '');
		$fechavencimiento = (!empty($_REQUEST['fechavencimiento']) ? $_REQUEST['fechavencimiento'] : '');
		$paciente = getValor("CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno)",'pacientes',$idpacientes);
		$duplicado = (!empty($_REQUEST['duplicado']) ? $_REQUEST['duplicado'] : 0);
		$user_id = $_SESSION['user_id_sen'];
		
		$newFechaEmision = date("Y-m-d", strtotime($fechaemision));
		$newFechaVencimiento = date("Y-m-d", strtotime($fechavencimiento));
		
		//Regional del usuario
		$sqlReg = "SELECT regional FROM usuarios WHERE id = ".$user_id;
		$rtaSqlReg = $mysqli->query($sqlReg);
		
		if($regSqlReg = $rtaSqlReg->fetch_assoc()){
			$regionalUsuario = $regSqlReg['regional'];
		}
		
		//Guardar en bitácora
		$txtduplicado = $duplicado == 1 ? ' - Duplicado' : '';
		bitacora('Beneficiarios','Se ha realizado la reimpresión del carnet del beneficiario ' . $paciente . '' .$txtduplicado. ' Código autorización:' . $codigoautorizacion, $idpacientes,'');
		
		//Guardar en tabla carnet_impresos
		$sql_carnet = "INSERT INTO carnet_impresos (idpacientes,fechaemision,fechavencimiento,idusuarios,regional,duplicado)
		VALUES(".$idpacientes.",'".$newFechaEmision."','".$newFechaVencimiento."',".$user_id.",'".$regionalUsuario."','".$duplicado."')";
		//echo $sql_carnet;
		$rtacarnet = $mysqli->query($sql_carnet);
		$response = $rtacarnet == true ? 1 : 0;
		echo $response;
	}
	
	function verificarImpresionBitacora(){ 
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$fecha = date('Y-m-d');
		
		//Si ha pasado más de un día de la impresión del carnet
		$sql = "SELECT * FROM bitacora 
				WHERE identificador = ".$id." 
				AND modulo = 'Beneficiarios'
				AND accion LIKE '%Se ha realizado la impresión del carnet del beneficiario%'
				AND DATE(fecha)< '".$fecha."'";
				//echo $sql;
		$result = $mysqli->query($sql);
		$total = $result->num_rows;
		if($total>0){
			//Solicitar código de autorización
			echo 1;
		}else{
			echo 0;
		}
	}	
	
	function validarCodigoAutorizacion(){ 
		global $mysqli;
		
		$codigoautorizacion = (!empty($_REQUEST['codigoautorizacion']) ? $_REQUEST['codigoautorizacion'] : '');
		$usuario = $_SESSION['usuario_sen'];
		
		//Buscar regional del usuario que va a reimprimir el carnet
		$sqlReg = " SELECT regional FROM usuarios WHERE usuario = '".$usuario."'";
		$resReg = $mysqli->query($sqlReg);
		if($val = $resReg->fetch_assoc()){
			$regional = $val['regional'];
		}
		
		//Si ha pasado más de un día de la impresión del carnet
		//Buscar código y regional del usuario que autoriza reimpresión del carnet
		$sql = "SELECT * FROM codigosautorizacion a
				INNER JOIN usuarios b ON b.id = a.idusuarios
				WHERE 1 ";
		
		//Si el usuario tiene regional 'Todos', solo se busca el código de autorización, sino, se busca la regional o.
		//el código del usuario que en su regional tenga configurado 'Todos'.
		if($regional != 'Todos'){
			$sql .= " AND (b.regional = '".$regional."' OR b.regional = 'Todos') ";	
		}	 
		
		$sql .= " AND codigo = '".$codigoautorizacion."'";
			//echo $sql;
		$result = $mysqli->query($sql);
		$total = $result->num_rows;
		if($total>0){
			//Código válido
			echo 1;
		}else{
			//Código inválido
			echo 0;
		}
	}
	
	function generarQrEditarPaciente($idpaciente,$expediente){
		
		$dir_ben = '../images/beneficiarios/'.$idpaciente.'/';
		$dirqr_ben = '../images/beneficiarios/'.$idpaciente.'/qr/';
		if(createFolder($dir_ben)){
			if(createFolder($dirqr_ben)){
				QR($idpaciente,$expediente);
			}						
		}
	}
		
	function deleteDirectory($dir) {
		if(!$dh = @opendir($dir)) return;
		while (false !== ($current = readdir($dh))) {
			if($current != '.' && $current != '..') {
				//echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
				if (!@unlink($dir.'/'.$current)) 
					deleteDirectory($dir.'/'.$current);
			}       
		}
		closedir($dh);
		//echo 'Se ha borrado el directorio '.$dir.'<br/>';
		@rmdir($dir);
	}
	
	//Crear el qr del paciente
	function QR($id,$expediente){
		$directorio = "../images/beneficiarios/".$id."/qr/";   
		$codigo = $id.'qr.png'; 
		$expediente = base64_encode($expediente);
		$info   = 'https://toolkit.maxialatam.com/senadisqa/controller/redireccion.php?id='.$expediente;
		QRcode::png($info,$directorio.$codigo,"H",6); 
		//echo 1;
	}
	
	//FUNCIONES VALIDACIÓN DERECHO
	function obtenerValidacionDeDerecho(){
		
		global $mysqli;
		
		$expediente = (!empty($_REQUEST['expediente']) ? $_REQUEST['expediente'] : '');
		
		//Verificar la regional del usuario
		$usuario = $_SESSION['usuario_sen'];
		$getRegUsuario = "SELECT regional FROM usuarios WHERE usuario = '".$usuario."'";
		$rtaGetRe = $mysqli->query($getRegUsuario);		
		if($rowReg = $rtaGetRe->fetch_assoc()){
			$regionalUsu = $rowReg['regional'];
		}
		
		//Buscar firmas de la regional del usuario
		$sqlF = "SELECT a.id, a.cargo FROM firmas a 
				INNER JOIN usuarios b ON b.id = a.idusuarios 
				WHERE b.regional = '".$regionalUsu."' AND a.estado = 'Activo'";		
		$rtaF = $mysqli->query($sqlF);	
		$totalF = $rtaF->num_rows; 
		
		if($totalF== 2){
			$firmas = 1;
			$arrayFirmas = array();
			
			while($rowF = $rtaF->fetch_assoc()){
				
				$cargo = $rowF['cargo'];
				$idfirma = $rowF['id'];
				
				if (strpos($cargo, "Director(a) General") !== false) {
					$cargoDirGen = $cargo;
					$idDirGen = $idfirma;
				}
				if (strpos($cargo, "Nal") !== false) {
					$cargoDirNac = $cargo;
					$idDirNac = $idfirma;
				}
				
				$arrayFirmas ['DirectorNacional'] = $cargoDirNac;
				$arrayFirmas ['DirectorGeneral'] = $cargoDirGen;
				$arrayFirmas ['IdDirGen'] = $idDirGen;
				$arrayFirmas ['IdDirNac'] = $idDirNac;
			}
		}else{
			
			//Si no hay firmas de la regional del usuario, buscar firmas de 'Todos'
			
			$sqlFirmTodos = "SELECT a.id, a.cargo FROM firmas a 
				INNER JOIN usuarios b ON b.id = a.idusuarios 
				WHERE b.regional = 'Todos' AND a.estado = 'Activo'";
				
			$rtaFirmTodos = $mysqli->query($sqlFirmTodos);	
			$totalFirmTodos = $rtaFirmTodos->num_rows; 	
			if($totalFirmTodos== 2){
				$firmas = 1;
				$arrayFirmas = array();
				
				while($rowF = $rtaFirmTodos->fetch_assoc()){
					
					$cargo = $rowF['cargo'];
					$idfirma = $rowF['id'];
					
					if (strpos($cargo, "Director(a) General") !== false) {
						$cargoDirGen = $cargo;
						$idDirGen = $idfirma;
					}
					if (strpos($cargo, "Nal") !== false) {
						$cargoDirNac = $cargo;
						$idDirNac = $idfirma;
					}
					
					$arrayFirmas ['DirectorNacional'] = $cargoDirNac;
					$arrayFirmas ['DirectorGeneral'] = $cargoDirGen;
					$arrayFirmas ['IdDirGen'] = $idDirGen;
					$arrayFirmas ['IdDirNac'] = $idDirNac;
				}
			}else{
				$firmas = 0;
			}
				
		}  
		//echo "EXPEDIENTE ES: ".$expediente;
		$sql = "SELECT 
					b.estatus, a.tipo_documento, c.fechaemision, c.fechavencimiento, a.fecha_vcto_cm, c.duracion, c.tipoduracion 
				FROM
					pacientes a 
				INNER JOIN solicitudes b ON b.idpaciente = a.id 
				INNER JOIN evaluacion c ON c.idsolicitud = b.id 
				WHERE b.estatus IN (3,24,26) AND a.expediente = ".$expediente." ORDER BY b.id DESC LIMIT 1";
				//echo "SQL ES:".$sql."\n -";
		$rta = $mysqli->query($sql);
		if($row = $rta->fetch_assoc()){
			
			$checkimpreso = $row['estatus'] == 26 ? 1 : 0;
			
			//Se realiza verificación por fechas ó duración-tipo de duración
			$tipodocumento = $row['tipo_documento'];
			$fechaemision = $row['fechaemision'];
			
			$duracion = $row['duracion'];	//A => Año , M => Mes
			$tipoduracion = $row['tipoduracion']; //Entero
			$fechaactual = date('Y-m-d');
			
			if($tipodocumento == '2'){
				$fechavencimiento = $row['fecha_vcto_cm'];	
			}else{
				$fechavencimiento = $row['fechavencimiento'];
			} 
			$arrfecha = explode('-',$fechavencimiento);
			
			$dia = $arrfecha[0];
			$mes = $arrfecha[1];
			$anio = $arrfecha[2]; 
			
			$esFecha = checkdate($mes, $dia, $anio);
			
			if($fechavencimiento !== '' && $esFecha == 1){
				
				if (RangoFechas($fechaemision, $fechavencimiento, $fechaactual))
				{

					$respuesta = 1;

				} else {

					$respuesta = 0;

				}
			}else{
				 
				//Validación por duración
				if($tipoduracion == 'A'){ 
					$tiempo = diferenciaFechas($tipoduracion,$fechaemision,$fechaactual);
					
					if($tiempo <= $duracion){
						$respuesta = 1;
					}else{
						$respuesta = 0;
					}
					
				}elseif($tipoduracion == 'M'){
					$tiempo = diferenciaFechas($tipoduracion,$fechaemision,$fechaactual);
					
					if($tiempo <= $duracion){
						$respuesta = 1;
					}else{
						$respuesta = 0;
					}
				}else{
					$respuesta = 0;
				}
			}
		}else{
			$respuesta = 0;
		}
		
		if($respuesta == 1){
			setlocale(LC_TIME, "spanish");
			$sql = " SELECT a.id AS idpaciente, a.tipo_documento, a.fecha_vcto_cm, CONCAT(a.nombre,' ',a.apellidopaterno,' ',a.apellidomaterno) AS nombrecompleto, 
					 a.cedula, a.fecha_nac, d.nombre AS nacionalidad, c.fechaemision, c.fechavencimiento 
					 FROM pacientes a 
					 INNER JOIN solicitudes b ON b.idpaciente = a.id 
					 INNER JOIN evaluacion c ON c.idsolicitud = b.id  
					 LEFT JOIN nacionalidades d ON d.id = a.nacionalidad
					 WHERE expediente = ".$expediente;
			$rta = $mysqli->query($sql);
			if($row = $rta->fetch_assoc()){
				
				$idpaciente = $row['idpaciente'];
				$tipodocumento = $row['tipo_documento'];
				$nombrecompleto = $row['nombrecompleto'];
				$cedula = $row['cedula'];
				$fecha_nac = formatFechaString($row['fecha_nac']);
				$nacionalidad = $row['nacionalidad'];
				$desde = formatFechaString($row['fechaemision']);
				if($tipodocumento == '2'){
					$fechavencimiento = $row['fecha_vcto_cm'];	
				}else{
					$fechavencimiento = $row['fechavencimiento'];
				}
				$hasta = formatFechaString($fechavencimiento); 
				
				$ruta = '../images/beneficiarios/'.$idpaciente.'/';
				$imagen = fotoPaciente($idpaciente,$ruta);
				$qr = qrPaciente($idpaciente);
			}
			
			
			$resultado = array(
							'nombrecompleto' => mb_strtoupper($nombrecompleto), 
							'cedula' => $cedula,
							'fecha_nac' => $fecha_nac,
							'nacionalidad' => $nacionalidad,
							'desde' => $desde, 
							'hasta' => $hasta,
							'imagen' => $imagen,
							'firmas' => $firmas,
							'arrayFirmas' => $arrayFirmas,
							'qr' => $qr,
							'fechaemision' => $row['fechaemision'],
							'fechavencimiento' => $fechavencimiento,
							'checkimpreso' => $checkimpreso
							);
							
			echo json_encode($resultado);
		}else{
			echo 0;
		}
				
	}
	
	function getCarnetImpresos(){
		
		global $mysqli;
		
		$idpacientes = (!empty($_REQUEST['idpacientes']) ? $_REQUEST['idpacientes'] : '');
		
		$sql = "SELECT a.fechacreacion, a.regional, b.nombre AS usuario, IF(duplicado = 1, 'Duplicado', 'Original') AS tipo
				FROM carnet_impresos a 
				INNER JOIN usuarios b ON b.id = a.idusuarios
				WHERE idpacientes = " . $idpacientes . " ORDER BY a.id DESC ";
		$rta = $mysqli->query($sql);
		if($rta->num_rows>0){
			$resultado = array();
			while($row = $rta->fetch_assoc()){
				
				$fechacreacion = $row['fechacreacion'];
				$regional = $row['regional'];
				$usuario = $row['usuario'];
				$tipo = $row['tipo'];
				
				$resultado[] = array(
								'fechacreacion' => $fechacreacion,
								'regional' => $regional,
								'usuario' => $usuario,
								'tipo' => $tipo 
								);
			
			}
			$response = array(
				"data" => $resultado
			);
			echo json_encode($response);
		}else{
			echo 0;
		}
	}
	
	function RangoFechas($fechainicio, $fechafin, $fecha){

		$fechainicio = strtotime($fechainicio);
		$fechafin = strtotime($fechafin);
		$fecha = strtotime($fecha);

		if(($fecha >= $fechainicio) && ($fecha <= $fechafin)) {

		 return true;

		} else {

		 return false;

		}
	}

	function diferenciaFechas($tipo,$fechainicio,$fechafin){
		$fechainicio = new DateTime($fechainicio);
		$fechafin = new DateTime($fechafin);
		$diferencia = $fechainicio->diff($fechafin);
		
		if($tipo == 'A'){
			$tiempo = $diferencia->y;
		}elseif($tipo == 'M'){
			$anios = ($diferencia->y)*12;
			$meses = $diferencia->m;
			$tiempo = $anios + $meses; 
		}else{
			echo "Error en el tipo \n";
		}
		return $tiempo;
	}
		
	function exportarExcel(){
		global $mysqli;
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		$data 	 = '';
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		/** Include PHPExcel */
		//require_once dirname(__FILE__) . '../../repositorio-lib/xls/Classes/PHPExcel.php';
		require_once '../../repositorio-lib/xls/Classes/PHPExcel.php';
		//require_once dirname(__FILE__) . '\PHPExcel.php'; // localhost de Eduardo
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("SENADIS")
		->setLastModifiedBy("SENADIS")
		->setTitle("Reporte de Pacientes")
		->setSubject("Reporte de Pacientes")
		->setDescription("Reporte de Pacientes")
		->setKeywords("Reporte de Pacientes")
		->setCategory("Reportes");
		//ESTILOS
		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
		$fontColor = new PHPExcel_Style_Color();
		$fontColor->setRGB('ffffff');
		$fontGreen = new PHPExcel_Style_Color();
		$fontGreen->setRGB('00b355');
		$fontRed = new PHPExcel_Style_Color();
		$fontRed->setRGB('ff0000');		
		$style = array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
		);
		$style2 = array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				)
		);
		//TITULO	
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Reporte de Pacientes');
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');		
		// ENCABEZADO 
		$objPHPExcel->getActiveSheet()
		->setCellValue('A4', 'CÉDULA')
		->setCellValue('B4', 'NOMBRE') 
		->setCellValue('C4', 'APELLIDO')
		->setCellValue('D4', 'FECHA DE NACIMIENTO')
		->setCellValue('E4', 'SEXO')
		->setCellValue('F4', 'TELEFONO');
		//LETRA
		$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
		$objPHPExcel->getActiveSheet()->getStyle("A4:F4")->applyFromArray($style);
		//FONDO
		$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('63b9db');		
		//SENTENCIA BASE
		$cedula = $_REQUEST['cedula'];
		$nombre = $_REQUEST['nombre'];
		$telefono = $_REQUEST['telefono'];
		$sexo = $_REQUEST['sexo'];
		$fecha_nac = $_REQUEST['fecha_nac'];

		$query  = " SELECT nombre,CONCAT(p.apellidopaterno, ' ', p.apellidomaterno) as apellido, cedula, celular, fecha_nac, (CASE sexo WHEN 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END) as sexo
					FROM pacientes 
         			WHERE 1=1";
         if ($cedula != '')
         	$query .=" AND cedula LIKE '%".$cedula."%' ";
         if ($nombre != '')
         	$query .=" AND nomrbe LIKE '%".$nombre."%' ";
         	$query .=" AND apellido LIKE '%".$nombre."%' ";
         if ($telefono != '')
         	$query .=" AND celular LIKE '%".$celular."%' ";
         if ($sexo != '')
         	if($sexo == 'Masculino'){
         		$query .="  AND sexo = 'M' ";         
         	}else{
         		$query .="  AND sexo = 'F' ";         
         	}
         if ($fecha_nac != '')
         	$query .=" AND fecha_nac LIKE '%".$fecha_nac."%' ";

         $query .=" ORDER BY fecha_nac ASC";			
		
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);							
		//$query  .= " $where2 ORDER BY id desc ";
		//debug('a: '.$query);
		$result = $mysqli->query($query);
		$i = 5;		
		while($row = $result->fetch_assoc()){
			$objPHPExcel->getActiveSheet()
			->setCellValue('A'.$i, $row['cedula'])
			->setCellValue('B'.$i, $row['nombre']) 
			->setCellValue('C'.$i, $row['apellido'])
			->setCellValue('D'.$i, $row['fecha_nac'])
			->setCellValue('E'.$i, $row['sexo'])
			->setCellValue('F'.$i, $row['celular']);
					
			//ESTILOS
			/* $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getAlignment()->applyFromArray(
						array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
			$objPHPExcel->getActiveSheet()->getStyle('AD'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			$objPHPExcel->getActiveSheet()->getStyle('AH'.$i)->getAlignment()->applyFromArray(
						array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)); */
			$i++;
		}
		//Ancho automatico
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);//setAutoSize(true);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 		
		//Renombrar hoja de Excel
		$objPHPExcel->getActiveSheet()->setTitle('SENADIS - Pacientes');
		//Redirigir la salida al navegador del cliente
		$hoy = date('dmY');
		$nombreArc = 'PACIENTES-'.$hoy.'.xls';
		//bitacora($_SESSION['usuario_sen'],'Equipos','Fue generado un archivo con el nombre: '.$nombreArc,0,'');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
?>