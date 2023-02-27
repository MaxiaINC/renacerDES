<?php
	include("../controller/conexion.php");
	require '../vendor/phpspreadsheet/vendor/autoload.php';

	global $mysqli; 
	
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
	$spreadsheet->getActiveSheet()->getStyle("A1:I5")->applyFromArray($stylebordernone);
	$spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');
	
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
	->setCellValue('D2', 'Secretaria Nacional de Discapacidad')
	->setCellValue('D3', 'Programa RENACER')
	->setCellValue('D4', 'Agenda');
	
	/* if(($desde!="*" && $desde != "null") || ($hasta!="*" && $hasta != "null") ){
		$spreadsheet->getActiveSheet()->setCellValue('D5', $desde.' / '.$hasta);
	} */
	$spreadsheet->getActiveSheet()->mergeCells('D2:F2');
	
	// ENCABEZADO 
	/* $spreadsheet->getActiveSheet()
	->setCellValue('A6', 'Expediente')
	->setCellValue('B6', 'Fecha cita')
	->setCellValue('C6', 'Hora inicio')
	->setCellValue('D6', 'Usuario') 
	->setCellValue('E6', 'Cédula') 
	->setCellValue('F6', 'Condición de salud')
	->setCellValue('G6', 'Junta Evaluadora')
	->setCellValue('H6', 'Provincia')
	->setCellValue('I6', 'Estado')
	; */
	
	$spreadsheet->getActiveSheet()->getStyle('A6:I6')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A6:I6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	$i = 7;
	//SENTENCIA BASE  
	$queryS ="	SELECT s.id,UPPER(CONCAT(p.nombre,' ',p.apellidopaterno,' ',p.apellidomaterno))  AS paciente, p.cedula, 
				date_format(time(date_sub(fecha_cita, INTERVAL 45 MINUTE)),'%H:%i') AS horainicial, 
				date_format(time(fecha_cita),'%H:%i') AS horafinal, DATE(s.fecha_cita) AS fecha, s.condicionsalud, 
				GROUP_CONCAT(CONCAT(m.nombre,' ',m.apellido)) AS medicos, p.expediente, dir.provincia, e.descripcion AS estado
				FROM solicitudes s 
				LEFT JOIN pacientes p ON s.idpaciente = p.id 
				LEFT JOIN discapacidades d ON d.id = s.iddiscapacidad
				LEFT JOIN direccion di ON di.id = p.direccion 
				LEFT JOIN direcciones dir ON dir.id = di.iddireccion
				LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta)
				LEFT JOIN estados e ON e.id = s.estatus
				WHERE s.fecha_cita IS NOT NULL
			"; //(s.estatus = 2 || s.estatus = 7)
	//Aplicar Filtros
	$where2 = '';
	$queryF 	= "SELECT filtrosmasivos FROM usuariosfiltros WHERE modulo = 'Calendario' AND usuario = '".$_SESSION['usuario_sen']."'";
	 	
	$resultF = $mysqli->query($queryF);
	if($resultF->num_rows >0){
		$rowF = $resultF->fetch_assoc();
		if (!isset($_REQUEST['data'])) {
			$data = $rowF['filtrosmasivos'];
		}
	}
	if($data != ''){
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
		if(!empty($data->edad)){
			$edad = json_encode($data->edad);
			if ($edad!="*" && $edad != "null"){
				if($edad=='primerainfacia'){
					$edadDesde = 0;
					$edadHasta = 5; 
				}
				if($edad=='infancia'){
					$edadDesde = 6;
					$edadHasta = 11;
				}
				if($edad=='adolescencia'){
					$edadDesde = 12;
					$edadHasta = 18;
				}
				if($edad=='juventud'){
					$edadDesde = 19;
					$edadHasta = 26;
				}
				if($edad=='adultez'){
					$edadDesde = 27;
					$edadHasta = 59;
				}
				if($edad=='personamayor'){
					$edadDesde = 60;
					$edadHasta = 150;
				}
				$query .= " AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) > ".$edadDesde." 
							AND YEAR(CURDATE())-YEAR(p.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nac,'%m-%d'), 0 , -1 ) < ".$edadHasta."";
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
		if(!empty($data->idmedicos)){
			$idmedicos = json_encode($data->idmedicos);
			if($idmedicos != '[""]'){
				$where2 .= " AND FIND_IN_SET($idmedicos,s.junta)";
			}
		}
		
		$vowels = array("[", "]");
		$where2 = str_replace($vowels, "", $where2);
	}
	$queryS  .= " $where2";
	//Fin Aplicar Filtros
	$queryS .= " GROUP BY s.id ORDER BY s.fecha_cita ASC ";
	 
	$resultS = $mysqli->query($queryS);
	while($rowS = $resultS->fetch_assoc()){ 
		$fecha = implode('/',array_reverse(explode('-', $rowS['fecha'])));
		/* $spreadsheet->getActiveSheet()
		->setCellValue('A'.$i,$rowS['expediente']) 
		->setCellValue('B'.$i,$fecha) 
		->setCellValue('C'.$i,$rowS['horafinal'])
		->setCellValue('D'.$i,$rowS['paciente']) 
		->setCellValue('E'.$i,$rowS['cedula']) 
		->setCellValue('F'.$i,$rowS['condicionsalud'])  
		->setCellValue('G'.$i,$rowS['medicos'])
		->setCellValue('H'.$i,$rowS['provincia'])
		->setCellValue('I'.$i,$rowS['estado'])
		; */
		
		$arr = array();
		$arr [] = $rowS['expediente'];
		$arr [] = $fecha;
		$arr [] = $rowS['horafinal'];
		$arr [] = $rowS['paciente'];
		$arr [] = $rowS['cedula'];
		$arr [] = $rowS['condicionsalud'];
		$arr [] = $rowS['medicos'];
		$arr [] = $rowS['provincia'];
		$arr [] = $rowS['estado'];
		$arrayData [] = $arr;
		
		$i++;
	} 
	
	$titles = array(
		'Expediente',
		'Fecha cita',
		'Hora inicio',
		'Usuario',
		'Cédula',
		'Condición de salud',
		'Junta Evaluadora',
		'Provincia',
		'Estado'
	);
	
	$spreadsheet->getActiveSheet()->getStyle('A6:I6')->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()
		->fromArray(
			$titles,  // The data to set
			NULL,        // Array values with this value will not be set
			'A6'         // Top left coordinate of the worksheet range where
						 //    we want to set these values (default is A1)
		);
	
	$spreadsheet->getActiveSheet()
		->fromArray(
			$arrayData,  // The data to set
			NULL,        // Array values with this value will not be set
			'A7'         // Top left coordinate of the worksheet range where
						 //    we want to set these values (default is A1)
		);
	//Ancho automatico
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);//setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		
	$hoy = date('dmY');
	$nombreArc = 'Reporte - Agenda '.$hoy.'.xlsx';
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