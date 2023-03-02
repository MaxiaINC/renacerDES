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
		case "getcorregimiento": //
			  getcorregimiento();
			  break;
		case "guardar_corregimiento": //
			  guardar_corregimiento();
			  break;
		case "editar_corregimiento": //
			  editar_corregimiento();
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
		
		$query = "	SELECT a.* FROM direcciones a WHERE 1 = 1  " ;					
		$query  .= " GROUP BY a.id ";
		
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
									<a class="dropdown-item text-info boton-modificar-corregimiento" data-id="'.$row['id'].'" href="corregimiento.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>
									<a class="dropdown-item text-danger font-w600 boton-eliminar" href="#" data-id="'.$row['id'].'"><i class="fas fa-ban mr-2"></i>Eliminar</a>
								</div>
							</div>
						</td>';
			$resultado[] = array(
				'id' 			=>	$row['id'],
				'acciones' 		=>	$acciones,
				'codigo'		=>	$row['codigo'],
				'provincia'		=>  $row['provincia'],
				'distrito'		=>  $row['distrito'],				
				'corregimiento' =>	$row['corregimiento'],
				'area' 			=>	$row['area'],
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

	function getcorregimiento(){
		global $mysqli;
		$id   = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$idcorregimiento = (!empty($_REQUEST['idcorregimiento']) ? $_REQUEST['idcorregimiento'] : '');
		
		$query = "	SELECT a.*
					FROM direcciones a
					WHERE a.id = '".$id."' ";
		if($idcorregimiento != ''){
		    $query .= " AND b.id = '".$idcorregimiento."' ";
		}
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){		
			$resultado['direccion'] = array(
				'codigo' 		=> $row['codigo'],
				'provincia'   	=> $row['provincia'],
				'distrito'   	=> $row['distrito'],
				'corregimiento' => $row['corregimiento'],
				'area' 			=> $row['area']
			);
		}
		echo json_encode($resultado);
	}
	
	function guardar_corregimiento(){
		global $mysqli;
		//$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');
		$codigo  		= (!empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '');
		$provincia  	= (!empty($_REQUEST['idprovincias']) ? $_REQUEST['idprovincias'] : '');
		$distrito  		= (!empty($_REQUEST['iddistritos']) ? $_REQUEST['iddistritos'] : '');
		$corregimiento	= (!empty($_REQUEST['idcorregimientos']) ? $_REQUEST['idcorregimientos'] : '');
		$area  			= (!empty($_REQUEST['areacor']) ? $_REQUEST['areacor'] : '');
		
		//BITACORA 
		$campos = array(
			'Código' 		=> $codigo,
			'Provincia' 	=> $provincia,
			'Distrito' 		=> $distrito,
			'Corregimiento' => $corregimiento,
			'Área' 			=> $area
		);
		
		$query = " INSERT INTO direcciones (id, codigo, provincia, distrito, corregimiento, area) VALUES (
							 NULL, '".$codigo."', '".$provincia."', '".$distrito."', '".$corregimiento."', '".$area."' );";
		if($mysqli->query($query)){
			$idcorregimiento = $mysqli->insert_id;
			nuevoRegistro('Corregimientos','Corregimiento',$idcorregimiento,$campos,$query);
			$response = array( "success" => true, "idcorregimiento" => $idcorregimiento );			
		}else{
			$response = array( "success" => false, "idcorregimiento" => '' );
		}
		echo json_encode($response);
	} 

	function editar_corregimiento(){
		global $mysqli;
		//$data 			= (!empty($_REQUEST['datos']) ? $_REQUEST['datos'] : '');		
		$iddireccion 	= (!empty($_REQUEST['iddireccion']) ? $_REQUEST['iddireccion'] : '');
		$codigo  		= (!empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '');
		$provincia  	= (!empty($_REQUEST['idprovincias']) ? $_REQUEST['idprovincias'] : '');
		$distrito  		= (!empty($_REQUEST['iddistritos']) ? $_REQUEST['iddistritos'] : '');
		$corregimiento	= (!empty($_REQUEST['idcorregimientos']) ? $_REQUEST['idcorregimientos'] : '');
		$area  			= (!empty($_REQUEST['areacor']) ? $_REQUEST['areacor'] : '');
		
		//BITACORA		
		$valoresold = getRegistroSQL("	SELECT a.codigo AS 'Código', a.provincia AS 'Provincia', a.distrito AS 'Distrito',
										a.corregimiento AS 'Corregimiento', a.area AS 'Área'
										FROM direcciones a
										WHERE a.id = '".$iddireccion."' ");	
		$query = "	UPDATE direcciones SET codigo = '".$codigo."', provincia = '".$provincia."', distrito = '".$distrito."', 
					corregimiento = '".$corregimiento."', area ='".$area."'
					WHERE id = '".$iddireccion."'; ";
					
		if($mysqli->query($query)){
			$valoresnew = array(
				'Código' 		=> $codigo,
				'Provincia' 	=> $provincia,
				'Distrito' 		=> $distrito,
				'Corregimiento' => $corregimiento,
				'Área' 			=> $area
			);			
			actualizarRegistro('Corregimientos','Corregimiento',$iddireccion,$valoresold,$valoresnew,$query);			
			$response = array( "success" => true, "idcorregimiento" => $iddireccion );
		}else{
			$response = array( "success" => false, "idcorregimiento" => $query );			
		}
		echo json_encode($response);
	}
	
	function eliminar(){
		global $mysqli;		
		$id 	= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$nombre = (!empty($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '');
		
		$query = "DELETE FROM direcciones WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			eliminarRegistro('Corregimientos','Corregimiento',$nombre,$id,$query);
		    echo 1;
		}else{
			echo 0;
		}
	}
	
?>