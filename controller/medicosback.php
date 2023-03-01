<?php 
	include("conexion.php");
	sessionrestore();
    $oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}

	switch($oper)
	{
	  	case "listado":
	  		listado();
	  		break;
		case "getmedico":
			getmedico();
			break;
		case "guardarmedico":
			guardarmedico();
			break;
		case "editarmedico":
			editarmedico();
			break;
		case "eliminar":
			eliminar();
			break;
		case "existe":
			  existe();
			  break;
		case "checkpacienteseguro":
			  checkpacienteseguro();
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
		$length   			 = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		/*--------------------------------------------------------------------*/

		$query = " 	SELECT m.id,m.cedula,m.nombre,m.apellido, GROUP_CONCAT(DISTINCT e.nombre SEPARATOR  ', ') as especialidad, 
					m.telefonocelular,m.correo, 
					GROUP_CONCAT(DISTINCT r.nombre SEPARATOR  ', ') as regional, m.nroregistro
		            FROM medicos m 
		            LEFT JOIN especialidades e ON FIND_IN_SET(e.id,m.especialidad)
		            LEFT JOIN regionales r on FIND_IN_SET(r.id, m.regional) 
		            WHERE 1 ";
		$query  .= " GROUP BY m.id ";
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
									<a class="dropdown-item text-info boton-modificar-medico" data-id="'.$row['id'].'" href="medico.php?id='.$row['id'].'"><i class="fas fa-pen mr-2 editarmedico"></i>Editar</a>
									<a class="dropdown-item text-danger font-w600 boton-eliminar-fisico" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			
			$resultado[] = array
			(
				'acciones' 		=> $acciones,
				'id' 			=> $row['id'],
				'nombre' 		=>	$row['nombre']." ".$row['apellido'],
				'cedula'	 	=>	$row['cedula'],
				'especialidad' 	=>	$row['especialidad'],
				'telefono'	 	=>	$row['telefonocelular'],
				'correo'	 	=>	$row['correo'],
				'regional'	 	=>	$row['regional'],
				'nroregistro'	 	=>	$row['nroregistro']
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

	function getmedico(){
		global $mysqli;
		$idmedico   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$resultado 	= array();
		
		$query 	= " SELECT * FROM medicos a WHERE a.id = '".$idmedico."' ";
		$result = $mysqli->query($query);
		if(!$result){
			die($mysqli->error);  
		}
		if($row = mysqli_fetch_array($result)){
			$resultado[] = array(
				'id' 				=> $row['id'],
				'cedula' 			=>	$row['cedula'],
				'tipo_documento' 	=> $row['tipo_documento'],
				'nombre' 			=>	$row['nombre'],
				'apellido' 			=>	$row['apellido'],				
				'especialidad' 		=>	$row['especialidad'],
				'telefonocelular' 	=>	$row['telefonocelular'],
				'telefonootro' 		=>	$row['telefonootro'],
				'correo' 			=>	$row['correo'],
				'discapacidades'	=> 	$row['discapacidades'],
				'regional'			=> 	$row['regional'],
				'nroregistro'			=> 	$row['nroregistro']
			);
		}
		$jsonString = json_encode($resultado[0]);
		echo $jsonString;
	}
	
	function guardarmedico(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');

		$queryVer="SELECT * FROM `medicos` WHERE nroregistro='".$data['nroregistro']."' ";
		$resultVer = $mysqli->query($queryVer);
		$num = $resultVer->num_rows;

		if($num > 0){
			echo 4;
		}else{
			$cedula 		= (!empty($data['cedula']) ? $data['cedula'] : '');
			$tipodocumento  = (!empty($data['tipodocumento']) ? $data['tipodocumento'] : '');
			$nombre 		= (!empty($data['nombre']) ? $data['nombre'] : '');		
			$apellido 		= (!empty($data['apellido']) ? $data['apellido'] : '');
			$especialidad 	= (!empty($data['idespecialidades']) ? $data['idespecialidades'] : '');
			$telefonocelular= (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
			$telefonootro 	= (!empty($data['telefonootro']) ? $data['telefonootro'] : '');
			$correo 		= (!empty($data['correo']) ? $data['correo'] : '');
			$discapacidades = (!empty($data['iddiscapacidades']) ? $data['iddiscapacidades'] : '');
			$regional 		= (!empty($data['idregionales']) ? $data['idregionales'] : '');
			$nroregistro 		= (!empty($data['nroregistro']) ? $data['nroregistro'] : '');
			
			$campos = array(
				'Cédula' 		=> $cedula,
				'Tipo de documento' => $tipodocumento,
				'Nombre' 		=> $nombre,
				'Apellido' 		=> $apellido,
				'Especialidad' 	=> getValor('nombre','especialidades',$especialidad,''),			
				'Teléfono' 		=> $telefonocelular,
				'Teléfono otro' => $telefonootro,
				'Correo' 		=> $correo,
				'Discapacidades'=> $discapacidades, 
				'Regional' 		=> getValor('nombre','regionales',$regional,''),
				'NroRegistro'	=> $nroregistro
			);
			$comn = "SELECT correo FROM medicos where correo = '".$correo."' ";
			$resultn = $mysqli->query($comn);
			$totn = $resultn->num_rows;
			if($totn > 0){
				echo 3;
			}else{
				//USUARIO
				$queryU = "	INSERT INTO usuarios (id,usuario,clave,nombre,correo,telefono,cargo,nivel,estado) 
							VALUES(null,'".$cedula."', '".$cedula."', '".$nombre." ".$apellido."', '".$correo."', '".$telefonocelular."', '', '10', 'Activo')";
				$resultU = $mysqli->query($queryU);
				if($resultU == true){		    
					$idusuario = $mysqli->insert_id;
					$query 	= "	INSERT INTO	medicos (cedula, tipo_documento, nombre, apellido, especialidad,telefonocelular, telefonootro, correo, idusuario, discapacidades, regional, nroregistro)
								VALUES ('".$cedula."','".$tipodocumento."','".$nombre."','".$apellido."','".$especialidad."','".$telefonocelular."','".$telefonootro."','".$correo."','".$idusuario."','".$discapacidades."','".$regional."', '".$nroregistro."')";
					//debugL($query);
					$result = $mysqli->query($query);
					if($result == true){
						$idmedico = $mysqli->insert_id;					
						nuevoRegistro('Médicos','Médicos',$idmedico,$campos,$query);
						nuevoRegistro('Usuarios','Usuarios',$idusuario,$campos,$query);
						//ENVIAR CORREO DEL USUARIO CREADO
						//correoNuevoUsuario($nombre, $usuario, $clave, $telefono, $correo, $cargo, $nivel);
						echo 1;
					}else{
						$queryU = "DELETE FROM usuarios WHERE id = '".$idusuario."' ";
						$resultU = $mysqli->query($queryU);
					}
				}else{
					echo 0;
				}
			}
		}
	}

	function editarmedico(){
		global $mysqli;
		//Data del formulario
		$data =(!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');

		$queryVer="SELECT * FROM `medicos` WHERE nroregistro='".$data['nroregistro']."' AND id != '".$data['idmedico']."' ";
		$resultVer = $mysqli->query($queryVer);
		$num = $resultVer->num_rows;

		if($num > 0){
			echo 4;
		}else{
			if(isset($data['idmedico'])){
				$idmedico 		= (!empty($data['idmedico']) ? $data['idmedico'] : '');
				$cedula 		= (!empty($data['cedula']) ? $data['cedula'] : '');
				$tipodocumento  = (!empty($data['tipodocumento']) ? $data['tipodocumento'] : '');
				$nombre 		= (!empty($data['nombre']) ? $data['nombre'] : '');		
				$apellido 		= (!empty($data['apellido']) ? $data['apellido'] : '');
				$especialidad 	= (!empty($data['idespecialidades']) ? $data['idespecialidades'] : '');
				$telefonocelular= (!empty($data['telefonocelular']) ? $data['telefonocelular'] : '');
				$telefonootro 	= (!empty($data['telefonootro']) ? $data['telefonootro'] : '');
				$correo 		= (!empty($data['correo']) ? $data['correo'] : '');
				$discapacidades = (!empty($data['iddiscapacidades']) ? $data['iddiscapacidades'] : '');
				$regional 		= (!empty($data['idregionales']) ? $data['idregionales'] : '');
				$nroregistro 		= (!empty($data['nroregistro']) ? $data['nroregistro'] : '');			
				
				$valoresold = getRegistroSQL("	SELECT m.cedula AS 'Cédula', m.tipo_documento AS 'Tipo de documento', 
												m.nombre AS 'Nombre', m.apellido AS 'Apellido', 
												GROUP_CONCAT(' ',e.nombre) AS 'Especialidad', m.telefonocelular AS 'Teléfono',
												m.telefonootro AS 'Teléfono otro', m.correo AS 'Correo', m.discapacidades AS 'Discapacidades',
												GROUP_CONCAT(' ',r.nombre) as Regional, m.nroregistro as 'Nro Registro'
												FROM medicos m 
												LEFT JOIN especialidades e ON FIND_IN_SET(e.id,m.especialidad)
												LEFT JOIN regionales r on FIND_IN_SET(r.id, m.regional) 
												WHERE m.id = '".$idmedico."' ");
				
				$query = "  UPDATE medicos SET cedula = '".$cedula."', tipo_documento = '".$tipodocumento."', nombre = '".$nombre."', 
							apellido = '".$apellido."', especialidad = '".$especialidad."', telefonocelular = '".$telefonocelular."', 
							telefonootro = '".$telefonootro."', correo = '".$correo."', discapacidades = '".$discapacidades."', 
							regional = '".$regional."', nroregistro = '".$nroregistro."' 
							WHERE id = '".$idmedico."' ";
				//debugL($query);
				$result  = mysqli_query($mysqli, $query);
	
				if($result){
					$idusuario = getValor('idusuario','medicos',$idmedico,'');
					$query = "  UPDATE usuarios SET nombre = '".$nombre." ".$apellido."', telefono = '".$telefonocelular."', 
								correo = '".$correo."' WHERE id = '".$idusuario."' ";
					//debugl($query);
					$result  = mysqli_query($mysqli, $query);
				
					$valoresnew = array(
						'Cédula' 		=> $cedula,
						'Tipo de documento' => $tipodocumento,
						'Nombre' 		=> $nombre,
						'Apellido' 		=> $apellido,
						'Especialidad' 	=> getValor('nombre','especialidades',$especialidad,''),			
						'Teléfono' 		=> $telefonocelular,
						'Teléfono otro' => $telefonootro,
						'Correo' 		=> $correo,
						'Discapacidades'=> $discapacidades, 
						'Regional' 		=> getValor('nombre','regionales',$regional,''),
						'NroRegistro'	=> $nroregistro
					);
					actualizarRegistro('Médicos','Médicos',$idmedico,$valoresold,$valoresnew,$query);
					echo 1;
				}else {
					echo 2;
				}
			}
		}

	}

	function eliminar(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query 	= "DELETE FROM medicos WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			//USUARIO
			$idusuario 	= getValor('idusuario','medicos',$id,'');
			$nombreU 	= getValor('nombre','usuarios',$idusuario,'');
			$queryU 	= " DELETE FROM usuarios WHERE id = '".$idusuario."' ";
			$resultU 	= $mysqli->query($queryU);
			//BITACORA
			eliminarRegistro('Médicos','Médicos',$nombre,$id,$query);
			eliminarRegistro('Usuarios','Usuario',$nombreU,$idusuario,$queryU);
		    echo 1;		    
		}else{
			echo 0;
		}	
	}

	function existe(){
		global $mysqli;
		$cedula = $_REQUEST['cedula'];
		$count = 0;
		$query = "SELECT cedula FROM medicos WHERE cedula = '".$cedula."' ";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}
	
	function checkpacienteseguro(){
		global $mysqli;
		$idseguro = $_REQUEST['id'];
		$count = 0;
		$query = "SELECT id FROM pacienteseguros WHERE idmedicos = '".$idseguro."' ";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}
