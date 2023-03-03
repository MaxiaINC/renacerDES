<?php
	include_once("phpmailer\src\PHPMailer.php");
	include_once("phpmailer\src\SMTP.php");
	include_once("phpmailer\src\Exception.php");
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	$account = "toolkit@maxialatam.com";
	$password = "j8ASJkSs59nTmK2q";

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

	$from = "toolkit@maxialatam.com";
	$from_name = "Maxia Toolkit - BMC";
	
	function cabeceracorreo(){
		$cabecera.="<html lang='es'>
						<head>
							<meta charset='UTF-8'>
							<meta name='viewport' content='width=device-width, initial-scale=1.0'>
							<style>
								*{margin:0px;padding:0px;}
								p{font-size: 15px;width:100%;margin:0px !important;}
								.sombralogo{background-color:#eeeeee;padding:0px;margin-bottom:15px}
								.logo{width: initial;position: relative !important;padding: 10px;}
								p.center{text-align: center;padding:5px !important;font-size:20px;}
							</style>
						</head>
						<body>
					";
		return $cabecera;
	}
	/*
		<div class='sombralogo'>
			<img class='logo' src='https://toolkit.maxialatam.com/pcm/images/encabezado-maxia-c.png'>
		</div>
	*/

	function footercorreo(){
		$footer  = "";
		$footer .= "<div style='background:#eeeeee;padding:10px;text-align: center;font-size: 14px;font-weight: bold;margin-bottom: 50px;margin-top: 50px;'>";
		$footer .= "© ".date('Y')." Maxia Latam";
		$footer .= "</div>";
		$footer .= '		</body>
					</html>';
		return $footer;
	}
	
?>