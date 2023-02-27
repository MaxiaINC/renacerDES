<?php
	include("../controller/conexion.php");
	include_once "../controller/funciones.php";
	verificarLogin('reportes');
    include_once("../fpdf/fpdf.php");
	include_once("imprimircolpro.php");
    $id = $_GET['sol'];
	$ev = $_GET['ev'];
	   
	class PDF extends FPDF{
    	// Cabecera de página
    	function Header(){	
    	}
    	// Pie de página
    	function Footer(){    		
    	}
    }
	
	//Creación del objeto de la clase heredada
	$pdf = new PDF('P', 'mm', 'Legal');
	//Establecemos los márgenes izquierda, arriba y derecha:
	$pdf->SetMargins(10, 15 , 10);
	//Establecemos el margen inferior:
	$pdf->SetAutoPageBreak(true,10); 
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);    
	$pdf->SetTextColor(0,0,0);
	
	//DATOS DEL SOLICITANTE
	$queryS = " SELECT CONCAT(p.nombre,' ',p.apellidopaterno, ' ',p.apellidomaterno) AS paciente, s.fecha_cita, p.id as idpaciente,
				s.iddiscapacidad as iddiscapacidad, GROUP_CONCAT(CONCAT(m.id,'|',m.nombre,' ',m.apellido,'|', REPLACE(e.nombre,',',' / '))) AS medicos, 
				s.sala AS sala, r.nombre AS regional, d.nombre AS discapacidad, hs.fecha, s.idacompanante, s.tipoacompanante, s.iddiscapacidad,
				ev.horainicio, ev.horafinal, ev.codigojunta, ev.diagnostico, ev.tiposolicitud, ev.documentos, ev.fechainiciodano, ev.ayudatecnica, ev.ayudatecnicaotro,
				ev.alfabetismo, ev.niveleducacional, ev.niveleducacionalcompletado, ev.tipoeducacion, ev.concurrenciatipoeducacion, ev.convivencia, ev.tipovivienda, 
				ev.viviendaadaptada, ev.cantidadhabitaciones, ev.mediotransporte, ev.estadocalles, ev.vinculos, ev.etnia, ev.religion, ev.ingresomensual, ev.ingresomensualotro, ev.observaciones,
				ev.fechavencimiento, ev.fechaemision, ev.ciudad as lugar, ev.cif, ev.porcentaje1 as porcentaje1, ev.porcentaje2 as porcentaje2, ev.criterio as criterio, 
				ev.certifica as certifica, ev.regla as regla, s.fecha_solicitud, s.estatus, ev.duracion, ev.tipoduracion
				FROM solicitudes s 
				LEFT JOIN pacientes p ON p.id = s.idpaciente 
				LEFT JOIN discapacidades d ON d.id = s.iddiscapacidad
				LEFT JOIN regionales r ON r.id = s.regional
				LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta) 
				LEFT JOIN especialidades e ON e.id = m.especialidad 
				LEFT JOIN historicosolicitudes hs ON hs.idsolicitud = s.id AND hs.estadoactual = 17
				LEFT JOIN evaluacion ev ON ev.idsolicitud = s.id
				WHERE s.id = ".$id." AND ev.id = ".$ev ;
	//debug($queryS);
	$resultS = $mysqli->query($queryS); 
	if($rows = $resultS->fetch_assoc()){
		$idpaciente	    	= $rows['idpaciente'];
		$paciente	    	= $rows['paciente'];
		$fecha 	        	= $rows['fecha'];
		$fecha_cita 	    = $rows['fecha_cita'];
		$medicos 			= $rows['medicos'];
		$discapacidad 		= $rows['discapacidad']; //
		$iddiscapacidad 	= $rows['iddiscapacidad'];
		$sala           	= $rows['sala'];
		$regional       	= $rows['regional'];
		$fechasol  			= $rows['fecha'];
		$idacompanante  	= $rows['idacompanante'];
		$tipoacompanante 	= $rows['tipoacompanante'];
		$horainicio  		= $rows['horainicio']; //
		$horafinal 			= $rows['horafinal']; //
		$codigojunta  		= $rows['codigojunta']; //
		$diagnostico  		= $rows['diagnostico']; //
		$tiposolicitud 		= $rows['tiposolicitud']; //
		$documentos 		= $rows['documentos']; //
		$fechainiciodano 	= $rows['fechainiciodano']; //
		$ayudastecnicas 	= $rows['ayudatecnica']; //
		$ayudastecnicasot 	= $rows['ayudatecnicaotro']; //		
		$alfabetismo 		= $rows['alfabetismo']; //
		$niveleducacional 	= $rows['niveleducacional']; //
		$niveleducacionalc 	= $rows['niveleducacionalcompletado']; //
		$tipoeducacion 		= $rows['tipoeducacion']; //
		$concurrenciatipoed	= $rows['concurrenciatipoeducacion']; //
		$convivencia  		= $rows['convivencia']; //
		$tipovivienda  		= $rows['tipovivienda']; // 
		$viviendaadaptada 	= $rows['viviendaadaptada']; //
		$cantidadhabitaciones 	= $rows['cantidadhabitaciones']; //
		$mediotransporte 	= $rows['mediotransporte']; //
		$estadocalles 		= $rows['estadocalles']; //
		$vinculos 			= $rows['vinculos']; //		
		$etnia 				= $rows['etnia']; //
		$religion 			= $rows['religion']; //
		$ingresomensual 	= $rows['ingresomensual']; //
		$ingresomensualotro = $rows['ingresomensualotro']; //
		$observaciones		= $rows['observaciones']; //		
		$fechavencimiento 	= $rows['fechavencimiento']; //
		$fechaemision 		= $rows['fechaemision']; //
		$lugar 				= $rows['lugar']; //
		$cif 				= $rows['cif']; //
		$porcentaje1 		= $rows['porcentaje1']; //
		$porcentaje2 		= $rows['porcentaje2']; //
		$criterio 			= $rows['criterio']; //
		$certifica 			= $rows['certifica']; //
		$estatus 			= $rows['estatus']; //
		$regla 				= $rows['regla'];
		$fecha_solicitud 	= $rows['fecha_solicitud'];
		$duracion 			= $rows['duracion'];
		$tipoduracion 		= $rows['tipoduracion'];
		//debug($cif);
		// echo $queryS;
		// echo $rows['porcentaje1'];
		// echo $rows['criterio'];
		// echo $rows['certifica'];
		// echo $rows['regla'];
		//PACIENTE
		$queryP = " SELECT nombre, apellidopaterno, apellidomaterno, cedula, tipo_documento, fecha_nac, sexo, telefono, celular, correo, nacionalidad,
					estado_civil, condicion_actividad, categoria_actividad, cobertura_medica, beneficios, discapacidades, direccion
					FROM pacientes WHERE id = '$idpaciente' ";					
		$resultP = $mysqli->query($queryP);		
		if($rowP = $resultP->fetch_assoc()){
			$nombre 			= $rowP['nombre']; //
			$apellidopaterno 	= $rowP['apellidopaterno']; //
			$apellidomaterno 	= $rowP['apellidomaterno']; //
			$cedula 			= $rowP['cedula']; //
			$tipo_documento 	= $rowP['tipo_documento']; //
			$fecha_nac 			= $rowP['fecha_nac'];
			$sexo 				= $rowP['sexo'];
			$telefono 			= $rowP['telefono'];
			$celular 			= $rowP['celular'];
			$correo 			= $rowP['correo'];
			$nacionalidad 		= $rowP['nacionalidad'];
			$estado_civil 		= $rowP['estado_civil'];			
			$condicion_actividad = $rowP['condicion_actividad'];
			$categoria_actividad = $rowP['categoria_actividad'];
			$cobertura_medica 	= $rowP['cobertura_medica'];
			$beneficios 		= $rowP['beneficios'];
			$discapacidades 	= $rowP['discapacidades'];
			$direccion 			= $rowP['direccion'];
		}
	}
	$cersi = '';
	$cerno = '';
	if($certifica == 'Si' || $certifica == 'SI' || $estatus == 3){
		$cersi = 'X';
		$cerno = '';
	}else if($certifica == 'No' || $certifica == 'NO' || $estatus == 4){
		$cersi = '';
		$cerno = 'X';
	}
    // Logo
    $pdf->Image('../images/senadis.png',9,20,40); //borde izq, borde sup, ancho
    $pdf->SetFont('Arial','B',10);
    
    $pdf->Cell(140,8,'','0',0,'C');
	$pdf->SetFillColor(178,179,183);
    $pdf->Cell(32,7,'HORA DE INICIO','1',0,'L',true); 
	$pdf->SetFillColor(255,255,255);
    $pdf->Cell(24,7,$horainicio,'1',1,'L');  
	
    $pdf->Cell(140,8,'','0',0,'C');  
	$pdf->SetFillColor(178,179,183);
    $pdf->Cell(32,7,'HORA FINAL','1',0,'L',true); 
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(24,7,$horafinal,'1',1,'L'); 		   
	$pdf->Ln(3);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(50,8,'','0',0,'C');  
    $pdf->Cell(140,8,' '.utf8_decode('PROTOCOLO PARA LA EVALUACIÓN, VALORIZACIÓN'),'0',1,'C');
	$pdf->Cell(50,8,'','0',0,'C');  
    $pdf->Cell(140,8,' '.utf8_decode('Y CERTIFICACIÓN DE LA DISCAPACIDAD'),'0',0,'C');
	
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,2,'','LRT',1,'L',true); 
	$pdf->Cell(3,8,'','LR',0,'L',true);
	$pdf->Cell(20,8,' NOMBRE:','TL',0,'L',true); 
	$pdf->Cell(75,8,utf8_decode($nombre),'T',0,'L'); 
	$pdf->Cell(22,8,' APELLIDO:','T',0,'L',true); 
	$pdf->Cell(73,8,utf8_decode($apellidopaterno.' '.$apellidomaterno),'T',0,'L'); 		
	$pdf->Cell(3,8,'','LR',1,'L',true);
	$pdf->Cell(3,8,'','LR',0,'L',true);  
	$pdf->Cell(48,8,' '.utf8_decode('FECHA DE EVALUACIÓN:'),'LT',0,'L',true);  
	$pdf->Cell(142,8,$fecha_cita,'T',0,'L');
	$pdf->Cell(3,8,'','LR',1,'L',true);
	
	$pdf->Cell(3,8,'','LR',0,'L',true);  
	$pdf->Cell(34,8,' '.utf8_decode('CÓDIGO DE JUNTA EVALUADORA:'),'LT',0,'L',true);  
	$pdf->Cell(84,8,'','T',0,'L');
	
	$codigojunta = trim($codigojunta);
	$longcj = strlen($codigojunta);
	$c1 = ''; $c2 = ''; $c3 = ''; $c4 = ''; $c5 = ''; $c6 = ''; $c7 = ''; $c8 = '';
	if($longcj == 4){
		$c5  = substr($codigojunta, -4,1);		$c6  = substr($codigojunta, -3,1);		$c7  = substr($codigojunta, -2,1);		$c8  = substr($codigojunta, -1,1);
	}elseif($longcj == 5){
		$c4  = substr($codigojunta, -5,1);		$c5  = substr($codigojunta, -4,1);		$c6  = substr($codigojunta, -3,1);		$c7  = substr($codigojunta, -2,1);		
		$c8  = substr($codigojunta, -1,1);
	}elseif($longcj == 6){
		$c3  = substr($codigojunta, -6,1);		$c4  = substr($codigojunta, -5,1);		$c5  = substr($codigojunta, -4,1); 		$c6  = substr($codigojunta, -3,1);		
		$c7  = substr($codigojunta, -2,1);		$c8  = substr($codigojunta, -1,1);
	}elseif($longcj == 7){
		$c2  = substr($codigojunta, -7,1);		$c3  = substr($codigojunta, -6,1);		$c4  = substr($codigojunta, -5,1);		$c5  = substr($codigojunta, -4,1);
		$c6  = substr($codigojunta, -3,1);		$c7  = substr($codigojunta, -2,1);		$c8  = substr($codigojunta, -1,1);
	}elseif($longcj == 8){
		$c1  = substr($codigojunta, -8,1);		$c2  = substr($codigojunta, -7,1);		$c3  = substr($codigojunta, -6,1);		$c4  = substr($codigojunta, -5,1);
		$c5  = substr($codigojunta, -4,1);		$c6  = substr($codigojunta, -3,1);		$c7  = substr($codigojunta, -2,1);      $c8  = substr($codigojunta, -1,1);
	}
	
	$pdf->Cell(9,8,$c1,'1',0,'C');  
	$pdf->Cell(9,8,$c2,'1',0,'C');  
	$pdf->Cell(9,8,$c3,'1',0,'C');  
	$pdf->Cell(9,8,$c4,'1',0,'C');  
	$pdf->Cell(9,8,$c5,'1',0,'C');  
	$pdf->Cell(9,8,$c6,'1',0,'C');  
	$pdf->Cell(9,8,$c7,'1',0,'C');
	$pdf->Cell(9,8,$c8,'1',0,'C');
	$pdf->Cell(3,8,'','LR',1,'L',true); 
	
	$pdf->Cell(3,2,'','L',0,'L',true);
	$pdf->Cell(190,2,'','TB',0,'L',true); 
	$pdf->Cell(3,2,'','R',1,'L',true); 
	
	$pdf->Cell(3,8,'','LR',0,'L',true);
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(190,8,' DOCUMENTO DE IDENTIDAD:','LT',0,'L',true); 
	$pdf->Cell(3,8,'','LR',1,'L');
	
	if($tipo_documento == 1){
		$cip = 'X';
		$pas = '';
	}elseif($tipo_documento == 2){
		$cip = '';
		$pas = 'X';
	}
	$longc = strlen($cedula);
	$td1 = ''; $td2 = ''; $td3 = ''; $td4 = ''; $td5 = ''; $td6 = ''; $td7 = ''; $td8 = ''; $td9 = ''; $td10 = ''; $td11 = '';
	if($longc == 6){
		$td4  = '';
		$td5  = '';		$td6  = substr($cedula, -6,1);		$td7  = substr($cedula, -5,1);		$td8  = substr($cedula, -4,1);
		$td9  = substr($cedula, -3,1);		$td10  = substr($cedula, -2,1);		$td11  = substr($cedula, -1,1);
	}elseif($longc == 7){
		$td4  = '';
		$td5  = substr($cedula, -7,1);		$td6  = substr($cedula, -6,1);		$td7  = substr($cedula, -5,1);		$td8  = substr($cedula, -4,1);
		$td9  = substr($cedula, -3,1);		$td10  = substr($cedula, -2,1);		$td11  = substr($cedula, -1,1);
	}elseif($longc == 8){
		$td4  = substr($cedula, -8,1);
		$td5  = substr($cedula, -7,1);		$td6  = substr($cedula, -6,1);		$td7  = substr($cedula, -5,1);		$td8  = substr($cedula, -4,1);
		$td9  = substr($cedula, -3,1);		$td10  = substr($cedula, -2,1);		$td11  = substr($cedula, -1,1);
	}elseif($longc == 9){
		$td3  = substr($cedula, -9,1);		$td4  = substr($cedula, -8,1);
		$td5  = substr($cedula, -7,1);		$td6  = substr($cedula, -6,1);		$td7  = substr($cedula, -5,1);		$td8  = substr($cedula, -4,1);
		$td9  = substr($cedula, -3,1);		$td10  = substr($cedula, -2,1);		$td11  = substr($cedula, -1,1);
	}elseif($longc == 10){
		$td2  = substr($cedula, -10,1);		$td3  = substr($cedula, -9,1);		$td4  = substr($cedula, -8,1);
		$td5  = substr($cedula, -7,1);		$td6  = substr($cedula, -6,1);		$td7  = substr($cedula, -5,1);		$td8  = substr($cedula, -4,1);
		$td9  = substr($cedula, -3,1);		$td10  = substr($cedula, -2,1);		$td11  = substr($cedula, -1,1);
	}elseif($longc == 11){
		$td1  = substr($cedula, -11,1);		$td2  = substr($cedula, -10,1);		$td3  = substr($cedula, -9,1);		$td4  = substr($cedula, -8,1);
		$td5  = substr($cedula, -7,1);		$td6  = substr($cedula, -6,1);		$td7  = substr($cedula, -5,1);		$td8  = substr($cedula, -4,1);
		$td9  = substr($cedula, -3,1);		$td10  = substr($cedula, -2,1);		$td11  = substr($cedula, -1,1);
	}
	
	$pdf->Cell(3,4,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(5,4,'T','LTR',0,'C',true); 
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(4,4,'','LT',0,'L');  
	$pdf->Cell(12,4,'','TB',0,'L');
	$pdf->Cell(6,4,'','TB',0,'L');
	$pdf->Cell(60,4,'','T',0,'L');  
	$pdf->Cell(12,4,'','BT',0,'L');  
	$pdf->Cell(6,4,'','T',0,'L');  
	$pdf->Cell(8,4,'','TR',0,'L');
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(77,4,'','LRT',0,'C',true);  
	$pdf->Cell(3,4,'','LR',1,'L');
	
	$pdf->Cell(3,5,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(5,5,'I','LR',0,'C',true); 
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(4,5,'','LR',0,'L');  
	$pdf->Cell(12,5,'C.I.P.','LTR',0,'L',true);  
	$pdf->Cell(6,5,$cip,'LTR',0,'C');  
	$pdf->Cell(60,5,'','LR',0,'L');  
	$pdf->Cell(12,5,'PAS.','LTR',0,'L',true);  
	$pdf->Cell(6,5,$pas,'LBT',0,'C');  
	$pdf->Cell(8,5,'','LR',0,'L');  
	$pdf->Cell(77,5,' '.utf8_decode('NÚMERO'),'LRB',0,'C',true);  
	$pdf->Cell(3,5,'','LR',1,'L');
	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(5,4,'PO','LR',0,'C',true);
	$pdf->SetY($y); $pdf->SetX($x+5); 
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(4,4,'','L',0,'L');  
	$pdf->Cell(12,4,'','T',0,'L');  
	$pdf->Cell(6,4,'','T',0,'L');  
	$pdf->Cell(60,4,'','0',0,'L');  
	$pdf->Cell(12,4,'','T',0,'L');  
	$pdf->Cell(6,4,'','T',0,'L');  
	$pdf->Cell(8,4,'','0',0,'L');  
	$pdf->Cell(7,8,$td1,'LT',0,'C');  
	$pdf->Cell(7,8,$td2,'LT',0,'C');  
	$pdf->Cell(7,8,$td3,'LT',0,'C');  
	$pdf->Cell(7,8,$td4,'LT',0,'C');  
	$pdf->Cell(7,8,$td5,'LT',0,'C');  
	$pdf->Cell(7,8,$td6,'LT',0,'C');  
	$pdf->Cell(7,8,$td7,'LT',0,'C');   
	$pdf->Cell(7,8,$td8,'LT',0,'C');   
	$pdf->Cell(7,8,$td9,'LT',0,'C');   
	$pdf->Cell(7,8,$td10,'LT',0,'C');   
	$pdf->Cell(7,8,$td11,'LT',0,'C');   
	$pdf->Cell(3,8,'','LR',1,'L',true);
	
	$pdf->Cell(3,2,'','L',0,'L',true);
	$pdf->Cell(190,2,'','TB',0,'L',true); 
	$pdf->Cell(3,2,'','R',1,'L',true);  
	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(190,8,' '.utf8_decode('1. CONDICIÓN DE SALUD  -  CIE-10'),'LT',0,'L',true);	
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(142,8,' '.utf8_decode('DIAGNÓSTICO'),'1',0,'C',true); 
	$pdf->Cell(48,8,' '.utf8_decode('CÓDIGO CIE-10 / DSM - IV'),'T',0,'L',true);	
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$arrdiagnostico = explode(',',$diagnostico);
	foreach ($arrdiagnostico as $valor){
		if($valor != ''){
			$queryE 	= " SELECT codigo, nombre FROM enfermedades WHERE id = ".$valor;
			$result_E 	= $mysqli->query($queryE);
			$row_E    	= $result_E->fetch_assoc();
			$codigo     = $row_E['codigo'];
			$nombre     = $row_E['nombre'];
			
			$pdf->Cell(3,10,'','LR',0,'L');
			//$pdf->Cell(142,10,utf8_decode($nombre),'1',0,'L'); 
			
			$pdf->SetFillColor(255,255,255);
			$x = $pdf->GetX() + 142; $y = $pdf->GetY();
			$pdf->MultiCell(142,5,utf8_decode($nombre),'LT','J',true);
			$pdf->SetY($y); $pdf->SetX($x);
	
			$pdf->Cell(48,10,$codigo,'LT',0,'C');  
			$pdf->Cell(3,10,'','LR',1,'L'); 
		}
	}
	
	$pdf->Cell(3,10,'','LR',0,'L');
	$pdf->Cell(142,10,'','1',0,'C'); 
	$pdf->Cell(48,10,'','T',0,'L');  
	$pdf->Cell(3,10,'','LR',1,'L'); 
	
	$pdf->Cell(3,2,'','L',0,'L');
	$pdf->Cell(190,2,'','TB',0,'L'); 
	$pdf->Cell(3,2,'','R',1,'L'); 
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(3,3,'','LR',0,'L');
	$pdf->Cell(30,3,'','LT',0,'R',true);   
	$pdf->Cell(5,3,'','TB',0,'C',true);   
	$pdf->Cell(38,3,'','T',0,'R',true);   
	$pdf->Cell(5,3,'','TB',0,'C',true);   
	$pdf->Cell(45,3,'','T',0,'R',true);   
	$pdf->Cell(5,3,'','TB',0,'C',true);    
	$pdf->Cell(50,3,'','T',0,'R',true);  
	$pdf->Cell(5,3,'','TB',0,'C',true);     
	$pdf->Cell(7,3,'','TR',0,'C',true);
	$pdf->Cell(3,3,'','LR',1,'L');
	
	if($tiposolicitud == 1){
		$ts_pv =  'X';
		$ts_rn =  '';
		$ts_re =  '';
		$ts_rc =  '';
	}elseif($tiposolicitud == 2){
		$ts_pv =  '';
		$ts_rn =  'X';
		$ts_re =  '';
		$ts_rc =  '';
	}elseif($tiposolicitud == 3){
		$ts_pv =  '';
		$ts_rn =  '';
		$ts_re =  'X';
		$ts_rc =  '';
	}elseif($tiposolicitud == 4){
		$ts_pv =  '';
		$ts_rn =  '';
		$ts_re =  '';
		$ts_rc =  'X';
	}
	
	$pdf->Cell(3,5,'','LR',0,'L');
	$pdf->Cell(30,5,'PRIMERA VEZ','LR',0,'R',true);   
	$pdf->Cell(5,5,$ts_pv,'1',0,'C');   
	$pdf->Cell(38,5,' '.utf8_decode('RENOVACIÓN'),'LR',0,'R',true);   
	$pdf->Cell(5,5,$ts_rn,'1',0,'C');   
	$pdf->Cell(45,5,' '.utf8_decode('REEVALUACIÓN'),'LR',0,'R',true);   
	$pdf->Cell(5,5,$ts_re,'1',0,'C');    
	$pdf->Cell(50,5,' '.utf8_decode('RECONSIDERACIÓN'),'LR',0,'R',true);  
	$pdf->Cell(5,5,$ts_rc,'1',0,'C');     
	$pdf->Cell(7,5,'','LR',0,'C',true);
	$pdf->Cell(3,5,'','LR',1,'L');
	
	$pdf->Cell(3,3,'','LR',0,'L');
	$pdf->Cell(30,3,'','LB',0,'R',true);   
	$pdf->Cell(5,3,'','TB',0,'C',true);   
	$pdf->Cell(38,3,'','B',0,'R',true);   
	$pdf->Cell(5,3,'','TB',0,'C',true);   
	$pdf->Cell(45,3,'','B',0,'R',true);   
	$pdf->Cell(5,3,'','TB',0,'C',true);    
	$pdf->Cell(50,3,'','B',0,'R',true);  
	$pdf->Cell(5,3,'','TB',0,'C',true);     
	$pdf->Cell(7,3,'','B',0,'C',true);
	$pdf->Cell(3,3,'','LR',1,'L');  
	
	$pdf->Cell(3,3,'','LR',0,'L'); 
	$pdf->Cell(4,3,'','L',0,'R'); 
	$pdf->Cell(35,3,'','T',0,'R');   
	$pdf->Cell(8,3,'','T',0,'C');  
	$pdf->Cell(4,4,'','T',0,'R');	
	$pdf->Cell(36,3,'','T',0,'R');   
	$pdf->Cell(8,3,'','T',0,'C');   
	$pdf->Cell(5,3,'','T',0,'R');
	$pdf->Cell(25,3,'','T',0,'R');   
	$pdf->Cell(8,3,'','TB',0,'C'); 
	$pdf->Cell(5,4,'','T',0,'R');	
	$pdf->Cell(40,3,'','T',0,'R');  
	$pdf->Cell(8,3,'','TB',0,'C');     
	$pdf->Cell(4,3,'','TR',0,'C');
	$pdf->Cell(3,3,'','LR',1,'L');
	
	$cm = ''; 		$rhc = ''; 		$hc = ''; 		$ec = '';
	$arrdocumentos = explode(',',$documentos);
	
	foreach ($arrdocumentos as $valor){		
		if($valor == '1'){
			$cm = 'X';
		}
		if($valor == '2'){
			$rhc = 'X';
		}
		if($valor == '3'){
			$hc = 'X';
		}
		if($valor == '4'){
			$ec = 'X';
		}		
	}
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(3,5,'','LR',0,'L'); 	
	$pdf->Cell(4,5,'','LR',0,'R');
	$pdf->Cell(35,5,' '.utf8_decode('CERTIFICADO MÉDICO'),'1',0,'R',true);
	$pdf->Cell(8,5,$cm,'1',0,'C');   
	$pdf->Cell(4,5,'','LR',0,'R');   
	$pdf->Cell(36,5,' '.utf8_decode('RESUMEN H. CLÍNICA'),'1',0,'R',true);
	$pdf->Cell(8,5,$rhc,'1',0,'C');   
	$pdf->Cell(5,5,'','LR',0,'L');   
	$pdf->Cell(25,5,' '.utf8_decode('HIST. CLÍNICA'),'1',0,'R',true);
	$pdf->Cell(8,5,$hc,'1',0,'C');    
	$pdf->Cell(5,5,'','LR',0,'R');  
	$pdf->Cell(40,5,'EST. COMPLEMENTARIOS','1',0,'R',true);
	$pdf->Cell(8,5,$ec,'1',0,'C');     
	$pdf->Cell(4,5,'','LR',0,'C');
	$pdf->Cell(3,5,'','LR',1,'L');
	
	$pdf->Cell(3,3,'','LR',0,'L'); 
	$pdf->Cell(4,3,'','LB',0,'R'); 
	$pdf->Cell(35,3,'','TB',0,'R');   
	$pdf->Cell(8,3,'','TB',0,'C');  
	$pdf->Cell(4,3,'','B',0,'R');	
	$pdf->Cell(36,3,'','TB',0,'R');   
	$pdf->Cell(8,3,'','TB',0,'C');   
	$pdf->Cell(5,3,'','B',0,'R');
	$pdf->Cell(25,3,'','TB',0,'R');   
	$pdf->Cell(8,3,'','TB',0,'C'); 
	$pdf->Cell(5,3,'','B',0,'R');	
	$pdf->Cell(40,3,'','TB',0,'R');  
	$pdf->Cell(8,3,'','TB',0,'C');     
	$pdf->Cell(4,3,'','RB',0,'C');
	$pdf->Cell(3,3,'','LR',1,'L');
	
	//2
	$pdf->Cell(0,4,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','L',0,'L');	
	$pdf->Cell(105,8,' '.utf8_decode(' 2. FECHA DE INICIACIÓN DEL DAÑO'),'1',0,'L',true);
	$arrfechainiciodano = explode('-',$fechainiciodano);
	$mesfid 	= $arrfechainiciodano[0];
	$yearfid 	= $arrfechainiciodano[1];
	$mesfid1  	= substr($mesfid, -2,1);		$mesfid2  = substr($mesfid, -1,1);
	$yearfid1  	= substr($yearfid, -4,1);		$yearfid2  = substr($yearfid, -3,1);		$yearfid3  = substr($yearfid, -2,1);		$yearfid4  = substr($yearfid, -1,1);
	$pdf->Cell(88,8,'','R',1,'L');
	
	$pdf->SetFillColor(214,216,215);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(3,8,'','L',0,'L');	
	$pdf->Cell(24,8,'  MES   (mm)','1',0,'L',true);
	$pdf->Cell(8,8,$mesfid1,'1',0,'C');
	$pdf->Cell(8,8,$mesfid2,'1',0,'C');
	$pdf->Cell(33,8,' '.utf8_decode('    AÑO   (aaaa)'),'1',0,'L',true);
	$pdf->Cell(8,8,$yearfid1,'1',0,'C');
	$pdf->Cell(8,8,$yearfid2,'1',0,'C');
	$pdf->Cell(8,8,$yearfid3,'1',0,'C');
	$pdf->Cell(8,8,$yearfid4,'1',0,'C');
	$pdf->Cell(88,8,'','R',1,'L');
	
	//3
	$pdf->Cell(0,4,'','LR',1,'L'); 
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');	
	$pdf->Cell(190,8,' '.utf8_decode('3. AYUDAS TÉCNICAS'),'LTB',0,'L',true); 
    $pdf->Cell(3,8,'','LR',1,'L');
	
	$atsillar 	= ''; 		$atortesis = ''; 		$atandadores = ''; 		$atprotesis = '';
	$atbastones = ''; 		$atayudaso = ''; 		$ataudifonos = ''; 		$atotros = ''; 		$atotrosesp = '';
	$arrayudast = explode(',',$ayudastecnicas);
	
	foreach ($arrayudast as $valor){
		//echo '.'.$valor.'.';
		if($valor == 'Silla de ruedas'){
			$atsillar = 'X';
		}
		if(utf8_decode($valor) == 'Órtesis'){
			$atortesis = 'X';
		}
		if($valor == 'Andadores'){
			$atandadores = 'X';
		}
		if(utf8_decode($valor == 'Prótesis')){
			$atprotesis = 'X';
		}
		if($valor == 'Bastones'){
			$atbastones = 'X';
		}
		if(utf8_decode($valor == 'Ayudas ópticas')){
			$atayudaso = 'X';
		}
		if(utf8_decode($valor == 'Audífonos')){
			$ataudifonos = 'X';
		}
		if($valor == 'Otros'){
			$atotros = 'X';
			$atotrosesp = $ayudastecnicasot;
		}
	}
	
	$pdf->Cell(3,3,'','LR',0,'L');	
	$pdf->Cell(190,3,'',0,'L'); 
    $pdf->Cell(3,3,'','LR',1,'L');	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(3,6,'','LR',0,'L'); 	
	$pdf->Cell(4,6,'','R',0,'R');
	$pdf->Cell(35,6,'SILLA DE RUEDAS','1',0,'L',true);
	$pdf->Cell(8,6,$atsillar,'1',0,'C');   
	$pdf->Cell(4,6,'','LR',0,'R');   
	$pdf->Cell(36,6,' '.utf8_decode('ÓRTESIS'),'1',0,'L',true);
	$pdf->Cell(8,6,$atortesis,'1',0,'C');   
	$pdf->Cell(5,6,'','LR',0,'L');   
	$pdf->Cell(25,6,'ANDADORES','1',0,'L',true);
	$pdf->Cell(8,6,$atandadores,'1',0,'C');    
	$pdf->Cell(5,6,'','LR',0,'R');  
	$pdf->Cell(40,6,' '.utf8_decode('PRÓTESIS'),'1',0,'L',true);
	$pdf->Cell(8,6,$atprotesis,'1',0,'C');     
	$pdf->Cell(4,6,'','',0,'C');
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(3,3,'','LR',0,'L');
	$pdf->Cell(190,3,'',0,'L');
    $pdf->Cell(3,3,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L'); 	
	$pdf->Cell(4,6,'','R',0,'R');
	$pdf->Cell(35,6,'BASTONES','1',0,'L',true);
	$pdf->Cell(8,6,$atbastones,'1',0,'C');   
	$pdf->Cell(4,6,'','LR',0,'R');   
	$pdf->Cell(36,6,' '.utf8_decode('AYUDAS ÓPTICAS'),'1',0,'L',true);
	$pdf->Cell(8,6,$atayudaso,'1',0,'C');   
	$pdf->Cell(5,6,'','LR',0,'L');   
	$pdf->Cell(25,6,' '.utf8_decode('AUDÍFONOS'),'1',0,'L',true);
	$pdf->Cell(8,6,$ataudifonos,'1',0,'C');    
	$pdf->Cell(5,6,'','',0,'R');  
	$pdf->Cell(48,6,'','',0,'L');    
	$pdf->Cell(4,6,'','',0,'C');
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(3,3,'','LR',0,'L');
	$pdf->Cell(190,3,'',0,'L');
    $pdf->Cell(3,3,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L'); 	
	$pdf->Cell(4,6,'','R',0,'R');
	$pdf->Cell(35,6,'OTROS','1',0,'L',true);
	$pdf->Cell(8,6,$atotros,'1',0,'C');   
	$pdf->Cell(4,6,'','LR',0,'R');   
	$pdf->Cell(22,6,' '.utf8_decode('ESPECIFÍQUE:'),'LTB',0,'L');
	$pdf->Cell(114,6,$atotrosesp,'TBR',0,'L');
	$pdf->Cell(3,6,'','',0,'L');
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(3,3,'','LR',0,'L');
	$pdf->Cell(190,3,'','B','L');
    $pdf->Cell(3,3,'','LR',1,'L');

	//4	
	$pdf->Cell(0,4,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');	
	$pdf->Cell(190,8,' '.utf8_decode('4. EDUCACIÓN'),'LTB',0,'L',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$alfabetizado 	= ''; 		$analfabeto = ''; 		$analfabetoi = ''; 		$noaplica = '';
	if($alfabetismo == '1'){
		$alfabetizado = 'X';
	}
	if($alfabetismo == '2'){
		$analfabeto = 'X';
	}
	if($alfabetismo == '3'){
		$analfabetoi = 'X';
	}
	if($alfabetismo == '4'){
		$noaplica = 'X';
	}
	
	$inicialc 	= ''; 			$inicialic = ''; 		$inicialich = ''; 		$inicialac = '';
	if($niveleducacional == '1'){
		if($niveleducacionalc == '1'){
			$inicialc = 'X';
		}
		if($niveleducacionalc == '2'){
			$inicialic = 'X';
		}
		if($niveleducacionalc == '3'){
			$inicialich = 'X';
		}
		if($niveleducacionalc == '4'){
			$inicialac = 'X';
		}
	}
	
	$primarioc 	= ''; 			$primarioic = ''; 		$primarioich = ''; 		$primarioac = '';
	if($niveleducacional == '2'){
		if($niveleducacionalc == '1'){
			$primarioc = 'X';
		}
		if($niveleducacionalc == '2'){
			$primarioic = 'X';
		}
		if($niveleducacionalc == '3'){
			$primarioich = 'X';
		}
		if($niveleducacionalc == '4'){
			$primarioac = 'X';
		}
	}
	
	$secundarioc 	= ''; 			$secundarioic = ''; 		$secundarioich = ''; 		$secundarioac = '';
	if($niveleducacional == '3'){
		if($niveleducacionalc == '1'){
			$secundarioc = 'X';
		}
		if($niveleducacionalc == '2'){
			$secundarioic = 'X';
		}
		if($niveleducacionalc == '3'){
			$secundarioich = 'X';
		}
		if($niveleducacionalc == '4'){
			$secundarioac = 'X';
		}
	}
	
	$terciarioc 	= ''; 			$terciarioic = ''; 		$terciarioich = ''; 		$terciarioac = '';
	if($niveleducacional == '4'){
		if($niveleducacionalc == '1'){
			$terciarioc = 'X';
		}
		if($niveleducacionalc == '2'){
			$terciarioic = 'X';
		}
		if($niveleducacionalc == '3'){
			$terciarioich = 'X';
		}
		if($niveleducacionalc == '4'){
			$terciarioac = 'X';
		}
	}
	
	$pdf->SetFillColor(255,255,255);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(3,12,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(11,12,'','LB',0,'C');
	$pdf->MultiCell(40,6,'MARCAR CON UNA CRUZ LO QUE CORRESPONDA','TB',0,'C');
	$pdf->SetY($y); $pdf->SetX($x+50);
	$pdf->Cell(11,12,'','B',0,'C');
	$pdf->Cell(6,12,'','LR',0,'L');
	$pdf->Cell(49,12,'NIVELES EDUCACIONALES','1',0,'C');
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(20,12,'COMPLETO','1',0,'C',true);
	$pdf->SetFont('Arial','B',6.4);
	$pdf->Cell(33,4,'INCOMPLETO','1',0,'C',true);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(21,6,'ADECUACIONES CURRICULARES','1',0,'C',true);
	$pdf->SetY($y); $pdf->SetX($x+21);
	$pdf->Cell(3,12,'','LR',1,'L');
	$pdf->SetY($y+4); $pdf->SetX($x-33);
	$pdf->Cell(15,8,'CONCURRE','1',1,'C',true);
	$pdf->SetY($y+4); $pdf->SetX($x-18);
	$pdf->MultiCell(18,2.65,' '.utf8_decode('CONCURRIÓ HASTA QUE NIVEL ESC.'),'1',1,'C',true);
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(3,1.5,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(61,1.5,'','L',1,'L');
	$pdf->Cell(3,6,'','LR',0,'L');	
	$pdf->Cell(50,6,'ALFABETIZADO                      SI','L',0,'L');
	$pdf->Cell(8,6,$alfabetizado,'1',0,'C');
	$pdf->Cell(3,6,'','R',0,'L');
	$pdf->SetY($y); $pdf->SetX($x+61);
	$pdf->Cell(6,9,'','LR',0,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(49,9,'NIVEL INICIAL','1',0,'L',true);
	$pdf->Cell(20,9,$inicialc,'1',0,'C');
	$pdf->Cell(15,9,$inicialic,'1',0,'C');
	$pdf->Cell(18,9,$inicialich,'1',0,'C');
	$pdf->Cell(21,9,$inicialac,'1',0,'C');
	$pdf->Cell(3,9,'','LR',1,'L');
	
	$y = $pdf->GetY();
	$pdf->SetFont('Arial','B',9);
	$pdf->SetY($y-1.5);
	$pdf->Cell(3,1.5,'','LR',0,'L');	
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(61,1.5,'','BL',1,'L');	
	$pdf->Cell(3,1.5,'','LR',0,'L');	
	$pdf->Cell(61,1.5,'','L',1,'L');	
	$pdf->Cell(3,6,'','LR',0,'L');	
	$pdf->Cell(50,6,'ANALFABETO                         SI','L',0,'L');
	$pdf->Cell(8,6,$analfabeto,'1',0,'C');
	$pdf->SetY($y+1.5); $pdf->SetX($x+58);
	$pdf->Cell(3,9,'','R',0,'L');	
	$pdf->Cell(6,9,'','LR',0,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(49,9,'NIVEL PRIMARIO','1',0,'L',true);
	$pdf->Cell(20,9,$primarioc,'1',0,'C');
	$pdf->Cell(15,9,$primarioic,'1',0,'C');
	$pdf->Cell(18,9,$primarioich,'1',0,'C');
	$pdf->Cell(21,9,$primarioac,'1',0,'C');
	$pdf->Cell(3,9,'','LR',1,'L');
	
	$y = $pdf->GetY();
	$pdf->SetFont('Arial','B',9);
	$pdf->SetY($y-1.5);
	$pdf->Cell(3,1.5,'','LR',0,'L');	
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(61,1.5,'','BL',1,'L');	
	$pdf->Cell(3,1.5,'','LR',0,'L');	
	$pdf->Cell(61,1.5,'','L',1,'L');	
	$pdf->Cell(3,6,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(255,255,255);
	$pdf->MultiCell(28,3,'ANALFABETO INSTRUMENTAL','L',1,'L',true);
	$pdf->SetFillColor(214,216,215);
	$pdf->SetY($y); $pdf->SetX($x+28);
	$pdf->Cell(22,6,'SI ','',0,'R');
	$pdf->Cell(8,6,$analfabetoi,'1',0,'C');
	$pdf->SetY($y-1.5); $pdf->SetX($x+58);
	$pdf->Cell(3,9,'','R',0,'L');	
	$pdf->Cell(6,9,'','LR',0,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(49,9,'NIVEL SECUNDARIO','1',0,'L',true);
	$pdf->Cell(20,9,$secundarioc,'1',0,'C');
	$pdf->Cell(15,9,$secundarioic,'1',0,'C');
	$pdf->Cell(18,9,$secundarioich,'1',0,'C');
	$pdf->Cell(21,9,$secundarioac,'1',0,'C');
	$pdf->Cell(3,9,'','LR',1,'L');	
	
	$y = $pdf->GetY();
	$pdf->SetFont('Arial','B',9);
	$pdf->SetY($y-1.5);
	$pdf->Cell(3,1.5,'','LR',0,'L');	
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(61,1.5,'','BL',1,'L');	
	$pdf->Cell(3,1.5,'','LR',0,'L');	
	$pdf->Cell(61,1.5,'','L',1,'L');	
	$pdf->Cell(3,6,'','LR',0,'L');	
	$pdf->Cell(50,6,'NO APLICABLE                       SI','L',0,'L');
	$pdf->Cell(8,6,$noaplica,'1',0,'C');
	$pdf->SetY($y+1.5); $pdf->SetX($x+58);
	$pdf->Cell(3,9,'','R',0,'L');	
	$pdf->Cell(6,9,'','LR',0,'L');
	$pdf->SetFont('Arial','B',7.7);
	$pdf->Cell(49,9,'NIVEL TERCIARIO / UNIVERSITARIO','1',0,'L',true);
	$pdf->Cell(20,9,$terciarioc,'1',0,'C');
	$pdf->Cell(15,9,$terciarioic,'1',0,'C');
	$pdf->Cell(18,9,$terciarioich,'1',0,'C');
	$pdf->Cell(21,9,$terciarioac,'1',0,'C');
	$pdf->Cell(3,9,'','LR',1,'L');
	
	$y = $pdf->GetY();
	$pdf->SetFont('Arial','B',7);
	$pdf->SetY($y-1.5);
	$pdf->Cell(3,1.5,'','LR',0,'L');	
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(61,1.5,'','BL',1,'L');
	
	$educacionnfc = '';	$educacionnfccc = '';	$educacionnfnc = '';	$educacionec = '';	$educacioneccc = '';	$educacionenc = '';
	$arrtipoeducacion = explode(',',$tipoeducacion);
	foreach ($arrtipoeducacion as $valor){
		//echo $valor;
		if($valor == '1'){
			if($concurrenciatipoed == '1'){
				$educacionnfc = 'X';
			}
			if($concurrenciatipoed == '2'){
				$educacionnfccc = 'X';
			}
			if($concurrenciatipoed == '3'){
				$educacionnfnc = 'X';
			}
		}
		if($valor == '2'){
			if($concurrenciatipoed == '1'){
				$educacionec = 'X';
			}
			if($concurrenciatipoed == '2'){
				$educacioneccc = 'X';
			}
			if($concurrenciatipoed == '3'){
				$educacionenc = 'X';
			}
		}
	}
	
	$pdf->Cell(0,4,'','LR',1,'L');
	$pdf->Cell(3,8,'','L',0,'L');
	$pdf->Cell(40,8,'','1',0,'L',true);
	$pdf->Cell(18,8,'CONCURRE','1',0,'C',true);
	$pdf->Cell(19,8,' '.utf8_decode('CONCURRIÓ'),'1',0,'C',true);
	$pdf->Cell(28,8,' '.utf8_decode('NUNCA CONCURRIÓ'),'1',0,'C',true);
	$pdf->Cell(27,8,'COMPLETAR CON:','',0,'R');
	$pdf->Cell(58,8,' '.utf8_decode('A - EDUCACIÓN ANTES DEL DAÑO'),'',0,'L');
	$pdf->Cell(3,8,'','R',1,'L');
	
	$pdf->Cell(3,8,'','L',0,'L');
	$pdf->Cell(40,8,' '.utf8_decode('EDUCACIÓN NO FORMAL'),'1',0,'L',true);
	$pdf->Cell(18,8,$educacionnfc,'1',0,'C');
	$pdf->Cell(19,8,$educacionnfccc,'1',0,'C');
	$pdf->Cell(28,8,$educacionnfnc,'1',0,'C');
	$pdf->Cell(27,8,'','',0,'R');
	$pdf->Cell(58,8,' '.utf8_decode('D - EDUCACIÓN DESPUÉS DEL DAÑO'),'',0,'L');
	$pdf->Cell(3,8,'','R',1,'L');
	
	$pdf->Cell(3,8,'','L',0,'L');
	$pdf->Cell(40,8,' '.utf8_decode('EDUCACIÓN ESPECIAL'),'1',0,'L',true);
	$pdf->Cell(18,8,$educacionec,'1',0,'C');
	$pdf->Cell(19,8,$educacioneccc,'1',0,'C');
	$pdf->Cell(28,8,$educacionenc,'1',0,'C');
	$pdf->Cell(27,8,'','',0,'R');
	$pdf->Cell(58,8,' '.utf8_decode('AD - EDUCACIÓN ANTES Y DESPUÉS DEL DAÑO'),'',0,'L');
	$pdf->Cell(3,8,'','R',1,'L');	
	$pdf->Cell(0,4,'','LRB',1,'L');
	
	//5
	$pdf->AddPage();
	$pdf->Cell(0,4,'','LRT',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');	
	$pdf->Cell(190,8,'5. ASPECTO HABITACIONAL','LTB',0,'L',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	$pdf->SetFillColor(214,216,215);
	
	//CONVIVENCIA
	$cvivesolo = '';		$viveac = '';		$cinternado = '';
	if($convivencia == 1){
		$cvivesolo = 'X';
	}elseif($convivencia == 2){
		$viveac = 'X';
	}elseif($convivencia == 3){
		$cinternado = 'X';
	}
	
	$pdf->SetFont('Arial','B',7);
	$yvs = $pdf->GetY(); $xvs = $pdf->GetX();
	$pdf->Cell(3,2,'','LR',0,'L');	
	$pdf->Cell(29,2,'','LT',0,'L',true);
	$pdf->Cell(3,2,'','RT',1,'L',true);
	$pdf->Cell(3,5,'','LR',0,'L');
	$pdf->Cell(23,5,'VIVE SOLO','L',0,'C',true);
	$pdf->Cell(6,5,$cvivesolo,'1',0,'C');
	$pdf->Cell(3,5,'','LR',1,'L',true);
	
	$pdf->Cell(3,3,'','LR',0,'L');
	$pdf->Cell(23,3,'','L',0,'L',true);	
	$pdf->Cell(6,3,'','T',0,'L',true);
	$pdf->Cell(3,3,'','R',1,'L',true);
	
	$pdf->Cell(3,5,'','L',0,'L');	
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(23,2.5,' '.utf8_decode('VIVE ACOMPAÑADO'),'L','C',true);
	$pdf->SetY($y); $pdf->SetX($x+23);
	$pdf->Cell(6,5,$viveac,'LRT',0,'C');
	$pdf->Cell(3,5,'','LR',1,'L',true);
	
	$pdf->Cell(3,3,'','L',0,'L');	
	$pdf->Cell(23,3,'','L',0,'L',true);	
	$pdf->Cell(6,3,'','T',0,'L',true);
	$pdf->Cell(3,3,'','R',1,'L',true);
	
	$pdf->Cell(3,5,'','L',0,'L');	
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(23,2.5,'INTERNADO/ ALBERGUES','L','C',true);
	$pdf->SetY($y); $pdf->SetX($x+23);
	$pdf->Cell(6,5,$cinternado,'1',0,'C');
	$pdf->Cell(3,5,'','LR',1,'L',true);
	
	$pdf->Cell(3,3,'','L',0,'L');	
	$pdf->Cell(23,3,'','LB',0,'L',true);	
	$pdf->Cell(6,3,'','TB',0,'L',true);
	$pdf->Cell(3,3,'','RB',1,'L',true);
	
	//TIPO DE VIVIENDA
	$tvsi = ''; 	$tvno = '';
	if($tipovivienda == 1){
		$tvsi = 'X';
	}elseif($tipovivienda == 2){
		$tvno = 'X';
	}
	//VIVIENDA ADAPTADA
	$vasi = ''; 	$vano = '';
	if($viviendaadaptada == 1){
		$vasi = 'X';
	}elseif($viviendaadaptada == 2){
		$vano = 'X';
	}
	//MEDIOS DE TRANSPORTE
	$mtmenos = ''; 	$mtmas = '';
	if($mediotransporte == 1){
		$mtmenos = 'X';
	}elseif($mediotransporte == 2){
		$mtmas = 'X';
	}	
	
	$pdf->SetY($yvs); $pdf->SetX($xvs+35);
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(3,5,'','',0,'L');
	$pdf->SetFont('Arial','B',8);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(58,4,'VIVIENDA','1',0,'L',true);
	$pdf->SetFont('Arial','B',7);
	$pdf->MultiCell(20,3,'CANTIDAD DE CUARTOS DE LA VIVIENDA','LRT','C',true);
	$pdf->SetY($y+4); $pdf->SetX($x);
	$pdf->SetFillColor(214,216,215);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(40,4,'','TL',0,'L',true);
	$pdf->Cell(6,4,utf8_decode('SÍ'),'T',0,'C',true);
	$pdf->Cell(3,4,'','T',0,'C',true);
	$pdf->Cell(6,4,'NO','TB',0,'C',true);
	$pdf->Cell(3,4,'','TR',1,'C',true);
	$pdf->SetY($y+4); $pdf->SetX($x);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(40,3,' '.utf8_decode('CON INFRAESTRUCTURA BÁSICA (SERVICIOS)'),'L',1,'C',true);
	$pdf->SetY($y); $pdf->SetX($x+40);
	$pdf->Cell(6,6,$tvsi,'1',0,'C');
	$pdf->Cell(3,6,'','L',0,'C',true);
	$pdf->Cell(6,6,$tvno,'1',0,'C');
	$pdf->Cell(3,6,'','RL',1,'C',true);
	$pdf->Cell(38);
	$pdf->Cell(58,2,'','RL',1,'C',true);
	$pdf->Cell(38);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(40,3,' '.utf8_decode('VIVIENDA ADAPTADA A LA SITUACIÓN DE LA PERSONA CON DISCAPACIDAD'),'L',1,'C',true);
	$pdf->SetY($y); $pdf->SetX($x+40);
	$pdf->Cell(6,4,utf8_decode('SÍ'),'',0,'C',true);
	$pdf->Cell(3,4,'','',0,'C',true);
	$pdf->Cell(6,4,'NO','',0,'C',true);
	$pdf->Cell(3,4,'','R',1,'C',true);
	$pdf->SetY($y+4); $pdf->SetX($x+40);
	$pdf->Cell(6,5,$vasi,'1',0,'C');
	$pdf->Cell(3,5,'','RL',0,'C',true);
	$pdf->Cell(6,5,$vano,'1',0,'C');
	$pdf->Cell(3,5,'','RL',1,'C',true);
	$pdf->SetY($y+9); $pdf->SetX($x);
	$pdf->Cell(40,1,'','LB',0,'C',true);
	$pdf->Cell(6,1,'','TB',0,'C',true);
	$pdf->Cell(3,1,'','B',0,'C',true);
	$pdf->Cell(6,1,'','TB',0,'C',true);
	$pdf->Cell(3,1,'','BR',1,'C',true);
	$pdf->SetY($yvs+9); $pdf->SetX($xvs+96);
	$pdf->SetFillColor(178,179,183);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->Cell(1,16,'','R',0,'C',true);
	$pdf->Cell(18,16,$cantidadhabitaciones,'1',0,'C'); // CANTIDAD DE CUARTOS
	$pdf->Cell(1,16,'','LR',0,'C',true);
	$pdf->SetY($y+16); $pdf->SetX($x);
	$pdf->Cell(1,1,'','B',0,'C',true);
	$pdf->Cell(18,1,'','TB',0,'C',true);
	$pdf->Cell(1,1,'','RB',0,'C',true);
	$pdf->SetY($yvs); $pdf->SetX($xvs+116);
	$pdf->Cell(3,4,'','LRT',0,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(74,4,'ACCESIBILIDAD','1',0,'C',true);
	$pdf->Cell(3,4,'','LR',1,'C');
	$pdf->SetFont('Arial','B',7);
	$pdf->SetY($yvs+4); $pdf->SetX($xvs+116);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(3,4,'','LR',0,'C');
	$pdf->Cell(74,4,'MEDIOS DE TRANSPORTE','LRT',0,'C',true);
	$pdf->Cell(3,4,'','LR',1,'C');
	$pdf->SetY($yvs+8); $pdf->SetX($xvs+116);
	$pdf->Cell(3,6,'','LR',0,'C');
	$pdf->Cell(8,6,'','L',0,'C',true);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(18,3,'MENOS DE 300 METROS','','C',true);
	$pdf->SetY($y); $pdf->SetX($x+18);
	$pdf->Cell(2,6,'','',0,'C',true);
	$pdf->Cell(6,6,$mtmenos,'1',0,'C');
	$pdf->Cell(2,6,'','L',0,'C',true);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(18,3,utf8_decode('MÁS DE 300 METROS'),'','C',true);
	$pdf->SetY($y); $pdf->SetX($x+18);
	$pdf->Cell(2,6,'','',0,'C',true);
	$pdf->Cell(6,6,$mtmas,'1',0,'C');
	$pdf->Cell(12,6,'','LR',0,'C',true);
	$pdf->Cell(3,6,'','LR',1,'C');
	
	$pdf->SetY($yvs+14); $pdf->SetX($xvs+119);
	$pdf->Cell(74,1,'','LRB',0,'C',true);
	$pdf->Cell(3,1,'','LR',1,'C');
	
	$pdf->SetY($yvs+15); $pdf->SetX($xvs+119);
	$pdf->Cell(74,4,'ESTADOS DE LAS CALLES','TLR',0,'C',true);
	$pdf->Cell(3,4,'','LR',1,'C');
	
	//ESTADOS DE LAS CALLES
	$ecpavimento = ''; 	$ecmejorado = ''; 	$ectierra = '';
	if($estadocalles == 1){
		$ecpavimento = 'X';
	}elseif($estadocalles == 2){
		$ecmejorado = 'X';
	}elseif($estadocalles == 3){
		$ectierra = 'X';
	}
	
	$pdf->SetY($yvs+19); $pdf->SetX($xvs+116);
	$pdf->Cell(3,6,'','LR',0,'C');
	$pdf->Cell(1,6,'','L',0,'C',true);
	$pdf->Cell(17,6,'PAVIMENTO','',0,'C',true);
	$pdf->Cell(1,6,'','',0,'C',true);
	$pdf->Cell(6,6,$ecpavimento,'1',0,'C');
	$pdf->Cell(1,6,'','L',0,'C',true);
	$pdf->Cell(16,6,'MEJORADO','',0,'C',true);
	$pdf->Cell(1,6,'','',0,'C',true);
	$pdf->Cell(6,6,$ecmejorado,'1',0,'C');
	$pdf->Cell(1,6,'','L',0,'C',true);
	$pdf->Cell(12,6,'TIERRA','',0,'C',true);
	$pdf->Cell(1,6,'','',0,'C',true);
	$pdf->Cell(6,6,$ectierra,'1',0,'C');
	$pdf->Cell(5,6,'','LR',0,'C',true);
	$pdf->Cell(3,6,'','LR',1,'C');
	
	$pdf->SetY($yvs+25); $pdf->SetX($xvs+119);
	$pdf->Cell(19,1,'','LB',0,'C',true);
	$pdf->Cell(6,1,'','BT',0,'C',true);
	$pdf->Cell(18,1,'','B',0,'C',true);
	$pdf->Cell(6,1,'','BT',0,'C',true);
	$pdf->Cell(14,1,'','B',0,'C',true);
	$pdf->Cell(6,1,'','BT',0,'C',true);
	$pdf->Cell(5,1,'','BR',0,'C',true);
	$pdf->Cell(3,1,'','LR',1,'C');
	
	$pdf->Cell(0,4,'','LR',1,'L');
	
	//6
	$pdf->SetFont('Arial','B',10);	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(190,8,' '.utf8_decode('6. SITUACIÓN SOCIO - FAMILIAR'),'LT',0,'L',true);  
    $pdf->Cell(3,8,'','LR',1,'L');
	
	$arrvinculos = explode(',',$vinculos);
	$vhijo = '';		$vmadre = '';		$vhermano = '';		$vconyuge = '';		$vpadre = '';		$vabuelo = '';		$votrof = '';		$votronof = '';
	foreach($arrvinculos as $valor){
		if($valor == 1){
			$vhijo = 'X';
		}
		if($valor == 2){
			$vmadre = 'X';
		}
		if($valor == 3){
			$vhermano = 'X';
		}
		if($valor == 4){
			$vconyuge = 'X';
		}
		if($valor == 5){
			$vpadre = 'X';
		}
		if($valor == 6){
			$vabuelo = 'X';
		}
		if($valor == 7){
			$votrof = 'X';
		}
		if($valor == 8){
			$votronof = 'X';
		}
	}
	
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(20,2,'','TLR',0,'R',true);
	$pdf->SetFillColor(214,216,215);	
	$pdf->Cell(20,2,'','LT',0,'R',true);
	$pdf->Cell(11,2,'','TB',0,'C',true);
	$pdf->Cell(28,2,'','T',0,'R',true);   
	$pdf->Cell(11,2,'','TB',0,'C',true);   
	$pdf->Cell(31,2,'','T',0,'R',true);   
	$pdf->Cell(11,2,'','TB',0,'C',true);    
	$pdf->Cell(40,2,'','T',0,'R',true);  
	$pdf->Cell(11,2,'','TB',0,'C',true);     
	$pdf->Cell(7,2,'','TR',0,'C',true);
	$pdf->Cell(3,2,'','LR',1,'L');

	$pdf->Cell(3,13,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(20,13,' '.utf8_decode('VÍNCULO'),'LRB',0,'C',true); 
	$pdf->SetFillColor(214,216,215);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(20,5,'HIJO ','LR',0,'R',true);   
	$pdf->Cell(11,5,$vhijo,'1',0,'C');   
	$pdf->Cell(28,5,'MADRE','LR',0,'R',true);   
	$pdf->Cell(11,5,$vmadre,'1',0,'C');   
	$pdf->Cell(31,5,'HERMANO','LR',0,'R',true);   
	$pdf->Cell(11,5,$vhermano,'1',0,'C');    
	$pdf->Cell(40,5,'OTROS FAMILIARES','LR',0,'R',true);  
	$pdf->Cell(11,5,$votrof,'1',0,'C');     
	$pdf->Cell(7,5,'','LR',0,'C',true);
	$pdf->Cell(3,5,'','LR',1,'L');	
	
	$pdf->SetY($y+5); $pdf->SetX($x+20);
	$pdf->Cell(20,2,' ','L',0,'R',true);
	$pdf->Cell(11,2,'','T',0,'C',true);   
	$pdf->Cell(28,2,'','',0,'R',true);   
	$pdf->Cell(11,2,'','T',0,'C',true);   
	$pdf->Cell(31,2,'','',0,'R',true);   
	$pdf->Cell(11,2,'','T',0,'C',true);    
	$pdf->Cell(40,2,'','',0,'R',true);  
	$pdf->Cell(11,2,'','T',0,'C',true);     
	$pdf->Cell(7,2,'','R',0,'C',true);
	$pdf->Cell(3,2,'','LR',1,'L');	
	
	$pdf->SetY($y+7); $pdf->SetX($x+20);
	$pdf->Cell(20,5,' '.utf8_decode('CÓNYUGE '),'LR',0,'R',true);
	$pdf->Cell(11,5,$vconyuge,'1',0,'C');   
	$pdf->Cell(28,5,'PADRE','LR',0,'R',true);   
	$pdf->Cell(11,5,$vpadre,'1',0,'C');   
	$pdf->Cell(31,5,'ABUELOS','LR',0,'R',true);   
	$pdf->Cell(11,5,$vabuelo,'1',0,'C');    
	$pdf->Cell(40,5,'OTROS NO FAMILIARES','LR',0,'R',true);  
	$pdf->Cell(11,5,$votronof,'1',0,'C');     
	$pdf->Cell(7,5,'','LR',0,'C',true);
	$pdf->Cell(3,5,'','LR',1,'L');
	
	$pdf->SetY($y+11); $pdf->SetX($x+20);
	$pdf->Cell(20,2,' ','LB',0,'R',true);
	$pdf->Cell(11,2,'','TB',0,'C',true);   
	$pdf->Cell(28,2,'','B',0,'R',true);   
	$pdf->Cell(11,2,'','TB',0,'C',true);   
	$pdf->Cell(31,2,'','B',0,'R',true);   
	$pdf->Cell(11,2,'','TB',0,'C',true);    
	$pdf->Cell(40,2,'','B',0,'R',true);  
	$pdf->Cell(11,2,'','TB',0,'C',true);     
	$pdf->Cell(7,2,'','RB',0,'C',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(0,4,'','LR',1,'L');
	
	//7	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(95,8,'7. ETNIA: '.$etnia,'LTB',0,'L',true);  
	$pdf->Cell(95,8,' '.utf8_decode('RELIGIÓN: ').$religion,'RTB',0,'L',true); 
    $pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->Cell(0,4,'','LR',1,'L');
	
	//8
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(190,8,' '.utf8_decode('8. ACOMPAÑANTE DURANTE LA EVALUACIÓN:'),1,0,'L',true); 
    $pdf->Cell(3,8,'','LR',1,'L');
	
	$acsi = '';		$acno = '';
	if($idacompanante != 0 ){
		$acsi = 'X';
	}else{
		$acno = 'X';
	}
	
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(42,2,' ','LT',0,'R',true);
	$pdf->Cell(10,2,'','T',0,'L',true);
	$pdf->Cell(92,2,'','T',0,'R',true);
	$pdf->Cell(10,2,'','T',0,'L',true);
	$pdf->Cell(36,2,'','T',0,'L',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(42,6,' SI','LR',0,'R',true);
	$pdf->Cell(10,6,$acsi,'1',0,'C');
	$pdf->Cell(92,6,'NO','LR',0,'R',true);
	$pdf->Cell(10,6,$acno,'1',0,'C');
	$pdf->Cell(36,6,'','LR',0,'L',true);
	$pdf->Cell(3,6,'','R',1,'L');
	
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(42,2,' ','LB',0,'R',true);
	$pdf->Cell(10,2,'','TB',0,'L',true);
	$pdf->Cell(92,2,'','B',0,'R',true);
	$pdf->Cell(10,2,'','TB',0,'L',true);
	$pdf->Cell(36,2,'','B',0,'L',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(0,4,'','LR',1,'L');
	
	//9	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(190,8,' '.utf8_decode('9. SITUACIÓN SOCIO-ECONÓMICA:'),'LT',0,'L',true);  
    $pdf->SetFillColor(255,255,255);	
	$pdf->Cell(3,8,'','LR',1,'L',true);
	
	$pdf->Cell(3,8,'','LR',0,'L',true);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(190,8,'INGRESO MENSUAL:','LT',0,'L',true);  
    $pdf->Cell(3,8,'','LR',1,'L');
	
	$imumenos = '';	$imuno = ''; 	$imdos = ''; 	$imtres = ''; 	$imcuatro = ''; 	$imcinco = ''; 	$imseis = ''; 	$imsiete = '';
	$imocho = ''; 	$imnueve = ''; 	$imdiez = ''; 	$imonce = ''; 		$imdoce = ''; 	$imtrece = ''; 	$imcatorce = '';
	
	if($ingresomensual == 'MENOS DE 100'){
		$imumenos = 'X';
	}elseif($ingresomensual == '100 a 124'){
		$imuno = 'X';
	}elseif($ingresomensual == '125 a 174'){
		$imdos = 'X';
	}elseif($ingresomensual == '175 a 249'){
		$imtres = 'X';
	}elseif($ingresomensual == '250 a 399'){
		$imcuatro = 'X';
	}elseif($ingresomensual == '400 a 599'){
		$imcinco = 'X';
	}elseif($ingresomensual == '600 a 799'){
		$imseis = 'X';
	}elseif($ingresomensual == '800 a 999'){
		$imsiete = 'X';
	}elseif($ingresomensual == '1000 a 1499'){
		$imocho = 'X';
	}elseif($ingresomensual == '1500 a 1999'){
		$imnueve = 'X';
	}elseif($ingresomensual == '2000 a 2499'){
		$imdiez = 'X';
	}elseif($ingresomensual == '2500 a 2999'){
		$imonce = 'X';
	}elseif($ingresomensual == '3000 a 3999'){
		$imdoce = 'X';
	}elseif($ingresomensual == '4000 a 4999'){
		$imtrece = 'X';
	}elseif($ingresomensual == '5000 y MÁS'){
		$imcatorce = 'X';
	}
	
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(3,2,'','L',0,'C',true);   
	$pdf->Cell(7,2,'','B',0,'C',true);   
	$pdf->Cell(40,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','B',0,'C',true); 
	$pdf->Cell(39,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','B',0,'C',true);   
	$pdf->Cell(40,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','B',0,'C',true);     
	$pdf->Cell(40,2,'','R',0,'L',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(3,6,'','LR',0,'C',true);   
	$pdf->Cell(7,6,$imumenos,'1',0,'C');   
	$pdf->Cell(40,6,' MENOS DE 100','LR',0,'L',true);
	$pdf->Cell(7,6,$imuno,'1',0,'C');   
	$pdf->Cell(39,6,' 100 a 124 ','LR',0,'L',true);   
	$pdf->Cell(7,6,$imdos,'1',0,'C');   
	$pdf->Cell(40,6,' 125 a 174','LR',0,'L',true);   
	$pdf->Cell(7,6,$imtres,'1',0,'C');    
	$pdf->Cell(40,6,' 175 a 249','LR',0,'L',true);  
	$pdf->Cell(3,6,'','LR',1,'L');  
	
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(3,2,'','L',0,'C',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);   
	$pdf->Cell(40,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);
	$pdf->Cell(39,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);   
	$pdf->Cell(40,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);     
	$pdf->Cell(40,2,'','R',0,'L',true); 
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(3,6,'','LR',0,'C',true);
	$pdf->Cell(7,6,$imcuatro,'1',0,'C');
	$pdf->Cell(40,6,' 250 a 399','LR',0,'L',true);
	$pdf->Cell(7,6,$imcinco,'1',0,'C');
	$pdf->Cell(39,6,' 400 a 599','LR',0,'L',true);
	$pdf->Cell(7,6,$imseis,'1',0,'C');
	$pdf->Cell(40,6,' 600 a 799','LR',0,'L',true);
	$pdf->Cell(7,6,$imsiete,'1',0,'C');
	$pdf->Cell(40,6,' 800 a 999','LR',0,'L',true);
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(3,2,'','L',0,'C',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);   
	$pdf->Cell(40,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','T',0,'C',true); 
	$pdf->Cell(39,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','T',0,'C',true);   
	$pdf->Cell(40,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);   
	$pdf->Cell(40,2,'','R',0,'L',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(3,6,'','LR',0,'C',true);   
	$pdf->Cell(7,6,$imocho,'1',0,'C');   
	$pdf->Cell(40,6,' 1,000 a 1,499','LR',0,'L',true);   
	$pdf->Cell(7,6,$imnueve,'1',0,'C'); 
	$pdf->Cell(39,6,' 1,500 a 1,999','LR',0,'L',true);   
	$pdf->Cell(7,6,$imdiez,'1',0,'C');   
	$pdf->Cell(40,6,' 2,000 a 2,499','LR',0,'L',true);   
	$pdf->Cell(7,6,$imonce,'1',0,'C'); 
	$pdf->Cell(40,6,' 2,500 a 2,999','LR',0,'L',true);
 	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(3,2,'','L',0,'C',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);   
	$pdf->Cell(40,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','T',0,'C',true); 
	$pdf->Cell(39,2,'','',0,'L',true);   
	$pdf->Cell(7,2,'','T',0,'C',true);   
	$pdf->Cell(40,2,'','',0,'L',true);    
	$pdf->Cell(7,2,'','T',0,'C',true);   
	$pdf->Cell(40,2,'','R',0,'L',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(3,6,'','LR',0,'C',true);
	$pdf->Cell(7,6,$imdoce,'1',0,'C');
	$pdf->Cell(40,6,' 3,000 a 3,999','LR',0,'L',true);
	$pdf->Cell(7,6,	$imtrece,'1',0,'C');
	$pdf->Cell(39,6,' 4,000 a 4,999','LR',0,'L',true);
	$pdf->Cell(7,6,$imcatorce,'1',0,'C');
	$pdf->Cell(38,6,utf8_decode(' 5,000 Y MÁS'),'L',0,'L',true);
	$pdf->Cell(18,6,' TOTAL ','',0,'L',true);
	$pdf->Cell(27,6,$ingresomensualotro,'1',0,'C');
	$pdf->Cell(4,6,'','LR',0,'L',true);
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(3,2,'','LB',0,'C',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);   
	$pdf->Cell(40,2,'','B',0,'L',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true); 
	$pdf->Cell(39,2,'','B',0,'L',true);   
	$pdf->Cell(7,2,'','TB',0,'C',true);   
	$pdf->Cell(40,2,'','B',0,'L',true);    
	$pdf->Cell(16,2,'','B',0,'L',true);
	$pdf->Cell(27,2,'','TB',0,'C',true);
	$pdf->Cell(4,2,'','B',0,'L',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(0,4,'','LR',1,'L');
	
	//10
	$pdf->Cell(3,8,'','L',0,'L');
	$pdf->Cell(190,8,' '.utf8_decode('10 CODIFICACIÓN CIF'),'',0,'L');
	$pdf->Cell(3,8,'','R',1,'L');
	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(190,8,'10.1 FUNCIONES CORPORALES','1',0,'L',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(214,216,215);
	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(49,8,'',1,0,'L',true);
	$pdf->SetFillColor(178,179,183);
	
	$pdf->Cell(6,8,'N1','1',0,'C',true);   
	$pdf->Cell(12,8,'N2','1',0,'C',true);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(6,8,'N3','1',0,'R',true);   
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(5,8,'','LTR',0,'C');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(6,8,'C','1',0,'C',true); 
	$pdf->Cell(9,8,'','LTR',0,'C');
	
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(6,8,'N1','1',0,'C',true);
	$pdf->Cell(12,8,'N2','1',0,'C',true); 	
	$pdf->Cell(6,8,'N3','1',0,'C',true);	
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(5,8,'','LTR',0,'C');	
	$pdf->Cell(6,8,'C','1',0,'C',true);	
	$pdf->Cell(9,8,'','LTR',0,'C');
	
	$pdf->Cell(6,8,'N1','1',0,'C',true);	
	$pdf->Cell(12,8,'N2','1',0,'C',true); 	
	$pdf->Cell(6,8,'N3','1',0,'C',true);
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(5,8,'','LTR',0,'C');
	$pdf->Cell(6,8,'C','1',0,'C',true); 	
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$cif = json_decode($cif);	
	$grupobuno = 0;		$grupobdos = 0;		$grupobtres = 0;	$grupobcuatro = 0;		$grupobcinco = 0;		
	$grupobseis = 0;	$grupobsiete = 0;	$grupobocho = 0;	
	$b1 = 0;			$b2 = 0;			$b3 = 0;			$b4 = 0;				$b5 = 0;
	$b6 = 0;			$b7 = 0;			$b8 = 0;
	$multi = 3;
	
	if(!empty($cif)){
		foreach($cif as $clave => $valor) {
			if($clave == 'b'){
				$bi = 1;
				foreach($valor as $claveb => $valorb) {
					$grupotxt = explode(' | ',$valorb->grupotxt);
					if($grupotxt[0] == 'b1'){		$grupobuno = 1;		}
					elseif($grupotxt[0] == 'b2'){	$grupobdos = 1;		}
					elseif($grupotxt[0] == 'b3'){	$grupobtres = 1;	}
					elseif($grupotxt[0] == 'b4'){	$grupobcuatro = 1;	}
					elseif($grupotxt[0] == 'b5'){	$grupobcinco = 1;	}
					elseif($grupotxt[0] == 'b6'){	$grupobseis = 1;	}
					elseif($grupotxt[0] == 'b7'){	$grupobsiete = 1;	}
					elseif($grupotxt[0] == 'b8'){	$grupobocho = 1;	}
				}
			}
		}
	}
	//FUNCIONES MENTALES 
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(49, 7, 'FUNCIONES MENTALES            (b110 a b199)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+49); 
	$pdf->SetFillColor(215,215,215);
	if($grupobuno == 1){
		$num = 1;
		imprimirfcb($cif, $num, $b1, $pdf, $multi);
		
	}else{
		$num = 1;
		colvaciasb('3', $pdf, $num);
	}
	
	//FUNCIONES SENSORIALES
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(49, 7, 'FUNCIONES SENSORIALES          Y DOLOR (b210 a b299)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+49); 
	$pdf->SetFillColor(215,215,215);
	if($grupobdos == 1){
		$num = 2;
		imprimirfcb($cif, $num, $b2, $pdf, $multi);
	}else{
		$num = 2;
		colvaciasb('3', $pdf, $num);
	}
	
	//FUNCIONES DE LA VOZ Y EL HABLA
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(49, 7, 'FUNCIONES DE LA VOZ Y             EL HABLA (b310 a b399)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+49); 
	$pdf->SetFillColor(215,215,215);
	if($grupobtres == 1){
		$num = 3;
		imprimirfcb($cif, $num, $b3, $pdf, $multi);
	}else{
		$num = 3;
		colvaciasb('3', $pdf, $num);
	}
	
	//FUNCIONES DE LOS SISTEMAS CARDIOV., HEMAT, INMUNOL. Y RESPIRATORIO (b410 a b499)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(49, 4.6, 'FUNCIONES DE LOS SISTEMAS CARDIOV., HEMAT, INMUNOL. Y RESPIRATORIO (b410 a b499)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+49); 
	$pdf->SetFillColor(215,215,215);
	if($grupobcuatro == 1){
		$num = 4;
		imprimirfcb($cif, $num, $b4, $pdf, $multi);
	}else{
		$num = 4;
		colvaciasb('3', $pdf, $num);
	}	
	
	//FUNCIONES DE LOS SISTEMAS DIGESTIVOS, METABÓLICO Y ENDOCRINO (b510 a b599)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(49, 4.6, ' '.utf8_decode('FUNCIONES DE LOS SISTEMAS DIGESTIVOS, METABÓLICO Y ENDOCRINO (b510 a b599)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+49); 
	$pdf->SetFillColor(215,215,215);
	if($grupobcinco == 1){
		$num = 5;
		imprimirfcb($cif, $num, $b5, $pdf, $multi);
	}else{
		$num = 5;
		colvaciasb('3', $pdf, $num);
	}
	
	//FUNCIONES GENITOURINARIAS REPRODUCTIVAS (b610 a b699)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(49, 7, 'FUNCIONES GENITOURINARIAS REPRODUCTIVAS (b610 a b699)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+49); 
	$pdf->SetFillColor(215,215,215);
	if($grupobseis == 1){
		$num = 6;
		imprimirfcb($cif, $num, $b6, $pdf, $multi);
	}else{
		$num = 6;
		colvaciasb('3', $pdf, $num);
	}
	
	//FUNCIONES NEURO-MUSCULAR-ESQUELÉTICAS Y RELACIONADAS CON EL MOVIMIENTO (b710 a b799)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',7.7);
	$pdf->MultiCell(49, 4.6, ' '.utf8_decode('FUNCIONES NEURO-MUSCULAR- ESQUELÉTICAS Y RELACIONADAS CON EL MOVIMIENTO (b710 a b799)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+49); 
	$pdf->SetFillColor(215,215,215);
	if($grupobsiete == 1){
		$num = 7;
		imprimirfcb($cif, $num, $b7, $pdf, $multi);
	}else{
		$num = 7;
		colvaciasb('3', $pdf, $num);
	}
	
	//FUNCIONES DE LA PIEL Y ESTRUCTURAS RELACIONADAS (b810 a b899)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(49, 4.6, 'FUNCIONES DE LA PIEL Y ESTRUCTURAS RELACIONADAS (b810 a b899)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+49); 
	$pdf->SetFillColor(215,215,215);
	if($grupobocho == 1){
		$num = 8;
		imprimirfcb($cif, $num, $b8, $pdf, $multi);
	}else{
		$num = 8;
		colvaciasb('3', $pdf, $num);
	}
	
	$pdf->Cell(0,20,'','LRB',1,'L');
	$pdf->AddPage();
	
	$gruposuno = 0;		$gruposdos = 0;		$grupostres = 0;	$gruposcuatro = 0;		$gruposcinco = 0;		$gruposseis = 0;	$grupossiete = 0;		$gruposocho = 0;
	$s1 = 0;			$s2 = 0;			$s3 = 0;			$s4 = 0;				$s5 = 0;				$s6 = 0;			$s7 = 0;				$s8 = 0;
	$multi = 3;
	if(!empty($cif)){
		foreach($cif as $clave => $valor) {
			if($clave == 's'){
				$si = 1;
				foreach($valor as $claved => $valord) {
					$grupotxt = explode(' | ',$valord->grupotxt);
					if($grupotxt[0] == 's1'){		$gruposuno = 1;		}
					elseif($grupotxt[0] == 's2'){	$gruposdos = 1;		}
					elseif($grupotxt[0] == 's3'){	$grupostres = 1;	}
					elseif($grupotxt[0] == 's4'){	$gruposcuatro = 1;	}
					elseif($grupotxt[0] == 's5'){	$gruposcinco = 1;	}
					elseif($grupotxt[0] == 's6'){	$gruposseis = 1;	}
					elseif($grupotxt[0] == 's7'){	$grupossiete = 1;	}
					elseif($grupotxt[0] == 's8'){	$gruposocho = 1;	}
				}
			}
		}
	}
	
	//10.2	ESTRUCTURAS CORPORALES
	$pdf->Cell(0,3,'','LRT',1,'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(190,8,'10.2 ESTRUCTURAS CORPORALES','1',0,'L',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(214,216,215);
	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(54,8,'',1,0,'L',true);
	$pdf->SetFillColor(178,179,183);
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);   
	$pdf->Cell(10,8,'N2','1',0,'C',true);
	$pdf->Cell(5,8,'N3','1',0,'C',true);   
	$pdf->Cell(5,8,'N4','1',0,'C',true);	
	$pdf->Cell(3,8,'','LTR',0,'C');
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(8,8,'','LTR',0,'C');
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);
	$pdf->Cell(10,8,'N2','1',0,'C',true); 	
	$pdf->Cell(5,8,'N3','1',0,'C',true);	
	$pdf->Cell(5,8,'N4','1',0,'C',true);	
	$pdf->Cell(3,8,'','LTR',0,'C');	
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(8,8,'','LTR',0,'C');
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);	
	$pdf->Cell(10,8,'N2','1',0,'C',true); 	
	$pdf->Cell(5,8,'N3','1',0,'C',true);
	$pdf->Cell(5,8,'N4','1',0,'C',true);	
	$pdf->Cell(3,8,'','LTR',0,'C');
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	//ESTRUCTURA DEL SISTEMA NERVIOSO (s110 a s199)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(54, 7, 'ESTRUCTURA DEL SISTEMA NERVIOSO (s110 a s199)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+54);
	$pdf->SetFillColor(215,215,215);
	if($gruposuno == 1){
		$num = 1;
		imprimirfcs($cif, $num, $s1, $pdf, $multi);
	}else{
		$num = 1;
		colvaciass('3', $pdf, $num);
	}
	
	//EL OJO, EL OIDO Y ESTRUCTURAS RELACIONADAS (s210 a s299)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(54, 7, ' '.utf8_decode('EL OJO, EL OIDO Y ESTRUCTURAS RELACIONADAS (s210 a s299)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+54);
	$pdf->SetFillColor(215,215,215);
	if($gruposdos == 1){
		$num = 2;
		imprimirfcs($cif, $num, $s2, $pdf, $multi);
	}else{
		$num = 2;
		colvaciass('3', $pdf, $num);
	}
	
	//ESTRUCTURAS INVOLUCRADAS EN LA VOZ Y EL HABLA (s310 a s399)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(54, 7, 'ESTRUCTURAS INVOLUCRADAS EN LA VOZ Y EL HABLA (s310 a s399)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+54);
	$pdf->SetFillColor(215,215,215);
	if($grupostres == 1){
		$num = 3;
		imprimirfcs($cif, $num, $s3, $pdf, $multi);
	}else{
		$num = 3;
		colvaciass('3', $pdf, $num);
	}
	
	//ESTRUCTURAS DE LOS SISTEMAS CARDIOVASCULAR, INMUNOLÓGICO Y RESPIRATORIO (s410 a s499)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(54, 4.6, ' '.utf8_decode('ESTRUCTURAS DE LOS SISTEMAS CARDIOVASCULAR, INMUNOLÓGICO Y RESPIRATORIO (s410 a s499)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+54);
	$pdf->SetFillColor(215,215,215);
	if($gruposcuatro == 1){
		$num = 4;
		imprimirfcs($cif, $num, $s4, $pdf, $multi);
	}else{
		$num = 4;
		colvaciass('3', $pdf, $num);
	}
	
	//ESTRUCTURAS RELACIONADAS CON LOS SITEMAS DIGESTIVO, METABÓLICO Y ENDOCRINO (s510 a s599)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',7.5);
	$pdf->MultiCell(54, 3.5, ' '.utf8_decode('ESTRUCTURAS RELACIONADAS CON LOS SITEMAS DIGESTIVO, METABÓLICO Y ENDOCRINO             (s510 a s599)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+54);
	$pdf->SetFillColor(215,215,215);
	if($gruposcinco == 1){
		$num = 5;
		imprimirfcs($cif, $num, $s5, $pdf, $multi);
	}else{
		$num = 5;
		colvaciass('3', $pdf, $num);
	}
	
	//ESTRUCTURAS RELACIONADAS CON EL SITEMA GENITOURINARIO Y EL SISTEMA REPRODUCTOR (s610 a s699)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(54, 3.5, 'ESTRUCTURAS RELACIONADAS CON EL SITEMA GENITOURINARIO Y EL SISTEMA REPRODUCTOR                (s610 a s699)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+54);
	$pdf->SetFillColor(215,215,215);
	if($gruposseis == 1){
		$num = 6;
		imprimirfcs($cif, $num, $s6, $pdf, $multi);
	}else{
		$num = 6;
		colvaciass('3', $pdf, $num);
	}
	
	//ESTRUCTURAS RELACIONADAS CON EL MOVIMIENTO (s710 a s799)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(54, 7, 'ESTRUCTURAS RELACIONADAS CON EL MOVIMIENTO (s710 a s799)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+54);
	$pdf->SetFillColor(215,215,215);
	if($grupossiete == 1){
		$num = 7;
		imprimirfcs($cif, $num, $s7, $pdf, $multi);
	}else{
		$num = 7;
		colvaciass('3', $pdf, $num);
	}
	
	//PIEL Y ESTRUCTURAS RELACIONADAS (s810 a s899)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(54, 7, 'PIEL Y ESTRUCTURAS RELACIONADAS (s810 a s899)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+54);
	$pdf->SetFillColor(215,215,215);
	if($gruposocho == 1){
		$num = 8;
		imprimirfcs($cif, $num, $s8, $pdf, $multi);
	}else{
		$num = 8;
		colvaciass('3', $pdf, $num);
	}	
	
	$pdf->Cell(3,20,'','L',0,'L');
	$pdf->SetFont('Arial','B',10);
	if($discapacidad != 'MENTAL' && $discapacidad != 'INTELECTUAL'){
		$pdf->Cell(190,20,'PORCENTAJE: '.$porcentaje1.' %','',0,'L');
		$pdf->Cell(3,20,'','R',1,'L');
	}else{
		$pdf->Cell(190,20,'','',1,'L');
	}
	
	$grupoduno = 0;		$grupoddos = 0;		$grupodtres = 0;	$grupodcuatro = 0;		$grupodcinco = 0;		$grupodseis = 0;	$grupodsiete = 0;		$grupodocho = 0;	$grupodnueve = 0;	
	$d1 = 0;			$d2 = 0;			$d3 = 0;			$d4 = 0;				$d5 = 0;				$d6 = 0;			$d7 = 0;				$d8 = 0;			$d9 = 0;
	$multi = 3;
	if(!empty($cif)){
		foreach($cif as $clave => $valor) {
			if($clave == 'd'){
				$di = 1;
				foreach($valor as $claved => $valord) {
					$grupotxt = explode(' | ',$valord->grupotxt);
					if($grupotxt[0] == 'd1'){		$grupoduno = 1;		}
					elseif($grupotxt[0] == 'd2'){	$grupoddos = 1;		}
					elseif($grupotxt[0] == 'd3'){	$grupodtres = 1;	}
					elseif($grupotxt[0] == 'd4'){	$grupodcuatro = 1;	}
					elseif($grupotxt[0] == 'd5'){	$grupodcinco = 1;	}
					elseif($grupotxt[0] == 'd6'){	$grupodseis = 1;	}
					elseif($grupotxt[0] == 'd7'){	$grupodsiete = 1;	}
					elseif($grupotxt[0] == 'd8'){	$grupodocho = 1;	}
					elseif($grupotxt[0] == 'd9'){	$grupodnueve = 1;	}
				}
			}
		}
	}
	
	//10.3	ACTIVIDAD Y PARTICIPACIÓN (Discapacidad - Desventaja)
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(190,8,' '.utf8_decode('10.3	ACTIVIDAD Y PARTICIPACIÓN (Discapacidad - Desventaja)'),'1',0,'L',true);
	$pdf->Cell(3,8,'','LR',1,'L');	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(214,216,215);	
	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(51,8,'',1,0,'L',true);
	$pdf->SetFillColor(178,179,183);
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);   
	$pdf->Cell(12,8,'N2','1',0,'C',true);
	$pdf->Cell(6,8,'N3','1',0,'C',true);   
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(4,8,'','LTR',0,'C');
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(8,8,'','LTR',0,'C');
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);
	$pdf->Cell(12,8,'N2','1',0,'C',true); 	
	$pdf->Cell(6,8,'N3','1',0,'C',true);	
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(4,8,'','LTR',0,'C');	
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(8,8,'','LTR',0,'C');
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);	
	$pdf->Cell(12,8,'N2','1',0,'C',true); 	
	$pdf->Cell(6,8,'N3','1',0,'C',true);
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(4,8,'','LTR',0,'C');
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(4,8,'C','1',0,'C',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	//APRENDIZAJE Y APLICACIÓN DEL CONOCIMIENTO (d110 a d199)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 7, ' '.utf8_decode('APRENDIZAJE Y APLICACIÓN DEL CONOCIMIENTO (d110 a d199)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupoduno == 1){
		//print_r($cif);
		$num = 1;
		imprimirfcd($cif, $num, $d1, $pdf, $multi);
	}else{
		$num = 1;
		colvaciasd('3', $pdf, $num);
	}
	
	//TAREAS Y DEMANDAS GENERALES (d210 a d299)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 7, 'TAREAS Y DEMANDAS GENERALES (d210 a d299)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupoddos == 1){
		$num = 2;
		imprimirfcd($cif, $num, $d2, $pdf, $multi);
	}else{
		$num = 2;
		colvaciasd('3', $pdf, $num);
	}
	
	//COMUNICACIÓN (d310 a d399)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 14, ' '.utf8_decode('COMUNICACIÓN (d310 a d399)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupodtres == 1){
		$num = 3;
		imprimirfcd($cif, $num, $d3, $pdf, $multi);
	}else{
		$num = 3;
		colvaciasd('3', $pdf, $num);
	}
	
	//MOVILIDAD, LOCOMOCIÓN, DISPOSICIÓN DEL CUERPO, DESTREZA (d410 a d499)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 4.6, ' '.utf8_decode('MOVILIDAD, LOCOMOCIÓN, DISPOSICIÓN DEL CUERPO, DESTREZA (d410 a d499)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupodcuatro == 1){
		$num = 4;
		imprimirfcd($cif, $num, $d4, $pdf, $multi);
	}else{
		$num = 4;
		colvaciasd('3', $pdf, $num);
	}
	
	//AUTOCUIDADO (d510 a d599)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 14, 'AUTOCUIDADO (d510 a d599)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupodcinco == 1){
		$num = 5;
		imprimirfcd($cif, $num, $d5, $pdf, $multi);
	}else{
		$num = 5;
		colvaciasd('3', $pdf, $num);
	}
	
	//VIDA DOMÉSTICA (d610 a d699)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 14, ' '.utf8_decode('VIDA DOMÉSTICA (d610 a d699)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupodseis == 1){
		$num = 6;
		imprimirfcd($cif, $num, $d6, $pdf, $multi);
	}else{
		$num = 6;
		colvaciasd('3', $pdf, $num);
	}
	
	//INTERACCIONES Y DEMANDAS INTERPERSONALES (d710 a d799)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 7, 'INTERACCIONES Y DEMANDAS INTERPERSONALES (d710 a d799)','LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupodsiete == 1){
		$num = 7;
		imprimirfcd($cif, $num, $d7, $pdf, $multi);
	}else{
		$num = 7;
		colvaciasd('3', $pdf, $num);
	}
	
	//ÁREAS PRINCIPALES DE LA VIDA DIARIA (d810 a d899)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 7, ' '.utf8_decode('ÁREAS PRINCIPALES DE LA VIDA DIARIA (d810 a d899)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupodocho == 1){
		$num = 8;
		imprimirfcd($cif, $num, $d8, $pdf, $multi);
	}else{
		$num = 8;
		colvaciasd('3', $pdf, $num);
	}
	
	//VIDA COMUNITARIA, SOCIAL Y CÍVICA (d910 a d999)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 7, ' '.utf8_decode('VIDA COMUNITARIA, SOCIAL Y CÍVICA (d910 a d999)'),'LRT','L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupodnueve == 1){
		$num = 9;
		imprimirfcd($cif, $num, $d9, $pdf, $multi);
	}else{
		$num = 9;
		colvaciasd('3', $pdf, $num);
	}
	
	$pdf->Cell(3,20,'','LB',0,'L');
	$pdf->SetFont('Arial','B',10);
	if ($porcentaje2 == '') {
		$porcentaje2 = '0';
	}
	if($discapacidad == 'MENTAL' || $discapacidad == 'INTELECTUAL'){
		$pdf->Cell(190,20,'PORCENTAJE: '.$porcentaje1.' %','B',0,'L');
		$pdf->Cell(3,20,'','RB',1,'L');	
	}else{
		$pdf->Cell(190,20,'PORCENTAJE: '.$porcentaje2.' %','B',0,'L');
		$pdf->Cell(3,20,'','RB',1,'L');
	}
	
	$pdf->AddPage();
	
	$grupoeuno = 0;		$grupoedos = 0;		$grupoetres = 0;	$grupoecuatro = 0;		$grupoecinco = 0;
	$e1 = 0;			$e2 = 0;			$e3 = 0;			$e4 = 0;				$e5 = 0;
	$multi = 3;
	if(!empty($cif)){
		foreach($cif as $clave => $valor) {
			if($clave == 'e'){
				$ei = 1;
				foreach($valor as $clavee => $valore) {
					$grupotxt = explode(' | ',$valore->grupotxt);
					if($grupotxt[0] == 'e1'){		$grupoeuno = 1;		}
					elseif($grupotxt[0] == 'e2'){	$grupoedos = 1;		}
					elseif($grupotxt[0] == 'e3'){	$grupoetres = 1;	}
					elseif($grupotxt[0] == 'e4'){	$grupoecuatro = 1;	}
					elseif($grupotxt[0] == 'e5'){	$grupoecinco = 1;	}
				}
			}
		}
	}
	
	//10.4 FACTORES AMBIENTALES
	$pdf->Cell(0,3,'','LRT',1,'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(190,8,'10.4 FACTORES AMBIENTALES','1',0,'L',true);
	$pdf->Cell(3,8,'','LR',1,'L');	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(214,216,215);
	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(51,8,'',1,0,'L',true);
	$pdf->SetFillColor(178,179,183);
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);   
	$pdf->Cell(12,8,'N2','1',0,'C',true);
	$pdf->Cell(6,8,'N3','1',0,'C',true);   
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(4,8,'','LTR',0,'C');
	$pdf->Cell(8,8,'C','1',0,'C',true);
	$pdf->Cell(8,8,'','LTR',0,'C');
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);
	$pdf->Cell(12,8,'N2','1',0,'C',true); 	
	$pdf->Cell(6,8,'N3','1',0,'C',true);	
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(4,8,'','LTR',0,'C');	
	$pdf->Cell(8,8,'C','1',0,'C',true);
	$pdf->Cell(8,8,'','LTR',0,'C');
	
	$pdf->Cell(5,8,'N1','1',0,'C',true);	
	$pdf->Cell(12,8,'N2','1',0,'C',true); 	
	$pdf->Cell(6,8,'N3','1',0,'C',true);
	$pdf->Cell(6,8,'N4','1',0,'C',true);	
	$pdf->Cell(4,8,'','LTR',0,'C');
	$pdf->Cell(8,8,'C','1',0,'C',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	//PRODUCTOS Y TECNOLOGÍA (e110 a e199)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 7, ' '.utf8_decode('PRODUCTOS Y TECNOLOGÍA       (e110 a e199)'),1,'L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupoeuno == 1){
		$num = 1;
		imprimirfae($cif, $num, $e1, $pdf, $multi);
	}else{
		$num = 1;
		colvaciase('3', $pdf, $num);
	}
	
	//ENTORNO NATURAL Y CAMBIOS EN EL ENTORNO DERIVADOS DE LA ACTIVIDAD HUMANA (e210 a e299)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 4.6, 'ENTORNO NATURAL Y CAMBIOS EN EL ENTORNO DERIVADOS DE LA ACTIVIDAD HUMANA (e210 a e299)',1,'L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupoedos == 1){
		$num = 2;
		imprimirfae($cif, $num, $e1, $pdf, $multi);
	}else{
		$num = 2;
		colvaciase('3', $pdf, $num);
	}
	
	//APOYO Y RELACIONES (e310 a e399)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 7, 'APOYO Y RELACIONES                      (e310 a e399)',1,'L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupoetres == 1){
		$num = 3;
		imprimirfae($cif, $num, $e1, $pdf, $multi);
	}else{
		$num = 3;
		colvaciase('3', $pdf, $num);
	}
	
	//ACTITUDES (e410 a e499)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 14, 'ACTITUDES (e410 a e499)',1,'L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupoecuatro == 1){
		$num = 4;
		imprimirfae($cif, $num, $e1, $pdf, $multi);
	}else{
		$num = 4;
		colvaciase('3', $pdf, $num);
	}
	
	//SERVICIOS, SISTEMAS Y POLÍTICAS (e510 a e599)
	$pdf->Cell(3,14,'','LR',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->SetFillColor(178,179,183);
	$pdf->MultiCell(51, 7, ' '.utf8_decode('SERVICIOS, SISTEMAS Y POLÍTICAS (e510 a e599)'),1,'L',true);
	$pdf->SetY($y); $pdf->SetX($x+51);
	$pdf->SetFillColor(215,215,215);
	if($grupoecinco == 1){
		$num = 5;
		imprimirfae($cif, $num, $e1, $pdf, $multi);
	}else{
		$num = 5;
		colvaciase('3', $pdf, $num);
	}

	$pdf->Cell(0,6,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');	
	$pdf->Cell(190,8,'11. TIPO DE DISCAPACIDAD','LTB',0,'L',true); 
    $pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->Cell(3,3,'','LR',0,'L');	
	$pdf->Cell(190,3,'','LRT',0,'L',true); 
    $pdf->Cell(3,3,'','LR',1,'L');	
	$pdf->SetFont('Arial','B',8);
	
	$dfisica = '';		$dvisual = '';		$dauditiva = '';		$dmental = '';		$dintelectual = '';		$dvisceral = '';
	//echo $discapacidad;
	if(utf8_decode($discapacidad) == 'FÍSICA'){
		$dfisica = 'X';
	}elseif($discapacidad == 'VISUAL'){
		$dvisual = 'X';
	}elseif($discapacidad == 'AUDITIVA'){
		$dauditiva = 'X';
	}elseif($discapacidad == 'MENTAL'){
		$dmental = 'X';
	}elseif($discapacidad == 'INTELECTUAL'){
		$dintelectual = 'X';
	}elseif($discapacidad == 'VISCERAL'){
		$dvisceral = 'X';
	}	
	
	$pdf->Cell(3,6,'','LR',0,'L'); 	
	$pdf->Cell(4,6,'','RL',0,'R',true);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(23,6,'INTELECTUAL','1',0,'C',true);
	$pdf->Cell(8,6,$dintelectual,'1',0,'C');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(4,6,'','LR',0,'R',true);
	$pdf->SetFillColor(214,216,215);	
	$pdf->Cell(17,6,' '.utf8_decode('FÍSICA'),'1',0,'C',true);
	$pdf->Cell(8,6,$dfisica,'1',0,'C');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(5,6,'','LR',0,'L',true);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(17,6,'MENTAL','1',0,'C',true);
	$pdf->Cell(8,6,$dmental,'1',0,'C');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(5,6,'','LR',0,'R',true);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(18,6,'AUDITIVA','1',0,'C',true);
	$pdf->Cell(8,6,$dauditiva,'1',0,'C');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(5,6,'','LR',0,'R',true);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(16,6,'VISUAL','1',0,'C',true);
	$pdf->Cell(8,6,$dvisual,'1',0,'C');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(5,6,'','LR',0,'R',true);
	$pdf->SetFillColor(214,216,215);
	$pdf->Cell(19,6,'VISCERAL','1',0,'C',true);
	$pdf->Cell(8,6,$dvisceral,'1',0,'C');
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(4,6,'','L',0,'C',true);
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->Cell(3,3,'','LR',0,'L'); 	
	$pdf->Cell(4,3,'','BL',0,'R',true);
	$pdf->Cell(23,3,'','BT',0,'C',true);
	$pdf->Cell(8,3,'','BT',0,'C',true);	
	$pdf->Cell(4,3,'','B',0,'R',true);	
	$pdf->Cell(17,3,'','BT',0,'C',true);
	$pdf->Cell(8,3,'','BT',0,'C',true);
	$pdf->Cell(5,3,'','B',0,'L',true);
	$pdf->Cell(17,3,'','BT',0,'C',true);
	$pdf->Cell(8,3,'','BT',0,'C',true);
	$pdf->Cell(5,3,'','B',0,'R',true);
	$pdf->Cell(18,3,'','BT',0,'C',true);
	$pdf->Cell(8,3,'','BT',0,'C',true);
	$pdf->Cell(5,3,'','B',0,'R',true);
	$pdf->Cell(16,3,'','BT',0,'C',true);
	$pdf->Cell(8,3,'','BT',0,'C',true);
	$pdf->Cell(5,3,'','B',0,'R',true);
	$pdf->Cell(19,3,'','BT',0,'C',true);
	$pdf->Cell(8,3,'','BT',0,'C',true);
	$pdf->Cell(4,3,'','B',0,'C',true);
	$pdf->Cell(3,3,'','LR',1,'L');

	$pdf->Cell(0,6,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(190,8,'12. OBSERVACIONES Y/O RECOMENDACIONES','LTB',0,'L',true); 
    $pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->Cell(3,8,'','',0,'L');
	$pdf->SetFillColor(255,255,255);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(190,8,utf8_decode($observaciones),'LRB','J',true);
	$yl = $pdf->GetY();
	$difa = $yl - $y;
	$pdf->SetY($y);
	$pdf->Cell(3,$difa,'','L',0,'L');
	$pdf->Cell(190); 
	$pdf->Cell(3,$difa,'','R',1,'L');
	$pdf->SetY($yl);	
	
	$pdf->Cell(0,6,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
	
	$pdf->Cell(3,1,'','LR',0,'L');
	$pdf->Cell(10,1,'','LT',0,'R',true);   
	$pdf->Cell(8,1,'','T',0,'C',true);   
	$pdf->Cell(50,1,'','T',0,'R',true);   
	$pdf->Cell(8,1,'','T',0,'C',true);   
	$pdf->Cell(50,1,'','TR',0,'R',true);
	$pdf->Cell(64,1,'','TR',0,'R',true); 
	$pdf->Cell(3,1,'','LR',1,'L');
	
	$pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(10,8,'13.','L',0,'C',true);
	$pdf->Cell(8,8,$cersi,'1',0,'C');
	$pdf->Cell(50,8,' CERTIFICA','LR',0,'L',true);
	$pdf->Cell(8,8,$cerno,'1',0,'C');
    $pdf->Cell(50,8,'NO CERTIFICA','L',0,'L',true);	
	$pdf->Cell(64,8,'','',0,'C',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->Cell(3,1,'','LR',0,'L');
	$pdf->Cell(10,1,'','LB',0,'R',true);   
	$pdf->Cell(8,1,'','BT',0,'C',true);   
	$pdf->Cell(50,1,'','B',0,'R',true);   
	$pdf->Cell(8,1,'','BT',0,'C',true);   
	$pdf->Cell(50,1,'','BR',0,'R',true); 
	$pdf->Cell(64,1,'','BR',0,'R',true);
	$pdf->Cell(3,1,'','LR',1,'L');
    
    $pdf->Cell(0,6,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
    $pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(190,8,'14. VALIDEZ DEL CERTIFICADO','1',0,'L',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->SetFillColor(214,216,215);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(85,2,'','L',0,'R',true);
	$pdf->Cell(15,2,'','B',0,'L',true);
	$pdf->Cell(5,2,'','',0,'L',true);
	$pdf->Cell(15,2,'','B',0,'L',true);
	$pdf->Cell(5,2,'','',0,'L',true);
	$pdf->Cell(65,2,'','',0,'L',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$arrfechavencimiento = explode('-',$fechavencimiento);
	$fvano = (isset($arrfechavencimiento[0]) ? $arrfechavencimiento[0] : '');
	$fvmes = (isset($arrfechavencimiento[1]) ? $arrfechavencimiento[1] : '');
	
	if($tipoduracion == 'A' || $tipoduracion == ''){
		$yduracion = $duracion;
		$mduracion = '';
	}else{
		$yduracion = '';
		$mduracion = $duracion;
	}

	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(85,6,'ESTE DOCUMENTO TIENE VALIDEZ POR UN PERIODO DE ','LR',0,'R',true);
	$pdf->Cell(15,6,$yduracion,'1',0,'C');
	$pdf->Cell(5,6,'','LR',0,'L',true);
	$pdf->Cell(15,6,$mduracion,'1',0,'C');
	$pdf->Cell(5,6,'','LR',0,'L',true);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	$pdf->MultiCell(65,3,'LUEGO DEL CUAL EL INTERESADO                    DEBE SER REEVALUADO ','R','L',true);
	$pdf->SetY($y); $pdf->SetX($x+65); 
	$pdf->Cell(3,6,'','LR',1,'L');	
	
	$pdf->Cell(3,5,'','LR',0,'L');
	$pdf->Cell(85,5,'','BL',0,'R',true);
	$pdf->Cell(15,5,utf8_decode('AÑO'),'BT',0,'C',true);
	$pdf->Cell(5,5,'','B',0,'L',true);
	$pdf->Cell(15,5,'MES','BT',0,'C',true);
	$pdf->Cell(5,5,'','B',0,'L',true);
	$pdf->Cell(65,5,'','B',0,'L',true);
	$pdf->Cell(3,5,'','LR',1,'L');
	
	$pdf->Cell(0,6,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
    $pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(190,8,' '.utf8_decode('15. LUGAR Y FECHA DE EMISIÓN '),'1',0,'L',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$arrfechaemision = explode('-',$fechaemision);	
	$pdf->SetFillColor(214,216,215);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(3,2,'','LR',0,'L');
	$pdf->Cell(3,2,'','L',0,'L',true);
	$pdf->Cell(100,2,'','B',0,'R',true);
	$pdf->Cell(20,2,'','',0,'L',true);
	$pdf->Cell(20,2,'','B',0,'L',true);
	$pdf->Cell(20,2,'','B',0,'L',true);
	$pdf->Cell(20,2,'','B',0,'L',true);
	$pdf->Cell(7,2,'','R',0,'L',true);
	$pdf->Cell(3,2,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(3,6,'','L',0,'L',true);
	$pdf->Cell(100,6,utf8_decode($lugar),'1',0,'L');
	$pdf->Cell(20,6,'','LR',0,'L',true);
	$pdf->Cell(20,6,$arrfechaemision[0],'1',0,'C');
	$pdf->Cell(20,6,$arrfechaemision[1],'1',0,'C');
	$pdf->Cell(20,6,$arrfechaemision[2],'1',0,'C');
	$pdf->Cell(7,6,'','LR',0,'L',true);
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(3,6,'','LB',0,'L',true);
	$pdf->Cell(100,6,'CIUDAD','BT',0,'C',true);
	$pdf->Cell(20,6,'','B',0,'L',true);
	$pdf->Cell(20,6,' '.utf8_decode('DÍA'),'BT',0,'C',true);
	$pdf->Cell(20,6,'MES','BT',0,'C',true);
	$pdf->Cell(20,6,' '.utf8_decode('AÑO'),'BT',0,'C',true);
	$pdf->Cell(7,6,'','B',0,'L',true);
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(0,6,'','LR',1,'L');
	
	$pdf->SetFillColor(178,179,183);
	$pdf->SetFont('Arial','B',10);
    $pdf->Cell(3,8,'','LR',0,'L');
	$pdf->Cell(47.5,8,'PROFESIONAL','1',0,'C',true);
	$pdf->Cell(47.5,8,'PROFESIONAL','1',0,'C',true);
	$pdf->Cell(47.5,8,'PROFESIONAL','1',0,'C',true);
	$pdf->Cell(47.5,8,'PROFESIONAL','1',0,'C',true);
	$pdf->Cell(3,8,'','LR',1,'L');
	
	$pdf->Cell(3,40,'','LR',0,'L');
	$pdf->Cell(47.5,40,'','LRT',0,'L');
	$pdf->Cell(47.5,40,'','LRT',0,'L');
	$pdf->Cell(47.5,40,'','LRT',0,'L');
	$pdf->Cell(47.5,40,'','LRT',0,'L');
	$pdf->Cell(3,40,'','LR',1,'L');
	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(3,6,'','LR',0,'L');
	$pdf->Cell(47.5,6,'FIRMA Y SELLO','LRB',0,'C');
	$pdf->Cell(47.5,6,'FIRMA Y SELLO','LRB',0,'C');
	$pdf->Cell(47.5,6,'FIRMA Y SELLO','LRB',0,'C');
	$pdf->Cell(47.5,6,'FIRMA Y SELLO','LRB',0,'C');
	$pdf->Cell(3,6,'','LR',1,'L');
	
	$pdf->Cell(0,3,'','LRB',1,'L');	
	
	$pdf->Output('I',"protocolo.pdf"); 	 
?>