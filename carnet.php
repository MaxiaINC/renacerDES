<?php

	include_once("controller/conexion.php");
	include_once("controller/funciones.php");
	
	$id = $_REQUEST['id'];
	$dup = $_REQUEST['dup'];
	$usuario = $_SESSION['usuario_sen'];
	
	global $mysqli;
	
	$getRegUsuario = "SELECT regional FROM usuarios WHERE usuario = '".$usuario."'";
	$rtaGetRe = $mysqli->query($getRegUsuario);		
	if($rowReg = $rtaGetRe->fetch_assoc()){
		$regionalUsu = $rowReg['regional'];
	}
	
	$sql = "SELECT a.id AS idpaciente, a.tipo_documento, a.fecha_vcto_cm, CONCAT(a.nombre,' ',a.apellidopaterno,' ',a.apellidomaterno) AS nombrecompleto, a.cedula, 
			a.fecha_nac, d.nombre AS nacionalidad, c.fechaemision, c.fechavencimiento 
			FROM pacientes a 
			INNER JOIN solicitudes b ON b.idpaciente = a.id 
			INNER JOIN evaluacion c ON c.idsolicitud = b.id 
			LEFT JOIN nacionalidades d ON d.id = a.nacionalidad 
			WHERE a.id = ".$id;
	$rta = $mysqli->query($sql);		
	if($row = $rta->fetch_assoc()){
		
		$nombrecompleto = $row['nombrecompleto'];
		$cedula = $row['cedula'];
		$fecha_nac = formatFechaString($row['fecha_nac']);
		$nacionalidad = $row['nacionalidad'];
		$desde = formatFechaString($row['fechaemision']);
		$hasta = formatFechaString($row['fechavencimiento']); 
		$ruta = 'images/beneficiarios/'.$id.'/';
		$imagen = fotoPaciente($id,$ruta);
	}
	
	$src_foto = "images/beneficiarios/".$id."/".$imagen."?".time();
	$src_qr   = "images/beneficiarios/".$id."/qr/".$id."qr.png?".time();
	
	//Buscar firmas para el carnet
	$sqlF = "SELECT a.id, a.cargo FROM firmas a 
			INNER JOIN usuarios b ON b.id = a.idusuarios 
			WHERE b.regional = '".$regionalUsu."' AND a.estado = 'Activo'";
	$rtaF = $mysqli->query($sqlF);	
	$totalF = $rtaF->num_rows; 
	
	if($totalF== 2){
		$firmas = 1;
		$arrayFirmas = array();
		
		while($rowF = $rtaF->fetch_assoc()){
			
			$cargo = $rowF['cargo'];
			$idfirma = $rowF['id'];
			
			if (strpos($cargo, "Director(a) General") !== false) {
				$cargoDirGen = $cargo;
				$idDirGen = $idfirma;
				$srcfirmGen = "images/firmas/".$idDirGen."/".$idDirGen.".png?".time();
			}
			if (strpos($cargo, "Nal") !== false) {
				$cargoDirNac = $cargo;
				$idDirNac = $idfirma;
				$srcfirmNac = "images/firmas/".$idDirNac."/".$idDirNac.".png?".time();
			} 
		}
	}else{
		//Si no hay firmas de la regional del usuario, buscar firmas de 'Todos'
			
		$sqlFirmTodos = "SELECT a.id, a.cargo FROM firmas a 
			INNER JOIN usuarios b ON b.id = a.idusuarios 
			WHERE b.regional = 'Todos' AND a.estado = 'Activo'";
			
		$rtaFirmTodos = $mysqli->query($sqlFirmTodos);	
		$totalFirmTodos = $rtaFirmTodos->num_rows; 	
		if($totalFirmTodos== 2){
			$firmas = 1;
			$arrayFirmas = array();
			
			while($rowF = $rtaFirmTodos->fetch_assoc()){
				
				$cargo = $rowF['cargo'];
				$idfirma = $rowF['id'];
				
				if (strpos($cargo, "Director(a) General") !== false) {
					$cargoDirGen = $cargo;
					$idDirGen = $idfirma;
					$srcfirmGen = "images/firmas/".$idDirGen."/".$idDirGen.".png?".time();
				}
				if (strpos($cargo, "Nal") !== false) {
					$cargoDirNac = $cargo;
					$idDirNac = $idfirma;
					$srcfirmNac = "images/firmas/".$idDirNac."/".$idDirNac.".png?".time();
				}
				
			}
		}
	} 
	
	//Verificar Duplicado
	if($dup == 1) {
		$txt_duplicado = "DUPLICADO";
	}else{
		$txt_duplicado = "&nbsp";
	}
	
	strpos($cargoDirGen, "Encargado") !== false ? $position = 'fixed' : $position = 'relative';
	strpos($cargoDirGen, "Encargado") !== false ? $top = '14em' : $top = '0';
	
?>	

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Carnet - Senadis</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<!-- Style -->
	<link href="./css/style.css" rel="stylesheet">
	<link href="./css/ajustes.css" rel="stylesheet">
	<style>
		@media print {
		   @page { 
			 margin: 0; 
		   }
		}
		.imgfoto_beneficiario{
			width: 100%;
		}
		.enc-carnet-imp{
			font-size: 2.1rem !important;
		}
		.enc-carnet2-imp{
			font-size: 2rem !important;
		}
		.cancel-button{
			display:none;
		}
	</style>

</head>	
<body style="background:#FFF; position: absolute;">
	<div style="transform: rotate(270deg); top: 40em; position: relative; width: 60em; left: -30em;">
		<div class="row m-0 pt-3"     style="width: 165%; left: inherit; position: fixed; zoom: 2;">
			<div class="col-md-2 p-4 text-center" >
				<img src="images/senadis2.png" class="" width="200" style="top: 3%; position: relative; width: 220%;">
			</div>
			<div class="col-md-10 p-0" style="right: -7%;">
				<div class="font-w900 d-block enc-carnet-imp text-center pr-5 mt-4 color-negro">REPÚBLICA DE PANAMÁ</div>
				<div class="font-w700 text-info d-block enc-carnet-imp text-center pr-5" style="color: #004080 !important;">CERTIFICADO DE DISCAPACIDAD</div>
			</div>
			<div class="col-md-12 px-2" style="top: -3em;">
				<img src="images/iconos-carnet.png" width="260" class="iconos-certificado">
				<div class="font-w800 fs-14 pb-1 color-negro" style="padding-left: 2%;top: 2em;position: relative;">Secretaria Nacional de Discapacidad</div>
			</div> 
			<div class="col-md-12 p-0 mb-4">
				<div class="font-w700 pl-4 txt_nombre color-negro" style="top: -5%;position: relative;left: -2%;font-size: 29px !important;"><?php echo mb_strtoupper($nombrecompleto); ?></div>
			</div> 
			<div class="col-md-4 p-0 pl-3" >
				<img class="d-inline border-radius imgfoto_beneficiario" src="<?php echo $src_foto; ?>" style=" margin-top: 2rem; top: -8%; position: relative; height: 103%;left: -8%;">
			</div>
			<div class="col-md-5 mt-1 pr-0 pt-4" style="padding-left: 0%;left: -1%; ">
				<div class="font-w900 d-block mb-4 fs-26 color-negro">C.I.P. &nbsp;&nbsp;&nbsp;<span class="txt_cedula"><?php echo $cedula; ?></span></div>
				<div class="font-w900 d-block fs-26 color-negro">NACIONALIDAD</div>
				<div class="font-w900 text-uppercase d-block mb-5 txt_nacionalidad fs-26 color-negro"><?php echo $nacionalidad; ?>
				</div>
				<div class="font-w900 d-block fs-26 color-negro">FECHA DE NACIMIENTO</div>
				<div class="font-w900 text-uppercase d-block txt_fechanacimiento fs-26 color-negro"><?php echo $fecha_nac; ?></div>
			</div>
			<div class="col-md-3 mt-4 pr-0 text-center" style=" font-size: 22px;">
				<div class="font-w900 d-block mb-5 text-white">.</div>
				<div class="font-w900 d-block fs-26 color-negro">EXPEDICIÓN</div>
				<div class="font-w900 text-uppercase mb-3 pb-2 txt_expedicion fs-26 color-negro" style="width: max-content;"><?php echo $desde; ?></div>
				<div class="font-w900 d-block fs-26" style="color: #ff4541">EXPIRACIÓN</div>
				<div class="font-w900 text-uppercase txt_expiracion fs-26 color-negro" style="width: max-content;"><?php echo $hasta; ?>
				</div>
			</div>
		</div>
	</div> 
	<div class="certificado dv_carnet" style="transform: rotate(270deg);top: 115em;position: relative;height: 75%;">
		<div class="row m-0 pt-3">
			<div class="col-md-12 text-center">
				<img src="images/senadis2.png" alt="" class="" width="100" style="width: 34%; top: 6em; position: relative;">
			</div>
			<div class="col-md-12 text-right" >
				<div class="font-w800" style="font-size:53px !important;left: 10%;position: relative;top: -3em;"><?php echo $txt_duplicado; ?></div>
			</div>
			<div class="col-md-12 text-center"> 
				<div class="font-w800 pt-3 fs-16 color-negro" style="font-size:46px !important;">Secretaria Nacional de Discapacidad</div>
			</div>
			<div class="col-md-8 mt-4 text-center" style="left: -10%;">  
				<!--<div class="font-w600 fs-24 d-block mt-4 pt-2 text-center mb-4"  style="font-size:42px !important;top: 30%;position: relative;">Director(a) General</div>-->
				<img class="imgfirma_directorgeneral" width="300" src="<?php echo $srcfirmGen; ?>" style="position: relative;top: 4%;width: 40em;">
				<div class="font-w800 fs-24 d-block mt-2 text-center mb-4 color-negro"  style="font-size:46px !important; position: <?php echo $position; ?>; top:<?php echo $top; ?>"><?php echo $cargoDirGen; ?></div>
				<!--<div class="font-w600 fs-24 d-block pt-4 text-center" style="font-size:41px !important; left: -11%;top: 65%; position: relative;">Dir. Nacional de Certificaciones a.i.</div>-->
				<img class="imgfirma_directorgeneral" width="300" src="<?php echo $srcfirmNac; ?>" style="position: relative; top: 2%; width: 40em;">
				<div class="font-w800 fs-24 d-block text-center color-negro" style="font-size:46px !important; width: 20em;left: -13%; position: relative;top: -2%;"><?php echo $cargoDirNac?></div>
			</div>
			<div class="col-md-4 pr-0 pt-2 text-right">
				<img class="imgqr_beneficiario" width="140" src="<?php echo $src_qr; ?>" style="width: 150%;height: 100%; position: relative; top: 12%;left: 10%;">
			</div>
			<div class="col-md-12 text-center" >
				<div class="font-w800 fs-24 color-negro" style="font-size:46px !important; left: -5%;position: relative;top: 90%;">www.senadis.gob.pa</div>
			</div>
		</div>
	</div>
	<?php linksfooter(); ?>
	<script>
		 window.onload = function () {
			  window.print();
			  setTimeout(function(){window.close();}, 1);
		 } 
	</script>
</body>
</html>