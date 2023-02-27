//var sitio = Cookies.get('sitio');

$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
	// page is now ready, initialize the calendar...
    $('#calendar').fullCalendar({
    	weekends: false,
    	contentHeight: 700,
    	locale: 'es',		
	    header: {
	        //left: 'prev,next today myCustomButton',
	    	left: 'prev,today,next',
	        center: 'title',
	        right: 'month,agendaWeek,agendaDay'	        
	    },
	    buttonIcons: {
	    	prev: 'left-single-arrow',
	        next: 'right-single-arrow',
	        prevYear: 'left-double-arrow',
	        nextYear: 'right-double-arrow'
	    },
	    eventLimit: true, // allow "more" link when too many events
	    //events: 'calendarioeventos.php',
	    events: {
	        url: 'controller/solicitudesback.php',
	        method: 'POST',
	        data: {
	        	oper: 'get_calendario'
	        },
	        error: function() {
	            alert('Hubo un error al cargar los eventos');
	        },
	        //color: 'yellow',   // a non-ajax option
	        //textColor: 'black' // a non-ajax option
	    },
        eventClick:  function(event, jsEvent, view) {
			//abrirOrden();
			/*
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
			console.log('eventClick');
			console.log('coo: '+coo);
			console.log('event.id: '+event.id);
			if(coo == 1 || coo == 6 ||coo == 9 || coo == 10 || coo == 11 ){
				abrirEvento(event.id); 
			}
			*/
			if(nivel != 11){
				abrirEvento(event.id);
			}
        }
    });
	$('#overlay').css('display','none');
	$("#mnuDashboard").removeClass("active");
	$("#mnuCalendario").addClass("active");
	
	//PROVINCIAS
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
	});
	//DISTRITOS
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
	$("#iddistritos").on('select2:select', function(e) {
		var id = 0;
		var provincia = $("#idprovincias").val();
		var distrito = $(this).val();
		corregimientos(id, provincia, distrito);
	});
	//CORREGIMIENTOS
	function corregimientos(id, provincia, distrito){
		$.get("controller/combosback.php?oper=corregimiento", { provincia: provincia, distrito: distrito }, function(result)
		{
			$("#idcorregimientos").empty();
			$("#idcorregimientos").append(result);
			if (id != 0){
				$("#idcorregimientos").val(id).trigger('change');
			}
		});
	}
	$("#idcorregimientos").on('select2:select', function(e) {
		var area = $(this).select2('data')[0].element.dataset.area;
		$("#area").val(area);
	});
	//CONDICIÓN DE SALUD
	function condicionsalud(id){
		$.get("controller/combosback.php?oper=condicionsalud", {}, function(result)
		{
			$("#idcondicionsalud").empty();
			$("#idcondicionsalud").append(result);
			if (id != 0){
				$("#idcondicionsalud").val(id).trigger('change');
			}
		});
	}
	//TIPO DE DISCAPACIDAD
	function discapacidades(id){
		$.get("controller/combosback.php?oper=discapacidades", {}, function(result)
		{
			$("#iddiscapacidades").empty();
			$("#iddiscapacidades").append(result);
			if (id != 0){
				$("#iddiscapacidades").val(id).trigger('change');
			}
		});
	}
	//ESTADOS
	function estados(id){
		$.get("controller/combosback.php?oper=estadossolicitudes", {}, function(result)
		{
			$("#idestados").empty();
			$("#idestados").append(result);
			if (id != 0){
				$("#idestados").val(id).trigger('change');
			}
		});
	}
	//MÉDICOS
	function medicos(id){
		$.get("controller/combosback.php?oper=medicos", {}, function(result)
		{
			$("#idmedicos").empty();
			$("#idmedicos").append(result);
			if (id != 0){
				$("#idmedicos").val(id).trigger('change');
			}
		});
	}	
	//FECHAS
	$('#desdef').bootstrapMaterialDatePicker({
		weekStart:0, switchOnClick:true, time:false,  format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
	});	
	$('#hastaf').bootstrapMaterialDatePicker({
		weekStart:0, switchOnClick:true, time:false, format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
	});
	provincias(0);
	distritos(0);
	corregimientos(0);
	condicionsalud(0);
	discapacidades(0);
	estados(0);
	medicos(0);
	
	function marcarFiltros(){
		var desde 		  = $('#desdef').val();
		var hasta	 	  = $('#hastaf').val();
		var provincia 	  = $('#idprovincias').val();
		var distrito 	  = $('#iddistritos').val();
		var corregimiento = $('#idcorregimientos').val();
		var edad 		  = $('#idedades option:selected').text();
		var condicion	  = $('#idcondicionsalud').val();
		var discapacidad  = $('#iddiscapacidades option:selected').text().charAt(0).toUpperCase() + $('#iddiscapacidades option:selected').text().slice(1).toLowerCase();
		var genero 		  = $('#idgeneros option:selected').text();
		var estado 		  = $('#idestados option:selected').text();
		let nameestados   = $('#idestados').next().attr('title');
		var filtro = 0;
		
		$('.bootstrap-badge').html('');
		if(desde != 0 && desde != null){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+desde+'</span>');
			filtro = 1;
		}
		if(hasta != 0 && hasta != null){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+hasta+'</span>');
			filtro = 1;
		}
		if(provincia != 0 && provincia != null && provincia != ' ... '){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+provincia+'</span>');
			filtro = 1;
		}	
		if(distrito != 0 && distrito != null && distrito != ' ... '){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+distrito+'</span>');
			filtro = 1;
		}
		if(corregimiento != 0 && corregimiento != null && corregimiento != ' ... '){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+corregimiento+'</span>');
			filtro = 1;
		}	
		if(edad != 0 && edad != null && edad != ' ... '){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+edad+'</span>');
			filtro = 1;
		}	
		if(condicion != 0 && condicion != null && condicion != ' ... '){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+condicion+'</span>');
			filtro = 1;
		}	
		if(discapacidad != 0 && discapacidad != null && discapacidad != ' ... '){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+discapacidad+'</span>');
			filtro = 1;
		}	
		if(genero != 0 && genero != null && genero != ' ... '){
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+genero+'</span>');
			filtro = 1;
		}
		if(estado != 0 && estado != null && estado != ' ... '){
			let lngEstado = nameestados.length;
			if(lngEstado>50){
				nameestados = nameestados.substring(0,50);	
				nameestados = nameestados+'...';
			} 
			
			$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500" data-toggle="tooltip"  data-original-title="PRUEBAS">'+nameestados+'</span>');
			filtro = 1;
		}
	}
	
	function filtrosMasivos() {
		var dataserialize 	= $("#form_filtrosmasivos").serializeArray();
		var data 			= {};
		
		for (var i in dataserialize) {
			//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
			if( dataserialize[i].name == 'idprovincias' || dataserialize[i].name == 'iddistritos' || 
				dataserialize[i].name == 'idcorregimientos' || dataserialize[i].name == 'idedades' || 
				dataserialize[i].name == 'iddiscapacidades' || dataserialize[i].name == 'idgeneros' || 
				dataserialize[i].name == 'idcondicionsalud'  || dataserialize[i].name == 'idestados'  ||
				dataserialize[i].name == 'idmedicos' ){
				data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
			}else{
				data[dataserialize[i].name] = dataserialize[i].value;	
			}
		} 
		data = JSON.stringify(data);	
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/calendarioback.php',
			data: { 
				'oper'	: 'guardarfiltros',
				'data'	: data
			},
			success: function (response) {			
				$('#calendar').fullCalendar( 'refetchEvents' );
				$('.config').removeClass('active');
				verificarfiltros();
				marcarFiltros();
			}
		});
	}
	
	function verificarfiltros(){
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/calendarioback.php',
			data: { 
				'oper'	: 'verificarfiltros'
			},
			success: function (response) {
				if (response == 1) {
					$('#filtromas').removeClass('bg-success').addClass('bg-warning');
				}else{
					$('#filtromas').removeClass('bg-warning').addClass('bg-success');
				}
			}
		});
	}
	verificarfiltros();
	 
	function abrirFiltrosMasivos(){
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/calendarioback.php',
			data: { 
				'oper'	: 'abrirfiltros'
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			success: function (response) {
				$('#overlay').css('display','none');
				if (response.data!="") {
					var datos = JSON.parse(response.data);			
					$("#desdef").val(datos.desdef);
					$("#hastaf").val(datos.hastaf);
					provincias(datos.idprovincias);
					distritos(datos.iddistritos, datos.idprovincias);
					corregimientos(datos.idcorregimientos, datos.idprovincias, datos.iddistritos);
					$('#idedades').val(datos.idedades).trigger('change');
					discapacidades(datos.iddiscapacidades);
					$('#idgeneros').val(datos.idgeneros).trigger('change');
					condicionsalud(datos.idcondicionsalud);
					estados(datos.idestados);
					medicos(datos.idmedicos);					
					//$('#idprovincias').val((datos.idprovincias).split(',')).trigger('change');			
				}
			},
			complete: function(response) {
				setTimeout(function () {
					marcarFiltros();
				},1000);
			}
		});
	}
	abrirFiltrosMasivos();

	function limpiarFiltrosMasivos(){
		$('#filtromas').removeClass('bg-warning').addClass('bg-success');
		$.get( "controller/calendarioback.php?oper=limpiarFiltrosMasivos");
		var dataserialize = $("#form_filtrosmasivos").serializeArray();
		for (var i in dataserialize) {
			$("#"+dataserialize[i].name).val(null).trigger("change");
			$('#calendar').fullCalendar( 'refetchEvents' );
			$('.config').removeClass('active');
			marcarFiltros();
		}
	} 

	$('#limpiarfiltros').click(function(){
		limpiarFiltrosMasivos();
	});
	
	//Filtrar
	$('#filtrar').on('click', function (e) {
		filtrosMasivos();
	});
}); 

function abrirEvento(id){
	//console.log('clic en el evento '+id);
	$("#modal-agendamiento-calendario").modal('show');
	$.get("controller/solicitudesback.php",{'oper':'cargar_cita','id':id},function(response){
		$("#agendamiento-codigo").html(response[0].id);
		$("#agendamiento-paciente").html(response[0].paciente);
		$("#agendamiento-fecha_cita").html(response[0].fecha);
		$("#agendamiento-tipo_discapacidad").html(response[0].discapacidad);		
		if(response[0].sala == ''){
			$("#agendamiento-sala").html('Sin asignar');
		}else{
			$("#agendamiento-sala").html(response[0].sala);
		}
		$("#agendamiento-estatus").html(response[0].estatus);
		if(response[0].estatus == 'Agendado' || response[0].estatus == 'ReAgendado'){
			$("#agendamiento-reagendar, #agendamiento-cancelarcita").removeClass('d-none').addClass('d-block');
		}else{
			$("#agendamiento-reagendar, #agendamiento-cancelarcita").removeClass('d-block').addClass('d-none');
		}
		$("#agendamiento-regional").html(response[0].regional);
		$("#agendamiento-idpaciente").val(response[0].idpaciente);
		$("#agendamiento-idevaluacion").val(response[0].idevaluacion);
		/*
		$.get("controller/evaluacionback.php?oper=evaluacionesporsolicitudes&idsolicitud="+id,function(response){  		
    		if (response == '1') {
    			$("#agendamiento-aceptar").prop('disabled',true)
    		}
		});
		*/		
		var url = response[0].foto.substring(3);
		if(url != ''){
			$.get(url)
			.done(function() { 
				$("#fotoperfil").attr('src',response[0].foto.substring(3));
			}).fail(function() { 
				$("#fotoperfil").attr('src',"images/user-upload.jpg");
			})
		}		
		var arreglo_medicos = response[0].medicos.split(',');
		$("#agendamiento-tabla_especialistas_cuerpo").html('');
		$.map(arreglo_medicos,function(medico){
			var registro = medico.split('|');
			var nombre = registro[0];
			var especialidad = registro[1];
			var ultimoespecialista = 1;
			var html_especialista='<tr>\
										<td style="align:left">'+nombre+'</td>\
										<td>'+especialidad+'</td>\
									</tr>';
			$("#agendamiento-tabla_especialistas_cuerpo").append(html_especialista);
		});
		
		$("#juntaevaluadora").html('');
		
		$("#tabla_especialistas_cuerpo").html('');
		especialistas  = "";
		ultimoespecialista = 0;
		cantidad_especialistas = 0;
		
		$.map(arreglo_medicos,function(medico){
			var registro = medico.split('|');
			var nombre = registro[0];
			var especialidad = registro[1];
			var ultimoespecialista = 1;
			var especialista='<li class="list-group-item d-flex px-0 py-2 justify-content-between">\
										<span class="font-w500">'+nombre+'</span>\
										<span class="mb-0">'+especialidad+'</span>\
									</li>';
			$("#juntaevaluadora").append(especialista);

			
		});
		if(response[0].estatus == 'No agendado' || response[0].estatus == 'Agendado' || response[0].estatus == 'ReAgendado'){ 
			$('.editar_juntaevaluadora').show() 
		} else 
		{
			$('.editar_juntaevaluadora').hide() 
		}
		
		var arreglo_medicoseditar = response[0].medicoseditar.split(',');
		ultimoespecialista = 0;
		$.map(arreglo_medicoseditar,function(medico){
			if(medico !== undefined){
				
				cantidad_especialistas_ = cantidad_especialistas +1;
				var idespecialista =  medico.split('|')[0];
				var nombre = medico.split('|')[1];
				var especialidad =  medico.split('|')[2];;
				especialistas += idespecialista+','; 
				
				var html_especialista='<tr data-id="'+ultimoespecialista+'">\
											<td class="text-center">\ <span  onclick="quitar_especialista('+ultimoespecialista+','+idespecialista+');" data-id="'+ultimoespecialista+'" class="fa fa-minus-circle" data-toggle="tooltip" aria-hidden="true" style="color:#FF0000;font-size:1.5em;cursor:pointer" title="Quitar especialista"></span>\
											</td>\
											<td>'+nombre+'</td>\
											<td>'+especialidad+'</td>\
										</tr>';
				ultimoespecialista	= ultimoespecialista + 1;
				$("#tabla_especialistas_cuerpo").append(html_especialista);
			}
		});
		
	},'json');
}

$("#agendamiento-aceptar").on('click', function(){
	/*
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
	if(coo == 1 || coo == 10 ){
   		localStorage.removeItem('arreglo_cif');
		var idpaciente = $("#agendamiento-idpaciente").val();
		var idevaluacion = $("#agendamiento-idevaluacion").val();
		var idsolicitud = $("#agendamiento-codigo").val();
 		location.href="evaluacion.php?idpaciente="+idpaciente+"&idsolicitud="+idsolicitud+"&idevaluacion="+idevaluacion;
	}
	*/
	var idpaciente = $("#agendamiento-idpaciente").val();
	var idevaluacion = $("#agendamiento-idevaluacion").val();
	var idsolicitud = $("#agendamiento-codigo").text();
	 
	location.href="evaluacion.php?idpaciente="+idpaciente+"&idsolicitud="+idsolicitud+"&idevaluacion="+idevaluacion;
});

$("#agendamiento-reagendar").on('click', function(){
	if($(".box-reagendar").hasClass("d-none")){
		$('.box-reagendar').removeClass('d-none').addClass('d-block');
		//$("#agendamiento-fecha_reagenda").focus();
	}else{
		$('.box-reagendar').removeClass('d-block').addClass('d-none');
	}	
});
$('#agendamiento-fecha_reagenda').bootstrapMaterialDatePicker({
	weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true, lang : 'es', cancelText: 'Cancelar' 
});
$("#reagendar-aceptar").on('click', function(){
	$('#preloader').css('display','block');
	var idsolicitud = $("#agendamiento-codigo").html();	
	var fecha = $("#agendamiento-fecha_reagenda").val();
	var estado = '7';
		
	//UPDATE ESTADO 
	$.get( "controller/calendarioback.php?oper=actualizarEstado", { idsolicitud: idsolicitud, fecha: fecha, estado: estado }, function(result)
	{
		swal('Buen trabajo', 'Cita reagendada satisfactoriamente', 'success');
		//$('#preloader').css('display','none');
		location.href = 'calendario.php';
	});
});

$("#agendamiento-cancelarcita").on('click', function(){
	$('#preloader').css('display','block');
	var idsolicitud = $("#agendamiento-codigo").html();
	var fecha = $("#agendamiento-fecha_cita").html();
	var estado = '12';
		
	//UPDATE ESTADO 
	$.get( "controller/calendarioback.php?oper=actualizarEstado", { idsolicitud: idsolicitud, fecha: fecha, estado: estado }, function(result)
	{
		swal('Buen trabajo', 'Cita cancelada satisfactoriamente', 'success');
		//$('#preloader').css('display','none');
		location.href = 'calendario.php';
	});
});
const peticionExcel = (archivo) =>{
	$('.chatbox').removeClass('active');
	$.ajax({
		type:'POST',
		url:`reportes/${archivo}`,
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
function exportar(tipo){ 
	peticionExcel('agenda.php');	
}
function exportarH(){ 
	let idsolicitud = $("#agendamiento-codigo").text();
	peticionExcel(`historialsolicitud.php?idsolicitud=${idsolicitud}`);	
}

function buscarExp (){
	let expediente = $('#expediente').val();
	if(expediente !== ''){
		$.get("controller/combosback.php?oper=solicitudesPaciente", { expediente: expediente }, function(result)
		{
			if(result !== '0'){
				$(".cmbsolicitudes").removeClass('d-none');
				$("#solicitud").empty();
				$("#solicitud").append(result);
			}else{
				$(".cmbsolicitudes").addClass('d-none');
				swal('Advertencia','No hay solicitudes registradas para este expediente.','warning');
			}		
		});
	}else{
		swal('Advertencia','Debe agregar el número de expediente.','warning');
	}
}
$('.editar_juntaevaluadora').on('click', function (e) {
	$('#juntaevaluadora').hide();
	$('#juntaevaluadora_editar').show();
	//$('#anadir_especialista').hide();
});
especialistasm(0, '');
function especialistasm(id, especialidad){
    $.get("controller/combosback.php?oper=especialistas", { especialidad: especialidad }, function(result)
    {
        $("#agendamiento-idespecialistas").empty();
        $("#agendamiento-idespecialistas").append(result);
        if (id != 0){
			//console.log('idespecialistas: '+id);
            $("#agendamiento-idespecialistas").val(id).trigger('change');
        }
    });
}
$('#agendamiento-cancelar').on('click', function (e) {
	$('#juntaevaluadora').show();
	$('#juntaevaluadora_editar').hide();
	$('#anadir_especialista').show();
});

$("#anadir_especialista").on('click',function(){
	if($("#agendamiento-idespecialistas").val() !== undefined && $("#agendamiento-idespecialistas").val() != 0){
		let contar = 0;
		$( "#tabla_especialistas_cuerpo tr" ).each(function( index ) {
			contar++;
		});
		cantidad_especialistas = contar;
		console.log(`CANTIDADESPECIALISTAS ES: ${cantidad_especialistas}`)
		if(cantidad_especialistas <= 3){
			cantidad_especialistas = cantidad_especialistas +1;
			var id = $("#agendamiento-idespecialistas").val();	
			var especialista = $("#agendamiento-idespecialistas").select2('data')[0].element.text;
			var especialidad = $("#agendamiento-idespecialistas").select2('data')[0].element.dataset.especialidad;

			if(especialistas.search(','+id+',') == -1){
				ultimoespecialista	= ultimoespecialista+1;
				var html_especialista='<tr data-id="'+ultimoespecialista+'">\
												<td class="text-center">\
													<span onclick="quitar_especialista('+ultimoespecialista+','+id+');" data-id="'+ultimoespecialista+'" class="fa fa-minus-circle" data-toggle="tooltip" aria-hidden="true" style="color:#FF0000;font-size:1.5em;cursor:pointer;" title="Quitar especialista"></span>\
												</td>\
												<td>'+especialista+'</td>\
												<td>'+especialidad+'</td>\
											</tr>';
				$("#tabla_especialistas_cuerpo").append(html_especialista);
				especialistas = especialistas + "," + id + ",";
				//console.log('especialistas añadir: '+especialistas);				
			}else{
				swal('ERROR','El Especialista <strong>'+especialista+'</strong> ya se encuentra seleccionado','error');
			}			
		}else{
				swal('ERROR','El límite de especialistas es de cuatro (4)','error');
		}
	}else{
		swal('ERROR','Debe seleccionar un especialista','error');
	}
});

function quitar_especialista(registro,id){
	$('tr[data-id="'+registro+'"]').remove();
	var str = id;
	especialistas = especialistas.replace(str,'');
	especialistas = especialistas.replace(/^( *, *)+|(, *(?=,|$))+/g, '');
	cantidad_especialistas = cantidad_especialistas - 1;
	//console.log('especialistas quitar: '+especialistas);
}

$("#agendamiento-cita").on('click',function(){ 
	let contar = 0;
	$( "#tabla_especialistas_cuerpo tr" ).each(function( index ) {
		contar++;
	});
	//var cantidad_especialistas = 3;
	if(contar < 3){
		swal("Error","Debe seleccionar mínimo tres (3) especialistas","error");
		return;
	}else{ 
			//$('#preloader').css('display', 'block');
			
			var idsolicitud = $('#agendamiento-codigo').html(); 
			var medicos = especialistas; 
			console.log(`LOS MEDICOS SON:${medicos}`)
			$.ajax({
				url: "controller/solicitudesback.php",
				type: "POST",
				data: {
					oper: 'editarJunta',
					medicos: medicos, 
					id: idsolicitud
				},
				dataType: "json",
				success: function(response){ 
					$('#preloader').css('display', 'none');
					if (response == 1){   
						swal('Buen trabajo', 'Junta Evaluadora modificada satisfactoriamente', 'success'); 
						vaciarModal()
					}else{
						swal('Error', 'Error al modificar la Junta Evaluadora', 'error');
					}
				}
			}); 
	}	
});

$('#modal-agendamiento-calendario').on('hidden.bs.modal', '.modal', function () {
    vaciarModal()
});

const vaciarModal = () =>{
	$('#juntaevaluadora').show();
	$('#juntaevaluadora_editar').hide(); 
	$("#tabla_especialistas_cuerpo").html('');
	$('.editar_juntaevaluadora').hide();
}
