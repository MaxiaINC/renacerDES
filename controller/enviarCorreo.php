<?php 

	function enviarMensaje($asunto,$mensaje,$correos,$adjuntos) {
		global $mysqli, $mail;
		$cuerpo = '';
		$cuerpo .= $mensaje;
		$cuerpo .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;'>";
		$cuerpo .= "© ".date('Y')." Maxia Latam";
		$cuerpo .= "</div>";
		$mail->addAddress($correos);
		$mail->FromName = "Senadis";
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $asunto;
		//$mail->MsgHTML($cuerpo);
		$mail->Body = $cuerpo;
		$mail->AltBody = "Senadis: $asunto";
		if($adjuntos != ''){
			foreach($adjuntos as $adjunto){
			
				$mail->AddAttachment($adjunto);
			}
		}
		if(!$mail->send()) {
			echo 'Mensaje no pudo ser enviado. ';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo 'Ha sido enviado el correo Exitosamente';
			$adjuntos = (!empty($_REQUEST['adjuntos']) ? $_REQUEST['adjuntos'] : array());
			foreach($adjuntos as $adjunto){
				if(is_file($adjunto))
				unlink($adjunto); //elimino el fichero
			}
			//echo true;
		}
	}
?>