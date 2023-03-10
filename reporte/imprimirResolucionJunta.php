<?php

	include_once("../controller/funciones.php");
	include_once("../controller/conexion.php");
	//verificarLogin(); 
    include_once("../fpdf/fpdf.php");
	//require("../fpdf/force_justify.php");
	
    $id 		 = $_GET['id']; 
	   
	class PDF extends FPDF{
    	// Cabecera de p�gina
    	function Header(){
			global $mysqli;		
			$numPag = $this->PageNo();
			$this->SetFont('Arial','I',8);
			
			$id = $_REQUEST['id'];

			$query = "SELECT a.nroresolucion FROM habilitacionjuntas a WHERE a.id = ".$id."";
			$result = $mysqli->query($query);
			if($row = $result->fetch_assoc()) {
				$nroresolucion = $row['nroresolucion'];
			}
			
			if($numPag > 1){
				$this->Cell(0,3,utf8_decode('Pág. ').$this->PageNo(),0,1,'R');	
				$this->Cell(0,3,utf8_decode('Resolución No ').$nroresolucion,0,0,'R');	
				$this->SetLineWidth(0.5);
				$this->Line(25,33,190,33);
			}
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
		
		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
		{
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
					$nb=substr_count($txt,' ');
					if($nb>0)
						$this->ws=($wmax-$this->GetStringWidth($txt))/$nb;
					else
						$this->ws=0;
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
    }    
    
	function obtener_nombre_mes($mes_num) {
		$meses = array(
			'01' => 'enero',
			'02' => 'febrero',
			'03' => 'marzo',
			'04' => 'abril',
			'05' => 'mayo',
			'06' => 'junio',
			'07' => 'julio',
			'08' => 'agosto',
			'09' => 'septiembre',
			'10' => 'octubre',
			'11' => 'noviembre',
			'12' => 'diciembre'
		);
		return $meses[$mes_num];
	}
	
	function convertir_numero_a_letras($numero) {
		$unidades = array('cero', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve');
		$decenas = array(10=>'diez', 20=>'veinte', 30=>'treinta', 40=>'cuarenta', 50=>'cincuenta', 60=>'sesenta', 70=>'setenta', 80=>'ochenta', 90=>'noventa');
		if ($numero < 10) {
			return $unidades[$numero];
		}
		if ($numero < 20) {
			$especial = array(11=>'once', 12=>'doce', 13=>'trece', 14=>'catorce', 15=>'quince', 16=>'diecis�is', 17=>'diecisiete', 18=>'dieciocho', 19=>'diecinueve');
			return $especial[$numero];
		}
		$decena = $numero - $numero % 10;
		$unidad = $numero % 10;
		if ($unidad > 0) {
			if ($decena == 20) {
				return 'veinti' . $unidades[$unidad];
			} else {
				return $decenas[$decena] . ' y ' . $unidades[$unidad];
			}
		} else {
			return $decenas[$decena];
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
	//$pdf->SetTopMargin(100);
	$pdf->SetMargins(25, 15 , 25);
	
	//Establecemos el margen inferior:
	//$pdf->SetAutoPageBreak(true,30);
    $pdf->SetFont('Arial','B',12);
    // T�tulo
	$pdf->Ln(22);
	$pdf->Cell(0,5,utf8_decode('REPÚBLICA DE PANAMÁ '),'0',1,'C');
	$pdf->Cell(0,5,utf8_decode('SECRETARÍA NACIONAL DE DISCAPACIDAD '),'0',1,'C');   
	$pdf->Ln(3);
	$pdf->Cell(0,5,utf8_decode('Dirección Nacional de Certificaciones '),'0',1,'C');   
	$pdf->Ln(3);
	
	$id = $_REQUEST['id'];

	$query = "SELECT a.nroresolucion, a.fechaevaluacion, a.fecharesolucion FROM habilitacionjuntas a WHERE a.id = ".$id."";
	$result = $mysqli->query($query);
	if($row = $result->fetch_assoc()) {
		
		$nroresolucion = $row['nroresolucion'];
		
		$fechaevaluacion = $row['fechaevaluacion'];
		$fecharesolucion = $row['fecharesolucion'];
		
		$fecha_hora_evaluacion = explode(" ", $fechaevaluacion);
		$fecha_hora_resolucion = explode(" ", $fecharesolucion);
		
		$fecha_arr_evaluacion = explode("-", $fecha_hora_evaluacion[0]);
		$fecha_arr_resolucion = explode("-", $fecha_hora_resolucion[0]);
		
		$dia_evaluacion = $fecha_arr_evaluacion[2];
		$dia_resolucion = $fecha_arr_resolucion[2];
		
		$mes_num_evaluacion = $fecha_arr_evaluacion[1];
		$mes_num_resolucion = $fecha_arr_resolucion[1];
		
		$anio_evaluacion = $fecha_arr_evaluacion[0];
		$anio_resolucion = $fecha_arr_resolucion[0];
		
		$mes_nom_evaluacion = obtener_nombre_mes($mes_num_evaluacion);
		$mes_nom_resolucion = obtener_nombre_mes($mes_num_resolucion);
		$fecha_formateada_evaluacion = "Del " . $dia_evaluacion . " de " . $mes_nom_evaluacion . " de " . $anio_evaluacion;

	}
			
	$pdf->Cell(0,5,utf8_decode('RESOLUCIÓN No ').$nroresolucion,'0',1,'C');   
	$pdf->Cell(0,5,'('.$fecha_formateada_evaluacion.')','0',1,'C');   
	$pdf->Ln(8);
   //$pdf->Ln(5);
   
	function letras($numuero){
		switch ($numuero)
		{
			case 31:{	$numu = "Treinta y uno";	break;	}
			case 30:{	$numu = "Treinta";		break;	}
			case 29:{	$numu = "Veintinueve";	break;	}
			case 28:{	$numu = "Veintiocho";	break;	}
			case 27:{	$numu = "Veintisiete";	break;	}
			case 26:{	$numu = "Veintiseis";	break;	}
			case 25:{	$numu = "Veinticinco";	break;	}
			case 24:{	$numu = "Veinticuatro";	break;	}
			case 23:{	$numu = "Veintitr�s";	break;	}
			case 22:{	$numu = "Veintidos";	break;	}
			case 21:{	$numu = "Veintiuno";	break;	}
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
			case 1:{	$numu = "Uno";		break;	}       
			case 0:{	$numu = "";			break;	}       
		}
		return lcfirst($numu);   
	}
	$meses = array('','enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	
	//CAJA 3 
	//$pdf->Cell(0,1,'','TL',1,'L');
	
	// Establecer la fuente en cursiva
	$pdf->SetFont('Arial', 'BI', 12);
	
	// Imprimir el texto en cursiva
	$pdf->Cell(0, 10, '"Por medio de la cual se conforma la Junta Evaluadora de la Discapacidad"', 0, 1, 'C');
	
	$pdf->Ln(8);
	$pdf->Cell(0,5,'EL DIRECTOR NACIONAL DE CERTIFICACIONES,',0,1,'C'); 
	$pdf->Ln(3);
	$pdf->SetFont('Arial','',12); 
	$pdf->Cell(0,5,'En uso de sus facultades legales,',0,1,'C'); 
	$pdf->Ln(3);
	$pdf->SetFont('Arial','B',12); 
	$pdf->Cell(0,5,'CONSIDERANDO:',0,1,'C'); 
	$pdf->Ln(3);
	$pdf->SetFont('Arial','',12);  
	$pdf->Cell(0,5,utf8_decode('Que mediante Ley No 23 de 28 de junio de 2007, se creó la Secretaría Nacional de'),0,1,'FJ');	
	$pdf->Cell(0,5,utf8_decode('Discapacidad (SENADIS), como entidad autónoma del Estado, con el fin de dirigir'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('y ejecutar la política de inclusión social de las personas con discapacidad y sus'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('familias;'),0,1,'L'); 
	$pdf->Ln(3); 
	$pdf->Cell(0,5,utf8_decode('Que los numerales 9 y 10 del Artículo 13 de la Ley supra citada, establecen como'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('funciones de la Secretaría Nacional de Discapacidad, el diseño de los baremos'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('nacionales necesarios para valorar la discapacidad que sustentan la emisión de la'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('certificación de discapacidad;'),0,1,'L'); 
	$pdf->Ln(3);
	$pdf->Cell(0,5,utf8_decode('Que mediante Decreto Ejecutivo No 37 de 11 de abril de 2014, modificado'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('mediante Decreto Ejecutivo No 18 de 24 de febrero de 2015, se creó la Dirección'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('Nacional de Certificaciones, la cual conforme al artículo 2, numeral 4, del  texto'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('legal citado, tiene como objetivos supervisar, dirigir y controlar los procesos de'),0,1,'FJ'); 
	$pdf->Cell(0,5,utf8_decode('evaluación, valoración y emisión de la certificación de la discapacidad;'),0,1,'L'); 
	$pdf->Ln(3);
	$pdf->Cell(0,5,utf8_decode('Que mediante Decreto Ejecutivo No 36 de 11 de abril de 2014, modificado'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('mediante Decreto Ejecutivo No 74 de 14 de abril de 2015, se reglamentó el'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('procedimiento de conformación y funcionamiento de las Juntas Evaluadoras de la'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Discapacidad, los baremos nacionales y se dictó el procedimiento para la'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('evaluación, valoración y certificación de la discapacidad;'),0,1,'L');
	$pdf->Ln(3);
	$pdf->Cell(0,5,utf8_decode('Que SENADIS atendiendo al tenor del artículo 7 del Decreto Ejecutivo No 36 de 11 '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('de abril de 2014, tiene la responsabilidad de conformar las Juntas Evaluadoras de '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('la Discapacidad, las cuales tienen como función ejecutar el procedimiento de '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('evaluación, valoración y certificación de las personas que de manera voluntaria '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('soliciten la certificación de discapacidad;'),0,1,'L');
	$pdf->Ln(3);
	$pdf->Cell(0,5,utf8_decode('Que conforme a lo establecido en los artículos 4 y 5, del Decreto Ejecutivo No 74 '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('de 14 de abril de 2015, cada Junta Evaluadora de la Discapacidad deberá estar'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('integrada por un mínimo tres (3) miembros y se deberán conformar '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('interdisciplinariamente, siendo requisito que sus miembros posean título'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('universitario en Medicina, Psicología, Fisioterapia, Fonoaudiología, Terapia'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Ocupacional, Trabajo Social, Docente, Psicopedagogo, Pedagogo, o Docente'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Especialista en dificultades del aprendizaje;'),0,1,'L');
	$pdf->Ln(3);
	$pdf->AddPage();
	$pdf->setY(38);
	$pdf->Cell(0,5,utf8_decode('Que el artículo 6, del Decreto Ejecutivo No 74 de 14 de abril de 2015, antes citado,'),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('estipula que los miembros de las Juntas Evaluadoras de la Discapacidad tienen '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('las siguientes funciones:'),0,1,'L');
	$pdf->Ln(3);
	$pdf->SetFont('Arial','I',11);
	$pdf->setX(32);
	$pdf->Cell(8,5,'1.	',0,0,'L');
	$pdf->Cell(0,5,utf8_decode('Evaluar a todas las personas que soliciten la certificación de discapacidad. Para'),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('ello las Juntas Evaluadoras de la Discapacidad, en el marco del Artículo 5, sobre '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('Igualdad y no discriminación de la Convención sobre los Derechos de las '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('Personas con Discapacidad, efectuará giras itinerantes en el interior del país. '),0,1,'FJ');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'2.	',0,0,'L');
	$pdf->Cell(0,5,utf8_decode('Completar el protocolo de evaluación y los formularios a partir de los cuales se'),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('obtendrá la información para valorar a la persona, siguiendo los procedimientos'),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('establecidos en los manuales que se aprueben para ello.'),0,1,'L');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'3.	',0,0,'L');
	$pdf->Cell(0,5,utf8_decode('Recopilar información clínica, social, laboral y escolar de las personas que soliciten '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('la evaluación para la certificación de discapacidad. '),0,1,'L');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'4.	',0,0,'L');
	$pdf->Cell(0,5,utf8_decode('Realizar las preguntas necesarias que lleven a esclarecer y ampliar las situaciones '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('determinadas de los solicitantes.'),0,1,'L');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'5.	',0,0,'L');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('Codificar las categorías que describen el perfil de funcionamiento de la persona, '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('según la CIF, acorde a lo requerido en cada caso.  '),0,1,'L');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'6.	',0,0,'L');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('Firmar y sellar el protocolo de evaluación.'),0,1,'L');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'7.	',0,0,'L');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('Firmar y sellar el formulario que sustenta el otorgamiento de la certificación de '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('discapacidad, cuando corresponda.'),0,1,'L');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'8.	',0,0,'L');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('Firmar y sellar el formulario de denegatoria de la certificación de discapacidad, '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('cuando corresponda.'),0,1,'L');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'9.	',0,0,'L');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('Brindar orientación y asesoría en lo que conlleva el proceso de certificación de la '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('discapacidad, a las personas que así lo soliciten'),0,1,'L');
	$pdf->Ln(3);
	$pdf->setX(32);
	$pdf->Cell(8,5,'10. ',0,0,'L');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('Mantener la confidencialidad y privacidad de la información contenida en los '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('expedientes de las personas que soliciten la certificación de discapacidad acorde a '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('lo que establece la Ley No 68 de 20 de noviembre de 2003, que regula los '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('derechos y obligaciones de los pacientes, en materia de información y de decisión '),0,1,'FJ');
	$pdf->setX(40);
	$pdf->Cell(0,5,utf8_decode('libre e informada.'),0,1,'L');
	$pdf->Ln(3);
	$pdf->SetFont('Arial','',12); 
	$pdf->Cell(0,5,utf8_decode('Que del mismo modo, el artículo 7, del Decreto Ejecutivo No 74 de 14 de abril de '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('2015, establece que los miembros de las Juntas Evaluadoras de la Discapacidad '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('serán escogidos y designados por el o la Directora de la Secretaría Nacional de '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Discapacidad, previo el cumplimiento del procedimiento de postulación y selección  '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('contemplado en el artículo 14 del Decreto Ejecutivo No 36 de 11 de abril de 2014; '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('modificado por el artículo 8 del Decreto Ejecutivo No 74 de 14 de abril de 2015;'),0,1,'L');
	$pdf->Ln(6);
	$pdf->Cell(0,5,utf8_decode('Que una vez cumplido, el procedimiento de postulación, selección y '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('nombramiento, arriba descrito, la Directora Nacional de la Secretaría de '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Discapacidad, mediante Resolución No 71 de 21 de septiembre de 2021, nombra a '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('la  Directora Nacional de Certificaciones quien queda facultada para instalar la '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Junta Evaluadora, para cada proceso de evaluación, valoración y certificación de '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('la discapacidad;'),0,1,'L');
	$pdf->Ln(6);
	$pdf->Cell(0,5,utf8_decode('Que en virtud de lo dispuesto en la Resolución antes mencionada, el Director '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Nacional de Certificaciones de la Secretaría Nacional de Discapacidad;'),0,1,'FJ');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',12);  
	$pdf->Cell(0,5,'RESUELVE:',0,1,'C');
	$pdf->Ln(5); 
	$pdf->Cell(24,5,'PRIMERO.',0,0,'C');
	$x = $pdf->getX();
	$y = $pdf->getY();
	$pdf->SetLineWidth(0.5);
	$pdf->Line(26,$y+5,46,$y+5);
	$pdf->SetLineWidth(0);
	$pdf->Cell(24,5,'INSTALAR',0,0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(7,5,' la ',0,0,'FJ');
	$pdf->SetFont('Arial','B',12); 
	$pdf->Cell(50,5,'JUNTA EVALUADORA,',0,0,'FJ');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(60,5,' conformada por los siguientes',0,1,'FJ');
	$pdf->Cell(0,5,'miembros:',0,1,'L');
	
	$pdf->AddPage();
	$pdf->setY(38);
	$query = "SELECT a.id, a.nroresolucion, b.idmedicos, c.idpacientes,
				  d.cedula AS cedulamedico,CONCAT(d.nombre,' ',d.apellido) AS medico,d.nroregistro,
			      e.cedula AS cedulapaciente, CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno) AS paciente, f.nombre AS especialidad,
				  (
						SELECT COUNT(*) 
						FROM habilitacionjuntas t2 
						WHERE 
							t2.idregionales = a.idregionales AND 
							t2.creation_time <= a.creation_time AND 
							DATE(t2.creation_time) = DATE(a.creation_time)
					) AS posicion
				  FROM habilitacionjuntas a
				  LEFT JOIN habilitacionjuntasmedicos b ON a.id = b.idhabilitacionjuntas
				  LEFT JOIN habilitacionjuntaspacientes c ON a.id = c.idhabilitacionjuntas
				  LEFT JOIN medicos d ON d.id = b.idmedicos
				  LEFT JOIN pacientes e ON e.id = c.idpacientes
				  LEFT JOIN especialidades f ON f.id = d.especialidad
				  WHERE a.id = ".$id."";
		$result = $mysqli->query($query);
	
		$resultado = array();
	
		while ($row = $result->fetch_assoc()) {
			//$resultado['id'] = $row['id'];
			$resultado['nroresolucion'] = $row['nroresolucion'];
			$resultado['nrojunta'] = $row['posicion'];
	
			$medicos = array();
			$pacientes = array();
			$medicos_ids = array();
			$pacientes_ids = array();
			
			 // Agregar todos los m�dicos y pacientes relacionados con la junta al array
			while ($row) {
				if (!in_array($row['idmedicos'], $medicos_ids)) {
					$medico = array(
						'id' => $row['idmedicos'],
						'cedula' => $row['cedulamedico'],
						'medico' => $row['medico'],
						'especialidad' => $row['especialidad'],
						'nroregistro' => $row['nroregistro'],
					);
					$medicos[] = $medico;
					$medicos_ids[] = $row['idmedicos'];
				}
				if (!in_array($row['idpacientes'], $pacientes_ids)) {
					$paciente = array(
						'id' => $row['idpacientes'],
						'cedula' => $row['cedulapaciente'],
						'paciente' => $row['paciente']
					);
					$pacientes[] = $paciente;
					$pacientes_ids[] = $row['idpacientes'];
				}
				$row = $result->fetch_assoc(); // Avanzar al siguiente registro
			}
 
		}
	$pdf->setX(15);
	$pdf->SetFont('Arial','B',12); 
	$pdf->Cell(10, 10, '', 'LTRB', 0, 'L');
	$pdf->Cell(50, 10, 'Nombre', 'LTRB', 0, 'C');
	$pdf->Cell(45, 10, 'Registro', 'LTRB', 0, 'C');
	$pdf->Cell(30, 10, 'Cédula', 'LTRB', 0, 'C');
	$pdf->Cell(50, 10, 'Profesión', 'LTRB', 1, 'C');
	
	$pdf->SetFont('Arial','',12); 
	$i = 0;
	
	foreach ($medicos as $medico) {
		$i++;
		$pdf->setX(15);
		$pdf->Cell(10,10,$i.'.',1,0,'L');
		$pdf->Cell(50,10,utf8_decode($medico['medico']),1,0,'C');
		$pdf->Cell(45,10,$medico['nroregistro'],1,0,'C');
		$pdf->Cell(30,10,$medico['cedula'],1,0,'C');
		$pdf->Cell(50,10,utf8_decode($medico['especialidad']),1,1,'C');
	}
	
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',12); 
	$pdf->Cell(25,4,'SEGUNDO. ',0,0,'FJ');
	$x = $pdf->getX();
	$y = $pdf->getY();
	$pdf->SetLineWidth(0.5);
	$pdf->Line(26,$y+4,47,$y+4);
	$pdf->SetLineWidth(0);
	$pdf->SetFont('Arial','',12); 
	$pdf->Cell(59,5,' Que los miembros de la',0,0,'FJ');
	$pdf->SetFont('Arial','B',12); 
	$pdf->Cell(54,5,'JUNTA EVALUADORA,',0,0,'FJ');
	$pdf->SetFont('Arial','',12); 
	$pdf->Cell(27,5,' conformada ',0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('mediante la presente resolución, quedan facultadas para evaluar conforme el '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('procedimiento contemplado en el Decreto Ejecutivo No 36 de 11 de abril de 2014, '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('a las personas descritas a continuación:'),0,1,'L');
	
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(121,10,'Nombre',1,0,'C');
	$pdf->Cell(40,10,'Cédula',1,1,'C');
	
	$pdf->SetFont('Arial','',12);
	
	foreach ($pacientes as $paciente) {
		$pdf->Cell(121,10,utf8_decode($paciente['paciente']),1,0,'C'); 
		$pdf->Cell(40,10,$paciente['cedula'],1,1,'C');
	}
	
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(25,5,'TERCERO.',0,0,'FJ');
	$x = $pdf->getX();
	$y = $pdf->getY();
	$pdf->SetLineWidth(0.5);
	$pdf->Line(26,$y+5,46,$y+5);
	$pdf->SetLineWidth(0);
	$pdf->SetFont('Arial','',12); 
	$pdf->Cell(0,5,utf8_decode('La presente resolución entrará a regir a partir de la fecha de su firma'),0,1,'FJ');
	$pdf->Ln(3);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(65,5,'FUNDAMENTO DE DERECHO.',0,0,'FJ');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(0,5,utf8_decode(' Artículos 1 y 13 de la Ley No 23 de 28 de junio de '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('2007; Capítulo II del Decreto Ejecutivo No 36 de 11 de abril de 2014;  Artículos 1  y '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('2  del Decreto Ejecutivo No 18 de 24 de febrero de 2015, que modifica el  Decreto '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Ejecutivo No 37 de 11 de abril de 2014, y Artículos 4, 5, 6, 7 y 8 del Decreto '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('Ejecutivo No 74 de 14 de abril de 2015,  que modifica el Decreto Ejecutivo No 36 '),0,1,'FJ');
	$pdf->Cell(0,5,utf8_decode('de 11 de abril de 2014.'),0,1,'L');
	$pdf->Ln(3); 
	$pdf->Cell(0,5,utf8_decode('Dada en la Ciudad de Panamá, el día '.letras($dia_resolucion).' ('.$dia_resolucion.') del mes de '.$mes_nom_resolucion.' del dos mil '),0,1,'FJ');
	$ultimos_dos_digitos = intval(substr($anio_resolucion, -2));
	$ultimos_dos_digitos_words = convertir_numero_a_letras($ultimos_dos_digitos);
	$pdf->Cell(0,5,$ultimos_dos_digitos_words.' ('.$anio_resolucion.').',0,1,'L');
	
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(0,5,'PUBLIQUESE Y CÚMPLASE',0,1,'C');
	$y1 = $pdf->getY(); 
	$pdf->Image('../images/reportes/sello.png',93,$y1+2,31);
	$pdf->setY($y1+43);
	$pdf->Cell(0,5,'Lcda. Aileen Aparicio',0,1,'C');
	$pdf->Cell(0,5,'Directora Nacional de Certificaciones',0,1,'C'); 
	$pdf->Output('I',"resolucion.pdf");
?>