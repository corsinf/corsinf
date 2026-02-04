<script>
    $(document).ready(function() {
        cargar_datos_estado_laboral('<?= $id_persona ?>');
        cargar_selects();

        $('input[name="tipo_cambio"]').on('change', function() {
            if ($(this).val() === 'recategorizacion') {
                Swal.fire({
                    title: 'Atención',
                    text: "Estos datos serán enviados a experiencia previa. ¿Desea continuar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (!result.value) {
                        $('#radio_baja').prop('checked', false);
                        $('#radio_recategorizacion').prop('checked', false);
                        enabled_campos(false);
                    } else {
                        enabled_campos(true);
                    }
                });
            } else if ($(this).val() === 'baja') {
                enabled_campos(true);
            } else if ($(this).val() === 'ninguno') {
                enabled_campos(false);
            }
        });

    });

    function cargar_selects() {
        $('#ddl_estado_laboral').prop('disabled', true);
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

                $('#ddl_estado_laboral').prop('disabled', true);

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

                var tipo_cambio = response[0].th_est_check_estado_laboral;

                $('input[name="tipo_cambio"]').prop('checked', false);

                if (tipo_cambio !== "" && tipo_cambio !== null) {
                    if (tipo_cambio == "RECATEGORIZACION") {
                        $('#radio_recategorizacion').prop('checked', true);
                    } else if (tipo_cambio == "DADO_BAJA") {
                        $('#radio_baja').prop('checked', true);
                        enabled_campos(true);
                    } else if (tipo_cambio == "NINGUNO") {
                        $('#radio_ninguno').prop('checked', true);
                        enabled_campos(false);
                    }
                } else {
                    $('input[name="tipo_cambio"]').prop('checked', false);
                    enabled_campos(false);
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
            }
        });
    }

    function insertar_editar_estado_laboral() {

        var radioSeleccionado = $('input[name="tipo_cambio"]:checked').val() || null;

        var estado_laboral; // ACTIVO / INACTIVO
        var tipo_cambio = null; // 1 = recategorización | 0 = baja | null = normal

        var radioSeleccionado = $('input[name="tipo_cambio"]:checked').val() || null;

        var tipo_cambio = null; // valor que irá a BD

        if (radioSeleccionado === 'baja') {
            tipo_cambio = 'DADO_BAJA';
        } else if (radioSeleccionado === 'recategorizacion') {
            tipo_cambio = 'RECATEGORIZACION';
        } else if (radioSeleccionado === 'ninguno') {
            tipo_cambio = 'NINGUNO';
        }

        var ddl_estado_laboral = $('#ddl_estado_laboral').val();
        var ddl_cargo = $('#ddl_cargo').val();
        var ddl_seccion = $('#ddl_seccion').val();
        var ddl_nomina = $('#ddl_nomina').val();
        var txt_remuneracion = $('#txt_remuneracion').val();
        var txt_fecha_contratacion_estado = $('#txt_fecha_contratacion_estado').val();

        var txt_fecha_salida_estado = $('#cbx_fecha_salida_estado').is(':checked') ? null : $('#txt_fecha_salida_estado').val();

        var per_id = '<?= $id_persona ?>';
        var pos_id = '<?= $id_postulante ?>';
        var txt_experiencia_estado_id = $('#txt_experiencia_estado_id').val();

        var parametros_estado_laboral = {
            'per_id': per_id,
            'pos_id': pos_id,
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
                    cargar_datos_estado_laboral('<?= $id_persona ?>');
                    limpiar_campos_estado_laboral_modal();
                    cargar_datos_experiencia_laboral('<?= $id_postulante ?>');
                    cargar_datos_info_adicional('<?= $id_persona ?>');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
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
                    cargar_datos_estado_laboral('<?= $id_persona ?>');
                    limpiar_campos_estado_laboral_modal();
                }
            }
        });
    }

    function enabled_campos(estado) {
        $('#ddl_cargo').prop('disabled', estado);
        $('#ddl_seccion').prop('disabled', estado);
        $('#ddl_nomina').prop('disabled', estado);
        $('#txt_remuneracion').prop('disabled', estado);
        $('#txt_fecha_contratacion_estado').prop('disabled', estado);
        $('#txt_fecha_salida_estado').prop('disabled', estado);
    }

    function abrir_modal_estado_laboral(id) {
        limpiar_campos_estado_laboral_modal();
        cargar_datos_modal_estado_laboral(id);
        $('#modal_estado_laboral').modal('show');
        $('#lbl_titulo_estado_laboral').html('Editar Estado Laboral');
        $('#btn_guardar_estado_laboral').html('<i class="bx bx-save"></i> Editar');
        $('#btn_eliminar_estado_laboral').show();
    }

    function limpiar_campos_estado_laboral_modal() {
        $('#form_estado_laboral').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#txt_remuneracion').val('');
        $('#txt_fecha_contratacion_estado').val('');
        $('#txt_fecha_salida_estado').val('');
        $('#txt_experiencia_estado_id').val('');
        $('#cbx_fecha_salida_estado').prop('checked', false);
        $('#txt_fecha_contratacion_estado').prop('disabled', false);
        $('#txt_fecha_salida_estado').prop('disabled', false);
        $('#lbl_titulo_estado_laboral').html('Agregar Estado Laboral');
        $('#btn_guardar_estado_laboral').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_estado_laboral').hide();

        $('#ddl_estado_laboral').val(null).trigger('change');
        $('#ddl_cargo').val(null).trigger('change');
        $('#ddl_seccion').val(null).trigger('change');
        $('#ddl_nomina').val(null).trigger('change');

        $('.select2-selection').removeClass('is-valid is-invalid');
        $('.select2-validation').each(function() {
            $('label.error[for="' + this.id + '"]').hide();
        });
    }

    function validar_fechas_est_lab() {
        var fecha_inicio = $('#txt_fecha_contratacion_estado').val();
        var fecha_final = $('#txt_fecha_salida_estado').val();
        var estado = $('#ddl_estado_laboral option:selected').text();

        // No validar si el checkbox está marcado (indefinido)
        if ($('#cbx_fecha_salida_estado').is(':checked')) {
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

<div class="modal fade" id="modal_estado_laboral" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_estado_laboral">
                        <i class='bx bx-briefcase me-2'></i>Estado Laboral Interno
                    </h5>
                    <small class="text-muted">Gestiona la situación contractual, cargos y remuneraciones.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_estado_laboral_modal()"></button>
            </div>

            <form id="form_estado_laboral" class="needs-validation">
                <input type="hidden" name="txt_experiencia_estado_id" id="txt_experiencia_estado_id">

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ddl_estado_laboral" class="form-label fw-semibold fs-7">Estado Laboral </label>
                            <select class="form-select select2-validation" id="ddl_estado_laboral" name="ddl_estado_laboral" required style="width: 100%;">
                                <option selected disabled value="">-- Seleccione un Estado --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_estado_laboral"></label>

                        </div>
                        <div class="col-md-6">
                            <label for="ddl_cargo" class="form-label fw-semibold fs-7">Cargo </label>
                            <select class="form-select select2-validation" id="ddl_cargo" name="ddl_cargo" required style="width: 100%;">
                                <option selected disabled value="">-- Seleccione un Cargo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_cargo"></label>

                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ddl_seccion" class="form-label fw-semibold fs-7">Sección / Área </label>
                            <select class="form-select select2-validation" id="ddl_seccion" name="ddl_seccion" required style="width: 100%;">
                                <option selected disabled value="">-- Seleccione una Sección --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_seccion"></label>

                        </div>
                        <div class="col-md-6">
                            <label for="ddl_nomina" class="form-label fw-semibold fs-7">Nómina </label>
                            <select class="form-select select2-validation" id="ddl_nomina" name="ddl_nomina" required style="width: 100%;">
                                <option selected disabled value="">-- Seleccione una Nómina --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_nomina"></label>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="txt_remuneracion" class="form-label fw-semibold fs-7">Remuneración </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-money'></i></span>
                                <input type="number" step="0.01" class="form-control" name="txt_remuneracion" id="txt_remuneracion" placeholder="0.00">
                            </div>
                            <label class="error" style="display: none;" for="txt_remuneracion"></label>

                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed mb-3">
                        <h6 class="text-muted fs-7 mb-2 fw-bold text-uppercase ls-1">Periodo de Gestión </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="txt_fecha_contratacion_estado" class="form-label fs-7 mb-1">Fecha de Contratación </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_contratacion_estado" id="txt_fecha_contratacion_estado" onchange="validar_fechas_est_lab();">
                            </div>
                            <div class="col-md-6">
                                <label for="txt_fecha_salida_estado" class="form-label fs-7 mb-1">Fecha de Salida </label>
                                <div class="input-group input-group-sm">
                                    <input type="date" class="form-control" name="txt_fecha_salida_estado" id="txt_fecha_salida_estado" onchange="validar_fechas_est_lab();">
                                    <div class="input-group-text bg-white border-start-0">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="cbx_fecha_salida_estado" onchange="checkbox_actualidad_est_lab();">
                                            <label class="form-check-label text-xs fw-bold text-primary" for="cbx_fecha_salida_estado">Indefinido</label>
                                        </div>
                                    </div>
                                    <label class="error" style="display: none;" for="txt_fecha_salida_estado"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-7 mb-2">Tipo de cambio / Novedad </label>
                            <div class="d-flex flex-wrap gap-3 p-2 border rounded bg-white">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cambio" id="radio_ninguno" value="ninguno" checked>
                                    <label class="form-check-label fs-7" for="radio_ninguno">NINGUNO</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cambio" id="radio_baja" value="baja">
                                    <label class="form-check-label fs-7 text-danger" for="radio_baja">DADO DE BAJA</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cambio" id="radio_recategorizacion" value="recategorizacion">
                                    <label class="form-check-label fs-7 text-success" for="radio_recategorizacion">RECATEGORIZACIÓN</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_estado_laboral" onclick="delete_datos_estado_laboral();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_estado_laboral_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_estado_laboral" onclick="if(validar_fechas_est_lab()) { insertar_editar_estado_laboral(); }">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
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
        agregar_asterisco_campo_obligatorio('txt_remuneracion');
        agregar_asterisco_campo_obligatorio('txt_fecha_contratacion_estado');
        agregar_asterisco_campo_obligatorio('txt_fecha_salida_estado');
        agregar_asterisco_campo_obligatorio('rbx_radio_baja');

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

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
                txt_remuneracion: {
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
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                    $element.next(".select2-container").find(".select2-selection").removeClass(
                        "is-valid").addClass("is-invalid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                    $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass(
                        "is-valid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                    $element.next(".select2-container").find(".select2-selection").removeClass(
                        "is-invalid").addClass("is-valid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, marcar todo el grupo como válido
                    $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass(
                        "is-valid");
                } else {
                    // Para otros elementos normales
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });
    })
</script>