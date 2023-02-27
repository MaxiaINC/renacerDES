<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//BITACORA
    //bitacora('Bitacora', 'Obtener listado de bitacora', '', '');
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Bitácora - Senadis</title>
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<link href="./css/style.css" rel="stylesheet">
	<!-- Select2 -->
	<link href="./vendor/select2/css/select2.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<!-- Sweetalert2 -->
	<link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
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
                                    Bitácora
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
                        <div class="col-md-12 mb-4 text-right">
                        </div>
                    </div>

                    <!--tabla-->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <table id="tablabitacora" class="display min-w850 ">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th id="accion">Acción</th>
                                            <th id="cusuario">Usuario</th>  
                                            <th id="cfecha">Fecha</th>
                                            <th id="cmodulo">Módulo</th>
                                            <th id="caccion">Acción</th><!--
                                            <th id="cidentificador">Identificador</th>
                                            <th id="csentencia">Sentencia</th>-->

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
    <!-- Datatable -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>	
    <!-- Bitacora -->
    <script src="./js/bitacora.js<?php autoVersiones(); ?>"></script>
    </body>
</html>