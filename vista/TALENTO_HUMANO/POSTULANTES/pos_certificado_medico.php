<script>
    $(document).ready(function() {
        cargar_datos_cerficados_medicos('<?= $id_postulante ?>');
    });

    //Certificados Médicos
    function cargar_datos_cerficados_medicos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_certificados_medicosC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_certificados_medicos').html(response);
            }
        });
    }

    function cargar_datos_modal_certificados_medicos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_certificados_medicosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_certificados_medicos_id').val(response[0]._id);
                $('#th_cer_motivo_certificado').val(response[0].th_cer_motivo_certificado);
                $('#th_cer_alergia_req').prop('checked', response[0].th_cer_alergia_req == 1);
                $('#th_cer_tratamiento_req').prop('checked', response[0].th_cer_tratamiento_req == 1);
                $('#txt_ruta_guardada_medico').val(response[0].th_cer_ruta_certficado);
            }
        });
    }

    function insertar_editar_certificados_medicos() {
        var form_data = new FormData(document.getElementById("form_certificados_medicos"));

        if ($("#form_certificados_medicos").valid()) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_certificados_medicosC.php?insertar=true',
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
                        cargar_datos_cerficados_medicos('<?= $id_postulante ?>');
                        limpiar_parametros_certificados_medicos();
                        $('#modal_agregar_certificados_medicos').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de certificados médicos
    function abrir_modal_certificados_medicos(id) {
        cargar_datos_modal_certificados_medicos(id);
        $('#modal_agregar_certificados_medicos').modal('show');
        $('#lbl_titulo_certificados_medicos').html('Editar Certificado Médico');
        $('#btn_guardar_certificados_medicos').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_certificado_medico').show();
    }

    function delete_datos_certificados_medicos() {
        var id = $('#txt_certificados_medicos_id').val();
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
                eliminar_certificados_medicos(id);
            }
        })
    }

    function eliminar_certificados_medicos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_certificados_medicosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_cerficados_medicos('<?= $id_postulante ?>');
                    limpiar_parametros_certificados_medicos();
                    $('#modal_agregar_certificados_medicos').modal('hide');
                }
            }
        });
    }

    function limpiar_parametros_certificados_medicos() {
        $('#txt_certificados_medicos_id').val('');
        $('#th_cer_motivo_certificado').val('');
        $('#th_cer_alergia_req').prop('checked', false);
        $('#th_cer_tratamiento_req').prop('checked', false);
        $('#th_cer_ruta_certficado').val('');
        $('#txt_ruta_guardada_medico').val('');
        // Limpiar validaciones
        $("#form_certificados_medicos").validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        // Cambiar texto
        $('#lbl_titulo_certificados_medicos').html('Agregar Certificado Médico');
        $('#btn_guardar_certificados_medicos').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_certificado_medico').hide();
    }

    function ruta_iframe_certificados_medicos(url) {
        $('#modal_ver_pdf_certificados_medicos').modal('show');
        var cambiar_ruta = $('#iframe_certificados_medicos_pdf').attr('src', url);
        var cambiar_ruta_btn = $('#btn_abrir_externo_cert').attr('href', url);
    }

    function limpiar_parametros_iframe_cert_medicos() {
        $('#iframe_certificados_medicos_pdf').attr('src', '');
    }

    //Función para validar fechas de certificados médicos
    function validar_fechas_certificados_medicos() {
        var fecha_inicio = $('#txt_med_fecha_inicio_certificado').val();
        var fecha_final = $('#txt_med_fecha_fin_certificado').val();
        var hoy = new Date();
        var fecha_actual = hoy.toISOString().split('T')[0];

        //* Validar que la fecha final no sea menor a la fecha de inicio
        if (fecha_inicio && fecha_final) {
            if (Date.parse(fecha_final) < Date.parse(fecha_inicio)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha final no puede ser menor a la fecha de inicio.",
                });
                reiniciar_campos_fecha_cer_medicos('#txt_med_fecha_fin_certificado');
                return;
            }
        }

        //* Validar que la fecha final no sea menor a la fecha de inicio
        if (fecha_inicio && fecha_final) {
            if (Date.parse(fecha_final) < Date.parse(fecha_inicio)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha final no puede ser menor a la fecha de inicio.",
                });
                reiniciar_campos_fecha_cer_medicos('#txt_med_fecha_fin_certificado');
                return;
            }
        }

        //* Función para reiniciar campos
        function reiniciar_campos_fecha_cer_medicos(campo) {
            $(campo).val('');
            $(campo).removeClass('is-valid is-invalid');
            $('.form-control').removeClass('is-valid is-invalid');
        }
    }
</script>

<div id="pnl_certificados_medicos">
</div>

<div class="modal fade" id="modal_agregar_certificados_medicos" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_certificados_medicos">
                        <i class='bx bx-pulse me-2'></i>Antecedentes Médicos
                    </h5>
                    <small class="text-muted">Registra información relevante sobre tu salud.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_certificados_medicos()"></button>
            </div>

            <form id="form_certificados_medicos" class="needs-validation">
                <div class="modal-body">

                    <input type="hidden" name="txt_certificados_medicos_id" id="txt_certificados_medicos_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="mb-4">
                        <label for="th_cer_motivo_certificado" class="form-label fw-semibold fs-7">Diagnóstico / Enfermedad</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class='bx bx-notepad'></i></span>
                            <input type="text" class="form-control form-control-sm" name="th_cer_motivo_certificado" id="th_cer_motivo_certificado" maxlength="100" placeholder="Ej: Hipertensión, Asma, etc.">
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted fs-7 mb-0 fw-bold text-uppercase">Condiciones Especiales</h6>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label for="th_cer_alergia_req" class="fs-7 fw-semibold text-dark">¿Posee alguna Alergia?</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="th_cer_alergia_req" id="th_cer_alergia_req">
                                <label class="form-check-label fs-7 fw-semibold text-primary" for="th_cer_alergia_req">Sí</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <label for="th_cer_tratamiento_req" class="fs-7 fw-semibold text-dark">¿Requiere Tratamiento Continuo?</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="th_cer_tratamiento_req" id="th_cer_tratamiento_req">
                                <label class="form-check-label fs-7 fw-semibold text-primary" for="th_cer_tratamiento_req">Sí</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="th_cer_ruta_certficado" class="form-label fw-semibold fs-7">Adjuntar Examen Médico <small class="text-muted fw-normal">(Opcional)</small></label>
                        <input type="file" class="form-control form-control-sm" name="th_cer_ruta_certficado" id="th_cer_ruta_certficado" accept=".pdf">
                        <input type="hidden" name="txt_ruta_guardada_medico" id="txt_ruta_guardada_medico">
                        <div class="form-text text-xs"><i class='bx bx-upload'></i> Formato PDF. Máximo 5MB.</div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_certificado_medico" onclick="delete_datos_certificados_medicos();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_parametros_certificados_medicos()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_certificados_medicos" onclick="insertar_editar_certificados_medicos();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver certificados médicos -->
<div class="modal fade" id="modal_ver_pdf_certificados_medicos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-2 rounded-circle me-2 text-primary shadow-sm">
                        <i class='bx bxs-file-pdf bx-sm'></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="lbl_titulo_certificados_medicos">Visualizar Documento</h5>
                        <small class="text-muted">Vista previa del certificado adjunto</small>
                    </div>
                </div>

                <!-- <div class="d-flex align-items-center gap-2">
                    <a href="#" id="btn_abrir_externo_cert" target="_blank" class="btn btn-outline-primary btn-sm d-none d-sm-inline-flex align-items-center" title="Abrir en nueva pestaña">
                        <i class='bx bx-link-external me-1'></i> Expandir
                    </a>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe_cert_medicos();" aria-label="Cerrar"></button>
                </div> -->
            </div>

            <div class="modal-body p-0 bg-light">
                <div class="w-100 position-relative" style="height: 80vh;">

                    <div class="position-absolute top-50 start-50 translate-middle text-muted" style="z-index: 0;">
                        <i class='bx bx-loader-alt bx-spin bx-md'></i> Cargando documento...
                    </div>

                    <iframe src=''
                        id="iframe_certificados_medicos_pdf"
                        class="w-100 h-100 border-0 position-relative"
                        style="z-index: 1;"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

            <div class="modal-footer py-1 bg-white">
                <small class="text-muted me-auto fst-italic"><i class='bx bx-info-circle'></i> Si el documento no carga, consultar con el administrador.</small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe_cert_medicos();">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_med_motivo_certificado');
        //agregar_asterisco_campo_obligatorio('txt_med_nom_medico');
        //agregar_asterisco_campo_obligatorio('txt_med_ins_medico');
        //agregar_asterisco_campo_obligatorio('txt_med_fecha_inicio_certificado');
        // agregar_asterisco_campo_obligatorio('txt_med_fecha_fin_certificado');
        //Validación de campos certificados medicos
        $("#form_certificados_medicos").validate({
            rules: {
                txt_med_motivo_certificado: {
                    required: true,
                },
                /*txt_med_nom_medico: {
                    required: true,
                },
                txt_med_ins_medico: {
                    required: true,
                },
                txt_med_fecha_inicio_certificado: {
                    required: true,
                },*/
                // txt_med_fecha_fin_certificado: {
                //     required: true,
                // },


            },

            messages: {
                txt_med_motivo_certificado: {
                    required: "Por favor, escriba el motivo del certificado médico",
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

    function txt_fecha_fin_certificado_1() {
        // Obtén el valor actual del campo de fecha
        var fechaFin = $('#txt_med_fecha_fin_certificado').val();

        // Si el campo tiene un valor, se marca como válido
        if (fechaFin) {
            // Agregar clase 'is-valid' y remover 'is-invalid'
            $('#txt_med_fecha_fin_certificado')
                .addClass('is-valid')
                .removeClass('is-invalid');
        } else {
            // Si no tiene valor, mostrar como inválido
            // $('#txt_med_fecha_fin_certificado')
            //     .addClass('is-invalid')
            //     .removeClass('is-valid');
        }

        // Si el campo está deshabilitado, se habilita y se limpia el valor
        if ($('#txt_med_fecha_fin_certificado').prop('disabled')) {
            $('#txt_med_fecha_fin_certificado').prop('disabled', false);
            $('#txt_med_fecha_fin_certificado').val('');
        }

        // Agregar validación dinámica al campo con jQuery Validate
        // $('#txt_med_fecha_fin_certificado').rules("add", {
        //     required: true,
        //     messages: {
        //         required: "La fecha de fin es obligatoria."
        //     }
        // });

        // Validar las fechas usando la función personalizada
        validar_fechas_certificados_medicos();
    }
</script>