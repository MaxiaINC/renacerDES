//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	$("#tablacorregimientos").DataTable().search("").draw();
	$('#tablacorregimientos_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablacorregimientos.ajax.reload();
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
//COMBOS
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
	$('.distritoselect').css('display','block');
	$('.distritoinput').css('display','none');
	$('#valordistrito').val('');
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

if(!getQueryVariable('id')){
	provincias(0);
	distritos(0);
}

if ( $("#tablacorregimientos").length ) {
//HEADER
$('#tablacorregimientos thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'ID'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 60px" /> ' );
			}else if(title == 'Código'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 90px" /> ' );
			}else if(title == 'Provincia'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 250px" /> ' );
			}else if(title == 'Distrito'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 250px" /> ' );
			}else if(title == 'Corregimiento'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 250px" /> ' );
			}else if(title == 'Área'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 190px" /> ' );
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
var tablacorregimientos = $("#tablacorregimientos").DataTable({
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
		$('th#cprovincia input').val(columns[3].search.search);
		$('th#cdistrito input').val(columns[4].search.search);
		$('th#ccorregimiento input').val(columns[5].search.search);
		$('th#carea input').val(columns[6].search.search);
	},
    ajax: {
        url: "controller/corregimientosback.php?oper=listado"
    },
    columns: [
        { "data": "acciones" },			//0
		{ "data": "id" }, 				//1
        { "data": "codigo" },			//2
		{ "data": "provincia" },		//3
		{ "data": "distrito" },			//4
		{ "data": "corregimiento" },	//5
		{ "data": "area" }				//6
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
tablacorregimientos.on('draw.dt', function(e){
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR
    $('.boton-eliminar').each(function(){
        var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().parent().next().next().next().next().html();
		$(this).on('click', function(){
            eliminarcorregimiento(id, nombre);
        });
    });
});

}

$("#nuevoCorregimiento").click(function(){
	location.href = 'corregimiento.php';
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
                location.href = 'corregimientos.php';
            }
        },
        function(isRechazo) {
            
        }
    );
});

$("#listadoCorregimientos").on("click",function(){
	location.href = 'corregimientos.php';
});

setId();
function setId() {
    if(getQueryVariable('id')){
        $('#iddireccion').val(getQueryVariable('id'));
    }
}

fillForm();
function fillForm() {
    if($('#iddireccion').val()){
		var id = $('#iddireccion').val();
		$.post("controller/corregimientosback.php?oper=getcorregimiento", {id: id}, function(response){
			const datos = JSON.parse(response);
			//DIRECCIÓN
			$("#codigo").val(datos.direccion.codigo);
			$("#area").val(datos.direccion.area);
			provincias(datos.direccion.provincia);
			distritos(datos.direccion.distrito, datos.direccion.provincia);
			$("#idcorregimientos").val(datos.direccion.corregimiento);			
		});
    }
}

$("#guardar").on("click",function(){
	guardar();
});
function guardar(){
	 
	
	let codigo = $('#codigo').val();
	let idprovincias = $('#idprovincias').val();
	let iddistritos = '';
	let val_dist = $('#valordistrito').val();
	val_dist == 'nuevodistrito' ? iddistritos = $('#distrito').val() : iddistritos = $('#iddistritos').val();
	let idcorregimientos = $('#idcorregimientos').val();
	let areacor = $('#area').val();
	var iddireccion = getQueryVariable('id');
	
	let msj = '';
    if (iddireccion == ''){
        var oper = "guardar_corregimiento";
		msj = 'creado';
    }else{
        var oper = "editar_corregimiento";
		msj = 'modificado';
    }
	
   if (validarform()){
        $.ajax({
	        url: "controller/corregimientosback.php",
	        type: "POST",
	        data: {
	            oper: oper,
                iddireccion: iddireccion,
				codigo: codigo,
				idprovincias: idprovincias,
				iddistritos: iddistritos,
				idcorregimientos: idcorregimientos,
				areacor: areacor
	        },
	        dataType: "json",
	        success: function(response){
	            $('#preloader').css('display', 'none');
				if (response.success == true){ 
					swal('Buen trabajo', `Corregimiento ${msj} satisfactoriamente`, 'success');
					location.href = "corregimientos.php"; 
	            }else{
	                swal('Error', 'Error al '+oper+' el corregimiento', 'error');
	            }
	        }
    	}); 
    }else{
    	$('#preloader').css('display', 'none');
    } 
}

function limpiarForm(){
	$("#form_corregimiento")[0].reset();
	$("#form_corregimiento").find('select').each(function() {
       $(this).val(0).trigger('change');
	});	
}

function validarform(){
	var verdad 	= true;
	var codigo			= $('#codigo').val();
    var idprovincias	= $('#idprovincias').val();
	let valdist 		= $('#valordistrito').val();
	if(valdist == 'nuevodistrito'){
		var iddistritos	= $('#distrito').val();	
	}else{
		var iddistritos	= $('#iddistritos').val();	
	}
	
	var idcorregimientos= $('#idcorregimientos').val();
	var area			= $('#area').val();	
    expresion	=/\w+@\w+\.+[a-z]/;

    if (codigo == ""){
		swal('Error', 'El código esta vacío', 'error');
		return false;
	}else if (idprovincias == "0"){
		swal('Error', 'Seleccione una provincia', 'error');
		return false;
	}else if (iddistritos == "0" || iddistritos == ''){
		swal('Error', 'Seleccione un distrito', 'error');
		return false;
	}else if (idcorregimientos == "0"){
		swal('Error', 'Seleccione un corregimiento', 'error');
		return false;
	}else if (area == ""){
		swal('Error', 'El área esta vacío', 'error');
		return false;
	}
	return verdad;
}

// PARA EDITAR corregimientos
$("#tablacorregimientos tbody").on('dblclick', 'tr', function(){
    $("#title_m").text("Editar usuario");
    var iddireccion = $(this).attr("id");
    cargarCorregimiento(iddireccion);
});

function cargarCorregimiento(iddireccion){
	location.href = 'corregimiento.php?id='+iddireccion;
}

function eliminarcorregimiento(id, nombre){
	
    var id = id;
    swal({
        title: "Confirmar",
        html: "¿Está seguro de eliminar el corregimiento: <b>"+nombre+"</b>?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: 'red',
        confirmButtonColor: '#09b354',
        confirmButtonText: 'Sí',
        cancelButtonText: "No"
    }).then(
        function(isConfirm){
            
            if (isConfirm.value == true)
            {
                $('#preloader').css('display', 'block');
                $.get("controller/corregimientosback.php",
                {
                    'oper'	: 'eliminar',
                    'id' 	: id,
					'nombre': nombre
                }, function(result)
                {
                    if (result == 1){
                        $('#preloader').css('display', 'none');
                        swal('Buen trabajo', 'Corregimiento eliminado satisfactoriamente', 'success');
                        // RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
                        tablacorregimientos.ajax.reload(null, false);
                        tablacorregimientos.columns.adjust();
                    }else{
                        $('#preloader').css('display', 'none');
                        swal('ERROR', 'Ha ocurrido un error al eliminar el corregimiento, intente más tarde', 'error');
                    }
                });
            }
        },
        function(isRechazo){
            
        }
    );
}
$('#nuevo_distrito').on('click', function(){
	$('.distritoselect').css('display','none');
	$('.distritoinput').css('display','block');
	$('#valordistrito').val('nuevodistrito');
});	
$('#mostrar_distritos').on('click', function(){
	$('.distritoselect').css('display','block');
	$('.distritoinput').css('display','none');
	$('#valordistrito').val('');
});	
$("select").select2({ language: "es" });