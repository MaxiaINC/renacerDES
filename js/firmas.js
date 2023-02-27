//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	$("#tabla").DataTable().search("").draw();
	$('#tabla_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tabla.ajax.reload();
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

if ( $("#tabla").length ) {
//HEADER
$('#tabla thead th').each( function (){
	let title = $(this).text();
	let id = $(this).attr('id');
	let ancho = $(this).width();
	if (title !== '' && title !== '-' && title !== 'Acciones') {
		if (screen.width > 1024) {
			$(this).html(`<form autocomplete="off" role="presentation"><input type="text" readonly onfocus="this.removeAttribute('readonly');" placeholder="${title}" id="f${id}" style="width: 100%" /></form>`);
		} else {
			$(this).html(`<form autocomplete="off" role="presentation"><input type="text" readonly onfocus="this.removeAttribute('readonly');" placeholder="${title}" id="f${id}" style="width: 100px" /></form>`);
		}
	} else if (title == 'Acción') {
		let ancho = '50px';
	}
	$(this).width(ancho);
	$('form').submit( function(e){ 
		e.preventDefault();
	});
});

/* TABLA */
var tabla = $("#tabla").DataTable({
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
		$('th#theadid input').val(columns[1].search.search);
		$('th#cregional input').val(columns[2].search.search);
		$('th#ccargo input').val(columns[3].search.search);
	},
    ajax: {
        url: "controller/firmasback.php?oper=listado"
    },
    columns: [
        { "data": "acciones" },		//0
		{ "data": "id" }, 			//1
        { "data": "nombre" },		//2
		{ "data": "regional" },    	//3 
		{ "data": "cargo" },	   	//4 
		{ "data": "estado" },	   	//5
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
tabla.on('draw.dt', function(e){
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $('.boton-estado').each(function(){
        let id = $(this).attr("data-id");
        let estado = $(this).attr("data-estado");
		
		var nombre = $(this).parent().parent().parent().next().html();
		$(this).on('click', function(){
            cambiarEstado(id, estado, nombre);
        });
		
    });
});

}

$("#nuevo").click(function(){
	location.href = 'firma.php';
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

$("#listado").on("click",function(){
	location.href = 'firmas.php';
});

setId();
function setId() {
    if(getQueryVariable('id')){
        $('#idnivel').val(getQueryVariable('id'));
    }
}

function limpiarForm(){
	$("#form_nivel")[0].reset();
	$("#form_nivel").find('select').each(function() {
       $(this).val(0).trigger('change');
	});
}


// PARA EDITAR niveles
$("#tabla tbody").on('dblclick', 'tr', function(){
    var id = $(this).attr("id");
    cargar(id);
});

function cargar(id){
	location.href = 'firma.php?id='+id;
}

function cambiarEstado(id, estado, nombre){
	console.log('estado',estado);
	let txt_estado = estado == 'Activo' ? 'inactivar' : 'activar';
    swal({
        title: "Confirmar",
        html: `¿Está seguro de ${txt_estado} la firma de: <b>${nombre}</b>?`,
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
                $.get("controller/firmasback.php",
                {
                    'oper'	: 'cambiarEstado',
                    'id' 	: id,
                    'estado' : estado,
					'nombre': nombre
                }, function(result)
                {
                    if (result == 1){
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Registro actualizado satisfactoriamente', 'success');
                        // RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
                        tabla.ajax.reload(null, false);
                        tabla.columns.adjust();
                    }else{
                        $('#preloader').css('display', 'none');
                        swal('ERROR', 'Ha ocurrido un error al eliminar el registro, intente más tarde', 'error');
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