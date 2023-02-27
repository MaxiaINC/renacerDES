<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//BITACORA
    //bitacora('Calendario', 'Consultar calendario', '', '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Calendario - Senadis</title>
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
	<link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
	<!-- Sweetalert2-->
    <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<!--FULLCALENDAR-->
    <link href="./vendor/fullcalendar/css/fullcalendar.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/ajustes-calendario.css">
	<!-- Ajustes -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="./css/ajustes.css" rel="stylesheet">
	<style>
	.select2-container--default .select2-selection--single .select2-selection__rendered{
		line-height: 40px;
		color: #7e7e7e;
		padding-left: 15px;
	}
	.select2-container--default .select2-selection--single{
		height: 40px;
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow{
		top: 8px;
	}
	</style>
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
							<div class="card-body py-0" id="DZ_W_Filtros_Body">
								<div class="form-config">
                                    <form id="form_filtrosmasivos" method="POST" autocomplete="off">
                                        <div class="d-block my-3">	
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
													<select name="idestados" id="idestados" class="form-control" multiple></select>
												</div>
											</div>
											<div class="form-group row mb-1">
												<label class="col-sm-4 col-form-label px-0">Médicos</label>
												<div class="col-sm-8">
													<select name="idmedicos" id="idmedicos" class="form-control"></select>
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
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                Calendario
                            </div>
                        </div>
						<ul class="navbar-nav header-right">
							<li class="nav-item dropdown notification_dropdown">
								<a class="nav-link bell config-link bg-success" id="filtromas" href="javascript:;" data-toggle="tooltip" title="Filtros">
									<i class="fas fa-filter text-white i-header"></i>
								</a>
							</li>
							<li class="nav-item dropdown notification_dropdown">
                                <a id="refrescar"class="nav-link bg-success"  href="#">
                                    <span class="btn-icon" data-toggle="tooltip" title="Refrescar" id="refrescar"><i class="fa fa-refresh text-white i-header"></i></span>
                                </a>
							</li>
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
				<div class="col-12 filtrosapl">
					<div class="bootstrap-badge mb-1"></div>
				</div>
				<div class="row mt-2">
					<div class="col-md-12 mb-3 text-right">
					    <!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".modal-columnas-modal-lg">
                            <i class="fa fa-columns mr-2"></i> Columnas
						</button>
						<button type="button" class="btn btn-primary btn-xs" id="nuevoProyecto">
							<i class="fa fa-plus-circle mr-2"></i> Nuevo
						</button>   --> 
						<button type="button" class="btn btn-primary text-white btn-xs" id="export_data" name="export_data" onclick="exportar(1)">
                            <i class="fas fa-file-excel mr-2"></i> Exportar agenda
                        </button>                     
					</div>
				</div>
				<!--Modal-->
				<!--tabla-->
				<div class="row">
                    	<div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="calendar-container">
										<div id="calendar" class="app-fullcalendar"></div>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>
				<!--fin tabla-->
            </div>
        </div>
    </div>
	<?php include "calendario-agendamiento.php";?>
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
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <?php linksfooter(); ?>
	<!-- Datatable -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>	
	<!-- CALENDARIO-->
	<script src="./vendor/moment/moment.min.js"></script>
	<script src="./vendor/fullcalendar/js/fullcalendar.min.js"></script>
	<script src="./vendor/fullcalendar/js/locale-all.js"></script>
	<!-- Sweetalert2-->
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
	<script type="text/javascript"> var nivel = <?php echo $_SESSION['nivel_sen']; ?></script>;
    <!-- Calendario -->
    <script src="./js/calendario.js<?php autoVersiones(); ?>"></script>	
	<script>
		$("select").select2();
	</script>
</body>

</html>