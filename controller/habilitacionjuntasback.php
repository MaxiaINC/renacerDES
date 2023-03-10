<?php
    include("conexion.php");
    sessionrestore();
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargar": 
			  cargar();
			  break;
		case "getPacientes": 
			  getPacientes();
			  break;
		case "getMedicos": 
			  getMedicos();
			  break;
		case "guardarHabilitacionJunta": 
			  guardarHabilitacionJunta();
			  break;
		case "actualizarHabilitacionJunta": 
			  actualizarHabilitacionJunta();
			  break;
		case "getUltimoNrojunta": 
			  getUltimoNrojunta();
			  break;
		case "getHabilitacionJuntas": 
			  getHabilitacionJuntas();
			  break;
		case "asignarCodigoResolucion": 
			  asignarCodigoResolucion();
			  break;
		case "eliminar": 
			  eliminar();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}
	
	function cargar(){
		
		global $mysqli;		
		$draw = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = $_REQUEST['order'][0]['0'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	    $orderBy = 0;//$_REQUEST['id'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	    $orderType = "DESC";//$_REQUEST['order'][0]['dir']; // ASC or DESC
	    $start   = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$nivel = $_SESSION['nivel_sen'];
		$user_id = $_SESSION['user_id_sen'];
		$regional_usu 	= getValor('regional','usuarios',$user_id);
		
		$query = "  SELECT a.id, a.nroresolucion, a.fechaevaluacion, a.fecharesolucion,
					GROUP_CONCAT(DISTINCT CONCAT(d.nombre,' ',d.apellido) SEPARATOR ', ') AS medicos, 
					GROUP_CONCAT(DISTINCT CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno, ' (', e.id, ')') SEPARATOR ', ') AS pacientes,
					f.nombre AS regional, 
					(
						SELECT COUNT(*) 
						FROM habilitacionjuntas t2 
						WHERE 
							t2.idregionales = a.idregionales AND 
							t2.creation_time <= a.creation_time AND 
							DATE(t2.creation_time) = DATE(a.creation_time)
					) AS posicion
					FROM habilitacionjuntas a 
					INNER JOIN habilitacionjuntasmedicos b ON a.id = b.idhabilitacionjuntas 
					INNER JOIN habilitacionjuntaspacientes c ON a.id = c.idhabilitacionjuntas 
					INNER JOIN medicos d ON b.idmedicos = d.id 
					INNER JOIN pacientes e ON c.idpacientes = e.id 
					INNER JOIN regionales f ON f.id = a.idregionales
					WHERE 1 = 1 "; 
		if($regional_usu != 'Todos' && $regional_usu != '' && $regional_usu != null){
			$query .= " AND f.nombre IN ('".$regional_usu."') ";
		}
		
		$hayFiltros = 0;
		$whereF = array();
		$where 	= '';
		$groupF = 0;
		$groupC = '';
		if (is_array($_REQUEST['columns'])) {
			for($i=0 ; $i<count($_REQUEST['columns']);$i++){
				$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
				if ($_REQUEST['columns'][$i]['search']['value']!="") {
					$campo = $_REQUEST['columns'][$i]['search']['value'];
					$campo = str_replace('^','',$campo);
					$campo = str_replace('$','',$campo);
					
					if ($column == 'regional') {
						$column = 'f.nombre';
						$whereF[]=" $column = '".$campo."' ";
					}
					if ($column == 'nroresolucion') {
						$column = 'a.nroresolucion';
						$whereF[]=" $column = '".$campo."' ";
					}
					/* if ($column == 'nrojunta') {
						$column = 'a.nrojunta';
						$whereF[]=" $column = '".$campo."' ";
					} */	
					if ($column == 'fechaevaluacion') {
						$column = 'a.fechaevaluacion';
						$whereF[]=" $column = '".$campo."' ";
					}
					if ($column == 'fecharesolucion') {
						$column = 'a.fecharesolucion';
						$whereF[]=" $column = '".$campo."' ";
					}				
					$hayFiltros++;
				}
			}
		}
		if ($hayFiltros > 0 && $groupF != 1)
			$where = " AND ".implode(" AND " , $whereF)." ";
		
		$query  .= "$where "; 
		$query  .= " $sWhere "; 
		
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		 
		$query .= " GROUP BY a.id, a.nroresolucion, a.fechaevaluacion ";
	
		$query .= " ORDER BY a.creation_time DESC LIMIT $start, $length ";
		//echo $query;
		$resultado = array();	
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		
		while($row = $result->fetch_assoc()){

			// Obtenemos la cadena con los nombres y los ids
			$pacientes_con_ids = $row['pacientes'];

			// Separamos los nombres e ids en un arreglo
			$pacientes_arr = explode(", ", $pacientes_con_ids);

			// Creamos un nuevo arreglo solo con los nombres de los pacientes
			$pacientes_nombres = array();
			foreach ($pacientes_arr as $paciente) {
			  $nombre = preg_replace('/\(.*\)/', '', $paciente); // Eliminamos los ids del nombre
			  $pacientes_nombres[] = $nombre;
			}

			// Unimos los nombres en una cadena separada por comas
			$pacientes_sin_ids = implode(", ", $pacientes_nombres); 
			
			$boton_verresolucion = '<a class="dropdown-item text-info boton-resolucion" data-id="'.$row['id'].'"><i class="fas fa-file-pdf mr-2"></i>Ver resoluci??n</a>';
			$boton_eliminar = '<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-file-pdf mr-2"></i>Eliminar</a>';
			
			$botones = '';			
			$botones .="	
				$boton_verresolucion
				$boton_eliminar
			";
			
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24"></rect>
											<circle fill="#000000" cx="5" cy="12" r="2"></circle>
											<circle fill="#000000" cx="12" cy="12" r="2">
											</circle><circle fill="#000000" cx="19" cy="12" r="2"></circle>
										</g>
									</svg>
								</div>
								<div class="dropdown-menu droptable dropdown-menu-right">';
			$acciones .= $botones;
			$acciones .= '		</div>
							</div>
						</td>';
			
			$resultado['data'][] = array(
				'id' 				=>	$row['id'],
				'nroresolucion'		=> 	$row['nroresolucion'],
				'acciones' 			=>	$acciones,
				'nrojunta'	 		=>	$row['posicion'],
				'fechaevaluacion' 	=>	$row['fechaevaluacion'],
				'fecharesolucion' 	=>	$row['fecharesolucion'],
				'regional' 			=>	$row['regional'],
				'medicos' 			=>	$row['medicos'],
				'solicitantes' 		=>	$pacientes_sin_ids,
			);
		}
		if(empty($resultado)){
			$resultado['data'][] = array(
				'id'=>'', 'nroresolucion' => '', 'acciones' => '', 'nrojunta'=>'', 'fecharesolucion'=>'', 'fechaevaluacion'=>'', 'regional'=>'', 'medicos'=>'', 'solicitantes'=>'',
			);
		}
		//BITACORA
		//guardarRegistroG('Solicitudes', 'Cargar solicitud');
		
		$resultado['draw'] = intval($draw);
		$resultado['recordsTotal'] = intval($recordsTotal);
		$resultado['recordsFiltered'] = intval($recordsTotal);
		echo json_encode($resultado);
	}

	function getMedicos (){
		global $mysqli;
			
		$id = $_REQUEST['id'];

		$query 	= "	SELECT a.id, a.cedula, a.nombre, a.apellido, IFNULL('',a.nroregistro) AS nroregistro, b.nombre AS especialidad
					FROM medicos a
					LEFT JOIN especialidades b ON b.id = a.especialidad
					WHERE a.id = '$id'
				";
		//debug($query);
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){			
			$resultado = array(
				'id'		=>	$row['id'], 
				'cedula'	=>	$row['cedula'], 
				'nombre'	=>	$row['nombre'], 
				'apellido' 	=>	$row['apellido'], 			
				'nroregistro' 	=>	$row['nroregistro'], 			
				'especialidad' 	=>	$row['especialidad'], 			
			);
		}
		
		echo json_encode($resultado);
	}
	
	function getPacientes (){
		global $mysqli;
			
		$id = $_REQUEST['id'];
		$idsolicitud = $_REQUEST['idsolicitud'];

		$query 	= "	SELECT a.id, a.cedula, a.nombre, a.apellidopaterno, a.apellidomaterno, b.fecha_solicitud,
					c.descripcion AS estado
					FROM pacientes a
					INNER JOIN solicitudes b ON b.idpaciente = a.id
					INNER JOIN estados c ON c.id = b.estatus
					WHERE b.estatus IN (1,5,31) 
					AND (b.fecha_cita < CURDATE() OR b.fecha_cita IS NULL)
					AND a.id = '$id' AND b.id = '$idsolicitud'
					ORDER BY b.fecha_solicitud DESC LIMIT 1
				";
				//echo $query;
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){			
			$resultado = array(
				'id'				=>	$row['id'], 
				'cedula'			=>	$row['cedula'], 
				'nombre'			=>	$row['nombre'], 
				'apellidopaterno' 	=>	$row['apellidopaterno'], 			
				'apellidomaterno' 	=>	$row['apellidomaterno'], 	
				'estado' 			=>	$row['estado'],
				'fechasolicitud' 	=>	$row['fecha_solicitud']
			);
		}
		
		echo json_encode($resultado);
	}
	
	function guardarHabilitacionJunta(){
		global $mysqli;
	
		$idregionales = (!empty($_REQUEST['idregionales']) ? $_REQUEST['idregionales'] : '');
		$nroresolucion = (!empty($_REQUEST['nroresolucion']) ? $_REQUEST['nroresolucion'] : '');
		//$nrojunta = (!empty($_REQUEST['nrojunta']) ? $_REQUEST['nrojunta'] : '');
		$fechaevaluacion = (!empty($_REQUEST['fechaevaluacion']) ? $_REQUEST['fechaevaluacion'] : '');
		$fecharesolucion = (!empty($_REQUEST['fecharesolucion']) ? $_REQUEST['fecharesolucion'] : '');
		$idsmedicos = (!empty($_REQUEST['idsmedicos']) ? $_REQUEST['idsmedicos'] : '');
		$idspacientes = (!empty($_REQUEST['idspacientes']) ? $_REQUEST['idspacientes'] : '');
		$txtmedicos = '';
		$txtpacientes = '';
		$querymedicos = '';
		$querypacientes = '';
		
		$query = "INSERT INTO habilitacionjuntas (idregionales,nroresolucion, fechaevaluacion, fecharesolucion) 
				  VALUES (".$idregionales.",'".$nroresolucion."','".$fechaevaluacion."','".$fecharesolucion."')";
 		//echo $query;
		$result = $mysqli->query($query);
	
		if($result == true){
			$id = $mysqli->insert_id; 
			if(!empty($idsmedicos)) {
				
				foreach($idsmedicos as $idmedico) {
					$sqlm = "INSERT INTO habilitacionjuntasmedicos (idhabilitacionjuntas, idmedicos) 
							 VALUES ('".$id."','".$idmedico."')";
					$resultm = $mysqli->query($sqlm);
					if($resultm == true){
						$nombresmedicos = getValor('CONCAT(nombre," ",apellido)','medicos',$idmedico,'');
						$cedulasmedicos = getValor('cedula','medicos',$idmedico,'');
						$txtmedicos .= $nombresmedicos.' C??dula:'.$cedulasmedicos;
						$txtmedicos .= ',';
						$querymedicos .= $sqlm."/";	
					}
				}
				$txtmedicos = rtrim($txtmedicos, ',');											
				$querymedicos = rtrim($querymedicos, '/');											
			}
	
			if(!empty($idspacientes)) {
				
				foreach($idspacientes as $idpaciente) {
					$sqlp = "INSERT INTO habilitacionjuntaspacientes (idhabilitacionjuntas, idpacientes) 
							 VALUES ('".$id."','".$idpaciente."')";
					$resultp = $mysqli->query($sqlp);
					if($resultp == true){
						$nombrespacientes = getValor('CONCAT(nombre," ",apellidopaterno," ",apellidomaterno)','pacientes',$idpaciente,'');
						$cedulaspacientes = getValor('cedula','pacientes',$idpaciente,'');
						$txtpacientes .= $nombrespacientes.' C??dula:'.$cedulaspacientes;
						$txtpacientes .= ',';
						$querypacientes .= $sqlp."/";	
					}					
				}
				$txtpacientes = rtrim($txtpacientes, ',');											
				$querypacientes = rtrim($querypacientes, '/');											
			}
			
			if($resultm == true && $resultp == true) {
				
				//Guardar en tabla control de n??meros de resoluci??n
				$sql = " INSERT INTO modulos_nroresolucion (idmodulo, nro_resolucion, tipo)
						 VALUES(".$id.",'".$nroresolucion."','Habilitaci??n de juntas')";
				$mysqli->query($sql);
				
				
				$campos = array(
					'Regional' 				=> getValor('nombre','regionales',$idregionales,''),
					'N??mero de resoluci??n' 	=> $nroresolucion,
					//'N??mero de junta' 		=> $nrojunta, 
					'Fecha' 				=> $fecha, 
					'M??dicos' 				=> $txtmedicos,  
					'Pacientes' 			=> $txtpacientes,  
				);
				
				$querybitacora = $query." / ".$querymedicos." / ".$querypacientes;
				nuevoRegistro('Habilitaci??n de junta evaluadora','Habilitaci??n de junta evaluadora',$id,$campos,$querybitacora);
				
				echo true;
			} else {
				echo false;
			}
		}
	}

	function actualizarHabilitacionJunta() {
		global $mysqli;
	
		$idhabilitacionjunta = (!empty($_REQUEST['idhabilitacionjunta']) ? $_REQUEST['idhabilitacionjunta'] : '');
		$idregionales = (!empty($_REQUEST['idregionales']) ? $_REQUEST['idregionales'] : '');
		$nroresolucion = (!empty($_REQUEST['nroresolucion']) ? $_REQUEST['nroresolucion'] : '');
		//$nrojunta = (!empty($_REQUEST['nrojunta']) ? $_REQUEST['nrojunta'] : '');
		$fechaevaluacion = (!empty($_REQUEST['fechaevaluacion']) ? $_REQUEST['fechaevaluacion'] : '');
		$fecharesolucion = (!empty($_REQUEST['fecharesolucion']) ? $_REQUEST['fecharesolucion'] : '');
		$idsmedicos = (!empty($_REQUEST['idsmedicos']) ? $_REQUEST['idsmedicos'] : array());
		$idspacientes = (!empty($_REQUEST['idspacientes']) ? $_REQUEST['idspacientes'] : array());
		$txtmedicos = '';
		$txtpacientes = '';
		$querymedicos = '';
		$querypacientes = '';
		
		$camposold = getRegistroSQL("	SELECT b.nombre AS 'Regional', a.nroresolucion AS 'N??mero de resoluci??n',
										a.fechaevaluacion AS 'Fecha para la evaluaci??n',
										a.fecharesolucion AS 'Fecha para la resoluci??n',
										(
											SELECT GROUP_CONCAT(CONCAT(b.nombre,' ',b.apellido,' C??dula:',b.cedula)) 
											FROM habilitacionjuntasmedicos a
											INNER JOIN medicos b ON b.id = a.idmedicos
											WHERE idhabilitacionjuntas = ".$idhabilitacionjunta."
										) AS 'M??dicos',
										(
											SELECT GROUP_CONCAT(CONCAT(b.nombre,' ',b.apellidopaterno,' ',b.apellidomaterno,' C??dula:',b.cedula)) 
											FROM habilitacionjuntaspacientes a 
											INNER JOIN pacientes b ON b.id = a.idpacientes 
											WHERE idhabilitacionjuntas = ".$idhabilitacionjunta." 
										) AS 'Pacientes' 
										FROM habilitacionjuntas a 
										INNER JOIN regionales b ON b.id = a.idregionales 
										WHERE a.id = ".$idhabilitacionjunta."
									");
		
		// Actualizar informaci??n de la junta
		$query = "UPDATE habilitacionjuntas
				  SET idregionales = ".$idregionales.", nroresolucion = '".$nroresolucion."',
				  fechaevaluacion = '".$fechaevaluacion."', fecharesolucion = '".$fecharesolucion."'
				  WHERE id = ".$idhabilitacionjunta;
		
		$result = $mysqli->query($query);
	
		if($result == true) {
			// Eliminar m??dicos y pacientes existentes
			$queryM = "DELETE FROM habilitacionjuntasmedicos WHERE idhabilitacionjuntas = ".$idhabilitacionjunta;
			$resultm = $mysqli->query($queryM);
	
			$queryP = "DELETE FROM habilitacionjuntaspacientes WHERE idhabilitacionjuntas = ".$idhabilitacionjunta;
			$resultp = $mysqli->query($queryP);
	
			// Insertar m??dicos y pacientes nuevos
			if(!empty($idsmedicos)) {
				foreach($idsmedicos as $idmedico) {
					// Validar que el idmedico sea un valor num??rico
					if(is_numeric($idmedico)) {
						$sqlm = "INSERT INTO habilitacionjuntasmedicos (idhabilitacionjuntas, idmedicos)
								 VALUES ('".$idhabilitacionjunta."','".$idmedico."')";
						$resultm = $mysqli->query($sqlm);
						if($resultm == true){
							$nombresmedicos = getValor('CONCAT(nombre," ",apellido)','medicos',$idmedico,'');
							$cedulasmedicos = getValor('cedula','medicos',$idmedico,'');
							$txtmedicos .= $nombresmedicos.' C??dula:'.$cedulasmedicos;
							$txtmedicos .= ',';
							$querymedicos .= $sqlm."/";		
						}
					}
				}
				$txtmedicos = rtrim($txtmedicos, ',');	
				$querymedicos = rtrim($querymedicos, '/');	
			}
	
			if(!empty($idspacientes)) {
				foreach($idspacientes as $idpaciente) {
					// Validar que el idpaciente sea un valor num??rico
					if(is_numeric($idpaciente)) {
						$sqlp = "INSERT INTO habilitacionjuntaspacientes (idhabilitacionjuntas, idpacientes)
								 VALUES ('".$idhabilitacionjunta."','".$idpaciente."')";
						$resultp = $mysqli->query($sqlp);
						if($resultp == true){
							$nombrespacientes = getValor('CONCAT(nombre," ",apellidopaterno," ",apellidomaterno)','pacientes',$idpaciente,'');
							$cedulaspacientes = getValor('cedula','pacientes',$idpaciente,'');
							$txtpacientes .= $nombrespacientes.' C??dula:'.$cedulaspacientes;
							$txtpacientes .= ',';
							$querypacientes .= $sqlp."/";	
						}
					}
				}
				$txtpacientes = rtrim($txtpacientes, ',');	
				$querypacientes = rtrim($querypacientes, '/');	
			}
			if($resultm == true && $resultp == true) {
				
				$camposnew = array( 
					'Regional' 	=> getValor('nombre','regionales',$idregionales,''),
					'N??mero de resoluci??n' => $nroresolucion,
					//'N??mero de junta' => $nrojunta,
					'Fecha para la evaluaci??n' => $fechaevaluacion, 
					'Fecha para la resoluci??n' => $fecharesolucion, 
					'M??dicos' 	=> $txtmedicos,  
					'Pacientes' => $txtpacientes,  
				); 
				
				$querybitacora = $query." / ".$queryM." / ".$queryP." / ".$querymedicos." / ".$querypacientes;
				actualizarRegistro('Habilitaci??n de junta evaluadora','Habilitaci??n de junta evaluadora',$idhabilitacionjunta,$camposold,$camposnew,$querybitacora);
				
				echo true;
			} else {
				echo false;
			}
		}
	
	}
	function getUltimoNrojunta(){
		global $mysqli;	
		
		$idregionales = (!empty($_REQUEST['idregionales']) ? $_REQUEST['idregionales'] : '');
		$tipo = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
		
		if($tipo == 'creacion'){
			$consultar_count = 'COUNT(id) + 1';
		}else{
			$consultar_count = 'COUNT(id)';
		}
		$sql = "SELECT ";
		$sql .= $consultar_count;
		$sql .=" AS ultimonrojunta 
				FROM habilitacionjuntas 
				WHERE idregionales = ".$idregionales." AND CURDATE() = DATE(creation_time) ";
		
		$result = $mysqli->query($sql);
		if($row = $result->fetch_assoc()){
			if($row['ultimonrojunta'] != ''){
				$nrojunta = $row['ultimonrojunta'];
			}else{
				$nrojunta = 1;
			}
		}
		echo $nrojunta;
	}
	
	function getHabilitacionJuntas () {
		global $mysqli;
	
		$id = $_REQUEST['id'];
	
		$query = "SELECT a.id, a.idregionales, a.nroresolucion, a.fechaevaluacion, a.fecharesolucion, b.idmedicos, c.idpacientes,
				  d.cedula AS cedulamedico,CONCAT(d.nombre,' ',d.apellido) AS medico, 
				  e.cedula AS cedulapaciente, CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno) AS paciente, f.nombre AS especialidad,
				  ( SELECT COUNT(*) FROM habilitacionjuntas t2 WHERE t2.id <= a.id AND t2.idregionales = a.idregionales ) AS posicion,
				  h.descripcion AS estado, g.fecha_solicitud
				  FROM habilitacionjuntas a
				  INNER JOIN habilitacionjuntasmedicos b ON a.id = b.idhabilitacionjuntas
				  INNER JOIN habilitacionjuntaspacientes c ON a.id = c.idhabilitacionjuntas
				  INNER JOIN medicos d ON d.id = b.idmedicos
				  INNER JOIN pacientes e ON e.id = c.idpacientes
				  LEFT JOIN especialidades f ON f.id = d.especialidad
				  LEFT JOIN solicitudes g ON g.idpaciente = e.id
				  LEFT JOIN estados h ON h.id = g.estatus
				  WHERE a.id = ".$id." ORDER BY g.id DESC";
	
		$result = $mysqli->query($query);
	
		$resultado = array();
	
		while ($row = $result->fetch_assoc()) {
			$resultado['idregionales'] = $row['idregionales'];
			$resultado['nroresolucion'] = $row['nroresolucion'];
			$resultado['nrojunta'] = $row['posicion'];
			$resultado['fechaevaluacion'] = $row['fechaevaluacion'];
			$resultado['fecharesolucion'] = $row['fecharesolucion'];
	
			$medicos = array();
			$pacientes = array();
			$medicos_ids = array();
			$pacientes_ids = array();
			
			 // Agregar todos los m??dicos y pacientes relacionados con la junta al array
			while ($row) {
				if (!in_array($row['idmedicos'], $medicos_ids)) {
					$medico = array(
						'id' => $row['idmedicos'],
						'cedula' => $row['cedulamedico'],
						'medico' => $row['medico'],
						'especialidad' => $row['especialidad'],
					);
					$medicos[] = $medico;
					$medicos_ids[] = $row['idmedicos'];
				}
				if (!in_array($row['idpacientes'], $pacientes_ids)) {
					$paciente = array(
						'id' => $row['idpacientes'],
						'cedula' => $row['cedulapaciente'],
						'paciente' => $row['paciente'],
						'estado' => $row['estado'],
						'fechasolicitud' => $row['fecha_solicitud'],
					);
					$pacientes[] = $paciente;
					$pacientes_ids[] = $row['idpacientes'];
				}
				$row = $result->fetch_assoc(); // Avanzar al siguiente registro
			}

			$resultado['medicos'] = $medicos;
			$resultado['pacientes'] = $pacientes;
		}
	
		echo json_encode($resultado);
	}
	
	function asignarCodigoResolucion(){
		global $mysqli;
		
		$idsolicitud = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$idregionales = (!empty($_REQUEST['idregionales']) ? $_REQUEST['idregionales'] : '');
		$year = date('Y');
		
		$sqlP = " SELECT nombre AS regional FROM regionales WHERE id = ".$idregionales;
		$rtaP = $mysqli->query($sqlP);
		if($rowP = $rtaP->fetch_assoc()){
			$regional = $rowP['regional'];
			
			($regional == 'Panam?? Oeste') ?	$inicReg = 'HAB-PAO' : $inicReg = 'HAB-' . strtoupper(substr($rowP['regional'], 0, 3));
			
			$sqlR = " SELECT nro_resolucion FROM modulos_nroresolucion WHERE SUBSTRING(nro_resolucion,1,7) = '".$inicReg."' ORDER BY id DESC LIMIT 1";
			
			$rtaR = $mysqli->query($sqlR);
			if($rowR = $rtaR->fetch_assoc()){
				
				$parts = explode("-",$rowR['nro_resolucion']);
				$numero = $parts[2]+1;
				$numero = str_pad($numero, 5, "0", STR_PAD_LEFT);
				$result = array(
					implode("-", array_slice($parts, 0, 2)),
					$numero
				);
				$codigo = implode("-", $result);
				$codigo = $codigo. '-' . $year; 
			}else{
				$numero = 1;
				$numero = str_pad($numero, 5, "0", STR_PAD_LEFT);
				$codigo = $inicReg."-".$numero. '-' . $year;
			}
			echo $codigo;
		}
	}
	
	function eliminar(){
		global $mysqli;
		
		$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "DELETE FROM habilitacionjuntas WHERE id = ".$id;
		$result = $mysqli->query($query);
		
		if($result == true){
			
			$sqlM = "DELETE FROM habilitacionjuntasmedicos WHERE idhabilitacionjuntas = ".$id;
			$resultM = $mysqli->query($sqlM);
			$sqlP = "DELETE FROM habilitacionjuntaspacientes WHERE idhabilitacionjuntas = ".$id;
			$resultP = $mysqli->query($sqlP);
			
			$querybitacora = $query.' / '.$sqlM.' / '.$sqlP;
			eliminarRegistro('Habilitaci??n de junta evaluadora','SolicitHabilitaci??n de junta evaluadora','Junta evaluadora '.$id,$id,$querybitacora);
			
			echo true;
		}else{
			echo false;
		}
	}
?>