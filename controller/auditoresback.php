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
		case "get":
			get();
			break;
		case "guardar":
			guardar();
			break;
		case "editar":
			editar();
			break;
		case "eliminar":
			eliminar();
			break;
		case "checkexpedienteauditor":
			checkexpedienteauditor();
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

		$query = " 	SELECT id, nombre
		            FROM auditores  
		            WHERE 1 ";
		$query  .= " GROUP BY id ";
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
									<a class="dropdown-item text-info boton-modificar" data-id="'.$row['id'].'" href="auditor.php?id='.$row['id'].'"><i class="fas fa-pen mr-2 editar"></i>Editar</a>
									<a class="dropdown-item text-danger font-w600 boton-eliminar-fisico" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			
			$resultado[] = array
			(
				'acciones' 		=> $acciones,
				'id' 			=> $row['id'],
				'nombre' 		=>	$row['nombre'],
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

	function get(){
		global $mysqli;
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$resultado 	= array();
		
		$query 	= " SELECT * FROM auditores a WHERE a.id = '".$id."' ";
		$result = $mysqli->query($query);
		if(!$result){
			die($mysqli->error);  
		}
		if($row = mysqli_fetch_array($result)){
			$resultado[] = array(
				'id' 				=> $row['id'],
				'nombre' 			=>	$row['nombre']
			);
		}
		$jsonString = json_encode($resultado[0]);
		echo $jsonString;
	}
	
	function guardar(){
		global $mysqli;
		$nombre 		= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$campos = array(
			'Nombre' 		=> $nombre
		); 
		
		$query 	= "	INSERT INTO	auditores (nombre)
					VALUES ('".$nombre."')";
		//debugL($query);
		$result = $mysqli->query($query);
		if($result == true){
			$id = $mysqli->insert_id;					
			nuevoRegistro('Auditores','Auditores',$id,$campos,$query);  
			$response = array( "success" => true, "msj" => 'Auditor creado satisfactoriamente' );
		}else{
			$response = array( "success" => false, "msj" => 'Error al crear el auditor' );
		}
		echo json_encode($response);
	}

	function editar(){
		global $mysqli;
		
		$id 		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre 	= (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');		
		
		$valoresold = getRegistroSQL("	SELECT nombre AS 'Nombre'
										FROM auditores  
										WHERE id = '".$id."' ");
		
		$query = "  UPDATE auditores SET nombre = '".$nombre."'
					WHERE id = '".$id."' ";
		//debugL($query);
		$result  = mysqli_query($mysqli, $query);

		if($result){
		
			$valoresnew = array(
				'Nombre' => $nombre
			);
			actualizarRegistro('Auditores','Auditores',$id,$valoresold,$valoresnew,$query);
			$response = array( "success" => true, "msj" => 'Auditor actualizado satisfactoriamente' );
		}else {
			$response = array( "success" => false, "msj" => 'Error al actualizar el auditor' );
		}
		echo json_encode($response);
		
	}

	function eliminar(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query 	= "DELETE FROM auditores WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			//BITACORA
			eliminarRegistro('Auditores','Auditores',$nombre,$id,$query);
		    echo 1;		    
		}else{
			echo 0;
		}	
	}
	
	function checkexpedienteauditor(){
		global $mysqli;
		$id = $_REQUEST['id'];
		$count = 0;
		$query = "SELECT id FROM auditorias WHERE id IN ('".$id."') ";
		$result = $mysqli->query($query);
		$count = $result->num_rows;
		echo $count;
	}
?>