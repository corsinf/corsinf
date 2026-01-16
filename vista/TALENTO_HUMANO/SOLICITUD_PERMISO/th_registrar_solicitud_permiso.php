<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script>
    $(document).ready(function() {
        <?php if ($_id != '') { ?>
            cargar_solicitud(<?= $_id ?>);
        <?php } ?>
        cargar_selects2();

        function cargar_selects2() {
            url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?busca_persona_nomina=true';
            cargar_select2_url('ddl_personas', url_personasC);
        }

        $('#ddl_personas').on('change', function() {
            let th_per_id = $(this).val();
            cargar_persona_familia(th_per_id);
        });

        function cargar_persona_familia(th_per_id) {
            // Si select2 ya está inicializado, destruirlo
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
                minimumInputLength: 0,
                placeholder: "Seleccione un familiar",
                language: {
                    noResults: function() {
                        return "No hay familiares disponibles para asignar";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            }).on('select2:select', function(e) {
                let data = e.params.data;

                let fecha_nacimiento = data.fecha_nacimiento;

                if (
                    fecha_nacimiento &&
                    fecha_nacimiento !== '1900-01-01' &&
                    fecha_nacimiento !== '1900-01-01 00:00:00'
                ) {
                    $('#txt_fecha_nacimiento').val(fecha_nacimiento);
                    calcularEdad(fecha_nacimiento);

                } else {
                    $('#txt_fecha_nacimiento').val('');
                }
            });
        }



        // Capturar cédula cuando se selecciona persona
        $('#ddl_personas').on('select2:select', function(e) {
            var data = e.params.data;
            if (data.text) {
                var cedula = data.text.split(' - ')[0]; // Extraer cédula del texto
                $('#txt_cedula_persona').val(cedula);
            }
        });

        // Control de checkboxes tipo permiso
        $('#cbx_permiso_fecha').change(function() {
            if ($(this).is(':checked')) {
                $('#cbx_permiso_hora').prop('checked', false);
                mostrar_campos_fecha();
            } else {
                ocultar_todos_campos_permiso();
            }
        });

        $('#cbx_permiso_hora').change(function() {
            if ($(this).is(':checked')) {
                $('#cbx_permiso_fecha').prop('checked', false);
                mostrar_campos_hora();
            } else {
                ocultar_todos_campos_permiso();
            }
        });

        // Visibilidad por motivo
        $('#ddl_motivo').change(function() {
            let v = $(this).val();
            $('#pnl_fallecimiento').hide();
            $('#txt_detalle_motivo').val('');

            if (v === 'FALLECIMIENTO') {
                $('#pnl_fallecimiento').slideDown();
            }
            if (v === 'PERSONAL') {
                $('#pnl_fallecimiento').slideDown();
            }
        });

        // Control de certificados
        $('.cert-check').on('change', function() {
            let target = $(this).data('target');

            if ($(this).is(':checked')) {
                $(target).slideDown();
            } else {
                $(target).slideUp();
                $(target).find('input[type="file"]').val('');
            }
            controlarPanelMedico();
        });

        // Control parentesco
        $('#ddl_parentesco').change(function() {
            let v = $(this).val();
            $('#pnl_tipo_adulto, #pnl_rango_edad').hide();
            $('#ddl_tipo_adulto, #ddl_rango_edad').val('');

            if (v === 'ADULTO') {
                $('#pnl_tipo_adulto').slideDown();
            } else if (v === 'HIJO') {
                $('#pnl_rango_edad').slideDown();
            }
        });

        // Calcular edad
        $('#txt_fecha_nacimiento').change(function() {
            calcularEdad($(this).val());
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

        function controlarPanelMedico() {
            let mostrar = $('#cbx_permiso_cita_medica').is(':checked') ||
                $('#cbx_permiso_enfermedad').is(':checked');

            if (mostrar) {
                $('#pnl_medico').slideDown();
            } else {
                $('#pnl_medico').slideUp();
                $('input[name="rbx_tipo_atencion"]').prop('checked', false);
                $('#txt_lugar, #txt_especialidad, #txt_medico').val('');
                $('#txt_fecha_atencion, #txt_hora_desde, #txt_hora_hasta').val('');
            }
        }
    });

    function calcularEdad(fecha) {
        if (!fecha) {
            $('#txt_edad').val('');
            return;
        }

        let f = new Date(fecha);
        let h = new Date();
        let edad = h.getFullYear() - f.getFullYear();
        let m = h.getMonth() - f.getMonth();

        if (m < 0 || (m === 0 && h.getDate() < f.getDate())) {
            edad--;
        }

        $('#txt_edad').val(edad);
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
                $("#ddl_motivo").val(r.motivo || '').trigger('change');
                $("#txt_cedula_persona").val(r.cedula || '');

                $('#ddl_personas').append($('<option>', {
                    value: r.per_id,
                    text: r.cedula + ' - ' + r.nombre_completo,
                    selected: true
                }));

                $("#txt_detalle_motivo").val(r.detalle || '');

                // Certificados
                if (r.maternidad_paternidad == 1) {
                    $('#cbx_permiso_maternidad_paternidad').prop('checked', true).trigger('change');
                }
                if (r.enfermedad == 1) {
                    $('#cbx_permiso_enfermedad').prop('checked', true).trigger('change');
                }
                if (r.cita_medica == 1) {
                    $('#cbx_permiso_cita_medica').prop('checked', true).trigger('change');
                }

                // Tipo de permiso
                if (r.total_dias != 0) {
                    $('#cbx_permiso_fecha').prop('checked', true).trigger('change');
                } else if (r.total_horas != 0) {
                    $('#cbx_permiso_hora').prop('checked', true).trigger('change');
                }

                // Fallecimiento
                if (r.motivo === 'FALLECIMIENTO') {
                    $("#ddl_parentesco").val(r.fam_hijos_adultos || '').trigger('change');
                    $("#ddl_rango_edad").val(r.rango_edad || '');
                    $("#ddl_tipo_adulto").val(r.tipo_cuidado || '');

                    const fechaNac = toDateInput(r.fecha_nacimiento);
                    $("#txt_fecha_nacimiento").val(fechaNac);
                    calcularEdad(fechaNac);
                }

                // Médico
                if (r.cita_medica == 1 || r.enfermedad == 1) {
                    $(`input[name="rbx_tipo_atencion"][value="${r.tipo_atencion || ''}"]`).prop('checked',
                        true);
                    $("#txt_lugar").val(r.lugar || '');
                    $("#txt_especialidad").val(r.especialidad || '');
                    $("#txt_medico").val(r.medico || '');
                    $("#txt_fecha_atencion").val(toDateInput(r.fecha_atencion) || '');
                    $("#txt_hora_desde").val(toTimeInput(r.hora_desde) || '');
                    $("#txt_hora_hasta").val(toTimeInput(r.hora_hasta) || '');
                }

                // Fechas permiso
                $("#txt_fecha_desde").val(toDateInput(r.fecha_desde) || '');
                $("#txt_fecha_hasta").val(toDateInput(r.fecha_hasta) || '');
                $("#txt_hora_permiso_desde").val(toTimeInput(r.hora_desde) || '');
                $("#txt_hora_permiso_hasta").val(toTimeInput(r.hora_hasta) || '');
                $("#txt_total_horas").val(r.total_horas || '');
                $("#txt_total_dias").val(r.total_dias || '');

                // Mostrar rutas de archivos si existen
                if (r.ruta_cert_nacido) {
                    $('#txt_ruta_cert_maternidad_guardada').val(r.ruta_cert_nacido);
                }
                if (r.ruta_cert_enfermedad) {
                    $('#txt_ruta_cert_enfermedad_guardada').val(r.ruta_cert_enfermedad);
                }
                if (r.ruta_cert_medico) {
                    $('#txt_ruta_cert_cita_guardada').val(r.ruta_cert_medico);
                }
            }
        });
    }

    function validar_formulario() {
        let motivo = $('#ddl_motivo').val();
        if (!motivo) {
            Swal.fire({
                icon: 'error',
                title: 'Campo Requerido',
                text: 'Seleccione el motivo del permiso'
            });
            return false;
        }

        if (!$('#cbx_permiso_fecha').is(':checked') && !$('#cbx_permiso_hora').is(':checked')) {
            Swal.fire({
                icon: 'error',
                title: 'Tipo de Permiso',
                text: 'Seleccione el tipo de permiso (Por Fecha o Por Hora)'
            });
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

            if (parentesco === 'ADULTO' && !$('#ddl_tipo_adulto').val()) {
                Swal.fire('Error', 'Seleccione el tipo de cuidado familiar', 'error');
                return false;
            }

            if (!$('#txt_fecha_nacimiento').val()) {
                Swal.fire('Error', 'Complete la fecha de nacimiento del familiar', 'error');
                return false;
            }
        }

        // Validar atención médica
        if ($('#cbx_permiso_cita_medica').is(':checked') || $('#cbx_permiso_enfermedad').is(':checked')) {
            if (!$('input[name="rbx_tipo_atencion"]:checked').val()) {
                Swal.fire('Error', 'Seleccione el tipo de atención médica', 'error');
                return false;
            }

            let camposMedicos = ['#txt_lugar', '#txt_especialidad', '#txt_medico', '#txt_fecha_atencion', '#txt_hora_desde',
                '#txt_hora_hasta'
            ];
            for (let campo of camposMedicos) {
                if (!$(campo).val()) {
                    Swal.fire('Error', 'Complete todos los campos de atención médica', 'error');
                    return false;
                }
            }
        }

        // Validar fechas
        if ($('#cbx_permiso_fecha').is(':checked')) {
            let fd = $('#txt_fecha_desde').val();
            let fh = $('#txt_fecha_hasta').val();

            if (!fd || !fh) {
                Swal.fire('Error', 'Complete las fechas del permiso', 'error');
                return false;
            }

            if (new Date(fd) > new Date(fh)) {
                Swal.fire('Error', 'La fecha desde debe ser menor o igual que la fecha hasta', 'error');
                return false;
            }
        }

        // Validar horas
        if ($('#cbx_permiso_hora').is(':checked')) {
            let fd = $('#txt_fecha_desde').val();
            let hd = $('#txt_hora_permiso_desde').val();
            let hh = $('#txt_hora_permiso_hasta').val();

            if (!fd || !hd || !hh) {
                Swal.fire('Error', 'Complete la fecha y las horas del permiso', 'error');
                return false;
            }

            if (hd >= hh) {
                Swal.fire('Error', 'La hora desde debe ser menor que la hora hasta', 'error');
                return false;
            }
        }

        return true;
    }

    function combinarFechaHora(fecha, hora) {
        if (!fecha || !hora) return '';
        return fecha + ' ' + hora + ':00';
    }

    function insertar_actualizar() {
        if (!validar_formulario()) return;

        var form_data = new FormData();
        let tipo_permiso = $('#cbx_permiso_fecha').is(':checked') ? 'FECHA' : 'HORA';

        let fechaDesde = $("#txt_fecha_desde").val();
        let fechaHasta = $("#txt_fecha_hasta").val();
        let horaDesdePermiso = combinarFechaHora(fechaDesde, $("#txt_hora_permiso_desde").val());
        let horaHastaPermiso = combinarFechaHora(fechaHasta, $("#txt_hora_permiso_hasta").val());

        let parametros = {
            '_id': $("#txt_id").val() || '',
            'id_persona': $("#ddl_personas").val() || '',
            'cedula_persona': $("#txt_cedula_persona").val() || '',
            'motivo': $("#ddl_motivo").val(),
            'detalle': $("#txt_detalle_motivo").val() || '',
            'tipo_permiso': tipo_permiso,

            // Certificados
            'maternidad_paternidad': $('#cbx_permiso_maternidad_paternidad').is(':checked') ? 1 : 0,
            'enfermedad': $('#cbx_permiso_enfermedad').is(':checked') ? 1 : 0,
            'cert_nacido_vivo': $('#cbx_permiso_maternidad_paternidad').is(':checked') ? 1 : 0,
            'cita_medica': $('#cbx_permiso_cita_medica').is(':checked') ? 1 : 0,
            'cert_medico': $('#cbx_permiso_cita_medica').is(':checked') ? 1 : 0,
            'cert_enfermedad': $('#cbx_permiso_enfermedad').is(':checked') ? 1 : 0,

            // Fallecimiento
            'parentesco': $("#ddl_parentesco").val() || null,
            'rango_edad': $("#ddl_rango_edad").val() || null,
            'tipo_adulto': $("#ddl_tipo_adulto").val() || null,
            'fecha_nacimiento': $("#txt_fecha_nacimiento").val() || null,

            // Médico
            'tipo_atencion': $('input[name="rbx_tipo_atencion"]:checked').val() || null,
            'lugar': $("#txt_lugar").val() || null,
            'especialidad': $("#txt_especialidad").val() || null,
            'medico': $("#txt_medico").val() || null,
            'fecha_atencion': $("#txt_fecha_atencion").val() || fechaDesde,
            'hora_desde': combinarFechaHora($("#txt_fecha_atencion").val(), $("#txt_hora_desde").val()),
            'hora_hasta': combinarFechaHora($("#txt_fecha_atencion").val(), $("#txt_hora_hasta").val()),

            // Permiso
            'fecha_desde': fechaDesde,
            'fecha_hasta': fechaHasta,
            'total_horas': parseFloat($("#txt_total_horas").val()) || 0,
            'total_dias': parseInt($("#txt_total_dias").val(), 10) || 0
        };

        // Agregar parametros al FormData
        form_data.append('parametros', JSON.stringify(parametros));

        // Agregar archivos
        let file_maternidad = $('#file_cert_maternidad')[0].files[0];
        let file_enfermedad = $('#file_cert_enfermedad')[0].files[0];
        let file_cita = $('#file_cert_cita')[0].files[0];

        if (file_maternidad) {
            form_data.append('file_cert_maternidad', file_maternidad);
        }
        if (file_enfermedad) {
            form_data.append('file_cert_enfermedad', file_enfermedad);
        }
        if (file_cita) {
            form_data.append('file_cert_cita', file_cita);
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
                        text: 'Solicitud guardada correctamente'
                    }).then(() => location.href =
                        "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitud_permiso");
                } else {
                    Swal.fire('Error', 'No se pudo guardar la solicitud', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Ocurrió un error al procesar la solicitud', 'error');
            }
        });

         $.ajax({
            url: 'https://localhost/corsinf/controlador/TALENTO_HUMANO/SOLICITUDES/index.php?ver_documento=true',
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
                        text: 'Solicitud guardada correctamente'
                    }).then(() => location.href =
                        "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitud_permiso");
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
                    <input type="hidden" id="txt_ruta_cert_maternidad_guardada">
                    <input type="hidden" id="txt_ruta_cert_enfermedad_guardada">
                    <input type="hidden" id="txt_ruta_cert_cita_guardada">

                    <!-- ================== PERSONA ================== -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ddl_personas" class="form-label fw-bold">
                                <i class="bi bi-person"></i> Persona
                            </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_personas"
                                name="ddl_personas">
                                <option selected disabled>-- Seleccione --</option>
                            </select>
                        </div>
                    </div>

                    <!-- ================== MOTIVO ================== -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">
                                <i class="bi bi-list-task"></i> Motivo del Permiso
                            </label>
                            <select class="form-control form-control-sm" id="ddl_motivo" name="ddl_motivo">
                                <option value="">-- Seleccione --</option>
                                <option value="PERSONAL">Personal</option>
                                <option value="CALAMIDAD">Calamidad Doméstica (Siniestros y Catástrofes)</option>
                                <option value="FALLECIMIENTO">Fallecimiento (familiares)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">
                                <i class="bi bi-pencil"></i> Detalle del Motivo
                            </label>
                            <input type="text" class="form-control form-control-sm" id="txt_detalle_motivo"
                                placeholder="Especifique detalles adicionales">
                        </div>
                    </div>

                    <!-- ================== TIPO DE CERTIFICADO ================== -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="fw-bold">
                                <i class="bi bi-toggle-on"></i> Certificados
                            </label>

                            <!-- MATERNIDAD / PATERNIDAD -->
                            <div class="form-check mt-2">
                                <input class="form-check-input cert-check" type="checkbox"
                                    id="cbx_permiso_maternidad_paternidad" data-target="#pnl_file_maternidad">
                                <label class="form-check-label" for="cbx_permiso_maternidad_paternidad">
                                    Maternidad / Paternidad
                                </label>
                            </div>

                            <div id="pnl_file_maternidad" class="row mb-2" style="display:none">
                                <div class="col-md-6">
                                    <input type="file" class="form-control form-control-sm" id="file_cert_maternidad"
                                        accept=".pdf,.doc,.docx">
                                    <small class="text-muted">
                                        PDF o Word • Máx. 5MB
                                    </small>
                                </div>
                            </div>

                            <!-- ENFERMEDAD -->
                            <div class="form-check mt-2">
                                <input class="form-check-input cert-check" type="checkbox" id="cbx_permiso_enfermedad"
                                    data-target="#pnl_file_enfermedad">
                                <label class="form-check-label" for="cbx_permiso_enfermedad">
                                    Enfermedad
                                </label>
                            </div>

                            <div id="pnl_file_enfermedad" class="row mb-2" style="display:none">
                                <div class="col-md-6">
                                    <input type="file" class="form-control form-control-sm" id="file_cert_enfermedad"
                                        accept=".pdf,.doc,.docx">
                                    <small class="text-muted">
                                        Certificado Médico • PDF o Word • Máx. 5MB
                                    </small>
                                </div>
                            </div>

                            <!-- CITA MÉDICA -->
                            <div class="form-check mt-2">
                                <input class="form-check-input cert-check" type="checkbox" id="cbx_permiso_cita_medica"
                                    data-target="#pnl_file_cita">
                                <label class="form-check-label" for="cbx_permiso_cita_medica">
                                    Cita Médica
                                </label>
                            </div>

                            <div id="pnl_file_cita" class="row mb-2" style="display:none">
                                <div class="col-md-6">
                                    <input type="file" class="form-control form-control-sm" id="file_cert_cita"
                                        accept=".pdf,.doc,.docx">
                                    <small class="text-muted">
                                        Certificado de Asistencia • PDF o Word • Máx. 5MB
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- ================== FALLECIMIENTO ================== -->
                    <div id="pnl_fallecimiento" style="display:none">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-people"></i> Información del Familiar Fallecido
                                </h6>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label class="fw-bold">Tipo de Familiar</label>
                                        <select class="form-control form-control-sm" id="ddl_parentesco">
                                            <option value="">-- Seleccione --</option>
                                            <option value="FAMILIAR">Familiar</option>
                                            <option value="HIJO">Hijo</option>
                                            <option value="ADULTO">Adulto (Cuidado Familiar)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3" id="pnl_rango_edad" style="display:none">
                                        <label class="fw-bold">Rango de Edad (Hijo)</label>
                                        <select class="form-control form-control-sm" id="ddl_rango_edad">
                                            <option value="">-- Seleccione --</option>
                                            <option value="0-5">0 - 5 años</option>
                                            <option value="6-11">6 - 11 años</option>
                                            <option value="12-17">12 - 17 años</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3" id="pnl_tipo_adulto" style="display:none">
                                        <label class="fw-bold">Tipo de Cuidado</label>
                                        <select class="form-control form-control-sm" id="ddl_tipo_adulto">
                                            <option value="">-- Seleccione --</option>
                                            <option value="DISCAPACIDAD">Discapacidad</option>
                                            <option value="ADULTO_MAYOR">Adulto Mayor</option>
                                            <option value="ENFERMEDAD_CATASTROFICA">Enfermedad Catastrófica</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="fw-bold">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control form-control-sm"
                                            id="txt_fecha_nacimiento">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="fw-bold">Edad</label>
                                        <input type="number" class="form-control form-control-sm" id="txt_edad"
                                            readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="ddl_familiar" class="fw-bold">Familiar</label>
                                        <select class="form-control form-control-sm" id="ddl_familiar">
                                            <option value="">-- Seleccione --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================== ATENCIÓN MÉDICA ================== -->
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
                                            <input class="form-check-input" type="radio" name="rbx_tipo_atencion"
                                                id="rbx_privada" value="PRIVADA">
                                            <label class="form-check-label" for="rbx_privada">Privada</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rbx_tipo_atencion"
                                                id="rbx_publica" value="PUBLICA">
                                            <label class="form-check-label" for="rbx_publica">Pública</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="fw-bold">Lugar</label>
                                        <input type="text" class="form-control form-control-sm" id="txt_lugar"
                                            placeholder="Ej: Hospital del Sur">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="fw-bold">Especialidad</label>
                                        <input type="text" class="form-control form-control-sm" id="txt_especialidad"
                                            placeholder="Ej: Cardiología">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="fw-bold">Nombre del Médico</label>
                                        <input type="text" class="form-control form-control-sm" id="txt_medico"
                                            placeholder="Dr/Dra.">
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

                    <!-- ================== TIPO DE PERMISO ================== -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="fw-bold"><i class="bi bi-toggle-on"></i> Tipo de Permiso</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="cbx_permiso_fecha">
                                <label class="form-check-label" for="cbx_permiso_fecha">Por Fecha</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="cbx_permiso_hora">
                                <label class="form-check-label" for="cbx_permiso_hora">Por Hora</label>
                            </div>
                        </div>
                    </div>

                    <!-- ================== FECHA Y HORA PERMISO ================== -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-clock-history"></i> Fecha y Hora del Permiso
                            </h6>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label class="fw-bold">Fecha Desde</label>
                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_desde">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-bold">Fecha Hasta</label>
                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_hasta">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-bold">Total Días</label>
                                    <input type="number" class="form-control form-control-sm" id="txt_total_dias"
                                        readonly style="background-color: #e9ecef;">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label class="fw-bold">Hora Desde</label>
                                    <input type="time" class="form-control form-control-sm" id="txt_hora_permiso_desde">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-bold">Hora Hasta</label>
                                    <input type="time" class="form-control form-control-sm" id="txt_hora_permiso_hasta">
                                </div>

                                <div class="col-md-3">
                                    <label class="fw-bold">Total Horas</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        id="txt_total_horas" readonly style="background-color: #e9ecef;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================== BOTONES ================== -->
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