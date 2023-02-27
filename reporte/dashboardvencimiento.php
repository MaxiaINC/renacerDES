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
	
	$spreadsheet->getActiveSheet()->getStyle("A1:H6")->applyFromArray($stylebordernone);
	$spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');
	
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
	->setCellValue('B4', 'Carnets próximos a vencer');
	
	if(($desde!="*" && $desde != "null") || ($hasta!="*" && $hasta != "null") ){
		$spreadsheet->getActiveSheet()->setCellValue('B5', $desde.' / '.$hasta);
	}
	$spreadsheet->getActiveSheet()->mergeCells('B2:D2');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
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
	->setCellValue('P7', 'Fecha de emision')
	->setCellValue('Q7', 'Fecha de vencimiento')
	->setCellValue('R7', 'Duración');	
	
	$spreadsheet->getActiveSheet()->getStyle('A7:R7')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A7:R7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
	//SENTENCIA BASE
	$query  = " SELECT s.id AS solicitud, a.expediente, CONCAT(a.nombre, ' ', a.apellidopaterno) AS nombre, a.cedula, a.telefono, a.celular, a.sexo, 
				YEAR(CURDATE())-YEAR(a.fecha_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(a.fecha_nac,'%m-%d'), 0 , -1 ) AS edad, 
				d.codigo, d.provincia, d.distrito, d.corregimiento, b.tipodiscapacidad, s.condicionsalud, 
				b.fechaemision, b.fechavencimiento, b.duracion, s.estatus, s.fecha_cita, s.fecha_solicitud,
				c.urbanizacion, c.calle, c.edificio, c.numero, e.descripcion AS estado
				FROM pacientes a 
				LEFT JOIN evaluacion b ON b.idpaciente = a.id 
                INNER JOIN solicitudes s ON s.idpaciente = a.id AND b.idsolicitud = s.id
				INNER JOIN estados e ON s.estatus = e.id
				LEFT JOIN direccion c ON c.id = a.direccion 
				LEFT JOIN direcciones d ON d.id = c.iddireccion
				WHERE s.estatus = 3 AND b.fechaemision IS NOT NULL 
				AND YEAR(b.fechavencimiento) >= '2020' 				
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
	//debugL($query);
	$result = $mysqli->query($query);
	$i = 8;
	while($row = $result->fetch_assoc()){
		//Sexo
		if($row['sexo'] == 'F'){
			$sexo = 'Femenino';
		}
		if($row['sexo'] == 'M'){
			$sexo = 'Masculino';
		}
		
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
		
		if($row['telefono'] != '' && $row['celular'] != ''){
			$telefono = $row['telefono'].', '.$row['celular'];
		}elseif($row['telefono'] != ''){
			$telefono = $row['telefono'];
		}elseif($row['celular'] != ''){
			$telefono = $row['celular'];
		}else{
			$telefono = '';
		}
		
		$mostrar = 0;
		$esfecha = validateDate($row['fechavencimiento']); 
		
		if( $row['fechaemision'] != '' && $row['fechavencimiento'] != '' && $esfecha == true){  
			
			$date1 = new DateTime($row['fechavencimiento']);
			$date2 = new DateTime(date('Y-m-d'));
			$diff = $date1->diff($date2);
			$anios = $diff->y;
			$months = $diff->m;
			  
			if($anios < 1 && $months <= 6 && ( $date1 > $date2 )){ 
				$fechavencimiento = implode('-',array_reverse(explode('-', $row['fechavencimiento'])));
				$spreadsheet->getActiveSheet()
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
				->setCellValue('P'.$i, $row['fechaemision'])
				->setCellValue('Q'.$i, $fechavencimiento)
				->setCellValue('R'.$i, $row['duracion']);

				//ESTILOS
				$spreadsheet->getActiveSheet()->getStyle('A'.$i.':Q'.$i)->getFont()->setSize(10);
				$spreadsheet->getActiveSheet()->getStyle('H'.$i)->getAlignment()->applyFromArray(
							array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
				$spreadsheet->getActiveSheet()->getStyle('A'.$i.':AC'.$i)->getAlignment()->applyFromArray(
							array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT));
							
				$i++;
			} 
		} 	
	}
	
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
	$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	
	$hoy = date('dmY');
	$nombreArc = 'Reporte - Carnets próximos a vencer '.$hoy.'.xlsx';
	// redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Type: application/vnd.ms-excel'); //xls
	header('Content-Disposition: attachment;filename='.$nombreArc);
	header('Cache-Control: max-age=0');	
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	ob_start();
	$writer->save('php://output');
   
	function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
		
	$xlsData = ob_get_contents();
	ob_end_clean();

	$response =  array(
			'name' => $nombreArc,
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
		);

	die(json_encode($response));
?>