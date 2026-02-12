<script>
    $(document).ready(function() {
        cargar_selects_riesgo('<?= $_id ?>');
        cargar_riesgos('<?= $_id ?>');
    });

    function cargar_selects_riesgo(car_id) {
        if ($('#ddl_req_riesgo').hasClass("select2-hidden-accessible")) {
            $('#ddl_req_riesgo').select2('destroy');
        }

        $('#ddl_req_riesgo').select2({
            dropdownParent: $('#modal_riesgo'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqct_riesgosC.php?buscar_riesgos=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        car_id: car_id
                    };
                },
                processResults: function(data) {
                    return { results: data };
                }
            },
            minimumInputLength: 0,
            placeholder: "-- SELECCIONE --",
            language: {
                noResults: function() { return "No hay opciones disponibles"; },
                searching: function() { return "Buscando..."; }
            }
        });
    }

    function cargar_riesgos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqct_riesgosC.php?listar_modal=true',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                $('#pnl_riesgo').hide().html(response).fadeIn(400);
            }
        });
    }

    function insertar_editar_riesgo() {
        var ddl_req_riesgo = $('#ddl_req_riesgo').val();
        var th_reqr_id     = $('#th_reqr_id_riesgo').val();
        var id_cargo       = '<?= $_id ?>';

        var parametros = {
            'id_req_riesgo': ddl_req_riesgo,
            'id_cargo': id_cargo,
            '_id': th_reqr_id,
        };

        if ($("#form_riesgo").valid()) {
            insertar_riesgo(parametros);
        }
    }

    function insertar_riesgo(parametros) {
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqct_riesgosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_riesgo').modal('hide');
                    limpiar_campos_riesgo_modal();
                    cargar_riesgos(<?= $_id ?>);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_riesgo(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este riesgo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_riesgo(id);
            }
        });
    }

    function eliminar_riesgo(id) {
        $.ajax({
            data: { id: id },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqct_riesgosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    limpiar_campos_riesgo_modal();
                    cargar_riesgos(<?= $_id ?>);
                }
            }
        });
    }

    function abrir_modal_riesgo(id) {
        limpiar_campos_riesgo_modal();
        cargar_selects_riesgo('<?= $_id ?>');
        if (id) {
            $('#lbl_titulo_riesgo').html('<i class="bx bx-edit me-2"></i>Editar Riesgo');
            $('#btn_guardar_riesgo').html('<i class="bx bx-save"></i> Editar');
        } else {
            $('#lbl_titulo_riesgo').html('<i class="bx bx-plus me-2"></i>Agregar Riesgo');
            $('#btn_guardar_riesgo').html('<i class="bx bx-save"></i> Guardar');
        }
        $('#modal_riesgo').modal('show');
    }

    function limpiar_campos_riesgo_modal() {
        $('#form_riesgo').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#th_reqr_id_riesgo').val('');
        $('#ddl_req_riesgo').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<div class="" id="pnl_riesgo"></div>

<div class="modal fade" id="modal_riesgo" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_riesgo">
                        <i class='bx bx-shield-quarter me-2'></i>Riesgo
                    </h5>
                    <small class="text-muted">Gestiona los riesgos asociados a este cargo.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_riesgo_modal()"></button>
            </div>

            <form id="form_riesgo" class="needs-validation">
                <input type="hidden" name="th_reqr_id_riesgo" id="th_reqr_id_riesgo">

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_req_riesgo" class="form-label fw-semibold fs-7">Riesgo </label>
                            <select class="form-select select2-validation" id="ddl_req_riesgo" name="ddl_req_riesgo" required style="width: 100%;">
                                <option selected value="">-- Seleccione un riesgo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_req_riesgo"></label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_riesgo_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_riesgo" onclick="insertar_editar_riesgo()">
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
        agregar_asterisco_campo_obligatorio('ddl_req_riesgo');

        $("#form_riesgo").validate({
            ignore: [],
            rules: {
                ddl_req_riesgo: { required: true }
            },
            messages: {
                ddl_req_riesgo: { required: "Seleccione un riesgo" }
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