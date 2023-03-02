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
		case "agregarExpediente": 
			  agregarExpediente();
			  break;
		case "actualizarEstado": 
			  actualizarEstado();
			  break;
		case "agregarAuditores": 
			  agregarAuditores();
			  break;
		case "getDocumentos": 
			  getDocumentos();
			  break; 
		case "abrirSolicitudes":
			  abrirSolicitudes();
			  break;
		case "actualizarRegional":
			  actualizarRegional();
			  break;
		case "getDatosPaciente":
			  getDatosPaciente();
			  break;
		case "actualizarHallazgos":
			  actualizarHallazgos();
			  break;
		case "getInformes":
			  getInformes();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}
	
	function cargar(){
		
		global $mysqli;		
		$draw = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    //$orderByColumnIndex  = $_REQUEST['order'][0]['0'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	    $orderBy = 0;//$_REQUEST['id'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	    $orderType = "DESC";//$_REQUEST['order'][0]['dir']; // ASC or DESC
	    $start   = (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length   = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$nivel = $_SESSION['nivel_sen'];
		$user_id = $_SESSION['user_id_sen'];
		$regional_usu 	= getValor('regional','usuarios',$user_id);
		
		$query = "  SELECT a.id, b.id AS idpacientes, b.cedula,LEFT(CONCAT(b.nombre,' ',b.apellidopaterno,' ',b.apellidomaterno),60) as paciente, 
					b.expediente, c.descripcion AS estado, GROUP_CONCAT(d.nombre) AS auditor, 
					a.idestados, a.idauditores, f.nombre AS regional
					FROM auditorias a
					LEFT JOIN pacientes b ON b.id = a.idpacientes 
					LEFT JOIN estados c ON c.id = a.idestados
					LEFT JOIN usuarios d ON FIND_IN_SET(d.id,a.idauditores)
					LEFT JOIN solicitudes e ON e.idpaciente = b.id
					INNER JOIN regionales f ON f.id = e.regional
					WHERE 1 = 1 "; 
					
		if($regional_usu != 'Todos' && $regional_usu != '' && $regional_usu != null){
			$query .= " AND f.nombre IN ('".$regional_usu."') ";
		}
		/* FILTRO GLOBAL */
		$sWhere = "";		
		if ( isset($_REQUEST['search']['value']) && $_REQUEST['search']['value'] != "" ) {
			$arrbus = explode(' ', $_REQUEST['search']['value']);
			
			//$sWhere = " HAVING GROUP_CONCAT(d.nombre) LIKE '%".addslashes( $_REQUEST['search']['value'] )."%' ";			
			//AGREGA LAS COLUMNAS
			$aColumns = "b.expediente|b.cedula|LEFT(CONCAT(b.nombre,' ',b.apellidopaterno,' ',b.apellidomaterno),60)|c.descripcion|d.nombre";
			$aColumns = explode("|", $aColumns);
		
			$sWhere .= " AND  ("; 
			$longitud = count($arrbus);
			for($j=0; $j<$longitud; $j++){
				for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
					$sWhere .= $aColumns[$i]." LIKE '%".addslashes( $arrbus[$j] )."%' OR ";		
				}
			}			
			
			$sWhere = substr_replace( $sWhere, "", -3 ); 
			$sWhere .= ')'; 
		}
		
		$hayFiltros = 0;
		$whereF = array();
		$where 	= '';
		$groupF = 0;
		$groupC = '';
		
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);
				
				if ($column == 'expediente') {
					$column = 'b.expediente';
					$whereF[]=" $column = '".$campo."' ";
				}
				if ($column == 'cedula') {
					$column = 'b.cedula';
					$whereF[]=" $column = '".$campo."' ";
				}
				if ($column == 'regional') {
					$column = 'f.nombre';
					$whereF[]=" $column = '".$campo."' ";
				}
				if ($column == 'paciente') {
					$match = str_replace(' ',' +',$campo);
					$whereF[]=" MATCH (b.nombre,b.apellidopaterno,b.apellidomaterno) AGAINST ('+".$match."' IN BOOLEAN MODE) ";
				}				
				if ($column == 'estatus') {
					$column = 'c.descripcion';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}
				if ($column == 'auditor') {
					$column = 'd.nombre';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}				
				$hayFiltros++;
			}
		}
		if ($hayFiltros > 0 /* && $groupF != 1 */)
			$where = " AND ".implode(" AND " , $whereF)." ";
		
		$query  .= "$where "; 
		$query  .= " $sWhere "; 
		//echo $query;
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$recordsTotal = $result->num_rows;
		 
		$query .= " GROUP BY a.id ";
		//Filtro de Group By
		/* if($groupF == 1){
			$query .= " HAVING GROUP_CONCAT(d.nombre) LIKE '%".$groupC."%'";
		} */ 
		$query .= " ORDER BY CAST(b.expediente AS UNSIGNED) DESC LIMIT $start, $length ";
		
		$resultado = array();	
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		
		while($row = $result->fetch_assoc()){
			
			$tieneEvidencias   = '';
			$rutaE 		= '../auditorias/'.$row['id'];
			if (is_dir($rutaE)) { 
			  if ($dhE = opendir($rutaE)) { 
				$num = 1;
				while (($fileE = readdir($dhE)) !== false) { 
					if ($fileE != "." && $fileE != ".." && $fileE != ".quarantine" && $fileE != ".tmb" && $fileE != "comentarios"){ 
						$nombrefile = $fileE;
						if($num > 1){
							$tieneEvidencias .= ", ";
						}
						$tieneEvidencias .= "<a href='".dirname($_SERVER['PHP_SELF'])."/".$rutaE."/".$fileE."' target='_blank'>".$nombrefile."</a>";
						$num++;
					}
				} 
				closedir($dhE); 
			  } 
			}
			$tieneEvidencias != '' ? $color = 'success': $color = 'info'; 
			
			$boton_estados = '<a class="dropdown-item text-info boton-estados" data-id="'.$row['id'].'" data-idestados="'.$row['idestados'].'"><i class="fas fa-layer-group mr-2"></i>Actualizar estado</a>';
			$boton_adjuntos = '<a class="dropdown-item text-'.$color.' boton-adjuntos" data-id="'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Adjuntos</a>';
			$boton_auditores = '<a class="dropdown-item text-info boton-auditores" data-id="'.$row['id'].'" data-idauditores="'.$row['idauditores'].'"><i class="fa fa-users mr-2"></i>Actualizar auditores</a>';
			//$boton_regional = '<a class="dropdown-item text-info boton-regional" data-id="'.$row['id'].'" data-idregionales="'.$row['idregionales'].'"><i class="fa fa-users mr-2"></i>actualizar regional</a>';
			$boton_documentos = '<a class="dropdown-item text-info boton-documentos" data-id="'.$row['id'].'" data-idpacientes="'.$row['idpacientes'].'" data-expediente="'.$row['expediente'].'"><i class="fa fa-file mr-2"></i>Ver documentos</a>';
			$boton_informes = '<a class="dropdown-item text-info boton-informes" data-id="'.$row['id'].'" data-idpacientes="'.$row['idpacientes'].'" data-expediente="'.$row['expediente'].'"><i class="fa fa-file mr-2"></i>Registro de hallazgos</a>';
			
			$botones = '';			
			$botones .="	
				$boton_estados
				$boton_adjuntos
				$boton_auditores
				$boton_documentos
				$boton_informes
			";
			if ($row['estado']!='17') {
				//$botones .= "$boton_aprobar";	
			}
			
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
				'expediente'		=> 	$row['expediente'],
				'acciones' 			=>	$acciones,
				'cedula'	 		=>	$row['cedula'],
				'paciente' 			=>	$row['paciente'],
				'regional'			=>	$row['regional'],
				'estatus'			=>	$row['estado'],
				'auditor'			=>  $row['auditor']
			);
		}
		if(empty($resultado)){
			$resultado['data'][] = array(
				'id'=>'', 'expediente' => '', 'acciones'=>'','cedula'=>'','paciente'=>'','regional'=>'', 'estatus'=>'', 'auditor'=>''
			);
		}
		//BITACORA
		//guardarRegistroG('Solicitudes', 'Cargar solicitud');
		
		$resultado['draw'] = intval($draw);
		$resultado['recordsTotal'] = intval($recordsTotal);
		$resultado['recordsFiltered'] = intval($recordsTotal);
		echo json_encode($resultado);
	}
	
	function agregarExpediente(){
		global $mysqli;
		$expediente  = (!empty($_REQUEST['expediente']) ? $_REQUEST['expediente'] : '');
		$idauditores  = (!empty($_REQUEST['idauditores']) ? $_REQUEST['idauditores'] : '');
		$estadonuevo = 22;
		
		$sql =" SELECT id FROM pacientes WHERE expediente = '".$expediente."'";
		$rta = $mysqli->query($sql);
		$total = $rta->num_rows;
		
		if($total>0){ //Evitar duplicado
			if($row = $rta->fetch_assoc()){
				$idpacientes = $row['id'];
				
				/* $sqlA = "SELECT id FROM auditorias WHERE idpacientes = ".$idpacientes;
				$rtaA = $mysqli->query($sqlA);		
				$totalA = $rtaA->num_rows;
				
				if($totalA>0){
					echo 3;
				}else{ */
					$query = " INSERT INTO auditorias (idpacientes,expediente,idestados,idauditores) 
								VALUES (".$idpacientes.",'".$expediente."',".$estadonuevo.",'".$idauditores."')";
					$result = $mysqli->query($query);
					$result == true ? $respuesta = 1 : $respuesta = 0;
					
					if($respuesta == 1){
						
						$idauditorias = $mysqli->insert_id;
						$campos = array(
							'Expediente' => $expediente, 
							'Estado' 	 => getValor('descripcion','estados',$estadonuevo,''),
							'Auditores' => getValoresA("nombre",'usuarios',$idauditores,'auditor')
						); 
						nuevoRegistro('Auditorías','Auditorías',$idauditorias,$campos,$query);
						
					}
					echo $respuesta;	
				//}				
			}
		}else{
			echo 2;
		} 		
	} 
	
	function actualizarEstado() 
	{
		global $mysqli;		
		$idauditorias  = (!empty($_REQUEST['idauditorias']) ? $_REQUEST['idauditorias'] : '');
		$idestados  = (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : '');
		$idestadosFinalizadoAuditoria = 25;
		
		$camposold = getRegistroSQL("	SELECT b.descripcion AS 'Estado'
										FROM auditorias a 
										INNER JOIN estados b ON b.id = a.idestados  
										WHERE a.id = ".$idauditorias." ");
											
		$query = "UPDATE auditorias SET idestados = ".$idestados." WHERE id = ".$idauditorias;	
		$result = $mysqli->query($query);
		
		if($result==true){
			
			if($idestados == $idestadosFinalizadoAuditoria){
				
				//Colocar auditoría como finalizada
				$sql = " UPDATE auditorias SET fechacierre = CURTIME() WHERE id = ".$idauditorias."";
				$mysqli->query($sql);
			}
			
			$camposnew = array(
				'Estado' => getValor('descripcion','estados',$idestados,'')
			);	
			actualizarRegistro('Auditorías','Auditorías',$idauditorias,$camposold,$camposnew,$query);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function agregarAuditores() 
	{
		global $mysqli;		
		$idauditorias  = (!empty($_REQUEST['idauditorias']) ? $_REQUEST['idauditorias'] : '');
		$idauditores  = (!empty($_REQUEST['idauditores']) ? $_REQUEST['idauditores'] : '');
		
		$camposold = getRegistroSQL("	SELECT GROUP_CONCAT(b.nombre) AS 'Auditores'
										FROM auditorias a 
										LEFT JOIN usuarios b ON FIND_IN_SET(b.id,a.idauditores)  
										WHERE a.id = ".$idauditorias." ");
										
		$query = "UPDATE auditorias SET idauditores = '".$idauditores."' WHERE id = ".$idauditorias;	
		$result = $mysqli->query($query);
		$result == true ? $response = 1 : $response = 0;
		if($response == 1){
			$camposnew = array( 
				'Auditores' => getValoresA("nombre",'usuarios',$idauditores,'auditor'), 
			);	
			actualizarRegistro('Auditorías','Auditorías',$idauditorias,$camposold,$camposnew,$query);
		}
		echo $response;
	}
	
	function actualizarRegional() 
	{
		global $mysqli;		
		$idauditorias  = (!empty($_REQUEST['idauditorias']) ? $_REQUEST['idauditorias'] : '');
		$idregionales  = (!empty($_REQUEST['idregionales']) ? $_REQUEST['idregionales'] : '');
		
		$camposold = getRegistroSQL("	SELECT b.nombre AS 'Regional'
										FROM auditorias a 
										INNER JOIN regionales b ON b.id = a.idregionales  
										WHERE a.id = ".$idregionales." ");
											
		$query = "UPDATE auditorias SET idregionales = ".$idregionales." WHERE id = ".$idauditorias;	
		$result = $mysqli->query($query);
		
		if($result==true){
			$camposnew = array(
				'Regional' => getValor('nombre','regionales',$idestados,'')
			);	
			actualizarRegistro('Auditorías','Auditorías',$idauditorias,$camposold,$camposnew,$query);
			echo 1;
		}else{
			echo 0;
		}
	}	
	
	function actualizarHallazgos() 
	{
		global $mysqli;		
		$idauditorias  = (!empty($_REQUEST['idauditorias']) ? $_REQUEST['idauditorias'] : '');
		$hallazgos  = (!empty($_REQUEST['hallazgos']) ? $_REQUEST['hallazgos'] : '');
		
		$camposold = getRegistroSQL("	SELECT a.hallazgos AS 'Hallazgos'
										FROM auditorias a   
										WHERE a.id = ".$idauditorias." ");
											
		$query = "UPDATE auditorias SET hallazgos = '".$hallazgos."' WHERE id = ".$idauditorias;	
		//echo $query;
		$result = $mysqli->query($query);
		
		if($result==true){
			$camposnew = array(
				'Hallazgos' => $hallazgos
			);	
			actualizarRegistro('Auditorías','Auditorías',$idauditorias,$camposold,$camposnew,$query);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function getDocumentos(){
		
		global $mysqli;		 
		$idpacientes  = (!empty($_REQUEST['idpacientes']) ? $_REQUEST['idpacientes'] : '');
		$expediente  = (!empty($_REQUEST['expediente']) ? $_REQUEST['expediente'] : '');
		
		$query = "  SELECT a.id, a.id AS idsolicitud, 'Solicitud' AS tipo, a.fecha_solicitud AS fecha, a.idpaciente, '' AS archivo 
					FROM solicitudes a
					WHERE a.idpaciente = ".$idpacientes." 
					UNION
					SELECT a.id, a.idsolicitud, 'Evaluación' AS tipo, b.fecha_cita AS fecha, a.idpaciente, '' AS archivo
					FROM evaluacion a
					INNER JOIN solicitudes b ON b.id = a.idsolicitud
					WHERE a.idpaciente = ".$idpacientes."
					UNION 
					SELECT a.id, a.idsolicitud, 'Resolución' AS tipo, '' AS fecha, '' AS idpaciente, c.archivo
					FROM resolucion a 
					INNER JOIN solicitudes b ON b.id = a.idsolicitud
					LEFT JOIN resolucionemision c ON c.idsolicitud = b.id
					WHERE b.idpaciente = ".$idpacientes."
					UNION 
					SELECT a.id, a.idsolicitud, 'Negatoria' AS tipo, '' AS fecha, '' AS idpaciente, '' AS archivo
					FROM negatorias a 
					INNER JOIN solicitudes b ON b.id = a.idsolicitud
					WHERE b.idpaciente = ".$idpacientes."
					";
					//echo $query;
					/* SELECT a.id, a.idsolicitud, 'Resolución' AS tipo, '' AS fecha, '' AS idpaciente, '' AS archivo
					FROM resolucion a 
					INNER JOIN solicitudes b ON b.id = a.idsolicitud
					WHERE b.idpaciente = ".$idpacientes."
					UNION */
					/* SELECT a.id, a.idsolicitud, 'Resolución Bitácora' AS tipo, '' AS fecha, '' AS idpaciente, a.archivo
					FROM resolucionemision a 
					INNER JOIN solicitudes b ON b.id = a.idsolicitud
					WHERE b.idpaciente = ".$idpacientes."
					UNION  */
		$result = $mysqli->query($query);
		$totalReg = $result->num_rows;
		
		$host= $_SERVER["HTTP_HOST"];
		
		while($row = $result->fetch_assoc()){
			$tipo = $row['tipo'];
			
			if($tipo == 'Solicitud'){
				$url = "https://" . $host . "/senadisdes/reporte/imprimirsolicitud.php?id=" . $row['id'];
			}elseif($tipo == 'Evaluación'){
				$url = "https://" . $host . "/senadisdes/reporte/imprimirprotocolo.php?sol=" . $row['idsolicitud'] . "&ev=" . $row['id']; 
			}elseif($tipo == 'Resolución'){
				if(empty($row['archivo'])){
					$url = "https://" . $host . "/senadisdes/reporte/verresolucion.php?id=" . $row['idsolicitud'];	
				}else{
					$url = "https://" . $host . "/senadisdes/solicitudes/" . $row['idsolicitud'] . "/" . $row['archivo']; 	
				}				
			}elseif($tipo == 'Negatoria'){
				$url = "https://" . $host . "/senadisdes/reporte/imprimirnegatoria.php?id=" . $row['idsolicitud'];
			}  
			$resultado['data'][] = array(
				'id' 	=>	$row['id'],
				'tipo'	=> 	$tipo,
				'fecha' => $row['fecha'],
				'ver' => '<a href="' . $url . '" target="_blank">Ver documento</a>'
			);
		}
		
		if(empty($resultado)){
			$resultado['data'][] = array(
				'id'=>'', 'tipo' => '', 'fecha_solicitud'=>'', 'fecha_cita' => '', 'ver' => ''
			);
		}
		
		$resultado['draw'] = intval($draw);
		$resultado['recordsTotal'] = intval($recordsTotal);
		$resultado['recordsFiltered'] = intval($recordsTotal);
		echo json_encode($resultado);
	}
	
	function abrirSolicitudes() {
		$idauditorias 	= (!empty($_REQUEST['idauditorias']) ? $_REQUEST['idauditorias'] : '');
		//$_SESSION['incidente_cor'] = $incidente;
		//$_SESSION['comentario_cor'] = '';
		
		$myPathInc = '../auditorias';
		$target_pathInc = utf8_decode($myPathInc);
		if (!file_exists($target_pathInc)) {
			mkdir($target_pathInc, 0777);
		}
		
		$myPathI = '../auditorias/'.$idauditorias;
		$target_pathI = utf8_decode($myPathI);
		if (!file_exists($target_pathI)) {
			mkdir($target_pathI, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		//RUTA
		$Path = '/../auditorias/'.$idauditorias.'/';
		$Path2 = '/../auditorias/auditoria/';
		//debugL('$Path: '.$incidente);
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');
		echo "l1_". $hash;
	} 

	function getDatosPaciente(){
		
		global $mysqli;		 
		$expediente  = (!empty($_REQUEST['expediente']) ? $_REQUEST['expediente'] : '');
		
		$query = "  SELECT cedula, CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) AS nombre, 
					(SELECT COUNT(*) FROM auditorias WHERE expediente = '".$expediente."' AND idestados != 25) AS asociado_a_auditoria
					FROM pacientes 
					WHERE expediente = '".$expediente."'"; 
		$result = $mysqli->query($query);		
		if($row = $result->fetch_assoc()){
			$resultado['data'] = array(
				'cedula' => $row['cedula'],
				'nombre' => $row['nombre'],
				'asociado_a_auditoria' => $row['asociado_a_auditoria']
			);
		}
		echo json_encode($resultado);
	}
	
	function getInformes(){
		
		global $mysqli;		 
		$idauditorias  = (!empty($_REQUEST['idauditorias']) ? $_REQUEST['idauditorias'] : '');
		
		$query = "  SELECT hallazgos
					FROM auditorias 
					WHERE id = '".$idauditorias."'"; 
		$result = $mysqli->query($query);		
		if($row = $result->fetch_assoc()){
			$resultado['data'] = array(
				'hallazgos' => $row['hallazgos']
			);
		}
		echo json_encode($resultado);
	}
?>