<script>
    $(document).ready(function() {
        cargar_datos_certificaciones_capacitaciones('<?= $id_postulante ?>');
        cargar_selects2_capacitaciones();
    });

    function cargar_selects2_capacitaciones() {
        url_CertificadoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_certificadoC.php?buscar=true';

        url_EventoCertifidoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_evento_certificadoC.php?buscar=true';

        url_PaisC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_paisC.php?buscar=true';

        cargar_select2_url('ddl_certificado', url_CertificadoC, '', '#modal_agregar_certificaciones');

        cargar_select2_url('ddl_evento_cert', url_EventoCertifidoC, '', '#modal_agregar_certificaciones');

        cargar_select2_url('ddl_pais_cerficacion', url_PaisC, '', '#modal_agregar_certificaciones');
    }

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
                // Datos básicos
                $('#txt_certificaciones_capacitaciones_id').val(response[0]._id);
                $('#txt_ruta_guardada_certificaciones_capacitaciones').val(response[0].th_cert_ruta_archivo);
                $('#txt_nombre_curso').val(response[0].th_cert_nombre_curso);

                // Duración en horas
                $('#txt_duracion_horas').val(response[0].th_cert_duracion_horas);

                // Fecha de inicio
                $('#txt_fecha_inicio_capacitacion').val(response[0].th_cert_fecha_desde);

                // Cargar selects
                $('#ddl_pais_cerficacion').append($('<option>', {
                    value: response[0].id_pais,
                    text: response[0].nombre_pais,
                    selected: true
                }));
                $('#ddl_evento_cert').append($('<option>', {
                    value: response[0].id_evento_cert,
                    text: response[0].nombre_evento_certificado,
                    selected: true
                }));
                $('#ddl_certificado').append($('<option>', {
                    value: response[0].id_certificado,
                    text: response[0].nombre_certificado,
                    selected: true
                }));

                // Manejar fecha de finalización o si está cursando actualmente
                var fecha_fin = response[0].th_cert_fecha_hasta;
                var sigue_cursando = response[0].th_cert_sigue_cursando;

                if (fecha_fin === '' || fecha_fin === null || sigue_cursando == 1 || sigue_cursando == '1') {
                    // Está cursando actualmente
                    $('#txt_fecha_final_capacitacion').val('');
                    $('#txt_fecha_final_capacitacion').prop('readonly', true);
                    $('#cbx_fecha_final_capacitacion').prop('checked', true);
                    $('#txt_fecha_final_capacitacion').rules("remove", "required");
                    $('#txt_fecha_final_capacitacion').addClass('is-valid');
                } else {
                    // Ya finalizó
                    $('#txt_fecha_final_capacitacion').val(fecha_fin);
                    $('#txt_fecha_final_capacitacion').prop('readonly', false);
                    $('#cbx_fecha_final_capacitacion').prop('checked', false);
                }
            }
        });
    }

    function insertar_editar_certificaciones_capacitaciones() {
        var form_data = new FormData(document.getElementById("form_certificaciones_capacitaciones")); // Captura todos los campos y archivos

        var txt_id_certificaciones_capacitaciones = $('#txt_certificaciones_capacitaciones_id').val();
        var ddl_pais_cerficacion = $('#ddl_pais_cerficacion').val();
        var ddl_certificado = $('#ddl_certificado').val();
        var ddl_evento_cert = $('#ddl_evento_cert').val();

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
        $('#ddl_pais_cerficacion').val(null).trigger('change');
        $('#ddl_evento_cert').val(null).trigger('change');
        $('#ddl_certificado').val(null).trigger('change');
        //Limpiar validaciones
        $("#form_certificaciones_capacitaciones").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        //Cambiar texto
        $('#lbl_titulo_certificaciones_capacitaciones').html('Agregar Certificado y/o Capacitación');
        $('#btn_guardar_certificaciones_capacitaciones').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_certificaciones').hide();
    }

    function limpiar_parametros_certificaciones_capacitaciones() {
        //certificaciones capacitaciones
        $('#txt_nombre_curso').val('');
        $('#txt_duracion_horas').val('');
        $('#txt_ruta_archivo').val('');
        $('#txt_certificaciones_capacitaciones_id').val('');
        $('#txt_ruta_guardada_certificaciones_capacitaciones').val('');
        $('#txt_fecha_inicio_capacitacion').val('');
        $('#txt_fecha_final_capacitacion').val('');
        $('#cbx_fecha_final_capacitacion').prop('checked', false);
        $('#txt_fecha_final_capacitacion').prop('readonly', false);
        $('#ddl_pais_cerficacion').val(null).trigger('change');
        $('#ddl_evento_cert').val(null).trigger('change');
        $('#ddl_certificado').val(null).trigger('change');

        //Limpiar validaciones
        $("#form_certificaciones_capacitaciones").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        $('.select2-selection').removeClass('is-valid is-invalid');

        //Cambiar texto
        $('#lbl_titulo_certificaciones_capacitaciones').html('Agregar Certificado y/o Capacitación');
        $('#btn_guardar_certificaciones_capacitaciones').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_certificaciones').hide();
    }

    function validar_fechas_certificaciones() {
        var fecha_inicio = $('#txt_fecha_inicio_capacitacion').val();
        var fecha_final = $('#txt_fecha_final_capacitacion').val();
        var hoy = new Date();
        var fecha_actual = hoy.toISOString().split('T')[0];
        var esta_cursando = $('#cbx_fecha_final_capacitacion').is(':checked');

        // Si está cursando actualmente, no validar fecha final
        if (esta_cursando) {
            fecha_final = '';
        }

        //* Validar que la fecha final no sea menor a la fecha de inicio
        if (fecha_inicio && fecha_final && !esta_cursando) {
            if (Date.parse(fecha_final) < Date.parse(fecha_inicio)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha final no puede ser menor a la fecha de inicio.",
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_final_capacitacion').val('');
                $('#txt_fecha_inicio_capacitacion').val('');
                $('#cbx_fecha_final_capacitacion').prop('checked', false);
                $('#txt_fecha_final_capacitacion').prop('readonly', false);
                return false;
            }
        }

        //* Validar que la fecha de inicio no sea mayor a la fecha actual
        if (fecha_inicio && Date.parse(fecha_inicio) > Date.parse(fecha_actual)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha de inicio no puede ser mayor a la fecha actual.",
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_inicio_capacitacion').val('');
            return false;
        }

        //* Validar que la fecha final no sea mayor a la fecha actual (solo si no está cursando)
        if (fecha_final && Date.parse(fecha_final) > Date.parse(fecha_actual) && !esta_cursando) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha final no puede ser mayor a la fecha actual.",
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_final_capacitacion').val('');
            $('#cbx_fecha_final_capacitacion').prop('checked', false);
            $('#txt_fecha_final_capacitacion').prop('readonly', false);
            return false;
        }

        return true;
    }

    function checkbox_actualidad_certificaciones() {
        if ($('#cbx_fecha_final_capacitacion').is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();
            var fecha_actual = year + '-' + mes + '-' + dia;

            $('#txt_fecha_final_capacitacion').val('');
            $('#txt_fecha_final_capacitacion').prop('readonly', true);
            $('#txt_fecha_final_capacitacion').rules("remove", "required");

            // Agregar clase 'is-valid' para poner el campo en verde
            $('#txt_fecha_final_capacitacion').addClass('is-valid');
            $('#txt_fecha_final_capacitacion').removeClass('is-invalid');
        } else {
            // Solo limpiar el campo si estaba previamente readonly
            if ($('#txt_fecha_final_capacitacion').prop('readonly')) {
                $('#txt_fecha_final_capacitacion').val('');
            }

            $('#txt_fecha_final_capacitacion').prop('readonly', false);
            $('#txt_fecha_final_capacitacion').rules("add", {
                required: true
            });
            $('#txt_fecha_final_capacitacion').removeClass('is-valid');
            $('#form_certificaciones_capacitaciones').validate().resetForm();
            $('.form-control').removeClass('is-valid is-invalid');
        }

        // Validar fechas
        validar_fechas_certificaciones();
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
                    <input type="hidden" name="cbx_fecha_final_capacitacion" id="hidden_cbx_fecha_final_academico" value="0">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="txt_nombre_curso" class="form-label fw-semibold fs-7">Nombre del Evento </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-book-bookmark'></i></span>
                                <textarea class="form-control form-control-sm no_caracteres" name="txt_nombre_curso" id="txt_nombre_curso" rows="2" maxlength="200" oninput="texto_mayusculas(this);" placeholder="Ej: CERTIFICACIÓN EN AWS CLOUD PRACTITIONER..."></textarea>
                            </div>
                            <label class="error" style="display: none;" for="txt_nombre_curso"></label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="ddl_pais_cerficacion" class="form-label fw-semibold fs-7">País </label>
                            <select class="form-select select2-validation" id="ddl_pais_cerficacion" name="ddl_pais_cerficacion" required>
                                <option value="">-- Seleccione País --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_pais_cerficacion"></label>
                        </div>

                        <div class="col-md-4">
                            <label for="ddl_evento_cert" class="form-label fw-semibold fs-7">Tipo de Evento </label>
                            <select class="form-select select2-validation" id="ddl_evento_cert" name="ddl_evento_cert" required>
                                <option value="">-- Seleccione tipo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_evento_cert"></label>
                        </div>

                        <div class="col-md-4">
                            <label for="ddl_certificado" class="form-label fw-semibold fs-7">Tipo de Certificado </label>
                            <select class="form-select select2-validation" id="ddl_certificado" name="ddl_certificado" required>
                                <option value="">-- Seleccione tipo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_certificado"></label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="txt_duracion_horas" class="form-label fw-semibold fs-7">Duración (Horas) </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-time'></i></span>
                                <input type="number" class="form-control" name="txt_duracion_horas" id="txt_duracion_horas" min="0" step="1" placeholder="Ej: 40">
                                <span class="input-group-text bg-white text-muted">horas</span>
                            </div>
                            <label class="error" style="display: none;" for="txt_duracion_horas"></label>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted fs-7 mb-0 fw-bold text-uppercase">Periodo de Evento </h6>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="cbx_fecha_final_capacitacion" id="cbx_fecha_final_capacitacion" onchange="checkbox_actualidad_certificaciones();">
                                <label for="cbx_fecha_final_capacitacion" class="form-check-label fs-7 fw-semibold text-primary">Cursando actualmente</label>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="txt_fecha_inicio_capacitacion" class="form-label fs-7 mb-1">Fecha Inicio </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_capacitacion" id="txt_fecha_inicio_capacitacion" onblur="checkbox_actualidad_certificaciones();">
                                <label class="error" style="display: none;" for="txt_fecha_inicio_capacitacion"></label>
                            </div>
                            <div class="col-md-6">
                                <label for="txt_fecha_final_capacitacion" class="form-label fs-7 mb-1">Fecha Finalización </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_final_capacitacion" id="txt_fecha_final_capacitacion" onblur="checkbox_actualidad_certificaciones();">
                                <label class="error" style="display: none;" for="txt_fecha_final_capacitacion"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-3 bg-light rounded-3 border border-dashed">
                                <label for="txt_ruta_archivo" class="form-label fw-semibold text-dark fs-7">Documento de Respaldo (PDF) </label>
                                <div class="input-group input-group-sm">
                                    <input type="file" class="form-control" name="txt_ruta_archivo" id="txt_ruta_archivo" accept=".pdf">
                                </div>
                                <input type="hidden" name="txt_ruta_guardada_certificaciones_capacitaciones" id="txt_ruta_guardada_certificaciones_capacitaciones">
                                <label class="error" style="display: none;" for="txt_ruta_archivo"></label>

                                <div class="form-text text-xs mt-2 text-muted" style="font-size: 0.75rem;">
                                    <i class='bx bx-cloud-upload me-1'></i> Adjunta el certificado escaneado. Asegúrate de que el archivo sea legible y no supere los 5MB.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_certificaciones" onclick="delete_datos_certificaciones_capacitaciones();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_parametros_certificaciones_capacitaciones()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_certificaciones_capacitaciones" onclick="validar_fechas_certificaciones(); insertar_editar_certificaciones_capacitaciones();">
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
        agregar_asterisco_campo_obligatorio('ddl_pais_cerficacion');
        agregar_asterisco_campo_obligatorio('ddl_evento_cert');
        agregar_asterisco_campo_obligatorio('ddl_certificado');
        agregar_asterisco_campo_obligatorio('txt_duracion_horas');
        agregar_asterisco_campo_obligatorio('txt_fecha_inicio_capacitacion');
        agregar_asterisco_campo_obligatorio('txt_fecha_final_capacitacion');

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

        //Validación Certificaciones y Capacitaciones
        $("#form_certificaciones_capacitaciones").validate({
            rules: {
                txt_nombre_curso: {
                    required: true,
                },
                txt_ruta_archivo: {
                    required: true,
                },
                ddl_pais_cerficacion: {
                    required: true,
                },
                ddl_evento_cert: {
                    required: true,
                },
                ddl_certificado: {
                    required: true,
                },
                txt_duracion_horas: {
                    required: true,
                    number: true,
                    min: 0
                },
                txt_fecha_inicio_capacitacion: {
                    required: true,
                },
                txt_fecha_final_capacitacion: {
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
                ddl_pais_cerficacion: {
                    required: "Por favor seleccione el país",
                },
                ddl_evento_cert: {
                    required: "Por favor seleccione el evento del certificado",
                },
                ddl_certificado: {
                    required: "Por favor seleccione el certificado",
                },
                txt_duracion_horas: {
                    required: "Por favor ingrese la duración en horas",
                    number: "Por favor ingrese un número válido",
                    min: "La duración debe ser mayor o igual a 0"
                },
                txt_fecha_inicio_capacitacion: {
                    required: "Por favor ingrese la fecha de inicio",
                },
                txt_fecha_final_capacitacion: {
                    required: "Por favor ingrese la fecha de finalización",
                },
            },

            highlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-valid").addClass("is-invalid");
                } else if ($element.is(':radio')) {
                    $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass("is-valid");
                } else {
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-invalid").addClass("is-valid");
                } else if ($element.is(':radio')) {
                    $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass("is-valid");
                } else {
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });

        // Actualizar el valor del checkbox oculto cuando cambie el checkbox visible
        $('#cbx_fecha_final_capacitacion').on('change', function() {
            $('#hidden_cbx_fecha_final_academico').val(this.checked ? '1' : '0');
        });
    });
</script>