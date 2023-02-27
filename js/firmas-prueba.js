var id	= getQueryVariable('id');

	if(id != ''){		
		$('.tipo').html('Actualizar inspección general');
		$(".titulo-boton").html('Actualizar');
		getform();
	}else{
		$('.tipo').html('Nueva inspección general');
		$(".titulo-boton").html('Guardar');
		$('.canvas_editar').css('display','none');
		$('.canvas_crear').css('display','block');
		$('.limpiar-firma-unidadejecutora').show();
		$('.boton-editar-firma-unidadejecutora').css('display','none');
		
		$('.limpiar-firma-solicitante').show();
		$('.boton-editar-firma-solicitante').css('display','none');
		//cargarcombos();
	}
	
configurarCanvas('unidadejecutora');

function configurarCanvas(tipo) {
	if(tipo=='unidadejecutora'){
		var idcanvas = 'canvas-unidadejecutora';
	}else if(tipo=='solicitante'){
	    var idcanvas = 'canvas-solicitante';
	}
	// Set up the canvas
	var canvas = document.getElementById(idcanvas);
    //resize just happened, pixels changed
    $(window).on("load resize", function(event){
        console.log($(this).width())
        if ($(this).width() >= 760 && $(this).width() <= 980) {
            canvas.width = 500     // 350px
            canvas.height = 300    // 200px
            $("#mostrar-firma-unidadejecutora").css("width", "400px");
            $("#mostrar-firma-solicitante").css("width", "400px");
            $("#mostrar-firma-unidadejecutora").css("height", "300px");
            $("#mostrar-firma-solicitante").css("height", "300px"); 
        }else if($(this).width() >= 350 && $(this).width() <= 760){
            canvas.width = 300     // 350px
            canvas.height = 300    // 200px
            $("#mostrar-firma-unidadejecutora").css("width", "300px");
            $("#mostrar-firma-solicitante").css("width", "300px");
            $("#mostrar-firma-unidadejecutora").css("height", "300px");
            $("#mostrar-firma-solicitante").css("height", "300px");
        }else {
            canvas.width = 800     // 350px
            canvas.height = 300    // 200px
            $("#mostrar-firma-unidadejecutora").css("width", "400px");
            $("#mostrar-firma-solicitante").css("width", "400px");
            $("#mostrar-firma-unidadejecutora").css("height", "300px");
            $("#mostrar-firma-solicitante").css("height", "300px");
        }
    });

    var ctx = canvas.getContext("2d");
	ctx.strokeStyle = "#222222";
	ctx.lineWith = 2;

	// Set up mouse events for drawing
	var drawing = false;
	var mousePos = { x:0, y:0 };
	var lastPos = mousePos;
	canvas.addEventListener("mousedown", function (e) {
			drawing = true;
	  lastPos = getMousePos(canvas, e);
	}, false);
	canvas.addEventListener("mouseup", function (e) {
	  drawing = false;
	}, false);
	canvas.addEventListener("mousemove", function (e) {
	  mousePos = getMousePos(canvas, e);
	}, false);

	// Get the position of the mouse relative to the canvas
	function getMousePos(canvasDom, mouseEvent) {
	  var rect = canvasDom.getBoundingClientRect();
	  return {
		x: mouseEvent.clientX - rect.left,
		y: mouseEvent.clientY - rect.top
	  };
	}

	// Get a regular interval for drawing to the screen
	window.requestAnimFrame = (function (callback) {
			return window.requestAnimationFrame || 
			   window.webkitRequestAnimationFrame ||
			   window.mozRequestAnimationFrame ||
			   window.oRequestAnimationFrame ||
			   window.msRequestAnimaitonFrame ||
			   function (callback) {
			window.setTimeout(callback, 1000/60);
			   };
	})();

	// Draw to the canvas
	function renderCanvas() {
	  if (drawing) {
		ctx.moveTo(lastPos.x, lastPos.y);
		ctx.lineTo(mousePos.x, mousePos.y);
		ctx.stroke();
		lastPos = mousePos;
	  }
	}

	// Allow for animation
	(function drawLoop () {
	  requestAnimFrame(drawLoop);
	  renderCanvas();
	})();

	// Set up touch events for mobile, etc
	canvas.addEventListener("touchstart", function (e) {
			mousePos = getTouchPos(canvas, e);
	  var touch = e.touches[0];
	  var mouseEvent = new MouseEvent("mousedown", {
		clientX: touch.clientX,
		clientY: touch.clientY
	  });
	  canvas.dispatchEvent(mouseEvent);
	}, false);
	canvas.addEventListener("touchend", function (e) {
	  var mouseEvent = new MouseEvent("mouseup", {});
	  canvas.dispatchEvent(mouseEvent);
	}, false);
	canvas.addEventListener("touchmove", function (e) {
	  var touch = e.touches[0];
	  var mouseEvent = new MouseEvent("mousemove", {
		clientX: touch.clientX,
		clientY: touch.clientY
	  });
	  canvas.dispatchEvent(mouseEvent);
	}, false);

	// Get the position of a touch relative to the canvas
	function getTouchPos(canvasDom, touchEvent) {
	  var rect = canvasDom.getBoundingClientRect();
	  return {
		x: touchEvent.touches[0].clientX - rect.left,
		y: touchEvent.touches[0].clientY - rect.top
	  };
	}

	// Prevent scrolling when touching the canvas
	document.body.addEventListener("touchstart", function (e) {
	  if (e.target == canvas) {
		e.preventDefault();
	  }
	}, false);
	document.body.addEventListener("touchend", function (e) {
	  if (e.target == canvas) {
		e.preventDefault();
	  }
	}, false);
	document.body.addEventListener("touchmove", function (e) {
	  if (e.target == canvas) {
		e.preventDefault();
	  }
	}, false);
	
	$("#"+idcanvas)[0].addEventListener('touchmove', function(e) {
	  e.preventDefault();
	  //brushMove();
	},false);
}

    function borrarFirma(tipo) {
	if(tipo=='unidadejecutora'){
		var idcanvas = 'canvas-unidadejecutora';
	}else if(tipo=='solicitante'){
	    var idcanvas = 'canvas-solicitante';
	}
	var canvas = document.getElementById(idcanvas);
	var ctx = canvas.getContext("2d");
	ctx.beginPath();
	ctx.clearRect(0, 0, canvas.width, canvas.height);
}

$('.boton-editar-firma-unidadejecutora').on( 'click', function() {
	$('.boton-editar-unidadejecutora').hide();
	$('#canvas-unidadejecutora').css('display','block');
	$('#mostrar-firma-unidadejecutora').css('display','none');
	$('.limpiar-firma-unidadejecutora').show();
	$('.cancelareditar-firma-unidadejecutora').show();
});
$('.cancelareditar-firma-unidadejecutora').on( 'click', function() {  
	$('.boton-editar-firma-unidadejecutora').show();
	$('#canvas-unidadejecutora').css('display','none');
	$('#mostrar-firma-unidadejecutora').css('display','block');
	$('.limpiar-firma-unidadejecutora').css('display','none');
	$('.cancelareditar-firma-unidadejecutora').css('display','none');
	borrarFirma('unidadejecutora');
});

$('.boton-editar-firma-solicitante').on( 'click', function() {
	$('.boton-editar-solicitante').hide();
	$('#canvas-solicitante').css('display','block');
	$('#mostrar-firma-solicitante').css('display','none');
	$('.limpiar-firma-solicitante').show();
	$('.cancelareditar-firma-solicitante').show();
});
$('.cancelareditar-firma-solicitante').on( 'click', function() {  
	$('.boton-editar-firma-solicitante').show();
	$('#canvas-solicitante').css('display','none');
	$('#mostrar-firma-solicitante').css('display','block');
	$('.limpiar-firma-solicitante').css('display','none');
	$('.cancelareditar-firma-solicitante').css('display','none');
	borrarFirma('solicitante');
});