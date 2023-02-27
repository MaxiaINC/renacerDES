






	$("#agregar_acompanante").on('click',function(){
		$("#modal-nuevoacompanante").modal('show');
		if(
			($("#idprovincias_ac").val() == 0 || $("#idprovincias_ac").val() == undefined ) &&
			($("#iddistritos_ac").val() == 0 || $("#iddistritos_ac").val() == undefined ) &&
			($("#idcorregimientos_ac").val() == 0 || $("#idcorregimientos_ac").val() == undefined ) &&
			$("#area_ac").val() == '' && $("#urbanizacion_ac").val() == '' && $("#calle_ac").val() == '' 
			&& $("#numero_ac").val() == ''
		){
			 swal({
				title: "Confirmar",
				text: "¿Desea seleccionar la misma dirección del paciente para el acompañante?",
				type: "warning",
				showCancelButton: true,
				cancelButtonColor: 'red',
				confirmButtonColor: '#09b354',
				confirmButtonText: 'Si',
				cancelButtonText: "No"
			}).then(
				function(isConfirm){
					if(isConfirm.value == true){
						//Se asignan valores del paciente al acompañante
						if($("#idprovincias").val() != 0 || $("#idprovincias").val() != undefined) $("#idprovincias_ac").val($("#idprovincias").val()).trigger("change");
						distritos_ac($("#iddistritos").val(), $("#idprovincias").val());
						corregimientos_ac($("#idcorregimientos").val(), $("#idprovincias").val(), $("#iddistritos").val());
						if($("#idcorregimientos").val() != 0 || $("#idcorregimientos").val() != undefined) $("#idcorregimientos_ac").val($("#idcorregimientos").val()).trigger("change");
						if($("#area").val() != 0 || $("#area").val() != undefined) $("#area_ac").val($("#area").val());  
						if($("#urbanizacion").val() != 0 || $("#urbanizacion").val() != undefined) $("#urbanizacion_ac").val($("#urbanizacion").val());  
						if($("#calle").val() != 0 || $("#calle").val() != undefined) $("#calle_ac").val($("#calle").val());   
						if($("#numerocasa").val() != 0 || $("#numerocasa").val() != undefined) $("#numero_ac").val($("#numerocasa").val()); 
					}
				},
				function(isRechazo) {
					console.log(isRechazo);
				}
			);
		}  
	});

//ARCHIVO DONDE SE ENCUENTRA TODA LA PROGRAMACION DE LOS MODALES DE PACIENTE
//NUEVO PACIENTE
//EDITAR PACIENTE
var currentDate = new Date();
$("#fecha_nac_ac").bootstrapMaterialDatePicker({
	weekStart:0, 
	format:'YYYY-MM-DD', 
	switchOnClick:true, 
	time:false,
	maxDate:currentDate
});
	
$("#tipodocumento_ac").on('change',function(){
	$("#cedula_ac").removeClass('cedula');
	$("#cedula_ac").val("");
	$("#cedula_ac").off();
	if($(this).val()!=0){
		var texto = $(this).select2('data')[0].element.text;
		$("#cedula_ac").prop('disabled',false);
		if($(this).val()==1){
			$("#cedula_ac").addClass('cedula');
			$("#cedula_ac").on('blur', function(){
				if( $(this).val() != '' ){
					if( !validar_cedula($(this).val()) ){
						$.when(swal('Error!','Formato de cédula inválido','error')).done(function(){$(this).focus();});
					}			
				}		
			});		
		}
	}else{
		var texto = '';
		$("#cedula_ac").prop('disabled',true);
	}
	$("#tipodocumento_txt_ac").html(texto);
});
	
$('#tipoacompanante').on('change', function(e) {
	console.log('tipoacompanante: '+$(this).val());
	if ($(this).val() == '4'||$(this).val() == '5') {
		$("#modal-nuevoacompanante-div_tutor").css("display","block");
	}else{
		$("#modal-nuevoacompanante-div_tutor").css("display","none");
	}
});

$('[data-toggle="tooltip"]').tooltip();

$("#modal-nuevoacompanante-guardar").on('click',function(){
	if($("#idacompanante").val() == ''){
		guardar_acompanante();
	}else{
		update_acompanante();
	}
});
$("#modal-nuevoacompanante-cancelar").on('click',function(){
	limpiar('modal-nuevoacompanante');
});

//COMBOS
function provincias_ac(id){
    $.get("controller/combosback.php?oper=provincia", {}, function(result)
    {
        $("#idprovincias_ac").empty();
        $("#idprovincias_ac").append(result);
        if (id != 0){
			$("#idprovincias_ac").val(id).trigger('change');
        }
    });
}
$("#idprovincias_ac").on('select2:select', function(e) {
	var id = 0;
	var provincia = $(this).val();
	distritos_ac(id, provincia);
});
function distritos_ac(id, provincia){
    $.get("controller/combosback.php?oper=distrito", { provincia: provincia }, function(result)
    {
        $("#iddistritos_ac").empty();
        $("#iddistritos_ac").append(result);
        if (id != 0){
			$("#iddistritos_ac").val(id).trigger('change');
        }
    });
}
$("#iddistritos_ac").on('select2:select', function(e) {
	var id = 0;
	var provincia = $("#idprovincias_ac").val();
	var distrito = $(this).val();
	corregimientos_ac(id, provincia, distrito);
});
function corregimientos_ac(id, provincia, distrito){
    $.get("controller/combosback.php?oper=corregimiento", { provincia: provincia, distrito: distrito }, function(result)
    {
        $("#idcorregimientos_ac").empty();
        $("#idcorregimientos_ac").append(result);
        if (id != 0){
			$("#idcorregimientos_ac").val(id).trigger('change');
        }
    });
}
$("#idcorregimientos_ac").on('select2:select', function(e) {
	var area = $('#idcorregimientos_ac option:selected').attr('data-area');
	$("#area_ac").val(area);
});
provincias_ac(0);
distritos_ac(0);
corregimientos_ac(0);

	function guardar_acompanante(){
		$('#overlay').css('display', 'block');
		var dataserialize = $('#form_acompanante').serializeArray();
		var datos = {};
		for (var i in dataserialize){
			//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
			if( dataserialize[i].name == 'tipodocumento_ac' || dataserialize[i].name == 'sexo_ac' ||
				dataserialize[i].name == 'estado_civil_ac' || dataserialize[i].name == 'idprovincias_ac' ||
				dataserialize[i].name == 'iddistritos_ac' || dataserialize[i].name == 'idcorregimientos_ac' ||
				dataserialize[i].name == 'tipotutor_ac' ){
				datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
			}else{
				datos[dataserialize[i].name] = dataserialize[i].value;
			}
		}
		var iddireccion = $('#idcorregimientos_ac option:selected').attr('data-id');
 
		var modo_tutor = '0';
		var sentencia = '';
		var juzgado = '';
		var circuito_judicial = '';
		var distrito_judicial = '';	

		if ($("#tipoacompanante").val() == '4'||$("#tipoacompanante").val() == '5') { 
			var sentencia = $("#modal-nuevoacompanante-nro_sentencia").val();	
			var juzgado = $("#modal-nuevoacompanante-juzgado").val();	
			var circuito_judicial = $("#modal-nuevoacompanante-circuito_judicial").val();	
			var distrito_judicial = $("#modal-nuevoacompanante-distrito_judicial").val();	
		}

		if(validarformNA(datos) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/beneficiariosback.php',
				dataType: 'json',
				data: { 
					'oper'		: 'crear_acompanante',				
					'arreglo'	: datos,
					'iddireccion' : iddireccion
				},
				success: function (response) {
					if(response !=  0){
						$('#overlay').css('display','none');						
						console.log(response);	
						$("#idacompanante").val(response.id);
						$("#td_acompanante").val(response.tipodocumento).trigger('change');
						$("#nombre_acompanante").val(response.nombre);
						$("#cedula_acompanante").val(response.cedula);
						$("#modal-nuevoacompanante-iddireccion").val(response.iddireccion); 
						$("#requiere_acompanante").val('SI').trigger('change');
						swal("¡Buen trabajo!","Acompañante creado satisfactoriamente","success");		
						$("#modal-nuevoacompanante").modal('hide');	
						$(".datosac").removeClass("d-none");							
					}else{
						swal('ERROR!','Ha ocurrido un error al guardar, por favor intente más tarde','error');	
						$('#overlay').css('display','none');
					}
				},
				error: function () {
					swal('ERROR!','Ha ocurrido un error al guardar, por favor intente más tarde','error');	
					$('#overlay').css('display','none');
				}
			});
		}else{
			$('#overlay').css('display','none');
		}
	}

	function update_acompanante(){
		var arreglo = {};
		var direccion = $("#modal-nuevoacompanante-iddireccion").val();
		var idacompanante = $("#idacompanante").val();
		var nombre = $("#nombre_ac").val();
		var apellido = $("#apellido_ac").val(); 
		var celular = $("#celular_ac").val();
		var telefono = $("#telefono_ac").val();
		var correo = $("#correo_ac").val();
		var fecha_nac = $("#fecha_nac_ac").val().split(" ")[0];
		var tipodocumento = $('#tipodocumento_ac').val();
		var cedula = $("#cedula_ac").val();
		var sexo = $('#sexo_ac').val();
		var nacionalidad = $("#nacionalidad_ac").val();
		var edocivil= $('#estado_civil_ac').val();	
	
		var urbanizacion = $("#urbanizacion_ac").val();
		var calle = $("#calle_ac").val();
		var edificio = $("#edificio_ac").val();
		var numero = $("#numero_ac").val();
		//var iddireccion = $("#idcorregimientos_ac").select2('data')[0].element.dataset.id;
		var iddireccion = $('#idcorregimientos_ac option:selected').attr('data-id');
		var modo_tutor = '0';
		var sentencia = '';
		var juzgado = '';
		var circuito_judicial = '';
		var distrito_judicial = '';	

		if ($("#tipoacompanante").val() == '4'||$("#tipoacompanante").val() == '5') {
			var modo_tutor = $('tipotutor_ac').val();	
			var sentencia = $("#sentencia_ac").val();	
			var juzgado = $("#juzgado_ac").val();	
			var circuito_judicial = $("#circuito_judicial_ac").val();	
			var distrito_judicial = $("#distrito_judicial_ac").val();	
		}
			

		arreglo['acompanante']={
			"nombre": nombre,
			"apellido": apellido,
			"celular": celular,
			"cedula": cedula,
			"telefono": telefono,
			"correo": correo,
			"fecha_nac": fecha_nac,
			"tipodocumento": tipodocumento,
			"nacionalidad": nacionalidad,
			"sexo": sexo,
			"edocivil": edocivil,
			"modo_tutor":modo_tutor,
			"sentencia":sentencia,
			"juzgado":juzgado,
			"circuito_judicial":circuito_judicial,
			"distrito_judicial":distrito_judicial
				
		};

		arreglo['direccion']={
			"urbanizacion": urbanizacion,
			"calle": calle,
			"edificio": edificio,
			"numero": numero,
			"iddireccion": iddireccion
		};
		$.ajax({
			type: 'post',
			url: 'controller/beneficiariosback.php',
			dataType: 'json',
			data: { 
				'oper'		: 'update_acompanante',				
				'arreglo'	:arreglo,
				'direccion' : direccion,
				'idacompanante' : idacompanante
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			success: function (response) {
				if(response !=  0){
					$('#overlay').css('display','none');						
					console.log(response);	
					$("#idacompanante").val(response.id);
					$("#nombre_acompanante").val(response.nombre);
					$("#cedula_acompanante").val(response.cedula);
					swal("¡Buen trabajo!","Acompañante modificado satisfactoriamente","success");		
					$("#modal-nuevoacompanante").modal('hide');		
			
				}else{
					swal('ERROR!','Ha ocurrido un error al guardar, por favor intente más tarde','error');	
					$('#overlay').css('display','none');
				}
			},
			error: function () {
					swal('ERROR!','Ha ocurrido un error al guardar, por favor intente más tarde','error');	
				$('#overlay').css('display','none');							
			}
		});		
	}
	
	function validarformNA(datos){
		var respuesta = 1;
		
		if (datos['cedula_ac'] == "" || datos['cedula_ac'] == null){
			swal('Error', 'El n&uacute;mero de documento es obligatorio', 'error');
			respuesta = 0;
		}else if (datos['nombre_ac'] == "" || datos['nombre_ac'] == null){
			swal('Error', 'El nombre es obligatorio', 'error');
			respuesta = 0;
		}else if (datos['apellido_ac'] == "" || datos['apellido_ac'] == null){
			swal('Error', 'El apellido es obligatorio', 'error');
			respuesta = 0;
		}else if (datos['celular_ac'] == "" || datos['celular_ac'] == null){
			swal('Error', 'El celular es obligatorio', 'error');
			respuesta = 0;
		}else if (datos['fecha_nac_ac'] == "" || datos['fecha_nac_ac'] == null){
			swal('Error', 'La fecha de nacimiento es obligatoria', 'error');
			respuesta = 0;
		}else if (datos['nacionalidad_ac'] == "" || datos['nacionalidad_ac'] == null){
			swal('Error', 'La nacionalidad es obligatoria', 'error');
			respuesta = 0;
		}else if (datos['sexo_ac'] == "" || datos['sexo_ac'] == null || datos['sexo_ac'] == 0){
			swal('Error', 'El sexo es obligatorio', 'error');
			respuesta = 0;
		}else if (datos['idprovincias_ac'] == "" || datos['idprovincias_ac'] == null || datos['idprovincias_ac'] == 0){
			swal('Error', 'La provincia es obligatoria', 'error');
			respuesta = 0;
		}else if (datos['iddistritos_ac'] == "" || datos['iddistritos_ac'] == null || datos['iddistritos_ac'] == 0){
			swal('Error', 'El distrito es obligatorio', 'error');
			respuesta = 0;
		}else if (datos['idcorregimientos_ac'] == "" || datos['idcorregimientos_ac'] == null || datos['idcorregimientos_ac'] == 0){
			swal('Error', 'El corregimiento es obligatorio', 'error');
			respuesta = 0;
		}
		
		return respuesta;
	}