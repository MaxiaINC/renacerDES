<?php
    include("conexion.php");
    include("funciones.php");
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
		case "subirFoto":
			  subirFoto();
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
		
		$query = "	SELECT a.id, b.nombre, a.cargo, b.regional, a.estado FROM firmas a
		            INNER JOIN usuarios b ON b.id = a.idusuarios 
					WHERE 1 = 1 " ;
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
									<a class="dropdown-item text-info boton-modificar-nivel" data-id="'.$row['id'].'" href="firma.php?id='.$row['id'].'"><i class="fas fa-pen mr-2"></i>Editar</a>';
			$acciones .= 			'<a class="dropdown-item '.$color_estado.' font-w600 boton-estado" href="#" data-id="'.$row['id'].'" data-estado="'.$estado.'"><i class="fas '.$icon_estado.' mr-2"></i>'.$txt_estado.'</a>';	
			$acciones .= '</div>
							</div>
						</td>';
						
			$resultado[] = array(
				'id' 		=>	$row['id'],
				'acciones' 	=>	$acciones,
				'nombre'	=>  $row['nombre'],
				'cargo'	    =>  $row['cargo'],
				'regional'	=>  $row['regional'],
				'estado'	=>  $estado
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
		
		$query = "	SELECT a.id, a.cargo, a.idusuarios, b.regional FROM firmas a 
					INNER JOIN usuarios b ON b.id = a.idusuarios 
					WHERE a.id = '".$id."' ";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
		    $ruta = '../images/firmas/'.$row['id'].'/';	
		    $imagen = fotoPaciente($row['id'],$ruta);
			$resultado = array(
				'id' 		=> $row['id'],
				'idusuarios'=> $row['idusuarios'],
				'cargo' 	=> $row['cargo'],
				'regional' 	=> $row['regional'],
				'firma'     => $imagen
			);
		}
		echo json_encode($resultado);
	}
	
	function guardar(){
		global $mysqli; 
		
		$idusuarios = (!empty($_REQUEST['idusuarios']) ? $_REQUEST['idusuarios'] : ''); 
		$regional = (!empty($_REQUEST['regional']) ? $_REQUEST['regional'] : ''); 
		$cargo = (!empty($_REQUEST['cargo']) ? $_REQUEST['cargo'] : ''); 
		
		//BITACORA 
		$campos = array(
			'Nombre' => getValor('nombre','usuarios',$idusuarios),
			'Regional' => $regional,
			'Cargo' => $cargo 
		);
		
		$sql = "SELECT a.id 
				FROM firmas a 
				INNER JOIN usuarios b ON b.id = a.idusuarios 
				WHERE b.regional = '".$regional."' AND a.cargo = '".$cargo."' AND a.estado = 'Activo'";
				//echo $sql;
		$rta = $mysqli->query($sql);
		$total = $rta->num_rows;
		if($total>0){
		    $response = array( "success" => false, "msj" => 'Ya existe una firma para esta regional y este cargo' );
		    echo json_encode($response);
		}else{
		    $query = "	INSERT INTO firmas (idusuarios,cargo) 
    					VALUES($idusuarios,'$cargo')";
						//echo $query;
    		if($mysqli->query($query)){
    			$id = $mysqli->insert_id;
    			nuevoRegistro('Firmas','Firma',$id,$campos,$query);
				
				//Foto
				$num 	= $_SESSION['user_id_sen'];
				$from 	= '../images/firmas/temporal/'.$num;
				
				if (is_dir($from)) {
					//Escaneamos el directorio
					$carpeta = @scandir($from);
					//Miramos si existen archivos
					if (count($carpeta) > 2){
						$to 	= '../images/firmas/'.$id.'/';
						$target_pathInc = utf8_decode($to);
						if (!file_exists($target_pathInc)) {
							mkdir($target_pathInc, 0777);
						}
						$verificarruta = '../images/firmas/temporal/'.$num.'/';
					// 	//Abro el directorio que voy a leer
						$target_path2 = utf8_decode($verificarruta);
					// 	echo $target_path2;
						if (file_exists($target_path2)){
							// echo "paso por aqui";
							$dir = opendir($from);
							while(($file = readdir($dir)) !== false){
								//Leo todos los archivos excepto . y ..
								if(strpos($file, '.') !== 0){
									$extension = pathinfo($from.'/'.$file, PATHINFO_EXTENSION);
									
									//Copio el archivo manteniendo el mismo nombre en la nueva carpeta
									copy($from.'/'.$file, $to.'/'.$id.'.'.$extension);
									unlink($from.'/'.$file);
								}
							}
							bitacora('Firmas','Fue agregada la firma',$id,'');
						}
					}
				}
    			
    			$response = array( "success" => true, "msj" => 'Registro creado satisfactoriamente' );			
    		}else{
    			$response = array( "success" => false, "msj" => 'Error al crear el registro' );
    		}
    		echo json_encode($response);
		}
		
	} 

	function editar(){
		global $mysqli;
		$id 		= (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');	
		$idusuarios = (!empty($_REQUEST['idusuarios']) ? $_REQUEST['idusuarios'] : '');
		$regional = (!empty($_REQUEST['regional']) ? $_REQUEST['regional'] : ''); 		
		$cargo 	= (!empty($_REQUEST['cargo']) ? $_REQUEST['cargo'] : '');	
		
		$valoresold = getRegistroSQL("	SELECT b.nombre AS 'Nombre', a.cargo AS 'Cargo'
										FROM firmas a 
										INNER JOIN usuarios b ON b.id = a.idusuarios
										WHERE a.id = '".$id."' ");		
		
		$query = "  UPDATE firmas SET idusuarios = '".$idusuarios."', cargo = '".$cargo."'
					WHERE id = '".$id."' ";	
					
		if($mysqli->query($query)){
			$valoresnew = array(
				'Nombre' => getValor('nombre','usuarios',$idusuarios),
				'Regional' => $regional,
				'Cargo' => $cargo
			);			
			actualizarRegistro('Firmas','Firma',$id,$valoresold,$valoresnew,$query);
			
			$response = array( "success" => true, "msj" => 'Registro actualizado satisfactoriamente' );
		}else{
			$response = array( "success" => false, "msj" => 'Error al actualizar el registro' );			
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
			
		$query = "UPDATE firmas SET estado = '". $estadoNuevo ."' WHERE id = '".$id."' ";
		$result = $mysqli->query($query);
		if($result == true){
			actualizarRegistro('Firmas','Firma',$id,$valoresold,$valoresnew,$query);
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
	
	function subirFoto(){
		
		$idfirma = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
		$id = $_SESSION['user_id_sen'];
		
		if(isset($_FILES['images'])){
			//echo json_encode($_FILES);
			//Parámetros optimización, resolución máxima permitida
			$max_ancho = 400;
			$max_alto = 200;
			
			if($idfirma !== ''){
				echo 'idfirma:'.$idfirma;
				createFolder("../images/firmas/".$idfirma."/");
				$patch = "../images/firmas/".$idfirma."/";
				$nombrearchivo = $idfirma;
			}else{
				createFolder("../images/firmas/temporal/".$id."/");
				$patch = "../images/firmas/temporal/".$id."/"; 
				$nombrearchivo = $id;
			}
				
			if($_FILES['images']['type']=='image/png' || $_FILES['images']['type']=='image/jpeg' || $_FILES['images']['type']=='image/gif'){
				
				$medidasimagen= getimagesize($_FILES['images']['tmp_name']);
				if($_FILES['images']['type']=='image/png')
					$extension = '.png';
				if($_FILES['images']['type']=='image/jpeg')
					$extension = '.jpg';	
				if($_FILES['images']['type']=='image/gif')
					$extension = '.gif';
				
				//Si las imagenes tienen una resolución y un peso aceptable se suben tal cual
				if($medidasimagen[0] < 400 && $_FILES['images']['size'] < 100000){
					
					//$nombrearchivo=$_FILES['images']['name'];
					move_uploaded_file($_FILES['images']['tmp_name'], $patch.'/'.$nombrearchivo.$extension);
					
				}else {
					
					//Si no, se generan nuevas imagenes optimizadas
					//$nombrearchivo=$_FILES['images']['name'];
					
					//Redimensionar
					$rtOriginal=$_FILES['images']['tmp_name'];
					
					if($_FILES['images']['type']=='image/jpeg'){
						$original = imagecreatefromjpeg($rtOriginal);
					}
					else if($_FILES['images']['type']=='image/png'){
						$original = imagecreatefrompng($rtOriginal);
					}
					else if($_FILES['images']['type']=='image/gif'){
						$original = imagecreatefromgif($rtOriginal);
					}

			 
					list($ancho,$alto)=getimagesize($rtOriginal);

					$x_ratio = $max_ancho / $ancho;
					$y_ratio = $max_alto / $alto;


					if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){
						$ancho_final = $ancho;
						$alto_final = $alto;
					}
					elseif (($x_ratio * $alto) < $max_alto){
						$alto_final = ceil($x_ratio * $alto);
						$ancho_final = $max_ancho;
					}
					else{
						$ancho_final = ceil($y_ratio * $ancho);
						$alto_final = $max_alto;
					}

					$lienzo=imagecreatetruecolor($ancho_final,$alto_final); 

					imagecopyresampled($lienzo,$original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
					 
					//imagedestroy($original);
					 
					$cal=8;

					if($_FILES['images']['type']=='image/jpeg'){
						imagejpeg($lienzo,$patch."/".$nombrearchivo.$extension);
					}
					else if($_FILES['images']['type']=='image/png'){
						imagepng($lienzo,$patch."/".$nombrearchivo.$extension);
					}
					else if($_FILES['images']['type']=='image/gif'){
						imagegif($lienzo,$patch."/".$nombrearchivo.$extension);
					}

				}
				
			} else echo 'fichero no soportado';
		}
		
	}
	
?>