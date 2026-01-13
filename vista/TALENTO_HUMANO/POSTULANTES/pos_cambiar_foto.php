<script>
    $(document).ready(function() {
        // Manejar el cambio en el input de tipo file
        $('#txt_copia_cambiar_foto').on('change', function(event) {
            const file = event.target.files[0]; // Obtener el primer archivo seleccionado
            if (file) {
                const reader = new FileReader(); // Crear un nuevo FileReader
                reader.onload = function(e) {
                    // Actualizar la imagen en el modal
                    $('#img_postulante_inf_modal').attr('src', e.target.result);
                    // También puedes actualizar la imagen en la sección de perfil si es necesario
                    // $('#img_postulante_inf').attr('src', e.target.result);
                };
                reader.readAsDataURL(file); // Leer el archivo como URL de datos
            }
        });
    });

    //para el nombre de la foto
    function cargar_datos_modal_cambiar_foto(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#img_postulante_inf_modal').attr('src', response[0].th_pos_foto_url || '../img/sin_imagen.jpg');
            }
        });
    }



    function insertar_editar_cambiar_foto() {
        var form_data = new FormData(document.getElementById("form_cambiar_foto"));

        // console.log([...form_data]);
        // console.log([...form_data.keys()]);
        // console.log([...form_data.values()]);
        // return;


        if ($("#form_cambiar_foto").valid()) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?insertar_imagen=true',
                type: 'post',
                data: form_data,
                contentType: false,
                processData: false,
                dataType: 'json',

                success: function(response) {
                    //console.log(response);
                    if (response == -1) {
                        Swal.fire({
                            title: '',
                            text: 'Algo extraño ha ocurrido, intente más tarde.',
                            icon: 'error',
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == -2) {
                        Swal.fire({
                            title: '',
                            text: 'Asegúrese de que el archivo subido sea una Imagen.',
                            icon: 'error',
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        recargar_imagen('<?= $id_postulante ?>');
                        limpiar_parametros_cambiar_foto();
                        $('#modal_agregar_cambiar_foto').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de cambiar foto 
    function abrir_modal_cambiar_foto(id) {
        cargar_datos_modal_cambiar_foto(id);

        $('#modal_agregar_cambiar_foto').modal('show');

        $('#lbl_titulo_cambiar_foto').html('Editar su foto de perfil');
        $('#btn_guardar_cambiar_foto').html('Guardar');

    }

    function delete_datos_cambiar_foto() {
        var id_postulante = $('#txt_postulante_id').val();
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                eliminar_cambiar_foto(id_postulante);
            }
        })
    }

    function eliminar_cambiar_foto(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    recargar_imagen('<?= $id_postulante ?>');
                    limpiar_parametros_cambiar_foto();
                    $('#modal_agregar_cambiar_foto').modal('hide');
                }
            }
        });
    }


    function limpiar_parametros_cambiar_foto() {
        //cambiar foto

        // $('#txt_postulante_id').val('');
        // $('#txt_ruta_guardada_carta_recomendacion').val('');

        //Limpiar validaciones
        $("#form_cambiar_foto").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        //Cambiar texto
        $('#lbl_titulo_cambiar_foto').html('Agregue una foto');
        $('#btn_guardar_cambiar_foto').html('Agregar');
    }

    function definir_ruta_iframe_cambiar_foto(url) {
        $('#modal_ver_imagen_cambiar_foto').modal('show');
        var cambiar_ruta = $('#iframe_cambiar_foto_imagen').attr('src', url);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_cambiar_foto_imagen').attr('src', '');
    }
</script>


<div id="pnl_cambiar_foto">

</div>

<!-- Modal para cambiar la foto-->
<div class="modal" id="modal_agregar_cambiar_foto" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h6><small class="text-body-secondary fw-bold" id="lbl_titulo_cambiar_foto">Foto de Perfil</small></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form id="form_cambiar_foto" enctype="multipart/form-data" method="post" style="width: inherit;">
                <div class="modal-body">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_copia_cambiar_foto" class="form-label form-label-sm">Foto de Perfil </label>
                            <div class="widget-user-image text-center">
                                <img class="rounded-circle p-1 bg-primary" src="../img/sin_imagen.jpg" class="img-fluid" id="img_postulante_inf_modal" alt="Imagen Perfil Postulante" width="110" height="110" />
                            </div>
                            <hr />
                            <input type="file" class="form-control form-control-sm" name="txt_copia_cambiar_foto" id="txt_copia_cambiar_foto" accept=".jpg, .jpeg, .png">
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_cambiar_foto" onclick="insertar_editar_cambiar_foto();">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('txt_copia_cambiar_foto');

        //Validación Referencias Laborales
        $("#form_cambiar_foto").validate({
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
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

            }
        });
    });
</script>