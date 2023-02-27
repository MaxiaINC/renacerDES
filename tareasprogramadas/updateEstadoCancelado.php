<?php

//--SCRIPT PARA ACTUALIZAR SOLICITUDES A ESTADO CANCELADO, SI HA ESTADO POR 6 MESES EN ESTADO PENDIENTE--//

include("../controller/conexion.php");

global $mysqli;

// consultar la fecha y hora actual
$current_timestamp = date("Y-m-d");
$estadoCancelado = 12;
$estadoPendiente = 16;

// consultar la fecha y hora de registro de la solicitud
$query = "SELECT id, estatus, DATE(fechacambioestado) AS fechacambioestado FROM solicitudes WHERE estatus = ".$estadoPendiente." ORDER BY id DESC ";
$result = $mysqli->query($query);		
while($row = $result->fetch_assoc()){
	
	$id = $row['id'];
	echo '<br>id='.$id;
	$estado = $row['estatus'];
	echo '<br>estatus='.$estado;
	$fechacambioestado = $row['fechacambioestado'];
	
	if($fechacambioestado != ''){
		$fechacambioestado = $fechacambioestado;
	}else{
		$sql = "SELECT fecha FROM solicitudes_estados WHERE idsolicitud = ".$id." AND estadoactual = ".$estadoPendiente." ORDER BY fecha DESC LIMIT 1 ";
		$rta = $mysqli->query($sql);		
		if($reg = $rta->fetch_assoc()){
			$fechacambioestado = $reg['fecha'];
		}
	}
	
	if($fechacambioestado != ''){
		if($estado == $estadoPendiente){
			
			$date1 = new DateTime($fechacambioestado);
			$date2 = new DateTime($current_timestamp);
			$diff = $date2->diff($date1);
			$months = ($diff->y * 12) + $diff->m;
			echo '<br>MESES: '.$months;
			if ($months >= 6) {
				$update_query = "UPDATE 
									solicitudes 
								SET 
									estatus = ".$estadoCancelado." 
								WHERE 
									estatus = ".$estadoPendiente."
									AND id = ".$id." ";
				echo '<br>'.$update_query;
				/* $result_update_query = $mysqli->query($update_query);
				if($result_update_query == true){
					
					//Crear registro en solicitudes_estados
					$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
								VALUES(".$id.",0, CURDATE(), '".$estadoPendiente."', '".$estadoCancelado."') "; 
								//echo $queryE;
					$mysqli->query($queryE);
					
					//Guardar en bitácora el cambio de estado
					$valoresoe = array('Estado' => getValor('descripcion','estados',$estadoPendiente,'') );
					$valoresne = array('Estado' => getValor('descripcion','estados',$estadoCancelado,'') );
					actualizarRegistro('Solicitudes','Solicitud (T.P.)',$id,$valoresoe,$valoresne,$update_query);
					
				}
				*/
			} 
		}
	}
} 

?>