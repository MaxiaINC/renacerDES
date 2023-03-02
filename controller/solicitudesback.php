<?php
    include("conexion.php");
    sessionrestore();
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "cargar": //
			  cargar();
			  break;
		case "get": 
			  get();
			  break;
		case "get_calendario": 
			  get_calendario();
			  break;
		case "getdatossolicitud": 
			  getdatossolicitud();
			  break;
		case "guardar_solicitud": 
			  guardar_solicitud();
			  break;
		case "editar_solicitud": //
			  editar_solicitud();
			  break;
		case "update_solicitud": 
			  update_solicitud();
			  break;
		case "update": 
			  update();
			  break;
		case "abriradjuntos":
			  abriradjuntos();
			  break;
		case "eliminar": //
			  eliminar();
			  break;
		case "aprobar": 
			  aprobar();
			  break;
		case "getsolicitudpaciente": 
			  getsolicitudpaciente();
			  break;
		case "getDatosAcompanantes": 
			  getDatosAcompanantes();
			  break;
		case "getsolicitudpacienteid": 
			  getsolicitudpacienteid();
			  break;
		case "getIdSolicitudpaciente": 
			  getIdSolicitudpaciente();
			  break;
		case "existe":
			  existe();
			  break;
	    case "subirfoto":
			  subirfoto();
			  break;
	    case "agendar_solicitud":
			  agendar_solicitud();
			  break;
		case "editar_agendar_solicitud":
			  editar_agendar_solicitud();
			  break;
		case "cargar_cita":
			  cargar_cita();
			  break;
		case "get_solicitud":
			  get_solicitud();
			  break;
		case "get_pacienteporsolicitud":
			  get_pacienteporsolicitud();
			  break;
		case "guardar_resolucion":
			  guardar_resolucion();
			  break;
		case "get_resolucion":
			  get_resolucion();
			  break;
		case "editar_resolucion":
			  editar_resolucion();
			  break;
		case "guardar_negatoria":
			  guardar_negatoria();
			  break;
		case "get_negatoria":
			  get_negatoria();
			  break;
		case "editar_negatoria":
			  editar_negatoria();
			  break;
		case "exportarExcel":
			  exportarExcel();
			  break;
		case "comentarios":
			  comentarios();
			  break;
		case "agregarComentario":
			  agregarComentario();
			  break;
		case "eliminarComentario":
			  eliminarComentario();
			  break;
		case "abrirSolicitudes":
			  abrirSolicitudes();
			  break;
		case "editarJunta":
			  editarJunta();
			  break;
		case "editarNoAsistio":
			  editarNoAsistio();
			  break;
		case "listarCertificados":
			  listarCertificados();
			  break;
		case "asignarCodigoResolucion":
			  asignarCodigoResolucion();
			  break; 
		case "cambiarEstado":
			  cambiarEstado();
			  break;  
		default:
			  echo "{failure:true}";
			  break;
	}
	
	function cargar(){
		//Actualiza las solicitudes si el paciente no asistió a la cita
		//editarNoAsistio();
		
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
		
		$query = "  SELECT s.id,IFNULL(s.cedula,p.cedula) AS cedula,LEFT(CONCAT(p.nombre,' ',p.apellidopaterno,' ',p.apellidomaterno),60) as paciente, 
					s.fecha_solicitud as fecha_solicitud, s.fecha_cita as fecha_cita,r.nombre as regional,s.regional as idregional, 
					e.descripcion as estatus, s.estatus as estado, s.observacionesestados, s.iddiscapacidad as iddiscapacidad, 
					f.nombre as discapacidad, LEFT(s.condicionsalud,60) as condicionsalud, p.expediente, s.idpaciente,
					s.reconsideracion, s.apelacion
					FROM solicitudes s
					LEFT JOIN pacientes p ON p.id = s.idpaciente
					LEFT JOIN regionales r ON r.id = s.regional
					LEFT JOIN estados e ON e.id = s.estatus
					LEFT JOIN discapacidades f ON f.id = s.iddiscapacidad
					WHERE 1 = 1 ";
		if($regional_usu != 'Todos' && $regional_usu != '' && $regional_usu != null){
			$query .= " AND r.nombre IN ('".$regional_usu."') ";
		}		
		/* FILTRO GLOBAL */
		$sWhere = "";		
		if ( isset($_REQUEST['search']['value']) && $_REQUEST['search']['value'] != "" ) {
			$arrbus = explode(' ', $_REQUEST['search']['value']);
			$arrtam = sizeof($arrbus);
			if($arrtam > 1){
				//AGREGA LAS COLUMNAS
			$aColumns = "p.nombre,p.apellidopaterno,p.apellidomaterno,r.nombre,s.regional,e.descripcion,s.observacionesestados,f.nombre,s.condicionsalud";
			$aColumns = explode(",", $aColumns);
		
				$sWhere = " AND  (";
				$longitud = count($arrbus);
				for($j=0; $j<$longitud; $j++){
					for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
						$sWhere .= $aColumns[$i]." LIKE '%".addslashes( $arrbus[$j] )."%' OR ";
					}
				}				
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}else{
				//AGREGA LAS COLUMNAS
			$aColumns = "s.id, p.cedula, p.nombre, p.apellidopaterno, p.apellidomaterno, s.fecha_solicitud, s.fecha_cita, r.nombre,
						 s.regional, e.descripcion, s.estatus, s.observacionesestados, s.iddiscapacidad, f.nombre, s.condicionsalud,
						 p.expediente";
			$aColumns = explode(",", $aColumns);
		
				$sWhere = " AND  (";
				for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
					$sWhere .= $aColumns[$i]." LIKE '%".addslashes( $_REQUEST['search']['value'] )."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}			
		}
		
		$hayFiltros = 0;
		$whereF 	= array();
		$where 		= '';
		for($i=0 ; $i<count($_REQUEST['columns']);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);
				
				if ($column == 'expediente') {
					$column = 'p.expediente';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}
				if ($column == 'cedula') {
					$column = 'p.cedula';
					$whereF[]=" $column LIKE '%".$campo."%' ";
					
				}
				if ($column == 'paciente') {
					//$column = "p.nombre";
					//$whereF[]=" $column LIKE '%".$campo."%' ";
					$match = str_replace(' ',' +',$campo);
					$whereF[]=" MATCH (p.nombre,p.apellidopaterno,p.apellidomaterno) AGAINST ('+".$match."' IN BOOLEAN MODE) ";
				}
				if ($column == 'fecha_solicitud') {
					$column = 's.fecha_solicitud';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}
				if ($column == 'fecha_cita') {
					$column = 's.fecha_cita';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}
				if ($column == 'regional') {
					$column = 'r.nombre';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}
				if ($column == 'estatus') { 
					if($campo == 'Certificó' || $campo == 'certificó'){
						$column = 'e.descripcion';
						$whereF[]=" $column IN ('Certificó','Pendiente por imprimir','Impreso') ";
					}else{ 
						$column = 'e.descripcion';
						$whereF[]=" $column LIKE '".$campo."%' ";	
					} 
				}
				if ($column == 'observacionesestados') {
					$column = 's.observacionesestados';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}
				if ($column == 'condicionsalud') {
					$column = 's.condicionsalud';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}
				if ($column == 'discapacidad') {
					$column = 'f.nombre';
					$whereF[]=" $column LIKE '%".$campo."%' ";
				}				
				$hayFiltros++;
			}
		}
		if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $whereF)." ";
		
		$query  .= "$where ";
		$result = $mysqli->query($query);
		
		/* if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		} */
		$recordsTotal = $result->num_rows;
		
		//$query  .= " ORDER BY CAST(p.expediente AS UNSIGNED) ASC LIMIT $start, $length ";
		$query  .= " ORDER BY CAST(p.expediente AS UNSIGNED) DESC LIMIT $start, $length ";
		//debugL($query,'LISTADOSOLDEBUGL');
		$resultado = array();	
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		
		while($row = $result->fetch_assoc()){
			
			$reconsideracion = $row['reconsideracion'];
			$apelacion = $row['apelacion'];
			
			$boton_calendario = '';
			$boton_evaluacion = '';
			$boton_eliminar = '';
			$boton_usuario = '';
			$boton_adjuntos = '';
			$boton_imprimir = '';
			$boton_certificado = '';
			$boton_negatoria = '';
			$boton_reconsideracion = '';
			$boton_apelacion = '';
			$idsolicitud = $row['id'];
			$boton_carnet ='';
			 
			if($row['fecha_cita'] == '0000-00-00 00:00:00'){
				$fecha_cita = '';
			}else{
				$fecha_cita = $row['fecha_cita'];
			}

			$tieneEvidencias   = '';
			$rutaE 		= '../solicitudes/'.$row['id'];
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
			
			if($tieneEvidencias != ''){
				$color = 'text-success';
				/*
				$id = $row['id'];
				$idusuario 			= $_SESSION['user_id_sen'];
				$estatusanterior = $row['estado'];
				if ($row['iddiscapacidad'] == '2' || $row['iddiscapacidad'] == '3') {
					if ($row['estado'] == '15') {
						$querystatus = "UPDATE solicitudes SET estatus = '16' WHERE id = '$id';";
						if($resultstatus = $mysqli->query($querystatus)){
							$row['estado'] = '16';
							$row['estatus'] = 'Pre aprobado';
							$queryhistorico = "INSERT INTO historicosolicitudes (id,idsolicitud,usuario,fecha,estadoanterior,estadoactual) VALUES (null,'$id','$idusuario',CURDATE(),'$estatusanterior','16')";
							// echo $queryhistorico;die();
							$resulthistorico = $mysqli->query($queryhistorico);
						}
					}
				}
				*/
			}else{
				$color = 'text-info';
			}
			$queryevaluaciones = " SELECT e.id FROM evaluacion e WHERE e.idsolicitud = '".$idsolicitud."' LIMIT 1; ";
			$resultevaluaciones = $mysqli->query($queryevaluaciones);
			$count = $resultevaluaciones->num_rows;			
			if ($count != 0) {
				$rowevaluaciones = $resultevaluaciones->fetch_assoc();
				if($_SESSION['nivel_sen'] !=  '6' && $_SESSION['nivel_sen'] !=  '11'){
					$boton_evaluacion = '<a class="dropdown-item text-info boton-evaluacion" data-id="'.$rowevaluaciones['id'].'" data-idpaciente="'.$row['idpaciente'].'" data-idsolicitud="'.$row['id'].'"><i class="fas fa-list mr-2"></i>Ver evaluación</a>';
				} 
			}
			$queryauditorias = "SELECT id FROM auditorias WHERE idpacientes = ".$row['idpaciente']." AND (fechacierre IS NULL OR DATE(fechacierre) < CURDATE()) LIMIT 1 ";
			$resultauditorias = $mysqli->query($queryauditorias);
			$resultauditorias->num_rows > 0 ? $auditoria = 1 : $auditoria = 0;
			
			//Reconsideración
			if ($_SESSION['nivel_sen'] == '1'|| $_SESSION['nivel_sen'] == '15') {
				if($row['estado'] == '28'){ //Reconsideración de negatoria generada
					if($reconsideracion == 0 || $reconsideracion == 1){
						$boton_reconsideracion = '<a class="dropdown-item text-info boton-reconsideracion" data-id="'.$row['id'].'"><i class="fas fa-file mr-2"></i></i>Reconsideración</a>';	
					}
				}
				if($row['estado'] == '5'){
					if($reconsideracion == 2){
						$boton_apelacion = '<a class="dropdown-item text-info boton-apelacion" data-id="'.$row['id'].'"><i class="fas fa-file mr-2"></i></i>Apelación</a>';	
					}
				}
			}

			if ($_SESSION['nivel_sen'] == '1'|| $_SESSION['nivel_sen'] == '9'|| $_SESSION['nivel_sen'] == '15') {
				$boton_eliminar = '<a class="dropdown-item text-danger boton-eliminar" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
				$boton_calendario = '<a class="dropdown-item text-info boton-agendar" data-id="'.$row['id'].'"><i class="fas fa-calendar mr-2"></i>Agendar</a>';
				//$boton_adjuntos = '<a class="dropdown-item '.$color.' boton-adjuntos" data-id="'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Adjuntar documentos</a>';
				if($row['estado'] == '2'){
					$boton_calendario = '<a class="dropdown-item text-success boton-agendar-editar" data-id="'.$row['id'].'" data-idregional="'.$row['idregional'].'"><i class="fas fa-calendar mr-2"></i>Agendar</a>';
				}
				if($row['estado'] == '4'){
					$boton_negatoria = '<a class="dropdown-item text-info boton-negatoria" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i></i>Negatoria</a>';
				}
			}
			$boton_adjuntos = '<a class="dropdown-item '.$color.' boton-adjuntos" data-id="'.$row['id'].'"><i class="fas fa-camera mr-2"></i>Adjuntar documentos</a>';
			if ($_SESSION['nivel_sen'] == '10' || $_SESSION['nivel_sen'] == '12' || $_SESSION['nivel_sen'] == '13' || $_SESSION['nivel_sen'] == '14' || $_SESSION['nivel_sen'] == '15') {
				$boton_calendario = '<a class="dropdown-item text-info boton-agendar" data-id="'.$row['id'].'"><i class="fas fa-calendar mr-2"></i>Agendar</a>';
				if($row['estado'] == '2'){
					$boton_calendario = '<a class="dropdown-item text-success boton-agendar-editar" data-id="'.$row['id'].'" data-idregional="'.$row['idregional'].'"><i class="fas fa-calendar mr-2"></i>Agendar</a>';
				}
			}
			if ($_SESSION['nivel_sen'] == '2' || $_SESSION['nivel_sen'] == '12' || $_SESSION['nivel_sen'] == '14' || $_SESSION['nivel_sen'] == '15' || $_SESSION['nivel_sen'] == '16') {
				if($row['estado'] == '4'){
					$boton_negatoria = '<a class="dropdown-item text-info boton-negatoria" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i></i>Negatoria</a>';
				}
			}
			if ($_SESSION['nivel_sen'] == '1'|| $_SESSION['nivel_sen'] == '9'||$_SESSION['nivel_sen'] == '11'|| $_SESSION['nivel_sen'] == '6'|| $_SESSION['nivel_sen'] == '15') {
				$boton_usuario ='<a class="dropdown-item text-info boton-paciente" data-id="'.$row['id'].'"><i class="fas fa-user mr-2"></i>Consultar usuario</a>';
			}
			if ($_SESSION['nivel_sen'] == '1' || $_SESSION['nivel_sen'] == '14' || $_SESSION['nivel_sen'] == '15') {
				if($row['estado'] == '24' || $row['estado'] == '26'){
					$boton_carnet = '<a class="dropdown-item text-info boton-carnet" data-id="'.$row['id'].'" data-idpaciente="'.$row['idpaciente'].'"><i class="fas fa-file mr-2"></i>Imprimir carnet</a>';
				}
			}
			if ($_SESSION['nivel_sen'] == '1' || $_SESSION['nivel_sen'] == '15' || $_SESSION['nivel_sen'] == '16') {
				$boton_imprimir = '<a class="dropdown-item text-info boton-imprimir" data-id="'.$row['id'].'"><i class="fas fa-file mr-2"></i>Solicitud</a>';
			}
			$boton_editar = '<a class="dropdown-item text-info boton-editar" data-id="'.$row['id'].'"><i class="fas fa-pencil mr-2"></i>Editar solicitud</a>';
			
			
			if($_SESSION['nivel_sen'] == '1' || $_SESSION['nivel_sen'] == '2' || $_SESSION['nivel_sen'] == '12' || $_SESSION['nivel_sen'] == '14' || $_SESSION['nivel_sen'] == '15' || $_SESSION['nivel_sen'] == '16'){
				if($row['estado'] == '3' || $row['estado'] == '24' || $row['estado'] == '26'){
					$boton_certificado = '<a class="dropdown-item text-info boton-emitir-certificado" data-id="'.$row['id'].'" data-idregional="'.$row['idregional'].'" data-iddiscapacidad= "'.$row['iddiscapacidad'].'"><i class="fas fa-drivers-license mr-2"></i>Emitir resolución</a>';
				}
			}
			if ($_SESSION['nivel_sen'] == '1' || $_SESSION['nivel_sen'] == '15') {
				$boton_historial = '<a class="dropdown-item text-info boton-historial" data-id="'.$row['id'].'"><i class="fas fa-file-excel mr-2"></i>Historial</a>';
			}
			
			$botones = '';			
			$botones .="	
				$boton_calendario
				$boton_evaluacion
				$boton_usuario
				$boton_adjuntos
				$boton_imprimir
				$boton_carnet
				$boton_certificado
				$boton_negatoria
				$boton_reconsideracion
				$boton_apelacion
				$boton_historial
				$boton_eliminar
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
			
			$idevaluacion = getId('id','evaluacion',$row['id'],'idsolicitud');
			$resultado['data'][] = array(
				'id' 				=>	$row['id'],
				'expediente'		=> 	$row['expediente'],
				'acciones' 			=>	$acciones,
				'cedula'	 		=>	$row['cedula'],
				'paciente' 			=>	$row['paciente'],
				'fecha_solicitud'	=>	$row['fecha_solicitud'],
				'fecha_cita'		=>	$fecha_cita,
				'regional'			=>	$row['regional'],
				'estatus'	 		=>	$row['estatus'],
				'discapacidad'		=>	$row['discapacidad'],
				'estado'	 		=>	$row['estado'],
				'observacionesestados'=>	$row['observacionesestados'],
				'condicionsalud'	=>	$row['condicionsalud'],
				'auditoria'	=>		$auditoria
			);
		}
		if(empty($resultado)){
			$resultado['data'][] = array(
				'id'=>'', 'expediente' => '', 'acciones'=>'','cedula'=>'','paciente'=>'','fecha_solicitud'=>'',
				'fecha_cita'=>'','regional'=>'','estatus'=>'', 'estado'=>'','observacionesestados'=>'','condicionsalud'=>'', 
				'discapacidad'=>'', 'auditoria'=>'',
			);
		}
		//BITACORA
		//guardarRegistroG('Solicitudes', 'Cargar solicitud');
		
		$resultado['draw'] = intval($draw);
		$resultado['recordsTotal'] = intval($recordsTotal);
		$resultado['recordsFiltered'] = intval($recordsTotal);
		echo json_encode($resultado);
	}
	
	function getdatossolicitud(){
		global  $mysqli;
		$idsolicitud = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$_SESSION['idsolicitud']=$id;
		$query = "  SELECT s.regional, s.foto, s.fecha_solicitud, s.fecha_cita, s.estatus AS idestatus, s.tipoacompanante, 
					g.descripcion AS estatus, s.idacompanante, s.condicionsalud, s.observacionesestados, s.idpaciente, 
					d.nombre AS discapacidad, s.iddiscapacidad, s.tipo AS tiposolicitud, s.reconsideracion, s.apelacion
					FROM solicitudes s 
					INNER JOIN discapacidades d ON d.id = s.iddiscapacidad
					INNER JOIN estados g ON s.estatus = g.id
					WHERE s.id = '".$idsolicitud."' ";
		$result = $mysqli->query($query);
		$data= '';
		while($row = $result->fetch_assoc()){			
			$queryP = " SELECT direccion FROM pacientes WHERE id = '".$row['idpaciente']."' ";
			$resultP = $mysqli->query($queryP); 
			if($rowP = $resultP->fetch_assoc()){
				$query_direccion = "SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero
									FROM direccion d LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
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
				'regional'			=> $row['regional'],
				'foto'				=> $row['foto'],
				'fecha_solicitud' 	=> $row['fecha_solicitud'],
				'fecha_cita' 		=> $row['fecha_cita'],
				'iddiscapacidad' 	=> $row['iddiscapacidad'],
				'tiposolicitud' 	=> $row['tiposolicitud'],
				'discapacidad' 		=> $row['discapacidad'],
				'idacompanante' 	=> $row['idacompanante'],
				'tipoacompanante' 	=> $row['tipoacompanante'],
				'idestatus' 		=> $row['idestatus'],
				'estatus' 			=> $row['estatus'],
				'condicionsalud' 	=> $row['condicionsalud'],
				'observaciones' 	=> $row['observacionesestados'],
				'direccion'			=> $direccion,			   
				'reconsideracion' 	=> $row['reconsideracion'],
				'apelacion' 		=> $row['apelacion']
			);
		}
		echo json_encode($data);
		//echo $direccion;
	}
	
	function get_solicitud(){
		global $mysqli;
		$idsolicitud = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$query = "	SELECT s.id AS id, CONCAT(p.nombre,' ',p.apellidopaterno, ' ',p.apellidomaterno) AS paciente, s.fecha_cita AS fecha, 
					p.id as idpaciente, s.iddiscapacidad as iddiscapacidad,
					GROUP_CONCAT(CONCAT(m.id,'|',m.nombre,' ',m.apellido,'|', REPLACE(e.nombre,',',' / ')))AS medicos, s.sala AS sala, 
					r.nombre AS regional, d.nombre AS discapacidad, s.idpaciente as idp, p.expediente
					FROM solicitudes s 
					LEFT JOIN pacientes p ON p.id = s.idpaciente 
					LEFT JOIN discapacidades d ON d.id = s.iddiscapacidad
					LEFT JOIN regionales r ON r.id = s.regional
					LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta) 
					LEFT JOIN especialidades e ON e.id = m.especialidad 
					WHERE s.id = ".$idsolicitud." ";
		//debug($query);
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			$queryP = " SELECT direccion FROM pacientes WHERE id = '".$row['idp']."' ";
			$resultP = $mysqli->query($queryP); 
			if($rowP = $resultP->fetch_assoc()){
				$query_direccion = "SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero
									FROM direccion d LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
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
				'id' 			=> $row['id'],
				'idpaciente'	=> $row['idpaciente'],
				'paciente'		=> $row['paciente'],
				'fecha' 		=> $row['fecha'],
				'medicos' 		=> $row['medicos'],
				'discapacidad' 	=> $row['discapacidad'],
				'iddiscapacidad'=> $row['iddiscapacidad'],
				'sala' 			=> $row['sala'],
				'regional' 		=> $row['regional'],
				'expediente' 	=> $row['expediente'],
				'direccion'		=> $direccion		   
			);
		}		
		echo json_encode($data);
	}
	
	function getDatosAcompanantes(){
		global $mysqli;
		$idacompanante = $_REQUEST['idacompanante'];
		$query = "	SELECT a.id, a.tipo_documento, a.cedula, CONCAT(a.nombre, ' ', a.apellido) AS nombre, a.nombre AS nombre_ac, 
					a.apellido AS apellido_ac, a.fecha_nac, a.sexo, a.estado_civil, a.telefono, a.celular, a.correo, a.nacionalidad, dir.provincia, dir.distrito, dir.corregimiento, dir.area, di.urbanizacion, di.calle, di.edificio, di.numero, a.sentencia, a.juzgado, 
					a.circuito_judicial, a.distrito_judicial, a.direccion, a.modo_tutor
					FROM `acompanantes` a 
					LEFT JOIN direccion di ON di.id = a.direccion 
					LEFT JOIN direcciones dir ON dir.id = di.iddireccion 
					WHERE a.id = '".$idacompanante."' ";
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
				'area_ac' 		=> $row['area'], 
				'urbanizacion' 	=> $row['urbanizacion'], 
				'calle' 		=> $row['calle'], 
				'edificio' 		=> $row['edificio'], 
				'numero' 		=> $row['numero'],
				'sentencia' 	=> $row['sentencia'],
				'juzgado' 		=> $row['juzgado'],
				'circuito_judicial' => $row['circuito_judicial'],
				'distrito_judicial' => $row['distrito_judicial'],
				'modo_tutor' 		=> $row['modo_tutor'],
				'direccion' 		=> $row['direccion']
			);
		}
		echo json_encode($resultado);
	}

	function agendar_solicitud(){
		global $mysqli;
		
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		$idsolicitud 	= (!empty($data['id']) ? $data['id'] : '');
		$idpaciente 	= getValor('idpaciente','solicitudes',$idsolicitud);
		$correo 		= getValor('correo','pacientes',$idpaciente);
		$fecha_cita  	= (!empty($data['agendamiento-fecha_cita']) ? $data['agendamiento-fecha_cita'] : '');
		$fecha_cita 	.= ':00';
		$discapacidad	= (!empty($_REQUEST['discapacidad']) ? $_REQUEST['discapacidad'] : 0);
		$iddiscapacidad = getValor('id','discapacidades',$discapacidad);
		$sala			= (!empty($data['agendamiento-sala']) ? $data['agendamiento-sala'] : '');
		$especialistas	= (!empty($data['agendamiento-idespecialistas']) ? $data['agendamiento-idespecialistas'] : 0);
		$hoy 			= date("Y-m-d H:i:s");
		
		$query = "	UPDATE solicitudes SET iddiscapacidad = '".$iddiscapacidad."', fecha_cita = '".$fecha_cita."', 
					junta = '".$especialistas."', sala = '".$sala."', estatus = '2'
					WHERE id = '".$idsolicitud."' ";
		//debugL('agendar_solicitud: '.$query);
		if($result = $mysqli->query($query)){
			$tipo = 'agendamiento';
			if($fecha_cita > $hoy){
				if($correo != '' && $correo != '0'){
					//enviar_correo($correo,$tipo,$idsolicitud);
				}
			}
			echo 1;
		}else{
			echo $query;
		}
	}
	
	function editar_solicitud(){	//Agendar solicitud
		global $mysqli;
		
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		$idsolicitud 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$discapacidad	= (!empty($_REQUEST['discapacidad']) ? $_REQUEST['discapacidad'] : 0);
		$iddiscapacidad = getId('id','discapacidades',$discapacidad,'nombre');
		$fecha_cita  	= (!empty($data['agendamiento-fecha_cita']) ? $data['agendamiento-fecha_cita'] : '');
		$fecha_cita 	.= ':00';
		$sala			= (!empty($data['agendamiento-sala']) ? $data['agendamiento-sala'] : '');
		//$especialistas	= (!empty($data['agendamiento-idespecialistas']) ? $data['agendamiento-idespecialistas'] : 0);
		//$idespecialistas = implode(',',$especialistas); //COMBO MULTIPLE
		$especialistas	= (!empty($data['medicos']) ? $data['medicos'] : 0);
		$estadoold 		= getValor('estatus','solicitudes',$idsolicitud,''); 
		
		$iddiscapacidad == '' ? $iddiscapacidad = 0 : $iddiscapacidad = $iddiscapacidad;
		
		/* $camposold = getRegistroSQL("	SELECT b.nombre AS 'Discapacidad', a.fecha_cita AS 'Fecha cita',
										GROUP_CONCAT(c.nombre,' ',c.apellido) AS 'Junta', a.sala AS 'Consultorio'
										FROM solicitudes a	
										LEFT JOIN discapacidades b ON b.id = a.iddiscapacidad
										LEFT JOIN medicos c ON FIND_IN_SET(c.id,a.junta)
										WHERE a.id = ".$idsolicitud."
										"); */
										
		$query = "  UPDATE solicitudes SET iddiscapacidad = '".$iddiscapacidad."', sala = '".$sala."', junta = '".$especialistas."', 
					fecha_cita = '".$fecha_cita."', fechacambioestado = NOW()
					 ";
		//if($idestado == 1){
			$query .= " , estatus = 2 ";
		//}
		$query .= " WHERE id = '".$idsolicitud."' ";
		//debugL('editar_solicitud: '.$query);
		if($result = $mysqli->query($query)){ 
			//Crear registro en solicitudes_estados
			$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
						VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), '".$estadoold."', '2') "; 
						//echo $queryE;
			$mysqli->query($queryE);
			
			$campos = array(
				'Discapacidad' 		=> getValor('nombre','discapacidades',$iddiscapacidad,''),
				'Fecha cita' 		=> $fecha_cita,
				'Junta' 			=> getValoresA("nombre,' ',apellido",'medicos',$especialistas,'medico'), 
				'Consultorio' 		=> $sala 
			); 
			nuevoRegistro('Agenda','Agenda',$idsolicitud,$campos,$query);
			echo 1;
		}else{
			echo $query;
		}
	}
	
	function editar_agendar_solicitud(){
		global $mysqli;
		$idsolicitud 	= (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$idpaciente 	= getValor('idpaciente','solicitudes',$idsolicitud);
		$correo 		= getValor('correo','pacientes',$idpaciente);
		$fecha_cita  	= (!empty($_REQUEST['fecha_cita']) ? $_REQUEST['fecha_cita'] : '');
		$discapacidad	= (!empty($_REQUEST['discapacidad']) ? $_REQUEST['discapacidad'] : 0);
		$sala			= (!empty($_REQUEST['sala']) ? $_REQUEST['sala'] : '');
		$especialistas	= (!empty($_REQUEST['especialistas']) ? $_REQUEST['especialistas'] : 0);
		$hoy 			= date("Y-m-d H:i:s");
		$query ="  UPDATE solicitudes SET iddiscapacidad = '$discapacidad', fecha_cita = '$fecha_cita', junta = '$especialistas', sala = '$sala', estatus ='2'
					WHERE id = '$idsolicitud';
		";
		if($result = $mysqli->query($query)){
			$tipo = 'agendamiento';
			if($fecha_cita > $hoy){
				if($correo != '' && $correo != '0'){
					enviar_correo($correo,$tipo,$idsolicitud);
				}
			}
			echo 1;
		}else{
			echo $query;
		}
	}
	
	function get_calendario(){
		global $mysqli;
		$desde 			= (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '*');
		$hasta 			= (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : '*');
		$provincia 		= (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '*');
		$distrito 		= (!empty($_REQUEST['distrito']) ? $_REQUEST['distrito'] : '*');
		$corregimiento 	= (!empty($_REQUEST['corregimiento']) ? $_REQUEST['corregimiento'] : '*');
		$edad 			= (!empty($_REQUEST['edad']) ? $_REQUEST['edad'] : '*'); 
		$condicion 		= (!empty($_REQUEST['condicion']) ? $_REQUEST['condicion'] : '*');
		$discapacidad 	= (!empty($_REQUEST['discapacidad']) ? $_REQUEST['discapacidad'] : '*');
		$genero 		= (!empty($_REQUEST['genero']) ? $_REQUEST['genero'] : '*');
		$estado 		= (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '*');
		$usuario 		= $_SESSION['usuario_sen'];
		$user_id 		= $_SESSION['user_id_sen'];
		$regional_usu 	= getValor('regional','usuarios',$user_id);
	
		$start 		= (!empty($_REQUEST['start']) ? $_REQUEST['start'] : '');
		$end 		= (!empty($_REQUEST['end']) ? $_REQUEST['end'] : '');
		$query = "	SELECT s.id,UPPER(CONCAT(p.nombre,' ',p.apellidopaterno,' ',p.apellidomaterno))  as paciente, 
					SUBSTR(s.fecha_cita,1,19) as fecha,d.nombre as discapacidad, s.estatus
					FROM solicitudes s 
					LEFT JOIN pacientes p ON s.idpaciente = p.id
					LEFT JOIN discapacidades d ON d.id = s.iddiscapacidad
					LEFT JOIN direccion di ON di.id = p.direccion 
					LEFT JOIN direcciones dir ON dir.id = di.iddireccion
					LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta)
					WHERE s.fecha_cita is not null AND SUBSTR(s.fecha_cita,1,10) BETWEEN '".$start."' AND '".$end."' ";
		if($regional_usu != 'Todos' && $regional_usu != '' && $regional_usu != null){
			$query .= " AND dir.provincia IN ('".$regional_usu."') ";
		}
					
		//Aplicar Filtros
		$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$usuario."'";		
		$resultF = $mysqli->query($queryF);
		if($resultF->num_rows >0){
			$rowF = $resultF->fetch_assoc();
			if (!isset($_REQUEST['data'])) {
				$data = $rowF['filtrosmasivos'];
			}
		}
		if(isset($data) && $data != ''){
			$where2 = '';
			$data = json_decode($data);
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND date(s.fecha_cita) >= ".$desdef." ";
			} else {
				//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND date(s.fecha_cita) <= ".$hastaf." ";
			}
			if(!empty($data->idprovincias)){
				$idprovincias = $data->idprovincias;
				if($idprovincias != '[""]'){
					$where2 .= " AND dir.provincia IN ('".$idprovincias."')";
				}
			}
			if(!empty($data->iddistritos)){
				$iddistritos = $data->iddistritos;
				if($iddistritos != '[""]'){
					$where2 .= " AND dir.distrito IN ('".$iddistritos."')";
				}
			}			
			if(!empty($data->idcorregimientos)){
				$idcorregimientos = $data->idcorregimientos;
				if($idcorregimientos != '[""]'){
					$where2 .= " AND dir.corregimiento IN ('".$idcorregimientos."')";
				}
			}
			if(!empty($data->edad)){
				$edad = json_encode($data->edad);
				if ($edad!="*" && $edad != "null"){
					if($edad == 'primerainfacia'){
						$edadDesde = 0;
						$edadHasta = 5; 
					}
					if($edad == 'infancia'){
						$edadDesde = 6;
						$edadHasta = 11;
					}
					if($edad == 'adolescencia'){
						$edadDesde = 12;
						$edadHasta = 18;
					}
					if($edad == 'juventud'){
						$edadDesde = 19;
						$edadHasta = 26;
					}
					if($edad == 'adultez'){
						$edadDesde = 27;
						$edadHasta = 59;
					}
					if($edad == 'personamayor'){
						$edadDesde = 60;
						$edadHasta = 150;
					}
					$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) > ".$edadDesde." 
								AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) < ".$edadHasta."";
				}
			}
			if(!empty($data->idcondicionsalud)){
				$idcondicionsalud = json_encode($data->idcondicionsalud);
				if($idcondicionsalud != '[""]'){
					$where2 .= " AND s.condicionsalud IN ($idcondicionsalud)";
				}
			}
			if(!empty($data->iddiscapacidades)){
				$iddiscapacidades = json_encode($data->iddiscapacidades);
				if($iddiscapacidades != '[""]'){
					$where2 .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
				}
			}
			if(!empty($data->idgeneros)){
				$idgeneros = json_encode($data->idgeneros);
				if($idgeneros != '[""]'){
					$where2 .= " AND p.sexo IN ($idgeneros)";
				}
			}
			if(!empty($data->idestados)){
				$idestados = json_encode($data->idestados);
				if($idestados != '[""]'){
					$where2 .= " AND s.estatus IN ($idestados)";
				}
			}
			if(!empty($data->idmedicos)){
				$idmedicos = json_encode($data->idmedicos);
				if($idmedicos != '[""]'){
					$where2 .= " AND m.id IN ($idmedicos)";
				}
			}
			
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
		}
		$query  .= isset($data) ? " $where2" : "";
		$query  .= " GROUP BY s.id ";
		//Fin Aplicar Filtros
		//debugL($query);
		//echo $query;
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			//echo $row['fecha'];
			$fecha = new DateTime($row['fecha']);
			$fecha->modify('+1 hour');
			$fecha_cita = $fecha->format('Y-m-d H:i:s');
			$clase = str_replace( array('á', 'é', 'í', 'ó', 'ú', 'Á', 'Á', 'Í', 'Ó', 'Ú'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $row['discapacidad'] );
			$claseM = strtolower($clase);
			//echo $clase;
			$event_array[] = array(
				'id' 		=> $row['id'],
				'title'		=> $row['paciente'],
				'start' 	=> $row['fecha'],
				'end' 		=> $fecha_cita,
				'tipo' 		=> $row['discapacidad'],
				'estatus' 	=> $row['estatus'],
				'className' => $claseM,
			);
		}
		if(empty($event_array)){
			$event_array = '';
		}
			
		echo json_encode($event_array);
		/*
		$arreglo[] =array(
				'className'=> "className",
				'end'=> "2019-11-29 11:00:00",
				'id'=> "14",
				'start'=> "2019-11-29 10:00:00",
				'tipo'=> "Visual",
				'title'=> "jose",
		); 
		*/
		//echo json_encode($arreglo);
	}  
	
	function cargar_cita(){
		global  $mysqli;
		$id = $_REQUEST['id'];
		$query ="SELECT s.id AS id, CONCAT(p.nombre,' ',p.apellidopaterno,' ',p.apellidomaterno) AS paciente, s.fecha_cita AS fecha, p.id as idpaciente,
				GROUP_CONCAT(CONCAT(m.nombre,' ',m.apellido,'|',IFNULL(e.nombre,'Sin especificar')))AS medicos, GROUP_CONCAT(CONCAT(m.id,'|',m.nombre,' ',m.apellido,'|', REPLACE(IFNULL(e.nombre,'Sin especificar'),',',' / ')))AS medicoseditar, s.sala AS sala, r.nombre AS regional, d.nombre AS discapacidad,s.foto, 
				f.id as idevaluacion, s.fecha_cita, g.descripcion AS estatus
				FROM solicitudes s 
				LEFT JOIN pacientes p ON p.id = s.idpaciente 
				LEFT JOIN discapacidades d ON d.id = s.iddiscapacidad
				LEFT JOIN regionales r ON r.id = s.regional
				LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta) 
				LEFT JOIN especialidades e ON e.id = m.especialidad
				LEFT JOIN evaluacion f ON s.id = f.idsolicitud
				INNER JOIN estados g ON s.estatus = g.id
				WHERE s.id = $id";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){	
			
			$data[] = array(
				'id' 			=> $row['id'],
				'idpaciente'	=> $row['idpaciente'],
				'paciente'		=> $row['paciente'],
				'fecha' 		=> $row['fecha'],
				'medicos' 		=> $row['medicos'],
				'medicoseditar' => $row['medicoseditar'],
				'discapacidad' 	=> $row['discapacidad'],
				'estatus' 		=> $row['estatus'],
				'sala' 			=> $row['sala'],
				'foto' 			=> $row['foto'],
				'regional' 		=> $row['regional'],
				'idevaluacion' 	=> $row['idevaluacion'],
				'fecha_cita' 	=> $row['fecha_cita']
			);
		}
		echo json_encode($data);
	}
	
	function cargar_cita_editar(){
		global  $mysqli;
		$id = $_REQUEST['id'];
		$query ="SELECT s.id AS id, CONCAT(p.nombre,' ',p.apellidopaterno,' ',p.apellidomaterno) AS paciente, s.fecha_cita AS fecha, p.id as idpaciente
				s.junta AS medicos, s.sala AS sala, s.regional as regional, s.iddiscapacidad AS discapacidad
				FROM solicitudes s 
				LEFT JOIN pacientes p ON p.id = s.idpaciente 
				LEFT JOIN discapacidades d ON d.id = s.iddiscapacidad
				LEFT JOIN regionales r ON r.id = s.regional
				LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta) 
				LEFT JOIN especialidades e ON e.id = m.especialidad 
				WHERE s.id = $id";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){	
			
			$data[] = array(
				'id' 		=> $row['id'],
				'idpaciente'		=> $row['idpaciente'],
				'paciente'		=> $row['paciente'],
				'fecha' 	=> $row['fecha'],
				'medicos' 		=> $row['medicos'],
				'discapacidad' 		=> $row['discapacidad'],
				'sala' => $row['sala'],
				'regional' => $row['regional'],
			);
		}
		echo json_encode($data);
	}

	function getsolicitudpaciente(){
		global $mysqli;
		$tipo_documento = $_REQUEST['tipo_documento'];
		$cedula = $_REQUEST['cedula'];
		$query = "	SELECT s.id
					FROM solicitudes s
					inner join pacientes p on p.id = s.idpaciente
					WHERE p.tipo_documento = '$tipo_documento' AND p.cedula = '$cedula' order by s.id desc LIMIT 1;";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' => $row['id'],
			);
		}
		echo json_encode($resultado);
	}

	function getsolicitudpacienteid(){
		global $mysqli;
		$idpaciente = $_REQUEST['idpaciente'];
		$query = "	SELECT s.id
					FROM solicitudes s
					inner join pacientes p on p.id = s.idpaciente
					WHERE p.id = '$idpaciente' LIMIT 1;";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}

	function getIdSolicitudpaciente(){
		global $mysqli;
		$idpaciente = $_REQUEST['idpaciente'];
		$query = "	SELECT s.id
					FROM solicitudes s
					inner join pacientes p on p.id = s.idpaciente
					WHERE p.id = '$idpaciente' LIMIT 1;";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' => $row['id'],
			);
		}
		echo json_encode($resultado);
	}

	function get_pacienteporsolicitud(){
		global $mysqli;
		$idsolicitud   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 0);
		
		$query = "	SELECT p.id
					FROM solicitudes s
					INNER JOIN pacientes p on p.id = s.idpaciente
					WHERE s.id = '".$idsolicitud."' ";
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

	function get(){
		global $mysqli;
		
		$id 	= $_REQUEST['id'];
		$query 	= "	SELECT *
					FROM medicos
					WHERE id = '$id'";
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			
			$resultado = array(
				'nombre' 				=>	$row['nombre'],
				'apellido' 				=>	$row['apellido'],			
				'cedula' 				=>	$row['cedula'],		
				'especialidad' 			=>	$row['especialidad'],			
				'telefonocelular' 		=>	$row['telefonocelular'],			
				'telefonootro' 			=>	$row['telefonootro'],			
				'correo' 				=>	$row['correo'],	
				'discapacidades'		=> 	$row['discapacidades'],
				'regional'				=> 	$row['regional'],
			);
		}
		
		if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}	
	
	function aprobar(){
		global $mysqli;
		$idusuario 			= $_SESSION['user_id_sen'];
		$id 	= $_REQUEST['id'];
		$queryestado ="SELECT s.estatus as estatus
				FROM solicitudes s 
				WHERE s.id = $id";
		$resultestado = $mysqli->query($queryestado);
		$rowestado = $resultestado->fetch_assoc();
		$estadoanterior = $rowestado['estatus'];
		$querystatus 	= "UPDATE solicitudes SET estatus = '17' WHERE id = '$id';";
		if($resultstatus = $mysqli->query($querystatus)){
			$queryhistorico = "INSERT INTO historicosolicitudes (id,idsolicitud,usuario,fecha,estadoanterior,estadoactual) VALUES (null,'$id','$idusuario',CURDATE(),'$estadoanterior','17')";
			$resulthistorico = $mysqli->query($queryhistorico);
		}
		
		echo 1;
	}
	
	function eliminar(){
		global $mysqli;		
		$id	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query 	= "DELETE FROM solicitudes WHERE id = '".$id."' ";
		$result = $mysqli->query($query);		
		if($result == true){
		    eliminarRegistro('Solicitudes','Solicitudes',$nombre,$id,$query);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function update(){
		global $mysqli;
		
		$id 						= $_REQUEST['id'];
		$nombre 					= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		$cedula 					= (!empty($_REQUEST['cedula']) ? $_REQUEST['cedula'] : '');
		$apellido					= (!empty($_REQUEST['apellido']) ? $_REQUEST['apellido'] : '');
		$especialidad				= (!empty($_REQUEST['especialidad']) ? $_REQUEST['especialidad'] : '');
		$telefonocelular			= (!empty($_REQUEST['telefonocelular']) ? $_REQUEST['telefonocelular'] : '');
		$telefonootro				= (!empty($_REQUEST['telefonootro']) ? $_REQUEST['telefonootro'] : '');
		$correo						= (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
		$discapacidades				= (!empty($_REQUEST['discapacidades']) ? $_REQUEST['discapacidades'] : '');
		$regional				= (!empty($_REQUEST['regional']) ? $_REQUEST['regional'] : '');
		
		$query 	= "	UPDATE medicos SET nombre = '$nombre', cedula = '$cedula', apellido = '$apellido' ,especialidad = '$especialidad', telefonocelular = '$telefonocelular', telefonootro = '$telefonootro', correo = '$correo',discapacidades='$discapacidades',regional='$regional' WHERE id = '$id'";
		$result = $mysqli->query($query);	
		
		echo 1;
	}
	
	function validarFecha($date, $format = 'Y-m-d H:i:s'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	function guardar_solicitud(){ //Guardar o editar solicitud
		global $mysqli;		
		
		//SOLICITUD
		$idsolicitud 		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$data 				= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		$regional			= (!empty($data['lugarsolicitud']) ? $data['lugarsolicitud'] : '');
		$tipodiscapacidad	= (!empty($data['tipodiscapacidad']) ? $data['tipodiscapacidad'] : 0);
		$tiposolicitud		= (!empty($data['tiposolicitud']) ? $data['tiposolicitud'] : 0);
		$estado				= (!empty($data['estadosolicitud']) ? $data['estadosolicitud'] : '1');
		$fecha_solicitud 	= (!empty($data['fecha_sol']) ? $data['fecha_sol'] : '');
		$observaciones		= (!empty($data['observaciones']) ? $data['observaciones'] : '');
		$condicionsalud		= (!empty($data['cssolicitud']) ? $data['cssolicitud'] : '');
		$cedula				= (!empty($data['cedula']) ? $data['cedula'] : '');
		//ACOMPAÑANTE
		$dataSolAc			= (!empty($_REQUEST['datosSolAc']) ? $_REQUEST['datosSolAc'] : '');
		$idacompananteSA	= (!empty($dataSolAc['idacompanante']) ? $dataSolAc['idacompanante'] : 0);
		$tipoacompananteSA	= (!empty($dataSolAc['tipoacompanante']) ? $dataSolAc['tipoacompanante'] : 0);
		$requiereacSA		= (!empty($dataSolAc['requiere_acompanante']) ? $dataSolAc['requiere_acompanante'] : '');
		
		//BENEFICIARIO
		$idbeneficiario		= (!empty($_REQUEST['idbeneficiario']) ? $_REQUEST['idbeneficiario'] : '');
		$correo 			= getValor('correo','pacientes',$idbeneficiario);
		$foto				= (!empty($_REQUEST['foto']) ? $_REQUEST['foto'] : '');
		//ACOMPAÑANTE
		$dataAc				= (!empty($_REQUEST['datosAc']) ? $_REQUEST['datosAc'] : '');
		$idacompanante		= (!empty($dataAc['idacompanante']) ? $dataAc['idacompanante'] : 0);
		$tipoacompanante	= (!empty($dataAc['tipoacompanante']) ? $dataAc['tipoacompanante'] : 0);
		
		$estadoReconsideracion = 5;
		$estadoApelacion = 31;

		$fecha_archivo		= explode(" ", $fecha_solicitud);
		$myPath = '../images/solicitudes/'.$idbeneficiario.'/';
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path))
			if(!mkdir("../images/solicitudes/".$idbeneficiario, 0777, true)) {
		    	die('Fallo al crear las carpetas...');
			}
		if($foto != ''){
			$ruta = "../images/solicitudes/".$idbeneficiario."/".$fecha_archivo[0].".jpg";
			rename ("../".$foto."","".$ruta."");
		}
		/*
		$validarFecha = validarFecha($fecha_solicitud);
		if($validarFecha === true){
			$fecha_sol = $fecha_solicitud;
			//echo 'validarFecha 1: '.$fecha_solicitud.' - ';
		}else{
			$fecha_s = implode('-',array_reverse(explode('-',$fecha_archivo[0])));
			$fecha_sol = $fecha_s.' '.$fecha_archivo[1];
			//echo 'validarFecha 2: '.$fecha_solicitud.' - ';
		}
		*/
		if($tiposolicitud == 1){
			$ntiposolicitud = 'Nueva';
		}elseif($tiposolicitud == 2){
			$ntiposolicitud = 'Reconsideración';
		}elseif($tiposolicitud == 3){
			$ntiposolicitud = 'Reevaluación';
		}elseif($tiposolicitud == 0){
			$ntiposolicitud = 'Sin especificar';
		}
			
		if($idsolicitud == '' || $idsolicitud == 'false'){
			
			$campos = array(
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
			$query 	= "	INSERT INTO	solicitudes (idpaciente, cedula, fecha_solicitud, regional, foto, idacompanante, estatus, tipoacompanante, iddiscapacidad, condicionsalud, observacionesestados,tipo,fechacambioestado)
						VALUES ('".$idbeneficiario."', '".$cedula."','".$fecha_solicitud."','".$regional."','".$ruta."','".$idacompananteSA."',1,'".$tipoacompananteSA."','".$tipodiscapacidad."','".$condicionsalud."','".$observaciones."','".$tiposolicitud."',NOW()) ";
			
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
				$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
							VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), '".$estado."', '".$estado."') ";
				$mysqli->query($queryE);
				
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
			$sqlIdpacienteOld = "SELECT idpaciente, reconsideracion FROM solicitudes WHERE id = ".$idsolicitud."";
			$rtaIdpacOld = $mysqli->query($sqlIdpacienteOld);
			if($rowIdpacOld = $rtaIdpacOld->fetch_assoc()){
				$idpacienteOld = $rowIdpacOld['idpaciente'];	
				$reconsideracionOld = $rowIdpacOld['reconsideracion'];
			}
				
			$query 	= "	UPDATE solicitudes SET idpaciente = '".$idbeneficiario."', fecha_solicitud = '".$fecha_solicitud."', 
						regional = '".$regional."', idacompanante = '".$idacompananteSA."', estatus = '".$estado."', 
						tipoacompanante = '".$tipoacompananteSA."', iddiscapacidad = '".$tipodiscapacidad."', 
						condicionsalud = '".$condicionsalud."', observacionesestados = '".$observaciones."', cedula = '".$cedula."',
						tipo = ".$tiposolicitud." ";
			
			if($estadoold != $estado){
				$query 	.= ", fechacambioestado = NOW()";
			}

			//Primera reconsideración
			if($estado == $estadoReconsideracion && $reconsideracionOld == 0){
				$query .= ", reconsideracion = 1";	
			}
			//Segunda reconsideración
			if($estado == $estadoReconsideracion && $reconsideracionOld == 1){
				$query .= ", reconsideracion = 2";	
			} 
			//Apelación
			if($estado == $estadoApelacion){
				$query .= ", apelacion = 1";	
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
	
	function existe(){
		global $mysqli;
		$cedula = $_REQUEST['cedula'];
		$count = 0;
		$query = "SELECT cedula FROM medicos WHERE cedula = '$cedula'";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}

	function abriradjuntos() {
		$solicitud 	= $_REQUEST['idsolicitud'];		
		$_SESSION['idsolicitud'] = $solicitud;
		$myPath = '../solicitudes/'.$solicitud;
		$target_path = utf8_decode($myPath);
		if (!file_exists($target_path)) {
			mkdir($target_path, 0777);
		}
		//$Path = dirname($_SERVER['PHP_SELF']) . '/../incidentes/'.$_SESSION['incidente'].'/';
		$Path = '/../solicitudes/'.$_SESSION['idsolicitud'].'/';
		//debug($Path);
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');		
		echo "l1_". $hash;		
	}

	function  subirfoto(){
		$ruta_imagen = '';
		$pathM = '../images/solicitudes/temp/';		
		if ($_FILES['boton_img_perfil']['error']==0	){
			//obtenemos el archivo a subir
			$fileN = rand(5, 15).'_foto_temporal.jpg'; 
			$fileN = str_replace(' ', '', $fileN);
			if ($fileN && move_uploaded_file($_FILES['boton_img_perfil']['tmp_name'],$pathM.''.$fileN)){
				sleep(1);//retrasamos la petición 1 segundos
				$ruta_imagen = 'images/solicitudes/temp/'.$fileN;
				echo $ruta_imagen;
			}else{
				//echo "Fallo al enviarla imágen";
				echo 0; 		    
			}
		} 
	}

	function update_solicitud(){
		global $mysqli;
		$idsolicitud   		=  $_REQUEST['idsolicitud'];
		$idpaciente 		= getValor('idpaciente','solicitudes',$idsolicitud);
		$correo 			= getValor('correo','pacientes',$idpaciente);
		$fecha_solicitud 	=  $_REQUEST['fecha_solicitud'];
		$tipodiscapacidad 	=  $_REQUEST['tipodiscapacidad'];
		$regional 			=  $_REQUEST['regional'];
		$foto 				=  $_REQUEST['foto'];
		$estado 			= $_REQUEST['estado'];
		$idacompanante 		=  $_REQUEST['idacompanante'];
		$idusuario 			= $_SESSION['user_id_sen'];
		$tipoacompanante 	=  (!empty($_REQUEST['tipoacompanante']) ? $_REQUEST['tipoacompanante'] : '0');
		$condicionsalud 	=  (!empty($_REQUEST['condicionsalud']) ? $_REQUEST['condicionsalud'] : '');
		$observacionesestados 	=  (!empty($_REQUEST['observacionesestados']) ? $_REQUEST['observacionesestados'] : '');
		
		$queryestado =" SELECT s.estatus FROM solicitudes s WHERE s.id = '".$idsolicitud."' ";
		$resultestado = $mysqli->query($queryestado);
		$rowestado = $resultestado->fetch_assoc();
		$estadoanterior = $rowestado['estatus'];
		$query = "	UPDATE solicitudes SET iddiscapacidad = '$tipodiscapacidad', foto = '$foto', regional = '$regional',
					fecha_solicitud = '$fecha_solicitud', idacompanante = '$idacompanante', estatus = '$estado',
					tipoacompanante = '$tipoacompanante', condicionsalud = '$condicionsalud', observacionesestados = '$observacionesestados'
					WHERE id = '".$idsolicitud."' ";
		//debug($query);
		if($result = $mysqli->query($query)){
			if($estado != $estadoanterior){
				$queryhistorico = " INSERT INTO historicosolicitudes (id,idsolicitud,usuario,fecha,estadoanterior,estadoactual) 
									VALUES (null,'".$idsolicitud."','".$idusuario."',CURDATE(),'".$estadoanterior."','".$estado."')";
				$resulthistorico = $mysqli->query($queryhistorico);
			}
			if ($estado == '11'){
				$tipo = 'aprobado';
				enviar_correo($correo,$tipo,$idsolicitud);
			}
			echo 1;
		}else{
			echo $query;
		}
	}
	
	function guardar_resolucion(){
		global $mysqli;
		
		$idsolicitud 		 = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$nro_resolucion 	 = (!empty($_REQUEST['nro_resolucion']) ? $_REQUEST['nro_resolucion'] : '');
		$nro_expediente 	 = (!empty($_REQUEST['nro_expediente']) ? $_REQUEST['nro_expediente'] : '');
		$validez_certificado = (!empty($_REQUEST['validez_certificado']) ? $_REQUEST['validez_certificado'] : '');
		$validez_tipo 		 = (!empty($_REQUEST['validez_tipo']) ? $_REQUEST['validez_tipo'] : '');
		$observacion		 = (!empty($_REQUEST['observacion']) ? $_REQUEST['observacion'] : '');
		
		$query 	= "	INSERT INTO	resolucion (idsolicitud, nro_resolucion, nro_expediente, validez_certificado, validez_tipo, observacion)
		VALUES ('$idsolicitud','$nro_resolucion','$nro_expediente','$validez_certificado','$validez_tipo','$observacion')";
		
		$result = $mysqli->query($query);
		
		if($result == true){
			
			//Guardar en tabla control de números de resolución
			$sql = " INSERT INTO modulos_nroresolucion (idmodulo, nro_resolucion, tipo)
					 VALUES(".$idsolicitud.",'".$nro_resolucion."','Certificó')";
			$mysqli->query($sql);
			
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function editar_resolucion(){
		global $mysqli;
		
		$idsolicitud 		 = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$nro_resolucion 	 = (!empty($_REQUEST['nro_resolucion']) ? $_REQUEST['nro_resolucion'] : '');
		$nro_expediente 	 = (!empty($_REQUEST['nro_expediente']) ? $_REQUEST['nro_expediente'] : '');
		$validez_certificado = (!empty($_REQUEST['validez_certificado']) ? $_REQUEST['validez_certificado'] : '');
		$validez_tipo 		 = (!empty($_REQUEST['validez_tipo']) ? $_REQUEST['validez_tipo'] : '');
		$observacion		 = (!empty($_REQUEST['observacion']) ? $_REQUEST['observacion'] : '');
		
		$query 	= "	UPDATE resolucion SET nro_resolucion = '$nro_resolucion', nro_expediente = '$nro_expediente', validez_certificado = '$validez_certificado', validez_tipo = '$validez_tipo', observacion = '$observacion'
					WHERE idsolicitud = '$idsolicitud' ";
		//echo $query;
		$result = $mysqli->query($query);
		
		if($result == true){
			
			$inicReg = substr($nro_resolucion, 0, 3);
			$regionales = array("BOC", "COC", "COL", "CHI", "DAR", "HER", "LOS", "PAN", "VER", "PAO");
			
			//Verificar nuevo formato de número de resolución
			if(in_array($inicReg,$regionales)){
				
				//Verificar si existe el número de resolución
				$sqlS = "SELECT id FROM modulos_nroresolucion WHERE nro_resolucion = '".$nro_resolucion."' LIMIT 1";
				$rta = $mysqli->query($sqlS);
				$reg = $rta->num_rows;
				
				if($reg == 0){
					
					//Guardar en tabla control de números de resolución
					$sqlI = " INSERT INTO modulos_nroresolucion (idmodulo, nro_resolucion, tipo)
							 VALUES(".$idsolicitud.",'".$nro_resolucion."','Certificó')";
					$mysqli->query($sqlI);
				}				
			}
			
			echo 1;
		}else{
			echo $query;
		}
	}
	
	function get_resolucion(){
		global  $mysqli;
		$id = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$iddiscapacidad = (!empty($_REQUEST['iddiscapacidad']) ? $_REQUEST['iddiscapacidad'] : '');
		
		$query ="SELECT *
				FROM resolucion 
				WHERE idsolicitud = $id";
		$result = $mysqli->query($query);
		if($row = $result->fetch_assoc()){	
			
			$data = array(
				'idsolicitud'		  => $row['idsolicitud'],
				'nro_expediente'	  => $row['nro_expediente'],
				'nro_resolucion' 	  => $row['nro_resolucion'],
				'validez_certificado' => $row['validez_certificado'],
				'validez_tipo' 		  => $row['validez_tipo'],
				'tieneresolucion' 	  => 1, 
				'observacion' 		  => $row['observacion'] 
			);
		} 
		if( isset($data) ) {
			echo json_encode($data);
		} else {
			
			$sql = "SELECT a.fecha_cita, CONCAT(b.nombre,' ',b.apellidopaterno, ' ',b.apellidomaterno) AS paciente,
					b.cedula, b.expediente, c.duracion, c.tipoduracion, c.codigojunta, c.observaciones
					FROM solicitudes a 
					INNER JOIN pacientes b ON b.id = a.idpaciente
					INNER JOIN evaluacion c ON c.idsolicitud = a.id
					WHERE a.id = '".$id."'";
			$rsql = $mysqli->query($sql);
			if($reg = $rsql->fetch_assoc()){
				
				$fechacita = $reg['fecha_cita'];
				$paciente = $reg['paciente'];
				$cedula = $reg['cedula'];
				$expediente = $reg['expediente'];
				$duracion = $reg['duracion'];
				$tipoduracion = $reg['tipoduracion'] == 'M' ? 'meses' : 'años';
				$codigojunta = $reg['codigojunta'];
				$observaciones = $reg['observaciones'];
				preg_match('/#\s(\d+)/', $observaciones, $matches);
				$criterio = $matches[1];
			
			}
			
			if($iddiscapacidad == 1){ //Física
			
				$txt_tipodiscapacidad = "física";
				$articulo = "artículo 62 del Decreto Ejecutivo N°36 de 2014";
				
			}else if($iddiscapacidad == 2){ //Visual
			
				$txt_tipodiscapacidad = "visual";
				$articulo = "artículo 64 del Decreto Ejecutivo N°36 de 2014";
				
			}else if($iddiscapacidad == 3){ //Auditiva
			
				$txt_tipodiscapacidad = "auditiva";
				$articulo = "artículo 63 del Decreto Ejecutivo N°36 de 2014";
				
			}else if($iddiscapacidad == 4){ //Mental 
			
				$txt_tipodiscapacidad = "mental";
				$articulo = "artículo 64 del Decreto Ejecutivo N° 36 de 11 de abril de 2014"; 
				
			}else if($iddiscapacidad == 5){ //Intelectual
			
				$txt_tipodiscapacidad = "intelectual";
				$articulo = "artículo 64 del Decreto Ejecutivo N° 36 de 11 de abril de 2014";
				
			}else if($iddiscapacidad == 6){ //Visceral
			
				$txt_tipodiscapacidad = "visceral";
				$articulo = "artículo 66 del Decreto Ejecutivo N°36 de 2014";
				
			}
			$arrfechacitaDatetime = explode(' ',$fechacita);
			$arrfechacitaDate = explode('-',$arrfechacitaDatetime[0]);
			$meses = array('','enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
			$mesev = (int)$arrfechacitaDate[1];
			$diaevalLetras = day_to_words($arrfechacitaDate[2]);
			$anyoletras = numberToWords($arrfechacitaDate[0]);
			$fechaevaluacion = $diaevalLetras. '(' . $arrfechacitaDate[2].') de '.$meses[$mesev].' de '.$anyoletras.' ('.$arrfechacitaDate[0].')';
			
			//$txt_observacion = "Fundamentación Legal: Que el día trece (13) de diciembre de dos mil veintidós (2022), tal cual 
			$txt_observacion = "Fundamentación Legal: Que el día ".$fechaevaluacion.", tal cual lo exigido por el artículo 74 del Decreto Ejecutivo N.° 36 de 11 de abril de 2014, la Dirección Nacional de Certificaciones le otorgó un cupo a ".$paciente.", para que la Junta Evaluadora efectuase la correspondiente evaluación de su condición de salud; Que conforme a lo establecido en el artículo 4 del Decreto Ejecutivo N.° 74 de 14 de abril de 2015, la Junta Evaluadora de la Discapacidad integrada por un mínimo tres (3) miembros, y cumpliendo con el requisito de interdisciplinariedad, según consta en la Resolución N° ".$codigojunta." de la Dirección Nacional de Certificaciones, evaluó y valoró a ".$paciente."; Que, en virtud de ello, la Junta Evaluadora, luego de evaluar y valorar de conformidad con los protocolos establecidos en el Decreto Ejecutivo N° 36 de 11 de abril de 2014, modificado por el Decreto Ejecutivo N° 74 de 14 de abril de 2015, ha determinado que ".$paciente." certifica con base en el criterio # ".$criterio." de discapacidad ".$txt_tipodiscapacidad.", correspondiente a los criterios establecidos en el ".$articulo."; por un periodo de ".$duracion." ".$tipoduracion.". 
Que mediante Resuelto No. 71 de 21 de septiembre de 2021 se nombró a la Lcda. Aileen A. Aparicio G. como Directora Nacional de Certificaciones de la SENADIS, siendo facultada para supervisar, dirigir y controlar los procesos de evaluación, valoración y emisión de la certificación.
Que por todas las consideraciones expuestas, la Directora de la Dirección Nacional de Certificaciones de la Secretaría Nacional de Discapacidad,																						
																																																																															RESUELVE:
PRIMERO: OTORGAR CERTIFICACIÓN DE LA DISCAPACIDAD a ".$paciente.", con cédula de identidad personal N° ".$cedula.", con expediente N° ".$expediente.", la Dirección Nacional de Certificaciones de la Secretaría Nacional de Discapacidad.
			
SEGUNDO: Señalar que el término de vigencia de la presente Certificación es de diez (".$duracion.") ".$tipoduracion.".
			
TERCERO: Ordenar la confección del carné de discapacidad, a favor ".$paciente.".
			
CUARTO: Notificar al interesado el contenido de la presente Resolución y entregarle copia de la misma.
			
QUINTO: La presente certificación de discapacidad, no vincula de manera alguna, a los programas  que administra la CSS, el MINSA y el IPHE.
			
SEXTO: Advertir que contra esta resolución puede interponerse el recurso de reconsideración, dentro del término de los cinco (05) días hábiles, contados a partir de su notificación.
			
SÉPTIMO: La presente resolución entrará a regir a partir de la fecha de su notificación. ";
			
			$data = array(
				'idsolicitud'		  => $idsolicitud,
				'nro_expediente'	  => $expediente,
				'nro_resolucion' 	  => '',
				'validez_certificado' => '',
				'validez_tipo' 		  => '',
				'tieneresolucion' 	  => 0,
				'observacion' 		  => $txt_observacion 
			);
			echo json_encode($data);
			//echo "0";
		}
	}
	
	function guardar_negatoria(){
		global $mysqli;
		
		$idsolicitud	= (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$nro_resolucion	= (!empty($_REQUEST['nro_resolucion']) ? $_REQUEST['nro_resolucion'] : '');
		$evaluacion		= (!empty($_REQUEST['evaluacion_negatoria']) ? $_REQUEST['evaluacion_negatoria'] : '');
		$primerc 		= (!empty($_REQUEST['primerc_negatoria']) ? $_REQUEST['primerc_negatoria'] : '');
		$segundoc		= (!empty($_REQUEST['segundoc_negatoria']) ? $_REQUEST['segundoc_negatoria'] : '');
		$fechasolicitud	  = (!empty($_REQUEST['fechasol_negatoria']) ? $_REQUEST['fechasol_negatoria'] : '');
		$fechaevaluacion  = (!empty($_REQUEST['fechaeva_negatoria']) ? $_REQUEST['fechaeva_negatoria'] : '');
		$fechanotifiquese = (!empty($_REQUEST['fechanot_negatoria']) ? $_REQUEST['fechanot_negatoria'] : '');
		$nombreencargado  = (!empty($_REQUEST['nombre_encargado']) ? $_REQUEST['nombre_encargado'] : '');
		$cargoencargado	  = (!empty($_REQUEST['cargo_encargado']) ? $_REQUEST['cargo_encargado'] : '');
		
		if($fechasolicitud != ''){
			$fechasolicitud = '"'.$fechasolicitud.'"';
		}else{
			$fechasolicitud = 'null'; 
		}
		if($fechaevaluacion != ''){
			$fechaevaluacion = '"'.$fechaevaluacion.'"';
		}else{
			$fechaevaluacion = 'null'; 
		}
		if($fechanotifiquese != ''){
			$fechanotifiquese = '"'.$fechanotifiquese.'"';
		}else{
			$fechanotifiquese = 'null'; 
		}
		$query 	= "	INSERT INTO	negatorias (idsolicitud, nro_resolucion, evaluacion, primerc, segundoc,nombre_encargado,cargo_encargado,fecha_solicitud,fecha_evaluacion,fecha_notifiquese)
					VALUES ('".$idsolicitud."','".$nro_resolucion."','".$evaluacion."','".$primerc."','".$segundoc."',
					'".$nombreencargado."','".$cargoencargado."',".$fechasolicitud.",".$fechaevaluacion.",".$fechanotifiquese.")";		
		$result = $mysqli->query($query);		
		if($result == true){
			
			//Guardar en tabla de números de resolución
			$sql = " INSERT INTO modulos_nroresolucion (idmodulo, nro_resolucion, tipo)
					 VALUES(".$idsolicitud.",'".$nro_resolucion."','Negatoria')";
			$mysqli->query($sql);
			
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function editar_negatoria(){
		global $mysqli;
		
		$idsolicitud	= (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		$nro_resolucion	= (!empty($_REQUEST['nro_resolucion']) ? $_REQUEST['nro_resolucion'] : '');
		$evaluacion		= (!empty($_REQUEST['evaluacion_negatoria']) ? $_REQUEST['evaluacion_negatoria'] : '');
		$primerc 		= (!empty($_REQUEST['primerc_negatoria']) ? $_REQUEST['primerc_negatoria'] : '');
		$segundoc		= (!empty($_REQUEST['segundoc_negatoria']) ? $_REQUEST['segundoc_negatoria'] : '');
		$fechasolicitud	  = (!empty($_REQUEST['fechasol_negatoria']) ? $_REQUEST['fechasol_negatoria'] : '');
		$fechaevaluacion  = (!empty($_REQUEST['fechaeva_negatoria']) ? $_REQUEST['fechaeva_negatoria'] : '');
		$fechanotifiquese = (!empty($_REQUEST['fechanot_negatoria']) ? $_REQUEST['fechanot_negatoria'] : '');
		$nombreencargado  = (!empty($_REQUEST['nombre_encargado']) ? $_REQUEST['nombre_encargado'] : '');
		$cargoencargado	  = (!empty($_REQUEST['cargo_encargado']) ? $_REQUEST['cargo_encargado'] : '');
		
		if($fechasolicitud != ''){
			$fechasolicitud = '"'.$fechasolicitud.'"';
		}else{
			$fechasolicitud = 'null'; 
		}
		if($fechaevaluacion != ''){
			$fechaevaluacion = '"'.$fechaevaluacion.'"';
		}else{
			$fechaevaluacion = 'null'; 
		}
		if($fechanotifiquese != ''){
			$fechanotifiquese = '"'.$fechanotifiquese.'"';
		}else{
			$fechanotifiquese = 'null'; 
		} 
		
		$query 	= "	UPDATE 
						negatorias 
					SET 
						nro_resolucion = '".$nro_resolucion."', 
						evaluacion = '".$evaluacion."',
						primerc = '".$primerc."',
						segundoc = '".$segundoc."',
						nombre_encargado = '".$nombreencargado."',
						cargo_encargado = '".$cargoencargado."',
						fecha_solicitud = ".$fechasolicitud.",
						fecha_evaluacion = ".$fechaevaluacion.",
						fecha_notifiquese = ".$fechanotifiquese." 
					WHERE 
					idsolicitud = '".$idsolicitud."'"; 	
		$result = $mysqli->query($query);		
		if($result == true){
			echo 1;
		}else{
			echo $query;
		}
	}
	
	function get_negatoria(){
		global  $mysqli;
		$id = $_REQUEST['idsolicitud']; 
		
		$query = "SELECT * FROM negatorias WHERE idsolicitud = $id ";
		$result = $mysqli->query($query);
		$records = $result->num_rows;
		if($records > 0){
			while($row = $result->fetch_assoc()){
				$data = array(
					'idsolicitud'	=> $row['idsolicitud'],					
					'nro_resolucion'=> $row['nro_resolucion'],
					'evaluacion'	=> $row['evaluacion'],
					'primerc' 		=> $row['primerc'],
					'segundoc'		=> $row['segundoc'],
					'fecha_solicitud'	=> $row['fecha_solicitud'], 
					'fecha_evaluacion'	=> $row['fecha_evaluacion'], 
					'fecha_notifiquese'	=> $row['fecha_notifiquese'], 
					'nombre_encargado'	=> $row['nombre_encargado'], 
					'cargo_encargado'	=> $row['cargo_encargado']					
				);
			} 
		}else{
			$data = array(
				'idsolicitud'		  => '',
				'nro_resolucion'	  => '',
				'evaluacion' 	  => '',
				'primerc' 		=> '',
				'segundoc' 		  => '' 
			);
		}		
		if( isset($data) ) {
			echo json_encode($data);
		} else {
			echo "0";
		}
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
		//require_once dirname(__FILE__) . '\PHPExcel.php'; //  
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maxia Latam")
		->setLastModifiedBy("Maxia Latam")
		->setTitle("Agenda")
		->setSubject("Agenda")
		->setDescription("Agenda")
		->setKeywords("Agenda")
		->setCategory("Agenda");
		
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
		setlocale(LC_TIME, "spanish");
		//TITULO	
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Agenda');
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($style);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1'); 
		
		//LETRA
		$objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getFont()->setBold(true)->setSize(12)->setColor($fontColor);
		$objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293f76');
		$objPHPExcel->getActiveSheet()->getStyle("A5:G5")->applyFromArray($style);
		//FONDO
		//$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB();
		  
		//CUERPO
		//Definir fuente
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		
		$objPHPExcel->getActiveSheet()
			->setCellValue('A5', 'Fecha cita')
			->setCellValue('B5', 'Hora inicio')
			->setCellValue('C5', 'Hora fin')
			->setCellValue('D5', 'Usuario') 
			->setCellValue('E5', 'Cédula') 
			->setCellValue('F5', 'Condición de salud')
			->setCellValue('G5', 'Junta Evaluadora');

		//FECHAS
		$queryF = " SELECT DISTINCT(DATE(fecha_cita)) AS fecha_cita FROM solicitudes 
					WHERE estatus = 2 AND fecha_cita is not null ORDER BY fecha_cita ASC ";
		$resultF = $mysqli->query($queryF);
		$i = 6;
		while($rowF = $resultF->fetch_assoc()){
			//$k = $i + 1;
			$fecha_cita = $rowF['fecha_cita'];
			/*			
			// ENCABEZADO
			$objPHPExcel->getActiveSheet($i)->mergeCells('A'.$i.':E'.$i);
			$objPHPExcel->getActiveSheet($i)->getStyle('A'.$i)->getFont()->setBold(true)->setSize(10);
			$meses = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
			$dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
			$nombredia = $dias[date('w', strtotime($fecha_cita)-1)];  
			//$nombredia = date('w', strtotime($fecha_cita));
			$newDate = date("d-m-Y", strtotime($fecha_cita));
			$mesDesc = strftime("%B", strtotime($newDate));
			$style = array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
			);
			$mesfin = explode('-',$fecha_cita);  
			$objPHPExcel->getActiveSheet($i)->getStyle('A'.$i)->applyFromArray($style);
			$objPHPExcel->getActiveSheet($i)->setCellValue('A'.$i, $nombredia.' '.$mesfin[2].' de '.$mesDesc);
			$objPHPExcel->getActiveSheet($i)->getStyle('A'.$k.':E'.$k)->getFont()->setBold(true)->setSize(10);
			$objPHPExcel->getActiveSheet($i)->getStyle('A'.$k.':E'.$k)->applyFromArray($style); 
			*/
			
			
				$query ="	SELECT s.id,UPPER(CONCAT(p.nombre,' ',p.apellidopaterno,' ',p.apellidomaterno))  AS paciente, p.cedula, 
							date_format(time(date_sub(fecha_cita, INTERVAL 45 MINUTE)),'%H:%i') AS horainicial, 
							date_format(time(fecha_cita),'%H:%i') AS horafinal, DATE(s.fecha_cita) AS fecha, s.condicionsalud, 
							GROUP_CONCAT(CONCAT(m.nombre,' ',m.apellido)) AS medicos
							FROM solicitudes s 
							LEFT JOIN pacientes p ON s.idpaciente = p.id 
							LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta)
							WHERE s.estatus = 2 AND DATE(s.fecha_cita) = '".$fecha_cita."' 
							GROUP BY s.id 
							ORDER BY s.fecha_cita ASC ";
				$result = $mysqli->query($query);
				
				
				//$j = $i + 2;
				while($row = $result->fetch_assoc()){ 
					//ESTILOS
					//$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);				    
					//$fecha_cita = $row['fecha']; 
					//$numeroreq = str_pad($row['id'], 4, "0", STR_PAD_LEFT); 
					//$horaini = substr($row['horainicial'],0,-3);
					$fecha = implode('/',array_reverse(explode('-', $row['fecha'])));
					//$horafin = substr($row['horafinal'],0,-3);
					$objPHPExcel->getActiveSheet()
					->setCellValue('A'.$i,$fecha) 
					->setCellValue('B'.$i,$row['horainicial']) 
					->setCellValue('C'.$i,$row['horafinal'])
					->setCellValue('D'.$i,$row['paciente']) 
					->setCellValue('E'.$i,$row['cedula']) 
					->setCellValue('F'.$i,$row['condicionsalud'])  
					->setCellValue('G'.$i,$row['medicos']);						
					
					/* 
					$objPHPExcel->getActiveSheet()->getStyle('AE'.$i)->getAlignment()->applyFromArray(
								array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
					$objPHPExcel->getActiveSheet()->getStyle('AH'.$i)->getAlignment()->applyFromArray(
								array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)); */
					//$j++;
					$i++;
				}
			//$k++;
			//$i += 2;
		} 

		//Ancho automatico
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);//setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(60);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

		//Renombrar hoja de Excel
		$objPHPExcel->getActiveSheet()->setTitle('SENADIS - Agenda');

		//Redirigir la salida al navegador del cliente
		$hoy = date('dmY');
		$nombreArc = 'Agenda-'.$hoy.'.xls';
		//bitacora($_SESSION['usuario_sen'],'Niveles','Fue generado un archivo con el nombre: '.$nombreArc,0,'');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$nombreArc.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
	
	function comentarios(){
		global $mysqli;
		/*--CONFIG-DATATABLE--------------------------------------------------*/
		//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC*/
		$orderType	= (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start		= (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$length		= (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/		
		$nivel 		= $_SESSION['nivel_sen'];
		$id 		= (!empty($_GET['id']) ? $_GET['id'] : 0);
		$acciones 	= '';
		$opciones	= '';
		
		$query  = " SELECT a.id, a.idmodulo, a.comentario, a.fecha, b.nombre
					FROM comentarios a
					LEFT JOIN usuarios b ON a.usuario = b.usuario
					WHERE modulo = 'Solicitudes' AND idmodulo IN (".$id.") ";
		$query .= " ORDER BY a.id DESC ";
		//echo($query);
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			//if($nivel != 1){
				$opciones .= '<a class="dropdown-item text-danger boton-eliminar-comentarios" data-id="'.$row['id'].'"><i class="fas fa-trash mr-2"></i>Eliminar</a>';
			//}
			$acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu droptable dropdown-menu-right droptable">
								'.$opciones.'
								</div>
							</div>
						</td>';

			$resultado[] = array(
				'id' 			=> $row['id'],
				'acciones' 		=> $acciones,
				'comentario' 	=> $row['comentario'],
				'nombre'		=> $row['nombre'],
				'fecha' 		=> $row['fecha']
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
	
	function agregarComentario(){
		global $mysqli;
		$idsolicitud	= $_REQUEST['id'];
		$comentario = $_REQUEST['coment'];
		$usuario 	= $_SESSION['usuario_sen'];
		$idusuario 	= $_SESSION['user_id_sen'];
		$fecha 		= date("Y-m-d");
		
		if($comentario != ''){
			$query = "INSERT INTO comentarios VALUES(null, 'Solicitudes', ".$idsolicitud.", '".$comentario."', '".$usuario."', NOW(), 'NO')";
			if($mysqli->query($query)){
				$id = $mysqli->insert_id;
				//BITACORA
				bitacora($_SESSION['usuario_sen'], "Solicitudes", "Se ha registrado un Comentario para la Solicitud #".$idsolicitud, $idsolicitud, $query);				
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}

	function eliminarComentario(){
		global $mysqli;

		$idsolicitud = $_REQUEST['idsolicitud'];
		$id 	 	 = $_REQUEST['idcomentario']; 
		$nivel 	 	 = $_SESSION['nivel_sen'];
		$usuario 	 = $_SESSION['usuario_sen'];
		 
		//Elimino el comentario si es usuario administrador o soporte
		if ($nivel == 1){
			$queryEs    = " DELETE FROM comentarios WHERE id = '$id'";
			$resultEs   = $mysqli->query($queryEs);
			if($resultEs){
				echo 1;
			}else{
			    echo 0;
			}
		}else{
			//Consulto si el usuario es el creador del comentario
		    $queryNoes  = "  SELECT * FROM comentarios WHERE id = ".$id." AND usuario = '".$usuario."' ";
    	    $resultNoes =    $mysqli->query($queryNoes);
			if($resultNoes->num_rows > 0){
				//Elimino el comentario
				$query  = "  DELETE FROM comentarios WHERE id = ".$id." AND usuario = '".$usuario."' ";
				$resultSi =    $mysqli->query($query);
				if($resultSi == true){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 2;
			}
		}
		bitacora($_SESSION['usuario_sen'], "Solicitudes", 'El Comentario #: '.$id.' fue eliminado.', $id, $query); 
	}
	
	function abrirSolicitudes() {
		$solicitud 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		//SOLICITUDES
		$myPathSol = '../solicitudes';
		$target_pathSol = utf8_decode($myPathSol);
		if (!file_exists($target_pathSol)) {
			mkdir($target_pathSol, 0777);
		}  
		//RUTA
		$Path = '../solicitudes/'.$solicitud.'/'; 
		$hash = strtr(base64_encode($Path), '+/=', '-_.');
		$hash = rtrim($hash, '.');
		echo "l1_". $hash;
	}
	 
	function editarJunta(){
		global $mysqli;
		
		$idsolicitud = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$junta 	 	 = (!empty($_REQUEST['medicos']) ? $_REQUEST['medicos'] : ''); 
		
		$camposold = getRegistroSQL("	SELECT 
											GROUP_CONCAT(c.nombre,' ',c.apellido) AS 'Junta'
										FROM 
											solicitudes a	
										LEFT JOIN medicos c ON FIND_IN_SET(c.id,a.junta)
										WHERE a.id = ".$idsolicitud."
										");
		
		$query 	= "	UPDATE 
						solicitudes 
					SET 
						junta = '".$junta."'
					WHERE 
						id = '".$idsolicitud."' ";
		
		$result = $mysqli->query($query);
		
		if($result == true){
			//bitacora("Calendario", "Fue modificada la junta evaluadora de la solicitud #".$idsolicitud, $idsolicitud, $query); 
			$camposnew = array( 
				'Junta' => getValoresA("nombre,' ',apellido",'medicos',$junta,'medico'), 
			); 
			actualizarRegistro('Agenda','Agenda',$idsolicitud,$camposold,$camposnew,$query);
			echo 1;
		}else{
			echo $query;
		}
	}
		
	/* function editarNoAsistio(){
		global $mysqli;
		
		$query = "
					SELECT 
						id,estatus,fecha_solicitud,fecha_cita,DATEDIFF( NOW() , fecha_cita ) AS dias 
					FROM 
						solicitudes 
					WHERE 
						estatus = 2 AND DATEDIFF( NOW() , fecha_cita ) > 1 AND DATEDIFF( NOW() , fecha_cita ) < 7 ";
					
		$result = $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$sql = "
					UPDATE 
						solicitudes 
					SET 
						estatus = 6,
						fechacambioestado = NOW()
					WHERE 
						id = ".$row['id']."";
						
			$rsql = $mysqli->query($sql);		
			if($rsql == true){
				
				bitacora("Solicitudes", "Fue cambiado el estado de la solicitud ".$row['id']." a No asistió", $row['id'], $sql);   
				$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
							VALUES(".$row['id'].", ".$_SESSION['user_id_sen'].", CURDATE(), '".$row['estatus']."', '6') "; 
				
				$mysqli->query($queryE);
				
			}
		}  
	} */
	
	function listarCertificados(){
		global $mysqli;
		
		$idsolicitud = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : ''); 
		
		$sql = " SELECT a.id, a.archivo, a.fecha, b.nombre
				 FROM resolucionemision a
				 INNER JOIN usuarios b ON b.usuario = a.usuario
				 WHERE 1";
				 
		if($idsolicitud !== ''){
			$sql .=" AND idsolicitud = ".$idsolicitud." ";
		}
		$sql .=" ORDER BY fecha DESC";
		
		$rta = $mysqli->query($sql);
		$resultado = array();
		while($row = $rta->fetch_assoc()){		
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'archivo'	=>	'<a target="_blank" href="./solicitudes/'.$idsolicitud.'/'.$row['archivo'].'">'.$row['archivo'].'</a>',
				'usuario'   =>  $row['nombre'], 
				'fecha'		=>	$row['fecha']
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
	
	function asignarCodigoResolucion(){
		global $mysqli;
		
		$idsolicitud = (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '');
		
		$sqlP = " SELECT b.nombre AS regional FROM solicitudes a INNER JOIN regionales b ON b.id = a.regional WHERE a.id = ".$idsolicitud;
		$rtaP = $mysqli->query($sqlP);
		if($rowP = $rtaP->fetch_assoc()){
			$regional = $rowP['regional'];
			
			($regional == 'Panamá Oeste') ?	$inicReg = 'PAO' : $inicReg = strtoupper(substr($rowP['regional'], 0, 3));
			
			$sqlR = " SELECT nro_resolucion FROM modulos_nroresolucion WHERE SUBSTRING(nro_resolucion,1,3) = '".$inicReg."' ORDER BY id DESC LIMIT 1";
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
			echo json_encode($codigo);
		}
	}	
	
	function cambiarEstado(){
		global $mysqli;
		 
		$id  = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$tipo  = (!empty($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '');
		$idestadosold = getValor('estatus','solicitudes',$id,''); 
		$usuario = $_SESSION['usuario_sen'];
		
		if($tipo == 'reconsideracion'){
			$idestados = 5;
		}elseif($tipo == 'apelacion'){
			$idestados = 31;
		}elseif($tipo == 'rcg'){
			$idestados = 27;
		}elseif($tipo == 'rng'){
			$idestados = 28;
		}

		$reconsideracion = getValor('reconsideracion','solicitudes',$id);
		$apelacion = getValor('apelacion','solicitudes',$id);

		$camposold = getRegistroSQL("	SELECT b.descripcion AS 'Estatus'
										FROM solicitudes a 
										INNER JOIN estados b ON b.id = a.estatus 
										WHERE a.id = ".$id." ");
		
		$query = "  UPDATE 
						solicitudes 
					SET 
						estatus  = '".$idestados."'";
						
		if($tipo == 'reconsideracion'){
			if($reconsideracion == 0){
				$query .= ", reconsideracion = 1";	
			}
			if($reconsideracion == 1){
				$query .= ", reconsideracion = 2";	
			}
		}
		if($tipo == 'apelacion'){
			$query .= ", apelacion = 1";	
		}				
						
		$query .= "	WHERE 
						id = '".$id."' ";
		
		
		if($result = $mysqli->query($query)){ 
		
			//Crear registro en solicitudes_estados
			$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
						VALUES(".$id.", ".$usuario.", CURDATE(), '".$idestadosold."', '".$idestados."') "; 
			$mysqli->query($queryE);
			
			$camposnew = array(
				'Estatus' => getValor('descripcion','estados',$idestados,'')
			);
			//Guardar en bitácora
			actualizarRegistro('Solicitudes','Solicitudes',$id,$camposold,$camposnew,$query);
				
			echo 1;
		}else{
			echo $query;
		}
	}
	 	
	function day_to_words($day) {
		$ones = array("", "uno", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve", "diez", "once", "doce", "trece", "catorce", "quince", "dieciséis", "diecisiete", "dieciocho", "diecinueve", "veinte", "veintiuno", "veintidós", "veintitrés", "veinticuatro", "veinticinco", "veintiséis", "veintisiete", "veintiocho", "veintinueve", "treinta", "treinta y uno");
		if ($day < 1 || $day > 31) {
			return "fuera de rango";
		} else {
			return $ones[$day];
		}
	}
	
	function numberToWords($year) {
		$num_letras = array(
			10 => 'diez',
			11 => 'once',
			12 => 'doce',
			13 => 'trece',
			14 => 'catorce',
			15 => 'quince',
			16 => 'dieciséis',
			17 => 'diecisiete',
			18 => 'dieciocho',
			19 => 'diecinueve',
			20 => 'veinte',
			21 => 'veintiuno',
			22 => 'veintidós',
			23 => 'veintitrés',
			24 => 'veinticuatro',
			25 => 'veinticinco',
			26 => 'veintiseis',
			27 => 'veintisiete',
			28 => 'veintiocho',
			29 => 'veintinueve',
			30 => 'treinta',
			31 => 'treinta y uno',
			32 => 'treinta y dos',
			33 => 'treinta y tres',
			34 => 'treinta y cuatro',
			35 => 'treinta y cinco',
		);
		
		$year_str = strval($year);
		$result = '';

		if (strlen($year_str) == 4) {
			$result = 'dos mil ';
			if ($year_str[2] . $year_str[3] != '00') {
				$result .= $num_letras[$year_str[2] . $year_str[3]];
			}
		}

		return $result;
	}
?>