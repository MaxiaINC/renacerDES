<?php
	include_once("conexion.php");
	include_once("funciones.php");
	global $sitio_actual, $domain_actual;
	
    if(!empty($_POST)){
		//echo ".";
		$usuario= $_REQUEST['txtUsuario'];
		$clave 	= $_REQUEST['txtClave'];
		$tokenls= $_REQUEST['tokenls'];
		$sistema= $_REQUEST['sistema'];
		// Hash password
		$hashed_pass = hash('sha256', stripslashes($clave) );
		if ($usuario != "" && $clave != "") {
			//$sistema = 'senadis';
			$sentencia = $mysqli->prepare("	SELECT id, usuario, clave, nombre, nivel 
											FROM usuarios 
											WHERE usuario = ? AND clave = ? AND estado = 'Activo' ");
			$sentencia->bind_param("ss", $usuario, $hashed_pass );
			$sentencia->execute();
			$resultado = $sentencia->get_result();
			if ($registro = $resultado->fetch_assoc()) {
				$id		= $registro['id'];
				$usuario= $registro['usuario'];
				$clave	= $registro['clave'];
				$nombre	= $registro['nombre'];
				$nivel	= $registro['nivel'];
				$token 	= '';
				// Update user token 
				$sentencia = $mysqli->prepare("	SELECT count(*) as allcount FROM user_token WHERE username = ? ");
				//debugL("1. SELECT count(*) as allcount FROM user_token WHERE username = '".$usuario."' ");
				//debugL("SELECT count(*) as allcount FROM user_token WHERE username = '".$usuario."' ","RASTREO-USUARIO ".$usuario);
    			$sentencia->bind_param("s", $usuario );
    			$sentencia->execute();
    			$resultado = $sentencia->get_result();
				if ($result_token = $resultado->fetch_assoc()) {
				    //$token = getToken(10);
				    //$_SESSION['token'] = $token;
					//debugL('$result_token: '.$result_token['allcount']);
				    if($result_token['allcount'] > 0){
						$tokenBD = getId('token','user_token',$usuario,'username');
						//debugL("TOKENBD = ".$tokenBD."","RASTREO-USUARIO ".$usuario);
						//debugL("TOKENls = ".$tokenls."","RASTREO-USUARIO ".$usuario);
						if ( $tokenBD == $tokenls && is_file( __DIR__.'\inSession\\'.$tokenls.'.json')) {
							//CREAR SESIÓN
							//crearSesionesCookies($id, $usuario, $clave, $nombre, $nivel, $sistema);
							$_SESSION['user_id_sen']	= $id;
							$_SESSION['usuario_sen']	= $usuario;
							$_SESSION['nombreUsu_sen']	= $nombre;
							$_SESSION['nivel_sen']		= $nivel;	
							
							setcookie("user_id_sen", "", time()-1);
							setcookie("usuario_sen", "", time()-1);
							setcookie("nombreUsu_sen", "", time()-1);
							setcookie("nivel_sen", "", time()-1);
							setcookie("sistema_sen", "", time()-1);	
							
							setcookie("user_id_sen", $_SESSION['user_id_sen'], time() + 60*60*24*30);
							setcookie("usuario_sen", $_SESSION['usuario_sen'], time() + 60*60*24*30);
							setcookie("nombreUsu_sen", $_SESSION['nombreUsu_sen'], time() + 60*60*24*30);
							setcookie("nivel_sen", $_SESSION['nivel_sen'], time() + 60*60*24*30);
							setcookie("sistema_sen", $sistema, time() + 60*60*24*30);
							
							//$token = crearTokenS();
							//Eliminar cookie token
							$arr_cookie_expires = array (
								'expires' => time() - 1,
								'path' => '/'.$sitio_actual,
								'domain' => $domain_actual, // leading dot for compatibility or use subdomain
								'secure' => true,     // or false
								'httponly' => true,    // or false
								'samesite' => 'None' // None || Lax  || Strict
							);
							
							//Crear json token
							if(!empty($_COOKIE['token_sen'])){
								$token = $_COOKIE['token_sen'];
								if (is_file( __DIR__.'\inSession\\'.$token.'.json')) {
									unlink(__DIR__.'\inSession\\'.$token.'.json');
								}
							}
							$longitud 	= 64;
							$token 		= "";
							$control	= true;
							while($control){
								$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
								for($i=0; $i<$longitud; $i++) {
									$token .= substr($str,rand(0,70),1);
								}
								if (!is_file( __DIR__.'\inSession\\'.$token.'.json')) {
									setSession($token);
									$control = false;
								}
							}
							setcookie('token_sen', '', $arr_cookie_expires);
							unset($_COOKIE['token_sen']);
							
							//Crear cookie token
							$arr_cookie_options = array (
													'expires' => time() + 60*60*24*30,
													'path' => '/'.$sitio_actual,
													'domain' => $domain_actual, // leading dot for compatibility or use subdomain
													'secure' => true,     // or false
													'httponly' => true,    // or false
													'samesite' => 'None' // None || Lax  || Strict
												);
							setcookie('token_sen', $token, $arr_cookie_options);
							
							//Renombrar archivo token json
							if ( is_file( __DIR__.'\inSession\\'.$tokenls.'.json')) {
								rename( __DIR__.'\inSession\\'.$tokenls.'.json',  __DIR__.'\inSession\\'.$token.'.json');
							}else{
								//debugL(" NO IS FILE ".$tokenls.".json","RASTREO-USUARIO ".$usuario);
							}
							$sentencia = $mysqli->prepare("	UPDATE user_token SET token = ? WHERE username = ? "); 
							$sentencia->bind_param("ss", $token, $usuario );
							
							//SALIDA
							$msg = 'dashboard.php';
							$error = false;
						}else{ 
							//SALIDA
							$msg = '<a style="cursor:pointer;" id="inicioalternativo">Ya existe una sesión activa. <br> ¿Cerrar esa sesión e <span style="text-decoration: underline;cursor:pointer;">iniciar acá</span> ?</a>';
							$error = true;
						}
                    }else{
						//Crear variables de sesión
						//crearSesionesCookies($id, $usuario, $clave, $nombre, $nivel, $sistema, $token);
						$_SESSION['user_id_sen']	= $id;
						$_SESSION['usuario_sen']	= $usuario;
						$_SESSION['nombreUsu_sen']	= $nombre;
						$_SESSION['nivel_sen']		= $nivel;	
						
						setcookie("user_id_sen", "", time()-1);
						setcookie("usuario_sen", "", time()-1);
						setcookie("nombreUsu_sen", "", time()-1);
						setcookie("nivel_sen", "", time()-1);
						setcookie("sistema_sen", "", time()-1);	
						
						setcookie("user_id_sen", $_SESSION['user_id_sen'], time() + 60*60*24*30);
						setcookie("usuario_sen", $_SESSION['usuario_sen'], time() + 60*60*24*30);
						setcookie("nombreUsu_sen", $_SESSION['nombreUsu_sen'], time() + 60*60*24*30);
						setcookie("nivel_sen", $_SESSION['nivel_sen'], time() + 60*60*24*30);
						setcookie("sistema_sen", $sistema, time() + 60*60*24*30);
						
						//Eliminar cookie token
						$arr_cookie_expires = array (
							'expires' => time() - 1,
							'path' => '/'.$sitio_actual,
							'domain' => $domain_actual, // leading dot for compatibility or use subdomain
							'secure' => true,     // or false
							'httponly' => true,    // or false
							'samesite' => 'None' // None || Lax  || Strict
						);
						
						//Crear json token
						//$token = crearTokenS();
						//!empty($_COOKIE['token_sen']) ? $token = $_COOKIE['token_sen'] : $token = "";
						if(!empty($_COOKIE['token_sen'])){
							$token = $_COOKIE['token_sen'];
							if (is_file( __DIR__.'\inSession\\'.$token.'.json')) {
								unlink(__DIR__.'\inSession\\'.$token.'.json');
							}
						}
						$longitud 	= 64;
						$token 		= "";
						$control	= true;
						while($control){
							$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
							for($i=0; $i<$longitud; $i++) {
								$token .= substr($str,rand(0,70),1);
							}
							if (!is_file( __DIR__.'\inSession\\'.$token.'.json')) {
								setSession($token);
								$control = false;
							}
						}
						setcookie('token_sen', '', $arr_cookie_expires);
    					unset($_COOKIE['token_sen']);
						
						//Crear cookie token
						$arr_cookie_options = array (
												'expires' => time() + 60*60*24*30,
												'path' => '/'.$sitio_actual,
												'domain' => $domain_actual, // leading dot for compatibility or use subdomain
												'secure' => true,     // or false
												'httponly' => true,    // or false
												'samesite' => 'None' // None || Lax  || Strict
											);
						setcookie('token_sen', $token, $arr_cookie_options);
								
                        $sentencia = $mysqli->prepare("	INSERT INTO user_token(username,token) VALUES(?, ?) ");
            			$sentencia->bind_param("ss", $usuario, $token );
						//SALIDA
						$msg = 'dashboard.php';
						$error = false;
                    }
                    $sentencia->execute();
    			    $resultado = $sentencia->get_result();
				}else{
					//debugL(" NO IF ","RASTREO-USUARIO ".$usuario);
				}
				//BITACORA
				bitacora('Index', 'Inicio de sesión', $id, "SELECT id, usuario, clave, nombre, nivel 
											FROM usuarios 
											WHERE usuario = '".$usuario."' AND clave = ? AND estado = 'Activo'");
				
				echo json_encode( array('error' => $error, 'msg'   => $msg, 'token' => $token ));
                exit;
			} else {
				echo json_encode(array(
                    'error' => true,
                    'msg'   => "Usuario o clave incorrecta"
                ));
                exit;
			}
		} else {
			echo json_encode(array(
                'error' => true,
                'msg'   => "<div class='alert alert-danger'>Debe llenar todos los campos!</div>"
            ));
            exit;
		}
	}else{
		echo "Campos vacios";
	}
	
function crearTokenS(){
	//$token = $_COOKIE['token_sen'];
	!empty($_COOKIE['token_sen']) ? $token = $_COOKIE['token_sen'] : $token = "";
	if (is_file( __DIR__.'\inSession\\'.$token.'.json')) {
		unlink(__DIR__.'\inSession\\'.$token.'.json');
	}
	$longitud 	= 64;
	$token 		= "";
	$control	= true;
	while($control){
		$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		for($i=0; $i<$longitud; $i++) {
			$token .= substr($str,rand(0,70),1);
		}
		if (!is_file( __DIR__.'\inSession\\'.$token.'.json')) {
			setSession($token);
			$control = false;
		}
	}
	return $token;
}

// Generate token
function getToken($length){
  $token = "";
  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
  $codeAlphabet.= "0123456789";
  $max = strlen($codeAlphabet); // edited

  for ($i=0; $i < $length; $i++) {
    $token .= $codeAlphabet[random_int(0, $max-1)];
  }

  return $token;
}
	
?>