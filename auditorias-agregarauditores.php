    <div class="modal fade" id="modal-auditorias-auditores" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card m-0">
                        <div class="card-header card-header-success card-header-icon bg-success-light">
                            <h4>Actualizar auditores </h4> 
							<button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
						<div class="card-body pb-0 pt-3"> 
							
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12">
									<label class="text-label">Auditores <span class="text-red">*</span></label>
								</div>
								<div class="col-12 col-sm-12 col-md-12 input-group"> 
									<input type="hidden" name="idauditoriasauditores" id="idauditoriasauditores">
									<select name="idauditores_editar" id="idauditores_editar" class="form-control" multiple> 
									</select>
								</div>
							</div> 
							<div class="row">
								<div class="modal-footer w-100 px-2">
									<button type="button" class="btn btn-xs bg-success text-white" onclick="agregarAuditores()">
										<i class="fas fa-check mr-2"></i> Guardar
									</button>
									<button type="button" class="btn btn-xs bg-danger text-white" onclick="cerrarModalAuditores()">
										<i class="fas fa-close mr-2"></i> Cancelar
									</button> 
								</div>								
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>