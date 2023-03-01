$("select").select2({ language: "es" });

//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	$("#tablamedicos").DataTable().search("").draw();
	$('#tablamedicos_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablamedicos.ajax.reload();
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
        $("#idespecialidades").empty();
        $("#idespecialidades").append(result);
        if (id != 0){
			$("#idespecialidades").val(id).trigger('change');
        }
    });
}

discapacidades(0);
function discapacidades(id){
    $.get("controller/combosback.php?oper=discapacidades", {}, function(result)
    {
        $("#iddiscapacidades").empty();
        $("#iddiscapacidades").append(result);
        if (id != 0){
			$("#iddiscapacidades").val(id).trigger('change');
        }
    });
}

regionales(0);
function regionales(id){
    $.get("controller/combosback.php?oper=regionales", {}, function(result)
    {
        $("#idregionales").empty();
        $("#idregionales").append(result);
        if (id != 0){
			$("#idregionales").val(id).trigger('change');
        }
    });
}

$("#tipodocumento").on('change',function(){
	$("#cedula").removeClass('cedula');
	$("#cedula").val("");
	$("#cedula").off();
	if($(this).val()!=0){
		var texto = $(this).select2('data')[0].element.text;
		$("#cedula").prop('disabled',false);
		if($(this).val()==1){
			$("#cedula").addClass('cedula');
			$("#cedula").on('blur', function(){
				if( $(this).val() != '' ){
					if( !validar_cedula($(this).val()) ){
						$.when(swal('Error','Formato de cédula inválido','error')).done(function(){$(this).focus();});    					    
					}else{
						var tipo_documento = $("#tipodocumento").val();
						var cedula 		   = $("#cedula").val();
						$.ajax({
							type: "POST",
							dataType: "json",
							url: 'controller/medicosback.php',
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
												fillFormMedico();
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
								alert( error );
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

if ( $("#tablamedicos").length ) {
//HEADER
$('#tablamedicos thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'ID'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 60px" /> ' );
			}else if(title == 'Cédula'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 120px" /> ' );
			}else if(title == 'Nombre'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" /> ' );
			}else if(title == 'Especialidad'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" /> ' );
			}else if(title == 'Teléfono'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
			}else if(title == 'Correo'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" /> ' );
			}else if(title == 'Regional'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
			}else if(title == 'No registro'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" /> ' );
			}
		}else{
			$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
		}
	}else if(title == 'Acciones'){
		var ancho = '50px';
	}
	$(this).width(ancho);
});

/* TABLA */
var tablamedicos = $("#tablamedicos").DataTable({
    scrollY: '100%',
	scrollX: true,
	scrollCollapse: true,
	destroy: true,
	ordering: false,
	processing: true,
	autoWidth : false,
	stateSave: true,
	searching: true,
	pageLength: 50,
	//lengthChange: false,
	//serverSide: true,
	//serverMethod: 'post',
	/*-ACCEDIENDO-AL_LOCALSTORE_PARA_RECUPERAR_VALORES-------------------*/
	stateLoadParams: function (settings, data) {
		console.log(data)
		const{columns}=data
		$('th#ccedula input').val(columns[2].search.search);
		$('th#cnombre input').val(columns[3].search.search);
		$('th#cespecialidad input').val(columns[4].search.search);
		$('th#ctelefono input').val(columns[5].search.search);
		$('th#ccorreo input').val(columns[6].search.search); 
		$('th#cregional input').val(columns[7].search.search);
		$('th#nroregistro input').val(columns[7].search.search);
	},
    ajax: {
        url: "controller/medicosback.php?oper=listado"
    },
    columns: [
        { "data": "acciones" },			//0
		{ "data": "id" }, 				//1
        { "data": "cedula" },			//2
		{ "data": "nombre" },			//3	
		{ "data": "especialidad" },		//4	
		{ "data": "telefono" },			//5
		{ "data": "correo" },			//6
		{ "data": "regional" },			//7
		{ "data": "nroregistro" }		//8
    ],
    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
    columnDefs: [//OCULTAR LA COLUMNA id, Observaciones
        {
            targets: [1],
            visible: false
        },{
			"targets"	: [ 0 ],
			"className"	:  'text-center'
		}
    ],
    language:
    {
        url: "js/Spanish.json",
    },
    //lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    drawCallback: function( settings )
    {
		ajustarTablas();
    },
    initComplete: function()
    {
		cargarDropdownMenu();
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
		//OCULTAR LOADER
		param++;
		loader(param, '1');
    },
	dom: '<"toolbarU toolbarDT">Blfrtip'
});/*fin tabla*/

// AL CARGARSE LA TABLA
tablamedicos.on('draw.dt', function(e){
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $('.boton-eliminar-fisico').each(function(){
        var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().parent().next().next().html();
		$(this).on('click', function(){
            eliminarMedico(id, nombre);
        });
    });
});

}

$("#nuevoMedico").click(function(){
	location.href = 'medico.php';
});

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
                location.href = 'medicos.php';
            }
        },
        function(isRechazo) {
            console.log(isRechazo);
        }
    );
});

$("#listadoMedicos").on("click",function(){
	location.href = 'medicos.php';
});

setId();
function setId() {
    if(getQueryVariable('id')){
        $('#idmedico').val(getQueryVariable('id'));
    }
}

fillFormMedico();
function fillFormMedico() {
    if($('#idmedico').val()){
		var id = $('#idmedico').val();
		$.post("controller/medicosback.php?oper=getmedico", {id}, function(response){
			const datos = JSON.parse(response);
			$('#tipodocumento').val(datos.tipo_documento).trigger('change');
			if(datos.tipo_documento == 1){
				$("#tipodocumento_txt").html('Cédula <span class="text-red">*</span>');
			}else if(datos.tipo_documento == 2){
				$("#tipodocumento_txt").html('Carnet migratorio <span class="text-red">*</span>');
			}else{
				$("#tipodocumento_txt").html('Documento de identidad personal <span class="text-red">*</span>');
			}
			$('#cedula').val(datos.cedula);
			$('#nombre').val(datos.nombre);
			$('#apellido').val(datos.apellido);
			especialidades(datos.especialidad.split(','));
			$('#telefonocelular').val(datos.telefonocelular);
			$('#telefonootro').val(datos.telefonootro);
			$('#correo').val(datos.correo);			
			discapacidades(datos.discapacidades.split(','));
			regionales(datos.regional.split(','));
			$('#nroregistro').val(datos.nroregistro);	
		});
    }
}

function guardar(){
    $('#preloader').css('display', 'block');
	var idmedico = getQueryVariable('id');
    var dataserialize = $('#form_medico').serializeArray();
	var datos = {};
	for (var i in dataserialize){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'tipodocumento' ){
			datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else if(dataserialize[i].name == 'idespecialidades' || dataserialize[i].name == 'iddiscapacidades' || 
				 dataserialize[i].name == 'idregionales'){
			datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val").join();
		}else{
			datos[dataserialize[i].name] = dataserialize[i].value;
		}
	}
	let msj = '';
    if (idmedico == ''){
        var oper = "guardarmedico";
		msj = 'creado';
    }else{
        var oper = "editarmedico";
		msj = 'modificado';
    }
    if (validarform()){
        $.ajax({
	        url: "controller/medicosback.php",
	        type: "POST",
	        data: {
	            oper: oper,
	            datos: datos,
                id: idmedico
	        },
	        dataType: "json",
	        success: function(response){
	            $('#preloader').css('display', 'none');
	            if (response == 1){ 
					swal('Buen trabajo', `Trabajador social ${msj} satisfactoriamente`, 'success');
					location.href = "medicos.php"; 
	            }else if(response == 3){
					swal('Advertencia', 'El trabajador social ya existe', 'error');
				}else if(response == 4){
					swal('Advertencia', 'El numero de registro ya existe en otro trabajador', 'error');
				}else{
	                swal('Error', 'Error al '+oper+' el trabajador social', 'error');
	            }
	        }
    	});
    }else{
    	$('#preloader').css('display', 'none');
    }
}

function limpiarForm(){
	$("#form_medico")[0].reset();
	$("#form_medico").find('select').each(function() {
       $(this).val(0).trigger('change');
	   $("#idespecialidades, #iddiscapacidades, #idregionales").val(null).trigger('change');
	});
	$("#tipodocumento_txt").html('Documento de identidad personal <span class="text-red">*</span>');
}

function validarform(){
	var verdad 		= true;
    var cedula		= $('#cedula').val();
	var tipodocumento	= $('#tipodocumento').val();
	var nombre		= $('#nombre').val();
	var apellido	= $('#apellido').val();
    var especialidad= $('#especialidad').val();
    var correo		= $('#correo').val();
    var discapacidades	= $('#discapacidades').val();
    var regional	= $('#regional').val();
    expresion		=/\w+@\w+\.+[a-z]/;

    if (tipodocumento == "0"){
		swal('Error', 'Seleccione un tipo de documento', 'error');
		return false;
	}else if (cedula==""){
		swal('Error', 'El número de documento esta vacío', 'error');
		return false;
	}else if (nombre==""){
		swal('Error', 'El nombre esta vacío', 'error');
		return false;
	}else if (apellido==""){
		swal('Error', 'El apellido esta vacío', 'error');
		return false;
	}else if (especialidad==""){
		swal('Error', 'La especialidad esta vacía', 'error');
		return false;
	}else if (correo==""){
		swal('Error', 'El correo esta vacío', 'error');
		return false;
	}else if (discapacidades==""){
		swal('Error', 'La discapacidad esta vacío', 'error');
		return false;
	}else if (regional==""){
		swal('Error', 'La regional esta vacío', 'error');
		return false;
	}
	return verdad;
}

// PARA EDITAR USUARIOS
$("#tablamedicos tbody").on('dblclick', 'tr', function(){
    $("#title_m").text("Editar usuario");
    var idmedico = $(this).attr("id");
    cargarMedico(idmedico);
});

function cargarMedico(idmedico){
    $.get("controller/medicosback.php?oper=getmedico", { idmedico: idmedico }, function(result){
        result = JSON.parse(result);
        result = result[0];
    });
}

function eliminarMedico(id, nombre){
	$.get("controller/medicosback.php?oper=checkpacientemedico", { id: id },
		function(result){
			if (result > 0) { // SI EXISTE UN PACIENTE CON ESE medico, NO SE PUEDE ELIMINAR.
				swal('ERROR','Existen pacientes asociados a este médico, no se puede eliminar','error');
			} else {
				swal({
					title: "Confirmar",
					text: "¿Está seguro de eliminar el médico "+nombre+"?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Sí',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value == true){
							$.get( "controller/medicosback.php", 
							{ 
								'oper'	: 'eliminar',
								'id' 	: id,
								'nombre': nombre
							}, function(result){
								if(result == 1){
									swal('Buen trabajo', 'Médico eliminado satisfactoriamente', 'success');
									// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
									tablamedicos.ajax.reload(null, false);
									tablamedicos.columns.adjust();
								} else {
									swal('ERROR','Ha ocurrido un error al eliminar el médico, intente más tarde','error');
								}
							});

						}
					}, function (isRechazo){
						console.log(isRechazo);
					}
				);
			}
		}
	);
}

$("select").select2({ language: "es" });