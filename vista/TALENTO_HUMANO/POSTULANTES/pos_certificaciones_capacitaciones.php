<script>
    $(document).ready(function() {
        cargar_datos_certificaciones_capacitaciones('<?= $id_postulante ?>');
    });

    //Certificaciones y Capacitaciones
    function cargar_datos_certificaciones_capacitaciones(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_certificaciones_capacitacionesC.php?listar=true',
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
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_certificaciones_capacitacionesC.php?listar_modal=true',
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
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_certificaciones_capacitacionesC.php?insertar=true',
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
                        cargar_datos_certificaciones_capacitaciones('<?= $id_postulante ?>');
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
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_certificaciones_capacitacionesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_certificaciones_capacitaciones('<?= $id_postulante ?>');
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
<div class="modal fade" id="modal_agregar_certificaciones" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_certificaciones_capacitaciones">
                        <i class='bx bx-award me-2'></i>Certificaciones y Capacitaciones
                    </h5>
                    <small class="text-muted">Registra cursos, seminarios o certificaciones técnicas obtenidas.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_certificaciones_capacitaciones()"></button>
            </div>

            <form id="form_certificaciones_capacitaciones" enctype="multipart/form-data" class="needs-validation">
                <div class="modal-body">

                    <input type="hidden" name="txt_certificaciones_capacitaciones_id" id="txt_certificaciones_capacitaciones_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="txt_nombre_curso" class="form-label fw-semibold fs-7">Nombre del Evento </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-book-bookmark'></i></span>
                                <textarea class="form-control form-control-sm no_caracteres" name="txt_nombre_curso" id="txt_nombre_curso" rows="2" maxlength="200" oninput="texto_mayusculas(this);" placeholder="Ej: Certificación en AWS Cloud Practitioner, Curso de Excel Avanzado..."></textarea>
                            </div>
                            <label class="error" style="display: none;" for="txt_nombre_curso"></label>

                        </div>
                    </div>

                    <div class="p-4 bg-light rounded-3 border border-dashed">
                        <label for="txt_ruta_archivo" class="form-label fw-semibold text-dark">Documento de Respaldo (PDF) </label>
                        <div class="input-group input-group-sm">
                            <input type="file" class="form-control" name="txt_ruta_archivo" id="txt_ruta_archivo" accept=".pdf">
                        </div>
                        <input type="hidden" name="txt_ruta_guardada_certificaciones_capacitaciones" id="txt_ruta_guardada_certificaciones_capacitaciones">
                        <label class="error" style="display: none;" for="txt_ruta_archivo"></label>

                        <div class="form-text text-xs mt-2 text-muted">
                            <i class='bx bx-cloud-upload me-1'></i> Adjunta el certificado escaneado. Asegúrate de que el archivo sea legible y no supere los 5MB.
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_certificaciones" onclick="delete_datos_certificaciones_capacitaciones();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_parametros_certificaciones_capacitaciones()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_certificaciones_capacitaciones" onclick="insertar_editar_certificaciones_capacitaciones();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_ver_pdf_certificaciones" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-2 rounded-circle me-2 text-primary shadow-sm">
                        <i class='bx bx-medal bx-sm'></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="lbl_titulo_certificaciones_capacitaciones">Certificado y/o Capacitación</h5>
                        <small class="text-muted">Vista previa de la certificación académica</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0 bg-light">
                <div class="w-100 position-relative" style="height: 80vh;">

                    <div class="position-absolute top-50 start-50 translate-middle text-muted" style="z-index: 0;">
                        <i class='bx bx-loader-alt bx-spin bx-md'></i> Cargando documento...
                    </div>

                    <iframe src=''
                        id="iframe_certificaciones_capacitaciones_pdf"
                        class="w-100 h-100 border-0 position-relative"
                        style="z-index: 1;"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

            <div class="modal-footer py-1 bg-white">
                <small class="text-muted me-auto fst-italic">
                    <i class='bx bx-info-circle'></i> Si el documento no carga, consultar con el administrador.
                </small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();">Cerrar</button>
            </div>

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