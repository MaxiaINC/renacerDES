







$(document).ready(function() {
	let cropper = null;

	$('#input-file').on('change', () => {
		let image = document.getElementById('img-cropper')
		let input = document.getElementById('input-file')

		let archivos = input.files
		let extensiones = input.value.substring(input.value.lastIndexOf('.'), input.value.lenght).toLowerCase();
		
		if(!archivos || !archivos.length){        
			image.src = "";
			input.value = "";
			
		} else if(input.getAttribute('accept').split(',').indexOf(extensiones) < 0){
			 swal('Error','Debe seleccionar una imagen','error');
			 input.value = "";

		} else {
			let imagenUrl = URL.createObjectURL(archivos[0])
			image.src = imagenUrl

			cropper = new Cropper(image, {
				aspectRatio: 1, // es la proporción en la que queremos que recorte en este caso 1:1
				preview: '.img-sample', // contenedor donde se va a ir viendo en tiempo real la imagen cortada
				zoomable: true, //Para que no haga zoom 
				viewMode: 1, //Para que no estire la imagen al contenedor
				responsive: false, //Para que no reacomode con zoom la imagen al contenedor
				dragMode: 'move', //Para que al arrastrar no haga nada
				ready(){ // metodo cuando cropper ya este activo, le ponemos el alto y el ancho del contenedor de cropper al 100%
					document.querySelector('.cropper-container').style.width = '100%'
					document.querySelector('.cropper-container').style.height = '100%'
				}
			})

			$('.modal-cropper').addClass('active')
			$('.modal-content-cropper').addClass('active')

			$('.modal-cropper').removeClass('remove')
			$('.modal-content-cropper').removeClass('remove')
		}
	})

	$('#close').on('click', () => {
		let image = document.getElementById('img-cropper')
		let input = document.getElementById('input-file')

		image.src = "";
		input.value = "";

		cropper.destroy()

		$('.modal-cropper').addClass('remove')
		$('.modal-content-cropper').addClass('remove')

		$('.modal-cropper').removeClass('active')
		$('.modal-content-cropper').removeClass('active')
	})

	$('#cut').on('click', () => {
		let idpaciente = getQueryVariable('id');
		let expediente = $('#expediente').val();
		if(idpaciente == '')
			idpaciente = '';
		
		let crop_image = document.getElementById('crop-image')
		let canva = cropper.getCroppedCanvas()
		let image = document.getElementById('img-cropper')
		let input = document.getElementById('input-file')

		canva.toBlob(function(blob){
			url_cut = URL.createObjectURL(blob);
			var formData = new FormData();

			formData.append('idpaciente', idpaciente);
			formData.append('oper', 'subirFoto');
			formData.append('images', blob);

			$.ajax('controller/beneficiariosback.php', {
				method: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function () {
					crop_image.src = url_cut;
					console.log('idpaciente',idpaciente);
					if(idpaciente !== '')
						getDatosCarnet(expediente);
						
				},
				error: function () {
				  console.log('Upload error');
				}
			});
		});
			
		image.src = "";
		input.value = "";

		cropper.destroy()

		$('.modal-cropper').addClass('remove')
		$('.modal-content-cropper').addClass('remove')

		$('.modal-cropper').removeClass('active')
		$('.modal-content-cropper').removeClass('active')
	})
})
