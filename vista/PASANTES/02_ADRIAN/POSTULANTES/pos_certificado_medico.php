<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_cerficados_medicos(<?= $id ?>);
        <?php } ?>
    });

    //Certificados Médicos
    function cargar_datos_cerficados_medicos(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosC.php?listar=true',
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
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_certificados_medicos_id').val(response[0]._id);
                $('#txt_med_motivo_certificado').val(response[0].th_cer_motivo_certificado);
                $('#txt_med_nom_medico').val(response[0].th_cer_nom_medico);
                $('#txt_med_ins_medico').val(response[0].th_cer_ins_medico);
                $('#txt_med_fecha_inicio_certificado').val(response[0].th_cer_fecha_inicio_certificado);
                $('#txt_med_fecha_fin_certificado').val(response[0].th_cer_fecha_fin_certificado);
                $('#txt_ruta_guardada_certificados_medicos').val(response[0].th_cer_ruta_certficado);
            }
        });
    }

    function insertar_editar_certificados_medicos() {
        var form_data = new FormData(document.getElementById("form_certificados_medicos"));
        var txt_id_certificados_medicos = $('#txt_certificados_medicos_id').val();

        if ($('#txt_ruta_certificados_medicos').val() === '' && txt_id_certificados_medicos != '') {
            var txt_ruta_certificados_medicos = $('#txt_ruta_guardada_certificados_medicos').val()
            $('#txt_ruta_certificados_medicos').rules("remove", "required");
        } else {
            var txt_ruta_certificados_medicos = $('#txt_ruta_certificados_medicos').val();
            $('#txt_ruta_certificados_medicos').rules("add", {
                required: true
            });
        }

        if ($("#form_certificados_medicos").valid()) {
            $.ajax({
                url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosC.php?insertar=true',
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
                            cargar_datos_cerficados_medicos(<?= $id ?>);
                        <?php } ?>
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
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_cerficados_medicos(<?= $id ?>);
                    <?php } ?>
                    limpiar_parametros_certificados_medicos();
                    $('#modal_agregar_certificados_medicos').modal('hide');
                }
            }
        });
    }

    function limpiar_parametros_certificados_medicos() {
        //certificaciones capacitaciones
        $('#txt_med_motivo_certificado').val('');
        $('#txt_med_nom_medico').val('');
        $('#txt_med_ins_medico').val('');
        $('#txt_med_fecha_inicio_certificado').val('');
        $('#txt_med_fecha_fin_certificado').val('');
        $('#txt_ruta_certificados_medicos').val('');
        $('#txt_certificados_medicos_id').val('');
        $('#txt_ruta_guardada_certificados_medicos').val('');
        //Limpiar validaciones
        $("#form_certificados_medicos").validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        //Cambiar texto
        $('#lbl_titulo_certificados_medicos').html('Agregar Certificado Médico');
        $('#btn_guardar_certificados_medicos').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_certificado_medico').hide();
    }

    function ruta_iframe_certificados_medicos(url) {
        $('#modal_ver_pdf_certificados_medicos').modal('show');
        var cambiar_ruta = $('#iframe_certificados_medicos_pdf').attr('src', url);
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

<!-- Modal para agregar certificados médicos-->
<div class="modal" id="modal_agregar_certificados_medicos" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_certificados_medicos">Agregar Certificado Médico</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_certificados_medicos()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificados_medicos">
                <div class="modal-body">
                    <input type="hidden" name="txt_certificados_medicos_id" id="txt_certificados_medicos_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_motivo_certificado" class="form-label form-label-sm">Motivo Certificado Médico </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_med_motivo_certificado" id="txt_med_motivo_certificado" maxlength="50">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_nom_medico" class="form-label form-label-sm">Nombre Médico Tratante </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_med_nom_medico" id="txt_med_nom_medico" maxlength="50">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_ins_medico" class="form-label form-label-sm">Nombre Institución Médica </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_med_ins_medico" id="txt_med_ins_medico" maxlength="50">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_fecha_inicio_certificado" class="form-label form-label-sm">Fecha Inicio Certificado</label>
                            <input type="date" class="form-control form-control-sm " name="txt_med_fecha_inicio_certificado" id="txt_med_fecha_inicio_certificado" onchange="txt_fecha_fin_certificado_1();">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_fecha_fin_certificado" class="form-label form-label-sm">Fecha Fin Certificado</label>
                            <input type="date" class="form-control form-control-sm " name="txt_med_fecha_fin_certificado" id="txt_med_fecha_fin_certificado" onchange="txt_fecha_fin_certificado_1();">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_ruta_certificados_medicos" class="form-label form-label-sm">Pdf Certificado Médico </label>
                            <input type="file" class="form-control form-control-sm" name="txt_ruta_certificados_medicos" id="txt_ruta_certificados_medicos" accept=".pdf">
                            <input type="text" class="form-control form-control-sm" name="txt_ruta_guardada_certificados_medicos" id="txt_ruta_guardada_certificados_medicos" hidden>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_certificados_medicos" onclick="insertar_editar_certificados_medicos(); validar_fechas_certificados_medicos()"><i class="bx bx-save"></i>Agregar</button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_certificado_medico" onclick="delete_datos_certificados_medicos();"><i class="bx bx-trash"></i>Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver certificados médicos -->
<div class="modal" id="modal_ver_pdf_certificados_medicos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_certificados_medicos">Certificados Médicos:</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe_cert_medicos();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificados_medicos">
                <div class="modal-body d-flex justify-content-center">
                    <iframe src='' id="iframe_certificados_medicos_pdf" frameborder="0" width="900px" height="700px"></iframe>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_med_motivo_certificado');
        agregar_asterisco_campo_obligatorio('txt_med_nom_medico');
        agregar_asterisco_campo_obligatorio('txt_med_ins_medico');
        agregar_asterisco_campo_obligatorio('txt_med_fecha_inicio_certificado');
        agregar_asterisco_campo_obligatorio('txt_med_fecha_fin_certificado');
        agregar_asterisco_campo_obligatorio('txt_ruta_certificados_medicos');
        //Validación de campos certificados medicos
        $("#form_certificados_medicos").validate({
            rules: {
                txt_med_motivo_certificado: {
                    required: true,
                },
                txt_med_nom_medico: {
                    required: true,
                },
                txt_med_ins_medico: {
                    required: true,
                },
                txt_med_fecha_inicio_certificado: {
                    required: true,
                },
                txt_med_fecha_fin_certificado: {
                    required: true,
                },
                txt_ruta_certificados_medicos: {
                    required: true,
                }

            },

            messages: {
                txt_med_motivo_certificado: {
                    required: "Por favor, escriba el motivo del certificado médico",
                },
                txt_med_nom_medico: {
                    required: "Por favor, escriba el nombre del médico tratante",
                },
                txt_med_ins_medico: {
                    required: "Por favor, escriba el nombre de la institución médica",
                },
                txt_med_fecha_inicio_certificado: {
                    required: "Por favor, seleccione la fecha de inicio del certificado médico",
                },
                txt_med_fecha_fin_certificado: {
                    required: "Por favor, seleccione la fecha de fin del certificado médico",
                },
                txt_ruta_certificados_medicos: {
                    required: "Por favor, seleccione el certificado médico",
                }
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
            $('#txt_med_fecha_fin_certificado')
                .addClass('is-invalid')
                .removeClass('is-valid');
        }

        // Si el campo está deshabilitado, se habilita y se limpia el valor
        if ($('#txt_med_fecha_fin_certificado').prop('disabled')) {
            $('#txt_med_fecha_fin_certificado').prop('disabled', false);
            $('#txt_med_fecha_fin_certificado').val('');
        }

        // Agregar validación dinámica al campo con jQuery Validate
        $('#txt_med_fecha_fin_certificado').rules("add", {
            required: true,
            messages: {
                required: "La fecha de fin es obligatoria."
            }
        });

        // Validar las fechas usando la función personalizada
        validar_fechas_certificados_medicos();
    }
</script>