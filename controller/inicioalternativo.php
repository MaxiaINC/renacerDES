<?php 
include_once("conexion.php");
include_once("funciones.php");
	
    if(!empty($_POST)){
		
		$usuario= $_REQUEST['usr'];
		$clave 	= $_REQUEST['pwd'];  
		$sistema = 'senadis';
		$hashed_pass = hash('sha256', stripslashes($clave) );
		$sentencia = $mysqli->prepare("	SELECT id, usuario, clave, nombre, nivel 
											FROM usuarios 
											WHERE usuario = ? AND clave = ? AND estado = 'Activo' ");
		$sentencia->bind_param("ss", $usuario, $hashed_pass );
		$sentencia->execute();
		$resultado = $sentencia->get_result();
		if ($registro = $resultado->fetch_assoc()) {
			$sql = $mysqli->prepare(" SELECT username, token FROM user_token WHERE username = ? ");
			$sql->bind_param("s", $usuario);
			$sql->execute();
			$result = $sql->get_result();
			if ($reg = $result->fetch_assoc()) {
				$username = $reg['username'];
				$token = $reg['token'];
				
				//debugL("USERNAME ES: ".$username."-TOKEN ANTES DE BORRAR ES: ".$token,"DEBUGL-INICIOALTERNATIVO");
				//Eliminar token
				$sentencia = $mysqli->prepare("	DELETE FROM user_token WHERE token = ? ");
				$sentencia->bind_param("s", $token );
				if ($sentencia->execute()) {
					
					//Eliminar cookies
					setcookie("user_id_sen", "", time()-1);
					setcookie("usuario_sen", "", time()-1);
					setcookie("nombreUsu_sen", "", time()-1);
					setcookie("nivel_sen", "", time()-1);
					setcookie("sistema_sen", "", time()-1);
	
					//Eliminar cookie token
					$arr_cookie_expires = array (
						'expires' => time() - 1,
						'path' => '/renacerdes',
						'domain' => 'localhost', // leading dot for compatibility or use subdomain
						'secure' => true,     // or false
						'httponly' => true,    // or false
						'samesite' => 'None' // None || Lax  || Strict
					);
					setcookie('token_sen', '', $arr_cookie_expires);
					unset($_COOKIE['token_sen']);		
					
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
	
					//Eliminar directorio 
				   unlink(__DIR__.'\inSession\\'.$token.'.json');
				   echo json_encode(array(
						'error' => false,
						'msg'   => "controller/login.php"
					));
					exit;
				}else{
					echo json_encode(array(
						'error' => true,
						'msg'   => "Error al eliminar"
					));
					exit;
				}
			}else{
				echo json_encode(array(
					'error' => true,
					'msg'   => "No existe token"
				));
				exit;
			}
		}else{
			echo json_encode(array(
                    'error' => true,
                    'msg'   => "Usuario o clave incorrecta"
                ));
                exit;
		}   
	}














?>