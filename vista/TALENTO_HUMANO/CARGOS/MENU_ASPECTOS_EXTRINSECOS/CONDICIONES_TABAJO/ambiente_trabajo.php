<script>
    $(document).ready(function() {
        cargar_selects_trabajo('<?= $_id ?>');
        cargar_trabajo('<?= $_id ?>');
    });

    function cargar_selects_trabajo(car_id) {
        if ($('#ddl_req_trabajo').hasClass("select2-hidden-accessible")) {
            $('#ddl_req_trabajo').select2('destroy');
        }

        $('#ddl_req_trabajo').select2({
            dropdownParent: $('#modal_trabajo'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqct_trabajoC.php?buscar_trabajo=true',
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

    function cargar_trabajo(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqct_trabajoC.php?listar_modal=true',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                $('#pnl_trabajo').hide().html(response).fadeIn(400);
            }
        });
    }

    function insertar_editar_trabajo() {
        var ddl_req_trabajo = $('#ddl_req_trabajo').val();
        var th_reqct_id     = $('#th_reqct_id').val();
        var id_cargo        = '<?= $_id ?>';

        var parametros = {
            'id_req_trabajo': ddl_req_trabajo,
            'id_cargo': id_cargo,
            '_id': th_reqct_id,
        };

        if ($("#form_trabajo").valid()) {
            insertar_trabajo(parametros);
        }
    }

    function insertar_trabajo(parametros) {
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqct_trabajoC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_trabajo').modal('hide');
                    limpiar_campos_trabajo_modal();
                    cargar_trabajo(<?= $_id ?>);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_trabajo(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar esta condición de trabajo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_trabajo(id);
            }
        });
    }

    function eliminar_trabajo(id) {
        $.ajax({
            data: { id: id },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqct_trabajoC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    limpiar_campos_trabajo_modal();
                    cargar_trabajo(<?= $_id ?>);
                }
            }
        });
    }

    function abrir_modal_trabajo(id) {
        limpiar_campos_trabajo_modal();
        cargar_selects_trabajo('<?= $_id ?>');
        if (id) {
            $('#lbl_titulo_trabajo').html('<i class="bx bx-edit me-2"></i>Editar Condición de Trabajo');
            $('#btn_guardar_trabajo').html('<i class="bx bx-save"></i> Editar');
        } else {
            $('#lbl_titulo_trabajo').html('<i class="bx bx-plus me-2"></i>Agregar Condición de Trabajo');
            $('#btn_guardar_trabajo').html('<i class="bx bx-save"></i> Guardar');
        }
        $('#modal_trabajo').modal('show');
    }

    function limpiar_campos_trabajo_modal() {
        $('#form_trabajo').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#th_reqct_id').val('');
        $('#ddl_req_trabajo').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<div class="" id="pnl_trabajo"></div>

<div class="modal fade" id="modal_trabajo" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_trabajo">
                        <i class='bx bx-briefcase-alt-2 me-2'></i>Condición de Trabajo
                    </h5>
                    <small class="text-muted">Gestiona las condiciones de trabajo requeridas para este cargo.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_trabajo_modal()"></button>
            </div>

            <form id="form_trabajo" class="needs-validation">
                <input type="hidden" name="th_reqct_id" id="th_reqct_id">

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_req_trabajo" class="form-label fw-semibold fs-7">Condición de Trabajo </label>
                            <select class="form-select select2-validation" id="ddl_req_trabajo" name="ddl_req_trabajo" required style="width: 100%;">
                                <option selected value="">-- Seleccione una condición de trabajo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_req_trabajo"></label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_trabajo_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_trabajo" onclick="insertar_editar_trabajo()">
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
        agregar_asterisco_campo_obligatorio('ddl_req_trabajo');

        $("#form_trabajo").validate({
            ignore: [],
            rules: {
                ddl_req_trabajo: { required: true }
            },
            messages: {
                ddl_req_trabajo: { required: "Seleccione una condición de trabajo" }
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