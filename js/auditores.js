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
		$('th#cnombre input').val(columns[2].search.search);
	},
    ajax: {
        url: "controller/auditoresback.php?oper=listado"
    },
    columns: [
        { "data": "acciones" },			//0
		{ "data": "id" }, 				//1
        { "data": "nombre" }			//2 
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
    $('.boton-eliminar-fisico').each(function(){
        var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().parent().next().html();
		$(this).on('click', function(){
            eliminar(id, nombre);
        });
    });
});

}

$("#nuevo").click(function(){
	location.href = 'auditor.php';
});


// PARA EDITAR 
$("#tabla tbody").on('dblclick', 'tr', function(){
    var id = $(this).attr("id");
location.href = `auditor.php?id=${id}`;
});

function eliminar(id, nombre){
	$.get("controller/auditoresback.php?oper=checkexpedienteauditor", { id: id },
		function(result){
			if (result > 0) { // SI EXISTE UN PACIENTE CON ESE medico, NO SE PUEDE ELIMINAR.
				swal('ERROR','Existen expedientes asociados a este auditor, no se puede eliminar','error');
			} else {
				swal({
					title: "Confirmar",
					text: "¿Está seguro de eliminar el auditor "+nombre+"?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Sí',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value == true){
							$.get( "controller/auditoresback.php", 
							{ 
								'oper'	: 'eliminar',
								'id' 	: id,
								'nombre': nombre
							}, function(result){
								if(result == 1){
									swal('Buen trabajo', 'Auditor eliminado satisfactoriamente', 'success');
									// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
									tabla.ajax.reload(null, false);
									tabla.columns.adjust();
								} else {
									swal('ERROR','Ha ocurrido un error al eliminar el auditor, intente más tarde','error');
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