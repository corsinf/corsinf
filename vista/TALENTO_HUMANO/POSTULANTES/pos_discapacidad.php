<script>
    $(document).ready(function() {

        cargar_datos_discapacidad('<?= $id_postulante ?>');
        cargar_selects_discapacidad();
        $('#ddl_discapacidad').on('change', function() {
            let id_discapacidad = $(this).val();
            cargar_discapacidad_escalas(id_discapacidad);
            $('#ddl_discapacidad_escala').val('').trigger('change.select2');

        });
    });

    function cargar_selects_discapacidad() {
        let url = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_discapacidadC.php?buscar=true';
        cargar_select2_url('ddl_discapacidad', url, '', '#modal_agregar_discapacidad');
        let url_dis_porcentaje = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_discapacidad_porcentajeC.php?buscar=true';
        cargar_select2_url('ddl_discapacidad_porcentaje', url_dis_porcentaje, '', '#modal_agregar_discapacidad');
        let url_dis_gravedad = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_discapacidad_gravedadC.php?buscar=true';
        cargar_select2_url('ddl_discapacidad_gravedad', url_dis_gravedad, '', '#modal_agregar_discapacidad');
    }

    function cargar_discapacidad_escalas(id_discapacidad) {
        // Si select2 ya está inicializado, destruirlo
        if ($('#ddl_discapacidad_escala').hasClass("select2-hidden-accessible")) {
            $('#ddl_discapacidad_escala').select2('destroy');
        }

        $('#ddl_discapacidad_escala').select2({
            dropdownParent: $('#modal_agregar_discapacidad'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_discapacidad_escalaC.php?buscar_discapacidad_escala=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        id_discapacidad: id_discapacidad
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            minimumInputLength: 0,
            placeholder: "-- SELECCIONE --",
            language: {
                noResults: function() {
                    return "No hay escalas disponibles para esta discapacidad";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
    }

    function cargar_datos_discapacidad(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_discapacidad').html(response);
            }
        });
    }

    function cargar_datos_modal_discapacidad(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {

                $('#ddl_discapacidad').append($('<option>', {
                    value: response[0].id_discapacidad,
                    text: response[0].discapacidad,
                    selected: true
                }));
                $('#ddl_discapacidad_escala').append($('<option>', {
                    value: response[0].id_escala_dis,
                    text: response[0].escala_discapacidad,
                    selected: true
                }));
                $('#ddl_discapacidad_porcentaje').append($('<option>', {
                    value: response[0].id_dis_porcentaje,
                    text: response[0].descripcion_dis_porcentaje,
                    selected: true
                }));
                $('#ddl_discapacidad_gravedad').append($('<option>', {
                    value: response[0].id_dis_gravedad,
                    text: response[0].descripcion_dis_gravedad,
                    selected: true
                }));
                $('#txt_porcentaje').val(response[0].th_pos_dis_porcentaje);
                $('#txt_escala').val(response[0].th_pos_dis_escala);
                $('#txt_discapacidad_id').val(response[0]._id);
                $('#txt_sustituto').val(response[0].sustituto);


                cargar_discapacidad_escalas(response[0].id_discapacidad);
            }
        });
    }

    function insertar_editar_discapacidad() {

        let parametros = {
            pos_id: '<?= $id_postulante ?>',
            ddl_discapacidad: $('#ddl_discapacidad').val(),
            ddl_discapacidad_escala: $('#ddl_discapacidad_escala').val(),
            ddl_discapacidad_gravedad: $('#ddl_discapacidad_gravedad').val(),
            ddl_discapacidad_porcentaje: $('#ddl_discapacidad_porcentaje').val(),
            txt_porcentaje: $('#txt_porcentaje').val(),
            txt_escala: $('#txt_escala').val(),
            txt_sustituto: $('#txt_sustituto').val(),
            _id: $('#txt_discapacidad_id').val()
        };

        if ($("#form_discapacidad").valid()) {
            insertar_discapacidad(parametros);
        }
    }

    function insertar_discapacidad(parametros) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadC.php?insertar=true',
            type: 'post',
            data: {
                parametros: parametros
            },
            dataType: 'json',
            success: function(response) {

                if (response == 1) {

                    Swal.fire('', 'Operación realizada con éxito.', 'success');

                    $('#modal_agregar_discapacidad').modal('hide');
                    cargar_datos_discapacidad(<?= $id_postulante ?>);
                    limpiar_campos_discapacidad();

                } else if (response == -2) {

                    Swal.fire(
                        'Atención',
                        'La discapacidad ya fue registrada para este postulante.',
                        'warning'
                    );

                } else {

                    Swal.fire(
                        'Error',
                        'No se pudo realizar la operación.',
                        'error'
                    );
                }
            },
            error: function() {
                Swal.fire(
                    'Error',
                    'Error en el servidor.',
                    'error'
                );
            }
        });
    }


    function abrir_modal_discapacidad(id = '') {

        limpiar_campos_discapacidad();

        if (id === '') {
            $('#lbl_titulo_discapacidad').html('Agregar Discapacidad');
            $('#btn_guardar_discapacidad').html('<i class="bx bx-save"></i> Agregar');
            $('#btn_eliminar_discapacidad').hide();
        } else {
            cargar_datos_modal_discapacidad(id);
            $('#lbl_titulo_discapacidad').html('Editar Discapacidad');
            $('#btn_guardar_discapacidad').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_discapacidad').show();
        }

        $('#modal_agregar_discapacidad').modal('show');
    }

    function delete_datos_discapacidad() {
        let id = $('#txt_discapacidad_id').val();

        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) eliminar_discapacidad(id);
        });
    }

    function eliminar_discapacidad(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadC.php?eliminar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro eliminado.', 'success');
                    $('#modal_agregar_discapacidad').modal('hide');
                    cargar_datos_discapacidad('<?= $id_postulante ?>');
                }
            }
        });
    }

    function limpiar_campos_discapacidad() {
        $('#form_discapacidad').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#txt_porcentaje').val('');
        $('#txt_escala').val('');
        $('#txt_discapacidad_id').val('');
        $('#txt_sustituto').val('');

        $('#ddl_discapacidad_escala').val(null).trigger('change');
        $('#ddl_discapacidad_porcentaje').val(null).trigger('change');
        $('#ddl_discapacidad_gravedad').val(null).trigger('change');
        $('#ddl_discapacidad').val(null).trigger('change');

        $('.select2-selection').removeClass('is-valid is-invalid');
        $('.select2-validation').each(function() {
            $('label.error[for="' + this.id + '"]').hide();
        });

    }
</script>

<div id="pnl_discapacidad"></div>

<div class="modal fade" id="modal_agregar_discapacidad" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_discapacidad">
                        <i class='bx bx-accessibility me-2'></i>Información de Discapacidad
                    </h5>
                    <small class="text-muted">Registra el tipo, escala y porcentaje según tu carnet oficial.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form_discapacidad" class="needs-validation">
                <input type="hidden" id="txt_discapacidad_id">

                <div class="modal-body">
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label for="ddl_discapacidad" class="form-label fw-semibold fs-7">Tipo de Discapacidad </label>
                            <select class="form-select select2-validation" id="ddl_discapacidad" name="ddl_discapacidad" required>
                                <option value="">-- Seleccione tipo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_discapacidad"></label>
                        </div>

                        <div class="col-md-6">
                            <label for="ddl_discapacidad_escala" class="form-label fw-semibold fs-7">Escala / Nivel <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" title="Primero seleccionar un Tipo de Discapacidad"></i> </label>
                            <select class="form-select select2-validation" id="ddl_discapacidad_escala" name="ddl_discapacidad_escala" required>
                                <option value="">-- Seleccione escala --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_discapacidad_escala"></label>
                        </div>

                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ddl_discapacidad_porcentaje" class="form-label fw-semibold fs-7">Porcentaje </label>
                            <select class="form-select select2-validation" id="ddl_discapacidad_porcentaje" name="ddl_discapacidad_porcentaje" required>
                                <option value="">-- Seleccione tipo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_discapacidad_porcentaje"></label>
                        </div>

                        <div class="col-md-6">
                            <label for="ddl_discapacidad_gravedad" class="form-label fw-semibold fs-7">Gravedad <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" title="Primero seleccionar un Tipo de Discapacidad"></i> </label>
                            <select class="form-select select2-validation" id="ddl_discapacidad_gravedad" name="ddl_discapacidad_gravedad" required>
                                <option value="">-- Seleccione escala --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_discapacidad_gravedad"></label>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="txt_porcentaje" class="form-label fw-semibold fs-7">Porcentaje (%) </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><strong>%</strong></span>
                                <input type="number" class="form-control form-control-sm" id="txt_porcentaje" name="txt_porcentaje" min="0" max="100" required placeholder="0 - 100">
                            </div>
                            <label class="error" style="display: none;" for="txt_porcentaje"></label>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="txt_sustituto" class="form-label fw-semibold fs-7">Sustituto
                                <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip"
                                    title="Opcional. Ingrese el nombre del sustituto registrado, si aplica.">
                                </i>

                            </label>
                            <input type="text" class="form-control form-control-sm" id="txt_sustituto" name="txt_sustituto" placeholder="Nombre del sustituto" oninput="texto_mayusculas(this);">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger btn-sm" id="btn_eliminar_discapacidad" onclick="delete_datos_discapacidad();" style="display:none;">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_discapacidad" onclick="insertar_editar_discapacidad();">
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

        agregar_asterisco_campo_obligatorio('ddl_discapacidad');
        agregar_asterisco_campo_obligatorio('ddl_discapacidad_escala');
        agregar_asterisco_campo_obligatorio('ddl_discapacidad_porcentaje');
        agregar_asterisco_campo_obligatorio('ddl_discapacidad_gravedad');
        agregar_asterisco_campo_obligatorio('txt_porcentaje');


        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

        $("#form_discapacidad").validate({
            rules: {
                ddl_discapacidad: {
                    required: true
                },
                ddl_discapacidad_escala: {
                    required: true
                },
                ddl_discapacidad_porcentaje: {
                    required: true
                },
                ddl_discapacidad_gravedad: {
                    required: true
                },
                txt_porcentaje: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100
                }
            },
            messages: {
                ddl_discapacidad: {
                    required: "Seleccione una discapacidad"
                },
                ddl_discapacidad_escala: {
                    required: "Seleccione una discapacidad escalada"
                },
                ddl_discapacidad_porcentaje: {
                    required: "Seleccione una discapacidad escalada"
                },
                ddl_discapacidad_gravedad: {
                    required: "Seleccione una discapacidad escalada"
                },
                txt_porcentaje: {
                    required: "Ingrese el porcentaje",
                    min: "Mínimo 0",
                    max: "Máximo 100"
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