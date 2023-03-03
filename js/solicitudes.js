/*-ARREGLO-DE-OPCIONES-SELECT-*/
let selectValue = [];
let state={id:"",nombreProyecto:"",nombreCliente:"",dropbox:""}
var especialistas  = "";
var cantidad_especialistas = 0;
var especialistas_editar  = "";
var cantidad_especialistas_editar = 0;
var ultimoespecialista = 0;

//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	//$("#tablasolicitudes").DataTable().search('').draw();
	//$('#tablasolicitudes_wrapper thead input').val('').change();
	tablasolicitudes.state.clear();
	window.location.reload();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablasolicitudes.ajax.reload();
    ajustarTablas();
});
//LOADER
param = 0;
function loader(param, fin){
	if(param == fin){
		$('#overlay').css('display','none');
		param = 0;
	}
}

especialidades(0);
function especialidades(id){
    $.get("controller/combosback.php?oper=especialidades", {}, function(result)
    {
        $("#agendamiento-idespecialidad").empty();
        $("#agendamiento-idespecialidad").append(result);
        if (id != 0){
			console.log('idespecialidad: '+id);
            $("#agendamiento-idespecialidad").val(id).trigger('change');
        }
    });
}
$("#agendamiento-idespecialidad").on('select2:select', function(e) {
	var id = 0;
	var especialidad = $(this).val();
	especialistasm(id, especialidad);
});

especialistasm(0, '');
function especialistasm(id, especialidad){
    $.get("controller/combosback.php?oper=especialistas", { especialidad: especialidad }, function(result)
    {
        $("#agendamiento-idespecialistas").empty();
        $("#agendamiento-idespecialistas").append(result);
        if (id != 0){
			//console.log('idespecialistas: '+id);
            $("#agendamiento-idespecialistas").val(id).trigger('change');
        }
    });
}

$("#agendamiento-fecha_cita").bootstrapMaterialDatePicker({
	weekStart:0,
	format : 'YYYY-MM-DD HH:mm',
	switchOnClick:true, 
	time:true,
	// minDate:d
});	

//HEADER
$('#tablasolicitudes thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'ID'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 60px" autocomplete="nope" /> ' );
			}else if(title == 'Expediente'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 90px" autocomplete="nope" /> ' );
			}else if(title == 'Cédula'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 115px" autocomplete="nope" /> ' );
			}else if(title == 'Nombre'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" autocomplete="nope" /> ' );
			}else if(title == 'Fecha de la solicitud'  || title == 'Fecha de cita'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" autocomplete="nope" /> ' );
			}else if(title == 'Regional'  || title == 'Estado'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" autocomplete="nope" /> ' );
			}else if(title == 'Observaciones de estados'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 190px" autocomplete="nope" /> ' );
			}else if(title == 'Condición de salud'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" autocomplete="nope" /> ' );
			}else if(title == 'Discapacidad'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 110px" autocomplete="nope" /> ' );
			}			
		}else{
			$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
		}
	}else if(title == 'Acciones'){
		var ancho = '50px';
	}
	$(this).width(ancho);
});

//TABLA
var tablasolicitudes = $("#tablasolicitudes").DataTable( {
    scrollY: '100%',
	scrollX: true,
	scrollCollapse: true,
	destroy: true,
	ordering: false,
	processing: true,
	autoWidth : false,
	stateSave: true,
	searching: true,
	//pageLength: 50,
	//lengthChange: false,
	serverSide: true,
	serverMethod: 'post',
	/*-ACCEDIENDO-AL_LOCALSTORE_PARA_RECUPERAR_VALORES-------------------*/
	stateLoadParams: function (settings, data) {			
		const{columns}=data
		$('th#cnrosolicitud input').val(columns[1].search.search);
		$('th#cexpediente input').val(columns[2].search.search);
		$('th#ccedula input').val(columns[3].search.search);
		$('th#cpaciente input').val(columns[4].search.search);
		$('th#cfecha_solicitud input').val(columns[5].search.search);
		$('th#cfecha_cita input').val(columns[6].search.search);
		$('th#cregional input').val(columns[7].search.search);
		$('th#cestatus input').val(columns[8].search.search);
		$('th#cdiscapacidad input').val(columns[9].search.search);
		$('th#cobservaciones input').val(columns[10].search.search);
		$('th#ccondicionsalud input').val(columns[11].search.search);		
	},
    ajax: {
        url: "controller/solicitudesback.php?oper=cargar"
    },
    columns: [
        { 	"data": "acciones" },				//0
		{ 	"data": "id" },						//1
		{ 	"data": "expediente" },				//2
		{ 	"data": "cedula" },					//3	
		{ 	"data": "paciente" },				//4		
		{ 	"data": "fecha_solicitud" },		//5		
		{ 	"data": "fecha_cita" },				//6
		{ 	"data": "regional" },				//7
		{ 	"data": "estatus" },				//8
		{ 	"data": "discapacidad" },			//9
		{ 	"data": "observacionesestados" }, 	//10
		{ 	"data": "condicionsalud" },			//11
		{ 	"data": "auditoria" }				//12
    ],
    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
    columnDefs: [//OCULTAR LA COLUMNA id, Observaciones 
        {
			"targets"	: [ 1,12 ],
			"visible"	:  false,
			"searchable": false
		},{
			"targets"	: [ 2, 3, 5, 6, 7, 8, 9 ],
			"className"	:  'text-center'
		}
    ],
	language:
    {
        url: "js/Spanish.json",
    },
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    drawCallback: function( settings ) 
    {
		ajustarTablas();
    },
    initComplete: function(){
		$('#preloader').css('display','none');
		var t = 0;
		this.api().columns().every( function () {
			var column = this;
		});
		/*
		//APLICAR BUSQUEDA POR COLUMNAS
        this.api().columns().every( function (){
            var that = this; 
            $( 'input', this.header() ).on( 'keyup change clear', function (valor)
            {
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
            } );
        });
		*/
		//OCULTAR LOADER
		param++;
		loader(param, '1');
		cargarDropdownMenu();
    },
	rowCallback: function( row, data) {
		if(data['auditoria'] == 1){
			$('td', row).css('background-color', '#e0fbed');
			$('td', row).css('color', '#5b636e');
		}
	},
	dom: '<"toolbarU toolbarDT">Blfrtip'
});/*fin tabla*/

$('#tablasolicitudes').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})
tablasolicitudes.columns().every( function () {
	var that = this;
	$( 'input', this.header() ).keypress(function (event) {
		if (this.value!='A11|') {
			if ( event.which == 13 ) {
				if ( that.search() !== this.value ) {
					that.search( this.value ).draw();
				}
			}
		}
	});	
});

//EDITAR
$("#tablasolicitudes tbody").on('dblclick','tr',function(){
	var idsolicitud = $(this).attr('id');
	console.log('idsolicitud: '+idsolicitud);
	if(idsolicitud != undefined ){
		var coo = '';
		var name = "nivel=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				coo = c.substring(name.length, c.length);
			}
		}
		console.log('coo: '+coo);
		//if(coo == 1 || coo == 6 ||coo == 9 || coo == 11 ){
			location.href='solicitud.php?idsolicitud='+idsolicitud;	
		//}
	}
});

const peticionExcel = (archivo) =>{
	$('.chatbox').removeClass('active');
	$.ajax({
		type:'POST',
		url:`reporte/${archivo}`,
		data: {},
		dataType:'json',
		beforeSend: function() {
			$('#preloader').css('display', 'block');
		},
	}).done(function(data){
		
		var $a = $("<a>");
		$a.attr("href",data.file);
		$("body").append($a);
		$a.attr("download",data.name);
		$a[0].click();
		$a.remove(); 
		$('#preloader').css('display', 'none');
	});
}

// AL CARGARSE LA TABLA
tablasolicitudes.on('draw.dt', function(e){
    //BOTÓN RECONSIDERACIÓN
    $(".boton-reconsideracion").each(function(){
		$(this).on('click',function(){
			let id = $(this).attr('data-id');
			let tipo = 'reconsideracion';
			cambiarEstado(id,tipo);
		});
	});
	//BOTÓN APELACIÓN
    $(".boton-apelacion").each(function(){
		$(this).on('click',function(){
			let id = $(this).attr('data-id');
			let tipo = 'apelacion';
			cambiarEstado(id,tipo);
		});
	});
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $(".boton-eliminar").each(function(){
		$(this).on('click',function(){
			var id = $(this).attr('data-id');
			var nombre = $(this).parent().parent().parent().next().next().next().html();
			eliminarSolicitud(id,nombre);
		});
	});
	//BOTON APROBAR
	$(".boton-aprobar").each(function(){
		$(this).on('click',function(){
			var id = $(this).attr('data-id');
			var nombre = $(this).parent().parent().next().html();
			aprobar(id,nombre);
		});
	});
	//BOTON ADJUNTOS
	 $('.boton-adjuntos').each(function(){
		var id = $(this).attr("data-id");
		$(this).on( 'click', function() {
			abrirsolicitudes(id);
		});
	});
	//BOTON IR AL PACIENTE
	$(".boton-paciente").each(function(){
		$(this).on('click',function(){
			var id = $(this).attr('data-id');
			$.get('controller/solicitudesback.php?oper=get_pacienteporsolicitud&id='+id,function(response){
				id = response.id;
				location.href ='beneficiario.php?id='+id+"&m=1";
			},'json');
		});
	});
	//BOTON IR A LA EVALUACIÓN
	$(".boton-evaluacion").each(function(){
		$(this).on('click',function(){
			var idevaluacion = $(this).attr('data-id');
			var idsolicitud = $(this).attr('data-idsolicitud');
			var idpaciente = $(this).attr('data-idpaciente');
			location.href ='evaluacion.php?idpaciente='+idpaciente+'&idsolicitud='+idsolicitud+'&idevaluacion='+idevaluacion;
		});
	});
	//BOTON IMPRIMIR CARNET
	$(".boton-carnet").each(function(){
		$(this).on('click',function(){ 
			var idpaciente = $(this).attr('data-idpaciente'); 
			window.open(`beneficiario.php?id=${idpaciente}#carnet`, '_self'); 
		});
	});
	//BOTON IMPRIMIR SOLICITUD
	$(".boton-imprimir").each(function(){
		$(this).on('click',function(){
			var id = $(this).attr('data-id');
			window.open('reporte/imprimirsolicitud.php?id='+id, '_blank'); 
		});
	});
	//BOTON HISTORIAL
	$(".boton-historial").each(function(){
		$(this).on('click',function(){
			var id = $(this).attr('data-id'); 
			peticionExcel(`historialsolicitud.php?idsolicitud=${id}`);
		});
	});
	//BOTON EMITIR CERTIFICADO
	$(".boton-emitir-certificado").each(function(){
		$(this).on('click',function(){
			var id = $(this).attr('data-id'); 
			var iddiscapacidad = $(this).attr('data-iddiscapacidad'); 
			
			$("#modal-resolucion-nuevo").modal('show');
			$("#idsolicitud_resolucion").val(id);
			jQuery.ajax({
				url: `controller/solicitudesback.php?oper=get_resolucion&idsolicitud=${id}&iddiscapacidad=${iddiscapacidad}`,
				dataType: "json",
				beforeSend: function(){
				   $('#overlay').css('display','block');
				},success: function(item) { 
					
					if(item.nro_resolucion == '' || item.nro_resolucion == undefined){
						jQuery.ajax({
							url: "controller/solicitudesback.php?oper=asignarCodigoResolucion&tipo=res&idsolicitud="+id,
							dataType: "json",
							success: function(resp) {
								$('#nro_resolucion').val(resp);
							}
						});
					}else{
						$('#nro_resolucion').val(item.nro_resolucion);
					}
					$('#overlay').css('display','none'); 
					$('#idupdated').val(item.idsolicitud);
					$('#nro_expediente').val(item.nro_expediente);
					$('#validez_certificado').val(item.validez_certificado);
					$('#validez_tipo').val(item.validez_tipo).trigger('change');
					console.log('observaci//on',item.observacion);
					//Texto de fundamento legal de la resolución, según tipo de discapacidad
					if(item.observacion == '' || item.observacion == null || item.observacion == undefined){
						if(item.tieneresolucion = 0){
							$('#res_observacion').val(item.observacion);			
						} 
					}else{
						$('#res_observacion').val(item.observacion);	
					}
					
					if(item != "0"){
						$("#emitir-certificado").prop('disabled', false);
						$("#emitir-certificado").css('background-color', '#4A89DC');
						$("#emitir-certificado").css('border', 'solid 2px #4A89DC'); 
					}else{
						$("#emitir-certificado").prop('disabled', true);
						$("#emitir-certificado").css('background-color', '#cacaca');
						$("#emitir-certificado").css('border', 'solid 2px #cacaca');
					}
				}
			});
			
			$('#tabla_verresoluciones thead th').each( function (){
				var title = $(this).text();
				var id = $(this).attr('id');
				var ancho = $(this).width();
				if ( title !== ''){
					if (screen.width > 1024){
						if(title == 'Ver documento' || title == 'Usuario'){
							$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 250px" /> ' );
						}else if(title == 'Fecha'){
							$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" /> ' );
						}			
					}else{
						$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
					}
				}
				$(this).width(ancho);
			});
			
			tabla_verresoluciones = $("#tabla_verresoluciones").DataTable({
				destroy: true,
				ordering: false,
				searching: false, 
				"ajax"		: {
					"url"	: "controller/solicitudesback.php?oper=listarCertificados&id="+id,
				},
				"columns"	: [
					{ 	"data": "id" },
					{ 	"data": "archivo" },
					{ 	"data": "usuario" },
					{ 	"data": "fecha" }
					],
				"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
				"columnDefs": [ //OCULTAR LA COLUMNA ID
					{
						"targets"	: [ 0 ],
						"visible"	: false,
						"searchable": false
					}
				],
				"language": {
					"url": "js/Spanish.json"
				}
			});
		});
	});
	//BOTON NEGATORIA
	$(".boton-negatoria").each(function(){
		$(this).on('click',function(){
			var id = $(this).attr('data-id'); 
			$("#modal-negatoria-nuevo").modal('show');
			$("#idsolicitud_negatoria").val(id);
			jQuery.ajax({
				url: "controller/solicitudesback.php?oper=get_negatoria&idsolicitud="+id,
				dataType: "json",
				beforeSend: function(){
				   $('#overlay').css('display','block');
				},success: function(item) { 
					$('#overlay').css('display','none'); 
					$('#idupdatednegatoria').val(item.idsolicitud);
					
					if(item.nro_resolucion == '' || item.nro_resolucion == undefined && item.id == ''){
						jQuery.ajax({
							url: "controller/solicitudesback.php?oper=asignarCodigoResolucion&tipo=neg&idsolicitud="+id,
							dataType: "json",
							success: function(resp) {
								$('#nro_negatoria').val(resp);
							}
						});
					}else{
						$('#nro_negatoria').val(item.nro_resolucion);
						$("#aprobarnegatoria-legal").removeClass('d-none');
					}
					
					$('#evaluacion_negatoria').val(item.evaluacion);
					$('#primerc_negatoria').val(item.primerc);
					$('#segundoc_negatoria').val(item.segundoc);
					$('#fechasol_negatoria').val(item.fecha_solicitud);
					$('#fechaeva_negatoria').val(item.fecha_evaluacion);
					$('#fechanot_negatoria').val(item.fecha_notifiquese);
					$('#nombre_encargado').val(item.nombre_encargado);
					$('#cargo_encargado').val(item.cargo_encargado);
					
					if(item != "0"){
						$("#emitir-negatoria").prop('disabled', false);
						$("#emitir-negatoria").css('background-color', '#4A89DC');
						$("#emitir-negatoria").css('border', 'solid 2px #4A89DC'); 
					}else{
						$("#emitir-negatoria").prop('disabled', true);
						$("#emitir-negatoria").css('background-color', '#cacaca');
						$("#emitir-negatoria").css('border', 'solid 2px #cacaca');
					}
				}
			});
			
		});
	});
	//BOTON AGENDAR
	$(".boton-agendar").each(function(){
		$(this).on( 'click', function() {
			var idregional = $(this).attr('data-idregional');
			var reg = $(this).closest('tr');
			var row = tablasolicitudes.row(reg).data();
			var datosJson = row;
			var id = $(this).attr('data-id'); 
			$.get('controller/solicitudesback.php?oper=get_solicitud&idsolicitud='+id,function(response){
				$("#modalAgendamientoNuevo").modal('show');
				$("#agendamiento-tipo_discapacidad").html(response.discapacidad);
				$("#agendamiento-paciente").html(response.paciente);
				$("#agendamiento-fecha_cita").val(response.fecha);
				$("#agendamiento-regional").html(response.regional);
				$("#agendamiento-codigo").html(response.id);
				$("#agendamiento-expediente").html(response.expediente);
				$("#agendamiento-idregional").val(idregional);
				$("#agendamiento-direccion").html(response.direccion);
				$("#agendamiento-estatus").html(response.estatus);
				$("#agendamiento-sala").val(response.sala);
				
				var respmed = response.medicos;
				if(respmed != null){
					var medicos = response.medicos.split(',');
					console.log('1');
				}else{
					var medicos = response.medicos;
					console.log('2');
				}				
				$("#tabla_especialistas_cuerpo").html('');
				especialistas  = "";
				ultimoespecialista = 0;
				cantidad_especialistas = 0;
				$.map(medicos,function(medico){
					if(medico !== undefined){
						cantidad_especialistas_ = cantidad_especialistas +1;
						var idespecialista =  medico.split('|')[0];
						var nombre = medico.split('|')[1];
						var especialidad =  medico.split('|')[2];;
						especialistas += idespecialista+',';
						var html_especialista='<tr data-id="'+ultimoespecialista+'">\
													<td class="text-center">\
														<span  onclick="quitar_especialista('+ultimoespecialista+','+idespecialista+');" data-id="'+ultimoespecialista+'" class="fa fa-minus-circle" data-toggle="tooltip" aria-hidden="true" style="color:#FF0000;font-size:1.5em;cursor:pointer" title="Quitar especialista"></span>\
													</td>\
													<td>'+nombre+'</td>\
													<td>'+especialidad+'</td>\
												</tr>';
						ultimoespecialista	= ultimoespecialista + 1;
						$("#tabla_especialistas_cuerpo").append(html_especialista);
					}
				});
				especialistas = especialistas.replace(/^( *, *)+|(, *(?=,|$))+/g, '');
				console.log('especialistas: '+especialistas);
			},'json');
		});
	});
	//BOTON EDITAR AGENDA
	$(".boton-agendar-editar").each(function(){
		$(this).on( 'click', function() {
			var idregional = $(this).attr('data-idregional');
			var reg = $(this).closest('tr');
			var row = tablasolicitudes.row(reg).data();
			var datosJson = row;	
			$.get('controller/solicitudesback.php?oper=get_solicitud&idsolicitud='+datosJson.id,function(response){
				$("#modalAgendamientoNuevo").modal('show');
				$("#agendamiento-tipo_discapacidad").html(response.discapacidad);
				$("#agendamiento-paciente").html(datosJson.paciente);
				$("#agendamiento-fecha_cita").val(datosJson.fecha_cita);
				$("#agendamiento-regional").html(datosJson.regional);
				$("#agendamiento-codigo").html(datosJson.id);
				$("#agendamiento-expediente").html(datosJson.expediente);
				$("#agendamiento-idregional").val(idregional);
				$("#agendamiento-direccion").html(response.direccion);
				$("#agendamiento-estatus").html(response.estatus);
				$("#agendamiento-sala").val(response.sala);
				
				var respmed = response.medicos;
				if(respmed != null){
					var medicos = response.medicos.split(',');
					console.log('1');
				}else{
					var medicos = response.medicos;
					console.log('2');
				}
				
				$("#tabla_especialistas_cuerpo").html('');
				especialistas  = "";
				ultimoespecialista = 0;
				cantidad_especialistas = 0;
				$.map(medicos,function(medico){
					if(medico !== undefined){
						cantidad_especialistas_ = cantidad_especialistas +1;
						var idespecialista =  medico.split('|')[0];
						var nombre = medico.split('|')[1];
						var especialidad =  medico.split('|')[2];;
						especialistas += idespecialista+',';
						var html_especialista='<tr data-id="'+ultimoespecialista+'">\
													<td class="text-center">\
														<span  onclick="quitar_especialista('+ultimoespecialista+','+idespecialista+');" data-id="'+ultimoespecialista+'" class="fa fa-minus-circle" data-toggle="tooltip" aria-hidden="true" style="color:#FF0000;font-size:1.5em;cursor:pointer" title="Quitar especialista"></span>\
													</td>\
													<td>'+nombre+'</td>\
													<td>'+especialidad+'</td>\
												</tr>';
						ultimoespecialista	= ultimoespecialista + 1;
						$("#tabla_especialistas_cuerpo").append(html_especialista);
					}
				});
				especialistas = especialistas.replace(/^( *, *)+|(, *(?=,|$))+/g, '');
				console.log('especialistas editar: '+especialistas);
			},'json');
		});
	});
});

$("#anadir_especialista").on('click',function(){
	if($("#agendamiento-idespecialistas").val() !== undefined && $("#agendamiento-idespecialistas").val() != 0){
		if(cantidad_especialistas <= 3){
			cantidad_especialistas = cantidad_especialistas +1;
			var id = $("#agendamiento-idespecialistas").val();	
			var especialista = $("#agendamiento-idespecialistas").select2('data')[0].element.text;
			var especialidad = $("#agendamiento-idespecialistas").select2('data')[0].element.dataset.especialidad;

			if(especialistas.search(','+id+',') == -1){
				ultimoespecialista	= ultimoespecialista+1;
				var html_especialista='<tr data-id="'+ultimoespecialista+'">\
												<td class="text-center">\
													<span onclick="quitar_especialista('+ultimoespecialista+','+id+');" data-id="'+ultimoespecialista+'" class="fa fa-minus-circle" data-toggle="tooltip" aria-hidden="true" style="color:#FF0000;font-size:1.5em;cursor:pointer;" title="Quitar especialista"></span>\
												</td>\
												<td>'+especialista+'</td>\
												<td>'+especialidad+'</td>\
											</tr>';
				$("#tabla_especialistas_cuerpo").append(html_especialista);
				especialistas = especialistas + "," + id + ",";
				console.log('especialistas añadir: '+especialistas);				
			}else{
				swal('ERROR','El Especialista <strong>'+especialista+'</strong> ya se encuentra seleccionado','error');
			}			
		}else{
				swal('ERROR','El límite de especialistas es de cuatro (4)','error');
		}
	}else{
		swal('ERROR','Debe seleccionar un especialista','error');
	}
});

function quitar_especialista(registro,id){
	$('tr[data-id="'+registro+'"]').remove();
	var str = id;
	especialistas = especialistas.replace(str,'');
	especialistas = especialistas.replace(/^( *, *)+|(, *(?=,|$))+/g, '');
	cantidad_especialistas = cantidad_especialistas - 1;
	//console.log('especialistas quitar: '+especialistas);
}

//***** ***** ***** ***** ***** SOLICITUD ***** ***** ***** ***** *****//
$("#nuevaSolicitud").click(function(){
	location.href = 'solicitud.php';
});

$("#agendamiento-cita").on('click',function(){ 
	let contar = 0;
	$( "#tabla_especialistas_cuerpo tr" ).each(function( index ) {
		contar++;
	});
	//var cantidad_especialistas = 3;
	if(contar < 3){
		swal("Error","Debe seleccionar mínimo tres (3) especialistas","error");
		return;
	}else if($("#agendamiento-fecha_cita").val() == ''){
		swal("Error","El campo <strong>Fecha de cita</strong> es obligatorio","error");
	}else{
		guardar();
	}	
});

//*****FIRMA */
$("#firma").on('click',function(){ 
	$("#modalFirmas").modal('show');
});

function guardar(){
    $('#preloader').css('display', 'block');
	var idsolicitud = $('#agendamiento-codigo').html();
	var discapacidad = $('#agendamiento-tipo_discapacidad').html();
    var dataserialize = $('#form_agendamiento').serializeArray();
	var datos = {};
	for (var i in dataserialize){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'agendamiento-idespecialidad' || dataserialize[i].name == 'agendamiento-idespecialistas'){
			datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			datos[dataserialize[i].name] = dataserialize[i].value;
		}
	}
	especialistas = especialistas.replace(/^( *, *)+|(, *(?=,|$))+/g, '');
	datos['medicos'] = especialistas;
    if (idsolicitud == ''){
        var oper = "agendar_solicitud";
    }else{
        var oper = "editar_solicitud";
    }
    
	$.ajax({
		url: "controller/solicitudesback.php",
		type: "POST",
		data: {
			oper: oper,
			datos: datos,
			discapacidad: discapacidad,
			id: idsolicitud
		},
		dataType: "json",
		success: function(response){
			//console.log(response);
			$('#preloader').css('display', 'none');
			if (response == 1){
				tablasolicitudes.ajax.reload(null, false);
				ajustarTablas();
				$("#modal_usuarios_ce").modal("hide");
				if(idsolicitud){
					swal('Buen trabajo', 'Cita modificada satisfactoriamente', 'success');
				}else{
					swal('Buen trabajo', 'Cita almacenada satisfactoriamente', 'success');
				}
				$("#modalAgendamientoNuevo").modal('hide');
				location.href = 'solicitudes.php';
			}else{
				swal('Error', 'Error general 1', 'error');
			}
		}
	});
}

function validarform(){ //FALTA
	var salida = 1;
    var cliente= $('#cliente').val();
    var actopublico= $('#actopublico').val();
    var proyecto= $('#proyecto').val();
    var monto= $('#monto').val();
    var ganancia= $('#ganancia').val();
    var tipopropuesta= $('#tipopropuesta').val();
    var trimestre= $('#trimestre').val();
    var año= $('#año').val()
    var vertical= $('#vertical').val();
    var sector= $('#sector').val();
    var estadopropuesta= $('#estadopropuesta').val();
    var responsabledsl= $('#responsabledsl').val();
    var partner= $('#partner').val();
    var equipocomercial= $('#equipocomercial').val();
    var calificacion= $('#calificacion').val();
    expresion=/\w+@\w+\.+[a-z]/;
    
    if (cliente==""){
		swal('Error', 'El campo Entidad / Cliente esta vacío', 'error');
		salida = 0;
	}else if (proyecto==""){
		swal('Error', 'El campo Proyecto esta vacío', 'error');
		salida = 0;
	}else if (tipopropuesta=="0"){
		swal('Error', 'Seleccione un tipo de propuesta', 'error');
		salida = 0;
	}else if (responsabledsl=="0"){
		swal('Error', 'Seleccione el Responsable DSL', 'error');
		salida = 0;
	}else if (estadopropuesta=="0"){
		swal('Error', 'Seleccione el estado de la propuesta', 'error');
		salida = 0;
	}else if (equipocomercial=="0"){
		swal('Error', 'Seleccione el equipo comercial', 'error');
		salida = 0;
	}else if (vertical=="0"){
		swal('Error', 'Seleccione el Vertical', 'error');
		salida = 0;
	}	
	return salida;
}

$("#modalAgendamientoNuevo").on('hide.bs.modal', function(){
    $('#form_agendamiento')[0].reset();
    $("#modalAgendamientoNuevo h4.card-title").text("Agendamiento");
});

function eliminarSolicitud(id,nombre){
    var id = id;
    swal({
        title: "Confirmar",
        html: "¿Esta seguro de eliminar la solicitud del usuario <strong>"+nombre+"</strong>?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: 'red',
        confirmButtonColor: '#09b354',
        confirmButtonText: 'Sí',
        cancelButtonText: "No"
    }).then(
        function(isConfirm)
        {
            //console.log(isConfirm);
            if (isConfirm.value==true)
            {
                $('#preloader').css('display', 'block');
                $.get("controller/solicitudesback.php",
                {
                    'oper'	: 'eliminar',
                    'id' 	: id,
					'nombre': nombre
                }, function(result) 
                {
                    if (result == 1)
                    {
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Solicitud eliminada satisfactoriamente', 'success');
                        // RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
                        tablasolicitudes.ajax.reload(null, false);
                        tablasolicitudes.columns.adjust();
                    } 
                    else 
                    {
                        $('#preloader').css('display', 'none');
                        swal('ERROR', 'Ha ocurrido un error al eliminar la solicitud, intente más tarde', 'error');
                    }
                });
            }
        },
        function(isRechazo){
            console.log(isRechazo);
        }
    );
}

function cambiarEstado(id,tipo){
    var id = id; 
	if(tipo == 'reconsideracion'){
		tipoestado = 'Reconsideración';
	}else if(tipo == 'apelacion'){
		tipoestado = 'Apelación';
	}else if(tipo == 'rcg'){
		tipoestado = 'Resolución de certificación generada';
	}else if(tipo == 'rng'){
		tipoestado = 'Resolución de negatoria generada';
	}
	
    swal({
        title: "Confirmar",
        html: `El estado de la solicitud va a ser cambiado a ${tipoestado} ¿Desea continuar?`,
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: 'red',
        confirmButtonColor: '#09b354',
        confirmButtonText: 'Sí',
        cancelButtonText: "No"
    }).then(
        function(isConfirm)
        { 
            if (isConfirm.value==true)
            {
                $('#preloader').css('display', 'block');
                $.get("controller/solicitudesback.php",
                {
                    'oper'	: 'cambiarEstado',
                    'id' 	: id, 
                    'tipo' 	: tipo
                }, function(result) 
                {
                    if (result == 1)
                    {
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Solicitud actualizada satisfactoriamente', 'success');
						if(tipo == 'rcg'){
							$("#modal-resolucion-nuevo").modal('hide');
						}else if(tipo == 'rng'){
							$("#modal-negatoria-nuevo").modal('hide');
						}
                        tablasolicitudes.ajax.reload(null, false);
                        tablasolicitudes.columns.adjust();
                    } 
                    else 
                    {
                        $('#preloader').css('display', 'none');
                        swal('ERROR', 'Ha ocurrido un error al actualizar la solicitud, intente más tarde', 'error');
                    }
                });
            }
        },
        function(isRechazo){
            console.log(isRechazo);
        }
    );
}

//***** ***** ***** ***** ***** RESOLUCIÓN ***** ***** ***** ***** *****//
//GUARDAR   

//CERTIFICACIÓN
/* $('#aprobarresolucion-legal').on('click', function(){
	let id = $("#idsolicitud_resolucion").val();
	
	
}); */

$("#emitir-resolucion").on('click',function(){
	//Cambiar a estado en el caso de que usuario LEGAL apruebe resolución
	//RCG Resolución de certificación generada
	let idsolicitud = $("#idsolicitud_resolucion").val();
	guardarResolucion(idsolicitud);
});

function guardarResolucion(idsolicitud){
	var idupdated			= $("#idupdated").val();
	var nro_resolucion 		= $("#nro_resolucion").val();
	var nro_expediente 		= $("#nro_expediente").val();
	var validez_certificado = $("#validez_certificado").val();
	let validez_tipo 		= $("#validez_tipo").val();
	var observacion 		= $("#res_observacion").val();
	
	//if(validarform(nro_resolucion,nro_expediente,validez_certificado,observacion) == 1){
		if(idupdated == ""){
			oper = 'guardar_resolucion'
		}else{
			oper = 'editar_resolucion'
		}
		$.ajax({
			type: 'post',
			url: 'controller/solicitudesback.php',
			dataType: 'json',
			data: { 
				'oper'					: oper,				
				'idsolicitud'			: idsolicitud,
				'nro_resolucion'		: nro_resolucion,
				'nro_expediente'		: nro_expediente,
				'validez_certificado'	: validez_certificado,
				'validez_tipo'			: validez_tipo,
				'observacion'			: observacion
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			success: function (response) {
				$('#overlay').css('display','none');
				if(response !=  0){ 						
					swal("Buen trabajo","Resolución guardada satisfactoriamente","success");
					//Cambiar a estado en el caso de que usuario LEGAL apruebe resolución
					//RCG Resolución de certificación generada
					let tipo = 'rcg';
					cambiarEstado(idsolicitud,tipo);
				}else{
					swal('ERROR','Ha ocurrido un error al guardar la resolución, por favor intente más tarde','error');	
				}
			},
			error: function () {
				swal('ERROR','Ha ocurrido un error al guardar la resolución, por favor intente más tarde','error');	
				$('#overlay').css('display','none');							
			}
		});
	//} 
}

function limpiarModalResolucion(){
	$("#idsolicitud_resolucion").val("");
	$("#idupdated").val("");
	$("#nro_resolucion").val("");
	$("#nro_expediente").val("");
	$("#validez_certificado").val("");
	$("#res_observacion").val("");
	$("#fechasol_negatoria").val("");
	$("#fechaeva_negatoria").val("");
	$("#fechanot_negatoria").val("");
	$("#nombre_encargado").val("");
	$("#cargo_encargado").val("");
}

$("#resolucion-cancelar").on('click',function(){
	limpiarModalResolucion();
}); 

$('#modal-resolucion-nuevo').on('hidden.bs.modal', function () {
	limpiarModalResolucion();
	$("#emitir-certificado").prop('disabled', true);
});

//EMITIR CERTIFICADO
$("#emitir-certificado").on('click',function(){
	var idsolicitud = $("#idsolicitud_resolucion").val();
	//window.open(`reporte/imprimirresolucion.php?id=${idsolicitud}`, '_blank');
	$.ajax({
		type:'POST',
		url:`reporte/imprimirresolucion.php?id=${idsolicitud}`,
		success: function (response) {
			window.open(response, '_blank'); 
			 setTimeout(() => {
				tabla_verresoluciones.ajax.reload();
			}, "4000")  
		},
		error: function () {
			console.log('errorrr')					
		}
	}); 
});

//***** ***** ***** ***** ***** NEGATORIA ***** ***** ***** ***** *****//

$("#fechasol_negatoria, #fechaeva_negatoria, #fechanot_negatoria").bootstrapMaterialDatePicker({
	weekStart:0,
	format : 'YYYY-MM-DD',
	switchOnClick:true,
	time: false
});	
//GUARDAR   
$("#aceptar-negatoria").on('click',function(){
	var idsolicitud = $("#idsolicitud_negatoria").val();
	guardarNegatoria(idsolicitud);
});

function guardarNegatoria(idsolicitud){
	var idupdated			= $("#idupdatednegatoria").val();
	var nro_resolucion 		= $("#nro_negatoria").val();
	var evaluacion_negatoria= $("#evaluacion_negatoria").val();
	var primerc_negatoria 	= $("#primerc_negatoria").val();
	var segundoc_negatoria  = $("#segundoc_negatoria").val();
	var fechasol_negatoria  = $("#fechasol_negatoria").val();
	var fechaeva_negatoria  = $("#fechaeva_negatoria").val();
	var fechanot_negatoria  = $("#fechanot_negatoria").val();
	var nombre_encargado  	= $("#nombre_encargado").val();
	var cargo_encargado 	= $("#cargo_encargado").val();
	
	
	//if(validarform(nro_resolucion,nro_expediente,validez_certificado,observacion) == 1){
		if(idupdated == ""){
			oper = 'guardar_negatoria'
		}else{
			oper = 'editar_negatoria'
		}
		$.ajax({
			type: 'post',
			url: 'controller/solicitudesback.php',
			dataType: 'json',
			data: { 
				'oper'					: oper,				
				'idsolicitud'			: idsolicitud,
				'nro_resolucion'		: nro_resolucion,
				'evaluacion_negatoria'	: evaluacion_negatoria,
				'primerc_negatoria'		: primerc_negatoria,
				'segundoc_negatoria'	: segundoc_negatoria,
				'fechasol_negatoria'	: fechasol_negatoria,
				'fechaeva_negatoria'	: fechaeva_negatoria,
				'fechanot_negatoria'	: fechanot_negatoria,
				'nombre_encargado'		: nombre_encargado,
				'cargo_encargado'		: cargo_encargado
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			success: function (response) {
				$('#overlay').css('display','none');
				if(response !=  0){ 						
					swal("Buen trabajo","Negatoria guardada satisfactoriamente","success");
					limpiarModalResolucion();
					$("#modal-negatoria-nuevo").modal('hide');
					$("#aprobarnegatoria-legal").removeClass('d-none');
				}else{
					swal('ERROR','Ha ocurrido un error al guardar la negatoria, por favor intente más tarde','error');	
				}
			},
			error: function () {
				swal('ERROR','Ha ocurrido un error al guardar la negatoria, por favor intente más tarde','error');	
				$('#overlay').css('display','none');							
			}
		});
	//} 
}

function limpiarModalNegatoria(){
	$("#idsolicitud_negatoria").val("");
	$("#idupdatednegatoria").val("");
	$("#nro_negatoria").val("");
	$("#evaluacion_negatoria").val("");
	$("#primerc_negatoria").val("");
	$("#segundoc_negatoria").val("");
}

$("#negatoria-cancelar").on('click',function(){
	limpiarModalNegatoria();
}); 

$('#modal-negatoria-nuevo').on('hidden.bs.modal', function () {
	limpiarModalNegatoria();
	$("#emitir-negatoria").prop('disabled', true);
});

//EMITIR CERTIFICADO
$("#emitir-negatoria").on('click',function(){
	var idsolicitud = $("#idsolicitud_negatoria").val();
	window.open('reporte/imprimirnegatoria.php?id='+idsolicitud, '_blank'); 
});

//***** ***** ***** ***** ***** MOSTRAR / OCULTAR COLUMNAS ***** ***** ***** ***** *****//
$('button.toggle-vis').on( 'click', function (e) {
    e.preventDefault();
	let  column = tablasolicitudes.column( $(this).attr('data-column') )
	var ocultar = $(this).attr('data-column');
	column.visible( ! column.visible() );
	//console.log({column,ocultar})
	if (column.visible()){
		    
	    $("#c"+$(this).attr('data-column')).removeClass("btn-danger")
		$.ajax({
            type: 'post',
            url: 'controller/solicitudesback.php',
            data: {
            'oper': 'guardarcolumnaocultar',
            'tipo': 'eliminar',
            'columna': ocultar
            },
            beforeSend: function() {},
            success: function(response) {
                console.log("lista de columnas actualizada guardada");
                verificarbotonocultarcolumna();
            }
        });
        
    } else {
		$("#c"+$(this).attr('data-column')).addClass("btn-danger")
		$.ajax({
            type: 'post',
            url: 'controller/solicitudesback.php',
            data: {
            'oper': 'guardarcolumnaocultar',
            'tipo': 'agregar',
            'columna': ocultar
            },
            beforeSend: function() {},
            success: function(response) {
                console.log("lista de columnas actualizada guardada");
                verificarbotonocultarcolumna();
            }
        });
	}
	$('#tablasolicitudes').width('100%'); 
	$('.dataTables_scrollHead table').width('100%');
} );    

const ocultarDefault=()=>{        
    $.ajax({
        type: 'post',
        url: 'controller/solicitudesback.php',
        data: {'oper': 'guardardeefault'},
        beforeSend: function() {},
        success: function(response){
            if (response == '1'){
                console.log("insertando-columnas")
            }else{
                console.log("columnas-insertadas")
            }
        }
    });
}

//ocultarDefault();
//buscarcolumnasocultas();
function buscarcolumnasocultas(){
    $.ajax({
        type: 'post',
        url: 'controller/solicitudesback.php',
        data: {
            'oper': 'consultarcolumnas',
        },
        beforeSend: function() {},
        success: function(response) {
            if (response != '0') {
                var columnas = response.split(',');
                console.log(columnas)
                for (var i = 0; i < columnas.length; i++) {
                    var column = tablasolicitudes.column(columnas[i]);
					column.visible(false);
					$("#c" + columnas[i]).addClass("btn-danger");
                }
            }
        }
    });
}
    
function verificarbotonocultarcolumna(){
	$('#preloader').css('display','block');
    $.ajax({
        type: 'post',
        url: 'controller/solicitudesback.php',
        data: {
            'oper': 'consultarcolumnas',
        },
        beforeSend: function() {},
        success: function(response) {
			$('#preloader').css( 'display','none');
            if (response != '0') {
                $("#botonocultarcolumnas").addClass("btn-danger")
                $("#botonocultarcolumnas").removeClass("btn-danger")
            }else{
                $("#botonocultarcolumnas").addClass("btn-danger")
                $("#botonocultarcolumnas").removeClass("btn-danger")
            }
        }
    });
}

$("select").select2({ language: "es" });

//ADJUNTOS
var dirxdefecto = 'solicitudes/solicitud';
$('#fevidencias').attr('src','filegator/solicitudes.php#/?cd=%2F'+dirxdefecto);
function abrirsolicitudes(id) {
	  var valid = true;
	  if ( valid ) {
		$.ajax({
			  type: 'post',
			  url: 'controller/solicitudesback.php',
			  data: { 
				'oper': 'abrirSolicitudes',
				'id': id		  
			  },
			  success: function (response) {
				$('#fevidencias').attr('src','filegator/solicitudes.php#/?cd=solicitudes/'+id);
				$('#modalEvidencias').modal('show');
				$('#modalEvidencias .modal-lg').css('width','1000px');
				$('#idsolicitudesevidencias').val(id);
				$('.titulo-evidencia').html('Solicitud: '+id+' - Evidencia');
			  },
			  error: function () {
			sweetAlert("Oops...", "Ha ocurrido un error al agregar la evidencia, intente más tarde", "error");
			  }
		   }); 
	  }
	  return valid;
}


//RNG Resolución de negatoria generada
//Cambiar a estado en el caso de que usuario LEGAL apruebe negatoria

//NEGATORIA
$('#aprobarnegatoria-legal').on('click', function(){
	
	let id = $("#idsolicitud_negatoria").val();
	let tipo = 'rng';
	cambiarEstado(id,tipo);
	
});