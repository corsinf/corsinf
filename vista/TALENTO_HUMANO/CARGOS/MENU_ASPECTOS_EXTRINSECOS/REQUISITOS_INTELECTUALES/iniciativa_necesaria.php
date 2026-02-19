<script>
    $(document).ready(function() {
        cargar_selects_iniciativa('<?= $_id ?>');
        cargar_iniciativas('<?= $_id ?>');
    });

    function cargar_selects_iniciativa(car_id) {
        if ($('#ddl_iniciativas').hasClass("select2-hidden-accessible")) {
            $('#ddl_iniciativas').select2('destroy');
        }

        $('#ddl_iniciativas').select2({
            dropdownParent: $('#modal_agregar_iniciativa'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_iniciativaC.php?buscar_iniciativas=true',
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

    function cargar_iniciativas(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_iniciativaC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_iniciativas').hide().html(response.html).fadeIn(400);

                // Si hay 1 o más registros, ocultamos el botón de agregar
                const btnAgregar = $('#pnl_iniciativa_necesaria');

                if (response.total > 0) {
                    btnAgregar.addClass('d-none').removeClass('d-flex');

                } else {
                    btnAgregar.removeClass('d-none').addClass('d-flex');
                }
            }
        });
    }

    function insertar_editar_iniciativa() {
        var ddl_iniciativas = $('#ddl_iniciativas').val();
        var id_cargo = '<?= $_id ?>';

        var parametros = {
            'id_req_iniciativa': ddl_iniciativas,
            'id_cargo': id_cargo
        }

        if ($("#form_iniciativa").valid()) {
            insertar_iniciativa(parametros);
        }
    }

    function insertar_iniciativa(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_iniciativaC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_agregar_iniciativa').modal('hide');
                    limpiar_campos_iniciativa_modal();
                    cargar_iniciativas(<?= $_id ?>);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_iniciativa(id_cargo, id_iniciativa) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este requisito de iniciativa?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_iniciativa(id_cargo, id_iniciativa);
            }
        })
    }

    function eliminar_iniciativa(id_cargo, id_iniciativa) {
        $.ajax({
            data: {
                id_cargo: id_cargo,
                id_iniciativa: id_iniciativa
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_iniciativaC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_agregar_iniciativa').modal('hide');
                    limpiar_campos_iniciativa_modal();
                    cargar_iniciativas(<?= $_id ?>);
                }
            }
        });
    }

    function abrir_modal_iniciativa() {
        limpiar_campos_iniciativa_modal();
        $('#lbl_titulo_iniciativa').html('<i class="bx bx-plus me-2"></i>Agregar Iniciativa');
        $('#btn_guardar_iniciativa').html('<i class="bx bx-save"></i> Guardar');
        $('#modal_agregar_iniciativa').modal('show');
    }

    function limpiar_campos_iniciativa_modal() {
        $('#form_iniciativa').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_iniciativas').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<div class="" id="pnl_iniciativas"></div>

<div class="modal fade" id="modal_agregar_iniciativa" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_iniciativa">
                        <i class='bx bx-bulb me-2'></i>Requisitos de Iniciativa
                    </h5>
                    <small class="text-muted">Gestiona los requisitos de iniciativa para este cargo.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_iniciativa_modal()"></button>
            </div>

            <form id="form_iniciativa" class="needs-validation">
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_iniciativas" class="form-label fw-semibold fs-7">Iniciativa </label>
                            <select class="form-select select2-validation" id="ddl_iniciativas" name="ddl_iniciativas" required style="width: 100%;">
                                <option selected value="">-- Seleccione una iniciativa --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_iniciativas"></label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_iniciativa_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_iniciativa" onclick="insertar_editar_iniciativa()">
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
        agregar_asterisco_campo_obligatorio('ddl_iniciativas');

        $("#form_iniciativa").validate({
            ignore: [],
            rules: {
                ddl_iniciativas: {
                    required: true
                }
            },
            messages: {
                ddl_iniciativas: {
                    required: "Seleccione la iniciativa"
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