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
	$spreadsheet->getActiveSheet()->getStyle("A1:E6")->applyFromArray($stylebordernone);
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
	->setCellValue('E3', 'Programa RENACER')
	->setCellValue('E4', 'Reporte de resoluciones');
	
		
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
		
		/******Filtro por fechas según radio button******/
		$optradio = (isset($data->optradio) ? $data->optradio : '');
		if($optradio == 'radio_sol'){
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND DATE(s.fecha_solicitud) >= ".$desdef." ";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND DATE(s.fecha_solicitud) <= ".$hastaf." ";
			}
		}else{
			if(!empty($data->desdef)){
				$desdef = json_encode($data->desdef);
				$where2 .= " AND DATE(s.fecha_cita) >= ".$desdef." ";
			}
			if(!empty($data->hastaf)){
				$hastaf = json_encode($data->hastaf);
				$where2 .= " AND DATE(s.fecha_cita) <= ".$hastaf." "; 
			}
		}
		/******Filtro por fechas según radio button******/  
		 
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
				$query .= " AND YEAR(CURDATE())-YEAR(a.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(a.fecha_nac,'%m-%d'), 0 , -1 ) >= ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(a.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(a.fecha_nac,'%m-%d'), 0 , -1 ) <= ".$edadHasta."";
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
	
/* 	if(($desde!="*" && $desde != "null") || ($hasta!="*" && $hasta != "null") ){
		$spreadsheet->getActiveSheet()->setCellValue('B5', $desde.' / '.$hasta);
	}
	$spreadsheet->getActiveSheet()->mergeCells('B2:D2'); */
	
	
	$desde = str_replace('"','',$desdef);
	$hasta = str_replace('"','',$hastaf);
	if($desde == "" && $desde == ""){
		$spreadsheet->getActiveSheet()->setCellValue('E5', 'Fecha: '.date('Y-m-d') );
	}else{
		if(($desde!="*" && $desde != "null") || ($hasta!="*" && $hasta != "null") ){
			//$spreadsheet->getActiveSheet()->setCellValue('B5', 'Desde:'.$desde.' Hasta:'.$hasta);
			$spreadsheet->getActiveSheet()->setCellValue('D5', 'Desde:'.$desde);
			$spreadsheet->getActiveSheet()->setCellValue('E5', ' Hasta:'.$hasta); 
		}
	}
	 
	$spreadsheet->getActiveSheet()->mergeCells('B2:E2');	
	$spreadsheet->getActiveSheet()->getStyle('A7:E7')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A7:E7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
	//SENTENCIA BASE
	$query  = " SELECT r.id, r.idsolicitud, a.expediente, r.nro_resolucion, 'Negatoria' AS tipo, s.fecha_solicitud 
				FROM negatorias r 
				INNER JOIN solicitudes s ON s.id = r.idsolicitud 
				INNER JOIN pacientes a ON a.id = s.idpaciente 
				LEFT JOIN direccion c ON c.id = a.direccion 
				LEFT JOIN direcciones d ON d.id = c.iddireccion
				WHERE 1 AND r.nro_resolucion != '' ";
	$query  .= " $where2";
	$query .= "	UNION SELECT r.id, r.idsolicitud, a.expediente, r.nro_resolucion, 'Certificación' AS tipo, s.fecha_solicitud 
				FROM resolucion r 
				INNER JOIN solicitudes s ON s.id = r.idsolicitud 
				INNER JOIN pacientes a ON a.id = s.idpaciente 
				LEFT JOIN direccion c ON c.id = a.direccion 
				LEFT JOIN direcciones d ON d.id = c.iddireccion
				WHERE 1 AND r.nro_resolucion != '' ";
	$query  .= " $where2";
	$query	.= "ORDER BY idsolicitud DESC";
	
	//Fin Aplicar Filtros 
	//echo $query;
	$result = $mysqli->query($query);
	$i = 8;
	$arrayData = [];
	while($row = $result->fetch_assoc()){	

	
		$arr = array();
		$arr [] = $row['idsolicitud'];
		$arr [] = $row['expediente'];
		$arr [] = $row['nro_resolucion'];
		$arr [] = $row['tipo'];
		$arr [] = $row['fecha_solicitud'];
		$arrayData [] = $arr;
		
		$i++;
	}
	
	$titles = array(
		'Número de solicitud',
		'Número de expediente',
		'Número de resolución',
		'Tipo de resolución',
		'Fecha de solicitud'  
	);
	
	$spreadsheet->getActiveSheet()->getStyle('A7:AE7')->getFont()->setBold(true);
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
			'A8'         // Top left coordinate of the worksheet range where
						 //    we want to set these values (default is A1)
		);
	//Ancho automatico	
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true); 

	// Alineación a la izquierda de todas las columnas
	$spreadsheet->getActiveSheet()->getStyle('A1:E' . (count($arrayData) + 7))
	->getAlignment()
	->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

	$hoy = date('dmY');
	$nombreArc = 'Reporte - Resoluciones '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	ob_start();
	$writer->save('php://output');
	  
	$xlsData = ob_get_contents();
	ob_end_clean();

	$response =  array(
			'name' => $nombreArc,
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
		);

	die(json_encode($response));
   
?>