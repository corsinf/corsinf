<script>
    $(document).ready(function() {
        cargar_datos_experiencia_laboral('<?= $id_postulante ?>');
        // console.log('Cargando experiencia laboral del postulante ID: <?= $id_postulante ?>');
        cargar_selects_experiencia_previa();
    });

    function cargar_selects_experiencia_previa() {
        url_nomina = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_nominaC.php?buscar=true';
        cargar_select2_url('ddl_nomina_experiencia', url_nomina, '', '#modal_agregar_experiencia');
    }

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
    //Experiencia Laboral
    function cargar_datos_experiencia_laboral_referencias(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_referencias_laboralesC.php?listar_modal_experiencia_referencias=true',
            type: 'post',
            data: {
                id: id
            },
            // Cambiamos a text para recibir el HTML directo o maneja el JSON adecuadamente
            dataType: 'json',
            success: function(response) {
                // Si el controlador hizo json_encode($texto), 'response' ya es el string
                if (response) {
                    $('#pnl_experencia_referencias_laborales').html(response);
                } else {
                    $('#pnl_experencia_referencias_laborales').html('<div class="alert alert-warning py-2 fs-7">No se pudo cargar la información.</div>');
                }
            },
            error: function(e) {
                console.log(e.responseText); // Esto te dirá en consola si hay un error de PHP
                $('#pnl_experencia_referencias_laborales').html("Error al cargar datos.");
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

                $('#ddl_nomina_experiencia').append($('<option>', {
                    value: response[0].id_nomina,
                    text: response[0].descripcion_nomina,
                    selected: true
                }));

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

                $('#txt_responsabilidades').val(response[0].th_expl_responsabilidades);
                $('#txt_logros').val(response[0].th_expl_logros);
                $('#txt_experiencia_id').val(response[0]._id);
                $('#txt_sueldo').val(response[0].th_expl_sueldo);

                cargar_datos_experiencia_laboral_referencias(response[0]._id);

            }
        });
    }

    function insertar_editar_experiencia_laboral() {
        var txt_nombre_empresa = $('#txt_nombre_empresa').val();
        var txt_cargos_ocupados = $('#txt_cargos_ocupados').val();
        var txt_fecha_inicio_laboral = $('#txt_fecha_inicio_laboral').val();
        var ddl_nomina_experiencia = $('#ddl_nomina_experiencia').val();
        var cbx_fecha_final_laboral = $('#cbx_fecha_final_laboral').prop('checked') ? 1 : 0;
        var txt_fecha_final_laboral = '';

        if ($('#cbx_fecha_final_laboral').is(':checked')) {
            txt_fecha_final_laboral = '';
        } else {
            txt_fecha_final_laboral = $('#txt_fecha_final_laboral').val();
        }

        var txt_responsabilidades = $('#txt_responsabilidades').val();
        var txt_logros = $('#txt_logros').val();
        var txt_id_postulante = '<?= $id_postulante ?>';
        var txt_id_experiencia_laboral = $('#txt_experiencia_id').val();
        var txt_sueldo = $('#txt_sueldo').val();

        var parametros_experiencia_laboral = {
            '_id': txt_id_experiencia_laboral,
            'txt_id_postulante': txt_id_postulante,
            'txt_id_persona': "<?= $id_persona ?? '' ?>",
            'txt_nombre_empresa': txt_nombre_empresa,
            'txt_cargos_ocupados': txt_cargos_ocupados,
            'txt_fecha_inicio_laboral': txt_fecha_inicio_laboral,
            'txt_fecha_final_laboral': txt_fecha_final_laboral,
            'cbx_fecha_final_laboral': cbx_fecha_final_laboral,
            'txt_responsabilidades': txt_responsabilidades,
            'txt_logros': txt_logros,
            'txt_sueldo': txt_sueldo,
            'ddl_nomina_experiencia': ddl_nomina_experiencia,
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
                    Swal.fire({
                        title: '¡Excelente!',
                        html: `
            <div class="text-center">
                <p class="mb-3">Operación realizada con éxito.</p>
                <div class="p-3 rounded-3" style="background-color: #f0fdf4; border: 1px dashed #22c55e;">
                    <i class="bx bx-info-circle text-success fs-4 mb-2"></i>
                    <p class="small text-muted mb-0">
                        Recuerda que puedes gestionar todas tus <b>referencias laborales</b> directamente desde cada tarjeta de experiencia usando el botón:
                    </p>
                    <span class="badge bg-success mt-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                        AGREGAR REFERENCIA
                    </span>
                </div>
            </div>
        `,
                        icon: 'success',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#198754'
                    });
                    cargar_datos_referencias_laborales('<?= $id_postulante ?>');
                    cargar_datos_experiencia_laboral('<?= $id_postulante ?>');
                    limpiar_campos_experiencia_laboral_modal();
                    $('#modal_agregar_experiencia').modal('hide');
                    cargar_datos_info_adicional(<?= $id_persona ?>);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    //* Función para editar el registro de formación academica
    function abrir_modal_experiencia_laboral(id) {
        cargar_datos_modal_experiencia_laboral(id);
        $('#pnl_experencia_referencias_laborales').slideDown();
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
                id: id,
                id_persona: '<?= $id_persona ?>',
                id_postulante: '<?= $id_postulante ?>'
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_experiencia_laboralC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_referencias_laborales('<?= $id_postulante ?>');
                    cargar_datos_experiencia_laboral('<?= $id_postulante ?>');
                    limpiar_campos_experiencia_laboral_modal();
                    $('#modal_agregar_experiencia').modal('hide');
                    cargar_datos_info_adicional(<?= $id_persona ?>);
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
        $('#ddl_nomina_experiencia').val(null).trigger('change');
        $('#cbx_fecha_final_laboral').prop('checked', false);
        $('#txt_responsabilidades').val('');
        $('#txt_logros').val('');
        $('#txt_experiencia_id').val('')
        $('#txt_sueldo').val('');
        $('#pnl_experencia_referencias_laborales').slideUp();
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

<script>
    $(document).ready(function() {
        cargar_datos_info_adicional(<?= $id_persona ?>);


    });

    function modal_referencia_experiencia(th_expl_id, empresa = '') {

        $('#txt_referencia_experiencia_id').val(th_expl_id);
        $('#txt_referencia_nombre_empresa').val('');
        $('#modal_agregar_referencia_laboral').modal('show');

    }


    function abrir_modal_experiencia_referencias_laborales(th_ref_id, th_expl_id) {

        $('#txt_referencia_experiencia_id').val(th_expl_id);
        abrir_modal_referencias_laborales(th_ref_id);
        $('#modal_agregar_referencia_laboral').modal('show');

    }


    function cargar_datos_info_adicional(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_informacion_adicionalC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_informacion_adicional').html(response);
            }
        });
    }
</script>


<style>
    #scroll_referencias::-webkit-scrollbar {
        width: 6px;
    }

    #scroll_referencias::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    #scroll_referencias::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
</style>
<div id="pnl_experiencia_laboral">
</div>


<!-- Modal para agregar experiencia laboral-->
<div class="modal fade" id="modal_agregar_experiencia" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_experiencia_laboral">
                        <i class='bx bx-briefcase-alt-2 me-2'></i>Experiencia Laboral
                    </h5>
                    <small class="text-muted">Detalla tu trayectoria profesional y logros alcanzados.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_experiencia_laboral_modal()"></button>
            </div>

            <form id="form_experiencia_laboral" class="needs-validation">
                <input type="hidden" id="txt_experiencia_id">

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="txt_nombre_empresa" class="form-label fw-semibold fs-7">Nombre Empresa </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-buildings'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_nombre_empresa" id="txt_nombre_empresa" maxlength="100" placeholder="Ej: Corporación XYZ">
                            </div>
                            <label class="error" style="display: none;" for="txt_nombre_empresa"></label>
                        </div>
                        <div class="col-md-6">
                            <label for="txt_cargos_ocupados" class="form-label fw-semibold fs-7">Cargo Ocupado </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-user'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_cargos_ocupados" id="txt_cargos_ocupados" maxlength="100" placeholder="Ej: Analista de Sistemas">
                            </div>
                            <label class="error" style="display: none;" for="txt_cargos_ocupados"></label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="txt_sueldo" class="form-label fw-semibold fs-7">Sueldo Mensual </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-money'></i></span>
                                <input type="number" class="form-control" name="txt_sueldo" id="txt_sueldo" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="ddl_nomina_experiencia" class="form-label fw-semibold fs-7">Figura Legal </label>
                            <select class="form-select select2-validation" name="ddl_nomina_experiencia" id="ddl_nomina_experiencia" style="width: 100%;">
                                <option value="">-- Seleccione --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_nomina_experiencia"></label>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted fs-7 mb-0 fw-bold text-uppercase ls-1">Periodo Laboral </h6>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="cbx_fecha_final_laboral" id="cbx_fecha_final_laboral" onchange="checkbox_actualidad_exp_prev();">
                                <label for="cbx_fecha_final_laboral" class="form-check-label fs-7 fw-semibold text-primary">Actualidad</label>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="txt_fecha_inicio_laboral" class="form-label fs-7 mb-1">Fecha Inicio </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_laboral" id="txt_fecha_inicio_laboral" onchange="checkbox_actualidad_exp_prev();">
                            </div>
                            <div class="col-md-6">
                                <label for="txt_fecha_final_laboral" class="form-label fs-7 mb-1">Fecha Fin </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_final_laboral" id="txt_fecha_final_laboral" onchange="checkbox_actualidad_exp_prev();">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label for="txt_responsabilidades" class="form-label fw-semibold fs-7">Descripción de Responsabilidades </label>
                            <textarea class="form-control no_caracteres" name="txt_responsabilidades" id="txt_responsabilidades" rows="3" oninput="texto_mayusculas(this);" maxlength="300" placeholder="Describe brevemente tus funciones principales y éxitos alcanzados..."></textarea>
                            <div class="form-text text-end text-xs">Máximo 300 caracteres.</div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label for="txt_logros" class="form-label fw-semibold fs-7">Descripción de Logros </label>
                            <textarea class="form-control no_caracteres" name="txt_logros" id="txt_logros" rows="3" maxlength="300" oninput="texto_mayusculas(this);" placeholder="Describe brevemente tus funciones principales y éxitos alcanzados..."></textarea>
                            <div class="form-text text-end text-xs">Máximo 300 caracteres.</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-2 border-bottom pb-1">
                                <i class='bx bx- id-card me-2 text-primary'></i>
                                <h6 class="text-primary fs-7 mb-0 fw-bold text-uppercase">Referencias de esta Experiencia</h6>
                            </div>
                            <div id="pnl_experencia_referencias_laborales" class="bg-white rounded border p-2" style="min-height: 50px;" style="display: none;">
                                <div class="text-center">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_experiencia" onclick="delete_datos_experiencia_laboral();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_experiencia_laboral_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_experiencia" onclick="validar_fechas_exp_prev(); insertar_editar_experiencia_laboral();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_nombre_empresa');
        agregar_asterisco_campo_obligatorio('txt_cargos_ocupados');
        agregar_asterisco_campo_obligatorio('ddl_nomina_experiencia');
        agregar_asterisco_campo_obligatorio('txt_fecha_inicio_laboral');
        agregar_asterisco_campo_obligatorio('txt_fecha_final_laboral');
        agregar_asterisco_campo_obligatorio('txt_responsabilidades');
        agregar_asterisco_campo_obligatorio('txt_logros');
        agregar_asterisco_campo_obligatorio('txt_sueldo');

        //! Validación Experiencia Laboral
        $("#form_experiencia_laboral").validate({
            rules: {
                txt_nombre_empresa: {
                    required: true,
                },
                txt_cargos_ocupados: {
                    required: true,
                },
                ddl_nomina_experiencia: {
                    required: true,
                },
                txt_fecha_inicio_laboral: {
                    required: true,
                },
                txt_fecha_final_laboral: {
                    required: true,
                },
                txt_responsabilidades: {
                    required: true,
                },
                txt_logros: {
                    required: true,
                },
                txt_sueldo: {
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
                ddl_nomina_experiencia: {
                    required: "Por favor seleccione la figura legal",
                },
                txt_fecha_inicio_laboral: {
                    required: "Por favor ingrese la fecha en la que iniciaron sus funciones",
                },
                txt_fecha_final_laboral: {
                    required: "Por favor ingrese la fecha de finalización o seleccione 'Actualidad' si sigue trabajando.",
                },
                txt_responsabilidades: {
                    required: "Por favor ingrese sus responsabilidades",
                },
                txt_logros: {
                    required: "Por favor ingrese sus logros",
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