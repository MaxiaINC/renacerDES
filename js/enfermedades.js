//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	$("#tablaenfermedades").DataTable().search("").draw();
	$('#tablaenfermedades_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablaenfermedades.ajax.reload();
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

if ( $("#tablaenfermedades").length ) {
//HEADER
$('#tablaenfermedades thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'ID'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 60px" /> ' );
			}else if(title == 'Código'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 90px" /> ' );
			}else if(title == 'Nombre'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 220px" /> ' );
			}else if(title == 'Grupo'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 115px" /> ' );
			}else if(title == 'Estado'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 115px" /> ' );
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
var tablaenfermedades = $("#tablaenfermedades").DataTable({
    scrollY: '100%',
	scrollX: true,
	scrollCollapse: true,
	destroy: true,
	ordering: false,
	//processing: true,
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
		$('th#ccodigo input').val(columns[2].search.search);
		$('th#cnombre input').val(columns[3].search.search);
		$('th#cgrupo input').val(columns[4].search.search);
		$('th#cestado input').val(columns[5].search.search);
	},
    ajax: {
        url: "controller/enfermedadesback.php?oper=listado"
    },
    columns: [
        { "data": "acciones" },		//0
		{ "data": "id" }, 			//1
        { "data": "codigo" },		//2
		{ "data": "nombre" },		//3
		{ "data": "grupo" },		//4
		{ "data": "estado" }		//5
    ],
    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
    columnDefs: [//OCULTAR LA COLUMNA id, Observaciones
        {
            targets: [1],
            visible: false
        },{
			"targets"	: [ 0,2 ],
			"className"	:  'text-left'
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
tablaenfermedades.on('draw.dt', function(e){
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $('.boton-eliminar').each(function(){
        var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().parent().next().next().html();
		$(this).on('click', function(){
            eliminarEnfermedad(id, nombre);
        });
    });
});

}

$("#nuevaEnfermedad").click(function(){
	location.href = 'enfermedad.php';
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
                location.href = 'enfermedades.php';
            }
        },
        function(isRechazo) {
            console.log(isRechazo);
        }
    );
});

$("#listadoEnfermedades").on("click",function(){
	location.href = 'enfermedades.php';
});

setId();
function setId() {
    if(getQueryVariable('id')){
        $('#idenfermedad').val(getQueryVariable('id'));
    }
}

fillForm();
function fillForm() {
    if($('#idenfermedad').val()){
		var id = $('#idenfermedad').val();
		$.post("controller/enfermedadesback.php?oper=getenfermedad", { id: id }, function(response){
			const datos = JSON.parse(response);
			$('#codigo').val(datos.enfermedad.codigo);
			$('#nombre').val(datos.enfermedad.nombre);
			$('#grupo').val(datos.enfermedad.grupo);
			$('#estado').val(datos.enfermedad.estado).trigger('change');
		});
    }
}

$("#guardar").on("click",function(){
	guardar();
});
function guardar(){
    $('#preloader').css('display', 'block');
	var idenfermedad = getQueryVariable('id');
    var dataserialize = $('#form_enfermedad').serializeArray();
	var datos = {};
	for (var i in dataserialize){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'estado' ){
			datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			datos[dataserialize[i].name] = dataserialize[i].value;
		}
	}
	let msj = '';
    if (idenfermedad == ''){
        var oper = "guardar_enfermedad";
		msj = 'creada';
    }else{
        var oper = "editar_enfermedad";
		msj = 'modificada';
    }
    if (validarform()){
        $.ajax({
	        url: "controller/enfermedadesback.php",
	        type: "POST",
	        data: {
	            oper: oper,
	            datos: datos,
                id: idenfermedad
	        },
	        dataType: "json",
	        success: function(response){
	            $('#preloader').css('display', 'none');
	            if (response == 1){ 
					swal('Buen trabajo', `Enfermedad ${msj} satisfactoriamente`, 'success');
					location.href = "enfermedades.php"; 
	            }else{
	                swal('Error', 'Error al '+oper+' la enfermedad', 'error');
	            }
	        }
    	});
    }else{
    	$('#preloader').css('display', 'none');
    }
}

function limpiarForm(){
	$("#form_enfermedad")[0].reset();
	$("#form_enfermedad").find('select').each(function() {
       $(this).val(0).trigger('change');
   });
}

function validarform(){
	var verdad 	= true;
    var codigo	= $('#codigo').val();
	var nombre	= $('#nombre').val();
    var grupo	= $('#grupo').val();
    var estado	= $('#estado').val();
    expresion	=/\w+@\w+\.+[a-z]/;

    if (codigo == ""){
		swal('Error', 'El código esta vacío', 'error');
		return false;
	}else if (nombre == ""){
		swal('Error', 'El nombre esta vacío', 'error');
		return false;
	}else if (grupo == ""){
		swal('Error', 'El grupo esta vacío', 'error');
		return false;
	}else if (estado == "0"){
		swal('Error', 'Seleccione un estado', 'error');
		return false;
	}
	return verdad;
}

// PARA EDITAR enfermedades
$("#tablaenfermedades tbody").on('dblclick', 'tr', function(){
    $("#title_m").text("Editar enfermedad");
    var idenfermedad = $(this).attr("id");
    cargarEnfermedad(idenfermedad);
});

function cargarUsuario(idenfermedad){
	location.href = 'enfermedad.php?id='+idenfermedad;
}

function eliminarEnfermedad(id, nombre){
	var id = id;
    swal({
        title: "Confirmar",
        html: "¿Está seguro de eliminar la enfermedad: <b>"+nombre+"</b>?",
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
                $.get("controller/enfermedadesback.php",
                {
                    'oper'	: 'eliminar',
                    'id' 	: id,
					'nombre': nombre
                }, function(result)
                {
                    if (result == 1){
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Enfermedad eliminada satisfactoriamente', 'success');
                        // RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
                        tablaenfermedades.ajax.reload(null, false);
                        tablaenfermedades.columns.adjust();
                    }else{
                        $('#preloader').css('display', 'none');
                        swal('ERROR', 'Ha ocurrido un error al eliminar la enfermedad, intente más tarde', 'error');
                    }
                });
            }
        },
        function(isRechazo){
            console.log(isRechazo);
        }
    );
}

$("select").select2({ language: "es" });