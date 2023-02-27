<!-- NUEVO PACIENTE	-->
<div class="modal fade" id="modal_historial_nrodoc" role="dialog" style="z-index: 10px">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card">
				<div class="card-header card-header-success card-header-icon">
					<h4 class="card-title">Historial</h4> 
					<button type="button" class="close" data-dismiss="modal">&times;</button>					
				</div> 
				<div class="card-content w-100 mb-2">
					<div class="col-sm-12 mt-3">
						<h5 class="col-form-label text-success d-inline-block">Información de n° de identificación</h5>
					</div>
					<div class="col-xl-12 mt-3"> 
						<div class="table-responsive">
							<table id="tabla_historial_nrodoc" class="display min-w850" style="width:100%">
								<thead>
									<tr>
										<th>Id</th>
										<th>Tipo de documento</th>
										<th>N° de documento</th>
										<th>Fecha de vcto. carnet migratorio</th>
										<th>Fecha de modificación</th> 
									</tr>
								</thead>
							</table>
						</div>		 
					</div>
				</div>
				 <div class="modal-footer"> 
					<button type="button" class="btn btn-danger btn-xs" id="cerrar_historial_nrodoc" data-dismiss="modal">
						Cerrar
					</button>
				</div>
			</div>
		</div>
	</div>
</div>