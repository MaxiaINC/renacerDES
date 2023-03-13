window.onload = function(){  

    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    //Change hash for page-reload
    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').on('shown', function (e) {
        window.location.hash = e.target.hash;
    }); 
} 

function filename(){
	var rutaAbsoluta = self.location.href;   
	var posicionUltimaBarra = rutaAbsoluta.lastIndexOf("/");
	var rutaRelativa = rutaAbsoluta.substring( posicionUltimaBarra + "/".length , rutaAbsoluta.length );
	return rutaRelativa;  
}
var getUrl = filename();
var arrUrl = getUrl.split('.');
var nombre_archivo = arrUrl[0];

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
$("#beneficios").on('select2:select', function(e) {
	var beneficio = $(this).val();
	if (beneficio == "1"){
		$("#beneficios_descripcion_box").css('display','block');	
		$("#beneficios_descripcion").addClass('mandatorio');
	}else{
		$("#beneficios_descripcion_box").css('display','none');
		$("#beneficios_descripcion").val('');
		$("#beneficios_descripcion").removeClass('mandatorio');
	}
});
$("#fecha_nac, #fecha_vcto_cm").bootstrapMaterialDatePicker({
	date: true,
	format:'YYYY-MM-DD',
	time:false,
	lang: 'es',
});
$("#fecha_nac").on('change', function(e) {
	var fecha_nac = $("#fecha_nac").val();
	var edad = calcularEdad(fecha_nac,'');
	$("#edad").val(edad);
});

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
						var cedulabd 	   = $("#cedulabd").val();
						var fecha_vcto_cm  = $("#fecha_vcto_cm").val();
						var fecha_vcto_cm_bd  = $("#fecha_vcto_cm_bd").val();
						var expediente	   = $("#expediente").val();
						
						if((cedula !== cedulabd && tipo_documento == 2) && (fecha_vcto_cm == fecha_vcto_cm_bd || fecha_vcto_cm == '')){ 
							swal({
								title: "Advertencia",
								html: "Debe ingresar la fecha de vencimiento del carnet migratorio",
								type: "info",
								//showCancelButton: true,
								//cancelButtonColor: 'red',
								confirmButtonColor: '#09b354',
								confirmButtonText: 'Sí'/*,
								cancelButtonText: "No"*/
							}).then(
								function(isConfirm){
									if (isConfirm.value == true){
										$("#fecha_vcto_cm").focus();
									}else{
										//limpiarForm();
										//$("#tipodocumento").focus();
									}
								},
								function(isRechazo){
									limpiarForm();
								}
							);
						}
						
						if(nombre_archivo == 'solicitud'){
							//Solicitudes
							$.ajax({
								type: "POST",
								dataType: "json",
								url: 'controller/beneficiariosback.php',
								data: { oper: 'existe', tipo_documento: tipo_documento, cedula: cedula },
								success: function( response ) { 
									if(response.success == true){
										$('#idbeneficiario').val(response.id);									
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
													$('#idbeneficiario').val('');
													limpiarForm();
													$("#tipodocumento").focus();
												}
											},
											function(isRechazo){
												limpiarForm();
											}
										);
									}else{
										
									}
								},
								error: function( error ){
									alert( error );
								}
							});
						}else{
							//Beneficiarios 
							//if(!getQueryVariable('id')){
								$.ajax({
									type: "POST",
									dataType: "json",
									url: 'controller/beneficiariosback.php',
									data: { oper: 'existe', tipo_documento: tipo_documento, cedula: cedula },
									beforeSend: function(){
									   $('#overlay').css('display','block');
									},
									success: function( response ) { 
										$('#overlay').css('display', 'none');
										if(response.success == true){
											swal('Error','El Nº de documento ya está registrado','error')
											$("#cedula").val('');
										}else{
											//limpiarForm();
										}
									},
									error: function( error ){
										alert( error );
									}
								});
							//} 
						}						
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

var usuarioSes = localStorage.getItem('user_sen');
if(!getQueryVariable('id')){
	provincias(0);
	distritos(0);
	corregimientos(0); 
	nacionalidades(0); 
	if(usuarioSes == 'dlombana' || nivelSes == 14 || nivelSes == 15){
		$("#expediente").removeAttr('disabled');
	}else{ 
		$(".box-expediente").addClass('d-none');
	}
}else{ 
	if(usuarioSes == 'dlombana' || nivelSes == 14 || nivelSes == 15){
		$("#expediente").removeAttr('disabled');
	}	
}

$("#cancelar").on('click',function(){
    swal({
        title: "Confirmar",
        text: "¿Esta seguro de cancelar el registro?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: 'red',
        confirmButtonColor: '#09b354',
        confirmButtonText: 'Sí',
        cancelButtonText: "No"
    }).then(
		function(isConfirm){
            if(isConfirm.value==true){
                location.href = 'beneficiarios.php';
            }
        },
        function(isRechazo) {
            console.log(isRechazo);
        }
    );
});

$("#listadoBeneficiarios").on("click",function(){
	location.href = 'beneficiarios.php';
});
$("#listadoSolicitudes").on("click",function(){
	location.href = 'solicitudes.php';
});
//$(document).ready(function() {
	setId();
	function setId() {
		if(getQueryVariable('id')){
			$('#idbeneficiario').val(getQueryVariable('id'));
		}
	}
	const ocultarCarnet = () =>{
		if(getQueryVariable('id')){
			if(nivelSes == 14 || nivelSes == 15){
				$('.li_carnet').show();
			}else{
				$('.li_carnet').hide();	
			} 
		}else{
			$('.li_carnet').hide();
		}
	}
	if(nombre_archivo !== 'solicitud'){
		ocultarCarnet();
	}
	
	function getAbsolutePath() {
		var loc = window.location;
		var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
		return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
	}
	var ruta = getAbsolutePath();  

	//Mapa  
	const mapa = (latitud,longitud) => {
		var map = new google.maps.Map(document.getElementById('map_canvas'), {
		  center: {lat: latitud, lng: longitud},
		  zoom: 16,
		  mapTypeId: 'roadmap',
		  restriction: {
			  latLngBounds: {
				north: 85,
				south: -85,
				west: -180,
				east: 180
			  },
			  strictBounds: true
			} 
		});
		
		var marker = new google.maps.Marker({
			position: {lat:latitud, lng: longitud},
			map: map,
			draggable: true
		});
		
		marker.addListener('dragend', function (event) {
			var lat = marker.getPosition().lat();
			var lng = marker.getPosition().lng();
			$("#latitud").val(lat);
			$("#longitud").val(lng);
			/* $.ajax({
				url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + lng + '&key=AIzaSyC037nleP4v84LrVNzb4a0fn33Ji37zC18',
				success: function (response) {
					$("#direccionmapa").val(response.results[0].formatted_address);
					$("#direccionmapa").trigger("change");
				}
			}); */
		}); 
		
		map.addListener('center_changed', function (event) {
			var center = map.getCenter();
			var lat = center.lat();
			var lng = center.lng();
			
			marker.setPosition({lat,lng});
			$("#latitud").val(lat);
			$("#longitud").val(lng); 
		});   	
		
		const center = {lat: 8.984700, lng: -79.521019};
		// Create a bounding box with sides ~10km away from the center point
		const defaultBounds = {
		  north: center.lat + 0.1,
		  south: center.lat - 0.1,
		  east: center.lng + 0.1,
		  west: center.lng - 0.1,
		}; 
		const input = document.getElementById("pac-input");
		const options = {
		  bounds: defaultBounds,
		  componentRestrictions: { country: "pa" },
		  fields: ["address_components", "geometry", "icon", "name"],
		  strictBounds: false
		};
		const autocomplete = new google.maps.places.Autocomplete(input, options);
		const southwest = { lat: 9.56506746243727, lng: 7.2938852895963455 }; 
		const northeast = { lat: -82.83419829963371, lng: -77.92330964824676};
		const newBounds = new google.maps.LatLngBounds(southwest, northeast);
		
		autocomplete.setBounds(newBounds);
		autocomplete.addListener("place_changed", () => {
			const place = autocomplete.getPlace();
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);
			}

			marker.setPosition(place.geometry.location);
			document.getElementById("pac-input").value="";
		}); 
	}
	
	if(nombre_archivo !== 'solicitud'){
		mapa(8.984700,-79.521019);  
	} 

	const getPaciente = (id) =>{
		$.post("controller/beneficiariosback.php?oper=getpaciente", {id: id}, function(response){
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
			if(datos.paciente.imagen !== ""){
				$(".crop-image").attr("src", `${ruta}images/beneficiarios/${id}/${datos.paciente.imagen}?${par}`);
			} 
			
			$(".imgqr").append(`<h5 class="col-form-label text-success text-left">Código QR</h5><img src="${ruta}images/beneficiarios/${id}/qr/${id}qr.png?${par}" width="150">`);
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
			$('#fecha_vcto_cm').val(datos.paciente.fecha_vcto_cm);
			$('#fecha_vcto_cm_bd').val(datos.paciente.fecha_vcto_cm);
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
			//OTROS
			$('#condicion_actividad').val(datos.paciente.condicion_actividad).trigger('change');
			$('#categoria_actividad').val(datos.paciente.categoria_actividad).trigger('change');
			$('#cobertura_medica').val((datos.paciente.cobertura_medica).split(',')).trigger('change');			
			if (datos.paciente.beneficios == "1"){
				$('#beneficios').val(datos.paciente.beneficios).trigger('change');
				$("#beneficios_descripcion_box").css('display','block');	
				$("#beneficios_descripcion").addClass('mandatorio');
				$('#beneficios_descripcion').val(datos.paciente.beneficios_des);
			}else{
				$('#beneficios').val('2').trigger('change');
				$("#beneficios_descripcion_box").css('display','none');
				$("#beneficios_descripcion").val('');
				$("#beneficios_descripcion").removeClass('mandatorio');
			}
			//niveles(datos.nivel);
			//Georeferencia
			if(datos.paciente.latitud != ''  && datos.paciente.latitud != null && datos.paciente.longitud != ''  && datos.paciente.longitud != null){ 
				$("#latitud").val(datos.paciente.latitud);
				$("#longitud").val(datos.paciente.longitud);
				var latitud = parseFloat(datos.paciente.latitud);
				var longitud = parseFloat(datos.paciente.longitud);
				if(nombre_archivo !== 'solicitud'){
					mapa(latitud,longitud);
				}  
			}else{
				$(".infoubicacion").text('La ubicación no ha sido seleccionada');
			} 
			
			
			
			//Mostrar tab carnet solo si la solicitud está en estado Pendiente por imprimir
			datos.paciente.carnet == 1 ? $('.li_carnet').show() : $('.li_carnet').hide();
			//Mostrar datos del carnet solo si la solicitud está en estado Pendiente por imprimir
			if(datos.paciente.carnet == 1)
			getDatosCarnet(datos.paciente.expediente);
			
			//Historial de cambio de número de documento
			if(datos.paciente.totalcertificados >= 1){
				
				$('.ver-historial_nrodoc').css("display","block");
				
				$('#tabla_historial_nrodoc thead th').each( function (){
					var title = $(this).text();
					var id = $(this).attr('id');
					var ancho = $(this).width();
					if ( title !== ''){
						if (screen.width > 1024){
							if(title == 'Tipo de documento' || title == 'N° de documento'){
								$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 250px" /> ' );
							}else if(title == 'Fecha de vcto. carnet migratorio' || title == 'Fecha de modificación'){
								$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" /> ' );
							}			
						}else{
							$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
						}
					}
					$(this).width(ancho);
				});

				tabla_historial_nrodoc = $("#tabla_historial_nrodoc").DataTable({
					destroy: true,
					ordering: false,
					searching: false, 
					"ajax"		: {
						"url"	: "controller/beneficiariosback.php?oper=getHistorialNrodoc&idpac="+id,
					},
					"columns"	: [
						{ 	"data": "id" },
						{ 	"data": "tipodocumento" },
						{ 	"data": "nrodoc" },
						{ 	"data": "fecha_vcto_cm" },
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
			}
		});
	}
	fillForm();
	function fillForm() {
		if($('#idbeneficiario').val()){
			var id = $('#idbeneficiario').val();
			getPaciente(id);
		}else{
			$(".infoubicacion").text('La ubicación no ha sido seleccionada');
		}
	}

	$("#guardar").on("click",function(){
		guardar();
	});
	function guardar(){
		$('#preloader').css('display', 'block');
		var idbeneficiario = getQueryVariable('id');
		var disabled = $('#form_beneficiario').find(':input:disabled').removeAttr('disabled');
		var dataserialize = $('#form_beneficiario').serializeArray();
		disabled.attr('disabled','disabled');
		var datos = {};
		for (var i in dataserialize){
			//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
			if( dataserialize[i].name == 'tipodocumento' || dataserialize[i].name == 'sexo' ||
				dataserialize[i].name == 'estado_civil' || dataserialize[i].name == 'idprovincias' ||
				dataserialize[i].name == 'iddistritos' || dataserialize[i].name == 'idcorregimientos' ||
				dataserialize[i].name == 'condicion_actividad' || dataserialize[i].name == 'categoria_actividad' ||
				dataserialize[i].name == 'beneficios' || dataserialize[i].name == 'status'){
				datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
			}else if(dataserialize[i].name == 'cobertura_medica'){
				datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val").join();
			}else{
				datos[dataserialize[i].name] = dataserialize[i].value;
			}
		}
		
		if (idbeneficiario == ''){
			var oper = "guardar_paciente";
		}else{
			var oper = "editar_paciente";
		}
		if (validarform()){
			$.ajax({
				url: "controller/beneficiariosback.php",
				type: "POST",
				data: {
					oper: oper,
					datos: datos,
					id: idbeneficiario
				},
				dataType: "json",
				success: function(response){
					$('#preloader').css('display', 'none');
					if (response.success == true){
						swal('Buen trabajo', response.msj, 'success');
						location.href = "beneficiarios.php";
					}else{
						swal('Error', response.msj, 'error');
					}
				}
			});
		}else{
			$('#preloader').css('display', 'none');
		}
	}

	function limpiarForm(){
		$("#form_beneficiario")[0].reset();
		$("#form_beneficiario").find('select').each(function() {
		   $(this).val(0).trigger('change');
		   $("#cobertura_medica").val(null).trigger('change');
		});
		$("#tipodocumento_txt").html('Documento de identidad personal <span class="text-red">*</span>');
	}

	function validarform(){
		var verdad 	= true;
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
		var condicion_actividad	= $('#condicion_actividad').val();
		var categoria_actividad	= $('#categoria_actividad').val();
		var cobertura_medica	= $('#cobertura_medica').val();
		var beneficios		= $('#beneficios').val();
		var beneficiosd		= $('#beneficios_descripcion').val();
		var fecha_vcto_cm	= $('#fecha_vcto_cm').val();
		var fecha_vcto_cm_bd	= $('#fecha_vcto_cm_bd').val();
		expresion	=/\w+@\w+\.+[a-z]/;

		if (tipodocumento == "0"){
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
		}else if (condicion_actividad == "0"){
			swal('Error', 'Seleccione una condición de actividad', 'error');
			return false;
		}/*else if (categoria_actividad == "0"){
			swal('Error', 'Seleccione una categoría de actividad', 'error');
			return false;
		}*/else if (cobertura_medica == "0"){
			swal('Error', 'Seleccione una cobertura médica', 'error');
			return false;
		}else if (beneficios == "0"){
			swal('Error', 'Seleccione si recibe o no beneficios', 'error');
			return false;
		}else if (beneficios == "1" && beneficiosd == ""){
			swal('Error', 'Debe indicar que beneficios del estado recibe', 'error');
			return false;
		}else if((cedula != cedulabd && tipodocumento == 2) && (fecha_vcto_cm == '' || fecha_vcto_cm == fecha_vcto_cm_bd)){
			if(fecha_vcto_cm == ''){
				swal('Error', 'Debe agregar la fecha de vencimiento del carnet migratorio', 'error');
			}else{
				swal('Error', 'Debe cambiar la fecha de vencimiento del carnet migratorio', 'error');
			}
			
			return false;
		}
		return verdad;
	}

	//Historial de cambio de número de documentación
	$(".ver-historial_nrodoc").on('click', function(){
		$("#modal_historial_nrodoc").modal('show');
	});

	if(!getQueryVariable('id') && !getQueryVariable('idsolicitud')){
		$('#fecha_nac').bootstrapMaterialDatePicker({
			weekStart: 0, format: 'YYYY-MM-DD', shortTime : true
		}).on('change', function(e, date) {
			
			let nombre = $('#nombre').val();
			let apellidopaterno = $('#apellidopaterno').val();
			let apellidomaterno = $('#apellidomaterno').val();
			let fechanac = $('#fecha_nac').val();
			
			$.post("controller/beneficiariosback.php?oper=existeNombreFecha", {nombre: nombre, apellidopaterno: apellidopaterno, apellidomaterno: apellidomaterno, fechanac: fechanac}, function(response){
				const rta = JSON.parse(response);
				if(rta.idpaciente !== 0){
					swal({
						title: "Notificación",
						html: `Existe un beneficiario con datos similares y con ${rta.documento}. ¿Desea cargar los datos?`,
						type: "info",
						showCancelButton: true,
						cancelButtonColor: 'red',
						confirmButtonColor: '#09b354',
						confirmButtonText: 'Sí',
						cancelButtonText: "No"
					}).then(
						function(isConfirm){
							if (isConfirm.value == true){
								$('#idbeneficiario').val(rta.idpaciente);
								getPaciente(rta.idpaciente);
							} 
						},
						function(isRechazo){
							//
						}
					);
				}						
			});
			
		});
	}
	const getDatosCarnet = (expediente) =>{
		console.log('pasó getdatos carnet');
		$('.advertencias').empty();
		let par = new Date().getTime()
		let idpaciente = getQueryVariable('id');
		$('#preloader').css('display', 'block');
		let tipo_documento = $('#tipodocumento').val();
		let fecha_vcto_cm = $('#fecha_vcto_cm').val();	
		
		if(tipo_documento == 2 && fecha_vcto_cm == ''){
			$('.advertencias').append(`<div class="alert alert-danger" role="alert"> Debe agregar la fecha de vencimiento del carnet migratorio. </div>`);
		}else{
			$.post("controller/beneficiariosback.php?oper=obtenerValidacionDeDerecho", {expediente: expediente}, function(response){
				const valores = JSON.parse(response);
				if(response != 0){
					
					let esvalido = 1;
					if(valores.desde == ''){
						$('.advertencias').append(`<div class="alert alert-danger" role="alert"> Debe agregar la fecha de emisión de la certificación. </div>`);
						esvalido = 0;
					}
					if(valores.hasta == ''){
						$('.advertencias').append(`<div class="alert alert-danger" role="alert"> Debe agregar la fecha de vencimiento de la certificación. </div>`);
						esvalido = 0;
					}
					if(valores.imagen == ''){
						$('.advertencias').append(`<div class="alert alert-danger" role="alert"> Debe agregar la foto del beneficiario. </div>`);
						esvalido = 0;
					} 
					if(valores.qr == false){
						$('.advertencias').append(`<div class="alert alert-danger" role="alert"> Debe generar el código QR. </div>`);
						esvalido = 0;
					}
					if(valores.firmas == false){
						$('.advertencias').append(`<div class="alert alert-danger" role="alert"> Se deben agregar las firmas en el módulo de firmas, por favor comuníquese con su supervisor. </div>`);
						esvalido = 0;
					}
					if(esvalido == 1){
						$('.certificado, .boton-imprimir').removeClass('d-none');
						$('.txt_nombre').text(valores.nombrecompleto);
						$('.txt_cedula').text(valores.cedula);
						$('.txt_nacionalidad').text(valores.nacionalidad);
						$('.txt_fechanacimiento').text(valores.fecha_nac);
						$('.txt_expedicion').text(valores.desde);
						$('.txt_expiracion').text(valores.hasta);
						$('#fechaemisionhidden').val(valores.fechaemision);
						$('#fechavencimientohidden').val(valores.fechavencimiento);
						$('.txt_directorgeneral').text(valores.arrayFirmas.DirectorGeneral);
						$('.imgfirma_directorgeneral').attr("src",`${ruta}images/firmas/${valores.arrayFirmas.IdDirGen}/${valores.arrayFirmas.IdDirGen}.png?${par}`);
						$('.imgfirma_directornacional').attr("src",`${ruta}images/firmas/${valores.arrayFirmas.IdDirNac}/${valores.arrayFirmas.IdDirNac}.png?${par}`);
						$('.txt_directornacional').text(valores.arrayFirmas.DirectorNacional);
						$('.imgfoto_beneficiario').attr("src",`${ruta}images/beneficiarios/${idpaciente}/${valores.imagen}?${par}`);
						$('.imgqr_beneficiario').attr("src",`${ruta}images/beneficiarios/${idpaciente}/qr/${idpaciente}qr.png?${par}`);
						
						if(valores.checkimpreso == 1){
							$("#marcarimpreso").prop("checked", true);
							$('.text_marcarimpreso').text('Impreso');
						}else{
							$('.text_marcarimpreso').text('Marcar como impreso');
						}
						
						//Listado de carnet impresos
						cargarListadoCarnets(idpaciente);
						
						//Decodificar QR
						/* const image = new Image();
						image.src = `${ruta}images/beneficiarios/${idpaciente}/qr/${idpaciente}qr.png?${par}`;

						image.onload = function() {
						  const canvas = document.createElement("canvas");
						  canvas.width = image.width;
						  canvas.height = image.height;

						  const ctx = canvas.getContext("2d");
						  ctx.drawImage(image, 0, 0);

						  const imageData = ctx.getImageData(0, 0, image.width, image.height);
						  const code = jsQR(imageData.data, imageData.width, imageData.height);

						  if (code) {
							console.log(code.data);
						  } else {
							console.log("No QR code found.");
						  }
						}; */
						
					}
				}else{
					$('.advertencias').append(`<div class="alert alert-danger" role="alert"> El beneficiario no ha sido certificado. </div>`);
				}
				$('#preloader').css('display', 'none');
			});
		}
	}
	
	let cargarListadoCarnets = (ipdacientes) =>{
		
		$.post("controller/beneficiariosback.php?oper=getCarnetImpresos", {idpacientes: ipdacientes}, function(response){
			if(response != 0){
				const resp = JSON.parse(response);
				
				$('.tit-listado-carnet').removeClass('d-none');
				
				 let items = '<table class="display w-65 border"><thead class="bg-success-light">'; 
				 items += '<tr><th class="font-w500">Fecha</th><th class="font-w500">Regional</th><th class="font-w500">Usuario</th><th class="font-w500">Tipo</th></thead><tbody>';
				 resp.data.forEach(item => {
					items += `<tr><td>${item.fechacreacion}</td><td>${item.regional}</td><td>${item.usuario}</td><td>${item.tipo}</td></tr>`; 
				 })
				 items += '</tbody></table>';
				$('.listado-carnet').html(items);
			}
		});
	}
	
	//Registra en bitácora las primeras 24 hras
	$("#imprimir").on('click',function(){
		
		//Manda a marcar como impreso solo la primera vez
		if($('#marcarimpreso').is(":checked")) {
			
		}else{
			$('#marcarimpreso').trigger('click');
		}
		
		
		let idpacientes = getQueryVariable('id');
		let fechaemision = $("#fechaemisionhidden").val();
		let fechavencimiento = $("#fechavencimientohidden").val();
		
		$('.duplicado').is(":checked") ? duplicado = 1 : duplicado = 0;
		
		$.ajax({
			type: "POST",
			dataType: "json",
			url: 'controller/beneficiariosback.php',
			data: { oper: 'verificarImpresionBitacora', id: idpacientes },
			success: function( response ) { 
				if(response == 1){
					//Solicitar código de autorización
					$("#modal-beneficiario-codigoautorizacion").modal('show');
				}else{
					
					if($(".duplicado").is(":checked")) {
						//Duplicado
						window.open(`carnet.php?id=${idpacientes}&dup=1`);	
						if(duplicado == 1){
							$(".duplicado").prop("checked", false);
						}
					}else{
						//No duplicado
						window.open(`carnet.php?id=${idpacientes}&dup=0`);
						if(duplicado == 1){
							$(".duplicado").prop("checked", false);
						}
					} 
					
					$.ajax({
						type: "POST",
						dataType: "json",
						url: 'controller/beneficiariosback.php',
						data: { oper: 'guardarImpresionBitacora',
								idpacientes: idpacientes, fechaemision: fechaemision, fechavencimiento: fechavencimiento, duplicado: duplicado },
						success: function( response ) { 
							if(duplicado == 1){
								$(".duplicado").prop("checked", false);
							}
							//Listado de carnet impresos
							cargarListadoCarnets(idpacientes); 
						},
						error: function( error ){ 
							if(duplicado == 1){
								$(".duplicado").prop("checked", false);
							}
							console.log('no se pudo marcar como impreso');
							//swal('Error', 'El carnet no se pudo registrar como impreso', 'error');
						}
					});
					 
				}
			},
			error: function( error ){
				alert( error );
			}
		});						
	}); 
	
	//Registra en bitácora las reimpresiones
	const validarCodigo = ()=>{
	    let idpacientes = getQueryVariable('id');
		let codigoautorizacion =  $("#codigoautorizacion").val();
		let fechaemision = $("#fechaemisionhidden").val();
		let fechavencimiento = $("#fechavencimientohidden").val();
		
		$('.duplicado').is(":checked") ? duplicado = 1 : duplicado = 0;
		
		if(codigoautorizacion == '' || codigoautorizacion == undefined){
			swal('Error', 'Debe agregar el código de autorización', 'error');
		}else{
			 
			$.ajax({
			type: "POST",
			dataType: "json",
			url: 'controller/beneficiariosback.php',
			data: { oper: 'validarCodigoAutorizacion', codigoautorizacion: codigoautorizacion },
			success: function( response ) { 
				
				if(response == 1){ 
					$("#modal-beneficiario-codigoautorizacion").modal('hide');
					$("#codigoautorizacion").val("");
					
					//Guardar bitácora reimpresión
					$.ajax({
						type: "POST",
						dataType: "json",
						url: 'controller/beneficiariosback.php',
						data: { oper: 'guardarImpresionBitacoraReimp',
								idpacientes: idpacientes, duplicado: duplicado, codigoautorizacion: codigoautorizacion, fechaemision: fechaemision, fechavencimiento: fechavencimiento },
						success: function( response ) { 
						
							//Listado de carnet impresos
							cargarListadoCarnets(idpacientes);	
							
						},
						error: function( error ){  
						}
					});
					
					if($(".duplicado").is(":checked")) {
						//Código válido
						window.open(`carnet.php?id=${idpacientes}&event=reimp&dup=1`);
						if(duplicado == 1){
							$(".duplicado").prop("checked", false);
						}						
					}else{
						//Código válido
						window.open(`carnet.php?id=${idpacientes}&event=reimp&dup=0`);	
						if(duplicado == 1){
							$(".duplicado").prop("checked", false);
						}						
					}
					
										
				}else{
					//Código inválido
					swal('Error', 'El código de autorización no es válido', 'error');			
				}
			},
			error: function( error ){
				alert( error );
			}
		});
		}
	}
	const cancelarCodigo = ()=>{
		$("#modal-beneficiario-codigoautorizacion").modal('hide');
	}
//});	 

//Fin document ready

$("select").select2({ language: "es" });


//Marcar como impreso, actualiza solicitud y registra en bitácora la primera vez
$(".marcarimpreso").click(function(event){
	if($(this).is(":checked")) {
		/* swal({
			title: "Confirmar",
			html: "Al presionar sí, se registrará el carnet como impreso. ¿Está seguro?",
			type: "info",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Sí',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm.value == true){ */
					
					let idpacientes = getQueryVariable('id');
					 
					$.ajax({
						type: "POST",
						dataType: "json",
						url: 'controller/beneficiariosback.php',
						data: { oper: 'marcarComoImpreso',
								idpacientes: idpacientes },
						success: function( response ) { 
							$('.text_marcarimpreso').text('Impreso');
							if(duplicado == 1){
								$(".duplicado").prop("checked", false);
							}
							console.log('marcado como impreso');
						},
						error: function( error ){ 
							if(duplicado == 1){
								$(".duplicado").prop("checked", false);
							}
							console.log('no se pudo marcar como impreso'); 
						}
					});	 
				/* 	
				}else{
					$("#marcarimpreso").prop("checked", false);
				}
			},
			function(isRechazo){
				limpiarForm();
			}
		); */
	}
  }); 
  