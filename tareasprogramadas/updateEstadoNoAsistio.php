<?php

//--SCRIPT PARA ACTUALIZAR SOLICITUDES A ESTADO NO ASISTIÓ, EN EL CASO DE QUE HAYA FINALIZADO EL DÍA Y LA SOLICITUD ESTÉ EN ESTADO AGENDADO--//

include("../controller/conexion.php");

global $mysqli;

// consultar la fecha y hora actual
$current_date = date("Y-m-d");
$current_time = date("G");
$estadoAgendado = 2;
$estadoNoAsistio = 6;

// consultar la fecha y hora de registro de la solicitud
$query = "SELECT id, estatus, fechacambioestado FROM solicitudes WHERE estatus = ".$estadoAgendado." AND DATE(fecha_cita) = '".$current_date."'";
//echo 'query:'.$query;
$result = $mysqli->query($query);		
while($row = $result->fetch_assoc()){
	
	$id = $row['id'];
	echo '<BR>id='.$id;
	$estado = $row['estatus'];
	//$fechacambioestado = strtotime($row['fechacambioestado']);
	//echo '<br>Hora actual: '.$current_time;
	if ($current_time >= 22) {  // 10pm
		$update_query = "UPDATE 
							solicitudes 
						SET 
							estatus = ".$estadoNoAsistio." 
						WHERE 
							estatus = ".$estadoAgendado."
							AND id = ".$id." ";
		echo '<br>'.$update_query;
		/* $result_update_query = $mysqli->query($update_query);
		if($result_update_query == true){
			
			//Crear registro en solicitudes_estados
			$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
						VALUES(".$id.",0, CURDATE(), '".$estadoAgendado."', '".$estadoNoAsistio."') "; 
						//echo $queryE;
			$mysqli->query($queryE);
			
			//Guardar en bitácora el cambio de estado
			$valoresoe = array('Estado' => getValor('descripcion','estados',$estadoAgendado,'') );
			$valoresne = array('Estado' => getValor('descripcion','estados',$estadoNoAsistio,'') );
			actualizarRegistro('Solicitudes','Solicitud (T.P.)',$id,$valoresoe,$valoresne,$update_query);
			
		}  */
	}
} 

?>