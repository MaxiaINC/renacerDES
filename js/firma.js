administradores('','');
function administradores(id,regional){
    $.get("controller/combosback.php?oper=administradores", {}, function(result)
    {
        $("#idusuarios").empty();
        $("#idusuarios").append(result);
        if (id != 0){
			$("#idusuarios").val(id).trigger('change');
			$("#regional").val(regional);
        }
    });
}
$("#idusuarios").on('select2:select',function(){
	let id = document.getElementById("idusuarios").value;
	$.post("controller/codigosautorizacionback.php?oper=getRegionalUsu", {id: id}, function(response){
		$("#regional").val(response);
	});
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
if(getQueryVariable('id')){
    fillForm(getQueryVariable('id'));    
}

function getAbsolutePath() {
	var loc = window.location;
	var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
	return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}
var ruta = getAbsolutePath();
	
function fillForm(id) {  
	$.post("controller/firmasback.php?oper=get", {id: id}, function(response){
	    let par = new Date().getTime();
		const datos = JSON.parse(response);
		administradores(datos.idusuarios,datos.regional);
		let src = `${ruta}images/firmas/${id}/${datos.firma}?${par}`;
		//$('#regional').val(datos.regional);
		$('#cargo').val(datos.cargo).trigger('change');
		datos.firma == ''? src = '' : src = src;
		$('#crop-image').attr('src',src);
	});
}

$("#guardar").on("click",function(){
	guardar();
});
function guardar(){
    //$('#preloader').css('display', 'block');
	let id = getQueryVariable('id');
	id != '' ? oper = 'editar' : oper = 'guardar';
	let idusuarios = $('#idusuarios').val();
	let regional = $('#regional').val();
	let cargo = $('#cargo').val();
	
	if (validarform()){	
	   $.ajax({
    		type: "POST",
    		dataType: "json",
    		url: 'controller/firmasback.php',
    		data: { oper: oper, id: id, idusuarios: idusuarios, regional: regional, cargo: cargo },
    		success: function( response ) { 
    		    $('#preloader').css('display', 'none');
    			if(response.success == true){
    				swal('Buen trabajo',response.msj, 'success');
    				location.href = 'firmas.php';
    			}else{
    				swal('Error',response.msj, 'error');		
    			}
    		},
    		error: function( error ){
    			swal('Error', 'Error al intentar crear el registro, Intente más tarde', 'error');		
    		}
    	}); 
	}
}

function limpiarForm(){
	$("#form_nivel")[0].reset();
	$("#form_nivel").find('select').each(function() {
       $(this).val(0).trigger('change');
	});
}

function validarform(){
	let verdad 	= true;
    let idusuarios	= $('#idusuarios').val();
    let regional	= $('#regional').val();
    let cargo		= $('#cargo').val();
    let srcimg      = $('#crop-image').attr('src');
    console.log(srcimg);
    if (idusuarios == "" || idusuarios == 0 || idusuarios == undefined){
		swal('Error', 'El nombre esta vacío', 'error');
		return false;
	}else if (regional == ""){
		swal('Error', 'El usuario no está asociado a una regional, debe agregar la regional en la configuración del usuario', 'error');
		return false;
	}else if (cargo == ""){
		swal('Error', 'El cargo está vacío', 'error');
		return false;
	}else if (srcimg == ""){
		swal('Error', 'Debe agregar la imagen', 'error');
		return false;
	}

	return verdad;
}  

$("select").select2({ language: "es" });