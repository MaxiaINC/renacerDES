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
	$sheet->setTitle('Reporte - Totales');
	
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
	
	$spreadsheet->getActiveSheet()->getStyle("A1:H5")->applyFromArray($stylebordernone);
	$spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');
	
	$spreadsheet->getActiveSheet()->getStyle('D2')->getFont()->setSize(12)->setBold(true)->getColor()->setRGB('293F76');
	$spreadsheet->getActiveSheet()->getStyle('D3')->getFont()->getColor()->setRGB('424949');
		
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
	->setCellValue('C2', 'Secretaria Nacional de Discapacidad')
	->setCellValue('C3', 'Programa RENACER')
	->setCellValue('C4', 'Totales');
	
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
		if(!empty($data->edad)){
			$edad = json_encode($data->edad);
			if ($edad!="*" && $edad != "null"){
				if($edad == 'primerainfacia'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad == 'infancia'){
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
				$query .= " AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) > ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(c.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(c.fecha_nac,'%m-%d'), 0 , -1 ) < ".$edadHasta."";
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
	
	$desde = str_replace('"','',$desdef);
	$hasta = str_replace('"','',$hastaf);
	if($desde == "" && $desde == ""){
		$spreadsheet->getActiveSheet()->setCellValue('C5', 'Fecha: '.date('Y-m-d') );
	}else{
		if(($desde!="*" && $desde != "null") || ($hasta!="*" && $hasta != "null") ){
			$spreadsheet->getActiveSheet()->setCellValue('C5', 'Desde:'.$desde.' / Hasta:'.$hasta);
		}
	}
	 
	$spreadsheet->getActiveSheet()->mergeCells('C2:E2');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
	->setCellValue('A6', 'Provincia')
	->setCellValue('B6', 'Suma de SOLICITADOS')
	->setCellValue('C6', 'Suma de CITADOS')
	->setCellValue('D6', 'Suma de EVALUADOS')		
	->setCellValue('E6', 'Suma de CERTIFICADOS')
	->setCellValue('F6', 'Suma de NO CERTIFICADOS')
	->setCellValue('G6', 'Suma de NO ASISTIÓ')
	->setCellValue('H6', 'Suma de PENDIENTE') 
	->setCellValue('I6', 'Suma de DESISTIO');	
	
	$spreadsheet->getActiveSheet()->getStyle('A6:I6')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A6:I6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
 	//SENTENCIA BASE
	$query  = " SELECT IFNULL(e.provincia,' Sin clasificar') AS provincia, COUNT(*) AS solicitudes, 
				SUM(case when a.estatus = 1 then 1 else 0 end) as noagendados, 
				SUM(case when a.estatus = 2 then 1 else 0 end) as agendados, 
				SUM(case when (a.estatus = 3 OR a.estatus = 4 OR a.estatus = 16) then 1 else 0 end) as evaluados, 
				SUM(case when ((a.estatus IN (3,27,24,26,29)) OR (a.estatus = 30 AND f.id IS NOT NULL)) then 1 else 0 end) as certificados, 
				SUM(case when ((a.estatus IN (4,28,5,31) OR (a.estatus = 30 AND g.id IS NOT NULL))) then 1 else 0 end) as nocertificados, 
				SUM(case when a.estatus = 6 then 1 else 0 end) as noasistio, 
				SUM(case when a.estatus = 16 then 1 else 0 end) as pendientes,
				SUM(case when a.estatus = 18 then 1 else 0 end) as desistieron				
				FROM solicitudes a 
				INNER JOIN pacientes c ON c.id = a.idpaciente
				LEFT JOIN direccion d ON d.id = c.direccion 
				LEFT JOIN direcciones e ON e.id = d.iddireccion
				LEFT JOIN resolucion f ON f.idsolicitud = a.id
				LEFT JOIN negatorias g ON g.idsolicitud = a.id
				WHERE 1 = 1 ";
	

	$query  .= " $where2";
	//Fin Aplicar Filtros
	
	$query .= " GROUP BY e.provincia ORDER BY provincia DESC "; 
	//debugL('EXPORTAR-TOTALES: '.$query);
	$result = $mysqli->query($query);
	$i = 7;
	$totalsolicitudes 	 = 0;
	$totalevaluados 	 = 0;
	$totalcertificados   = 0;
	$totalnocertificados = 0;
	$totalnoasistio 	 = 0;
	$totalpendientes 	 = 0;
	$totaldesistieron 	 = 0;
	
	while($row = $result->fetch_assoc()){
		if($row['provincia'] == ' Sin clasificar'){
			$provincia = trim($row['provincia']);
		}else{
			$provincia = $row['provincia'];
		}
		$spreadsheet->getActiveSheet()
		->setCellValue('A'.$i, $provincia)
		->setCellValue('B'.$i, $row['noagendados'])
		->setCellValue('C'.$i, $row['solicitudes'])
		->setCellValue('D'.$i, $row['evaluados'])
		->setCellValue('E'.$i, $row['certificados'])
		->setCellValue('F'.$i, $row['nocertificados'])
		->setCellValue('G'.$i, $row['noasistio'])
		->setCellValue('H'.$i, $row['pendientes']) 
		->setCellValue('I'.$i, $row['desistieron']);	
		
		$totalsolicitudes 	 += $row['solicitudes'];
		$totalevaluados 	 += $row['evaluados'];
		$totalcertificados   += $row['certificados'];
		$totalnocertificados += $row['nocertificados'];
		$totalnoasistio 	 += $row['noasistio'];
		$totalpendientes 	 += $row['pendientes'];
		$totalnoagendados 	 += $row['noagendados'];
		$totaldesistieron 	 += $row['desistieron'];
	
		//ESTILOS
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getFont()->setSize(10);
		$spreadsheet->getActiveSheet()->getStyle('H'.$i)->getAlignment()->applyFromArray(
					array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
		$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AJ'.$i)->getAlignment()->applyFromArray(
					array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
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
	 
	$spreadsheet->getActiveSheet()
	->setCellValue('A'.$i, "Total general")
	->setCellValue('B'.$i, $totalnoagendados)
	->setCellValue('C'.$i, $totalsolicitudes)
	->setCellValue('D'.$i, $totalevaluados)
	->setCellValue('E'.$i, $totalcertificados)
	->setCellValue('F'.$i, $totalnocertificados)
	->setCellValue('G'.$i, $totalnoasistio)
	->setCellValue('H'.$i, $totalpendientes) 
	->setCellValue('I'.$i, $totaldesistieron);
	
	$spreadsheet->getActiveSheet()->getStyle('A'.$i.':I'.$i.'')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A'.$i.':I'.$i.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
	//Ancho automatico	
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	
	$hoy = date('dmY');
	$nombreArc = 'Reporte - Totales '.$hoy.'.xlsx';
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