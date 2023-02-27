<?php
	//$url = "https://renacer.senadis.gob.pa/senadisqa/api/funcionesapi.php"; // Definimos la URL
	$url = "https://toolkit.maxialatam.com/senadisdes/api/funcionesapi.php"; // Definimos la URL

	$expediente = base64_decode($_REQUEST['id']);
	$request = array("oper" => "generarToken", "expediente" => $expediente);
	
	$ch = curl_init(); // Inicializamos CURL
	curl_setopt($ch,CURLOPT_URL,$url); // Enviamos la peticion GET de la URL
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
	//curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_POSTFIELDS, $request); // Enviamos los datos via POST
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
	//curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem"); //ACTIVAR EN SERVIDOR SENADIS
	$result = curl_exec($ch); // Ejecutamos la peticion GET
	$err = curl_error($ch);
	curl_close($ch);  // Cerramos CURL
	if($err) {
		echo 'Curl Error: ' . $err;
	} else {
		//$data = json_encode($result); // Decodificamos los datos jSON a un Objeto Std
		//print_r($result);// Mostramos los resultados
		//echo $result;
		$bearer = trim($result);
		//echo $bearer;
		
		//NUEVO CURL
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  //CURLOPT_URL => "https://renacer.senadis.gob.pa/senadisqa/api",
		  CURLOPT_URL => "https://toolkit.maxialatam.com/senadisdes/api",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  //CURLOPT_CAINFO => dirname(__FILE__)."/cacert.pem", //ACTIVAR EN SERVIDOR SENADIS
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer $bearer",
				"Content-type: application/json",
			  ),
		));
		$data = curl_exec($curl); 		
		//echo "data es $data \n";
		curl_close($curl);   // Cerramos CURL
	 	if($data == 1){
			header("Status: 301 Moved Permanently");
			
			//header("Location: https://renacer.senadis.gob.pa/senadisqa/api/validar.php?id=".$bearer);
			header("Location: https://toolkit.maxialatam.com/senadisdes/api/validar.php?id=".$bearer);
			exit;  

		}else{
			
			header("Status: 301 Moved Permanently");
			//header("Location: https://renacer.senadis.gob.pa/senadisqa/api/validar.php?id=0");
			header("Location: https://toolkit.maxialatam.com/senadisdes/api/validar.php?id=0");
			exit;  
		}       
	}
?>