<?php
	include_once("controller/conexion.php");
	include_once("controller/funciones.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//BITACORA
    //bitacora('Solicitudes', 'Obtener Dashboard', '', '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard - Senadis</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<link href="./vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
	<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
	<link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
	<!-- Select -->
	<link href="./css/select2/select2.min.css" rel="stylesheet" />
	<!--<link href="../repositorio-tema/assets/fonts/fonts.googleapis.com.css" rel="stylesheet" />-->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
	<!--<link href="../repositorio-tema/assets/css/bootstrap-material-datetimepicker.css" rel="stylesheet" >-->
	<!-- Datetimepicker -->
	<link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
	<!--Sweetalert2-->
    <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<!-- Ajustes -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="./css/ajustes.css" rel="stylesheet">
	<link href="./css/dashboardsen.css" rel="stylesheet">
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
    <div id="overlay">
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
            Configuración start
        ***********************************-->
		<div class="config">
			<div class="config-close"></div>
			<div class="custom-tab-1">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#filtrosconfig">Filtros</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active show" id="filtrosconfig" role="tabpanel">
						<div class="card mb-sm-3 mb-md-0">
							<div class="card-header d-none">
								<div>
                                    <h6 class="mb-1">Filtros</h6>
								</div>
							</div>
							<div class="card-body p-0" id="DZ_W_Filtros_Body">
								<div class="form-config">
                                    <form id="form_filtrosmasivos" method="POST" autocomplete="off">
                                        <div class="d-block my-3">	
											<div class="form-group row mb-1">
											     <label class="col-sm-4 col-form-label px-0"><input class="radiofechas radio_solicitud" type="radio" name="optradio" value="radio_sol" checked=""> Fecha de solicitud</label> 
                                                <label class="col-sm-4 col-form-label px-0"><input class="radiofechas radio_cita" type="radio" name="optradio" value="radio_cita" checked="">  Fecha de cita</label> 
                                            </div>
											<div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label px-0">Desde</label> 
                                                <div class="col-sm-8">
                                                    <input type="text" name="desdef" id="desdef" class="form-control text" placeholder="yyyy-mm-dd">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label px-0">Hasta</label> 
                                                <div class="col-sm-8">
                                                    <input type="text" id="hastaf" name="hastaf" class="form-control text" placeholder="yyyy-mm-dd">
                                                </div>
                                            </div>
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Provincia</label>
												<div class="col-sm-8">
													<select name="idprovincias" id="idprovincias" class="form-control"></select>
												</div>
											</div>
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Distrito</label>
												<div class="col-sm-8">
													<select name="iddistritos" id="iddistritos" class="form-control"></select>
												</div>
											</div>
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Corregimiento</label>
												<div class="col-sm-8">
													<select name="idcorregimientos" id="idcorregimientos" class="form-control"></select>
												</div>
											</div>
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Edad</label>
												<div class="col-sm-8">
													<select name="idedades" id="idedades" class="form-control">
														<option value="0" selected> ... </option>
														<option value="primerainfacia">0 a 5 años</option>
														<option value="infancia">6 a 11 años</option>
														<option value="adolescencia">12 a 18 años</option>
														<option value="juventud">19 a 26 años</option>
														<option value="adultez">27 a 59 años</option>
														<option value="personamayor">60 años o más </option>
													</select>
												</div>
											</div>											
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Discapacidad</label>
												<div class="col-sm-8">
													<select name="iddiscapacidades" id="iddiscapacidades" class="form-control"></select>
												</div>
											</div>
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Género</label>
												<div class="col-sm-8">
													<select name="idgeneros" id="idgeneros" class="form-control">
														<option value="0"> ... </option>
														<option value="M">Masculino</option>
														<option value="F">Femenino</option>
													</select>
												</div>
											</div>
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Condición de salud</label>
												<div class="col-sm-8">
													<select name="idcondicionsalud" id="idcondicionsalud" class="form-control"></select>
												</div>
											</div>
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Estados</label>
												<div class="col-sm-8">
													<select name="idestados" id="idestados" class="form-control"></select>
												</div>
											</div>
											</br>
											<div class="text-right">
												<button type="button" class="col-xs-12 btn btn-primary btn-xs" id="filtrar" title="Filtrar">
													<i class="fas fa-filter"></i> Filtrar
												</button>
												<button type="button" class="col-xs-12 btn btn-warning btn-xs text-white" id="limpiarfiltros" title="Limpiar">
													<i class="fas fa-eraser"></i> Limpiar
												</button>
											</div>
                                        </div>
                                    </form>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
            Configuración End
        ***********************************-->
		
		<!--**********************************
            Reportes start
        ***********************************-->
		<div class="chatbox">
			<div class="chatbox-close"></div>
			<div class="custom-tab-1">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#reportes">Reportes</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active show" id="reportes" role="tabpanel">
						<div class="card mb-sm-3 mb-md-0">
							<div class="card-header d-none">
								<div>
                                    <h6 class="mb-1">Reportes</h6>
								</div>
							</div>
							<div class="card-body p-0" id="DZ_W_Filtros_Body">
								<div class="form-reportes">
                                    <form id="form_reportes" method="POST" autocomplete="off">
                                        <div class="d-block my-3">
											<div class="text-center">
												<button type="button" class="col-xs-12 btn btn-success btn-xs mt-2 mb-4" id="exportar" title="Estadísticas de Usuarios">
													Estadísticas de Usuarios
												</button>
												</br>
												<button type="button" class="col-xs-12 btn btn-success btn-xs mb-4" id="exportar-totales" title="Totales">
													Totales
												</button>
												</br>
												<button type="button" class="col-xs-12 btn btn-success btn-xs mb-4" id="vencimiento" title="Vencimiento">
													Vencimiento
												</button>
												</br>
												<button type="button" class="col-xs-12 btn btn-success btn-xs mb-4" id="exportar-estacionamiento" title="Reporte de estacionamiento">
													Reporte Estacionamiento
												</button>
												</br>
												<button type="button" class="col-xs-12 btn btn-success btn-xs" id="exportar-resoluciones" title="Reporte de resoluciones">
													Reporte Resoluciones
												</button>
											</div>
                                        </div>
                                    </form>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
            Reportes End
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
                                Dashboard
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
							<?php if($_SESSION['nivel_sen'] == 1 || $_SESSION['nivel_sen'] == 12 || $_SESSION['nivel_sen'] == 13 || $_SESSION['nivel_sen'] == 14 || $_SESSION['nivel_sen'] == 15): ?>
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell config-link bg-success" id="filtromas" href="javascript:;"  data-toggle="tooltip" title="Filtros">
									<i class="fas fa-filter text-white i-header"></i>
                                </a>
							</li>
							<?php endif; ?>
							<?php
							// Administrador / Captador
							if($_SESSION['nivel_sen'] == 1 || $_SESSION['nivel_sen'] == 13 || $_SESSION['nivel_sen'] == 15): echo '
							<li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell bell-link bg-success" href="javascript:;"  data-toggle="tooltip" title="Reportes">
									<i class="fas fa-file-download text-white i-header"></i>
                                </a>
							</li>';
							endif;
							?>
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
            <!-- row -->
			<div class="container-fluid">		
				<div class="filtrosapl" style="margin-top: -30px;">
					<div class="bootstrap-badge mb-1"></div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body p-3">
								<div class="row text-center">
									<div class="col-6 col-md-2 mb-1">
										<div class="bgl-primary rounded  px-1 py-2">
											<span class="font-w500">Solicitudes</span>
											<h4 class="mb-0 text-success" id="txtSolicitados">0</h4>											
										</div>
									</div>
									<div class="col-6 col-md-2 mb-1">
										<div class="bgl-primary rounded  px-1 py-2">
											<span class="font-w500">Citados</span>
											<h4 class="mb-0 text-success" id="txtSolicitudes">0</h4>											
										</div>
									</div>
									<div class="col-6 col-md-2 mb-1">
										<div class="bgl-primary rounded  px-1 py-2">
											<span class="font-w500">Evaluados</span>
											<h4 class="mb-0 text-success" id="txtEvaluados">0</h4>										
										</div>
									</div>
									<div class="col-6 col-md-2 mb-1">
										<div class="bgl-primary rounded  px-1 py-2">
											<span class="font-w500">Resultados</span>
											<h4 class="mb-0">
												<span class="text-success" id="txtCertificados" data-toggle="tooltip" title="Certificados">0</span> /
												<span class="text-danger" id="txtNoCertificados" data-toggle="tooltip" title="No Certificados">0</span>
											</h4>
										</div>
									</div>
									<div class="col-6 col-md-2 mb-1">
										<div class="bgl-primary rounded  px-1 py-2">
											<span class="font-w500">No asistió</span>
											<h4 class="mb-0 text-success" id="txtNoAsistio">0</h4>											
										</div>
									</div>
									<div class="col-6 col-md-2 mb-1">
										<div class="bgl-primary rounded  px-1 py-2">
											<span class="font-w500">Pendientes</span>
											<h4 class="mb-0 text-success" id="txtPendientesEvaluar">0</h4>											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="card">
							<div class="card-body p-3">
								<div class="col-xs-12 col-sm-12 col-md-12 p-0">
									<div class="form-group label-floating">
										<h5 class="text-success mb-0">Ubicaciones</h5>
									</div>
								</div>
								<div id="mapprueba" ></div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card">
							<div class="card-body p-3">
								<div class="col-12 p-0 mb-3">
									<div class="form-group label-floating">
										<h5 class="text-success">Certificados</h5>
									</div>
								</div>
								<div class="row">
									<div class="col-6 content px-2">
										<p class="txt-sexo d-none">Mujeres</p>
										<img class="img-sexo" src="images/mujer.png" />
										<p class="txt-mujeres" id="valMujeres">0%</p>
									</div>
									<div class="col-6 content px-2">
										<p class="txt-sexo d-none">Hombres</p>
										<img class="img-sexo" src="images/hombre.png" />
										<p class="txt-hombres" id="valHombres">0%</p>
									</div>
									<div class="col-12 content txt-imagenpersona">
										<p>De los usuarios certificados, el </br>
										<b><span id="bMujeres">0%</span> (<span id="valMujeresT">0</span>)</b> son mujeres y el </br>
										<b><span id="bHombres">0%</span> (<span id="valHombresT">0</span>)</b> son hombres</p> 
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card">
							<div class="card-body p-3">
								<div class="col-xs-12 col-sm-12 col-md-12 p-0">
									<div class="form-group label-floating">
										<h5 class="text-success mb-0">Condición laboral</h5>
									</div>
								</div>
								<div class="chart">
									<div id="pie_condicion_laboral"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Distribución por tipo de discapacidad, Condición laboral -->
				<div class="row">
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="col-xs-12 col-sm-12 col-md-12 p-0">
									<div class="form-group label-floating">
										<h5 class="text-success">Distribución por tipo de discapacidad</h5>
									</div>
								</div>
								<div class="chart">
									<div id="bar_tipo_discapacidad"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="col-xs-12 col-sm-12 col-md-12 p-0">
									<div class="form-group label-floating">
										<h5 class="text-success">Nivel de alfabetismo</h5>
									</div>
								</div>
								<div class="chart">
									<div id="bar_nivel_alfabetismo"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Ingresos Familiares por usuario, Nivel educativo -->
				<div class="row">
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="col-xs-12 col-sm-12 col-md-12 p-0">
									<div class="form-group label-floating">
										<h5 class="text-success">Ingresos familiares</h5>
									</div>
								</div>
								<div class="chart">
									<div id="line_ingresos_familiares"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="col-xs-12 col-sm-12 col-md-12 p-0">
									<div class="form-group label-floating">
										<h5 class="text-success">Nivel educativo</h5>
									</div>
								</div>
								<div class="chart">
									<div id="line_nivel_educativo"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Distribución por tipo de discapacidad, Condición laboral -->
				<div class="row">
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="col-xs-12 col-sm-12 col-md-12 p-0">
									<div class="form-group label-floating">
										<h5 class="text-success">Solicitudes</h5>
									</div>
								</div>
								<div class="chart">
									<div id="bar_solicitudes_mes"></div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-sm-6 col-xxl-3">
						<div class="widget-stat card bg-warning">
							<div class="card-body p-4">
								<div class="media">
									<span class="mr-3">
										<i class="fa fa-calendar" aria-hidden="true"></i>
									</span>									
								</div>
								<div class="media-body text-white pt-4">
									<p class="mb-4 fs-16">Tiempo promedio que toman las solicitudes, desde su registro hasta que son agendadas.</p>
									<h4 class="text-white" id="promSA">0</h4>
									<div class="progress mb-2 bg-primary">
										<div class="progress-bar progress-animated bg-light" style="width: 0%"></div>
									</div>
									<span  id="cantSA">0</span>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-sm-6 col-xxl-3">
						<div class="widget-stat card bg-success">
							<div class="card-body p-4">
								<div class="media">
									<span class="mr-3">
										<i class="fas fa-file-alt"></i>
									</span>
								</div>
								<div class="media-body text-white pt-4">
									<p class="mb-4 fs-16">Tiempo promedio que toman las solicitudes, desde su registro hasta la generación del resultado. </br> (certifica o no certifica)</p>
									<h4 class="text-white" id="promSR">0</h4>
									<div class="progress mb-2 bg-primary">
										<div class="progress-bar progress-animated bg-light" style="width: 0%"></div>
									</div>
									<span  id="cantSR">0%</span>
								</div>
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
	<!-- Gráficos -->
	<script src="js/graph/js/maps/highmaps.js"></script>
	<script src="js/graph/js/highcharts.js"></script>
	<script src="js/graph/js/modules/data.js"></script>
	<script src="js/graph/js/modules/exporting.js"></script>
	<script src="js/graph/js/maps/exporting.js"></script>
	<script src="js/graph/js/maps/pa-all.js"></script>
	<script type="text/javascript"> var nivel = <?php echo $_SESSION['nivel_sen']; ?></script>;
	<script src="./js/dashboard.js"></script>
	<script>
		$("select").select2();
	</script>
</body>
</html>