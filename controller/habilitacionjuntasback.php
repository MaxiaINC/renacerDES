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
		
		$query = "  SELECT a.id, a.nroresolucion, a.nrojunta, a.fechaevaluacion, a.fecharesolucion,
					GROUP_CONCAT(DISTINCT CONCAT(d.nombre,' ',d.apellido) SEPARATOR ', ') AS medicos, 
					GROUP_CONCAT(DISTINCT CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno, ' (', e.id, ')') SEPARATOR ', ') AS pacientes,
					f.nombre AS regional
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
					if ($column == 'nrojunta') {
						$column = 'a.nrojunta';
						$whereF[]=" $column = '".$campo."' ";
					}	
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
		 
		$query .= " GROUP BY a.id, a.nroresolucion, a.nrojunta, a.fechaevaluacion ";
	
		$query .= " ORDER BY a.id DESC LIMIT $start, $length ";
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
			
			$boton_verresolucion = '<a class="dropdown-item text-info boton-resolucion" data-id="'.$row['id'].'"><i class="fas fa-file-pdf mr-2"></i>Ver resolución</a>';
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
				'nrojunta'	 		=>	$row['nrojunta'],
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

		$query 	= "	SELECT id, cedula, nombre, apellidopaterno, apellidomaterno
					FROM pacientes
					WHERE id = '$id'
				";
		//debug($query);
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){			
			$resultado = array(
				'id'				=>	$row['id'], 
				'cedula'			=>	$row['cedula'], 
				'nombre'			=>	$row['nombre'], 
				'apellidopaterno' 	=>	$row['apellidopaterno'], 			
				'apellidomaterno' 	=>	$row['apellidomaterno'], 			
			);
		}
		
		echo json_encode($resultado);
	}
	
	function guardarHabilitacionJunta(){
		global $mysqli;
	
		$idregionales = (!empty($_REQUEST['idregionales']) ? $_REQUEST['idregionales'] : '');
		$nroresolucion = (!empty($_REQUEST['nroresolucion']) ? $_REQUEST['nroresolucion'] : '');
		$nrojunta = (!empty($_REQUEST['nrojunta']) ? $_REQUEST['nrojunta'] : '');
		$fechaevaluacion = (!empty($_REQUEST['fechaevaluacion']) ? $_REQUEST['fechaevaluacion'] : '');
		$fecharesolucion = (!empty($_REQUEST['fecharesolucion']) ? $_REQUEST['fecharesolucion'] : '');
		$idsmedicos = (!empty($_REQUEST['idsmedicos']) ? $_REQUEST['idsmedicos'] : '');
		$idspacientes = (!empty($_REQUEST['idspacientes']) ? $_REQUEST['idspacientes'] : '');
		$txtmedicos = '';
		$txtpacientes = '';
		$querymedicos = '';
		$querypacientes = '';
		
		$query = "INSERT INTO habilitacionjuntas (idregionales,nroresolucion, nrojunta, fechaevaluacion, fecharesolucion) 
				  VALUES (".$idregionales.",'".$nroresolucion."','".$nrojunta."','".$fechaevaluacion."','".$fecharesolucion."')";
 
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
						$txtmedicos .= $nombresmedicos.' Cédula:'.$cedulasmedicos;
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
						$txtpacientes .= $nombrespacientes.' Cédula:'.$cedulaspacientes;
						$txtpacientes .= ',';
						$querypacientes .= $sqlp."/";	
					}					
				}
				$txtpacientes = rtrim($txtpacientes, ',');											
				$querypacientes = rtrim($querypacientes, '/');											
			}
			
			if($resultm == true && $resultp == true) {
				
				//Guardar en tabla control de números de resolución
				$sql = " INSERT INTO solicitudes_nroresolucion (idsolicitud, nro_resolucion, tipo)
						 VALUES(".$id.",'".$nroresolucion."','Habilitación de juntas')";
				$mysqli->query($sql);
				
				
				$campos = array(
					'Regional' 				=> getValor('nombre','regionales',$idregionales,''),
					'Número de resolución' 	=> $nroresolucion,
					'Número de junta' 		=> $nrojunta, 
					'Fecha' 				=> $fecha, 
					'Médicos' 				=> $txtmedicos,  
					'Pacientes' 			=> $txtpacientes,  
				);
				
				$querybitacora = $query." / ".$querymedicos." / ".$querypacientes;
				nuevoRegistro('Habilitación de junta evaluadora','Habilitación de junta evaluadora',$id,$campos,$querybitacora);
				
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
		$nrojunta = (!empty($_REQUEST['nrojunta']) ? $_REQUEST['nrojunta'] : '');
		$fechaevaluacion = (!empty($_REQUEST['fechaevaluacion']) ? $_REQUEST['fechaevaluacion'] : '');
		$fecharesolucion = (!empty($_REQUEST['fecharesolucion']) ? $_REQUEST['fecharesolucion'] : '');
		$idsmedicos = (!empty($_REQUEST['idsmedicos']) ? $_REQUEST['idsmedicos'] : array());
		$idspacientes = (!empty($_REQUEST['idspacientes']) ? $_REQUEST['idspacientes'] : array());
		$txtmedicos = '';
		$txtpacientes = '';
		$querymedicos = '';
		$querypacientes = '';
		
		$camposold = getRegistroSQL("	SELECT b.nombre AS 'Regional', a.nroresolucion AS 'Número de resolución', a.nrojunta AS 'Número de junta',
										a.fechaevaluacion AS 'Fecha para la evaluación',
										a.fecharesolucion AS 'Fecha para la resolución',
										(
											SELECT GROUP_CONCAT(CONCAT(b.nombre,' ',b.apellido,' Cédula:',b.cedula)) 
											FROM habilitacionjuntasmedicos a
											INNER JOIN medicos b ON b.id = a.idmedicos
											WHERE idhabilitacionjuntas = ".$idhabilitacionjunta."
										) AS 'Médicos',
										(
											SELECT GROUP_CONCAT(CONCAT(b.nombre,' ',b.apellidopaterno,' ',b.apellidomaterno,' Cédula:',b.cedula)) 
											FROM habilitacionjuntaspacientes a 
											INNER JOIN pacientes b ON b.id = a.idpacientes 
											WHERE idhabilitacionjuntas = ".$idhabilitacionjunta." 
										) AS 'Pacientes' 
										FROM habilitacionjuntas a 
										INNER JOIN regionales b ON b.id = a.idregionales 
										WHERE a.id = ".$idhabilitacionjunta."
									");
		
		// Actualizar información de la junta
		$query = "UPDATE habilitacionjuntas
				  SET idregionales = ".$idregionales.", nroresolucion = '".$nroresolucion."', nrojunta = '".$nrojunta."',
				  fechaevaluacion = '".$fechaevaluacion."', fecharesolucion = '".$fecharesolucion."'
				  WHERE id = ".$idhabilitacionjunta;
		
		$result = $mysqli->query($query);
	
		if($result == true) {
			// Eliminar médicos y pacientes existentes
			$queryM = "DELETE FROM habilitacionjuntasmedicos WHERE idhabilitacionjuntas = ".$idhabilitacionjunta;
			$resultm = $mysqli->query($queryM);
	
			$queryP = "DELETE FROM habilitacionjuntaspacientes WHERE idhabilitacionjuntas = ".$idhabilitacionjunta;
			$resultp = $mysqli->query($queryP);
	
			// Insertar médicos y pacientes nuevos
			if(!empty($idsmedicos)) {
				foreach($idsmedicos as $idmedico) {
					// Validar que el idmedico sea un valor numérico
					if(is_numeric($idmedico)) {
						$sqlm = "INSERT INTO habilitacionjuntasmedicos (idhabilitacionjuntas, idmedicos)
								 VALUES ('".$idhabilitacionjunta."','".$idmedico."')";
						$resultm = $mysqli->query($sqlm);
						if($resultm == true){
							$nombresmedicos = getValor('CONCAT(nombre," ",apellido)','medicos',$idmedico,'');
							$cedulasmedicos = getValor('cedula','medicos',$idmedico,'');
							$txtmedicos .= $nombresmedicos.' Cédula:'.$cedulasmedicos;
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
					// Validar que el idpaciente sea un valor numérico
					if(is_numeric($idpaciente)) {
						$sqlp = "INSERT INTO habilitacionjuntaspacientes (idhabilitacionjuntas, idpacientes)
								 VALUES ('".$idhabilitacionjunta."','".$idpaciente."')";
						$resultp = $mysqli->query($sqlp);
						if($resultp == true){
							$nombrespacientes = getValor('CONCAT(nombre," ",apellidopaterno," ",apellidomaterno)','pacientes',$idpaciente,'');
							$cedulaspacientes = getValor('cedula','pacientes',$idpaciente,'');
							$txtpacientes .= $nombrespacientes.' Cédula:'.$cedulaspacientes;
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
					'Número de resolución' => $nroresolucion,
					'Número de junta' => $nrojunta,
					'Fecha para la evaluación' => $fechaevaluacion, 
					'Fecha para la resolución' => $fecharesolucion, 
					'Médicos' 	=> $txtmedicos,  
					'Pacientes' => $txtpacientes,  
				); 
				
				$querybitacora = $query." / ".$queryM." / ".$queryP." / ".$querymedicos." / ".$querypacientes;
				actualizarRegistro('Habilitación de junta evaluadora','Habilitación de junta evaluadora',$idhabilitacionjunta,$camposold,$camposnew,$querybitacora);
				
				echo true;
			} else {
				echo false;
			}
		}
	
	}
	function getUltimoNrojunta(){
		global $mysqli;	
		$sql = "SELECT MAX(nrojunta) + 1 AS ultimonrojunta FROM habilitacionjuntas";
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
	
		$query = "SELECT a.id, a.idregionales, a.nroresolucion, a.nrojunta, a.fechaevaluacion, a.fecharesolucion, b.idmedicos, c.idpacientes,
				  d.cedula AS cedulamedico,CONCAT(d.nombre,' ',d.apellido) AS medico, 
				  e.cedula AS cedulapaciente, CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno) AS paciente, f.nombre AS especialidad
				  FROM habilitacionjuntas a
				  LEFT JOIN habilitacionjuntasmedicos b ON a.id = b.idhabilitacionjuntas
				  LEFT JOIN habilitacionjuntaspacientes c ON a.id = c.idhabilitacionjuntas
				  LEFT JOIN medicos d ON d.id = b.idmedicos
				  LEFT JOIN pacientes e ON e.id = c.idpacientes
				  LEFT JOIN especialidades f ON f.id = d.especialidad
				  WHERE a.id = ".$id."";
	
		$result = $mysqli->query($query);
	
		$resultado = array();
	
		while ($row = $result->fetch_assoc()) {
			$resultado['idregionales'] = $row['idregionales'];
			$resultado['nroresolucion'] = $row['nroresolucion'];
			$resultado['nrojunta'] = $row['nrojunta'];
			$resultado['fechaevaluacion'] = $row['fechaevaluacion'];
			$resultado['fecharesolucion'] = $row['fecharesolucion'];
	
			$medicos = array();
			$pacientes = array();
			$medicos_ids = array();
			$pacientes_ids = array();
			
			 // Agregar todos los médicos y pacientes relacionados con la junta al array
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
						'paciente' => $row['paciente']
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
		
		$sqlP = " SELECT nombre AS regional FROM regionales WHERE id = ".$idregionales;
		$rtaP = $mysqli->query($sqlP);
		if($rowP = $rtaP->fetch_assoc()){
			$regional = $rowP['regional'];
			
			($regional == 'Panamá Oeste') ?	$inicReg = 'PAO' : $inicReg = strtoupper(substr($rowP['regional'], 0, 3));
			
			$sqlR = " SELECT nro_resolucion FROM solicitudes_nroresolucion WHERE SUBSTRING(nro_resolucion,1,3) = '".$inicReg."' ORDER BY id DESC LIMIT 1";
			$rtaR = $mysqli->query($sqlR);
			if($rowR = $rtaR->fetch_assoc()){ 
				$arrRes = explode("-",$rowR['nro_resolucion']);
				$numero = $arrRes[1]+1;
				$numero = str_pad($numero, 5, "0", STR_PAD_LEFT);
				$codigo = $arrRes[0]."-".$numero;
			}else{
				$numero = 1;
				$numero = str_pad($numero, 5, "0", STR_PAD_LEFT);
				$codigo = $inicReg."-".$numero;
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
			eliminarRegistro('Habilitación de junta evaluadora','SolicitHabilitación de junta evaluadora','Junta evaluadora '.$id,$id,$querybitacora);
			
			echo true;
		}else{
			echo false;
		}
	}
?>