<script>
    $(document).ready(function() {

        cargar_datos_licencias_transportes(<?= $id_persona ?>);
        cargar_selects_licencias_transportes();

        $("input[name='txt_fecha_expedicion']").on("blur", function() {
            if (!verificar_fecha_inicio_fecha_fin('txt_fecha_expedicion', 'txt_fecha_vencimiento')) return;
        });
        $("input[name='txt_fecha_vencimiento']").on("blur", function() {
            if (!verificar_fecha_inicio_fecha_fin('txt_fecha_expedicion', 'txt_fecha_vencimiento')) return;
        });

    });

    function cargar_selects_licencias_transportes() {
        let url_licencias_transportesC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_licencia_transporteC.php?buscar_tt_hh=true&id_persona=<?= $id_persona ?>';
        cargar_select2_url('ddl_licencia_transporte', url_licencias_transportesC, '');

        let url_licencias_transportes_estadosC = '../controlador/TALENTO_HUMANO/th_per_licencias_transportesC.php?buscar_estados_licencias=true';
        cargar_select2_url('ddl_estado_licencia', url_licencias_transportes_estadosC, '');
    }

    function cargar_datos_licencias_transportes(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_licencias_transportesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_licencias_transportes').html(response);
            }
        });
    }

    function cargar_datos_modal_licencias_transportes(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_licencias_transportesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                let datos = response[0];

                $('#txt_id_licencia_transporte').val(datos._id);
                $('#txt_numero_licencia').val(datos.numero_licencia);
                $('#txt_autoridad_emisora').val(datos.autoridad_emisora);
                $('#txt_escuela').val(datos.escuela);
                $('#txt_fecha_expedicion').val(datos.fecha_expedicion);
                $('#txt_fecha_vencimiento').val(datos.fecha_vencimiento);

                // Cargar Banco
                $('#ddl_licencia_transporte').append($('<option>', {
                    value: datos.id_licencia_transporte,
                    text: datos.tipo_licencia_transporte,
                    selected: true
                }));

                // Cargar Banco
                $('#ddl_estado_licencia').append($('<option>', {
                    value: datos.estado_licencia,
                    text: datos.estado_licencia,
                    selected: true
                }));
            }
        });
    }

    function insertar_editar_licencias_transportes() {

        if ($("#form_licencias_transportes").valid()) {

            let form_data = document.getElementById('form_licencias_transportes');
            let parametros = new FormData(form_data);

            parametros.append('per_id', '<?= $id_persona ?>');

            insertar_licencias_transportes(parametros);
        }
    }


    function insertar_licencias_transportes(parametros) {
        $.ajax({
            data: parametros,
            url: '../controlador/TALENTO_HUMANO/th_per_licencias_transportesC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_agregar_licencias_transportes').modal('hide');
                    cargar_datos_licencias_transportes(<?= $id_persona ?>);
                    limpiar_campos_licencias_transportes_modal();
                } else if (response == -2) {
                    Swal.fire(
                        'Atención',
                        'La persona ya está asignada a esta licencias_transportes.',
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

    function abrir_modal_licencias_transportes(id) {
        if (id === '') {
            limpiar_campos_licencias_transportes_modal();
            $('#modal_agregar_licencias_transportes').modal('show');
            $('#lbl_titulo_licencias_transportes').html('Agregar Lincencia de Transporte');
            $('#btn_guardar_licencias_transportes').html('<i class="bx bx-save"></i> Agregar');
            $('#btn_eliminar_licencias_transportes').hide();
        } else {
            cargar_datos_modal_licencias_transportes(id);
            $('#modal_agregar_licencias_transportes').modal('show');
            $('#lbl_titulo_licencias_transportes').html('Editar Lincencia de Transporte');
            $('#btn_guardar_licencias_transportes').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_licencias_transportes').show();
        }
    }

    function delete_datos_licencias_transportes() {
        let id = $('#txt_id_licencia_transporte').val();

        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_licencias_transportes(id);
            }
        });
    }

    function eliminar_licencias_transportes(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_per_licencias_transportesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro eliminado.', 'success');
                    $('#modal_agregar_licencias_transportes').modal('hide');
                    cargar_datos_licencias_transportes(<?= $id_persona ?>);
                    limpiar_campos_licencias_transportes_modal();
                }
            }
        });
    }

    function limpiar_campos_licencias_transportes_modal() {
        $('#form_licencias_transportes').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');

        // Resetear IDs y Títulos
        $('#txt_id_licencia_transporte').val('');
        $('#lbl_titulo_licencias_transportes').html('Agregar Lincencia de Transporte');
        $('#btn_guardar_licencias_transportes').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_licencias_transportes').hide();


        // Limpiar Inputs de texto
        $('#txt_numero_licencia').val('');
        $('#txt_autoridad_emisora').val('');
        $('#txt_escuela').val('');
        $('#txt_fecha_expedicion').val('');
        $('#txt_fecha_vencimiento').val('');

        // Limpiar Selects (vaciamos y disparamos el cambio)
        $('#ddl_licencia_transporte').val(null).trigger('change');
        $('#ddl_estado_licencia').val(null).trigger('change');


        $('.select2-selection').removeClass('is-valid is-invalid');
        $('.select2-validation').each(function() {
            $('label.error[for="' + this.id + '"]').hide();
        });
    }
</script>
<div id="pnl_licencias_transportes"></div>

<div class="modal fade" id="modal_agregar_licencias_transportes" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary">
                        <i class='bx bx-car me-2'></i>Licencia de Transporte
                    </h5>
                    <small class="text-muted">Registra los detalles de tu habilitación para conducir vehículos.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_licencias_transportes_modal()"></button>
            </div>

            <form id="form_licencias_transportes" class="needs-validation">
                <div class="modal-body">

                    <input type="hidden" id="txt_id_licencia_transporte" name="txt_id_licencia_transporte">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="txt_numero_licencia" class="form-label fw-semibold fs-7">Número de Licencia </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-id-card'></i></span>
                                <input type="text" class="form-control" id="txt_numero_licencia" name="txt_numero_licencia" required placeholder="Ej: 1712345678">
                            </div>
                            <label class="error" style="display: none;" for="txt_numero_licencia"></label>

                        </div>
                        <div class="col-md-6">
                            <label for="ddl_licencia_transporte" class="form-label fw-semibold fs-7">Tipo de Licencia de Transporte </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_licencia_transporte" name="ddl_licencia_transporte" required style="width: 100%;">
                            </select>
                            <label class="error" style="display: none;" for="ddl_licencia_transporte"></label>

                        </div>

                        <div class="col-md-6">
                            <label for="ddl_estado_licencia" class="form-label fw-semibold fs-7">Estado </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_estado_licencia" name="ddl_estado_licencia" required style="width: 100%;">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_estado_licencia"></label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="txt_autoridad_emisora" class="form-label fw-semibold fs-7">Autoridad Emisora </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-buildings'></i></span>
                                <input type="text" class="form-control" id="txt_autoridad_emisora" name="txt_autoridad_emisora" placeholder="Ej: ANT, CTE...">
                            </div>
                            <label class="error" style="display: none;" for="txt_autoridad_emisora"></label>
                        </div>
                        <div class="col-md-6">
                            <label for="txt_escuela" class="form-label fw-semibold fs-7">Escuela de Conducción </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-buildings'></i></span>
                                <input type="text" class="form-control" id="txt_escuela" name="txt_escuela" placeholder="Nombre de la institución">
                            </div>
                            <label class="error" style="display: none;" for="txt_escuela"></label>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed mb-2">
                        <h6 class="text-muted fs-7 mb-2 fw-bold text-uppercase ls-1">Vigencia de la Licencia </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="txt_fecha_expedicion" class="form-label fs-7 mb-1">Fecha de Expedición </label>
                                <input type="date" class="form-control form-control-sm" id="txt_fecha_expedicion" name="txt_fecha_expedicion" required>
                            </div>
                            <div class="col-md-6">
                                <label for="txt_fecha_vencimiento" class="form-label fs-7 mb-1">Fecha de Vencimiento </label>
                                <input type="date" class="form-control form-control-sm" id="txt_fecha_vencimiento" name="txt_fecha_vencimiento" required onblur="">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display:none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_licencias_transportes" onclick="delete_datos_licencias_transportes();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_licencias_transportes_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_licencias_transportes" onclick="insertar_editar_licencias_transportes();">
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

        agregar_asterisco_campo_obligatorio('txt_numero_licencia');
        agregar_asterisco_campo_obligatorio('ddl_licencia_transporte');
        agregar_asterisco_campo_obligatorio('ddl_estado_licencia');
        agregar_asterisco_campo_obligatorio('txt_autoridad_emisora');
        agregar_asterisco_campo_obligatorio('txt_escuela');
        agregar_asterisco_campo_obligatorio('txt_fecha_expedicion');
        agregar_asterisco_campo_obligatorio('txt_fecha_vencimiento');


        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });


        $("#form_licencias_transportes").validate({
            rules: {
                txt_numero_licencia: {
                    required: true
                },
                ddl_licencia_transporte: {
                    required: true
                },
                ddl_estado_licencia: {
                    required: true
                },
                txt_autoridad_emisora: {
                    required: true
                },
                txt_escuela: {
                    required: true
                },
                txt_fecha_expedicion: {
                    required: true
                },
                txt_fecha_vencimiento: {
                    required: true
                },
            },
            messages: {
                txt_numero_licencia: {
                    required: "Por favor ingrese el número de licencia."
                },
                ddl_licencia_transporte: {
                    required: "Por favor seleccione el tipo de licencia."
                },
                ddl_estado_licencia: {
                    required: "Por favor seleccione el estado de la licencia."
                },
                txt_autoridad_emisora: {
                    required: "Por favor ingrese la autoridad emisora."
                },
                txt_escuela: {
                    required: "Por favor ingrese la escuela de conducir."
                },
                txt_fecha_expedicion: {
                    required: "Por favor ingrese la fecha de expedición."
                },
                txt_fecha_vencimiento: {
                    required: "Por favor ingrese la fecha de vencimiento."
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
    });
</script>