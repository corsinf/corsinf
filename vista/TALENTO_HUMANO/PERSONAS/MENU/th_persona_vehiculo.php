<script>
    $(document).ready(function() {
        cargar_datos_vehiculos(<?= $id_persona ?>);
        cargar_selects_vehiculos();
    });

    function cargar_selects_vehiculos() {
        url_tipoVehiculoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_vehiculoC.php?buscar=true';
        cargar_select2_url('ddl_tipo_vehiculo', url_tipoVehiculoC, '', '#modal_agregar_vehiculo');
    }

    // Cargar datos de vehículos
    function cargar_datos_vehiculos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_vehiculosC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_vehiculos').html(response);
            }
        });
    }

    function cargar_datos_modal_vehiculo(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_vehiculosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_placa_original').val(response[0].th_per_veh_placa_original);
                $('#ddl_tipo_vehiculo').append($('<option>', {
                    value: response[0].id_vehiculo,
                    text: response[0].tipo_vehiculo_descripcion,
                    selected: true
                }));
                $('#txt_vehiculo_id').val(response[0]._id);
                $('#txt_nombre_propietario').val(response[0].th_per_veh_nombre_propietario);
            }
        });
    }

    function insertar_editar_vehiculo() {
        var txt_placa_original = $('#txt_placa_original').val();
        var txt_nombre_propietario = $('#txt_nombre_propietario').val();
        var ddl_tipo_vehiculo = $('#ddl_tipo_vehiculo').val();
        var txt_id_persona = '<?= $id_persona ?>';
        var txt_id_vehiculo = $('#txt_vehiculo_id').val();

        var parametros_vehiculo = {
            '_id': txt_id_vehiculo,
            'txt_id_persona': txt_id_persona,
            'txt_placa_original': txt_placa_original,
            'txt_nombre_propietario': txt_nombre_propietario,
            'ddl_tipo_vehiculo': ddl_tipo_vehiculo,
        }

        if ($("#form_vehiculo").valid()) {
            insertar_vehiculo(parametros_vehiculo);
        }
    }

    function insertar_vehiculo(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_per_vehiculosC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_agregar_vehiculo').modal('hide');
                    cargar_datos_vehiculos(<?= $id_persona  ?>);
                    limpiar_campos_vehiculo_modal();
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    // Función para editar el registro de vehículo
    function abrir_modal_vehiculo(id) {
        cargar_datos_modal_vehiculo(id);
        $('#modal_agregar_vehiculo').modal('show');
        $('#lbl_titulo_vehiculo').html('Editar Vehículo');
        $('#btn_guardar_vehiculo').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_vehiculo').show();
    }

    function delete_datos_vehiculo() {
        id = $('#txt_vehiculo_id').val();
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_vehiculo(id);
            }
        })
    }

    function eliminar_vehiculo(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_per_vehiculosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_vehiculos(<?= $id_persona ?>);
                    limpiar_campos_vehiculo_modal();
                    $('#modal_agregar_vehiculo').modal('hide');
                }
            }
        });
    }

    function limpiar_campos_vehiculo_modal() {
        $('#txt_placa_original').val('');
        $('#ddl_tipo_vehiculo').val('').trigger('change');
        $('#txt_vehiculo_id').val('');

        // Limpiar validaciones
        $("#form_vehiculo").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');

        // Cambiar texto
        $('#lbl_titulo_vehiculo').html('Agregar Vehículo');
        $('#btn_guardar_vehiculo').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_vehiculo').hide();
    }
</script>

<div id="pnl_vehiculos">
</div>

<!-- Modal para agregar vehículo -->
<div class="modal" id="modal_agregar_vehiculo" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_vehiculo">Agregar Vehículo</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_vehiculo_modal();"></button>
            </div>

            <!-- Modal body -->
            <form id="form_vehiculo">
                <input type="text" id="txt_vehiculo_id" hidden>
                <div class="modal-body">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_tipo_vehiculo" class="form-label form-label-sm">
                                Tipo de Vehículo
                            </label>
                            <select class="form-select form-select-sm mb-2"
                                name="ddl_tipo_vehiculo"
                                id="ddl_tipo_vehiculo">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_nombre_propietario" class="form-label form-label-sm">
                                Nombre del Propietario
                                <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" title="Si no es el propietario, indique el nombre del titular registrado."></i>
                            </label>

                            <input type="text"
                                class="form-control form-control-sm"
                                name="txt_nombre_propietario"
                                id="txt_nombre_propietario"
                                maxlength="100"
                                placeholder="Ingrese el nombre completo" oninput="texto_mayusculas(this);">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_placa_original" class="form-label form-label-sm">Placa Original </label>
                            <input type="text"
                                class="form-control form-control-sm text-uppercase"
                                name="txt_placa_original"
                                id="txt_placa_original"
                                maxlength="20"
                                placeholder="Ej: ABC-1234">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <small class="text-muted">
                                <i class="bx bx-info-circle"></i>
                                La placa síntesis se generará automáticamente
                            </small>
                        </div>
                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_vehiculo" onclick="insertar_editar_vehiculo();">
                        <i class="bx bx-save"></i>Agregar
                    </button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_vehiculo" onclick="delete_datos_vehiculo();">
                        <i class="bx bx-trash"></i>Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_tipo_vehiculo');
        agregar_asterisco_campo_obligatorio('txt_nombre_propietario');
        agregar_asterisco_campo_obligatorio('txt_placa_original');

        // Validación Vehículo
        $("#form_vehiculo").validate({
            rules: {
                ddl_tipo_vehiculo: {
                    required: true,
                },
                txt_placa_original: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
            },
            messages: {
                ddl_tipo_vehiculo: {
                    required: "Por favor seleccione el tipo de vehículo",
                },
                txt_placa_original: {
                    required: "Por favor ingrese la placa del vehículo",
                    minlength: "La placa debe tener al menos 3 caracteres",
                    maxlength: "La placa no puede exceder 20 caracteres"
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

        // Convertir a mayúsculas automáticamente
        $('#txt_placa_original').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
</script>

<style>
    /* Tarjeta estilo "List Item" */
    .custom-card-compact {
        border-radius: 10px;
        transition: all 0.2s ease;
        background: #ffffff;
        border: 1px solid #edf2f7 !important;
    }

    .custom-card-compact:hover {
        border-color: #cbd5e0 !important;
        background: #f8fafc;
    }

    /* Icono pequeño */
    .mini-status-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        /* Evita que se encoja */
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .bg-primary-soft {
        background-color: #eef2ff;
    }

    /* Estilo de Placas tipo etiqueta */
    .badge-plate {
        font-family: 'Monaco', 'Consolas', monospace;
        font-size: 0.7rem;
        padding: 2px 6px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 4px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }

    .badge-plate-alt {
        font-family: 'Monaco', 'Consolas', monospace;
        font-size: 0.7rem;
        padding: 2px 6px;
        background: #ffffff;
        color: #94a3b8;
        border-radius: 4px;
        border: 1px dashed #cbd5e0;
    }

    /* Botón de edición minimalista */
    .btn-edit-minimal {
        width: 30px;
        height: 30px;
        min-width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #64748b;
        transition: all 0.2s;
    }

    .btn-edit-minimal:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: rotate(15deg);
    }
</style>