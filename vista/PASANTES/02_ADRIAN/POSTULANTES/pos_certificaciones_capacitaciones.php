<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_certificaciones_capacitaciones(<?= $id ?>);
        <?php } ?>
    });

    //Certificaciones y Capacitaciones
    function cargar_datos_certificaciones_capacitaciones(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificaciones_capacitacionesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_certificaciones_capacitaciones').html(response);
            }
        });
    }

    function cargar_datos_modal_certificaciones_capacitaciones(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificaciones_capacitacionesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_certificaciones_capacitaciones_id').val(response[0]._id);
                $('#txt_ruta_guardada_certificaciones_capacitaciones').val(response[0].th_cert_ruta_archivo);
                $('#txt_nombre_curso').val(response[0].th_cert_nombre_curso);
            }
        });
    }

    function insertar_editar_certificaciones_capacitaciones() {
        var form_data = new FormData(document.getElementById("form_certificaciones_capacitaciones")); // Captura todos los campos y archivos

        var txt_id_certificaciones_capacitaciones = $('#txt_certificaciones_capacitaciones_id').val();

        if ($('#txt_ruta_archivo').val() === '' && txt_id_certificaciones_capacitaciones != '') {
            var txt_ruta_archivo = $('#txt_ruta_guardada_certificaciones_capacitaciones').val()
            $('#txt_ruta_archivo').rules("remove", "required");
        } else {
            var txt_ruta_archivo = $('#txt_ruta_archivo').val();
            $('#txt_ruta_archivo').rules("add", {
                required: true
            });
        }

        if ($("#form_certificaciones_capacitaciones").valid()) {

            $.ajax({
                url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificaciones_capacitacionesC.php?insertar=true',
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
                            text: 'Asegúrese de que el archivo subido sea un PDF.',
                            icon: 'error',
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        <?php if (isset($_GET['id'])) { ?>
                            cargar_datos_certificaciones_capacitaciones(<?= $id ?>);
                        <?php } ?>
                        limpiar_parametros_certificaciones_capacitaciones();
                        $('#modal_agregar_certificaciones').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de certificaciones y capacitaciones
    function abrir_modal_certificaciones_capacitaciones(id) {
        cargar_datos_modal_certificaciones_capacitaciones(id);
        $('#modal_agregar_certificaciones').modal('show');
        $('#lbl_titulo_certificaciones_capacitaciones').html('Editar Capacitación y/o Certificación');
        $('#btn_guardar_certificaciones_capacitaciones').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_certificaciones').show();

    }

    function delete_datos_certificaciones_capacitaciones() {
        var id = $('#txt_certificaciones_capacitaciones_id').val();
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_certificaciones_capacitaciones(id);
            }
        })
    }

    function eliminar_certificaciones_capacitaciones(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificaciones_capacitacionesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_certificaciones_capacitaciones(<?= $id ?>);
                    <?php } ?>
                    limpiar_parametros_certificaciones_capacitaciones();
                    $('#modal_agregar_certificaciones').modal('hide');
                }
            }
        });
    }

    function limpiar_parametros_certificaciones_capacitaciones() {
        //certificaciones capacitaciones
        $('#txt_nombre_curso').val('');
        $('#txt_ruta_archivo').val('');
        $('#txt_certificaciones_capacitaciones_id').val('');
        $('#txt_ruta_guardada_certificaciones_capacitaciones').val('');
        //Limpiar validaciones
        $("#form_certificaciones_capacitaciones").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        //Cambiar texto
        $('#lbl_titulo_certificaciones_capacitaciones').html('Agregar Certificado y/o Capacitación');
        $('#btn_guardar_certificaciones_capacitaciones').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_certificaciones').hide();
    }

    function definir_ruta_iframe_certificaciones(url) {
        var cambiar_ruta = $('#iframe_certificaciones_capacitaciones_pdf').attr('src', url);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_certificaciones_capacitaciones_pdf').attr('src', '');
    }
</script>

<div id="pnl_certificaciones_capacitaciones">
</div>

<!-- Modal para agregar certificaciones y capacitaciones-->
<div class="modal" id="modal_agregar_certificaciones" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_certificaciones_capacitaciones">Agregar Certificado y/o Capacitación</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_certificaciones_capacitaciones()"></button>
            </div>

            <!-- Modal body -->
            <form id="form_certificaciones_capacitaciones" enctype="multipart/form-data" method="post" style="width: inherit;">

                <div class="modal-body">
                    <input type="hidden" name="txt_certificaciones_capacitaciones_id" id="txt_certificaciones_capacitaciones_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_nombre_curso" class="form-label form-label-sm">Nombre Curso y/o Capacitación </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_nombre_curso" id="txt_nombre_curso" value="" maxlength="100">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_ruta_archivo" class="form-label form-label-sm">1. PDF del Certificado Obtenido </label>
                            <input type="file" class="form-control form-control-sm" name="txt_ruta_archivo" id="txt_ruta_archivo" accept=".pdf" value="">
                            <input type="hidden" class="form-control form-control-sm" name="txt_ruta_guardada_certificaciones_capacitaciones" id="txt_ruta_guardada_certificaciones_capacitaciones">
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_certificaciones_capacitaciones" onclick="insertar_editar_certificaciones_capacitaciones();"><i class="bx bx-save"></i>Agregar</button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_certificaciones" onclick="delete_datos_certificaciones_capacitaciones();"><i class="bx bx-trash"></i>Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal_ver_pdf_certificaciones" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_certificaciones_capacitaciones">Certificado y/o Capacitación</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificaciones_capacitaciones">
                <div class="modal-body d-flex justify-content-center">
                    <iframe src='' id="iframe_certificaciones_capacitaciones_pdf" frameborder="0" width="900px" height="700px"></iframe>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_nombre_curso');
        agregar_asterisco_campo_obligatorio('txt_ruta_archivo');

        //Validación Certificaciones y Capacitaciones
        $("#form_certificaciones_capacitaciones").validate({
            rules: {
                txt_nombre_curso: {
                    required: true,
                },
                txt_ruta_archivo: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_curso: {
                    required: "Por favor ingrese el nombre de su certificado",
                },
                txt_ruta_archivo: {
                    required: "Por favor ingrese el PDF de su certificado",
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
    })
</script>