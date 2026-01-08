<script>
    $(document).ready(function() {

        cargar_datos_discapacidad('<?= $id_postulante ?>');
        cargar_selects_discapacidad();

    });

    function cargar_selects_discapacidad() {
        let url = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_discapacidadC.php?buscar=true';
        cargar_select2_url('ddl_discapacidad', url, '', '#modal_agregar_discapacidad');
    }

    function cargar_datos_discapacidad(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_discapacidad').html(response);
            }
        });
    }

    function cargar_datos_modal_discapacidad(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {

                $('#ddl_discapacidad').append($('<option>', {
                    value: response[0].id_discapacidad,
                    text: response[0].discapacidad,
                    selected: true
                }));
                $('#txt_porcentaje').val(response[0].th_pos_dis_porcentaje);
                $('#txt_escala').val(response[0].th_pos_dis_escala);
                $('#txt_discapacidad_id').val(response[0]._id);
            }
        });
    }

    function insertar_editar_discapacidad() {

        let parametros = {
            pos_id: '<?= $id_postulante ?>',
            ddl_discapacidad: $('#ddl_discapacidad').val(),
            txt_porcentaje: $('#txt_porcentaje').val(),
            txt_escala: $('#txt_escala').val(),
            _id: $('#txt_discapacidad_id').val()
        };

        if ($("#form_discapacidad").valid()) {
            insertar_discapacidad(parametros);
        }
    }

    function insertar_discapacidad(parametros) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadC.php?insertar=true',
            type: 'post',
            data: {
                parametros: parametros
            },
            dataType: 'json',
            success: function(response) {

                if (response == 1) {

                    Swal.fire('', 'Operación realizada con éxito.', 'success');

                    $('#modal_agregar_discapacidad').modal('hide');
                    cargar_datos_discapacidad(<?= $id_postulante ?>);
                    limpiar_campos_discapacidad();

                } else if (response == -2) {

                    Swal.fire(
                        'Atención',
                        'La discapacidad ya fue registrada para este postulante.',
                        'warning'
                    );

                } else {

                    Swal.fire(
                        'Error',
                        'No se pudo realizar la operación.',
                        'error'
                    );
                }
            },
            error: function() {
                Swal.fire(
                    'Error',
                    'Error en el servidor.',
                    'error'
                );
            }
        });
    }


    function abrir_modal_discapacidad(id = '') {

        limpiar_campos_discapacidad();

        if (id === '') {
            $('#lbl_titulo_discapacidad').html('Agregar Discapacidad');
            $('#btn_guardar_discapacidad').html('<i class="bx bx-save"></i> Agregar');
            $('#btn_eliminar_discapacidad').hide();
        } else {
            cargar_datos_modal_discapacidad(id);
            $('#lbl_titulo_discapacidad').html('Editar Discapacidad');
            $('#btn_guardar_discapacidad').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_discapacidad').show();
        }

        $('#modal_agregar_discapacidad').modal('show');
    }

    function delete_datos_discapacidad() {
        let id = $('#txt_discapacidad_id').val();

        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) eliminar_discapacidad(id);
        });
    }

    function eliminar_discapacidad(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadC.php?eliminar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro eliminado.', 'success');
                    $('#modal_agregar_discapacidad').modal('hide');
                    cargar_datos_discapacidad('<?= $id_postulante ?>');
                }
            }
        });
    }

    function limpiar_campos_discapacidad() {
        $('#form_discapacidad').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_discapacidad').val('');
        $('#txt_porcentaje').val('');
        $('#txt_escala').val('');
        $('#txt_discapacidad_id').val('');
    }
</script>

<div id="pnl_discapacidad"></div>

<div class="modal" id="modal_agregar_discapacidad" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5><small class="fw-bold" id="lbl_titulo_discapacidad">Agregar Discapacidad</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form_discapacidad">
                <input type="hidden" id="txt_discapacidad_id">

                <div class="modal-body">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label class="form-label form-label-sm">Discapacidad</label>
                            <select class="form-select form-select-sm" id="ddl_discapacidad" required>
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Porcentaje</label>
                            <input type="number" class="form-control form-control-sm"
                                id="txt_porcentaje" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Escala</label>
                            <input type="text" class="form-control form-control-sm text-uppercase"
                                id="txt_escala" maxlength="50">
                        </div>
                    </div>

                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4"
                        id="btn_guardar_discapacidad"
                        onclick="insertar_editar_discapacidad();">
                        <i class="bx bx-save"></i> Agregar
                    </button>

                    <button type="button" class="btn btn-danger btn-sm px-4"
                        id="btn_eliminar_discapacidad"
                        onclick="delete_datos_discapacidad();" style="display:none;">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('ddl_discapacidad');
        agregar_asterisco_campo_obligatorio('txt_porcentaje');

        $("#form_discapacidad").validate({
            rules: {
                ddl_discapacidad: {
                    required: true
                },
                txt_porcentaje: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100
                }
            },
            messages: {
                ddl_discapacidad: {
                    required: "Seleccione una discapacidad"
                },
                txt_porcentaje: {
                    required: "Ingrese el porcentaje",
                    min: "Mínimo 0",
                    max: "Máximo 100"
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });

    });
</script>