	var diagnosticos  = ",";
	var ultimodiagnostico = 0;	
	var idpaciente = getQueryVariable('idpaciente');
	var idevaluacion = getQueryVariable('idevaluacion');
	var idsolicitud = getQueryVariable('idsolicitud');
	var estatus = 13;
	var discapacidad = '';
	var cargarEstadoSolicitud = 0;
	var estadoPendiente = 16;
	var removerEstados = false;

	$("#listadoSolicitudes").on("click",function(){
		location.href = 'solicitudes.php';
	});
	
	//COMBOS
	function estados(id){
		$.get("controller/combosback.php?oper=estados", {}, function(result)
		{
			$("#idestados").empty();
			$("#idestados").append(result);
			if (id != 0){
				$("#idestados").val(id).trigger('change');
			}
		});
	}
	estados(0);
	
	$(document).ready(function() {
		var coo = '';
		var name = "nivel=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		//alert('decodedCookie: '+decodedCookie);
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				coo = c.substring(name.length, c.length);
			}
		}
		if(coo == 2 ){
		    $("#nombre").prop('disabled',true);
			$("#apellido").prop('disabled',true);
			$("#cedula").prop('disabled',true);
			$("#fecha_nacimiento").prop('disabled',true);
			$("#edad").prop('disabled',true);
			$("#iddiscapacidades").prop('disabled',true);
			$("#tipo_solicitud").prop('disabled',true);
			$("#hora_inicio").prop('disabled',true);
			$("#hora_final").prop('disabled',true);
			$("#nro_junta").prop('disabled',true);
			$("#iniciacion_dano").prop('disabled',true);
			$("#ayudas_tecnicas").prop('disabled',true);
			$("#ayudas_tecnicas_otro").prop('disabled',true);
			$("#alfabetismo").prop('disabled',true);
			$("#nivel_educacional").prop('disabled',true);
			$("#nivel_educacional_completado").prop('disabled',true);
			$("#nivel_educacional_completado").prop('disabled',true);
			$("#concurrencia_educacional").prop('disabled',true);
			$("#tipo_educacion").prop('disabled',true);
			$("#concurrencia_tipo_educacion").prop('disabled',true);
			$("#convivencia").prop('disabled',true);
			$('input:radio[name="tipo_vivienda"]').prop('disabled',true);
			$('input:radio[name="vivienda_adaptada"]').prop('disabled',true);
			$('input:radio[name="medio_de_transporte"]').prop('disabled',true);
			$('input:radio[name="estado_calles"]').prop('disabled',true);
			$("#etnia").prop('disabled',true);
			$("#religion").prop('disabled',true);
			$("#ingreso_mensual").prop('disabled',true);
			$("#salario_total").prop('disabled',true);
			$("#acompanante").prop('disabled',true);
			$("#datos_acompanante").prop('disabled',true);
			$("#observaciones").prop('disabled',true);
			$("#fecha_vencimiento").prop('disabled',true);
			$("#fecha_emision").prop('disabled',true);
			$("#lugar_emision").prop('disabled',true);
			$("#idestados").prop('disabled',true);
			$("#adecuacion").prop('disabled',true);
			$("#porcentaje1").prop('disabled',true);
			$("#porcentaje2").prop('disabled',true);
			$("#criterio").prop('disabled',true);
			$("#regla").prop('disabled',true);
			$("#certifica").prop('disabled',true);
			$("#cantidadhabitaciones").prop('disabled',true);
			$("#ayudas_tecnicas>option[value='0'], #tipo_educacion>option[value='0']").prop('disabled',true);
			$('#certificadomedico').prop('disabled',true);
            $('#resumnen_h_clinica').prop('disabled',true);
            $('#hist_clinica').prop('disabled',true);
            $('#est_complementarios').prop('disabled',true);
            $('#evaluacion-hijo').prop('disabled',true);
            $('#evaluacion-madre').prop('disabled',true);
            $('#evaluacion-hermano').prop('disabled',true);
            $('#evaluacion-conyuge').prop('disabled',true);
            $('#evaluacion-padre').prop('disabled',true);
            $('#evaluacion-abuelo').prop('disabled',true);
            $('#evaluacion-otro_familiar').prop('disabled',true);
            $('#evaluacion-otro_no_familiar').prop('disabled',true);
            $("#anadir_diagnostico").prop('disabled',true);
		}
	$("#ayudas_tecnicas>option[value='0'], #tipo_educacion>option[value='0']").attr('disabled','disabled');
	//console.log('idpaciente: '+idpaciente);
	if(idpaciente != ''){
		$.get('controller/beneficiariosback.php?oper=getpaciente&id='+idpaciente+'&idsolicitud='+idsolicitud,function(response){
			discapacidad = response.paciente.discapacidad;
			$("#nombre").val(response.paciente.nombre);
			$("#apellido").val(response.paciente.apellido);
			$("#cedula").val(response.paciente.cedula);
			$("#iddiscapacidades").val(discapacidad).trigger('change');
			$("#fecha_nacimiento").val(response.paciente.fecha_nac).trigger('change');
			/*
			$.get('controller/evaluacionesback.php?oper=getnombreacompanante&idpaciente='+idpaciente,function(response){
				$("#acompanante").val(response.nombre).trigger('change');				
			},'json');
			*/
			
			var fecha_nac = response.paciente.fecha_nac; 
			var discapacidad = response.paciente.discapacidad;

			let edad = calcularEdad(fecha_nac,'');
			$("#edad").val(edad);		  
			if(edad < 18){ 
				$("#menor_de_edad").val(0);
				var menor = 0;
			}else{ 
				$("#menor_de_edad").val(1);
				var menor = 1;
			}
			
			if(discapacidad == 'MENTAL' || discapacidad == 'INTELECTUAL'){
				if(menor == 0){
					tipo = 'NIÑO';
				}else if(menor == 1){
					tipo = 'ADULTO';
				}
				$("#titulo_discapacidad").html("DISCAPACIDAD: "+discapacidad+' '+tipo);	
			}
			
		},'json');
	}
	if (idevaluacion != '') { 
		//$.get('controller/evaluacionback.php?oper=get&idevaluacion='+idevaluacion,function(response){
		jQuery.ajax({
			url: 'controller/evaluacionback.php?oper=get&idevaluacion='+idevaluacion,
			dataType: "json",
			beforeSend: function(){
			   $('#overlay').css('display','block');
			},success: function(response) {
				console.log(response)
			$('#overlay').css('display','none');
			
			$("#nombre").val(response.nombre);
			$("#apellido").val(response.apellido);
			$("#cedula").val(response.cedula);
			$("#fecha_nacimiento").val(response.fecha_nac).trigger('change');
			$("#iddiscapacidades").val(response.tipodiscapacidad).trigger('change');
			$("#tipo_solicitud").val(response.tiposolicitud).trigger('change');
			$("#hora_inicio").val(response.horainicio);
			$("#hora_final").val(response.horafinal);
			$("#nro_junta").val(response.codigojunta);
			$("#iniciacion_dano").val(response.fechainiciodano);
			if(response.ayudatecnica != null){
				$("#ayudas_tecnicas").val(response.ayudatecnica.split(',')).trigger('change');
			}			
			$("#ayudas_tecnicas_otro").val(response.ayudatecnicaotro);
			$("#alfabetismo").val(response.alfabetismo).trigger('change');
			$("#nivel_educacional").val(response.niveleducacional).trigger('change');
			$("#nivel_educacional_completado").val(response.niveleducacionalcompletado).trigger('change');
			$("#nivel_texto").val(response.niveleducacionalincompleto);
			$("#adecuacion").val(response.adecuacion);
			$("#concurrencia_educacional").val(response.concurrenciaeducacionalcompletado).trigger('change');
			if(response.tipoeducacion != null){
				$("#tipo_educacion").val(response.tipoeducacion.split(',')).trigger('change');
			}			
			$("#concurrencia_tipo_educacion").val(response.concurrenciatipoeducacion).trigger('change');
			$("#convivencia").val(response.convivencia).trigger('change');			
			$("#tipo_vivienda").val(response.tipovivienda).trigger('change');
			$("#vivienda_adaptada").val(response.viviendaadaptada).trigger('change');
			$("#medio_de_transporte").val(response.mediotransporte).trigger('change');
			$("#estado_calles").val(response.estadocalles).trigger('change');
			if(response.vinculos != null){
				$("#vinculos").val(response.vinculos.split(',')).trigger('change');
			}
			if(response.etnia != null){
				$("#etnia").val(response.etnia).trigger('change');
			}
			if(response.religion != null){
				$("#religion").val(response.religion).trigger('change');
			}
			$("#ingreso_mensual").val(response.ingresomensual).trigger('change');
			$("#salario_total").val(response.ingresomensualotro);
			$("#acompante").val(response.acompanante).trigger('change');
			$("#datos_acompanante").val(response.nombreacompanante);
			$("#observaciones").val(response.observaciones);
			$("#cantidad_vencimiento").val(response.duracion);
			$("#tipo_vencimiento").val(response.tipoduracion).trigger('change');
			$("#fecha_vencimiento").val(response.fechavencimiento);
			$("#fecha_emision").val(response.fechaemision);
			if(response.modalidad == "1"){
				$("#presencial").prop("checked", true).trigger('change');
			}else if(response.modalidad == "2"){
				$("#virtual").prop("checked", true).trigger('change');
			}
			
			var fecha_nac = response.fecha_nac;
			var fecha_cita = response.fecha_cita;
			var discapacidad = response.tipodiscapacidad;

			let edad = calcularEdad(fecha_nac,'');
			
			$("#edad").val(edad);			
			//EDAD AL MOMENTO DE LA SOLICITUD
			let edad_sol = calcularEdad(fecha_nac,fecha_cita);
			$("#edad_sol").val(edad_sol);
			if(edad_sol < 18){
				//console.log('menor de edad');
				$("#menor_de_edad").val(0);
				var menor = 0;
			}else{
				//console.log('mayor de edad');
				$("#menor_de_edad").val(1);
				var menor = 1;
			}
			
			//console.log('menor: '+menor);
			//console.log(' paso discapacidad: '+discapacidad);			
			if(discapacidad == 'MENTAL' || discapacidad == 'INTELECTUAL'){
				if(menor == 0){
					tipo = 'NIÑO';
				}else if(menor == 1){
					tipo = 'ADULTO';
				}
				$("#titulo_discapacidad").html("DISCAPACIDAD: "+discapacidad+' '+tipo);	
			}else{
				$("#titulo_discapacidad").html("DISCAPACIDAD: "+discapacidad);	
			}			
			$("#lugar_emision").val(response.ciudad);
			$("#idestados").val(response.idestados).trigger('change');
			//No mostrar el botón que activa el estado pendiente, si no está agendado
			if(response.idestados != 2){
				$(".boton-estado-pendiente").css("display","none");
			}
			$("#concurrircon").val(response.concurrircon);
			$("#estudioscomplementarios").val(response.estudioscomplementarios);
			
			cargarEstadoSolicitud = 1;
			$("#adecuacion").val(response.adecuacion);
			$("#porcentaje1").val(response.porcentaje1);
			$("#porcentaje2").val(response.porcentaje2);
			$("#criterio").val(response.criterio);
			$("#regla").val(response.regla);
			$("#certifica").val(response.certifica);
			$(".resultadoFormula").html(response.resultadoFormula);
			$("#cantidadhabitaciones").val(response.cantidadhabitaciones);
			idsolicitud = response.idsolicitud;
			$("#imprimirprotocolo").css('display','inline-block');
			$("#imprimirprotocolo").click(function(){
				window.open('reporte/imprimirprotocolo.php?sol='+idsolicitud+'&ev='+idevaluacion, '_blank');
			});
			
			var documentos = response.documentos;
			if(documentos != null){
				if(documentos.indexOf("1") != '-1'){
					$('#certificadomedico').prop("checked", true);
				}
				if(documentos.indexOf("2") != '-1'){
					$('#resumnen_h_clinica').prop("checked", true);
				}
				if(documentos.indexOf("3") != '-1'){
					$('#hist_clinica').prop("checked", true);
				}
				if(documentos.indexOf("4") != '-1'){
					$('#est_complementarios').prop("checked", true);
				}
			}
			diagnosticos = response.diagnostico;
			if(diagnosticos != null){
				var longit = diagnosticos.length;
				//console.log(diagnosticos);
				//diagnosticoswhile = diagnosticos.substr(0,longit-1);
				diagnosticoswhile = diagnosticos;
				//console.log(diagnosticoswhile);
				diagnosticoswhile = diagnosticoswhile.split(",");
				diagnosticoswhile = diagnosticoswhile.filter(Boolean);
				i = 0;
				//console.log(diagnosticoswhile[0]);
				//console.log(diagnosticoswhile[1]);
				//console.log(diagnosticoswhile[2]);
				//console.log(diagnosticoswhile.length);
				while(i < diagnosticoswhile.length){
					//console.log(diagnosticoswhile[i]);
					var data_cie20;
					$.get("controller/combosback.php?oper=enfermedades3&id="+diagnosticoswhile[i],function(response){  		
						ultimodiagnostico	= ultimodiagnostico	+1;
						var codigo=response[0].text.split(' | ')[0]
						var nombre=response[0].text.split(' | ')[1];	
						var id= response[0].id;
						var html_nuevo_diagnostico='<tr data-id="'+ultimodiagnostico+'">\
														<td class="text-center">\
															<span onclick="quitar_diagnostico('+ultimodiagnostico+','+id+');" data-id="'+ultimodiagnostico+'" class="btn fas fa-minus bg-danger text-white p-1" data-toggle="tooltip" aria-hidden="true" title="Eliminar diagnóstico"></span>\
														</td>\
														<td>'+nombre+'</td>\
														<td>'+codigo+'</td>\
													</tr>';
						$("#diagnosticos_cuerpo").append(html_nuevo_diagnostico);
					},'json');
					i++;
				}
			}
			
			var cif = response.cif;
			//cif = JSON.parse(cif);
			//console.log("cif ev: " + response.cif);
			if (cif!=null && cif != '') {
			    cargarTablaCIF(cif,'-');   
			}
		}
		});
		//},'json');
	}else{
		//Obtener solo el estado de la solicitud
		jQuery.ajax({
			url: 'controller/evaluacionback.php?oper=getEstadoSolicitud&idsolicitud='+idsolicitud,
			dataType: "json",
			beforeSend: function(){
			    $('#overlay').css('display','block');
			},success: function(response) {
				$('#overlay').css('display','none');
				$("#idestados").val(response.idestados).trigger('change');
				$("#tipo_solicitud").val(response.tiposolicitud).trigger('change');
				let fechacita = response.fechacita;
				let fechanac = response.fechanac; 
				let edad_sol = calcularEdad(fechanac,fechacita);
				$("#edad_sol").val(edad_sol); 

				cargarEstadoSolicitud = 1;
			}
		});
	}
	});
	$('#hora_inicio, #hora_final').bootstrapMaterialDatePicker({switchOnClick:true, date:false, shortTime: true, twelvehour: false, format : 'hh:mm a' });
	$("#fecha_vencimiento").bootstrapMaterialDatePicker({
	    date: true,
		format:'DD-MM-YYYY',
		time:false,
		cancelText: 'Cancelar',
	    lang: 'es',
	});
	$("#fecha_emision").bootstrapMaterialDatePicker({
	    date: true,
		format:'DD-MM-YYYY', 
		time:false,
	    lang: 'es',
		cancelText: 'Cancelar',
	    maxDate:  new Date()
	});

	$("#iniciacion_dano").bootstrapMaterialDatePicker({
	    
		format:'MM-YYYY', 
		time:false,
	    lang: 'es',
	    cancelText: 'Cancelar'
	});
	$(".dtp-actual-day").css('display','none');
	$(".dtp-actual-num").css('display','none');
	// datepicker is opened
	$('#iniciacion_dano').bootstrapMaterialDatePicker().on('open',function(e, date){
		$(".dtp-picker-calendar").css('display','none');
	});
	// datepicker is closed
	$('#iniciacion_dano').bootstrapMaterialDatePicker().on('close',function(e, date){
		$(".dtp-picker-calendar").css('display','block');
	});

	var currenDate = new Date();
	$('select').each(function(){
		let id__select = $(this).attr('id');
		if(id__select != 'iddiagnosticos'){
		$(this).select2({placeholder:'Seleccione'});
		$(this).val(0).trigger('change');
		}  
	});

	$(document).on('focus', '.select2.select2-container', function (e) {  
		var isOriginalEvent = e.originalEvent // don't re-open on closing focus event
		var isSingleSelect = $(this).find(".select2-selection--single").length > 0 // multi-select will pass focus to input

		if (isOriginalEvent && isSingleSelect) {
			$(this).siblings('select:enabled').select2('open');
		}
	});

	$("#anadir_diagnostico").on('click',function() {
		ultimodiagnostico	= ultimodiagnostico	+1;
		var codigo=$("#iddiagnosticos").select2('data')[0].element.text.split(' | ')[0]
		var nombre=$("#iddiagnosticos").select2('data')[0].element.text.split(' | ')[1];	
		var id = $("#iddiagnosticos").val();
		//console.log(diagnosticos+'-'+id);
		if(diagnosticos != null && diagnosticos != ''){
		    if(diagnosticos.search(','+id+',') == -1){
    			var html_nuevo_diagnostico='<tr data-id="'+ultimodiagnostico+'">\
    											<td class="text-center">\
    												<span onclick="quitar_diagnostico('+ultimodiagnostico+','+id+');" data-id="'+ultimodiagnostico+'" class="btn fas fa-minus bg-danger text-white p-1" data-toggle="tooltip" aria-hidden="true" title="Eliminar diagnóstico"></span>\
    											</td>\
    											<td>'+nombre+'</td>\
    											<td>'+codigo+'</td>\
    										</tr>';
    			$("#diagnosticos_cuerpo").append(html_nuevo_diagnostico);
				diagnosticos = diagnosticos	+","+id+",";					
    		}else{
    			swal('ERROR','El diagnostico <strong>'+nombre+'</strong> ya se encuentra seleccionado','error');
    		}
			//console.log('1. '+diagnosticos);
		}else{
		    var html_nuevo_diagnostico='<tr data-id="'+ultimodiagnostico+'">\
											<td class="text-center">\
												<span onclick="quitar_diagnostico('+ultimodiagnostico+','+id+');" data-id="'+ultimodiagnostico+'" class="btn fas fa-minus bg-danger text-white p-1" data-toggle="tooltip" aria-hidden="true" title="Eliminar diagnóstico"></span>\
											</td>\
											<td>'+nombre+'</td>\
											<td>'+codigo+'</td>\
										</tr>';
			$("#diagnosticos_cuerpo").append(html_nuevo_diagnostico);
			diagnosticos = diagnosticos	+""+id+",";
			//console.log('2. '+diagnosticos);
		}
		
	});
		
	function quitar_diagnostico(registro,id){
		$('tr[data-id="'+registro+'"]').remove();
		//var str = ','+id+',';
		//diagnosticos = diagnosticos.replace(str,',');
		diagnosticos = diagnosticos.replace(id,'');
		diagnosticos = diagnosticos.replace(',,',',');
		console.log('quitar_diagnostico: '+diagnosticos);
	}

	$('[data-toggle="tooltip"]').tooltip(); 

	$('#ayudas_tecnicas').on('change',function(){		
		if($(this).val().indexOf('Otros')!= '-1' ){
			$("#div-ayudas_tecnicas_otro").show();
			$("#ayudas_tecnicas_otro").addClass('mandatorio');
		}else{
			$("#div-ayudas_tecnicas_otro").hide();
			$("#ayudas_tecnicas_otro").removeClass('mandatorio');
		}
	});
	$("#nivel_educacional_completado").on('change',function(){		
		if($(this).val() == 3){
			$("#div-nivel_educacional_completado").show();
			$("#nivel_educacional_completado").addClass('mandatorio');
		}else{
			$("#div-nivel_educacional_completado").hide();
			$("#nivel_educacional_completado").removeClass('mandatorio');
		}
	})
	/*
	$('#iddiscapacidades').on('change',function(){
		if($(this).val() != null || $(this).val() != 'undefined'){
			discapacidad = $(this).val();
		}
		var menor = $("#menor_de_edad").val();
		var tipo = '';
		//console.log('cambio select discapacidad');
		//console.log('discapacidad: '+discapacidad);
		console.log('menor: '+menor);
		if(discapacidad == 'MENTAL' || discapacidad == 'INTELECTUAL'){
			if(menor == 0){
				tipo = 'NIÑO';
			}else if(menor == 1){
				tipo = 'ADULTO';
			}
		}
		$("#titulo_discapacidad").html("DISCAPACIDAD: "+discapacidad+' '+tipo);		
	});
	*/
	$("#acompanante").on('change',function(){
		if($(this).val() == 'SI'){
			$("#datos_acompanante").prop('disabled',false);			
		}else{
			$("#datos_acompanante").prop('disabled',true);
		}
	});
	$("#boton").on('click',function(){
		if(mandatorio("div-evaluacion")==1){
			if(diagnosticos == ',' || diagnosticos ==''){
				swal('ERROR','Debe seleccionar al menos un diagnóstico','error');
			}else{
			    estatus = 14;
				if(idevaluacion != ''){
					modificarEvaluacion();
				}else{
					guardarEvaluacion();
				}
			}
		}
	});
	$("#boton-borrador").on('click',function(){
	    estatus = 13;
		
		let idestados = $("#idestados").val();
	
		if(idevaluacion != ''){
			modificarEvaluacion();
		}else{
			guardarEvaluacion();
		}
		
	});

var data_cie10;
$.get("controller/combosback.php?oper=enfermedades2",function(response){  		
	data_cie10 = response;
},'json');


$.fn.select2.amd.require(["select2/data/array","select2/utils"], function (ArrayData, Utils) {
    //console.log('select2.amd.require');
	function CustomData ($element, options) {
        CustomData.__super__.constructor.call(this, $element, options);
    }
	Utils.Extend(CustomData, ArrayData);
			
	//console.log('Extend');
    CustomData.prototype.query = function (params, callback) {
		//console.log('CustomData');
    	var datos = {
            results: []
        };
		//console.log('params.term: '+params.term);
        if(params.term != '' && params.term !== undefined && params.term.length >=3){
        	//console.log('CONFIG: '+params.term);
			var regex = new RegExp(params.term, "i");
			$.each(data_cie10, function(key, val){
				if (val.text.search(regex) != -1) {
					datos.results.push({
						  "id": val.id,
						  "text": val.text
					});
				}
			});
        }
	    callback(datos);        	
    };
	//console.log('datos: '+datos);
    $("#iddiagnosticos").select2({
        dataAdapter: CustomData,
        placeholder:'Buscar',
		language: "es",
        minimumInputLength: 3
    });
});

function modificarEvaluacion(){
	var tipodiscapacidad = $("#iddiscapacidades").val();
	var tiposolicitud	 = $("#tipo_solicitud").val();
	var horainicio		 = $("#hora_inicio").val();
	var horafinal 		 = $("#hora_final").val(); 
	var documentoident	 = $("#cedula").val();
	var documentos = '';
	if($('#certificadomedico').is(':checked')){
		documentos = documentos + '1,';
	}
	if($('#resumnen_h_clinica').is(':checked')){
		documentos = documentos + '2,';
	}
	if($('#hist_clinica').is(':checked')){
		documentos = documentos + '3,';
	}
	if($('#est_complementarios').is(':checked')){
		documentos = documentos + '4,';
	}
	var codigojunta		 					= $("#nro_junta").val();
	var fechainiciodano  					= $("#iniciacion_dano").val();
	var ayudatecnica 	 					= $("#ayudas_tecnicas").val().join();
	var ayudatecnicaotro 					= $("#ayudas_tecnicas_otro").val();
	var alfabetismo 	 					= $("#alfabetismo").val();
	var niveleducacional 					= $("#nivel_educacional").val();	
	var niveleducacionalcompletado 			= $("#nivel_educacional_completado").val();
	var niveleducacionalincompleto 			= $("#nivel_educacional_completado").val();
	var concurrenciaeducacionalcompletado	= $("#concurrencia_educacional").val();
	var tipoeducacion						= $("#tipo_educacion").val().join();
	var concurrenciatipoeducacion 			= $("#concurrencia_tipo_educacion").val();
	var convivencia 			= $("#convivencia").val();
	var tipovivienda 			= $("#tipo_vivienda").val();
	var viviendaadaptada 		= $("#vivienda_adaptada").val();
	var mediotransporte 		= $("#medio_de_transporte").val();
	var estadocalles 			= $("#estado_calles").val();
	var vinculos 				= $("#vinculos").val().join();
	var etnia 					= $("#etnia").val();
	var religion 				= $("#religion").val();
	var ingresomensual 			= $("#ingreso_mensual").val();
	var ingresomensualotro		= $("#salario_total").val();
	var acompanante 			= $("#acompanante").val();
	var nombreacompanante 		= $("#datos_acompanante").val();
	var observaciones 			= $("#observaciones").val();
	var fechavencimiento 		= $("#fecha_vencimiento").val();
	var cantidadvencimiento 	= $("#cantidad_vencimiento").val();
	var tipovencimiento 		= $("#tipo_vencimiento").val();
	var fechaemision 			= $("#fecha_emision").val();
	var ciudad 					= $("#lugar_emision").val();
	var idestados				= $("#idestados").val();
	var adecuacion				= $("#adecuacion").val();
	var cantidadhabitaciones 	= $("#cantidadhabitaciones").val();
	var porcentaje1 			= $("#porcentaje1").val();
	var porcentaje2 			= $("#porcentaje2").val();
	var criterio 				= $("#criterio").val();
	var regla 					= $("#regla").val();
	var certifica 				= $("#certifica").val();
	var resultadoFormula		= $("#resultado_CalcularFormula").html();
	let concurrircon 			= $('#concurrircon').val();
	let estudioscomplementarios = $('#estudioscomplementarios').val();

	var modalidad = '';
	if($('#presencial').is(':checked')){
		modalidad = '1';
	}else if($('#virtual').is(':checked')){
		modalidad = '2';
	}
	
	//console.log('update: '+diagnosticos);
	if(validarform(tipodiscapacidad,tiposolicitud,documentoident,codigojunta,fechainiciodano,alfabetismo,convivencia,ingresomensual,acompanante,fechaemision,ciudad, modalidad) == 1){

		swal({
			title: "Confirmar",
			text: `Este expediente se va a guardar con el estado ${$('#idestados :selected'). text()}, está seguro ?`,
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){ 
				if (isConfirm.value === true) {
					$.ajax({
						type: 'post',
						url: 'controller/evaluacionback.php',
						data: { 
							'oper'				: 'update',
							'id'				: idevaluacion,
							'tipodiscapacidad' 	: tipodiscapacidad,
							'tiposolicitud' 	: tiposolicitud,
							'horainicio' 		: horainicio,
							'horafinal' 		: horafinal,
							'documentos' 		: documentos,
							'diagnosticos' 		: diagnosticos,
							'codigojunta' 		: codigojunta,
							'fechainiciodano' 	: fechainiciodano,
							'ayudatecnica' 		: ayudatecnica,
							'ayudatecnicaotro' 	: ayudatecnicaotro,
							'alfabetismo' 		: alfabetismo,
							'niveleducacional' 	: niveleducacional,
							'niveleducacionalcompletado' 	: niveleducacionalcompletado,
							'niveleducacionalincompleto' 	: niveleducacionalincompleto,
							'concurrenciaeducacionalcompletado' : concurrenciaeducacionalcompletado,
							'tipoeducacion' 	: tipoeducacion,
							'concurrenciatipoeducacion' 	: concurrenciatipoeducacion,
							'convivencia' 		: convivencia,
							'tipovivienda' 		: tipovivienda,
							'viviendaadaptada' 	: viviendaadaptada,
							'mediotransporte' 	: mediotransporte,
							'estadocalles' 		: estadocalles,
							'vinculos' 			: vinculos,
							'etnia' 			: etnia,
							'religion' 			: religion,
							'ingresomensual' 	: ingresomensual,
							'ingresomensualotro': ingresomensualotro,
							'acompanante' 		: acompanante,
							'nombreacompanante' : nombreacompanante,
							'observaciones' 	: observaciones,
							'fechavencimiento' 	: fechavencimiento,
							'cantidadvencimiento' : cantidadvencimiento,
							'tipovencimiento' 	: tipovencimiento,
							'fechaemision' 		: fechaemision,
							'ciudad' 			: ciudad,
							'idestados'			: idestados,
							'idpaciente' 		: idpaciente,
							'adecuacion'		: adecuacion,
							'cantidadhabitaciones':cantidadhabitaciones,
							'idsolicitud' 		: idsolicitud,
							'porcentaje1'		: porcentaje1,
							'porcentaje2'		: porcentaje2,
							'criterio'			: criterio,
							'regla'				: regla,
							'certifica'			: certifica,
							'resultadoFormula'	: resultadoFormula,
							'concurrircon'		: concurrircon,
							'estudioscomplementarios'	: estudioscomplementarios,
							'modalidad'			: modalidad,
						},
						beforeSend: function() {
							$('#overlay').css('display','block');
						},
						success: function (response) {
							if(response != 0){
								guardarCif(response,2);
							}else{
								swal('ERROR','Ha ocurrido un error al modificar, por favor intente más tarde','error');	
								$('#overlay').css('display','none');
							}
						},
						error: function (error) {
								swal('ERROR','Ha ocurrido un error al modificar, por favor intente más tarde','error');	
							$('#overlay').css('display','none');							
						}
					});
				}
			}
		);
	}
}

function guardarEvaluacion(){
	var tipodiscapacidad = $("#iddiscapacidades").val();
	var tiposolicitud	 = $("#tipo_solicitud").val();
	var horainicio		 = $("#hora_inicio").val();
	var horafinal 		 = $("#hora_final").val();
	var documentoident	 = $("#cedula").val();
	var documentos = '';
	if($('#certificadomedico').is(':checked')){
		documentos = documentos + '1,';
	}
	if($('#resumnen_h_clinica').is(':checked')){
		documentos = documentos + '2,';
	}
	if($('#hist_clinica').is(':checked')){
		documentos = documentos + '3,';
	}
	if($('#est_complementarios').is(':checked')){
		documentos = documentos + '4,';
	}
	var codigojunta		 					= $("#nro_junta").val();
	var fechainiciodano  					= $("#iniciacion_dano").val();
	var ayudatecnica 	 					= $("#ayudas_tecnicas").val().join();
	var ayudatecnicaotro 					= $("#ayudas_tecnicas_otro").val();
	var alfabetismo 	 					= $("#alfabetismo").val();
	var niveleducacional 					= $("#nivel_educacional").val();	
	var niveleducacionalcompletado 			= $("#nivel_educacional_completado").val();
	var niveleducacionalincompleto 			= $("#nivel_educacional_completado").val();
	var concurrenciaeducacionalcompletado	= $("#concurrencia_educacional").val();
	var tipoeducacion						= $("#tipo_educacion").val();
	var tipoeducacionjoin						= $("#tipo_educacion").val().join();
	var concurrenciatipoeducacion 			= $("#concurrencia_tipo_educacion").val();
	var convivencia 						= $("#convivencia").val();
	var tipovivienda 			= $("#tipo_vivienda").val();
	var viviendaadaptada 		= $("#vivienda_adaptada").val();
	var mediotransporte 		= $("#medio_de_transporte").val();
	var estadocalles 			= $("#estado_calles").val();
	var vinculos 				= $("#vinculos").val().join();	
	var adecuacion				= $("#adecuacion").val();
	var etnia 					= $("#etnia").val();
	var cantidadhabitaciones 	= $("#cantidadhabitaciones").val();
	var porcentaje1 			= $("#porcentaje1").val();
	var porcentaje2 			= $("#porcentaje2").val();
	var criterio 				= $("#criterio").val();
	var regla 					= $("#regla").val();
	var certifica 				= $("#certifica").val();
	var religion 				= $("#religion").val();
	var ingresomensual 			= $("#ingreso_mensual").val();
	var ingresomensualotro		= $("#salario_total").val();
	var acompanante 			= $("#acompanante").val();
	var nombreacompanante 		= $("#datos_acompanante").val();
	var observaciones 			= $("#observaciones").val();
	var fechavencimiento 		= $("#fecha_vencimiento").val();
	var cantidadvencimiento 	= $("#cantidad_vencimiento").val();
	var tipovencimiento 		= $("#tipo_vencimiento").val();
	var fechaemision 			= $("#fecha_emision").val();
	var ciudad 					= $("#lugar_emision").val();
	var idestados 				= $("#idestados").val();
	let concurrircon 			= $('#concurrircon').val();
	let estudioscomplementarios = $('#estudioscomplementarios').val();

	var modalidad = '';
	if($('#presencial').is(':checked')){
		modalidad = '1';
	}else if($('#virtual').is(':checked')){
		modalidad = '2';
	}

// 	var idsolicitud = '';
// 	$.get('controller/solicitudesback.php?oper=getIdSolicitudpaciente&idpaciente='+idpaciente,function(response){
// 		idsolicitud = response.id;
		if(validarform(tipodiscapacidad,tiposolicitud,documentoident,codigojunta,fechainiciodano,alfabetismo,convivencia,ingresomensual,acompanante,fechaemision,ciudad, modalidad) == 1){
			swal({
				title: "Confirmar",
				text: `Este expediente se va a guardar con el estado ${$('#idestados :selected'). text()}, está seguro ?`,
				type: "warning",
				showCancelButton: true,
				cancelButtonColor: 'red',
				confirmButtonColor: '#09b354',
				confirmButtonText: 'Si',
				cancelButtonText: "No"
			}).then(
				function(isConfirm){ 
					if (isConfirm.value === true) {
						$.ajax({
							type: 'post',
							url: 'controller/evaluacionback.php',
							data: { 
								'oper'		: 'guardar',
								'tipodiscapacidad' 	: tipodiscapacidad,
								'tiposolicitud' 	: tiposolicitud,
								'horainicio' 	: horainicio,
								'horafinal' 	: horafinal,
								'documentos' 	: documentos,
								'diagnosticos' 	: diagnosticos,
								'codigojunta' 	: codigojunta,
								'fechainiciodano' 	: fechainiciodano,
								'ayudatecnica' 	: ayudatecnica,
								'ayudatecnicaotro' 	: ayudatecnicaotro,
								'alfabetismo' 	: alfabetismo,
								'niveleducacional' 	: niveleducacional,
								'niveleducacionalcompletado' 	: niveleducacionalcompletado,
								'niveleducacionalincompleto' 	: niveleducacionalincompleto,
								'concurrenciaeducacionalcompletado' 	: concurrenciaeducacionalcompletado,
								'tipoeducacion' 	: tipoeducacion,
								'concurrenciatipoeducacion' 	: concurrenciatipoeducacion,
								'convivencia' 	: convivencia,
								'tipovivienda' 	: tipovivienda,
								'viviendaadaptada' 	: viviendaadaptada,
								'mediotransporte' 	: mediotransporte,
								'estadocalles' 	: estadocalles,
								'vinculos' 	: vinculos,
								'etnia' 	: etnia,
								'religion' 	: religion,
								'ingresomensual' 	: ingresomensual,
								'ingresomensualotro' 	: ingresomensualotro,
								'acompanante' 	: acompanante,
								'nombreacompanante' 	: nombreacompanante,
								'observaciones' 	: observaciones,
								'fechavencimiento' 	: fechavencimiento,
								'cantidadvencimiento' : cantidadvencimiento,
								'tipovencimiento' 	: tipovencimiento,
								'fechaemision' 	: fechaemision,
								'ciudad' 	: ciudad,
								'idestados'	: idestados,
								'idpaciente' : idpaciente,
								'estatus': estatus,
								'adecuacion':adecuacion,
								'cantidadhabitaciones':cantidadhabitaciones,
								'porcentaje1':porcentaje1,
								'porcentaje2':porcentaje2,
								'criterio':criterio,
								'regla':regla,
								'certifica':certifica,
								'idsolicitud': idsolicitud,
								'concurrircon': concurrircon,
								'estudioscomplementarios': estudioscomplementarios,
								'modalidad'			: modalidad,
							},
							beforeSend: function() {
								$('#overlay').css('display','block');
							},
							success: function (response) {
								if(response != 0){
									guardarCif(response,1);
								}else{
									swal('ERROR','Ha ocurrido un error al guardar, por favor intente más tarde','error');	
									$('#overlay').css('display','none');
								}
							},
							error: function () {
									swal('ERROR','Ha ocurrido un error al guardar, por favor intente más tarde','error');	
								$('#overlay').css('display','none');							
							}
						});
					}
				}
			);
		}
// 	},'json');
}

function validarform(tipodiscapacidad,tiposolicitud,documentoident,codigojunta,fechainiciodano,alfabetismo,convivencia,ingresomensual,acompanante,fechaemision,ciudad, modalidad){
	var respuesta = 1;
	
	if (tipodiscapacidad == "0" || tipodiscapacidad == null){
		swal('Error', 'El Tipo de discapacidad es obligatorio', 'error');
		respuesta = 0;
	}else if (tiposolicitud == "0" || tiposolicitud == null){
		swal('Error', 'El Tipo de solicitud es obligatorio', 'error');
		respuesta = 0;
	}else if (documentoident == "" || documentoident == null){
		swal('Error', 'El documento de identidad es obligatorio', 'error');
		respuesta = 0;
	}else if (codigojunta == "" || codigojunta == null){
		swal('Error', 'El código de la junta es obligatorio', 'error');
		respuesta = 0;
	}else if (fechainiciodano == "" || fechainiciodano == null){
		swal('Error', 'La fecha de inicio del daño es obligatoria', 'error');
		respuesta = 0;
	}else if (alfabetismo == "" || alfabetismo == null){
		swal('Error', 'El nivel de alfabetismo es obligatorio', 'error');
		respuesta = 0;
	}else if (convivencia == "" || convivencia == null){
		swal('Error', 'El tipo de convivencia es obligatorio', 'error');
		respuesta = 0;
	}else if (ingresomensual == "" || ingresomensual == null){
		swal('Error', 'El ingreso mensual es obligatorio', 'error');
		respuesta = 0;
	}else if (acompanante == "" || acompanante == null){
		swal('Error', 'Debe indicar si posee o no acompañante', 'error');
		respuesta = 0;
	}else if (fechaemision == "" || fechaemision == null){
		swal('Error', 'La fecha de emisión es obligatoria', 'error');
		respuesta = 0;
	}else if (ciudad == "" || ciudad == null){
		swal('Error', 'El lugar es obligatorio', 'error');
		respuesta = 0;
	}else if (modalidad == "" || modalidad == null){
		swal('Error', 'Seleccione la modalidad de la evaluación', 'error');
		respuesta = 0;
	}
	
	return respuesta;
}

//Activación o Inactivación de estados
$('#idestados').on('change', function (e) { 
	if (!removerEstados) {
		if(cargarEstadoSolicitud == 0){
			let estado = parseInt(this.value);
			
			if(estado == 1){ //No agendado
				let mostrar = [2,12,18,19];
				removerOpciones(estado,mostrar);
			}else if(estado == 2){ //Agendado
				let mostrar = [3,4,6,16];
				removerOpciones(estado,mostrar);
			}else if(estado == 12){ //Cancelado
				let mostrar = [12];
				removerOpciones(estado,mostrar);
			}else if(estado == 18){ //Desistió
				let mostrar = [18];
				removerOpciones(estado,mostrar);
			}else if(estado == 19){ //Falleció
				let mostrar = [19];
				removerOpciones(estado,mostrar);
			}else if(estado == 6){ //No asistió
				let mostrar = [6];
				removerOpciones(estado,mostrar);
			}else if(estado == 16){ //Pendiente
				let mostrar = [2,12];
				removerOpciones(estado,mostrar);
			}else if(estado == 3){ //Certificó
				let mostrar = [3];
				removerOpciones(estado,mostrar);
			}else if(estado == 4){ //No certificó
				let mostrar = [4];
				removerOpciones(estado,mostrar);
			}else if(estado == 27){ //Resolución de certificación generada
				let mostrar = [24];
				removerOpciones(estado,mostrar);
			}else if(estado == 28){ //Resolución de negatoria generada
				let mostrar = [5];
				removerOpciones(estado,mostrar);
			}else if(estado == 24){ //Pendiente por carnet
				let mostrar = [26];
				removerOpciones(estado,mostrar);
			}else if(estado == 26){ //Carnet impreso
				let mostrar = [29];
				removerOpciones(estado,mostrar);
			}else if(estado == 29){ //Por retirar documentos
				let mostrar = [30];
				removerOpciones(estado,mostrar);
			}else if(estado == 30){ //Finalizado
				let mostrar = [30];
				removerOpciones(estado,mostrar);
			}else if(estado == 5){ //Reconsideración
				let mostrar = [2,31];
				removerOpciones(estado,mostrar);
			}else if(estado == 31){ //Apelación
				let mostrar = [2,30];
				removerOpciones(estado,mostrar);
			}
		}
	}

	removerEstados = true;
	
});


let removerOpciones = (estado,mostrar) =>{
	$("#idestados option").each(function() {
		let val = parseInt($(this).val());	
		if(!mostrar.includes(val) && val != estado){
			$(`#idestados [value="${val}"]`).remove();
		}  
	});
}

function validarEstudiosComplementarios(concurrircon,estudioscomplementarios){
	var respuesta = 1;
	
	if (concurrircon == ''){
		swal('Error', 'El campo Deberá concurrir con, es obligatorio', 'error');
		respuesta = 0;
	}else if (estudioscomplementarios == ''){
		swal('Error', 'El Estudios complementarios es obligatorio', 'error');
		respuesta = 0;
	}
	
	return respuesta;
}
//Modal documentos pendientes
let guardarEstudiosComplementarios = () =>{
	let estadoPendiente = 16
	let concurrircon = $('#concurrircon').val();
	let estudioscomplementarios = $('#estudioscomplementarios').val();
	
	if(validarEstudiosComplementarios(concurrircon,estudioscomplementarios) == 1){
		$('#idestados').val(estadoPendiente).trigger('change');
		swal({
			title: "Confirmar",
			text: `El estado de la solicitud ha sido cambiado a Pendiente, para guardar la información debe presionar el botón guardar ¿Desea hacerlo ? `,
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){ 
				if (isConfirm.value === true) {
					$('#boton-borrador').click();
				}	
			}
		) 
	}
}

function generarFormatoNoAsistio() {
	// Aquí se generaría el formato PDF
	
	// Abrir ventana con Windows
	window.open('reporte/constancianoasistio.php');
  }
  
  function generarFormatoAsistenciaSolo() {
	// Aquí se generaría el formato PDF
	
	// Abrir ventana con Windows
	window.open('reporte/certificacionesconstancia.php');
  }
  
  function generarFormatoAsistenciaAcompanante() {
	// Aquí se generaría el formato PDF
	
	// Abrir ventana con Windows
	window.open('reporte/constanciadeasistencia.php');
  }