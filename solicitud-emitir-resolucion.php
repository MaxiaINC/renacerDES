<div class="modal fade" id="modal-resolucion-nuevo" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card">
				<div class="card-header card-header-success card-header-icon bg-success-light">
					<h4 class="card-title">Resolución</h4> 
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="card-body pb-0 pt-3">
					<div class="default-tab">
						<ul class="nav nav-pills review-tab" role="tablist">
							<li class="nav-item active emitirres">
								<a class="nav-link active" data-toggle="tab" href="#emitirres">Emitir resolución</a>
							</li> 
							<li class="nav-item active verres">
								<a class="nav-link" data-toggle="tab" href="#verres">Ver certificados</a>
							</li>
						</ul>
					</div>
					<div class="tab-content" style="border:none;">
						<div class="tab-pane fade show active" id="emitirres" role="tabpanel">
							<div class="row mt-3">
								<input type="hidden" id="idsolicitud_resolucion"> 
								<input type="hidden" id="idupdated"> 
								<div class="col-md-4 col-12">
									<label class="control-label" > Número de resolución</label>
									<input type="text" class="form-control" id="nro_resolucion" name="nro_resolucion" autocomplete="off" maxlength="30" disabled>
								</div>
								<div class="col-md-4 col-12 d-none">
									<label class="control-label" > Expediente</label>
									<input type="text" class="form-control " id="nro_expediente" name="nro_expediente" autocomplete="off" maxlength="30" disabled="disabled" >							
								</div>
								<!--<div class="col-md-4 col-12">
									<label class="control-label" > Validez del certificado</label>                            
									<input type="number" class="form-control" id="validez_certificado" name="validez_certificado" autocomplete="off" maxlength="10">
								</div>
								<div class="col-md-4 col-12">
									<label class="control-label" > Tipo</label>                            
									<select class="form-control" id="validez_tipo" name="validez_tipo">
										<option value="anyos">Años</option>
										<option value="meses">Meses</option>
									</select>
								</div>-->
								<div class="col-12 mt-1">
									<label class="control-label" > Observación</label>                            
									 <textarea class="form-control rounded-0" id="res_observacion" rows="30"  maxlength="2800"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="modal-footer w-100 px-2">
									<?php if($_SESSION['nivel_sen'] != 16): ?>
									<button type="button" class="btn btn-xs bg-success text-white" id="emitir-resolucion">
										<i class="fas fa-check mr-2"></i> Aceptar
									</button>
									<?php endif; ?>
									<?php if($_SESSION['nivel_sen'] == 2 || $_SESSION['nivel_sen'] == 15): ?>
									<button type="button" class="btn btn-xs bg-info text-white" id="aprobarresolucion-legal">
										<i class="fas fa-check mr-2"></i> Aprobar
									</button>
									<?php endif; ?>
									<button type="button" class="btn btn-xs bg-danger text-white" id="resolucion-cancelar" data-dismiss="modal">
										<i class="fas fa-times mr-2"></i> Cancelar
									</button>
									<?php if($_SESSION['nivel_sen'] != 16): ?>
									<button type="button" class="btn btn-xs bg-info text-white" id="emitir-certificado" disabled>
										<i class="fas fa-drivers-license mr-2"></i> Emitir Certificado
									</button>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="verres" role="tabpanel">
							<div class="col-xl-12 mt-3"> 
								<div class="table-responsive">
									<table id="tabla_verresoluciones" class="display min-w850" style="width:100%">
										<thead>
											<tr>
												<th>Id</th>
												<th>Ver documento</th>
												<th>Usuario</th> 
												<th>Fecha</th> 
											</tr>
										</thead>
									</table>
								</div>		 
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>