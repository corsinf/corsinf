<script>
    $(document).ready(function() {
        cargar_datos_estado_laboral(<?= $id_persona ?>);
        cargar_selects();
    });

    function cargar_selects() {
        url_estado_laboralC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_estado_laboralC.php?buscar=true';
        cargar_select2_url('ddl_estado_laboral', url_estado_laboralC, '', '#modal_estado_laboral');
        url_cargoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?buscar=true';
        cargar_select2_url('ddl_cargo', url_cargoC, '', '#modal_estado_laboral');
        url_seccionC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_seccionC.php?buscar=true';
        cargar_select2_url('ddl_seccion', url_seccionC, '', '#modal_estado_laboral');
        url_nominaC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_nominaC.php?buscar=true';
        cargar_select2_url('ddl_nomina', url_nominaC, '', '#modal_estado_laboral');
    }

    function cargar_datos_estado_laboral(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_estado_laboralC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_estado_laboral').html(response.html);

                if (response.tiene_registros) {
                    $('#pnl_crear_estado_laboral').addClass('d-none');
                } else {
                    $('#pnl_crear_estado_laboral').removeClass('d-none');
                }
            }
        });
    }

    function cargar_datos_modal_estado_laboral(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_estado_laboralC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#ddl_estado_laboral').append($('<option>', {
                    value: response[0].id_estado_laboral,
                    text: response[0].estado_laboral_descripcion,
                    selected: true
                }));

                // Cargar cargo
                $('#ddl_cargo').append($('<option>', {
                    value: response[0].id_cargo,
                    text: response[0].cargo_nombre,
                    selected: true
                }));

                // Cargar sección
                $('#ddl_seccion').append($('<option>', {
                    value: response[0].id_seccion,
                    text: response[0].seccion_descripcion,
                    selected: true
                }));

                // Cargar nómina
                if (response[0].id_nomina) {
                    $('#ddl_nomina').append($('<option>', {
                        value: response[0].id_nomina,
                        text: response[0].nomina_nombre,
                        selected: true
                    }));
                }

                $('#txt_remuneracion').val(response[0].th_est_remuneracion);
                $('#txt_fecha_contratacion_estado').val(response[0].th_est_fecha_contratacion);

                // Cargar el radio button de tipo de cambio
                var tipo_cambio = response[0].th_est_check_estado_laboral;
                if (tipo_cambio == 1) {
                    $('#radio_recategorizacion').prop('checked', true);
                } else {
                    $('#radio_baja').prop('checked', true);
                }

                // Verificar si la fecha de salida es NULL, vacía o 01/01/1900
                var fechaSalida = response[0].th_est_fecha_salida;
                if (!fechaSalida || fechaSalida === '' || fechaSalida === null ||
                    fechaSalida === '1900-01-01' || fechaSalida === '01/01/1900') {
                    $('#cbx_fecha_salida_estado').prop('checked', true);
                    $('#txt_fecha_salida_estado').val('');
                    $('#txt_fecha_salida_estado').prop('disabled', true);
                } else {
                    $('#txt_fecha_salida_estado').val(fechaSalida);
                    $('#cbx_fecha_salida_estado').prop('checked', false);
                    $('#txt_fecha_salida_estado').prop('disabled', false);
                }

                $('#txt_experiencia_estado_id').val(response[0]._id);
                ocultar_opciones_estado();
            }
        });
    }

    function insertar_editar_estado_laboral() {
        var ddl_estado_laboral = $('#ddl_estado_laboral').val();
        var ddl_cargo = $('#ddl_cargo').val();
        var ddl_seccion = $('#ddl_seccion').val();
        var ddl_nomina = $('#ddl_nomina').val();
        var txt_remuneracion = $('#txt_remuneracion').val();
        var txt_fecha_contratacion_estado = $('#txt_fecha_contratacion_estado').val();

        // Obtener el valor del radio button: 1 = RECATEGORIZACIÓN, 0 = DADO DE BAJA
        var tipo_cambio = $('input[name="tipo_cambio"]:checked').val() === 'recategorizacion' ? 1 : 0;

        // Si el checkbox está marcado, enviar null
        var txt_fecha_salida_estado = $('#cbx_fecha_salida_estado').is(':checked') ? null : $('#txt_fecha_salida_estado').val();

        var per_id = '<?= $id_persona ?>';
        var txt_experiencia_estado_id = $('#txt_experiencia_estado_id').val();

        var parametros_estado_laboral = {
            'per_id': per_id,
            'ddl_estado_laboral': ddl_estado_laboral,
            'ddl_cargo': ddl_cargo,
            'ddl_seccion': ddl_seccion,
            'ddl_nomina': ddl_nomina,
            'txt_remuneracion': txt_remuneracion,
            'tipo_cambio': tipo_cambio,
            'txt_fecha_contratacion_estado': txt_fecha_contratacion_estado,
            'txt_fecha_salida_estado': txt_fecha_salida_estado,
            '_id': txt_experiencia_estado_id,
        }

        if ($("#form_estado_laboral").valid()) {
            insertar_estado_laboral(parametros_estado_laboral);
        }
    }

    function insertar_estado_laboral(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_per_estado_laboralC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_estado_laboral').modal('hide');
                    cargar_datos_estado_laboral(<?= $id_persona ?>);
                    limpiar_campos_estado_laboral_modal();
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function ocultar_opciones_estado() {
        var select_opciones_estado = $('#ddl_estado_laboral option:selected').text();
        $('#txt_fecha_contratacion_estado').prop('disabled', false);
        $('#txt_fecha_salida_estado').prop('disabled', false);

        if (select_opciones_estado === "Freelancer" || select_opciones_estado === "Autonomo") {
            $('#txt_fecha_contratacion_estado').prop('disabled', true);
            $('#txt_fecha_salida_estado').prop('disabled', true);
            $('#txt_fecha_contratacion_estado').val('');
            $('#txt_fecha_salida_estado').val('');
        }
    }

    function abrir_modal_estado_laboral(id) {
        limpiar_campos_estado_laboral_modal();
        cargar_datos_modal_estado_laboral(id);
        $('#modal_estado_laboral').modal('show');
        $('#lbl_titulo_estado_laboral').html('Editar Estado Laboral');
        $('#btn_guardar_estado_laboral').html('<i class="bx bx-save"></i> Editar');
        $('#btn_eliminar_estado_laboral').show();
    }

    function delete_datos_estado_laboral() {
        id = $('#txt_experiencia_estado_id').val();
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_estado_laboral(id);
            }
        })
    }

    function eliminar_estado_laboral(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_per_estado_laboralC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_estado_laboral').modal('hide');
                    cargar_datos_estado_laboral(<?= $id_persona ?>);
                    limpiar_campos_estado_laboral_modal();
                }
            }
        });
    }

    function limpiar_campos_estado_laboral_modal() {
        $('#form_estado_laboral').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_estado_laboral').val('').trigger('change');
        $('#ddl_cargo').val('').trigger('change');
        $('#ddl_seccion').val('').trigger('change');
        $('#ddl_nomina').val('').trigger('change');
        $('#txt_remuneracion').val('');
        $('#txt_fecha_contratacion_estado').val('');
        $('#txt_fecha_salida_estado').val('');
        $('#txt_experiencia_estado_id').val('');
        $('#cbx_fecha_salida_estado').prop('checked', false);
        $('#radio_baja').prop('checked', true);
        $('#txt_fecha_contratacion_estado').prop('disabled', false);
        $('#txt_fecha_salida_estado').prop('disabled', false);
        $('#lbl_titulo_estado_laboral').html('Agregar Estado Laboral');
        $('#btn_guardar_estado_laboral').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_estado_laboral').hide();
    }

    function validar_fechas_est_lab() {
        var fecha_inicio = $('#txt_fecha_contratacion_estado').val();
        var fecha_final = $('#txt_fecha_salida_estado').val();
        var estado = $('#ddl_estado_laboral option:selected').text();

        // No validar si el checkbox está marcado (indefinido)
        if ($('#cbx_fecha_salida_estado').is(':checked')) {
            return true;
        }

        if (estado === "Freelancer" || estado === "Autonomo") {
            return true;
        }

        if (!fecha_inicio || !fecha_final) {
            return true;
        }

        if (Date.parse(fecha_final) < Date.parse(fecha_inicio)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha de salida no puede ser menor a la fecha de contratación.",
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_salida_estado').val('');
            $('#cbx_fecha_salida_estado').prop('checked', false);
            return false;
        }
        return true;
    }

    function checkbox_actualidad_est_lab() {
        if ($('#cbx_fecha_salida_estado').is(':checked')) {
            $('#txt_fecha_salida_estado').val('');
            $('#txt_fecha_salida_estado').prop('disabled', true);
            $('#txt_fecha_salida_estado').rules("remove", "required");
            $('#txt_fecha_salida_estado').addClass('is-valid');
            $('#txt_fecha_salida_estado').removeClass('is-invalid');
        } else {
            $('#txt_fecha_salida_estado').prop('disabled', false);
            $('#txt_fecha_salida_estado').rules("add", {
                required: true
            });
            $('#txt_fecha_salida_estado').removeClass('is-valid');
        }
    }
</script>

<div id="pnl_estado_laboral"></div>

<div class="modal" id="modal_estado_laboral" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_estado_laboral">Agregar Estado Laboral</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_estado_laboral_modal()"></button>
            </div>
            <form id="form_estado_laboral">
                <input type="hidden" name="txt_experiencia_estado_id" id="txt_experiencia_estado_id">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="ddl_estado_laboral" class="form-label form-label-sm">Estado Laboral:</label>
                            <select class="form-select form-select-sm" id="ddl_estado_laboral" name="ddl_estado_laboral" onchange="ocultar_opciones_estado()" required>
                                <option selected disabled value="">-- Seleccione un Estado --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ddl_cargo" class="form-label form-label-sm">Cargo:</label>
                            <select class="form-select form-select-sm" id="ddl_cargo" name="ddl_cargo" required>
                                <option selected disabled value="">-- Seleccione un Cargo --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="ddl_seccion" class="form-label form-label-sm">Sección:</label>
                            <select class="form-select form-select-sm" id="ddl_seccion" name="ddl_seccion" required>
                                <option selected disabled value="">-- Seleccione una Sección --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ddl_nomina" class="form-label form-label-sm">Nómina:</label>
                            <select class="form-select form-select-sm" id="ddl_nomina" name="ddl_nomina" required>
                                <option selected disabled value="">-- Seleccione una Nómina --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_remuneracion" class="form-label form-label-sm">Remuneración:</label>
                            <input type="number" step="0.01" class="form-control form-control-sm" name="txt_remuneracion" id="txt_remuneracion" placeholder="0.00">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="txt_fecha_contratacion_estado" class="form-label form-label-sm">Fecha de contratación:</label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_contratacion_estado" id="txt_fecha_contratacion_estado" onchange="validar_fechas_est_lab();">
                        </div>
                        <div class="col-md-6">
                            <label for="txt_fecha_salida_estado" class="form-label form-label-sm">Fecha de salida:</label>
                            <div class="input-group input-group-sm">
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_salida_estado" id="txt_fecha_salida_estado" onchange="validar_fechas_est_lab();">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="cbx_fecha_salida_estado" onchange="checkbox_actualidad_est_lab();" title="Marcar si el periodo es indefinido">
                                    <label class="form-check-label ms-1" for="cbx_fecha_salida_estado" style="font-size: 0.8rem;">Indefinido</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label class="form-label form-label-sm">Tipo de cambio:</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cambio" id="radio_baja" value="baja" checked>
                                    <label class="form-check-label" for="radio_baja">
                                        DADO DE BAJA
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cambio" id="radio_recategorizacion" value="recategorizacion">
                                    <label class="form-check-label" for="radio_recategorizacion">
                                        RECATEGORIZACIÓN
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_estado_laboral" onclick="if(validar_fechas_est_lab()) { insertar_editar_estado_laboral(); }"><i class="bx bx-save"></i> Agregar</button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_estado_laboral" onclick="delete_datos_estado_laboral();"><i class="bx bx-trash"></i> Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_estado_laboral');
        agregar_asterisco_campo_obligatorio('ddl_cargo');
        agregar_asterisco_campo_obligatorio('ddl_seccion');
        agregar_asterisco_campo_obligatorio('ddl_nomina');
        agregar_asterisco_campo_obligatorio('txt_fecha_contratacion_estado');
        agregar_asterisco_campo_obligatorio('txt_fecha_salida_estado');

        $("#form_estado_laboral").validate({
            rules: {
                ddl_estado_laboral: {
                    required: true,
                },
                ddl_cargo: {
                    required: true,
                },
                ddl_seccion: {
                    required: true,
                },
                ddl_nomina: {
                    required: true,
                },
                txt_fecha_contratacion_estado: {
                    required: function() {
                        var estado = $('#ddl_estado_laboral option:selected').text();
                        return estado !== "Freelancer" && estado !== "Autonomo";
                    }
                },
                txt_fecha_salida_estado: {
                    required: function() {
                        var estado = $('#ddl_estado_laboral option:selected').text();
                        return estado !== "Freelancer" && estado !== "Autonomo" && !$('#cbx_fecha_salida_estado').is(':checked');
                    }
                },
            },
            messages: {
                ddl_estado_laboral: {
                    required: "Por favor seleccione el estado laboral",
                },
                ddl_cargo: {
                    required: "Por favor seleccione un cargo",
                },
                ddl_seccion: {
                    required: "Por favor seleccione una sección",
                },
                ddl_nomina: {
                    required: "Por favor seleccione una nómina",
                },
                txt_fecha_contratacion_estado: {
                    required: "Por favor ingrese la fecha de contratación",
                },
                txt_fecha_salida_estado: {
                    required: "Por favor ingrese la fecha de salida",
                },
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            }
        });
    })
</script>