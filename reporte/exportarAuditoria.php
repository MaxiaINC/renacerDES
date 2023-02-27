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
	$spreadsheet->getActiveSheet()->getStyle("A1:F5")->applyFromArray($stylebordernone);
	$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');
	
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
	->setCellValue('D4', 'Auditorías');
	
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
	
	//LocalStorage
	$bexpediente= $_REQUEST['bexpediente'];
	$bcedula	= $_REQUEST['bcedula'];
	$bpaciente 	= $_REQUEST['bpaciente'];
	$bregional	= $_REQUEST['bregional'];
	$bestado 	= $_REQUEST['bestado'];
	$bauditor	= $_REQUEST['bauditor'];
	
	$spreadsheet->getActiveSheet()->getStyle('A6:F6')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A6:F6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	$i = 7;
	//SENTENCIA BASE  
	$queryS ="	SELECT b.expediente, b.cedula,LEFT(CONCAT(b.nombre,' ',b.apellidopaterno,' ',b.apellidomaterno),60) as paciente, 
				c.descripcion AS estado, GROUP_CONCAT(d.nombre) AS auditores, 
				f.nombre AS regional
				FROM auditorias a
				LEFT JOIN pacientes b ON b.id = a.idpacientes 
				LEFT JOIN estados c ON c.id = a.idestados
				LEFT JOIN usuarios d ON FIND_IN_SET(d.id,a.idauditores)
				LEFT JOIN solicitudes e ON e.idpaciente = b.id
				INNER JOIN regionales f ON f.id = e.regional
				WHERE 1 = 1 
				";
	//LocalStorage
	if($bexpediente != ''){
		$where .= " AND b.expediente = '".$bexpediente."' ";
	}
	if($bcedula != ''){
		$where .= " AND b.cedula = '".$bcedula."' ";
	}
	if($bpaciente != ''){
		$match = str_replace(' ',' +',$bpaciente);
		$where .=" AND MATCH (b.nombre,b.apellidopaterno,b.apellidomaterno) AGAINST ('+".$match."' IN BOOLEAN MODE) ";
	}
	if($bregional != ''){
		$where .= " AND f.nombre LIKE '%".$bregional."%' ";
	}
	if($bestado != ''){
		$where .= " AND c.descripcion LIKE '%".$bestado."%' ";
	}
	if($bauditor != ''){
		$where .= " AND d.nombre LIKE '%".$bauditor."%' ";
	}
	
	$queryS .=" $where GROUP BY a.id";
	$queryS .= "  ORDER BY a.id DESC ";
	//echo $queryS; 
	$resultS = $mysqli->query($queryS);
	while($rowS = $resultS->fetch_assoc()){ 
		$fecha = implode('/',array_reverse(explode('-', $rowS['fecha'])));
		
		$arr = array();
		$arr [] = $rowS['expediente'];
		$arr [] = $rowS['cedula'];
		$arr [] = $rowS['paciente'];
		$arr [] = $rowS['regional'];
		$arr [] = $rowS['estado'];
		$arr [] = $rowS['auditores'];
		$arrayData [] = $arr;
		
		$i++;
	} 
	
	$titles = array(
		'Expediente',
		'Cédula',
		'Nombre',
		'Regional',
		'Estado',
		'Auditores' 
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
		
	$hoy = date('dmY');
	$nombreArc = 'Reporte - Auditorías '.$hoy.'.xlsx';
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