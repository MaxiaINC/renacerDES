
//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){ 
	tablasolicitudes.state.clear();
	window.location.reload();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablasolicitudes.ajax.reload();
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

//HEADER
$('#tablasolicitudes thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'ID'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 60px" autocomplete="nope" /> ' );
			}else if(title == 'Expediente'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 90px" autocomplete="nope" /> ' );
			}else if(title == 'Cédula'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 115px" autocomplete="nope" /> ' );
			}else if(title == 'Nombre'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" autocomplete="nope" /> ' );
			}else if(title == 'Fecha de la solicitud'  || title == 'Fecha de cita'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" autocomplete="nope" /> ' );
			}else if(title == 'Regional'  || title == 'Estado'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" autocomplete="nope" /> ' );
			}else if(title == 'Observaciones de estados'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 190px" autocomplete="nope" /> ' );
			}else if(title == 'Condición de salud'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" autocomplete="nope" /> ' );
			}else if(title == 'Discapacidad'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 110px" autocomplete="nope" /> ' );
			}else if(title == 'Modalidad'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 300px" autocomplete="nope" /> ' );
			}			
		}else{
			$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
		}
	}else if(title == 'Acciones'){
		var ancho = '50px';
	}
	$(this).width(ancho);
});

//TABLA
var tablasolicitudes = $("#tablasolicitudes").DataTable( {
    scrollY: '100%',
	scrollX: true,
	scrollCollapse: true,
	destroy: true,
	ordering: false,
	processing: true,
	autoWidth : false,
	stateSave: true,
	searching: true,
	//pageLength: 50,
	//lengthChange: false,
	serverSide: true,
	serverMethod: 'post',
	/*-ACCEDIENDO-AL_LOCALSTORE_PARA_RECUPERAR_VALORES-------------------*/
	stateLoadParams: function (settings, data) {			
		const{columns}=data
		console.log(columns)
		$('th#cnrosolicitud input').val(columns[1].search.search);
		$('th#cexpediente input').val(columns[2].search.search);
		$('th#ccedula input').val(columns[3].search.search);
		$('th#cpaciente input').val(columns[4].search.search);
		$('th#cfecha_solicitud input').val(columns[5].search.search);  	
	},
    ajax: {
        url: "controller/estacionamientosback.php?oper=cargar"
    },
    columns: [
        { 	"data": "acciones" },				//0
		{ 	"data": "id" },						//1 
		{ 	"data": "cedula" },					//3	
		{ 	"data": "nombre" }, 				//4		
		{ 	"data": "fecha_solicitud" },		//5 
		{ 	"data": "regional" },				//7
		{ 	"data": "estado" },				//8 
    ],
    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
    columnDefs: [//OCULTAR LA COLUMNA id, Observaciones 
        {
			"targets"	: [ 1 ],
			"visible"	:  false,
			"searchable": false
		},{
			"targets"	: [ 2, 3, 5, 6 ],
			"className"	:  'text-center'
		}
    ],
	language:
    {
        url: "js/Spanish.json",
    },
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    drawCallback: function( settings ) 
    {
		ajustarTablas();
    },
    initComplete: function(){
		$('#preloader').css('display','none');
		var t = 0;
		this.api().columns().every( function () {
			var column = this;
		});
		/*
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
		*/
		//OCULTAR LOADER
		param++;
		loader(param, '1');
		cargarDropdownMenu();
    },
	rowCallback: function( row, data) {
		if(data['auditoria'] == 1){
			$('td', row).css('background-color', '#e0fbed');
			$('td', row).css('color', '#5b636e');
		}
	},
	dom: '<"toolbarU toolbarDT">Blfrtip'
});/*fin tabla*/

$('#tablasolicitudes').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})
tablasolicitudes.columns().every( function () {
	var that = this;
	$( 'input', this.header() ).keypress(function (event) {
		if (this.value!='A11|') {
			if ( event.which == 13 ) {
				if ( that.search() !== this.value ) {
					that.search( this.value ).draw();
				}
			}
		}
	});	
});

//EDITAR
$("#tablasolicitudes tbody").on('dblclick','tr',function(){
	var idsolicitud = $(this).attr('id');
	console.log('idsolicitud: '+idsolicitud);
	if(idsolicitud != undefined ){
		var coo = '';
		var name = "nivel=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				coo = c.substring(name.length, c.length);
			}
		}
		console.log('coo: '+coo);
		//if(coo == 1 || coo == 6 ||coo == 9 || coo == 11 ){
			location.href='estacionamiento.php?id='+idsolicitud;	
		//}
	}
});

const peticionExcel = (archivo) =>{
	$('.chatbox').removeClass('active');
	$.ajax({
		type:'POST',
		url:`reporte/${archivo}`,
		data: {},
		dataType:'json',
		beforeSend: function() {
			$('#preloader').css('display', 'block');
		},
	}).done(function(data){
		
		var $a = $("<a>");
		$a.attr("href",data.file);
		$("body").append($a);
		$a.attr("download",data.name);
		$a[0].click();
		$a.remove(); 
		$('#preloader').css('display', 'none');
	});
}

// AL CARGARSE LA TABLA
tablasolicitudes.on('draw.dt', function(e){ 
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $(".boton-eliminar").each(function(){
		$(this).on('click',function(){
			var id = $(this).attr('data-id');
			var nombre = $(this).parent().parent().parent().next().next().next().html();
			eliminarSolicitud(id,nombre);
		});
	}); 
}); 

//***** ***** ***** ***** ***** SOLICITUD ***** ***** ***** ***** *****//
$("#nuevaSolicitud").click(function(){
	location.href = 'estacionamiento.php';
});  

function eliminarSolicitud(id,nombre){
    var id = id;
    swal({
        title: "Confirmar",
        html: "¿Esta seguro de eliminar la solicitud del usuario <strong>"+nombre+"</strong>?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: 'red',
        confirmButtonColor: '#09b354',
        confirmButtonText: 'Sí',
        cancelButtonText: "No"
    }).then(
        function(isConfirm)
        {
            //console.log(isConfirm);
            if (isConfirm.value==true)
            {
                $('#preloader').css('display', 'block');
                $.get("controller/estacionamientosback.php",
                {
                    'oper'	: 'eliminar',
                    'id' 	: id,
					'nombre': nombre
                }, function(result) 
                {
                    if (result == 1)
                    {
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Solicitud eliminada satisfactoriamente', 'success');
                        // RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
                        tablasolicitudes.ajax.reload(null, false);
                        tablasolicitudes.columns.adjust();
                    } 
                    else 
                    {
                        $('#preloader').css('display', 'none');
                        swal('ERROR', 'Ha ocurrido un error al eliminar la solicitud, intente más tarde', 'error');
                    }
                });
            }
        },
        function(isRechazo){
            console.log(isRechazo);
        }
    );
}   