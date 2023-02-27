<?php
include("../controller/conexion.php");
include_once "../controller/funciones.php";
verificarLogin('reportes');
include_once("../fpdf/fpdf.php");
$id 		 = $_GET['id']; 
   
class PDF extends FPDF{
	// Cabecera de página
	function Header(){

	}
	// Pie de página
	function Footer(){   		
	}
}    

 //Creación del objeto de la clase heredada
$pdf = new FPDF('P', 'mm', 'Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);    
$pdf->SetFillColor(255,255,255);    
$pdf->SetTextColor(0,0,0);	
//Establecemos los márgenes izquierda, arriba y derecha:
$pdf->SetMargins(10, 15 , 10);
//Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,10);

// Logo
$pdf->Image('../images/senadis.png',150,10,50); //borde izq, borde sup, ancho
$pdf->SetFont('Arial','B',12);
 
 // Título
$pdf->Ln(40);
$pdf->SetFont('Arial','B',16);
$pdf->SetTextColor(0,0,0);

$pdf->Cell(0,8,utf8_decode('DIRECCIÓN NACIONAL DE CERTIFICACIONES'),0,1,'C');
$pdf->Cell(0,8,utf8_decode('NO ASISTIÓ'),0,1,'C');

$pdf->SetFont('Arial','',12);

$pdf->Cell(0,10,utf8_decode('Teléfonos: 504-3281 / 504-3319'),0,1,'C');

$pdf->Ln(20);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'__________________________, _______ de  __________________________  de 20____',0,1,'L');
$pdf->Cell(0,10,'',0,1); // Añadir espacio en blanco

$pdf->Cell(0,10,utf8_decode('Descripción de la situación:'),0,1);
$pdf->Cell(0,10,'',0,1); // Añadir espacio en blanco

$pdf->MultiCell(0,10,utf8_decode('Por medio del presente informe hacemos constar que para el día __________________ se le cita a _____________________________________ con cédula __________________________, para acudir a la entrevista ante la Junta Evaluadora de Discapacidad a las _______________, como parte del proceso de Certificación, sin embargo, deseamos comunicar el incumplimiento de la cita; razón por cual se cancela la evaluación por parte de la Junta Evaluadora habilitada para su atención mediante Resolución N°_________________.'),0,'J');

$pdf->Cell(0,10,'',0,1); // Añadir espacio en blanco

$pdf->Cell(0,10,'Miembros de la Junta Evaluadora:',0,1);
$pdf->Cell(0,10,'',0,1); // Añadir espacio en blanco

$pdf->Cell(0,10,'_________________________________',0,1,'L');
$pdf->Cell(0,10,'_________________________________',0,1,'L');
$pdf->Cell(0,10,'_________________________________',0,1,'L');

$pdf->Output('certificaciones.pdf','I');
