//$(document).ready( function () {
if(nivel == '1' || nivel == '12' || nivel == '13' || nivel == '14'){
	var enabled_gr = true;
}else{
	var enabled_gr = false;
}
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
//});

//LOADER
funciones = 11;
param = 0;
function loader(param, fin){
	if(param == fin){
		$('#overlay').css('display','none');
		param = 0;
		$('.config').removeClass('active');
	}
}

var filtro = 0;
/*
if(filtro == 1){
	$('a.config-link').removeClass('bg-warning').addClass('bg-success');
	//$('i.fa-filter').removeClass('text-success').addClass('text-white');
}else{
	$('a.config-link').removeClass('bg-success').addClass('bg-info');
	//$('i.fa-filter').removeClass('text-white').addClass('text-success');
}
*/
$('#desdef').bootstrapMaterialDatePicker({
	weekStart:0, switchOnClick:false, time:false,  format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
});	
$('#hastaf').bootstrapMaterialDatePicker({
	weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
});

function formatNumber( num ) {
    separador =  '.'; // separador para los miles
    sepDecimal = ','; // separador para los decimales
    num += '';
    const splitStr = num.split('.');
    let splitLeft = splitStr[0];
    const splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1].substring(0, 2) : '';
    const regx = /(\d+)(\d{3})/;
    while (regx.test(splitLeft)) {
        splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
    }
    return splitLeft + splitRight;
}
//	VALIDA CARACTERES	//
function numeros(e,t) {  
	if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode != 8 && e.keyCode != 9){
		e.preventDefault();
		$(t).addClass('form-valide-error');
	}else{
		$(t).removeClass('form-valide-error');			
	}
}
function letras(e,t) { 
	if ((e.keyCode < 65 || e.keyCode > 90) && e.keyCode != 8 && e.keyCode != 9 && e.keyCode != 32 && e.keyCode !== 0){
		e.preventDefault(); 
		$(t).addClass('form-valide-error');
	}else{
		$(t).removeClass('form-valide-error');
	}
}
function correos(e,t) { 
		if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(t).val()))){
			$(t).addClass('form-valide-error');		
		}else{
			$(t).removeClass('form-valide-error');	
		}
}
//VALIDA CAMPOS VACIOS
function campos(e,t) { 
	if($(t).val()==='' || $(t).val()===0){ $(t).addClass('form-valide-error');}
	else{$(t).removeClass('form-valide-error');}	
}

//Mapa
function cargarMapa(){	  
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper"	: "mapa"
		}
	}).done(function(data) {
		Highcharts.mapChart('mapprueba', {
			chart: {
				map: 'countries/pa/pa-all'
			},
			title: {
				text: 'Ubicaciones'
			},
			subtitle: {
				text: ''
			},
			mapNavigation: {
				enabled: true,
				buttonOptions: {
					verticalAlign: 'bottom'
				}
			},
			colorAxis: {
				min: 0
			},
			credits: {
                enabled: false
            },
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				printChart:"Imprimir gráfico",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
			},
			exporting: {
				enabled: enabled_gr,
				buttons: {
					contextButton: {
						menuItems: ["printChart", "downloadJPEG", "downloadPDF"]
					}
				}
			},
			series: [{
				data: data,
				name: 'Certificaciones',
				states: {
					hover: {
						color: '#BADA55'
					}
				},
				dataLabels: {
					enabled: true,
					format: '{point.name}'
				}
			}]
		});
		param++;
		loader(param, funciones);
	}); 
}
//Usuarios Certificados
function cargarUsuariosCertificados() {	
    $.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper": "usuariosCertificados"
		}
	}).done(function(data) {
	    $('#valMujeres').text(data.mujeres + '%');
		$('#valHombres').text(data.hombres + '%');
		$('#valMujeresT').text(formatNumber(data.mujeresT));
		$('#valHombresT').text(formatNumber(data.hombresT));
		$('#bMujeres').text(data.mujeres + '%');
		$('#bHombres').text(data.hombres + '%');
		param++;
		loader(param, funciones);		
	});
}
//Tipo de discapacidad
function cargarTipodiscapacidad() {	
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper"		: "tipoDiscapacidad"
		}
	}).done(function(data) { 
		var categorias = JSON.parse(data.categorias);
		var valores = JSON.parse(data.valores);
		
		Highcharts.chart('bar_tipo_discapacidad', {
			chart: {
				type: 'bar'
			},
			title: {
				text: 'Distribución por tipo de discapacidad'
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				categories: categorias,
				title: {
					text: null
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '',
					align: 'high'
				},
				labels: {
					overflow: 'justify'
				}
			},
			tooltip: {
				valueSuffix: ''
			},
			plotOptions: {
				bar: {
					dataLabels: {
						enabled: true
					}
				}
			},
			legend: {
				itemStyle: {
					fontSize:'10px',
					font: '10pt Trebuchet MS, Verdana, sans-serif',
					color: '#7e7e7e'
				},
				itemHoverStyle: {
					color: '#36C95F'
				},
				itemHiddenStyle: {
					color: '#7e7e7e'
				},
				//borderWidth: 1,
				//backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
				//shadow: true
			},
			credits: {
				enabled: false
			},
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				printChart:"Imprimir gráfico",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
			},
			exporting: {
				enabled: enabled_gr,
				buttons: {
					contextButton: {
						menuItems: ["printChart", "downloadJPEG", "downloadPDF"]
					}
				}
			},
			series: valores
		});
		param++;
		loader(param, funciones);
	}); 
}
//Pie - Condición Laboral de Usuarios
function cargarCondicionLaboralUsuarios() {	
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper": "condicionLaboralUsuarios"
		}
	}).done(function(data) { 
		arrCondicionLab = [];
		for (i=0; i<data.length; i++) {
			data[i].y = Number(data[i].y);
			arrCondicionLab.push(data[i]);
		} 
		
		Highcharts.chart('pie_condicion_laboral', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Condición laboral'
			},
			accessibility: {
				point: {
					valueSuffix: '%'
				}
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false,
						format: '{point.name}'
					},
					showInLegend: true
				}
			},
			legend: {
				itemStyle: {
					fontSize:'10px',
					font: '10pt Trebuchet MS, Verdana, sans-serif',
					color: '#7e7e7e'
				},
				itemHoverStyle: {
					color: '#36C95F'
				},
				itemHiddenStyle: {
					color: '#7e7e7e'
				}
			},
			credits: {
                enabled: false
            },
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				printChart:"Imprimir gráfico",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
			},
			exporting: {
				enabled: enabled_gr,
				buttons: {
					contextButton: {
						menuItems: ["printChart", "downloadJPEG", "downloadPDF"]
					}
				}
			},
			series: [{
				//type: 'pie',
				name: 'Condición Laboral',
				colorByPoint: true,
				data: arrCondicionLab
			}]
		}); 
		param++;
		loader(param, funciones);
	});
}
//Ingresos familiares
function cargarIngresosFamiliaresUsuarios() {	
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper": "ingresosFamiliaresUsuarios"
		}
	}).done(function(data) { 	
		var categorias = JSON.parse(data.categorias);
		var valores = JSON.parse(data.valores);
		
		Highcharts.chart('line_ingresos_familiares', {
			chart: {
				type: 'line'
			},
			title: {
				text: 'Ingresos familiares'
			},
			subtitle: {
				text: ''
			},
			xAxis: {
					categories: categorias,
					title: {
						text: 'Ingresos'
					}
				},
			yAxis: {
				title: {
					text: null
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: false
				}
			},
			legend: {
				itemStyle: {
					fontSize:'10px',
					font: '10pt Trebuchet MS, Verdana, sans-serif',
					color: '#7e7e7e'
				},
				itemHoverStyle: {
					color: '#36C95F'
				},
				itemHiddenStyle: {
					color: '#7e7e7e'
				},
				//borderWidth: 1,
				//backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
				//shadow: true
			},
			credits: {
				enabled: false
			},
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				printChart:"Imprimir gráfico",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
			},
			exporting: {
				enabled: enabled_gr,
				buttons: {
					contextButton: {
						menuItems: ["printChart", "downloadJPEG", "downloadPDF"]
					}
				}
			},
			series: valores
	    });
		param++;
		loader(param, funciones);
    });
}
//Nivel Alfabetismo
function cargarNivelAlfabetismo() {	
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper": "nivelAlfabetismo"
		}
	}).done(function(data) {		
		var categorias = JSON.parse(data.categorias);
		var valores = JSON.parse(data.valores);

		Highcharts.chart('bar_nivel_alfabetismo', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Nivel de alfabetismo'
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				categories: categorias,
				title: {
					text: 'Niveles'
				}
			},
			yAxis: {
				title: {
					text: null
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: false
				}
			},
			legend: {
				itemStyle: {
					fontSize:'10px',
					font: '10pt Trebuchet MS, Verdana, sans-serif',
					color: '#7e7e7e'
				},
				itemHoverStyle: {
					color: '#36C95F'
				},
				itemHiddenStyle: {
					color: '#7e7e7e'
				},
				//borderWidth: 1,
				//backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
				//shadow: true
			},
			credits: {
				enabled: false
			},
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				printChart:"Imprimir gráfico",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
			},
			exporting: {
				enabled: enabled_gr,
				buttons: {
					contextButton: {
						menuItems: ["printChart", "downloadJPEG", "downloadPDF"]
					}
				}
			},
			series: valores
		});
		param++;
		loader(param, funciones);
	});
}
//Nivel Educativo
function cargarNivelEducativo() {	
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper"		: "nivelEducativo"
		}
	}).done(function(data) {		
		var categorias = JSON.parse(data.categorias);
		var valores = JSON.parse(data.valores);

		Highcharts.chart('line_nivel_educativo', {
			chart: {
				type: 'line'
			},
			title: {
				text: 'Nivel educativo'
			},
			subtitle: {
				text: ''
			},
			xAxis: {
					categories: categorias,
					title: {
						text: 'Niveles'
					}
				},
			yAxis: {
				title: {
					text: null
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: false
				}
			},
			legend: {
				itemStyle: {
					fontSize:'10px',
					font: '10pt Trebuchet MS, Verdana, sans-serif',
					color: '#7e7e7e'
				},
				itemHoverStyle: {
					color: '#36C95F'
				},
				itemHiddenStyle: {
					color: '#7e7e7e'
				},
				//borderWidth: 1,
				//backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
				//shadow: true
			},
			credits: {
				enabled: false
			},
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				printChart:"Imprimir gráfico",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
			},
			exporting: {
				enabled: enabled_gr,
				buttons: {
					contextButton: {
						menuItems: ["printChart", "downloadJPEG", "downloadPDF"]
					}
				}
			},
			series: valores
		});
		param++;
		loader(param, funciones);
	}); 
}
//Solicitudes por mes
function solicitudesMes() {	
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper": "solicitudesMes"
		}
	}).done(function(data) {		
		var categorias = JSON.parse(data.categorias);
		var valores = JSON.parse(data.valores);

		Highcharts.chart('bar_solicitudes_mes', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Solicitudes'
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				categories: categorias,
				title: {
					text: ''
				}
			},
			yAxis: {
				title: {
					text: null
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: false
				}
			},
			legend: {
				itemStyle: {
					fontSize:'10px',
					font: '10pt Trebuchet MS, Verdana, sans-serif',
					color: '#7e7e7e'
				},
				itemHoverStyle: {
					color: '#36C95F'
				},
				itemHiddenStyle: {
					color: '#7e7e7e'
				},
				enabled: true
			},
			credits: {
				enabled: false
			},
			lang: {
				viewFullscreen:"Ver en pantalla completa",
				printChart:"Imprimir gráfico",
				downloadPNG:"Descargar imagen PNG",
				downloadJPEG:"Descargar imagen",
				downloadPDF:"Descargar documento PDF",
				downloadSVG:"Descargar vector SVG",
			},
			exporting: {
				enabled: enabled_gr,
				buttons: {
					contextButton: {
						menuItems: ["printChart", "downloadJPEG", "downloadPDF"]
					}
				}
			},
			series: valores
		});
		param++;
		loader(param, funciones);
	});
}
//Totales
function cargarTotales() {
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper"		: "totales"
		}
	}).done(function(data) {
		$("#txtSolicitados").text(data.solicitudes);
		$("#txtSolicitudes").text(data.citados);
		$("#txtEvaluados").text(data.evaluados);
		$("#txtCertificados").html('<i class="fas fa-check-circle"></i> '+data.certificados);
		$("#txtNoCertificados").html('<i class="fas fa-times-circle"></i> '+data.nocertificados);
		$("#txtNoAsistio").text(data.noasistio);
		$("#txtPendientesEvaluar").text(data.pendientes);
		param++;
		loader(param, funciones);
	}); 
}
//Promedio Solicitud Agendamiento
function cargarPromedioSA() {
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper"	: "promedioSA"
		}
	}).done(function(data) {
		$("#cantSA").text(data.cantSA+' solicitudes');
		$("#promSA").text(data.promSA+' días');
		param++;
		loader(param, funciones);
	}); 
}
//Promedio Solicitud Final
function cargarPromedioSR() {
	$.ajax({
		url: "controller/dashboardback.php",
		cache: false,
		dataType: "json",
		method: "POST",
		data: {
			"oper"	: "promedioSR"
		}
	}).done(function(data) {
		$("#cantSR").text(data.cantSR+' solicitudes');
		$("#promSR").text(data.promSR+' días');
		param++;
		loader(param, funciones);
	}); 
}

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
		$('.bootstrap-badge').append('<span class="badge badge-info mr-1 font-w500">'+estado+'</span>');
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
			dataserialize[i].name == 'idcondicionsalud'  || dataserialize[i].name == 'idestados' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}
	} 
	data = JSON.stringify(data);	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/dashboardback.php',
		data: { 
			'oper'	: 'guardarfiltros',
			'data'	: data
		},
		success: function (response) {			
			$('.config').removeClass('active');
			verificarfiltros();
			marcarFiltros();
			cargarMapa();
			cargarUsuariosCertificados();
			cargarTipodiscapacidad();
			cargarCondicionLaboralUsuarios();
			cargarIngresosFamiliaresUsuarios();
			cargarNivelAlfabetismo();
			cargarNivelEducativo();
			solicitudesMes();
			cargarTotales();
			cargarPromedioSA();
			cargarPromedioSR();
		}
	});
}

function verificarfiltros(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/dashboardback.php',
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
 
function abrirFiltrosMasivos(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/dashboardback.php',
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
				datos.optradio=='radio_sol' ? $(".radio_solicitud").prop('checked', true) : $(".radio_cita").prop('checked', true);
			}
		},
		complete: function(response) {
			setTimeout(function () {
				marcarFiltros();
			},2000);
		}
	});
}

function filtrar(){
	filtrosMasivos();
}
//Filtrar
$('#filtrar').on('click', function (e) {
	param = 0;
	$('#overlay').css('display','block');
	filtrar();
});
//Limpiar Filtros
function limpiarFiltrosMasivos(){
	$('#filtromas').removeClass('bg-warning').addClass('bg-success');
	$.get( "controller/dashboardback.php?oper=limpiarFiltrosMasivos");
	var dataserialize = $("#form_filtrosmasivos").serializeArray();
	for (var i in dataserialize) {
		$("#"+dataserialize[i].name).val(null).trigger("change");
		$('.config').removeClass('active');
		marcarFiltros();
		cargarMapa();
		cargarUsuariosCertificados();
		cargarTipodiscapacidad();
		cargarCondicionLaboralUsuarios();
		cargarIngresosFamiliaresUsuarios();
		cargarNivelAlfabetismo();
		cargarNivelEducativo();
		solicitudesMes();
		cargarTotales();
		cargarPromedioSA();
		cargarPromedioSR();
	}
} 

$('#limpiarfiltros').click(function(){
	limpiarFiltrosMasivos();
});

//Cargar Elementos
verificarfiltros();
abrirFiltrosMasivos();
cargarMapa();
cargarUsuariosCertificados();
cargarTipodiscapacidad();
cargarCondicionLaboralUsuarios();
cargarIngresosFamiliaresUsuarios();
cargarNivelAlfabetismo();
cargarNivelEducativo();
solicitudesMes();
cargarTotales();
cargarPromedioSA();
cargarPromedioSR();

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

//Exportar
$('#exportar').on('click', function (e) {
	peticionExcel('dashboardexportar.php');	
});
 
//Exportar Totales
$('#exportar-totales').on('click', function (e) { 
	peticionExcel('dashboardexportar-totales.php'); 
});

//Vencimiento
$('#vencimiento').on('click', function (e) { 
	peticionExcel('dashboardvencimiento.php'); 
});