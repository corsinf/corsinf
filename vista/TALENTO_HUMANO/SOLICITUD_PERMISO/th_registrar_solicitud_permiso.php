<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script>
    const session = <?= json_encode($_SESSION) ?>;
    const TIPO_USUARIO = session.INICIO.TIPO;
    let id_persona = (TIPO_USUARIO === 'DBA' || TIPO_USUARIO === 'ADMINISTRADOR') ? '' : session.INICIO.NO_CONCURENTE;

    function cargar_persona_familia(th_per_id) {

        if ($('#ddl_familiar').hasClass("select2-hidden-accessible")) {
            $('#ddl_familiar').select2('destroy');
        }

        $('#ddl_familiar').select2({
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?buscar=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        th_per_id: th_per_id
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            placeholder: "Seleccione un familiar",
            minimumInputLength: 0
        }).on('select2:select', function(e) {
            let data = e.params.data;

            if (data.parentesco === "HIJO/A" || data.parentesco === "HIJO") {
                $('#ddl_parentesco').val('HIJO').trigger('change');
                $('#ddl_parentesco').prop('disabled', true);
                $('#txt_fecha_nacimiento').prop('disabled', true);

            } else {
                $('#ddl_parentesco').val('OTRO').trigger('change');
                $('#ddl_parentesco').prop('disabled', true);
                $('#txt_fecha_nacimiento').prop('disabled', true);
            }

            if (data.fecha_nacimiento) {
                $('#txt_fecha_nacimiento').val(data.fecha_nacimiento);
                let edadCalculada = calcularEdad(data.fecha_nacimiento);
            } else {
                $('#txt_fecha_nacimiento').val('');
                $('#txt_fecha_nacimiento').prop('disabled', false);
                $('#txt_fecha_nacimiento').focus();
            }
        });
    }



    $(document).ready(function() {
        <?php if ($_id != '') { ?>
            cargar_solicitud(<?= $_id ?>);
        <?php } ?>
        cargar_selects2();

        const TIPO_USUARIO = "<?= $_SESSION['INICIO']['TIPO'] ?>";

        const session = <?= json_encode($_SESSION) ?>;

        if (TIPO_USUARIO === 'DBA' || TIPO_USUARIO === 'ADMINISTRADOR') {
            console.log(session);
        } else {
            console.log(session);
            $('#ddl_personas').prop('disabled', true);
        }


        function cargar_selects2() {
            url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?busca_persona_nomina=true';
            cargar_select2_url('ddl_personas', url_personasC);
            url_cargoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?buscar=true';
            cargar_select2_url('ddl_cargo', url_cargoC);
        }

        $('#ddl_personas').on('change', function() {
            let th_per_id = $(this).val();
            cargar_persona_familia(th_per_id);
            cargar_datos_persona(th_per_id);
            $('#pnl_familiares').toggle(true);
            limpiar_campos();
        });

        $('#ddl_personas').on('select2:select', function(e) {
            var data = e.params.data;
            if (data.text) {
                var cedula = data.text.split(' - ')[0];
                $('#txt_cedula_persona').val(cedula);
            }
        });

        // ============= CONTROL TIPO DE MOTIVO =============
        $('input[name="rbx_tipo_motivo"]').change(function() {
            let v = $(this).val();

            if (v === 'MOTIVO_PERSONAL') {
                $('#pnl_motivo_medico').slideUp();
                $('#pnl_motivo_personal').slideDown();
                $('#ddl_motivo_medico').val('');
                limpiarCamposMedicos();
            } else if (v === 'MOTIVO_MEDICO') {
                $('#pnl_motivo_personal').slideUp();
                $('#pnl_motivo_medico').slideDown();
                $('#ddl_motivo').val('');
                $('#pnl_info_adicional').slideUp();
                $('#pnl_acta_defuncion').slideUp();
            }
        });

        $('#ddl_motivo').change(function() {
            let v = $(this).val();

            $('#pnl_info_adicional').hide();
            $('#txt_detalle_motivo').val('');
            $('#pnl_acta_defuncion').slideUp();

            if (v === 'PERSONAL') {
                $('#pnl_info_adicional').slideDown();
            }

            if (v === 'CALAMIDAD') {}

            if (v === 'FALLECIMIENTO') {
                $('#pnl_info_adicional').slideDown();
                $('#pnl_acta_defuncion').slideDown();
            }
        });

        // ============= MOTIVO MÉDICO =============
        $('#ddl_motivo_medico').change(function() {
            let v = $(this).val();

            // Ocultar todo primero
            $('#pnl_file_certificado').slideUp();
            $('#pnl_medico').slideUp();

            if (v == "ENFERMEDAD") {
                $('#title_certificado').html('<i class="bi bi-file-earmark-medical"></i> Certificado de Enfermedad');
                $('#pnl_file_certificado').slideDown();
                $('#pnl_medico').slideDown();
            } else if (v == "CITA_MEDICA") {
                $('#title_certificado').html('<i class="bi bi-calendar-check"></i> Certificado de Cita Médica');
                $('#pnl_file_certificado').slideDown();
                $('#pnl_medico').slideDown();
            } else if (v == "MATERNIDAD_PATERNIDAD") {
                $('#title_certificado').html('<i class="bi bi-person-heart"></i> Certificado de Nacido Vivo');
                $('#pnl_file_certificado').slideDown();
                $('#pnl_medico').slideDown();
                limpiar_panel_medico();
            }
        });

        function limpiar_panel_medico() {
            $('#txt_lugar').val('');
            $('#txt_especialidad').val('');
            $('#txt_medico').val('');
            $('#txt_fecha_atencion').val('');
            $('#txt_hora_desde').val('');
            $('#txt_hora_hasta').val('');
            $('input[name="rbx_tipo_atencion"]').prop('checked', false);
        }

        $('#ddl_parentesco').change(function() {
            let v = $(this).val();

            if (v === 'OTRO') {
                // Muestra Adulto y oculta Rango Edad
                $('#pnl_tipo_adulto').slideDown();
                $('#pnl_rango_edad').slideUp();

                // Opcional: Resetear el valor del que se oculta
                $('#ddl_rango_edad').val('').trigger('change');
            } else if (v === 'HIJO') {
                // Muestra Rango Edad y oculta Adulto
                $('#pnl_rango_edad').slideDown();
                $('#pnl_tipo_adulto').slideUp();

                // Opcional: Resetear el valor del que se oculta
                $('#ddl_tipo_adulto').val('').trigger('change');
            } else {
                // Si selecciona cualquier otra cosa (o vacío), oculta ambos
                $('#pnl_tipo_adulto').slideUp();
                $('#pnl_rango_edad').slideUp();
            }
        });

        // Calcular edad
        $('#txt_fecha_nacimiento').change(function() {
            calcularEdad($(this).val());
            let edad = $('#txt_edad').val();
        });

        // Calcular horas
        $('#txt_hora_permiso_desde, #txt_hora_permiso_hasta').change(function() {
            let d = $('#txt_hora_permiso_desde').val();
            let h = $('#txt_hora_permiso_hasta').val();
            if (d && h) {
                let ini = new Date(`1970-01-01T${d}`);
                let fin = new Date(`1970-01-01T${h}`);
                let diff = (fin - ini) / 3600000;

                if (diff < 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hora Inválida',
                        text: 'La hora hasta debe ser mayor que la hora desde',
                        confirmButtonColor: '#3085d6'
                    });
                    $('#txt_total_horas').val(0);
                } else {
                    $('#txt_total_horas').val(diff.toFixed(2));
                }
            }
        });

        // Calcular días
        $('#txt_fecha_desde, #txt_fecha_hasta').change(function() {
            let f1 = $('#txt_fecha_desde').val();
            let f2 = $('#txt_fecha_hasta').val();

            if (f1 && f2) {
                let fecha1 = new Date(f1);
                let fecha2 = new Date(f2);

                if (fecha2 < fecha1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha Inválida',
                        text: 'La fecha hasta debe ser mayor o igual que la fecha desde',
                        confirmButtonColor: '#3085d6'
                    });
                    $('#txt_total_dias').val(0);
                } else {
                    let diff = Math.ceil((fecha2 - fecha1) / (1000 * 60 * 60 * 24)) + 1;
                    $('#txt_total_dias').val(diff);
                }
            }
        });

        // Validar archivos
        $('input[type="file"]').on('change', function() {
            const file = this.files[0];
            if (!file) return;

            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Formato no permitido. Solo PDF o Word.', 'error');
                $(this).val('');
                return;
            }

            if (file.size > maxSize) {
                Swal.fire('Error', 'El archivo supera los 5MB.', 'error');
                $(this).val('');
                return;
            }
        });

        $('#file_certificado, #file_act_defuncion').on('change', function() {
            const file = this.files[0];
            if (!file) return;

            const maxSize = 5 * 1024 * 1024;
            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Formato no permitido. Solo PDF o Word.', 'error');
                $(this).val('');
                return;
            }

            if (file.size > maxSize) {
                Swal.fire('Error', 'El archivo supera los 5MB.', 'error');
                $(this).val('');
                return;
            }
        });
    });

    function controlarPanelMedico() {
        // Verifica si hay AL MENOS uno seleccionado
        let algunoSeleccionado = $('.cert-check:checked').length > 0;

        if (algunoSeleccionado) {
            $('#pnl_medico').slideDown();
        } else {
            // Si no hay nada, limpia y oculta todo
            $('#pnl_medico').slideUp();
            $('[id^="pnl_file_"]').slideUp();

            // Limpiar campos
            $('input[name="rbx_tipo_atencion"]').prop('checked', false);
            $('#txt_lugar, #txt_especialidad, #txt_medico').val('');
            $('#txt_fecha_atencion, #txt_hora_desde, #txt_hora_hasta').val('');
            // Limpiar inputs de archivos
            $('input[type="file"]').val('');
        }
    }

    function calcularEdad(fecha) {
        if (!fecha || fecha === '1900-01-01') {
            $('#txt_edad').val('');
            return 0;
        }
        let f = new Date(fecha);
        let h = new Date();
        let edad = h.getFullYear() - f.getFullYear();
        let m = h.getMonth() - f.getMonth();

        if (m < 0 || (m === 0 && h.getDate() < f.getDate())) {
            edad--;
        }

        $('#txt_edad').val(edad);
        return edad;
    }

    function mostrar_campos_fecha() {
        $('#txt_fecha_desde, #txt_fecha_hasta, #txt_total_dias').closest('.col-md-3').show();
        $('#txt_hora_permiso_desde, #txt_hora_permiso_hasta, #txt_total_horas').closest('.col-md-3').hide();
    }

    function mostrar_campos_hora() {
        $('#txt_hora_permiso_desde, #txt_hora_permiso_hasta, #txt_total_horas').closest('.col-md-3').show();
        $('#txt_fecha_hasta, #txt_total_dias').closest('.col-md-3').hide();
    }

    function ocultar_todos_campos_permiso() {
        $('#txt_fecha_desde, #txt_fecha_hasta, #txt_hora_permiso_desde, #txt_hora_permiso_hasta, #txt_total_horas, #txt_total_dias')
            .closest('.col-md-3').hide();
    }

    function toDateInput(val) {
        if (!val) return '';
        return val.split(' ')[0];
    }

    function toTimeInput(val) {
        if (!val || val.startsWith('1900')) return '';
        return val.split(' ')[1].substring(0, 5);
    }

    function limpiar_campos() {
        $('#ddl_cargo').val('').trigger('change');
        $('#ddl_familiar').val('').trigger('change');
        $('#txt_fecha_nacimiento').prop('disabled', false);
        $('#ddl_parentesco').prop('disabled', false);
        $('#txt_fecha_nacimiento').val('');
        $('#txt_edad').val('');
        $('#ddl_parentesco').val('');
    }

    function limpiarCamposMedicos() {
        $('#ddl_motivo_medico').val('');
        $('input[name="rbx_tipo_atencion"]').prop('checked', false);
        $('#txt_lugar, #txt_especialidad, #txt_medico').val('');
        $('#txt_fecha_atencion, #txt_hora_desde, #txt_hora_hasta').val('');
        $('#file_certificado').val('');
        $('#pnl_file_certificado').slideUp();
        $('#pnl_medico').slideUp();
    }


    function cargar_solicitud(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_solicitud_permisoC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                var r = (Array.isArray(response) && response.length > 0) ? response[0] : response;
                if (!r) return;

                $("#txt_id").val(r._id || r.id || '');
                $("#txt_cedula_persona").val(r.cedula || '');


                // Cargar persona
                $('#ddl_personas').empty().append($('<option>', {
                    value: r.id_persona,
                    text: r.cedula + ' - ' + r.nombre_completo,
                    selected: true
                })).trigger('change');

                cargar_persona_familia(r.id_persona);
                cargar_datos_persona(r.id_persona);

                // ========== TIPO DE MOTIVO ==========
                let tipo_motivo = r.tipo_motivo || 'MOTIVO_PERSONAL';
                $(`input[name="rbx_tipo_motivo"][value="${tipo_motivo}"]`).prop('checked', true).trigger('change');

                if (tipo_motivo === 'MOTIVO_PERSONAL') {
                    $("#ddl_motivo").val(r.motivo || '').trigger('change');
                    $("#txt_detalle_motivo").val(r.detalle || '');
                } else if (tipo_motivo === 'MOTIVO_MEDICO') {
                    $("#ddl_motivo_medico").val(r.motivo || '').trigger('change');
                    $("#txt_detalle_motivo_medico").val(r.detalle || '');
                }

                // Tipo de solicitud
                if (r.tipo_solicitud) {
                    $(`input[name="tipo_asunto"][value="${r.tipo_solicitud}"]`).prop('checked', true);
                }

                // ========== CERTIFICADO ==========
                if (r.ruta_certificado) {
                    $('#txt_ruta_certificado_guardada').val(r.ruta_certificado);
                    mostrarVistaPrevia('#pnl_file_certificado', r.ruta_certificado, 'Certificado Médico');
                }

                // Sección médica
                if (r.tipo_atencion) {
                    $(`input[name="rbx_tipo_atencion"][value="${r.tipo_atencion}"]`).prop('checked', true);
                }

                $("#txt_lugar").val(r.lugar || '');
                $("#txt_especialidad").val(r.especialidad || '');
                $("#txt_medico").val(r.medico || '');
                $("#txt_fecha_atencion").val(toDateInput(r.fecha_atencion) || '');
                $("#txt_hora_desde").val(toTimeInput(r.hora_desde) || '');
                $("#txt_hora_hasta").val(toTimeInput(r.hora_hasta) || '');

                // ========== PARENTESCO ==========
                if (r.fam_hijos_adultos) {
                    $("#ddl_parentesco").val(r.fam_hijos_adultos).trigger('change');

                    setTimeout(() => {
                        if (r.fam_hijos_adultos === 'HIJO' && r.rango_edad) {
                            $("#ddl_rango_edad").val(r.rango_edad).trigger('change');
                        }
                        if (r.fam_hijos_adultos === 'OTRO' && r.tipo_cuidado) {
                            $("#ddl_otro").val(r.tipo_cuidado).trigger('change');
                        }
                    }, 200);

                    const fechaNac = toDateInput(r.fecha_nacimiento);
                    $("#txt_fecha_nacimiento").val(fechaNac);
                    if (typeof calcularEdad === "function") calcularEdad(fechaNac);
                }

                // ========== ACTA DE DEFUNCIÓN ==========
                if (r.ruta_act_defuncion) {
                    $('#txt_ruta_act_defuncion_guardada').val(r.ruta_act_defuncion);
                    mostrarVistaPrevia('#pnl_acta_defuncion', r.ruta_act_defuncion, 'Acta de Defunción');
                }

                if (r.th_ppa_id > 0) {
                    cargar_datos_pariente(r.th_ppa_id);
                }

                // Planificación
                if (r.planificacion && r.planificacion !== "") {
                    $("#pnl_espacio_docente").show();
                    $(`input[name="rbx_planificacion"][value="${r.planificacion}"]`).prop('checked', true);
                }
            }
        });
    }

    function cargar_datos_persona(per_id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_persona_departamento=true',
            type: 'post',
            data: {
                id: per_id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    console.log(response[0]);
                    $('#ddl_estado_civil').val(response[0].estado_civil);
                    $('#ddl_genero').val(response[0].sexo);
                    if (response[0].nombre_departamento == "DOCENTES") {
                        $('#pnl_espacio_docente').slideDown();
                    } else {

                        $('#pnl_espacio_docente').slideUp();
                    }
                }
            }
        });

    }

    function cargar_datos_pariente(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_parientesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#ddl_familiar').append($('<option>', {
                        value: response[0]._id,
                        text: response[0].apellidos + " " + response[0].nombres + " - " + response[0].parentesco_nombre,
                        selected: true
                    }));

                    calcularEdad(response[0].fecha_nacimiento);
                    $('#txt_fecha_nacimiento').prop('disabled', true);
                    $('#txt_fecha_nacimiento').val(response[0].fecha_nacimiento);
                    let edad = $('#txt_edad').val();
                }
            }
        });
    }

    function validar_formulario() {
        let tipo_motivo = $('input[name="rbx_tipo_motivo"]:checked').val();
        let id_persona = $("#ddl_personas").val();
        if (!id_persona) {
            Swal.fire('Error', 'Seleccione una persona', 'error');
            return false;
        }

        if (tipo_motivo === 'MOTIVO_PERSONAL') {
            let motivo = $('#ddl_motivo').val();
            if (!motivo) {
                Swal.fire('Error', 'Seleccione el motivo del permiso', 'error');
                return false;
            }

            // Validar fallecimiento
            if (motivo === 'FALLECIMIENTO') {
                let parentesco = $('#ddl_parentesco').val();
                if (!parentesco) {
                    Swal.fire('Error', 'Seleccione el tipo de parentesco', 'error');
                    return false;
                }

                if (parentesco === 'HIJO' && !$('#ddl_rango_edad').val()) {
                    Swal.fire('Error', 'Seleccione el rango de edad del hijo', 'error');
                    return false;
                }

                if (parentesco === 'OTRO' && !$('#ddl_otro').val()) {
                    Swal.fire('Error', 'Seleccione el tipo de cuidado familiar', 'error');
                    return false;
                }

                let tiene_acta_nueva = $('#file_act_defuncion')[0].files.length > 0;
                let tiene_acta_guardada = $('#txt_ruta_act_defuncion_guardada').val();

                if (!tiene_acta_nueva && !tiene_acta_guardada) {
                    Swal.fire('Error', 'Debe adjuntar el Acta de Defunción', 'error');
                    return false;
                }
            }
        } else if (tipo_motivo === 'MOTIVO_MEDICO') {
            let motivo_medico = $('#ddl_motivo_medico').val();
            if (!motivo_medico) {
                Swal.fire('Error', 'Seleccione el motivo médico', 'error');
                return false;
            }


            // Validar atención médica
            if (!$('input[name="rbx_tipo_atencion"]:checked').val()) {
                Swal.fire('Error', 'Seleccione el tipo de atención médica', 'error');
                return false;
            }

            let camposMedicos = ['#txt_lugar', '#txt_especialidad', '#txt_medico', '#txt_fecha_atencion', '#txt_hora_desde', '#txt_hora_hasta'];
            for (let campo of camposMedicos) {
                if (!$(campo).val()) {
                    Swal.fire('Error', 'Complete todos los campos de atención médica', 'error');
                    return false;
                }
            }
        }

        return true;
    }

    function combinarFechaHora(fecha, hora) {
        if (!fecha || !hora) return '';
        return fecha + ' ' + hora + ':00';
    }

    function mostrarVistaPrevia(panelSelector, rutaArchivo, nombreDoc) {
        const $panel = $(panelSelector);

        // Remover vista previa anterior si existe
        $panel.find('.vista-previa-documento').remove();

        // Crear elemento de vista previa
        const vistaPrevia = `
        <div class="vista-previa-documento mt-2 p-2 border rounded bg-light">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bx bx-file-blank fs-3 text-primary me-2"></i>
                    <div>
                        <small class="fw-bold d-block">${nombreDoc}</small>
                        <small class="text-muted">Archivo actual guardado</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="${rutaArchivo}" target="_blank" class="btn btn-sm btn-outline-primary" title="Ver documento">
                        <i class="bx bx-show"></i> Ver
                    </a>
                    <a href="${rutaArchivo}" download class="btn btn-sm btn-outline-success" title="Descargar">
                        <i class="bx bx-download"></i>
                    </a>
                </div>
            </div>
        </div>
    `;

        // Agregar al final del panel (no dentro de col-md-6)
        $panel.find('.card-body').append(vistaPrevia);
    }


    // ============= ACTUALIZAR FUNCIÓN insertar_actualizar =============
    function insertar_actualizar() {
        if (!validar_formulario()) return;

        var form_data = new FormData();
        let tipo_motivo = $('input[name="rbx_tipo_motivo"]:checked').val();
        let motivo = '';
        let motivo_medico = '';
        let detalle_motivo = '';

        if (tipo_motivo === 'MOTIVO_PERSONAL') {
            motivo = $("#ddl_motivo").val();
            detalle_motivo = $("#txt_detalle_motivo").val() || '';
        } else if (tipo_motivo === 'MOTIVO_MEDICO') {
            motivo_medico = $("#ddl_motivo_medico").val();
            detalle_motivo = $("#txt_detalle_motivo_medico").val() || '';
        }

        let tipo_asunto = $('input[name="tipo_asunto"]:checked').val();
        let fechaDesde = $("#txt_fecha_desde").val();
        let fechaHasta = $("#txt_fecha_hasta").val();

        let parametros = {
            '_id': $("#txt_id").val() || '',
            'id_persona': $("#ddl_personas").val() || '',
            'cedula_persona': $("#txt_cedula_persona").val() || '',
            'tipo_motivo': tipo_motivo,
            'motivo': motivo,
            'motivo_medico': motivo_medico,
            'detalle': detalle_motivo,
            'tipo_asunto': tipo_asunto,
            'th_ppa_id': $("#ddl_familiar").val() || '',

            // Parentesco
            'parentesco': $("#ddl_parentesco").val() || null,
            'rango_edad': $("#ddl_rango_edad").val() || null,
            'tipo_adulto': $("#ddl_otro").val() || null,
            'fecha_nacimiento': $("#txt_fecha_nacimiento").val() || null,

            // Médico
            'tipo_atencion': $('input[name="rbx_tipo_atencion"]:checked').val() || null,
            'lugar': $("#txt_lugar").val() || null,
            'especialidad': $("#txt_especialidad").val() || null,
            'medico': $("#txt_medico").val() || null,
            'fecha_atencion': $("#txt_fecha_atencion").val() || fechaDesde,
            'hora_desde': combinarFechaHora($("#txt_fecha_atencion").val(), $("#txt_hora_desde").val()),
            'hora_hasta': combinarFechaHora($("#txt_fecha_atencion").val(), $("#txt_hora_hasta").val()),

            // Rutas actuales
            'ruta_certificado_actual': $("#txt_ruta_certificado_guardada").val() || null,
            'ruta_act_defuncion_actual': $("#txt_ruta_act_defuncion_guardada").val() || null,
            'planificacion': $('input[name="rbx_planificacion"]:checked').val() || null,
        };

        form_data.append('parametros', JSON.stringify(parametros));

        // Agregar archivos
        let file_certificado = $('#file_certificado')[0].files[0];
        let file_acta_defuncion = $('#file_act_defuncion')[0].files[0];

        if (file_certificado) {
            form_data.append('file_certificado', file_certificado);
        }
        if (file_acta_defuncion) {
            form_data.append('file_act_defuncion', file_acta_defuncion);
        }

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_solicitud_permisoC.php?insertar_editar=true',
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Solicitud guardada correctamente',
                        showCancelButton: true,
                        confirmButtonText: 'Ver PDF',
                        cancelButtonText: 'Volver al listado'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let id = $("#txt_id").val() || res.id_insertado;
                            verPDFGenerado(id);
                        } else {
                            location.href = "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitud_permiso";
                        }
                    });
                } else {
                    Swal.fire('Error', 'No se pudo guardar la solicitud', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Ocurrió un error al procesar la solicitud', 'error');
            }
        });
    }

    function verPDFGenerado(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_solicitud_permisoC.php?obtener_ruta_pdf=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.ruta) {
                    window.open(response.ruta, '_blank');
                }
            }
        });
    }

    function eliminar() {
        var id = $("#txt_id").val() || '';
        if (!id) {
            Swal.fire('Atención', 'ID no encontrado para eliminar', 'warning');
            return;
        }

        Swal.fire({
            title: '¿Eliminar Solicitud?',
            text: "¿Está seguro de eliminar esta solicitud de permiso?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controlador/TALENTO_HUMANO/th_solicitud_permisoC.php?eliminar=true',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res == 1) {
                            Swal.fire('Eliminado', 'Solicitud eliminada correctamente', 'success')
                                .then(() => window.location.href =
                                    "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitud_permiso"
                                );
                        } else {
                            Swal.fire('Error', 'No se pudo eliminar', 'error');
                        }
                    }
                });
            }
        });
    }
</script>



<div class="page-wrapper">
    <div class="page-content">

        <div class="card border-primary border-3">
            <div class="card-body p-4">

                <div class="card-title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div><i class="bx bx-layer me-2 font-22 text-primary"></i></div>
                        <h5 class="mb-0 text-primary">
                            <?= ($_id == '') ? 'Registrar Solicitud' : 'Modificar Solicitud' ?>
                        </h5>
                    </div>

                    <div>
                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitud_permiso"
                            class="btn btn-outline-dark btn-sm">
                            <i class="bx bx-arrow-back"></i> Regresar
                        </a>
                    </div>
                </div>

                <hr>
                <form id="form_permiso">
                    <input type="hidden" id="txt_id" name="txt_id">
                    <input type="hidden" id="txt_cedula_persona" name="txt_cedula_persona">
                    <input type="hidden" id="txt_ruta_certificado_guardada">
                    <input type="hidden" id="txt_ruta_act_defuncion_guardada">

                    <!-- PERSONA -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ddl_personas" class="form-label fw-bold">
                                <i class="bi bi-person"></i> Persona
                            </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_personas" name="ddl_personas">
                                <option selected disabled>-- Seleccione --</option>
                            </select>
                        </div>
                    </div>

                    <div id="pnl_persona_informacion_adicional" class="row mb-3">
                        <div class="col-md-3">
                            <label for="ddl_cargo" class="form-label fw-bold">Cargo: </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_cargo" name="ddl_cargo">
                                <option selected disabled>-- Seleccione --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="ddl_genero" class="form-label fw-bold">Género: </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_genero" name="ddl_genero">
                                <option value="" selected disabled>-- Seleccione --</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="ddl_estado_civil" class="form-label fw-bold">Estado Civil: </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_estado_civil" name="ddl_estado_civil">
                                <option selected disabled value="">-- Seleccione --</option>
                                <option value="Soltero/a">SOLTERO(A)</option>
                                <option value="Casado/a">CASADO(A)</option>
                                <option value="Divorciado/a">DIVORCIADO(A)</option>
                                <option value="Viudo/a">VIUDO(A)</option>
                                <option value="Union libre">UNIÓN LIBRE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-chat-left-text"></i> Asunto
                            </label>
                            <div class="d-flex gap-3 pt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_asunto" id="radio_solicitud" value="SOLICITUD" checked>
                                    <label class="form-check-label" for="radio_solicitud">SOLICITUD</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_asunto" id="radio_justificacion" value="JUSTIFICACION">
                                    <label class="form-check-label" for="radio_justificacion">JUSTIFICACIÓN</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TIPO DE MOTIVO (PERSONAL O MÉDICO) -->
                    <div id="pnl_tipo_motivo" class="row mb-3">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <label class="fw-bold mb-3">
                                        <i class="bi bi-toggle-on"></i> Seleccione el Tipo de Motivo
                                    </label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="rbx_tipo_motivo" id="rbx_motivo_personal" value="MOTIVO_PERSONAL">
                                            <label class="form-check-label fw-bold" for="rbx_motivo_personal">Personal</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="rbx_tipo_motivo" id="rbx_motivo_medico" value="MOTIVO_MEDICO">
                                            <label class="form-check-label fw-bold" for="rbx_motivo_medico">Médico</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MOTIVO PERSONAL -->
                    <div id="pnl_motivo_personal" class="row mb-3" style="display: none;">
                        <div class="col-md-6">
                            <label class="fw-bold">
                                <i class="bi bi-list-task"></i> Motivo del Permiso
                            </label>
                            <select class="form-control form-control-sm" id="ddl_motivo" name="ddl_motivo">
                                <option value="">-- Seleccione --</option>
                                <option value="PERSONAL">Personal</option>
                                <option value="CALAMIDAD">Calamidad Doméstica</option>
                                <option value="FALLECIMIENTO">Fallecimiento</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">
                                <i class="bi bi-pencil"></i> Detalle del Motivo
                            </label>
                            <input type="text" class="form-control form-control-sm" id="txt_detalle_motivo" placeholder="Especifique detalles adicionales">
                        </div>
                    </div>

                    <!-- MOTIVO MÉDICO -->
                    <div id="pnl_motivo_medico" class="row mb-3" style="display: none;">
                        <div class="col-md-6">
                            <label class="fw-bold">
                                <i class="bi bi-list-task"></i> Motivo del Permiso Médico
                            </label>
                            <select class="form-control form-control-sm" id="ddl_motivo_medico" name="ddl_motivo_medico">
                                <option value="">-- Seleccione --</option>
                                <option value="MATERNIDAD_PATERNIDAD">Maternidad/Paternidad</option>
                                <option value="ENFERMEDAD">Enfermedad</option>
                                <option value="CITA_MEDICA">Cita Médica</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">
                                <i class="bi bi-pencil"></i> Detalle del Motivo
                            </label>
                            <input type="text" class="form-control form-control-sm" id="txt_detalle_motivo_medico" placeholder="Especifique detalles adicionales">
                        </div>
                    </div>

                    <!-- ACTA DE DEFUNCIÓN -->
                    <div id="pnl_acta_defuncion" class="row mb-3" style="display: none;">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-danger mb-3">
                                        <i class="bi bi-file-earmark-text"></i> Acta de Defunción (Obligatorio)
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Adjuntar Acta</label>
                                            <input type="file" class="form-control form-control-sm" id="file_act_defuncion" accept=".pdf,.doc,.docx">
                                            <small class="text-muted">PDF o Word • Máx. 5MB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INFORMACIÓN ADICIONAL (PARA PERSONAL) -->
                    <div id="pnl_info_adicional" style="display:none">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="text-primary mb-3"><i class="bi bi-people"></i> Información adicional</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="fw-bold">Tipo de Familiar</label>
                                        <select class="form-control form-control-sm" id="ddl_parentesco">
                                            <option value="">-- Seleccione --</option>
                                            <option value="HIJO">Hijo</option>
                                            <option value="OTRO">Otro</option>
                                        </select>
                                    </div>
                                    <div id="pnl_familiares" class="col-md-3">
                                        <label for="ddl_familiar" class="fw-bold">Familiar Seleccionado</label>
                                        <select class="form-control form-control-sm" id="ddl_familiar"></select>
                                    </div>
                                    <div class="col-md-3" id="pnl_rango_edad" style="display:none">
                                        <label class="fw-bold text-danger">Rango de Edad (Hijo)</label>
                                        <select class="form-control form-control-sm" id="ddl_rango_edad">
                                            <option value="">-- Seleccione --</option>
                                            <option value="0-5">0 - 5 años</option>
                                            <option value="6-11">6 - 11 años</option>
                                            <option value="12-17">12 - 17 años</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" id="pnl_tipo_adulto" style="display:none">
                                        <label class="fw-bold text-danger">Tipo de Cuidado</label>
                                        <select class="form-control form-control-sm" id="ddl_otro">
                                            <option value="">-- Seleccione --</option>
                                            <option value="DISCAPACIDAD">Discapacidad</option>
                                            <option value="ADULTO_MAYOR">Adulto Mayor</option>
                                            <option value="ENFERMEDAD_CATASTROFICA">Enfermedad Catastrófica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <label class="fw-bold">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control form-control-sm" id="txt_fecha_nacimiento">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="fw-bold">Edad Calculada</label>
                                        <input type="number" class="form-control form-control-sm" id="txt_edad" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CERTIFICADO MÉDICO (ÚNICO) -->
                    <div id="pnl_file_certificado" class="row mb-3" style="display:none">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 id="title_certificado" class="text-primary mb-3">
                                        <i class="bi bi-file-earmark-medical"></i> Certificado Médico
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Adjuntar Certificado</label>
                                            <input type="file" class="form-control form-control-sm" id="file_certificado" accept=".pdf,.doc,.docx">
                                            <small class="text-muted">PDF o Word • Máx. 5MB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ATENCIÓN MÉDICA -->
                    <div id="pnl_medico" style="display:none">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-heart-pulse"></i> Detalle de Atención Médica
                                </h6>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="fw-bold">Tipo de Atención</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rbx_tipo_atencion" id="rbx_privada" value="PRIVADA">
                                            <label class="form-check-label" for="rbx_privada">Privada</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rbx_tipo_atencion" id="rbx_publica" value="PUBLICA">
                                            <label class="form-check-label" for="rbx_publica">Pública</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="fw-bold">Lugar</label>
                                        <input type="text" class="form-control form-control-sm" id="txt_lugar" placeholder="Ej: Hospital">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold">Especialidad</label>
                                        <input type="text" class="form-control form-control-sm" id="txt_especialidad">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold">Nombre del Médico</label>
                                        <input type="text" class="form-control form-control-sm" id="txt_medico">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="fw-bold">Fecha Atención</label>
                                        <input type="date" class="form-control form-control-sm" id="txt_fecha_atencion">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold">Hora Desde</label>
                                        <input type="time" class="form-control form-control-sm" id="txt_hora_desde">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold">Hora Hasta</label>
                                        <input type="time" class="form-control form-control-sm" id="txt_hora_hasta">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PLANIFICACIÓN DOCENTE -->
                    <div id="pnl_espacio_docente" class="row mb-3" style="display: none;">
                        <div class="col-md-12">
                            <label class="fw-bold small text-muted text-uppercase">
                                <i class="bi bi-person-check-fill"></i> Espacio de Responsabilidad (Personal Docente)
                            </label>
                            <div class="border p-2 rounded bg-light">
                                <div class="d-flex gap-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rbx_planificacion" id="plani_no" value="NO_REQUIERE">
                                        <label class="form-check-label fw-bold" for="plani_no" style="font-size: 0.85rem;">
                                            NO REQUIERE PLANIFICACIÓN
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rbx_planificacion" id="plani_si" value="ANEXO_PLANIFICACION">
                                        <label class="form-check-label fw-bold" for="plani_si" style="font-size: 0.85rem;">
                                            ANEXO PLANIFICACIÓN
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTONES -->
                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-success btn-sm px-4" onclick="insertar_actualizar()">
                                <i class="bx bx-save"></i> Guardar
                            </button>
                            <?php if ($_id != '') { ?>
                                <button type="button" class="btn btn-danger btn-sm px-4" onclick="eliminar()">
                                    <i class="bx bx-trash"></i> Eliminar
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>