<?php

//FUNCIONES CORPORALES
function extraervalb($b){
	$long = strlen($b);
	if($long == 1){
		$b11  = "";					$b12  = "";					$b13  = "";					$b14  = "";
	}elseif($long == 2){
		$b11  = substr($b, -1,1);	$b12  = "";					$b13  = "";					$b14  = "";
	}elseif($long == 3){
		$b11  = substr($b, -2,1);	$b12  = substr($b, -1,1);	$b13  = "";					$b14  = "";
	}elseif($long == 4){
		$b11  = substr($b, -3,1);	$b12  = substr($b, -2,1);	$b13  = substr($b, -1,1);	$b14  = "";
	}elseif($long == 5){
		$b11  = substr($b, -4,1);	$b12  = substr($b, -3,1);	$b13  = substr($b, -2,1);	$b14  = substr($b, -1,1);
	}
	
	return $arr = array($b11, $b12, $b13, $b14);
}

function imprimirvaloresb($valores, $categorizador, $pdf, $num, $bi){
	if($bi == 1 || ($bi % 4 === 0 && $bi % 8 !== 0) || $bi % 7 === 0 || $bi % 10 === 0){
		
		if($bi > 1){
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(3,14,'','LR',0,'L');
			$pdf->Cell(49,14, '','LR',0,'L',true);
			$pdf->SetFillColor(214,216,215);
		}
		$pdf->Cell(6,14,'b'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,$valores[0],'1',0,'R');
		$pdf->Cell(6,14,$valores[1],'1',0,'C');
		$pdf->Cell(6,14,$valores[2],'1',0,'C',true);
		$pdf->Cell(6,14,$valores[3],'1',0,'C',true);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(5,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(6,14,$categorizador,'1',0,'C',true);
			$pdf->Cell(9,14,'','LR',0,'C');
	}elseif($bi % 3 === 0){
		$pdf->Cell(6,14,'b'.$num,'1',0,'C',true);	
		$pdf->Cell(6,14,$valores[0],'1',0,'C',true);	
		$pdf->Cell(6,14,$valores[1],'1',0,'C',true);	
		$pdf->Cell(6,14,$valores[2],'1',0,'C',true);	
		$pdf->Cell(6,14,$valores[3],'1',0,'C',true);	
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(5,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(6,14,$categorizador,'1',0,'C',true);	
		$pdf->Cell(3,14,'','LR',1,'L');
	}elseif($bi % 2 === 0 || $bi % 5 === 0){
		$pdf->Cell(6,14,'b'.$num,'1',0,'C',true);	
		$pdf->Cell(6,14,$valores[0],'1',0,'C',true);	
		$pdf->Cell(6,14,$valores[1],'1',0,'C',true);	
		$pdf->Cell(6,14,$valores[2],'1',0,'C',true);	
		$pdf->Cell(6,14,$valores[3],'1',0,'C',true);	
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(5,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(6,14,$categorizador,'1',0,'C',true);	
			$pdf->Cell(9,14,'','LR',0,'C');
	}
}

function colvaciasb($col, $pdf, $num){	
	if($col == 3){
		$pdf->Cell(6,14,'b'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,'','1',0,'R');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C',true);
		$pdf->Cell(6,14,'','1',0,'C',true);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(5,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(6,14,'','1',0,'C',true);
			$pdf->Cell(9,14,'','LR',0,'C');
	}
	if($col == 3 || $col == 2){
		$pdf->Cell(6,14,'b'.$num,'1',0,'C',true);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(5,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
			$pdf->Cell(9,14,'','LR',0,'C');
	}
	if($col == 3 || $col == 2 || $col == 1){
		$pdf->Cell(6,14,'b'.$num,'1',0,'C',true);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(5,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(6,14,'','1',0,'C',true);	
		$pdf->Cell(3,14,'','LR',1,'L');
	}	
}

function ordenar($array, $on, $order=SORT_ASC){
	$new_array = array();
	$sortable_array = array();

	if (count($array) > 0) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					if ($k2 == $on) {
						$sortable_array[$k] = $v2;
					}
				}
			} else {
				$sortable_array[$k] = $v;
			}
		}

		switch ($order) {
			case SORT_ASC:
				asort($sortable_array);
			break;
			case SORT_DESC:
				arsort($sortable_array);
			break;
		}

		foreach ($sortable_array as $k => $v) {
			$new_array[$k] = $array[$k];
		}
	}
	return $new_array;
}

function imprimirfcb($cif, $num, $bnum, $pdf, $multi){
	//$cif = json_decode($cif);
	foreach($cif as $clave => $valor) {
		if($clave == 'b'){
			$bi = 1;
			$fin = sizeof($valor);
			$k = 1;
			$mod = 0;
			
			$valor = ordenar($valor, 'grupo', SORT_DESC);
			foreach($valor as $claveb => $valorb) {				
				$b = str_replace('b','',$valorb->codigocif);
				$grupotxt 	= explode(' | ',$valorb->grupotxt);
				$grupo 		= str_replace('b','',$grupotxt[0]);
				
				if($grupo == $num){
					$categorizador = $valorb->c1;
					if($num == 7){
						debug($categorizador);
					}					
					$valores = extraervalb($b);
					imprimirvaloresb($valores, $categorizador, $pdf, $num, $bi);
					$bnum++;
					$bi++;
				}elseif($grupo == $num+1){
					if (fmod($bnum, $multi) <> 0) {
						$mod = ($bnum-fmod($bnum, $multi))+$multi;
					}					
					$falta = $mod - $bnum;					
					if($falta == 0){
						colvaciasb('3', $pdf, $num);
						break;
					}elseif($falta == 2){
						colvaciasb('2', $pdf, $num);
						break;
					}elseif($falta == 1){
						colvaciasb('1', $pdf, $num);
						break;
					}
				}
				if ($k == $fin) {
					if (fmod($bnum, $multi) <> 0) {
						$mod = ($bnum-fmod($bnum, $multi))+$multi;
					}					
					$falta = $mod - $bnum;					
					if($falta == 0){
						colvaciasb('3', $pdf, $num);
						break;
					}elseif($falta == 2){
						colvaciasb('2', $pdf, $num);
						break;
					}elseif($falta == 1){
						colvaciasb('1', $pdf, $num);
						break;
					}
				}
				$k++;
			}			
		}
	}
}

//ESTRUCTURAS CORPORALES
function extraervals($s){
	$long = strlen($s);
	if($long == 1){
		$s11  = "";					$s12  = "";					$s13  = "";					$s14  = "";
	}elseif($long == 2){
		$s11  = substr($s, -1,1);	$s12  = "";					$s13  = "";					$s14  = "";
	}elseif($long == 3){
		$s11  = substr($s, -2,1);	$s12  = substr($s, -1,1);	$s13  = "";					$s14  = "";
	}elseif($long == 4){
		$s11  = substr($s, -3,1);	$s12  = substr($s, -2,1);	$s13  = substr($s, -1,1);	$s14  = "";
	}elseif($long == 5){
		$s11  = substr($s, -4,1);	$s12  = substr($s, -3,1);	$s13  = substr($s, -2,1);	$s14  = substr($s, -1,1);
	}
	
	return $arr = array($s11, $s12, $s13, $s14);
}

function imprimirvaloress($valores, $categorizadoruno, $categorizadordos, $categorizadortres, $pdf, $num, $si){	
	if($si == 1 || ($si % 4 === 0 && $si % 8 !== 0) || $si % 7 === 0 || $si % 10 === 0){
		if($si > 1){
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(3,14,'','LR',0,'L');
			$pdf->Cell(51,14, '','LR',0,'L',true);
			$pdf->SetFillColor(214,216,215);
		}
		$pdf->Cell(5,14,'s'.$num,'1',0,'C',true);
		$pdf->Cell(5,14,$valores[0],'1',0,'R');
		$pdf->Cell(5,14,$valores[1],'1',0,'C');
		$pdf->Cell(5,14,$valores[2],'1',0,'C');
		$pdf->Cell(5,14,$valores[3],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(3,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
		$pdf->Cell(4,14,$categorizadortres,'1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');	
	}elseif($si % 3 === 0){
		$pdf->Cell(5,14,'s'.$num,'1',0,'C',true);
		$pdf->Cell(5,14,$valores[0],'1',0,'C');
		$pdf->Cell(5,14,$valores[0],'1',0,'C');
		$pdf->Cell(5,14,$valores[0],'1',0,'C');
		$pdf->Cell(5,14,$valores[0],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(3,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
		$pdf->Cell(4,14,$categorizadortres,'1',0,'C');
		$pdf->Cell(3,14,'','LR',1,'L');
	}elseif($si % 2 === 0 || $si % 5 === 0){
		$pdf->Cell(5,14,'s'.$num,'1',0,'C',true);
		$pdf->Cell(5,14,$valores[0],'1',0,'C');
		$pdf->Cell(5,14,$valores[1],'1',0,'C');
		$pdf->Cell(5,14,$valores[2],'1',0,'C');
		$pdf->Cell(5,14,$valores[3],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(3,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
		$pdf->Cell(4,14,$categorizadortres,'1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
}

function colvaciass($col, $pdf, $num){	
	if($col == 3){
		$pdf->Cell(5,14,'s'.$num,'1',0,'C',true);
		$pdf->Cell(5,14,'','1',0,'R');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(3,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
	if($col == 3 || $col == 2){
		$pdf->Cell(5,14,'s'.$num,'1',0,'C',true);
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(3,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
	if($col == 3 || $col == 2 || $col == 1){
		$pdf->Cell(5,14,'s'.$num,'1',0,'C',true);
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->Cell(5,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(3,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(3,14,'','LR',1,'L');
	}	
}

function imprimirfcs($cif, $num, $snum, $pdf, $multi){	
	foreach($cif as $clave => $valor) {		
		if($clave == 's'){
			$si = 1;
			$fin = sizeof($valor);
			$k = 1;
			$mod = 0;
			$valor = ordenar($valor, 'grupo', SORT_DESC);
			foreach($valor as $claves => $valors) {
				$s = str_replace('s','',$valors->codigocif);
				$grupotxt 	= explode(' | ',$valors->grupotxt);
				$grupo 		= str_replace('s','',$grupotxt[0]);
				if($grupo == $num){
					$categorizadoruno = $valors->c1;
					$categorizadordos = $valors->c2;
					$categorizadortres = $valors->c3;
					$valores = extraervals($s);
					imprimirvaloress($valores, $categorizadoruno, $categorizadordos, $categorizadortres, $pdf, $num, $si);
					$snum++;
					$si++;
				}elseif($grupo == $num+1){
					if (fmod($snum, $multi) <> 0) {
						$mod = ($snum-fmod($snum, $multi))+$multi;
					}					
					$falta = $mod - $snum;
					if($falta == 0){
						colvaciass('3', $pdf, $num);
						break;
					}elseif($falta == 2){
						colvaciass('2', $pdf, $num);
						break;
					}elseif($falta == 1){
						colvaciass('1', $pdf, $num);
						break;
					}
				}
				if ($k == $fin) {
					if (fmod($snum, $multi) <> 0) {
						$mod = ($snum-fmod($snum, $multi))+$multi;
					}					
					$falta = $mod - $snum;
					if($falta == 0){
						colvaciass('3', $pdf, $num);
						break;
					}elseif($falta == 2){
						colvaciass('2', $pdf, $num);
						break;
					}elseif($falta == 1){
						colvaciass('1', $pdf, $num);
						break;
					}
				}
				$k++;
			}
		}
	}
}

//ACTIVIDAD Y PARTICIPACIÓN
function extraervald($d){
	$long = strlen($d);
	if($long == 1){
		$d11  = "";					$d12  = "";					$d13  = "";					$d14  = "";
	}elseif($long == 2){
		$d11  = substr($d, -1,1);	$d12  = "";					$d13  = "";					$d14  = "";
	}elseif($long == 3){
		$d11  = substr($d, -2,1);	$d12  = substr($d, -1,1);	$d13  = "";					$d14  = "";
	}elseif($long == 4){
		$d11  = substr($d, -3,1);	$d12  = substr($d, -2,1);	$d13  = substr($d, -1,1);	$d14  = "";
	}elseif($long == 5){
		$d11  = substr($d, -4,1);	$d12  = substr($d, -3,1);	$d13  = substr($d, -2,1);	$d14  = substr($d, -1,1);
	}
	
	return $arr = array($d11, $d12, $d13, $d14);
}

function imprimirvaloresd($valores, $categorizadoruno, $categorizadordos, $pdf, $num, $di){
	//print_r($valores).'-';
	if($di == 1 || ($di % 4 === 0 && $di % 8 !== 0) || $di % 7 === 0 || $di % 10 === 0){
		//echo $num;
		if($di > 1){
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(3,14,'','LR',0,'L');
			$pdf->Cell(51,14, '','LR',0,'L',true);
			$pdf->SetFillColor(214,216,215);
		}
		$pdf->Cell(5,14,'d'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,$valores[0],'1',0,'C');
		$pdf->Cell(6,14,$valores[1],'1',0,'C');
		$pdf->Cell(6,14,$valores[2],'1',0,'C');
		$pdf->Cell(6,14,$valores[3],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');	
	}elseif($di % 3 === 0){
		$pdf->Cell(5,14,'d'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,$valores[0],'1',0,'C');
		$pdf->Cell(6,14,$valores[1],'1',0,'C');
		$pdf->Cell(6,14,$valores[2],'1',0,'C');
		$pdf->Cell(6,14,$valores[3],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
		$pdf->Cell(3,14,'','LR',1,'L');
	}elseif($di % 2 === 0 || $di % 5 === 0){
		$pdf->Cell(5,14,'d'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,$valores[0],'1',0,'C');
		$pdf->Cell(6,14,$valores[1],'1',0,'C');
		$pdf->Cell(6,14,$valores[2],'1',0,'C');
		$pdf->Cell(6,14,$valores[3],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
}

function colvaciasd($col, $pdf, $num){	
	if($col == 3){
		$pdf->Cell(5,14,'d'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,'','1',0,'R');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
	if($col == 3 || $col == 2){
		$pdf->Cell(5,14,'d'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,'','1',0,'R');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
	if($col == 3 || $col == 2 || $col == 1){
		$pdf->Cell(5,14,'d'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(3,14,'','LR',1,'L');
	}
}

function imprimirfcd($cif, $num, $dnum, $pdf, $multi){
	//print_r($cif);
	//echo print_r($cif->d);
	$valor = $cif->d;
	$fin = sizeof($valor);
	$di = 1;
	$k = 1;
	/*
	foreach($cif as $clave => $valor) {
		if($clave == 'd'){
			$di = 1;
			$fin = sizeof($valor);			
			$k = 1;
			*/
			$valor = ordenar($valor, 'grupo', SORT_DESC);
			foreach($valor as $claved => $valord) {
				
				//echo $k.'<br>';
				//echo $valord->codigocif.'<br>';
				$d = str_replace('d','',$valord->codigocif);
				
				$grupotxt 	= explode(' | ',$valord->grupotxt);
				$grupo 		= str_replace('d','',$grupotxt[0]);
				
				//echo $grupo.'<br>';
				if($grupo == $num){
					//echo $grupo.' | '.$num.' | '.$d.' | '.$dnum.'<br>';
				}
				
				if($grupo == $num){
					$categorizadoruno = $valord->c1;
					$categorizadordos = $valord->c2;
					$valores = extraervald($d);
					if($num == 5){
						//echo $di.'<br>';
					}
					imprimirvaloresd($valores, $categorizadoruno, $categorizadordos, $pdf, $num, $di);
					$dnum++;
					$di++;					
				}/*elseif($grupo == $num+1){
					if (fmod($dnum, $multi) <> 0) {
						$mod = ($dnum-fmod($dnum, $multi))+$multi;
					}					
					$falta = $mod - $dnum;
					if($falta == 0){
						colvaciasd('3', $pdf, $num);
						break;
					}elseif($falta == 2){
						colvaciasd('2', $pdf, $num);
						break;
					}elseif($falta == 1){
						colvaciasd('1', $pdf, $num);
						break;
					}
				}
				if ($k == $fin) {
					if (fmod($dnum, $multi) <> 0) {
						$mod = ($dnum-fmod($dnum, $multi))+$multi;
					}					
					$falta = $mod - $dnum;
					if($falta == 0){
						colvaciasd('3', $pdf, $num);
						break;
					}elseif($falta == 2){
						colvaciasd('2', $pdf, $num);
						break;
					}elseif($falta == 1){
						colvaciasd('1', $pdf, $num);
						break;
					}
				}
				*/
				$k++;
			}
			//if($num == 5){
				if (fmod($dnum, $multi) <> 0) {
					$mod = ($dnum-fmod($dnum, $multi))+$multi;
					$falta = $mod - $dnum;
				}else{
					$falta = 0;
				}				
				
				//echo $falta.'<br>';
				//echo $dnum.'<br>';
				//echo $multi.'<br>';
				//echo fmod($dnum, $multi).'<br>';
				if($falta == 0){
					//echo $num;
					//colvaciasd('3', $pdf, $num);
					//break;
				}elseif($falta == 2){
					colvaciasd('2', $pdf, $num);
					//break;
				}elseif($falta == 1){
					colvaciasd('1', $pdf, $num);
					//break;
				}
			//}
			//break;
			/*
		}
	}
	*/
}

//FACTORES AMBIENTALES
function extraervale($e){
	$long = strlen($e);
	if($long == 1){
		$e11  = "";					$e12  = "";					$e13  = "";					$e14  = "";
	}elseif($long == 2){
		$e11  = substr($e, -1,1);	$e12  = "";					$e13  = "";					$e14  = "";
	}elseif($long == 3){
		$e11  = substr($e, -2,1);	$e12  = substr($e, -1,1);	$e13  = "";					$e14  = "";
	}elseif($long == 4){
		$e11  = substr($e, -3,1);	$e12  = substr($e, -2,1);	$e13  = substr($e, -1,1);	$e14  = "";
	}elseif($long == 5){
		$e11  = substr($e, -4,1);	$e12  = substr($e, -3,1);	$e13  = substr($e, -2,1);	$e14  = substr($e, -1,1);
	}
	
	return $arr = array($e11, $e12, $e13, $e14);
}

function imprimirvalorese($valores, $categorizadoruno, $categorizadordos, $pdf, $num, $ei){	
	if($ei == 1 || ($ei % 4 === 0 && $ei % 8 !== 0) || $ei % 7 === 0 || $ei % 10 === 0){
		if($ei > 1){
			$pdf->SetFillColor(178,179,183);
			$pdf->Cell(3,14,'','LR',0,'L');
			$pdf->Cell(51,14, '','LR',0,'L',true);
			$pdf->SetFillColor(214,216,215);
		}
		$pdf->Cell(5,14,'e'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,$valores[0],'1',0,'C');
		$pdf->Cell(6,14,$valores[1],'1',0,'C');
		$pdf->Cell(6,14,$valores[2],'1',0,'C');
		$pdf->Cell(6,14,$valores[3],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	
	}elseif($ei % 3 === 0){
		$pdf->Cell(5,14,'e'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,$valores[0],'1',0,'C');
		$pdf->Cell(6,14,$valores[1],'1',0,'C');
		$pdf->Cell(6,14,$valores[2],'1',0,'C');
		$pdf->Cell(6,14,$valores[3],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
		$pdf->Cell(3,14,'','LR',1,'L');
	}elseif($ei % 2 === 0 || $ei % 5 === 0){
		$pdf->Cell(5,14,'e'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,$valores[0],'1',0,'C');
		$pdf->Cell(6,14,$valores[1],'1',0,'C');
		$pdf->Cell(6,14,$valores[2],'1',0,'C');
		$pdf->Cell(6,14,$valores[3],'1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,$categorizadoruno,'1',0,'C');
		$pdf->Cell(4,14,$categorizadordos,'1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
}

function colvaciase($col, $pdf, $num){	
	if($col == 3){
		$pdf->Cell(5,14,'e'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,'','1',0,'R');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
	if($col == 3 || $col == 2){
		$pdf->Cell(5,14,'e'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,'','1',0,'R');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
			$pdf->Cell(8,14,'','LR',0,'C');
	}
	if($col == 3 || $col == 2 || $col == 1){
		$pdf->Cell(5,14,'e'.$num,'1',0,'C',true);
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->Cell(6,14,'','1',0,'C');
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(4,14,'.','LR',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(4,14,'','1',0,'C');
		$pdf->Cell(3,14,'','LR',1,'L');
	}
}

function imprimirfae($cif, $num, $enum, $pdf, $multi){
	foreach($cif as $clave => $valor) {
		if($clave == 'e'){
			$ei = 1;
			$fin = sizeof($valor);
			$k = 1;
			foreach($valor as $clavee => $valore) {
				$e = str_replace('e','',$valore->codigocif);
				$grupotxt 	= explode(' | ',$valore->grupotxt);
				$grupo 		= str_replace('e','',$grupotxt[0]);
				if($grupo == $num){
					$categorizadoruno = $valore->c1;
					$categorizadordos = $valore->c2;
					$valores = extraervale($e);
					imprimirvalorese($valores, $categorizadoruno, $categorizadordos, $pdf, $num, $ei);
					$enum++;
					$ei++;					
				}elseif($grupo == $num+1){
					if (fmod($enum, $multi) <> 0) {
						$mod = ($enum-fmod($enum, $multi))+$multi;
					}else{
						$mod = 0;
					}
					$falta = $mod - $enum;
					if($falta == 0){
						colvaciase('3', $pdf, $num);
						break;
					}elseif($falta == 2){
						colvaciase('2', $pdf, $num);
						break;
					}elseif($falta == 1){
						colvaciase('1', $pdf, $num);
						break;
					}
				}
				if ($k == $fin) {
					if (fmod($enum, $multi) <> 0) {
						$mod = ($enum-fmod($enum, $multi))+$multi;
					}else{
						$mod = 0;
					}
					$falta = $mod - $enum;
					if($falta == 0){
						colvaciase('3', $pdf, $num);
						break;
					}elseif($falta == 2){
						colvaciase('2', $pdf, $num);
						break;
					}elseif($falta == 1){
						colvaciase('1', $pdf, $num);
						break;
					}
				}
				$k++;
			}
		}
	}
}

?>