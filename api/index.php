<?php









use Firebase\JWT\JWT;

include("funcionesapi.php");
include("../controller/conexion.php");
require_once('../jwt/jwt.php');

$headers = apache_request_headers();

$token = getBearerToken();
//$token = json_decode($token);
//$token = str_replace('"\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n','',$token);
//echo "INDEX-->token: ".$token;
if($token){
	$expediente = validarToken($token);
	//echo "INDEX EXPEDIENTE:".$expediente."\n";
	if($expediente !== 'NO'){ 
		$validacion = obtenerValidacionDeDerecho($expediente);
		echo $validacion;
	} else {
		echo "No ve los datos";
	}
}else{
	echo "No hay token en cabecera";
} 



 
?>