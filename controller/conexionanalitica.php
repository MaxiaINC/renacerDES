<?php
	//CONEXION DASHBOARD
	$mysqli = new mysqli("127.0.0.1", "root", "M4X14W3B", "senadisanalitica");
	if ($mysqli->connect_error) {
		echo "Fallo al conectar a MySQL: (" . $mysqli->connect_error . ") " . $mysqli->connect_error;
	}
	$mysqli->set_charset("utf8");
	$lifetime=60*60*24*30;
    session_start();
	setcookie(session_name(),session_id(),time()+$lifetime);
    
	date_default_timezone_set("America/Panama");
    include("funcionesbitacora.php");
	
	function debug($txt) {
		$f = fopen("debug.txt", "w"); 
		fwrite($f, $txt);
		fclose($f);
	}
	function debugL($txt,$fileName='debugL') {
		$fileName.='.txt';
		$f = fopen($fileName, "a"); 
		fwrite($f, $txt.PHP_EOL);
		fclose($f);
	}
    function debugJ($entrada,$fileName='debugJ') {
		$json_string = json_encode($entrada);
		$file = $fileName.='.json'; 
		file_put_contents($file, $json_string); 
	}
?>