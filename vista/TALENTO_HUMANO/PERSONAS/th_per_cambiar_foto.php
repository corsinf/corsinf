<script>
    $(document).ready(function() {
        // Manejar el cambio en el input de tipo file
        // 1. Manejador Global para errores de carga de imagen
        // Este código detecta cualquier error 404 y pone la imagen por defecto automáticamente
        $('#img_persona_inf_modal').on('error', function() {
            console.log("Error 404: No se encontró la imagen. Cargando imagen por defecto...");
            $(this).attr('src', '../img/sin_imagen.jpg');
        });

        // 2. Tu función para cambiar la foto localmente
        $('#txt_copia_cambiar_foto_persona').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Simplemente asignamos el resultado. 
                    // Si el archivo estuviera corrupto, el manejador de arriba actuará.
                    $('#img_persona_inf_modal').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    function insertar_editar_cambiar_foto_persona() {
        var form_data = new FormData(document.getElementById("form_cambiar_foto_persona"));

        // console.log([...form_data]);
        // console.log([...form_data.keys()]);
        // console.log([...form_data.values()]);
        // return;

        if ($("#form_cambiar_foto_persona").valid()) {
            $.ajax({
                url: '../controlador/GENERAL/th_personasC.php?insertar_imagen=true',
                type: 'post',
                data: form_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response == -1) {
                        Swal.fire({
                            title: '',
                            text: 'Algo extraño ha ocurrido, intente más tarde.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == -2) {
                        Swal.fire({
                            title: '',
                            text: 'Asegúrese de que el archivo subido sea una Imagen.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        <?php if (isset($_GET['_id'])) { ?>
                            recargar_imagen_persona('<?= $_id ?>');
                        <?php } ?>
                        limpiar_parametros_cambiar_foto_persona();
                        $('#modal_agregar_cambiar_foto_persona').modal('hide');
                    }
                }
            });
        }
    }

    function recargar_imagen_persona(id) {
        $.ajax({
            url: '../controlador/GENERAL/th_personasC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#img_persona_inf').attr('src', response[0].foto_url + '?' + Math.random());
            }
        });
    }

    function abrir_modal_cambiar_foto_persona(id) {
        $('#modal_agregar_cambiar_foto_persona').modal('show');
        $('#lbl_titulo_cambiar_foto_persona').html('Editar foto de perfil');
        $('#btn_guardar_cambiar_foto_persona').html('Guardar');
    }

    function limpiar_parametros_cambiar_foto_persona() {
        $("#form_cambiar_foto_persona").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        $('#lbl_titulo_cambiar_foto_persona').html('Agregue una foto');
        $('#btn_guardar_cambiar_foto_persona').html('Agregar');
    }
</script>

<!-- Modal para cambiar la foto de persona -->
<div class="modal" id="modal_agregar_cambiar_foto_persona" tabindex="-1" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6><small class="text-body-secondary fw-bold" id="lbl_titulo_cambiar_foto_persona">Foto de
                        Perfil</small></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="form_cambiar_foto_persona" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <input type="hidden" name="txt_cedula_foto" id="txt_cedula_foto">
                    <input type="hidden" name="txt_persona_id_foto" id="txt_persona_id_foto">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_copia_cambiar_foto_persona" class="form-label form-label-sm">Foto de Perfil</label>
                            <div class="widget-user-image text-center">
                                <img class="rounded-circle p-1 bg-primary" src="../img/sin_imagen.jpg" id="img_persona_inf_modal" alt="Imagen Perfil Persona" width="110" height="110" />
                            </div>
                            <hr />
                            <input type="file" class="form-control form-control-sm" name="txt_copia_cambiar_foto" id="txt_copia_cambiar_foto_persona" accept=".jpg, .jpeg, .png">
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_cambiar_foto_persona" onclick="insertar_editar_cambiar_foto_persona();">Agregar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_copia_cambiar_foto_persona');

        $("#form_cambiar_foto_persona").validate({
            rules: {
                txt_copia_cambiar_foto: {
                    required: true,
                },
            },
            messages: {
                txt_copia_cambiar_foto: {
                    required: "Por favor suba una foto de perfil",
                },
            },
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
</script>