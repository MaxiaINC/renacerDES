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
		case "get":
			  get();
			  break;
		case "guardar":
			  guardar();
			  break;
		case "editar":
			  editar();
			  break;
		case "cambiarEstado":
			  cambiarEstado();
			  break;
		case "getRegionalUsu":
			  getRegionalUsu();
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
		
		$query = "	SELECT a.id, b.nombre, a.codigo, b.regional, a.estado FROM codigosautorizacion a
		            INNER JOIN usuarios b ON b.id = a.idusuarios 
					WHERE 1 = 1  " ;
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			
			$estado = $row['estado']; 
			$txt_estado = $estado == 'Activo'? 'Inactivar' : 'Activar';
			$color_estado = $estado == 'Activo'? 'text-danger' : 'text-info';
			$icon_estado = $estado == 'Activo'? 'fa-ban' : 'fa-check';
			
			$acciones = '<td>
							<div class="dropdown ml-auto">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu droptable dropdown-menu-right">
									<a class="dropdown-item text-info boton-modificar-nivel" data-id="'.$row['id'].'" href="codigoautorizacion.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>';
			$acciones .= 			'<a class="dropdown-item '.$color_estado.' font-w600 boton-estado" href="#" data-id="'.$row['id'].'" data-estado="'.$estado.'"><i class="fas '.$icon_estado.' mr-2"></i>'.$txt_estado.'</a>';	
			$acciones .= '</div>
							</div>
						</td>';
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones,
				'nombre'		=>  $row['nombre'],
				'codigo'	=>  $row['codigo'],
				'regional'	=>  $row['regional'],
				'estado'	=>  $row['estado']
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
		
		$query = "	SELECT a.id, a.codigo, a.idusuarios, b.regional, a.estado FROM codigosautorizacion a 
					INNER JOIN usuarios b ON b.id = a.idusuarios 
					WHERE a.id = '".$id."' ";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){		
			$resultado = array(
				'id' 		=> $row['id'],
				'idusuarios'=> $row['idusuarios'],
				'codigo' 	=> $row['codigo'],
				'regional' 	=> $row['regional'],
				'estado' 	=> $row['estado']
				
			);
		}
		echo json_encode($resultado);
	}
	
	function guardar(){
		global $mysqli; 
		
		$idusuarios = (!empty($_REQUEST['idusuarios']) ? $_REQUEST['idusuarios'] : ''); 
		$regional = (!empty($_REQUEST['regional']) ? $_REQUEST['regional'] : ''); 
		$codigo = (!empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : ''); 
		
		//BITACORA 
		$campos = array(
			'Nombre' => getValor('nombre','usuarios',$idusuarios),
			'Regional' => $regional,
			'Código' => $codigo 
		);
		$sql = "SELECT id FROM codigosautorizacion WHERE idusuarios = ".$idusuarios."";
		$rta = $mysqli->query($sql);
		$total = $rta->num_rows;
		if($total>0){
		    $response = array( "success" => false, "msj" => 'Ya existe un código de autorización para este usuario' );
		    echo json_encode($response);
		}else{
		    $query = "	INSERT INTO codigosautorizacion (idusuarios, codigo) 
    					VALUES($idusuarios, '$codigo')";
						//echo $query;
    		if($mysqli->query($query)){
    			$id = $mysqli->insert_id;
    			nuevoRegistro('Códigos Autorización','Código Autorización',$id,$campos,$query);
    			
    			$response = array( "success" => true, "msj" => 'Código de autorización creado satisfactoriamente' );			
    		}else{
    			$response = array( "success" => false, "msj" => 'Error al crear el código de autorización' );
    		}
    		echo json_encode($response);
		}
		
	} 

	function editar(){
		global $mysqli;
		$id 		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');	
		$idusuarios = (!empty($_REQUEST['idusuarios']) ? $_REQUEST['idusuarios'] : '');
		$regional = (!empty($_REQUEST['regional']) ? $_REQUEST['regional'] : ''); 		
		$codigo 	= (!empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '');	
		
		$valoresold = getRegistroSQL("	SELECT b.nombre AS 'Nombre', a.codigo AS 'Código', b.regional AS 'Regional'
										FROM codigosautorizacion a 
										INNER JOIN usuarios b ON b.id = a.idusuarios
										WHERE a.id = '".$id."' ");		
		
		$query = "  UPDATE codigosautorizacion SET idusuarios = '".$idusuarios."', codigo = '".$codigo."'
					WHERE id = '".$id."' ";	
					
		if($mysqli->query($query)){
			$valoresnew = array(
				'Nombre' => getValor('nombre','usuarios',$idusuarios),
				'Regional' => $regional,
				'Código' => $codigo
			);			
			actualizarRegistro('Códigos Autorización','Código Autorización',$id,$valoresold,$valoresnew,$query);
			
			$response = array( "success" => true, "msj" => 'Código de autorización actualizado satisfactoriamente' );
		}else{
			$response = array( "success" => false, "msj" => 'Error al actualizar el código de autorización' );			
		}
		echo json_encode($response);
	}
	
	function cambiarEstado(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$estado = (!empty($_REQUEST['estado']) ? $_REQUEST['estado'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$estadoNuevo = $estado == 'Activo' ? 'Inactivo' : 'Activo';
		
		$valoresold = array(
			'Estado' => $estado
		);	
		$valoresnew = array(
			'Estado' => $estadoNuevo
		);	
			
		$query = "UPDATE codigosautorizacion SET estado = '". $estadoNuevo ."' WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			actualizarRegistro('Códigos Autorización','Código Autorización',$id,$valoresold,$valoresnew,$query);
		    echo 1;
		}else{
			echo 0;
		}
	}
	
	function getRegionalUsu(){
		global $mysqli;
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		
		$query = "	SELECT regional FROM usuarios a WHERE a.id = '".$id."' ";
		$result = $mysqli->query($query);
		if($row = $result->fetch_assoc()){	
			$regional = $row['regional'];
		}else{
			$regional = 0;
		}
		echo $regional;
	}
	
?>