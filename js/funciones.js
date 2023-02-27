function getQueryVariable(variable)
{
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++)
	{
		var pair = vars[i].split("=");
		if(pair[0] == variable){return pair[1];}
	}
	return(false);
}

//MENU
if(screen.width >= 1200){
	$('#main-wrapper').addClass('menu-toggle');
	//$('.hamburger').addClass('is-active');	
}

// TOOLTIPS
$('[data-toggle="tooltip"]').tooltip();

//AJUSTAR DATATABLES
function ajustarTablas(){
	if (screen.width > 1024){
		//console.log('screen.width: '+screen.width);
		$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
		$('.dataTables_scrollHead table').width('100%');
		$('.dataTables_scrollBody table').width('100%');
	}
}
$('.nav-control').on('click', function(e){
	ajustarTablas();
});

verificarToken();
function verificarToken(){
	var pageURL 	= $(location). attr("href");
	var arrUrl		= pageURL.split("/");
	var sistema		= arrUrl[3];
	var token = localStorage.getItem("token_sen");
	var usuario = localStorage.getItem("user_sen");
	$.ajax({  
		type: "POST",  
		url: "localStorage.php",
		dataType: "json",
		data: { token_sen: token, usuario_sen: usuario, sistema_sen: sistema },
		success: function(response){
			//console.log('response.valor: '+response.valor);
			if(response.valor == 0 || response.valor == 3){
				//console.log('cerrar.php');
				location.href = "cerrar.php?token="+token;
			}else if(response.valor == 2){
				//console.log('renovar localStorage');
				localStorage.setItem("user_sen",response.usuario);
				localStorage.setItem('token_sen', response.token);
				var today = new Date();
				var dd = ("0" + today.getDate()).slice(-2)
				var mm = today.getMonth() + 1; //January is 0!
				var yyyy = today.getFullYear();
				localStorage.setItem("fechaconsulta_sen",`${yyyy}-${mm}-${dd}`);
			}
		}
	});
}
$('#cerrarsesion').click(function(){
	cerrarsesion();
});
function cerrarsesion(){
	//alert('cerrarsesion');
	var token = localStorage.getItem("token_sen");
	location.href = "cerrar.php?token_sen="+token;
	window.localStorage.clear();
}

//Dropdown Menu Datatable
function cargarDropdownMenu(){
	/*
	var dropdownMenu;
	$(window).on('show.bs.dropdown', function(e) {
		dropdownMenu = $(e.target).find('.dropdown-menu');
		$('body').append(dropdownMenu.detach());
		dropdownMenu.css('display', 'block');
		dropdownMenu.position({
		  'my': 'right top',
		  'at': 'right bottom',
		  'of': $(e.relatedTarget)
		})
	});
	$(window).on('hide.bs.dropdown', function(e) {
		$(e.target).append(dropdownMenu.detach());
		dropdownMenu.hide();
	});
	*/
}

/*VALIDACIÓN DE CAMPOS DE TEXTO*/
$(".solotexto").each(function(){
	$(this).bind('keypress', solotexto);
});
$(".solonumero").each(function(){
	$(this).bind('keypress', soloNumero);
});	
$(".solotextoynumero").each(function(){
	$(this).bind('keypress', solotextoynumero);
});
$(".campo_cedula").each(function(){
	$(this).bind('keypress', campo_cedula);
});
$(".soloemail").each(function(){
	$(this).on('change', function(){
		if( $(this).val() != '' ){
			if( !validar_correo($(this).val()) ){
				$.when(swal('Error!','Dirección de correo inválida','error')).done(function(){$(this).focus();});
				$(this).focus();
			}			
		}		
	});		
});

$(".cedula").each(function(){
	$(this).on('blur', function(){
		if( $(this).val() != '' ){
			if( !validar_cedula($(this).val()) ){
				$.when(swal('Error!','Formato de cédula inválido','error')).done(function(){$(this).focus();});
			}			
		}		
	});		
});
$(".celular").each(function(){
	$(this).on('blur', function(){
		var valor = $(this).val();
		if( valor != '' ){
			var valor1 = valor.substring(0,4);
			var valor2 = valor.substring(4,8);
			var valor_final = valor1+'-'+valor2;
			$(this).val(valor_final);
		}		
	});	
	$(this).on('focus', function(){
		var valor =$(this).val() ;
		var valor_nuevo = valor.replace(/-/g,'');
		$(this).val(valor_nuevo);
	});
});
$(".telefono").each(function(){
	$(this).on('blur', function(){
		var valor = $(this).val();
		if( valor != '' ){
			var valor1 = valor.substring(0,3);
			var valor2 = valor.substring(3,8);
			var valor_final = valor1+'-'+valor2;
			$(this).val(valor_final);
		}		
	});	
	$(this).on('focus', function(){
		var valor =$(this).val() ;
		var valor_nuevo = valor.replace(/-/g,'');
		$(this).val(valor_nuevo);
	});
});

function solotexto(event) {
   var value = String.fromCharCode(event.which);
   var pattern = new RegExp(/[A-Za-záéíóúÁÉÍÓÚñÑ ']/g);
   return pattern.test(value);
}
function solotextoynumero(event) {
   var value = String.fromCharCode(event.which);
   var pattern = new RegExp(/[A-Za-z0-9-']/g);
   return pattern.test(value);
}
function campo_cedula(event) {
   var value = String.fromCharCode(event.which);
   var pattern = new RegExp(/[0-9-aeinpvAEINPV']/g);
   return pattern.test(value);
}
function soloNumero(event) {
   var value = String.fromCharCode(event.which);
   var pattern = new RegExp(/[0-9 ]/g);
   return pattern.test(value);
}
function validar_correo(correo){
	var patron=/^\w+([\.-]?\w+)@\w+([\.-]?\w+)(\.\w{2,4})+$/;
	if(correo.search(patron)==0){
	//Mail correcto
		return true;
	}else{
		//Mail incorrecto
		$.when(swal('Error!','Dirección de correo inválida','error')).done(function(){return false;});
	}
}

function validar_cedula(cedula){
	Array.prototype.insert = function(index, item) {
		this.splice(index, 0, item);
	};

	var re = /^P$|^(?:PE|E|N|[23456789]|[23456789](?:A|P)?|1[0123]?|1[0123]?(?:A|P)?)$|^(?:PE|E|N|[23456789]|[23456789](?:AV|PI)?|1[0123]?|1[0123]?(?:AV|PI)?)-?$|^(?:PE|E|N|[23456789](?:AV|PI)?|1[0123]?(?:AV|PI)?)-(?:\d{1,4})-?$|^(PE|E|N|[23456789](?:AV|PI)?|1[0123]?(?:AV|PI)?)-(\d{1,4})-(\d{1,6})$/i;
	var matched = cedula.match(re);
	// matched contains:
	// 1) if the cedula is complete (cedula = 8-AV-123-123)
	//    matched = [cedula, first part, second part, third part]
	//    [8AV-123-123]
	// 2) if the cedula is not complete (cedula = "1-1234")
	//    matched = ['1-1234', undefined, undefined, undefined]
	var isComplete = false;
	if (matched !== null) {
		matched.splice(0, 1); // remove the first match, it contains the input string.
		if (matched[0] !== undefined) {
			// if matched[0] is set => cedula complete
			isComplete = true;
			if (matched[0].match(/^PE|E|N$/)) {
				matched.insert(0, "0");
			}
			if (matched[0].match(/^(1[0123]?|[23456789])?$/)) {
				matched.insert(1, "");
			}
			if (matched[0].match(/^(1[0123]?|[23456789])(AV|PI)$/)) {
				var tmp = matched[0].match(/(\d+)(\w+)/);
				matched.splice(0, 1);
				matched.insert(0, tmp[1]);
				matched.insert(1, tmp[2]);
			}
		} // matched[0]
	}
	var result = {
	isValid: cedula.length === 0 ? true : re.test(cedula),
	inputString: cedula,
	isComplete: isComplete,
	cedula: isComplete ? matched.splice(0, 4) : null
	};
	//console.log(result);
	return result.isValid;
}

function calcularEdad(fecha,fecha_cita='') {
	//console.log('calcularEdad: fecha_cita: '+fecha_cita);
	if(fecha_cita != '' && fecha_cita != null){
		var fc = fecha_cita.split('-');
		var fechacita = fc[2]+'-'+fc[1]+'-'+fc[0];
		var hoy = new Date(fecha_cita);
	}else{
		var hoy = new Date();
	}		
	var cumpleanos = new Date(fecha);
	
	var edad = hoy.getFullYear() - cumpleanos.getFullYear();
	var m = hoy.getMonth() - cumpleanos.getMonth();
	if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
		edad--;
	}
	console.log('fecha: '+fecha);
	console.log('fechacita: '+fechacita);
	//console.log('hoy: '+hoy);
	//console.log('cumpleanos: '+cumpleanos);
	//console.log('edad: '+edad);
	return edad;
}

//"¡Exito!""¡Error!""¡Advertencia!"
const notification=(msj,title,tipo)=>{    
    let option={positionClass: "toast-top-right",
                timeOut: 5e3,
                closeButton: !0,
                debug: !1,
                newestOnTop: !0,
                progressBar: !0,
                preventDuplicates: !0,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: !1 }
                
    if(tipo==="success"){
        toastr.success( msj,title,option);
    }else if(tipo==="error"){
        toastr.error( msj,title,option);
    }else if(tipo==="warning"){
        toastr.warning( msj,title,option);    
    }else if(tipo==="info"){
        toastr.info( msj,title,option);    
    }         
}

//$(".dashboard_bar").append('<span class="text-warning">(PILOTO)</span>');

var dropdownMenu;
$(window).on('show.bs.dropdown', function(e) {        
  dropdownMenu = $(e.target).find('.dropdown-menu.droptable');
  $('body').append(dropdownMenu.detach());          
  dropdownMenu.css('display', 'block');             
  dropdownMenu.position({                           
    'my': 'right top',                            
    'at': 'right bottom',                         
    'of': $(e.relatedTarget)                      
  })                                                
});                                                   
$(window).on('hide.bs.dropdown', function(e) {        
  $(e.target).append(dropdownMenu.detach());        
  dropdownMenu.hide();                              
});

// MENU - CAMBIO DE CLAVE //
function cambiarClave() {
	var valid = true;
	if( $("#nuevaclave").val()==''){ 
		swal('Advertencia!', 'debe llenar el campo Nueva Clave', 'error');
		return;
	}
	if ( valid ) {
	$.ajax({
		  type: "post",
		  url: "controller/usuariosback.php",
		  data: { 
			"oper"	: "cambiarClave",
			"clave" : $("#nuevaclave").val()
		  },
		  success: function (response) {
				if(response){ 
					swal('Buen trabajo', 'Clave modificada satisfactoriamente', 'success');
					$("#nuevaclave").val('');
				}else{
					swal('ERROR!', 'Ha ocurrido un error al grabar el Registro, intente más tarde', 'error');
				}
		  },
		  error: function () {
				swal('ERROR!', 'Ha ocurrido un error al grabar el Registro, intente más tarde', 'error');
		  }
	   }); 
	}
	return valid;
}