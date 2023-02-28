<?php
	include_once("controller/conexion.php");
	global $mysqli;
	//include_once("controller/funciones.php");
	$sistema = 'senadis';
	$id 	 = $_SESSION['user_id_sen'];	
	$token   = $_REQUEST['token_sen'];		
	//$mysqli->query("INSERT INTO bitacora values(null, '$usuario', now(), 'LOGIN', 'Cierre de sesión', $id, '$token')");
	
	//debugL("USERID: ".$id." -CERRAR SESION ".$token,"RASTREO-USUARIO ".$_SESSION['usuario_sen']);
	
	//DIRECTORIO
	if (file_exists(__DIR__.'\controller\inSession\\'.$token.'.json')){
		unlink(__DIR__.'\controller\inSession\\'.$token.'.json');
	}
	
	//BD
	$sentencia = $mysqli->prepare("	DELETE FROM user_token WHERE token = ? ");
	$sentencia->bind_param("s", $token );
	//$sentencia->execute();
	if ($sentencia->execute()) { 
	   //debugL("EXITO - DELETE FROM user_token WHERE token =".$token,"RASTREO-USUARIO ".$_SESSION['usuario_sen']);
	} else {
	   //debugL("FALLO - DELETE FROM user_token WHERE token =".$token,"RASTREO-USUARIO ".$_SESSION['usuario_sen']);
	}
	
	setcookie("user_id_sen", "", time()-1);
	setcookie("usuario_sen", "", time()-1);
	setcookie("nombreUsu_sen", "", time()-1);
	setcookie("nivel_sen", "", time()-1);
	setcookie("sistema_sen", "", time()-1);
	//COOKIES
	/* $arr_cookie_expires = array (
		'expires' => time() - 1,
		'path' => '/'.$sistema,
		'domain' => 'toolkit.maxialatam.com', // leading dot for compatibility or use subdomain
		'secure' => true,     // or false
		'httponly' => true,    // or false
		'samesite' => 'None' // None || Lax  || Strict
	);
	setcookie('user_id', '', $arr_cookie_expires);
	setcookie('usuario', '', $arr_cookie_expires);
	setcookie('nombreUsu', '', $arr_cookie_expires);
	setcookie('nivel', '', $arr_cookie_expires);
	setcookie('sistema', '', $arr_cookie_expires); */
	//SESSION
	session_unset();
	session_destroy();
	header('Location: index.php');
	
?>