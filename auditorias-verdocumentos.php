    <div class="modal fade" id="modal-auditorias-documentos" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="card m-0">
                        <div class="card-header card-header-success card-header-icon bg-success-light">
                            <h4>Ver documentos </h4> 
							<button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
						<div class="card-body pb-0 pt-3"> 
							
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12">
									<label class="text-label">Documentos</label>
								</div>
								<div class="col-12 col-sm-12 col-md-12 input-group"> 
									<div class="table-responsive">
										<table id="tabladocumentos" class="display min-w850">
											<thead>
												<tr> 
													<th id="ciddoc">ID</th>
													<th id="ctipodoc">Tipo</th> 
													<th id="cfechadoc">Fecha</th> 
													<th id="cverdoc">Ver</th> 
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div> 
								</div>
							</div> 
							<div class="row">
								<div class="modal-footer w-100 px-2">
									<button type="button" class="btn btn-xs bg-danger text-white" onclick="cerrarModalDocumentos()">
										<i class="fas fa-close mr-2"></i> Cerrar
									</button> 
								</div>								
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>