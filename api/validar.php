<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Validación de derecho</title>
	<link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<link href=".././css/style.css" rel="stylesheet">		
	<link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href=".././css/ajustes.css" rel="stylesheet">
</head>

<body>
	<div id="overlay" style="display: none;">
		<div class="sk-three-bounce">
			<div class="sk-child sk-bounce1"></div>
			<div class="sk-child sk-bounce2"></div>
			<div class="sk-child sk-bounce3"></div>
		</div>
	</div>
	<div class="header pl-0">
	<div class="header-content">
		<nav class="navbar navbar-expand">
			<div class="collapse navbar-collapse justify-content-between">
				<div class="header-left">
					<div class="dashboard_bar">
						Validación de derecho
					</div>
				</div>

				<ul class="navbar-nav header-right">
				   <li class="nav-item dropdown header-profile">
						<a class="nav-link" href="javascript:;" role="button" data-toggle="dropdown">
							<div class="round-header"></div>
							<div class="header-info">
								<span></span>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
<?php
	
//use Firebase\JWT\JWT;
//require_once('../php-jwt-main/vendor/autoload.php');
require_once('../jwt/jwt.php');
$token = $_REQUEST['id'];
$type = gettype($token);
/* echo "TOKEN ES".$token; */
$secretKey  = '123maxia';
if($token == '0'){
		
?>
<div class="content-body ml-0 mt-3">
	<div class="container-fluid pt-0">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-body text-center">
						<img class="logo-abbr mb-1" src="https://renacer.senadis.gob.pa/images/senadis.png" width="160">
						<div class="col-sm-12 mb-2 text-body font-w500">
							Validación de derecho
						</div>
						<div class="alert alert-danger fs-20" role="alert">
						  El beneficiario no posee una certificación activa
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<?php
		
}else{
	try { 
		$token = JWT::decode($token, $secretKey, ['HS512']);
		// echo "<BR>AUN TOKEN";
		// $issuedAt   = new DateTimeImmutable();
		// $expira = $token->exp;
		// $inicio = $token->iat;
		// $actual = $issuedAt->getTimestamp();
		// echo "<br>expiró: ".$expira;
		// echo "<br>inicio: ".$inicio;
		// echo "<br>actual: ".$actual;
		$expediente = $token->data->expediente;
		include("../controller/conexion.php");
		include("funcionesapi.php");
		
		$datosvalidacion = getDatosValidacion($expediente);
		$nombrecompleto = $datosvalidacion['nombrecompleto'];
		$desde = implode('/',explode('-',$datosvalidacion['desde']));
		$hasta = implode('/',explode('-',$datosvalidacion['hasta']));
		$nro_resolucion = implode('/',explode('-',$datosvalidacion['nro_resolucion']));
		//var_dump($datosvalidacion);
?>		
<div class="content-body ml-0 mt-3">
	<div class="container-fluid pt-0">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-body text-center">
						<img class="logo-abbr mb-1" src="https://renacer.senadis.gob.pa/images/senadis.png" width="160">
						<div class="col-sm-12 mb-2 text-body font-w500">
							Validación de derecho
						</div>
						<div class="alert alert-success text-body" role="alert">
							El beneficiario <br>
							<div class="fs-22 font-w500 text-info mb-2"><?php echo $nombrecompleto ?></div> <br>
							Está certificado desde la fecha:<br>
							<span class="fs-16 font-w500 text-info"><?php echo $desde ?></span> hasta el <span class="fs-16 font-w500 text-info"><?php echo $hasta ?></span><br>
							Resolución número: <span class="fs-16 font-w500 text-info"><?php echo $nro_resolucion ?></span>
						</div> 
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
				
<?php
	} 
	catch (\Exception $e) {  
		
?>

<div class="content-body ml-0 mt-3">
	<div class="container-fluid pt-0">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-body text-center">
						<div class="row">
							<div id="content" class="col-lg-12">								
								<div class="form-group col-12 col-sm-6 col-md-12">
									<img class="logo-abbr mb-1" src="https://renacer.senadis.gob.pa/images/senadis.png" width="160">
									<div class="col-sm-12 mb-2 text-body font-w500">
										Validación de derecho
									</div>
									<div class="alert alert-danger fs-20" role="alert">
									  <span class="font-w500">NO</span> tiene permisos para ver esta información
									</div>
									<div>Debe escanear nuevamente el código QR para acceder a la información</div>
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
				
<?php
	}
}
?>
</body>
</html>

