<script>
    $(document).ready(function() {
        cargar_datos_parientes(<?= $id_persona ?>);
        cargar_select_parientes(<?= $id_persona ?>)
    });

    function cargar_selects_parientes() {
        url_parentescoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_parentescoC.php?buscar=true';
        cargar_select2_url('ddl_parentesco', url_parentescoC, '', '#modal_parientes');
    }


    function cargar_select_parientes(id_persona) {
        // Si select2 ya está inicializado, destruirlo
        if ($('#ddl_parentesco').hasClass("select2-hidden-accessible")) {
            $('#ddl_parentesco').select2('destroy');
        }

        $('#ddl_parentesco').select2({
            dropdownParent: $('#modal_parientes'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_parentescoC.php?buscar_parientes=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        th_per_id: id_persona
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            minimumInputLength: 0,
            placeholder: "Seleccione un requisito",
            language: {
                noResults: function() {
                    return "No hay requisitos disponibles para asignar";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
    }

    function cargar_datos_parientes(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_parientes').html(response);
            }
        });
    }

    function cargar_datos_modal_parientes(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#ddl_parentesco').append($('<option>', {
                        value: response[0].id_parentesco,
                        text: response[0].parentesco_nombre,
                        selected: true
                    })).trigger('change');

                    $('#txt_nombres_pariente').val(response[0].nombres);
                    $('#txt_apellidos_pariente').val(response[0].apellidos);
                    $('#txt_telefono_pariente').val(response[0].numero_telefono);
                    $('#txt_fecha_nacimiento_pariente').val(response[0].fecha_nacimiento);
                    $('#chk_contacto_emergencia').prop('checked', response[0].contacto_emergencia == 1);
                    $('#txt_pariente_id').val(response[0]._id);

                    if (
                        response[0].fecha_nacimiento &&
                        response[0].fecha_nacimiento !== '1900-01-01' &&
                        response[0].fecha_nacimiento !== '1900-01-01 00:00:00'
                    ) {
                        $('#txt_fecha_nacimiento_pariente').val(response[0].fecha_nacimiento);
                        calcular_edad_pariente();
                    } else {
                        $('#txt_fecha_nacimiento_pariente').val('');
                        $('#txt_edad_pariente').val(''); // si tienes input de edad
                    }

                }
            }
        });
    }

    function validar_parentesco_seleccionado() {
        var id_parentesco = $('#ddl_parentesco').val();

        if (!id_parentesco) {
            return;
        }

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?obtener_info_parentesco=true',
            type: 'post',
            data: {
                id_parentesco: id_parentesco
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    var requiere_fec_nac = response[0].requiere_fec_nac;

                    if (requiere_fec_nac == 1) {
                        // Hacer obligatoria la fecha de nacimiento
                        $('#txt_fecha_nacimiento_pariente').rules('add', {
                            required: true,
                            messages: {
                                required: "La fecha de nacimiento es obligatoria para este parentesco"
                            }
                        });
                        $('#lbl_fecha_nacimiento_pariente').addClass('campo-obligatorio');

                        // Agregar asterisco si no existe
                        if ($('#lbl_fecha_nacimiento_pariente .text-danger').length == 0) {
                            $('#lbl_fecha_nacimiento_pariente').append(' <span class="text-danger">*</span>');
                        }
                    } else {
                        // Hacer opcional la fecha de nacimiento
                        $('#txt_fecha_nacimiento_pariente').rules('remove', 'required');
                        $('#lbl_fecha_nacimiento_pariente').removeClass('campo-obligatorio');

                        // Remover asterisco
                        $('#lbl_fecha_nacimiento_pariente .text-danger').remove();
                    }
                }
            }
        });
    }

    function calcular_edad_pariente() {
        var fecha_nacimiento = $('#txt_fecha_nacimiento_pariente').val();
        if (fecha_nacimiento) {
            var hoy = new Date();
            var nacimiento = new Date(fecha_nacimiento);
            var edad = hoy.getFullYear() - nacimiento.getFullYear();
            var mes = hoy.getMonth() - nacimiento.getMonth();

            if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
                edad--;
            }

            $('#txt_edad_pariente').val(edad >= 0 ? edad : 0);
        } else {
            $('#txt_edad_pariente').val('');
        }
    }

    function insertar_editar_parientes() {
        var ddl_parentesco = $('#ddl_parentesco').val();
        var txt_nombres_pariente = $('#txt_nombres_pariente').val();
        var txt_apellidos_pariente = $('#txt_apellidos_pariente').val();
        var txt_telefono_pariente = $('#txt_telefono_pariente').val();
        var txt_fecha_nacimiento_pariente = $('#txt_fecha_nacimiento_pariente').val();
        var chk_contacto_emergencia = $('#chk_contacto_emergencia').is(':checked') ? 1 : 0;
        var per_id = '<?= $id_persona ?>';
        var txt_pariente_id = $('#txt_pariente_id').val();

        var parametros_parientes = {
            'per_id': per_id,
            'ddl_parentesco': ddl_parentesco,
            'txt_nombres': txt_nombres_pariente,
            'txt_apellidos': txt_apellidos_pariente,
            'txt_telefono': txt_telefono_pariente,
            'txt_fecha_nacimiento': txt_fecha_nacimiento_pariente,
            'chk_contacto_emergencia': chk_contacto_emergencia,
            '_id': txt_pariente_id,
        }

        if ($("#form_parientes").valid()) {
            insertar_parientes(parametros_parientes);
        }
    }

    function insertar_parientes(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_parientes').modal('hide');
                    cargar_datos_parientes(<?= $id_persona ?>);
                    limpiar_campos_parientes_modal();
                } else if (response == -2) {
                    Swal.fire('', 'Ya se alcanzó el límite permitido para este tipo de parentesco.', 'warning');
                } else if (response == -4) {
                    Swal.fire('', 'Parentesco no válido.', 'warning');
                } else if (response == -3) {
                    Swal.fire('', 'La fecha de nacimiento es obligatoria para este tipo de parentesco.', 'warning');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function abrir_modal_pariente(id) {
        limpiar_campos_parientes_modal();
        cargar_datos_modal_parientes(id);
        $('#modal_parientes').modal('show');
        $('#lbl_titulo_parientes').html('Editar Referencia Personal');
        $('#btn_guardar_parientes').html('<i class="bx bx-save"></i> Editar');
        $('#btn_eliminar_parientes').show();
    }

    function abrir_modal_nuevo_pariente() {
        limpiar_campos_parientes_modal();
        $('#modal_parientes').modal('show');
        $('#lbl_titulo_parientes').html('Agregar Referencia Personal');
        $('#btn_guardar_parientes').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_parientes').hide();
    }

    function delete_datos_parientes() {
        id = $('#txt_pariente_id').val();
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
                eliminar_parientes(id);
            }
        })
    }

    function eliminar_parientes(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_parientes').modal('hide');
                    cargar_datos_parientes(<?= $id_persona ?>);
                    limpiar_campos_parientes_modal();
                }
            }
        });
    }

    function limpiar_campos_parientes_modal() {
        $('#form_parientes').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_parentesco').val('').trigger('change');
        $('#txt_nombres_pariente').val('');
        $('#txt_apellidos_pariente').val('');
        $('#txt_telefono_pariente').val('');
        $('#txt_fecha_nacimiento_pariente').val('');
        $('#txt_edad_pariente').val('');
        $('#chk_contacto_emergencia').prop('checked', false);
        $('#txt_pariente_id').val('');
        $('#lbl_titulo_parientes').html('Agregar Referencia Personal');
        $('#btn_guardar_parientes').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_parientes').hide();

        // Remover validación dinámica y asterisco
        $('#txt_fecha_nacimiento_pariente').rules('remove', 'required');
        $('#lbl_fecha_nacimiento_pariente').removeClass('campo-obligatorio');
        $('#lbl_fecha_nacimiento_pariente .text-danger').remove();
    }
</script>

<div id="pnl_parientes">
</div>

<!-- Modal Referencias Personales -->
<div class="modal fade" id="modal_parientes" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_parientes">
                        <i class='bx bx-group me-2'></i>Referencia Personal / Familiar
                    </h5>
                    <small class="text-muted">Registra a tus parientes directos o contactos de confianza.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_parientes_modal()"></button>
            </div>

            <form id="form_parientes" class="needs-validation">
                <input type="hidden" name="txt_pariente_id" id="txt_pariente_id">

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="txt_nombres_pariente" class="form-label fw-semibold fs-7">Nombres </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-user'></i></span>
                                <input type="text" class="form-control" name="txt_nombres_pariente" id="txt_nombres_pariente" required placeholder="Nombres completos">
                            </div>
                            <label class="error" style="display: none;" for="txt_nombres_pariente"></label>

                        </div>
                        <div class="col-md-6">
                            <label for="txt_apellidos_pariente" class="form-label fw-semibold fs-7">Apellidos </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-user'></i></span>
                                <input type="text" class="form-control" name="txt_apellidos_pariente" id="txt_apellidos_pariente" required placeholder="Apellidos completos">
                            </div>
                            <label class="error" style="display: none;" for="txt_apellidos_pariente"></label>

                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ddl_parentesco" class="form-label fw-semibold fs-7">Parentesco </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_parentesco" name="ddl_parentesco" onchange="validar_parentesco_seleccionado()" required style="width: 100%;">
                                <option selected disabled value="">-- Seleccione un Parentesco --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_parentesco"></label>
                            
                        </div>
                        <div class="col-md-6">
                            <label for="txt_telefono_pariente" class="form-label fw-semibold fs-7">Número de Teléfono </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-phone'></i></span>
                                <input type="text" class="form-control solo_numeros_int" name="txt_telefono_pariente" id="txt_telefono_pariente" placeholder="Ej: 0987654321">
                            </div>
                            <label class="error" style="display: none;" for="txt_telefono_pariente"></label>

                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed mb-3">
                        <h6 class="text-muted fs-7 mb-2 fw-bold text-uppercase ls-1">Datos Demográficos </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="txt_fecha_nacimiento_pariente" class="form-label fs-7 mb-1">Fecha de Nacimiento </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_nacimiento_pariente" id="txt_fecha_nacimiento_pariente" onchange="calcular_edad_pariente()">
                            </div>
                            <div class="col-md-6">
                                <label for="txt_edad_pariente" class="form-label fs-7 mb-1">Edad Calculada</label>
                                <input type="number" class="form-control form-control-sm bg-white" name="txt_edad_pariente" id="txt_edad_pariente" readonly placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" id="chk_contacto_emergencia" name="chk_contacto_emergencia">
                                <label class="form-check-label fw-bold text-primary" for="chk_contacto_emergencia">
                                    ¿Es contacto de emergencia?
                                </label>
                            </div>
                            <div class="form-text text-xs ms-0 mt-1">
                                <i class='bx bx-info-circle'></i> Si se marca, este pariente será el contacto principal en caso de incidentes.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_parientes" onclick="delete_datos_parientes();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_parientes_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_parientes" onclick="insertar_editar_parientes();">
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
        agregar_asterisco_campo_obligatorio('ddl_parentesco');
        agregar_asterisco_campo_obligatorio('txt_nombres_pariente');
        agregar_asterisco_campo_obligatorio('txt_apellidos_pariente');
        agregar_asterisco_campo_obligatorio('txt_telefono_pariente');
        agregar_asterisco_campo_obligatorio('txt_fecha_nacimiento_pariente');
        agregar_asterisco_campo_obligatorio('txt_edad_pariente');
        agregar_asterisco_campo_obligatorio('chk_contacto_emergencia');

        $("#form_parientes").validate({
            rules: {
                ddl_parentesco: {
                    required: true,
                },
                txt_nombres_pariente: {
                    required: true,
                },
                txt_apellidos_pariente: {
                    required: true,
                },
                txt_telefono_pariente: {
                    required: false,
                    digits: true,
                    minlength: 7,
                    maxlength: 10
                }
            },
            messages: {
                ddl_parentesco: {
                    required: "Por favor seleccione el parentesco",
                },
                txt_nombres_pariente: {
                    required: "Por favor ingrese los nombres",
                },
                txt_apellidos_pariente: {
                    required: "Por favor ingrese los apellidos",
                },
                txt_telefono_pariente: {
                    digits: "Ingrese solo números",
                    minlength: "Debe tener al menos 7 dígitos",
                    maxlength: "No debe exceder 10 dígitos"
                }

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

<style>
    /* Estilo para el botón de llamada de emergencia */
    .btn-call-emergency {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background-color: #ffeded;
        color: #dc3545;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-call-emergency:hover {
        background-color: #dc3545;
        color: white;
        transform: scale(1.1);
    }

    /* Avatar más pequeño para ahorrar espacio */
    .avatar-mini {
        width: 34px;
        height: 34px;
        font-size: 1rem;
    }

    .item-pariente:hover {
        background-color: #fcfcfc;
    }
</style>