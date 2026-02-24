<script>
    $(document).ready(function() {
        var id_cargo = $('#txt_id_cargo').val();
        cargar_experiencias_necesarias(id_cargo);
        <?php if ($es_plaza) { ?>
            cargar_plaza_experiencias_necesarias('<?= $_id_plaza ?>');
        <?php } ?>
    });

    function cargar_selects_experiencia(car_id, pla_id) {
        var id_cargo = $('#txt_id_cargo').val();
        var pla_id = 0;
        <?php if ($es_plaza) { ?>
            pla_id = '<?= $_id_plaza ?>';
        <?php } ?>

        var data_extra = {
            'car_id': id_cargo,
            'pla_id': pla_id
        };

        var url = '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?buscar_rango_profesional=true';
        cargar_select2_url('ddl_rango_profesional', url, '', '#modal_experiencia_necesaria', 0, data_extra);
    }

    function cargar_experiencias_necesarias(id, button = true) {
        cargar_selects_experiencia(id);

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id,
                button_delete: button
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_experiencia_necesaria').hide().html(response.html).fadeIn(400);
            }
        });
    }

    function cargar_datos_modal_experiencia_necesaria(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#th_reqi_experiencia_id').val(response[0]._id);
                    $('#ddl_rango_profesional').append($('<option>', {
                        value: response[0].id_rango_profesional,
                        text: response[0].rango_nombre + ' (' + response[0].min_anios_exp + ' - ' + response[0].max_anios_exp + ' años)',
                        selected: true
                    })).trigger('change');
                }
            }
        });
    }

    function insertar_editar_experiencia_necesaria() {
        var id_rango = $('#ddl_rango_profesional').val();
        var id_exp = $('#th_reqi_experiencia_id').val();
        var id_cargo = $('#txt_id_cargo').val();

        var parametros = {
            'id_rango_profesional': id_rango,
            'id_cargo': id_cargo,
            '_id': id_exp,
        };

        if ($("#form_experiencia_necesaria").valid()) {
            insertar_experiencia_necesaria(parametros);
        }
    }

    function insertar_experiencia_necesaria(parametros) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_experiencia_necesaria').modal('hide');
                    limpiar_campos_experiencia_necesaria_modal();
                    cargar_experiencias_necesarias(id_cargo);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_experiencia_necesaria(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este requisito de experiencia?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_experiencia_necesaria(id);
            }
        });
    }

    function eliminar_experiencia_necesaria(id) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_experiencia_necesaria').modal('hide');
                    limpiar_campos_experiencia_necesaria_modal();
                    cargar_experiencias_necesarias(id_cargo);
                }
            }
        });
    }

    function abrir_modal_experiencia_necesaria(id) {
        limpiar_campos_experiencia_necesaria_modal();
        if (id) {
            cargar_datos_modal_experiencia_necesaria(id);
            $('#lbl_titulo_experiencia_necesaria').html('<i class="bx bx-edit me-2"></i>Editar Experiencia Necesaria');
            $('#btn_guardar_experiencia_necesaria').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_experiencia_necesaria').show();
        } else {
            $('#lbl_titulo_experiencia_necesaria').html('<i class="bx bx-plus me-2"></i>Agregar Experiencia Necesaria');
            $('#btn_guardar_experiencia_necesaria').html('<i class="bx bx-save"></i> Guardar');
            $('#btn_eliminar_experiencia_necesaria').hide();
        }
        $('#modal_experiencia_necesaria').modal('show');
    }

    function limpiar_campos_experiencia_necesaria_modal() {
        $('#form_experiencia_necesaria').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#th_reqi_experiencia_id').val('');
        $('#ddl_rango_profesional').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<?php if ($es_plaza) { ?>
    <script>
        function cargar_plaza_experiencias_necesarias(id, button = true) {
            var id_cargo = $('#txt_id_cargo').val();
            cargar_selects_experiencia(id_cargo, id);

            $.ajax({
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_experienciaC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: button
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_plaza_experiencia_necesaria').hide().html(response.html).fadeIn(400);
                }
            });
        }

        function insertar_editar_experiencia_necesaria_plaza() {
            var id_rango = $('#ddl_rango_profesional').val();
            var id_exp = $('#th_reqi_experiencia_id').val();
            var cn_pla_id = '<?= $_id_plaza ?>';

            var parametros = {
                'id_rango_profesional': id_rango,
                'cn_pla_id': cn_pla_id,
                '_id': id_exp,
            };

            if ($("#form_experiencia_necesaria").valid()) {
                insertar_experiencia_necesaria_plaza(parametros);
            }
        }

        function insertar_experiencia_necesaria_plaza(parametros) {
            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_experienciaC.php?insertar_editar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        $('#modal_experiencia_necesaria').modal('hide');
                        limpiar_campos_experiencia_necesaria_modal();
                        cargar_plaza_experiencias_necesarias('<?= $_id_plaza ?>');
                    } else {
                        Swal.fire('', 'Operación fallida', 'warning');
                    }
                }
            });
        }

        function delete_datos_experiencia_necesaria(id) {
            Swal.fire({
                title: '¿Eliminar Registro?',
                text: "¿Está seguro de eliminar este requisito de experiencia?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    eliminar_experiencia_necesaria(id);
                }
            });
        }

        function eliminar_experiencia_necesaria(id) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_experienciaC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                        $('#modal_experiencia_necesaria').modal('hide');
                        limpiar_campos_experiencia_necesaria_modal();
                        cargar_plaza_experiencias_necesarias('<?= $_id_plaza ?>');
                    }
                }
            });
        }
    </script>
<?php } ?>

<input type="hidden" name="txt_id_cargo" id="txt_id_cargo" value="<?= $_id ?>">

<div class="" id="pnl_experiencia_necesaria"></div>

<?php if ($es_plaza) { ?>
    </br>
    <strong>Requisitos Adicionales</strong>
    </br>
    <div class="" id="pnl_plaza_experiencia_necesaria"></div>
<?php } ?>


<div class="modal fade" id="modal_experiencia_necesaria" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_experiencia_necesaria">
                        <i class='bx bx-briefcase me-2'></i>Experiencia Necesaria
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    onclick="limpiar_campos_experiencia_necesaria_modal()"></button>
            </div>

            <form id="form_experiencia_necesaria" class="needs-validation">
                <input type="hidden" name="th_reqi_experiencia_id" id="th_reqi_experiencia_id">

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_rango_profesional" class="form-label fw-semibold fs-7">Rango Profesional </label>
                            <select class="form-select select2-validation" id="ddl_rango_profesional" name="ddl_rango_profesional" required style="width: 100%;">
                                <option selected value="">-- Seleccione un rango --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_rango_profesional"></label>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                        <div>
                            <button type="button"
                                class="btn btn-danger btn-sm"
                                id="btn_eliminar_experiencia_necesaria"
                                style="display:none;"
                                onclick="delete_datos_experiencia_necesaria($('#th_reqi_experiencia_id').val())">
                                <i class="bx bx-trash"></i> Eliminar
                            </button>
                        </div>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-secondary btn-sm me-2"
                                data-bs-dismiss="modal"
                                onclick="limpiar_campos_experiencia_necesaria_modal()">
                                Cancelar
                            </button>
                            <?php if ($es_plaza) { ?>
                                <button type="button" class="btn btn-primary btn-sm px-4"
                                    id="btn_guardar_experiencia_necesaria_plaza"
                                    onclick="insertar_editar_experiencia_necesaria_plaza()">
                                    <i class="bx bx-save"></i> Guardar
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-primary btn-sm px-4"
                                    id="btn_guardar_experiencia_necesaria"
                                    onclick="insertar_editar_experiencia_necesaria()">
                                    <i class="bx bx-save"></i> Guardar
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_rango_profesional');

        $("#form_experiencia_necesaria").validate({
            ignore: [],
            rules: {
                ddl_rango_profesional: {
                    required: true
                }
            },
            messages: {
                ddl_rango_profesional: {
                    required: "Seleccione el rango profesional"
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