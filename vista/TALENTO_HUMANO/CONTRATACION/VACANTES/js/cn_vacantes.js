function postular(cn_pla_id) {
    let th_pos_id = $('#txt_pos_id').val();
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_postulacionC.php?crear_postulacion=true',
        type: 'POST',
        dataType: 'json',
        data: {
            cn_pla_id: cn_pla_id,
            th_pos_id: th_pos_id
        },
        success: function (response) {
            if (response == 1) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Postulación enviada!',
                    text: '¡Tu postulación fue registrada exitosamente!',
                    confirmButtonColor: '#0d6efd'
                }).then(function () {
                    cargar_plazas(); // ← recargar para actualizar botones
                });
            } else if (response == -1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No puede registrarse en esta plaza',
                    text: 'Ya estás postulado o no cumples los requisitos.',
                    confirmButtonColor: '#fd7e14'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar tu postulación',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

function datos_postulante() {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar_personas_rol=true',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            // console.log(response);
            var datos = Array.isArray(response) ? response[0] : response;
            var id_postulante = datos ? parseInt(datos.id_postulante) : 0;

            if (response == -2) {
                bloquear_vista();
                $('#modalSinAcceso').modal('show');
                return;
            }

            if (!datos || isNaN(id_postulante) || id_postulante <= 0) {
                bloquear_vista();
                $('#modalSinPostulante').modal('show');
            } else {
                $('#txt_pos_id').val(id_postulante);
                cargar_plazas(); // ← solo aquí, cuando ya tenemos id_postulante
            }
        },
        error: function () {
            bloquear_vista();
            $('#modalSinPostulante').modal('show');
        }
    });
}

function bloquear_vista() {
    $('#pnl_plazas').html('');
    $('#pnl_paginacion').html('');
    $('#txt_buscar_plaza').prop('disabled', true);
}

function irACompletarCV(modulo_sistema) {
    location.href = `../vista/inicio.php?mod=${modulo_sistema}&acc=index`;
}

function ir_inicio(modulo_sistema) {
    location.href = `../vista/inicio.php?mod=${modulo_sistema}&acc=index`;
}