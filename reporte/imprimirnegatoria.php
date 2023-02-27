<?php
	include("../controller/conexion.php");
	include_once "../controller/funciones.php";
	//verificarLogin();
    include_once("../fpdf/fpdf.php");

    $id 		 = $_GET['id']; 
	   
	class PDF extends FPDF{
    	// Cabecera de página
    	function Header(){	
    	}
    	// Pie de página
    	function Footer(){
			// Go to 1.5 cm from bottom
			$this->SetY(-15);
			// Select Arial italic 8
			$this->SetFont('Arial','I',8);
			// Print centered page number
			$this->Cell(0,10,'- '.$this->PageNo().' -',0,0,'C');
    	}
		
		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
			$k=$this->k;
			if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
			{
				$x=$this->x;
				$ws=$this->ws;
				if($ws>0)
				{
					$this->ws=0;
					$this->_out('0 Tw');
				}
				$this->AddPage($this->CurOrientation);
				$this->x=$x;
				if($ws>0)
				{
					$this->ws=$ws;
					$this->_out(sprintf('%.3F Tw',$ws*$k));
				}
			}
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$s='';
			if($fill || $border==1)
			{
				if($fill)
					$op=($border==1) ? 'B' : 'f';
				else
					$op='S';
				$s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
			}
			if(is_string($border))
			{
				$x=$this->x;
				$y=$this->y;
				if(is_int(strpos($border,'L')))
					$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
				if(is_int(strpos($border,'T')))
					$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
				if(is_int(strpos($border,'R')))
					$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
				if(is_int(strpos($border,'B')))
					$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
			}
			if($txt!='')
			{
				if($align=='R')
					$dx=$w-$this->cMargin-$this->GetStringWidth($txt);
				elseif($align=='C')
					$dx=($w-$this->GetStringWidth($txt))/2;
				elseif($align=='FJ')
				{
					//Set word spacing
					$wmax=($w-2*$this->cMargin);
					$this->ws=($wmax-$this->GetStringWidth($txt))/substr_count($txt,' ');
					$this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
					$dx=$this->cMargin;
				}
				else
					$dx=$this->cMargin;
				$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
				if($this->ColorFlag)
					$s.='q '.$this->TextColor.' ';
				$s.=sprintf('BT %.2F %.2F Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt);
				if($this->underline)
					$s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
				if($this->ColorFlag)
					$s.=' Q';
				if($link)
				{
					if($align=='FJ')
						$wlink=$wmax;
					else
						$wlink=$this->GetStringWidth($txt);
					$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$wlink,$this->FontSize,$link);
				}
			}
			if($s)
				$this->_out($s);
			if($align=='FJ')
			{
				//Remove word spacing
				$this->_out('0 Tw');
				$this->ws=0;
			}
			$this->lasth=$h;
			if($ln>0)
			{
				$this->y+=$h;
				if($ln==1)
					$this->x=$this->lMargin;
			}
			else
				$this->x+=$w;
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
    }    
    
    //Creación del objeto de la clase heredada
    //$pdf = new PDF('P', 'mm', array(215.9,355.6));
	$pdf = new PDF('P', 'mm', 'Legal');
    $pdf->AliasNbPages();
    $pdf->AddPage();   
    $pdf->SetFillColor(255,255,255);    
	$pdf->SetTextColor(0,0,0);	
	//Establecemos los márgenes izquierda, arriba y derecha:
	$pdf->SetMargins(25, 25 , 25);
	//Establecemos el margen inferior:
	$pdf->SetAutoPageBreak(true,30);
    $pdf->SetFont('Arial','B',11);
    // Título
	$pdf->Ln(18);
	$pdf->Cell(0,5,'REPÚBLICA DE PANAMÁ ','0',1,'C');
	$pdf->Cell(0,5,'SECRETARÍA NACIONAL DE DISCAPACIDAD ','0',1,'C');   
	$pdf->Cell(0,5,'Dirección Nacional de Certificaciones ','0',1,'C');   
	//$pdf->Cell(0,5,'Certificado de Discapacidad ','0',1,'C');   
	//$pdf->Ln(5);
   
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
				DATE_FORMAT(s.fecha_cita,'%Y-%m-%d') AS fecha, DATE_FORMAT(s.fecha_solicitud,'%Y-%m-%d') AS fecha_solicitud, 
				p.id as idpaciente, s.iddiscapacidad as iddiscapacidad, 
				GROUP_CONCAT(CONCAT(m.id,'|',m.nombre,' ',m.apellido,'|', REPLACE(e.nombre,',',' / '))) AS medicos, 
				s.sala AS sala, r.nombre AS regional, d.nombre AS discapacidad, f.codigojunta, f.cif, p.expediente AS nro_expediente, 
				g.nro_resolucion, g.evaluacion, f.fechaemision, f.fechavencimiento, g.primerc, g.segundoc, f.diagnostico, f.ciudad,
				TIMESTAMPDIFF(YEAR,p.fecha_nac,CURDATE()) AS edad, g.fecha_solicitud AS fechasol_negatoria, g.fecha_evaluacion AS fechaeval_negatoria, g.fecha_notifiquese AS fechanot_negatoria, g.nombre_encargado, g.cargo_encargado
				FROM solicitudes s 
				INNER JOIN pacientes p ON p.id = s.idpaciente 
				INNER JOIN discapacidades d ON d.id = s.iddiscapacidad
				LEFT JOIN regionales r ON r.id = s.regional
				LEFT JOIN medicos m ON FIND_IN_SET(m.id,s.junta) 
				LEFT JOIN especialidades e ON e.id = m.especialidad
				LEFT JOIN evaluacion f ON p.id = f.idpaciente AND f.idpaciente = s.idpaciente AND s.id = f.idsolicitud 	
				LEFT JOIN negatorias g ON s.id = g.idsolicitud
				WHERE s.id = ".$id." ";
	//echo $queryS;
	$resultS = $mysqli->query($queryS); 
	if($rowS = $resultS->fetch_assoc()){
		$id 		    	= $rowS['id'];
		$idpaciente	    	= $rowS['idpaciente'];
		$paciente	    	= $rowS['paciente'];
		$fecha 	        	= $rowS['fecha'];
		$fecha_solicitud 	= $rowS['fecha_solicitud'];
		$medicos 			= $rowS['medicos'];
		$discapacidad 		= $rowS['discapacidad'];
		$iddiscapacidad 	= $rowS['iddiscapacidad'];
		$sala           	= $rowS['sala'];
		$regional       	= $rowS['regional'];
		$codigojunta    	= $rowS['codigojunta'];
		$cif    			= $rowS['cif'];
		$expediente	 		= $rowS['nro_expediente'];
		$resolucion			= $rowS['nro_resolucion'];
		$evaluacion    		= $rowS['evaluacion'];
		$fechaemision    	= $rowS['fechaemision'];
		$fechavencimiento   = $rowS['fechavencimiento'];
		$diagnostico   		= $rowS['diagnostico'];
		$validezc   		= $rowS['validez_certificado'];
		$ciudad   			= $rowS['ciudad'];
		$primercriterio 	= $rowS['primerc'];
		$segundocriterio 	= $rowS['segundoc'];
		$edad 				= $rowS['edad'];
		$fechasol_negatoria = $rowS['fechasol_negatoria'];
		$fechaeval_negatoria= $rowS['fechaeval_negatoria'];
		$fechanot_negatoria = $rowS['fechanot_negatoria'];
		$nombre_encargado 	= $rowS['nombre_encargado'];
		$cargo_encargado 	= $rowS['cargo_encargado'];
		
		//DIAGNOSTICOS
		$arrdiagnostico = explode(',',$diagnostico); 
		$newdiagnostico = array_filter($arrdiagnostico);
		$listadodiag 	= implode(", ", $newdiagnostico);
		if($listadodiag != ''){			
			$queryDiag = "  SELECT CONCAT(nombre, ' ',codigo) AS nombresDiag
							FROM enfermedades WHERE id IN (".$listadodiag.") ";
			//echo $listadodiag;
			//print_r($diagnostico);
			$resultDiag = $mysqli->query($queryDiag); 
			$nombresDiag = array();
			if($rowDiag = $resultDiag->fetch_assoc()){
				$nombresDiag[] = $rowDiag['nombresDiag'];
			}
		}else{
			$codigosDiag = '';
		}
		
		$datetime1 = explode('-',$fechavencimiento);
		$datetime2 = explode('-',$fechaemision);
		$vencimiento = $rowS['validez_certificado'];//$datetime1[1] - $datetime2[2];		
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
			$tipo_documento	 = $rowP['tipo_documento'];
			
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
	//$pdf->Cell(142,6,'','0',0,'L');
    //$pdf->SetTextColor(231,19,31);	
	$pdf->Cell(0,6,'Resolución N°  '.$resolucion,'0',1,'C'); 
	$pdf->Ln(5);
	$pdf->Cell(0,6,'“Por medio de la cual se NIEGA la Certificación de Discapacidad a '.utf8_decode(trim($nombre).' '.trim($apellidopaterno).' '.trim($apellidomaterno)).'”','0',1,'C'); 
	$pdf->Ln(5);
	$pdf->Cell(0,6,'LA DIRECTORA NACIONAL DE CERTIFICACIONES DE LA','0',1,'C'); 
	$pdf->Cell(0,6,'SECRETARÍA NACIONAL DE DISCAPACIDAD','0',1,'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',11); 
	$pdf->Cell(0,6,'En uso de sus facultades legales,','0',1,'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',11); 
	$pdf->Cell(0,6,'CONSIDERANDO','0',1,'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',11);
	$pdf->MultiCell(0,6,'Que mediante Ley N.° 23 de 28 de junio de 2007, se creó la Secretaría Nacional de Discapacidad (SENADIS), como entidad autónoma del Estado, con el fin de dirigir y ejecutar la política de inclusión social de las personas con discapacidad y sus familias;',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,6,'Que los numerales 9 y 10 del Artículo 13 de la Ley supra citada, establecen como funciones de la Secretaría Nacional de Discapacidad, el diseño de los baremos nacionales necesarios para valorar la discapacidad que sustentan la emisión de la certificación de discapacidad;',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,6,'Que mediante Decreto Ejecutivo N.° 37 de 11 de abril de 2014, modificado mediante Decreto Ejecutivo N.° 18 de 24 de febrero de 2015, se creó la Dirección Nacional de Certificaciones, la cual conforme al artículo 2, numeral 4, del texto legal citado, tiene como objetivos supervisar, dirigir y controlar los procesos de evaluación, valoración y emisión de la certificación de la discapacidad;',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,6,'Que mediante Decreto Ejecutivo N.° 36 de 11 de abril de 2014, modificado mediante Decreto Ejecutivo N.° 74 de 14 de abril de 2015, se reglamentó el procedimiento de conformación y funcionamiento de las Juntas Evaluadoras de la Discapacidad, los baremos nacionales y se dictó el procedimiento para la evaluación, valoración y certificación de la discapacidad;',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,6,'Que SENADIS atendiendo al tenor del artículo 7 del Decreto Ejecutivo N° 36 de 11 de abril de 2014, conformó las Juntas Evaluadoras de la Discapacidad, las cuales tienen como función ejecutar el procedimiento de evaluación, valoración y certificación de las personas que de manera voluntaria soliciten la certificación de discapacidad;',0,'J',1);
	
	//DATOS DE LA EVALUACION
	$pdf->Ln(4);
	//$fechae = 'veintidós (22) de abril de dos mil veintiuno (2021)';
	
	$arrfechaevaluacion = explode('-',$fecha); 
	$mesev 	   = (int)$arrfechaevaluacion[1]; 
	$fechae 	= ''.$arrfechaevaluacion[2].' de '.$meses[$mesev].' de '.$arrfechaevaluacion[0].'';
	 
	//Fecha de evaluación de tabla negatorias 
	$arrfechaeval_negat = explode('-',$fechaeval_negatoria);
	$mesev_neg = (int)$arrfechaeval_negat[1];
	$fechae_neg = ''.$arrfechaeval_negat[2].' de '.$meses[$mesev_neg].' de '.$arrfechaeval_negat[0].''; 
	$fechae_neg != '' ? $fechae_neg = $fechae_neg : $fechae_neg = $fechae;
	
	$nombree = utf8_decode(trim($nombre).' '.trim($apellidopaterno).' '.trim($apellidomaterno));
	if($tipo_documento == 1){
		$tipo_documentoe = 'cédula de identidad personal N°';
	}else{
		$tipo_documentoe = 'carnet migratorio N°';
	}
	$direccione = '';
	if($urbanizacion != ''){
		$direccione .= $urbanizacion;
	}
	if($calle != ''){
		$direccione .= ', calle '.$calle;
	}
	if($numero != ''){
		$direccione .= ', casa '.$numero;
	}
	if($edificio != ''){
		$direccione .= ', apartamento #'.$edificio;
	}
	if($corregimiento != ''){
		$direccione .= ', corregimiento de '.utf8_decode($corregimiento);
	}
	if($distrito != ''){
		$direccione .= ', distrito de '.utf8_decode($distrito);
	}
	if($provincia != ''){
		$direccione .= ', provincia de '.utf8_decode($provincia);
	}
	//DATOS DE LA SOLICITUD
	$fechasol = 'veintidós (22) de abril de dos mil veintiuno (2021)';
	$fechasol = $fecha_solicitud;
	
	$arrfechasolicitud = explode('-',$fecha_solicitud);
	$messol = (int)$arrfechasolicitud[1];
	$fechasol = ''.$arrfechasolicitud[2].' de '.$meses[$messol].' de '.$arrfechasolicitud[0].'';
	 
	$arrfechasol_negatoria = explode('-',$fechasol_negatoria);
	$messol_neg   = (int)$arrfechasol_negatoria[1];
	$fechasol_neg = ''.$arrfechasol_negatoria[2].' de '.$meses[$messol_neg].' de '.$arrfechasol_negatoria[0].'';
	$fechasol_neg != '' ? $fechasol_neg = $fechasol_neg : $fechasol_neg = $fechasol;
	
	if(utf8_decode($discapacidad) == 'FÍSICA'){
		$articulodiscapacidad = '62';
	}elseif(utf8_decode($discapacidad) == 'AUDITIVA'){
		$articulodiscapacidad = '63';
	}elseif(utf8_decode($discapacidad) == 'VISUAL'){
		$articulodiscapacidad = '64';
	}elseif(utf8_decode($discapacidad) == 'MENTAL'){
		$articulodiscapacidad = '65';
	}elseif(utf8_decode($discapacidad) == 'INTELECTUAL'){
		$articulodiscapacidad = '65-A';
	}elseif(utf8_decode($discapacidad) == 'VISCERAL'){
		$articulodiscapacidad = '66';
	}
	
	$ndiscapacidad = utf8_decode($discapacidad);
	if($ndiscapacidad == 'FÍSICA'){
		$articulo_conclusion = " acorde al Decreto Ejecutivo N° 36 del 11 de abril de 2014 en el Artículo 62";
	}else if($ndiscapacidad == 'VISUAL'){
		$articulo_conclusion = " acorde al Decreto Ejecutivo N° 36 del 11 de abril de 2014 en el Artículo 64";
	}else if($ndiscapacidad == 'AUDITIVA'){
		$articulo_conclusion = " acorde al Decreto Ejecutivo N° 36 del 11 de abril de 2014 en el Artículo 63";
	}else if($ndiscapacidad == 'VISCERAL'){
		$articulo_conclusion = " acorde al Decreto Ejecutivo N° 36 del 11 de abril de 2014 en el Artículo 66";
	}else if($ndiscapacidad == 'MENTAL'){
		if($edad < 18){
			$articulo_conclusion = "acorde al Decreto Ejecutivo N° 74 de 14 abril de 2015, que modifica el Decreto Ejecutivo N°36 de 2014, en el Artículo 65 numeral 1 letras a y b";
		}else{
			$articulo_conclusion = "acorde al Decreto Ejecutivo N° 74 de 14 abril de 2015, que modifica el Decreto Ejecutivo N°36 de 2014, en el Artículo 65 numeral 1 letras c y d";
		}
	}else if($ndiscapacidad == 'INTELECTUAL'){
		if($edad < 18){
			$articulo_conclusion = "acorde al Decreto Ejecutivo N° 74 de 14 abril de 2015, que modifica el Decreto Ejecutivo N°36 de 2014, en el Artículo 65-A numeral 1 letras a y b";
		}else{
			$articulo_conclusion = "acorde al Decreto Ejecutivo N° 74 de 14 abril de 2015, que modifica el Decreto Ejecutivo N°36 de 2014, en el Artículo 65-A numeral 1 letras c y d";
		}
	}
	$pdf->MultiCell(0,6,'Que el día '.$fechasol_neg.', acudió a la Dirección Nacional de Certificaciones, '.$nombree.', con '.$tipo_documentoe.' '.$cedula.', con domicilio ubicado en '.$direccione.', quien de manera voluntaria, solicitó a dicha Dirección la correspondiente evaluación, valoración y certificación de su discapacidad,',0,'J',1);

	$pdf->Ln(4);
	$pdf->MultiCell(0,6,'Que el día '.$fechae_neg.', tal cual lo exigido por el artículo 74, del Decreto N.° 36 de 2014, antes descrito, la Dirección Nacional de Certificaciones, le otorgó un cupo a '.$nombree.', para que la Junta Evaluadora, efectuase la correspondiente evaluación de su condición de salud;',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,6,'Que conforme a lo establecido en el artículo 4, del Decreto Ejecutivo N.° 74 de 14 de abril de 2015, la Junta Evaluadora de la Discapacidad integrada por un mínimo tres (3) miembros, y cumpliendo con el requisito de interdisciplinariedad, según consta en la Resolución de conformación de Junta Evaluadora N.° '.$codigojunta.' de la Dirección Nacional de Certificaciones, evaluó y valoró a la solicitante;',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,6,'Que en virtud de ello, la Junta Evaluadora, luego de evaluar y valorar de conformidad a los protocolos establecidos, en el Decreto Ejecutivo N.° 36 de 11 de abril de 2014, modificado mediante Decreto Ejecutivo Nº 74 de 14 de abril de 2015, ha determinado que, '.$nombree.' con '.$tipo_documentoe.' '.$cedula.' no cumple con ninguno de los criterios que rigen para certificar como persona con Discapacidad '.utf8_decode($discapacidad).' '.$articulo_conclusion.'., según el informe remitido por dicha Junta, la que sustenta su decisión en función de los siguientes aspectos:',0,'J',1);
	$pdf->Ln(4);
	$pdf->SetFont('Arial','B',11);
	$pdf->MultiCell(0,6,'Esta decisión se sustenta:','0',1,'C');
	$pdf->Ln(4);
	$pdf->SetFont('Arial','',11);
	$pdf->MultiCell(0,6,'1.	En la evaluación diagnóstica que presentó '.$nombree.' con '.$tipo_documentoe.' '.$cedula.' emitida por su médico tratante el cual refiere que presenta:',0,'J',1);
	$pdf->Cell(10,6,'','0',0,'C');
	$pdf->MultiCell(0,6,$evaluacion,'0',1,'C');
	$pdf->Ln(4);
	$pdf->SetFont('Arial','B',11);
	$pdf->MultiCell(0,6,'        Diagnóstico según CIE10','0',1,'C');
	$pdf->SetFont('Arial','',11);
	if(!empty($nombresDiag)){
		foreach($nombresDiag AS $diag){
			$pdf->MultiCell(0,6,'        •    '.$diag,'0',1,'C');
		}
	}	
	$pdf->Ln(4);
	$pdf->MultiCell(0,6,'2.	De acuerdo con los componentes de la CIF tenemos que: ','0',1,'C');
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
	
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(58,5,'        Funciones Corporales: ','',0,'L');  
	$pdf->SetFont('Arial','',11);
	//$pdf->Cell(148,5,$b,'R',1,'L'); 
	if($wfyc>300){ //101
		$celda1 = substr($b, 0, 72);
		$celda2 = substr($b, 72, 172);
		$celda2 = substr($b, 172, -1);
		$pdf->Cell(155,5,$celda1,'',1,'L');
		$pdf->Cell(200,5,$celda2,'',1,'L');
		$pdf->Cell(200,5,$celda3,'',1,'L');
	}elseif($wfyc>72 && $wfyc<172){
		$celda1 = substr($b, 0, 72);
		$celda2 = substr($b, 72, 172);
		$pdf->Cell(155,5,$celda1,'',1,'L');
		$pdf->Cell(200,5,$celda2,'',1,'L');
	}elseif($wfyc<72){
		$celda1 = substr($b, 0, 72);
		$pdf->Cell(155,5,$celda1,'',1,'L');
	}
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(58,5,'        Estructuras Corporales:  ','',0,'L');  
	$pdf->SetFont('Arial','',11);
	//$pdf->Cell(146,5,$s,'R',1,'L');  
	if($weyc>300){ //101
		$celda1 = substr($s, 0, 72);
		$celda2 = substr($s, 72, 172);
		$celda2 = substr($s, 172, -1);
		$pdf->Cell(153,5,$celda1,'',1,'L');
		$pdf->Cell(200,5,$celda2,'',1,'L');
		$pdf->Cell(200,5,$celda3,'',1,'L');
	}elseif($weyc>72 && $weyc<172){
		$celda1 = substr($s, 0, 72);
		$celda2 = substr($s, 72, 172);
		$pdf->Cell(153,5,$celda1,'',1,'L');
		$pdf->Cell(200,5,$celda2,'',1,'L');
	}elseif($weyc<72){
		$celda1 = substr($s, 0, 72);
		$pdf->Cell(153,5,$celda1,'',1,'L');
	}
	$pdf->SetFont('Arial','B',11);
	$h = $pdf->GetMultiCellHeight(143,5,$d,'',1,'L');
	$pdf->Cell(58,5,'        Actividad y Participación: ','',0,'L');  //76 
	$pdf->SetFont('Arial','',11);
	
	$arrayd = explode('  ',$d);
	$countd = count($arrayd);
	$celda1 = ''; $celda2 = ''; $celda3 = '';
	$y = 0; $z = 0;
	for($i=0; $i < $countd; $i++){
		if(strlen($celda1) <= 50 ){
			if($i == 0){
				$celda1 .= $arrayd[$i];
			}else{
				$celda1 .= '  '.$arrayd[$i];
			}
		}elseif(strlen($celda1) <= 120 && strlen($celda2) <= 58 ){
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
	$pdf->Cell(108,5,$celda1,'',1,'L');
	if($celda2 != ''){
		$pdf->Cell(0,5,'        '.$celda2,'',1,'L');
	}
	if($celda3 != ''){
		$pdf->Cell(0,5,'        '.$celda3,'',1,'L');
	}
	 
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(58,5,'        Factores Ambientales:  ','',0,'L');  
	$pdf->SetFont('Arial','',11);
	if($wfa>300){ //101
		$celda1 = substr($e, 0, 72);
		$celda2 = substr($e, 72, 172);
		$celda2 = substr($e, 172, -1);
		$pdf->Cell(156,5,$celda1,'',1,'L');
		$pdf->Cell(200,5,$celda2,'',1,'L');
		$pdf->Cell(200,5,$celda3,'',1,'L');
	}elseif($wfa>72 && $wfa<172){
		$celda1 = substr($e, 0, 72);
		$celda2 = substr($e, 72, 172);
		$pdf->Cell(156,5,$celda1,'',1,'L');
		$pdf->Cell(200,5,$celda2,'',1,'L');
	}elseif($wfa<72){
		$celda1 = substr($e, 0, 72);
		$pdf->Cell(156,5,$celda1,'',1,'L');
	}
	
	if($ndiscapacidad == 'FÍSICA'){
		$articulo = "De acuerdo al Decreto Ejecutivo N°36 de 11 abril de 2014, Artículo 62 que rige la discapacidad física.";
	}else if($ndiscapacidad == 'VISUAL'){
		$articulo = "De acuerdo al Decreto Ejecutivo N°36 de 11 abril de 2014, Artículo 64 que rige la discapacidad visual.";
	}else if($ndiscapacidad == 'AUDITIVA'){
		$articulo = "De acuerdo al Decreto Ejecutivo N°36 de 11 abril de 2014 Artículo 63 que rige la discapacidad auditiva.";
	}else if($ndiscapacidad == 'VISCERAL'){
		$articulo = "De acuerdo al Decreto Ejecutivo N°36 del 11 abril de 2014, Artículo 66 que rige la discapacidad visceral. ";
	}else if($ndiscapacidad == 'MENTAL'){
		if($edad < 18){
			$articulo = "De acuerdo al Decreto Ejecutivo N° 74 de 14 abril de 2015, que modifica el Decreto Ejecutivo N°36 de 2014, en el Artículo 65 numeral 1 letras a y b, que rige la discapacidad mental en niños.";
		}else{
			$articulo = "De acuerdo al Decreto Ejecutivo N° 74 de 14 abril de 2015, que modifica el Decreto Ejecutivo N°36 de 2014, en el Artículo 65 numeral 1 letras c y d, que rige la discapacidad mental en adultos.";
		}
	}else if($ndiscapacidad == 'INTELECTUAL'){
		if($edad < 18){
			$articulo = "De acuerdo al Decreto Ejecutivo N° 74 de 14 abril de 2015, que modifica el Decreto Ejecutivo N°36 de 2014, en el Artículo 65-A numeral 1 letras a y b, que rige la discapacidad intelectual en niños.";
		}else{
			$articulo = "De acuerdo al Decreto Ejecutivo N° 74 de 14 abril de 2015, que modifica el Decreto Ejecutivo N°36 de 2014, en el Artículo 65-A numeral 1 letras c y d, que rige la discapacidad intelectual en adultos.";
		}
	}
	$pdf->Ln(4);
	//$pdf->MultiCell(0,5,'Ante la evaluación expuesta y acogiéndonos a los criterios plasmados en el Artículo No. 62 del Decreto Ejecutivo N°36 de 11 abril de 2014 citamos el Artículo 62.','',1,'L');  
	$pdf->MultiCell(0,5,($articulo),0,'J',1);
	$pdf->Ln(4);
	$pdf->SetFont('Arial','B',11);
	$pdf->MultiCell(0,5,'La Junta Evaluadora tomando en cuenta tanto las evidencias presentadas por la interesada, así como la evaluación y valoración realizada por la Junta Evaluadora de Discapacidad, aclara que de acuerdo al artículo antes mencionado:',0,'J',1);
	
	$pdf->Ln(4);
	$pdf->Cell(0,5,'En el primer criterio:','',1,'L');	
	$pdf->SetFont('Arial','',11);	
	$pdf->MultiCell(0,5,utf8_decode($primercriterio),0,'J',1);
	$pdf->Ln(4);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0,5,'En el segundo criterio:','',1,'L');	
	$pdf->SetFont('Arial','',11);	
	$pdf->MultiCell(0,5,utf8_decode($segundocriterio),0,'J',1);
	$pdf->Ln(4);
	$pdf->SetFont('Arial','B',11);
	 
	$pdf->MultiCell(0,5,'Por lo tanto '.$nombree.' con '.$tipo_documentoe.' '.$cedula.' no cumple con ninguno de los criterios que rigen para certificar como persona con Discapacidad '.utf8_decode($discapacidad).' '.$articulo_conclusion.'.',0,'J',1);
	$pdf->Ln(4);
	$pdf->SetFont('Arial','',11);
	//Que mediante Resuelto No. 71 de 21 de septiembre de 2021
	$pdf->MultiCell(0,5,'Que mediante Resolución No. 006-2023 de 19 de enero de 2023, se designó a '.$nombre_encargado.', como '.$cargo_encargado.' de la Secretaria Nacional de Discapacidad, siendo facultada para supervisar, dirigir y controlar los procesos de evaluación, valoración y emisión de la certificación de discapacidad, además de desempeñar las funciones técnicas y/o administrativas, relacionadas con las tareas adscritas a la Dirección.',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,5,'Que por todas las consideraciones expuestas, la Directora de la Dirección Nacional de Certificaciones de la Secretaría Nacional de Discapacidad;',0,'J',1);
	$pdf->Ln(8);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0,5,'RESUELVE:','',1,'C');
	$pdf->Ln(4);
	$pdf->SetFont('Arial','',11);	
	$pdf->MultiCell(0,5,'PRIMERO: DENEGAR LA CERTIFICACIÓN DE LA DISCAPACIDAD a '.$nombree.', con '.$tipo_documentoe.' '.$cedula.' con expediente '.$expediente.', de la Dirección Nacional de Certificaciones de la Secretaría Nacional de Discapacidad.',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,5,'SEGUNDO: Notificar a la interesada el contenido de la presente Resolución y entregarle copia de la misma.',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,5,'TERCERO: Advertir que contra esta resolución puede interponerse el recurso de reconsideración, dentro del término de los cinco (5) días hábiles, contados a partir de su notificación.',0,'J',1);
	$pdf->Ln(4);
	$pdf->MultiCell(0,5,'CUARTO: La presente resolución entrará a regir a partir de la fecha de su notificación.',0,'J',1);
	$pdf->SetFont('Arial','B',11);
	$pdf->Ln(6);
	$pdf->Cell(0,5,'FUNDAMENTO DE DERECHO:','',1,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Ln(3);
	$pdf->Cell(10,6,'•',0,0,'R');
	$pdf->MultiCell(0,6,'Artículo 1, 13, numerales 9 y 10 de la Ley N° 23 de 28 de junio de 2007;',0,'J',1);
	$pdf->Cell(10,6,'•',0,0,'R');
	$pdf->MultiCell(0,6,'Artículos 7 y 74 del Decreto Ejecutivo N° 36 de 11 de abril de 2014, modificado por el Decreto Ejecutivo N° 74 de 14 de abril de 2015;',0,'J',1);
	$pdf->Cell(10,6,'•',0,0,'R');
	$pdf->MultiCell(0,6,'Artículo 2, numeral 4 del Decreto Ejecutivo N° 18 de 24 de febrero de 2015, que modifica el Decreto Ejecutivo N° 37 de 11 de abril de 2014;',0,'J',1);
	$pdf->Cell(10,6,'•',0,0,'R');
	$pdf->MultiCell(0,6,'Artículo 4 del Decreto Ejecutivo N° 74 de 14 de abril de 2015, que modifica el Decreto Ejecutivo N° 36 de 11 de abril de 2014; Ley 38 de 31 de julio de 2000.',0,'J',1);
	
	$pdf->Ln(6);
	$arrfechanot_negatoria = explode('-',$fechanot_negatoria);
	$diasNot = $arrfechanot_negatoria[2];
	$anioNot = $arrfechanot_negatoria[0];
	$formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);
	$diasNotLetra = $formatterES->format($diasNot);
	$formatterESAnio = new NumberFormatter("es", NumberFormatter::SPELLOUT);
	$anioNotLetra = $formatterESAnio->format($anioNot);	
	
	$mesNot = (int)$arrfechanot_negatoria[1];
	$mesNotLetra = $meses[$mesNot];
	
	$pdf->MultiCell(0,5,'Dada en la Ciudad de Panamá, a los '.utf8_decode($diasNotLetra).' ('.$diasNot.') día del mes de '.utf8_decode($mesNotLetra).' del '.utf8_decode($anioNotLetra).' ('.$anioNot.').',0,'J',1);
	
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(0,5,'NOTIFÍQUESE Y CÚMPLASE ','',1,'L');
	$valorY = $pdf->GetY();
	$pdf->Ln(50);
	$pdf->SetY($valorY+50);
	$pdf->Cell(0,5,utf8_decode($nombre_encargado),'',1,'C');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(0,5,utf8_decode($cargo_encargado),'',1,'C');
	$pdf->Image('../images/sello.png',88,$valorY+5,35); //borde izq, borde sup, ancho
	
	$pdf->Output('I',"resolucion.pdf");
?>