<script>
    $(document).ready(function() {

        cargar_datos_bancos(<?= $id_persona ?>);
        cargar_selects_bancos();

    });

    function cargar_selects_bancos() {
        let url_bancosC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_bancosC.php?buscar_tt_hh=true&id_persona=<?= $id_persona ?>';
        cargar_select2_url('ddl_bancos', url_bancosC, '');

        let url_tipo_cuenta_bancosC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_cuenta_bancoC.php?buscar=true';
        cargar_select2_url('ddl_tipo_cuenta', url_tipo_cuenta_bancosC, '');
    }

    function cargar_datos_bancos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_bancosC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_bancos').html(response);
            }
        });
    }

    function cargar_datos_modal_bancos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_bancosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                let datos = response[0];

                // Cargar Banco
                $('#ddl_bancos').append($('<option>', {
                    value: datos.id_banco,
                    text: datos.banco_descripcion,
                    selected: true
                }));

                // Cargar Tipo de Cuenta
                $('#ddl_tipo_cuenta').append($('<option>', {
                    value: datos.id_tipo_cuenta,
                    text: datos.tipo_cuenta_descripcion,
                    selected: true
                }));

                // Cargar campos de texto
                $('#txt_bancos_id').val(datos._id);
                $('#txt_numero_cuenta').val(datos.th_ban_numero_cuenta);

                // Cargar checkbox (1 marcado, 0 desmarcado)
                $('#cbx_es_principal').prop('checked', datos.es_principal == "1");
            }
        });
    }
    // function insertar_editar_bancos() {

    //     let parametros = {
    //         per_id: '<?= $id_persona ?>',
    //         ddl_bancos: $('#ddl_bancos').val(),
    //         _id: $('#txt_bancos_id').val()
    //     };

    //     if ($("#form_bancos").valid()) {
    //         insertar_bancos(parametros);
    //     }


    // }

    function insertar_editar_bancos() {

        if ($("#form_bancos").valid()) {

            let form_data = document.getElementById('form_bancos');
            let parametros = new FormData(form_data);

            parametros.append('per_id', '<?= $id_persona ?>');

            let cbx_es_principal = $('#cbx_es_principal').is(':checked') ? 1 : 0;
            parametros.set('cbx_es_principal', cbx_es_principal);

            insertar_bancos(parametros);
        }
    }


    function insertar_bancos(parametros) {
        $.ajax({
            data: parametros,
            url: '../controlador/TALENTO_HUMANO/th_per_bancosC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_agregar_bancos').modal('hide');
                    cargar_datos_bancos(<?= $id_persona ?>);
                    limpiar_campos_bancos_modal();
                } else if (response == -2) {
                    Swal.fire(
                        'Atención',
                        'La persona ya está asignada a esta Bancos.',
                        'warning'
                    );
                } else {

                    Swal.fire(
                        'Error',
                        'No se pudo realizar la operación.',
                        'error'
                    );
                }
            }
        });
    }

    function abrir_modal_bancos(id) {
        if (id === '') {
            limpiar_campos_bancos_modal();
            $('#modal_agregar_bancos').modal('show');
            $('#lbl_titulo_bancos').html('Agregar Bancos');
            $('#btn_guardar_bancos').html('<i class="bx bx-save"></i> Agregar');
            $('#btn_eliminar_bancos').hide();
        } else {
            cargar_datos_modal_bancos(id);
            $('#modal_agregar_bancos').modal('show');
            $('#lbl_titulo_bancos').html('Editar Bancos');
            $('#btn_guardar_bancos').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_bancos').show();
        }
    }

    function delete_datos_bancos() {
        let id = $('#txt_bancos_id').val();

        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_bancos(id);
            }
        });
    }

    function eliminar_bancos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_per_bancosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro eliminado.', 'success');
                    $('#modal_agregar_bancos').modal('hide');
                    cargar_datos_bancos(<?= $id_persona ?>);
                    limpiar_campos_bancos_modal();
                }
            }
        });
    }

    function limpiar_campos_bancos_modal() {
        $('#form_bancos').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');

        // Resetear IDs y Títulos
        $('#txt_bancos_id').val('');
        $('#lbl_titulo_bancos').html('Agregar Bancos');
        $('#btn_guardar_bancos').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_bancos').hide();

        // Limpiar Inputs de texto
        $('#txt_numero_cuenta').val('');

        // Limpiar Selects (vaciamos y disparamos el cambio)
        $('#ddl_bancos').val(null).trigger('change');
        $('#ddl_tipo_cuenta').val(null).trigger('change');

        // Desmarcar el checkbox de Principal
        $('#cbx_es_principal').prop('checked', false);

        $('.select2-selection').removeClass('is-valid is-invalid');
        $('.select2-validation').each(function() {
            $('label.error[for="' + this.id + '"]').hide();
        });
    }
</script>
<div id="pnl_bancos"></div>

<div class="modal fade" id="modal_agregar_bancos" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_bancos">
                        <i class='bx bx-credit-card-front me-2'></i>Información Bancaria
                    </h5>
                    <small class="text-muted">Registra las cuentas para transferencias y pagos.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_bancos_modal()"></button>
            </div>

            <form id="form_bancos" class="needs-validation">
                <div class="modal-body">

                    <input type="hidden" id="txt_bancos_id" name="txt_bancos_id">

                    <div class="row mb-col mb-3">
                        <div class="col-md-7">
                            <label for="ddl_bancos" class="form-label fw-semibold fs-7">Institución Financiera </label>
                            <div class="input-group input-group-sm">
                                <select class="form-select select2-validation" id="ddl_bancos" name="ddl_bancos" required style="width: 85%;">
                                </select>
                            </div>
                            <label class="error" style="display: none;" for="ddl_bancos"></label>
                        </div>

                        <div class="col-md-5">
                            <label for="ddl_tipo_cuenta" class="form-label fw-semibold fs-7">Tipo de Cuenta </label>
                            <div class="input-group input-group-sm">
                                <select class="form-select select2-validation" id="ddl_tipo_cuenta" name="ddl_tipo_cuenta" required>
                                </select>
                            </div>
                            <label class="error" style="display: none;" for="ddl_tipo_cuenta"></label>
                        </div>
                    </div>

                    <div class="row mb-col mb-3 align-items-end">
                        <div class="col-md-8">
                            <label for="txt_numero_cuenta" class="form-label fw-semibold fs-7">Número de Cuenta </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class='bx bx-hash'></i></span>
                                <input type="text"
                                    class="form-control form-control-sm no_caracteres"
                                    name="txt_numero_cuenta"
                                    id="txt_numero_cuenta"
                                    maxlength="100"
                                    placeholder="Ej: 2200456..."
                                    oninput="texto_mayusculas(this);">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-2 border rounded bg-light bg-opacity-50">
                                <label class="form-label fw-semibold fs-7 mb-1 d-block text-center">¿Es Principal? </label>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input type="checkbox" class="form-check-input" name="cbx_es_principal" id="cbx_es_principal">
                                    <label class="form-check-label fs-7 ms-2 fw-bold text-primary" for="cbx_es_principal">SÍ</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-text text-xs text-muted">
                        <i class='bx bx-info-circle'></i> Asegúrese de que el número de cuenta pertenezca al titular.
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display:none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_bancos" onclick="delete_datos_bancos();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_bancos_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_bancos" onclick="insertar_editar_bancos();">
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

        agregar_asterisco_campo_obligatorio('ddl_bancos');
        agregar_asterisco_campo_obligatorio('ddl_tipo_cuenta');
        agregar_asterisco_campo_obligatorio('txt_numero_cuenta');

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });


        $("#form_bancos").validate({
            rules: {
                ddl_bancos: {
                    required: true
                },
                ddl_tipo_cuenta: {
                    required: true
                },
                txt_numero_cuenta: {
                    required: true
                },
            },
            messages: {
                ddl_bancos: {
                    required: "Por favor seleccione una Bancos"
                },
                ddl_tipo_cuenta: {
                    required: "Por favor seleccione un Tipo de Cuenta"
                },
                txt_numero_cuenta: {
                    required: "Por favor ingrese el número de cuenta"
                }
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
    });
</script>