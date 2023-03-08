<div class="modal fade" id="modal-negatoria-nuevo" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card">
				<div class="card-header card-header-success card-header-icon bg-success-light">
					<h4 class="card-title">Emitir Negatoria</h4> 
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="card-body pb-0 pt-3">
					<div class="row">
						<input type="hidden" id="idsolicitud_negatoria"> 
						<input type="hidden" id="idupdatednegatoria"> 
						<div class="col-md-4 col-12">
							<label class="control-label" > Número de resolución</label>
							<input type="text" class="form-control" id="nro_negatoria" name="nro_negatoria" autocomplete="off" maxlength="30" disabled>
						</div>
						<div class="form-row col-12 mt-3">
							<div class="col-md-4 col-12">
								<label class="control-label" > Fecha de solicitud</label>
								<input type="text" class="form-control" id="fechasol_negatoria" name="fechasol_negatoria" autocomplete="off" maxlength="30">
							</div>
							<div class="col-md-4 col-12">
								<label class="control-label" > Fecha de evaluación</label>
								<input type="text" class="form-control" id="fechaeva_negatoria" name="fechaeva_negatoria" autocomplete="off" maxlength="30">
							</div>
							<div class="col-md-4 col-12">
								<label class="control-label" > Fecha de notifíquese</label>
								<input type="text" class="form-control" id="fechanot_negatoria" name="fechanot_negatoria" autocomplete="off" maxlength="30">
							</div>
						</div>
						<div class="col-md-6 col-6 mt-3">
							<label class="control-label" > Nombre del encargado</label>
							<input type="text" class="form-control" id="nombre_encargado" name="nombre_encargado" autocomplete="off" maxlength="100">
						</div>
						<div class="col-md-6 col-6 mt-3">
							<label class="control-label" > Cargo del encargado</label>
							<input type="text" class="form-control" id="cargo_encargado" name="cargo_encargado" autocomplete="off" maxlength="100">
						</div>
						<div class="col-12 mt-3">
							<label class="control-label" > Evaluación diagnóstica médico tratante</label>                            
							<textarea class="form-control" id="evaluacion_negatoria" rows="5"  maxlength="2800"></textarea>
						</div>
						<div class="col-12 mt-3">
							<label class="control-label" > Primer criterio:</label>                            
							<textarea class="form-control" id="primerc_negatoria" rows="6"  maxlength="2800"></textarea>
						</div>
						<div class="col-12 mt-3">
							<label class="control-label" > Segundo criterio</label>                            
							<textarea class="form-control" id="segundoc_negatoria" rows="6"  maxlength="2800"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="modal-footer w-100 px-2">
							<?php if($_SESSION['nivel_sen'] != 16): ?>
							<button type="button" class="btn btn-xs bg-success text-white" id="aceptar-negatoria">
								<i class="fas fa-check mr-2"></i> Guardar
							</button>
							<?php endif; ?>
							<?php if($_SESSION['nivel_sen'] == 2 || $_SESSION['nivel_sen'] == 15): ?>
							<button type="button" class="btn btn-xs bg-info text-white d-none" id="aprobarnegatoria-legal">
								<i class="fas fa-check mr-2"></i> Aprobar
							</button>
							<?php endif; ?>
							<button type="button" class="btn btn-xs bg-danger text-white" id="negatoria-cancelar" data-dismiss="modal">
								<i class="fas fa-times mr-2"></i> Cancelar
							</button>
							<?php if($_SESSION['nivel_sen'] != 16): ?>
							<button type="button" class="btn btn-xs bg-info text-white" id="emitir-negatoria" disabled>
								<i class="fas fa-drivers-license mr-2"></i> Emitir negatoria
							</button>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>