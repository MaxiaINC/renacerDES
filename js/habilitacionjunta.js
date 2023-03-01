var idhabilitacionjunta = getQueryVariable('id');
var idsmedicos = []; 
var idspacientes = []; 
var especialistasSeleccionados = [];
var pacientesSeleccionados = [];
var idsmedicosfin = [];
var idspacientesfin = [];
//var initialMinDate;

//Asignar fecha actual
var date = new Date();
var year = date.getFullYear();
var month = ('0' + (date.getMonth() + 1)).slice(-2);
var day = ('0' + date.getDate()).slice(-2);
var hours = ('0' + date.getHours()).slice(-2);
var minutes = ('0' + date.getMinutes()).slice(-2);
var seconds = ('0' + date.getSeconds()).slice(-2);
var dateString = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;   
document.getElementById('fecharesolucion').value = dateString;

	
$('#fecharesolucion').bootstrapMaterialDatePicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    lang: 'es',
    cancelText: 'Cancelar',
    okText: 'Aceptar',
	clearButton: true,
	clearText: 'Limpiar',
	onClose: function(clear) {
		if (clear) {
		  $('#fecharesolucion').val('');
		}
	}
}).on('change', function(e, date){
	
	let fecharesolucion = $('#fecharesolucion').val();
	let fechaevaluacion = $('#fechaevaluacion').val();
	
	if(fechaevaluacion != '' && fechaevaluacion < fecharesolucion){
		$('#fechaevaluacion').val('');
	}
	
	$('#fechaevaluacion').bootstrapMaterialDatePicker('setMinDate', date);	
	 
});;

$('#fechaevaluacion').bootstrapMaterialDatePicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    lang: 'es',
    cancelText: 'Cancelar',
    okText: 'Aceptar',
	clearButton: true,
	clearText: 'Limpiar',
    onClose: function(clear) {
        if (clear) {
            $('#fechaevaluacion').val('');
        }
    }
})

if(idhabilitacionjunta == ''){
	let initialMinDate = $('#fecharesolucion').val();
	console.log('ini',initialMinDate);
    $('#fechaevaluacion').bootstrapMaterialDatePicker('setMinDate', initialMinDate);
}

$("#listado").on('click',function(){
	location.href="habilitacionjuntas.php";
});

regionales();
function regionales(id){
    $.get("controller/combosback.php?oper=regionalesPorNivel", {}, function(result)
    {
        $("#idregionales").empty();
        $("#idregionales").append(result);
        if (id != 0){
			$("#idregionales").val(id).trigger('change');
        }
    });
}


$("#idregionales").on('select2:select', function (e) {
	
	let idregionales = $(this).val();
	
	if(idregionales != 0){
		//Asignar código de resolución
		asignarCodigoResolucion(idregionales);
		
		//Asignar número de junta
		//if(idhabilitacionjunta == ''){
			getUltimoNrojunta(idregionales);
		//}
	}else{
		$("#nroresolucion").val('');
	}
	
});

let asignarCodigoResolucion = (idregionales) =>{

	$('#overlay').css('display','block');
	$("#nroresolucion").val(''); 
	$.ajax({
        type: 'post',
        url: 'controller/habilitacionjuntasback.php',
        data: {
            'oper': 'asignarCodigoResolucion',
            'idregionales': idregionales
        },
        beforeSend: function() {
			$('#overlay').css('display','none');
		},
        success: function(response) {
			$('#nroresolucion').val(response);
        }
    });
}

let getUltimoNrojunta =(idregionales) =>{
	$('#overlay').css('display','block');
	$('#nrojunta').val('');
	$.ajax({
        type: 'post',
        url: 'controller/habilitacionjuntasback.php',
        data: {
            'oper': 'getUltimoNrojunta',
            'idregionales': idregionales
        },
        beforeSend: function() {
			$('#overlay').css('display','none');
		},
        success: function(response) {
			$('#nrojunta').val(response);
        }
    });
}
$('#idmedicos').select2({
	placeholder: 'Buscar',
	minimumInputLength: 6,
	language: {
		inputTooShort: function(args) {
		  var minLength = args.minimum - args.input.length;
		  return 'Por favor introduzca la cédula o nombre';
		},
		searching: function() {
		  return "Buscando...";
		}
	},
	ajax: {
		url: 'controller/combosback.php',
		type: 'GET',
		dataType: 'json',
		delay: 250,
		data: function(params) {
		return {
		  oper: 'especialistasArray',
		  search: params.term,
		  page: params.page
		};
		},
		processResults: function(data, params) {
			params.page = params.page || 1;
			var results = [];
			// Buscar solo los resultados que coincidan exactamente con el término de búsqueda
			if (params.term && data.items) {
				for (var i = 0; i < data.items.length; i++) {
				  if (data.items[i].id == params.term) {
					  
					results.push(data.items[i]);
					break;
				  }
				}
			}

			// Si no se encontró una coincidencia exacta, agregar el término de búsqueda a los resultados
			if (results.length == 0) {
				results.push({
					id: data[0].id,
					text: data[0].text
				});
			}

			return {
				results: results,
				pagination: {
				  more: false
				}
			};
		},
		cache: true,
		
	}
}).on('select2:select', function (e) {
	// Actualizar el valor del campo de texto cuando se selecciona un elemento de la lista
	var selectedData = e.params.data;
	$(this).val(selectedData.id).trigger('change');
});

$('#idpacientes').select2({
	placeholder: 'Buscar',
	minimumInputLength: 6,
	language: {
		inputTooShort: function(args) {
		  var minLength = args.minimum - args.input.length;
		  return 'Por favor introduzca la cédula o nombre';
		},
		searching: function() {
		  return "Buscando...";
		}
	},
	ajax: {
		url: 'controller/combosback.php',
		type: 'GET',
		dataType: 'json',
		delay: 250,
		data: function(params) {
		return {
		  oper: 'pacientesArray',
		  search: params.term,
		  page: params.page
		};
		},
		processResults: function(data, params) {
			params.page = params.page || 1;
			var results = [];
			// Buscar solo los resultados que coincidan exactamente con el término de búsqueda
			if (params.term && data.items) {
				
				for (var i = 0; i < data.items.length; i++) {
				  if (data.items[i].id == params.term) {
					results.push(data.items[i]);
					break;
				  }
				}
			}

			// Si no se encontró una coincidencia exacta, agregar el término de búsqueda a los resultados
			if (results.length == 0) {
			results.push({ id: data[0].id, text: data[0].text });
			}

			return {
				results: results,
				pagination: {
				  more: false
				}
			};
		},
		cache: true
	}
}).on('select2:select', function (e) {
	// Actualizar el valor del campo de texto cuando se selecciona un elemento de la lista
	var selectedData = e.params.data;
	$(this).val(selectedData.id).trigger('change');
}); 

const eliminarMedico = (id) => {
	$(`#medico_${id}`).remove();
	especialistasSeleccionados.splice(especialistasSeleccionados.indexOf(parseInt(id)), 1);
};

const eliminarPaciente = (id) => {
	$(`#paciente_${id}`).remove();
	pacientesSeleccionados.splice(pacientesSeleccionados.indexOf(parseInt(id)), 1);
};

function validarform(){
	let verdad 	= true;
    let idregionales = $('#idregionales').val();
    let nroresolucion = $('#nroresolucion').val();
    let nrojunta = $('#nrojunta').val();
    let fechaevaluacion = $('#fechaevaluacion').val();
    let fecharesolucion = $('#fecharesolucion').val();
	 
	
    if (idregionales == "" || idregionales == 0 || idregionales == undefined ){
		swal('Error', 'El campo Regional esta vacío', 'error');
		return false;
	}else if (nroresolucion == ""){
		swal('Error', 'El Número de resolución esta vacío', 'error');
		return false;
	}else if (nrojunta == ""){
		swal('Error', 'El Número de junta esta vacío', 'error');
		return false;
	}else if (fechaevaluacion == ""){
		swal('Error', 'La Fecha para la evaluación está vacía', 'error');
		return false;
	}else if (fecharesolucion == ""){
		swal('Error', 'La Fecha para la resolución está vacía', 'error');
		return false;
	}else if (idsmedicosfin.length === 0){
		swal('Error', 'Debe agregar los especialistas', 'error');
		return false;
	}else if (idsmedicosfin.length < 3){
		swal('Error', 'Debe seleccionar al menos 3 especialistas', 'error');
		return false;
	}else if (idspacientes.length === 0){
		swal('Error', 'Debe agregar los beneficiarios', 'error');
		return false;
	}

	return verdad;
}   

function guardar (){
	
	let idregionales = $('#idregionales').val();
	let nroresolucion = $('#nroresolucion').val();
	let nrojunta = $('#nrojunta').val();
	let fechaevaluacion = $('#fechaevaluacion').val();
	let fecharesolucion = $('#fecharesolucion').val();
	idsmedicos.length = 0;	
	idspacientes.length = 0;	
	
	let filasmedicos = $('#tabla_especialistas tbody tr');
	filasmedicos.map(function() { 
		idsmedicos.push($(this).attr('id'));
	});
	
	idsmedicosfin = idsmedicos.map(elemento => parseInt(elemento.replace('medico_', '')));
	
	let filaspacientes = $('#tabla_beneficiarios tbody tr');
	filaspacientes.map(function() { 
		idspacientes.push($(this).attr('id'));
	}); 
	idspacientesfin = idspacientes.map(elemento => parseInt(elemento.replace('paciente_', '')));
	
	if (validarform()){	
		
		if (idhabilitacionjunta == ''){
			var oper = "guardarHabilitacionJunta";
		}else{
			var oper = "actualizarHabilitacionJunta";
		}
		
		$.ajax({
			type: 'post',
			url: 'controller/habilitacionjuntasback.php',
			dataType: 'json',
			data: { 
				'oper'					: oper,				
				'idhabilitacionjunta'	: idhabilitacionjunta,
				'idregionales'			: idregionales,
				'nroresolucion'			: nroresolucion,
				'nrojunta'				: nrojunta,
				'fechaevaluacion'		: fechaevaluacion,
				'fecharesolucion'		: fecharesolucion,
				'idsmedicos'			: idsmedicosfin,
				'idspacientes'			: idspacientesfin
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			success: function (response) {
				$('#overlay').css('display','none');
				if(response !=  0){ 						
					swal("Buen trabajo","Habilitación de junta guardada satisfactoriamente","success"); 
					location.href="habilitacionjuntas.php";
				}else{
					swal('ERROR','Ha ocurrido un error al guardar la Habilitación de junta, por favor intente más tarde','error');	
				}
			},
			error: function () {
				swal('ERROR','Ha ocurrido un error al guardar la Habilitación de junta, por favor intente más tarde','error');	
				$('#overlay').css('display','none');							
			}
		}); 
	}
}

if(idhabilitacionjunta != '' || idhabilitacionjunta != false){
	getHabilitacionJuntas();	
} 

function getHabilitacionJuntas () {
	
	especialistasSeleccionados = [];
	pacientesSeleccionados = [];
	$.ajax({
		type: "post",
		url: 'controller/habilitacionjuntasback.php?id='+idhabilitacionjunta,
		data: {
            'oper': 'getHabilitacionJuntas',
        },
        beforeSend: function() {},
		success: function(response) {
			var resultado = JSON.parse(response);
			regionales(resultado.idregionales); 
			$('#nroresolucion').val(resultado.nroresolucion);
			$('#nrojunta').val(resultado.nrojunta);
			$('#fechaevaluacion').val(resultado.fechaevaluacion);  
			$('#fecharesolucion').val(resultado.fecharesolucion);  

			let initialMinDate = resultado.fecharesolucion;

			if (initialMinDate) {
				$('#fechaevaluacion').bootstrapMaterialDatePicker('setMinDate', initialMinDate);
			}
			// Recorremos el arreglo de médicos y creamos una fila en la tabla por cada uno
			var htmlmedicos = '';
			for (var i = 0; i < resultado.medicos.length; i++) {
				var medico = resultado.medicos[i].medico;
				var profesion = resultado.medicos[i].especialidad;
				var nroregistro = resultado.medicos[i].nroregistro;
				nroregistro = nroregistro == undefined ? '' : nroregistro;
				
				var cedulamedico = resultado.medicos[i].cedula;
				var idmedicos = resultado.medicos[i].id;
				
				especialistasSeleccionados.push(idmedicos)
				
				htmlmedicos += `<tr id="medico_${idmedicos}">
                  <td class="text-center"><span class="fa fa-minus-circle" onclick="eliminarMedico(${idmedicos})" style="color:#FF0000;font-size:1.5em;cursor:pointer;" title="Quitar especialista"></span></td>
                  <td>${medico}</td>
                  <td>${nroregistro}</td>
                  <td>${cedulamedico}</td>
                  <td>${profesion}</td>
                </tr>`;
			}
			$('#tabla_especialistas tbody').append(htmlmedicos);
			
			// Recorremos el arreglo de pacientes y creamos una fila en la tabla por cada uno
			var htmlpacientes = '';
			for (var j = 0; j < resultado.pacientes.length; j++) {
				var paciente = resultado.pacientes[j].paciente;
				var cedula = resultado.pacientes[j].cedula; 
				var idpacientes = resultado.pacientes[j].id; 
				
				pacientesSeleccionados.push(idpacientes)
				
				htmlpacientes += `<tr id="paciente_${idpacientes}">
                     <td class="text-center"><span class="fa fa-minus-circle" onclick="eliminarPaciente(${idpacientes})" style="color:#FF0000;font-size:1.5em;cursor:pointer;" title="Quitar especialista"></span></td>
											
                     <td>${paciente}</td>
                     <td>${cedula}</td>
                  </tr>`;
			}
			$('#tabla_beneficiarios tbody').append(htmlpacientes);
		}
	});
}


$("#anadir_especialista").on('click',function(){
	
	if($("#idmedicos").val() !== null &&$("#idmedicos").val() !== undefined && $("#idmedicos").val() != 0){
		
		
		
		if(especialistasSeleccionados.length >= 4){
            swal('ERROR','Ha alcanzado el límite de especialistas','error');
            return;
        }
		
		let id = $("#idmedicos").val();	 
		
		if(especialistasSeleccionados.includes(parseInt(id))){
			swal('ERROR','El especialista ya ha sido seleccionado','error');
			$("#idmedicos").val(null).trigger('change');
			return;
		}else{
			especialistasSeleccionados.push(parseInt(id));
		}
		
		jQuery.ajax({
			url: "controller/habilitacionjuntasback.php?oper=getMedicos&id="+id,
			dataType: "json",
			beforeSend: function(){
			$('#overlay').css('display','block');
			},success: function(item) {
			 $('#overlay').css('display','none'); 

			let html = `<tr id="medico_${item.id}">
              <td class="text-center"><span class="fa fa-minus-circle" onclick="eliminarMedico(${id})" style="color:#FF0000;font-size:1.5em;cursor:pointer;" title="Quitar especialista"></span></td>
              <td>${item.nombre} ${item.apellido}</td>
              <td>${item.nroregistro}</td>
              <td>${item.cedula}</td>
              <td>${item.especialidad}</td>
            </tr>`;
			
			$("#tabla_especialistas tbody").append(html);
			$("#idmedicos").val(null).trigger('change');
			}
		});
		
	}else{
		swal('ERROR','Debe seleccionar un especialista','error');
	}
});

$("#anadir_paciente").on('click',function(){
	if($("#idpacientes").val() !== null && $("#idpacientes").val() !== undefined && $("#idpacientes").val() != 0){
		
		let id = $("#idpacientes").val();
		
		if(pacientesSeleccionados.includes(parseInt(id))){
			swal('ERROR','El beneficiario ya ha sido seleccionado','error');
			$("#idpacientes").val(null).trigger('change');
			return;
		}else{
			pacientesSeleccionados.push(parseInt(id));
		}
		
		jQuery.ajax({
			url: "controller/habilitacionjuntasback.php?oper=getPacientes&id="+id,
			dataType: "json",
			beforeSend: function(){
			$('#overlay').css('display','block');
			},success: function(item) {
			 $('#overlay').css('display','none'); 

			let html = `<tr id="paciente_${item.id}">
              <td class="text-center"><span class="fa fa-minus-circle" onclick="eliminarPaciente(${item.id})" style="color:#FF0000;font-size:1.5em;cursor:pointer;" title="Quitar beneficiario"></span></td>
              <td>${item.nombre} ${item.apellidopaterno} ${item.apellidomaterno}</td>
              <td>${item.cedula}</td>
									 
            </tr>`;
			
			$("#tabla_beneficiarios tbody").append(html);
			  $("#idpacientes").val(null).trigger('change');
			}
		}); 
		
	}else{
		swal('ERROR','Debe seleccionar un beneficiario','error');
	}
});
$("#idregionales").select2({ language: "es" });