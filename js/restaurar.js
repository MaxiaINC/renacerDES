$(function() {
    $("#usuario").keyup(function() {
        $(this).val($(this).val().toLowerCase());
    });

    $('#restaurar').on('click', function() {

        var correo = $("#correo").val();
        if (correo == "") {
            swal('Advertencia', 'Debes introducir el correo', 'info');
        } else {
            $('#preloader').css('display', 'block');
            $.ajax({
                url: 'controller/recuperar.php',
                type: 'POST',
                dataType: 'json',
                data: { correo: correo },
                success: function(data) {
                    $('#preloader').css('display', 'none');

                    if (data.error === true) {
                        swal('Error', data.msg, 'error');
                    } else {
                        $("#correo").val("");
                        swal({
                                title: data.msg,
                                text: "Presione 'OK' para continuar.",
                                type: "success",
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                showCancelButton: false,
                                confirmButtonColor: '#09b354',
                                confirmButtonText: 'Ok',
                            })
                            .then((value) => {
                                $('#boxrestaurar').slideUp(300, function() {
                                    $(this).hide();
                                });
                                setTimeout(() => {
                                    $('#boxlogin').slideDown(300, function() {
                                        $(this).show();
                                    });
                                }, 400)

                            });
                    }
                },
                error: function(data) {
                    //console.log('2 '+data);
                    $('#preloader').css('display', 'none');
                    swal('Error', 'Ha ocurrido un error al restaurar su contraseña. Intente más tarde', 'error');
                }
            });

        }
    });
});