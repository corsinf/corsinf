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
                console.log(response);
            }
        });
    }

    function cargar_datos_modal_formacion_academica(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contacto_emergenciaC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {

                $('#txt_titulo_obtenido').val(response[0].th_fora_titulo_obtenido);
                $('#txt_institucion').val(response[0].th_fora_institución);
                $('#txt_fecha_inicio_academico').val(response[0].th_fora_fecha_inicio_formacion);
                $('#txt_fecha_final_academico').val(response[0].th_fora_fecha_fin_formacion);

                $('#txt_formacion_id').val(response[0]._id);

                console.log(response);
            }
        });
    }


    //Contacto de Emergencia
    function insertar_editar_contacto_emergencia(button) {

        var row = $(button).closest('.pnl_contacto_emergencia');

        var txt_nombre_contacto_emergencia = row.find('.txt_nombre_contacto_emergencia').val();
        var txt_telefono_contacto_emergencia = row.find('.txt_telefono_contacto_emergencia').val();
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
            console.log(parametros_contacto_emergencia);
            clonar_formulario_contacto_emergencia();
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
                        limpiar_campos_contacto_emergencia_modal();
                    <?php } ?>
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function clonar_formulario_contacto_emergencia() {
        $('.pnl_contacto_emergencia').first().clone().appendTo($('.agregar_formulario'));
        $('#txt_nombre_contacto_emergencia').val('');
        $('#txt_telefono_contacto_emergencia').val('');
        limpiar_campos_contacto_emergencia_modal();

    }

    function limpiar_campos_contacto_emergencia_modal() {
        $('#txt_nombre_contacto_emergencia').val('');
        $('#txt_telefono_contacto_emergencia').val('');

        //Limpiar validaciones
        $("#form_contacto_emergencia").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
    }
</script>

<!-- Modal para los contactos de Emergencia -->
<div class="modal" id="modal_contacto_emergencia" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Contactos de Emergencia</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_contacto_emergencia_modal();"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nombre del Contacto</th>
                                <th>Teléfono del Contacto</th>
                            </tr>
                        </thead>
                        <tbody id="tbl_contacto_emergencia">

                        </tbody>
                    </table>
                </div>
                <hr class="border-dark">
                <div class="agregar_formulario">
                    <form id="form_contacto_emergencia">
                        <div class="pnl_contacto_emergencia">
                            <div class="row">
                                <input type="text" id="txt_id_contacto_emergencia" hidden>
                                <div class="col-11">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="txt_nombre_contacto_emergencia" class="form-label form-label-sm">Nombre del contacto de Emergencia <label style="color: red;">*</label></label>
                                                <input type="text" class="form-control form-control-sm txt_nombre_contacto_emergencia" name="txt_nombre_contacto_emergencia" id="txt_nombre_contacto_emergencia" value="" placeholder="Escriba el nombre de un contacto de emergencia" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="txt_telefono_contacto_emergencia" class="form-label form-label-sm">Teléfono del contacto de Emergencia <label style="color: red;">*</label></label>
                                                <input type="text" class="form-control form-control-sm txt_telefono_contacto_emergencia" name="txt_telefono_contacto_emergencia" id="txt_telefono_contacto_emergencia" value="" placeholder="Escriba el número de un contacto de emergencia" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1 d-flex justify-content-start align-items-center mt-2">
                                    <button type="button" class="btn btn-sm btn-success" id="btn_agregar_contacto_emergencia" onclick="insertar_editar_contacto_emergencia(this);"><i class="bx bx-plus me-0"></i></button>
                                    <!-- <button type="button" class="btn btn-sm btn-danger ms-1" id="btn_eliminar_contacto_emergencia"><i class="bx bx-trash me-0"></i></button> -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>