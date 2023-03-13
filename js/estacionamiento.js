var idsolicitud = getQueryVariable('id');

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

function validarForm(){
	let verdad 	= true;
    let lugarsolicitud	= $('#lugarsolicitud').val(); 
    let tiposolicitud	= $('#tiposolicitud').val(); 
    let fecha_sol = $('#fecha_sol').val();
    let requiereacompanante = $('#requiere_acompanante').val();
    let tipodocumento = $('#tipodocumento').val();
    let cedula = $('#cedula').val();
    let cedulabd = $('#cedulabd').val();
    let nombre = $('#nombre').val();
    let apellidomaterno	= $('#apellidomaterno').val();
    let telefonocelular	= $('#telefonocelular').val();
    let fecha_nac = $('#fecha_nac').val(); 
    let sexo = $('#sexo').val(); 
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

let grabar = () =>{
    if(!getQueryVariable('id')){
        guardar();
    }else{
        editar();
    }    
}  

let guardar = () => {
    
    let datosSol = {};
    let datosBen = {};
    let datosAco = {};

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
      
    //Campos acompanante  
    let tipodocumento_ac = $('#tipodocumento_ac').val();
    let cedula_ac = $('#cedula_ac').val();
    let nombre_ac = $('#nombre_ac').val();
    let apellidopaterno_ac = $('#apellidopaterno_ac').val();
    let apellidomaterno_ac = $('#apellidomaterno_ac').val();
    let fecha_nac_ac = $('#fecha_nac_ac').val();
    let sexo_ac = $('#sexo_ac').val();
    let idprovincias_ac = $('#idprovincias_ac').val();
    let iddistritos_ac = $('#iddistritos_ac').val();
    let idcorregimientos_ac = $('#idcorregimientos_ac').val();
    let urbanizacion_ac = $('#urbanizacion_ac').val();
    let calle_ac = $('#calle_ac').val();
    let numerocasa_ac = $('#numerocasa_ac').val();
    let telefonocelular_ac = $('#telefonocelular_ac').val();
    let telefonootro_ac = $('#telefonootro_ac').val();
    let correo_ac = $('#correo_ac').val();
    var nombreacompanante_ac = $('#nombreacompanante_ac').val();

    //Datos solicitud
    datosSol['lugarsolicitud'] = lugarsolicitud;
    datosSol['tipodiscapacidad'] = tipodiscapacidad;
    datosSol['tiposolicitud'] = tiposolicitud;
    datosSol['estadosolicitud'] = estadosolicitud;
    datosSol['fecha_sol'] = fecha_sol;
    datosSol['requiereacompanante'] = requiereacompanante;
    //datosSol['idacompanante'] = idacompanante;
    datosSol['caracteristicavehiculo'] = caracteristicavehiculo;
    datosSol['adaptado'] = adaptado;
    datosSol['placa'] = placa;
    datosSol['marca'] = marca;
    datosSol['modelo'] = modelo;
    datosSol['nromotor'] = nromotor;

    //Datos beneficiario
    //datosBen['idbeneficiario'] = idbeneficiario;
    //datosBen['tipobeneficiario'] = tipobeneficiario;
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

    //Datos acompanante 
    datosAco['tipodocumento_ac'] = tipodocumento_ac;
    datosAco['cedula_ac'] = cedula_ac;
    datosAco['nombre_ac'] = nombre_ac;
    datosAco['apellidopaterno_ac'] = apellidopaterno_ac;
    datosAco['apellidomaterno_ac'] = apellidomaterno_ac;
    datosAco['fecha_nac_ac'] = fecha_nac_ac;
    datosAco['sexo_ac'] = sexo_ac;
    datosAco['idprovincias_ac'] = idprovincias_ac;
    datosAco['iddistritos_ac'] = iddistritos_ac;
    datosAco['idcorregimientos_ac'] = idcorregimientos_ac;
    datosAco['urbanizacion_ac'] = urbanizacion_ac;
    datosAco['calle_ac'] = calle_ac;
    datosAco['numerocasa_ac'] = numerocasa_ac;
    datosAco['telefonocelular_ac'] = telefonocelular_ac;
    datosAco['telefonootro_ac'] = telefonootro_ac;
    datosAco['correo_ac'] = correo_ac; 
    
    if (validarForm()){    
        $.when(
            $.post('controller/estacionamientosback.php', { oper: 'crear', datosSol: datosSol }),
            $.post('controller/beneficiariosestacionamientosback.php', { oper: 'crear', datosBen: datosBen }),
            $.post('controller/acompanantesestacionamientosback.php', { oper: 'crear', datosAco: datosAco })
        ).done(function(respuestaSol, respuestaBen, respuestaAco) {

            let jsonResponseSol = JSON.parse(respuestaSol[0]);
            let jsonResponseBen = JSON.parse(respuestaBen[0]);
            let jsonResponseAco = JSON.parse(respuestaAco[0]);

            let idsolicitud = jsonResponseSol.idsolicitud;
            let idbeneficiario = jsonResponseBen.idbeneficiario;
            let idacompanante = jsonResponseAco.idacompanante;

            // Relacionar los IDs en la tabla de estacionamientos
            $.post('controller/estacionamientosback.php', { oper: 'relacionar', idbeneficiario: idbeneficiario, idacompanante: idacompanante, idsolicitud: idsolicitud })
            .done(function(resp) {
                if(resp==1){
                    swal("¡Buen trabajo!","Solicitud de permiso de estacionamiento creada satisfactoriamente","success");		
                }else{
                    swal("Error","Error al crear la solicitud de permiso de estacionamiento","error");		
                }  
            })
            .fail(function() {
                swal("Error","Error al crear la solicitud de permiso de estacionamiento","error");		
            }); 
        }).fail(function() {
            swal("Error","Error al crear la solicitud de permiso de estacionamiento","error");		
        });     
    }
} 

function editar() {
        
    let datosSol = {};
    let datosBen = {};
    let datosAco = {};

    //Campos solicitud
    let lugarsolicitud = $('#lugarsolicitud').val();
    let tipodiscapacidad = $('#tipodiscapacidad').val();
    let tiposolicitud = $('#tiposolicitud').val();
    let estadosolicitud = $('#estadosolicitud').val();
    let fecha_sol = $('#fecha_sol').val();
    let requiereacompanante = $('#requiere_acompanante').val();
    let idbeneficiario = $('#idbeneficiario').val();
    let idacompanante = $('#idacompanante_ac').val();
    let caracteristicavehiculo = $('#caracteristicavehiculo').val();
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
      
    //Campos acompanante  
    let tipodocumento_ac = $('#tipodocumento_ac').val();
    let cedula_ac = $('#cedula_ac').val();
    let nombre_ac = $('#nombre_ac').val();
    let apellido_ac = $('#apellido_ac').val(); 
    let fecha_nac_ac = $('#fecha_nac_ac').val();
    let sexo_ac = $('#sexo_ac').val();
    let idprovincias_ac = $('#idprovincias_ac').val();
    let iddistritos_ac = $('#iddistritos_ac').val();
    let idcorregimientos_ac = $('#idcorregimientos_ac').val();
    let urbanizacion_ac = $('#urbanizacion_ac').val();
    let calle_ac = $('#calle_ac').val();
    let numerocasa_ac = $('#numerocasa_ac').val();
    let telefonocelular_ac = $('#telefonocelular_ac').val();
    let telefonootro_ac = $('#telefonootro_ac').val();
    let correo_ac = $('#correo_ac').val(); 
    let direccion = $("#modal-nuevoacompanante-iddireccion").val();
    let iddireccion = $('#idcorregimientos_ac option:selected').attr('data-id');

    //Datos solicitud
    datosSol['lugarsolicitud'] = lugarsolicitud;
    datosSol['tipodiscapacidad'] = tipodiscapacidad;
    datosSol['tiposolicitud'] = tiposolicitud;
    datosSol['estadosolicitud'] = estadosolicitud;
    datosSol['fecha_sol'] = fecha_sol;
    datosSol['requiereacompanante'] = requiereacompanante;
    datosSol['idbeneficiario'] = idbeneficiario;
    datosSol['idacompanante'] = idacompanante;
    datosSol['caracteristicavehiculo'] = caracteristicavehiculo;
    datosSol['adaptado'] = adaptado;
    datosSol['placa'] = placa;
    datosSol['marca'] = marca;
    datosSol['modelo'] = modelo;
    datosSol['nromotor'] = nromotor;

    //Datos beneficiario
    //datosBen['tipobeneficiario'] = tipobeneficiario;
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

    //Datos acompanante 
    datosAco['tipodocumento_ac'] = tipodocumento_ac;
    datosAco['cedula_ac'] = cedula_ac;
    datosAco['nombre_ac'] = nombre_ac;
    datosAco['apellido_ac'] = apellido_ac; 
    datosAco['fecha_nac_ac'] = fecha_nac_ac;
    datosAco['sexo_ac'] = sexo_ac;
    datosAco['idprovincias_ac'] = idprovincias_ac;
    datosAco['iddistritos_ac'] = iddistritos_ac;
    datosAco['idcorregimientos_ac'] = idcorregimientos_ac;
    datosAco['urbanizacion_ac'] = urbanizacion_ac;
    datosAco['calle_ac'] = calle_ac;
    datosAco['numerocasa_ac'] = numerocasa_ac;
    datosAco['telefonocelular_ac'] = telefonocelular_ac;
    datosAco['telefonootro_ac'] = telefonootro_ac;
    datosAco['correo_ac'] = correo_ac;
    datosAco['direccion'] = direccion;
    datosAco['iddireccion'] = iddireccion;
 
    if (validarForm()){    
        $.when(
            $.post('controller/estacionamientosback.php', { oper: 'editar', id: idsolicitud, datosSol: datosSol }),
            $.post('controller/beneficiariosestacionamientosback.php', { oper: 'editar', id: idsolicitud, datosBen: datosBen }),
            $.post('controller/acompanantesestacionamientosback.php', { oper: 'editar', id: idsolicitud, datosAco: datosAco })
        ).done(function(respuestaSol, respuestaBen, respuestaAco) {

            let jsonResponseSol = JSON.parse(respuestaSol[0]);
            let jsonResponseBen = JSON.parse(respuestaBen[0]);
            let jsonResponseAco = JSON.parse(respuestaAco[0]);

            let idsolicitud = jsonResponseSol.idsolicitud;
            let idbeneficiario = jsonResponseBen.idbeneficiario;
            let idacompanante = jsonResponseAco.idacompanante;

            // Relacionar los IDs en la tabla de estacionamientos
            $.post('controller/estacionamientosback.php', { oper: 'relacionar', idsolicitud: idsolicitud, idbeneficiario: idbeneficiario, idacompanante: idacompanante })
            .done(function(resp) {
                if(resp==1){
                    swal("¡Buen trabajo!","Solicitud de permiso de estacionamiento editada satisfactoriamente","success");		
                }else{
                    swal("Error","Error al editar la solicitud de permiso de estacionamiento","error");		
                }  
            })
            .fail(function() {
                swal("Error","Error al editar la solicitud de permiso de estacionamiento","error");		
            }); 
        }).fail(function() {
            swal("Error","Error al editar la solicitud de permiso de estacionamiento","error");		
        }); 
    }
}


get();
function get(){

    // Cargar los datos de estacionamientosback.php utilizando el ID de la solicitud
    $.get('controller/estacionamientosback.php', { oper: 'getdatossolicitud', idsolicitud: idsolicitud })
    .done(function(respuestaEst) {
        let jsonResponseEst = JSON.parse(respuestaEst);
        let idbeneficiario = jsonResponseEst.idbeneficiario;
        let idacompanante = jsonResponseEst.idacompanante;

        //Datos de la solicitud
		$('#lugarsolicitud').val(jsonResponseEst.regional).trigger('change');
		$('#tipodiscapacidad').val(jsonResponseEst.iddiscapacidad).trigger('change');
		$('#tiposolicitud').val(jsonResponseEst.tiposolicitud).trigger('change');
		$('#estadosolicitud').val(jsonResponseEst.idestatus).trigger('change');
		$('#fecha_sol').val(jsonResponseEst.fecha_solicitud);
		$('#cssolicitud').val(jsonResponseEst.condicionsalud);
        $('#idbeneficiario').val(jsonResponseEst.idbeneficiario);
        $('#idacompanante_ac').val(jsonResponseEst.idacompanante);
		$('#observaciones').val(jsonResponseEst.observaciones);
		$('#tipoacompanante').val(jsonResponseEst.tipoacompanante).trigger('change');
        $("#caracteristicavehiculo").val(jsonResponseEst.caracteristicavehiculo).trigger('change');
        $("#adaptado").val(jsonResponseEst.adaptado).trigger('change');
        $("#placa").val(jsonResponseEst.placa); 
        $("#marca").val(jsonResponseEst.marca); 
        $("#modelo").val(jsonResponseEst.modelo); 
        $("#nromotor").val(jsonResponseEst.nromotor); 
        $('#requiere_acompanante').val(jsonResponseEst.requiereacompanante); 

        //Datos del beneficiario
        $.get('controller/beneficiariosestacionamientosback.php', { oper: 'getbeneficiario', id: idbeneficiario })
        .done(function(respuestaBen) {
            let jsonResponseBen = JSON.parse(respuestaBen);
            $('#tipodocumento').val(jsonResponseBen.paciente.tipo_documento).trigger('change');
            if(jsonResponseBen.paciente.tipo_documento == 1){
                $("#tipodocumento_txt").html('Cédula <span class="text-red">*</span>');
            }else if(jsonResponseBen.paciente.tipo_documento == 2){
                $(".vcto_cm").removeClass('d-none');
                $("#tipodocumento_txt").html('Carnet migratorio <span class="text-red">*</span>');
            }else{
                $("#tipodocumento_txt").html('Documento de identidad personal <span class="text-red">*</span>');
            }
            
            $('#cedula').val(jsonResponseBen.paciente.cedula);
            $('#cedulabd').val(jsonResponseBen.paciente.cedula);
            $('#nombre').val(jsonResponseBen.paciente.nombre);
            $('#apellidopaterno').val(jsonResponseBen.paciente.apellidopaterno);
            $('#apellidomaterno').val(jsonResponseBen.paciente.apellidomaterno);
            $('#expediente').val(jsonResponseBen.paciente.expediente);
            $('#correo').val(jsonResponseBen.paciente.correo);
            $('#telefonocelular').val(jsonResponseBen.paciente.celular);
            $('#telefonootro').val(jsonResponseBen.paciente.telefono);			
            $('#fecha_nac').val(jsonResponseBen.paciente.fecha_nac); 
            var edad = calcularEdad(jsonResponseBen.paciente.fecha_nac);	
            $('#edad').val(edad); 
            $('#sexo').val(jsonResponseBen.paciente.sexo).trigger('change'); 
            $("#urbanizacion").val(jsonResponseBen.direccion.urbanizacion);
            $("#calle").val(jsonResponseBen.direccion.calle);
            $("#edificio").val(jsonResponseBen.direccion.edificio);
            $("#numerocasa").val(jsonResponseBen.direccion.numero);
            $("#area").val(jsonResponseBen.direccion.area);
            provincias(jsonResponseBen.direccion.provincia);
            distritos(jsonResponseBen.direccion.distrito, jsonResponseBen.direccion.provincia);
            corregimientos(jsonResponseBen.direccion.corregimiento, jsonResponseBen.direccion.provincia, jsonResponseBen.direccion.distrito);
        })
        .fail(function() {
            swal("Error","Error al obtener los datos del beneficiario","error");		
        });

        if(jsonResponseEst.requiereacompanante){
            //Datos del acompanante
            $.get('controller/acompanantesestacionamientosback.php', { oper: 'getDatosAcompanantes', idacompanante: idacompanante })
            .done(function(respuestaAco) {
                let jsonResponseAco = JSON.parse(respuestaAco);
                $('#td_acompanante, #td_acompanante_ac, #tipodocumento_ac').val(jsonResponseAco.tipo_documento).trigger('change');
				$("#cedula_acompanante, #cedula_ac").val(jsonResponseAco.cedula);
				$("#nombre_ac").val(jsonResponseAco.nombre_ac);
				$("#apellido_ac").val(jsonResponseAco.apellido_ac);
				$("#celular_ac").val(jsonResponseAco.celular);
				$("#telefono_ac").val(jsonResponseAco.telefono);
				$("#correo_ac").val(jsonResponseAco.correo);
				$("#fecha_nac_ac").val(jsonResponseAco.fecha_nac);
				$("#nacionalidad_ac").val(jsonResponseAco.nacionalidad);
				$("#sexo_ac").val(jsonResponseAco.sexo).trigger('change');
				$("#estado_civil_ac").val(jsonResponseAco.estado_civil).trigger('change');
				provincias_ac(jsonResponseAco.provincia);
				distritos_ac(jsonResponseAco.distrito,jsonResponseAco.provincia);
				corregimientos_ac(jsonResponseAco.corregimiento,jsonResponseAco.provincia,jsonResponseAco.distrito);
                $("#modal-nuevoacompanante-iddireccion").val(jsonResponseAco.direccion); 
				$("#area_ac").val(jsonResponseAco.area_ac);
				$("#urbanizacion_ac").val(jsonResponseAco.urbanizacion);
				$("#calle_ac").val(jsonResponseAco.calle);
				$("#edificio_ac").val(jsonResponseAco.edificio);
				$("#numero_ac").val(jsonResponseAco.numero); 
				
				//Dirección acompañante
				$("#modal-nuevoacompanante-iddireccion").val(jsonResponseAco.direccion); 
            })
            .fail(function() {
                swal("Error","Error al obtener los datos del acompañante","error");		
            });
        }
        
    })
    .fail(function() {
        swal("Error","Error al cargar los datos de la solicitud de estacionamiento","error");		
    });

}  

$("select").select2({ language: "es" });