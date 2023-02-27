<?php

//--SCRIPT PARA ACTUALIZAR SOLICITUDES A ESTADO PENDIENTE POR CARNET, 24 HORAS LUEGO DE HABER ESTADO EN RESOLUCIÓN DE CERTIFICACIÓN GENERADA--//

include("../controller/conexion.php");

global $mysqli;

// consultar la fecha y hora actual
$current_timestamp = strtotime(date("Y-m-d H:i:s"));
$estadoResolucionDeCertificacionGenerada = 27;
$estadoPendientePorCarnet = 24;

// consultar la fecha y hora de registro de la solicitud
$query = "SELECT id, estatus, fechacambioestado FROM solicitudes WHERE estatus = ".$estadoResolucionDeCertificacionGenerada."";
$result = $mysqli->query($query);		
while($row = $result->fetch_assoc()){
	
	$id = $row['id'];
	echo '<br>id='.$id;
	$estado = $row['estatus'];
	$fechacambioestado = strtotime($row['fechacambioestado']);
	
	if($fechacambioestado != ''){
		$fechacambioestado = $fechacambioestado;
	}else{
		$sql = "SELECT fecha FROM solicitudes_estados WHERE idsolicitud = ".$id." AND estadoactual = ".$estadoResolucionDeCertificacionGenerada." ORDER BY fecha DESC LIMIT 1 ";
		$rta = $mysqli->query($sql);		
		if($reg = $rta->fetch_assoc()){
			$fechacambioestado = strtotime($reg['fecha']);
		}
	}
	//echo '<br>fechacambioestado ='.$fechacambioestado;
	if($fechacambioestado != ''){
		
		if($estado == $estadoResolucionDeCertificacionGenerada){
			// comparar la diferencia entre las dos fechas y horas
			$difference = $current_timestamp - $fechacambioestado;
			//echo 'fechaactual:'.$current_timestamp.'-fechacambioestado:'.$fechacambioestado;
			echo '<br>diferencia:'.$difference;
			// si ha pasado más de 24 horas, actualizar el estado de la solicitud
			if ($difference >= 86400) {
				$update_query = "UPDATE 
									solicitudes 
								SET 
									estatus = ".$estadoPendientePorCarnet." 
								WHERE 
									estatus = ".$estadoResolucionDeCertificacionGenerada."
									AND id = ".$id." ";
				echo '<br>'.$update_query;
				/* $result_update_query = $mysqli->query($update_query);
				if($result_update_query == true){
					
					//Crear registro en solicitudes_estados
					$queryE = " INSERT INTO solicitudes_estados (idsolicitud,usuario,fecha,estadoanterior,estadoactual)
								VALUES(".$id.",0, CURDATE(), '".$estadoResolucionDeCertificacionGenerada."', '".$estadoPendientePorCarnet."') "; 
								//echo $queryE;
					$mysqli->query($queryE);
					
					//Guardar en bitácora el cambio de estado
					$valoresoe = array('Estado' => getValor('descripcion','estados',$estadoResolucionDeCertificacionGenerada,'') );
					$valoresne = array('Estado' => getValor('descripcion','estados',$estadoPendientePorCarnet,'') );
					actualizarRegistro('Solicitudes','Solicitud (T.P.)',$id,$valoresoe,$valoresne,$update_query);
					
				} */
			} 
		}
	}else{
		echo '<br>no pasó:';
	}
} 

?>