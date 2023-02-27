<?php
	include("../controller/conexion.php");
	require '../vendor/phpspreadsheet/vendor/autoload.php';

	global $mysqli;
	$usuario = $_SESSION['usuario_sen'];
	
	/** Error reporting */
	//error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	//load phpspreadsheet class using namespaces
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	//call xlsx writer class to make an xlsx file
	use PhpOffice\PhpSpreadsheet\IOFactory;
	//make a new spreadsheet object
	$spreadsheet = new Spreadsheet();
	//obtener la hoja activa actual, (que es la primera hoja)
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('Reporte');
	
	$fontColor = new \PhpOffice\PhpSpreadsheet\Style\Color();
	$fontColor->setRGB('ffffff');
	
	$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
	$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
	);
	$stylebordernone = [
		'borders' => [
			'allBorders' => [
				'borderStyle' => 'thin',
				'color' => ['argb' => 'ffffff'],
			],
		],
	];
    $stylebordertop = [
		'borders' => [
			'top' => [
				'borderStyle' => 'thick',
				'color' => ['argb' => '000000'],
			],
		],
	];
	$spreadsheet->getActiveSheet()->getStyle("A1:K6")->applyFromArray($stylebordernone);
	$spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');
	
	$spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setSize(12)->setBold(true)->getColor()->setRGB('293F76');
	$spreadsheet->getActiveSheet()->getStyle('B3')->getFont()->getColor()->setRGB('424949');
		
	// ENCABEZADO
	$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
	$drawing->setName('Renacer');
	$drawing->setDescription('Renacer');
	$drawing->setPath('../images/renacer-index.png');
	$drawing->setCoordinates('A2');
	$drawing->setOffsetX(15);
	$drawing->setOffsetY(6);
	$drawing->setHeight(45);
	$drawing->setWorksheet($spreadsheet->getActiveSheet());
	
	$spreadsheet->getActiveSheet() 
	//ENCABEZADOS
	->setCellValue('B2', 'Secretaria Nacional de Discapacidad')
	->setCellValue('B3', 'Programa RENACER')
	->setCellValue('B4', 'Estadísticas de Usuarios');
	
	if(($desde!="*" && $desde != "null") || ($hasta!="*" && $hasta != "null") ){
		$spreadsheet->getActiveSheet()->setCellValue('B5', $desde.' / '.$hasta);
	}
	$spreadsheet->getActiveSheet()->mergeCells('B2:D2');
	
	// ENCABEZADO 
/* 	$spreadsheet->getActiveSheet()
	->setCellValue('A7', 'Usuario')
	->setCellValue('B7', 'Cédula')
	->setCellValue('C7', 'Expediente')
	->setCellValue('D7', 'Fecha de cita')
	->setCellValue('E7', 'Fecha de solicitud')
	->setCellValue('F7', 'Estado')
	->setCellValue('G7', 'Código')
	->setCellValue('H7', 'Provincia')		
	->setCellValue('I7', 'Distrito')
	->setCellValue('J7', 'Corregimiento')
	->setCellValue('K7', 'Dirección')	
	->setCellValue('L7', 'Teléfono')
	->setCellValue('M7', 'Sexo')
	->setCellValue('N7', 'Edad')
	->setCellValue('O7', 'Tipo de Discapacidad')
	->setCellValue('P7', 'Condición de Salud')
	->setCellValue('Q7', 'Cobertura Médica')
	->setCellValue('R7', 'Condición de Actividad')
	->setCellValue('S7', 'Beneficios')
	->setCellValue('T7', 'Ayuda Técnica')
	->setCellValue('U7', 'Nivel Educativo')
	->setCellValue('V7', 'Aspecto Habitacional')
	->setCellValue('W7', 'Tipo de vivienda')
	->setCellValue('X7', 'Etnia')
	->setCellValue('Y7', 'Religión')
	->setCellValue('Z7', 'Rango de ingreso Mensual')
	->setCellValue('AA7', 'Ingresos')
	->setCellValue('AB7', 'Estado de las calles')
	->setCellValue('AC7', 'Medio de transporte')
	->setCellValue('AD7', 'Funciones corporales')
	->setCellValue('AE7', 'Estructuras Corporales')
	->setCellValue('AF7', 'Actividad y participación')
	->setCellValue('AG7', 'Factores ambientales')
	->setCellValue('AH7', 'Observaciones de la solicitud')
	->setCellValue('AI7', 'Solicitud')
	->setCellValue('AJ7', 'Lugar de la Solicitud')
	->setCellValue('AK7', 'CIE 10')
	->setCellValue('AL7', 'Condición de salud');
	 */
	$spreadsheet->getActiveSheet()->getStyle('A7:AL7')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A7:AL7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
	//SENTENCIA BASE
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
				$where2 .= " AND d.provincia IN ('".$idprovincias."')";
			}
		}
		if(!empty($data->iddistritos)){
			$iddistritos = $data->iddistritos;
			if($iddistritos != '[""]'){
				$where2 .= " AND d.distrito IN ('".$iddistritos."')";
			}
		}			
		if(!empty($data->idcorregimientos)){
			$idcorregimientos = $data->idcorregimientos;
			if($idcorregimientos != '[""]'){
				$where2 .= " AND d.corregimiento IN ('".$idcorregimientos."')";
			}
		}
		if(!empty($data->idedades)){
			$edad = json_encode($data->idedades);
			debugL("IDEDADES".$edad);
			if ($edad!="*" && $edad != "null"){
				if($edad == 'primerainfacia'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == '"infancia"'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad == 'adolescencia'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad == 'juventud'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad == 'adultez'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad == 'personamayor'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(a.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(a.fecha_nac,'%m-%d'), 0 , -1 ) > ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(a.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(a.fecha_nac,'%m-%d'), 0 , -1 ) < ".$edadHasta."";
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
				$where2 .= " AND a.sexo IN ($idgeneros)";
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
	
	$query .= " GROUP BY s.id "; 
	debugL('EXPORTAR:'.$query);
	$result = $mysqli->query($query);
	$i = 8;
	while($row = $result->fetch_assoc()){		
		//Condición actividad
		if($row['condicion_actividad'] == 0){
			$condicionactividad = 'Sin Especificar';
		}
		if($row['condicion_actividad'] == 1){
			$condicionactividad = 'Trabaja';
		}
		if($row['condicion_actividad'] == 2){
			$condicionactividad = 'No trabaja';
		}
		if($row['condicion_actividad'] == 3){
			$condicionactividad = 'Busca trabajo';
		}
		if($row['condicion_actividad'] == 4){
			$condicionactividad = 'No busca trabajo';
		}
		if($row['condicion_actividad'] == 5){
			$condicionactividad = 'No aplicable';
		}
		
		//Nivel educacional
		if($row['niveleducacional'] == 0){
			$niveleducacional = 'Sin Especificar';
		}
		if($row['niveleducacional'] == 1){
			$niveleducacional = 'Inicial';
		}
		if($row['niveleducacional'] == 2){
			$niveleducacional = 'Primario';
		}
		if($row['niveleducacional'] == 3){
			$niveleducacional = 'Secundario';
		}
		if($row['niveleducacional'] == 4){
			$niveleducacional = 'Terciario / Universitario';
		}
		
		//Estado de calles
		if($row['estadocalles'] == 1){
			$estadocalles = 'Asfaltado o pavimento';
		}
		if($row['estadocalles'] == 2){
			$estadocalles = 'Mejorado';
		}
		if($row['estadocalles'] == 3){
			$estadocalles = 'Tierra';
		}
		
		//Medios de transporte
		if($row['mediotransporte'] == 1){
			$mediotransporte = 'Menos de 300 metros';
		}
		if($row['mediotransporte'] == 2){
			$mediotransporte = 'Más de 200 metros';
		}
		
		//Tipo vivienda
		if($row['tipovivienda'] == 1){
			$tipovivienda = 'Vivienda con infaestructura básica (servicios)';
		}
		if($row['tipovivienda'] == 2){
			$tipovivienda = 'Vivienda sin infaestructura básica (servicios)';
		}	

		//Cobertura médica
		if($row['cobertura_medica'] == 1){
			$coberturamedica = 'Sin Especificar';
		}
		if($row['cobertura_medica'] == 2){
			$coberturamedica = 'Seguro social';
		}
		if($row['cobertura_medica'] == 3){
			$coberturamedica = 'Seguro privado';
		}
		if($row['cobertura_medica'] == 4){
			$coberturamedica = 'Ninguno';
		}
		
		//Aspecto habitacional
		if($row['convivencia'] == 0){
			$aspectohabitacional = 'Sin Especificar';
		}
		if($row['convivencia'] == 1){
			$aspectohabitacional = 'Vive solo';
		}
		if($row['convivencia'] == 2){
			$aspectohabitacional = 'Vive acompañado';
		}
		if($row['convivencia'] == 3){
			$aspectohabitacional = 'Internado / Albergue';
		}
		
		//Sexo
		if($row['sexo'] == 'F'){
			$sexo = 'Femenino';
		}
		if($row['sexo'] == 'M'){
			$sexo = 'Masculino';
		}
		
		//Estado
		/*
		if($row['estatus'] == '3'){
			$estatus = 'Certificado';
		}else{
			$estatus = 'Sin certificar';
		}
		*/
		
		//Dirección
		$direccion = '';
		
		if($row['urbanizacion'] != ''){
			$direccion .= "Urbanización: ".trim($row['urbanizacion'])." ";
		}elseif($row['calle'] != ''){
			$direccion .= "Calle: ".trim($row['calle'])." ";
		}elseif($row['edificio'] != ''){
			$direccion .= "Edificio: ".trim($row['edificio'])." ";
		}elseif($row['numero'] != ''){
			$direccion .= "Número: ".trim($row['numero']);
		}
		
		//CODIGOS
		$funcionesc = '';
		$estructurasc = '';		
		$actividadp = '';
		$factoresa = '';
		if($row['cif'] != ''){
			$cif = json_decode($row['cif']);
			foreach($cif as $clave => $valor) {
				if($clave == 'b'){
					$bi = 0;
					foreach($valor as $claveb => $valorb) {
						if($bi == 0){
							$funcionesc .= trim($valorb->codigocif);
						}else{
							$funcionesc .= '  '.trim($valorb->codigocif);
						}
						if($valorb->c1 != ''){
							$funcionesc .= '.'.trim($valorb->c1);
						}
						if($valorb->c2 != ''){
							$funcionesc .= '.'.trim($valorb->c2);
						}
						if($valorb->c3 != ''){
							$funcionesc .= '.'.trim($valorb->c3);
						}
						$bi++;
					}
				}
				if($clave == 'd'){
					$di = 0;
					foreach($valor as $claved => $valord) {
						if($di == 0){
							$actividadp .= $valord->codigocif;
						}else{
							$actividadp .= '  '.$valord->codigocif;
						}
						if($valord->c1 != ''){
							$actividadp .= '.'.$valord->c1;
						}
						if($valord->c2 != ''){
							$actividadp .= ''.$valord->c2;
						}
						if($valord->c3 != ''){
							$actividadp .= ''.$valord->c3;
						}
						$di++;
					}
				}
				if($clave == 's'){
					$si = 0;
					foreach($valor as $claves => $valors) {
						if($si == 0){
							$estructurasc .= $valors->codigocif;
						}else{
							$estructurasc .= '  '.$valors->codigocif;
						}
						if($valors->c1 != ''){
							$estructurasc .= '.'.$valors->c1;
						}
						if($valors->c2 != ''){
							$estructurasc .= ''.$valors->c2;
						}
						if($valors->c3 != ''){
							$estructurasc .= ''.$valors->c3;
						}
						$si++;
					}
				}
				if($clave == 'e'){
					$ei = 0;
					foreach($valor as $clavee => $valore) {
						if($ei == 0){
							$factoresa .= $valore->codigocif;
						}else{
							$factoresa .= '  '.$valore->codigocif;
						}
						if($valore->c1 != ''){
							$factoresa .= ''.$valore->c1;
						}
						if($valore->c2 != ''){
							$factoresa .= ''.$valore->c2;
						}
						if($valore->c3 != ''){
							$factoresa .= ''.$valore->c3;
						}
						$ei++;
					}
				}
			}
		}
		
		if($row['telefono'] != '' && $row['celular'] != ''){
			$telefono = $row['telefono'].', '.$row['celular'];
		}elseif($row['telefono'] != ''){
			$telefono = $row['telefono'];
		}elseif($row['celular'] != ''){
			$telefono = $row['celular'];
		}else{
			$telefono = '';
		}
		
		$arr = array(); 
		$arr [] = $row['nombre'];
		$arr [] = $row['cedula'];
		$arr [] = $row['expediente'];
		$arr [] = $row['fecha_cita'];
		$arr [] = $row['fecha_solicitud'];
		$arr [] = $row['estado'];
		$arr [] = $row['codigo'];
		$arr [] = $row['provincia'];
		$arr [] = $row['distrito'];
		$arr [] = $row['corregimiento'];
		$arr [] = $direccion;
		$arr [] = $telefono;
		$arr [] = $sexo;
		$arr [] = $row['edad'];
		$arr [] = $row['tipodiscapacidad'];
		$arr [] = ucfirst($row['condicionsalud']); 
		$arr [] = $coberturamedica;
		$arr [] = $condicionactividad;
		$arr [] = $row['beneficios'];
		$arr [] = $row['ayudatecnica']; 
		$arr [] = $niveleducacional;
		$arr [] = $aspectohabitacional;
		$arr [] = $tipovivienda;
		$arr [] = $row['etnia'];
		$arr [] = $row['religion']; 
		$arr [] = $row['ingresomensual']; 
		$arr [] = $row['ingresomensualotro'];
		$arr [] = $estadocalles; 
		$arr [] = $mediotransporte;
		$arr [] = trim($funcionesc);
		$arr [] = trim($estructurasc); 
		$arr [] = trim($actividadp);
		$arr [] = trim($factoresa);
		$arr [] = $row['observacionessol'];
		$arr [] = $row['solicitud'];
		$arr [] = $row['lugarsolicitud'];
		$arr [] = $row['cie'];
		$arr [] = $row['desccie'];
		$arrayData [] = $arr;
		/* $spreadsheet->getActiveSheet()
		->setCellValue('A'.$i, $row['nombre'])
		->setCellValue('B'.$i, $row['cedula'])
		->setCellValue('C'.$i, $row['expediente'])
		->setCellValue('D'.$i, $row['fecha_cita'])
		->setCellValue('E'.$i, $row['fecha_solicitud'])
		->setCellValue('F'.$i, $row['estado'])
		->setCellValue('G'.$i, $row['codigo'])
		->setCellValue('H'.$i, $row['provincia'])
		->setCellValue('I'.$i, $row['distrito'])
		->setCellValue('J'.$i, $row['corregimiento'])
		->setCellValue('K'.$i, $direccion)		
		->setCellValue('L'.$i, $telefono)
		->setCellValue('M'.$i, $sexo)
		->setCellValue('N'.$i, $row['edad'])
		->setCellValue('O'.$i, $row['tipodiscapacidad'])
		->setCellValue('P'.$i, ucfirst($row['condicionsalud']))
		->setCellValue('Q'.$i, $coberturamedica)
		->setCellValue('R'.$i, $condicionactividad)
		->setCellValue('S'.$i, ucfirst($row['beneficios']))
		->setCellValue('T'.$i, $row['ayudatecnica'])
		->setCellValue('U'.$i, $niveleducacional)
		->setCellValue('V'.$i, $aspectohabitacional)
		->setCellValue('W'.$i, $tipovivienda)
		->setCellValue('X'.$i, $row['etnia'])
		->setCellValue('Y'.$i, $row['religion'])
		->setCellValue('Z'.$i, $row['ingresomensual'])
		->setCellValue('AA'.$i, $row['ingresomensualotro'])
		->setCellValue('AB'.$i, $estadocalles)
		->setCellValue('AC'.$i, $mediotransporte)
		->setCellValue('AD'.$i, trim($funcionesc))
		->setCellValue('AE'.$i, trim($estructurasc))
		->setCellValue('AF'.$i, trim($actividadp))
		->setCellValue('AG'.$i, trim($factoresa))
		->setCellValue('AH'.$i, $row['observacionessol'])
		->setCellValue('AI'.$i, $row['solicitud'])
		->setCellValue('AJ'.$i, $row['lugarsolicitud'])
		->setCellValue('AK'.$i, $row['cie'])
		->setCellValue('AL'.$i, $row['desccie']); */		

		//ESTILOS
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getFont()->setSize(10);
		$spreadsheet->getActiveSheet()->getStyle('H'.$i)->getAlignment()->applyFromArray(
					array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AC'.$i)->getAlignment()->applyFromArray(
					array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT));
		$spreadsheet->getActiveSheet()->getStyle('Z'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');  
		$spreadsheet->getActiveSheet()->getStyle('AF'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('AB'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('AB'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('AD'.$i)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
		$spreadsheet->getActiveSheet()->getStyle('AD'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$spreadsheet->getActiveSheet()->getStyle('AH'.$i)->getAlignment()->applyFromArray(
					array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
		$i++;
	}
	
	$titles = array( 
		'Usuario',
		'Cédula',
		'Expediente',
		'Fecha de cita',
		'Fecha de solicitud',
		'Estado',
		'Código',
		'Provincia',
		'Distrito',
		'Corregimiento',
		'Dirección',
		'Teléfono',
		'Sexo',
		'Edad',
		'Tipo de Discapacidad',
		'Condición de Salud',
		'Cobertura Médica',
		'Condición de Actividad',
		'Beneficios',
		'Ayuda Técnica',
		'Nivel Educativo',
		'Aspecto Habitacional',
		'Tipo de vivienda',
		'Etnia',
		'Religión',
		'Rango de ingreso Mensual', 
		'Ingresos',
		'Estado de las calles',
		'Medio de transporte',
		'Funciones corporales',
		'Estructuras Corporales',
		'Actividad y participación',
		'Factores ambientales',
		'Observaciones de la solicitud',
		'Solicitud',
		'Lugar de la Solicitud',
		'CIE 10',
		'Condición de salud' 
	); 
	 
	$spreadsheet->getActiveSheet()->getStyle('A7:AL7')->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()
		->fromArray(
			$titles,  // The data to set
			NULL,        // Array values with this value will not be set
			'A7'         // Top left coordinate of the worksheet range where
						 //    we want to set these values (default is A1)
		);
 
	$spreadsheet->getActiveSheet()
		->fromArray(
			$arrayData,  // The data to set
			NULL,        // Array values with this value will not be set
			'A9'         // Top left coordinate of the worksheet range where
						 //    we want to set these values (default is A1)
		);
	//Ancho automatico	
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(20);
	$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(40);
	$spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('AL')->setWidth(40);
	
	$hoy = date('dmY');
	$nombreArc = 'Reporte - Estadisticas de usuarios '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
   
?>