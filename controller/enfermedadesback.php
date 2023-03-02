<?php
    include("conexion.php");
    sessionrestore();
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];
	}
	
	switch($oper){
		case "listado": //
			  listado();
			  break;
		case "getenfermedad": //
			  getenfermedad();
			  break;
		case "guardar_enfermedad": //
			  guardar_enfermedad();
			  break;
		case "editar_enfermedad": //
			  editar_enfermedad();
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
		
		$query = "	SELECT a.*
					FROM enfermedades a 
					WHERE 1 = 1  " ;
		
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
									<a class="dropdown-item text-info boton-modificar-enfermedad" data-id="'.$row['id'].'" href="enfermedad.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
									<a class="dropdown-item text-danger font-w600 boton-eliminar" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones,
				'codigo'		=>	$row['codigo'],
				'nombre'		=>  $row['nombre'],
				'grupo'			=>  $row['grupo'],				
				'estado' 		=>	$row['estado']
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

	function getenfermedad(){
		global $mysqli;
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "	SELECT a.*
					FROM enfermedades a
					WHERE a.id = '".$id."' ";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){		
			$resultado['enfermedad'] = array(
				'codigo' 	=> $row['codigo'],
				'nombre'   	=> $row['nombre'],
				'grupo' 	=> $row['grupo'],
				'estado' 	=> $row['estado']
			);
		}
		echo json_encode($resultado);
	}
	
	function guardar_enfermedad(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		$codigo  		= (!empty($data['codigo']) ? $data['codigo'] : '');
		$nombre  		= (!empty($data['nombre']) ? $data['nombre'] : '');
		$grupo			= (!empty($data['grupo']) ? $data['grupo'] : '');
		$estado			= (!empty($data['estado']) ? $data['estado'] : '');		
		//BITACORA 
		$campos = array(
			'Código' 	=> $codigo,
			'Nombre' 	=> $nombre,
			'Grupo' 	=> $grupo,
			'Estado' 	=> $estado
		);
		
		$query = "	INSERT INTO enfermedades (id, codigo, nombre, grupo, estado) 
					VALUES(null, '".$codigo."', '".$nombre."', '".$grupo."', '".$estado."')";
		if($mysqli->query($query)){
			$idenfermedad = $mysqli->insert_id;
			nuevoRegistro('Enfermedades','Enfermedad',$idenfermedad,$campos,$query);
			echo 1;
		}else{
			echo $query;
		}
	} 

	function editar_enfermedad(){
		global $mysqli;
		$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');		
		//DATOS PERSONALES
		$idenfermedad	= (!empty($data['idenfermedad']) ? $data['idenfermedad'] : '');
		$codigo  		= (!empty($data['codigo']) ? $data['codigo'] : '');
		$nombre  		= (!empty($data['nombre']) ? $data['nombre'] : '');
		$grupo			= (!empty($data['grupo']) ? $data['grupo'] : '');
		$estado			= (!empty($data['estado']) ? $data['estado'] : '');
		//BITACORA		
		$valoresold = getRegistroSQL("	SELECT a.codigo AS 'Código', a.nombre AS 'Nombre', a.grupo AS 'Grupo', 
										a.estado AS 'Estado'
										FROM enfermedades a
										WHERE a.id = '".$idenfermedad."' ");		
		//UPDATE
		$query = " UPDATE enfermedades SET codigo = '".$codigo."', nombre = '".$nombre."', 
							  grupo = '".$grupo."', estado = '".$estado."'
							  WHERE id = '".$idenfermedad."' ";	
		if($mysqli->query($query)){
			$valoresnew = array(
				'Código' 	=> $codigo,
				'Nombre' 	=> $nombre,
				'Grupo' 	=> $grupo,
				'Estado' 	=> $estado
			);
			actualizarRegistro('Enfermedades','Enfermedad',$idenfermedad,$valoresold,$valoresnew,$query);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function eliminar(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query = "DELETE FROM enfermedades WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			eliminarRegistro('Enfermedades','Enfermedad',$nombre,$id,$query);
		    echo 1;
		}else{
			echo 0;
		}
	}
	
?>