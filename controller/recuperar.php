<?php
	include_once("conexion.php");
    include_once("..\phpmailer\src\PHPMailer.php");
    include_once("..\phpmailer\src\SMTP.php");
    include_once("..\phpmailer\src\Exception.php");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
	$account="toolkit@maxialatam.com";
	$password="j8ASJkSs59nTmK2q";
    
	if (!isset($mail)) {
		$mail = new PHPMailer();
		//$mail->SMTPDebug = 3;
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';
		$mail->Host = "smtp.office365.com";
		$mail->SMTPAuth= true;
		$mail->Port = 587;
		$mail->From = "toolkit@maxialatam.com";
		$mail->setFrom('toolkit@maxialatam.com', 'Maxia - Toolkit');
	    $mail->addReplyTo('soporte@maxialatam.com', 'Maxia - Toolkit');
		$mail->Username= $account;
		$mail->Password= $password;
		$mail->SMTPSecure = 'tls';
	}
	include_once("enviarCorreo.php");

	
    if(!empty($_POST)){
		$correo   = (!empty($_REQUEST['correo']) ? $_REQUEST['correo'] : '');
        
		$longitud = 6;
        $password = "";
		
		if(isset($_POST['clave'])){
    		$correo   = $_SESSION['correo'];
    		$password   = $_POST['clave'];
		}

		if ($correo != "") {
			$sentencia = $mysqli->prepare("	SELECT u.id,u.usuario,u.correo, u.nombre, u.clave
			                                FROM usuarios u
			                                WHERE u.correo = ?  ");
            
			$sentencia->bind_param("s", $correo);
			$sentencia->execute();
			$resultado = $sentencia->get_result();
			if ($registro = $resultado->fetch_assoc()) {
				$clave = $registro['clave'];
				$enviado = RecuperarClave($registro['nombre'],$registro['usuario'],$clave,$registro['correo']);
				echo json_encode(array(
					'error' => false,
					'msg'   => "Contraseña recuperada exitosamente!"
				));
				exit;
			} else {
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Usuario incorrecto!"
                ));
                exit;
			}
		} else {
			echo json_encode(array(
                'error' => true,
                'msg'   => "Debe llenar todos los campos!",
                'pwd'  	=> $password,
                'correo'=> $correo
            ));
            exit;
		}
	}else{
		echo "VACIOS";
	}


//////////////////////////////////////////////////////////////////////////////////

	function RecuperarClave($nombre,$usuario, $clave, $correo){
		global $mysqli, $mail;

		$fecha = date("Y-m-d");
		$asunto = "Recuperar contraseña Senadis ";
		
		//Cuerpo
		$fecha = implode('/',array_reverse(explode('-', $fecha)));

		$cuerpo = '';		

		$cuerpo .= "<div style='background:#eeeeee; padding: 5px 0 5px 10px; display: flex; '>";
		$cuerpo .= "<img src='https://toolkit.maxialatam.com/senadis/images/favicon.png' style='width: initial;height: 60px;float: left; position: absolute !important;'>";
		$cuerpo .= "<p style='margin:auto; font-weight:bold; width: 100%; text-align: center;'>Senadis<br>";
		$cuerpo .= "Recuperar contraseña<br>";
		$cuerpo .= "</div>";

        $user_name = strtoupper($nombre);

		$cuerpo .= "<div style='width: 100%; text-align: right;'><b>Fecha:</b> ".$fecha."&nbsp;&nbsp;&nbsp;</div>";
		$cuerpo .= "<p style='font-size: 18px;width:100%;'>Hola <b>".$user_name."</b>, </p>";
		$cuerpo .= "<b>Los datos de acceso son los siguientes: </b><br>  
					Usuario: ".$usuario."<br>
					Clave: ".$clave."<br>
					<p>";
		$cuerpo .= "<br><br>"; 
//		$cuerpo .= footercorreo();
		$cuerpo .="<p style='font-size: 15px;width:100%; '>Un saludo,</p>";
		$cuerpo .="<p style='font-size: 15px;width:100%; '>El equipo de <b>Senadis</b>.</p>";
		$cuerpo .= " <br><p style='font-size: 15px;width:100%; margin:0px !important;text-align: center;padding: 15px;'>
						<a href='https://toolkit.maxialatam.com/senadis/index.php' target='_blank' style='background-color: #1E3D7A;color: #FFFFFF;padding: 10px 20px;border-radius: 4px;text-decoration: none;'>Más en Senadis</a>
					</p><br>";


		enviarMensaje($asunto,$cuerpo,$correo,'');
	}


	
?>