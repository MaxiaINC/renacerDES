<?php
	include("../controller/conexion.php"); 
	require '../vendor/phpspreadsheet/vendor/autoload.php';

	global $mysqli; 
	$idsolicitud 	= (!empty($_REQUEST['idsolicitud']) ? $_REQUEST['idsolicitud'] : '0');
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
	$spreadsheet->getActiveSheet()->getStyle("A1:C7")->applyFromArray($stylebordernone);
	$spreadsheet->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');
	
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
	->setCellValue('B4', 'Historial de solicitud');
	
	$spreadsheet->getActiveSheet()->mergeCells('D2:F2');
	
	$sql = "	SELECT 
					CONCAT(b.nombre,' ',b.apellidopaterno,' ',b.apellidomaterno) AS beneficiario,
					b.cedula, b.expediente, a.fecha_solicitud
				FROM
					solicitudes a
				INNER JOIN pacientes b ON b.id = a.idpaciente
				WHERE a.id = ".$idsolicitud;
	$r = $mysqli->query($sql);
	
	if($reg = $r->fetch_assoc()){
		
		$beneficiario = $reg['beneficiario'];
		$cedula = $reg['cedula'];
		$expediente = $reg['expediente'];
		$fechasolicitud = $reg['fecha_solicitud'];
	} 
	
	$spreadsheet->getActiveSheet()->setCellValue('A6', "Paciente: ".$beneficiario." - Expediente: ".$expediente);
	$spreadsheet->getActiveSheet()->setCellValue('B6', "Fecha de solicitud: ".$fechasolicitud);
	
	// ENCABEZADO 
	$spreadsheet->getActiveSheet()
	->setCellValue('A8', 'Acción')
	->setCellValue('B8', 'Fecha')
	->setCellValue('C8', 'Usuario');
	
	$spreadsheet->getActiveSheet()->getStyle('A8:C8')->getFont()->setBold(true)->setSize(10)->setColor($fontColor);
	$spreadsheet->getActiveSheet()->getStyle('A8:C8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('293F76');
	$i = 9;
	//SENTENCIA BASE  
	$query ="	SELECT a.id, b.nombre, a.fecha, a.accion, a.modulo 
				FROM bitacora a 
				INNER JOIN usuarios b ON b.usuario = a.usuario
				WHERE modulo IN ('Solicitudes','Agenda') AND identificador = ".$idsolicitud."
				UNION
				SELECT a.id, d.nombre, a.fecha, a.accion, a.modulo 
				FROM bitacora a  
				LEFT JOIN evaluacion b ON ((b.id = a.identificador AND a.accion NOT LIKE '%Fue actualizado un registro en Solicitud%' ) OR (a.accion LIKE '%Fue actualizado un registro en Solicitud%' AND a.identificador = ".$idsolicitud."))
				INNER JOIN solicitudes c ON c.id = b.idsolicitud
				INNER JOIN usuarios d ON d.usuario = a.usuario
				WHERE modulo = 'Evaluación' AND b.idsolicitud = ".$idsolicitud." AND accion != 'guardarCif'
				ORDER BY fecha DESC
				";  
	$rta = $mysqli->query($query);
	while($row = $rta->fetch_assoc()){
		$id = $row['id']; 
		
		$actualizar = 0;
		$pos = strpos($row['accion'],'Fue creado un registro en Evaluación con el id');
		$posUpd = strpos($row['accion'],'Fue actualizado un registro en Evaluación con el id');
		if ($pos !== false) { //Crear evaluación
			
			$arrAccion = explode('<li>',$row['accion']);
			$newstring = ""; 
			foreach ($arrAccion as $clave => $valor) {
				
				if($clave>0){
					 
					$arrCampo = explode(':',$valor);
					$campo = trim(strip_tags($arrCampo[0]));
					$value = strip_tags($arrCampo[1]);	
					$replValue = str_replace('"',' ',$arrCampo[1]);
					$replValue = str_replace('</li>',' ',$replValue);
					$replValue = str_replace('.',' ',$replValue);
					
					if($campo == 'Tipo de solicitud'){ 
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Primera vez',2=>'Renovación',3=>'Reevaluación',4=>'Reconsideración']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Alfabetismo'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Alfabetizado',2=>'Analfabeto',3=>'Analfabeto instrumental',4=>'No aplicable']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Nivel educacional'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Inicial',2=>'Primario',3=>'Secundario',4=>'Terciario / Universitario']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Nivel educacional completado'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Completo',2=>'Incompleto (Concurre)',3=>'Incompleto (Concurrió hasta que nivel esc.)',4=>'Adecuaciones curriculares']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Concurrencia del tipo de educación'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Concurre',2=>'Concurrió',3=>'Nunca concurrió']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Convivencia'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Vive solo',2=>'Vive acompañado',3=>'Internado / Albergue']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Tipo de vivienda'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'SI',2=>'NO']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Concurrencia educacional completada'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Educación antes del daño',2=>'Educación después del daño',3=>'Educación antes y después del daño']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Vivienda adaptada'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'SI',2=>'NO']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Medio de transporte'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Menos de 300 metros',2=>'Más de 300 metros']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Estado de las calles'){
						$newvalue = getNameCmbStatic($replValue,[0=>'Sin especificar',1=>'Asfaltado o pavimento',2=>'Mejorado',3=>'Tierra']);
						$newstring .= $campo.':'.$newvalue.'<li>'; 
					}elseif($campo == 'Vínculos'){
						$n_vinculos = getNameCmbVinculos($replValue);
						$newstring .= $campo.':'.$n_vinculos.'<li>'; 
					}elseif($campo == 'Documentos'){
						$n_documentos = getNameCmbDocumentos($replValue);
						$newstring .= $campo.':'.$n_documentos.'<li>';
					}elseif($campo == 'Diagnósticos'){
						$n_diagnosticos = getValoresA("codigo,'-',nombre",'enfermedades',$replValue,'diagnosticos');
						$newstring .= $campo.':'.$n_diagnosticos.'<li>';
					}elseif($campo == 'Tipo de educación'){
						$n_tipoeducacion = getNameTipoEducacion($replValue);
						$newstring .= $campo.':'.$n_tipoeducacion.'<li>';
					}else{
						$newstring .= $campo.':'.$replValue.'<li>'; 
					}	 
				}else{
					$newstring .= strip_tags($valor);
					$newstring = $newstring.'<li>'; 
					$newstring = strip_tags(str_replace("<li>","\n",($newstring)));
				}
			}
			$newstring = str_replace("<li>","\n",$newstring);
			
		}elseif($posUpd !== false){ //Actualizar evaluación
			
			$actualizar = 1;
			$arrAccion = explode('<li>',$row['accion']);
			$newstring = ""; 
			
			foreach ($arrAccion as $clave => $valor) { 
				if($clave>0){
					
					$posCamp = strpos($valor,'El campo');
					if($posCamp !== false){
						$valor = strip_tags($valor);
						
						$posNombreAcompanante = strpos($valor,'Nombre del/la acompañante');
						if($posNombreAcompanante !== false){ 
							$valor = str_replace('Nombre del/la acompañante','Nombre del-la acompañante',$valor);
						} 
						
						$arrCampo = explode('/',$valor);
						$strAnt = $arrCampo[0];
						$strNew = $arrCampo[1];
						$arrStrAnt = explode(':',$strAnt);
						$arrStrNew = explode(':',$strNew);
						$cmpAnt = $arrStrAnt[0];
						$valAnt = trim($arrStrAnt[1]);
						$cmpNew = $arrStrNew[0];
						$valNew = trim($arrStrNew[1]);
							
						$posTipoSol = strpos($cmpAnt,'Tipo de solicitud');
						$posAlf = strpos($cmpAnt,'Alfabetismo');
						$posNivEd = strpos($cmpAnt,'Nivel educacional');
						$posNivEdC = strpos($cmpAnt,'Nivel educacional completado');
						$posTipoEd = strpos($cmpAnt,'Tipo de educación');
						$posConcTipEd = strpos($cmpAnt,'Concurrencia del tipo de educación');
						$posConviv = strpos($cmpAnt,'Convivencia');
						$posTipoViv = strpos($cmpAnt,'Tipo de vivienda');
						$posConcComp = strpos($cmpAnt,'Concurrencia educacional completada');
						$posVivAdap = strpos($cmpAnt,'Vivienda adaptada');
						$posMedioTr = strpos($cmpAnt,'Medio de transporte');
						$posEstCall = strpos($cmpAnt,'Estado de las calles');
						$posVinculos = strpos($cmpAnt,'Vínculos');
						$posDocument = strpos($cmpAnt,'Documentos');
						$posDiag = strpos($cmpAnt,'Diagnósticos');
						$posAcomp = strpos($cmpAnt,'Acompañante');
						$posNomAcomp = strpos($cmpAnt,'Nombre del-la acompañante');
						$posAyudTec = strpos($cmpAnt,'Ayuda técnica');
						$posDurac = strpos($cmpAnt,'Duración');
						$posTipoDurac = strpos($cmpAnt,'Tipo de duración');
						
						if($posTipoSol !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'Primera vez',2=>'Renovación',3=>'Reevaluación',4=>'Reconsideración']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'Primera vez',2=>'Renovación',3=>'Reevaluación',4=>'Reconsideración']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posAlf !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'Alfabetizado',2=>'Analfabeto',3=>'Analfabeto instrumental',4=>'No aplicable']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'Alfabetizado',2=>'Analfabeto',3=>'Analfabeto instrumental',4=>'No aplicable']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posNivEd !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'Inicial',2=>'Primario',3=>'Secundario',4=>'Terciario / Universitario']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'Inicial',2=>'Primario',3=>'Secundario',4=>'Terciario / Universitario']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posNivEdC !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'Completo',2=>'Incompleto (Concurre)',3=>'Incompleto (Concurrió hasta que nivel esc.)',4=>'Adecuaciones curriculares']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'Completo',2=>'Incompleto (Concurre)',3=>'Incompleto (Concurrió hasta que nivel esc.)',4=>'Adecuaciones curriculares']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posConcTipEd !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'Concurre',2=>'Concurrió',3=>'Nunca concurrió']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'Concurre',2=>'Concurrió',3=>'Nunca concurrió']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posConviv !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'Vive solo',2=>'Vive acompañado',3=>'Internado / Albergue']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'Vive solo',2=>'Vive acompañado',3=>'Internado / Albergue']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posTipoViv !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'SI',2=>'NO']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'SI',2=>'NO']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posConcComp !== false){
							$lmpValAnt = trim(str_replace('.', ' ',$valAnt));
							$lmpValNew = trim(str_replace('.', ' ',$valNew));
							$newValAnt = getNameCmbStatic($lmpValAnt,[0=>'Sin especificar',1=>'Educación antes del daño',2=>'Educación después del daño',3=>'Educación antes y después del daño']);
							$newValNew = getNameCmbStatic($lmpValNew,[0=>'Sin especificar',1=>'Educación antes del daño',2=>'Educación después del daño',3=>'Educación antes y después del daño']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posVivAdap !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'SI',2=>'NO']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'SI',2=>'NO']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posMedioTr !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'Menos de 300 metros',2=>'Más de 300 metros']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'Menos de 300 metros',2=>'Más de 300 metros']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posEstCall !== false){
							$newValAnt = getNameCmbStatic($valAnt,[0=>'Sin especificar',1=>'Asfaltado o pavimento',2=>'Mejorado',3=>'Tierra']);
							$newValNew = getNameCmbStatic($valNew,[0=>'Sin especificar',1=>'Asfaltado o pavimento',2=>'Mejorado',3=>'Tierra']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posVinculos !== false){
							$newValAnt = getNameCmbVinculos($valAnt); 
							$newValNew = getNameCmbVinculos($valNew); 
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>'; 
						}elseif($posDocument !== false){
							$n_documentosAnt = getNameCmbDocumentos($valAnt);
							$n_documentosNew = getNameCmbDocumentos($valNew);
							$newstring .= $cmpAnt.': '.$n_documentosAnt.' / '.$cmpNew.': '.$n_documentosNew.'<li>';
						}elseif($posTipoEd !== false){
							$posStrArrAnt = strpos($valAnt,'Array');
							$posStrArrNew = strpos($valNew,'Array');
							
							if($posStrArrAnt !== false || $posStrArrNew !== false){
							}else{
								$newValAnt = getNameTipoEducacion($valAnt);
								$newValNew = getNameTipoEducacion($valNew);
								$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValAnt.'<li>';
							}
							
						}elseif($posDiag !== false){
							$valNew = str_replace('.','',$valNew);
							$n_diagnosticosAnt = getValoresA("codigo,'-',nombre",'enfermedades',$valAnt,'diagnosticos');
							$n_diagnosticosNew = getValoresA("codigo,'-',nombre",'enfermedades',$valNew,'diagnosticos');
							if($n_diagnosticosAnt != $n_diagnosticosNew){
								$newstring .= $cmpAnt.': '.$n_diagnosticosAnt.' / '.$cmpNew.': '.$n_diagnosticosNew.'<li>';	
							} 
						}elseif($posAyudTec !== false){
							debugL('valAnt:'.$valAnt.'-valNew:'.$valNew,'DEBUGLREPORTE');
							$isEmptyAnt = inEmpty($valAnt);
							$isEmptyNew = inEmpty($valNew);
							debugL('isEmptyAnt:'.$isEmptyAnt.'-isEmptyAnt:'.$isEmptyNew,'DEBUGLREPORTE');
							if($isEmptyAnt == 1 && $isEmptyNew == 1){
							}else{
								$newstring .= $cmpAnt.': '.$valAnt.' / '.$cmpNew.': '.$valNew.'<li>';
							} 
						}elseif($posDurac !== false){
							$isEmptyAnt = inEmpty($valAnt);
							$isEmptyNew = inEmpty($valNew);
							if($isEmptyAnt == 1 && $isEmptyNew == 1){
							}else{
								$newstring .= $cmpAnt.': '.$valAnt.' / '.$cmpNew.': '.$valNew.'<li>';
							}  
						}elseif($posTipoDurac !== false){
							$isEmptyAnt = inEmpty($valAnt);
							$isEmptyNew = inEmpty($valNew);
							
							//debugL('isEmptyAnt:'.$isEmptyAnt.'-isEmptyAnt:'.$isEmptyAnt,'DEBUGLREPORTE');
							if($isEmptyAnt == 1 && $isEmptyNew == 1){
							}else{
								//debugL('ANTES valAnt:'.$valAnt.'-valNew:'.$valNew.'-','DEBUGLREPORTE');
								$valAnt = getStrTipoDuracion($valAnt);
								$valNew = getStrTipoDuracion($valNew); 
								//debugL('DESPUÉS valAnt:'.$valAnt.'-valNew:'.$valNew.'-','DEBUGLREPORTE');
								$newstring .= $cmpAnt.': '.$valAnt.' / '.$cmpNew.': '.$valNew.'<li>';
							}  
						}elseif($posAcomp !== false){
							$lmpValAnt = getIntAcomp($valAnt);
							$lmpValNew = getIntAcomp($valNew); 
							$newValAnt = getNameCmbStatic($lmpValAnt,[0=>'Sin especificar',1=>'SI',2=>'NO']);
							$newValNew = getNameCmbStatic($lmpValNew,[0=>'Sin especificar',1=>'SI',2=>'NO']);
							$newstring .= $cmpAnt.': '.$newValAnt.' / '.$cmpNew.': '.$newValNew.'<li>';
						}elseif($posNomAcomp !== false){ 
						
							$tipeant = gettype($valAnt); 
							$tipenew = gettype($valNew);  
							$isEmptyAnt = inEmpty($valAnt);
							$isEmptyNew = inEmpty($valNew); 
							
							if($isEmptyAnt == 1 && $isEmptyNew == 1){
							}else{
								
								$newstring .= $cmpAnt.': '.$valAnt.' / '.$cmpNew.': '.$valNew.'<li>';
								$newstring = str_replace('Nombre del-la acompañante','Nombre del/la acompañante',$newstring);
							}
						}else{
							$newstring .= $valor.'<li>'; 
						} 
						$newstring = strip_tags(str_replace("<li>","\n",($newstring)));
					} 
				}else{
					$newstring .= strip_tags($valor);
					$newstring = $newstring.'<li>'; 
					$newstring = strip_tags(str_replace("<li>","\n",($newstring)));
				} 
			} 
		}else{  
			$newstring = strip_tags(str_replace("<li>","\n",($row['accion'])));
		} 
		 
		$newstring = utf16_2_utf8($newstring);
		
		$long = strlen($newstring);
		
		if(($actualizar == 0) || ($long>70 && $actualizar == 1)){
			$spreadsheet->getActiveSheet()
			//->setCellValue('A'.$i,utf16_2_utf8(strip_tags(str_replace("<li>","\n",($row['accion'])))))
			->setCellValue('A'.$i,$newstring)
			->setCellValue('B'.$i,$row['fecha'])
			->setCellValue('C'.$i,$row['nombre']);
			$spreadsheet->getActiveSheet()
			->getStyle('A'.$i)
			->getAlignment()
			->setWrapText(true);
			$i++;
		} 
	} 
	
	function getIntAcomp($string){
		$lmpStr = trim(str_replace('.', ' ',$string));
		if($lmpStr == 'SI'){
			$rta = 1;
		}else if($lmpStr == 'NO'){
			$rta = 2;
		}else{
			$rta = 0;
		}
		return $rta;
	}
	
	function getStrTipoDuracion($string){
		$string = trim(str_replace('.',' ',$string));
		if($string == 'A'){
			$rta = 'Año(s)';
		}elseif($string == 'M'){
			$rta = 'Mes(es)';
		}else{
			$rta = 'Sin especificar';
		}
		return $rta;
	}
	
	function getNameTipoEducacion($string){
		$n_tipoeducacion = str_replace(0,"Sin especificar",$string);
		$n_tipoeducacion = str_replace(1,"Educación no formal",$n_tipoeducacion);
		$n_tipoeducacion = str_replace(2,"Escuela especial",$n_tipoeducacion);
		return $n_tipoeducacion;
	}
	
	function getNameCmbDocumentos($string){
		$n_documentos = str_replace(1,"Certificado médico",$string);
		$n_documentos = str_replace(2,"Resumen historia clínica",$n_documentos);
		$n_documentos = str_replace(3,"Historia clínica",$n_documentos);
		$n_documentos = str_replace(4,"Estudios",$n_documentos);
		$n_documentos = str_replace(0,"Sin especificar",$n_documentos);
		return $n_documentos;
	}
	
	function getNameCmbVinculos($string){
		$n_vinculos = str_replace(0,'Sin especificar',$string);
		$n_vinculos = str_replace(1,'Hijo',$n_vinculos);
		$n_vinculos = str_replace(2,'Madre',$n_vinculos);
		$n_vinculos = str_replace(3,'Hermano',$n_vinculos);
		$n_vinculos = str_replace(4,'Cónyuge',$n_vinculos);
		$n_vinculos = str_replace(5,'Padre',$n_vinculos);
		$n_vinculos = str_replace(6,'Abuelo',$n_vinculos);
		$n_vinculos = str_replace(7,'Otro familiar',$n_vinculos);
		$n_vinculos = str_replace(8,'Otro no familiar',$n_vinculos);
		return $n_vinculos;
	}
	
	function getNameCmbStatic($var,$array){
		
		if($var === 'A'){
			$var = 1;
		}elseif($var === 'D'){
			$var = 2;
		}elseif($var === 'AD'){
			$var = 3;
		}
		
		foreach ($array as $clave => $valor) {  
			if($var == $clave){
				return $valor;	
			}  
		} 
	}
	
	function getNameCmbMultStatic($tring,$replace,$find){
		
		$new = str_replace($find, $replace, $string);
		return ($new);
	}
	
	function utf16_2_utf8 ($string) {
		$find = array('u00e1', 'u00e9', 'u00ed', 'u00f3', 'u00fa', 'u00c1', 'u00c9', 'u00cd', 'u00d3', 'u00da', 'u00f1', 'u00d1');
		$replace = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'U', 'ñ', 'Ñ');	
		$new = str_replace($find, $replace, $string);  
		return ($new);
	}
	
	function inEmpty($val){
		
		if($val == '' || $val == ' ' || $val == '0' || $val == ' .' || $val == '.'){
			return 1;
		}else{
			return 0;
		} 
	}
	//Ancho automatico
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(100);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(20);
	$hoy = date('dmY');
	$nombreArc = 'Reporte - Historial Solicitud '.$hoy.'.xlsx';
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