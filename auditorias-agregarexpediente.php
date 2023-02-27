    <div class="modal fade" id="modal-auditorias-solicitud" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="card m-0">
                        <div class="card-header card-header-success card-header-icon bg-success-light">
                            <h4 class="card-title">Agregar expediente </h4> 
							<button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
						<div class="card-body pb-0 pt-3"> 
							
							<div class="row mt-3"> 
								<div class="col-md-6 col-12">
									<label class="control-label" >Expediente N° <span class="text-red">*</span></label>
									<input type="text" class="form-control" id="expediente" name="expediente" autocomplete="off">
								</div>
								<div class="col-md-6 col-12 mt-2">
								</div>
								<div class="col-md-6 col-12 mt-2">
									<label class="control-label" >Cédula </label>
									<input type="text" class="form-control" id="cedula" name="cedula" autocomplete="off" disabled>
								</div>
								<div class="col-md-6 col-12 mt-2">
									<label class="control-label" >Nombre </label>
									<input type="text" class="form-control" id="nombre" name="nombre" autocomplete="off" disabled>
								</div>
								<!-- <div class="col-md-6 col-12">
									<label class="control-label" >Regional <span class="text-red">*</span></label>
									<select id="idregionales">
									</select>
								</div> -->
								<div class="col-md-12 col-12 mt-2"> 
									<label class="control-label" >Auditores <span class="text-red">*</span></label>
									<select name="idauditores" id="idauditores" class="form-control" multiple> 
									</select>
								</div>
							</div>
							<div class="row">
								<div class="modal-footer w-100 px-2">
									<button type="button" class="btn btn-xs bg-success text-white" onclick="agregarExpediente()">
										<i class="fas fa-check mr-2"></i> Agregar
									</button>
									<button type="button" class="btn btn-xs bg-danger text-white" onclick="cerrarModalExpediente()">
										<i class="fas fa-close mr-2"></i> Cancelar
									</button> 
								</div>								
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>