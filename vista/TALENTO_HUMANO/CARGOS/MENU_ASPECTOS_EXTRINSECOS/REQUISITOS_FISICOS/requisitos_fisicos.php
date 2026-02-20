<script>
    $(document).ready(function() {
        var id_cargo = $('#txt_id_cargo').val();
        cargar_selects_reqf_fisico(id_cargo);
        cargar_reqf_fisicos(id_cargo);
        <?php if ($es_plaza) { ?>
            cargar_plaza_reqf_fisicos('<?= $_id_plaza ?>');
        <?php } ?>
    });

    function cargar_selects_reqf_fisico(car_id, pla_id) {
        var id_cargo = $('#txt_id_cargo').val();
        var pla_id = 0;
        <?php if ($es_plaza) { ?>
            pla_id = '<?= $_id_plaza ?>';
        <?php } ?>

        url_req_fisicoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_reqf_fisicosC.php?buscar=true';
        cargar_select2_url('ddl_req_fisico', url_req_fisicoC, '', '#modal_reqf_fisico');

        if ($('#ddl_req_fisico_det').hasClass("select2-hidden-accessible")) {
            $('#ddl_req_fisico_det').select2('destroy');
        }

        $('#ddl_req_fisico').on('select2:select', function() {
            var id_req_fisico = $(this).val();

            if ($('#ddl_req_fisico_det').hasClass("select2-hidden-accessible")) {
                $('#ddl_req_fisico_det').select2('destroy');
            }
            $('#ddl_req_fisico_det').val(null).prop('disabled', false).trigger('change');

            $('#ddl_req_fisico_det').select2({
                dropdownParent: $('#modal_reqf_fisico'),
                ajax: {
                    url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqf_fisicosC.php?buscar_req_fisicos_detalle=true',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term,
                            car_id: id_cargo,
                            pla_id: pla_id,
                            id_req_fisico: id_req_fisico
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
        });

        $('#ddl_req_fisico').on('select2:clear select2:unselect', function() {
            if ($('#ddl_req_fisico_det').hasClass("select2-hidden-accessible")) {
                $('#ddl_req_fisico_det').select2('destroy');
            }
            $('#ddl_req_fisico_det').val(null).prop('disabled', true).trigger('change');
        });
    }

    function cargar_reqf_fisicos(id, button = true) {
        cargar_selects_reqf_fisico(id);

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqf_fisicosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id,
                button_delete: button
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_reqf_fisico').hide().html(response).fadeIn(400);
            }
        });
    }

    function insertar_editar_reqf_fisico() {
        var ddl_req_fisico_det = $('#ddl_req_fisico_det').val();
        var th_reqf_id = $('#th_reqf_id').val();
        var id_cargo = $('#txt_id_cargo').val();

        var parametros = {
            'id_req_fisico_det': ddl_req_fisico_det,
            'id_cargo': id_cargo,
            '_id': th_reqf_id,
        };

        if ($("#form_reqf_fisico").valid()) {
            insertar_reqf_fisico(parametros);
        }
    }

    function insertar_reqf_fisico(parametros) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqf_fisicosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_reqf_fisico').modal('hide');
                    limpiar_campos_reqf_fisico_modal();
                    cargar_reqf_fisicos(id_cargo);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_reqf_fisico(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este requisito físico?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_reqf_fisico(id);
            }
        });
    }

    function eliminar_reqf_fisico(id) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqf_fisicosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    limpiar_campos_reqf_fisico_modal();
                    cargar_reqf_fisicos(id_cargo);
                }
            }
        });
    }

    function abrir_modal_reqf_fisico(id) {
        limpiar_campos_reqf_fisico_modal();
        $('#ddl_req_fisico_det').val(null).prop('disabled', true).trigger('change');

        cargar_selects_reqf_fisico('<?= $_id ?>');
        if (id) {
            $('#lbl_titulo_reqf_fisico').html('<i class="bx bx-edit me-2"></i>Editar Requisito Físico');
            $('#btn_guardar_reqf_fisico').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_reqf_fisico').show();
        } else {
            $('#lbl_titulo_reqf_fisico').html('<i class="bx bx-plus me-2"></i>Agregar Requisito Físico');
            $('#btn_guardar_reqf_fisico').html('<i class="bx bx-save"></i> Guardar');
            $('#btn_eliminar_reqf_fisico').hide();
        }
        $('#modal_reqf_fisico').modal('show');
    }

    function limpiar_campos_reqf_fisico_modal() {
        $('#ddl_req_fisico_det').val(null).prop('disabled', true).trigger('change');
        $('#form_reqf_fisico').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#th_reqf_id').val('');
        $('#ddl_req_fisico').val(null).trigger('change');
        $('#ddl_req_fisico_det').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<?php if ($es_plaza) { ?>
    <script>
        function cargar_plaza_reqf_fisicos(id, button = true) {
            var id_cargo = $('#txt_id_cargo').val();
            cargar_selects_reqf_fisico(id_cargo, id);
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqf_fisicosC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: button
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_plaza_reqf_fisico').hide().html(response).fadeIn(400);
                }
            });
        }

        function insertar_editar_reqf_fisico_plaza() {
            var ddl_req_fisico_det = $('#ddl_req_fisico_det').val();
            var th_reqf_id = $('#th_reqf_id').val();
            var cn_pla_id = '<?= $_id_plaza ?>';

            var parametros = {
                'id_req_fisico_det': ddl_req_fisico_det,
                'cn_pla_id': cn_pla_id,
                '_id': th_reqf_id,
            };

            if ($("#form_reqf_fisico").valid()) {
                insertar_reqf_fisico_plaza(parametros);
            }
        }

        function insertar_reqf_fisico_plaza(parametros) {

            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqf_fisicosC.php?insertar_editar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        $('#modal_reqf_fisico').modal('hide');
                        limpiar_campos_reqf_fisico_modal();
                        cargar_plaza_reqf_fisicos('<?= $_id_plaza ?>');
                    } else {
                        Swal.fire('', 'Operación fallida', 'warning');
                    }
                }
            });
        }

        function delete_datos_reqf_fisico(id) {
            Swal.fire({
                title: '¿Eliminar Registro?',
                text: "¿Está seguro de eliminar este requisito físico?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    eliminar_reqf_fisico(id);
                }
            });
        }

        function eliminar_reqf_fisico(id) {

            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqf_fisicosC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                        limpiar_campos_reqf_fisico_modal();
                        cargar_plaza_reqf_fisicos('<?= $_id_plaza ?>');
                    }
                }
            });
        }
    </script>
<?php } ?>

<input type="hidden" name="txt_id_cargo" id="txt_id_cargo" value="<?= $_id ?>">

<div class="" id="pnl_reqf_fisico"></div>

<?php if ($es_plaza) { ?>
    </br>
    <strong>Requisitos Adicionales</strong>
    </br>
    <div class="" id="pnl_plaza_reqf_fisico">
    </div>
<?php } ?>


<div class="modal fade" id="modal_reqf_fisico" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_reqf_fisico">
                        <i class='bx bx-body me-2'></i>Requisito Físico
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_reqf_fisico_modal()"></button>
            </div>

            <form id="form_reqf_fisico" class="needs-validation">
                <input type="hidden" name="th_reqf_id" id="th_reqf_id">

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_req_fisico" class="form-label fw-semibold fs-7">Tipo de Requisito Físico </label>
                            <select class="form-select select2-validation" id="ddl_req_fisico" name="ddl_req_fisico" required style="width: 100%;">
                                <option selected value="">-- Seleccione un tipo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_req_fisico"></label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_req_fisico_det" class="form-label fw-semibold fs-7">Detalle del Requisito Físico </label>
                            <select class="form-select select2-validation" id="ddl_req_fisico_det" name="ddl_req_fisico_det" required style="width: 100%;" disabled>
                                <option selected value="">-- Seleccione primero un tipo --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_req_fisico_det"></label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <div class="ms-auto">
                        <?php if ($es_plaza) { ?>
                            <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_reqf_fisico_modal()">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_reqf_fisico_plaza" onclick="insertar_editar_reqf_fisico_plaza()">
                                <i class="bx bx-save"></i> Guardar
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_reqf_fisico_modal()">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_reqf_fisico" onclick="insertar_editar_reqf_fisico()">
                                <i class="bx bx-save"></i> Guardar
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_req_fisico');
        agregar_asterisco_campo_obligatorio('ddl_req_fisico_det');

        $("#form_reqf_fisico").validate({
            ignore: [],
            rules: {
                ddl_req_fisico: {
                    required: true
                },
                ddl_req_fisico_det: {
                    required: true
                }
            },
            messages: {
                ddl_req_fisico: {
                    required: "Seleccione el tipo de requisito físico"
                },
                ddl_req_fisico_det: {
                    required: "Seleccione el detalle del requisito físico"
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