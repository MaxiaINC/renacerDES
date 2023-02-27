	<?php
	
	include("../controller/conexion.php");
	
	
	
	
	
	
	
	
	
	
	
	$query  = " SELECT s.id AS solicitud, a.expediente, CONCAT(a.nombre, ' ', a.apellidopaterno, ' ', a.apellidomaterno) AS nombre, a.cedula, a.telefono, a.celular, a.sexo, 
				YEAR(CURDATE())-YEAR(a.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(a.fecha_nac,'%m-%d'), 0 , -1 ) AS edad, 
				d.codigo, d.provincia, d.distrito, d.corregimiento, b.tipodiscapacidad, s.condicionsalud, b.ayudatecnica, b.niveleducacional, 
				b.etnia, b.religion, b.ingresomensual, b.ingresomensualotro, b.estadocalles, b.mediotransporte,
				a.condicion_actividad, a.cobertura_medica, a.beneficios, b.tipovivienda, b.convivencia, s.estatus, 
				s.observacionesestados AS observacionessol, b.observaciones AS observacionesev, s.fecha_cita, s.fecha_solicitud,
				c.urbanizacion, c.calle, c.edificio, c.numero, b.cif, e.descripcion AS estado, r.nombre AS lugarsolicitud, 
				GROUP_CONCAT( DISTINCT enf.codigo  SEPARATOR ', ' ) AS cie,
				GROUP_CONCAT( DISTINCT enf.nombre  SEPARATOR ', ' ) AS desccie
				FROM pacientes a 
				LEFT JOIN evaluacion b ON b.idpaciente = a.id 
                INNER JOIN solicitudes s ON s.idpaciente = a.id
				INNER JOIN estados e ON s.estatus = e.id
				LEFT JOIN direccion c ON c.id = a.direccion 
				LEFT JOIN direcciones d ON d.id = c.iddireccion
				LEFT JOIN regionales r ON s.regional = r.id
				LEFT JOIN enfermedades enf ON FIND_IN_SET(enf.id,b.diagnostico)
				WHERE 1  AND YEAR(CURDATE())-YEAR(a.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(a.fecha_nac,'%m-%d'), 0 , -1 ) > 6 
							AND YEAR(CURDATE())-YEAR(a.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(a.fecha_nac,'%m-%d'), 0 , -1 ) < 11  AND date(s.fecha_cita) >= '2015-05-07'  AND date(s.fecha_cita) <= '2021-12-28'  GROUP BY s.id ";
		echo $query;					
	$result = $mysqli->query($query); 
	while($row = $result->fetch_assoc()){
		echo $row['nombre'];
		echo "<br>"; 
	}