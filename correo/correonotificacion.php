<?php
	
	function notificacion($estado,$nameProyect,$nameClient,$dropbox){
		include_once("configuracioncorreo.php");
		$asunto =intval($estado)==9?"Vobo | ".$nameClient." | aprobado ":"Vobo | ".$nameClient." |".$nameProyect;
		echo $asunto;
		$cuerpo .= cabeceracorreo();
        if(intval($estado)==9){
            //echo $estado;
            //revisado
            $cuerpo .= "<div>";
	        $cuerpo .= "<p>La propuesta para el $nameProyect del cliente $nameClient ha sido revisada y firmado el control de diseño.</p>";
	        $cuerpo .= "<div style='text-align: center;margin-top: 25px;margin-bottom: 15px;height: 35px;'>";
	        $cuerpo .= "</div>";
        }else{
            //echo $estado;
            //pediente revision
            $cuerpo .= "<div>";
        	$cuerpo .= "<p>La propuesta para el $nameProyect para $nameClient está lista para su revisión y firma del Control de Diseño.</p>";
        	$cuerpo .= "<div style='text-align: center;margin-top: 25px;margin-bottom: 15px;height: 35px;'>";
        	$cuerpo .= "<div style='text-align: center;margin-top: 25px;margin-bottom: 15px;height: 35px;'>";
        	$cuerpo .="<div>$dropbox</div>";
        	$cuerpo .= "</div>";
        }
		$cuerpo .= footercorreo();
		
		$mail->From = $from;
		$mail->FromName= $from_name;
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $cuerpo;
		
		$mail->addAddress("christopher_carnevale@outlook.com", "christopher");
		//$mail->addAddress("Lismary.18@gmail.com", "lismary");
		$mail->addAddress("Yamirelis.yepez@maxialatam.com","Yamirelis");
		$mail->addAddress("soluciones@maxialatam.com","Maxias");
		//$mail->addAddress($correo, $nombre);
		if(!$mail->send()){
			echo "Error!";
		}else{
			echo "Bien!";
		}
	}
    
?>