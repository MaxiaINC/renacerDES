if(!getQueryVariable('id')){
	provincias(0);
	distritos(0);
	corregimientos(0); 
	nacionalidades(0);  
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
                            url: 'controller/estacionamientosback.php',
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
                                                limpiarForm();
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
    let url = 'controller/estacionamientosback.php?oper=getbeneficiario';
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
        //var arrfecha = datos.paciente.fecha_nac.split('-');
        //var fechan = arrfecha.reverse();
        //var fechanac = fechan.join("-");
        var edad = calcularEdad(datos.paciente.fecha_nac);	
        $('#edad').val(edad);
        nacionalidades(datos.paciente.nacionalidad);
        $('#sexo').val(datos.paciente.sexo).trigger('change');
        $('#estado_civil').val(datos.paciente.estado_civil).trigger('change');
        $('#status').val(datos.paciente.status).trigger('change');
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

fillForm();
function fillForm() {
    if($('#idbeneficiario').val() && $('#tipobeneficiario').val()){
        let id = $('#idbeneficiario').val();
        let tipo = $('#tipobeneficiario').val();
        getPaciente(id,tipo);
    }else{
        $(".infoubicacion").text('La ubicación no ha sido seleccionada');
    }
}

function validarForm(){
	var verdad 	= true;
    var lugarsolicitud	= $('#lugarsolicitud').val();
	//var tipodiscapacidad= $('#tipodiscapacidad').val();
    var tiposolicitud	= $('#tiposolicitud').val();
    var estadosolicitud	= $('#estadosolicitud').val();
    var fecha_sol		= $('#fecha_sol').val();
    //let requiere_acompanante = $('#requiere_acompanante').val();
    let idbeneficiario = $('#idbeneficiario').val();
    var tipodocumento	= $('#tipodocumento').val();
    var cedula			= $('#cedula').val();
    var cedulabd		= $('#cedulabd').val();
    var nombre			= $('#nombre').val();
    var apellidomaterno	= $('#apellidomaterno').val();
    var telefonocelular	= $('#telefonocelular').val();
    var fecha_nac		= $('#fecha_nac').val();
    var nacionalidad	= $('#nacionalidad').val();	
    var sexo			= $('#sexo').val();
    var estado_civil	= $('#estado_civil').val();
    var idprovincias	= $('#idprovincias').val();
    var iddistritos		= $('#iddistritos').val();
    var idcorregimientos= $('#idcorregimientos').val();
    var area			= $('#area').val(); 
   /*  let caracteristica = $('#caracteristica').val();
    let adaptado = $('#adaptado').val();
    let placa = $('#placa').val();
    let marca = $('#marca').val();
    let modelo = $('#modelo').val();
    let nromotor = $('#nromotor').val(); */

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
    }else if (nacionalidad == ""){
        swal('Error', 'La nacionalidad esta vacía', 'error');
        return false;
    }else if (sexo == "0"){
        swal('Error', 'Seleccione un sexo', 'error');
        return false;
    }else if (estado_civil == "0"){
        swal('Error', 'Seleccione un estado civil', 'error');
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
    let msj = (idsolicitud != '' && id != false) ? 'actualizada' : 'creada';

    //Campos solicitud
    let lugarsolicitud = $('#lugarsolicitud').val();
    let tipodiscapacidad = $('#tipodiscapacidad').val();
    let tiposolicitud = $('#tiposolicitud').val();
    let estadosolicitud = $('#estadosolicitud').val();
    let fecha_sol = $('#fecha_sol').val();
    let idbeneficiario = $('#idbeneficiario').val();
    let caracteristica = $('#caracteristica').val();
    let adaptado = $('#adaptado').val();
    let placa = $('#placa').val();
    let marca = $('#marca').val();
    let modelo = $('#modelo').val();
    let nromotor = $('#nromotor').val();

    //Campos beneficiario
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

    let datosSol = {};
    let datosBen = {};

    //Datos solicitud
    datosSol['lugarsolicitud'] = lugarsolicitud;
    datosSol['tipodiscapacidad'] = tipodiscapacidad;
    datosSol['tiposolicitud'] = tiposolicitud;
    datosSol['estadosolicitud'] = estadosolicitud;
    datosSol['fecha_sol'] = fecha_sol;
    datosSol['caracteristica'] = caracteristica;
    datosSol['adaptado'] = adaptado;
    datosSol['placa'] = placa;
    datosSol['marca'] = marca;
    datosSol['modelo'] = modelo;
    datosSol['nromotor'] = nromotor;

    //Datos beneficiario
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

    if (idbeneficiario == ''){
        var operBen = "guardarBeneficiario";
    }else{
        var operBen = "editarBeneficiario";
    }

    if (validarForm()){    
        $.ajax({
            url: "controller/estacionamientosback.php",
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
                    console.log('PASÓXY');
                    //GUARDAR SOLICITUD
                    //Validar datos de la solicitud
                  /* if (validarformSol()){  */
                        $.ajax({
                            url: "controller/estacionamientosback.php",
                            type: "POST",
                            data: {
                                oper: 'guardar_solicitud',
                                datos: datosSol,
                              /* datosAc: datosAc,
                                datosSolAc: datosSolAc,  */
                                id: idsolicitud,
                                idbeneficiario: response.idpaciente
                            },
                            dataType: "json",
                            success: function(responseSol){
                                $('#preloader').css('display', 'none');
                                if (responseSol.success == true){ 
                                    swal('Buen trabajo', `Solicitud de permiso de estacionamiento ${msj} satisfactoriamente`, 'success');
                                    //location.href = "solicitudes.php"; 
                                }else{
                                    swal('Error', 'Error al '+operSol+' la solicitud', 'error');
                                }
                            }
                        });
                  /* }else{
                        $('#preloader').css('display', 'none');
                    } */ 
                }else{
                    swal('Error',response.msj, 'error');
                }
            }
        });
    } 
}


$("select").select2({ language: "es" });