<?php
    include_once("conexion.php");
    sessionrestore();


	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "listado": 
              listado();
			  break;
		case "getbitacora": 
              getbitacora();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}

	function listado(){
		global $mysqli;		
		/*--CONFIG-DATATABLE--------------------------------------------------*/
		$data   = (!empty($_REQUEST['data']) ? $_REQUEST['data'] : '');
    	//contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables
		$draw = (!empty($_REQUEST["draw"]) ? $_REQUEST["draw"] : 0);
		/*----------------------------------------------------------------------
		$orderByColumnIndex  = (!empty($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0);  
		//Obtener el nombre de la columna de clasificación de su índice
		$orderBy= (!empty($_REQUEST['columns'][$orderByColumnIndex]['data']) ?$_REQUEST['columns'][$orderByColumnIndex]['data'] : 0 );
		//ASC or DESC*/
		$orderType 	= (!empty($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'DESC'); 
	    $start   	= (!empty($_REQUEST['start']) ? $_REQUEST['start'] : 0);	
		$rowperpage = (!empty($_REQUEST['length']) ? $_REQUEST['length'] : 10);
		$vacio 		= array();
		$columns    = (!empty($_REQUEST['columns']) ? $_REQUEST['columns'] : $vacio);
		/*--------------------------------------------------------------------*/
		$nivel    = $_SESSION['nivel_sen'];

		$query  = " SELECT a.id, b.nombre AS usuario, a.fecha, a.modulo, LEFT(a.accion,45) as accion, a.accion  as acciontt, a.identificador, a.sentencia AS sentenciatt , LEFT(a.sentencia,45) as sentencia
					FROM bitacora a 
					INNER JOIN usuarios b ON a.usuario = b.usuario
					WHERE 1 = 1
					";

		if($nivel == 5 || $nivel == 7){
			$query .=" AND a.usuario = '".$usuario."'";
		}
		$hayFiltros = 0;
		for($i=0 ; $i<count($columns);$i++){
			$column = $_REQUEST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_REQUEST['columns'][$i]['search']['value']!="") {

                
				$campo = $_REQUEST['columns'][$i]['search']['value'];
				$campo = str_replace('^','',$campo);
				$campo = str_replace('$','',$campo);

				if ($column == 'usuario') {
					$column = 'b.nombre';
					$where2[] = " $column LIKE '%".$campo."%' ";
				}
				
				if ($column == 'fecha') {
					$column = "a.fecha";
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'modulo') {
					$column = 'a.modulo';
					$where2[] = " $column like '%".$campo."%' ";
				}
				if ($column == 'accion') {
					$column = 'a.accion';
					$where2[] = " $column like '%".$campo."%' ";
				} 				
				$hayFiltros++;
			}
		}		
		
//		echo $hayFiltros;
		$where = "";
		if ($hayFiltros > 0){
			$where .= " AND ".implode(" AND " , $where2)." ";// id like '%searchValue%' or name like '%searchValue%'
		}
		$query  .= " $where ";
		
		//$query .= " GROUP BY a.id ";
		$query .= " ORDER BY fecha DESC,a.id DESC ";
		$result = $mysqli->query($query);
		$recordsTotal = $result->num_rows;
		$query  .= " LIMIT ".$start.",".$rowperpage;
		if(!$result = $mysqli->query($query)){
		  die($mysqli->error);  
		}
		$resultado = array();
		$result = $mysqli->query($query);
		$recordsFiltered = $result->num_rows;
		while($row = $result->fetch_assoc()){
			/*
			$sentencia ="";
			$longsentencia = strlen($row['sentencia']);
			if($longsentencia>42){
				$points = " ...";
				$sentencia = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['sentenciatt']."'>".$row['sentenciatt'].$points."</span>";
			}else{ 
				$sentencia = $row['sentencia'];
			}
			*/
			$accion ="";
			$longaccion = strlen($row['accion']);
			if($longaccion>42){
				$points = " ...";
				$accion = "<span data-toggle='tooltip' class='prueba' data-placement='right' data-original-title='".$row['acciontt']."'>".$row['accion'].$points."</span>";
			}else{ 
				$accion = $row['accion'];
			}

		    $acciones = '<td>
							<div class="dropdown ml-auto text-center">
								<div class="btn-link" data-toggle="dropdown">
									<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
								</div>
								<div class="dropdown-menu dropdown-menu-center">
								    <a class="dropdown-item text-warning" href="bitacora-ne.php?id='.$row['id'].'"><i class="fas fa-eye mr-2"></i>Ver</a>
								</div>
							</div>
						</td>';
		    $resultado[] = array(			
				'id' 			=>	$row['id'],	
				'acciones' 		=> $acciones,
				'usuario'		=>	$row['usuario'], 
				'fecha'			=>	$row['fecha'],
				'modulo'		=>	$row['modulo'],
				'accion'		=>	$row['acciontt'],/*
				'identificador'	=>	$row['identificador'],
				'sentencia'		=>	$sentencia,*/
			);
		}
		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($rowperpage),
			"data" => $resultado
		  );
		;
		echo json_encode($response);
	}
	
	function getbitacora(){
		global $mysqli;
		
		$idbitacora	= $_REQUEST['idbitacora'];
		$query 		= "	SELECT * FROM bitacora WHERE id = '$idbitacora' ";
		$result 	= $mysqli->query($query);
		
		while($row = $result->fetch_assoc()){
			$resultado = array(
				'usuario'		=>	$row['usuario'], 
				'fecha' 		=>	$row['fecha'],
				'modulo' 		=>	$row['modulo'],
				'accion' 		=>	$row['accion'],
				'identificador' =>	$row['identificador'],
				'sentencia' 	=>	$row['sentencia']
			);
		}
			if( isset($resultado) ) {
			echo json_encode($resultado);
		} else {
			echo "0";
		}
	}
	
?>