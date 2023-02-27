//LIMPIAR COLUMNAS
$('#limpiarCol').on('click', function(){
	$("#tablabitacora").DataTable().search("").draw();
	$('#tablabitacora_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function(){
	tablabitacora.ajax.reload();
    ajustarTablas();
});
	
$('#tablabitacora thead th').each(function() {
	var title = $(this).text();
	var id = $(this).attr('id');
	var ancho = $(this).width();
	console.log(title);
	console.log(ancho);
	console.log(id);
	if (title !== '' && title !== '-' && id !== 'accion') {
		if (screen.width > 1024) {
			//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');
			if(title == 'Usuario' || title == "Módulo"){
				$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 140px" /> ');
			}else if(title == 'Fecha'  ){
				$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
			}else if(title == 'Acción'){
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 350px" /> ');
			}else if(title == 'Identificador'){
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 130px" /> ');
			}else if(title == 'Sentencia'){
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 600x" /> ');
			}else{
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else {
			$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
		}
	} else if (id == 'accion') {
		var ancho = '50px';
	}
	$(this).width(ancho);
});

/*tabla*/
tablabitacora = $("#tablabitacora").DataTable({
	scrollY: '100%',
	scrollX: true,
	scrollCollapse: true,
	searching: true,
	destroy: true,
	ordering: false,
	processing: true,
	autoWidth : true,
	stateSave: true,
	serverSide: true,
	serverMethod: 'post', 
	ajax: {
		url: "controller/bitacoraback.php?oper=listado",
	},
	columns	: [
		{ 	"data": "id" },			//0
		{ 	"data": "acciones" },	//1
		{ 	"data": "usuario" },	//2
		{ 	"data": "fecha" },		//3
		{ 	"data": "modulo" },		//4
		{ 	"data": "accion" }		//5
	],
	rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
	columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
		{
			targets : [0,1],
			visible: false
		},
		{
			targets: [2, 3, 4, 5],
			className: 'text-left'
		}
		
	],
	language: {
		url: "js/Spanish.json",
	},
	lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
	initComplete: function() {		
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
	},
	dom: '<"toolbarU toolbarDT">Blfrtip'
});
/*fin tabla*/
tablabitacora.columns().every( function () {
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
$('#tablabitacora').on( 'draw.dt', function () {		
	cargarDropdownMenu();
	ajustarTablas();
});