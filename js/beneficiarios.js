
//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	$("#tablabeneficiarios").DataTable().search("").draw();
	$('#tablabeneficiarios_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablabeneficiarios.ajax.reload();
    ajustarTablas();
});
//LOADER
param = 0;
function loader(param, fin){
	if(param == fin){
		$('#overlay').css('display','none');
		param = 0;
	}
}if ( $("#tablabeneficiarios").length ) {
//HEADER
$('#tablabeneficiarios thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'ID'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 60px" /> ' );
			}else if(title == 'Expediente'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 90px" /> ' );
			}else if(title == 'Nombre'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 220px" /> ' );
			}else if(title == 'Cédula'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 115px" /> ' );
			}else if(title == 'Teléfono'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 115px" /> ' );
			}else if(title == 'Sexo'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 80px" /> ' );
			}else if(title == 'Fecha de Nacimiento'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 155px" /> ' );
			}else if(title == 'Discapacidad'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 110px" /> ' );
			}			
		}else{
			$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
		}
	}else if(title == 'Acciones'){
		var ancho = '50px';
	}
	$(this).width(ancho);
});
$('#preloader').css('display','block');
/* TABLA */
var tablabeneficiarios = $("#tablabeneficiarios").DataTable({
    scrollY: '100%',
	scrollX: true,
	scrollCollapse: true,
	searching: true,
	destroy: true,
	ordering: false,
	processing: true,
	autoWidth : false,
	stateSave: true,
	serverSide: true,
	serverMethod: 'post',
	select: { style: 'multi' },
	/*-ACCEDIENDO-AL_LOCALSTORE_PARA_RECUPERAR_VALORES-------------------*/
	stateLoadParams: function (settings, data) {
		const{columns}=data
		$('th#cexpediente input').val(columns[2]['search']['search']);
		$('th#cnombre input').val(columns[3]['search']['search']);
		$('th#ccedula input').val(columns[4]['search']['search']);
		$('th#ctelefono input').val(columns[5]['search']['search']);
		$('th#csexo input').val(columns[6]['search']['search']); 
		$('th#cfecha_nac input').val(columns[7]['search']['search']);
		$('th#cdiscapacidades input').val(columns[8]['search']['search']);
	}, 
    ajax: {
        url: "controller/beneficiariosback.php?oper=listado"
    },
    columns: [
        { "data": "acciones" },			//0
		{ "data": "id" }, 				//1
        { "data": "expediente" },		//2
		{ "data": "nombre" },			//3
		{ "data": "cedula" },			//4
		{ "data": "telefono" },			//5
		{ "data": "sexo" },				//6
		{ "data": "fecha_nac" },		//7
		{ "data": "discapacidades" },	//8
    ],
    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
    columnDefs: [//OCULTAR LA COLUMNA id, Observaciones
        {
            targets: [1],
            visible: false
        },{
			"targets"	: [ 0,2 ],
			"className"	:  'text-center'
		}
    ],
    language:
    {
        url: "js/Spanish.json",
    },
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    /* drawCallback: function( settings )
    {
		ajustarTablas();
    }, */
    initComplete: function()
    {
		//cargarDropdownMenu();
		//APLICAR BUSQUEDA POR COLUMNAS
       /*  this.api().columns().every( function (){
            var that = this; 
            $( 'input', this.header() ).on( 'keyup change clear', function (valor)
            {
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
            } );
        }); */
		//OCULTAR LOADER
		$('#preloader').css('display','none');
		param++;
		loader(param, '1');
    },
	dom: '<"toolbarU toolbarDT">Blfrtip'
});/*fin tabla*/

tablabeneficiarios.columns().every( function () {
	var that = this;
	$( 'input', this.header() ).keypress(function (event) {
		if (this.value!='A11|') {
			if ( event.which == 13 ) {
				if ( that.search() !== this.value ) {
					that
						.search( this.value )
						.draw();
				}
			}
		}
	});	
});
	
// AL CARGARSE LA TABLA
tablabeneficiarios.on('draw.dt', function(e){
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $('.boton-eliminar').each(function(){
        var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().parent().next().next().html();
		$(this).on('click', function(){
            eliminarBeneficiario(id, nombre);
        });
    });
});

}

$("#nuevoBeneficiario").click(function(){
	location.href = 'beneficiario.php';
});

// PARA EDITAR beneficiarios
$("#tablabeneficiarios tbody").on('dblclick', 'tr', function(){
	$("#title_m").text("Editar usuario");
	var idbeneficiario = $(this).attr("id");
	cargarUsuario(idbeneficiario);
});

function cargarUsuario(idbeneficiario){
	location.href = 'beneficiario.php?id='+idbeneficiario;
}

function eliminarBeneficiario(id, nombre){
	//console.log('eliminarBeneficiario: '+id);
	var id = id;
	swal({
		title: "Confirmar",
		html: "¿Está seguro de eliminar el beneficiario: <b>"+nombre+"</b>?",
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
				$.get("controller/beneficiariosback.php",
				{
					'oper'	: 'eliminar',
					'id' 	: id,
					'nombre': nombre
				}, function(result)
				{
					if (result == 1){
						$('#preloader').css('display', 'none');
						swal('Buen trabajo', 'Beneficiario eliminado satisfactoriamente', 'success');
						// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
						tablabeneficiarios.ajax.reload(null, false);
						tablabeneficiarios.columns.adjust();
					}else{
						$('#preloader').css('display', 'none');
						swal('ERROR', 'Ha ocurrido un error al eliminar el beneficiario, intente más tarde', 'error');
					}
				});
			}
		},
		function(isRechazo){
			console.log(isRechazo);
		}
	);
}