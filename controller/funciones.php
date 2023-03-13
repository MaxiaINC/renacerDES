<?php
	
function autoVersiones(){
	echo '?v='.rand(1000, 9999);
}

function crearSesionesCookies($id, $usuario, $clave, $nombre, $nivel, $sistema){
	//SESIONES 
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
	//debugL('SESION ES:'.json_encode($_SESSION),'DEBUGLSESIONES');
	/*//COOKIES
	//ELIMINAR
	$arr_cookie_expires = array (
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
	setcookie('sistema', '', $arr_cookie_expires);
	//CREAR
	$arr_cookie_options = array (
		'expires' => time() + 60*60*24*30,
		'path' => '/'.$sistema,
		'domain' => 'toolkit.maxialatam.com', // leading dot for compatibility or use subdomain
		'secure' => true,     // or false
		'httponly' => true,    // or false
		'samesite' => 'None' // None || Lax  || Strict
	);
	setcookie("user_id", $id, $arr_cookie_options);
	setcookie("usuario", $usuario, $arr_cookie_options);
	setcookie("nombreUsu", $nombre, $arr_cookie_options);
	setcookie("nivel", $nivel, $arr_cookie_options);
	setcookie("sistema", $sistema, $arr_cookie_options); */
}

function verificarLogin($valor = '') {
	sessionrestore('view');
	debugL('PASÓ VERIFICARLOGIN-SESSIONUSERSEN'.$_SESSION["usuario_sen"],'DEBUGLSESIONES');
	if($_SESSION["usuario_sen"] != ''){
		return 1;
		//checkToken();
	}else{
		if($valor == 'reportes'){
			header('Location: ../index.php');
		}else{
			header('Location: index.php');
		}
	}
} 

function cierreforzado(){
    //bitacora($_SESSION['usuario'], 'Seguridad', 'Solicitud de interfaz '.$_SERVER['REQUEST_URI'].' rechazada por falta de permisos', 0, '');
	header('Location: cerrar.php');
	die();
}

//Crear directorio
function createFolder($directorio){  
	if(file_exists($directorio)){
		return true;
	}else{
		$target_path2 = utf8_decode($directorio);
		if (!file_exists($target_path2))
		mkdir($target_path2, 0777);
		return true;
	}
}
function checkToken() {
	/*
    global $mysqli;
    if (isset($_SESSION['usuario'])) {
        $sentencia = $mysqli->prepare("	SELECT token
									FROM user_token 
									WHERE username = ? ");
		//debugL("4. SELECT token FROM user_token WHERE username = '".$_SESSION['usuario']."' ");
	    $sentencia->bind_param("s", $_SESSION['usuario'] );
	    $sentencia->execute();
	    $resultado = $sentencia->get_result();
	    if ($registro = $resultado->fetch_assoc()) {
            $token = $registro['token']; 
            if($_SESSION['token'] != $token){
                session_destroy();
                header('Location: index.php');
            } else {
                return 1;
            }
        }else{
			//debugL("5. No existe token para ese usuario ");
		}
    }else{
		//debugL("6. No existe sesión ");
	}
	*/
}

function setSession($fileName='000') {
	$json_string = json_encode($_SESSION);
	$file = __DIR__.'\inSession\\'.$fileName.'.json'; 
	file_put_contents($file, $json_string);
}
function getSession(){
	$token=$_COOKIE['token_sen'];
	$str = file_get_contents( 'inSession/'.$token.'.json');
	return  json_decode($str, true);
}	
/* function sessionrestore($where=""){
	$token = $_COOKIE['token_sen'];
	debugL('TOKEN ES:'.$token,'DEBUGLSESIONES');
	if ($where=='view') {
		$str = file_get_contents( __DIR__.'\inSession\\'.$token.'.json');
	}else{
		$str = file_get_contents( 'inSession/'.$token.'.json');
	}	
	debugL('str ES:'.$str,'DEBUGLSESIONES');
	$_SESSION = json_decode($str, true);
} */

function yearsCombos(){
    $inicio = 2014;
    $fin = date('Y');
    $options = '';
    for($i = $inicio; $i <= $fin; $i++){
        if($i == 2020){
            $options .= '<option value="'.$i.'" selected>'.$i.'</option>';   
        }else{
            $options .= '<option value="'.$i.'">'.$i.'</option>';
        }
    }
    echo $options;
}

function showname(){  
    $nombre = $_SESSION['nombreUsuario_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
}

function menu() {
	$nivel = $_SESSION['nivel_sen'];
	$usuario = $_SESSION['usuario_sen'];
	echo ' 
		<div class="deznav">
            <div class="deznav-scroll bg-success-light">
                <ul class="metismenu" id="menu">
					<li>
						<a href="dashboard.php" aria-expanded="false" data-toggle="tooltip" title="Dashboard" data-placement="right">
							<i class="fa fa-bar-chart-o"></i>
							<span class="nav-text">Dashboard</span>
						</a>
					</li>';
					if($nivel != 17):
					echo '<li>
						<a href="solicitudes.php" aria-expanded="false" data-toggle="tooltip" title="Solicitudes" data-placement="right">
							<i class="fa fa-tasks"></i>
							<span class="nav-text">Solicitudes</span>
						</a>
					</li>';
					endif;
					if($nivel == 1 || $nivel == 8 || $nivel == 9 || $nivel == 10 || $nivel == 11 || $nivel == 12 || $nivel == 14 || $nivel == 15):
					echo '
					<li>
						<a href="calendario.php" aria-expanded="false" data-toggle="tooltip" title="Calendario" data-placement="right">
							<i class="fa fa-calendar"></i>
							<span class="nav-text">Calendario</span>
						</a>
					</li>';
					endif;
					if($nivel == 1 || $nivel == 15 || $nivel == 16):
					echo '
					<li>
						<a href="auditorias.php" aria-expanded="false" data-toggle="tooltip" title="Auditorías" data-placement="right">
							<i class="fa fa-file"></i>
							<span class="nav-text">Auditorías</span>
						</a>
					</li>';
					endif;
					if($nivel == 1 || $nivel == 15):
					echo '
					<li>
						<a href="habilitacionjuntas.php" aria-expanded="false" data-toggle="tooltip" title="Habilitación de junta evaluadora" data-placement="right">
							<i class="fa fa-users"></i>
							<span class="nav-text">Habilitación de junta evaluadora</span>
						</a>
					</li>';
					endif;
					if($nivel == 1 || $nivel == 15 || $nivel == 17):
					echo '<!--<li>
						<a href="estacionamientos.php" aria-expanded="false" data-toggle="tooltip" title="Solicitud de permiso de estacionamiento" data-placement="right">
							<i class="fa fa-car"></i>
							<span class="nav-text">Solicitud de permiso de estacionamiento</span>
						</a>
					</li>-->';
					endif;
					if($nivel == 1 || $nivel == 8 || $nivel == 14 || $nivel == 15 || $usuario == 'dlombana'):
					echo '
					<li>
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false" data-toggle="tooltip" title="Maestros">
                            <i class="fa fa-building-o"></i>
                            <span class="nav-text">Maestros</span>
                        </a>
                        <ul aria-expanded="false" class="bg-success-light">
							<li><a href="beneficiarios.php" data-toggle="tooltip" title="Listado de beneficiarios">Beneficiarios</a></li>';
							if($nivel == 1 || $nivel == 15):
							echo '
							<li><a href="medicos.php" data-toggle="tooltip" title="Listado de junta evaluadora">Junta evaluadora</a></li>
							<li><a href="corregimientos.php" data-toggle="tooltip" title="Listado de corregimientos">Corregimientos</a></li>
							<li><a href="enfermedades.php" data-toggle="tooltip" title="Listado de CIE">Condición de salud</a></li>
							<li><a href="firmas.php" data-toggle="tooltip" title="Firmas">Firmas</a></li>
							<li><a href="codigosautorizacion.php" data-toggle="tooltip" title="Códigos de autorización para reimpresión">Códigos de autorización para reimpresión</a></li>';
							endif;
						echo '
                        </ul>
					</li>';
					endif;
					echo '
					<!--
					<li>
						<a href="plantillas.php" data-toggle="tooltip" title="Plantillas" data-placement="right">
							<i class="fa fa-gears"></i>
							<span class="nav-text">Plantillas</span>
						</a>
					</li>
					-->
					';
					if($nivel == 1 || $nivel == 15):
					echo '
					<li>
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false" data-toggle="tooltip" title="Seguridad">
							<i class="fas fa-shield-alt"></i>
							<span class="nav-text">Seguridad</span>
						</a>
						<ul aria-expanded="false" class="bg-success-light">							
							<li><a href="usuarios.php" data-toggle="tooltip" title="Listado de usuarios">Usuarios</a></li>
							<li><a href="niveles.php" data-toggle="tooltip" title="Listado de niveles">Niveles</a></li>
							<li><a href="bitacora.php" data-toggle="tooltip" title="Bitácora del sistema">Bitácora</a></li>
						</ul>
					</li>';
					endif;
					echo '
					<li>
						<a href="#" title="Cambiar clave" id="cambiarclave">
							<i class="fa fa-key"></i>
							<span class="nav-text">Cambiar clave</span>
						</a>
						<ul class="link-list">
							<li>
								<!--<div class="form-group col-xs-12 col-sm-12">--><label class="text-label ml-4">Nueva clave</label><input type="password" id="nuevaclave" name="nuevaclave" class="form-control text ml-4 mb-2" style="width: 81% !important; height: 30px !important;"><!--</div>-->
									<a href="javascript:cambiarClave();" data-deploy-menu="notification" class="btn btn-primary btn-xs text-white ml-4 mr-4 fs-12">Cambiar clave</a>
								<!-- </div> -->
							</li>
						</ul>
					</li>
					<li>
						<a href="#" title="Cerrar sesión" id="cerrarsesion">
							<i class="fa fa-sign-out-alt"></i>
							<span class="nav-text">Cerrar sesión</span>
						</a>
					</li>
                </ul>
            </div>
        </div>
	';
}

function menusup(){
    echo '<div id="header" class="header-logo-app header-dark">
			<a href="#" class="header-title"></a>
			<a href="#" class="header-logo enabled"></a>
			<!--<a href="inicio.php" class="header-icon header-icon-1 no-border font-14"><i class="fa fa-home"></i></a> -->
			<a href="#" class="header-icon header-icon-1 hamburger-animated" data-deploy-menu="menu-1"></a>
			<div id="nombremenu"></div>
			<a class="header-title">';
			    showname();
	echo    '</a>'; 
	echo	'</div>';
}

function navheaderbotones(){
    $nombre = $_COOKIE['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	echo '              <ul class="navbar-nav header-right">
							<li class="nav-item dropdown notification_dropdown">
                                <a id="refrescar"class="nav-link bg-success"  href="#">
                                    <span class="btn-icon" data-toggle="tooltip" title="Refrescar" id="refrescar"><i class="fa fa-refresh text-white i-header"></i></span>
                                </a>
							</li>
							 <li class="nav-item dropdown notification_dropdown">
                                <a id="limpiarCol"class="nav-link bg-success" href="#">
                                    <span class="btn-icon" data-toggle="tooltip" title="Limpiar" id="limpiarCol"><i class="fa fa-eraser text-white i-header"></i></span>
                                </a>
							</li>
							<!-- <li class="nav-item dropdown notification_dropdown">
                                <a id="filtrosmasivos"class="nav-link bell bell-link bg-success" onclick="abrirFiltrosMasivos()" href="#">
                                    <span class="btn-icon" data-toggle="tooltip" title="Filtros" id="filtrosmasivos"><i class="fa fa-filter text-white i-header"></i></span>
                                </a>
							</li> --> 
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:;" role="button" data-toggle="dropdown">
                                    <div class="round-header">'.$inombre.'</div>
                                    <div class="header-info">
                                        <span>'.$nombre.'</span>
                                    </div>
                                </a>
                            </li>
                        </ul>';
}

function linksfooter(){
	echo '
		<script src="./vendor/global/global.min.js"></script>
		<script src="./js/custom.min.js"></script>
		<script src="./js/deznav-init.js"></script>
		<script src="./vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
		<!-- Momment js is must -->
		<script src="./vendor/moment/moment.min.js"></script>
		<!-- Toastr -->
		<script src="./vendor/toastr/js/toastr.min.js"></script>
		<!-- Select 2 -->
		<script src="./js/select2/select2.min.js"></script>
		<script src="./js/select2/select2-es.min.js"></script>
		<script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
		<!-- Datatable -->
		<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>	
		<!-- Sweetalert2-->
		<script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
		<!-- Fechas -->
		<script src="./vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
		<script src="./vendor/bootstrap-material-datetimepicker/js/datepicker-es.js"></script>
		<!-- Ajustes -->
		<script type="text/javascript" src="js/funciones.js"></script>
	';
}

function fotoPaciente($id,$ruta){
	//echo "PASÓ FOTOPAC";
	$imagen = "";
	//$ruta = '../images/beneficiarios/'.$id.'/';
	if (is_dir($ruta)){
		
		// Abre un gestor de directorios para la ruta indicada
		$gestor = opendir($ruta); 
		// Recorre todos los elementos del directorio
		while (($archivo = readdir($gestor)) !== false)  { 
			$ruta_completa = $ruta . "/" . $archivo;

			// Se muestran todos los archivos y carpetas excepto "." y ".."
			if ($archivo != "." && $archivo != ".." && $archivo != ".quarantine" && $archivo != ".tmb" && $archivo != "qr") { 
					
					$imagen = $archivo; 
			}
		}  
		closedir($gestor); 
	}
	
	return $imagen;
}

function qrPaciente($id){
	$qr = false;
	$rutaqr = '../images/beneficiarios/'.$id.'/qr';
	if (is_dir($rutaqr)){
		$qr = true;
	}
	return $qr;
}

function formatFechaString($fecha){
	setlocale(LC_TIME, "spanish");
	$newfecha = date("d-m-Y", strtotime($fecha));
	$newfecha = strtoupper(str_replace('.','',ucwords(strftime("%d-%h-%Y", strtotime($newfecha)))));
	return $newfecha;
}
?>