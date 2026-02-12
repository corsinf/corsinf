<script>
    $(document).ready(function() {
        cargar_selects_aptitud('<?= $_id ?>');
        cargar_aptitudes('<?= $_id ?>');
    });

    function cargar_selects_aptitud(car_id) {
        if ($('#ddl_habilidades').hasClass("select2-hidden-accessible")) {
            $('#ddl_habilidades').select2('destroy');
        }

        $('#ddl_habilidades').select2({
            dropdownParent: $('#modal_agregar_aptitud'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?buscar_habilidades=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        car_id: car_id
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
                    return "No hay opciones disponibles";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
    }

    function cargar_aptitudes(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_aptitudes').hide().html(response).fadeIn(400);
            }
        });
    }

    function cargar_datos_modal_aptitud(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?listar=true',
            type: 'post',
            data: {
                aptitud_id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#ddl_habilidades').append($('<option>', {
                        value: response[0].th_hab_id,
                        text: response[0].habilidad_nombre,
                        selected: true
                    })).trigger('change');

                    $('#th_reqa_experiencia_id').val(response[0]._id);
                }
            }
        });
    }

    function insertar_editar_aptitud() {
        var ddl_habilidades = $('#ddl_habilidades').val();
        var th_reqa_experiencia_id = $('#th_reqa_experiencia_id').val();
        var id_cargo = '<?= $_id ?>';

        var parametros = {
            'th_hab_id': ddl_habilidades,
            'id_cargo': id_cargo,
            '_id': th_reqa_experiencia_id,
        }

        if ($("#form_aptitud").valid()) {
            insertar_aptitud(parametros);
        }
    }

    function insertar_aptitud(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_agregar_aptitud').modal('hide');
                    limpiar_campos_aptitud_modal();
                    cargar_aptitudes(<?= $_id ?>);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_aptitud(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar esta aptitud requerida?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_aptitud(id);
            }
        })
    }

    function eliminar_aptitud(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_agregar_aptitud').modal('hide');
                    limpiar_campos_aptitud_modal();
                    cargar_aptitudes(<?= $_id ?>);
                }
            }
        });
    }

    function abrir_modal_aptitud(id) {
        limpiar_campos_aptitud_modal();
        if (id) {
            cargar_datos_modal_aptitud(id);
            $('#lbl_titulo_aptitud').html('<i class="bx bx-edit me-2"></i>Editar Aptitud');
            $('#btn_guardar_aptitud').html('<i class="bx bx-save"></i> Editar');
        } else {
            $('#lbl_titulo_aptitud').html('<i class="bx bx-plus me-2"></i>Agregar Aptitud');
            $('#btn_guardar_aptitud').html('<i class="bx bx-save"></i> Guardar');
        }
        $('#modal_agregar_aptitud').modal('show');
    }

    function limpiar_campos_aptitud_modal() {
        $('#form_aptitud').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#th_reqa_experiencia_id').val('');
        $('#ddl_habilidades').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<div class="" id="pnl_aptitudes"></div>

<div class="modal fade" id="modal_agregar_aptitud" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_aptitud">
                        <i class='bx bx-brain me-2'></i>Aptitudes y Habilidades
                    </h5>
                    <small class="text-muted">Gestiona las habilidades requeridas para este cargo.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_aptitud_modal()"></button>
            </div>

            <form id="form_aptitud" class="needs-validation">
                <input type="hidden" name="th_reqa_experiencia_id" id="th_reqa_experiencia_id">

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_habilidades" class="form-label fw-semibold fs-7">Habilidad/Aptitud</label>
                            <select class="form-select select2-validation" id="ddl_habilidades" name="ddl_habilidades" required style="width: 100%;">
                                <option selected value="">-- Seleccione una habilidad --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_habilidades"></label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_aptitud_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_aptitud" onclick="insertar_editar_aptitud()">
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
        agregar_asterisco_campo_obligatorio('ddl_habilidades');

        $("#form_aptitud").validate({
            ignore: [],
            rules: {
                ddl_habilidades: {
                    required: true
                }
            },
            messages: {
                ddl_habilidades: {
                    required: "Seleccione la habilidad"
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-validation') || element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
            submitHandler: () => false
        });
    });
</script>