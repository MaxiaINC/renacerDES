<?php
    include("conexion.php");
    sessionrestore();
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "enfermedades":
			enfermedades();
		break;		
		case "enfermedades2":
			enfermedades2();
		break;
		case "enfermedades3":
			enfermedades3();
		break;
		case "estadossolicitudes":
			estadossolicitudes();
		break;
		case "grupo_cif":
			grupo_cif();
		break;
		case "discapacidades":
			discapacidades();
		break;
		case "estados":
			estados();
		break;
		case "cif":
			cif();
		break;
		case "regionales":
			regionales();
		break;
		case "regionalesUsu":
			regionalesUsu();
		break;
		case "regionalesPorNivel":
			regionalesPorNivel();
		break;
		case "provincia":
			provincia();
		break;
		case "distrito":
			distrito();
		break;
		case "corregimiento":
			corregimiento();
		break;
		case "especialidades":
			especialidades();
		break;		
		case "especialistas":
			especialistas();
		break;
		case "especialistasArray":
			especialistasArray();
		break;
		case "pacientes":
			pacientes();
		break;
		case "pacientesArray":
			pacientesArray();
		break;
		case "campos_plantilla":
			campos_plantilla();
		break;
		case "provinciaDash":
			provinciaDash();
		break;
		case "distritoDash":
			distritoDash();
		break;
		case "corregimientoDash":
			corregimientoDash();
		break;
		case "condicionsalud":
			condicionsalud();
		break;
		case "niveles":
	  		  niveles();
	  		  break;
		case "medicos":
	  		  medicos();
	  		  break;
		case "solicitudesPaciente":
	  		  solicitudesPaciente();
	  		  break;
		case "estadosauditoria":
	  		  estadosauditoria();
	  		  break;
		case "auditores":
	  		  auditores();
	  		  break;
		case "regionalesAuditoria":
			 regionalesAuditoria();
			 break;
		case "administradores":
			  administradores();
			 break;
		case "nacionalidades":
			  nacionalidades();
			 break;
		case "pacienteshabilitacionjuntas":
			pacienteshabilitacionjuntas();
			break;	
		default:
			  echo "{failure:true}";
			  break;

	}
	function especialistas(){
		global $mysqli;
		$regional 	  = (!empty($_REQUEST['regional']) ? $_REQUEST['regional'] : '');
		$discapacidad = (!empty($_REQUEST['discapacidad']) ? $_REQUEST['discapacidad'] : '');
		$especialidad = (!empty($_REQUEST['especialidad']) ? $_REQUEST['especialidad'] : '');
		$query = "SELECT DISTINCT m.id,m.cedula,concat(m.nombre,' ',m.apellido) as nombre, e.nombre as especialidad FROM medicos m 
					LEFT JOIN  especialidades e ON e.id = m.especialidad
					WHERE 1 ";
// 		if($regional != ''){
// 			$query .= " AND FIND_IN_SET('$regional',m.regional) ";
// 		}
		if($especialidad != ''){
			$query .= " AND FIND_IN_SET('$especialidad',m.especialidad)";
		}
		if($discapacidad != ''){
			$query .= " AND FIND_IN_SET('$discapacidad',m.discapacidades) ";
		}
		// echo $query;
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."' data-cedula='".$row['cedula']."' data-especialidad='".$row['especialidad']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	function especialistasCedula(){
		global $mysqli;
		$regional 	  = (!empty($_REQUEST['regional']) ? $_REQUEST['regional'] : '');
		$discapacidad = (!empty($_REQUEST['discapacidad']) ? $_REQUEST['discapacidad'] : '');
		$especialidad = (!empty($_REQUEST['especialidad']) ? $_REQUEST['especialidad'] : '');
		$query = "SELECT DISTINCT m.id,concat(m.cedula,' - ',m.nombre,' ',m.apellido) as nombre, e.nombre as especialidad FROM medicos m 
					LEFT JOIN  especialidades e ON e.id = m.especialidad
					WHERE 1 ";
// 		if($regional != ''){
// 			$query .= " AND FIND_IN_SET('$regional',m.regional) ";
// 		}
		if($especialidad != ''){
			$query .= " AND FIND_IN_SET('$especialidad',m.especialidad)";
		}
		if($discapacidad != ''){
			$query .= " AND FIND_IN_SET('$discapacidad',m.discapacidades) ";
		}
		// echo $query;
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."' data-especialidad='".$row['especialidad']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	function especialidades(){
		global $mysqli;
		$query ="SELECT DISTINCT id,nombre FROM especialidades;";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	function regionales(){
		global $mysqli;
		$query ="SELECT DISTINCT id,codigo,nombre FROM regionales;";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['codigo']." | ".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	function regionalesPorNivel(){
		global $mysqli;
		$user_id = $_SESSION['user_id_sen'];
		$regional_usu = getValor('regional','usuarios',$user_id);
		
		$query ="SELECT DISTINCT id,codigo,nombre 
				 FROM regionales
				 WHERE 1 ";
		if($regional_usu != 'Todos' && $regional_usu != '' && $regional_usu != null){
			$query .= " AND nombre IN ('".$regional_usu."') ";
		}	
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['codigo']." | ".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	function regionalesUsu(){
		global $mysqli;
		$query ="SELECT DISTINCT id,nombre FROM regionales;";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		$combo .= "<option value='Todos'>Todos</option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['nombre']."'>".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	function estadossolicitudes(){
		global $mysqli;
		$query ="SELECT DISTINCT id,descripcion FROM estados where tipo = 'solicitud' AND estado = 'Activo' ";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['descripcion']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	function provincia(){
		global $mysqli;
		
		$query ="SELECT DISTINCT provincia FROM direcciones;";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['provincia']."'>".$row['provincia']."</option>";
		}
		echo $combo;
	}	
	function distrito(){
		global $mysqli;
		$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '');
		
		$query ="SELECT DISTINCT distrito FROM direcciones WHERE provincia	= '".$provincia."'; ";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['distrito']."'>".$row['distrito']."</option>";
		}
		echo $combo;
	}	
	function corregimiento(){
		global $mysqli;
		$provincia = (!empty($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '');
		$distrito = (!empty($_REQUEST['distrito']) ? $_REQUEST['distrito'] : '');
		
		$query ="SELECT DISTINCT id, corregimiento, area FROM direcciones WHERE provincia ='".$provincia."' AND distrito ='".$distrito."'; ";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['corregimiento']."' data-id='".$row['id']."' data-area='".$row['area']."' >".$row['corregimiento']."</option>";
		}
		echo $combo;
	}

	function enfermedades(){
		global $mysqli;
		$query ="SELECT id, codigo, nombre FROM enfermedades ";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."' data-codigo='".$row['codigo']."'>".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	function discapacidades(){
		global $mysqli;
		$query ="SELECT id, nombre FROM discapacidades;";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	function estados(){
		global $mysqli;
		$query ="SELECT id, descripcion AS nombre FROM estados WHERE id NOT IN (11,13,14,15,17,20) AND estado = 'Activo' ";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}
	function enfermedades2(){
		global $mysqli;
		$query ="SELECT id, codigo, nombre FROM enfermedades";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()){
			$resultado[] = array(
				'id'	=> $row['id'],
				'text'	=> $row['codigo'].' | '.$row['nombre']				
			);
		}
		echo json_encode($resultado);
	}

	function pacientes(){
		global $mysqli;
		$query ="SELECT id, CONCAT(cedula, ' | ',nombre,' ',apellidopaterno, ' ', apellidomaterno) as nombre FROM pacientes";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while ($row = $result->fetch_assoc()){
				$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";	
		}
		$combo .= "</select>";
		echo $combo;
	}
	
	function especialistasArray() {
		global $mysqli;
		$search = $_REQUEST['search'];
		$query = "  SELECT a.id, a.cedula, a.nombre AS nombremedico, b.nombre AS especialidad,
					CONCAT(a.cedula, ' | ', a.nombre, ' ', a.apellido) AS text 
					FROM medicos a
					LEFT JOIN especialidades b ON b.id = a.especialidad
					WHERE a.cedula LIKE '%".$search."%' OR CONCAT(a.nombre, ' ', a.apellido) LIKE '%".$search."%'";
					
		$result = $mysqli->query($query);
		$resultado = array();
		while ($row = $result->fetch_assoc()) {
			$resultado[] = array(
				'id' => $row['id'],
				'cedula' => $row['cedula'],
				'nombremedico' => $row['nombremedico'],
				'especialidad' => $row['especialidad'],
				'text' => $row['text']			
			);
		}
		echo json_encode($resultado);
	}
	
	function pacientesArray() {
		global $mysqli;
		$search = $_REQUEST['search'];
		/*$query = "  SELECT id, CONCAT(cedula, ' | ', nombre, ' ', apellidopaterno, ' ', apellidomaterno) AS nombre 
					FROM pacientes
					WHERE cedula LIKE '%".$search."%' OR CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) LIKE '%".$search."%'";*/
		$query = " SELECT a.id, CONCAT(a.cedula, ' | ', a.nombre, ' ', a.apellidopaterno, ' ', a.apellidomaterno) AS nombre
				   FROM pacientes a 
				   INNER JOIN solicitudes b ON b.idpaciente = a.id 
				   WHERE b.estatus IN (2,5,31) AND
				   a.cedula LIKE '%".$search."%' OR CONCAT(a.nombre,' ',a.apellidopaterno,' ',a.apellidomaterno) LIKE '%".$search."%'
				   LIMIT 100
				    ";

		$result = $mysqli->query($query);
		$resultado = array();
		while ($row = $result->fetch_assoc()) {
			$resultado[] = array(
				'id' => $row['id'],
				'text' => $row['nombre']				
			);
		}
		echo json_encode($resultado);
	}
	
	function enfermedades3(){
		global $mysqli;
		$id = $_REQUEST['id'];
		$query ="SELECT id, codigo, nombre FROM enfermedades where id = $id";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()){
			$resultado[] = array(
				'id'	=> $row['id'],
				'text'	=> $row['codigo'].' | '.$row['nombre']				
			);
		}
		echo json_encode($resultado);
	}

	function grupo_cif(){
		global $mysqli;
		$categoria = $_REQUEST['categoria'];
		$query = "SELECT id,codigo,descripcion,nivel FROM `cif` WHERE nivel = 1 AND codigo LIKE '$categoria%'";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."' data-codigo='".$row['codigo']."' data-nombre='".$row['descripcion']."' data-grupo='".$row['codigo']."' data-nivel='".$row['nivel']."' >".$row['codigo']." | ".$row['descripcion']."</option>";
		}
		$combo .= "</select>";		
		echo $combo;	
	}	
	function cif(){
		global $mysqli;
		$grupo = $_REQUEST['grupo'];
		$query = "SELECT id,codigo,descripcion,nivel FROM `cif` WHERE nivel != 1 AND codigo LIKE '$grupo%'";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."' data-codigo='".$row['codigo']."' data-nombre='".$row['descripcion']."' data-nivel='".$row['nivel']."' >".$row['codigo']." | ".$row['descripcion']."</option>";
		}
		$combo .= "</select>";		
		echo $combo;	
	}
	function campos_plantilla(){
		global $mysqli;
		$query ="SELECT nombre,variable FROM campos_plantilla";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while ($row = $result->fetch_assoc()){
				$combo .= "<option value='".$row['variable']."'>".$row['nombre']."</option>";	
		}
		echo $combo;
	}
	function provinciaDash(){
		global $mysqli;
		$query ="SELECT DISTINCT provincia FROM direcciones;";
		$result = $mysqli->query($query);
		$combo = "<option value='0'> ... </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['provincia']."'>".$row['provincia']."</option>";
		}
		echo $combo;
	}	
	function distritoDash(){
		global $mysqli;
		$provincia = $_REQUEST['provincia'];
		$query ="SELECT DISTINCT distrito FROM direcciones WHERE provincia	= '".$provincia."';";
		$result = $mysqli->query($query);
		$combo = "<option value='0'> ... </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['distrito']."'>".$row['distrito']."</option>";
		}
		echo $combo;
	}	
	function corregimientoDash(){
		global $mysqli;
		$provincia = $_REQUEST['provincia'];
		$distrito = $_REQUEST['distrito'];
		$query ="SELECT DISTINCT id, corregimiento, area FROM direcciones WHERE provincia ='$provincia' AND distrito	='$distrito';";
		$result = $mysqli->query($query);
		$combo = "<option value='0'> ... </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['corregimiento']."' data-id='".$row['id']."' data-area='".$row['area']."' >".$row['corregimiento']."</option>";
		}
		echo $combo;
	}
	function condicionsalud(){
		global $mysqli;
		//$provincia = $_REQUEST['provincia'];
		//$distrito = $_REQUEST['distrito'];
		$query ="SELECT DISTINCT(condicionsalud) FROM solicitudes ";
		$result = $mysqli->query($query);
		$combo = "<option value='0'> ... </option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['condicionsalud']."'>".$row['condicionsalud']."</option>";
		}
		echo $combo;
	}
	function niveles(){
	    global $mysqli;

		$combo 	= '';
		$query  = " SELECT id, nombre
					FROM niveles
					WHERE nombre != ''
					AND estado = 'Activo'
					GROUP BY nombre ORDER BY nombre";
		$result = $mysqli->query($query);
		$combo .= "<option value='0'>Sin Asignar</option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}	
	function medicos(){
	    global $mysqli;

		$combo 	= '';
		$query  = " SELECT id, CONCAT(nombre,' ',apellido) AS nombre
					FROM medicos
					WHERE 1
					ORDER BY nombre";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		echo $combo;
	}	
	
	function solicitudesPaciente(){
	    global $mysqli;
		
		$expediente = (!empty($_REQUEST['expediente']) ? $_REQUEST['expediente'] : '');
		$combo 	= '';
		
		$query  = " SELECT a.id, a.fecha_solicitud
					FROM solicitudes a
					INNER JOIN pacientes b ON b.id = a.idpaciente
					WHERE expediente = '".$expediente."'
					ORDER BY fecha_solicitud";
					
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		if($result->num_rows > 0 ){	
			while($row = $result->fetch_assoc()){
				$combo .= "<option value='".$row['id']."'>".$row['fecha_solicitud']."</option>";
			}
		}else{
			$combo = 0;
		}
		echo $combo;
	}
	
	function estadosauditoria(){
		global $mysqli;
		$query ="SELECT DISTINCT id,descripcion FROM estados WHERE tipo = 'auditoría' AND estado = 'Activo'";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['descripcion']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}	
	
	function auditores(){
		global $mysqli;
		$query ="SELECT id,nombre FROM usuarios WHERE nivel IN (1,16) AND estado = 'Activo'";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	
	function regionalesAuditoria(){
		global $mysqli;
		$query ="SELECT DISTINCT id,codigo,nombre, IF(nombre = 'Panamá', 1, 2) AS orden FROM regionales ORDER BY orden ";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){ 
			$row['nombre'] == 'Panamá' ? $nombre = 'Sede Principal' : $nombre = $row['nombre'];
			$combo .= "<option value='".$row['id']."'>".$nombre."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	
	function administradores(){
		global $mysqli;
		$query ="SELECT id,nombre FROM usuarios WHERE nivel = 1 AND estado = 'Activo'";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."' >".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}

	function nacionalidades(){
		global $mysqli;
		$query ="SELECT id,nombre FROM nacionalidades WHERE 1";
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	}
	
	function pacienteshabilitacionjuntas(){
		global $mysqli;
		$id = $_REQUEST['id'];
		$search = $_REQUEST['search'];

		$query =" 	SELECT a.id, CONCAT(a.cedula, ' | ', a.nombre, ' ', a.apellidopaterno, ' ', a.apellidomaterno) AS nombre,
					c.descripcion AS estado
					FROM pacientes a 
					INNER JOIN solicitudes b ON b.idpaciente = a.id 
					INNER JOIN estados c ON c.id = b.estatus
					WHERE b.estatus IN (2,5,31) AND
					(a.cedula LIKE '%".$search."%' OR CONCAT(a.nombre,' ',a.apellidopaterno,' ',a.apellidomaterno) LIKE '%".$search."%')
					ORDER BY b.fecha_solicitud
					LIMIT 100";
					echo $query;
		$result = $mysqli->query($query);
		$combo = "<option value='0'></option>";
		while($row = $result->fetch_assoc()){
			$combo .= "<option value='".$row['id']."'>".$row['nombre']." | ".$row['estado']."</option>";
		}
		$combo .= "</select>";
		echo $combo;
	} 
?>