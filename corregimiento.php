<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora('Corregimientos', 'Agregar / Editar corregimientos', 0, '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Corregimientos - Senadis</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<!-- Select2 -->
	<link href="./vendor/select2/css/select2.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<!-- Style -->
	<link href="./css/style.css" rel="stylesheet">	
	<!-- Sweetalert2 -->
    <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<!-- Datetimepicker -->
	<link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">	
	<!-- Ajustes -->
	<link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                                Corregimiento
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
                        <button type="button" class="btn btn-primary btn-xs" id="listadoCorregimientos">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<form id="form_corregimiento">
									<div class="row">
										<div class="col-sm-12">
											<h5 class="col-form-label text-success">Datos</h5>
										</div>
										<div class="form-group col-12 col-sm-6 col-md-3">
											<label class="text-label">Código</label>
											<input type="hidden" class="form-control" name="iddireccion" id="iddireccion">
											<input type="text" name="codigo" id="codigo" class="form-control">
										</div>
										<div class="form-group col-12 col-sm-6 col-md-3">
											<label class="text-label">Provincia <span class="text-red">*</span></label>
											<select class="form-control mandatorio" id="idprovincias" name="idprovincias"></select>
										</div>
										<input type="hidden" name="valordistrito" id="valordistrito" class="form-control">
										<div class="form-group col-12 col-sm-6 col-md-3 distritoselect">
											<label class="text-label">Distrito <span class="text-red">*</span><i id="nuevo_distrito" class="fa fa-plus-circle" data-toggle="tooltip" aria-hidden="true" style="color: #0662ad; font-size: 1.5em; cursor: pointer; margin-left: 5px;" title="" data-original-title="Nuevo Distrito"></i></label>
											<select class="form-control mandatorio" id="iddistritos" name="iddistritos"></select>  
										</div>
										<div class="form-group col-12 col-sm-6 col-md-3 distritoinput" style="display:none;">
											<label class="text-label">Distrito <span class="text-red">*</span><i id="mostrar_distritos" class="fa fa-plus-circle" data-toggle="tooltip" aria-hidden="true" style="color: #0662ad; font-size: 1.5em; cursor: pointer; margin-left: 5px;" title="" data-original-title="Mostrar Distritos"></i></label> 
											<input type="text" name="distrito" id="distrito" class="form-control"> 
										</div>
										<div class="form-group col-12 col-sm-6 col-md-3">
											<label class="text-label">Corregimiento <span class="text-red">*</span></label>
											<input type="text" name="idcorregimientos" id="idcorregimientos" class="form-control mandatorio">
										</div>
										<div class="form-group col-12 col-sm-6 col-md-3">
											<label class="text-label">Área <span class="text-red">*</span></label>
											<input type="text" name="area" id="area" class="form-control mandatorio">
										</div>
									</div>
									<div class="col-sm-12 pr-0 text-right">
										<button type="button" class="btn btn-primary btn-xs" id="guardar">
											<i class="fas fa-check-circle mr-2"></i>Guardar
										</button>
										<!--
										<button type="button" class="btn btn-danger btn-xs" id="cancelar">
											<i class="fas fa-ban mr-2"></i>Cancelar
										</button>
										-->
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
	<!-- corregimientos -->
    <script src="./js/corregimientos.js<?php autoVersiones(); ?>"></script>	
</body>

</html>