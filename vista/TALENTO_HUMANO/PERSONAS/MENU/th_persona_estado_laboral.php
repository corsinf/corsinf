<script>
    $(document).ready(function() {

        cargar_datos_estado_laboral(<?= $_id ?>);

        cargar_selects();

    });

    function cargar_selects() {
        url_cargoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?buscar=true';
        cargar_select2_url('ddl_cargo', url_cargoC, '', '#modal_estado_laboral');
        url_seccionC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_seccionC.php?buscar=true';
        cargar_select2_url('ddl_seccion', url_seccionC, '', '#modal_estado_laboral');
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
                $('#pnl_estado_laboral').html(response);
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
                $('#ddl_estado_laboral').val(response[0].th_est_estado_laboral);
                $('#ddl_cargo').append($('<option>', {
                    value: response[0].id_cargo,
                    text: response[0].cargo_nombre,
                    selected: true
                }));
                $('#ddl_seccion').append($('<option>', {
                    value: response[0].id_seccion,
                    text: response[0].seccion_descripcion,
                    selected: true
                }));
                $('#txt_fecha_contratacion_estado').val(response[0].th_est_fecha_contratacion);
                $('#txt_fecha_salida_estado').val(response[0].th_est_fecha_salida);
                $('#txt_experiencia_estado_id').val(response[0]._id);

                ocultar_opciones_estado();
            }
        });
    }


    function insertar_editar_estado_laboral() {
        var ddl_estado_laboral = $('#ddl_estado_laboral').val();
        var ddl_cargo = $('#ddl_cargo').val();
        var ddl_seccion = $('#ddl_seccion').val();
        var txt_fecha_contratacion_estado = $('#txt_fecha_contratacion_estado').val();
        var txt_fecha_salida_estado = $('#txt_fecha_salida_estado').val();
        var per_id = '<?= $_id ?>';
        var txt_experiencia_estado_id = $('#txt_experiencia_estado_id').val();

        var parametros_estado_laboral = {
            'per_id': per_id,
            'ddl_estado_laboral': ddl_estado_laboral,
            'ddl_cargo': ddl_cargo,
            'ddl_seccion': ddl_seccion,
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
                    cargar_datos_estado_laboral(<?= $_id ?>);
                    limpiar_campos_estado_laboral_modal();
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function ocultar_opciones_estado() {
        var select_opciones_estado = $('#ddl_estado_laboral');
        var valor_seleccionado = select_opciones_estado.val();

        $('#txt_fecha_contratacion_estado').prop('disabled', false);
        $('#txt_fecha_salida_estado').prop('disabled', false);

        if (valor_seleccionado === "Freelancer" || valor_seleccionado === "Autonomo") {
            $('#txt_fecha_contratacion_estado').prop('disabled', true);
            $('#txt_fecha_salida_estado').prop('disabled', true);
            $('#txt_fecha_contratacion_estado').val('');
            $('#txt_fecha_salida_estado').val('');
        }
    }

    function abrir_modal_estado_laboral(id) {
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
            text: "¿Esta seguro de eliminar este registro?",
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
                    cargar_datos_estado_laboral(<?= $_id ?>);
                    limpiar_campos_estado_laboral_modal();
                }
            }
        });
    }

    function limpiar_campos_estado_laboral_modal() {
        $('#form_estado_laboral').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_estado_laboral').val('');
        $('#ddl_cargo').val('');
        $('#ddl_seccion').val('');
        $('#txt_fecha_contratacion_estado').val('');
        $('#txt_fecha_salida_estado').val('');
        $('#txt_experiencia_estado_id').val('');
        $('#cbx_fecha_salida_estado').prop('checked', false);
        $('#txt_fecha_contratacion_estado').prop('disabled', false);
        $('#txt_fecha_salida_estado').prop('disabled', false);
        $('#lbl_titulo_estado_laboral').html('Agregar Estado Laboral');
        $('#btn_guardar_estado_laboral').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_estado_laboral').hide();
    }

    function validar_fechas_est_lab() {
        var fecha_inicio = $('#txt_fecha_contratacion_estado').val();
        var fecha_final = $('#txt_fecha_salida_estado').val();
        var estado = $('#ddl_estado_laboral').val();

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
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();

            var fecha_actual = year + '-' + mes + '-' + dia;
            $('#txt_fecha_salida_estado').val(fecha_actual);
            $('#txt_fecha_salida_estado').prop('disabled', true);
            $('#txt_fecha_salida_estado').rules("remove", "required");
            $('#txt_fecha_salida_estado').addClass('is-valid');
            $('#txt_fecha_salida_estado').removeClass('is-invalid');

        } else {
            if ($('#txt_fecha_salida_estado').prop('disabled')) {
                $('#txt_fecha_salida_estado').val('');
            }

            $('#txt_fecha_salida_estado').prop('disabled', false);
            $('#txt_fecha_salida_estado').rules("add", {
                required: true
            });
            $('#txt_fecha_salida_estado').removeClass('is-valid');
        }

        validar_fechas_est_lab();
    }
</script>

<div id="pnl_estado_laboral">
</div>

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
                            <label for="ddl_estado_laboral" class="form-label form-label-sm">Estado laboral:</label>
                            <select class="form-select form-select-sm" id="ddl_estado_laboral" name="ddl_estado_laboral" onchange="ocultar_opciones_estado();" required>
                                <option selected disabled value="">-- Seleccione un Estado Laboral --</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                                <option value="Prueba">En prueba</option>
                                <option value="Pasante">Pasante</option>
                                <option value="Freelancer">Freelancer</option>
                                <option value="Autonomo">Autónomo</option>
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
                        <div class="col-md-12">
                            <label for="ddl_seccion" class="form-label form-label-sm">Sección:</label>
                            <select class="form-select form-select-sm" id="ddl_seccion" name="ddl_seccion" required>
                                <option selected disabled value="">-- Seleccione una Sección --</option>
                            </select>
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
                                    <input class="form-check-input mt-0" type="checkbox" id="cbx_fecha_salida_estado" onchange="checkbox_actualidad_est_lab();" title="Marcar si trabaja actualmente">
                                    <label class="form-check-label ms-1" for="cbx_fecha_salida_estado" style="font-size: 0.8rem;">Actual</label>
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
                txt_fecha_contratacion_estado: {
                    required: function() {
                        var estado = $('#ddl_estado_laboral').val();
                        return estado !== "Freelancer" && estado !== "Autonomo";
                    }
                },
                txt_fecha_salida_estado: {
                    required: function() {
                        var estado = $('#ddl_estado_laboral').val();
                        return estado !== "Freelancer" && estado !== "Autonomo";
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