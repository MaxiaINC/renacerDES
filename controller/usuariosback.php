<?php 
	include("conexion.php");
    include("../correo/correonotificacion.php");
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
		case "getusuario":
			getusuario();
			break;
		case "guardarusuario":
			guardarusuario();
			break;
		case "editarusuario":
			editarusuario();
			break;
		case "eliminar":
			eliminar();
			break;
		case "cambiarClave":
			cambiarClave();
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

		$query = " 	SELECT a.id, a.usuario, a.nombre, a.correo, a.telefono, a.cargo, a.estado, b.nombre AS nivel 
					FROM usuarios a 
					LEFT JOIN niveles b ON b.id = a.nivel 
					WHERE 1 = 1 ";

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
									<a class="dropdown-item text-info boton-modificar-usuario" data-id="'.$row['id'].'" href="usuario.php?id='.$row['id'].'"><i class="fas fa-pen mr-2 editarUsuario"></i>Editar</a>
									<a class="dropdown-item text-danger font-w600 boton-eliminar-fisico" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			
			$resultado[] = array
			(
				'acciones' 	=> $acciones,
				'id' 		=> $row['id'],
				'usuario'	=>	$row['usuario'], 
				'nombre' 	=>	$row['nombre'], 
				'correo' 	=>	$row['correo'], 
				'telefono' 	=>	$row['telefono'], 
				'cargo' 	=>	$row['cargo'], 
				'nivel' 	=>	$row['nivel'],
				'estado' 	=>	$row['estado']
			);
		}
		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsFiltered),
			"recordsFiltered" => intval($length),
			"data" => $resultado
		  );
		echo json_encode($response);
	}

	function getusuario(){
		global $mysqli;

		$idusuario   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$query = " 	SELECT a.id, a.usuario, a.nombre, a.clave, a.correo, a.telefono, a.cargo, a.estado, b.id AS nivel, a.regional
					FROM usuarios a 
					LEFT JOIN niveles b ON b.id = a.nivel 
					WHERE a.id = '".$idusuario."' ";
					
		$result = $mysqli->query($query);
		if(!$result){
			die($mysqli->error);  
		}
		
		$resultado = array();
		if($row = mysqli_fetch_array($result)){
			$resultado[] = array(
				'id' 		=> $row['id'],
				'usuario' 	=> $row['usuario'],
				'nombre' 	=> $row['nombre'],
				//'clave' 	=> $row['clave'],
				'correo' 	=> $row['correo'],
				'telefono' 	=> $row['telefono'],
				'cargo' 	=> $row['cargo'],
				'regional' 	=> $row['regional'],
				'estado' 	=> $row['estado'],
				'nivel' 	=> $row['nivel']
			);
		}
		$jsonString = json_encode($resultado[0]);
		echo $jsonString;
	}
	
	function guardarusuario(){
		global $mysqli;
		$data =(!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		$idusuario =(!empty($data['idusuario']) ? $data['idusuario'] : '');
		$usuario =(!empty($data['usuario']) ? $data['usuario'] : '');
		$nombre =(!empty($data['nombre']) ? $data['nombre'] : '');
		$correo =(!empty($data['correo']) ? $data['correo'] : '');
		$telefono =(!empty($data['telefono']) ? $data['telefono'] : '');
		$cargo =(!empty($data['cargo']) ? $data['cargo'] : '');
		$estados =(!empty($data['estados']) ? $data['estados'] : '');
		$niveles =(!empty($data['niveles']) ? $data['niveles'] : '');
		$regional =(!empty($data['regional']) ? $data['regional'] : '');
		$clave = 'senadis123';
		$hashed_pass = hash('sha256', stripslashes($clave) );
		
		$campos = array(
			'Usuario' 		=> $usuario,
			'Nombre' 		=> $nombre,
			'Clave' 		=> $clave,
			'Correo' 		=> $correo,
			'Telefono' 		=> $telefono,
			'Cargo' 		=> $cargo,
			'Regional' 		=> $regional,
			'Estado' 		=> $estado,
			'Nivel' 		=> getValor('nombre','niveles',$niveles,'')
		);
		$comc = "SELECT usuario FROM usuarios where usuario = '$usuario'";
		$resultc = $mysqli->query($comc);

		$comn = "SELECT correo FROM usuarios where correo = '".$correo."' ";
		$resultn = $mysqli->query($comn);
		$totc = $resultc->num_rows;
		$totn = $resultn->num_rows;
		if($totc > 0 && $totn > 0){
			echo 4;			
		}elseif($totc > 0 || $totn > 0){
			if ($totc>0){
				echo 2;
			}
			if($totn>0){
				echo 3;
			}
		}elseif($totc <= 0 && $totn <= 0){
			$query = "	INSERT INTO usuarios (id,usuario,clave,nombre,correo,telefono,cargo,nivel,estado,regional) 
						VALUES(null,'".$usuario."', '".$hashed_pass."', '".$nombre."', '".$correo."', '".$telefono."', '".$cargo."', '".$niveles."', '".$estados."','".$regional."')";
			
			$result = $mysqli->query($query);		
			if($result==true){		    
				$idusuario = $mysqli->insert_id;		    
				nuevoRegistro('Usuarios','Usuarios',$idusuario,$campos,$query);
				//ENVIAR CORREO DEL USUARIO CREADO
				//correoNuevoUsuario($nombre, $usuario, $clave, $telefono, $correo, $cargo, $nivel);
				echo 1;
			}else{
				echo 0;
			}
		}
	}

	function editarusuario(){
		global $mysqli;
		//Data del formulario
		$data =(!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		if(isset($data['idusuario'])){
			$idusuario =(!empty($data['idusuario']) ? $data['idusuario'] : '');
			$usuario =(!empty($data['usuario']) ? $data['usuario'] : '');
			$nombre =(!empty($data['nombre']) ? $data['nombre'] : '');
			$clave =(!empty($data['clave']) ? $data['clave'] : '');
			$correo =(!empty($data['correo']) ? $data['correo'] : '');
			$telefono =(!empty($data['telefono']) ? $data['telefono'] : '');
			$cargo =(!empty($data['cargo']) ? $data['cargo'] : '');
			$estados =(!empty($data['estados']) ? $data['estados'] : '');
			$niveles =(!empty($data['niveles']) ? $data['niveles'] : '');
			$regional =(!empty($data['regional']) ? $data['regional'] : '');
			
			$valoresold = getRegistroSQL("	SELECT u.usuario AS 'Usuario', u.nombre AS 'Nombre', u.correo AS 'Correo', 
											u.telefono AS 'Teléfono', u.cargo AS 'Cargo', u.regional AS 'Regional', u.estado AS 'Estado', n.nombre AS 'Nivel' 
											FROM usuarios u 
											INNER JOIN niveles n ON u.nivel = n.id 
											WHERE u.id = '".$idusuario."' ");
											
			$comc = "SELECT usuario FROM usuarios where usuario = '$usuario' AND id != ".$idusuario." ";
			$resultc = $mysqli->query($comc);

			$comn = "SELECT correo FROM usuarios where correo = '".$correo."' AND id != ".$idusuario." ";
			$resultn = $mysqli->query($comn);
			$totc = $resultc->num_rows;
			$totn = $resultn->num_rows;
			
			if($totc > 0 && $totn > 0){
				echo 4;			
			}elseif($totc > 0 || $totn > 0){
				if ($totc>0){
					echo 2;
				}
				if($totn>0){
					echo 3;
				}
			}elseif($totc <= 0 && $totn <= 0){
				$query = "  UPDATE 
								usuarios 
							SET 
								usuario = '".$usuario."', 
								 ";
				if($clave != ""){
					$hashed_pass = hash('sha256', stripslashes($clave) );
					$query .="		clave = '".$hashed_pass."', ";
				}
				
				$query .="		nombre = '".$nombre."', 
								correo = '".$correo."', 
								telefono = '".$telefono."', 
								cargo = '".$cargo."', 
								regional = '".$regional."', 
								nivel = '".$niveles."', 
								estado = '".$estados."' 
							WHERE id = '".$idusuario."'";
				//debug($query);
				$result  = mysqli_query($mysqli, $query);

				if($result){
					$valoresnew = array(
						'Usuario' 		=> $usuario,
						'Nombre' 		=> $nombre,
						'Correo' 		=> $correo,
						'Teléfono' 		=> $telefono,
						'Cargo' 		=> $cargo, 
						'Regional' 		=> $regional,
						'Estado' 		=> $estados,
						'Nivel' 		=> getValor('nombre','niveles',$niveles,'')
					);
					actualizarRegistro('Usuarios','Usuarios',$idusuario,$valoresold,$valoresnew,$query);
					echo 1;
				}else {
					echo 0;
				}
			} 
		}
	}

	function eliminar(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query = "DELETE FROM usuarios WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			eliminarRegistro('Usuarios','Usuario',$nombre,$id,$query);
		    echo 1;
		}else{
			echo 0;
		}
	}
	
	function cambiarClave() 
	{
		global $mysqli;		
		$id 	= $_SESSION['user_id_sen'];
		$clave  = $_REQUEST['clave'];
		$hashed_pass = hash('sha256', stripslashes($clave) );
		
		if($id != ''){
			$query = "UPDATE usuarios SET clave = '".$hashed_pass."' WHERE id = '".$id."'";		
			$result = $mysqli->query($query);
			
			if($result==true){		    
				bitacora($_SESSION['usuario'], "Usuarios", "Se ha cambiado la clave del usuario #".$id."", $id , $query);
				echo 1;
			}else{
				echo 0;
			}
		} 	
	}
	
?>