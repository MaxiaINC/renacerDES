<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	bitacora('Junta evaluadora', 'Agregar / Editar junta evaluadora', 0, '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Junta evaluadora - Senadis</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<!-- Custom Stylesheet -->
    <link href="./vendor/select2/css/select2.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
	<link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
	<!--Sweetalert2-->
    <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<!-- Ajustes -->
    <link href="./css/ajustes.css" rel="stylesheet">	
</head>

<body>
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->
	
	<!--*******************
        Overlay start
    ********************-->
    <div id="overlay" style="display: none;">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Overlay end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="dashboard.php" class="brand-logo">
                <img class="logo-abbr" src="./images/favicon.png" alt="">
				<!--
                <img class="logo-compact" src="./images/logo-text.png" alt="">
                <img class="brand-title" src="./images/logo-text.png" alt="">
				-->
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->
		
        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                Junta evaluadora
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
                           <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:;" role="button" data-toggle="dropdown">
                                    <div class="round-header"><?php echo $inombre; ?></div>
                                    <div class="header-info">
                                        <span><?php echo $nombre; ?></span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php menu(); ?>
        <!--**********************************
            Sidebar end
        ***********************************-->


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid pt-0">
				<div class="row">
                    <div class="col-md-12 mb-4 text-right barraOpc">
                        <button type="button" class="btn btn-primary btn-xs" id="listadoMedicos">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">								
								<form id="form_medico">
									<div class="row">
										<div class="form-group col-12 col-sm-6 col-md-6">
											<label class="text-label" id="tipodocumento_txt">Documento de identidad personal </label>
											<div class="row">
												<div class="form-group col-12 col-sm-5 pr-0">													
													<input type="hidden" class="form-control" name="idmedico" id="idmedico">
													<select lang="es" class="form-control mandatorio" name="tipodocumento" id="tipodocumento" autocomplete="off">	  
														<option value="0">Seleccione</option>
														<option value="1">Cédula</option>
														<option value="2">Carnet migratorio</option>
													</select>
												</div>
												<div class="form-group col-12 col-sm-7 pl-0">
													<input type="text" name="cedula" id="cedula" class="form-control text solotextoynumero">
												</div>
											</div>
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<label class="text-label">Nombre <span class="text-red">*</span></label>
											<input type="text" name="nombre" id="nombre" class="form-control text">
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<label class="text-label">Apellido <span class="text-red">*</span></label>
											<input type="text" name="apellido" id="apellido" class="form-control text">
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<label class="text-label">Correo <span class="text-red">*</span></label>
											<input type="text" name="correo" id="correo" class="form-control text">
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<label class="text-label">Teléfono</label>
											<input type="text" name="telefonocelular" id="telefonocelular" class="form-control text">
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<label class="text-label">Teléfono otro</label>
											<input type="text" name="telefonootro" id="telefonootro" class="form-control text">
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<label class="text-label">Especialidades <span class="text-red">*</span></label>
											<select id="idespecialidades" name="idespecialidades" class="mandatorio" multiple="true"></select>
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<label class="text-label">Discapacidades que puede atender <span class="text-red">*</span></label>
											<select id="iddiscapacidades" name="iddiscapacidades" class="mandatorio" multiple="true"></select>
										</div>
										<div class="form-group col-xs-12 col-sm-6">
											<label class="text-label">Regionales <span class="text-red">*</span></label>
											<select id="idregionales" name="idregionales" class="mandatorio" multiple="true"></select>
										</div>
										<div class="col-sm-12 text-right">
											<button type="button" class="btn btn-primary btn-xs" onClick="guardar();">
												<i class="fas fa-check-circle mr-2"></i>Guardar
											</button>
											<!--
											<button type="button" class="btn btn-danger" id="cancelar">
												<i class="fas fa-ban mr-2"></i>Cancelar
											</button>
											-->
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
            Content body end
        ***********************************-->


		<!--**********************************
            Footer start
        ***********************************-->
		<div class="footer">
			<?php include_once('footer.php'); ?>
		</div>
		<!--**********************************
            Footer end
        ***********************************-->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
	<?php linksfooter(); ?>
	<!--Sweetalert2-->
	<script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <!-- Usuario -->
    <script src="./js/medicos.js<?php autoVersiones(); ?>"></script>	
</body>

</html>