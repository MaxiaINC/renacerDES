<div class="row">
	<div class="col-sm-12">
		<h5 class="col-form-label text-success">CODIFICACIÓN CIF - <strong><span id="titulo_discapacidad"></span></strong></h5>
	</div>
	<div class="col-sm-4 col-md-3" >
		<label class="text-label">Componente</label>
		<select class="form-control" id="select_categoria_cif" name="select_categoria_cif">
			<option value="0">Seleccione</option>
			<option value="b">Funciones corporales</option>
			<option value="s">Estructuras corporales</option>
			<option value="d">Actividad y participación (Discapacidad - Desventaja)</option>
			<option value="e">Factores ambientales</option>
		</select>
	</div>
	<div class="col-sm-4 col-md-3">
		<label class="text-label">Capítulo</label>
		<select class="form-control" id="select_grupo_cif" name="select_grupo_cif"></select>
	</div>	
	<div class="col-sm-4 col-md-3">
		<label class="text-label">Codificación CIF</label>
		<select class="form-control" id="select_cif" name="select_cif"></select>
	</div>
	<div class="col-sm-4 col-md-3 text-left">		
		<h4 class="text-white">.</h4>
		<button type="button" class="btn btn-primary btn-xs" id="anadir_cif">
			<i class="fa fa-plus-circle" aria-hidden="true"></i>
		</button>
		<h4 id="mensaje_elemento_agregado" class="d-inline-block ml-2" style="opacity: 0;" ><i class="fas fa-check-circle text-success"></i></i></h4>
	</div>
</div>
<div class="my-4">
	<div class="default-tab">
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item w-150 text-center">
				<a class="nav-link active" data-toggle="tab" href="#tablaTemporalCIF_b">Funciones corporales</a>
			</li>
			<li class="nav-item w-150 text-center">
				<a class="nav-link" data-toggle="tab" href="#tablaTemporalCIF_s">Estructuras corporales</a>
			</li>
			<li class="nav-item w-230 text-center">
				<a class="nav-link" data-toggle="tab" href="#tablaTemporalCIF_d">Actividad y participación (Discapacidad - Desventaja)</a>
			</li>
			<li class="nav-item w-150 text-center">
				<a class="nav-link" data-toggle="tab" href="#tablaTemporalCIF_e">Factores ambientales</a>
			</li>
			<li class="nav-item text-center">
				<a class="nav-link h-eval" data-toggle="tab" href="#tablaCalcular">Evaluación</a>
			</li>
			<li class="nav-item w-230 text-center">
				<a class="nav-link" data-toggle="tab" href="#formSolicitudEstudiosComplementarios">Solicitud de estudios complementarios</a>
			</li>
			<!--<li class="nav-item w-230 text-center">
				<a class="nav-link" data-toggle="tab" href="#formatos">Generar documentos</a>
			</li>-->
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active show" id="tablaTemporalCIF_b" role="tabpanel">
				
			</div>
			<div class="tab-pane fade" id="tablaTemporalCIF_s">
				
			</div>
			<div class="tab-pane fade" id="tablaTemporalCIF_d">
				
			</div>
			<div class="tab-pane fade" id="tablaTemporalCIF_e">
				
			</div>
			<div class="tab-pane fade" id="tablaCalcular">
				<div class="pt-4">
					<div class="col-12 text-center">
						<button type="button" id="evaluacion-calcular" class="btn btn-success btn-xs">
							<i class="fas fa-calculator mr-2"></i>Calcular
						</button>
						<button type="button" id="reiniciar-calculo" class="btn btn-info btn-xs">
							<i class="fas fa-undo mr-2"></i>Reiniciar calculo
						</button>
						<div class="col-12 py-4 my-3 text-left bg-success-light resultadoFormula" id="resultado_CalcularFormula"></div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="formSolicitudEstudiosComplementarios">
				<div class="col-12">
					<label class="text-label mt-3">Deberá concurrir con:</label>
					<textarea class="form-control" id="concurrircon" name="concurrircon"></textarea>
				</div>
				<div class="col-12">
					<label class="text-label mt-3">Estudios complementarios</label>
					<textarea class="form-control" id="estudioscomplementarios" name="estudioscomplementarios"></textarea>
				</div>
				<div class="form-group label-floating is-empty text-right mt-3 mr-3 boton-estado-pendiente">
					<button type="button" class="btn btn-primary" onClick="guardarEstudiosComplementarios()">
						<i class="fas fa-check-circle mr-2" aria-hidden="true"></i>Pendiente
					</button>											
				</div>
			</div>
			<!--<div class="tab-pane fade" id="formatos">
				<div class="col-12">
					<button type="button" class="btn btn-danger" onClick="generarFormatoNoAsistio()">
						<i class="fas fa-file-pdf mr-2" aria-hidden="true"></i>Generar formato No Asistió
					</button>
					<button type="button" class="btn btn-danger" onClick="generarFormatoAsistenciaSolo()">
						<i class="fas fa-file-pdf mr-2" aria-hidden="true"></i>Generar formato Constancia de Asistencia a Evaluación (Solo)
					</button>
					<button type="button" class="btn btn-danger" onClick="generarFormatoAsistenciaAcompanante()">
						<i class="fas fa-file-pdf mr-2" aria-hidden="true"></i>Constancia en Compañía de
					</button>
				</div>
			</div>-->
		</div>
	</div>
</div>