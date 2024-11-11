<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_estado_laboral(<?= $id ?>);
        <?php } ?>

        //cargar_datos_masivos(1);
    });

    /* function cargar_datos_masivos(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_estado_laboralC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
            }
        });
    } */

    function cargar_datos_estado_laboral(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_estado_laboralC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_estado_laboral').html(response);
            }
        });
    }

    function cargar_datos_modal_estado_laboral(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_estado_laboralC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#ddl_estado_laboral').val(response[0].th_est_estado_laboral);
                $('#txt_fecha_contratacion_estado').val(response[0].th_est_fecha_contratacion);
                $('#txt_fecha_salida_estado').val(response[0].th_est_fecha_salida);

                $('#txt_experiencia_estado_id').val(response[0]._id);
            }
        });
    }

    function ocultar_opciones_estado() {
        var select_opciones_estado = $('#ddl_estado_laboral');
        var valor_seleccionado = select_opciones_estado.val();

        $('#txt_fecha_contratacion_estado').prop('disabled', false);
        $('#txt_fecha_salida_estado').prop('disabled', false);

        if (valor_seleccionado === "Freelancer" || valor_seleccionado === "Autonomo") {
            $('#txt_fecha_contratacion_estado').prop('disabled', true);
            $('#txt_fecha_salida_estado').prop('disabled', true);
        }
    }

    //Estado Laboral
    function insertar_editar_estado_laboral() {
        var ddl_estado_laboral = $('#ddl_estado_laboral').val();
        var txt_fecha_contratacion_estado = $('#txt_fecha_contratacion_estado').val();
        var txt_fecha_salida_estado = $('#txt_fecha_salida_estado').val();
        var id_postulante = '<?= $id ?>';
        var txt_experiencia_estado_id = $('#txt_experiencia_estado_id').val();

        var parametros_estado_laboral = {
            'id_postulante': id_postulante,
            'ddl_estado_laboral': ddl_estado_laboral,
            'txt_fecha_contratacion_estado': txt_fecha_contratacion_estado,
            'txt_fecha_salida_estado': txt_fecha_salida_estado,
            '_id': txt_experiencia_estado_id,
        }

        if ($("#form_estado_laboral").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_estado_laboral);
            insertar_estado_laboral(parametros_estado_laboral);
        }

    }

    function insertar_estado_laboral(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_estado_laboralC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_estado_laboral(<?= $id ?>);
                        limpiar_campos_estado_laboral_modal();
                    <?php } ?>
                    $('#modal_estado_laboral').modal('hide');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function abrir_modal_estado_laboral(id) {
        cargar_datos_modal_estado_laboral(id);

        $('#modal_estado_laboral').modal('show');
        $('#lbl_titulo_experiencia_laboral').html('Editar Estado Laboral');
        $('#btn_guardar_estado_laboral').html('Editar');

    }

    function limpiar_campos_estado_laboral_modal() {
        $('#form_estado_laboral').validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');

        $('#ddl_estado_laboral').val('');
        $('#txt_fecha_contratacion_estado').val('');
        $('#txt_fecha_salida_estado').val('');
        $('#txt_experiencia_estado_id').val('');
    }
</script>

<div id="pnl_estado_laboral">

</div>

<!-- <div class="row pt-3 mb-col">
    <div class="col-md-12">
        <h6 class="fw-bold mb-2">Inactivo</h6>
        <p>Ene 2022 - Oct 2023</p>
    </div>
</div>
<div class="row pt-3 mb-col">
    <div class="col-6">
        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
    </div>
</div> -->

<!-- Modal para agregar estado laboral-->
<div class="modal" id="modal_estado_laboral" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue su estado laboral</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_estado_laboral_modal()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_estado_laboral">
                <input type="hidden" name="txt_experiencia_estado_id" id="txt_experiencia_estado_id">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_estado_laboral" class="form-label form-label-sm">Estado laboral: <label style="color: red;">*</label></label>
                            <select class="form-select form-select-sm" id="ddl_estado_laboral" name="ddl_estado_laboral" onchange="ocultar_opciones_estado();" required>
                                <option selected disabled value="">-- Selecciona un Estado Laboral -- <label style="color: red;">*</label></option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                                <option value="Prueba">En prueba</option>
                                <option value="Pasante">Pasante</option>
                                <option value="Freelancer">Freelancer</option>
                                <option value="Autonomo">Autónomo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_contratacion_estado" class="form-label form-label-sm">Fecha de contratación <label style="color: red;">*</label></label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_contratacion_estado" id="txt_fecha_contratacion_estado">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_salida_estado" class="form-label form-label-sm">Fecha de salida <label style="color: red;">*</label></label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_salida_estado" id="txt_fecha_salida_estado">
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_estado_laboral" onclick="insertar_editar_estado_laboral();">Guardar Estado Laboral</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        //Validación Estado Laboral
        $("#form_estado_laboral").validate({
            rules: {
                ddl_estado_laboral: {
                    required: true,
                },
                txt_fecha_contratacion_estado: {
                    required: true,
                },
                txt_fecha_salida_estado: {
                    required: true,
                },
            },
            messages: {
                ddl_estado_laboral: {
                    required: "Por favor seleccione su estado laboral",
                },
                txt_fecha_contratacion_estado: {
                    required: "Por favor ingrese la fecha de su contratación",
                },
                txt_fecha_salida_estado: {
                    required: "Por favor ingrese la fecha de su salida",
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
    })
</script>