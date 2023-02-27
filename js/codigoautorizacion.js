administradores();
function administradores(id){
    $.get("controller/combosback.php?oper=administradores", {}, function(result)
    {
        $("#idusuarios").empty();
        $("#idusuarios").append(result);
        if (id != 0){
			$("#idusuarios").val(id).trigger('change');
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
	location.href = 'codigosautorizacion.php';
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

function fillForm(id) {  
	$.post("controller/codigosautorizacionback.php?oper=get", {id: id}, function(response){
		const datos = JSON.parse(response);
		administradores(datos.idusuarios)
		//regionales(datos.idregionales)
		/* $('#idusuarios').val(datos.idusuarios).trigger('change');
		$('#idregionales').val(datos.idregionales).trigger('change'); */
		$('#regional').val(datos.regional);
		$('#codigo').val(datos.codigo);
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
	let idregionales = $('#idregionales').val();
	let codigo = $('#codigo').val();
	if (validarform()){	
        $.ajax({
    		type: "POST",
    		dataType: "json",
    		url: 'controller/codigosautorizacionback.php',
    		data: { oper: oper, id: id, idusuarios: idusuarios, idregionales: idregionales, codigo: codigo },
    		success: function( response ) { 
    		    $('#preloader').css('display', 'none');
    			if(response.success == true){
    				swal('Buen trabajo',response.msj, 'success');
    				location.href = 'codigosautorizacion.php';
    			}else{
    				swal('Error',response.msj, 'error');		
    			}
    		},
    		error: function( error ){
    			swal('Error', 'Error al intentar crear el código de autorización, Intente más tarde', 'error');		
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
    let codigo		= $('#codigo').val();

    if (idusuarios == "" || idusuarios == 0 || idusuarios == undefined){
		swal('Error', 'El nombre esta vacío', 'error');
		return false;
	}else if (regional == ""){
		swal('Error', 'El usuario no está asociado a una regional, debe agregar la regional en la configuración del usuario', 'error');
		return false;
	}else if (codigo == ""){
		swal('Error', 'El código está vacío', 'error');
		return false;
	}
	return verdad;
}  

$("select").select2({ language: "es" });