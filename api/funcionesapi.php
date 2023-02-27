
<?php 

//use Firebase\JWT\JWT;
//require_once('../php-jwt-main/vendor/autoload.php');

require_once('../jwt/jwt.php');
require_once('constantes.php');

$oper = isset($_POST['oper'])?$_POST['oper']:"";
if($oper != ''){
	generarToken();
}
	
function generarToken(){
	$expediente = $_POST['expediente'];
	//echo "PASÓ GENERAR TOKEN<BR>";
	
	//echo "EXPEDIENTE ES: ".$expediente;
	$secretKey  = '123maxia';
	$tokenId    = base64_encode(random_bytes(16));
	$issuedAt   = new DateTimeImmutable();
	$expire     = $issuedAt->modify('+60 minutes')->getTimestamp();      // Tiempo de expiración
	//$serverName = "https://renacer.senadis.gob.pa/"; 
	$serverName = "https://toolkit.maxialatam.com/"; 

	// Create the token as an array
	$data = [
		'iat'  => $issuedAt->getTimestamp(),    // Issued at: time when the token was generated
		'jti'  => $tokenId,                     // Json Token Id: an unique identifier for the token
		'iss'  => $serverName,                  // Issuer
		'nbf'  => $issuedAt->getTimestamp(),    // Not before
		'exp'  => $expire,                      // Expire
		'data' => [                             // Data related to the signer user
			'expediente' => $expediente,  
		]
	];

	// Encode the array to a JWT string.
	$jwt = JWT::encode(
		$data,      //Data to be encoded in the JWT
		$secretKey, // The signing key
		'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
	); 
	//echo "TOKEN ES:".$jwt."<BR>";
	echo $jwt;
}

function getAuthorizationHeader(){
	$headers = null;
	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	}
	else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	} elseif (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		}
	}
	return $headers;
}
		
function getBearerToken() {
	$headers = getAuthorizationHeader();
	//$headers = apache_request_headers();
	//var_dump($headers);
	// HEADER: Get the access token from the header
	if (!empty($headers)) {
		if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
			//echo "FUNCIONES-EXPEDIENTE: ".$matches[1];
			return $matches[1];
			//echo "TOKEN ENCONTRADO";
		}
	}else{
		echo "TOKEN NO ENCONTRADO";
	}
	//$this->throwError( ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
} 

function validarToken($token) {
	//echo "\n".'validarToken: '.$token."\n";
	$secretKey  = '123maxia';
	
	$token = JWT::decode($token, $secretKey, ['HS512']);
	//print_r($token);
	$now = new DateTimeImmutable();
	//$serverName = "https://renacer.senadis.gob.pa/";
	$serverName = "https://toolkit.maxialatam.com/";
	if ($token->iss !== $serverName ||
		$token->nbf > $now->getTimestamp() ||
		$token->exp < $now->getTimestamp())
	{
		header('HTTP/1.1 401 Unauthorized');
		return "NO";
		//exit;
	}else{
		$expediente = $token->data->expediente;
		//echo "\n FUNCIONES - EXPEDIENTE: ".$expediente." \n";
		return $expediente;
	}	
} 

function obtenerValidacionDeDerecho($expediente){
	global $mysqli;
	//echo "EXPEDIENTE ES: ".$expediente;
	$sql = "SELECT 
				c.fechaemision, c.fechavencimiento, a.tipo_documento, a.fecha_vcto_cm, c.duracion, c.tipoduracion
			FROM
				pacientes a 
			INNER JOIN solicitudes b ON b.idpaciente = a.id 
			INNER JOIN evaluacion c ON c.idsolicitud = b.id 
			WHERE b.estatus IN (3,24,26) AND a.expediente = ".$expediente." ORDER BY b.id DESC LIMIT 1";
			//echo "SQL ES:".$sql."\n -";
	$rta = $mysqli->query($sql);
	if($row = $rta->fetch_assoc()){
		
		$tipodocumento = $row['tipo_documento'];
		$fechaemision = $row['fechaemision'];
		$fechavencimiento = $row['fechavencimiento'];
		$duracion = $row['duracion'];	//A => Año , M => Mes
		$tipoduracion = $row['tipoduracion']; //Entero
		$fechaactual = date('Y-m-d');
		
		if($tipodocumento == '2'){
			$fechavencimiento = $row['fecha_vcto_cm'];	
		}else{
			$fechavencimiento = $row['fechavencimiento'];
		}
			
		$arrfecha = explode('-',$fechavencimiento);
		
		$dia = $arrfecha[0];
		$mes = $arrfecha[1];
		$anio = $arrfecha[2];
		
		/* echo "dia ".$dia." \n";
		echo "mes ".$mes." \n";
		echo "anio ".$anio." \n"; */
		
		$esFecha = checkdate($mes, $dia, $anio);
		//echo "esFecha ".$esFecha." \n";
		//echo "fechavencimiento ".$fechavencimiento." \n";
		//Validación por fecha de vencimiento
		if($fechavencimiento !== '' && $esFecha == 1){
			//echo "PASÓ 1 \n";
			if (RangoFechas($fechaemision, $fechavencimiento, $fechaactual))
			{

				$respuesta = 1;

			} else {

				$respuesta = 0;

			}
		}else{
			//echo "PASÓ 2 \n";
			//Validación por duración
			if($tipoduracion == 'A'){ 
				$tiempo = diferenciaFechas($tipoduracion,$fechaemision,$fechaactual);
				//echo "TIEMPO-A: \n $tiempo";
				if($tiempo <= $duracion){
					$respuesta = 1;
				}else{
					$respuesta = 0;
				}
				
			}elseif($tipoduracion == 'M'){
				$tiempo = diferenciaFechas($tipoduracion,$fechaemision,$fechaactual);
				//echo "TIEMPO-B: \n $tiempo";
				if($tiempo <= $duracion){
					$respuesta = 1;
				}else{
					$respuesta = 0;
				}
			}else{
				$respuesta = 0;
			}
		}
	}
	return json_encode($respuesta);
}

function RangoFechas($fechainicio, $fechafin, $fecha){

	$fechainicio = strtotime($fechainicio);
	$fechafin = strtotime($fechafin);
	$fecha = strtotime($fecha);

	if(($fecha >= $fechainicio) && ($fecha <= $fechafin)) {

	 return true;

	} else {

	 return false;

	}
}

function diferenciaFechas($tipo,$fechainicio,$fechafin){
	/* echo "fechainicio $fechainicio \n";
	echo "fechafin $fechafin \n"; */
	$fechainicio = new DateTime($fechainicio);
	$fechafin = new DateTime($fechafin);
	$diferencia = $fechainicio->diff($fechafin);
	
	if($tipo == 'A'){
		$tiempo = $diferencia->y;
	}elseif($tipo == 'M'){
		$anios = ($diferencia->y)*12;
		$meses = $diferencia->m;
		$tiempo = $anios + $meses;
		/* echo "anios $anios \n";
		echo "meses $meses \n";
		echo "tiempo $tiempo \n"; */
	}else{
		echo "Error en el tipo \n";
	}
	return $tiempo;
}

function getDatosValidacion($expediente){
	
	global $mysqli;
	
	$sql = " SELECT a.nombre, a.apellidopaterno, a.apellidomaterno, c.fechaemision, c.fechavencimiento, d.nro_resolucion
			 FROM pacientes a 
			 INNER JOIN solicitudes b ON b.idpaciente = a.id 
			 INNER JOIN evaluacion c ON c.idsolicitud = b.id 
			 LEFT JOIN resolucion d ON d.idsolicitud = b.id
			 WHERE expediente = ".$expediente." ORDER BY b.id DESC LIMIT 1";
	//echo "SQL: ".$sql;
	$rta = $mysqli->query($sql);
	if($row = $rta->fetch_assoc()){
		
		$nombrecompleto = $row['nombre'].' '.$row['apellidopaterno'].' '.$row['apellidomaterno'];
		$desde = $row['fechaemision'];
		$hasta = $row['fechavencimiento']; 
		$nro_resolucion = $row['nro_resolucion'];
		
	}
	$resultado = array('nombrecompleto' => $nombrecompleto, 'desde' => $desde, 'hasta' => $hasta, 'nro_resolucion' => $nro_resolucion);
	return $resultado;
}

?>