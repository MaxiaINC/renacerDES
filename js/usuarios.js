$("select").select2({ language: "es" });

//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	$("#tablausuarios").DataTable().search("").draw();
	$('#tablausuarios_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablausuarios.ajax.reload();
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

niveles(0);
function niveles(id){
    $.get("controller/combosback.php?oper=niveles", {}, function(result)
    {
        $("#niveles").empty();
        $("#niveles").append(result);
        if (id != 0){
			console.log('niveles: '+id);
            $("#niveles").val(id).trigger('change');
        }
    });
}
regionales(0);
function regionales(id){
    $.get("controller/combosback.php?oper=regionalesUsu", {}, function(result)
    {
        $("#regional").empty();
        $("#regional").append(result);
        if (id != 0){ 
            $("#regional").val(id).trigger('change');
        }
    });
}

if ( $("#tablausuarios").length ) {
//HEADER
$('#tablausuarios thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'ID'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 60px" /> ' );
			}else if(title == 'Usuario'){ 
				$(this).html( `<input type="text" placeholder="${title}" id="f${id}" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');" style="width: 150px" /> ` );
			}else if(title == 'Nombre'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" /> ' );
			}else if(title == 'Correo'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" /> ' );
			}else if(title == 'Teléfono'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
			}else if(title == 'Cargo'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" /> ' );
			}else if(title == 'Nivel'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
			}else if(title == 'Estado'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 80px" /> ' );
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
var tablausuarios = $("#tablausuarios").DataTable({
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
		const{columns}=data
		$('th#cusuario input').val(columns[2].search.search);
		$('th#cnombre input').val(columns[3].search.search);
		$('th#ccorreo input').val(columns[4].search.search);
		$('th#ctelefono input').val(columns[5].search.search);
		$('th#ccargo input').val(columns[6].search.search); 
		$('th#cnivel input').val(columns[7].search.search);
		$('th#cestado input').val(data.columns[8].search.search);
	},
    ajax: {
        url: "controller/usuariosback.php?oper=listado"
    },
    columns: [
        { "data": "acciones" },		//0
		{ "data": "id" }, 			//1
        { "data": "usuario" },		//2
		{ "data": "nombre" }, 		//3
		{ "data": "correo" },		//4
		{ "data": "telefono" },		//5
		{ "data": "cargo" },		//6
		{ "data": "nivel" },		//7
		{ "data": "estado" } 		//8
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
tablausuarios.on('draw.dt', function(e){
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $('.boton-eliminar-fisico').each(function(){
        var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().parent().next().next().html();
		$(this).on('click', function(){
            eliminarUsuario(id, nombre);
        });
    });
});

}

$("#nuevoUsuario").click(function(){
	location.href = 'usuario.php';
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
                location.href = 'usuarios.php';
            }
        },
        function(isRechazo) {
            console.log(isRechazo);
        }
    );
});

$("#listadoUsuarios").on("click",function(){
	location.href = 'usuarios.php';
});

setId();
function setId() {
    if(getQueryVariable('id')){
        $('#idusuario').val(getQueryVariable('id'));
    }
}


let idusuariourl = getQueryVariable('id');
if(idusuariourl != ""){
	$('.cls_clave').css('display','block');
}

fillForm();
function fillForm() {
    if($('#idusuario').val()){
		var id = $('#idusuario').val();
		$.post("controller/usuariosback.php?oper=getusuario", {id}, function(response){
			const datos = JSON.parse(response);
			$('#usuario').val(datos.usuario);
			$('#nombre').val(datos.nombre);
			//$('#clave').val(datos.clave);
			$('#correo').val(datos.correo);
			$('#telefono').val(datos.telefono);
			$('#cargo').val(datos.cargo);
			regionales(datos.regional);
			$('#estados').val(datos.estado).trigger('change');
			niveles(datos.nivel);
		});
    }
}

function guardar(){
    $('#preloader').css('display', 'block');
	var idusuario = getQueryVariable('id');
    var dataserialize = $('#form_usuario').serializeArray();
	var datos = {};
	for (var i in dataserialize){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'estados' || dataserialize[i].name == 'niveles'){
			datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			datos[dataserialize[i].name] = dataserialize[i].value;
		}
	}
	let msj = '';
    if (idusuario == ''){
        var oper = "guardarusuario";
		msj = 'creado';
    }else{
        var oper = "editarusuario";
		msj = 'modificado';
    }
    if (validarform()){
        $.ajax({
	        url: "controller/usuariosback.php",
	        type: "POST",
	        data: {
	            oper: oper,
	            datos: datos,
                id: idusuario
	        },
	        dataType: "json",
	        success: function(response){
	            //console.log(response);
	            $('#preloader').css('display', 'none');
	            if (response == 1){ 
					swal('Buen trabajo', `Usuario ${msj} satisfactoriamente`, 'success');
					location.href = "usuarios.php"; 
	            }else if(response == 2){
	                swal('Advertencia', 'El usuario ya existe', 'error');
	            }else if(response == 3){
	                swal('Advertencia', 'El correo ya existe', 'error');
	            }else if(response == 4){
	                swal('Advertencia', 'El usuario y el correo ya existe', 'error');
	            }else{
	                swal('Error', 'Ocurrió un error al guardar el usuario', 'error');
	            }
	        }
    	});
    }else{
    	$('#preloader').css('display', 'none');
    }
}

function limpiarForm(){
	$("#form_usuario")[0].reset();
	$("#form_usuario").find('select').each(function() {
       $(this).val(0).trigger('change');
   });
}

function validarform(){
	var verdad 	= true;
    var usuario	= $('#usuario').val();
	var nombre	= $('#nombre').val();
	var clave	= $('#clave').val();
    var correo	= $('#correo').val();
    var cargo	= $('#cargo').val();
    var estado	= $('#estado').val();
    var nivel	= $('#niveles').val();
	let idusuario_url = getQueryVariable('id');
    expresion	=/\w+@\w+\.+[a-z]/;

    if (usuario==""){
		swal('Error', 'El campo Usuario está vacío', 'error');
		return false;
	}else if (nombre==""){
		swal('Error', 'El campo Nombre está vacío', 'error');
		return false;
	}/* else if (clave=="" && idusuario_url != ""){
		swal('Error', 'El campo Clave está vacío', 'error');
		return false;
	} */else if (correo==""){
		swal('Error', 'El campo Correo está vacío', 'error');
		return false;
	}else if (telefono==""){
		swal('Error', 'El campo Teléfono está vacío', 'error');
		return false;
	}else if (cargo==""){
		swal('Error', 'El campo Cargo está vacío', 'error');
		return false;
	}else if (estado==""){
		swal('Error', 'El campo Estado está vacío', 'error');
		return false;
	}else if (nivel=="0" || nivel == undefined || nivel == ''){
		console.log(`nivel es ${nivel}`)
		swal('Error', 'El campo Nivel está vacío', 'error');
		return false;
	}
	return verdad;
}

// PARA EDITAR USUARIOS
$("#tablausuarios tbody").on('dblclick', 'tr', function(){
    $("#title_m").text("Editar usuario");
    var idusuario = $(this).attr("id");
    cargarUsuario(idusuario);
});

function cargarUsuario(idusuario){
    $.get("controller/usuariosback.php?oper=getusuario", { idusuario: idusuario }, function(result){
        result = JSON.parse(result);
        result = result[0];
    });
}

function eliminarUsuario(id, nombre){
	var id = id;
    swal({
        title: "Confirmar",
        html: "¿Está seguro de eliminar el usuario: <b>"+nombre+"</b>?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: 'red',
        confirmButtonColor: '#09b354',
        confirmButtonText: 'Sí',
        cancelButtonText: "No"
    }).then(
        function(isConfirm){
            if (isConfirm.value == true){
                $('#preloader').css('display', 'block');
                $.get("controller/usuariosback.php",
                {
                    'oper'	: 'eliminar',
                    'id' 	: id,
					'nombre': nombre
                }, function(result)
                {
                    if (result == 1){
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Usuario eliminado satisfactoriamente', 'success');
                        // RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
                        tablausuarios.ajax.reload(null, false);
                        tablausuarios.columns.adjust();
                    }else{
                        $('#preloader').css('display', 'none');
                        swal('ERROR', 'Ha ocurrido un error al eliminar el usuario, intente más tarde', 'error');
                    }
                });
            }
        },
        function(isRechazo){
            console.log(isRechazo);
        }
    );
}