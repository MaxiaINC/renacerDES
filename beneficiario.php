<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora('Beneficiarios', 'Agregar / Editar beneficiario', 0, '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Beneficiario - Senadis</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<!-- Select2 -->
	<link href="./vendor/select2/css/select2.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<!--Cropper-->
	<link rel="stylesheet" href="./css/cropper/cropper.css">
    <link rel="stylesheet" href="./css/cropper/cropperindex.css">		   
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
	<link href="./css/ajustes.css<?php autoVersiones(); ?>"" rel="stylesheet">
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
                                Beneficiario
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
                        <button type="button" class="btn btn-primary btn-xs" id="listadoBeneficiarios">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Beneficiarios</span>
						</button> 
						<button type="button" class="btn btn-primary btn-xs" id="listadoSolicitudes">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Solicitudes</span>
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
											<a class="nav-link active" data-toggle="tab" href="#datos">Beneficiario</a>
										</li> 
										<li class="nav-item li_carnet">
											<a class="nav-link" data-toggle="tab" href="#carnet">Carnet</a>
										</li> 
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade show active px-4 py-2" id="datos" role="tabpanel">
											<form id="form_beneficiario">
												<div class="row">
													<div class="col align-self-start"></div>
													
													<div class="col align-self-end"></div>
													<div class="col-sm-12">
														<h5 class="col-form-label text-success">Datos personales</h5>
													</div>
													<div class="form-group col-12 col-sm-3 col-md-3 mb-0"> 
														<div class="mb-1 text-center">
															<div class="group">
																<img src="images/default-avatar.png" alt="" class="crop-image" id="crop-image">
																<input type="file" name="input-file" id="input-file" accept=".png,.jpg,.jpeg">
																<label for="input-file" class="label-file">Haz click aquí para subir una imagen</label>
															</div>
														</div> 
													</div>
													<div class="col-12 col-sm-9 col-md-9 m-0">
														<div class="row">
															<div class="col-12 col-sm-6 col-md-4">
																<label class="text-label" id="tipodocumento_txt">Documento de identidad personal 
																	<span class="text-red">*</span>
																</label>
																<label>
																	<span class="fa fa-eye ver-historial_nrodoc" style="display:none;"></span>
																</label>
																<div class="row">
																	<div class="form-group col-12 col-sm-5 pr-0">													
																		<input type="hidden" class="form-control" name="idbeneficiario" id="idbeneficiario">
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
														
															<div class="col-12 col-sm-6 col-md-4">
																<label class="text-label">Nombre <span class="text-red">*</span></label>
																<input type="text" name="nombre" id="nombre" class="form-control solotexto mandatorio">
															</div>
															<div class="col-12 col-sm-6 col-md-4">
																<label class="text-label">Apellido paterno</label>
																<input type="text" name="apellidopaterno" id="apellidopaterno" class="form-control solotexto">
															</div>
															<div class="col-12 col-sm-6 col-md-4">
																<label class="text-label">Apellido materno <span class="text-red">*</span></label>
																<input type="text" name="apellidomaterno" id="apellidomaterno" class="form-control solotexto mandatorio">
															</div>
															<div class="col-12 col-sm-6 col-md-4 box-expediente">
																<label class="text-label">Expediente</label>
																<input type="text" name="expediente" id="expediente" class="form-control solonumero" disabled="disabled">
															</div>
															<div class="col-12 col-sm-6 col-md-4">
																<label class="text-label">Nacionalidad <span class="text-red">*</span></label>
																<select name="nacionalidad" id="nacionalidad" class="form-control mandatorio"></select> 
															</div>
														</div>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Correo</label>
														<input type="text" name="correo" id="correo" class="form-control soloemail">
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
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Estado civil <span class="text-red">*</span></label>
														<select name="estado_civil" id="estado_civil" class="form-control mandatorio">
															<option value="0">Seleccione</option>
															<option value="1">Soltero/a</option>
															<option value="2">Casado/a</option>
															<option value="3">Divorciado/a</option>
															<option value="4">Viudo/a</option>
															<option value="5">Unido/a</option>
														</select>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Status </label>
														<select name="status" id="status" class="form-control">
															<option value="0">Seleccione</option>
															<option value="1">Jubilado</option>
															<option value="2">Pensionado / Por Invalidez</option>
															<option value="3">Pensionado / Por Vejez</option>
															<option value="4">Sin Beneficio</option> 
														</select>
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3 vcto_cm d-none">
														<label class="text-label">Fecha de vencimiento de Carnet Migratorio <span class="text-red">*</span></label>
														<input type="text" name="fecha_vcto_cm" id="fecha_vcto_cm" class="form-control mandatorio">
														<input type="hidden" name="fecha_vcto_cm_bd" id="fecha_vcto_cm_bd">
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
														
														<label class="text-label mt-3">Corregimiento <span class="text-red">*</span></label>
														<select class="form-control mandatorio" id="idcorregimientos" name="idcorregimientos"></select>
														
														<label class="text-label mt-3">Urbanización</label>
														<input type="text" name="urbanizacion" id="urbanizacion" class="form-control">
														
														<label class="text-label mt-3">Edificio</label>
														<input type="text" name="edificio" id="edificio" class="form-control">
														
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
													
														<label class="text-label">Distrito <span class="text-red">*</span></label>
														<select class="form-control mandatorio" id="iddistritos" name="iddistritos"></select>
														
														<label class="text-label mt-3">Área <span class="text-red">*</span></label>
														<input type="text" name="area" id="area" class="form-control mandatorio" disabled="disabled">
														
														<label class="text-label mt-3">Calle</label>
														<input type="text" name="calle" id="calle" class="form-control">
														
														<label class="text-label mt-3">Apto / Casa Nº</label>
														<input type="text" name="numerocasa" id="numerocasa" class="form-control">
														
													</div>
													
													<div class="col-12 col-sm-6">
														<h5 class="col-form-label text-success text-left float-left mr-1 p-0">Ubicación</h5><h5 class="col-form-label text-danger infoubicacion p-0"></h5>
														<div class="col-12 col-sm-12 pl-0 pr-0 mb-2 mt-2">
															<input id="pac-input" class="controls mb-2 pl-3 buscador-googlemaps" type="text" placeholder="Buscar">
														</div>
														<input id="latitud" name="latitud" type="hidden">
														<input id="longitud" name="longitud" type="hidden">
														<div id="map_canvas" style="width: 100%; height: 250px;"></div>
														<!--<input type="text" class="form-control mt-3" name="direccionmapa" id="direccionmapa" autocomplete="off">-->
													</div>
													
													<!--<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Corregimiento <span class="text-red">*</span></label>
														<select class="form-control mandatorio" id="idcorregimientos" name="idcorregimientos"></select>
													</div>-->
													<!--<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Área <span class="text-red">*</span></label>
														<input type="text" name="area" id="area" class="form-control mandatorio" disabled="disabled">
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
														<label class="text-label">Edificio</label>
														<input type="text" name="edificio" id="edificio" class="form-control">
													</div>
													<div class="form-group col-12 col-sm-6 col-md-3">
														<label class="text-label">Apto / Casa Nº</label>
														<input type="text" name="numerocasa" id="numerocasa" class="form-control">
													</div>-->
												</div>
												<hr class="mt-2 mb-2">
												<!-- Otros -->
												<div class="row">
													<div class="col-sm-12">
														<h5 class="col-form-label text-success">Otros</h5>
													</div>
													<div class="form-group col-12 col-sm-6">
														<label class="text-label">Condición de actividad <span class="text-red">*</span></label>
														<select class="form-control mandatorio" id="condicion_actividad" name="condicion_actividad">
															<option value="0">Seleccione</option>
															<option value="1">Trabaja</option>
															<option value="2">No trabaja</option>
															<option value="3">Busca trabajo</option>
															<option value="4">No busca trabajo</option>
															<option value="5">No aplicable</option>
														</select>
													</div>
													<div class="form-group col-12 col-sm-6">
														<label class="text-label">Categoría de la actividad</label>
														<select class="form-control mandatorio" id="categoria_actividad" name="categoria_actividad">
															<option value="0">Seleccione</option>
															<option value="1">Obrero o empleado</option>
															<option value="2">Patrón (con personal a cargo)</option>
															<option value="3">Trabajador por cuenta propia</option>
															<option value="4">Trabajador de empresa privada</option>
															<option value="5">Servidor Público</option>
														</select>
													</div>
													<div class="form-group col-12 col-sm-6">
														<label class="text-label">Cobertura médica <span class="text-red">*</span></label>
														<select class="form-control mandatorio" multiple id="cobertura_medica" name="cobertura_medica">
															<option value="0">Seleccione</option>
															<option value="1">Seguro social</option>
															<option value="2">Seguro privado</option>
															<option value="3">Ninguno</option>
														</select>
													</div>
													<div class="form-group col-12 col-sm-6">
														<label class="text-label">Recibe beneficio de programas sociales del estado <span class="text-red">*</span></label>
														<select class="form-control mandatorio" id="beneficios" name="beneficios">
															<option value="0">Seleccione</option>
															<option value="1">Sí</option>
															<option value="2">No</option>
														</select>
													</div>
													<div class="col-12" id="beneficios_descripcion_box" style="display: none;">
														<div class="form-group label-floating">
															<label class="text-label">Indique cuales <span class="text-red">*</span></label>
															<textarea class="form-control" rows="4" id="beneficios_descripcion" name="beneficios_descripcion"></textarea>
														</div>
													</div> 
													
													<div class="col-12 col-sm-6 text-center imgqr"></div>
													 
												</div>
												<div class="col-sm-12 pr-0 text-right">
													<button type="button" class="btn btn-primary btn-xs" id="guardar">
														<i class="fas fa-check-circle mr-2"></i>Guardar
													</button>
												</div>
											</form>
										</div>
										
										<div class="tab-pane fade px-4 py-2" id="carnet" role="tabpanel">
											<div class="advertencias"></div>
											
											<div class="carnet-beneficiario row justify-content-center" id="carnet-beneficiario">
												
												<div class="certificado d-none" style="border: 1px solid #ccc;">
													<div class="row m-0">
														<div class="col-md-4 p-0 text-center" >
															<img src="images/senadis2.png" class="" width="80">
														</div>
														<div class="col-md-8" >
															<div class="font-w700 d-block enc-carnet text-center color-negro">REPÚBLICA DE PANAMÁ</div>
															<div class="font-w700 text-info d-block enc-carnet title-second text-center">CERTIFICADO DE DISCAPACIDAD</div>
														</div>
														<div class="col-md-12 px-2 mt-1" >
															<img src="images/iconos-carnet.png" width="85" class="iconos-certificado">
															<div class="font-w700 fs-6 color-negro">Secretaria Nacional de Discapacidad</div>
														</div> 
														<div class="col-md-12 p-0 pt-1" >
															<div class="font-w700 fs-10 color-negro txt_nombre"></div>
														</div> 
														<div class="col-md-4 mt-1 p-0" >
															<img class="mt-0 d-inline border-radius foto-certificado imgfoto_beneficiario" alt="" style="height:105%">
														</div>
														<div class="col-md-5 fs-10 mt-3 pl-0 pr-0">
															<div class="font-w700 d-block mb-2 color-negro">C.I.P. <span class="txt_cedula"></span></div>
															<div class="font-w700 d-block color-negro">NACIONALIDAD</div>
															<div class="font-w700 text-uppercase d-block mb-2 color-negro txt_nacionalidad">
															</div>
															<div class="font-w700 d-block color-negro">FECHA DE NACIMIENTO</div>
															<div class="font-w700 text-uppercase d-block color-negro txt_fechanacimiento"></div>
														</div>
														<div class="col-md-3 fs-10 mt-3 pl-0 pr-0 text-center">
															<div class="font-w700 d-block mb-2 text-white">.</div>
															<div class="font-w700 d-block color-negro">EXPEDICIÓN</div>
															<div class="font-w700 text-uppercase mb-2 color-negro txt_expedicion">
															</div>
															<div class="font-w700 d-block" style="color: #ff4541">EXPIRACIÓN</div>
															<div class="font-w700 text-uppercase color-negro txt_expiracion">
															</div>
														</div>
													</div>
												</div>
												<div class="certificado d-none" style="border: 1px solid #ccc;">
													<div class="row m-0">
														<div class="col-md-12 text-center">
															<img src="images/senadis2.png" alt="" class="pl-26" width="100">
														</div>
														<div class="col-md-12 text-center"> 
															<div class="font-w700 fs-10 color-negro">Secretaria Nacional de Discapacidad</div>
														</div>
														<div class="col-md-8 col-sm-8 fs-10 mt-4 text-center">  
															<img class="imgfirma_directorgeneral" width="100">
															<div class="font-w700 d-block text-center mb-1 color-negro txt_directorgeneral" ></div>
															
															<!--<div class="font-w700 d-block mt-3 text-center mb-4 txt-directorgeneral" >Director(a) General</div>-->
															<img class="imgfirma_directornacional" width="100">
															<div class="font-w700 d-block pt-2 text-center color-negro txt_directornacional" style="width: 20em;"></div>
															
															<!--<div class="font-w700 d-block pt-2 text-center txt-directornacional">Dir. Nacional de Certificaciones a.i.</div>-->
														</div>
														<div class="col-md-4 col-sm-4 fs-10 p-0 pt-2 text-right">
															<img class="imgqr_beneficiario" width="115">
														</div>
														<div class="col-md-12 mt-2 text-center" >
															<div class="font-w700 fs-10 color-negro">www.senadis.gob.pa</div>
														</div>
													</div>
												</div>
												
												<div class="col-sm-12 pr-0 text-right d-none boton-imprimir">
													<div class="checkdupli mr-2" style="margin-right: 6% !important; display: inline;">
														<input id="marcarimpreso" class="marcarimpreso" type="checkbox">&nbsp;&nbsp;<label class="text_marcarimpreso"></label>
														<input type="hidden" id="fechaemisionhidden">
														<input type="hidden" id="fechavencimientohidden">
													</div>
													<div class="checkdupli mr-2" style="margin-right: 6% !important; display: inline;">
														<input class="duplicado" type="checkbox" value="duplicado" name="countries[]" />&nbsp;&nbsp;<label>Duplicado</label>
													</div>
													<button type="button" class="btn btn-info btn-xs" id="imprimir">
														<i class="fas fa-print mr-2"></i>Imprimir
													</button>
												</div>
											</div>
											<div class="col-12 mt-3 pl-0 tit-listado-carnet d-none">
												<h6 class="text-success">Historial de solicitudes de impresiones de carnet</h6>
											</div>
											<div class="listado-carnet"></div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
					</div>
					
				</div>
			</div>
			<?php include "historial_nrodoc.php"; ?>
			<?php include "beneficiario-codigoautorizacion.php"; ?>
			<?php include "beneficiario-cropper.php"; ?>
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
	<!-- Cropper -->
	<script src="./js/jsQR/jsQR.js"></script>
	<script src="./js/cropper/cropper.js"></script>
	<!-- Beneficiario -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyC037nleP4v84LrVNzb4a0fn33Ji37zC18"></script>
    <script> var nivelSes = <?php echo $_SESSION['nivel_sen'] ?></script>	
	<script src="./js/beneficiario.js<?php autoVersiones(); ?>"></script>	
	<script src="./js/beneficiario-cropper.js<?php autoVersiones(); ?>"></script>
	<script>
	window.onload = function(){  

		var url = document.location.toString();
		if (url.match('#')) {
			//$('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
			$("a[href$='#carnet']").click();
		}

		//Change hash for page-reload
		/* $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').on('shown', function (e) {
			window.location.hash = e.target.hash;
		});  */
	} 
</script>
</body>

</html>