<?php
	include_once("../phpmailer/src/PHPMailer.php");
	include_once("../phpmailer/src/SMTP.php");
	include_once("../phpmailer/src/Exception.php");
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
?>