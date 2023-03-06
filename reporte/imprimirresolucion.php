<?php













	include_once("../controller/funciones.php");
	include_once("../controller/conexion.php");
	verificarLogin(); 
    include_once("../fpdf/fpdf.php");
    $id 		 = $_GET['id']; 
	   
	class PDF extends FPDF{
    	// Cabecera de p�gina
    	function Header(){	
    	}
    	// Pie de p�gina
    	function Footer(){
    	}
		
		function GetMultiCellHeight($w, $h, $txt, $border=null, $align='J') {
			// Calculate MultiCell with automatic or explicit line breaks height
			// $border is un-used, but I kept it in the parameters to keep the call
			//   to this function consistent with MultiCell()
			$cw = &$this->CurrentFont['cw'];
			if($w==0)
				$w = $this->w-$this->rMargin-$this->x;
			$wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
			$s = str_replace("\r",'',$txt);
			$nb = strlen($s);
			if($nb>0 && $s[$nb-1]=="\n")
				$nb--;
			$sep = -1;
			$i = 0;
			$j = 0;
			$l = 0;
			$ns = 0;
			$height = 0;
			while($i<$nb){
				// Get next character
				$c = $s[$i];
				if($c=="\n"){
					// Explicit line break
					if($this->ws>0)
					{
						$this->ws = 0;
						$this->_out('0 Tw');
					}
					//Increase Height
					$height += $h;
					$i++;
					$sep = -1;
					$j = $i;
					$l = 0;
					$ns = 0;
					continue;
				}
				if($c==' '){
					$sep = $i;
					$ls = $l;
					$ns++;
				}
				$l += $cw[$c];
				if($l>$wmax){
					// Automatic line break
					if($sep==-1){
						if($i==$j)
							$i++;
						if($this->ws>0)
						{
							$this->ws = 0;
							$this->_out('0 Tw');
						}
						//Increase Height
						$height += $h;
					}else{
						if($align=='J'){
							$this->ws = ($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
							$this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
						}
						//Increase Height
						$height += $h;
						$i = $sep+1;
					}
					$sep = -1;
					$j = $i;
					$l = 0;
					$ns = 0;
				}else
					$i++;
			}
			// Last chunk
			if($this->ws>0){
				$this->ws = 0;
				$this->_out('0 Tw');
			}
			//Increase Height
			$height += $h;

			return $height;
		}
		
		function Rotate($angle,$x=-1,$y=-1)
		{
			if($x==-1)
				$x=$this->x;
			if($y==-1)
				$y=$this->y;
			if($this->angle!=0)
				$this->_out('Q');
			$this->angle=$angle;
			if($angle!=0)
			{
				$angle*=M_PI/180;
				$c=cos($angle);
				$s=sin($angle);
				$cx=$x*$this->k;
				$cy=($this->h-$y)*$this->k;
				$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
			}
		}

		function _endpage()
		{
			if($this->angle!=0)
			{
				$this->angle=0;
				$this->_out('Q');
			}
			parent::_endpage();
		}
		
		var $angle=0;
		
		function RotatedText($x,$y,$txt,$angle)
		{
			//Text rotated around its origin
			$this->Rotate($angle,$x,$y);
			$this->Text($x,$y,$txt);
			$this->Rotate(0);
		}

		function RotatedImage($file,$x,$y,$w,$h,$angle)
		{
			//Image rotated around its upper-left corner
			$this->Rotate($angle,$x,$y);
			$this->Image($file,$x,$y,$w,$h);
			$this->Rotate(0);
		}
    }    
    
    //Creaci�n del objeto de la clase heredada
    //$pdf = new PDF('P', 'mm', array(215.9,355.6));
	$pdf = new PDF('P', 'mm', 'Legal');
    $pdf->AliasNbPages();
    $pdf->AddPage();   
    $pdf->SetFillColor(255,255,255);    
	$pdf->SetTextColor(0,0,0);	
	//Establecemos los m�rgenes izquierda, arriba y derecha:
	$pdf->SetMargins(8, 15 , 8);
	//Establecemos el margen inferior:
	$pdf->SetAutoPageBreak(true,30);
    $pdf->SetFont('Arial','B',11);
    // T�tulo
	$pdf->Ln(22);
	$pdf->Cell(0,5,utf8_decode('REPÚBLICA DE PANAMÁ '),'0',1,'C');
	$pdf->Cell(0,5,utf8_decode('SECRETARÍA NACIONAL DE DISCAPACIDAD '),'0',1,'C');   
	$pdf->Cell(0,5,utf8_decode('Dirección Nacional de Certificaciones '),'0',1,'C');   
	$pdf->Cell(0,5,utf8_decode('Certificado de Discapacidad '),'0',1,'C');   
   //$pdf->Ln(5);
   //Sello
    $pdf->RotatedImage('../images/reportes/sello.png',169,315,-350,26,-20);
	function letras($numuero){
		switch ($numuero)
		{
			case 20:{	$numu = "Veinte";		break;	}
			case 19:{	$numu = "Diecinueve";	break;	}
			case 18:{	$numu = "Dieciocho";	break;	}
			case 17:{	$numu = "Diecisiete";	break;	}
			case 16:{	$numu = "Dieciseis";	break;	}
			case 15:{	$numu = "Quince";		break;	}
			case 14:{	$numu = "Catorce";		break;	}
			case 13:{	$numu = "Trece";		break;	}
			case 12:{	$numu = "Doce";			break;	}
			case 11:{	$numu = "Once";			break;	}
			case 10:{	$numu = "Diez";		break;	}
			case 9:{	$numu = "Nueve";	break;	}
			case 8:{	$numu = "Ocho";		break;	}
			case 7:{	$numu = "Siete";	break;	}
			case 6:{	$numu = "Seis";		break;	}
			case 5:{	$numu = "Cinco";	break;	}
			case 4:{	$numu = "Cuatro";	break;	}
			case 3:{	$numu = "Tres";		break;	}
			case 2:{	$numu = "Dos";		break;	}
			case 1:{	$numu = "Un";		break;	}       
			case 0:{	$numu = "";			break;	}       
		}
		return $numu;   
	}
	$meses = array('','enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	
	//DATOS DEL SOLICITANTE
	$queryS = " SELECT s.id AS id, CONCAT(p.nombre,' ',p.apellidopaterno, ' ',p.apellidomaterno) AS paciente, 
				DATE_FORMAT(s.fecha_cita,'%Y-%m-%d') AS fecha, p.id as idpaciente, s.iddiscapacidad as iddiscapacidad, 
				GROUP_CONCAT(CONCAT(m.id,'|',m.nombre,' ',m.apellido,'|', REPLACE(e.nombre,',',' / '))) AS medicos, 
				s.sala AS sala, r.nombre AS regional, d.nombre AS discapacidad, f.codigojunta, f.cif, g.nro_expediente, 
				g.nro_resolucion, g.observacion, f.fechaemision, f.fechavencimiento, g.validez_certificado, g.validez_tipo, f.diagnostico, f.ciudad,
				f.duracion, f.tipoduracion
				FROM solicitudes s 
				INNER JOIN pacientes p ON p.id = s.idpaciente 
				INNER JOIN discapacidades d ON d.id = s.iddiscapacidad
				LEFT JOIN regionales r ON r.id = s.regional
				LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta) 
				LEFT JOIN especialidades e ON e.id = m.especialidad
				LEFT JOIN evaluacion f ON p.id = f.idpaciente AND f.idpaciente = s.idpaciente AND s.id = f.idsolicitud 	
				LEFT JOIN resolucion g ON s.id = g.idsolicitud
				WHERE s.id = ".$id." ";
				
	$resultS = $mysqli->query($queryS); 
	if($rowS = $resultS->fetch_assoc()){
		$id 		    	= $rowS['id'];
		$idpaciente	    	= $rowS['idpaciente'];
		$paciente	    	= $rowS['paciente'];
		$fecha 	        	= $rowS['fecha'];
		$medicos 			= $rowS['medicos'];
		$discapacidad 		= $rowS['discapacidad'];
		$iddiscapacidad 	= $rowS['iddiscapacidad'];
		$sala           	= $rowS['sala'];
		$regional       	= $rowS['regional'];
		$codigojunta    	= $rowS['codigojunta'];
		$cif    			= $rowS['cif'];
		$expediente	 		= $rowS['nro_expediente'];
		$resolucion			= $rowS['nro_resolucion'];
		$observacion    	= $rowS['observacion'];
		$fechaemision    	= $rowS['fechaemision'];
		$fechavencimiento   = $rowS['fechavencimiento'];
		$diagnostico   		= $rowS['diagnostico'];
		//$validezc   		= $rowS['validez_certificado']; //N�mero de a�os o meses
		//$validezt   		= $rowS['validez_tipo'];		//A�os o mes (A/M)
		$validezc 			= $rowS['duracion'];
		$validezt 			= $rowS['tipoduracion'];
		$ciudad   			= $rowS['ciudad'];
		
		//DIAGNOSTICOS
		$arrdiagnostico = explode(',',$diagnostico); 
		$newdiagnostico = array_filter($arrdiagnostico);
		$listadodiag 	= implode(", ", $newdiagnostico);
		if($listadodiag != ''){			
			$queryDiag = " SELECT group_concat(' ',codigo) AS codigosDiag FROM enfermedades WHERE id IN (".$listadodiag.") ";
			//echo $listadodiag;
			//print_r($diagnostico);
			$resultDiag = $mysqli->query($queryDiag); 
			if($rowDiag = $resultDiag->fetch_assoc()){
				$codigosDiag 	= $rowDiag['codigosDiag'];
			}
		}else{
			$codigosDiag = '';
		}
		
		$datetime1 = explode('-',$fechavencimiento);
		$datetime2 = explode('-',$fechaemision);
		//$vencimiento = $rowS['validez_certificado'];//$datetime1[1] - $datetime2[2];
		$vencimiento = $validezc;
		$validezt == 'M' ? $validez_tipo = 'Meses.' : $validez_tipo = 'Años.';		
		$letras = letras($vencimiento);
		
		$queryP = " SELECT nombre, apellidopaterno, apellidomaterno, cedula, tipo_documento, fecha_nac, sexo, telefono, celular, correo, nacionalidad, estado_civil,
					condicion_actividad, categoria_actividad, cobertura_medica, beneficios, idacompanante, discapacidades, direccion
					FROM pacientes WHERE id = '$idpaciente'";
					
		$resultP = $mysqli->query($queryP); 
		if($rowP = $resultP->fetch_assoc()){			
			$nombre			 = $rowP['nombre'];
			$apellidopaterno = $rowP['apellidopaterno'];
			$apellidomaterno = $rowP['apellidomaterno'];
			$cedula			 = $rowP['cedula'];
			
			$query_direccion = "SELECT dir.provincia, dir.distrito,dir.corregimiento,dir.area,d.urbanizacion,d.calle,d.edificio,d.numero
								FROM direccion d LEFT JOIN direcciones dir ON dir.id = d.iddireccion 
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
		}		
	}
	$pdf->Cell(142,6,'','0',0,'L');
    $pdf->SetTextColor(231,19,31);	
	$pdf->Cell(30,6,utf8_decode('Resolución N°  '),'0',0,'L'); 
	$pdf->Cell(10,6,utf8_decode($resolucion),'0',1,'L');
	//CAJA 1
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(37,5,'Nombre Completo:','TL',0,'L');  
	$pdf->SetFont('Arial','',11); 
	$pdf->Cell(163,5,utf8_decode(trim($nombre).' '.trim($apellidopaterno).' '.trim($apellidomaterno)),'TR',1,'L'); 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(56,5,utf8_decode('CIP o identificación personal: '),'L',0,'L');  
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(144,5,$cedula,'R',1,'L');
	$direccion = '                   Urbanización '.utf8_decode(trim($urbanizacion));
	if($calle != ''){
		$direccion .= ', Calle '.utf8_decode(trim($calle));
	}
	if($edificio != ''){
		$direccion .= ', Edificio '.utf8_decode(trim($edificio));
	}
	if($numero != ''){
		$direccion .= ', Casa '.utf8_decode(trim($numero));
	}
	if($corregimiento != ''){
		$direccion .= ', Corregimiento '.utf8_decode(trim($corregimiento));
	}	
	if($distrito != ''){
		$direccion .= ', Distrito '.utf8_decode(trim($distrito));
	}
	if($provincia != ''){
		$direccion .= ', Provincia '.utf8_decode(trim($provincia));
	}
	//$direccion = '                   Urbanizaci�n '.utf8_decode(trim($urbanizacion)).', Calle '.utf8_decode(trim($calle)).', Casa '.utf8_decode(trim($numero)).', Corregimiento '.utf8_decode(trim($corregimiento)).', Distrito '.utf8_decode(trim($distrito)).', Provincia '.utf8_decode(trim($provincia)).'.';
	//$h = $pdf->GetMultiCellHeight(175,5,utf8_decode('Urbanizaci�n '.$urbanizacion.', Calle '.$calle.', Casa '.$numero.', Corregimiento '.$corregimiento.', Distrito '.$distrito.', Provincia '.$provincia.'.'),'R',1,'L');
	$pdf->SetFont('Arial','B',11);
	$y = $pdf->GetY(); $x = $pdf->GetX();
	
	//$y = $pdf->GetY(); $x = $pdf->GetX(); $pdf->SetY($y+5);
	//$pdf->Cell(21,5,'','L',0,'L');
	
    $pdf->SetFont('Arial','',11);
	$pdf->MultiCell(200,5,utf8_decode($direccion),'RL',1,'L');
	$yd = $pdf->GetY(); $xd = $pdf->GetX();
	$pdf->SetY($y); $pdf->SetX($x);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(20,5,'Domicilio: ','',0,'L');
	$pdf->SetY($yd); $pdf->SetX($xd);
	
	/*
	$dircompl = '';
	if($calle != ''){
		$dircompl .= ', Calle '.$calle;
	}
	if($numero != ''){
		$dircompl .= ', Casa '.$numero;
	}
	if($corregimiento != ''){
		$dircompl .= ', Corregimiento '.$corregimiento;
	}
	if($distrito != ''){
		$dircompl .= ', Distrito '.$distrito;
	}
	if($provincia != ''){
		$dircompl .= ', Provincia '.$provincia;
	}
	$domicilio = 'Urbanizaci�n '.utf8_decode($urbanizacion.''.$dircompl.'.');
	$wdomic = strlen('Urbanizaci�n '.$urbanizacion.''.$dircompl.'.');
	if($wdomic>300){ //101
		$celda1 = substr($domicilio, 0, 100);
		$celda2 = substr($domicilio, 100, 200);
		$celda2 = substr($domicilio, 200, -1);
		$pdf->Cell(180,5,$celda1,'R',1,'L');
		$pdf->Cell(200,5,$celda2,'LR',1,'L');
		$pdf->Cell(200,5,$celda3,'LR',1,'L');
	}elseif($wdomic>72 && $wdomic<192){
		$celda1 = substr($domicilio, 0, 85);
		$celda2 = substr($domicilio, 85, 200);
		$pdf->Cell(180,5,$celda1,'R',1,'L');
		$pdf->Cell(200,5,$celda2,'LR',1,'L');
	}elseif($wdomic<72){
		$celda1 = substr($domicilio, 0, 100);
		$pdf->Cell(180,5,$celda1,'R',1,'L');
	}
	*/
	//$pdf->MultiCell(175,5,'Urbanizaci�n '.utf8_decode($urbanizacion.', Calle '.$calle.', Casa '.$numero.', Corregimiento '.$corregimiento.', Distrito '.$distrito.', Provincia '.$provincia.'.'),'R',1,'L');
	 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(41,5,utf8_decode('Fecha de evaluación: '),'L',0,'L');  
	$pdf->SetFont('Arial','',11);
	$arrfechaevaluacion = explode('-',$fecha);
	$mesev = (int)$arrfechaevaluacion[1];
	$pdf->Cell(159,5,utf8_decode($arrfechaevaluacion[2].' de '.$meses[$mesev].' de '.$arrfechaevaluacion[0]),'R',1,'L'); 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(56,5,utf8_decode('Código de Junta Evaluadora:  '),'BL',0,'L');  
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(144,5,utf8_decode($codigojunta),'BR',1,'L');
	$pdf->Cell(143,1.6,'','0',1,'L');
	
	//CAJA 2 
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(38,5,utf8_decode('Diagnóstico CIE-10: '),'TL',0,'L'); 
	$pdf->SetFont('Arial','',11);	
	$pdf->Cell(162,5,utf8_decode($codigosDiag),'TR',1,'L'); 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(63,5,utf8_decode('Diagnóstico Funcional (CIF):  '),'L',0,'L');  
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(137,5,'','R',1,'L'); 
	$pdf->SetFont('Arial','B',11);
	$b = '';
	$d = '';
	$s = '';
	$e = '';
	$cif = json_decode($cif);
	
	if(!empty($cif)){
		foreach($cif as $clave => $valor) {
			if($clave == 'b'){
				$bi = 0;
				foreach($valor as $claveb => $valorb) {
					if($bi == 0){
						$b .= $valorb->codigocif;					
					}else{
						$b .= '  '.$valorb->codigocif;
					}
					if($valorb->c1 != ''){
						$b .= '.'.$valorb->c1;
					}
					if($valorb->c2 != ''){
						$b .= '.'.$valorb->c2;
					}
					if($valorb->c3 != ''){
						$b .= '.'.$valorb->c3;
					}
					$bi++;				
				}
			}
			if($clave == 'd'){
				$di = 0;
				foreach($valor as $claved => $valord) {
					if($di == 0){
						$d .= $valord->codigocif;
					}else{
						$d .= '  '.$valord->codigocif;
					}
					if($valord->c1 != ''){
						$d .= '.'.$valord->c1;
					}
					if($valord->c2 != ''){
						$d .= ''.$valord->c2;
					}
					if($valord->c3 != ''){
						$d .= ''.$valord->c3;
					}
					$di++;
				}
			}
			if($clave == 's'){
				$si = 0;
				foreach($valor as $claves => $valors) {
					if($si == 0){
						$s .= $valors->codigocif;
					}else{
						$s .= '  '.$valors->codigocif;
					}
					if($valors->c1 != ''){
						$s .= '.'.$valors->c1;
					}
					if($valors->c2 != ''){
						$s .= ''.$valors->c2;
					}
					if($valors->c3 != ''){
						$s .= ''.$valors->c3;
					}
					$si++;
				}
			}
			if($clave == 'e'){
				$ei = 0;
				foreach($valor as $clavee => $valore) {
					if($ei == 0){
						$e .= $valore->codigocif;
					}else{
						$e .= '  '.$valore->codigocif;
					}
					if($valore->c1 != ''){
						$e .= ''.$valore->c1;
					}
					if($valore->c2 != ''){
						$e .= ''.$valore->c2;
					}
					if($valore->c3 != ''){
						$e .= ''.$valore->c3;
					}
					$ei++;
				}
			}
		}
	}

	
	$wfyc = strlen($b);
	$weyc = strlen($s);
	$wayp = strlen($d);
	$wfa  = strlen($e);
	
	$pdf->Cell(45,5,'Funciones Corporales: ','L',0,'L');  
	$pdf->SetFont('Arial','',11);	
	//$pdf->Cell(148,5,$b,'R',1,'L'); 
	if($wfyc>300){ //101
		$celda1 = substr($b, 0, 72);
		$celda2 = substr($b, 72, 172);
		$celda2 = substr($b, 172, -1);
		$pdf->Cell(155,5,utf8_decode($celda1),'R',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda2),'LR',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda3),'LR',1,'L');
	}elseif($wfyc>72 && $wfyc<172){
		$celda1 = substr($b, 0, 72);
		$celda2 = substr($b, 72, 172);
		$pdf->Cell(155,5,utf8_decode($celda1),'R',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda2),'LR',1,'L');
	}elseif($wfyc<72){
		$celda1 = substr($b, 0, 72);
		$pdf->Cell(155,5,utf8_decode($celda1),'R',1,'L');
	}
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(47,5,'Estructuras Corporales:  ','L',0,'L');  
	$pdf->SetFont('Arial','',11);
	//$pdf->Cell(146,5,$s,'R',1,'L');  
	if($weyc>300){ //101
		$celda1 = substr($s, 0, 72);
		$celda2 = substr($s, 72, 172);
		$celda2 = substr($s, 172, -1);
		$pdf->Cell(153,5,utf8_decode($celda1),'R',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda2),'LR',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda3),'LR',1,'L');
	}elseif($weyc>72 && $weyc<172){
		$celda1 = substr($s, 0, 72);
		$celda2 = substr($s, 72, 172);
		$pdf->Cell(153,5,utf8_decode($celda1),'R',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda2),'LR',1,'L');
	}elseif($weyc<72){
		$celda1 = substr($s, 0, 72);
		$pdf->Cell(153,5,utf8_decode($celda1),'R',1,'L');
	}
	$pdf->SetFont('Arial','B',11);
	$h = $pdf->GetMultiCellHeight(143,5,utf8_decode($d),'R',1,'L');
	$pdf->Cell(50,5,utf8_decode('Actividad y Participación: '),'L',0,'L');  //76 
	$pdf->SetFont('Arial','',11);
	
	$arrayd = explode('  ',$d);
	$countd = count($arrayd);
	$celda1 = ''; $celda2 = ''; $celda3 = '';
	$y = 0; $z = 0;
	for($i=0; $i < $countd; $i++){
		if(strlen($celda1) <= 70 ){
			if($i == 0){
				$celda1 .= $arrayd[$i];
			}else{
				$celda1 .= '  '.$arrayd[$i];
			}
		}elseif(strlen($celda1) <= 160 && strlen($celda2) <= 98 ){
			if($y == 0){
				$celda2 .= $arrayd[$i];
			}else{
				$celda2 .= '  '.$arrayd[$i];
			}
			$y++;
		}else{
			if($z == 0){
				$celda3 .= $arrayd[$i];
			}else{
				$celda3 .= '  '.$arrayd[$i];
			}
			$z++;
		}
	}
	$pdf->Cell(150,5,utf8_decode($celda1),'R',1,'L');
	if($celda2 != ''){
		$pdf->Cell(200,5,utf8_decode($celda2),'LR',1,'L');
	}
	if($celda3 != ''){
		$pdf->Cell(200,5,utf8_decode($celda3),'LR',1,'L');
	}
	 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(44,5,'Factores Ambientales:  ','L',0,'L');  
	$pdf->SetFont('Arial','',11);
	if($wfa>300){ //101
		$celda1 = substr($e, 0, 72);
		$celda2 = substr($e, 72, 172);
		$celda2 = substr($e, 172, -1);
		$pdf->Cell(156,5,utf8_decode($celda1),'R',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda2),'LR',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda3),'LR',1,'L');
	}elseif($wfa>72 && $wfa<172){
		$celda1 = substr($e, 0, 72);
		$celda2 = substr($e, 72, 172);
		$pdf->Cell(156,5,utf8_decode($celda1),'R',1,'L');
		$pdf->Cell(200,5,utf8_decode($celda2),'LR',1,'L');
	}elseif($wfa<72){
		$celda1 = substr($e, 0, 72);
		$pdf->Cell(156,5,utf8_decode($celda1),'R',1,'L');
	}
	$pdf->Cell(0,1.5,'','T',1,'L');
	
	//CAJA 3 
	$pdf->Cell(0,1,'','TL',1,'L');
	/*
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(48,5,'Fundamentaci�n Legal: ','TL',0,'L');
	$pdf->SetFont('Arial','',11); 
	$pdf->Cell(20,5,'Que el d�a ','T',0,'L'); 
	$pdf->SetFont('Arial','',11); 
	$arrfechaevaluacion = explode('-',$fecha);
	$mesev = $arrfechaevaluacion[1];
	$dias = letras($arrfechaevaluacion[2]);
	$anio = $arrfechaevaluacion[0]; 
	$fin  = lcfirst(letras(substr($anio, -2))); 
	$pdf->Cell(120,5,' '.$dias.' ('.$arrfechaevaluacion[2].') de '.$meses[$mesev].' de dos mil '.$fin.' ('.$arrfechaevaluacion[0].')','T',0,'FJ');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(8,5,', tal','TR',1,'FJ');
	$pdf->Cell(0,5,'cual lo exigido por el art�culo 74 del Decreto Ejecutivo N.� 36 de 11 de abril de 2014, la Direcci�n','LR',1,'FJ');
	$pdf->Cell(92,5,'Nacional de Certificaciones le otorg� un cupo a ','L',0,'L'); 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(104,5,utf8_decode($nombre.' '.$apellidopaterno.' '.$apellidomaterno),'R',1,'L'); 
    $pdf->SetFont('Arial','',11);	
	$pdf->Cell(0,5,', para que la Junta Evaluadora, efectuase la correspondiente evaluaci�n de su condici�n de salud;','LR',1,'FJ'); 
	//$pdf->Cell(0,5,'','LR',1,'L');
	
	$pdf->Cell(0,5,'Que conforme a lo establecido en el art�culo 4 del Decreto Ejecutivo N.� 74 de 14 de abril de 2015, la','LR',1,'FJ'); 
	$pdf->Cell(0,5,'Junta Evaluadora de la Discapacidad integrada por un m�nimo tres (3) miembros, y cumpliendo con el','LR',1,'FJ'); 
	$pdf->Cell(123,5,'requisito de interdisciplinariedad, seg�n consta en la Resoluci�n','L',0,'FJ');  
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(25,5,'N.� '.$resolucion,'',0,'L'); 
    $pdf->SetFont('Arial','',11);
	$pdf->Cell(48,5,'de la Direcci�n Nacional ','R',1,'FJ');
	$pdf->Cell(70,5,'de Certificaciones, evalu� y valor� a ','L',0,'L'); 
	$pdf->SetFont('Arial','B',11);
	$pdf->MultiCell(126,5,utf8_decode($nombre.' '.$apellidopaterno.' '.$apellidomaterno),'R',1,'L');
	//$pdf->Cell(0,5,'','LR',1,'L');
	
	$pdf->SetFont('Arial','',11); 
	$pdf->Cell(0,5,'Que en virtud de ello, la Junta Evaluadora, luego de evaluar y valorar de conformidad a los protocolos','LR',1,'FJ');
	$pdf->Cell(0,5,'establecidos en el Decreto Ejecutivo N.� 36 de 11 de abril de 2014, modificado por el Decreto Ejecutivo','LR',1,'FJ');
	$pdf->Cell(104,5,'N� 74 de 14 de abril de 2015, ha determinado que','L',0,'FJ'); 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(92,5,utf8_decode($nombre.' '.$apellidopaterno.' '.$apellidomaterno).',','R',1,'L');
	$pdf->SetFont('Arial','',11);
	*/
	$pdf->MultiCell(0,5,''.utf8_decode($observacion).'','LR','J');
	
	$pdf->SetFont('Arial','B',11); /*
	$pdf->Cell(0,5,'RESUELVE:','LR',1,'C');
	//$pdf->Cell(0,5,'','LR',1,'L');
	
	//$h = $pdf->GetMultiCellHeight(64,5,utf8_decode($nombre.' '.$apellidopaterno.' '.$apellidomaterno).',','R','L');
	$pdf->Cell(132,5,'PRIMERO: OTORGAR CERTIFICACI�N DE LA DISCAPACIDAD a','L',0,'FJ');
	//$y = $pdf->GetY(); $x = $pdf->GetX(); $pdf->SetY($y+5);
    //$pdf->Cell(21,5,'','L',0,'L');
	//$pdf->SetY($y); $pdf->SetX($x); 
	$pdf->SetFont('Arial','B',11);
	$wnombre = strlen($nombre.' '.$apellidopaterno.' '.$apellidomaterno);
	if($wnombre > 32){
		$pdf->Cell(64,5,utf8_decode($nombre.' '.$apellidopaterno).'','R',1,'L');
		$pdf->Cell(25,5,utf8_decode($apellidomaterno).'','L',0,'L');
		$bordep = 0;
	}else{
		$pdf->Cell(64,5,utf8_decode($nombre.' '.$apellidopaterno.' '.$apellidomaterno).',','R',1,'L');
		$bordep = 'L';
	}	
	
	$pdf->SetFont('Arial','',11); 
	$pdf->Cell(92,5,'portador de la c�dula de identidad personal',$bordep,0,'FJ');  
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(25,5,$cedula,'0',0,'L');
	$pdf->SetFont('Arial','',11); 
	$wexp = strlen($expediente);
	if($wexp > 4){
		$sexp = 1;
		$wnum = '30';
		$bnum = 'L';
		$bexp = 'R';
		$wcexp = 54;
		$bdir = 'R';
		$snum = 0;
		$bdel = '0';
	}else{
		$sexp = 0;
		$wnum = '30';
		$bnum = 'R';
		$bexp = '0';
		$wcexp = 54;
		$bdir = 'R';
		$snum = 1;
		$bdel = 'L';
	}
	$pdf->Cell($wcexp,5,', con expediente ',$bexp,$sexp,'FJ'); 
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(25,5,'N.� '.$expediente,$bnum,$snum,'L'); 
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(14,5,'de la',$bdel,0,'FJ'); 
	$pdf->Cell(0,5,'Direcci�n Nacional de Certificaciones de la Secretar�a Nacional de Discapacidad.',$bdir,1,'L'); 
	$pdf->Cell(0,3,'','LR',1,'L');
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(24,5,'SEGUNDO: ','L',0,'L'); 
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(132,5,'Se�alar que el t�rmino de vigencia de la presente Certificaci�n es de','',0,'L');
	$pdf->SetFont('Arial','',11); 
	$pdf->Cell(21,5,strtolower($letras).' ('.$vencimiento.')','',0,'L'); 
	$pdf->SetFont('Arial','',11); 
	$pdf->Cell(19,5,'a�os. ','R',1,'L');
	$pdf->Cell(0,3,'','LR',1,'L');
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(23,5,'TERCERO: ','L',0,'L');
	$y = $pdf->GetY(); $x = $pdf->GetX(); $pdf->SetY($y+5);
	$pdf->Cell(21,5,'','L',0,'L');
	$pdf->SetY($y); $pdf->SetX($x);
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(110,5,'Ordenar la confecci�n del carn� de discapacidad, a favor de','',0,'FJ');
	$pdf->SetFont('Arial','B',11); 
	if($wnombre > 32){
		$pdf->Cell(63,5,utf8_decode($nombre.' '.$apellidopaterno).'','R',1,'L');
		$pdf->Cell(196,5,utf8_decode($apellidomaterno).'.','R',1,'L');
		$bordep = 'L';
	}else{
		$pdf->Cell(63,5,utf8_decode($nombre.' '.$apellidopaterno.' '.$apellidomaterno).'.','R',1,'L');
		$bordep = 'L';
	} 
	$pdf->Cell(0,3,'','LR',1,'L');
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(21,5,'CUARTO: ',$bordep,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(175,5,'Notificar al interesado el contenido de la presente Resoluci�n y entregarle copia de la ','R',1,'FJ'); 
	$pdf->Cell(0,5,'misma.','LR',1,'L'); 
	$pdf->Cell(0,3,'','LR',1,'L');
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(20,5,'QUINTO: ','L',0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(176,5,'La presente certificaci�n de discapacidad, no vincula de manera alguna, a los programas','R',1,'FJ'); 
	$pdf->Cell(0,5,'que administra la CSS, el MINSA y el IPHE.','LR',1,'L'); 
	$pdf->Cell(0,3,'','LR',1,'L');
	$pdf->SetFont('Arial','B',11);  
	$pdf->Cell(18,5,'SEXTO: ','L',0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(178,5,'Advertir que contra esta resoluci�n puede interponerse el recurso de reconsideraci�n, dentro ','R',1,'FJ'); 
	$pdf->Cell(0,5,'del t�rmino de los cinco (05) d�as h�biles, contados  a partir de su notificaci�n.','LR',1,'L'); 
	$pdf->Cell(0,3,'','LR',1,'L');
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(21,5,'SEPTIMO: ','BL',0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(175,5,' La presente resoluci�n entrar� a regir a partir de la fecha de su notificaci�n. ','BR',1,'L'); */
	$pdf->Cell(0,1.5,'','T',1,'L');
	
	$arrfechaemision = explode('-',$fechaemision);
	$mese = (int)$arrfechaemision[1];	
	$arrfechavencimiento = explode('-',$fechavencimiento);
	$mesv = (int)$arrfechavencimiento[0];
	
	$validezvenc = (int)$arrfechaemision[2] + (int)$validezc;
	
	//CAJA 4
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(45,5,'Validez del Certificado:','TL',0,'L');  
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(20,5,utf8_decode($letras.' ('.$vencimiento.')'),'T',0,'L'); 
	$pdf->SetFont('Arial','',11);
    $pdf->Cell(10,5,utf8_decode($validez_tipo),'T',0,'L');	
    $pdf->Cell(34,5,'','T',0,'L');	
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(45,5,utf8_decode('Fecha de Vencimiento: '),'T',0,'L'); 
	$pdf->SetFont('Arial','',11);
	
	if($validezt != 'meses'){
		//Sumar a�os
		$pdf->Cell(46,5,utf8_decode($arrfechaevaluacion[2].' de '.$meses[$mesev].' de '.$validezvenc),'TR',1,'L');
	}else{
		//Sumar meses
		$fecha_emision_final = date_create($arrfechaevaluacion[2]."-".$mesev."-".$arrfechaemision[2]);
		date_add($fecha_emision_final, date_interval_create_from_date_string("".$validezc." months"));
		$fecha_final = date_format($fecha_emision_final,"d-m-Y");
		$arrfecha_final = explode("-",$fecha_final);
		$dia = $arrfecha_final[0];
		$mes = (int)$arrfecha_final[1];
		$anyo = $arrfecha_final[2];
		
		$pdf->Cell(46,5,utf8_decode($dia.' de '.$meses[$mes].' de '.$anyo),'TR',1,'L');
	}
	$pdf->SetFont('Arial','B',11); 	
	$pdf->Cell(50,5,utf8_decode('Lugar y fecha de emisión:  '),'L',0,'L'); 
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(150,5,utf8_decode(trim($ciudad)).', '.$arrfechaemision[0].' de '.$meses[$mese].' de '.$arrfechaemision[2],'R',1,'L'); 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(200,5,utf8_decode('Firma y sellos de el(la) Director(a) Nacional de Certificaciones: '),'BLR',1,'L'); 
	//$pdf->Cell(200,5,'','BR',1,'L');
	
	$myPath = '../solicitudes/';
	if (!file_exists($myPath))
		mkdir($myPath, 0777);
	$myPath = '../solicitudes/'.$id.'/';
	$target_path2 = utf8_decode($myPath);
	if (!file_exists($target_path2))
		mkdir($target_path2, 0777);
	//$pdf->Output('I',"resolucion_".date('Ymd_h_m_s').".pdf"); 
	
	$sql = " INSERT INTO resolucionemision (idsolicitud,archivo,fecha,usuario) VALUES
			 (".$id.",'resolucion_".date('Ymd_h_m_s').".pdf',NOW(),'".$_SESSION['usuario_sen']."')";
			// echo $sql;
	$mysqli->query($sql);
	
	//$pdf->Output('I', "C:/wamp64/www/senadisqa/solicitudes/".$id."/resolucion_".date('Ymd_h_m_s').".pdf");
	$pdf->Output('F',"C:/wamp64/www/".$sitio_actual."/solicitudes/".$id."/resolucion_".date('Ymd_h_m_s').".pdf");
	echo "solicitudes/".$id."/resolucion_".date('Ymd_h_m_s').".pdf";
//	$pdf->Output('I',"resolucion.pdf");
?>