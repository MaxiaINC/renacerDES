setId();
function setId() {
    if(getQueryVariable('id')){
        $('#id').val(getQueryVariable('id'));
    }
}

fillForm();
function fillForm() {
	console.log('pasó');
    if($('#id').val()){
		var id = $('#id').val();
		$.post("controller/auditoresback.php?oper=get", {id}, function(response){
			console.log('pasó 2');
			const datos = JSON.parse(response);
			$('#nombre').val(datos.nombre); 
		});
    }
}

$("#guardar").on("click",function(){
	guardar();
});
function guardar(){
    //$('#preloader').css('display', 'block');
	let id = getQueryVariable('id');
	id != '' ? oper = 'editar' : oper = 'guardar';
	let nombre = $('#nombre').val();
	
	if (validarform()){	
        $.ajax({
    		type: "POST",
    		dataType: "json",
    		url: 'controller/auditoresback.php',
    		data: { oper: oper, id: id, nombre: nombre },
    		success: function( response ) { 
    		    $('#preloader').css('display', 'none');
    			if(response.success == true){
    				swal('Buen trabajo',response.msj, 'success');
    				location.href = 'auditores.php';
    			}else{
    				swal('Error',response.msj, 'error');		
    			}
    		},
    		error: function( error ){
    			swal('Error', 'Error al intentar crear el auditor, Intente más tarde', 'error');		
    		}
    	});	
	}
} 

function validarform(){
	let verdad 	= true;
    let nombre	= $('#nombre').val(); 

    if (nombre == "" || nombre == undefined){
		swal('Error', 'El nombre esta vacío', 'error');
		return false;
	}
	return verdad;
} 