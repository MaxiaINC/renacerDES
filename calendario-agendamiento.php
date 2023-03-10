    <div class="modal fade" id="modal-agendamiento-calendario" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header card-header-success card-header-icon bg-success-light">
                            <h4 class="card-title">Agendamiento</h4> 
							<button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
						<div class="card-body pb-0 pt-3">
							<input type="hidden" id="agendamiento-idpaciente">
							<input type="hidden" id="agendamiento-idevaluacion">
							<input type="hidden" id="agendamiento-idregional">
							
							<div class="row">
								<div class="col-md-8 col-lg-12">
									<div class="media d-sm-flex d-block text-center text-sm-left pb-2 mb-2 border-bottom">
										<img alt="image" class="rounded mt-2 mr-4" width="100" src="images/user-solid.svg" style="filter: invert(48%);">
										<!--<i class="fas fa-user fa-6x pt-2 px-4 text-dark" aria-hidden="true"></i>-->
										<div class="media-body align-items-center">
											<div class="d-sm-flex d-block justify-content-between my-3 my-sm-0">
												<div>
													<h3 class="fs-18 text-black font-w600 mb-0" id="agendamiento-paciente"></h3>
													<p class="m-0">Solicitud: <span id="agendamiento-codigo"></span></p>
													<p class="m-0">Regional: <span id="agendamiento-regional"></span></p>
													<p class="m-0">Discapacidad: <span id="agendamiento-tipo_discapacidad"></span></p>
													<p class="m-0">Fecha de cita: <span id="agendamiento-fecha_cita"></span></p>
													<p class="m-0">Sala: <span id="agendamiento-sala"></span></p>
													<p class="m-0">Estatus: <span id="agendamiento-estatus"></span></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="card">
								<div class="card-header border-0 pb-0 px-3">
									<h2 class="card-title text-success">Junta evaluadora</h2>
									<button type="button" class="btn btn-primary light fa fa-pen editar_juntaevaluadora" span class="caret m-l-5"></span></button>
								</div>
								<div class="card-body py-0 px-3">
									<ul class="list-group list-group-flush" id="juntaevaluadora">
										<li class="list-group-item d-flex px-0 justify-content-between">
											<strong>Medico</strong>
											<span class="mb-0">Especialidad</span>
										</li>
									</ul>
									<div id="juntaevaluadora_editar" class="row" style="display:none">
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
										<div class="modal-footer w-100 px-2">
											<button type="button" class="btn btn-xs bg-success text-white" id="agendamiento-cita">
												<i class="fas fa-check mr-2"></i> Aceptar
											</button>
											<button type="button" class="btn btn-xs bg-danger text-white" id="agendamiento-cancelar">
												<i class="fas fa-times mr-2"></i> Cancelar
											</button>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="modal-footer w-100 px-2">
									<div class="box-reagendar w-100 p-4 mb-4 d-none bg-success-light">
										<label class="control-label font-w500" >Fecha para reagendar</label>                            
										<input type="text" class="form-control mandatorio" id="agendamiento-fecha_reagenda" name="agendamiento-fecha_reagenda" autocomplete="off" maxlength="20" style="width: 85%;display: inline-block;">
										<button type="button" class="btn btn-xs bg-success text-white" id="reagendar-aceptar">
										<i class="fas fa-check"></i>
									</button>
									</div>
									<!--<button type="button" class="btn btn-xs bg-success text-white d-none" id="agendamiento-reagendar">
										<i class="fas fa-calendar-alt mr-2"></i> Reagendar
									</button>-->
									<button type="button" class="btn btn-xs bg-danger text-white d-none" id="agendamiento-cancelarcita">
										<i class="fas fa-calendar-times mr-2"></i> Cancelar cita
									</button>
									<button type="button" class="btn btn-xs bg-success text-white" id="agendamiento-aceptar">
										<i class="fas fa-arrow-circle-right mr-2"></i> Evaluación
									</button>
									<!--<button type="button" class="btn btn-primary text-white btn-xs" onClick="exportarH()">
										<i class="fas fa-file-excel mr-2"></i> Historial
									</button>-->
									<!-- <button type="button" class="btn btn-xs bg-danger text-white" id="agendamiento-cancelar" data-dismiss="modal">Cerrar</button> -->
								</div>								
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>