<script>
    $(document).ready(function() {
        cargar_datos_parientes(<?= $id_persona ?>);
        cargar_selects_parientes();
    });

    function cargar_selects_parientes() {
        url_parentescoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_parentescoC.php?buscar=true';
        cargar_select2_url('ddl_parentesco', url_parentescoC, '', '#modal_parientes');
    }

    function cargar_datos_parientes(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_parientes').html(response);
            }
        });
    }

    function cargar_datos_modal_parientes(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#ddl_parentesco').append($('<option>', {
                        value: response[0].id_parentesco,
                        text: response[0].parentesco_nombre,
                        selected: true
                    }));
                    $('#txt_nombres_pariente').val(response[0].nombres);
                    $('#txt_apellidos_pariente').val(response[0].apellidos);
                    $('#txt_pariente_id').val(response[0]._id);
                }
            }
        });
    }

    function insertar_editar_parientes() {
        var ddl_parentesco = $('#ddl_parentesco').val();
        var txt_nombres_pariente = $('#txt_nombres_pariente').val();
        var txt_apellidos_pariente = $('#txt_apellidos_pariente').val();
        var per_id = '<?= $id_persona ?>';
        var txt_pariente_id = $('#txt_pariente_id').val();

        var parametros_parientes = {
            'per_id': per_id,
            'ddl_parentesco': ddl_parentesco,
            'txt_nombres': txt_nombres_pariente,
            'txt_apellidos': txt_apellidos_pariente,
            '_id': txt_pariente_id,
        }

        if ($("#form_parientes").valid()) {
            insertar_parientes(parametros_parientes);
        }
    }

    function insertar_parientes(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_parientes').modal('hide');
                    cargar_datos_parientes(<?= $id_persona ?>);
                    limpiar_campos_parientes_modal();
                } else if (response == -4) {
                    Swal.fire('', 'Ya existe un esposo/cónyuge registrado. Solo se permite uno.', 'warning');
                } else if (response == -5) {
                    Swal.fire('', 'Ya existen 2 padres registrados. Solo se permiten 2 padres.', 'warning');
                } else if (response == -3) {
                    Swal.fire('', 'Parentesco no válido.', 'warning');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function abrir_modal_pariente(id) {
        limpiar_campos_parientes_modal(); // Primero limpia
        cargar_datos_modal_parientes(id); // Luego carga los datos
        $('#modal_parientes').modal('show');
        $('#lbl_titulo_parientes').html('Editar Pariente');
        $('#btn_guardar_parientes').html('<i class="bx bx-save"></i> Editar');
        $('#btn_eliminar_parientes').show(); // Muestra el botón eliminar
    }

    function abrir_modal_nuevo_pariente() {
        limpiar_campos_parientes_modal();
        $('#modal_parientes').modal('show');
        $('#lbl_titulo_parientes').html('Agregar Pariente');
        $('#btn_guardar_parientes').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_parientes').hide(); // Oculta el botón eliminar
    }

    function delete_datos_parientes() {
        id = $('#txt_pariente_id').val();
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_parientes(id);
            }
        })
    }

    function eliminar_parientes(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    $('#modal_parientes').modal('hide');
                    cargar_datos_parientes(<?= $id_persona ?>);
                    limpiar_campos_parientes_modal();
                }
            }
        });
    }

    function limpiar_campos_parientes_modal() {
        $('#form_parientes').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_parentesco').val('').trigger('change');
        $('#txt_nombres_pariente').val('');
        $('#txt_apellidos_pariente').val('');
        $('#txt_pariente_id').val('');
        $('#lbl_titulo_parientes').html('Agregar Pariente');
        $('#btn_guardar_parientes').html('<i class="bx bx-save"></i> Agregar');
        $('#btn_eliminar_parientes').hide();
    }
</script>

<div id="pnl_parientes">
</div>

<!-- Modal Parientes -->
<div class="modal" id="modal_parientes" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_parientes">Agregar Pariente</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_parientes_modal()"></button>
            </div>
            <form id="form_parientes">
                <input type="hidden" name="txt_pariente_id" id="txt_pariente_id">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_parentesco" class="form-label form-label-sm">Parentesco:</label>
                            <select class="form-select form-select-sm" id="ddl_parentesco" name="ddl_parentesco" required>
                                <option selected disabled value="">-- Seleccione un Parentesco --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="txt_nombres_pariente" class="form-label form-label-sm">Nombres:</label>
                            <input type="text" class="form-control form-control-sm" name="txt_nombres_pariente" id="txt_nombres_pariente" required>
                        </div>
                        <div class="col-md-6">
                            <label for="txt_apellidos_pariente" class="form-label form-label-sm">Apellidos:</label>
                            <input type="text" class="form-control form-control-sm" name="txt_apellidos_pariente" id="txt_apellidos_pariente" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_parientes" onclick="insertar_editar_parientes();"><i class="bx bx-save"></i> Agregar</button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_parientes" onclick="delete_datos_parientes();"><i class="bx bx-trash"></i> Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_parentesco');
        agregar_asterisco_campo_obligatorio('txt_nombres_pariente');
        agregar_asterisco_campo_obligatorio('txt_apellidos_pariente');

        $("#form_parientes").validate({
            rules: {
                ddl_parentesco: {
                    required: true,
                },
                txt_nombres_pariente: {
                    required: true,
                },
                txt_apellidos_pariente: {
                    required: true,
                },
            },
            messages: {
                ddl_parentesco: {
                    required: "Por favor seleccione el parentesco",
                },
                txt_nombres_pariente: {
                    required: "Por favor ingrese los nombres",
                },
                txt_apellidos_pariente: {
                    required: "Por favor ingrese los apellidos",
                },
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            }
        });
    })
</script>