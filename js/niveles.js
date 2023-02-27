//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	$("#tablaniveles").DataTable().search("").draw();
	$('#tablaniveles_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablaniveles.ajax.reload();
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

if ( $("#tablaniveles").length ) {
//HEADER
$('#tablaniveles thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'ID'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 60px" /> ' );
			}else if(title == 'Nombre'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 250px" /> ' );
			}else if(title == 'Descripción'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 350px" /> ' );
			}else if(title == 'Estado'){
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
var tablaniveles = $("#tablaniveles").DataTable({
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
		$('th#cnombre input').val(columns[2].search.search);
		$('th#cdescripcion input').val(columns[3].search.search);
		$('th#cestado input').val(columns[4].search.search);
	},
    ajax: {
        url: "controller/nivelesback.php?oper=listado"
    },
    columns: [
        { "data": "acciones" },		//0
		{ "data": "id" }, 			//1
        { "data": "nombre" },		//2
		{ "data": "descripcion" },	//3
		{ "data": "estado" }		//4
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
tablaniveles.on('draw.dt', function(e){
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $('.boton-eliminar').each(function(){
        var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().parent().next().next().html();
		$(this).on('click', function(){
            eliminarNivel(id, nombre);
        });
    });
});

}

$("#nuevoNivel").click(function(){
	location.href = 'nivel.php';
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
                location.href = 'niveles.php';
            }
        },
        function(isRechazo) {
            console.log(isRechazo);
        }
    );
});

$("#listadoNiveles").on("click",function(){
	location.href = 'niveles.php';
});

setId();
function setId() {
    if(getQueryVariable('id')){
        $('#idnivel').val(getQueryVariable('id'));
    }
}

fillForm();
function fillForm() {
    if($('#idnivel').val()){
		var id = $('#idnivel').val();
		$.post("controller/nivelesback.php?oper=getnivel", {id: id}, function(response){
			const datos = JSON.parse(response);
			//DATOS
			$('#nombre').val(datos.nombre);
			$('#descripcion').val(datos.descripcion);
			$('#estado').val(datos.estado).trigger('change');
		});
    }
}

$("#guardar").on("click",function(){
	guardar();
});
function guardar(){
    $('#preloader').css('display', 'block');
	var idnivel = getQueryVariable('id');
    var dataserialize = $('#form_nivel').serializeArray();
	var datos = {};
	for (var i in dataserialize){
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'estado'){
			datos[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			datos[dataserialize[i].name] = dataserialize[i].value;
		}
	}
	let msj = '';
    if (idnivel == ''){
        var oper = "guardar_nivel";
		msj = 'creado';
    }else{
        var oper = "editar_nivel";
		msj = 'modificado';
    }
    if (validarform()){
        $.ajax({
	        url: "controller/nivelesback.php",
	        type: "POST",
	        data: {
	            oper: oper,
	            datos: datos,
                id: idnivel
	        },
	        dataType: "json",
	        success: function(response){
	            $('#preloader').css('display', 'none');
				if (response.success == true){ 
					swal('Buen trabajo', `Nivel ${msj} satisfactoriamente`, 'success');
					location.href = "niveles.php"; 
	            }else{
	                swal('Error', 'Error al '+oper+' el nivel', 'error');
	            }
	        }
    	});
    }else{
    	$('#preloader').css('display', 'none');
    }
}

function limpiarForm(){
	$("#form_nivel")[0].reset();
	$("#form_nivel").find('select').each(function() {
       $(this).val(0).trigger('change');
	});
}

function validarform(){
	var verdad 	= true;
    var nombre			= $('#nombre').val();
    var descripcion		= $('#descripcion').val();
    var estado			= $('#estado').val();
    expresion	=/\w+@\w+\.+[a-z]/;

    if (nombre == ""){
		swal('Error', 'El nombre esta vacío', 'error');
		return false;
	}else if (descripcion == ""){
		swal('Error', 'La descripción esta vacía', 'error');
		return false;
	}else if (estado == "0"){
		swal('Error', 'Seleccione un estado', 'error');
		return false;
	}
	return verdad;
}

// PARA EDITAR niveles
$("#tablaniveles tbody").on('dblclick', 'tr', function(){
    $("#title_m").text("Editar nivel");
    var idnivel = $(this).attr("id");
    cargarNivel(idnivel);
});

function cargarNivel(idnivel){
	location.href = 'nivel.php?id='+idnivel;
}

function eliminarNivel(id, nombre){
    var id = id;
    swal({
        title: "Confirmar",
        html: "¿Está seguro de eliminar el nivel: <b>"+nombre+"</b>?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: 'red',
        confirmButtonColor: '#09b354',
        confirmButtonText: 'Sí',
        cancelButtonText: "No"
    }).then(
        function(isConfirm){
            //console.log(isConfirm);
            if (isConfirm.value == true)
            {
                $('#preloader').css('display', 'block');
                $.get("controller/nivelesback.php",
                {
                    'oper'	: 'eliminar',
                    'id' 	: id,
					'nombre': nombre
                }, function(result)
                {
                    if (result == 1){
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Nivel eliminado satisfactoriamente', 'success');
                        // RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
                        tablaniveles.ajax.reload(null, false);
                        tablaniveles.columns.adjust();
                    }else{
                        $('#preloader').css('display', 'none');
                        swal('ERROR', 'Ha ocurrido un error al eliminar el nivel, intente más tarde', 'error');
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