<!-- NUEVO PACIENTE	-->
<div class="modal fade" id="modal-nuevoacompanante" role="dialog" style="z-index: 10px">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="card">
				<div class="card-header card-header-success card-header-icon">
					<h4 class="card-title">Nuevo Acompañante</h4> 
					<button type="button" class="close" data-dismiss="modal">&times;</button>					
				</div>
				<input type="hidden" class="form-control" name="modal-nuevoacompanante-idacompanante" id="modal-nuevoacompanante-idacompanante">
				<input type="hidden" class="form-control" name="modal-nuevoacompanante-iddireccion" id="modal-nuevoacompanante-iddireccion">
				<div class="card-body py-2">
					<form id="form_acompanante">
						<div class="row">						
							<div class="col-sm-12">
								<h5 class="col-form-label text-success d-inline-block">Datos personales</h5>
							</div>
							<div class="form-group col-12 col-sm-6 col-md-6">
								<label class="text-label" id="tipodocumento_txt_ac">Documento de identidad personal <span class="text-red">*</span></label>
								<div class="row">
									<div class="form-group col-12 col-sm-5 pr-0">
										<input type="hidden" class="form-control" name="idacompanante_ac" id="idacompanante_ac">
										<select lang="es" class="form-control mandatorio" name="tipodocumento_ac" id="tipodocumento_ac" autocomplete="off">	  
											<option value="0">Seleccione</option>
											<option value="1">Cédula</option>
											<option value="2">Carnet migratorio</option>
										</select>
									</div>
									<div class="form-group col-12 col-sm-7 pl-0">
										<input type="text" name="cedula_ac" id="cedula_ac" class="form-control text solotextoynumero">
									</div>
								</div>
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Nombre <span class="text-red">*</span></label>
								<input type="text" name="nombre_ac" id="nombre_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Apellido <span class="text-red">*</span></label>
								<input type="text" name="apellido_ac" id="apellido_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Teléfono celular <span class="text-red">*</span></label>
								<input type="text" name="celular_ac" id="celular_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Teléfono</label>
								<input type="text" name="telefono_ac" id="telefono_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Correo</label>
								<input type="text" name="correo_ac" id="correo_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Fecha de nacimiento <span class="text-red">*</span></label>
								<input type="text" name="fecha_nac_ac" id="fecha_nac_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Nacionalidad <span class="text-red">*</span></label>
								<input type="text" name="nacionalidad_ac" id="nacionalidad_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Sexo <span class="text-red">*</span></label>
								<select name="sexo_ac" id="sexo_ac" class="form-control mandatorio">
									<option value="0">Seleccione</option>
									<option value="M">Masculino</option>
									<option value="F">Femenino</option>
								</select>
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Estado civil</label>
								<select name="estado_civil_ac" id="estado_civil_ac" class="form-control mandatorio">
									<option value="0">Seleccione</option>
									<option value="1">Soltero/a</option>
									<option value="2">Casado/a</option>
									<option value="3">Divorciado/a</option>
									<option value="4">Viudo/a</option>
									<option value="5">Unido/a</option>
								</select>
							</div>							
							<div class="col-sm-12">
								<h5 class="col-form-label text-success d-inline-block">Dirección residencial</h5>
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Provincia <span class="text-red">*</span></label>
								<select class="form-control mandatorio" id="idprovincias_ac" name="idprovincias_ac"></select>
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Distrito <span class="text-red">*</span></label>
								<select class="form-control mandatorio" id="iddistritos_ac" name="iddistritos_ac"></select>
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Corregimiento <span class="text-red">*</span></label>
								<select class="form-control mandatorio" id="idcorregimientos_ac" name="idcorregimientos_ac"></select>
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Área <span class="text-red">*</span></label>
								<input type="text" name="area_ac" id="area_ac" class="form-control mandatorio" disabled="disabled">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Urbanización</label>
								<input type="text" name="urbanizacion_ac" id="urbanizacion_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Calle</label>
								<input type="text" name="calle_ac" id="calle_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Edificio</label>
								<input type="text" name="edificio_ac" id="edificio_ac" class="form-control">
							</div>
							<div class="form-group col-12 col-sm-6 col-md-3">
								<label class="text-label">Apto / Casa Nº</label>
								<input type="text" name="numero_ac" id="numero_ac" class="form-control">
							</div>
							<div id="modal-nuevoacompanante-div_tutor" style="display:none;">
								<div class="row px-3">
									<div class="col-sm-12">
										<h5 class="col-form-label text-success d-inline-block">Datos adicionales</h5>
									</div>
									<div class="form-group col-12 col-sm-6 col-md-3">
										<label class="text-label">Tipo de tutor o curador</label>
										<select name="tipotutor_ac" id="tipotutor_ac" class="form-control mandatorio">
											<option value="0">Seleccione</option>
											<option value="1">Provicional</option>
											<option value="2">Definitivo</option>
										</select>
									</div>
									<div class="form-group col-12 col-sm-6 col-md-3">
										<label class="text-label">Nro. Sentencia</label>
										<input type="text" name="sentencia_ac" id="sentencia_ac" class="form-control">
									</div>
									<div class="form-group col-12 col-sm-6 col-md-3">
										<label class="text-label">Juzgado</label>
										<input type="text" name="juzgado_ac" id="juzgado_ac" class="form-control">
									</div>
									<div class="form-group col-12 col-sm-6 col-md-3">
										<label class="text-label">Circuito Judicial</label>
										<input type="text" name="circuito_judicial_ac" id="circuito_judicial_ac" class="form-control">
									</div>
									<div class="form-group col-12 col-sm-6 col-md-3">
										<label class="text-label">Distrito Judicial</label>
										<input type="text" name="distrito_judicial_ac" id="distrito_judicial_ac" class="form-control">
									</div>
								</div>
							</div>
						</div>
					</form>					
					<!-- Modal Footer -->
					<div class="modal-footer px-0">
						<button type="button" class="btn btn-primary btn-xs" id="modal-nuevoacompanante-guardar">
							<i class="fas fa-check-circle mr-2"></i>Guardar
						</button>
						<button type="button" class="btn btn-danger btn-xs" id="modal-nuevoacompanante-cancelar" data-dismiss="modal">
							<i class="fas fa-ban mr-2"></i>Cancelar
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>