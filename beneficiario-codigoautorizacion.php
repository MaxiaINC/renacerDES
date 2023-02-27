    <div class="modal fade" id="modal-beneficiario-codigoautorizacion" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card m-0">
                        <div class="card-header card-header-success card-header-icon bg-success-light">
                            <h4 class="card-title">Código de autorización</h4> 
							<button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
						<div class="card-body pb-0 pt-3"> 
							
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12">
									<label class="text-label">Este carnet ha sido impreso anteriormente, puede comunicarse con su supervisor para que suministre el código de autorización para la reimpresión. Recuerde que si desea que salga la palabra "Duplicado" en el carnet, debe seleccionarlo antes de imprimir. <span class="text-red">*</span></label>
								</div>
								<div class="col-12 col-sm-12 col-md-12 input-group">  
									<input type="text" name="codigoautorizacion" id="codigoautorizacion" class="form-control">
								</div>
							</div> 
							<div class="row">
								<div class="modal-footer w-100 px-2">
									<button type="button" class="btn btn-xs bg-success text-white" onclick="validarCodigo()">
										<i class="fas fa-check mr-2"></i> Aceptar
									</button>
									<button type="button" class="btn btn-xs bg-danger text-white" onclick="cancelarCodigo()">
										<i class="fas fa-close mr-2"></i> Cancelar
									</button> 
								</div>								
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>