<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora('Niveles', 'Agregar / Editar nivel', 0, '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Habilitación de junta evaluadora - Senadis</title>
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
                                Habilitación de junta evaluadora
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
                        <button type="button" class="btn btn-primary btn-xs" id="listado">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<form id="form_beneficiario">
									<div class="row">
										<div class="col-sm-12">
											<h5 class="col-form-label text-success">Datos</h5>
										</div>
										<div class=" col-md-4">
											<label class="control-label">Regional <span class="text-red">*</span></label>
											<select class="form-control" id="idregionales" name="idregionales"></select> 
										</div>
										<div class="form-group col-4">
											<label class="text-label">Número de resolución <span class="text-red">*</span></label>
											<input type="text" name="nroresolucion" id="nroresolucion" class="form-control mandatorio" disabled>
										</div>
										<div class="form-group col-4">
											<label class="text-label">Número de junta <span class="text-red">*</span></label>
											<input type="text" name="nrojunta" id="nrojunta" class="form-control mandatorio" disabled>
										</div>
										<div class="form-group col-4">
											<label class="text-label">Fecha para la resolución <span class="text-red">*</span></label>
											<input type="text" name="fecharesolucion" id="fecharesolucion" class="form-control mandatorio">
										</div>
										<div class="form-group col-4">
											<label class="text-label">Fecha para la evaluación <span class="text-red">*</span></label>
											<input type="text" name="fechaevaluacion" id="fechaevaluacion" class="form-control mandatorio">
										</div> 
										<div class="col-sm-12">
											<h5 class="col-form-label text-success">Especialistas</h5>
										</div>
										<div class="col-md-8">
											<label class="control-label" >Cédula</label>
											<select class="form-control" id="idmedicos" name="idmedicos"></select>
										</div>
										<div class="col-md-4">
											<label class="control-label text-white d-block">Añadir</label>
											<button type="button" class="btn btn-xs bg-success text-white" id="anadir_especialista">
												<i class="fas fa-plus-circle"></i>
											</button>
										</div>
										<div class="col-12 my-4">
											<table id="tabla_especialistas" class="display w-100 border">
												<thead class="bg-success-light">
													<th class="text-center font-w500" style="width:10%">Acción</th>
													<th class="font-w500" style="width:25%">Nombre</th>
													<th class="font-w500" style="width:20%">Registro</th>
													<th class="font-w500" style="width:17%">Cédula</th>
													<th class="font-w500" style="width:25%">Profesión</th>
												</thead>
												<tbody id="tabla_especialistas_cuerpo"></tbody>
											</table>
										</div>
										<div class="col-sm-12">
											<h5 class="col-form-label text-success">Solicitantes</h5>
										</div>
										<div class="col-md-8">
											<label class="control-label" >Cédula</label>
											<select class="form-control" id="idpacientes" name="idpacientes"></select> 
										</div>
										<div class="col-md-4">
											<label class="control-label text-white d-block">Añadir</label>
											<button type="button" class="btn btn-xs bg-success text-white" id="anadir_paciente">
												<i class="fas fa-plus-circle"></i>
											</button>
										</div>
										<div class="col-12 my-4">
											<table id="tabla_beneficiarios" class="display w-100 border">
												<thead class="bg-success-light">
													<th class="text-center font-w500" style="width:10%">Acción</th>
													<th class="font-w500" style="width:30%">Nombre</th>
													<th class="font-w500" style="width:30%">Cédula</th>
													<th class="font-w500" style="width:30%">Estado</th>
												</thead>
												<tbody id="tabla_beneficiarios_cuerpo"></tbody>
											</table>
										</div>
									</div>
									<div class="col-sm-12 pr-0 text-right">
										<button type="button" class="btn btn-primary btn-xs" onclick="guardar();">
											<i class="fas fa-check-circle mr-2"></i>Guardar
										</button>
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
	<!-- Nivel -->
    <script src="./js/habilitacionjunta.js<?php autoVersiones(); ?>"></script>	
</body>

</html>