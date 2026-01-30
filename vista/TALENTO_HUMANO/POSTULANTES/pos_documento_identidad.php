<script>
    $(document).ready(function() {
        cargar_datos_documentos_identidad('<?= $id_postulante ?>');
        cargar_selects_documentos();
    });


    function cargar_selects_documentos() {
        url_docIdentidadC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_doc_identidadC.php?buscar=true';
        cargar_select2_url('ddl_tipo_documento_identidad', url_docIdentidadC, '', '#modal_agregar_documentos_identidad');
    }


    //Documentos de Identidad
    function cargar_datos_documentos_identidad(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_documentosC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_documentos_identidad').html(response);
            }
        });
    }

    function obtener_documentos_repetidos() {
        documentos_identidad = $('input[name="documentos_identidad[]"]').map(function() {
            return $(this).val();
        }).get();

        //console.log(documentos_identidad);

        $('#ddl_tipo_documento_identidad option').each(function() {
            if (documentos_identidad.includes($(this).val())) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    }

    function cargar_datos_modal_documentos_identidad(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_documentosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_documentos_identificacion_id').val(response[0]._id);

                $('#ddl_tipo_documento_identidad').append($('<option>', {
                    value: response[0].id_documento,
                    text: response[0].nombre_documento,
                    selected: true
                }));

                $('#txt_ruta_guardada_documentos_identidad').val(response[0].th_pos_documentos);
            }
        });
    }

    function insertar_editar_documentos_identidad() {
        var form_data = new FormData(document.getElementById("form_documento_identidad"));
        var txt_id_documentos_identidad = $('#txt_documentos_identificacion_id').val();

        if ($('#txt_ruta_documentos_identidad').val() === '' && txt_id_documentos_identidad != '') {
            var txt_ruta_documentos_identidad = $('#txt_ruta_guardada_documentos_identidad').val()
            $('#txt_ruta_documentos_identidad').rules("remove", "required");
        } else {
            var txt_ruta_documentos_identidad = $('#txt_ruta_documentos_identidad').val();
            $('#txt_ruta_documentos_identidad').rules("add", {
                required: true
            });
        }

        if ($("#form_documento_identidad").valid()) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_documentosC.php?insertar=true',
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
                        cargar_datos_documentos_identidad('<?= $id_postulante ?>');
                        limpiar_parametros_documentos_identidad();
                        $('#modal_agregar_documentos_identidad').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de documentos identidad
    function abrir_modal_documentos_identidad(id) {
        cargar_datos_modal_documentos_identidad(id);
        $('#modal_agregar_documentos_identidad').modal('show');
        $('#lbl_titulo_documentos_identidad').html('Editar Documento Identidad');
        $('#btn_guardar_documentos_identidad').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_documento_identidad').show();

    }

    function delete_datos_documentos_identidad() {
        var id = $('#txt_documentos_identificacion_id').val();
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
                eliminar_documentos_identidad(id);
            }
        })
    }

    function eliminar_documentos_identidad(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_documentosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_documentos_identidad('<?= $id_postulante ?>');
                    limpiar_parametros_documentos_identidad();
                    $('#modal_agregar_documentos_identidad').modal('hide');
                }
            }
        });
    }

    function limpiar_parametros_documentos_identidad() {
        //certificaciones capacitaciones
        $('#ddl_tipo_documento_identidad').val(null).trigger('change');
        $('#txt_cargar_documento_identidad').val('');
        $('#txt_ruta_documentos_identidad').val('');
        $('#txt_documentos_identificacion_id').val('');
        $('#txt_ruta_guardada_documentos_identidad').val('');
        //Limpiar validaciones
        $("#form_documento_identidad").validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        //Cambiar texto
        $('#lbl_titulo_documentos_identidad').html('Agregar Documento Identidad');
        $('#btn_guardar_documentos_identidad').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_documento_identidad').hide();
    }

    function ruta_iframe_documento_identificacion(url) {
        $('#modal_ver_pdf_documentos_identidad').modal('show');
        var cambiar_ruta = $('#iframe_documentos_identidad_pdf').attr('src', url);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_documentos_identidad_pdf').attr('src', '');
    }
</script>

<div id="pnl_documentos_identidad">
</div>

<!-- Modal para agregar documento de identidad-->
<div class="modal fade" id="modal_agregar_documentos_identidad" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_documentos_identidad">
                        <i class='bx bx-id-card me-2'></i>Documento de Identidad
                    </h5>
                    <small class="text-muted">Carga tus documentos oficiales (Cédula, Pasaporte, Licencia, etc.)</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_documentos_identidad()"></button>
            </div>

            <form id="form_documento_identidad" class="needs-validation">
                <div class="modal-body">

                    <input type="hidden" name="txt_documentos_identificacion_id" id="txt_documentos_identificacion_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="row mb-col mb-3">
                        <div class="col-md-12">
                            <label for="ddl_tipo_documento_identidad" class="form-label fw-semibold fs-7">Tipo de Documento </label>
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm" id="ddl_tipo_documento_identidad" name="ddl_tipo_documento_identidad" onchange="obtener_documentos_repetidos();">
                                    <option selected disabled value="">-- Selecciona una opción --</option>
                                    <!--
                                    <option value="Cédula de Identidad">Cédula de Identidad</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                    <option value="Licencia">Licencia</option>
                                    <option value="Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana">Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana</option>
-->
                                </select>
                            </div>
                            <label class="error" style="display: none;" for="ddl_tipo_documento_identidad"></label>
                        </div>
                    </div>

                    <div class="mb-col mb-3">
                        <label for="txt_ruta_documentos_identidad" class="form-label fw-semibold">Adjuntar Documento (PDF) </label>
                        <input type="file" class="form-control form-control-sm" name="txt_ruta_documentos_identidad" id="txt_ruta_documentos_identidad" accept=".pdf">
                        <input type="hidden" name="txt_ruta_guardada_documentos_identidad" id="txt_ruta_guardada_documentos_identidad">
                        <div class="form-text text-xs">
                            <i class='bx bx-upload'></i> Sube el documento escaneado por ambos lados. Máximo 5MB.
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_documento_identidad" onclick="delete_datos_documentos_identidad();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_parametros_documentos_identidad()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_documentos_identidad" onclick="insertar_editar_documentos_identidad();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_ver_pdf_documentos_identidad" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-2 rounded-circle me-2 text-primary shadow-sm">
                        <i class='bx bxs-id-card bx-sm'></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="lbl_titulo_documentos_identidad">Documento Identidad</h5>
                        <small class="text-muted">Vista previa del documento de identidad</small>
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
                        id="iframe_documentos_identidad_pdf"
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
        agregar_asterisco_campo_obligatorio('ddl_tipo_documento_identidad');
        agregar_asterisco_campo_obligatorio('txt_ruta_documentos_identidad');

        //Validación Documento de Identidad
        $("#form_documento_identidad").validate({
            rules: {
                ddl_tipo_documento_identidad: {
                    required: true,
                },
                txt_ruta_documentos_identidad: {
                    required: true,
                },
            },
            messages: {
                ddl_tipo_documento_identidad: {
                    required: "Por favor eliga el documento de identidad que va a subir",
                },
                txt_ruta_documentos_identidad: {
                    required: "Por favor suba su documento de identidad",
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