<?php
	include("conexion.php");
	sessionrestore();
	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	switch($oper){
		case "usuariosCertificados": 
              usuariosCertificados();
			  break;
		case "nivelAlfabetismo": 
              nivelAlfabetismo();
			  break;
	    case "nivelEducativo": 
              nivelEducativo();
			  break;
		case "condicionLaboralUsuarios": 
              condicionLaboralUsuarios();
			  break;
	    case "ingresosFamiliaresUsuarios": 
              ingresosFamiliaresUsuarios();
			  break;
	    case "tipoDiscapacidad": 
              tipoDiscapacidad();
			  break;			  
		case "solicitudesMes": 
              solicitudesMes();
			  break;
		case "mapa": 
              mapa();
			  break;
	    case "totales": 
              totales();
			  break;
		case "promedioSA": 
              promedioSA();
			  break;
	    case "promedioSR": 
              promedioSR();
			  break;			  
	    case "guardarfiltros": 
              guardarfiltros();
			  break;
		case "abrirfiltros": 
              abrirfiltros();
			  break;
	    case "verificarfiltros": 
              verificarfiltros();
			  break;
	    case "limpiarFiltrosMasivos": 
              limpiarFiltrosMasivos();
			  break;
		default:
			  echo "{failure:true}";
			  break;
	}

function nivelAlfabetismo() {
	global $mysqli;
	$usuario 		= $_SESSION['usuario_sen'];
	
	$query = "	SELECT COALESCE(e.alfabetismo,0) AS alfabetismo, 
				SUM(p.sexo = 'F') AS mujeres, 
				SUM(p.sexo = 'M') AS hombres 
				FROM pacientes p 
				INNER JOIN evaluacion e ON e.idpaciente = p.id 
				INNER JOIN solicitudes s ON s.idpaciente = p.id 
				LEFT JOIN direccion d ON d.id = p.direccion 
				LEFT JOIN direcciones dir ON dir.id = d.iddireccion
				WHERE 1 ";
	
	//Aplicar Filtros
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(s.fecha_cita) >= ".$desdef." ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(s.fecha_cita) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND dir.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND dir.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND dir.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND s.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND p.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND s.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//Fin Aplicar Filtros
	
	$query .= " GROUP BY e.alfabetismo ORDER BY e.alfabetismo ";	 
	
	$result = $mysqli->query($query);	
	$nbrows = $result->num_rows;
	
	if($nbrows > 0){
		$cantidadF = array();
		$cantidadH = array();
		$categorias = array();
		
		$nameF = 'Mujeres';
		$nameH = 'Hombres';
		
		while($row = $result->fetch_assoc()){
			$alfabetismo = (int)$row['alfabetismo'];
				
			if($alfabetismo == 0){
				$categorias[] = 'Sin Especificar';
			}
			if($alfabetismo == 1){
				$categorias[] = 'Alfabetizado';
			}
			if($alfabetismo == 2){
				$categorias[] = 'Analfabeto';
			}
			if($alfabetismo == 3){
				$categorias[] = 'Analfabeto instrumental';
			}
			if($alfabetismo == 4){
				$categorias[] = 'No aplicable';
			}
			$cantidadF[] = (int)$row['mujeres'];
			$cantidadH[] = (int)$row['hombres'];
		}
		$valores[] 	= array(
			'color' => '#267cbc',
			'name' 	=> $nameH,
			'data' 	=> $cantidadH
		);
		$valores[] 	= array(
			'color' => '#f7a6ce',
			'name' 	=> $nameF,
			'data' 	=> $cantidadF
		); 
		echo json_encode(array( 'categorias' => json_encode($categorias), 'valores' => json_encode($valores)  )	);
	} else { 
		$valores[] 	= array(
				'name' 	=> "Sin resultados",
				'data' 	=> [0]
			);
		echo json_encode(array( 'categorias' => json_encode("Sin resultados"), 'valores' => json_encode($valores)  )	);
	}
}

function usuariosCertificados() {
	global $mysqli; 
	$usuario 		= $_SESSION['usuario_sen'];
	
	$query = "	SELECT 
	            SUM(p.sexo = 'F') AS mujeres,
	            SUM(p.sexo = 'M') AS hombres
	            FROM pacientes p
                INNER JOIN solicitudes s ON s.idpaciente = p.id
				LEFT JOIN direccion d ON d.id = p.direccion 
				LEFT JOIN direcciones e ON e.id = d.iddireccion
                WHERE s.estatus = 3 ";
	
	//Aplicar Filtros
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(s.fecha_cita) >= ".$desdef." ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(s.fecha_cita) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND e.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND e.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND e.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND s.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND p.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND s.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//Fin Aplicar Filtros 
	
	$result = $mysqli->query($query);	
	$nbrows = $result->num_rows;
	
	if($nbrows > 0){
		while($row = $result->fetch_assoc()){
		    $total = $row['mujeres'] + $row['hombres'];
			if($total > 0){
				$cantidadF = round($row['mujeres']/$total*100,1);
				$cantidadH = round($row['hombres']/$total*100,1);
			}else{
				$cantidadF = 0;
				$cantidadH = 0;
			}
			$cantidadFT = (int)$row['mujeres'];
			$cantidadHT = (int)$row['hombres'];
		}
		echo json_encode(array( 'mujeres' => $cantidadF, 'hombres' => $cantidadH, 'mujeresT' => $cantidadFT, 'hombresT' => $cantidadHT  )	);
	} else { 
		echo json_encode(array( 'mujeres' => '0', 'hombres' => '0', 'mujeresT' => '0', 'hombresT' => '0'  )	);
	}	
}
	
function condicionLaboralUsuarios() {
	global $mysqli;
	$resultado 		= array();
	$usuario 		= $_SESSION['usuario_sen'];
	 
	$query = " 	SELECT COALESCE(condicion_actividad,0) AS condicion_actividad, COUNT(p.id) AS numero 
				FROM pacientes p 
				INNER JOIN solicitudes s ON s.idpaciente = p.id 
				LEFT JOIN direccion d ON d.id = p.direccion 
				LEFT JOIN direcciones e ON e.id = d.iddireccion 
				WHERE 1 "; 
	 
	//Aplicar Filtros
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(s.fecha_cita) >= ".$desdef." ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(s.fecha_cita) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND e.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND e.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND e.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND s.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND p.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND s.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//Fin Aplicar Filtros
	
	$query .= "	GROUP by condicion_actividad ";	 
	
	$result = $mysqli->query($query);
	$nbrows = $result->num_rows;

	if($nbrows > 0){
		$objJson = array();
		while($rec = $result->fetch_assoc()){
			
			if($rec['condicion_actividad'] == 0){
				$name = 'Sin Especificar';
			}
			if($rec['condicion_actividad'] == 1){
				$name = 'Trabaja';
			}
			if($rec['condicion_actividad'] == 2){
				$name = 'No trabaja';
			}
			if($rec['condicion_actividad'] == 3){
				$name = 'Busca trabajo';
			}
			if($rec['condicion_actividad'] == 4){
				$name = 'No busca trabajo';
			}
			if($rec['condicion_actividad'] == 5){
				$name = 'No aplicable';
			}
			$y = round($rec['numero']);
			$objJson[] = array(
				'name' => $name,
				'y'    => $y  
			);
		} 
		echo json_encode($objJson);
	}else{
		$objJson[] = array(
				'name' => "Sin resultados",
				'y'    => 0  
			);
		echo json_encode($objJson);
	} 
}
	
function nivelEducativo() {	
	global $mysqli;
	$usuario 		= $_SESSION['usuario_sen'];
	
	$query = "	SELECT 
	            COALESCE(e.niveleducacional,0) AS niveleducacional,
	            SUM(p.sexo = 'F') AS mujeres,
	            SUM(p.sexo = 'M') AS hombres
	            FROM pacientes p
                INNER JOIN evaluacion e ON e.idpaciente = p.id
				INNER JOIN solicitudes s ON s.idpaciente = p.id 
				LEFT JOIN direccion d ON d.id = p.direccion 
				LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
				WHERE 1 ";
	
	//Aplicar Filtros
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(s.fecha_cita) >= ".$desdef." ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(s.fecha_cita) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND dir.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND dir.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND dir.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND s.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND p.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND s.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//Fin Aplicar Filtros
	
	$query .= " GROUP BY e.niveleducacional ORDER BY e.niveleducacional";
	
	
	$result = $mysqli->query($query);	
	$nbrows = $result->num_rows;
	
	if($nbrows > 0){  
		
		$cantidadF = array();
		$cantidadH = array();
		$categorias = array();
		
		$nameF = 'Mujeres';
		$nameH = 'Hombres';
		
		while($row = $result->fetch_assoc()){
			$niveleducacional = (int)$row['niveleducacional'];
			
			if($niveleducacional == 0){
				$categorias[] = 'Sin Especificar';
			}
			if($niveleducacional == 1){
				$categorias[] = 'Inicial';
			}
			if($niveleducacional == 2){
				$categorias[] = 'Primario';
			}
			if($niveleducacional == 3){
				$categorias[] = 'Secundario';
			}
			if($niveleducacional == 4){
				$categorias[] = 'Terciario / Universitario';
			}
			$cantidadF[] = (int)$row['mujeres'];
			$cantidadH[] = (int)$row['hombres'];
		}
		$valores[] 	= array(
			'color' => '#267cbc',
			'name' 	=> $nameH,
			'data' 	=> $cantidadH
		);
		$valores[] 	= array(
			'color' => '#f7a6ce',
			'name' 	=> $nameF,
			'data' 	=> $cantidadF
		); 
		echo json_encode(array( 'categorias' => json_encode($categorias), 'valores' => json_encode($valores)  )	);
	} else { 
		$valores[] 	= array(
				'name' 	=> "Sin resultados",
				'data' 	=> [0]
			);
		echo json_encode(array( 'categorias' => json_encode("Sin resultados"), 'valores' => json_encode($valores)  )	);
	}	
}

function ingresosFamiliaresUsuarios() {	
	global $mysqli;
	$usuario 		= $_SESSION['usuario_sen'];
	
	$query = "	SELECT COALESCE(e.ingresomensual,'Sin Especificar') AS ingresomensual, 
				SUM(p.sexo = 'F') AS mujeres, SUM(p.sexo = 'M') AS hombres, 
				SUBSTRING_INDEX(e.ingresomensual,'a',1) as ordenar 
				FROM pacientes p 
				INNER JOIN evaluacion e ON e.idpaciente = p.id 
				INNER JOIN solicitudes s ON s.idpaciente = p.id 
				LEFT JOIN direccion d ON d.id = p.direccion 
				LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
				WHERE 1 ";
	
	//Aplicar Filtros
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(s.fecha_cita) >= ".$desdef." ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(s.fecha_cita) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND dir.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND dir.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND dir.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == 'primerainfacia'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND s.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND p.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND s.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//Fin Aplicar Filtros	
	$query .= " GROUP BY e.ingresomensual ORDER BY ABS(ordenar) ";
	
	
	$result = $mysqli->query($query);	
	$nbrows = $result->num_rows;
	
	if($nbrows > 0){  		
		$cantidadF = array();
		$cantidadH = array();
		$categorias = array();
		
		$nameF = 'Mujeres';
		$nameH = 'Hombres';
		
		while($row = $result->fetch_assoc()){
			$nc = html_entity_decode(ucfirst(strtolower(htmlentities($row['ingresomensual']))));
			$categorias[] = $nc;
			$cantidadF[] = (int)$row['mujeres'];
			$cantidadH[] = (int)$row['hombres'];
		}
		$valores[] 	= array(
			'color' => '#267cbc',
			'name' 	=> $nameH,
			'data' 	=> $cantidadH
		);
		$valores[] 	= array(
			'color' => '#f7a6ce',
			'name' 	=> $nameF,
			'data' 	=> $cantidadF
		); 
		echo json_encode(array( 'categorias' => json_encode($categorias), 'valores' => json_encode($valores)  )	);
	} else { 
		$valores[] 	= array(
				'name' 	=> "Sin resultados",
				'data' 	=> [0]
			);
		echo json_encode(array( 'categorias' => json_encode("Sin resultados"), 'valores' => json_encode($valores)  )	);
	}
}

function tipoDiscapacidad() {	
	global $mysqli;
	$usuario 		= $_SESSION['usuario_sen'];
	 
	$query = "	SELECT b.nombre AS name, COUNT(*) AS solicitudes, 
				SUM(case when a.estatus = 3 then 1 else 0 end) as certificados, 
				SUM(case when a.estatus = 4 then 1 else 0 end) as nocertificados 
				FROM solicitudes a 
				INNER JOIN discapacidades b ON a.iddiscapacidad = b.id 
				INNER JOIN pacientes c ON c.id = a.idpaciente 
				LEFT JOIN direccion d ON d.id = c.direccion 
				LEFT JOIN direcciones e ON e.id = d.iddireccion 
				WHERE 1 ";
				
	$querySol = "	SELECT
						b.nombre AS name, COUNT(*) AS solicitudes 
					FROM solicitudes a 
						INNER JOIN discapacidades b ON a.iddiscapacidad = b.id 
						INNER JOIN pacientes c ON c.id = a.idpaciente 
						LEFT JOIN direccion d ON d.id = c.direccion 
						LEFT JOIN direcciones e ON e.id = d.iddireccion 
					WHERE 1 ";
					
	//Aplicar Filtros
	$queryF	 = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(a.fecha_cita) >= ".$desdef." ";
			$whereSol .= " AND date(a.fecha_solicitud) >= ".$desdef." ";
		} 
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(a.fecha_cita) <= ".$hastaf." ";
			$whereSol .= " AND date(a.fecha_solicitud) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND e.provincia IN ('".$idprovincias."')";
				$whereSol .= " AND e.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND e.distrito IN ('".$iddistritos."')";
				$whereSol .= " AND e.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND e.corregimiento IN ('".$idcorregimientos."')";
				$whereSol .= " AND e.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
				$querySol .= " AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND a.condicionsalud IN ($idcondicionsalud)";
				$whereSol .= " AND a.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND a.iddiscapacidad IN ($iddiscapacidades)";
				$whereSol .= " AND a.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND c.sexo IN ($idgeneros)";
				$whereSol .= " AND c.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND a.estatus IN ($idestados)";
				$whereSol .= " AND a.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
		$whereSol = str_replace($vowels, "", $whereSol);
	}
	$query  .= " $where2";
	$querySol  .= " $whereSol";
	//Fin Aplicar Filtros
	$query .= " GROUP BY b.nombre ORDER BY b.nombre";
	$querySol .= " GROUP BY b.nombre ORDER BY b.nombre ";
	 
	$result = $mysqli->query($query);	
	$nbrows = $result->num_rows;
	
	if($nbrows > 0){
		
		$cantidadsolicitudes = array();
		$resultSol = $mysqli->query($querySol);	 
		while($rowSol = $resultSol->fetch_assoc()){
			$nameSol[] = $rowSol['name'];
			$cantidadsolicitudes[] = (int)$rowSol['solicitudes'];
		} 
		
		$cantidadcertificados = array();
		$cantidadnocertificados = array();
		//$cantidadsolicitudes = array();
		$categorias = array();
		
		$nameCertificados = 'Certificados';
		$nameNoCertificados  = 'No certificados';
		$nameSolicitudes  = 'Solicitudes';
		
		while($row = $result->fetch_assoc()){
			$nc = html_entity_decode(ucfirst(strtolower(htmlentities($row['name']))));
			$categorias[] = $nc;
			$cantidadcertificados[] = (int)$row['certificados'];
			//$cantidadsolicitudes[] = (int)$row['solicitudes'];
			$cantidadnocertificados[] = (int)$row['nocertificados'];
		}
		if(in_array('Física', $categorias))
        {
		}else{ 
			$categorias = arrayInsert($categorias, 'Física', 0);
			$cantidadcertificados = arrayInsert($cantidadcertificados, 0, 0);
			$cantidadnocertificados = arrayInsert($cantidadnocertificados, 0, 0);
		} 
		if(in_array('Visual', $categorias))
        {
		}else{ 
			$categorias = arrayInsert($categorias, 'Visual', 1);
			$cantidadcertificados = arrayInsert($cantidadcertificados, 0, 1);
			$cantidadnocertificados = arrayInsert($cantidadnocertificados, 0, 1);
		}
		if(in_array('Auditiva', $categorias))
        {
		}else{ 
			$categorias = arrayInsert($categorias, 'Auditiva', 2);
			$cantidadcertificados = arrayInsert($cantidadcertificados, 0, 2);
			$cantidadnocertificados = arrayInsert($cantidadnocertificados, 0, 2);
		}
		if(in_array('Mental', $categorias))
        {
		}else{ 
			$categorias = arrayInsert($categorias, 'Mental', 3);
			$cantidadcertificados = arrayInsert($cantidadcertificados, 0, 3);
			$cantidadnocertificados = arrayInsert($cantidadnocertificados, 0, 3);
		}
		if(in_array('Intelectual', $categorias))
        {
		}else{ 
			$categorias = arrayInsert($categorias, 'Intelectual', 4);
			$cantidadcertificados = arrayInsert($cantidadcertificados, 0, 4);
			$cantidadnocertificados = arrayInsert($cantidadnocertificados, 0, 4);
		}
		if(in_array('Visceral', $categorias))
        {
		}else{ 
			$categorias = arrayInsert($categorias, 'Visceral', 5);
			$cantidadcertificados = arrayInsert($cantidadcertificados, 0, 5);
			$cantidadnocertificados = arrayInsert($cantidadnocertificados, 0, 5);
		}
		 
		$valores[] 	= array(
			'color' => '#267cbc',
			'name' 	=> $nameSolicitudes,
			'data' 	=> $cantidadsolicitudes
		);
		$valores[] 	= array(
			'color' => '#37cc42',
			'name' 	=> $nameCertificados,
			'data' 	=> $cantidadcertificados
		);
		$valores[] 	= array(
			'color' => '#8E8E8E',
			'name' 	=> $nameNoCertificados,
			'data' 	=> $cantidadnocertificados
		); 
		echo json_encode(array( 'categorias' => json_encode($categorias), 'valores' => json_encode($valores)  )	);
	} else { 
		$valores[] 	= array(
				'name' 	=> "Sin resultados",
				'data' 	=> [0]
			);
		echo json_encode(array( 'categorias' => json_encode("Sin resultados"), 'valores' => json_encode($valores)  )	);
	}
}

function mapa(){
	global $mysqli;	
	$usuario 		= $_SESSION['usuario_sen'];
	 
	$query = "	SELECT COALESCE(e.provincia,'Sin Especificar') AS provincia, COUNT(*) AS numero
				FROM pacientes p 
				INNER JOIN solicitudes s ON s.idpaciente = p.id 
				LEFT JOIN direccion d ON d.id = p.direccion 
				LEFT JOIN direcciones e ON e.id = d.iddireccion 
				WHERE s.estatus = 3 
			";
	
	//Aplicar Filtros
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(s.fecha_cita) >= ".$desdef." ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(s.fecha_cita) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND e.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND e.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND e.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND s.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND p.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND s.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//Fin Aplicar Filtros
	
	$query .= " GROUP by e.provincia ORDER by e.provincia ";
	
	$result = $mysqli->query($query);	
	$nbrows = $result->num_rows;
	
	if($nbrows > 0){  
		
		$data = array(); 
		
		while($row = $result->fetch_assoc()){
			
			$provincia = $row['provincia']; 
			$numero	   = (int)$row['numero']; 
			
			if($provincia == 'Bocas del Toro'){ 
				$databc[] = 'pa-bc';
				$databc[] = $numero;
			}
			if($provincia == 'Chiriquí'){ 
				$datach[] = 'pa-ch';
				$datach[] = $numero;
			}
			if($provincia == 'Coclé'){ 
				$datacc[] = 'pa-cc';
				$datacc[] = $numero;
			}
			if($provincia == 'Colón'){ 
				$datacl[] = 'pa-cl';
				$datacl[] = $numero;
			}
			if($provincia == 'Comarca Emberá'){ 
				$dataem[] = 'pa-em';
				$dataem[] = $numero;
			}
			if($provincia == 'Comarca Kuna Yala'){ 
				$dataky[] = 'pa-1119';
				$dataky[] = $numero;
			}
			if($provincia == 'Comarca Nobe-Buglé'){ 
				$datanb[] = 'pa-nb';
				$datanb[] = $numero;
			}
			if($provincia == 'Darién'){ 
				$datadr[] = 'pa-dr';
				$datadr[] = $numero;
			}
			if($provincia == 'Herrera'){ 
				$datahe[] = 'pa-he';
				$datahe[] = $numero;
			}
			if($provincia == 'Los Santos'){ 
				$datals[] = 'pa-ls';
				$datals[] = $numero;
			}
			if($provincia == 'Panamá'){ 
				$datasb[] = 'pa-sb';
				$datasb[] = $numero;
			} 
			if($provincia == 'Veraguas'){ 
				$datavr[] = 'pa-vr';
				$datavr[] = $numero;
			} 
			 
		}  
		if(empty($databc)){
			$databc[] = 'pa-bc';
			$databc[] = 0;
		}
		if(empty($datach)){
			$datach[] = 'pa-ch';
			$datach[] = 0;
		}
		if(empty($datacc)){
			$datacc[] = 'pa-cc';
			$datacc[] = 0;
		}
		if(empty($datacl)){
			$datacl[] = 'pa-cl';
			$datacl[] = 0;
		}
		if(empty($dataem)){
			$dataem[] = 'pa-em';
			$dataem[] = 0;
		}
		if(empty($dataky)){
			$dataky[] = 'pa-1119';
			$dataky[] = 0;
		}
		if(empty($datanb)){
			$datanb[] = 'pa-nb';
			$datanb[] = 0;
		}
		if(empty($datadr)){
			$datadr[] = 'pa-dr';
			$datadr[] = 0;
		}
		if(empty($datahe)){
			$datahe[] = 'pa-he';
			$datahe[] = 0;
		}
		if(empty($datals)){
			$datals[] = 'pa-ls';
			$datals[] = 0;
		}
		if(empty($datasb)){
			$datasb[] = 'pa-sb';
			$datasb[] = 0;
		}
		if(empty($datavr)){
			$datavr[] = 'pa-vr';
			$datavr[] = 0;
		}
		$data[] = $databc;
		$data[] = $datach;
		$data[] = $datacc;
		$data[] = $datacl;
		$data[] = $dataem;
		$data[] = $dataky;
		$data[] = $datanb;
		$data[] = $datadr;
		$data[] = $datahe;
		$data[] = $datals;
		$data[] = $datals;
		$data[] = $datasb;
		$data[] = $datavr;
		
		echo json_encode($data);
	} else { 
		$data = array();
		$databc = array();
		
		$databc[] = 'pa-bc';
		$databc[] = 0;
		$datach[] = 'pa-ch';
		$datach[] = 0;
		$datacc[] = 'pa-cc';
		$datacc[] = 0;
		$datacl[] = 'pa-cl';
		$datacl[] = 0;
		$dataem[] = 'pa-em';
		$dataem[] = 0;
		$dataky[] = 'pa-1119';
		$dataky[] = 0;
		$datanb[] = 'pa-nb';
		$datanb[] = 0;
		$datadr[] = 'pa-dr';
		$datadr[] = 0;
		$datahe[] = 'pa-he';
		$datahe[] = 0;
		$datals[] = 'pa-ls';
		$datals[] = 0;
		$datasb[] = 'pa-sb';
		$datasb[] = 0;
		$datavr[] = 'pa-vr';
		$datavr[] = 0;
		
		$data[] = $databc;
		$data[] = $datach;
		$data[] = $datacc;
		$data[] = $datacl;
		$data[] = $dataem;
		$data[] = $dataky;
		$data[] = $datanb;
		$data[] = $datadr;
		$data[] = $datahe;
		$data[] = $datals;
		$data[] = $datals;
		$data[] = $datasb;
		$data[] = $datavr;
		echo json_encode($data);
	}
}

function solicitudesMes() {
	global $mysqli;
	$usuario 		= $_SESSION['usuario_sen'];
	
	$query = "	SELECT  
				SUM( CASE WHEN estatus = '3' then 1 else 0 end ) as cantC,
				SUM( CASE WHEN estatus = '4' then 1 else 0 end ) as cantNC,
				SUM( CASE WHEN estatus = '2' then 1 else 0 end ) as cantA,
				SUM( CASE WHEN estatus = '1' then 1 else 0 end ) as cantNA,
				MONTH(s.fecha_cita) AS mes
				FROM pacientes p 
				INNER JOIN solicitudes s ON s.idpaciente = p.id 
				LEFT JOIN direccion d ON d.id = p.direccion 
				LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
				WHERE YEAR(s.fecha_cita) = YEAR(CURDATE()) "; 
				
	$querySol = "	SELECT 
					COUNT(*) AS cantM
					FROM pacientes p 
					INNER JOIN solicitudes s ON s.idpaciente = p.id 
					LEFT JOIN direccion d ON d.id = p.direccion 
					LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
					WHERE YEAR(s.fecha_solicitud) = YEAR(CURDATE()) ";
					
	   
	//Aplicar Filtros
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(s.fecha_cita) >= ".$desdef." ";
			$whereSol .= " AND date(s.fecha_solicitud) >= ".$desdef." ";
		}  
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(s.fecha_cita) <= ".$hastaf." ";
			$whereSol .= " AND date(s.fecha_solicitud) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND dir.provincia IN ('".$idprovincias."')";
				$whereSol .= " AND dir.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND dir.distrito IN ('".$iddistritos."')";
				$whereSol .= " AND dir.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND dir.corregimiento IN ('".$idcorregimientos."')";
				$whereSol .= " AND dir.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
				$querySol .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND s.condicionsalud IN ($idcondicionsalud)";
				$whereSol .= " AND s.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
				$whereSol .= " AND s.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND p.sexo IN ($idgeneros)";
				$whereSol .= " AND p.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND s.estatus IN ($idestados)";
				$whereSol .= " AND s.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
		$whereSol = str_replace($vowels, "", $whereSol);
	}
	$query  .= " $where2";
	$querySol  .= " $whereSol";
	//Fin Aplicar Filtros
	
	$query .= " GROUP BY MONTH(s.fecha_cita) ORDER BY MONTH(s.fecha_cita) ASC ";	
	$querySol .= " GROUP BY MONTH(s.fecha_solicitud) ORDER BY MONTH(s.fecha_solicitud) ASC ";	
	
	
	$result = $mysqli->query($query);	
	$nbrows = $result->num_rows;
	$meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	if($nbrows > 0){
		$solicitudes = array();
		$resultSol = $mysqli->query($querySol);	 
		while($rowSol = $resultSol->fetch_assoc()){
			$solicitudes[] = (int)$rowSol['cantM'];
		} 
	
		$cantidadM = array();
		$cantidadC = array();
		$cantidadNC = array();
		$cantidadA = array();
		$cantidadNA = array();
		$categorias = array();
		
		while($row = $result->fetch_assoc()){
			$mes = (int)$row['mes'];
			$categorias[] = $meses[$mes];	
			//$cantidadM[] = (int)$row['cantM'];
			$cantidadC[] = (int)$row['cantC'];
			$cantidadNC[] = (int)$row['cantNC'];
			$cantidadA[] = (int)$row['cantA'];
			$cantidadNA[] = (int)$row['cantNA'];
		}
		$valores[] 	= array(
			'color' => '#267cbc',
			'name' 	=> 'Solicitudes',
			'data' 	=> $solicitudes
		);
		$valores[] 	= array(
			'color' => '#37cc42',
			'name' 	=> 'Certificados',
			'data' 	=> $cantidadC
		);
		$valores[] 	= array(
			'color' => '#ec202a',
			'name' 	=> 'No certificados',
			'data' 	=> $cantidadNC
		);
		$valores[] 	= array(
			'color' => '#000000',
			'name' 	=> 'Agendados',
			'data' 	=> $cantidadA
		);
		$valores[] 	= array(
			'color' => '#8e8e8e',
			'name' 	=> 'No agendados',
			'data' 	=> $cantidadNA
		);
		
		echo json_encode(array( 'categorias' => json_encode($categorias), 'valores' => json_encode($valores)  )	);
	} else { 
		$valores[] 	= array(
				'name' 	=> "Sin resultados",
				'data' 	=> [0]
			);
		echo json_encode(array( 'categorias' => json_encode("Sin resultados"), 'valores' => json_encode($valores)  )	);
	}
}

function totales(){
	global $mysqli;
	$usuario 		= $_SESSION['usuario_sen'];
	 
	$query = "	SELECT 
				SUM(case when a.estatus = 1 then 1 else 0 end) as noagendados, 
				SUM(case when a.estatus = 2 then 1 else 0 end) as agendados, 
				SUM(case when (a.estatus = 3 OR a.estatus = 4 OR a.estatus = 16) then 1 else 0 end) as evaluados, 
				SUM(case when a.estatus = 3 then 1 else 0 end) as certificados, 
				SUM(case when a.estatus = 4 then 1 else 0 end) as nocertificados,
				SUM(case when a.estatus = 6 then 1 else 0 end) as noasistio,
				SUM(case when a.estatus = 16 then 1 else 0 end) as pendientes
				FROM solicitudes a 
				INNER JOIN pacientes c ON c.id = a.idpaciente 
				LEFT JOIN direccion d ON d.id = c.direccion 
				LEFT JOIN direcciones e ON e.id = d.iddireccion 
				WHERE 1 ";
	//Aplicar Filtros
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$whereSol = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(a.fecha_cita) >= ".$desdef." ";
			$whereSol .= " AND date(a.fecha_solicitud) >= ".$desdef." ";
		}  
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(a.fecha_cita) <= ".$hastaf." ";
			$whereSol .= " AND date(a.fecha_solicitud) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND e.provincia IN ('".$idprovincias."')";
				$whereSol .= " AND e.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND e.distrito IN ('".$iddistritos."')";
				$whereSol .= " AND e.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND e.corregimiento IN ('".$idcorregimientos."')";
				$whereSol .= " AND e.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
				AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
				$querySol .= " AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde."
							AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";				
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND a.condicionsalud IN ($idcondicionsalud)";
				$whereSol .= " AND a.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND a.iddiscapacidad IN ($iddiscapacidades)";
				$whereSol .= " AND a.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND c.sexo IN ($idgeneros)";
				$whereSol .= " AND c.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND a.estatus IN ($idestados)";
				$whereSol .= " AND a.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//debugL("TOTALES:".$query,"DEBUGLTOTALES");
	$whereSol .= " $querySol ";
	//Fin Aplicar Filtros 
	 
	$queryCantSol = getRegistroSQL("SELECT COUNT(*) AS cantSol 
									FROM solicitudes a 
									INNER JOIN pacientes c ON c.id = a.idpaciente 
									LEFT JOIN direccion d ON d.id = c.direccion 
									LEFT JOIN direcciones e ON e.id = d.iddireccion 
									WHERE 1 = 1 ".$whereSol." ");
	$solicitudes = $queryCantSol['cantSol'];
	
	$result = $mysqli->query($query);  
	if($row = $result->fetch_assoc()){
		
		$row['certificados'] == null ? $certificados = 0 : $certificados = $row['certificados'];
		$row['nocertificados'] == null ? $nocertificados = 0 : $nocertificados = $row['nocertificados'];
		
		$resultado = array(
			'solicitudes'	 => $solicitudes,
			'citados'	 	 => $certificados + $nocertificados + $row['pendientes'] + $row['noasistio'],
			'evaluados' 	 => $row['evaluados'],
			'noagendados' 	 => $row['noagendados'],
			'agendados' 	 => $row['agendados'],
			'certificados'	 => $certificados,
			'nocertificados' => $nocertificados,
			'noasistio'		 => $row['noasistio'],
			'pendientes'	 => $row['pendientes'],
		); 
	}
	echo json_encode($resultado);
}

function promedioSA(){
	global $mysqli;
	$usuario 		= $_SESSION['usuario_sen'];
	 
	$query = "	SELECT ROUND(SUM(TIMESTAMPDIFF(DAY, DATE(a.fecha_solicitud), a.fecha_cita)) / COUNT(*) ) AS promedio, COUNT(*) AS cantidad 
				FROM solicitudes a 				
				INNER JOIN pacientes c ON c.id = a.idpaciente 
				LEFT JOIN direccion d ON d.id = c.direccion 
				LEFT JOIN direcciones e ON e.id = d.iddireccion
				WHERE a.fecha_cita IS NOT NULL AND YEAR(a.fecha_solicitud) >= '2020'";
	
	//Aplicar Filtros
	$queryF	 = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(a.fecha_cita) >= ".$desdef." ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(a.fecha_cita) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND e.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND e.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND e.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == '"primerainfacia"'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND a.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND a.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND c.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND a.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//Fin Aplicar Filtros 
	
	$result = $mysqli->query($query);  
	if($row = $result->fetch_assoc()){
		
		$row['promedio'] == null ? $promedio = 0 : $promedio = $row['promedio'];
		
		$resultado = array(
			'cantSA'	 => $row['cantidad'],
			'promSA' 	 => $promedio
		); 
	}
	echo json_encode($resultado);
}

function promedioSR(){
	global $mysqli;
	$usuario 		= $_SESSION['usuario_sen'];
	 
	$query = "	SELECT ROUND(SUM(TIMESTAMPDIFF(DAY, DATE(a.fecha_solicitud), STR_TO_DATE(b.fechaemision, '%d-%m-%Y'))) / COUNT(*) ) AS promedio,
				COUNT(*) AS cantidad 
				FROM solicitudes a 
				INNER JOIN evaluacion b ON b.idsolicitud = a.id 
				INNER JOIN pacientes c ON c.id = a.idpaciente 
				LEFT JOIN direccion d ON d.id = c.direccion 
				LEFT JOIN direcciones e ON e.id = d.iddireccion 
				WHERE YEAR(a.fecha_solicitud) >= '2020'  
				";
	
	//Aplicar Filtros
	$queryF	 = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";		
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
		$where2 = '';
		$data = json_decode($data);
		if(!empty($data->desdef)){
			$desdef = json_encode($data->desdef);
			$where2 .= " AND date(a.fecha_cita) >= ".$desdef." ";
		} else {
			//$where2 .= " AND a.fechacreacion >= '" . date("Y")."-01-01'";
		}
		if(!empty($data->hastaf)){
			$hastaf = json_encode($data->hastaf);
			$where2 .= " AND date(a.fecha_cita) <= ".$hastaf." ";
		}
		if(!empty($data->idprovincias)){
			$idprovincias = $data->idprovincias;
			if($idprovincias != '[""]'){
				$where2 .= " AND e.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND e.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND e.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			if ($edad!="*" && $edad != "null"){
				if($edad == 'primerainfacia'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == '"adolescencia"'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == '"juventud"'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == '"adultez"'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == '"personamayor"'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
			}
		}
		if(!empty($data->idcondicionsalud)){
			$idcondicionsalud = json_encode($data->idcondicionsalud);
			if($idcondicionsalud != '[""]'){
				$where2 .= " AND a.condicionsalud IN ($idcondicionsalud)";
			}
		}
		if(!empty($data->iddiscapacidades)){
			$iddiscapacidades = json_encode($data->iddiscapacidades);
			if($iddiscapacidades != '[""]'){
				$where2 .= " AND a.iddiscapacidad IN ($iddiscapacidades)";
			}
		}
		if(!empty($data->idgeneros)){
			$idgeneros = json_encode($data->idgeneros);
			if($idgeneros != '[""]'){
				$where2 .= " AND c.sexo IN ($idgeneros)";
			}
		}
		if(!empty($data->idestados)){
			$idestados = json_encode($data->idestados);
			if($idestados != '[""]'){
				$where2 .= " AND a.estatus IN ($idestados)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$query  .= " $where2";
	//Fin Aplicar Filtros
	 
	$result = $mysqli->query($query);  
	if($row = $result->fetch_assoc()){
		
		$row['promedio'] == null ? $promedio = 0 : $promedio = $row['promedio'];
		
		$resultado = array(
			'cantSR'	 => $row['cantidad'],
			'promSR' 	 => $promedio
		); 
	}
	echo json_encode($resultado);
}

function guardarfiltros() {
	global $mysqli;
	$data = $_REQUEST['data'];
	$usuario = $_SESSION['usuario_sen'];
	$query  = " SELECT * FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."' ";

	$result = $mysqli->query($query);
	$count = $result->num_rows;
	
	if( $count > 0 ) 
		$query = "UPDATE usuariosfiltros SET filtrosmasivos = '".$data."' WHERE modulo = 'Dashboard' AND usuario = '".$usuario."' ";
	else
		$query = "INSERT INTO usuariosfiltros VALUES (null, '".$usuario."', 'Dashboard', '', '".$data."')";
	if($mysqli->query($query))
		echo true;		
} 

function abrirfiltros() {
	global $mysqli;
	$usuario = $_SESSION['usuario_sen'];
	
	$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";
	$result = $mysqli->query($query);
	$response = new StdClass;
	if($result->num_rows >0){
		$row = $result->fetch_assoc();				
		$data = $row['filtrosmasivos'];
		$response->data = $data;
	} else {
		$response->data = '';
	}
	
	$response->success = true;
	echo json_encode($response);
}

function verificarfiltros() {
	global $mysqli;
	$usuario = $_SESSION['usuario_sen'];
	
	$query = "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."'";
	$result = $mysqli->query($query);
	$response = 0;
	if($result->num_rows >0){
		$row = $result->fetch_assoc();				
		$data = $row['filtrosmasivos'];
		$filtrosmasivos = json_decode($data);
		foreach($filtrosmasivos as $clave => $valor){
			if($valor != '' || $valor != 0){
				$response = 1;
			}
		}
	} else {
		$response = 0;
	}
	echo $response;
}

function limpiarFiltrosMasivos(){
	global $mysqli;
	$usuario = $_SESSION['usuario_sen'];
	
	$query = "DELETE FROM usuariosfiltros WHERE modulo = 'Dashboard' AND usuario = '".$usuario."' ";
	if($mysqli->query($query))
		echo true;		
}

function arrayInsert($array, $item, $position)
{
	
    $begin = array_slice($array, 0, $position);
    array_Push($begin, $item);
    $end = array_slice($array, $position);
    $resultArray = array_merge($begin, $end); 
    return $resultArray;
}

?>