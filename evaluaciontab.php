<?php
	include_once("controller/funciones.php");
	include_once("controller/conexion.php");
	verificarLogin();
	$nombre = $_SESSION['nombreUsu_sen'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	bitacora('Beneficiarios', 'Agregar / Editar beneficiario', 0, '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Evaluación - Senadis</title>
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
                                Evaluación
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
								<form id="form_beneficiario">
									<div id="accordion-evaluacion" class="accordion accordion-rounded-stylish accordion-bordered accordion-active-header">
										<div class="accordion__item">
											<div class="accordion__header collapsed px-4 py-2" data-toggle="collapse" data-target="#generales">
												<span class="accordion__header--icon"></span>
												<span class="accordion__header--text">Generales</span>
												<span class="accordion__header--indicator"></span>
											</div>
											<div id="generales" class="collapse accordion__body" data-parent="#accordion-evaluacion">
												<div class="accordion__body--text">
													<div class="row">
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Tipo de discapacidad <span class="text-red">*</span></label>
															<select class="form-control" id="iddiscapacidades" name="iddiscapacidades"></select>
														</div>
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Tipo de solicitud <span class="text-red">*</span></label>
															<select id="tipo_solicitud" class="form-control" name="tipo_solicitud" autocomplete="off">
																<option value="0">Seleccione</option>
																<option value="1">PRIMERA VEZ</option>
																<option value="2">RENOVACIÓN</option>
																<option value="3">REEVALUACIÓN</option>
																<option value="4">RECONSIDERACIÓN</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Hora de inicio</label>
															<input type="text" name="hora_inicio" id="hora_inicio" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Hora Final</label>
															<input type="text" name="hora_final" id="hora_final" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Fecha de iniciación del daño <span class="text-red">*</span></label>
															<input type="text" name="iniciacion_dano" id="iniciacion_dano" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6 col-md-9">
															<label class="text-label">Ayudas técnicas</label>
															<select id="ayudas_tecnicas" class="form-control" name="ayudas_tecnicas" autocomplete="off" multiple>		
																<option value="Silla de ruedas">Silla de ruedas</option>
																<option value="Órtesis">Órtesis</option>
																<option value="Andadores">Andadores</option>
																<option value="Prótesis">Prótesis</option>
																<option value="Bastones">Bastones</option>
																<option value="Ayudas ópticas">Ayudas ópticas</option>
																<option value="Audífonos">Audífonos</option>
																<option value="Otros">Otros</option>
															</select>
														</div>
														<div class="form-group col-12">
															<label class="text-label d-block">Documentos</label>
															
															<input type="checkbox" id="certificadomedico" name="certificadomedico" value="1" class="chk_tiposolicitud">
															<span class="checkbox-material"></span>
															<span class="font-w500 ml-1 mr-5">Certificado médico</span>
															
															<input type="checkbox" id="resumnen_h_clinica" name="resumnen_h_clinica" value="2" class="chk_tiposolicitud">
															<span class="checkbox-material"></span>
															<span class="font-w500 ml-1 mr-5">Resumen historia clínica</span>
															
															<input type="checkbox" id="hist_clinica" name="hist_clinica" value="3" class="chk_tiposolicitud">
															<span class="checkbox-material"></span>
															<span class="font-w500 ml-1 mr-5">Historia clínica</span>
															
															<input type="checkbox" id="est_complementarios" name="est_complementarios" value="4" class="chk_tiposolicitud">
															<span class="checkbox-material"></span>
															<span class="font-w500 ml-1 mr-5">Estudios complementarios</span>
														</div>
														<div class="form-group col-12">
															<label class="text-label">Diagnóstico(s) <span class="text-red">*</span></label>
															<select class="form-control" name="iddiagnosticos" id="iddiagnosticos" autocomplete="off" style="width: 50% !important"></select>
															<button type="button" class="btn btn-xs bg-success text-white" id="anadir_diagnostico">Añadir</button>
															<div class="table-responsive">
																<table id="tabla_diagnosticos" class="display">
																	<thead>
																		<th>Acción</th>
																		<th>Diagnóstico</th>
																		<th>Código CIE-10 / DSM - IV</th>
																	</thead>
																	<tbody id="diagnosticos_cuerpo"></tbody>
																</table>
																</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="accordion__item">
											<div class="accordion__header collapsed px-4 py-2" data-toggle="collapse" data-target="#datospaciente">
												<span class="accordion__header--icon"></span>
												<span class="accordion__header--text">Datos del paciente</span>
												<span class="accordion__header--indicator"></span>
											</div>
											<div id="datospaciente" class="collapse accordion__body" data-parent="#accordion-evaluacion">
												<div class="accordion__body--text">
													<div class="row">
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Nombre <span class="text-red">*</span></label>
															<input type="text" name="nombre" id="nombre" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Apellido <span class="text-red">*</span></label>
															<input type="text" name="apellido" id="apellido" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Fecha de nacimiento <span class="text-red">*</span></label>
															<select class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"></select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Edad <span class="text-red">*</span></label>
															<input type="text" name="edad" id="edad" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Documento de identidad personal <span class="text-red">*</span></label>
															<input type="text" name="cedula" id="cedula" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Código de junta evaluadora <span class="text-red">*</span></label>
															<input type="text" name="nro_junta" id="nro_junta" class="form-control">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="accordion__item">
											<div class="accordion__header collapsed px-4 py-2" data-toggle="collapse" data-target="#educacion">
												<span class="accordion__header--icon"></span>
												<span class="accordion__header--text">Educación</span>
												<span class="accordion__header--indicator"></span>
											</div>
											<div id="educacion" class="collapse accordion__body" data-parent="#accordion-evaluacion">
												<div class="accordion__body--text">
													<div class="row">
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Alfabetismo <span class="text-red">*</span></label>
															<select class="form-control" id="alfabetismo" name="alfabetismo">
																<option value="0">Seleccione</option>
																<option value="1">Alfabetizado</option>
																<option value="2">Analfabeto</option>
																<option value="3">Analfabeto instrumental</option>
																<option value="4">No aplicable</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Nivel de educación</label>
															<select class="form-control" id="nivel_educacional" name="nivel_educacional">
																<option value="0">Seleccione</option>
																<option value="1">Inicial</option>
																<option value="2">Primario</option>
																<option value="3">Secundario</option>
																<option value="4">Terciario / Universiatio</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Nivel Completado</label>
															<select class="form-control" multiple id="nivel_completado" name="nivel_completado">
																<option value="0">Seleccione</option>
																<option value="1">Completo</option>
																<option value="2">Incompleto (Concurre)</option>
																<option value="3">Incompleto (Concurrió hasta que nivel esc.)</option>
																<option value="4">Adecuaciones curriculares</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Adecuación curricular</label>
															<input type="text" name="adecuacion" id="adecuacion" class="form-control">
														</div>
														<div class="col-12 d-none" id="nivel_educacional">
															<div class="form-group label-floating">
																<label class="text-label">Especifique el nivel <span class="text-red">*</span></label>
																<textarea class="form-control" rows="4" id="nivel_educacional_completado" name="nivel_educacional_completado"></textarea>
															</div>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Concurrencia (nivel completado)</label>
															<select class="form-control" id="concurrencia_educacional" name="concurrencia_educacional">
																<option value="0">Seleccione</option>
																<option value="A">Educación antes del daño</option>
																<option value="D">Educación después del daño</option>
																<option value="AD">Educación antes y después del daño</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Tipo de educación</label>
															<select class="form-control" id="tipo_educacion" name="tipo_educacion">
																<option value="0">Seleccione</option>
																<option value="1">Educación no formal</option>
																<option value="2">Escuela especial</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Concurrencia (Tipo de eduación)</label>
															<select class="form-control" id="concurrencia_tipo_educacion" name="concurrencia_tipo_educacion">
																<option value="0">Seleccione</option>
																<option value="1">Concurre</option>
																<option value="2">Concurrió</option>
																<option value="3">Nunca concurrió</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="accordion__item">
											<div class="accordion__header collapsed px-4 py-2" data-toggle="collapse" data-target="#habitacional">
												<span class="accordion__header--icon"></span>
												<span class="accordion__header--text">Aspecto habitacional</span>
												<span class="accordion__header--indicator"></span>
											</div>
											<div id="habitacional" class="collapse accordion__body" data-parent="#accordion-evaluacion">
												<div class="accordion__body--text">
													<div class="row">
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Convivencia <span class="text-red">*</span></label>
															<select class="form-control" id="convivencia" name="convivencia">
																<option value="0">Seleccione</option>
																<option value="1">Vive solo</option>
																<option value="2">Vive acompañado</option>
																<option value="3">Internado / Albergue</option>	
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Vivienda con infraestructura básica (servicios) <span class="text-red">*</span></label>
															<select class="form-control" id="tipo_vivienda" name="tipo_vivienda">
																<option value="0">Seleccione</option>
																<option value="1">SI</option>
																<option value="2">NO</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Vivienda adaptada a la situación de la persona con discapacidad <span class="text-red">*</span></label>
															<select class="form-control" id="vivienda_adaptada" name="vivienda_adaptada">
																<option value="0">Seleccione</option>
																<option value="1">SI</option>
																<option value="2">NO</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Cantidad de cuartos en la vivienda <span class="text-red">*</span></label>
															<input type="text" name="cantidadhabitaciones" id="cantidadhabitaciones" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Medios de transporte <span class="text-red">*</span></label>
															<select class="form-control" id="medio_de_transporte" name="medio_de_transporte">
																<option value="0">Seleccione</option>
																<option value="1">SI</option>
																<option value="2">NO</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Estados de las calles <span class="text-red">*</span></label>
															<select class="form-control" id="medio_de_transporte" name="medio_de_transporte">
																<option value="0">Seleccione</option>
																<option value="1">Asfaltado o pavimento</option>
																<option value="2">Mejorado</option>
																<option value="3">Tierra</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="accordion__item">
											<div class="accordion__header collapsed px-4 py-2" data-toggle="collapse" data-target="#familiar">
												<span class="accordion__header--icon"></span>
												<span class="accordion__header--text">Situación socio - familiar</span>
												<span class="accordion__header--indicator"></span>
											</div>
											<div id="familiar" class="collapse accordion__body" data-parent="#accordion-evaluacion">
												<div class="accordion__body--text">
													<div class="row">
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Vinculo <span class="text-red">*</span></label>
															<select class="form-control" id="vinculo" name="vinculo">
																<option value="0">Seleccione</option>
																<option value="1">Hijo</option>
																<option value="2">Madre</option>
																<option value="3">Hermano</option>
																<option value="4">Cónyuge</option>
																<option value="5">Padre</option>
																<option value="6">Abuelo</option>
																<option value="7">Otro familiar</option>
																<option value="8">Otro no familiar</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Etnia</label>
															<input type="text" name="etnia" id="etnia" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Religión</label>
															<input type="text" name="religion" id="religion" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Ingreso Mensual <span class="text-red">*</span></label>
															<select class="form-control" id="ingreso_mensual" name="ingreso_mensual">
																<option value="MENOS DE 100">MENOS DE 100</option>
																<option value="100 a 124">100 a 124</option>
																<option value="125 a 174">125 a 174</option>
																<option value="175 a 249">175 a 249</option>
																<option value="250 a 399">250 a 399</option>
																<option value="400 a 599">400 a 599</option>
																<option value="600 a 799">600 a 799</option>
																<option value="800 a 999">800 a 999</option>
																<option value="1000 a 1499">1000 a 1499</option>
																<option value="1500 a 1999">1500 a 1999</option>
																<option value="2000 a 2499">2000 a 2499</option>
																<option value="2500 a 2999">2500 a 2999</option>
																<option value="3000 a 3999">3000 a 3999</option>
																<option value="4000 a 4999">4000 a 4999</option>
																<option value="5000 y MÁS">5000 y MÁS</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Total</label>
															<input type="text" name="salario_total" id="salario_total" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Acompañante durante la evaluación <span class="text-red">*</span></label>
															<select class="form-control" id="acompante" name="acompante">
																<option value="0">Seleccione</option>
																<option value="1">SI</option>
																<option value="2">NO</option>
															</select>
														</div>
														<div class="form-group col-12 col-sm-6">
															<label class="text-label">Total</label>
															<input type="text" name="datos_acompanante" id="datos_acompanante" class="form-control" disabled="disabled">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="accordion__item">
											<div class="accordion__header collapsed px-4 py-2" data-toggle="collapse" data-target="#evaluacion">
												<span class="accordion__header--icon"></span>
												<span class="accordion__header--text">Evaluación</span>
												<span class="accordion__header--indicator"></span>
											</div>
											<div id="evaluacion" class="collapse accordion__body" data-parent="#accordion-evaluacion">
												<div class="accordion__body--text">
													<div class="row">
														<div class="form-group col-12">
															<?php include "evaluacion-div_tabla_cif.php" //TABLA DE EVALUACIONES CIF ?>	
														</div>
														<div class="form-group col-12">
															<label class="text-label">Observaciones y/o Recomendaciones</label>
															<textarea class="form-control" rows="4" id="observaciones" name="observaciones"></textarea>
														</div>
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Validez por un periodo de</label>
															<input type="text" name="cantidad_vencimiento" id="cantidad_vencimiento" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Fecha de vencimiento</label>
															<input type="text" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Fecha de emisión</label>
															<input type="text" name="fecha_emision" id="fecha_emision" class="form-control">
														</div>
														<div class="form-group col-12 col-sm-6 col-md-3">
															<label class="text-label">Lugar</label>
															<input type="text" name="ciudad_emision" id="ciudad_emision" class="form-control">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-12 pr-0 text-right">
										<button type="button" class="btn btn-primary btn-xs" id="guardar">
											<i class="fas fa-check-circle mr-2"></i>Guardar
										</button>
										<button type="button" class="btn btn-danger btn-xs" id="cancelar">
											<i class="fas fa-ban mr-2"></i>Cancelar
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
	<!--Sweetalert2-->
	<script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <!-- Beneficiario -->
    <script src="./js/beneficiarios.js<?php autoVersiones(); ?>"></script>	
</body>

</html>