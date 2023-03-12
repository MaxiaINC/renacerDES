<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//BITACORA
	//bitacora('Solicitudes', 'Agregar / Editar solicitud', 0, '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Solicitud de permiso de estacionamiento - Senadis</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<!-- Select2 -->
	<link href="./vendor/select2/css/select2.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<!-- Toastr -->
	<link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
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
                                Solicitud de permiso de estacionamiento
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
                        <button type="button" class="btn btn-primary btn-xs" id="listadoSolicitudes">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<div class="default-tab">
									<ul class="nav nav-pills review-tab" role="tablist">
										<li class="nav-item active">
											<a class="nav-link active" data-toggle="tab" href="#datosolicitud">Datos del solicitante</a>
										</li> 
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#datosvehiculo">Datos del vehículo</a>
										</li> 
										<li class="nav-item" >
											<a class="nav-link" data-toggle="tab" href="#aprobacionpermiso">Aprobación de permiso</a>
										</li> 
										<li class="nav-item comentarios" id="tabcom">
											<a class="nav-link" data-toggle="tab" href="#comentarios">Comentarios</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade show active px-4 py-2" id="datosolicitud" role="tabpanel">
											<form id="form_solicitud">
												<div class="row">
													<div class="col-sm-12">
														<h5 class="col-form-label text-success">Datos de la solicitud</h5>
														<input type="hidden" class="form-control" name="idsolicitud" id="idsolicitud">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Lugar de la solicitud (REGIONAL) <span class="text-red">*</span></label>
														<select name="lugarsolicitud" id="lugarsolicitud" class="form-control mandatorio"></select>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Tipo de discapacidad</label>
														<select id="tipodiscapacidad" class="form-control" name="tipodiscapacidad" autocomplete="off">
															<option value="0">Seleccione</option>												
															<option value="1">FÍSICA</option>
															<option value="2">VISUAL</option>
															<option value="3">AUDITIVA</option>
															<option value="4">MENTAL</option>
															<option value="5">INTELECTUAL</option>
															<option value="6">VISCERAL</option>
														</select>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Tipo de solicitud <span class="text-red">*</span></label>
														<select name="tiposolicitud" id="tiposolicitud" class="form-control mandatorio">
															<option value="0">Seleccione</option> 
															<option value="1">PRIMERA VEZ</option>
															<option value="2">RENOVACIÓN</option>
															<option value="3">DUPLICADO</option> 
														</select>
													</div> 
													<!--<div class="form-group col-12 col-sm-6 col-md-3 estadosolicitud">
														<label class="text-label">Estado <span class="text-red">*</span></label>
														<select name="estadosolicitud" id="estadosolicitud" class="form-control mandatorio"></select>
													</div>-->
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Fecha de la solicitud <span class="text-red">*</span></label>
														<input type="text" name="fecha_sol" id="fecha_sol" class="form-control mandatorio">
													</div>
												</div>
											</form>
											<form id="form_beneficiario">
												<div class="row">
													<div class="col-sm-12">
														<h5 class="col-form-label text-success">Datos del solicitante</h5>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label" id="tipodocumento_txt">Documento de identidad personal <span class="text-red">*</span></label>
														<label>
															<span class="fa fa-eye ver-historial_nrodoc" style="display:none;"></span>
														</label>
														<div class="row">
															<div class="form-group col-12 col-sm-5 pr-0">													
																<input type="hidden" class="form-control" name="idbeneficiario" id="idbeneficiario">
																<input type="hidden" class="form-control" name="tipobeneficiario" id="tipobeneficiario">
																<select lang="es" class="form-control mandatorio" name="tipodocumento" id="tipodocumento" autocomplete="off">	  
																	<option value="0">Seleccione</option>
																	<option value="1">Cédula</option>
																	<option value="2">Carnet migratorio</option>
																</select>
															</div>
															<div class="form-group col-12 col-sm-7 pl-0">
																<input type="text" name="cedula" id="cedula" class="form-control text solotextoynumero">
																<input type="hidden" id="cedulabd" >
															</div>
														</div>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Nombre <span class="text-red">*</span></label>
														<input type="text" name="nombre" id="nombre" class="form-control solotexto mandatorio">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Apellido paterno</label>
														<input type="text" name="apellidopaterno" id="apellidopaterno" class="form-control solotexto">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Apellido materno <span class="text-red">*</span></label>
														<input type="text" name="apellidomaterno" id="apellidomaterno" class="form-control solotexto mandatorio">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Fecha de nacimiento <span class="text-red">*</span></label>
														<input type="text" name="fecha_nac" id="fecha_nac" class="form-control mandatorio">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Edad</label>
														<input type="text" name="edad" id="edad" class="form-control text" disabled="disabled">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Sexo <span class="text-red">*</span></label>
														<select name="sexo" id="sexo" class="form-control mandatorio">
															<option value="0">Seleccione</option>
															<option value="M">Masculino</option>
															<option value="F">Femenino</option>
														</select>
													</div>
												</div>
												<hr class="mt-2 mb-2">
												<!-- Dirección residencial-->
												<div class="row">
													<div class="col-sm-12">
														<h5 class="col-form-label text-success">Dirección residencial</h5>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Provincia <span class="text-red">*</span></label>
														<select class="form-control mandatorio" id="idprovincias" name="idprovincias"></select>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Distrito <span class="text-red">*</span></label>
														<select class="form-control mandatorio" id="iddistritos" name="iddistritos"></select>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Corregimiento <span class="text-red">*</span></label>
														<select class="form-control mandatorio" id="idcorregimientos" name="idcorregimientos"></select>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Urbanización</label>
														<input type="text" name="urbanizacion" id="urbanizacion" class="form-control">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Calle</label>
														<input type="text" name="calle" id="calle" class="form-control">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Apto / Casa Nº</label>
														<input type="text" name="numerocasa" id="numerocasa" class="form-control">
													</div>
                                                    <div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Teléfono celular <span class="text-red">*</span></label>
														<input type="text" name="telefonocelular" id="telefonocelular" class="form-control solonumero mandatorio">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Teléfono otro</label>
														<input type="text" name="telefonootro" id="telefonootro" class="form-control solonumero">
													</div>
                                                    <div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Correo</label>
														<input type="text" name="correo" id="correo" class="form-control soloemail">
													</div>
												</div>
												<hr class="mt-2 mb-2"> 
											</form>
											<form id="form_solicitud_acompanante">
												<div class="row justify-content-start">
													<div class="col-sm-12">
														<h5 class="col-form-label text-success d-inline-block">Datos del acompañante</h5>
														<i id="agregar_acompanante" class="fa fa-plus-circle" data-toggle="tooltip" aria-hidden="true" style="color: #0662ad; font-size: 1.5em; cursor: pointer; margin-left: 5px;" title="Crear acompañante"></i>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-2 ac-input">
														<input type="hidden" class="form-control" name="idacompanante" id="idacompanante">
														<label class="text-label">Requiere acompañante <span class="text-red">*</span></label>
														<select id="requiere_acompanante" class="form-control" name="requiere_acompanante" autocomplete="off">
															<option value="0">Seleccione</option>
															<option value="SI">SÍ</option>
															<option value="NO">NO</option>
														</select>
													</div>  
												</div>
											</form> 
											
										</div>
										<div class="tab-pane fade px-4 py-2" id="datosvehiculo" role="tabpanel">
											<h5 class="col-form-label text-success">Datos del vehículo</h5>
											<div class="row">
												<div class="form-group col-12 col-sm-6 col-md-3">
													<label class="text-label">Característica del vehículo</label>
													<select id="caracteristicavehiculo" class="form-control" name="caracteristicavehiculo" autocomplete="off">
														<option value=0>Seleccione</option>
														<option value=1>Propio</option>
														<option value=2>Familiar</option>
														<option value=3>Colectivo</option>
														<option value=4>Taxi</option>
													</select>
												</div>
												<div class="form-group col-12 col-sm-6 col-md-3">
													<label class="text-label">Adaptado</label>
													<select id="adaptado" class="form-control" name="adaptado" autocomplete="off">
														<option value=0>Seleccione</option>
														<option value=1>Adaptado</option>
														<option value=2>No adaptado</option>
													</select>
												</div>
												<div class="form-group col-12 col-sm-6 col-md-3">
													<label class="text-label">Placa N°</label>
													<input type="text" name="placa" id="placa" class="form-control mandatorio">
												</div>
												<div class="form-group col-12 col-sm-6 col-md-3">
													<label class="text-label">Marca</label>
													<input type="text" name="marca" id="marca" class="form-control mandatorio">
												</div>
												<div class="form-group col-12 col-sm-6 col-md-3">
													<label class="text-label">Modelo</label>
													<input type="text" name="modelo" id="modelo" class="form-control mandatorio">
												</div>
												<div class="form-group col-12 col-sm-6 col-md-3">
													<label class="text-label">N° de motor</label>
													<input type="text" name="nromotor" id="nromotor" class="form-control mandatorio">
												</div>
											</div>
										</div>
										<div class="tab-pane fade px-4 py-2" id="aprobacionpermiso" role="tabpanel">
											<h5 class="col-form-label text-success">Aprobación de permiso</h5>
											<div class="form-group col-12 col-sm-6 col-md-3">
												<label class="text-label">Tiempo de vigencia <span class="text-red">*</span></label>
												<div class="col-sm-6 p-0 d-inline-block">
													<input type="text" name="duracion" id="duracion" class="form-control">
												</div>
												<div class="col-sm-6 p-0 d-inline-block float-right">
													<select class="form-control" id="tipoduracion" name="tipoduracion">
														<option value="0">Seleccione</option>
														<option value="M">Mes(es)</option>
														<option value="A">Año(s)</option>
													</select>
												</div>
											</div>
											<?php if($_SESSION['nivel_sen'] == 1 || $_SESSION['nivel_sen'] == 15 || $_SESSION['nivel_sen'] == 17): ?>
											<div class="row">
												<div class="text-right col-12"> 
													<button type="button" class="btn btn-info btn-xs" onclick="aprobarSolicitud();"><i class="fas fa-check-circle mr-2"></i>Aprobar</button> 														
												</div>  
											</div>
											<?php endif; ?>
										</div>
										<div class="tab-pane fade px-4 py-2" id="comentarios">
										    <form id="formcomentarios">
												<div class="row">
													<div class="col-sm-12">
														<h5 class="col-form-label text-success">Comentarios</h5>
													</div>
													<div class="form-group col-12">
														<label class="text-label">Nuevo Comentario</label>
														<textarea rows="4" class="form-control inc-edit" name="comentario" id="comentario"></textarea>
													</div>
													
													<div class="text-right col-12">
														<button type="button" class="btn btn-warning  text-white btn-xs" onclick="limpiarComentario();"><i class="fas fa-eraser"></i> Limpiar</button>
														<button type="button" class="btn btn-primary btn-xs" onclick="agregarComentario();"><i class="fas fa-check-circle mr-2"></i>Agregar</button> 														
													</div> 
												</div>
												<div class="row">
													<div class="col-12 mt-4">
														<div class="table-responsive">
															<table id="tablacomentario" class="display min-w850 ">
																<thead>
																	<tr>
																		<th>ID</th>
																		<th>Acción</th>
																		<th>Comentario</th>
																		<th>Usuario</th>
																		<th>Fecha</th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table>
														</div>  
													</div>
												</div>
											</form>
										</div>
									</div>
								<?php if($_SESSION['nivel_sen'] == 1 || $_SESSION['nivel_sen'] == 15): ?>
									<div class="col-sm-12 pr-0 text-right mt-3">
										<button type="button" class="btn btn-primary btn-xs" id="guardar-solicitud" onClick="guardar()">
											<i class="fas fa-check-circle mr-2"></i>Guardar
										</button>
									</div> 
								</div>
								<?php endif; ?>
							</div>
                        </div>
					</div>					
				</div>
			</div>
			<?php include "historial_nrodoc.php"; ?>
		</div>
		<!--**********************************
            Content body end
        ***********************************-->
		<?php include "estacionamientoacompanante.php"; ?>

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
	<!-- Solicitud -->
	 <script> var nivelSes = <?php echo $_SESSION['nivel_sen'] ?></script>
    <script src="./js/estacionamiento.js<?php autoVersiones(); ?>"></script>
	<script src="./js/estacionamientoacompanante.js<?php autoVersiones(); ?>"></script>	
</body>

</html>