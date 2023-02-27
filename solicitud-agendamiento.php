<div class="modal fade" id="modalAgendamientoNuevo" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card">
				<div class="card-header card-header-success card-header-icon bg-success-light">
					<h4 class="card-title">Agendamiento</h4> 
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="card-body pb-0 pt-3">
					<div class="row">
						<div class="col-md-8 col-lg-12">
							<div class="media d-sm-flex d-block text-center text-sm-left pb-2 mb-2 border-bottom">
								<img alt="image" class="rounded mt-2 mr-4" width="90" src="images/user-solid.svg" style="filter: invert(48%);">
								<!--<i class="fas fa-user fa-6x pt-2 px-4 text-dark" aria-hidden="true"></i>-->
								<div class="media-body align-items-center">
									<div class="d-sm-flex d-block justify-content-between my-3 my-sm-0">
										<div class="row">
											<div class="col-md-6">
												<p class="m-0 font-w500">Solicitud: <span id="agendamiento-codigo" class="font-w100"></span></p>
												<p class="m-0 font-w500">Expediente: <span id="agendamiento-expediente" class="font-w100"></span></p>
												<p class="m-0 font-w500">Regional: <span id="agendamiento-regional" class="font-w100"></span></p>	
												<p class="m-0 font-w500">Discapacidad: <span id="agendamiento-tipo_discapacidad" class="font-w100"></span></p>
												<!--<p class="m-0 font-w500">Estatus: <span id="agendamiento-estatus" class="font-w100"></span></p>-->
											</div>
											<div class="col-md-6">
												<p class="m-0 font-w500">Usuario: <span id="agendamiento-paciente" class="font-w100"></span></p>													
												<p class="m-0 font-w500">Dirección: <span id="agendamiento-direccion" class="font-w100"></span></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<form id="form_agendamiento">
						<div class="row">								
							<div class="col-12">
								<h6 class="text-success">Datos de la cita</h6>
							</div>
							<div class="col-md-4 col-sm-6 mt-1">
								<label class="control-label" ><span class="text-red">*</span> Fecha de Cita</label>                            
								<input type="text" class="form-control" id="agendamiento-fecha_cita" name="agendamiento-fecha_cita" autocomplete="off" maxlength="20">
							</div>
							<div class="col-md-4 col-sm-6 mt-1">
								<label class="control-label" >Consultorio</label>                            
								<input type="text" class="form-control" id="agendamiento-sala"  name="agendamiento-sala" autocomplete="off" maxlength="20">
							</div> 
							<div class="col-md-4 col-sm-6 mt-1">
								<label class="control-label" >Miembro de Junta</label>                            
								<select class="form-control" id="agendamiento-idespecialidad" name="agendamiento-idespecialidad" autocomplete="off">
								</select>
							</div>
							<div class="col-md-8 mt-3">
								<label class="control-label" >Especialistas</label>
								<select class="form-control" id="agendamiento-idespecialistas" name="agendamiento-idespecialistas"></select>
							</div>
							<div class="col-md-4">
								<label class="control-label text-white d-block">Añadir</label>
								<button type="button" class="btn btn-xs bg-success text-white mt-3" id="anadir_especialista">
									<i class="fas fa-plus-circle"></i>
								</button>
							</div>
							<div class="col-12 my-4">
								<table id="tabla_especialistas" class="display w-100 border">
									<thead class="bg-success-light">
										<th class="text-center font-w500" style="width:10%">Acción</th>
										<th class="font-w500" style="width:50%">Nombre</th>
										<th class="font-w500" style="width:40%">Médico</th>
									</thead>
									<tbody id="tabla_especialistas_cuerpo"></tbody>
								</table>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="modal-footer w-100 px-2">
							<button type="button" class="btn btn-xs bg-success text-white" id="agendamiento-cita">
								<i class="fas fa-check mr-2"></i> Aceptar
							</button>
							<button type="button" class="btn btn-xs bg-danger text-white" id="agendamiento-cancelar" data-dismiss="modal">
								<i class="fas fa-times mr-2"></i> Cancelar
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>