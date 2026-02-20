<script>
    $(document).ready(function() {
        var id_cargo = $('#txt_id_cargo').val();
        cargar_idiomas(id_cargo);
        <?php if ($es_plaza) { ?>
            cargar_plaza_idiomas('<?= $_id_plaza ?>');
        <?php } ?>
    });

    function cargar_selects_idioma(car_id, pla_id) {
        var id_cargo = $('#txt_id_cargo').val();
        var pla_id = 0;
        <?php if ($es_plaza) { ?>
            pla_id = '<?= $_id_plaza ?>';
        <?php } ?>

        if ($('#ddl_idiomas').hasClass("select2-hidden-accessible")) {
            $('#ddl_idiomas').select2('destroy');
        }

        $('#ddl_idiomas').select2({
            dropdownParent: $('#modal_agregar_idioma'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_idiomasC.php?buscar_idiomas=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        car_id: id_cargo,
                        pla_id: pla_id,
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

        cargar_select2_url('ddl_idiomas_nivel', url_IdiomaNivelC, '', '#modal_agregar_idioma');
    }

    function cargar_idiomas(id, button = true) {
        cargar_selects_idioma(id);

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_idiomasC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id,
                button_delete: button
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_idiomas').hide().html(response).fadeIn(400);
            }
        });
    }

    function cargar_datos_modal_idioma(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_idiomasC.php?listar=true',
            type: 'post',
            data: {
                idioma_id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#ddl_idiomas').append($('<option>', {
                        value: response[0].id_idiomas,
                        text: response[0].idioma_descripcion,
                        selected: true
                    })).trigger('change');

                    $('#ddl_idiomas_nivel').append($('<option>', {
                        value: response[0].id_idiomas_nivel,
                        text: response[0].nivel_descripcion,
                        selected: true
                    })).trigger('change');

                    $('#th_reqid_experiencia_id').val(response[0]._id);
                }
            }
        });
    }

    function insertar_editar_idioma() {
        var ddl_idiomas = $('#ddl_idiomas').val();
        var ddl_idiomas_nivel = $('#ddl_idiomas_nivel').val();
        var th_reqid_experiencia_id = $('#th_reqid_experiencia_id').val();
        var id_cargo = $('#txt_id_cargo').val();

        var parametros = {
            'id_idiomas': ddl_idiomas,
            'id_idiomas_nivel': ddl_idiomas_nivel,
            'id_cargo': id_cargo,
            '_id': th_reqid_experiencia_id,
        }

        if ($("#form_idioma").valid()) {
            insertar_idioma(parametros);
        }
    }

    function insertar_idioma(parametros) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_idiomasC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_agregar_idioma').modal('hide');
                    limpiar_campos_idioma_modal();
                    cargar_idiomas(id_cargo);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_idioma(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este requisito de idioma?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_idioma(id);
            }
        })
    }

    function eliminar_idioma(id) {
        var id_cargo = $('#txt_id_cargo').val();

        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_idiomasC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_agregar_idioma').modal('hide');
                    limpiar_campos_idioma_modal();
                    cargar_idiomas(id_cargo);
                }
            }
        });
    }

    function abrir_modal_idioma(id) {
        limpiar_campos_idioma_modal();
        if (id) {
            cargar_datos_modal_idioma(id);
            $('#lbl_titulo_idioma').html('<i class="bx bx-edit me-2"></i>Editar Idioma');
            $('#btn_guardar_idioma').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_idioma').show();
        } else {
            $('#lbl_titulo_idioma').html('<i class="bx bx-plus me-2"></i>Agregar Idioma');
            $('#btn_guardar_idioma').html('<i class="bx bx-save"></i> Guardar');
            $('#btn_eliminar_idioma').hide();
        }
        $('#modal_agregar_idioma').modal('show');
    }

    function limpiar_campos_idioma_modal() {
        $('#form_idioma').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#th_reqid_experiencia_id').val('');
        $('#ddl_idiomas').val(null).trigger('change');
        $('#ddl_idiomas_nivel').val(null).trigger('change');
        $('.select2-selection').removeClass('is-valid is-invalid');
    }
</script>

<?php if ($es_plaza) { ?>
    <script>
        function cargar_plaza_idiomas(id, button = true) {
            var id_cargo = $('#txt_id_cargo').val();
            cargar_selects_idioma(id_cargo, id);
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_idiomasC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: button
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_plaza_idiomas').hide().html(response).fadeIn(400);
                }
            });
        }

        function insertar_editar_idioma_plaza() {
            var ddl_idiomas = $('#ddl_idiomas').val();
            var ddl_idiomas_nivel = $('#ddl_idiomas_nivel').val();
            var th_reqid_experiencia_id = $('#th_reqid_experiencia_id').val();
            var cn_pla_id = '<?= $_id_plaza ?>';

            var parametros = {
                'id_idiomas': ddl_idiomas,
                'id_idiomas_nivel': ddl_idiomas_nivel,
                'cn_pla_id': cn_pla_id,
                '_id': th_reqid_experiencia_id,
            }

            if ($("#form_idioma").valid()) {
                insertar_idioma_plaza(parametros);
            }
        }

        function insertar_idioma_plaza(parametros) {

            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_idiomasC.php?insertar_editar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        $('#modal_agregar_idioma').modal('hide');
                        limpiar_campos_idioma_modal();
                        cargar_plaza_idiomas('<?= $_id_plaza ?>');
                    } else {
                        Swal.fire('', 'Operación fallida', 'warning');
                    }
                }
            });
        }

        function delete_datos_idioma(id) {
            Swal.fire({
                title: '¿Eliminar Registro?',
                text: "¿Está seguro de eliminar este requisito de idioma?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    eliminar_idioma(id);
                }
            })
        }

        function eliminar_idioma(id) {

            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_idiomasC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                        $('#modal_agregar_idioma').modal('hide');
                        limpiar_campos_idioma_modal();
                        cargar_plaza_idiomas('<?= $_id_plaza ?>');
                    }
                }
            });
        }
    </script>
<?php } ?>

<input type="hidden" name="txt_id_cargo" id="txt_id_cargo" value="<?= $_id ?>">

<div class="" id="pnl_idiomas"></div>

<?php if ($es_plaza) { ?>
    </br>
    <strong>Requisitos Adicionales</strong>
    </br>
    <div class="" id="pnl_plaza_idiomas">
    </div>
<?php } ?>


<div class="modal fade" id="modal_agregar_idioma" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_idioma">
                        <i class='bx bx-world me-2'></i>Requisito de Idiomas
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_idioma_modal()"></button>
            </div>

            <form id="form_idioma" class="needs-validation">
                <input type="hidden" name="th_reqid_experiencia_id" id="th_reqid_experiencia_id">

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ddl_idiomas" class="form-label fw-semibold fs-7">Idioma </label>
                            <select class="form-select select2-validation" id="ddl_idiomas" name="ddl_idiomas" required style="width: 100%;">
                                <option selected value="">-- Seleccione un idioma --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_idiomas"></label>
                        </div>
                        <div class="col-md-6">
                            <label for="ddl_idiomas_nivel" class="form-label fw-semibold fs-7">Nivel </label>
                            <select class="form-select select2-validation" id="ddl_idiomas_nivel" name="ddl_idiomas_nivel" required style="width: 100%;">
                                <option selected value="">-- Seleccione un nivel --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_idiomas_nivel"></label>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                        <div class="ms-auto">
                            <?php if ($es_plaza) { ?>
                                <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_idioma_modal()">Cancelar</button>
                                <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_idioma_plaza" onclick="insertar_editar_idioma_plaza()">
                                    <i class="bx bx-save"></i> Guardar
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_idioma_modal()">Cancelar</button>
                                <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_idioma" onclick="insertar_editar_idioma()">
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
    let url_IdiomaNivelC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_idiomas_nivelC.php?buscar=true';

    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_idiomas');
        agregar_asterisco_campo_obligatorio('ddl_idiomas_nivel');

        $("#form_idioma").validate({
            ignore: [],
            rules: {
                ddl_idiomas: {
                    required: true
                },
                ddl_idiomas_nivel: {
                    required: true
                }
            },
            messages: {
                ddl_idiomas: {
                    required: "Seleccione el idioma"
                },
                ddl_idiomas_nivel: {
                    required: "Seleccione el nivel"
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