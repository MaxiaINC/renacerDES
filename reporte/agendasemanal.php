<?php
/**
**
*ENVIO DE CORREO DE AGENDA SEMANAL
**
**/
global $mysqli;
header('Content-Type: text/html; charset=UTF-8');
error_reporting(1);
require_once("../controller/conexion.php");
require_once("../controller/correoconfig.php");

require '../vendor/phpspreadsheet/vendor/autoload.php';
/** Error reporting */
//error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call xlsx writer class to make an xlsx file
use PhpOffice\PhpSpreadsheet\IOFactory;

$numsem = (date('W')+2);
$numsemana = str_pad($numsem, 2, "0", STR_PAD_LEFT);

$asunto = "Agenda - Semana ".$numsemana.", año ".date('Y');
$semana = "Semana ".$numsemana." del Año ".date('Y');

$queryMJ  = " SELECT id, CONCAT(nombre,' ',apellido) AS nombre, correo FROM medicos ";
$resultMJ = $mysqli->query($queryMJ);
//echo $queryMJ;
while($rowMJ = $resultMJ->fetch_assoc()){
	$idmiembro 	= $rowMJ['id'];
	$nombre 	= $rowMJ['nombre'];
	$correo 	= $rowMJ['correo'];
	$mensaje	= '';
	$i 			= 1;
	
	$queryMC  = "	SELECT s.id,UPPER(CONCAT(p.nombre,' ',p.apellidopaterno,' ',p.apellidomaterno))  AS paciente, p.cedula, 
					date_format(time(date_sub(fecha_cita, INTERVAL 45 MINUTE)),'%H:%i') AS horainicial, 
					date_format(time(fecha_cita),'%H:%i') AS horafinal, DATE(s.fecha_cita) AS fecha, s.condicionsalud, 
					GROUP_CONCAT(CONCAT(m.nombre,' ',m.apellido)) AS medicos
					FROM solicitudes s 
					LEFT JOIN pacientes p ON s.idpaciente = p.id 
					LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta)
					WHERE (s.estatus = 2 || s.estatus = 7) AND s.fecha_cita is not null
					AND s.fecha_cita BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND ADDDATE(DATE_ADD(CURDATE(), INTERVAL 8 DAY), INTERVAL 6 DAY) 
					AND FIND_IN_SET('".$idmiembro."',s.junta)
					GROUP BY s.id 
					ORDER BY s.fecha_cita ASC ";
	
	$nombrediaI = '';
	$resultMC = $mysqli->query($queryMC);
	if($resultMC->num_rows > 0){
		while($rowMC = $resultMC->fetch_assoc()){
			$dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
			$nombredia = $dias[date('w', strtotime($rowMC['fecha'])-1)];
			
			$fecha 			= implode('/',array_reverse(explode('-', $rowMC['fecha'])));
			$horainicial 	= $rowMC['horainicial'];
			$paciente 		= $rowMC['paciente'];
			$cedula	 		= $rowMC['cedula'];
			$condicionsalud = $rowMC['condicionsalud'];
			$medicos 		= $rowMC['medicos'];
			
			if($nombrediaI != $nombredia){
				$mensaje .= "<tr style='font-size: 12px; color: #000000; text-align: center; '>
								<td colspan='7' style='background: #479fe7;color: #ffffff;text-transform: uppercase;font-weight: 500;'>".$nombredia."</td>
							 </tr>";
			}			
			
			$mensaje .= "<tr style='font-size: 12px; color: #000000; text-align: center; '>";
			$mensaje .="<td style='padding: 10px 15px;'>".$i."</td>
						<td style='padding: 10px 15px;'>".$fecha."</td>
						<td style='padding: 10px 15px;'>".$horainicial."</td>
						<td style='padding: 10px 15px;'>".$paciente."</td>
						<td style='padding: 10px 15px;'>".$cedula."</td>
						<td style='padding: 10px 15px;'>".$condicionsalud."</td>
						<td style='padding: 10px 15px;'>".$medicos."</td>
					";
			$mensaje .= "</tr>";
			$nombrediaI = $nombredia;
			$i++;
		}
		//EJECUTAR EXCEL 
		$ran = rand(0,99999);
		generarExcel($idmiembro,$ran);
		//ENVIAR CORREO
		enviarMensaje($asunto,$mensaje,$correo,$semana,$ran);
		//debugL($idmiembro.'|'.$correo);
		//debugL($queryMC);
	}else{
		$mensaje .= "SIN MANTENIMIENTOS PARA ESTA SEMANA";
	}	
}

function generarExcel($idmiembro,$ran){
	global $mysqli;
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
	$spreadsheet->getActiveSheet()->getStyle("A1:G5")->applyFromArray($stylebordernone);
	$spreadsheet->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');
	
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
	$spreadsheet->getActiveSheet()->mergeCells('D2:F2');
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
	->setCellValue('A6', 'Expediente')
	->setCellValue('B6', 'Fecha cita')
	->setCellValue('C6', 'Hora inicio')
	->setCellValue('D6', 'Usuario') 
	->setCellValue('E6', 'Cédula') 
	->setCellValue('F6', 'Condición de salud')
	->setCellValue('G6', 'Junta Evaluadora');
	
	$spreadsheet->getActiveSheet()->getStyle('A6:G6')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A6:G6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	
	//SENTENCIA BASE
	$queryF = " SELECT DISTINCT(DATE(fecha_cita)) AS fecha_cita 
				FROM solicitudes 
				WHERE (estatus = 2 || estatus = 7) AND fecha_cita is not null ";
	if($idmiembro != 0){
		$queryF .= " AND FIND_IN_SET('".$idmiembro."',junta) ";
	}
	$queryF .= " ORDER BY fecha_cita ASC ";
	$resultF = $mysqli->query($queryF);
	$i = 7;
	while($rowF = $resultF->fetch_assoc()){
		$fecha_cita = $rowF['fecha_cita'];
		
		$query ="	SELECT s.id,UPPER(CONCAT(p.nombre,' ',p.apellidopaterno,' ',p.apellidomaterno))  AS paciente, p.cedula, 
					date_format(time(date_sub(fecha_cita, INTERVAL 45 MINUTE)),'%H:%i') AS horainicial, 
					date_format(time(fecha_cita),'%H:%i') AS horafinal, DATE(s.fecha_cita) AS fecha, s.condicionsalud, 
					GROUP_CONCAT(CONCAT(m.nombre,' ',m.apellido)) AS medicos, p.expediente
					FROM solicitudes s 
					LEFT JOIN pacientes p ON s.idpaciente = p.id 
					LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta)
					WHERE (s.estatus = 2 || s.estatus = 7) AND DATE(s.fecha_cita) = '".$fecha_cita."' 
				";
		if($idmiembro != 0){
			$query .= " AND s.fecha_cita BETWEEN DATE_ADD(CURDATE(), INTERVAL 8 DAY) AND ADDDATE(DATE_ADD(CURDATE(), INTERVAL 8 DAY), INTERVAL 6 DAY) 
						AND FIND_IN_SET('".$idmiembro."',s.junta) ";
		}
		$query .= " GROUP BY s.id 
					ORDER BY s.fecha_cita ASC ";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){ 
			$fecha = implode('/',array_reverse(explode('-', $row['fecha'])));
			$spreadsheet->getActiveSheet()
			->setCellValue('A'.$i,$row['expediente']) 
			->setCellValue('B'.$i,$fecha) 
			->setCellValue('C'.$i,$row['horainicial'])
			->setCellValue('D'.$i,$row['paciente']) 
			->setCellValue('E'.$i,$row['cedula']) 
			->setCellValue('F'.$i,$row['condicionsalud'])  
			->setCellValue('G'.$i,$row['medicos']);
			$i++;
		}
	}
	
	//Ancho automatico
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);//setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		
	$hoy = date('dmY');	
	$nombreArc = 'Reporte - Agenda '.$hoy.' - '.$ran.'.xlsx';
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save($nombreArc);
}

function enviarMensaje($asunto,$mensaje,$correo,$semana,$ran) {
	echo $correo.'-'.$semana.'<br>';
	global $mysqli, $mail;
	$cuerpo = "";
	
	$cuerpo .= "<div style='padding: 30px;font-family: arial,sans-serif;'>
					<div style='padding: 5px 10px; font-size: 14px;color: #000000; '>
						<img src='https://toolkit.maxialatam.com/senadisf2/images/renacer-index.png' style='max-width: 200px;'>
						<p style='margin:auto; font-weight:bold; width: 100%; text-align: center; margin-left: -200px;text-transform: uppercase;'>Agenda semanal</p>
					</div>

					<table border='1' style='width: 100%; border: 1px solid #a8a8a8; border-collapse: collapse;'>
						<tr style='font-size: 12px; font-weight: bold; color: #ffffff; background: #293f76; text-align: center; '>							
							<td style='padding: 10px 15px;'>#</td>
							<td style='padding: 10px 15px;'>Fecha</td>
							<td style='padding: 10px 15px;'>Hora inicial</td>
							<td style='padding: 10px 15px;'>Usuario</td>
							<td style='padding: 10px 15px;'>Cédula</td>
							<td style='padding: 10px 15px;'>Condición de salud</td>
							<td style='padding: 10px 15px;'>Junta evaluadora</td>
						</tr>
						".$mensaje."
					</table>
					<p>".$correo."</p>
				</div>";
					
	$mail->addAddress('lismarygoyo@gmail.com');
	/*
	foreach($correo as $destino){
	   $mail->addAddress($destino);
	}
	*/
	//$mail->addReplyTo('daniel.coronel@maxialatam.com', 'Daniel Coronel');
	
	//COPIA OCULTA
	//$mail->addBCC('lismary.18@gmail.com');
	$mail->FromName = "Senadis - Renacer";
	$mail->isHTML(true); // Set email format to HTML
	$mail->Subject = $asunto;
	//$mail->MsgHTML($cuerpo);
	$mail->Body = $cuerpo;
	
	//add adjunto
	$hoy = date('dmY');
	$adjunto = 'Reporte - Agenda '.$hoy.' - '.$ran.'.xlsx';
	$mail->clearAttachments();
	$mail->AddAttachment($adjunto);
		
	$mail->AltBody = "Senadis - Renacer: $asunto";
	if(!$mail->send()) {
		echo 'Mensaje no pudo ser enviado. ';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Ha sido enviado el correo Exitosamente';
		// clear all addresses and attachments for the next mail
		$mail->ClearAddresses();
		echo true;
	}  
}

?>