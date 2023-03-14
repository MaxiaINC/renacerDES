var cargarEstadoSolicitud = 0;
var tienereconsideracion, tieneapelacion, tieneevaluacion;
var removerEstados = false;
var nivelSoloVer = [2,10,11,16];//Legal, Miembro de juntas, Consultas, Auditor
var nivelLegal = [1,15,2]; 
var nivelImpresion = [1,15,14];

//COMBOS
function regionales(id){
    $.get("controller/combosback.php?oper=regionales", {}, function(result)
    {
        $("#lugarsolicitud").empty();
        $("#lugarsolicitud").append(result);
        if (id != 0){
			$("#lugarsolicitud").val(id).trigger('change');
        }
    });
}
function estados(id){
    $.get("controller/combosback.php?oper=estadossolicitudes", {}, function(result)
    {
        $("#estadosolicitud").empty();
        $("#estadosolicitud").append(result);
        if (id != 0){
			$("#estadosolicitud").val(id).trigger('change');
        }
    });
}
regionales(0);
estados(0);

$('#requiere_acompanante').on('select2:select', function (e) {
	$(this).val() == 'SI' ? $("#agregar_acompanante").click() : $(".datosac").addClass("d-none")
});

$("#fecha_sol").bootstrapMaterialDatePicker({
	date: true,
	format:'YYYY-MM-DD HH:mm:ss',
	time:true,
	lang: 'es',
})

/* $("#td_acompanante").on('change',function(){
	$("#cedula_acompanante").removeClass('cedula');
	$("#cedula_acompanante").val("");
	$("#cedula_acompanante").off();
	if($(this).val()!=0){
		var texto = $(this).select2('data')[0].element.text;
		$("#cedula_acompanante").prop('disabled',false);
		if($(this).val()==1){
			$("#cedula_acompanante").addClass('cedula_acompanante');
			$("#cedula_acompanante").on('blur', function(){
				if( $(this).val() != '' ){
					if( !validar_cedula($(this).val()) ){
						$.when(swal('Error','Formato de cédula inválido','error')).done(function(){$(this).focus();});    					    
					}else{
						var tipo_documento = $("#td_acompanante").val();
						var cedula = $("#cedula_acompanante").val();
						$.ajax({
							type: "POST",
							dataType: "json",
							url: 'controller/beneficiariosback.php',
							data: { oper: 'existe_ac', tipo_documento: tipo_documento, cedula: cedula },
							success: function( response ) { 
								if(response.success == true){
									$('#idacompanante').val(response.id);
									$('#nombre_acompanante').val(response.nombre).attr('disabled','disabled');
									//$.when(swal('Error','El número de documento ya existe','error')).done(function(){$(this).focus();});
								}else{
									$('#nombre_acompanante').val(response.nombre).removeAttr('disabled');
									$('#nombre_acompanante').focus();
								}
							},
							error: function( error ){
								alert( error );
							}
						});
					}
				}
			});		
		}
	}else{
		$("#cedula_acompanante").prop('disabled',true);
	}
	$("#tipodocumento_txt_ac").html(texto+' <span class="text-red">*</span>');
}); */

$("#cancelar").on('click',function(){
    swal({
        title: "Confirmar",
        text: "¿Esta seguro de cancelar el registro?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: 'red',
        confirmButtonColor: '#09b354',
        confirmButtonText: 'Si',
        cancelButtonText: "No"
    }).then(
		function(isConfirm){
            if(isConfirm.value == true){
                location.href = 'solicitudes.php';
            }
        },
        function(isRechazo) {
            console.log(isRechazo);
        }
    );   
});

$("#listadoSolicitudes").on("click",function(){
	location.href = 'solicitudes.php';
});

setIdSol();
function setIdSol() {
    if(getQueryVariable('idsolicitud')){
        $('#idsolicitud').val(getQueryVariable('idsolicitud'));
		$('.boxexpediente').css('display','block');
		$('.estadosolicitud').css('display','block');
		
    }else{
		$('.boxexpediente').css('display','none');
		$('.estadosolicitud').css('display','none');
		
		//Asignar fecha automática actual a la solicitud
		let fecha = new Date();
		let fechaFormateada = fecha.toISOString().slice(0, 19).replace('T', ' ');
		$("#fecha_sol").val(fechaFormateada);
		
	}
}

function peticion(metodo, url, parametros){
	return new Promise((resolve, reject) => {
		$.ajax({
			url: url,
			type: metodo,
			data: parametros,
			dataType: "json",
			success: function(response){
				resolve(response)
			}
		});
	})
}  
if(getQueryVariable('idsolicitud')){
	consumir();
} 

async function consumir(){
	var idsolicitud = $('#idsolicitud').val();
	console.log('idsolicitud',idsolicitud)
	await peticion("POST","controller/solicitudesback.php?oper=getdatossolicitud", { idsolicitud: idsolicitud }).then(function(response){
		tienereconsideracion = parseInt(response.reconsideracion);
		tieneapelacion = parseInt(response.apelacion); 
		tieneevaluacion = parseInt(response.tieneevaluacion); 

		proyecto = response;
		//Datos de la solicitud
		$('#lugarsolicitud').val(proyecto.regional).trigger('change');
		$('#tipodiscapacidad').val(proyecto.iddiscapacidad).trigger('change');
		$('#tiposolicitud').val(proyecto.tiposolicitud).trigger('change');
		$('#estadosolicitud').val(response.idestatus).trigger('change');
		$('#fecha_sol').val(proyecto.fecha_solicitud);
		$('#cssolicitud').val(proyecto.condicionsalud);
		$('#observaciones').val(proyecto.observaciones);
		$('#tipoacompanante').val(proyecto.tipoacompanante).trigger('change');

		//Acompañante
		if(proyecto.idacompanante != '0'){
			$('#requiere_acompanante').val('SI').trigger('change');
			$(".datosac").removeClass("d-none");	
			$("#boton-editar-acompanante").show();
			$.get('controller/solicitudesback.php?oper=getDatosAcompanantes&idacompanante='+proyecto.idacompanante,function(response){
				$("#datos-acompanante").show();
				$("#datos-acompanante").find("input").each(function(){
					$(this).addClass('mandatorio');
				});
				$("#idacompanante, #idacompanante_ac").val(response.id);
				if(response.id != ''){
					$("#agregar_acompanante").removeClass('fa fa-plus-circle');
					$("#agregar_acompanante").addClass('fa fa-eye');
					$("#agregar_acompanante").removeAttr('title');
					$("#agregar_acompanante").attr('data-original-title','Ver acompañante');
					$("#agregar_acompanante").attr('title','Ver acompañante');
				}
				$('#td_acompanante, #td_acompanante_ac, #tipodocumento_ac').val(response.tipo_documento).trigger('change');
				$("#cedula_acompanante, #cedula_ac").val(response.cedula);
				$("#nombre_acompanante").val(response.nombre);
				$("#nombre_ac").val(response.nombre_ac);
				$("#apellido_ac").val(response.apellido_ac);
				$("#celular_ac").val(response.celular);
				$("#telefono_ac").val(response.telefono);
				$("#correo_ac").val(response.correo);
				$("#fecha_nac_ac").val(response.fecha_nac);
				$("#nacionalidad_ac").val(response.nacionalidad);
				$("#sexo_ac").val(response.sexo).trigger('change');
				$("#estado_civil_ac").val(response.estado_civil).trigger('change');
				provincias_ac(response.provincia);
				distritos_ac(response.distrito,response.provincia);
				corregimientos_ac(response.corregimiento,response.provincia,response.distrito);
				//$("#idprovincias_ac").val(response.provincia).trigger('change');
				//$("#iddistritos_ac").val(response.distrito).trigger('change');
				//$("#idcorregimientos_ac").val(response.corregimiento).trigger('change');
				$("#area_ac").val(response.area_ac);
				$("#urbanizacion_ac").val(response.urbanizacion);
				$("#calle_ac").val(response.calle);
				$("#edificio_ac").val(response.edificio);
				$("#numero_ac").val(response.numero);
				$("#tipotutor_ac").val(response.tipotutor_ac);
				$("#sentencia_ac").val(response.sentencia);
				$("#juzgado_ac").val(response.juzgado);
				$("#circuito_judicial_ac").val(response.circuito_judicial);
				$("#distrito_judicial_ac").val(response.distrito_judicial);
				
				//Dirección acompañante
				$("#modal-nuevoacompanante-iddireccion").val(response.direccion); 
			},'json');
		}else{
			$('#requiere_acompanante').val('NO').trigger('change');
		}
		//COMENTARIOS
		abrirComentarios(idsolicitud);
	})
	//PACIENTE 
	$.get('controller/solicitudesback.php?oper=get_pacienteporsolicitud&id='+idsolicitud,function(response){
		$('#idbeneficiario').val(response.id)
		fillForm();
	},'json');
}

//fillFormSolicitud();
/* function fillFormSolicitud() {

	if($('#idsolicitud').val()){
		//SOLICITUD
		var idsolicitud = $('#idsolicitud').val();
		$.post("controller/solicitudesback.php?oper=getdatossolicitud", { idsolicitud: idsolicitud }, function(response){
			const proyecto = JSON.parse(response);
			//Datos de la solicitud
			$('#lugarsolicitud').val(proyecto.regional).trigger('change');
			$('#tipodiscapacidad').val(proyecto.iddiscapacidad).trigger('change');
			$('#tiposolicitud').val(proyecto.tiposolicitud).trigger('change');
			$('#estadosolicitud').val(proyecto.idestatus).trigger('change');
			$('#fecha_sol').val(proyecto.fecha_solicitud);
			$('#cssolicitud').val(proyecto.condicionsalud);
			$('#observaciones').val(proyecto.observaciones);
			$('#tipoacompanante').val(proyecto.tipoacompanante).trigger('change');
			$('#tienereconsideracion').val(proyecto.reconsideracion);
			$('#tieneapelacion').val(proyecto.apelacion);			
			tienereconsideracion = proyecto.reconsideracion;
			tieneapelacion = proyecto.apelacion; 

			//cargarEstadoSolicitud = 1;

			//Acompañante
			if(proyecto.idacompanante != '0'){
				$('#requiere_acompanante').val('SI').trigger('change');
				$(".datosac").removeClass("d-none");	
				$("#boton-editar-acompanante").show();
				$.get('controller/solicitudesback.php?oper=getDatosAcompanantes&idacompanante='+proyecto.idacompanante,function(response){
					$("#datos-acompanante").show();
					$("#datos-acompanante").find("input").each(function(){
						$(this).addClass('mandatorio');
					});
					$("#idacompanante, #idacompanante_ac").val(response.id);
					if(response.id != ''){
						$("#agregar_acompanante").removeClass('fa fa-plus-circle');
						$("#agregar_acompanante").addClass('fa fa-eye');
						$("#agregar_acompanante").removeAttr('title');
						$("#agregar_acompanante").attr('data-original-title','Ver acompañante');
						$("#agregar_acompanante").attr('title','Ver acompañante');
					}
					$('#td_acompanante, #td_acompanante_ac, #tipodocumento_ac').val(response.tipo_documento).trigger('change');
					$("#cedula_acompanante, #cedula_ac").val(response.cedula);
					$("#nombre_acompanante").val(response.nombre);
					$("#nombre_ac").val(response.nombre_ac);
					$("#apellido_ac").val(response.apellido_ac);
					$("#celular_ac").val(response.celular);
					$("#telefono_ac").val(response.telefono);
					$("#correo_ac").val(response.correo);
					$("#fecha_nac_ac").val(response.fecha_nac);
					$("#nacionalidad_ac").val(response.nacionalidad);
					$("#sexo_ac").val(response.sexo).trigger('change');
					$("#estado_civil_ac").val(response.estado_civil).trigger('change');
					provincias_ac(response.provincia);
					distritos_ac(response.distrito,response.provincia);
					corregimientos_ac(response.corregimiento,response.provincia,response.distrito);
					//$("#idprovincias_ac").val(response.provincia).trigger('change');
					//$("#iddistritos_ac").val(response.distrito).trigger('change');
					//$("#idcorregimientos_ac").val(response.corregimiento).trigger('change');
					$("#area_ac").val(response.area_ac);
					$("#urbanizacion_ac").val(response.urbanizacion);
					$("#calle_ac").val(response.calle);
					$("#edificio_ac").val(response.edificio);
					$("#numero_ac").val(response.numero);
					$("#tipotutor_ac").val(response.tipotutor_ac);
					$("#sentencia_ac").val(response.sentencia);
					$("#juzgado_ac").val(response.juzgado);
					$("#circuito_judicial_ac").val(response.circuito_judicial);
					$("#distrito_judicial_ac").val(response.distrito_judicial);
					
					//Dirección acompañante
					$("#modal-nuevoacompanante-iddireccion").val(response.direccion); 
				},'json');
			}else{
				$('#requiere_acompanante').val('NO').trigger('change');
			}
			//COMENTARIOS
			abrirComentarios(idsolicitud);
		});
		//PACIENTE 
		$.get('controller/solicitudesback.php?oper=get_pacienteporsolicitud&id='+idsolicitud,function(response){
			$('#idbeneficiario').val(response.id)
			fillForm();
		},'json');
	}
} */



$("#guardar-solicitud").on("click",function(){
	guardarSolicitud();
});

function guardarSolicitud(){
    $('#preloader').css('display', 'block');
	var idsolicitud = getQueryVariable('idsolicitud');
	var idbeneficiario = $('#idbeneficiario').val();
	//DATOS SOLICITUD
	var dataserializeSol = $('#form_solicitud').serializeArray();
	var datosSol = {};
	for (var i in dataserializeSol){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserializeSol[i].name == 'requiere_acompanante' || dataserializeSol[i].name == 'tipoacompanante' ||
			dataserializeSol[i].name == 'td_acompanante' ){
			datosSol[dataserializeSol[i].name] = $("#"+dataserializeSol[i].name).select2("val");
		}else{
			datosSol[dataserializeSol[i].name] = dataserializeSol[i].value;
		}
	}
	datosSol['cedula'] = $('#cedula').val();
	//ACOMPANANTE 
	var dataserializeSolAc = $('#form_solicitud_acompanante').serializeArray();
	var datosSolAc = {};
	for (var i in dataserializeSolAc){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserializeSolAc[i].name == 'lugarsolicitud' || dataserializeSolAc[i].name == 'tipodiscapacidad' ||
			dataserializeSolAc[i].name == 'estadosolicitud' ){
			datosSolAc[dataserializeSol[i].name] = $("#"+dataserializeSolAc[i].name).select2("val");
		}else{
			datosSolAc[dataserializeSolAc[i].name] = dataserializeSolAc[i].value;
		}
	}
	
	//DATOS BENEFICIARIO
    var dataserialize = $('#form_beneficiario').serializeArray();
	var datos = {};
	for (var i in dataserialize){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'tipodocumento' || dataserialize[i].name == 'sexo' ||
			dataserialize[i].name == 'estado_civil' || dataserialize[i].name == 'idprovincias' ||
			dataserialize[i].name == 'iddistritos' || dataserialize[i].name == 'idcorregimientos' ||
			dataserialize[i].name == 'condicion_actividad' || dataserialize[i].name == 'categoria_actividad' ||
			dataserialize[i].name == 'beneficios'){
			datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else if(dataserialize[i].name == 'cobertura_medica'){
			datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val").join();
		}else{
			datos[dataserialize[i].name] = dataserialize[i].value;
		}
	}
	datos['expediente'] = $('#expediente').val();
	datos['idacompanante'] = $('#idacompanante').val();
	//DATOS ACOMPAÑANTE
	var dataserializeAc = $('#form_acompanante').serializeArray();
	var datosAc = {};
	for (var i in dataserializeAc){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserializeAc[i].name == 'requiere_acompanante' || dataserializeAc[i].name == 'tipo_acompanante' ||
			dataserializeAc[i].name == 'td_acompanante' ){
			datosAc[dataserializeAc[i].name] = $("#"+dataserializeAc[i].name).select2("val");
		}else{
			datosAc[dataserializeAc[i].name] = dataserializeAc[i].value;
		}
	}
	let msj = '';
    if (idsolicitud == ''){
        var oper = "guardar_solicitud";
		msj = 'creada';
    }else{
        var oper = "guardar_solicitud";
		msj = 'modificada';
    }
	
	//Validar datos del beneficiario
    if (validarform()){
		
		if(idsolicitud == ""){
			if (idbeneficiario == ''){
				var operbeneficiario = "guardar_paciente";
			}else{
				var operbeneficiario = "editar_paciente";
			}
			$.ajax({
				url: "controller/beneficiariosback.php",
				type: "POST",
				data: {
					oper: operbeneficiario,
					datos: datos,
					id: ''
				},
				dataType: "json",
				success: function(response){ 
					$('#preloader').css('display', 'none');
					if (response.success == true){
						//GUARDAR SOLICITUD
						//Validar datos de la solicitud
						if (validarformSol()){
							$.ajax({
								url: "controller/solicitudesback.php",
								type: "POST",
								data: {
									oper: oper,
									datos: datosSol,
									datosAc: datosAc,
									datosSolAc: datosSolAc,
									id: idsolicitud,
									idbeneficiario: response.idpaciente
								},
								dataType: "json",
								success: function(responseSol){
									$('#preloader').css('display', 'none');
									if (responseSol.success == true){ 
										swal('Buen trabajo', `Solicitud ${msj} satisfactoriamente`, 'success');
										location.href = "solicitudes.php"; 
									}else{
										swal('Error', 'Error al '+oper+' la solicitud', 'error');
									}
								}
							});
						}else{
							$('#preloader').css('display', 'none');
						}
					}else{
						swal('Error',response.msj, 'error');
					}
				}
			});
		}else{
			let ced = $('#cedula').val();
			let cedbd = $('#cedulabd').val();
			if(ced != cedbd){
				swal({
					title: "Confirmar",
					text: `El campo cédula ha sido modificado, está seguro de realizar esta acción ?`,
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){ 
						if (isConfirm.value === true) {
							if (idbeneficiario == ''){
								var operbeneficiario = "guardar_paciente";
							}else{
								var operbeneficiario = "editar_paciente";
							}
							$.ajax({
								url: "controller/beneficiariosback.php",
								type: "POST",
								data: {
									oper: operbeneficiario,
									datos: datos,
									id: ''
								},
								dataType: "json",
								success: function(response){ 
									$('#preloader').css('display', 'none');
									if (response.success == true){
										//GUARDAR SOLICITUD
										if (validarformSol()){
											$.ajax({
												url: "controller/solicitudesback.php",
												type: "POST",
												data: {
													oper: oper,
													datos: datosSol,
													datosAc: datosAc,
													datosSolAc: datosSolAc,
													id: idsolicitud,
													idbeneficiario: response.idpaciente
												},
												dataType: "json",
												success: function(responseSol){
													$('#preloader').css('display', 'none');
													if (responseSol.success == true){ 
														swal('Buen trabajo', `Solicitud ${msj} satisfactoriamente`, 'success');
														location.href = "solicitudes.php"; 
													}else{
														swal('Error', 'Error al '+oper+' la solicitud', 'error');
													}
												}
											});
										}else{
											$('#preloader').css('display', 'none');
										}
									}else{
										swal('Error',response.msj, 'error');
									}
								}
							});
						}else{
							$('#preloader').css('display', 'none');
						}
					}
				);
			}else{
				if (idbeneficiario == ''){
					var operbeneficiario = "guardar_paciente";
				}else{
					var operbeneficiario = "editar_paciente";
				}
				$.ajax({
					url: "controller/beneficiariosback.php",
					type: "POST",
					data: {
						oper: operbeneficiario,
						datos: datos,
						id: ''
					},
					dataType: "json",
					success: function(response){ 
						$('#preloader').css('display', 'none');
						if (response.success == true){
							//GUARDAR SOLICITUD
							if (validarformSol()){
								$.ajax({
									url: "controller/solicitudesback.php",
									type: "POST",
									data: {
										oper: oper,
										datos: datosSol,
										datosAc: datosAc,
										datosSolAc: datosSolAc,
										id: idsolicitud,
										idbeneficiario: response.idpaciente
									},
									dataType: "json",
									success: function(responseSol){
										$('#preloader').css('display', 'none');
										if (responseSol.success == true){ 
											swal('Buen trabajo', `Solicitud ${msj} satisfactoriamente`, 'success');
											location.href = "solicitudes.php"; 
										}else{
											swal('Error', 'Error al '+oper+' la solicitud', 'error');
										}
									}
								});
							}else{
								$('#preloader').css('display', 'none');
							}
						}else{
							swal('Error',response.msj, 'error');
						}
					}
				});
			}
		}
		
		
    }else{
    	$('#preloader').css('display', 'none');
    }
}

function limpiarFormSol(){
	$("#form_solicitud")[0].reset();
	$("#form_solicitud").find('select').each(function() {
       $(this).val(0).trigger('change');
   });
}
function limpiarFormAc(){
	$("#form_acompanante")[0].reset();
	$("#form_acompanante").find('select').each(function() {
       $(this).val(0).trigger('change');
   });
}

function validarformSol(){
	var verdad 	= true;
    var lugarsolicitud	= $('#lugarsolicitud').val();
	var tipodiscapacidad= $('#tipodiscapacidad').val();
    var tiposolicitud	= $('#tiposolicitud').val();
    var estadosolicitud	= $('#estadosolicitud').val();
    var fecha_sol		= $('#fecha_sol').val();
    let tipoacompanante	= $('#tipoacompanante').val();
    let requiere_acompanante = $('#requiere_acompanante').val();
    expresion	=/\w+@\w+\.+[a-z]/;
	
	if(getQueryVariable('idsolicitud')){
		 if (lugarsolicitud == "0" || lugarsolicitud == undefined){
			swal('Error', 'Seleccione el lugar de la solicitud', 'error');
			return false;
		}else if (tipodiscapacidad == "" || tipodiscapacidad == 0 || tipodiscapacidad == undefined){
			swal('Error', 'Seleccione el tipo de discapacidad', 'error');
			return false;
		}else if (tiposolicitud == "" || tiposolicitud == 0 || tiposolicitud == undefined){
			swal('Error', 'Seleccione el tipo de solicitud', 'error');
			return false;
		}else if (estadosolicitud == "" || estadosolicitud == 0 || estadosolicitud == undefined){
			swal('Error', 'Seleccione el estado de la solicitud', 'error');
			return false;		
		}else if (fecha_sol == "" || fecha_sol == undefined){
			swal('Error', 'La fecha de solicitud esta vacía', 'error');
			return false;
		}else if ((tipoacompanante == '' || tipoacompanante == 0 || tipoacompanante == undefined) && 
		(requiere_acompanante == 'SI')){
			swal('Error', 'Seleccione el tipo de acompañante', 'error');
			return false;
		}else if(requiere_acompanante == '' || requiere_acompanante == 0 || requiere_acompanante == undefined){
			swal('Error', 'Seleccione el campo Requiere acompañante', 'error');
			return false;
		}
	}else{
		 if (lugarsolicitud == "0" || lugarsolicitud == undefined){
			swal('Error', 'Seleccione el lugar de la solicitud', 'error');
			return false;
		}else if (tipodiscapacidad == "" || tipodiscapacidad == 0 || tipodiscapacidad == undefined){
			swal('Error', 'Seleccione el tipo de discapacidad', 'error');
			return false;
		}else if (tiposolicitud == "" || tiposolicitud == 0 || tiposolicitud == undefined){
			swal('Error', 'Seleccione el tipo de solicitud', 'error');
			return false;
		}else if (fecha_sol == "" || fecha_sol == undefined){
			swal('Error', 'La fecha de solicitud esta vacía', 'error');
			return false;
		}else if ((tipoacompanante == '' || tipoacompanante == 0 || tipoacompanante == undefined) && 
		(requiere_acompanante == 'SI')){
			swal('Error', 'Seleccione el tipo de acompañante', 'error');
			return false;
		}else if(requiere_acompanante == '' || requiere_acompanante == 0 || requiere_acompanante == undefined){
			swal('Error', 'Seleccione el campo Requiere acompañante', 'error');
			return false;
		}
	}
   
	return verdad;
}

$('#tablacomentario').on( 'draw.dt', function () {	
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR COMENTARIOS
	$('.boton-eliminar-comentarios').each(function(){
		var id = $(this).attr("data-id"); 
		$(this).on( 'click', function() {
			eliminarcomentario(id);
		});
	});
});

$('#tablacomentario').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
});

const abrirComentarios = (idsolicitud) => {	
	//HEADER
	$('#tablacomentario thead th').each( function (){
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		if ( title !== '' && title !== '-' && title !== 'Acciones'){
			if (screen.width > 1024){
				if(title == 'ID'){
					$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 40px" /> ' );
				}else if(title == 'Comentario'){
					$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 300px" /> ' );
				}else if(title == 'Usuario'){
					$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" /> ' );
				}else if(title == 'Fecha'){
					$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" /> ' );
				}
			}else{
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
			}
		}else if(title == 'Acción'){
			var ancho = '50px';
		}
		$(this).width(ancho);
	});
	//COMENTARIOS
	tablacomentario = $("#tablacomentario").DataTable({
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		autoWidth : false,
		stateSave: true,
		searching: true,
		lengthChange: false,
		"ajax"		: {
			"url"	: "controller/solicitudesback.php?oper=comentarios&id="+idsolicitud,
		},
		"columns"	: [
			{ 	"data": "id" },			//0
			{ 	"data": "acciones" },	//1
			{ 	"data": "comentario" },	//2
			{ 	"data": "nombre" },		//3
			{ 	"data": "fecha" }		//4
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
		},
		initComplete: function(){
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
		},
		dom: '<"toolbarC toolbarDT">Blfrtip'
	});
}

const agregarComentario = ()=>{	
	var coment  = $('#comentario').val();		
	var idsolicitud = getQueryVariable('idsolicitud');
	if(coment==''){
		$('#comentario').addClass('form-valide-error-bottom');
		return;
	}
	if (coment != '') {
		$.ajax({
			type: 'post',
			url: 'controller/solicitudesback.php',
			data: { 
				'oper'	: 'agregarComentario',
				'id' 	: idsolicitud,
				'coment': coment
			},
			beforeSend: function() {
				$('#preloader').css('display','block');
			},
			success: function (response) {
				$('#preloader').css('display','none');
				if(response != 0){					
					$('#comentario').val("");					
					notification("Comentario Almacenado satisfactoriamente","¡Exito!",'success');
					tablacomentario.ajax.reload(null, false);
				}else{
					$('#preloader').css('display','none');					 
					notification("Ha ocurrido un error al grabar el Comentario, intente mas tarde","Error",'error');
				}
			},
			error: function () {
				$('#preloader').css('display','none');
				notification("Ha ocurrido un error al grabar el Comentario, intente mas tarde","Error",'error');
			}
		});
	}
	return;
}

const eliminarcomentario = (id)=>{
	var idsolicitud  = getQueryVariable('idsolicitud');
	var idcomentario = id;
	swal({
		title: "Confirmar",
		text: "¿Esta seguro de eliminar el comentario?",
		type: "warning",
		showCancelButton: true,
		cancelButtonColor: 'red',
		confirmButtonColor: '#09b354',
		confirmButtonText: 'Sí',
		cancelButtonText: "No"
	}).then(
		function(isConfirm){
			if (isConfirm.dismiss!="cancel"){
				$.get( "controller/solicitudesback.php?oper=eliminarComentario", 
				{ 
					onlydata : "true",
					idcomentario : idcomentario,
					idsolicitud  : idsolicitud
				}, function(result){
					if(result == 1){
						notification("Comentario eliminado satisfactoriamente","¡Exito!",'success');
						tablacomentario.ajax.reload(null, false);
					} else if(result == 2){
						notification("No tiene permisos para eliminar este comentario","Error",'error');
					} else {
						notification("Ha ocurrido un error al eliminar el comentario","Error",'error');
					}
				});
			}
		}, function (isRechazo){
			
		}
	);
}

const limpiarComentario = () => {
	$('#comentario').val('');
}

//Activación o Inactivación de estados
$('#estadosolicitud').on('change', function (e) {
	 
	if (!removerEstados) {
		if(cargarEstadoSolicitud == 0){
	
			let estado = parseInt(this.value);
			let mostrar = [];
	
			if(estado == 1){ //No agendado
				nivelSoloVer.includes(nivelSes) ? mostrar = [1] : mostrar = [2,12,18,19];
				removerOpciones(estado,mostrar);
			}else if(estado == 2){ //Agendado
				nivelSoloVer.includes(nivelSes) ? mostrar = [2] : mostrar = [2,6];
				removerOpciones(estado,mostrar);
			}else if(estado == 12){ //Cancelado
				mostrar = [12];
				removerOpciones(estado,mostrar);
			}else if(estado == 18){ //Desistió
				mostrar = [18];
				removerOpciones(estado,mostrar);
			}else if(estado == 19){ //Falleció
				mostrar = [19];
				removerOpciones(estado,mostrar);
			}else if(estado == 6){ //No asistió
				mostrar = [6];
				removerOpciones(estado,mostrar);
			}else if(estado == 16){ //Pendiente
				nivelSoloVer.includes(nivelSes) ? mostrar = [16] : mostrar = [2,12,16];
				removerOpciones(estado,mostrar);
			}else if(estado == 3){ //Certificó
				nivelLegal.includes(nivelSes) ? mostrar = [3,27] : mostrar = [3];
				removerOpciones(estado,mostrar);
			}else if(estado == 4){ //No certificó 
				nivelLegal.includes(nivelSes) ? mostrar = [4,28] : mostrar = [4];
				removerOpciones(estado,mostrar);
			}else if(estado == 27){ //Resolución de certificación generada
				nivelSoloVer.includes(nivelSes) ? mostrar = [27] : mostrar = [24,27];
				removerOpciones(estado,mostrar);
			} else if(estado == 28){ //Resolución de negatoria generada
				if(nivelLegal.includes(nivelSes)){
					if(tienereconsideracion == 0){
						//Muestra reconsideración
						mostrar = [5]; 
					}else{  
						if(tieneapelacion != 1){
							//Muestra apelación
							mostrar = [31,28]; 
						}else{
							//Muestra finalizado
							mostrar = [30,28]; 
						} 
					} 
				}else{ 
					mostrar = [28]; 
				} 
				removerOpciones(estado,mostrar);
			} else if(estado == 24){ //Pendiente por carnet
				nivelImpresion.includes(nivelSes) ? mostrar = [26,24] : mostrar = [24];
				removerOpciones(estado,mostrar);
			}else if(estado == 26){ //Carnet impreso
				nivelImpresion.includes(nivelSes) ? mostrar = [29,26] : mostrar = [26];
				removerOpciones(estado,mostrar);
			}else if(estado == 29){ //Por retirar documentos
				nivelSoloVer.includes(nivelSes) ? mostrar = [29] : mostrar = [30,29];
				removerOpciones(estado,mostrar);
			}else if(estado == 30){ //Finalizado
				mostrar = [30]; 
				removerOpciones(estado,mostrar);
			}else if(estado == 5){ //Reconsideración
				nivelSoloVer.includes(nivelSes) ? mostrar = [5] : mostrar = [2,5];
				removerOpciones(estado,mostrar);
			}else if(estado == 31){ //Apelación
				nivelSoloVer.includes(nivelSes) ? mostrar = [5] : mostrar = [2,5];
				removerOpciones(estado,mostrar);
			}
		
		}
	}

	removerEstados = true;
	
}); 

let removerOpciones = (estado,mostrar) =>{ 
	$("#estadosolicitud option").each(function() {
		let val = parseInt($(this).val());	
		if(!mostrar.includes(val) && val != estado){
			$(`#estadosolicitud [value="${val}"]`).remove();
		}  
	});
}

$("select").select2({ language: "es" });
