<script>
    $(document).ready(function() {
        var id_cargo = $('#txt_id_cargo').val();
        cargar_selects_aptitud(id_cargo);
        cargar_aptitudes(id_cargo);
        <?php if ($es_plaza) { ?>
            cargar_plaza_aptitudes('<?= $_id_plaza ?>');
        <?php } ?>
    });

    function cargar_selects_aptitud(car_id, pla_id) {
        var id_cargo = $('#txt_id_cargo').val();
        var pla_id = 0;
        <?php if ($es_plaza) { ?>
            pla_id = '<?= $_id_plaza ?>';
        <?php } ?>

        data_extra = {
            'car_id': id_cargo,
            'pla_id': pla_id
        };

        url_tecnicasC = '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?buscar_habilidades_tecnicas=true';
        cargar_select2_url('ddl_habilidades_tecnicas', url_tecnicasC, '', '#modal_agregar_aptitud', 0, data_extra);

        url_blandasC = '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?buscar_habilidades_blandas=true';
        cargar_select2_url('ddl_habilidades_blandas', url_blandasC, '', '#modal_agregar_aptitud', 0, data_extra);
    }

    function cargar_aptitudes(id, button = true) {
        cargar_selects_aptitud(id);

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id,
                button_delete: button
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
                    var item = response[0];
                    var ddlTarget = (item.th_tiph_id == 1) ?
                        '#ddl_habilidades_tecnicas' :
                        '#ddl_habilidades_blandas';

                    $(ddlTarget).append($('<option>', {
                        value: item.th_hab_id,
                        text: item.habilidad_nombre,
                        selected: true
                    })).trigger('change');

                    $('#th_reqa_experiencia_id').val(item._id);
                }
            }
        });
    }

    function insertar_editar_aptitud() {
        var tecnica = $('#ddl_habilidades_tecnicas').val();
        var blanda = $('#ddl_habilidades_blandas').val();

        if (!tecnica && !blanda) {
            Swal.fire('', 'Debe seleccionar al menos una habilidad técnica o una habilidad blanda.', 'warning');
            return;
        }

        var parametros = {
            'th_hab_id_tecnica': tecnica || '',
            'th_hab_id_blanda': blanda || '',
            'id_cargo': $('#txt_id_cargo').val(),
            '_id': $('#th_reqa_experiencia_id').val()
        };

        insertar_aptitud(parametros);
    }

    function insertar_aptitud(parametros) {
        var id_cargo = $('#txt_id_cargo').val();

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
                    cargar_aptitudes(id_cargo);
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
        });
    }

    function eliminar_aptitud(id) {
        var id_cargo = $('#txt_id_cargo').val();

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
                    cargar_aptitudes(id_cargo);
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
            $('#btn_eliminar_aptitud').show();
        } else {
            $('#lbl_titulo_aptitud').html('<i class="bx bx-plus me-2"></i>Agregar Aptitud');
            $('#btn_guardar_aptitud').html('<i class="bx bx-save"></i> Guardar');
            $('#btn_eliminar_aptitud').hide();
        }
        $('#modal_agregar_aptitud').modal('show');
    }

    function limpiar_campos_aptitud_modal() {
        $('#th_reqa_experiencia_id').val('');
        $('#ddl_habilidades_tecnicas').val(null).trigger('change');
        $('#ddl_habilidades_blandas').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<?php if ($es_plaza) { ?>
    <script>
        function cargar_plaza_aptitudes(id, button = true) {
            var id_cargo = $('#txt_id_cargo').val();
            cargar_selects_aptitud(id_cargo, id);
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_aptitudC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: button
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_plaza_aptitudes').hide().html(response).fadeIn(400);
                }
            });
        }

        function insertar_editar_aptitud_plaza() {
            var tecnica = $('#ddl_habilidades_tecnicas').val();
            var blanda = $('#ddl_habilidades_blandas').val();

            if (!tecnica && !blanda) {
                Swal.fire('', 'Debe seleccionar al menos una habilidad técnica o una habilidad blanda.', 'warning');
                return;
            }

            var parametros = {
                'cn_hab_id_tecnica': tecnica || '',
                'cn_hab_id_blanda': blanda || '',
                'cn_pla_id': '<?= $_id_plaza ?>',
                '_id': $('#th_reqa_experiencia_id').val()
            };

            insertar_aptitud_plaza(parametros);
        }

        function insertar_aptitud_plaza(parametros) {

            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_aptitudC.php?insertar_editar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        $('#modal_agregar_aptitud').modal('hide');
                        limpiar_campos_aptitud_modal();
                        cargar_plaza_aptitudes('<?= $_id_plaza ?>');
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
            });
        }

        function eliminar_aptitud(id) {

            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_aptitudC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                        $('#modal_agregar_aptitud').modal('hide');
                        limpiar_campos_aptitud_modal();
                        cargar_plaza_aptitudes('<?= $_id_plaza ?>');
                    }
                }
            });
        }
    </script>
<?php } ?>

<input type="hidden" name="txt_id_cargo" id="txt_id_cargo" value="<?= $_id ?>">

<div class="" id="pnl_aptitudes"></div>

<?php if ($es_plaza) { ?>
    </br>
    <strong>Requisitos Adicionales</strong>
    </br>
    <div class="" id="pnl_plaza_aptitudes">
    </div>
<?php } ?>


<div class="modal fade" id="modal_agregar_aptitud" tabindex="-1" aria-hidden="true" role="dialog"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_aptitud">
                        <i class='bx bx-brain me-2'></i>Aptitudes y Habilidades
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    onclick="limpiar_campos_aptitud_modal()"></button>
            </div>

            <form id="form_aptitud">
                <input type="hidden" name="th_reqa_experiencia_id" id="th_reqa_experiencia_id">

                <div class="modal-body">
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label for="ddl_habilidades_tecnicas" class="form-label fw-semibold fs-7">
                                <i class='bx bx-chip me-1 text-primary'></i>Habilidad Técnica
                            </label>
                            <select class="form-select" id="ddl_habilidades_tecnicas"
                                name="ddl_habilidades_tecnicas" style="width: 100%;">
                                <option value="">-- Seleccione --</option>
                            </select>
                            <small class="text-muted">Opcional si selecciona una blanda</small>
                        </div>

                        <div class="col-md-6">
                            <label for="ddl_habilidades_blandas" class="form-label fw-semibold fs-7">
                                <i class='bx bx-user-voice me-1 text-info'></i>Habilidad Blanda / Iniciativa
                            </label>
                            <select class="form-select" id="ddl_habilidades_blandas"
                                name="ddl_habilidades_blandas" style="width: 100%;">
                                <option value="">-- Seleccione --</option>
                            </select>
                            <small class="text-muted">Opcional si selecciona una técnica</small>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <div class="ms-auto">
                        <?php if ($es_plaza) { ?>
                            <button type="button" class="btn btn-secondary btn-sm me-2"
                                data-bs-dismiss="modal"
                                onclick="limpiar_campos_aptitud_modal()">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm px-4"
                                id="btn_guardar_aptitud_plaza"
                                onclick="insertar_editar_aptitud_plaza()">
                                <i class="bx bx-save"></i> Guardar
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-secondary btn-sm me-2"
                                data-bs-dismiss="modal"
                                onclick="limpiar_campos_aptitud_modal()">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm px-4"
                                id="btn_guardar_aptitud"
                                onclick="insertar_editar_aptitud()">
                                <i class="bx bx-save"></i> Guardar
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>