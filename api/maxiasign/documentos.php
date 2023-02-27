<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header('content-type: application/json; charset=utf-8');
include("../../controller/conexion.php");
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);

	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	
	require 'vendor/autoload.php';

	$app = new \Slim\App;
	
	$app->post('/buscardocumento', function (Request $request, Response $response) {
        global $mysqli;
        $resultado = $request->getParsedBody();
		$iddocumento = $resultado['iddocumento'];
		
		$query = $mysqli->prepare("SELECT * FROM documentosfirmados WHERE iddocumento = ? ");
		$query->bind_param("i", $iddocumento);	
		$query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $documento = $row["documento"];
	   if($query->execute()){
            echo $documento;
        }else{
            echo 'Documento no encontrado';
        }

        $query->close(); 
		
	});
	
	$app->post('/enviardocumento', function (Request $request, Response $response) {
        global $mysqli;
        
		$resultado = $request->getParsedBody();
		$iddocumento = $resultado['iddocumento'];
		$documento = $resultado['documento'];
		$version = '2';
		$firmado = '1';

		
		$query_firmante = $mysqli->prepare("SELECT * FROM documentosfirmados WHERE iddocumento = ? ");
		$query_firmante->bind_param("i", $iddocumento);	
		$query_firmante->execute();
        $result = $query_firmante->get_result();
        $row = $result->fetch_assoc();
        $firmante = $row["firmante"];
        
		$query = $mysqli->prepare("	INSERT INTO `documentosfirmados`(`iddocumento`, `documento`, `version`, `firmado`, `firmante`) VALUES (?,?,?,?,?) ");
		$query->bind_param("sssss", $iddocumento, $documento,$version,$firmado,$firmante);
        if($query->execute()){
            echo 'Documento firmado enviado con éxito.';
        }else{
            echo 'Error al enviar el documento firmado.';
        }

        $query->close(); 

	});
	
	$app->run();