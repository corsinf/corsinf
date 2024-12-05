<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_estado_laboral(<?= $id ?>);
        <?php } ?>
    });

    //Estado Laboral
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
            //console.log(parametros_estado_laboral);
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

    function abrir_modal_estado_laboral(id) {
        cargar_datos_modal_estado_laboral(id);
        $('#modal_estado_laboral').modal('show');
        $('#lbl_titulo_estado_laboral').html('Editar Estado Laboral');
        $('#btn_guardar_estado_laboral').html('Editar');

    }

    function delete_datos_estado_laboral() {
        //Para revisar y enviar el dato como parametro 
        id = $('#txt_experiencia_estado_id').val();
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_estado_laboral(id);
            }
        })
    }

    function eliminar_estado_laboral(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_estado_laboralC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_estado_laboral(<?= $id ?>);
                        limpiar_campos_estado_laboral_modal();
                    <?php } ?>
                    $('#modal_estado_laboral').modal('hide');
                }
            }
        });
    }

    function limpiar_campos_estado_laboral_modal() {
        $('#form_estado_laboral').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_estado_laboral').val('');
        $('#txt_fecha_contratacion_estado').val('');
        $('#txt_fecha_salida_estado').val('');
        $('#txt_experiencia_estado_id').val('');
        //Cambiar texto
        $('#lbl_titulo_estado_laboral').html('Agregar Estado Laboral');
        $('#btn_guardar_estado_laboral').html('Agregar');
    }

    function validar_fechas_est_lab() {
        var fecha_inicio = $('#txt_fecha_contratacion_estado').val();
        var fecha_final = $('#txt_fecha_salida_estado').val();
        var hoy = new Date();
        var fecha_actual = hoy.toISOString().split('T')[0];
        //* Validar que la fecha final no sea menor a la fecha de inicio
        if (fecha_inicio && fecha_final) {
            if (Date.parse(fecha_final) < Date.parse(fecha_inicio)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha final no puede ser menor a la fecha de inicio.",
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_salida_estado').val('');
                $('#cbx_fecha_salida_estado').prop('checked', false);
                $('#txt_fecha_salida_estado').prop('disabled', false);
            }
            if (Date.parse(fecha_inicio) > Date.parse(fecha_final)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha de inicio no puede ser mayor a la fecha final.",
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_contratacion_estado').val('');
                $('#cbx_fecha_salida_estado').prop('checked', false);
                $('#txt_fecha_salida_estado').prop('disabled', false);
            }
        }
    }
</script>

<div id="pnl_estado_laboral">
</div>

<!-- Modal para agregar estado laboral-->
<div class="modal" id="modal_estado_laboral" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_estado_laboral">Agregar Estado Laboral</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_estado_laboral_modal()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_estado_laboral">
                <input type="hidden" name="txt_experiencia_estado_id" id="txt_experiencia_estado_id">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_estado_laboral" class="form-label form-label-sm">Estado laboral:</label>
                            <select class="form-select form-select-sm" id="ddl_estado_laboral" name="ddl_estado_laboral" onchange="ocultar_opciones_estado();" required>
                                <option selected disabled value="">-- Selecciona un Estado Laboral --</option>
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
                            <label for="txt_fecha_contratacion_estado" class="form-label form-label-sm">Fecha de contratación</label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_contratacion_estado" id="txt_fecha_contratacion_estado">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_salida_estado" class="form-label form-label-sm">Fecha de salida </label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_salida_estado" id="txt_fecha_salida_estado">
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_estado_laboral" onclick="validar_fechas_est_lab(); insertar_editar_estado_laboral();">Agregar</button>
                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_estado" onclick="delete_datos_estado_laboral();">Eliminar</button>
                </div> -->
                <div class="modal-footer d-flex justify-content-center">
                    <?php if ($id == '') { ?>
                        <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center" onclick="insertar_editar_estado_laboral(); validar_fechas_est_lab();" type="button"><i class="bx bx-save"></i> Guardar</button>
                    <?php } else { ?>
                        <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="insertar_editar_estado_laboral(); validar_fechas_est_lab();" type="button"><i class="bx bx-save"></i> Guardar</button>
                        <button class="btn btn-danger btn-sm px-4 m-1 d-flex align-items-center" onclick="delete_datos_estado_laboral()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_estado_laboral');
        agregar_asterisco_campo_obligatorio('txt_fecha_contratacion_estado');
        agregar_asterisco_campo_obligatorio('txt_fecha_salida_estado');

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

    function checkbox_actualidad_est_lab() {
        if ($('#cbx_fecha_salida_estado').is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();

            txt_fecha_contratacion_estado
            txt_fecha_salida_estado

            var fecha_actual = year + '-' + mes + '-' + dia;
            $('#txt_fecha_salida_estado').val(fecha_actual);
            txt_fecha_salida_estado
            $('#txt_fecha_salida_estado').prop('disabled', true);
            $('#txt_fecha_salida_estado').rules("remove", "required");

            // Agregar clase 'is-valid' para poner el campo en verde
            $('#txt_fecha_salida_estado').addClass('is-valid');
            $('#txt_fecha_salida_estado').removeClass('is-invalid');

        } else {
            // Solo limpiar el campo si estaba previamente deshabilitado
            if ($('#txt_fecha_salida_estado').prop('disabled')) {
                $('#txt_fecha_salida_estado').val('');
            }

            $('#txt_fecha_salida_estado').prop('disabled', false);
            $('#txt_fecha_salida_estado').rules("add", {
                required: true
            });
            $('#txt_fecha_salida_estado').removeClass('is-valid');
            $('#form_experiencia_laboral').validate().resetForm();
            $('.form-control').removeClass('is-valid is-invalid');
        }

        // Validar fechas
        validar_fechas_est_lab();
    }
</script>