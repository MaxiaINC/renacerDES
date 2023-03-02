//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	tablahabilitacionjuntas.state.clear();
	window.location.reload();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablahabilitacionjuntas.ajax.reload();
    ajustarTablas();
});
$("#nuevo").on('click', function(){
	location.href="habilitacionjunta.php";
});

//HEADER
$('#tablahabilitacionjuntas thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'N° de resolución'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" autocomplete="nope" /> ' );
			}else if(title == 'N° de junta'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 150px" autocomplete="nope" /> ' );
			}else if(title == 'Fecha para la evaluación' || title == 'Fecha para la resolución'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" autocomplete="nope" /> ' );
			}else if(title == 'Médicos'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 300px" autocomplete="nope" /> ' );
			}else if(title == 'Beneficiarios'){
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
var tablahabilitacionjuntas = $("#tablahabilitacionjuntas").DataTable( {
	columnDefs: [
		{ orderable: false, targets: 0 }
	  ],
	  order: [[3, 'asc']],
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
	//serverSide: true,
	serverMethod: 'get',
	/*-ACCEDIENDO-AL_LOCALSTORE_PARA_RECUPERAR_VALORES-------------------*/
	stateLoadParams: function (settings, data) {			
		const{columns}=data
		$('th#cnroresolucion input').val(columns[2].search.search);
		$('th#cnrojunta input').val(columns[3].search.search);
		$('th#cfechaevaluacion input').val(columns[4].search.search);
		$('th#cfecharesolucion input').val(columns[4].search.search);
		$('th#cmedicos input').val(columns[5].search.search);
		$('th#cbeneficiarios input').val(columns[6].search.search); 
		//$('th#cestado input').val(columns[7].search.search); 
	},
    ajax: {
        url: "controller/habilitacionjuntasback.php?oper=cargar"
    },
    columns: [
        { 	"data": "acciones" },			//0
		{ 	"data": "id" },					//1
		{ 	"data": "nroresolucion" },		//2
		{ 	"data": "nrojunta" },			//3	
		{ 	"data": "fechaevaluacion" },	//4	
		{ 	"data": "fecharesolucion" },	//4	
		{ 	"data": "medicos" },			//5
		{ 	"data": "solicitantes" },		//6
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
		//param++;
		//loader(param, '1');
		$('#overlay').css('display','none');
		cargarDropdownMenu();
    },
	dom: '<"toolbarU toolbarDT">Blfrtip'
});/*fin tabla*/

$('#tablahabilitacionjuntas').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})
/* tablahabilitacionjuntas.columns().every( function () {
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
});  */

// AL CARGARSE LA TABLA
tablahabilitacionjuntas.on('draw.dt', function(e){
	$(".boton-resolucion").each(function(){
		$(this).on('click',function(){
			let id = $(this).attr('data-id');
			window.open('reporte/imprimirResolucionJunta.php?id='+id, '_blank');
		});
	}); 
	$(".boton-eliminar").each(function(){
		$(this).on('click',function(){
			let id = $(this).attr('data-id');
			eliminar(id);
		});
	});
});

$('#nuevaSolicitud').on('click',function(){
	$('#modal-auditorias-solicitud').modal('show');
});

//Adjuntos
/* var dirxdefecto = 'incidente';
$('#fevidencias').attr('src','filegator/auditorias.php#/?cd=%2F'+dirxdefecto);
function abrirsolicitudes(id) {
	  var valid = true;
	  if ( valid ) {
		$.ajax({
			  type: 'post',
			  url: 'controller/auditoriasback.php',
			  data: { 
				'oper': 'abrirSolicitudes',
				'id': id		  
			  },
			  success: function (response) {
				$('#fevidencias').attr('src','filegator/auditorias.php#/?cd=auditorias/'+id);
				$('#modalAdjuntos').modal('show');
				$('#modalAdjuntos .modal-lg').css('width','1000px');
				$('#idauditoriasadjuntos').val(id);
				$('.titulo-evidencia').html('Auditoría: '+id+' - Adjuntos');
				console.log('id'+id);
			  },
			  error: function () { 
				swal('Error', 'Ocurrió un error al agregar el adjunto', 'error');
			  }
		   }); 
	  }
	  return valid;
	} */
		
//Buscar datos paciente
/* $("#expediente").on('blur', function(){
	let expediente = $('#expediente').val();
	
	$.post("controller/auditoriasback.php?oper=getDatosPaciente", {expediente: expediente}, function(response){
		const datos = JSON.parse(response); 
		$('#cedula').val(datos.data.cedula);
		$('#nombre').val(datos.data.nombre);
	}); 
}); */


//EDITAR
$("#tablahabilitacionjuntas tbody").on('dblclick','tr',function(){
	var id = $(this).attr('id');
	console.log('id: '+id);
	
	location.href='habilitacionjunta.php?id='+id;
});

function eliminar(id){
    swal({
        title: "Confirmar",
        html: "¿Esta seguro de eliminar el registro?",
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
                $.get("controller/habilitacionjuntasback.php",
                {
                    'oper'	: 'eliminar',
                    'id' 	: id
                }, function(result) 
                {
                    if (result == 1)
                    {
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Registro eliminado satisfactoriamente', 'success');
                        tablahabilitacionjuntas.ajax.reload(null, false);
                        tablahabilitacionjuntas.columns.adjust();
                    } 
                    else 
                    {
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
