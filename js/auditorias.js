//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	//$("#tablaauditorias").DataTable().search('').draw();
	//$('#tablaauditorias_wrapper thead input').val('').change();
	tablaauditorias.state.clear();
	window.location.reload();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablaauditorias.ajax.reload();
    ajustarTablas();
});

const estados = () => {
    $.get("controller/combosback.php?oper=estadosauditoria", {}, function(result)
    {
        $("#idestados").empty();
        $("#idestados").append(result);
    });
}
const auditores = () => {
    $.get("controller/combosback.php?oper=auditores", {}, function(result)
    {
        $("#idauditores,#idauditores_editar").empty();
        $("#idauditores,#idauditores_editar").append(result);
    });
}
const regionales = () => {
    $.get("controller/combosback.php?oper=regionalesAuditoria", {}, function(result)
    {
        $("#idregionales,#idregionales_editar").empty();
        $("#idregionales,#idregionales_editar").append(result);
    });
}
estados();
auditores();
regionales();

//HEADER
$('#tablaauditorias thead th').each( function (){
    var title = $(this).text();
    var id = $(this).attr('id');
	var ancho = $(this).width();
	if ( title !== '' && title !== '-' && title !== 'Acciones'){
		if (screen.width > 1024){
			if(title == 'Expediente'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" autocomplete="nope" /> ' );
			}else if(title == 'Cédula'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" autocomplete="nope" /> ' );
			}else if(title == 'Nombre'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 210px" autocomplete="nope" /> ' );
			}else if(title == 'Fecha de la solicitud'  || title == 'Fecha de cita'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" autocomplete="nope" /> ' );
			}else if(title == 'Regional'  || title == 'Estado'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" autocomplete="nope" /> ' );
			}else if(title == 'Auditor'){
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" autocomplete="nope" /> ' );
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
var tablaauditorias = $("#tablaauditorias").DataTable( {
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
		$('th#cexpediente input').val(columns[2].search.search);
		$('th#ccedula input').val(columns[3].search.search);
		$('th#cpaciente input').val(columns[4].search.search);
		$('th#cregional input').val(columns[5].search.search);
		$('th#cestado input').val(columns[6].search.search);
		$('th#cauditor input').val(columns[7].search.search); 
	},
    ajax: {
        url: "controller/auditoriasback.php?oper=cargar"
    },
    columns: [
        { 	"data": "acciones" },				//0
		{ 	"data": "id" },						//1
		{ 	"data": "expediente" },				//2
		{ 	"data": "cedula" },					//3	
		{ 	"data": "paciente" },				//4	
		{ 	"data": "regional" },				//5
		{ 	"data": "estatus" },				//6
		{ 	"data": "auditor" },				//7
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
		//param++;
		//loader(param, '1');
		$('#overlay').css('display','none');
		cargarDropdownMenu();
    },
	dom: '<"toolbarU toolbarDT">Blfrtip'
});/*fin tabla*/

$('#tablaauditorias').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})
tablaauditorias.columns().every( function () {
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

// AL CARGARSE LA TABLA
tablaauditorias.on('draw.dt', function(e){
	 $(".boton-auditores").each(function(){
		$(this).on('click',function(){
			let id = $(this).attr('data-id');
			let idauditores = $(this).attr('data-idauditores'); 
			
			if(idauditores.includes(',')){
				$("#idauditores_editar").val(idauditores.split(',')).trigger("change");	
			}else{
				$("#idauditores_editar").val(idauditores).trigger("change");
			} 
			
			$('#idauditoriasauditores').val(id);
			$('#modal-auditorias-auditores').modal('show');
		});
	});
	$(".boton-estados").each(function(){
		$(this).on('click',function(){
			let id = $(this).attr('data-id');
			let idestados = $(this).attr('data-idestados');
			$('#idauditoriasestados').val(id);
			$('#idestados').val(idestados).trigger("change");
			$('#modal-auditorias-estados').modal('show');
		});
	});
	$(".boton-regional").each(function(){
		$(this).on('click',function(){
			let id = $(this).attr('data-id');
			let idregionales = $(this).attr('data-idregionales');
			$('#idauditoriasregional').val(id);
			$('#idregionales_editar').val(idregionales).trigger('change');
			$('#modal-auditorias-regional').modal('show');
		});
	});
	$(".boton-documentos").each(function(){
		$(this).on('click',function(){ 
			let idpacientes = $(this).attr('data-idpacientes'); 
			let expediente = $(this).attr('data-expediente'); 
			getDocumentos(idpacientes,expediente);
			$('#modal-auditorias-documentos').modal('show');
		});
	});
	$('.boton-adjuntos').each(function(){
		let id = $(this).attr("data-id");			
		$(this).on( 'click', function() {
			abrirsolicitudes(id);
		});
	});
	$(".boton-informes").each(function(){
		$(this).on('click',function(){
			let id = $(this).attr('data-id');
			$('#idauditoriasgenerarinforme').val(id);
			$('#modal-auditorias-generarinforme').modal('show');
			getInformes();
		});
	});
});

$('#nuevaSolicitud').on('click',function(){
	$('#modal-auditorias-solicitud').modal('show');
});

//Adjuntos
var dirxdefecto = 'incidente';
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
				
			  },
			  error: function () { 
				swal('Error', 'Ocurrió un error al agregar el adjunto', 'error');
			  }
		   }); 
	  }
	  return valid;
	}
		
//Buscar datos paciente
$("#expediente").on('blur', function(){
	let expediente = $('#expediente').val();
	
	if(expediente != ''){
		$.ajax({
			url: "controller/auditoriasback.php",
			type: "GET",
			data: {
				oper: 'getDatosPaciente',
				expediente: expediente
			},
			dataType: "json",
			beforeSend: function() {
				$('#preloader').css('display', 'block');
			},
			success: function(response){
				$('#preloader').css('display', 'none');
				
				if (response == null){ 
					swal('Error', 'El expediente no existe', 'error');
				}else{ 
					if(response.data.asociado_a_auditoria == 0){
						$('#cedula').val(response.data.cedula);
						$('#nombre').val(response.data.nombre); 
					}else{
						swal('Error', 'Este expediente ya está siendo auditado', 'error');
					} 
				}
			}
		});
	}

});

//Agregar expediente
const agregarExpediente = () =>{
	
	let expediente = $('#expediente').val();
	let idregionales = $('#idregionales').val();
	let idauditores = $('#idauditores').val().join();
	
	if (validarform()){
		$.ajax({
			url: "controller/auditoriasback.php",
			type: "POST",
			data: {
				oper: 'agregarExpediente',
				expediente: expediente,
				idregionales: idregionales,
				idauditores: idauditores
			},
			dataType: "json",
			success: function(response){
				$('#preloader').css('display', 'none');
				
				if (response == 0){ 
					swal('Error', 'Ocurrió un error al agregar el expediente', 'error');
				}else if(response == 2){
					swal('Advertencia', 'El expediente no existe', 'warning');
				}else if(response == 3){
					swal('Advertencia', 'El expediente ya ha sido seleccionado', 'warning');
				}else{
					swal('Buen trabajo!', 'El expediente fue agregado satisfactoriamente', 'success');
					cerrarModalExpediente();
					tablaauditorias.ajax.reload();
				}
			}
		});
	}
}

//Cambiar estado
const actualizarEstado = () =>{
	
	let idauditorias = $('#idauditoriasestados').val();
	let idestados = $('#idestados').val();
	
	if(idestados == '' || idestados == 0 || idestados == undefined){
		swal('Advertencia', 'Debe seleccionar el estado', 'warning');
	}else{
		$.ajax({
			url: "controller/auditoriasback.php",
			type: "POST",
			data: {
				oper: 'actualizarEstado',
				idauditorias: idauditorias,
				idestados: idestados
			},
			dataType: "json",
			success: function(response){
				$('#preloader').css('display', 'none');
				
				if (response == 0){ 
					swal('Error', 'Ocurrió un error al cambiar el estado', 'error');
				}else{
					swal('Buen trabajo!', 'El estado fue modificado satisfactoriamente', 'success');
					cerrarModalEstado();
					tablaauditorias.ajax.reload();
				}
			}
		});
	} 
}

//Get Hallazgos
let getInformes = () =>{
	let idauditorias = $('#idauditoriasgenerarinforme').val();
	
	$.post("controller/auditoriasback.php?oper=getInformes", {idauditorias: idauditorias}, function(response){
		const datos = JSON.parse(response); 
		$('#hallazgos').val(datos.data.hallazgos);
	}); 
}

//Actualizar Informe (por ahora hallazgo)
const guardarInforme = () =>{
	
	let idauditorias = $('#idauditoriasgenerarinforme').val();
	let hallazgos = $('#hallazgos').val();
	
	if(hallazgos == ''){
		swal('Advertencia', 'Debe ingresar la información', 'warning');
	}else{
		$.ajax({
			url: "controller/auditoriasback.php",
			type: "POST",
			data: {
				oper: 'actualizarHallazgos',
				idauditorias: idauditorias,
				hallazgos: hallazgos
			},
			dataType: "json",
			success: function(response){
				$('#preloader').css('display', 'none');
				
				if (response == 0){ 
					swal('Error', 'Ocurrió un error al actualizar', 'error');
				}else{
					swal('Buen trabajo!', 'El registro fue modificado satisfactoriamente', 'success');
					$('#hallazgos').val('');
					cerrarModalInformes(); 
				}
			}
		});
	} 
}

//Agregar auditores
const agregarAuditores = () =>{
	
	let idauditorias = $('#idauditoriasauditores').val();
	let idauditores = $('#idauditores_editar').val().join();
	
	if(idauditores == ''){
		swal('Advertencia', 'Debe seleccionar el auditor', 'warning');
	}else{
		$.ajax({
			url: "controller/auditoriasback.php",
			type: "POST",
			data: {
				oper: 'agregarAuditores',
				idauditorias: idauditorias,
				idauditores: idauditores
			},
			dataType: "json",
			success: function(response){
				$('#preloader').css('display', 'none');
				
				if (response == 0){ 
					swal('Error', 'Ocurrió un error al agregar el auditor', 'error');
				}else{
					swal('Buen trabajo!', 'El auditor fue agregado satisfactoriamente', 'success');
					cerrarModalAuditores();
					tablaauditorias.ajax.reload();
				}
			}
		});
	} 
}

//Actualizar regional
const actualizarRegional = () =>{
	
	let idauditorias = $('#idauditoriasregional').val();
	let idregionales = $('#idregionales_editar').val();
	
	if(idregionales == '' || idregionales == undefined){
		swal('Advertencia', 'Debe seleccionar la regional', 'warning');
	}else{
		$.ajax({
			url: "controller/auditoriasback.php",
			type: "POST",
			data: {
				oper: 'actualizarRegional',
				idauditorias: idauditorias,
				idregionales: idregionales
			},
			dataType: "json",
			success: function(response){
				$('#preloader').css('display', 'none');
				
				if (response == 0){ 
					swal('Error', 'Ocurrió un error al actualizar la regional', 'error');
				}else{
					swal('Buen trabajo!', 'La regional fue actualizada satisfactoriamente', 'success');
					cerrarModalRegional();
					tablaauditorias.ajax.reload();
				}
			}
		});
	} 
}

let getDocumentos = (idpacientes,expediente) =>{
	

	$('#tabladocumentos thead th').each( function (){
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		if ( title !== '' && title !== '-' && title !== 'Acciones'){
			if (screen.width > 1024){
				if(title == 'ID'){
					$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" autocomplete="nope" /> ' );
				}else if(title == 'Tipo'){
					$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" autocomplete="nope" /> ' );
				}else if(title == 'Fecha'){
					$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 200px" autocomplete="nope" /> ' );
				}			
			}else{
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
			}
		}else if(title == 'Acciones'){
			var ancho = '50px';
		}
		$(this).width(ancho);
	});
	tabladocumentos = $("#tabladocumentos").DataTable({
		 scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : false,
		stateSave: true,
		searching: true, 
		stateLoadParams: function (settings, data) {			
			const{columns}=data
			$('th#ciddoc input').val(columns[0].search.search);
			$('th#ctipodoc input').val(columns[1].search.search);
			$('th#cfechadoc input').val(columns[2].search.search); 
		},
		"ajax"		: {
			"url"	: `controller/auditoriasback.php?oper=getDocumentos&idpacientes=${idpacientes}&expediente=${expediente}`,
		},
		"columns"	: [
			{ 	"data": "id" },
			{ 	"data": "tipo" },
			{ 	"data": "fecha" },
			{ 	"data": "ver" }
			],
		"rowId": 'id',  
		 columnDefs: [//OCULTAR LA COLUMNA id, Observaciones 
			{
				"targets"	: [ 0,2 ],
				"visible"	:  false,
				"searchable": false
			}
		],
		"language": {
			"url": "js/Spanish.json"
		}
	});
	
	tabladocumentos.columns().every( function () {
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
}

function validarform(){
	let verdad 	= true;
    let expediente	= $('#expediente').val();
	let idregionales = $('#idregionales').val();
	let idauditores	= $('#idauditores').val();

    if (expediente==""){
		swal('Advertencia', 'Debe ingresar el campo Expediente', 'warning');
		return false;
	}/* else if (idregionales=="" || idregionales=="0" || idregionales == undefined){
		swal('Advertencia', 'Debe seleccionar el campo Regional', 'warning');
		return false;
	} */else if (idauditores=="" || idauditores == undefined){
		swal('Advertencia', 'Debe seleccionar el campo Auditores', 'warning');
		return false;
	}
	return verdad;
}

$('#modal-auditorias-solicitud').on('hidden.bs.modal', function (e) {
	$('#expediente').val('');
	$('#cedula').val('');
	$('#nombre').val(''); 
	$('#idauditores').val('0').trigger('change');
});
const cerrarModalExpediente = () =>{
	$('#modal-auditorias-solicitud').modal('hide');
	$('#expediente').val('');
	$('#cedula').val('');
	$('#nombre').val(''); 
	$('#idauditores').val('0').trigger('change');
}
const cerrarModalEstado = () =>{
	$('#modal-auditorias-estados').modal('hide');
	$("#idauditoriasestados").val('');
	$("#idestados").val(0).trigger('change');
}
const cerrarModalDocumentos = () =>{
	$('#modal-auditorias-documentos').modal('hide');
}
const cerrarModalAuditores = () =>{
	$('#modal-auditorias-auditores').modal('hide');
	$("#idauditoriasauditores").val('');
	$("#idauditores").val(0).trigger('change');
}
const cerrarModalRegional = () =>{
	$('#modal-auditorias-regional').modal('hide');
	$("#idauditoriasregional").val('');
	$("#idregionales_editar").val(0).trigger('change');
}
const cerrarModalInformes = () =>{
	$('#modal-auditorias-generarinforme').modal('hide');
	$("#idauditoriasgenerarinforme").val('');
	$("#hallazgos").val('');
}

//Reporte excel
const exportar = () =>{
	peticionExcel('exportarAuditoria.php');	
} 
const peticionExcel = (archivo) =>{
	
	let localS = localStorage.getItem('DataTables_tablaauditorias_/senadisdes/auditorias.php');
	localS = jQuery.parseJSON(localS);
	
	//PARAMETROS
	param  = "bexpediente=" + localS['columns'][2]['search']['search'];
	param += "&bcedula=" + localS['columns'][3]['search']['search'];
	param += "&bpaciente=" + localS['columns'][4]['search']['search'];
	param += "&bregional=" + localS['columns'][5]['search']['search'];
	param += "&bestado=" + localS['columns'][6]['search']['search'];
	param += "&bauditor=" + localS['columns'][7]['search']['search'];
		
	$('.chatbox').removeClass('active');
	
	$.ajax({
		type:'POST',
		url:`reporte/${archivo}?${param}`,
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

$('select').select2();
