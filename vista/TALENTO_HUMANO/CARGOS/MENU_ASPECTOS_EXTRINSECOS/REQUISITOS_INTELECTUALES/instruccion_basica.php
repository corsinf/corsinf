<script>
    $(document).ready(function() {
        var id_cargo = $('#txt_id_cargo').val();
        cargar_instrucciones_basicas(id_cargo);
        <?php if ($es_plaza) { ?>
            cargar_plaza_instrucciones_basicas('<?= $_id_plaza ?>');
        <?php } ?>
    });

    function cargar_selects_ins_basica(car_id, pla_id) {
        var id_cargo = $('#txt_id_cargo').val();
        var pla_id = 0;
        <?php if ($es_plaza) { ?>
            pla_id = '<?= $_id_plaza ?>';
        <?php } ?>

        data_extra = {
            'car_id': id_cargo,
            'pla_id': pla_id
        };

        url_instruccionC = '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_instruccionC.php?buscar_nivel_academico=true';
        cargar_select2_url('ddl_nivel_academico', url_instruccionC, '', '#modal_instruccion_basica', 0, data_extra);
    }

    function cargar_instrucciones_basicas(id, button = true) {
        cargar_selects_ins_basica(id);

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_instruccionC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id,
                button_delete: button
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_instruccion_basica').hide().html(response).fadeIn(400);
            }
        });
    }

    function cargar_datos_modal_instruccion_basica(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_instruccionC.php?listar=true', // Ajustar ruta si es distinta
            type: 'post',
            data: {
                instruccion_id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    // Cargar el nivel académico en el Select2
                    $('#ddl_nivel_academico').append($('<option>', {
                        value: response[0].id_nivel_academico,
                        text: response[0].nivel_academico_descripcion,
                        selected: true
                    })).trigger('change');
                    $('#th_reqi_instruccion_id').val(response[0]._id);
                }
            }
        });
    }

    function insertar_editar_instruccion_basica() {
        var ddl_nivel_academico = $('#ddl_nivel_academico').val();
        var th_reqi_instruccion_id = $('#th_reqi_instruccion_id').val();
        var id_cargo = $('#txt_id_cargo').val();

        var parametros = {
            'id_nivel_academico': ddl_nivel_academico,
            'id_cargo': id_cargo,
            '_id': th_reqi_instruccion_id,
        }

        if ($("#form_instruccion_basica").valid()) {
            insertar_instruccion_basica(parametros);
        }
    }

    function insertar_instruccion_basica(parametros) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_instruccionC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_instruccion_basica').modal('hide');
                    limpiar_campos_instruccion_basica_modal();
                    cargar_instrucciones_basicas(id_cargo);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_instruccion_basica(id) {

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
                eliminar_instruccion_basica(id);
            }
        })
    }

    function eliminar_instruccion_basica(id) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_instruccionC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_instruccion_basica').modal('hide');
                    limpiar_campos_instruccion_basica_modal();
                    cargar_instrucciones_basicas(id_cargo);
                }
            }
        });
    }

    function abrir_modal_instruccion_basica(id) {
        limpiar_campos_instruccion_basica_modal();
        if (id) {
            cargar_datos_modal_instruccion_basica(id);
            $('#lbl_titulo_instruccion_basica').html('<i class="bx bx-edit me-2"></i>Editar Instrucción Básica');
            $('#btn_guardar_instruccion_basica').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_instruccion_basica').show();
        } else {
            $('#lbl_titulo_instruccion_basica').html('<i class="bx bx-plus me-2"></i>Agregar Instrucción Básica');
            $('#btn_guardar_instruccion_basica').html('<i class="bx bx-save"></i> Guardar');
            $('#btn_eliminar_instruccion_basica').hide();
        }
        $('#modal_instruccion_basica').modal('show');
    }

    function limpiar_campos_instruccion_basica_modal() {
        $('#form_instruccion_basica').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#th_reqi_instruccion_id').val('');
        $('#ddl_nivel_academico').val(null).trigger('change');

        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>
<?php if ($es_plaza) { ?>
    <script>
        function cargar_plaza_instrucciones_basicas(id, button = true) {
            var id_cargo = $('#txt_id_cargo').val();
            cargar_selects_ins_basica(id_cargo, id);
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_instruccionC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: button
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_plaza_instruccion_basica').hide().html(response).fadeIn(400);
                }
            });
        }

        function insertar_editar_instruccion_basica_plaza() {
            var ddl_nivel_academico = $('#ddl_nivel_academico').val();
            var th_reqi_instruccion_id = $('#th_reqi_instruccion_id').val();
            var cn_pla_id = '<?= $_id_plaza ?>';

            var parametros = {
                'id_nivel_academico': ddl_nivel_academico,
                'cn_pla_id': cn_pla_id,
                '_id': th_reqi_instruccion_id,
            }

            if ($("#form_instruccion_basica").valid()) {
                insertar_instruccion_basica_plaza(parametros);
            }
        }

        function insertar_instruccion_basica_plaza(parametros) {

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
                        $('#modal_instruccion_basica').modal('hide');
                        limpiar_campos_instruccion_basica_modal();
                        cargar_plaza_instrucciones_basicas('<?= $_id_plaza ?>');
                    } else {
                        Swal.fire('', 'Operación fallida', 'warning');
                    }
                }
            });
        }


        function delete_datos_instruccion_basica(id) {

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
                    eliminar_instruccion_basica(id);
                }
            })
        }

        function eliminar_instruccion_basica(id) {

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
                        $('#modal_instruccion_basica').modal('hide');
                        limpiar_campos_instruccion_basica_modal();
                        cargar_plaza_instrucciones_basicas('<?= $_id_plaza ?>');
                    }
                }
            });
        }
    </script>
<?php } ?>

<input type="hidden" name="txt_id_cargo" id="txt_id_cargo" value="<?= $_id ?>">
<div class="" id="pnl_instruccion_basica">

</div>

<?php if ($es_plaza) { ?>
    </br>
    <strong>Requisitos Adicionales</strong>
    </br>
    <div class="" id="pnl_plaza_instruccion_basica">
    </div>
<?php } ?>


<div class="modal fade" id="modal_instruccion_basica" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_instruccion_basica">
                        <i class='bx bx-briefcase me-2'></i>Instrucción Basica
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_instruccion_basica_modal()"></button>
            </div>

            <form id="form_instruccion_basica" class="needs-validation">
                <input type="hidden" name="th_reqi_instruccion_id" id="th_reqi_instruccion_id">

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="ddl_nivel_academico" class="form-label fw-semibold fs-7">Nivel Académico </label>
                            <select class="form-select select2-validation" id="ddl_nivel_academico" name="ddl_nivel_academico" required style="width: 100%;">
                                <option selected value="">-- Seleccione un nivel academino --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_nivel_academico"></label>

                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                        <div class="ms-auto">
                            <?php if ($es_plaza) { ?>
                                <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_instruccion_basica_modal()">funciona</button>
                                <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_instruccion_basica_plaza" onclick="insertar_editar_instruccion_basica_plaza() ">
                                    <i class="bx bx-save"></i> Guardar
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_instruccion_basica_modal()">Cancelar</button>
                                <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_instruccion_basica" onclick="insertar_editar_instruccion_basica() ">
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
        agregar_asterisco_campo_obligatorio('ddl_nivel_academico');

        $("#form_instruccion_basica").validate({
            ignore: [], // IMPORTANTE: Para que valide campos ocultos (como el select de select2)
            rules: {
                ddl_nivel_academico: {
                    required: true
                }
            },
            messages: {
                ddl_nivel_academico: {
                    required: "Seleccione el nivel académico"
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