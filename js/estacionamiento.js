var idsolicitud = getQueryVariable('id');

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

if(!getQueryVariable('id')){
	provincias(0);
	distritos(0);
	corregimientos(0); 
	nacionalidades(0);  

    //Asignar fecha automática actual a la solicitud
    let fecha = new Date();
    let fechaFormateada = fecha.toISOString().slice(0, 19).replace('T', ' ');
    $("#fecha_sol").val(fechaFormateada);
}

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
    $.get("controller/combosback.php?oper=estadosestacionamiento", {}, function(result)
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

//COMBOS
function nacionalidades(id){
    $.get("controller/combosback.php?oper=nacionalidades", {}, function(result)
    {
        $("#nacionalidad").empty();
        $("#nacionalidad").append(result);
        if (id != 0){
			$("#nacionalidad").val(id).trigger('change');
        }
    });
}
function provincias(id){
    $.get("controller/combosback.php?oper=provincia", {}, function(result)
    {
        $("#idprovincias").empty();
        $("#idprovincias").append(result);
        if (id != 0){
			$("#idprovincias").val(id).trigger('change');
        }
    });
}
$("#idprovincias").on('select2:select', function(e) {
	var id = 0;
	var provincia = $(this).val();
	distritos(id, provincia);
});
function distritos(id, provincia){
    $.get("controller/combosback.php?oper=distrito", { provincia: provincia }, function(result)
    {
        $("#iddistritos").empty();
        $("#iddistritos").append(result);
        if (id != 0){
			$("#iddistritos").val(id).trigger('change');
        }
    });
}
$("#iddistritos").on('select2:select', function(e) {
	var id = 0;
	var provincia = $("#idprovincias").val();
	var distrito = $(this).val();
	corregimientos(id, provincia, distrito);
});
function corregimientos(id, provincia, distrito){
    $.get("controller/combosback.php?oper=corregimiento", { provincia: provincia, distrito: distrito }, function(result)
    {
        $("#idcorregimientos").empty();
        $("#idcorregimientos").append(result);
        if (id != 0){
			$("#idcorregimientos").val(id).trigger('change');
        }
    });
}
$("#idcorregimientos").on('select2:select', function(e) {
	var area = $(this).select2('data')[0].element.dataset.area;
	$("#area").val(area);
});

$("#fecha_sol").bootstrapMaterialDatePicker({
	date: true,
	format:'YYYY-MM-DD HH:mm:ss',
	time:true,
	lang: 'es',
})

$("#fecha_nac").bootstrapMaterialDatePicker({
	date: true,
	format:'YYYY-MM-DD', 
	lang: 'es',
})

$("#listadoSolicitudes").on("click",function(){
	location.href = 'estacionamientos.php';
});

//Consulta en la tabla de pacientes de renacer 
$("#tipodocumento").on('change',function(){
	$("#cedula").removeClass('cedula');
	$("#cedula").val("");
	$("#cedula").off();
	if($(this).val()!=0){
		$(this).val()==2 ? $(".vcto_cm").removeClass('d-none') : $(".vcto_cm").addClass('d-none');
		var texto = $(this).select2('data')[0].element.text;
		$("#cedula").prop('disabled',false);
		let tipodoc = $(this).val();
		if($(this).val()==1 || $(this).val()==2){
			$("#cedula").addClass('cedula');
			$("#cedula").on('blur', function(){
				if( $(this).val() != '' ){
					if( !validar_cedula($(this).val()) && tipodoc == 1){
						$.when(swal('Error','Formato de cédula inválido','error')).done(function(){$(this).focus();});    					    
					}else{
						
						var tipo_documento = $("#tipodocumento").val();
						var cedula 		   = $("#cedula").val(); 
						 
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: 'controller/beneficiariosestacionamientosback.php',
                            data: { oper: 'existeBeneficiario', tipo_documento: tipo_documento, cedula: cedula },
                            success: function( response ) { 
                                if(response.success == true){
                                    $('#idbeneficiario').val(response.id);	
                                    $('#tipobeneficiario').val(response.tipo);									
                                    swal({
                                        title: "Confirmar",
                                        html: "El número de documento ya existe. ¿Desea cargar los datos?",
                                        type: "info",
                                        showCancelButton: true,
                                        cancelButtonColor: 'red',
                                        confirmButtonColor: '#09b354',
                                        confirmButtonText: 'Sí',
                                        cancelButtonText: "No"
                                    }).then(
                                        function(isConfirm){
                                            if (isConfirm.value == true){
                                                fillForm();
                                            }else{
                                                //limpiarForm();
                                                $("#tipodocumento").focus();
                                            }
                                        },
                                        function(isRechazo){
                                            limpiarForm();
                                        }
                                    );
                                }else{
                                    //limpiarForm();
                                }
                            },
                            error: function( error ){
                                //alert( error );
                            }
                        }); 						
					}			
				}		
			});		
		}
	}else{
		var texto = '';
		$("#cedula").prop('disabled',true);
	}
	$("#tipodocumento_txt").html(texto+' <span class="text-red">*</span>');
});
const getPaciente = (id,tipo) =>{
    console.log('tipo',tipo);
    let url = 'controller/beneficiariosestacionamientosback.php?oper=getbeneficiario';
    if(tipo == 'certificaciones'){
        url = 'controller/beneficiariosback.php?oper=getpaciente';
    }
    $.post(`${url}`, {id: id}, function(response){
        const datos = JSON.parse(response);
        let par = new Date().getTime()
        
        //DATOS PERSONALES
        $('#tipodocumento').val(datos.paciente.tipo_documento).trigger('change');
        if(datos.paciente.tipo_documento == 1){
            $("#tipodocumento_txt").html('Cédula <span class="text-red">*</span>');
        }else if(datos.paciente.tipo_documento == 2){
            $(".vcto_cm").removeClass('d-none');
            $("#tipodocumento_txt").html('Carnet migratorio <span class="text-red">*</span>');
        }else{
            $("#tipodocumento_txt").html('Documento de identidad personal <span class="text-red">*</span>');
        }
         
        $('#cedula').val(datos.paciente.cedula);
        $('#cedulabd').val(datos.paciente.cedula);
        $('#nombre').val(datos.paciente.nombre);
        $('#apellidopaterno').val(datos.paciente.apellidopaterno);
        $('#apellidomaterno').val(datos.paciente.apellidomaterno);
        $('#expediente').val(datos.paciente.expediente);
        $('#correo').val(datos.paciente.correo);
        $('#telefonocelular').val(datos.paciente.celular);
        $('#telefonootro').val(datos.paciente.telefono);			
        $('#fecha_nac').val(datos.paciente.fecha_nac); 
        var edad = calcularEdad(datos.paciente.fecha_nac);	
        $('#edad').val(edad); 
        $('#sexo').val(datos.paciente.sexo).trigger('change'); 
        //DIRECCIÓN
        $("#urbanizacion").val(datos.direccion.urbanizacion);
        $("#calle").val(datos.direccion.calle);
        $("#edificio").val(datos.direccion.edificio);
        $("#numerocasa").val(datos.direccion.numero);
        $("#area").val(datos.direccion.area);
        provincias(datos.direccion.provincia);
        distritos(datos.direccion.distrito, datos.direccion.provincia);
        corregimientos(datos.direccion.corregimiento, datos.direccion.provincia, datos.direccion.distrito); 
    });
}
 
function fillForm() {
    if(idsolicitud == '' && idsolicitud == false){
        if($('#idbeneficiario').val() && $('#tipobeneficiario').val()){
            let id = $('#idbeneficiario').val();
            let tipo = $('#tipobeneficiario').val();
            getPaciente(id,tipo);
        }
    }else{
        let id = $('#idbeneficiario').val();
        let tipo = $('#tipobeneficiario').val();
        tipo = tipo != '' ? tipo : 'estacionamientos';
        getPaciente(id,tipo);
    } 
}

function validarForm(){
	let verdad 	= true;
    let lugarsolicitud	= $('#lugarsolicitud').val(); 
    let tiposolicitud	= $('#tiposolicitud').val();
    let estadosolicitud	= $('#estadosolicitud').val();
    let fecha_sol = $('#fecha_sol').val();
    let requiereacompanante = $('#requiere_acompanante').val();
    let idbeneficiario = $('#idbeneficiario').val();
    let tipodocumento = $('#tipodocumento').val();
    let cedula = $('#cedula').val();
    let cedulabd = $('#cedulabd').val();
    let nombre = $('#nombre').val();
    let apellidomaterno	= $('#apellidomaterno').val();
    let telefonocelular	= $('#telefonocelular').val();
    let fecha_nac = $('#fecha_nac').val();
    let nacionalidad = $('#nacionalidad').val();	
    let sexo = $('#sexo').val();
    let estado_civil = $('#estado_civil').val();
    let idprovincias = $('#idprovincias').val();
    let iddistritos	= $('#iddistritos').val();
    let idcorregimientos= $('#idcorregimientos').val();
    let area = $('#area').val();  

    if (lugarsolicitud == "0"){
        swal('Error', 'Seleccione el lugar de la solicitud', 'error');
        return false;
    }else if (tiposolicitud == "0"){
        swal('Error', 'Seleccione el tipo de solicitud', 'error');
        return false;
    }else if (fecha_sol == ""){
        swal('Error', 'Seleccione la fecha de la solicitud', 'error');
        return false;
    }else if (tipodocumento == "0"){
        swal('Error', 'Seleccione un tipo de documento', 'error');
        return false;
    }else if (cedula == ""){
        swal('Error', 'El número de documento esta vacío', 'error');
        return false;
    }else if (nombre == ""){
        swal('Error', 'El nombre esta vacío', 'error');
        return false;
    }else if (apellidomaterno == ""){
        swal('Error', 'El apellido materno esta vacío', 'error');
        return false;
    }else if (telefonocelular==""){
        swal('Error', 'El teléfono celular esta vacío', 'error');
        return false;
    }else if (fecha_nac == ""){
        swal('Error', 'La fecha de nacimiento esta vacía', 'error');
        return false;
    }else if (requiereacompanante == "0"){
        swal('Error', 'Seleccione si requiere o no un acompañante', 'error');
        return false;
    }else if (sexo == "0"){
        swal('Error', 'Seleccione un sexo', 'error');
        return false;
    }else if (idprovincias == "0"){
        swal('Error', 'Seleccione una provincia', 'error');
        return false;
    }else if (iddistritos == "0"){
        swal('Error', 'Seleccione un distrito', 'error');
        return false;
    }else if (idcorregimientos == "0"){
        swal('Error', 'Seleccione un corregimiento', 'error');
        return false;
    }else if (area == ""){
        swal('Error', 'El área esta vacío', 'error');
        return false;
    }
    return verdad;
}

//Guardar solicitud de permiso estacionamiento
let guardar = () => {
    
    let idsolicitud = getQueryVariable('id');
    let msj = (idsolicitud != '' && idsolicitud != false) ? 'actualizada' : 'creada';
    

    //Campos solicitud
    let lugarsolicitud = $('#lugarsolicitud').val();
    let tipodiscapacidad = $('#tipodiscapacidad').val();
    let tiposolicitud = $('#tiposolicitud').val();
    let estadosolicitud = $('#estadosolicitud').val();
    let fecha_sol = $('#fecha_sol').val();
    let requiereacompanante = $('#requiere_acompanante').val();
    let caracteristicavehiculo = $('#caracteristicavehiculo').val();
    let adaptado = $('#adaptado').val();
    let placa = $('#placa').val();
    let marca = $('#marca').val();
    let modelo = $('#modelo').val();
    let nromotor = $('#nromotor').val();

    //Campos beneficiario
    let idbeneficiario = $('#idbeneficiario').val();
    let tipobeneficiario = $('#tipobeneficiario').val();
    let tipodocumento = $('#tipodocumento').val();
    let cedula = $('#cedula').val();
    let nombre = $('#nombre').val();
    let apellidopaterno = $('#apellidopaterno').val();
    let apellidomaterno = $('#apellidomaterno').val();
    let fecha_nac = $('#fecha_nac').val();
    let sexo = $('#sexo').val();
    let idprovincias = $('#idprovincias').val();
    let iddistritos = $('#iddistritos').val();
    let idcorregimientos = $('#idcorregimientos').val();
    let urbanizacion = $('#urbanizacion').val();
    let calle = $('#calle').val();
    let numerocasa = $('#numerocasa').val();
    let telefonocelular = $('#telefonocelular').val();
    let telefonootro = $('#telefonootro').val();
    let correo = $('#correo').val();

    //Campos acompañante
    let idacompanante = $('#idacompanante').val();

    let datosSol = {};
    let datosBen = {};

    //Datos solicitud
    datosSol['lugarsolicitud'] = lugarsolicitud;
    datosSol['tipodiscapacidad'] = tipodiscapacidad;
    datosSol['tiposolicitud'] = tiposolicitud;
    datosSol['estadosolicitud'] = estadosolicitud;
    datosSol['fecha_sol'] = fecha_sol;
    datosSol['requiereacompanante'] = requiereacompanante;
    datosSol['idacompanante'] = idacompanante;
    datosSol['caracteristicavehiculo'] = caracteristicavehiculo;
    datosSol['adaptado'] = adaptado;
    datosSol['placa'] = placa;
    datosSol['marca'] = marca;
    datosSol['modelo'] = modelo;
    datosSol['nromotor'] = nromotor;

    //Datos beneficiario
    datosBen['idbeneficiario'] = idbeneficiario;
    datosBen['tipobeneficiario'] = tipobeneficiario;
    datosBen['tipodocumento'] = tipodocumento;
    datosBen['cedula'] = cedula;
    datosBen['nombre'] = nombre;
    datosBen['apellidopaterno'] = apellidopaterno;
    datosBen['apellidomaterno'] = apellidomaterno;
    datosBen['fecha_nac'] = fecha_nac;
    datosBen['sexo'] = sexo;
    datosBen['idprovincias'] = idprovincias;
    datosBen['iddistritos'] = iddistritos;
    datosBen['idcorregimientos'] = idcorregimientos;
    datosBen['urbanizacion'] = urbanizacion;
    datosBen['calle'] = calle;
    datosBen['numerocasa'] = numerocasa;
    datosBen['telefonocelular'] = telefonocelular;
    datosBen['telefonootro'] = telefonootro;
    datosBen['correo'] = correo;

    if ((idbeneficiario == '') || (idbeneficiario !='' && tipobeneficiario == 'certificaciones')){
        var operBen = "guardarBeneficiario";
    }else{
        var operBen = "editarBeneficiario";
    }

    //Guardar beneficiario
    if (validarForm()){    
        $.ajax({
            url: "controller/beneficiariosestacionamientosback.php",
            type: "POST",
            data: {
                oper: operBen,
                datos: datosBen,
                id: idbeneficiario
            },
            dataType: "json",
            success: function(response){ 
                $('#preloader').css('display', 'none');
                if (response.success == true){
                    //Guardar solicitud
                    $.ajax({
                        url: "controller/estacionamientosback.php",
                        type: "POST",
                        data: {
                            oper: 'guardar_solicitud',
                            datos: datosSol, 
                            id: idsolicitud,
                            idbeneficiario: response.idpaciente
                        },
                        dataType: "json",
                        success: function(responseSol){
                            $('#preloader').css('display', 'none');
                            if (responseSol.success == true){ 
                                swal('Buen trabajo',responseSol.msj, 'success');
                                //location.href = "estacionamientos.php"; 
                            }else{
                                swal('Error',responseSol.msj, 'error');
                            }
                        }
                    });
                }else{
                    swal('Error',response.msj, 'error');
                }
            }
        });
    } 
}

//CARGAR DATOS SOLICITUD
if(idsolicitud){
    consumir();
} 
async function consumir(){ 
	
	await peticion("POST","controller/estacionamientosback.php?oper=getdatossolicitud", { idsolicitud: idsolicitud }).then(function(response){
		tienereconsideracion = parseInt(response.reconsideracion);
		tieneapelacion = parseInt(response.apelacion); 
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
        $("#caracteristicavehiculo").val(response.caracteristicavehiculo).trigger('change');
        $("#adaptado").val(response.adaptado).trigger('change');
        $("#placa").val(response.placa); 
        $("#marca").val(response.marca); 
        $("#modelo").val(response.modelo); 
        $("#nromotor").val(response.nromotor); 

		//Acompañante
		if(proyecto.idacompanante != '0'){
			$('#requiere_acompanante').val('SI').trigger('change');
			$(".datosac").removeClass("d-none");	
			$("#boton-editar-acompanante").show();
			$.get('controller/acompanantesestacionamientosback.php?oper=getDatosAcompanantes&idacompanante='+proyecto.idacompanante,function(response){
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
                $("#modal-nuevoacompanante-iddireccion").val(response.direccion); 
				$("#area_ac").val(response.area_ac);
				$("#urbanizacion_ac").val(response.urbanizacion);
				$("#calle_ac").val(response.calle);
				$("#edificio_ac").val(response.edificio);
				$("#numero_ac").val(response.numero); 
				
				//Dirección acompañante
				$("#modal-nuevoacompanante-iddireccion").val(response.direccion); 
			},'json');
		}else{
			$('#requiere_acompanante').val('NO').trigger('change');
		}
		//Comentarios
		//abrirComentarios(idsolicitud);
	})
    //Beneficiario 
	$.get('controller/beneficiariosestacionamientosback.php?oper=get_pacienteporsolicitud&id='+idsolicitud,function(response){
		$('#idbeneficiario').val(response.id)
		fillForm();
	},'json');
} 

//Aprobar permiso de estacionamiento
const aprobarSolicitud = () =>{

    let duracion = $("#duracion").val();
    let tipoduracion = $("#tipoduracion").val();

    if(duracion == '' || (tipoduracion == '' || tipoduracion == '0' || tipoduracion == undefined)){
        swal('Error','Debe agregar el tiempo de vigencia', 'error');
    }else{
        $.ajax({
            url: "controller/estacionamientosback.php",
            type: "POST",
            data: {
                oper: 'aprobarSolicitud', 
                idsolicitud: idsolicitud,
                duracion: duracion,
                tipoduracion: tipoduracion
            },
            dataType: "json",
            success: function(rsp){
                $('#preloader').css('display', 'none');
                if (rsp.success == true){ 
                    swal('Buen trabajo',rsp.msj, 'success'); 
                }else{
                    swal('Error',rsp.msj, 'error');
                }
            }
        });
    }
}

$("select").select2({ language: "es" });