<?php
include_once("controller/conexion.php");
include_once("controller/funciones.php");

$usuario = (!empty($_REQUEST['usuario_sen']) ? $_REQUEST['usuario_sen'] : '');
$token = (!empty($_REQUEST['token_sen']) ? $_REQUEST['token_sen'] : '');
$sistema = (!empty($_REQUEST['sistema_sen']) ? $_REQUEST['sistema_sen'] : '');
$resultado = array();

if($token != '' && $token != 'undefined' && $token != 'null'){
	if (is_file( __DIR__.'\controller\inSession\\'.$token.'.json')) {
		if($_SESSION['usuario_sen'] == ''){
			$user 	= getId('username','user_token',$token,'token');
			$id 	= getId('id','usuarios',$user,'usuario');
			$clave 	= getId('clave','usuarios',$user,'usuario');
			$nombre = getId('nombre','usuarios',$user,'usuario');
			$nivel 	= getId('nivel','usuarios',$user,'usuario');
			
			crearSesionesCookies($id, $user, $clave, $nombre, $nivel, $sistema, $token);
		}
		$resultado = array( 'success' => true, 'mensaje' => 'Renovar Sesiones', 'token' => '', 'usuario' => '', 'valor' => 1 );
	}else{
		$resultado = array( 'success' => false, 'mensaje' => 'No existe token inSession', 'token' => $token, 'usuario' => '', 'valor' => 0 );
	}
}else{
	if($_COOKIE['user_id_sen'] != '' || $_SESSION['user_id_sen'] != ''){
		if($_COOKIE['user_id_sen'] != ''){
			$idusuario = $_COOKIE['user_id_sen'];
		}else{
			$idusuario = $_SESSION['user_id_sen'];
		}
		
		$usuario= getId('usuario','usuarios',$idusuario,'id');
		$clave 	= getId('clave','usuarios',$idusuario,'id');
		$nombre = getId('nombre','usuarios',$idusuario,'id');
		$nivel 	= getId('nivel','usuarios',$idusuario,'id');
		$token 	= getId('token','user_token',$usuario,'username');
		 
		crearSesionesCookies($id, $user, $clave, $nombre, $nivel, $sistema, $token);
		
		$resultado = array( 'success' => true, 'mensaje' => 'Renovar token', 'token' => $token, 'usuario' => $usuario, 'valor' => 2 );
	}else{
		$resultado = array( 'success' => false, 'mensaje' => '_COOKIE or _SESSION vacia', 'token' => '', 'usuario' => '', 'valor' => 3 );
	}
}
echo json_encode($resultado);

?>