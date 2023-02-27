<?php
    include("conexion.php");
    sessionrestore();
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "listado":
			  listado();
			  break;
		case "getnivel":
			  getnivel();
			  break;
		case "guardar_nivel":
			  guardar_nivel();
			  break;
		case "editar_nivel":
			  editar_nivel();
			  break;
		case "eliminar":
			  eliminar();
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
		
		$query = "	SELECT * FROM niveles a WHERE 1 = 1 AND estado = 'Activo' " ;
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
									<a class="dropdown-item text-info boton-modificar-nivel" data-id="'.$row['id'].'" href="nivel.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
									<a class="dropdown-item text-danger font-w600 boton-eliminar" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones,
				'nombre'		=>  $row['nombre'],
				'descripcion'	=>  $row['descripcion'],				
				'estado' 		=>	$row['estado']
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

	function getnivel(){
		global $mysqli;
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "	SELECT * FROM niveles a WHERE a.id = '".$id."' ";
		if($idsolicitud != ''){
		    $query .= " AND b.id = '".$idsolicitud."' ";
		}
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' 			=> $row['id'],
				'nombre'   		=> $row['nombre'],
				'descripcion' 	=> $row['descripcion'],
				'estado' 		=> $row['estado']
			);
		}
		echo json_encode($resultado);
	}
	
	function guardar_nivel(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		//DATOS PERSONALES
		$idnivel		= (!empty($data['idnivel']) ? $data['idnivel'] : '');
		$nombre  		= (!empty($data['nombre']) ? $data['nombre'] : '');
		$descripcion	= (!empty($data['descripcion']) ? $data['descripcion'] : '');
		$estado			= (!empty($data['estado']) ? $data['estado'] : '');
		
		//BITACORA 
		$campos = array(
			'Nombre' 		=> $nombre,
			'Descripción' 	=> $descripcion,
			'Estado' 		=> $estado
		);
		
		$query = "	INSERT INTO niveles (id, nombre, descripcion, fecha, estado) 
					VALUES(null, '".$nombre."', '".$descripcion."', now(), '".$estado."')";
		if($mysqli->query($query)){
			$idnivel = $mysqli->insert_id;
			nuevoRegistro('niveles','nivel',$idnivel,$campos,$query);
			
			$response = array( "success" => true, "idnivel" => $idnivel );			
		}else{
			$response = array( "success" => false, "idnivel" => '' );
		}
		echo json_encode($response);
	} 

	function editar_nivel(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');		
		//DATOS PERSONALES
		$idnivel		= (!empty($data['idnivel']) ? $data['idnivel'] : '');
		$nombre  		= (!empty($data['nombre']) ? $data['nombre'] : '');
		$descripcion	= (!empty($data['descripcion']) ? $data['descripcion'] : '');
		$estado			= (!empty($data['estado']) ? $data['estado'] : '');
		
		$valoresold = getRegistroSQL("	SELECT a.nombre AS 'Nombre', a.descripcion AS 'Descripción', a.estado AS 'Estado'
										FROM niveles a 
										WHERE a.id = '".$idnivel."' ");		
		
		$query = " UPDATE niveles SET nombre = '".$nombre."', descripcion = '".$descripcion."', estado = '".$estado."'
						 WHERE id = '".$idnivel."' ";	
		if($mysqli->query($query)){
			$valoresnew = array(
				'Nombre' 		=> $nombre,
				'Descripción' 	=> $descripcion,
				'Estado' 		=> $estado
			);			
			actualizarRegistro('niveles','nivel',$idnivel,$valoresold,$valoresnew,$query);
			
			$response = array( "success" => true, "idnivel" => $idnivel );
		}else{
			$response = array( "success" => false, "idnivel" => '' );			
		}
		echo json_encode($response);
	}
	
	function eliminar(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query = "DELETE FROM niveles WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			eliminarRegistro('niveles','nivel',$nombre,$id,$query);
		    echo 1;
		}else{
			echo 0;
		}
	}
	
?>