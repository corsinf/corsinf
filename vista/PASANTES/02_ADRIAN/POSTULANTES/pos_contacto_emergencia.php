<script>
    $(document).ready(function() {

        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_contactos_emergencia(<?= $id ?>);
        <?php } ?>

    });

    //Formación Académica
    function cargar_datos_contactos_emergencia(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contacto_emergenciaC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#tbl_contacto_emergencia').html(response);
            }
        });
    }

    //Contacto de Emergencia
    function insertar_editar_contacto_emergencia() {

        var txt_nombre_contacto_emergencia = $('#txt_nombre_contacto_emergencia').val();
        var txt_telefono_contacto_emergencia = $('#txt_telefono_contacto_emergencia').val();
        var txt_id_postulante = '<?= $id ?>';
        var txt_id_contacto_emergencia = $('#txt_id_contacto_emergencia').val();

        var parametros_contacto_emergencia = {
            '_id': txt_id_contacto_emergencia,
            'txt_id_postulante': txt_id_postulante,
            'txt_nombre_contacto_emergencia': txt_nombre_contacto_emergencia,
            'txt_telefono_contacto_emergencia': txt_telefono_contacto_emergencia,
        };

        if ($("#form_contacto_emergencia").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar_contacto_emergencia(parametros_contacto_emergencia);
        }
    }

    function insertar_contacto_emergencia(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contacto_emergenciaC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_contactos_emergencia(<?= $id ?>);
                    <?php } ?>
                    limpiar_campos_contacto_emergencia_modal();
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function guardar_cambios_contacto_emergencia(id) {

        var txt_nombre_contacto_emergencia = $('#txt_nombre_contacto_emergencia_' + id).val();
        var txt_telefono_contacto_emergencia = $('#txt_telefono_contacto_emergencia_' + id).val();
        var txt_id_postulante = '<?= $id ?>';
        var txt_id_contacto_emergencia = $('#txt_id_contacto_emergencia_' + id).val();

        var parametros_guardar_contacto_emergencia = {
            '_id': txt_id_contacto_emergencia,
            'txt_id_postulante': txt_id_postulante,
            'txt_nombre_contacto_emergencia': txt_nombre_contacto_emergencia,
            'txt_telefono_contacto_emergencia': txt_telefono_contacto_emergencia,
        };

        if ((txt_nombre_contacto_emergencia) != '' && (txt_nombre_contacto_emergencia) != null &&
            (txt_telefono_contacto_emergencia) != '' && (txt_telefono_contacto_emergencia) != null) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            //console.log(parametros_guardar_contacto_emergencia);
            guardar_contacto_emergencia(parametros_guardar_contacto_emergencia);
        } else {
            Swal.fire('', 'No se pueden guardar campos vacios', 'warning')
        }
    }

    function guardar_contacto_emergencia(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contacto_emergenciaC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_contactos_emergencia(<?= $id ?>);
                    <?php } ?>
                    limpiar_campos_contacto_emergencia_modal();
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function mostrar_contacto_emergencia(id) {

        $('#span_nombre_' + id).hide();
        $('#span_telefono_' + id).hide();
        $('#txt_nombre_contacto_emergencia_' + id).show().focus();
        $('#txt_telefono_contacto_emergencia_' + id).show();

        $('#btn_editar_' + id).hide();
        $('#btn_guardar_' + id).show();
    }

    function delete_datos_contacto_emergencia(id) {
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                eliminar_contacto_emergencia(id);
            }
        })
    }

    function eliminar_contacto_emergencia(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contacto_emergenciaC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_contactos_emergencia(<?= $id ?>);
                    <?php } ?>
                    limpiar_campos_contacto_emergencia_modal();
                }
            }
        });
    }

    function limpiar_campos_contacto_emergencia_modal() {
        $('#txt_nombre_contacto_emergencia').val('');
        $('#txt_telefono_contacto_emergencia').val('');
        $('#txt_id_contacto_emergencia').val('');

        //Limpiar validaciones
        $("#form_contacto_emergencia").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');

    }
</script>

<!-- Modal para los contactos de Emergencia -->
<div class="modal" id="modal_contacto_emergencia" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold">Contactos de Emergencia</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_contacto_emergencia_modal();"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="form_contacto_emergencia">
                    <div class="pnl_contacto_emergencia">
                        <input type="hidden" id="txt_id_contacto_emergencia">

                        <div class="row mb-col">
                            <div class="col-md-12">
                                <label for="txt_nombre_contacto_emergencia" class="form-label form-label-sm">Nombre del contacto de Emergencia </label>
                                <input type="text" class="form-control form-control-sm txt_nombre_contacto_emergencia no_caracteres" name="txt_nombre_contacto_emergencia" id="txt_nombre_contacto_emergencia" value="" placeholder="Escriba el nombre de un contacto de emergencia" maxlength="100">
                            </div>
                        </div>

                        <div class="row mb-col">
                            <div class="col-md-12">
                                <label for="txt_telefono_contacto_emergencia" class="form-label form-label-sm">Teléfono del contacto de Emergencia </label>
                                <input type="text" class="form-control form-control-sm txt_telefono_contacto_emergencia solo_numeros_int" name="txt_telefono_contacto_emergencia" id="txt_telefono_contacto_emergencia" value="" placeholder="Escriba el número de un contacto de emergencia" maxlength="15">
                            </div>
                        </div>
                    </div>

                    <div id="btn_agregar_editar_contacto_emergencia" class="d-flex justify-content-end align-items-end mt-1">
                        <button type="button" class="btn btn-sm btn-success" id="btn_agregar_contacto_emergencia" onclick="insertar_editar_contacto_emergencia();"><i class="bx bx-plus me-0"></i> Agregar</button>
                    </div>
                </form>
                
                <hr>
                
                <div class="table-responsive">
                    <form id='form_contacto_emergencia_1'>
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nombre del Contacto</th>
                                    <th>Teléfono del Contacto</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_contacto_emergencia">

                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('txt_nombre_contacto_emergencia');
        agregar_asterisco_campo_obligatorio('txt_telefono_contacto_emergencia');

        //Validación Contacto de Emergencia
        $("#form_contacto_emergencia").validate({
            rules: {
                txt_nombre_contacto_emergencia: {
                    required: true,
                    maxlength: 100
                },
                txt_telefono_contacto_emergencia: {
                    required: true,
                    maxlength: 15
                },
            },
            messages: {
                txt_nombre_contacto_emergencia: {
                    required: "Por favor ingrese el nombre de su contacto",
                },
                txt_telefono_contacto_emergencia: {
                    required: "Por favor ingrese el teléfono de su contacto",
                },
            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

            }
        });
    });
</script>