<script>
    $(document).ready(function() {
        var id_cargo = $('#txt_id_cargo').val();
        cargar_selects_responsabilidad(id_cargo);
        cargar_responsabilidades(id_cargo);
        <?php if ($es_plaza) { ?>
            cargar_plaza_responsabilidades('<?= $_id_plaza ?>');
        <?php } ?>
    });

    function cargar_selects_responsabilidad(car_id, pla_id) {
        var id_cargo = $('#txt_id_cargo').val();
        var pla_id = 0;
        <?php if ($es_plaza) { ?>
            pla_id = '<?= $_id_plaza ?>';
        <?php } ?>

        data_extra = {
            'car_id': id_cargo,
            'pla_id': pla_id
        };

        url_responsabilidadC = '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqr_responsabilidadesC.php?buscar_responsabilidades_car_pla=true';
        cargar_select2_url('ddl_req_res_det', url_responsabilidadC, '', '#modal_responsabilidad', 0, data_extra);
    }

    function cargar_responsabilidades(id, button = true) {
        cargar_selects_responsabilidad(id);

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqr_responsabilidadesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id,
                button_delete: button
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_responsabilidad').hide().html(response).fadeIn(400);
            }
        });
    }

    function insertar_editar_responsabilidad() {
        var ddl_req_res_det = $('#ddl_req_res_det').val();
        var th_reqr_id      = $('#th_reqr_id').val();
        var id_cargo        = $('#txt_id_cargo').val();

        var parametros = {
            'id_req_res_det': ddl_req_res_det,
            'id_cargo':       id_cargo,
            '_id':            th_reqr_id,
        };

        if ($("#form_responsabilidad").valid()) {
            insertar_responsabilidad(parametros);
        }
    }

    function insertar_responsabilidad(parametros) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqr_responsabilidadesC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_responsabilidad').modal('hide');
                    limpiar_campos_responsabilidad_modal();
                    cargar_responsabilidades(id_cargo);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_responsabilidad(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar esta responsabilidad?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_responsabilidad(id);
            }
        });
    }

    function eliminar_responsabilidad(id) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqr_responsabilidadesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    limpiar_campos_responsabilidad_modal();
                    cargar_responsabilidades(id_cargo);
                }
            }
        });
    }

    function abrir_modal_responsabilidad(id) {
        limpiar_campos_responsabilidad_modal();
        cargar_selects_responsabilidad('<?= $_id ?>');
        if (id) {
            $('#lbl_titulo_responsabilidad').html('<i class="bx bx-edit me-2"></i>Editar Responsabilidad');
            $('#btn_guardar_responsabilidad').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_responsabilidad').show();
        } else {
            $('#lbl_titulo_responsabilidad').html('<i class="bx bx-plus me-2"></i>Agregar Responsabilidad');
            $('#btn_guardar_responsabilidad').html('<i class="bx bx-save"></i> Guardar');
            $('#btn_eliminar_responsabilidad').hide();
        }
        $('#modal_responsabilidad').modal('show');
    }

    function limpiar_campos_responsabilidad_modal() {
        $('#form_responsabilidad').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#th_reqr_id').val('');
        $('#ddl_req_res_det').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<?php if ($es_plaza) { ?>
    <script>
        function cargar_plaza_responsabilidades(id, button = true) {
            var id_cargo = $('#txt_id_cargo').val();
            cargar_selects_responsabilidad(id_cargo, id);
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqr_responsabilidadesC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: button
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_plaza_responsabilidad').hide().html(response).fadeIn(400);
                }
            });
        }

        function insertar_editar_responsabilidad_plaza() {
            var ddl_req_res_det = $('#ddl_req_res_det').val();
            var th_reqr_id      = $('#th_reqr_id').val();
            var cn_pla_id       = '<?= $_id_plaza ?>';

            var parametros = {
                'id_req_res_det': ddl_req_res_det,
                'cn_pla_id':      cn_pla_id,
                '_id':            th_reqr_id,
            };

            if ($("#form_responsabilidad").valid()) {
                insertar_responsabilidad_plaza(parametros);
            }
        }

        function insertar_responsabilidad_plaza(parametros) {

            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqr_responsabilidadesC.php?insertar_editar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        $('#modal_responsabilidad').modal('hide');
                        limpiar_campos_responsabilidad_modal();
                        cargar_plaza_responsabilidades('<?= $_id_plaza ?>');
                    } else {
                        Swal.fire('', 'Operación fallida', 'warning');
                    }
                }
            });
        }

        function delete_datos_responsabilidad(id) {
            Swal.fire({
                title: '¿Eliminar Registro?',
                text: "¿Está seguro de eliminar esta responsabilidad?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    eliminar_responsabilidad(id);
                }
            });
        }

        function eliminar_responsabilidad(id) {

            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqr_responsabilidadesC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                        limpiar_campos_responsabilidad_modal();
                        cargar_plaza_responsabilidades('<?= $_id_plaza ?>');
                    }
                }
            });
        }
    </script>
<?php } ?>

<input type="hidden" name="txt_id_cargo" id="txt_id_cargo" value="<?= $_id ?>">

<div class="" id="pnl_responsabilidad"></div>

<?php if ($es_plaza) { ?>
    </br>
    <strong>Requisitos Adicionales</strong>
    </br>
    <div class="" id="pnl_plaza_responsabilidad">
    </div>
<?php } ?>


<div class="modal fade" id="modal_responsabilidad" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_responsabilidad">
                        <i class='bx bx-task me-2'></i>Responsabilidad
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_responsabilidad_modal()"></button>
            </div>

            <form id="form_responsabilidad" class="needs-validation">
                <input type="hidden" name="th_reqr_id" id="th_reqr_id">

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_req_res_det" class="form-label fw-semibold fs-7">Responsabilidad </label>
                            <select class="form-select select2-validation" id="ddl_req_res_det" name="ddl_req_res_det" required style="width: 100%;">
                                <option selected value="">-- Seleccione una responsabilidad --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_req_res_det"></label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <div class="ms-auto">
                        <?php if ($es_plaza) { ?>
                            <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_responsabilidad_modal()">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_responsabilidad_plaza" onclick="insertar_editar_responsabilidad_plaza()">
                                <i class="bx bx-save"></i> Guardar
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_responsabilidad_modal()">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_responsabilidad" onclick="insertar_editar_responsabilidad()">
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
        agregar_asterisco_campo_obligatorio('ddl_req_res_det');

        $("#form_responsabilidad").validate({
            ignore: [],
            rules: {
                ddl_req_res_det: { required: true }
            },
            messages: {
                ddl_req_res_det: { required: "Seleccione una responsabilidad" }
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