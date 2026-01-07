<script>
    $(document).ready(function() {
        cargar_datos_experiencia_laboral(<?= $id_postulante ?>);
        // console.log('Cargando experiencia laboral del postulante ID: <?= $id_postulante ?>');
    });

    //Experiencia Laboral
    function cargar_datos_experiencia_laboral(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_experiencia_laboralC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_experiencia_laboral').html(response);
            }
        });
    }

    function cargar_datos_modal_experiencia_laboral(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_experiencia_laboralC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_nombre_empresa').val(response[0].th_expl_nombre_empresa);
                $('#txt_cargos_ocupados').val(response[0].th_expl_cargos_ocupados);
                $('#txt_fecha_inicio_laboral').val(response[0].th_expl_fecha_inicio_experiencia);

                var fecha_fin_laboral = response[0].th_expl_fecha_fin_experiencia;

                if (fecha_fin_laboral === '') {
                    var hoy = new Date();
                    var dia = String(hoy.getDate()).padStart(2, '0');
                    var mes = String(hoy.getMonth() + 1).padStart(2, '0');
                    var year = hoy.getFullYear();

                    var fecha_actual_laboral = year + '-' + mes + '-' + dia;
                    $('#txt_fecha_final_laboral').val(fecha_actual_laboral);
                    $('#txt_fecha_final_laboral').prop('disabled', true);
                    $('#cbx_fecha_final_laboral').prop('checked', true);
                } else {
                    $('#cbx_fecha_final_laboral').prop('checked', false);
                    $('#txt_fecha_final_laboral').prop('disabled', false);
                    $('#txt_fecha_final_laboral').val(fecha_fin_laboral);
                }

                $('#txt_responsabilidades_logros').val(response[0].th_expl_responsabilidades_logros);
                $('#txt_experiencia_id').val(response[0]._id);
                $('#txt_sueldo').val(response[0].th_expl_sueldo);
            }
        });
    }

    function insertar_editar_experiencia_laboral() {
        var txt_nombre_empresa = $('#txt_nombre_empresa').val();
        var txt_cargos_ocupados = $('#txt_cargos_ocupados').val();
        var txt_fecha_inicio_laboral = $('#txt_fecha_inicio_laboral').val();
        var cbx_fecha_final_laboral = $('#cbx_fecha_final_laboral').prop('checked') ? 1 : 0;
        var txt_fecha_final_laboral = '';

        if ($('#cbx_fecha_final_laboral').is(':checked')) {
            txt_fecha_final_laboral = '';
        } else {
            txt_fecha_final_laboral = $('#txt_fecha_final_laboral').val();
        }

        var txt_responsabilidades_logros = $('#txt_responsabilidades_logros').val();
        var txt_id_postulante = '<?= $id_postulante ?>';
        var txt_id_experiencia_laboral = $('#txt_experiencia_id').val();
        var txt_sueldo = $('#txt_sueldo').val();

        var parametros_experiencia_laboral = {
            '_id': txt_id_experiencia_laboral,
            'txt_id_postulante': txt_id_postulante,
            'txt_nombre_empresa': txt_nombre_empresa,
            'txt_cargos_ocupados': txt_cargos_ocupados,
            'txt_fecha_inicio_laboral': txt_fecha_inicio_laboral,
            'txt_fecha_final_laboral': txt_fecha_final_laboral,
            'cbx_fecha_final_laboral': cbx_fecha_final_laboral,
            'txt_responsabilidades_logros': txt_responsabilidades_logros,
            'txt_sueldo': txt_sueldo,
        }

        if ($("#form_experiencia_laboral").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            //console.log(parametros_experiencia_laboral)
            insertar_experiencia_laboral(parametros_experiencia_laboral);
        }
    }

    function insertar_experiencia_laboral(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_experiencia_laboralC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    cargar_datos_experiencia_laboral('<?= $id_postulante ?>');
                    limpiar_campos_experiencia_laboral_modal();
                    $('#modal_agregar_experiencia').modal('hide');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    //* Función para editar el registro de formación academica
    function abrir_modal_experiencia_laboral(id) {
        cargar_datos_modal_experiencia_laboral(id);
        $('#modal_agregar_experiencia').modal('show');
        $('#lbl_titulo_experiencia_laboral').html('Editar Experiencia Laboral');
        $('#btn_guardar_experiencia').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_experiencia').show();

    }

    function delete_datos_experiencia_laboral() {
        //Para revisar y enviar el dato como parametro 
        id = $('#txt_experiencia_id').val();
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
                eliminar_experiencia_laboral(id);
            }
        })
    }

    function eliminar_experiencia_laboral(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_experiencia_laboralC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_experiencia_laboral('<?= $id_postulante ?>');
                    limpiar_campos_experiencia_laboral_modal();
                    $('#modal_agregar_experiencia').modal('hide');
                }
            }
        });
    }

    function limpiar_campos_experiencia_laboral_modal() {
        $('#form_experiencia_laboral').validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        $('#txt_nombre_empresa').val('');
        $('#txt_cargos_ocupados').val('');
        $('#txt_fecha_inicio_laboral').val('');
        $('#txt_fecha_final_laboral').val('');
        $('#txt_fecha_final_laboral').prop('disabled', false);
        $('#cbx_fecha_final_laboral').prop('checked', false);
        $('#txt_responsabilidades_logros').val('');
        $('#txt_experiencia_id').val('')
        $('#txt_sueldo').val('')
        //Cambiar texto
        $('#lbl_titulo_experiencia_laboral').html('Agregar Experiencia Laboral');
        $('#btn_guardar_experiencia').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_experiencia').hide();

    }

    function validar_fechas_exp_prev() {
        var fecha_inicio = $('#txt_fecha_inicio_laboral').val();
        var fecha_final = $('#txt_fecha_final_laboral').val();
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
                $('#txt_fecha_final_laboral').val('');
                $('#cbx_fecha_final_laboral').prop('checked', false);
                $('#txt_fecha_final_laboral').prop('disabled', false);
            }
            if (Date.parse(fecha_inicio) > Date.parse(fecha_final)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha de inicio no puede ser mayor a la fecha final.",
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_inicio_laboral').val('');
                $('#cbx_fecha_final_laboral').prop('checked', false);
                $('#txt_fecha_final_laboral').prop('disabled', false);
            }
        }

        //* Validar que la fecha de inicio y final no sean mayores a la fecha actual
        if (fecha_inicio && Date.parse(fecha_inicio) > Date.parse(fecha_actual)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha de inicio no puede ser mayor a la fecha actual.",
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_inicio_laboral').val('');
        }

        if (fecha_final && Date.parse(fecha_final) > Date.parse(fecha_actual)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha de finalización no puede ser mayor a la fecha actual.",
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_final_laboral').val('');
            $('#cbx_fecha_final_laboral').prop('checked', false);
            $('#txt_fecha_final_laboral').prop('disabled', false);
        }
    }
</script>

<div id="pnl_experiencia_laboral">
</div>

<!-- Modal para agregar experiencia laboral-->
<div class="modal" id="modal_agregar_experiencia" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_experiencia_laboral">Agregar Experiencia Laboral</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_experiencia_laboral_modal()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_experiencia_laboral">
                <input type="hidden" id="txt_experiencia_id">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_nombre_empresa" class="form-label form-label-sm">Nombre Empresa </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_nombre_empresa" id="txt_nombre_empresa" maxlength="100">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_cargos_ocupados" class="form-label form-label-sm">Cargos Ocupados </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_cargos_ocupados" id="txt_cargos_ocupados" maxlength="100">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_sueldo" class="form-label form-label-sm">
                                Sueldo
                            </label>
                            <input
                                type="number"
                                class="form-control form-control-sm"
                                name="txt_sueldo"
                                id="txt_sueldo"
                                step="0.01"
                                min="0">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_inicio_laboral" class="form-label form-label-sm">Fecha Inicio </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_inicio_laboral" id="txt_fecha_inicio_laboral" onchange="checkbox_actualidad_exp_prev();">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_final_laboral" class="form-label form-label-sm">Fecha de Finalización </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_final_laboral" id="txt_fecha_final_laboral" onchange="checkbox_actualidad_exp_prev();">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col md-12">
                            <input type="checkbox" class="form-check-input" name="cbx_fecha_final_laboral" id="cbx_fecha_final_laboral" onchange="checkbox_actualidad_exp_prev();">
                            <label for="cbx_fecha_final_laboral" class="form-label form-label-sm">Actualidad</label>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_responsabilidades_logros" class="form-label form-label-sm">Descripción de Responsabilidades y/o Logros</label>
                            <textarea type="text" class="form-control form-control-sm no_caracteres" name="txt_responsabilidades_logros" id="txt_responsabilidades_logros" maxlength="300"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_experiencia" onclick="validar_fechas_exp_prev(); insertar_editar_experiencia_laboral();"><i class="bx bx-save"></i>Agregar</button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_experiencia" onclick="delete_datos_experiencia_laboral();"><i class="bx bx-trash"></i>Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_nombre_empresa');
        agregar_asterisco_campo_obligatorio('txt_cargos_ocupados');
        agregar_asterisco_campo_obligatorio('txt_fecha_inicio_laboral');
        agregar_asterisco_campo_obligatorio('txt_fecha_final_laboral');
        agregar_asterisco_campo_obligatorio('txt_responsabilidades_logros');

        //! Validación Experiencia Laboral
        $("#form_experiencia_laboral").validate({
            rules: {
                txt_nombre_empresa: {
                    required: true,
                },
                txt_cargos_ocupados: {
                    required: true,
                },
                txt_fecha_inicio_laboral: {
                    required: true,
                },
                txt_fecha_final_laboral: {
                    required: true,
                },
                txt_responsabilidades_logros: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_empresa: {
                    required: "Por favor ingrese el nombre de la empresa",
                },
                txt_cargos_ocupados: {
                    required: "Por favor ingrese los cargos ocupados",
                },
                txt_fecha_inicio_laboral: {
                    required: "Por favor ingrese la fecha en la que iniciaron sus funciones",
                },
                txt_fecha_final_laboral: {
                    required: "Por favor ingrese la fecha de finalización o seleccione 'Actualidad' si sigue trabajando.",
                },
                txt_responsabilidades_logros: {
                    required: "Por favor ingrese sus responsabilidades y logros",
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

    function checkbox_actualidad_exp_prev() {
        if ($('#cbx_fecha_final_laboral').is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();

            var fecha_actual = year + '-' + mes + '-' + dia;
            $('#txt_fecha_final_laboral').val(fecha_actual);

            $('#txt_fecha_final_laboral').prop('disabled', true);
            $('#txt_fecha_final_laboral').rules("remove", "required");

            // Agregar clase 'is-valid' para poner el campo en verde
            $('#txt_fecha_final_laboral').addClass('is-valid');
            $('#txt_fecha_final_laboral').removeClass('is-invalid');

        } else {
            // Solo limpiar el campo si estaba previamente deshabilitado
            if ($('#txt_fecha_final_laboral').prop('disabled')) {
                $('#txt_fecha_final_laboral').val('');
            }

            $('#txt_fecha_final_laboral').prop('disabled', false);
            $('#txt_fecha_final_laboral').rules("add", {
                required: true
            });
            $('#txt_fecha_final_laboral').removeClass('is-valid');
            $('#form_experiencia_laboral').validate().resetForm();
            $('.form-control').removeClass('is-valid is-invalid');
        }

        $("input[name='txt_fecha_contratacion_estado']").on("blur", function() {
            if (!verificar_fecha_inicio_fecha_fin('txt_fecha_inicio_laboral', 'txt_fecha_final_laboral')) return;
        });
        $("input[name='txt_fecha_inicio_laboral']").on("blur", function() {
            if (!verificar_fecha_inicio_fecha_fin('txt_fecha_inicio_laboral', 'txt_fecha_final_laboral')) return;
        });


    }
</script>