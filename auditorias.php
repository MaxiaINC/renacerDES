<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//BITACORA
    //bitacora('Auditorias', 'Obtener listado de solicitudes', '', '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Auditorías - Senadis</title>
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
	<!-- Datatable -->
	<link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
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
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                Auditorías
                            </div>
                        </div>
                        <?php //navheaderbotones(); ?>
						<ul class="navbar-nav header-right">
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
					<div class="col-md-12 mb-3 text-right">
					    <!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".modal-columnas-modal-lg">
                            <i class="fa fa-columns mr-2"></i> Columnas
						</button>  -->
						<button type="button" class="btn btn-primary text-white btn-xs" onclick="exportar()">
                            <i class="fas fa-file-excel mr-2"></i> Exportar
                        </button>    
						<button type="button" class="btn btn-primary btn-xs" id="nuevaSolicitud">
							<i class="fa fa-plus-circle mr-2"></i> Nueva
						</button>  
					</div>
				</div>
				<!--tabla-->
				<div class="row">
					<div class="col-xl-12">
						<div class="table-responsive">
							<table id="tablaauditorias" class="display min-w850">
								<thead>
									<tr>
										<th>Acciones</th>
                                        <th>ID</th>
                                        <th id="cexpediente">Expediente</th>
                                        <th id="ccedula">Cédula</th>
                                        <th id="cpaciente">Nombre</th>
                                        <th id="cregional">Regional</th>
                                        <th id="cestado">Estado</th>
										<th id="cauditor">Auditor</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<!--fin tabla-->
            </div>
        </div>
		<?php include_once('auditorias-agregarexpediente.php'); ?>
		<?php include_once('auditorias-agregarauditores.php'); ?>
		<?php include_once('auditorias-actualizarregional.php'); ?>
		<?php include_once('auditorias-cambiarestados.php'); ?>
		<?php include_once('auditorias-verdocumentos.php'); ?>
		<?php include_once('auditorias-adjuntos.php'); ?>
		<?php include_once('auditorias-generarinformes.php'); ?>
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
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <?php linksfooter(); ?>
	<!-- Auditorias -->
    <script src="./js/auditorias.js?<?php autoVersiones(); ?>"></script>	
</body>

</html>