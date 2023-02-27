<div class="modal fade" id="modalFirmas" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="card">
				<div class="card-header card-header-success card-header-icon bg-success-light">
					<h4 class="card-title">Firma</h4> 
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="card-body pb-0 pt-3">
							<div class="row mt-2"> 
								<canvas id="canvas-limpio" name="canvas-limpio" height="300" style="display: none;">
								</canvas> 
								
								<div class="col-xs-12 col-lg-4 col-xl-2 col-md-6"> 
									<label class="control-label">
										<label class="text-right">
											<span class="bg-info text-white fa fa-pencil boton-editar-firma-unidadejecutora" data-id="1" data-toggle="tooltip" data-original-title="Editar" data-placement="right" style="top: -10px; padding: 3px; margin: 5px 2px; border-radius: 4px; cursor: pointer;"></span>
											<span class="bg-warning text-white fa fa-eraser limpiar-firma-unidadejecutora" onclick="javascript:borrarFirma('unidadejecutora');" data-toggle="tooltip" data-original-title="Limpiar " data-placement="right" style="top: -10px; padding: 3px; margin: 5px 2px; border-radius: 4px; cursor: pointer; display: none;"></span>
											<span class="bg-danger text-white fa fa-close cancelareditar-firma-unidadejecutora" data-toggle="tooltip" data-original-title="Cancelar" data-placement="right" style="top: -10px; padding: 3px; margin: 5px 2px; border-radius: 4px; cursor: pointer; display: none;"></span>
										</label>
									</label>
									<canvas id="canvas-unidadejecutora" name="canvas-unidadejecutora" height="300" class="canva_crear canva-firma">
										Este navegador no soporta la lectura de firmas.
									</canvas> 
									<img id="mostrar-firma-unidadejecutora" src="" alt="Firma de Unidad Ejecutora" class="canva_editar canva-firma" style="display: none;"/>
								</div> 
							</div> 
					</div>
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