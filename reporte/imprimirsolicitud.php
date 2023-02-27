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
    $pdf->Image('../images/senadis.png',8,10,58); //borde izq, borde sup, ancho
    $pdf->SetFont('Arial','B',12);
	
	//DATOS DEL SOLICITANTE
	$queryS = " SELECT CONCAT(p.nombre,' ',p.apellidopaterno, ' ',p.apellidomaterno) AS paciente, s.fecha_cita AS fecha, p.id as idpaciente,
				s.iddiscapacidad as iddiscapacidad, GROUP_CONCAT(CONCAT(m.id,'|',m.nombre,' ',m.apellido,'|', REPLACE(e.nombre,',',' / '))) AS medicos, 
				s.sala AS sala, r.nombre AS regional, d.nombre AS discapacidad, hs.fecha, s.idacompanante, s.tipoacompanante, s.iddiscapacidad 
				FROM solicitudes s 
				LEFT JOIN pacientes p ON p.id = s.idpaciente 
				LEFT JOIN discapacidades d ON d.id = s.iddiscapacidad
				LEFT JOIN regionales r ON r.id = s.regional
				LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta) 
				LEFT JOIN especialidades e ON e.id = m.especialidad 
				LEFT JOIN historicosolicitudes hs ON hs.idsolicitud = s.id AND hs.estadoactual = 17
				WHERE s.id = ".$id;
	$resultS = $mysqli->query($queryS); 
	if($rowS = $resultS->fetch_assoc()){
		$idpaciente	    = $rowS['idpaciente'];
		$paciente	    = $rowS['paciente'];
		$fecha 	        = $rowS['fecha'];
		$medicos 		= $rowS['medicos'];
		$discapacidad 	= $rowS['discapacidad'];
		$iddiscapacidad = $rowS['iddiscapacidad'];
		$sala           = $rowS['sala'];
		$regional       = $rowS['regional'];
		$fechasol  		= $rowS['fecha'];
		$idacompanante  = $rowS['idacompanante'];
		$tipoacompanante = $rowS['tipoacompanante'];
		$iddiscapacidad  = $rowS['iddiscapacidad'];
	
		$queryP = " SELECT nombre, apellidopaterno, apellidomaterno, cedula, tipo_documento, fecha_nac, sexo, telefono, celular, correo, nacionalidad,
					estado_civil, condicion_actividad, categoria_actividad, cobertura_medica, beneficios, discapacidades, direccion, status
					FROM pacientes WHERE id = '$idpaciente' ";					
		$resultP = $mysqli->query($queryP);
		//echo $queryP;
		if($rowP = $resultP->fetch_assoc()){			
			$query_direccion = "SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero
								FROM direccion d 
								LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
								WHERE d.id = '".$rowP['direccion']."'";
			
			$result_d = $mysqli->query($query_direccion);
			$row_d    = $result_d->fetch_assoc();
			$provincia     = $row_d['provincia'];
			$distrito      = $row_d['distrito'];
			$corregimiento = $row_d['corregimiento'];
			$area          = $row_d['area'];
			$urbanizacion  = $row_d['urbanizacion'];
			$calle         = $row_d['calle'];
			$edificio      = $row_d['edificio'];
			$numero        = $row_d['numero'];
			
			if($idacompanante != 0){
				$queryA = "SELECT * FROM acompanantes WHERE id = '".$idacompanante."'";
				$resultA = $mysqli->query($queryA);
				$rowA   = $resultA->fetch_assoc();
				$nombreacomp = utf8_decode($rowA['nombre']);
				$apellidoacomp = utf8_decode($rowA['apellido']);
				$telefonoacomp = $rowA['telefono'];
				$celularacomp = $rowA['celular'];
				$correroacomp = utf8_decode($rowA['correo']);
				$fechanacacomp = $rowA['fecha_nac'];
				$sexoacomp = $rowA['sexo'];
				$tipodocacomp = $rowA['tipo_documento'];
				$cedulaacomp = $rowA['cedula'];
				$nacionalidadacomp = utf8_decode($rowA['nacionalidad']);
				$modotutoracomp = $rowA['modo_tutor'];
				
				$query_direccionA = " 	SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero
										FROM direccion d 
										LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
										WHERE d.id = '".$rowA['direccion']."' ";
				
				$result_dA = $mysqli->query($query_direccionA);
				$row_dA    = $result_dA->fetch_assoc();
				$provinciaA     = $row_dA['provincia'];
				$distritoA      = $row_dA['distrito'];
				$corregimientoA = $row_dA['corregimiento'];
				$areaA          = $row_dA['area'];
				$urbanizacionA  = $row_dA['urbanizacion'];
				$calleA         = $row_dA['calle'];
				$edificioA      = $row_dA['edificio'];
				$numeroA        = $row_dA['numero'];
			}else{
				$nombreacomp = '';
				$apellidoacomp = '';
				$telefonoacomp = '';
				$celularacomp = '';
				$correroacomp = '';
				$fechanacacomp = '';
				$sexoacomp = '';
				$tipodocacomp = '';
				$cedulaacomp = '';
				$nacionalidadacomp = '';
				$modotutoracomp = '';
				
				$provinciaA     = '';
				$distritoA      = '';
				$corregimientoA = '';
				$areaA          = '';
				$urbanizacionA  = '';
				$calleA         = '';
				$edificioA      = '';
				$numeroA        = '';
			}
			
			
			//********** ********** ********** CUADRO DEL ENCABEZADO ********** ********** **********//
			$pdf->Cell(65,8,'','0',0,'C'); 
			$pdf->SetTextColor(231,19,31);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(.5);
			$pdf->Cell(0,8,'PARA USO DEL FUNCIONARIO DE SENADIS','1',1,'C'); 
			$pdf->SetLineWidth(0);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(65,3,'','0',0,'C');
			$pdf->Cell(0,3,'','LR',1,'C');
			$pdf->Cell(65,7,'','0',0,'C');
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(71,7,' TURNO:___________________________','L',0,'L');
			$pdf->SetFont('Arial','B',11);
			if($fechasol != ''){
				$arrfechasol = explode('-', $fechasol);				
				$pdf->Cell(60,7,$arrfechasol[2].'  /  '.$arrfechasol[1].'  /  '.$arrfechasol[0].'     ','R',1,'R');
			}else{
				$pdf->Cell(60,7,'____/ ____/ ____/   ','R',1,'R');
			}			
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(65,7,'','0',0,'C'); 
			$pdf->Cell(71,7,' HORA:______________','L',0,'L'); 
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(60,7,'DÍA  MES  AÑO     ','R',1,'R'); 
			$pdf->Cell(65,7,'','0',0,'C');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(53,7,'TIPO DE DISCAPACIDAD A EVALUAR:','BL',0,'L');
			$pdf->SetFont('','BU');
			$pdf->Cell(78,7,' '.strtoupper(utf8_decode($discapacidad)).' ','BR',1,'L'); 
			$pdf->SetFont('Arial','B',18);
			$pdf->Ln(4);
			//********** ********** ********** FIN CUADRO DEL ENCABEZADO ********** ********** **********//
			
			//********** ********** ********** TEXTO INICIAL ********** ********** **********//
			$pdf->Cell(200,8,'SOLICITUD DE CERTIFICADO DE DISCAPACIDAD','0',1,'C'); 
			$pdf->SetFont('Arial','',8.7);
			$pdf->Cell(200,4,'Los datos que a continuación se consignarán, pertenecen a la persona evaluada y que solicita por sí o por medio de un tercero, el otorgamien-','0',1,'L'); 
			$pdf->Cell(200,4,'to de una Certificación de Discapacidad.','0',1,'L'); 
			$pdf->Cell(200,4,'El presente documento tiene carácter de Declaración Jurada, por tanto se advierte a quien lo suscribe el contenido del artículo 385 del Código ','0',1,'L'); 
			$pdf->Cell(200,4,'Penal de la República de Panamá que contempla el tipo penal de falsedad.','0',1,'L'); 
			$pdf->SetFont('Arial','B',8.8);
			$pdf->Cell(21,4,'Artículo 385:','0',0,'L'); 
			$pdf->SetFont('Arial','',8.8);
			$pdf->Cell(200,4,'“El tesigo, perito intérprete o traductor que, ante la autoridad competente, afirme una falsedad o niegue o calle la verdad, en ','0',1,'L'); 
			$pdf->Cell(200,4,'todo o en parte de su declarción, dictamen, interpretación o traducción, será sancionado con prisión de dos o cuatro años','0',1,'L'); 
			$pdf->Cell(200,4,'Cuando el delito es cometido en una causa criminal en perjuicio del inculcado o es la base sobre la cual una autoridad jurisdiccional dicta ','0',1,'L'); 
			$pdf->Cell(200,4,'sentencia, la prisión será de cuatro a ocho años.”','0',1,'L'); 
			$pdf->Ln(2);
			//********** ********** ********** TEXTO INICIAL ********** ********** **********//
			
			//********** ********** ********** DATOS DEL SOLICITANTE ********** ********** **********//
			$pdf->SetFont('Arial','B',11);
			$pdf->SetFillColor(178,179,183);			
			$pdf->Cell(0,8,'DATOS DEL SOLICITANTE','1',1,'L',true);
			$pdf->SetFillColor(215,215,215);
			$pdf->Cell(0,2,'','LRT',1,'L',true); 
			$pdf->Cell(3,8,'','LR',0,'L',true);
			$pdf->Cell(30,8,' APELLIDOS','LTR',0,'L',true); 
			$pdf->Cell(54,8,' '.utf8_decode($rowP['apellidopaterno']),'T',0,'L'); 
			$pdf->Cell(54,8,' '.utf8_decode($rowP['apellidomaterno']),'T',0,'L'); 
			$pdf->Cell(52,8,'         ','T',0,'L'); 
			$pdf->Cell(3,8,'','LR',1,'L',true);
			$pdf->Cell(3,3,'','LR',0,'L',true);
			$pdf->Cell(30,3,'','LRB',0,'L',true);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(54,3,'PATERNO','B',0,'C'); 
			$pdf->Cell(54,3,'MATERNO','B',0,'C'); 
			$pdf->Cell(52,3,'CASADA','B',0,'C'); 
			$pdf->Cell(3,3,'','LR',1,'L',true); 
			$pdf->Cell(3,10,'','LR',0,'L',true); 
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(30,10,' NOMBRES','1',0,'L',true);  
			$pdf->Cell(160,10,' '.utf8_decode(($rowP['nombre'])),'1',0,'L');
			$pdf->Cell(3,10,'','LR',1,'L',true); 
			$pdf->Cell(3,10,'','LR',0,'L',true);  
			$pdf->Cell(30,10,' TELÉFONO','1',0,'L',true);  
			$pdf->Cell(68,10,' '.$rowP['telefono'],'1',0,'L');  
			$pdf->Cell(40,10,'CELULAR ','1',0,'R',true); 
			$pdf->Cell(52,10,' '.$rowP['celular'],'1',0,'L'); 
			$pdf->Cell(3,10,'','LR',1,'L',true);  
			$pdf->Cell(3,10,'','LR',0,'L',true);  
			$pdf->Cell(50,10,' CORREO ELECTRÓNICO','1',0,'L',true);  
			$pdf->Cell(140,10,' '.utf8_decode($rowP['correo']),'1',0,'L');   
			$pdf->Cell(3,10,'','LR',1,'L',true);  
			$pdf->Cell(3,2,'','LB',0,'L',true);
			$pdf->Cell(190,2,'','BT',0,'L',true);  
			$pdf->Cell(3,2,'','BR',1,'L',true); 	
			$pdf->Ln(2);
			//********** ********** ********** FIN DATOS DEL SOLICITANTE ********** ********** **********//
			
			//********** ********** ********** FECHA DE NACIMIENTO ********** ********** **********//
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(0,1.5,'','LRT',1,'L',true);
			$pdf->Cell(54,3,'','L',0,'L',true);			
			$pdf->Cell(8,3,'D','B',0,'C',true);    
			$pdf->Cell(8,3,'D','B',0,'C',true);    
			$pdf->Cell(2,3,'','',0,'C',true);    
			$pdf->Cell(8,3,'M','B',0,'C',true);    
			$pdf->Cell(8,3,'M','B',0,'C',true);    
			$pdf->Cell(2,3,'','',0,'C',true);    
			$pdf->Cell(8,3,'A','B',0,'C',true);    
			$pdf->Cell(8,3,'A','B',0,'C',true);    
			$pdf->Cell(8,3,'A','B',0,'C',true);    
			$pdf->Cell(8,3,'A','B',0,'C',true);    
			$pdf->Cell(36,3,'','',0,'C',true);    
			$pdf->Cell(8,3,'','B',0,'C',true);    
			$pdf->Cell(20,3,'','',0,'C',true);   
			$pdf->Cell(8,3,'','B',0,'C',true);   
			$pdf->Cell(2,3,'','R',1,'L',true);
			$pdf->SetFont('Arial','B',11);
			$fecha_nac = $rowP['fecha_nac'];
			$a4 = substr($fecha_nac, -10,1); 
			$a3 = substr($fecha_nac, -9,1); 
			$a2 = substr($fecha_nac, -8,1); 
			$a1 = substr($fecha_nac, -7,1); 
			$m2 = substr($fecha_nac, -5,1); 
			$m1 = substr($fecha_nac, -4,1); 
			$d2 = substr($fecha_nac, -2,1); 
			$d1 = substr($fecha_nac, -1,1); 
			$pdf->Cell(54,8,'FECHA DE NACIMIENTO','LR',0,'L',true);  
			$pdf->Cell(8,8,$d2,'LR',0,'C');   
			$pdf->Cell(8,8,$d1,'LR',0,'C');   
			$pdf->Cell(2,8,'','LR',0,'C',true);    
			$pdf->Cell(8,8,$m2,'LR',0,'C');   
			$pdf->Cell(8,8,$m1,'LR',0,'C');   
			$pdf->Cell(2,8,'','LR',0,'C',true);    
			$pdf->Cell(8,8,$a4,'LR',0,'C');   
			$pdf->Cell(8,8,$a3,'LR',0,'C');   
			$pdf->Cell(8,8,$a2,'LR',0,'C');   
			$pdf->Cell(8,8,$a1,'LR',0,'C');
			//********** ********** ********** FIN FECHA DE NACIMIENTO ********** ********** **********//
			
			//********** ********** ********** SEXO ********** ********** **********//
			if($rowP['sexo']=='M'){
				$feme = '';
				$masc = 'X';
			}else{
				$feme = 'X';
				$masc = '';
			}
			$pdf->Cell(36,8,'SEXO:   M ','LR',0,'R',true);    
			$pdf->Cell(8,8,$masc,'1',0,'C');   
			$pdf->Cell(19,8,'F  ','LR',0,'R',true);    
			$pdf->Cell(8,8,$feme,'1',0,'C');   
			$pdf->Cell(3,7,'','LR',1,'L',true);
			$pdf->Cell(54,2,'','BL',0,'L',true);  
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(2,2,'','B',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(2,2,'','B',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(36,2,'','B',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(19,2,'','B',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(3,2,'','BR',1,'L',true);
			$pdf->Ln(2);
			//********** ********** ********** FIN SEXO ********** ********** **********//
			
			//********** ********** ********** DOCUMENTO DE IDENTIDAD PERSONAL ********** ********** **********//
			if($rowP['tipo_documento']==1){
				$cedula = 'X';
				$pasaporte = '';
			}else{
				$cedula = '';
				$pasaporte = 'X';
			}			
			$pdf->Cell(0,2,'','TLR',1,'L',true);  
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(95,8,'DOCUMENTO DE IDENTIDAD PERSONAL: ','L',0,'L',true);
			$pdf->Cell(25,8,'CÉDULA ','R',0,'R',true);
			$pdf->Cell(8,8,$cedula,'1',0,'C');    
			$pdf->Cell(40,8,'PASAPORTE ','LR',0,'R',true);    
			$pdf->Cell(8,8,$pasaporte,'1',0,'C');   
			$pdf->Cell(20,8,'','LR',1,'L',true);			
			$pdf->Cell(0,2,'','LRB',0,'L',true); 
			$pdf->Ln(2);
			
			$cedulaP = $rowP['cedula']; 
			$long = strlen($cedulaP);
			if($long==4){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; $c8  = "";  $c7  = ""; $c6 = ""; 	$c5  = ""; 
				$c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}elseif($long==5){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; $c8  = "";  $c7  = ""; $c6 = "";
				$c5  = substr($cedulaP, -5,1);  $c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);  
				$c2  = substr($cedulaP, -2,1); 	$c1  = substr($cedulaP, -1,1);
			}elseif($long==6){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; $c8  = "";  $c7  = ""; 	$c6  = substr($cedulaP, -6,1); 	$c5  = substr($cedulaP, -5,1); 
				$c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}elseif($long==7){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; $c8  = ""; $c7  = substr($cedulaP, -7,1); 	
				$c6  = substr($cedulaP, -6,1); 	$c5  = substr($cedulaP, -5,1); $c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}elseif($long==8){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; 	$c8  = substr($cedulaP, -8,1); 
				$c7  = substr($cedulaP, -7,1); 	$c6  = substr($cedulaP, -6,1); 	$c5  = substr($cedulaP, -5,1); 
				$c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}elseif($long==9){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9  = substr($cedulaP, -9,1); 	$c8  = substr($cedulaP, -8,1); 
				$c7  = substr($cedulaP, -7,1); 	$c6  = substr($cedulaP, -6,1); 	$c5  = substr($cedulaP, -5,1); 
				$c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}elseif($long==10){
				$c13 = ""; $c12 = ""; $c11 = "";  
				$c10 = substr($cedulaP, -10,1); $c9  = substr($cedulaP, -9,1); 	$c8  = substr($cedulaP, -8,1); 
				$c7  = substr($cedulaP, -7,1); 	$c6  = substr($cedulaP, -6,1); 	$c5  = substr($cedulaP, -5,1); 
				$c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}elseif($long==11){
				$c13 = ""; $c12 = ""; $c11 = substr($cedulaP, -11,1); 
				$c10 = substr($cedulaP, -10,1); $c9  = substr($cedulaP, -9,1); 	$c8  = substr($cedulaP, -8,1); 
				$c7  = substr($cedulaP, -7,1); 	$c6  = substr($cedulaP, -6,1); 	$c5  = substr($cedulaP, -5,1); 
				$c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}elseif($long==12){
				$c13 = ""; $c12 = substr($cedulaP, -12,1); $c11 = substr($cedulaP, -11,1); 
				$c10 = substr($cedulaP, -10,1); $c9  = substr($cedulaP, -9,1); 	$c8  = substr($cedulaP, -8,1); 
				$c7  = substr($cedulaP, -7,1); 	$c6  = substr($cedulaP, -6,1); 	$c5  = substr($cedulaP, -5,1); 
				$c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}elseif($long==13){
				$c13 = substr($cedulaP, -13,1); $c12 = substr($cedulaP, -12,1); $c11 = substr($cedulaP, -11,1); 
				$c10 = substr($cedulaP, -10,1); $c9  = substr($cedulaP, -9,1); 	$c8  = substr($cedulaP, -8,1); 
				$c7  = substr($cedulaP, -7,1); 	$c6  = substr($cedulaP, -6,1); 	$c5  = substr($cedulaP, -5,1); 
				$c4  = substr($cedulaP, -4,1);  $c3  = substr($cedulaP, -3,1);   $c2  = substr($cedulaP, -2,1); 
				$c1  = substr($cedulaP, -1,1);
			}else{
				$c13 =""; $c12 =""; $c11 =""; $c10 =""; $c9 ="";	$c8 =""; 
				$c7  =""; $c6 =""; $c5 ="";	$c4  =""; $c3 =""; $c2 ="";	$c1  ="";
			}
			$pdf->Ln(2);
			//********** ********** ********** FIN DOCUMENTO DE IDENTIDAD PERSONAL ********** ********** **********//
			
			//********** ********** ********** NÚMERO DE DOCUMENTO DE IDENTIDAD PERSONAL ********** ********** **********//			
			$pdf->Cell(0,6,' NÚMERO DE DOCUMENTO DE IDENTIDAD PERSONAL                                     NACIONALIDAD','TLR',1,'L',true);
			$pdf->Cell(2,9,'','LR',0,'L',true);  
			$pdf->Cell(9,9,$c13,'1',0,'C');   
			$pdf->Cell(9,9,$c12,'1',0,'C');    
			$pdf->Cell(9,9,$c11,'1',0,'C');   
			$pdf->Cell(9,9,$c10,'1',0,'C');   
			$pdf->Cell(9,9,$c9,'1',0,'C');    
			$pdf->Cell(9,9,$c9,'1',0,'C');   
			$pdf->Cell(9,9,$c7,'1',0,'C');   
			$pdf->Cell(9,9,$c6,'1',0,'C');   
			$pdf->Cell(9,9,$c5,'1',0,'C');   
			$pdf->Cell(9,9,$c4,'1',0,'C');   
			$pdf->Cell(9,9,$c3,'1',0,'C');   
			$pdf->Cell(9,9,$c2,'1',0,'C');   
			$pdf->Cell(9,9,$c1,'1',0,'C');    
			$pdf->Cell(9,9,'','LR',0,'C',true);   
			$pdf->Cell(65,9,utf8_decode($rowP['nacionalidad']),'1',0,'L');  
			$pdf->Cell(3,9,'','LR',1,'L',true);
			$pdf->Cell(2,4,'','LB',0,'C',true);
			$pdf->Cell(117.2,4,'','TB',0,'C',true); 
			$pdf->Cell(9,4,'','B',0,'C',true);
			$pdf->Cell(65,4,'','TB',0,'C',true);
			$pdf->Cell(2.8,4,'','RB',1,'L',true);
			$pdf->Ln(2);
			//********** ********** ********** FIN NÚMERO DE DOCUMENTO DE IDENTIDAD PERSONAL ********** ********** **********//
			
			//********** ********** ********** DIRECCIÓN RESIDENCIAL ********** ********** **********//			
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(0,2,'','LRT',1,'L',true);	
			$pdf->Cell(3,8,'','LR',0,'L',true);
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(190,8,'DIRECCIÓN RESIDENCIAL','1',0,'L',true); 
			$pdf->SetFillColor(215,215,215);
			$pdf->Cell(3,8,'','LR',1,'L',true);			
			$pdf->Cell(3,12,'','LR',0,'L',true);
			$pdf->Cell(33,12,' URBANIZACIÓN','1',0,'L',true); 
			$pdf->Cell(75,12,' '.utf8_decode($urbanizacion),'1',0,'L'); 
			$pdf->Cell(22,12,'CALLE ','1',0,'R',true); 
			$pdf->Cell(60,12,' '.utf8_decode($calle),'1',0,'L'); 
			$pdf->Cell(3,12,'','LR',1,'L',true); 
			$pdf->Cell(3,12,'','LR',0,'L',true); 
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(22,12,' EDIFICIO','1',0,'L',true);  
			$pdf->Cell(76,12,' '.utf8_decode($edificio),'1',0,'L'); 
			$pdf->Cell(38,12,'APTO / CASA N° ','1',0,'R',true); 
			$pdf->Cell(54,12,' '.$numero,'1',0,'L'); 
			$pdf->Cell(3,12,'','LR',1,'L',true); 
			$pdf->Cell(3,12,'','LR',0,'L',true); 
			$pdf->Cell(36,12,' CORREGIMIENTO','1',0,'L',true);  
			$pdf->Cell(72,12,' '.utf8_decode($corregimiento),'1',0,'L');  
			$pdf->Cell(28,12,'DISTRITO ','1',0,'R',true); 
			$pdf->Cell(54,12,' '.utf8_decode($distrito),'1',0,'L'); 
			$pdf->Cell(3,12,'','LR',1,'L',true);  
			$pdf->Cell(3,12,'','LR',0,'L',true);  
			$pdf->Cell(26,12,' PROVINCIA','1',0,'L',true);  
			$pdf->Cell(164,12,' '.utf8_decode($provincia),'1',0,'L');   
			$pdf->Cell(3,12,'','LR',1,'L',true);			
			if($area == 'Urbana'){
				$urbana   = "X";
				$rural    = "";
				$indigena = "";
			}elseif($area == 'Rural'){
				$urbana   = "";
				$rural    = "X";
				$indigena = "";
			}elseif($area == 'Indígena'){
				$urbana   = "";
				$rural    = "";
				$indigena = "X";
			}
			$pdf->Cell(3,2,'','LR',0,'L',true);  
			$pdf->Cell(15,2,'','LRT',0,'L',true);  
			$pdf->Cell(35,2,'','T',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(48,2,'','T',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(48,2,'','T',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(26,2,'','T',0,'L');  
			$pdf->Cell(3,2,'','LR',1,'L',true);
			
			$pdf->Cell(3,7,'','LR',0,'L',true);  
			$pdf->Cell(15,7,' ZONA','LR',0,'L',true);  
			$pdf->Cell(35,7,'URBANA','R',0,'R');  
			$pdf->Cell(6,7,' '.$urbana,'LR',0,'L');  
			$pdf->Cell(48,7,'RURAL','LR',0,'R');  
			$pdf->Cell(6,7,' '.$rural,'LR',0,'L');  
			$pdf->Cell(48,7,'INDÍGENA','LR',0,'R');  
			$pdf->Cell(6,7,' '.$indigena,'LR',0,'L');  
			$pdf->Cell(26,7,'','L',0,'L');  
			$pdf->Cell(3,7,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','LR',0,'L',true);  
			$pdf->Cell(15,2,'','LRB',0,'L',true);  
			$pdf->Cell(35,2,'','B',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(48,2,'','B',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(48,2,'','B',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(26,2,'','B',0,'L');  
			$pdf->Cell(3,2,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','LB',0,'L',true);
			$pdf->Cell(190,2,'','BT',0,'L',true);  
			$pdf->Cell(3,2,'','BR',1,'L',true);
			$pdf->Ln(2);
			//********** ********** ********** FIN DIRECCIÓN RESIDENCIAL ********** ********** **********//			
			
			//********** ********** ********** ESTADO CIVIL ********** ********** ********** **********//
			$pdf->SetFont('Arial','B',11);
			if($rowP['estado_civil']==1){
				$soltero 	= 'X';
				$casado		= '';
				$divorciado = '';
				$viudo 		= '';
				$unido 		= '';
			}
			if($rowP['estado_civil']==2){
				$soltero 	= '';
				$casado		= 'X';
				$divorciado = '';
				$viudo 		= '';
				$unido 		= '';
			}
			if($rowP['estado_civil']==3){
				$soltero 	= '';
				$casado		= '';
				$divorciado = 'X';
				$viudo 		= '';
				$unido 		= '';
			}
			if($rowP['estado_civil']==4){
				$soltero 	= '';
				$casado		= '';
				$divorciado = '';
				$viudo 		= 'X';
				$unido 		= '';
			}
			if($rowP['estado_civil']==5){
				$soltero 	= '';
				$casado		= '';
				$divorciado = '';
				$viudo 		= '';
				$unido 		= 'X';
			}
			$pdf->Cell(0,7,'  ESTADO CIVIL','TLR',1,'L',true); 
			$pdf->Cell(36,4,'SOLTERO/A','LR',0,'C',true);   
			$pdf->Cell(4,4,$soltero,'1',0,'C');   
			$pdf->Cell(30,4,'CASADO/A','LR',0,'C',true);   
			$pdf->Cell(4,4,$casado,'1',0,'C');   
			$pdf->Cell(41,4,'DIVORCIADO/A','LR',0,'C',true);   
			$pdf->Cell(4,4,$divorciado,'1',0,'C');    
			$pdf->Cell(30,4,'VIUDO/A','LR',0,'C',true);  
			$pdf->Cell(4,4,$viudo,'1',0,'C');   
			$pdf->Cell(30,4,'UNIDO/A','LR',0,'C',true);   
			$pdf->Cell(4,4,$unido,'1',0,'C');   
			$pdf->Cell(9,4,'','LR',1,'C',true);
			
			$pdf->Cell(36,4,'','BL',0,'C',true);   
			$pdf->Cell(4,4,'','TB',0,'C',true);   
			$pdf->Cell(30,4,'','B',0,'C',true);   
			$pdf->Cell(4,4,'','TB',0,'C',true);   
			$pdf->Cell(41,4,'','B',0,'C',true);   
			$pdf->Cell(4,4,'','TB',0,'C',true);    
			$pdf->Cell(30,4,'','B',0,'C',true);  
			$pdf->Cell(4,4,'','TB',0,'C',true);   
			$pdf->Cell(30,4,'','B',0,'C',true);   
			$pdf->Cell(4,4,'','TB',0,'C',true);   
			$pdf->Cell(9,4,'','BR',1,'C',true); 
			$pdf->Ln(2);
			//********** ********** ********** FIN ESTADO CIVIL ********** ********** ********** **********//
			
			//********** ********** ********** CONDICIÓN DE ACTIVIDAD ********** ********** **********//
			if($rowP['condicion_actividad'] == 1){
				$trabaja = 'X';
				$notrabaja = '';
				$busca = '';
				$nobusca = '';
				$noaplica = '';
			}elseif($rowP['condicion_actividad'] == 2){
				$trabaja = '';
				$notrabaja = 'X';
				$busca = '';
				$nobusca = '';
				$noaplica = '';
			}elseif($rowP['condicion_actividad'] == 3){
				$trabaja = '';
				$notrabaja = '';
				$busca = 'X';
				$nobusca = '';
				$noaplica = '';
			}elseif($rowP['condicion_actividad'] == 4){
				$trabaja = '';
				$notrabaja = '';
				$busca = '';
				$nobusca = 'X';
				$noaplica = '';
			}elseif($rowP['condicion_actividad'] == 5){
				$trabaja = '';
				$notrabaja = '';
				$busca = '';
				$nobusca = '';
				$noaplica = 'X';
			}
			
			if($rowP['categoria_actividad']==1){
				$obrero = 'X';
				$patron = '';
				$busca = '';
				$cuentap = ''; 
			}elseif($rowP['categoria_actividad']==2){
				$obrero = '';
				$patron = 'X';
				$busca = '';
				$cuentap = '';
			}elseif($rowP['categoria_actividad']==3){
				$obrero = '';
				$patron = '';
				$busca = '';
				$cuentap = 'X';
			}elseif($rowP['categoria_actividad']==4){
				$obrero = '';
				$patron = '';
				$busca = '';
				$cuentap = 'X';
			} 
			$pdf->Cell(98,7,'  CONDICIÓN DE ACTIVIDAD','1',0,'L',true); 
			$pdf->Cell(98,7,'CATEGORÍA DE ACTIVIDAD','1',1,'L',true); 
			 
			$pdf->Cell(3,2,'','TL',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(40,2,'','T',0,'L',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(47,2,'','T',0,'L',true);   
			$pdf->Cell(4,2,'','TL',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(90,2,'','TR',1,'L',true);
			
			$pdf->Cell(3,4,'','LR',0,'C',true);   
			$pdf->Cell(4,4,$trabaja,'1',0,'C');   
			$pdf->Cell(40,4,'TRABAJA','LR',0,'L',true);   
			$pdf->Cell(4,4,$nobusca,'1',0,'C');   
			$pdf->Cell(47,4,'NO BUSCA TRABAJO','L',0,'L',true);   
			$pdf->Cell(4,4,'','LR',0,'C',true);   
			$pdf->Cell(4,4,$obrero,'1',0,'C');    
			$pdf->Cell(90,4,'OBRERO O EMPLEADO','LR',1,'L',true);  
			
			$pdf->Cell(3,2,'','L',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(40,2,'','',0,'L',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(47,2,'','',0,'L',true);   
			$pdf->Cell(4,2,'','L',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(90,2,'','R',1,'L',true); 
			
			$pdf->Cell(3,4,'','LR',0,'C',true);   
			$pdf->Cell(4,4,$notrabaja,'1',0,'C');   
			$pdf->Cell(40,4,'NO TRABAJA','LR',0,'L',true);   
			$pdf->Cell(4,4,$noaplica,'1',0,'C');   
			$pdf->Cell(47,4,'NO APLICABLE','LR',0,'L',true);   
			$pdf->Cell(4,4,'','LR',0,'C',true);   
			$pdf->Cell(4,4,$patron,'1',0,'C');    
			$pdf->Cell(90,4,'PATRÓN (con personal a cargo)','LR',1,'L',true);  
			
			$pdf->Cell(3,2,'','L',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(40,2,'','',0,'L',true);   
			$pdf->Cell(4,2,'','T',0,'C',true);   
			$pdf->Cell(47,2,'','',0,'L',true);   
			$pdf->Cell(4,2,'','L',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(90,2,'','R',1,'L',true);
			
			$pdf->Cell(3,4,'','LR',0,'C',true);   
			$pdf->Cell(4,4,$busca,'1',0,'C');   
			$pdf->Cell(40,4,'BUSCA TRABAJO','L',0,'L',true);   
			$pdf->Cell(4,4,'','0',0,'C',true);   
			$pdf->Cell(47,4,'','0',0,'L',true);   
			$pdf->Cell(4,4,'','LR',0,'C',true);   
			$pdf->Cell(4,4,$cuentap,'',0,'C');    
			$pdf->Cell(90,4,'TRABAJO POR CUENTA PROPIA','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','LB',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(40,2,'','B',0,'L',true);   
			$pdf->Cell(4,2,'','B',0,'C',true);   
			$pdf->Cell(47,2,'','B',0,'L',true);   
			$pdf->Cell(4,2,'','BL',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);   
			$pdf->Cell(90,2,'','BR',1,'L',true);
			$pdf->Ln(2);
			//********** ********** ********** FIN CONDICIÓN DE ACTIVIDAD ********** ********** **********//
			
			//********** ********** ********** COBERTURA MÉDICA ********** ********** **********//			
			//$cobertura_medica = explode(",", $rowP['cobertura_medica']);
			$cobertura_medica = $rowP['cobertura_medica'];
			if($cobertura_medica !="" ){ 
				$segurosocial = 'X';
				$seguroprivado = 'X';
				$ninguno = ''; 
			}else{
				if($cobertura_medica[0]==1){
					$segurosocial = 'X';
					$seguroprivado = '';
					$ninguno = ''; 
				}elseif($cobertura_medica[0]==2){
					$segurosocial = '';
					$seguroprivado = 'X';
					$ninguno = '';
				}else{
					$segurosocial = '';
					$seguroprivado = '';
					$ninguno = 'X';
				}
			} 			
			$pdf->Cell(0,8,'  COBERTURA MÉDICA','TLR',1,'L',true); 
			$pdf->Cell(3,4,'','LR',0,'C',true);   
			$pdf->Cell(4,4,$segurosocial,'1',0,'C');   
			$pdf->Cell(65,4,'SEGURO SOCIAL','LR',0,'L',true);   
			$pdf->Cell(4,4,$seguroprivado,'1',0,'C');    
			$pdf->Cell(65,4,'SEGURO PRIVADO','LR',0,'L',true);
			$pdf->Cell(4,4,$ninguno,'1',0,'C');    
			$pdf->Cell(51,4,'NINGUNO','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','LB',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);    
			$pdf->Cell(65,2,'','B',0,'L',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);    
			$pdf->Cell(65,2,'','B',0,'L',true);
			$pdf->Cell(4,2,'','TB',0,'C',true);     
			$pdf->Cell(51,2,'','BR',1,'L',true);			
			$pdf->Ln(2); 
			//********** ********** ********** FIN COBERTURA MÉDICA ********** ********** **********//			
			
			//**************************************STATUS*******************************************//
			
			$rowP['status']==1 ? $jubilado = "X" : $jubilado = "";
			$rowP['status']==2 ? $pensionadoinvalidez = "X" : $pensionadoinvalidez = "";
			$rowP['status']==3 ? $pensionadovejez = "X" : $pensionadovejez = "";
			$rowP['status']==4 ? $sinbeneficio = "X" : $sinbeneficio = "";
			
			$pdf->AddPage();
			$pdf->Cell(0,7,' STATUS','TLR',1,'C',true); 
			$pdf->Cell(3,4,'','LR',0,'C',true);   
			$pdf->Cell(4,4,$jubilado,'1',0,'C');   
			$pdf->Cell(189,4,'JUBILADO','LR',1,'L',true);
			$pdf->Cell(3,2,'','L',0,'C',true);   
			$pdf->Cell(4,2,'','T',0,'C',true);
			$pdf->Cell(189,2,'','R',1,'L',true);
			$pdf->Cell(3,4,'','LR',0,'C',true); 
			$pdf->Cell(4,4,$pensionadoinvalidez,'1',0,'C');   
			$pdf->Cell(189,4,'PENSIONADO / POR INVALIDEZ','LR',1,'L',true);
			$pdf->Cell(3,2,'','L',0,'C',true);   
			$pdf->Cell(4,2,'','T',0,'C',true);
			$pdf->Cell(189,2,'','R',1,'L',true);
			$pdf->Cell(3,4,'','LR',0,'C',true); 
			$pdf->Cell(4,4,$pensionadovejez,'1',0,'C');   
			$pdf->Cell(189,4,'PENSIONADO / POR VEJEZ','LR',1,'L',true);
			$pdf->Cell(3,2,'','L',0,'C',true);   
			$pdf->Cell(4,2,'','T',0,'C',true);
			$pdf->Cell(189,2,'','R',1,'L',true);
			$pdf->Cell(3,4,'','LR',0,'C',true); 
			$pdf->Cell(4,4,$sinbeneficio,'1',0,'C');   
			$pdf->Cell(189,4,'SIN BENEFICIO','LR',1,'L',true);			
			
			$pdf->Cell(3,2,'','LB',0,'C',true);   
			$pdf->Cell(4,2,'','TB',0,'C',true);    
			$pdf->Cell(65,2,'','B',0,'L',true);   
			$pdf->Cell(4,2,'','B',0,'C',true);    
			$pdf->Cell(65,2,'','B',0,'L',true);
			$pdf->Cell(4,2,'','B',0,'C',true);     
			$pdf->Cell(51,2,'','BR',1,'L',true);
			$pdf->Ln(2); 
			
			//**************************************FIN STATUS*******************************************//
			
			//********** ********** ********** BENEFICIOS ********** ********** **********//			
			$pdf->Cell(136,2,'','TL',0,'L',true);   
			$pdf->Cell(4,2,'','T',0,'C',true);    
			$pdf->Cell(8,2,'','T',0,'L',true);
			$pdf->Cell(4,2,'','T',0,'C',true);   
			$pdf->Cell(44,2,'','TR',1,'L',true);
			if($rowP['beneficios']!=""){
				$sibene = "X";
				$nobene = "";
				$beneficios = $rowP['beneficios'];
			}else{
				$sibene = "";
				$nobene = "X";
				$beneficios = "";
			}
			$pdf->Cell(136,4,'  RECIBO BENEFICIOS DE PROGRAMAS SOCIALES DEL ESTADO','L',0,'L',true);   
			$pdf->Cell(4,4,$sibene,'1',0,'C');    
			$pdf->Cell(12,4,' SI','LR',0,'L',true);
			$pdf->Cell(4,4,$nobene,'1',0,'C');    
			$pdf->Cell(40,4,' NO','LR',1,'L',true);
			
			$pdf->Cell(136,2,'','L',0,'L',true);   
			$pdf->Cell(4,2,'','T',0,'C',true);    
			$pdf->Cell(12,2,'','B',0,'L',true);
			$pdf->Cell(4,2,'','T',0,'C',true);    
			$pdf->Cell(40,2,'','R',1,'L',true);
			
			$pdf->Cell(3,7,'','LR',0,'C',true);
			$pdf->Cell(190,7,'EN EL CASO DE RESPONDER SÍ ¿CUÁLES?','LTR',0,'L');    
			$pdf->Cell(3,7,'','LR',1,'L',true); 
			$pdf->Cell(3,7,'','LR',0,'C',true);
			$pdf->Cell(190,7,utf8_decode($beneficios),'LBR',0,'L');    
			$pdf->Cell(3,7,'','LR',1,'L',true); 
			$pdf->Cell(3,2,'','LB',0,'L',true);
			$pdf->Cell(190,2,'','TB',0,'L',true); 
			$pdf->Cell(3,2,'','RB',1,'L',true);			
			$pdf->Ln(2);
			//********** ********** ********** FIN BENEFICIOS ********** ********** **********//
			
			$pdf->Cell(40,4,'NOTA IMPORTANTE: ','0',0,'L'); 
			$pdf->SetFont('Arial','',12);
			$pdf->Cell(155,4,'La siguiente información solo debe completarse en el caso que el (la) solicitante','0',1,'L'); 
			$pdf->Cell(200,4,'sea menor de edad o requiera del apoyo de un tercero para solicitar la certificación de discapacidad.','0',1,'L'); 
			$pdf->Ln(2);
			
			//********** ********** ********** DATOS DEL ACOMPAÑANTE ********** ********** **********//
			$pdf->SetFont('Arial','B',11);
			if($tipoacompanante == 1){
				$familiarA = 'X';
				$madreA = '';
				$padreA = '';
				$tutorA = '';
				$curadorA = '';
				$otroA = '';
			}elseif($tipoacompanante == 2){
				$familiarA = '';
				$madreA = 'X';
				$padreA = '';
				$tutorA = '';
				$curadorA = '';
				$otroA = '';
			}elseif($tipoacompanante == 3){
				$familiarA = '';
				$madreA = '';
				$padreA = 'X';
				$tutorA = '';
				$curadorA = '';
				$otroA = '';
			}elseif($tipoacompanante == 4){
				$familiarA = '';
				$madreA = '';
				$padreA = '';
				$tutorA = 'X';
				$curadorA = '';
				$otroA = '';
			}elseif($tipoacompanante == 5){
				$familiarA = '';
				$madreA = '';
				$padreA = '';
				$tutorA = '';
				$curadorA = 'X';
				$otroA = '';
			}elseif($tipoacompanante == 6){
				$familiarA = '';
				$madreA = '';
				$padreA = '';
				$tutorA = '';
				$curadorA = '';
				$otroA = 'X';
			}else{
				$familiarA = '';
				$madreA = '';
				$padreA = '';
				$tutorA = '';
				$curadorA = '';
				$otroA = '';
			}
			
			$pdf->Cell(0,2,'','LRT',1,'L',true);
			$pdf->Cell(3,8,'','LR',0,'L',true);
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(190,8,'  DATOS DEL ACOMPAÑANTE','1',0,'L',true); 
			$pdf->SetFillColor(215,215,215);
			$pdf->Cell(3,8,'','LR',1,'L',true);
			$pdf->Cell(3,2,'','LR',0,'L',true);
			$pdf->Cell(190,2,'','LRT',0,'L',true); 
			$pdf->Cell(3,2,'','LR',1,'L',true);
			
			$pdf->Cell(3,5,'','LR',0,'L',true);    
			$pdf->Cell(3,5,'','LR',0,'L',true);    
			$pdf->Cell(5,5,$familiarA,'1',0,'L'); 
			$pdf->Cell(28,5,' FAMILIAR','LR',0,'L',true);
			$pdf->Cell(5,5,$madreA,'1',0,'L');    
			$pdf->Cell(28,5,' MADRE','LR',0,'L',true);
			$pdf->Cell(5,5,$padreA,'1',0,'L'); 
			$pdf->Cell(28,5,' PADRE','LR',0,'L',true); 
			$pdf->Cell(5,5,$tutorA,'1',0,'L'); 
			$pdf->Cell(28,5,' TUTOR','LR',0,'L',true); 
			$pdf->Cell(5,5,$curadorA,'1',0,'L'); 
			$pdf->Cell(28,5,' CURADOR','LR',0,'L',true); 
			$pdf->Cell(5,5,$otroA,'1',0,'L'); 
			$pdf->Cell(17,5,' OTRO','LR',0,'L',true); 
			$pdf->Cell(3,5,'','LR',1,'L',true); 
			
			$pdf->Cell(3,2,'','LR',0,'L',true);
			$pdf->Cell(190,2,'','LR',0,'L',true); 
			$pdf->Cell(3,2,'','LR',1,'L',true);			
			$pdf->Cell(3,10,'','LR',0,'L',true);
			$pdf->Cell(28,10,'  APELLIDOS','1',0,'L',true); 
			$pdf->Cell(162,10,' '.$apellidoacomp,'TR',0,'L');   
			$pdf->Cell(3,10,'','LR',1,'L',true);	
			$pdf->Cell(3,10,'','LR',0,'L',true); 
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(28,10,'  NOMBRES','1',0,'L',true);  
			$pdf->Cell(162,10,' '.$nombreacomp,'TR',0,'L');  
			$pdf->Cell(3,10,'','LR',1,'L',true); 
			$pdf->Cell(3,10,'','LR',0,'L',true);  
			$pdf->Cell(28,10,'  TELÉFONO','1',0,'L',true);  
			$pdf->Cell(70,10,' '.$telefonoacomp,'TR',0,'L');  
			$pdf->Cell(34,10,'CELULAR  ','TLR',0,'R',true); 
			$pdf->Cell(58,10,' '.$celularacomp,'TR',0,'L'); 
			$pdf->Cell(3,10,'','LR',1,'L',true);			
			$pdf->Cell(3,10,'','LR',0,'L',true);  
			$pdf->Cell(55,10,'  CORREO ELECTRÓNICO','1',0,'L',true);  
			$pdf->Cell(135,10,' '.$correroacomp,'TLR',0,'L');   
			$pdf->Cell(3,10,'','LR',1,'L',true);			
			$pdf->Cell(3,2,'','L',0,'L',true);
			$pdf->Cell(190,2,'','TB',0,'L',true); 
			$pdf->Cell(3,2,'','R',1,'L',true);
			 
			//FECHA DE NACIMIENTO
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(3,2,'','LR',0,'L',true);
			$pdf->Cell(190,2,'','LR',0,'L',true);
			$pdf->Cell(3,2,'','LR',1,'L',true);
			
			$pdf->Cell(3,7,'','LR',0,'L',true);
			$pdf->Cell(52,3,'','L',0,'L',true);  
			$pdf->Cell(8,3,'D','B',0,'C',true);    
			$pdf->Cell(8,3,'D','B',0,'C',true);    
			$pdf->Cell(2,3,'','',0,'C',true);    
			$pdf->Cell(8,3,'M','B',0,'C',true);    
			$pdf->Cell(8,3,'M','B',0,'C',true);    
			$pdf->Cell(2,3,'','',0,'C',true);    
			$pdf->Cell(8,3,'A','B',0,'C',true);    
			$pdf->Cell(8,3,'A','B',0,'C',true);    
			$pdf->Cell(8,3,'A','B',0,'C',true);    
			$pdf->Cell(8,3,'A','B',0,'C',true);    
			$pdf->Cell(30,3,'','',0,'C',true);    
			$pdf->Cell(8,3,'','B',0,'C',true);    
			$pdf->Cell(24,3,'','',0,'C',true);   
			$pdf->Cell(8,3,'','',0,'C',true);   
			$pdf->Cell(3,3,'','LR',1,'L',true);
			
			$fecha_nac = $fechanacacomp;
			$aa4 = substr($fecha_nac, -10,1); 
			$aa3 = substr($fecha_nac, -9,1); 
			$aa2 = substr($fecha_nac, -8,1); 
			$aa1 = substr($fecha_nac, -7,1); 
			$ma2 = substr($fecha_nac, -5,1); 
			$ma1 = substr($fecha_nac, -4,1); 
			$da2 = substr($fecha_nac, -2,1); 
			$da1 = substr($fecha_nac, -1,1);
			
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(3,8,'','LR',0,'L',true);  
			$pdf->Cell(52,8,'  FECHA DE NACIMIENTO','LR',0,'L',true);  
			$pdf->Cell(8,8,$da2,'LR',0,'C');   
			$pdf->Cell(8,8,$da1,'LR',0,'C');   
			$pdf->Cell(2,8,'','LR',0,'C',true);    
			$pdf->Cell(8,8,$ma2,'LR',0,'C');   
			$pdf->Cell(8,8,$ma1,'LR',0,'C');   
			$pdf->Cell(2,8,'','LR',0,'C',true);    
			$pdf->Cell(8,8,$aa4,'LR',0,'C');   
			$pdf->Cell(8,8,$aa3,'LR',0,'C');   
			$pdf->Cell(8,8,$aa2,'LR',0,'C');   
			$pdf->Cell(8,8,$aa1,'LR',0,'C');
			
			if($sexoacomp == 'M'){
				$femeA = '';
				$mascA = 'X';
			}else{
				$femeA = 'X';
				$mascA = '';
			}
			$pdf->Cell(30,7,'SEXO:   M  ','LR',0,'R',true);    
			$pdf->Cell(8,7,$mascA,'1',0,'C');   
			$pdf->Cell(18,7,'F  ','LR',0,'R',true);    
			$pdf->Cell(8,7,$femeA,'1',0,'C');
			$pdf->Cell(6,7,'','LR',0,'L',true);
			$pdf->Cell(3,7,'','LR',1,'L',true);
			
			$pdf->Cell(3,7,'','LR',0,'L',true);
			$pdf->Cell(52,2,'','BL',0,'L',true);  
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(2,2,'','B',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(2,2,'','B',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(30,2,'','B',0,'C',true);    
			$pdf->Cell(8,2,'','TB',0,'C',true);    
			$pdf->Cell(18,2,'','',0,'C',true); 
			$pdf->Cell(8,2,'','T',0,'C',true);			
			$pdf->Cell(6,2,'','B',0,'C',true);  
			$pdf->Cell(3,2,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','L',0,'L',true);
			$pdf->Cell(190,2,'','TB',0,'L',true); 
			$pdf->Cell(3,2,'','R',1,'L',true);
			
			//DOCUMENTO DE ID PERSONAL
			if($tipodocacomp == 1){
				$cedulaA = 'X';
				$pasaporteA = '';
			}else{
				$cedulaA = '';
				$pasaporteA = 'X';
			}
			$pdf->Cell(3,2,'','L',0,'C',true);
			$pdf->Cell(190,2,'','LRT',0,'C',true);
			$pdf->Cell(3,2,'','RL',1,'C',true);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(3,6,'','L',0,'C',true);
			$pdf->Cell(100,6,'  DOCUMENTO DE IDENTIDAD PERSONAL:','L',0,'L',true);
			$pdf->Cell(20,6,'CÉDULA ','R',0,'R',true);			
			$pdf->Cell(8,6,$cedulaA,'1',0,'C');    
			$pdf->Cell(50,6,'PASAPORTE ','LR',0,'R',true);    
			$pdf->Cell(8,6,$pasaporteA,'1',0,'C');
			$pdf->Cell(4,6,'','L',0,'L',true);			
			$pdf->Cell(3,6,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','L',0,'C',true);
			$pdf->Cell(190,2,'','LRB',0,'L',true);  
			$pdf->Cell(3,2,'','LR',1,'C',true);  
			
			$pdf->Cell(3,2,'','L',0,'C',true);
			$pdf->Cell(190,2,'','T',0,'L',true);
			$pdf->Cell(3,2,'','R',1,'C',true);
			
			$cedulaA = $cedulaacomp;
			$long = strlen($cedulaA);
			if($long==4){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; $c8  = "";  $c7  = ""; $c6 = ""; 	$c5  = ""; 
				$c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}elseif($long==5){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; $c8  = "";  $c7  = ""; $c6 = "";
				$c5  = substr($cedulaA, -5,1);  $c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);  
				$c2  = substr($cedulaA, -2,1); 	$c1  = substr($cedulaA, -1,1);
			}elseif($long==6){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; $c8  = "";  $c7  = ""; 	$c6  = substr($cedulaA, -6,1); 	$c5  = substr($cedulaA, -5,1); 
				$c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}elseif($long==7){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; $c8  = ""; $c7  = substr($cedulaA, -7,1); 	
				$c6  = substr($cedulaA, -6,1); 	$c5  = substr($cedulaA, -5,1); $c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}elseif($long==8){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9 = ""; 	$c8  = substr($cedulaA, -8,1); 
				$c7  = substr($cedulaA, -7,1); 	$c6  = substr($cedulaA, -6,1); 	$c5  = substr($cedulaA, -5,1); 
				$c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}elseif($long==9){
				$c13 = ""; $c12 = ""; $c11 = "";  $c10 = ""; $c9  = substr($cedulaA, -9,1); 	$c8  = substr($cedulaA, -8,1); 
				$c7  = substr($cedulaA, -7,1); 	$c6  = substr($cedulaA, -6,1); 	$c5  = substr($cedulaA, -5,1); 
				$c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}elseif($long==10){
				$c13 = ""; $c12 = ""; $c11 = "";  
				$c10 = substr($cedulaA, -10,1); $c9  = substr($cedulaA, -9,1); 	$c8  = substr($cedulaA, -8,1); 
				$c7  = substr($cedulaA, -7,1); 	$c6  = substr($cedulaA, -6,1); 	$c5  = substr($cedulaA, -5,1); 
				$c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}elseif($long==11){
				$c13 = ""; $c12 = ""; $c11 = substr($cedulaA, -11,1); 
				$c10 = substr($cedulaA, -10,1); $c9  = substr($cedulaA, -9,1); 	$c8  = substr($cedulaA, -8,1); 
				$c7  = substr($cedulaA, -7,1); 	$c6  = substr($cedulaA, -6,1); 	$c5  = substr($cedulaA, -5,1); 
				$c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}elseif($long==12){
				$c13 = ""; $c12 = substr($cedulaA, -12,1); $c11 = substr($cedulaA, -11,1); 
				$c10 = substr($cedulaA, -10,1); $c9  = substr($cedulaA, -9,1); 	$c8  = substr($cedulaA, -8,1); 
				$c7  = substr($cedulaA, -7,1); 	$c6  = substr($cedulaA, -6,1); 	$c5  = substr($cedulaA, -5,1); 
				$c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}elseif($long==13){
				$c13 = substr($cedulaA, -13,1); $c12 = substr($cedulaA, -12,1); $c11 = substr($cedulaA, -11,1); 
				$c10 = substr($cedulaA, -10,1); $c9  = substr($cedulaA, -9,1); 	$c8  = substr($cedulaA, -8,1); 
				$c7  = substr($cedulaA, -7,1); 	$c6  = substr($cedulaA, -6,1); 	$c5  = substr($cedulaA, -5,1); 
				$c4  = substr($cedulaA, -4,1);  $c3  = substr($cedulaA, -3,1);   $c2  = substr($cedulaA, -2,1); 
				$c1  = substr($cedulaA, -1,1);
			}else{
				$c13 =""; $c12 =""; $c11 =""; $c10 =""; $c9 ="";	$c8 =""; 
				$c7  =""; $c6 =""; $c5 ="";	$c4  =""; $c3 =""; $c2 ="";	$c1  ="";
			}			
			
			//NÚMERO DE DOCUMENTO DE ID PERSONAL
			$pdf->Cell(3,2,'','L',0,'C',true);
			$pdf->Cell(190,2,'','TLR',0,'C',true);
			$pdf->Cell(3,2,'','RL',1,'C',true);			
			
			$pdf->Cell(3,4,'','L',0,'C',true);
			$pdf->Cell(190,4,'  NÚMERO DE DOCUMENTO DE IDENTIDAD PERSONAL                                     NACIONALIDAD','LR',0,'L',true);
			$pdf->Cell(3,4,'','RL',1,'C',true);
			
			$pdf->Cell(3,8,'','LR',0,'L',true);  
			$pdf->Cell(3,8,'','LR',0,'L',true);  
			$pdf->Cell(9,8,$c13,'1',0,'C');   
			$pdf->Cell(9,8,$c12,'1',0,'C');    
			$pdf->Cell(9,8,$c11,'1',0,'C');   
			$pdf->Cell(9,8,$c10,'1',0,'C');   
			$pdf->Cell(9,8,$c9,'1',0,'C');    
			$pdf->Cell(9,8,$c8,'1',0,'C');   
			$pdf->Cell(9,8,$c7,'1',0,'C');   
			$pdf->Cell(9,8,$c6,'1',0,'C');   
			$pdf->Cell(9,8,$c5,'1',0,'C');   
			$pdf->Cell(9,8,$c4,'1',0,'C');   
			$pdf->Cell(9,8,$c3,'1',0,'C');   
			$pdf->Cell(9,8,$c2,'1',0,'C');   
			$pdf->Cell(9,8,$c1,'1',0,'C');   
			$pdf->Cell(8,8,'','LR',0,'C',true);   
			$pdf->Cell(59,8,' '.$nacionalidadacomp,'1',0,'L');  
			$pdf->Cell(3,8,'','LR',0,'L',true); 
			$pdf->Cell(3,8,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','LR',0,'C',true);
			$pdf->Cell(190,2,'','BLR',0,'L',true);
			$pdf->Cell(3,2,'','LR',1,'C',true);
			
			$pdf->Cell(3,2,'','L',0,'C',true);
			$pdf->Cell(190,2,'','T',0,'L',true);
			$pdf->Cell(3,2,'','R',1,'C',true);
			
			//DIRECCIÓN RESIDENCIAL
			$pdf->Cell(3,8,'','LR',0,'C',true);
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(190,8,'  DIRECCIÓN RESIDENCIAL','1',0,'L',true);
			$pdf->SetFillColor(215,215,215);
			$pdf->Cell(3,8,'','LR',1,'C',true);	
			
			$pdf->Cell(3,10,'','LR',0,'L',true);
			$pdf->Cell(35,10,'  URBANIZACIÓN','1',0,'L',true); 
			$pdf->Cell(76,10,' '.utf8_decode($urbanizacionA),'1',0,'L'); 
			$pdf->Cell(20,10,'CALLE ','1',0,'R',true); 
			$pdf->Cell(59,10,' '.utf8_decode($calleA),'1',0,'L'); 
			$pdf->Cell(3,10,'','LR',1,'L',true); 
			$pdf->Cell(3,10,'','LR',0,'L',true); 
			
			$pdf->Cell(36,10,'  EDIFICIO','1',0,'L',true);  
			$pdf->Cell(62,10,' '.utf8_decode($edificioA),'1',0,'L'); 
			$pdf->Cell(38,10,'APTO / CASA N° ','1',0,'R',true); 
			$pdf->Cell(54,10,' '.$numeroA,'1',0,'L'); 
			$pdf->Cell(3,10,'','LR',1,'L',true);
			
			$pdf->Cell(3,10,'','LR',0,'L',true); 
			$pdf->Cell(41,10,'  CORREGIMIENTO','1',0,'L',true);  
			$pdf->Cell(65,10,' '.utf8_decode($corregimientoA),'1',0,'L');  
			$pdf->Cell(32,10,'DISTRITO ','1',0,'R',true); 
			$pdf->Cell(52,10,' '.utf8_decode($distritoA),'1',0,'L'); 
			$pdf->Cell(3,10,'','LR',1,'L',true);
			
			$pdf->Cell(3,10,'','LR',0,'L',true);  
			$pdf->Cell(30,10,'  PROVINCIA','1',0,'L',true);  
			$pdf->Cell(160,10,' '.utf8_decode($provinciaA),'1',0,'L');   
			$pdf->Cell(3,10,'','LR',1,'L',true);
			
			//$area = $rowA['area'];
			if($areaA == 'Urbana'){
				$urbana   = "X";
				$rural    = "";
				$indigena = "";
			}elseif($areaA == 'Rural'){
				$urbana   = "";
				$rural    = "X";
				$indigena = "";
			}elseif($areaA == 'Indígena'){
				$urbana   = "";
				$rural    = "";
				$indigena = "X";
			}
			
			$pdf->Cell(3,2,'','LR',0,'L',true);  
			$pdf->Cell(19,2,'','LRT',0,'L',true);  
			$pdf->Cell(35,2,'','T',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(46,2,'','T',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(46,2,'','T',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(26,2,'','T',0,'L');  
			$pdf->Cell(3,2,'','LR',1,'L',true);
			
			$pdf->Cell(3,7,'','LR',0,'L',true);  
			$pdf->Cell(19,7,'  ZONA','LR',0,'L',true);  
			$pdf->Cell(35,7,'URBANA ','LR',0,'R');  
			$pdf->Cell(6,7,$urbana,'LR',0,'L');  
			$pdf->Cell(46,7,'RURAL ','LR',0,'R');  
			$pdf->Cell(6,7,$rural,'LR',0,'L');  
			$pdf->Cell(46,7,'INDÍGENA ','LR',0,'R');  
			$pdf->Cell(6,7,$indigena,'LR',0,'L');  
			$pdf->Cell(26,7,'','LR',0,'L');  
			$pdf->Cell(3,7,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','LR',0,'L',true);  
			$pdf->Cell(19,2,'','LRB',0,'L',true);  
			$pdf->Cell(35,2,'','B',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(46,2,'','B',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(46,2,'','B',0,'L');  
			$pdf->Cell(6,2,'','TB',0,'L');  
			$pdf->Cell(26,2,'','B',0,'L');  
			$pdf->Cell(3,2,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','L',0,'L',true);
			$pdf->Cell(190,2,'','TB',0,'L',true);
			$pdf->Cell(3,2,'','R',1,'L',true);
			 
			$pdf->Cell(3,6,'','LR',0,'L',true);
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(190,6,'  EN CASO DE TUTOR O CURADOR COMPLETAR LA SIGUIENTE INFORMACIÓN:','1',0,'L',true);
			$pdf->SetFillColor(215,215,215);
			$pdf->Cell(3,6,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','LR',0,'L',true);
			$pdf->Cell(30,2,'','TL',0,'L',true);
			$pdf->Cell(6,2,'','TB',0,'L',true);
			$pdf->Cell(30,2,'','T',0,'L',true);
			$pdf->Cell(6,2,'','TB',0,'L',true);
			$pdf->Cell(53,2,'','T',0,'L',true);
			$pdf->Cell(7,2,'','TB',0,'L',true);
			$pdf->Cell(7,2,'','TB',0,'L',true);
			$pdf->Cell(1,2,'','T',0,'L',true);
			$pdf->Cell(7,2,'','TB',0,'L',true);
			$pdf->Cell(7,2,'','TB',0,'L',true);
			$pdf->Cell(1,2,'','T',0,'L',true);
			$pdf->Cell(7,2,'','TB',0,'L',true);
			$pdf->Cell(7,2,'','TB',0,'L',true);
			$pdf->Cell(7,2,'','TB',0,'L',true);
			$pdf->Cell(7,2,'','TB',0,'L',true);
			$pdf->Cell(7,2,'','T',0,'L',true);
			$pdf->Cell(3,2,'','LR',1,'L',true);
			 
			if($modotutoracomp != ""){
				if($rowA['modo_tutor'] == 1){
					$provicional = "X";
					$definitivo  = "";
				}elseif($rowA['modo_tutor'] == 2){
					$provicional = "";
					$definitivo  = "X";
				}else{
					$provicional = "";
					$definitivo  = "";
				}
				$tutor = "X";
				$sentencia 		   = $rowA['sentencia'];
				$juzgado 		   = $rowA['juzgado'];
				$circuito_judicial = $rowA['circuito_judicial'];
				$distrito_judicial = $rowA['distrito_judicial'];
			}else{
				$provicional = "";
				$definitivo  = "";
				$tutor = "";
				$sentencia 		   = "";
				$juzgado 		   = "";
				$circuito_judicial = "";
				$distrito_judicial = "";
			}
			
			$long = strlen($sentencia);
			if($long==3){
				$s1  = substr($sentencia, -1,1);
				$s2  = substr($sentencia, -2,1);
				$s3  = substr($sentencia, -3,1);
				$s4  = "";
				$s5  = "";
				$s6  = "";
				$s7  = "";
				$s8  = "";
			}elseif($long==4){
				$s1  = substr($sentencia, -1,1);
				$s2  = substr($sentencia, -2,1);
				$s3  = substr($sentencia, -3,1);
				$s4  = substr($sentencia, -4,1);
				$s5  = "";
				$s6  = "";
				$s7  = "";
				$s8  = "";
			}elseif($long==5){
				$s1  = substr($sentencia, -1,1);
				$s2  = substr($sentencia, -2,1);
				$s3  = substr($sentencia, -3,1);
				$s4  = substr($sentencia, -4,1);
				$s5  = substr($sentencia, -5,1);
				$s6  = "";
				$s7  = "";
				$s8  = "";
			}elseif($long==6){
				$s1  = substr($sentencia, -1,1);
				$s2  = substr($sentencia, -2,1);
				$s3  = substr($sentencia, -3,1);
				$s4  = substr($sentencia, -4,1);
				$s5  = substr($sentencia, -5,1);
				$s6  = substr($sentencia, -6,1);
				$s7  = "";
				$s8  = "";
			}elseif($long==7){
				$s1  = substr($sentencia, -1,1);
				$s2  = substr($sentencia, -2,1);
				$s3  = substr($sentencia, -3,1);
				$s4  = substr($sentencia, -4,1);
				$s5  = substr($sentencia, -5,1);
				$s6  = substr($sentencia, -6,1);
				$s7  = substr($sentencia, -7,1);
				$s8  = "";
			}elseif($long==8){ 
				$s1  = substr($sentencia, -1,1);
				$s2  = substr($sentencia, -2,1);
				$s3  = substr($sentencia, -3,1);
				$s4  = substr($sentencia, -4,1);
				$s5  = substr($sentencia, -5,1);
				$s6  = substr($sentencia, -6,1);
				$s7  = substr($sentencia, -7,1);
				$s8  = substr($sentencia, -8,1);
			}else{
				$s1  = "";
				$s2  = "";
				$s3  = "";
				$s4  = "";
				$s5  = "";
				$s6  = "";
				$s7  = "";
				$s8  = "";
			}
			
			$pdf->Cell(3,6,'','LR',0,'L',true);
			$pdf->Cell(30,6,' PROVICIONAL','LR',0,'L',true);
			$pdf->Cell(6,6,$provicional,'1',0,'L');
			$pdf->Cell(30,6,'DEFINITIVO','LR',0,'R',true);
			$pdf->Cell(6,6,$definitivo,'1',0,'L');
			$pdf->Cell(53,6,'N° DE SENTENCIA ','LR',0,'R',true);
			$pdf->Cell(7,6,$s8,'1',0,'C');
			$pdf->Cell(7,6,$s7,'1',0,'C');
			$pdf->Cell(1,6,'','LR',0,'C',true);
			$pdf->Cell(7,6,$s6,'1',0,'C');
			$pdf->Cell(7,6,$s5,'1',0,'C');
			$pdf->Cell(1,6,'','LR',0,'C',true);
			$pdf->Cell(7,6,$s4,'1',0,'C');
			$pdf->Cell(7,6,$s3,'1',0,'C');
			$pdf->Cell(7,6,$s2,'1',0,'C');
			$pdf->Cell(7,6,$s1,'1',0,'C');
			$pdf->Cell(7,6,'','LR',0,'C',true);
			$pdf->Cell(3,6,'','LR',1,'C',true);
			
			$pdf->Cell(3,2,'','LR',0,'L',true);
			$pdf->Cell(30,2,'','BL',0,'L',true);
			$pdf->Cell(6,2,'','BT',0,'L',true);
			$pdf->Cell(30,2,'','B',0,'L',true);
			$pdf->Cell(6,2,'','BT',0,'L',true);
			$pdf->Cell(53,2,'','B',0,'L',true);
			$pdf->Cell(7,2,'','BT',0,'L',true);
			$pdf->Cell(7,2,'','BT',0,'L',true);
			$pdf->Cell(1,2,'','B',0,'L',true);
			$pdf->Cell(7,2,'','BT',0,'L',true);
			$pdf->Cell(7,2,'','BT',0,'L',true);
			$pdf->Cell(1,2,'','B',0,'L',true);
			$pdf->Cell(7,2,'','BT',0,'L',true);
			$pdf->Cell(7,2,'','BT',0,'L',true);
			$pdf->Cell(7,2,'','BT',0,'L',true);
			$pdf->Cell(7,2,'','BT',0,'L',true);
			$pdf->Cell(7,2,'','B',0,'L',true);
			$pdf->Cell(3,2,'','LR',1,'L',true); 
			
			$pdf->Cell(3,2,'','L',0,'L',true);
			$pdf->Cell(190,2,'','TB',0,'L',true); 
			$pdf->Cell(3,2,'','R',1,'L',true); 
			
			$pdf->Cell(3,10,'','LR',0,'L',true);
			$pdf->Cell(24,10,'JUZGADO','LRT',0,'L',true);
			$pdf->Cell(38,10,$juzgado,'1',0,'L');
			$y = $pdf->GetY(); $x = $pdf->GetX();
			$pdf->MultiCell(24,5,'CIRCUITO JUDICIAL','LRT',0,'L',true);
			$pdf->SetY($y); $pdf->SetX($x+24); 
			$pdf->Cell(40,10,$circuito_judicial,'1',0,'L');
			$y = $pdf->GetY(); $x = $pdf->GetX();
			$pdf->MultiCell(24,5,'DISTRITO JUDICIAL','LRT',0,'L',true);
			$pdf->SetY($y); $pdf->SetX($x+24); 
			$pdf->Cell(40,10,$distrito_judicial,'1',0,'L');
			$pdf->Cell(3,10,'','LR',1,'L',true);
			
			$pdf->Cell(3,4,'','LB',0,'L',true);
			$pdf->Cell(190,4,'','TB',0,'L',true); 
			$pdf->Cell(3,4,'','RB',1,'L',true);
			
			$pdf->Ln(2);
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(0,8,'  LUGAR Y FECHA DE SOLICITUD','1',1,'L',true);
			$pdf->SetFillColor(215,215,215);
			
			
			$pdf->Cell(3,2,'','LT',0,'L',true);
			$pdf->Cell(190,2,'','TB',0,'L',true); 
			$pdf->Cell(3,2,'','RT',1,'L',true);
			
			$pdf->Cell(3,4,'','LR',0,'L',true);
			$pdf->Cell(120,4,'','LR',0,'L');
			$pdf->Cell(4,4,'','LR',0,'L',true);
			$pdf->Cell(20,4,'','L',0,'L');
			$pdf->Cell(46,4,'','TR',0,'R');
			$pdf->Cell(3,4,'','LR',1,'L',true);
			
			$dia = $arrfechasol[2];
			$mes = $arrfechasol[1];
			$anio = $arrfechasol[0];
			
			$pdf->Cell(3,8,'','LR',0,'L',true);
			$pdf->Cell(18,8,'LUGAR:','L',0,'L');
			$pdf->Cell(102,8,utf8_decode($regional),'R',0,'L');	
			$pdf->Cell(4,8,'','LR',0,'L',true);			
			
			$pdf->Cell(20,8,' FECHA:','L',0,'L');
			$pdf->Cell(46,8,$dia.'  /  '.$mes.'  /  '.$anio.'   ','R',0,'R');
			$pdf->Cell(3,8,'','LR',1,'L',true);
			
			$pdf->Cell(3,4,'','LR',0,'L',true);
			$pdf->Cell(120,4,'','LR',0,'L');
			$pdf->Cell(4,4,'','LR',0,'L',true);
			$pdf->Cell(20,4,'','L',0,'L');
			$pdf->Cell(46,4,'DÍA   MES   AÑO   ','RB',0,'R');
			$pdf->Cell(3,4,'','LR',1,'L',true);
			
			$pdf->Cell(3,2,'','LB',0,'L',true);
			$pdf->Cell(120,2,'','TB',0,'L',true);
			$pdf->Cell(4,2,'','B',0,'L',true);			
			$pdf->Cell(66,2,'','TB',0,'L',true);			
			$pdf->Cell(3,2,'','RB',1,'L',true);
		}
	}
	
	$pdf->Ln(20);	
	//$pdf->SetY(280);
	$pdf->Cell(95,8,'FIRMA DEL SOLICITANTE / ACOMPAÑANTE','T',0,'L');  
	
	$pdf->Output('I',"SOLICITUD.pdf"); 	 
?>