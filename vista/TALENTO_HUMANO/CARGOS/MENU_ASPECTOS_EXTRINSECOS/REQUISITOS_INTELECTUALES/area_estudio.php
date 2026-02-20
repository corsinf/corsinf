<script>
    $(document).ready(function() {
        var id_cargo = $('#txt_id_cargo').val();
        cargar_area_estudios(id_cargo);
        <?php if ($es_plaza) { ?>
            cargar_plaza_area_estudios('<?= $_id_plaza ?>');
        <?php } ?>
    });

    function cargar_selects_area_estudios(car_id, pla_id) {

        var id_cargo = $('#txt_id_cargo').val();
        var pla_id = 0;
        <?php if ($es_plaza) { ?>
            pla_id = '<?= $_id_plaza ?>';
        <?php } ?>

        data_extra = {
            'car_id': id_cargo,
            'pla_id': pla_id
        }

        url_area_estudioC = '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_area_estudioC.php?buscar_area_estudio_car_pla=true';
        cargar_select2_url('ddl_area_estudio', url_area_estudioC, '', '#modal_area_estudios', 0, data_extra);

    }

    function cargar_area_estudios(id, button = true) {
        cargar_selects_area_estudios(id);

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_area_estudioC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id,
                button_delete: button
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_area_estudios').hide().html(response).fadeIn(400);
            }
        });
    }

    function cargar_datos_modal_area_estudios(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_area_estudioC.php?listar=true', // Ajustar ruta si es distinta
            type: 'post',
            data: {
                id_area_estudio: id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    // Cargar el nivel académico en el Select2
                    $('#ddl_area_estudio').append($('<option>', {
                        value: response[0].id_area_estudio,
                        text: response[0].area_estudio_descripcion,
                        selected: true
                    })).trigger('change');

                    $('#id_reqi_cargo_estudio').val(response[0]._id);
                }
            }
        });
    }

    function insertar_editar_area_estudios() {
        var ddl_area_estudio = $('#ddl_area_estudio').val();
        var id_reqi_cargo_estudio = $('#id_reqi_cargo_estudio').val();
        var id_cargo = $('#txt_id_cargo').val();

        var parametros = {
            'ddl_area_estudio': ddl_area_estudio,
            'id_cargo': id_cargo,
            '_id': id_reqi_cargo_estudio,
        }

        if ($("#form_area_estudios").valid()) {
            insertar_area_estudios(parametros);
        }
    }

    function insertar_area_estudios(parametros) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_area_estudioC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_area_estudios').modal('hide');
                    limpiar_campos_area_estudios_modal();
                    cargar_area_estudios(id_cargo);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_area_estudios(id) {

        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este requisito de instrucción?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_area_estudios(id);
            }
        })
    }

    function eliminar_area_estudios(id) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_area_estudioC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_area_estudios').modal('hide');
                    limpiar_campos_area_estudios_modal();
                    cargar_area_estudios(id_cargo);
                }
            }
        });
    }

    function abrir_modal_area_estudios(id) {
        limpiar_campos_area_estudios_modal();
        if (id) {
            cargar_datos_modal_area_estudios(id);
            $('#lbl_titulo_area_estudios').html('<i class="bx bx-edit me-2"></i>Editar Área de Estudios');
            $('#btn_guardar_area_estudios').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_area_estudios').show();
        } else {
            $('#lbl_titulo_area_estudios').html('<i class="bx bx-plus me-2"></i>Agregar Área de Estudios');
            $('#btn_guardar_area_estudios').html('<i class="bx bx-save"></i> Guardar');
            $('#btn_eliminar_area_estudios').hide();
        }
        $('#modal_area_estudios').modal('show');
    }

    function limpiar_campos_area_estudios_modal() {
        $('#form_area_estudios').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#id_reqi_cargo_estudio').val('');
        $('#ddl_area_estudio').val(null).trigger('change');

        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<?php if ($es_plaza) { ?>
    <script>
        function cargar_plaza_area_estudios(id, button = true) {
            var id_cargo = $('#txt_id_cargo').val();
            cargar_selects_area_estudios(id_cargo, id);
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_instruccionC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: button
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_plaza_area_estudios').hide().html(response).fadeIn(400);
                }
            });
        }

        function insertar_editar_area_estudios_plaza() {
            var ddl_area_estudio = $('#ddl_area_estudio').val();
            var id_reqi_cargo_estudio = $('#id_reqi_cargo_estudio').val();
            var cn_pla_id = '<?= $_id_plaza ?>';

            var parametros = {
                'id_nivel_academico': ddl_area_estudio,
                'cn_pla_id': cn_pla_id,
                '_id': id_reqi_cargo_estudio,
            }

            if ($("#form_area_estudios").valid()) {
                insertar_area_estudios_plaza(parametros);
            }
        }

        function insertar_area_estudios_plaza(parametros) {

            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_instruccionC.php?insertar_editar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        $('#modal_area_estudios').modal('hide');
                        limpiar_campos_area_estudios_modal();
                        cargar_plaza_area_estudios('<?= $_id_plaza ?>');
                    } else {
                        Swal.fire('', 'Operación fallida', 'warning');
                    }
                }
            });
        }

        function delete_datos_area_estudios(id) {

            Swal.fire({
                title: '¿Eliminar Registro?',
                text: "¿Está seguro de eliminar este requisito de instrucción?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    eliminar_area_estudios(id);
                }
            })
        }

        function eliminar_area_estudios(id) {

            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_instruccionC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                        $('#modal_area_estudios').modal('hide');
                        limpiar_campos_area_estudios_modal();
                        cargar_plaza_area_estudios('<?= $_id_plaza ?>');
                    }
                }
            });
        }
    </script>
<?php } ?>

<input type="hidden" name="txt_id_cargo" id="txt_id_cargo" value="<?= $_id ?>">
<div class="" id="pnl_area_estudios">

</div>

<?php if ($es_plaza) { ?>
    </br>
    <strong>Requisitos Adicionales</strong>
    </br>
    <div class="" id="pnl_plaza_area_estudios">
    </div>
<?php } ?>


<div class="modal fade" id="modal_area_estudios" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_area_estudios">
                        <i class='bx bx-briefcase me-2'></i>Área de Estudio
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_area_estudios_modal()"></button>
            </div>

            <form id="form_area_estudios" class="needs-validation">
                <input type="hidden" name="id_reqi_cargo_estudio" id="id_reqi_cargo_estudio">

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_area_estudio" class="form-label fw-semibold fs-7">Área de Estudio </label>
                            <select class="form-select select2-validation" name="ddl_area_estudio" id="ddl_area_estudio" style="width: 100%;">
                                <option value="">-- Seleccione --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_area_estudio"></label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                        <div class="ms-auto">
                            <?php if ($es_plaza) { ?>
                                <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_area_estudios_modal()">Cancelar</button>
                                <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_area_estudios_plaza" onclick="insertar_editar_area_estudios_plaza() ">
                                    <i class="bx bx-save"></i> Guardar
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_area_estudios_modal()">Cancelar</button>
                                <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_area_estudios" onclick="insertar_editar_area_estudios() ">
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
        agregar_asterisco_campo_obligatorio('ddl_area_estudio');

        $("#form_area_estudios").validate({
            ignore: [], // IMPORTANTE: Para que valide campos ocultos (como el select de select2)
            rules: {
                ddl_area_estudio: {
                    required: true
                }
            },

            errorPlacement: function(error, element) {
                // Si el elemento es un Select2, ponemos el error después del contenedor de Select2
                if (element.hasClass('select2-validation') || element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
                // Si es select2, podemos forzar un refresh visual si fuera necesario
            },
            unhighlight: function(element) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
            submitHandler: () => false
        });

    });
</script>