<?php
    include_once("conexion.php");
    sessionrestore();
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "eventos": 
              eventos();
			  break;
	    case "guardarfiltros": 
              guardarfiltros();
			  break;
		case "abrirfiltros": 
              abrirfiltros();
			  break;
	    case "verificarfiltros": 
              verificarfiltros();
			  break;
	    case "limpiarFiltrosMasivos": 
              limpiarFiltrosMasivos();
			  break;
		case "actualizarEstado":
			  actualizarEstado();
	  		  break;
		default:
			  echo "{failure:true}";
			  break;
	}
	
	function eventos(){
		global $mysqli;
		/* $where = ""; */
		$whereA = "";
		$whereB = "";
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');		
		$start  = $_REQUEST['start'];
		$end   	= $_REQUEST['end'];
		$nivel 	= $_SESSION['nivel_sen'];
		$query 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$_SESSION['usuario_sen']."'";		
		$result = $mysqli->query($query);
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			if (!isset($_REQUEST['data'])) {
				$data = $row['filtrosmasivos'];
			}
		}
		if($data != ''){
			$data 				= json_decode($data);
			$desdef 			= (isset($data->desdef) ? $data->desdef : '');
			$hastaf 			= (isset($data->hastaf) ? $data->hastaf : '');
			$idempresasf 		= (isset($data->idempresasf) ? $data->idempresasf : '');
			$idclientesf 		= (isset($data->idclientesf) ? $data->idclientesf : '');
			$idproyectosf 		= (isset($data->idproyectosf) ? $data->idproyectosf : '');
			$idserviciosf 		= (isset($data->idserviciosf) ? $data->idserviciosf : '');
			$idsistemasf 		= (isset($data->idsistemasf) ? $data->idsistemasf : '');
			$idambientesf 		= (isset($data->idambientesf) ? $data->idambientesf : '');
			$idsubambientesf 	= (isset($data->idsubambientesf) ? $data->idsubambientesf : '');
			$idprioridadesf 	= (isset($data->idprioridadesf) ? $data->idprioridadesf : '');			
			$idestadosf 		= (isset($data->idestadosf) ? $data->idestadosf : '');
			$iddepartamentosf 	= (isset($data->iddepartamentosf) ? $data->iddepartamentosf : '');
			$asignadoaf 		= (isset($data->asignadoaf) ? $data->asignadoaf : '');
			$solicitantef 		= (isset($data->solicitantef) ? $data->solicitantef : '');
			
			if($desdef != ''){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND a.fechacreacion >= $desdef ";
			}
			if($hastaf != ''){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND a.fechacreacion <= $hastaf ";
			}
			if($idempresasf != ''){
				$where2 .= " AND a.idempresas IN ($idempresasf)";
			}
			if($idclientesf != '' ){
				$where2 .= " AND a.idclientes IN ($idclientesf)";
			}
			if($idproyectosf != ''){
				$where2 .= " AND a.idproyectos IN ($idproyectosf)";
			}
			if(!empty($data->idserviciosf)){
				$idserviciosf = json_encode($data->idserviciosf);
				$where2 .= " AND a.idservicios IN ($idserviciosf)";
			}
			if(!empty($data->idsistemasf)){
				$idsistemasf = json_encode($data->idsistemasf);
				$where2 .= " AND a.idsistemas IN ($idsistemasf)";
			}
			if(!empty($data->idambientesf)){
				$idambientesf = json_encode($data->idambientesf);
				$where2 .= " AND a.idambientes IN ($idambientesf)";
			}
			if(!empty($data->idsubambientesf)){
				$idsubambientesf = json_encode($data->idsubambientesf);
				$where2 .= " AND a.idsubambientes IN ($idsubambientesf)";
			}
			if(!empty($data->idprioridadesf)){
				$idprioridadesf = json_encode($data->idprioridadesf);
				$where2 .= " AND a.idprioridades IN ($idprioridadesf)";
			}
			if(!empty($data->idestadosf)){
				$idestadosf = json_encode($data->idestadosf);
				$where2 .= " AND a.idestados IN ($idestadosf)";
			}
			if($iddepartamentosf != '' ){
				$where2 .= " AND a.iddepartamentos IN ($iddepartamentosf)";
			}
			if(!empty($data->asignadoaf)){
				$asignadoaf = json_encode($data->asignadoaf);
				$where2 .= " AND a.asignadoa IN ($asignadoaf)";
				/*
				$asignadoaf = json_encode($data->asignadoaf);
				$i = 0;
				foreach($data->asignadoaf as $usuarios){
					if($i > 0)
						$asignadoaf .=",";
					$asignadoaf .= "'$usuarios'";
					$i++;
				}
				if($asignadoaf != "''"){
					$where2 .= " AND a.asignadoa IN ($asignadoaf)";	
				}
				*/
			}
			if(!empty($data->solicitantef)){
				$solicitantef = json_encode($data->solicitantef);
				$where2 .= " AND a.solicitante IN ($solicitantef)";
			}
			if(!empty($data->tipoinc) && empty($data->tipoprev)){ 
				$where2 .= " AND a.tipo = 'incidente' "; 
			}	
			if(empty($data->tipoinc) && !empty($data->tipoprev)){ 
				$where2 .= " AND a.tipo = 'preventivo' ";
			}
			if(!empty($data->tipoinc) && !empty($data->tipoprev)){ 
				$where2 .= " AND a.tipo IN ('incidente','preventivo')"; 
			}
				
			$vowels = array("[", "]");
			$where2 = str_replace($vowels, "", $where2);
			$whereB = str_replace($vowels, "", $whereB);
		}
		//INCIDENTES - PREVENTIVOS
		$query  = "	(SELECT a.id,a.titulo,a.tipo,a.idestados, 
					CONCAT(IFNULL(a.fechacreacion,CURDATE()), ' ', IFNULL(a.horacreacion, CURTIME() ) ) as fecha1, 
					CONCAT(IFNULL(a.fechacreacion,CURDATE()), ' ', IFNULL(ADDTIME(a.horacreacion, '00:20:00'), ADDTIME(CURTIME(), '00:20:00') ) ) as fecha2 
					FROM incidentes a ";
		if($nivel!= 4){
			$query.= "WHERE 1 = 1";
		}else{
			$query.= "	INNER JOIN usuarios c ON a.asignadoa = c.correo
						INNER JOIN usuarios d ON a.creadopor = d.correo
						WHERE 1 = 1
						AND c.usuario = '".$_SESSION['usuario_sen']."' OR d.usuario = '".$_SESSION['usuario_sen']."'";
		}
		$query  .= "$where2";
		//ACTIVIDADES DE PROYECTOS
		$query 	.= ")UNION
					(SELECT a.id, a.titulo,a.tipo,a.idestados, 
					CONCAT(IFNULL(DATE(a.start_date),CURDATE()), ' ', IFNULL(TIME(a.start_date), CURTIME() ) ) as fecha1,
					CONCAT(IFNULL(DATE(a.start_date),CURDATE()), ' ', IFNULL(ADDTIME(TIME(a.start_date), '00:20:00'), ADDTIME(CURTIME(), '00:20:00') ) ) as fecha2
					FROM proyectosganttdet a ";
		if($nivel!= 4){
			$query.= "WHERE 1 = 1";
		}else{
			$query.= "	INNER JOIN usuarios c ON a.asignadoa = c.correo
						INNER JOIN usuarios d ON a.creadopor = d.correo
						WHERE 1 = 1
						AND c.usuario = '".$_SESSION['usuario_sen']."' OR d.usuario = '".$_SESSION['usuario_sen']."'";
		}
		$query  .= "$where2";
		//$query 	.= ")"; 
		//REUNIONES
		$query 	.= ")UNION
					(SELECT a.id, a.titulo,'reunion',a.idestados, 
					CONCAT(IFNULL(DATE(a.fechaplan),CURDATE()), ' ', IFNULL(TIME(a.horainicioplan), CURTIME() ) ) as fecha1,
					CONCAT(IFNULL(DATE(a.fechaplan),CURDATE()), ' ', IFNULL(ADDTIME(TIME(a.horainicioplan), '00:20:00'), ADDTIME(CURTIME(), '00:20:00') ) ) as fecha2
					FROM reuniones a ";
		if($nivel!= 4){
			$query.= "WHERE 1 = 1";
		}else{
			$query.= "	INNER JOIN usuarios c ON a.moderador = c.correo
						WHERE 1 = 1
						AND c.usuario = '".$_SESSION['usuario_sen']."' ";
		}
		$query  .= "$where2";
		$query 	.= ")"; 
		
		if($start != ''){
			//$query .= " AND fechacreacion >= '".$start."' "; 
		}
		if($end != ''){
			//$query .= " AND fechacreacion <= '".$end."' "; 
		}
		
		//debug($query);
		/* if ($hayFiltros > 0)
			$where = " AND ".implode(" AND " , $where)." ";// id like '%searchValue%' or name like '%searchValue%'
		else
			$where = ""; */
		
		/* $query  .= " $where $where2"; */
		//$query  .= "$where2"; 
		//$query  .= " GROUP BY a.id "; 
		//debugL('QF'.$query);
		
		$result = $mysqli->query($query); 
		$count = $result->num_rows;		
		$row = $result->fetch_assoc();
		$fecha1 = new DateTime($row['fecha1']);
		$fecha2 = new DateTime($row['fecha2']);
		$auxfecha = new DateTime($row['fecha1']);
		$minutos = 30;
		$i=0; $id=1;
		$event_array = '';
		$hora = 7;
		$result = $mysqli->query($query); 
		while($row = $result->fetch_assoc()){
			$titulo = utf8_decode($row['titulo']);
			$estatus = $row['idestados'];
			$tipo = $row['tipo'];
			if ($tipo == 'incidente') {
				$className  = 'eventIncidente';
			} else if($estatus == '17') {
				$className = 'eventOrdenFin';
			} else if($tipo == 'preventivo'){
				$className = 'eventOrden';
			} else if($tipo =='proyecto'){
				$className = 'eventProy';
			} else if($tipo =='reunion'){
				$className = 'eventReu';
			}
			
			$fecha1 = new DateTime($row['fecha1']);
			$fecha2 = new DateTime($row['fecha2']);
			if ($auxfecha->format('Y-m-d') == $fecha1->format('Y-m-d')) {
				$fecha1->add(new DateInterval('PT'. $minutos .'M'));
				$row['fecha1'] = $fecha1->format('Y-m-d H:i:s');
				$fecha2->add(new DateInterval('PT'. $minutos  .'M'));
				$row['fecha2'] = $fecha2->format('Y-m-d H:i:s');
				$minutos += 30;
			} else {
				$minutos = 30;
				$fecha1->add(new DateInterval('PT'. $minutos .'M'));
				$row['fecha1'] = $fecha1->format('Y-m-d H:i:s');
				$fecha2->add(new DateInterval('PT'. $minutos  .'M'));
				$row['fecha2'] = $fecha2->format('Y-m-d H:i:s');
				$auxfecha = new DateTime($row['fecha1']);
				$minutos += 30;
			}
			
			$event_array[] = array(
				'id' 		=> $row['id'],
				'title'		=> $row['titulo'].': '.utf8_encode($titulo),
				'start' 	=> $row['fecha1'],
				'end' 		=> $row['fecha2'],
				'tipo' 		=> $row['tipo'],
				'className' => $className,
			);
		}       
		echo json_encode($event_array);	
	}

/* 	function eventos() 
	{
		global $mysqli;
		
		$start = $_REQUEST['start'];
		$end   = $_REQUEST['end'];
		$chkt = (!empty($_REQUEST['chkt']) ? $_REQUEST['chkt'] : '');
		$chki = (!empty($_REQUEST['chki']) ? $_REQUEST['chki'] : '');
		$chkp = (!empty($_REQUEST['chkp']) ? $_REQUEST['chkp'] : '');
		$chka = (!empty($_REQUEST['chka']) ? $_REQUEST['chka'] : '');
		$chkc = (!empty($_REQUEST['chkc']) ? $_REQUEST['chkc'] : '');
		$cmbr = (!empty($_REQUEST['cmbr']) ? $_REQUEST['cmbr'] : '0');
		debug($chkt.', '.$chki.', '.$chkp.', '.$chka.', '.$cmbr);
		$query  = "SELECT id,titulo,tipo,idestados, DATE_ADD(fechacreacion, INTERVAL 400 MINUTE) as fecha1, DATE_ADD(fechacreacion, INTERVAL 420 MINUTE) as fecha2 ";
		$query .= "FROM incidentes ";
		$query .= "WHERE 1=1 ";
		
		if ($start == "")
			$query .= "AND fechacreacion >= subdate(CURDATE(),45) ";
		else
			$query .= "AND fechacreacion >= '$start' ";
		
		if ($end == "")
			$query .= "AND   fechacreacion <= adddate(CURDATE(),45) ";
		else
			$query .= "AND   fechacreacion <= '$end' ";
		*/
		/* if ($chki == 'true' AND $chkp == 'true')
			$query .= "AND   tipo = tipo "; */
		/*if ($chki == 'true')
			$query .= "AND   tipo = 'incidente' ";
		elseif ($chkp == 'true')
			$query .= "AND   tipo = 'preventivo' ";
		
		if ($chka == 'true' AND $chkc == 'true')
			$query .= "AND   estado = estado ";
		elseif ($chka == 'true')
			$query .= "AND   estado <> '36' ";
		elseif ($chkc == 'true')
			$query .= "AND   estado = '36' ";
		
		
		if ($cmbr != '0')
			$query .= "AND   proveedor like '%$cmbr%' ";
		
		
		$result = $mysqli->query($query); 
		$count = $result->num_rows;		
		$row = $result->fetch_assoc();
		$fecha1 = new DateTime($row['fecha1']);
		$fecha2 = new DateTime($row['fecha2']);
		$auxfecha = new DateTime($row['fecha1']);
		$minutos = 30;
		$i=0; $id=1;
		$event_array = '';
		$hora = 7;
		$result = $mysqli->query($query); 
		while($row = $result->fetch_assoc()){
			$titulo = utf8_decode($row['titulo']);
			$estatus = $row['estado'];
			$tipo = $row['tipo'];
			if ($tipo == 'incidente') {
				$className  = 'eventIncidente';
			} else if($estatus == '36') {
				$className = 'eventOrdenFin';
			} else{
				$className = 'eventOrden';
			}
			
			$fecha1 = new DateTime($row['fecha1']);
			$fecha2 = new DateTime($row['fecha2']);
			if ($auxfecha->format('Y-m-d') == $fecha1->format('Y-m-d')) {
				$fecha1->add(new DateInterval('PT'. $minutos .'M'));
				$row['fecha1'] = $fecha1->format('Y-m-d H:i:s');
				$fecha2->add(new DateInterval('PT'. $minutos  .'M'));
				$row['fecha2'] = $fecha2->format('Y-m-d H:i:s');
				$minutos += 30;
			} else {
				$minutos = 30;
				$fecha1->add(new DateInterval('PT'. $minutos .'M'));
				$row['fecha1'] = $fecha1->format('Y-m-d H:i:s');
				$fecha2->add(new DateInterval('PT'. $minutos  .'M'));
				$row['fecha2'] = $fecha2->format('Y-m-d H:i:s');
				$auxfecha = new DateTime($row['fecha1']);
				$minutos += 30;
			}
			
			$event_array[] = array(
				'id' 		=> $row['id'],
				'title'		=> $row['titulo'].': '.utf8_encode($titulo).' - '.$row['proveedor'],
				'start' 	=> $row['fecha1'],
				'end' 		=> $row['fecha2'],
				'tipo' 		=> $row['tipo'],
				'className' => $className,
			);
		}       
		echo json_encode($event_array);	
	} */
	
	function guardarfiltros() {
		global $mysqli;
		$data = $_REQUEST['data'];
		$usuario = $_SESSION['usuario_sen'];
		$query  = " SELECT * FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";

		$result = $mysqli->query($query);
		$count = $result->num_rows;
		
		if( $count > 0 ) 
			$query = "UPDATE usuariosfiltros SET filtrosmasivos = '".$data."' WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";
		else
			$query = "INSERT INTO usuariosfiltros VALUES (null, '".$usuario."', 'Calendario', '', '".$data."')";
		if($mysqli->query($query))
			echo true;		
	} 
	
	function abrirfiltros() {
		global $mysqli;
		$usuario = $_SESSION['usuario_sen'];
		
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";
		$result = $mysqli->query($query);
		$response = new StdClass;
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			$data = $row['filtrosmasivos'];
			$response->data = $data;
		} else {
			$response->data = '';
		}
		
		$response->success = true;
		echo json_encode($response);
	}
	
	function verificarfiltros() {
		global $mysqli;
		$usuario = $_SESSION['usuario_sen'];
		
		$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";
		$result = $mysqli->query($query);
		$response = 0;
		if($result->num_rows >0){
			$row = $result->fetch_assoc();				
			$data = $row['filtrosmasivos'];
			$filtrosmasivos = json_decode($data);
			foreach($filtrosmasivos as $clave => $valor){
				if($valor != '' || $valor != 0){
					$response = 1;
				}
			}
		} else {
			$response = 0;
		}
		echo $response;
	}
	
	function limpiarFiltrosMasivos(){
		global $mysqli;
		$usuario = $_SESSION['usuario_sen'];
		
		$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$usuario."' ";
		if($mysqli->query($query))
			echo true;
	}
	
	function actualizarEstado(){
		global $mysqli;		
		$idsolicitud	= $_REQUEST['idsolicitud'];
		$estado 		= $_REQUEST['estado'];
		$fecha			= $_REQUEST['fecha'];
		$idusuario 		= $_SESSION['user_id_sen'];
		
        $valorviejo = getRegistroSQL(" SELECT p.estatus FROM solicitudes p WHERE p.id= '".$idsolicitud."'");
		$idestadosOld = getValor('estatus','solicitudes',$idsolicitud,''); 
        if(intval($estado) == 7){
			$fechaanterior = getRegistroSQL(" SELECT p.fecha_cita FROM solicitudes p WHERE p.id = '".$idsolicitud."'");
			$camposold = getRegistroSQL(" 	SELECT 
												b.descripcion AS 'Estado', a.fecha_cita AS 'Fecha cita'
											FROM solicitudes a 
											LEFT JOIN estados b ON b.id = a.estatus
											WHERE a.id = '".$idsolicitud."'");
	        $queryS = "	UPDATE solicitudes SET estatus = '".$estado."', fecha_cita = '".$fecha."' 
						WHERE id = ".$idsolicitud." ";
			$resultS = $mysqli->query($queryS);
			if($resultS == true){
				//Crear registro en solicitudes_estados
				$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
							VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), '".$idestadosOld."', '".$estado."') ";
				$mysqli->query($queryE);
				$camposnew = array(  
					'Estado' 	 => getValor('descripcion','estados',$estado,''),
					'Fecha cita' => $fecha
				); 
				actualizarRegistro('Agenda','Agenda',$idsolicitud,$camposold,$camposnew,$queryS);
			}
			$queryC = "	UPDATE citas SET estado = '".$estado."', fecha_cita = '".$fecha."' 
						WHERE idsolicitud = ".$idsolicitud." AND fecha_cita = '".$fechaanterior['fecha_cita']."' ";
			$resultC = $mysqli->query($queryC);
		}elseif(intval($estado) == 12){
			$camposold = getRegistroSQL(" 	SELECT 
												b.descripcion AS 'Estado', a.fecha_cita AS 'Fecha cita'
											FROM solicitudes a 
											LEFT JOIN estados b ON b.id = a.estatus
											WHERE a.id = '".$idsolicitud."'");
	        $queryS = "UPDATE solicitudes SET estatus = '".$estado."', fecha_cita = null WHERE id = ".$idsolicitud." ";
			$resultS = $mysqli->query($queryS);
			$camposnew = array(  
				'Estado' 	 => getValor('descripcion','estados',$estado,''),
				'Fecha cita' => $fecha
			); 
			actualizarRegistro('Agenda','Agenda',$idsolicitud,$camposold,$camposnew,$queryS);
			if($resultS == true){
				//Crear registro en solicitudes_estados
				$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
							VALUES(".$idsolicitud.", ".$_SESSION['user_id_sen'].", CURDATE(), '".$idestadosOld."', '".$estado."') ";
				$mysqli->query($queryE);
			}
			$queryC = "	UPDATE citas SET estado = '".$estado."' WHERE idsolicitud = ".$idsolicitud." AND fecha_cita = '".$fecha."' ";
			$resultC = $mysqli->query($queryC);
		}
		//debugL($queryS);
		//debugL($queryC);
		echo true;
		if($resultS == true && $resultC == true){			
			echo true;
		}else{
			echo false;
		}
	}
	
?>