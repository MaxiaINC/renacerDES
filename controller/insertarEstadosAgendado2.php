

<?php

include("conexionanalitica.php");

global $mysqli;
$sql = " SELECT id,idsolicitud,usuario,fecha,estadoactual 
		FROM solicitudes_estados 
		WHERE estadoactual = 2 AND idsolicitud = 21147";
		echo $sql."<br>";
$rta = $mysqli->query($sql);
while ($reg = $rta->fetch_assoc()) {
	
	//$idSol = $reg['id'];
	$idsolicitud = $reg['idsolicitud'];
	$usuarioSol = $reg['usuario'];
	$fechaSol = $reg['fecha'];
	$estadoactual = $reg['estadoactual'];
	
	//echo 'idSol:'.$idSol.'<br>';
	echo 'idsolicitud:'.$idsolicitud.'<br>';
	echo 'usuarioSol:'.$usuarioSol.'<br>';
	echo 'fechaSol:'.$fechaSol.'<br>';
	echo 'estadoactual:'.$estadoactual.'<br>';
	 
	$query = " SELECT bitacoracopia2.id,DATE(bitacoracopia2.fecha) AS fecha, 
				TIME(bitacoracopia2.fecha) AS hora,
				bitacoracopia2.identificador,usuarios.id AS idusuario,
				bitacoracopia2.sentencia 
				FROM bitacoracopia2 
				LEFT JOIN usuarios ON usuarios.usuario = bitacoracopia2.usuario
				WHERE identificador = $idsolicitud
				AND modulo IN ('Solicitudes','Evaluación')
				AND 
				sentencia LIKE '% estatus =  2%'
				AND bitacoracopia2.id < 332416
				";
				
	$result = $mysqli->query($query);
	$total = $result->num_rows;
	if($result->num_rows > 0){
		while ($row = $result->fetch_assoc()) {
			
			echo '<br>Entro en tabla bitácora<BR>';
			echo '-----------------------<BR>';
			
			//$idbit = $row['id'];
			$fechaBit = $row['fecha'];
			$horaBit = $row['hora'];
			$identificador = $row['identificador'];
			$usuarioBit = $row['idusuario'];
			$sentencia = $row['sentencia'];
			
			//echo 'idbit:'.$idbit.'<br>';
			echo 'fechaBit:'.$fechaBit.'-Hora:'.$horaBit.'<br>';
			echo 'identificador:'.$identificador.'<br>';
			echo 'usuarioBit:'.$usuarioBit.'<br>';
			echo 'sentencia:'.$sentencia.'<br>'; 
			 
			
			if($idsolicitud == $identificador && $usuarioSol == $usuarioBit &&
			$fechaSol == $fechaBit){
				echo "SON IGUALES NO INSERTAR<BR>";
				return false;
			}else{
				echo "SON DIFERENTES - INSERTAR<BR>";
				$queyUs = "SELECT usuario FROM usuarios WHERE id =".$usuarioSol."";
				$resultUs = $mysqli->query($queyUs);
				if ($rowUs = $resultUs->fetch_assoc()) {
					$usuario = $rowUs['usuario'];
				}
				/* $insert = "INSERT INTO bitacoracopia2 (usuario,fecha,modulo,accion,identificador,sentencia) VALUES
				  ('".$usuario."','".$fechaSol." 08:00:00','Solicitudes','Fue actualizado un registro en Solicitud con el id #".$idsolicitud.". <br/><ul><li>El campo <b>Estado</b> fue modificado. Valor nuevo: Agendado.</li></ul>',".$idsolicitud.",'UPDATE solicitudes SET estatus =  2  WHERE id =  ".$idsolicitud." - Ajuste ')";
				  echo "INSERT:".$insert;
						$mysqli->query($insert);  */ 
			} 
			
			
			/* $arraySent = explode('estatus',$sentencia);
			$elEstatus = $arraySent[1];
			$resultado = substr($elEstatus, 0,5);
			echo 'resultado:'.$resultado; */

		}
	}else{
		echo "NO TIENE NINGUNO, INSERTAR EN BITÁCORA<BR>";
		$queyUs = "SELECT usuario FROM usuarios WHERE id =".$usuarioSol."";
		$resultUs = $mysqli->query($queyUs);
		if ($rowUs = $resultUs->fetch_assoc()) {
			$usuario = $rowUs['usuario'];
		}
		
		/* $insert = "INSERT INTO bitacoracopia2 (usuario,fecha,modulo,accion,identificador,sentencia) VALUES
				  ('".$usuario."','".$fechaSol." 08:00:00','Solicitudes','Fue actualizado un registro en Solicitud con el id #".$idsolicitud.". <br/><ul><li>El campo <b>Estado</b> fue modificado. Valor nuevo: Agendado.</li></ul>',".$idsolicitud.",'UPDATE solicitudes SET estatus =  2  WHERE id =  ".$idsolicitud." - Ajuste ')";
		$mysqli->query($insert);  */
	}
}

?>