<script>
    $(document).ready(function() {

        cargar_datos_comision(<?= $id_persona ?>);
        cargar_selects_comision();

    });

    function cargar_selects_comision() {
        let url_comisionC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_comisionC.php?buscar=true';
        cargar_select2_url('ddl_comision', url_comisionC, '', '#modal_agregar_comision');
    }

    function cargar_datos_comision(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?listar=true',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                $('#pnl_comision').html(response);
            }
        });
    }

    function cargar_datos_modal_comision(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?listar_modal=true',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function(response) {

                $('#ddl_comision').append($('<option>', {
                    value: response[0].id_comision,
                    text: response[0].comision_nombre,
                    selected: true
                }));

                $('#txt_comision_id').val(response[0]._id);
            }
        });
    }

    function insertar_editar_comision() {

        let parametros = {
            per_id: '<?= $id_persona ?>',
            ddl_comision: $('#ddl_comision').val(),
            _id: $('#txt_comision_id').val()
        };

        if ($("#form_comision").valid()) {
            insertar_comision(parametros);
        }
    }

    function insertar_comision(parametros) {
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_agregar_comision').modal('hide');
                    cargar_datos_comision(<?= $id_persona ?>);
                    limpiar_campos_comision_modal();
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function abrir_modal_comision(id) {
        if (id === '') {
            limpiar_campos_comision_modal();
            $('#modal_agregar_comision').modal('show');
            $('#lbl_titulo_comision').html('Agregar Comisión');
            $('#btn_guardar_comision').html('<i class="bx bx-save"></i> Agregar');
            $('#btn_eliminar_comision').hide();
        } else {
            cargar_datos_modal_comision(id);
            $('#modal_agregar_comision').modal('show');
            $('#lbl_titulo_comision').html('Editar Comisión');
            $('#btn_guardar_comision').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_comision').show();
        }
    }

    function delete_datos_comision() {
        let id = $('#txt_comision_id').val();

        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_comision(id);
            }
        });
    }

    function eliminar_comision(id) {
        $.ajax({
            data: { id: id },
            url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro eliminado.', 'success');
                    $('#modal_agregar_comision').modal('hide');
                    cargar_datos_comision(<?= $id_persona ?>);
                    limpiar_campos_comision_modal();
                }
            }
        });
    }

    function limpiar_campos_comision_modal() {
        $('#form_comision').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_comision').val('');
        $('#txt_comision_id').val('');
        $('#lbl_titulo_comision').html('Agregar Comisión');
        $('#btn_guardar_comision').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_comision').hide();
    }
</script>
<div id="pnl_comision"></div>
<div class="modal" id="modal_agregar_comision" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5>
                    <small class="text-body-secondary fw-bold" id="lbl_titulo_comision">
                        Agregar Comisión
                    </small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        onclick="limpiar_campos_comision_modal()"></button>
            </div>

            <form id="form_comision">
                <input type="hidden" id="txt_comision_id">

                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label class="form-label form-label-sm">Comisión:</label>
                            <select class="form-select form-select-sm" id="ddl_comision" required>
                                <option selected disabled value="">-- Seleccione una Comisión --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1"
                            id="btn_guardar_comision"
                            onclick="insertar_editar_comision();">
                        <i class="bx bx-save"></i> Agregar
                    </button>

                    <button type="button" style="display:none;"
                            class="btn btn-danger btn-sm px-4 m-1"
                            id="btn_eliminar_comision"
                            onclick="delete_datos_comision();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('ddl_comision');

        $("#form_comision").validate({
            rules: {
                ddl_comision: {
                    required: true
                }
            },
            messages: {
                ddl_comision: {
                    required: "Por favor seleccione una comisión"
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
