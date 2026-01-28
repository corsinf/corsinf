<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
$_per_id = (isset($_GET['_per_id'])) ? $_GET['_per_id'] : '';
$ruta = '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script>
    //quitar session

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
                $('#lbl_fecha_nacimiento').text(data.fecha_nacimiento);
                let edadCalculada = calcularEdad(data.fecha_nacimiento, data.parentesco);
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

        const USER_DATA = {
            tipo: "<?= $_SESSION['INICIO']['TIPO'] ?>",
            id: "<?= (($_SESSION['INICIO']['TIPO'] === 'DBA' || $_SESSION['INICIO']['TIPO'] === 'ADMINISTRADOR')) ? '' : $_SESSION['INICIO']['NO_CONCURENTE'] ?>"
        };


        if (USER_DATA.tipo === 'DBA' || USER_DATA.tipo === 'ADMINISTRADOR') {
            <?php if (isset($_per_id) && $_per_id !== '') : ?>
                $('#ddl_personas').prop('disabled', true);
                cargar_datos_persona(<?= $_per_id ?>);
                cargar_persona_familia(<?= $_per_id ?>);
            <?php endif; ?>
        } else {
            $('#ddl_personas').prop('disabled', true);
        }


        function cargar_selects2() {
            url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?busca_persona_nomina=true';
            cargar_select2_url('ddl_personas', url_personasC);
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

        $('input[name="tipo_calculo"]').change(function() {
            if ($('#rbtn_fecha').is(':checked')) {
                $('#pnl_calculo_fecha').slideDown();
                $('#pnl_calculo_horas').slideUp();
            } else if ($('#rbtn_horas').is(':checked')) {
                $('#pnl_calculo_fecha').slideUp();
                $('#pnl_calculo_horas').slideDown();
            }
        });

        // Inicializar con el modo fecha por defecto
        $('#rbtn_fecha').prop('checked', true).trigger('change');

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
                $('#pnl_certificado_asistencia').slideDown();
            }
        });

        $('#ddl_motivo').change(function() {
            let v = $(this).val();

            $('#pnl_info_adicional').hide();
            $('#txt_detalle_motivo').val('');
            $('#pnl_acta_defuncion').slideUp();

            if (v === 'PERSONAL') {}
            if (v === 'FAMILIAR') {
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
        // Usamos .on('blur') para que se ejecute al perder el foco
        $('#txt_hora_permiso_desde, #txt_hora_permiso_hasta').on('blur', function() {
            let d = $('#txt_hora_permiso_desde').val();
            let h = $('#txt_hora_permiso_hasta').val();

            if (d && h) {
                let ini = new Date(`1970-01-01T${d}`);
                let fin = new Date(`1970-01-01T${h}`);

                // Cálculo de diferencia en milisegundos a horas
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
        $('#txt_fecha_desde, #txt_fecha_hasta').on('blur', function() {
            let f1 = $('#txt_fecha_desde').val();
            let f2 = $('#txt_fecha_hasta').val();

            if (f1 && f2) {
                // Es recomendable reemplazar '-' por '/' para evitar problemas de zona horaria en algunos navegadores
                let fecha1 = new Date(f1.replace(/-/g, '\/'));
                let fecha2 = new Date(f2.replace(/-/g, '\/'));

                if (fecha2 < fecha1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha Inválida',
                        text: 'La fecha hasta debe ser mayor o igual que la fecha desde',
                        confirmButtonColor: '#3085d6'
                    });
                    $('#txt_total_dias').val(0);
                } else {
                    // Cálculo de diferencia en días
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


    function calcularEdad(fecha, tipo_pariente = "OTRO") {
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

        if (tipo_pariente == "HIJO/A") {
            let rango = "";
            if (edad >= 0 && edad <= 5) {
                rango = "0-5";
            } else if (edad >= 6 && edad <= 11) {
                rango = "6-11";
            } else if (edad >= 12 && edad <= 17) {
                rango = "12-17";
            } else {
                rango = ""; // Fuera de los rangos especificados
            }

            // 3. Seleccionar el valor en el dropdown
            $('#ddl_rango_edad').val(rango);
        }
        $('#lbl_edad').text(edad + ' años');

        return edad;
    }

    function toDateInput(val) {
        if (!val) return '';
        return val.split(' ')[0];
    }

    function toTimeInput(val) {
        if (!val || val.startsWith('1900')) return '';
        return val.split(' ')[1].substring(0, 5);
    }
    // Para los campos de fecha simples
    $('#txt_fecha_desde, #txt_fecha_hasta').on('blur', function() {
        calcularDiasFecha();
    });

    // Para los campos de horas (usando delegación de eventos)
    $(document).on('blur', '#txt_fecha_horas, #txt_hora_desde, #txt_hora_hasta', function() {
        calcularTotalHoras();
    });

    // Para las horas de atención (usando delegación de eventos)
    $(document).on('blur', '#txt_hora_desde_atencion, #txt_hora_hasta_atencion', function() {
        validarHorasAtencion();
    });

    function validarHorasAtencion() {
        const horaDesde = $('#txt_hora_desde_atencion').val();
        const horaHasta = $('#txt_hora_hasta_atencion').val();

        if (horaDesde && horaHasta) {
            // Convertimos a minutos para comparar fácilmente
            const [h1, m1] = horaDesde.split(':').map(Number);
            const [h2, m2] = horaHasta.split(':').map(Number);

            const totalMinutosDesde = h1 * 60 + m1;
            const totalMinutosHasta = h2 * 60 + m2;

            if (totalMinutosHasta <= totalMinutosDesde) {
                Swal.fire({
                    icon: 'error',
                    title: 'Rango de horas inválido',
                    text: 'La "Hora Hasta" debe ser mayor que la "Hora Desde".',
                    confirmButtonColor: '#d33'
                });

                // Limpiamos el campo "Hasta" para obligar a corregir
                $('#txt_hora_hasta_atencion').val('');
            }
        }
    }

    function calcularDiasFecha() {
        let fecha1 = $('#txt_fecha_desde').val();
        let fecha3 = $('#txt_fecha_hasta').val();

        if (fecha1 && fecha3) {
            // Creamos los objetos de fecha
            let f1 = new Date(fecha1);
            let f3 = new Date(fecha3);

            // Calculamos la diferencia en milisegundos
            let diferencia = f3.getTime() - f1.getTime();

            // Convertimos a días (ms / (1000ms * 60s * 60m * 24h))
            let totalDias = Math.floor(diferencia / (1000 * 60 * 60 * 24));

            // Validamos que el resultado no sea negativo
            if (totalDias < 0) {
                console.log("La fecha 'Hasta' debe ser mayor a 'Desde'");
                $('#txt_total_dias').val(0);
            } else {
                // Sumamos 1 si quieres contar el día inicial como un día de permiso
                // de lo contrario, deja solo totalDias
                $('#txt_total_dias').val(totalDias + 1);
            }
        }
    }

    function calcularTotalHoras() {
        let fecha = $('#txt_fecha_horas').val();
        let horaDesde = $('#txt_hora_desde').val();
        let horaHasta = $('#txt_hora_hasta').val();

        if (fecha && horaDesde && horaHasta) {
            if (horaDesde >= horaHasta) {
                Swal.fire('Advertencia', 'La hora desde debe ser menor que la hora hasta', 'warning');
                $('#txt_total_horas').val(""); // Limpiamos para tipo time
                return;
            }

            let desde = new Date('2000-01-01T' + horaDesde);
            let hasta = new Date('2000-01-01T' + horaHasta);

            let diffMs = hasta - desde;
            let diffMinutosTotales = Math.floor(diffMs / (1000 * 60));

            let horas = Math.floor(diffMinutosTotales / 60);
            let minutos = diffMinutosTotales % 60;

            // FORMATO REQUERIDO PARA <input type="time">: "HH:mm" (con ceros a la izquierda)
            let hh = horas.toString().padStart(2, '0');
            let mm = minutos.toString().padStart(2, '0');

            let resultadoTime = `${hh}:${mm}`;

            $('#txt_total_horas').val(resultadoTime);
        }
    }


    function limpiar_campos() {
        $('#lbl_cargo').val('').trigger('change');
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
        $('#txt_fecha_atencion, #txt_hora_desde_atencion, #txt_hora_hasta_atencion').val('');
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
                $("#txt_hora_desde_atencion").val(toTimeInput(r.hora_desde) || '');
                $("#txt_hora_hasta_atencion").val(toTimeInput(r.hora_hasta) || '');

                // ========== PARENTESCO ==========
                if (r.fam_hijos_adultos) {
                    $("#ddl_parentesco").val(r.fam_hijos_adultos).trigger('change');


                    if (r.fam_hijos_adultos === 'HIJO' && r.rango_edad) {
                        $("#ddl_rango_edad").val(r.rango_edad).trigger('change');
                    }
                    if (r.fam_hijos_adultos === 'OTRO' && r.tipo_cuidado) {
                        $("#ddl_otro").val(r.tipo_cuidado).trigger('change');
                    }


                    const fechaNac = toDateInput(r.fecha_nacimiento);
                    $("#txt_fecha_nacimiento").val(fechaNac);
                    if (typeof calcularEdad === "function") calcularEdad(fechaNac);
                }

                // ========== ACTA DE DEFUNCIÓN ==========
                if (r.ruta_act_defuncion) {
                    $('#txt_ruta_act_defuncion_guardada').val(r.ruta_act_defuncion);
                    mostrarVistaPrevia('#pnl_acta_defuncion', r.ruta_act_defuncion, 'Acta de Defunción');
                }

                if (r.ruta_certificado_asistencia) {
                    $('#txt_ruta_asistencia_guardada').val(r.ruta_certificado_asistencia);
                    $('#pnl_certificado_asistencia').show();
                    mostrarVistaPrevia('#pnl_certificado_asistencia', r.ruta_certificado_asistencia, 'Certificado de Asistencia');
                }

                if (r.th_ppa_id > 0) {
                    cargar_datos_pariente(r.th_ppa_id);
                }

                // Planificación
                if (r.planificacion && r.planificacion !== "") {
                    $("#pnl_espacio_docente").show();
                    $(`input[name="rbx_planificacion"][value="${r.planificacion}"]`).prop('checked', true);
                }

                let tipoCalculo = r.tipo_calculo || 'fecha';

                if (tipoCalculo === 'fecha') {
                    $('#rbtn_fecha').prop('checked', true).trigger('change');
                    $("#txt_fecha_desde").val(toDateInput(r.fecha_desde_permiso));
                    $("#txt_fecha_hasta").val(toDateInput(r.fecha_hasta_permiso));
                    $("#txt_total_dias").val(parseInt(r.total_dias_permiso) || 0);
                } else {
                    $('#rbtn_horas').prop('checked', true).trigger('change');
                    $("#txt_fecha_horas").val(toDateInput(r.fecha_principal_permiso));
                    $("#txt_hora_desde").val(toTimeInput(r.fecha_desde_permiso));
                    $("#txt_hora_hasta").val(toTimeInput(r.fecha_hasta_permiso));
                    // r.total_horas_permiso viene de la BD (ej: 150.00)
                    let minutosTotales = parseFloat(r.total_horas_permiso) || 0;

                    if (minutosTotales > 0) {
                        let horas = Math.floor(minutosTotales / 60);
                        let minutos = Math.round(minutosTotales % 60);

                        // Formatear con ceros a la izquierda para el input type="time"
                        let hh = horas.toString().padStart(2, '0');
                        let mm = minutos.toString().padStart(2, '0');

                        $("#txt_total_horas").val(`${hh}:${mm}`);
                    } else {
                        $("#txt_total_horas").val("00:00");
                    }
                }
                let validator = $("#form_solicitud").validate();
                validator.resetForm();

                // Quitamos manualmente las clases de Bootstrap por si acaso
                $("#form_solicitud").find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
            }
        });
    }

    function cargar_datos_persona(per_id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_persona_departamento_cargo=true',
            type: 'post',
            data: {
                id: per_id
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#ddl_personas').append($('<option>', {
                        value: response[0].id_persona,
                        text: response[0].cedula + " - " + response[0].nombre_completo,
                        selected: true
                    }));

                    $("#lbl_cargo").text(response[0].nombre_cargo || 'No asignado');
                    $("#lbl_genero").text(response[0].sexo || 'No asignado');
                    $("#lbl_estado_civil").text(response[0].estado_civil || 'No asignado');

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
    /*

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

            let camposMedicos = ['#txt_lugar', '#txt_especialidad', '#txt_medico', '#txt_fecha_atencion', '#txt_hora_desde_atencion', '#txt_hora_hasta_atencion'];
            for (let campo of camposMedicos) {
                if (!$(campo).val()) {
                    Swal.fire('Error', 'Complete todos los campos de atención médica', 'error');
                    return false;
                }
            }
        }
        if ($('#rbtn_fecha').is(':checked')) {
            if (!$('#txt_fecha_desde').val() || !$('#txt_fecha_hasta').val()) {
                Swal.fire('Error', 'Complete todas las fechas del rango', 'error');
                return false;
            }
        } else {
            if (!$('#txt_fecha_horas').val() || !$('#txt_hora_desde').val() || !$('#txt_hora_hasta').val()) {
                Swal.fire('Error', 'Complete la fecha y el horario (Desde/Hasta)', 'error');
                return false;
            }
        }

        return true;
    }
        */

    function combinarFechaHora(fecha, hora) {
        if (!fecha || !hora) return '';
        return fecha + ' ' + hora + ':00';
    }

    function mostrarVistaPrevia(panelSelector, rutaArchivo, nombreDoc) {
        const $panel = $(panelSelector);

        // 1. Limpiamos cualquier vista previa existente para no duplicar
        $panel.find('.vista-previa-documento').remove();

        // 2. Creamos la estructura con el diseño "Imagen 5"
        const vistaPrevia = `
    <div class="vista-previa-documento mt-3 p-3 border rounded shadow-xs bg-white" 
         style="border-left: 4px solid #212529 !important;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bx bxs-file-pdf fs-2 text-danger me-3"></i>
                <div>
                    <small class="fw-bold d-block text-uppercase text-muted" style="font-size: 0.7rem;">${nombreDoc}</small>
                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">Archivo guardado</span>
                </div>
            </div>
            <button type="button" 
                    onclick="ruta_iframe_documento_identificacion('${rutaArchivo}');" 
                    class="btn btn-dark btn-sm px-4 fw-bold shadow-sm">
                <i class="bx bx-show-alt me-1"></i> DOCUMENTO
            </button>
        </div>
    </div>`;

        // 3. Inserción inteligente: Busca el card-body, si no existe, lo pega al final del panel
        const $target = $panel.find('.card-body').length ? $panel.find('.card-body') : $panel;
        $target.append(vistaPrevia);
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

        // ===== FECHAS DEL PERMISO (calculadas) =====
        let tipoCalculo = $('#rbtn_fecha').is(':checked') ? 'fecha' : 'horas';
        let fechaPrincipalPermiso, desdePermiso, hastaPermiso, totalDias = 0,
            totalHoras = 0;

        if (tipoCalculo === 'fecha') {
            desdePermiso = $('#txt_fecha_desde').val();
            hastaPermiso = $('#txt_fecha_hasta').val();
            totalDias = parseInt($('#txt_total_dias').val()) || 0;
        } else {
            let fecha = $('#txt_fecha_horas').val();
            let horaD = $('#txt_hora_desde').val();
            let horaH = $('#txt_hora_hasta').val();
            fechaPrincipalPermiso = fecha;
            desdePermiso = fecha + ' ' + horaD + ':00';
            hastaPermiso = fecha + ' ' + horaH + ':00';

            let valorTime = $('#txt_total_horas').val();
            if (valorTime) {
                let partes = valorTime.split(':');
                let h = parseInt(partes[0]) || 0;
                let m = parseInt(partes[1]) || 0;

                // Convertimos todo a minutos
                totalHoras = parseFloat((h * 60) + m);
            }
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

            // FECHAS DEL PERMISO (calculadas)
            'tipo_calculo': tipoCalculo,
            'fecha_principal_permiso': fechaPrincipalPermiso,
            'desde_permiso': desdePermiso,
            'hasta_permiso': hastaPermiso,
            'total_dias': totalDias,
            'total_horas': totalHoras,

            // Médico
            'tipo_atencion': $('input[name="rbx_tipo_atencion"]:checked').val() || null,
            'lugar': $("#txt_lugar").val() || null,
            'especialidad': $("#txt_especialidad").val() || null,
            'medico': $("#txt_medico").val() || null,
            'fecha_atencion': $("#txt_fecha_atencion").val() || fechaDesde,
            'hora_desde': combinarFechaHora($("#txt_fecha_atencion").val(), $("#txt_hora_desde_atencion").val()),
            'hora_hasta': combinarFechaHora($("#txt_fecha_atencion").val(), $("#txt_hora_hasta_atencion").val()),

            // Rutas actuales
            'ruta_certificado_actual': $("#txt_ruta_certificado_guardada").val() || null,
            'ruta_act_defuncion_actual': $("#txt_ruta_act_defuncion_guardada").val() || null,
            'planificacion': $('input[name="rbx_planificacion"]:checked').val() || null,
        };

        form_data.append('parametros', JSON.stringify(parametros));

        // Agregar archivos
        let file_certificado = $('#file_certificado')[0].files[0];
        let file_acta_defuncion = $('#file_act_defuncion')[0].files[0];
        let file_certificado_asistencia = $('#file_certificado_asistencia')[0].files[0];

        if (file_certificado) {
            form_data.append('file_certificado', file_certificado);
        }
        if (file_acta_defuncion) {
            form_data.append('file_act_defuncion', file_acta_defuncion);
        }
        if (file_certificado_asistencia) {
            form_data.append('file_certificado_asistencia', file_certificado_asistencia);
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

    function ruta_iframe_documento_identificacion(url) {
        if (!url || url === "" || url === null) {
            Swal.fire('Error', 'No existe una ruta de archivo válida para este documento.', 'error');
            return;
        }

        // Mostramos el modal
        $('#modal_ver_pdf_documentos_identidad').modal('show');

        // Asignamos la ruta al iframe
        $('#iframe_documentos_identidad_pdf').attr('src', url);
    }

    /**
     * Limpia el iframe al cerrar el modal
     */
    function limpiar_parametros_iframe() {
        $('#iframe_documentos_identidad_pdf').attr('src', '');
    }
</script>



<div class="page-wrapper">
    <div class="page-content">

        <div class="card border-primary border-bottom border-3 shadow-sm">
            <div class="card-body p-4">

                <div class="card-title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div><i class="bx bx-layer me-2 font-22 text-primary"></i></div>
                        <h5 class="mb-0 text-primary">
                            <?= ($_id == '') ? 'Registrar Solicitud' : 'Modificar Solicitud' ?>
                        </h5>
                    </div>

                    <div>
                        <a href="../vista/inicio.php?mod=$modulo_sistema&acc=th_solicitud_permiso"
                            class="btn btn-outline-dark btn-sm">
                            <i class="bx bx-arrow-back"></i> Regresar
                        </a>
                    </div>
                </div>

                <hr>
                <form id="form_solicitud">
                    <input type="hidden" id="txt_id" name="txt_id">
                    <input type="hidden" id="txt_cedula_persona" name="txt_cedula_persona">
                    <input type="hidden" id="txt_ruta_certificado_guardada">
                    <input type="hidden" id="txt_ruta_act_defuncion_guardada">
                    <input type="hidden" id="txt_ruta_certificado_asistencia">

                    <!-- INFORMACIÓN DEL SOLICITANTE -->
                    <div class="card border-primary border-bottom border-3 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="bi bi-person-badge me-2"></i>Información del Solicitante</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="ddl_personas" class="form-label fw-bold">Persona </label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_personas" name="ddl_personas">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="tipo_asunto">Asunto </label>
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
                            <div id="pnl_persona_informacion_adicional" class="row g-3 mt-1 mb-3">
                                <div class="col-md-4">
                                    <label class="fw-bold small d-block">Cargo</label>
                                    <span id="lbl_cargo" class="text-muted small">---</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-bold small d-block">Género</label>
                                    <span id="lbl_genero" class="text-muted small">---</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-bold small d-block">Estado Civil</label>
                                    <span id="lbl_estado_civil" class="text-muted small">---</span>
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- MOTIVO DE LA AUSENCIA -->
                    <div class="card border-primary border-bottom border-3 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="bi bi-question-circle me-2"></i>Motivo de la Ausencia</h6>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="fw-bold mb-2 small text-uppercase" for="rbx_tipo_motivo">Seleccione el Tipo </label>
                                    <div class="d-flex gap-4 border p-2 rounded bg-white">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="rbx_tipo_motivo" id="rbx_motivo_personal" value="MOTIVO_PERSONAL">
                                            <label class="form-check-label fw-bold" for="rbx_motivo_personal">Personal / Familiar</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="rbx_tipo_motivo" id="rbx_motivo_medico" value="MOTIVO_MEDICO">
                                            <label class="form-check-label fw-bold" for="rbx_motivo_medico">Médico / Salud</label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="pnl_motivo_personal" class="row g-3 mb-3" style="display: none;">
                                <div class="col-md-6">
                                    <label class="fw-bold" for="ddl_motivo">Categoría </label>
                                    <select class="form-select form-select-sm" id="ddl_motivo" name="ddl_motivo">
                                        <option value="">-- Seleccione --</option>
                                        <option value="PERSONAL">Personal</option>
                                        <option value="FAMILIAR">Familiar</option>
                                        <option value="CALAMIDAD">Calamidad Doméstica</option>
                                        <option value="FALLECIMIENTO">Fallecimiento</option>
                                    </select>
                                </div>
                                <div class="col-md-6"> <label class="fw-bold" for="txt_detalle_motivo">Detalle específico </label>
                                    <textarea
                                        class="form-control form-control-sm"
                                        id="txt_detalle_motivo"
                                        name="txt_detalle_motivo"
                                        rows="1"></textarea>
                                </div>
                            </div>

                            <div id="pnl_info_adicional" style="display:none">
                                <h6 class="text-primary mb-3"><i class="bi bi-people me-2"></i> Información adicional</h6>
                                <div class="row g-3">
                                    <div class="col-md-3" hidden>
                                        <label class="fw-bold">Tipo de Familiar</label>
                                        <select class="form-control form-control-sm" id="ddl_parentesco">
                                            <option value="">-- Seleccione --</option>
                                            <option value="HIJO">Hijo</option>
                                            <option value="OTRO">Otro</option>
                                        </select>
                                    </div>
                                    <div id="pnl_familiares" class="col-md-3">
                                        <label for="ddl_familiar" class="fw-bold">Familiar Seleccionado </label>
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
                                        <label for="ddl_otro" class="fw-bold text-danger">Tipo de Cuidado </label>
                                        <select class="form-control form-control-sm" id="ddl_otro">
                                            <option value="">-- Seleccione --</option>
                                            <option value="DISCAPACIDAD">Discapacidad</option>
                                            <option value="ADULTO_MAYOR">Adulto Mayor</option>
                                            <option value="ENFERMEDAD_CATASTROFICA">Enfermedad Catastrófica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="fw-bold">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control form-control-sm" id="txt_fecha_nacimiento" hidden>
                                            <div id="lbl_fecha_nacimiento" class="text-muted">---</div>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="fw-bold">Edad Calculada</label>
                                            <input type="number" class="form-control form-control-sm" id="txt_edad" hidden readonly>
                                            <div id="lbl_edad" style="font-size: 0.9rem;">0 años</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="pnl_motivo_medico" class="row g-3" style="display: none;">
                                <div class="col-md-6">
                                    <label for="ddl_motivo_medico" class="fw-bold">Categoría Médica </label>
                                    <select class="form-select form-select-sm" id="ddl_motivo_medico" name="ddl_motivo_medico">
                                        <option value="">-- Seleccione --</option>
                                        <option value="MATERNIDAD_PATERNIDAD">Maternidad/Paternidad</option>
                                        <option value="ENFERMEDAD">Enfermedad</option>
                                        <option value="CITA_MEDICA">Cita Médica</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="txt_detalle_motivo_medico" class="fw-bold">Observación Médica </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_detalle_motivo_medico" id="txt_detalle_motivo_medico">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="pnl_certificado_asistencia" class="card border-primary border-bottom border-3 shadow-sm mb-3" style="display: none;">
                        <div class="col-md-6">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-file-earmark-check me-2"></i> Certificado de Asistencia
                                </h6>
                                <label for="file_certificado_asistencia" class="fw-bold">Adjuntar Certificado</label>
                                <input type="file" class="form-control form-control-sm" id="file_certificado_asistencia" accept=".pdf">

                                <div class="row">
                                    <div class="col-md-6">

                                        <small class="text-muted">PDF • Máx. 5MB</small>
                                        <input type="hidden" id="txt_ruta_asistencia_guardada">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ACTA DE DEFUNCIÓN -->
                    <div id="pnl_acta_defuncion" class="card border-primary border-bottom border-3 shadow-sm mb-3" style="display: none;">
                        <div class="col-md-6">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-file-earmark-text me-2"></i> Acta de Defunción (Obligatorio)
                                </h6>
                                <label for="file_act_defuncion" class="fw-bold">Adjuntar Acta </label>
                                <input type="file" class="form-control form-control-sm" id="file_act_defuncion" accept=".pdf">

                                <div class="row">
                                    <div class="col-md-6">

                                        <small class="text-muted">PDF • Máx. 5MB</small>
                                    </div>
                                    <input type="hidden" id="txt_ruta_act_defuncion_guardada">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INFORMACIÓN ADICIONAL (PARA PERSONAL) -->


                    <!-- CERTIFICADO MÉDICO (ÚNICO) -->
                    <div id="pnl_file_certificado" class="card border-primary border-bottom border-3 shadow-sm mb-3" style="display:none">
                        <div class="col-md-6">
                            <div class="card-body">
                                <h6 id="title_certificado" class="text-primary mb-3">
                                    <i class="bi bi-file-earmark-medical me-2"></i> Certificado Médico
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="fw-bold" for="file_certificado">Adjuntar Certificado </label>
                                        <input type="file" class="form-control form-control-sm" id="file_certificado" accept=".pdf">
                                        <small class="text-muted">PDF • Máx. 5MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ATENCIÓN MÉDICA -->
                    <div id="pnl_medico" class="card border-primary border-bottom border-3 shadow-sm mb-3" style="display:none">
                        <div class="card-body">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-heart-pulse me-2"></i> Detalle de Atención Médica
                            </h6>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="fw-bold small text-uppercase" for="rbx_tipo_atencion">Tipo de Atención </label><br>
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
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="fw-bold" for="txt_lugar">Lugar / Clínica </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_lugar" id="txt_lugar" placeholder="Ej: Hospital">
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-bold" for="txt_especialidad">Especialidad </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_especialidad" id="txt_especialidad">
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-bold" for="txt_medico">Nombre del Médico </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_medico" id="txt_medico">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="fw-bold" for="txt_fecha_atencion">Fecha Atención </label>
                                    <input type="date" class="form-control form-control-sm" name="txt_fecha_atencion" id="txt_fecha_atencion">
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-bold" for="txt_hora_desde_atencion">Hora Desde </label>
                                    <input type="time" class="form-control form-control-sm" name="txt_hora_desde_atencion" id="txt_hora_desde_atencion">
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-bold" for="txt_hora_hasta_atencion">Hora Hasta </label>
                                    <input type="time" class="form-control form-control-sm" name="txt_hora_hasta_atencion" id="txt_hora_hasta_atencion">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PLANIFICACIÓN DE TIEMPOS -->
                    <div class="card border-primary border-bottom border-3 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="bi bi-calendar-event me-2"></i>Fecha y Hora del Permiso</h6>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="fw-bold mb-2 small text-uppercase" for="tipo_calculo">Método de Cálculo </label>
                                    <div class="d-flex gap-4 border p-2 rounded bg-white">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_calculo" id="rbtn_fecha" value="fecha">
                                            <label class="form-check-label fw-bold" for="rbtn_fecha">Por Rango de Fechas (Días) </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_calculo" id="rbtn_horas" value="horas">
                                            <label class="form-check-label fw-bold" for="rbtn_horas">Por Horas </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="pnl_calculo_fecha" style="display:none">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="fw-bold" for="txt_fecha_desde">Desde </label>
                                        <input type="date" class="form-control form-control-sm" name="txt_fecha_desde" id="txt_fecha_desde">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold" for="txt_fecha_hasta">Hasta </label>
                                        <input type="date" class="form-control form-control-sm" name="txt_fecha_hasta" id="txt_fecha_hasta">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold text-success" for="txt_total_dias">Total Días </label>
                                        <input type="number" class="form-control form-control-sm bg-white" name="txt_total_dias" id="txt_total_dias" readonly>
                                    </div>
                                </div>
                            </div>

                            <div id="pnl_calculo_horas" style="display:none">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="fw-bold" for="txt_fecha_horas">Fecha del Permiso </label>
                                        <input type="date" class="form-control form-control-sm" name="txt_fecha_horas" id="txt_fecha_horas">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="fw-bold" for="txt_hora_desde">Hora Desde </label>
                                        <input type="time" class="form-control form-control-sm" name="txt_hora_desde" id="txt_hora_desde">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="fw-bold" for="txt_hora_hasta">Hora Hasta </label>
                                        <input type="time" class="form-control form-control-sm" name="txt_hora_hasta" id="txt_hora_hasta">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="fw-bold text-success" for="txt_total_horas">Total Horas </label>
                                        <input type="time" class="form-control form-control-sm bg-white" name="txt_total_horas" id="txt_total_horas" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PLANIFICACIÓN DOCENTE -->
                    <div id="pnl_espacio_docente" class="card border-primary border-bottom border-3 shadow-sm mb-3" style="display: none;">
                        <div class="card-body">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person-check-fill me-2"></i> Espacio de Responsabilidad (Personal Docente)
                            </h6>
                            <div class="d-flex gap-4 border p-2 rounded bg-white">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rbx_planificacion" id="plani_no" value="NO_REQUIERE">
                                    <label class="form-check-label fw-bold" for="plani_no">
                                        NO REQUIERE PLANIFICACIÓN
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rbx_planificacion" id="plani_si" value="ANEXO_PLANIFICACION">
                                    <label class="form-check-label fw-bold" for="plani_si">
                                        ANEXO PLANIFICACIÓN
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTONES -->
                    <hr>
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary px-4 shadow-sm" onclick="insertar_actualizar()">
                                <i class="bx bx-save"></i> Guardar Solicitud
                            </button>
                            <?php if ($_id != '') { ?>
                                <button type="button" class="btn btn-danger px-4 shadow-sm" onclick="eliminar()">
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

<div class="modal fade" id="modal_ver_pdf_documentos_identidad" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark bg-opacity-10 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-2 rounded-circle me-2 text-primary shadow-sm">
                        <i class='bx bxs-file-pdf bx-sm'></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Visor de Documentos</h5>
                        <small class="text-muted" id="lbl_subtitulo_pdf">Vista previa del archivo adjunto</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-0 bg-light">
                <div class="w-100 position-relative" style="height: 80vh;">
                    <div class="position-absolute top-50 start-50 translate-middle text-muted" style="z-index: 0;">
                        <i class='bx bx-loader-alt bx-spin bx-md'></i> Cargando documento...
                    </div>
                    <iframe src=""
                        id="iframe_documentos_identidad_pdf"
                        class="w-100 h-100 border-0 position-relative"
                        style="z-index: 1;"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
            <div class="modal-footer py-2 bg-white">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();">Cerrar Visualizador</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        // 1. Agregar asteriscos a campos obligatorios
        agregar_asterisco_campo_obligatorio('ddl_personas');
        agregar_asterisco_campo_obligatorio('txt_fecha_desde');
        agregar_asterisco_campo_obligatorio('txt_fecha_hasta');
        agregar_asterisco_campo_obligatorio('txt_fecha_horas');
        agregar_asterisco_campo_obligatorio('txt_hora_desde');
        agregar_asterisco_campo_obligatorio('txt_hora_hasta');
        agregar_asterisco_campo_obligatorio('ddl_motivo_medico');
        agregar_asterisco_campo_obligatorio('txt_detalle_motivo_medico');
        agregar_asterisco_campo_obligatorio('file_act_defuncion');
        agregar_asterisco_campo_obligatorio('tipo_calculo');
        agregar_asterisco_campo_obligatorio('txt_total_dias');
        agregar_asterisco_campo_obligatorio('txt_total_horas');
        agregar_asterisco_campo_obligatorio('rbx_tipo_motivo');
        agregar_asterisco_campo_obligatorio('ddl_familiar');
        agregar_asterisco_campo_obligatorio('ddl_motivo');
        agregar_asterisco_campo_obligatorio('txt_detalle_motivo');
        agregar_asterisco_campo_obligatorio('tipo_asunto');
        agregar_asterisco_campo_obligatorio('txt_lugar');
        agregar_asterisco_campo_obligatorio('txt_especialidad');
        agregar_asterisco_campo_obligatorio('txt_fecha_atencion');
        agregar_asterisco_campo_obligatorio('txt_medico');
        agregar_asterisco_campo_obligatorio('txt_hora_desde_atencion');
        agregar_asterisco_campo_obligatorio('txt_hora_hasta_atencion');
        agregar_asterisco_campo_obligatorio('file_certificado');
        agregar_asterisco_campo_obligatorio('rbx_tipo_atencion');

        // 2. Validación para select2
        $(".select2-validation").on("select2:select", function() {
            $(this).valid();
        });

        // 3. Validación del Formulario
        $("#form_solicitud").validate({
            ignore: [], // No ignorar campos ocultos
            rules: {
                // ========== OBLIGATORIOS SIEMPRE ==========
                ddl_personas: {
                    required: true
                },
                tipo_asunto: {
                    required: true
                },
                rbx_tipo_motivo: {
                    required: true
                },

                // ========== MOTIVO PERSONAL ==========
                ddl_motivo: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_PERSONAL';
                    }
                },
                txt_detalle_motivo: {
                    required: function() {
                        let tipoMotivo = $('input[name="rbx_tipo_motivo"]:checked').val();
                        let motivo = $('#ddl_motivo').val();
                        return tipoMotivo === 'MOTIVO_PERSONAL' &&
                            (motivo === 'PERSONAL' || motivo === 'CALAMIDAD' || motivo === 'FALLECIMIENTO' || motivo === 'FAMILIAR');
                    },
                    minlength: 10
                },

                // ========== INFO ADICIONAL (Fallecimiento y Familiar) ==========
                ddl_parentesco: {
                    required: function() {
                        let tipoMotivo = $('input[name="rbx_tipo_motivo"]:checked').val();
                        let motivo = $('#ddl_motivo').val();
                        return tipoMotivo === 'MOTIVO_PERSONAL' && (motivo === 'FALLECIMIENTO' || motivo === 'FAMILIAR');
                    }
                },
                ddl_familiar: {
                    required: function() {
                        let tipoMotivo = $('input[name="rbx_tipo_motivo"]:checked').val();
                        let motivo = $('#ddl_motivo').val();
                        return tipoMotivo === 'MOTIVO_PERSONAL' && (motivo === 'FALLECIMIENTO' || motivo === 'FAMILIAR');
                    }
                },
                txt_fecha_nacimiento: {
                    required: function() {
                        let tipoMotivo = $('input[name="rbx_tipo_motivo"]:checked').val();
                        let motivo = $('#ddl_motivo').val();
                        return tipoMotivo === 'MOTIVO_PERSONAL' && (motivo === 'FALLECIMIENTO' || motivo === 'FAMILIAR');
                    }
                },
                ddl_rango_edad: {
                    required: function() {
                        return $('#ddl_parentesco').val() === 'HIJO' && $('#pnl_rango_edad').is(':visible');
                    }
                },
                ddl_otro: {
                    required: function() {
                        return $('#ddl_parentesco').val() === 'OTRO' && $('#pnl_tipo_adulto').is(':visible');
                    }
                },

                // ========== ACTA DE DEFUNCIÓN ==========
                file_act_defuncion: {
                    required: function() {
                        let tipoMotivo = $('input[name="rbx_tipo_motivo"]:checked').val();
                        let motivo = $('#ddl_motivo').val();
                        let tieneActaGuardada = $('#txt_ruta_act_defuncion_guardada').val();
                        return tipoMotivo === 'MOTIVO_PERSONAL' && motivo === 'FALLECIMIENTO' && !tieneActaGuardada;
                    }
                },

                // ========== MOTIVO MÉDICO ==========
                ddl_motivo_medico: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    }
                },
                txt_detalle_motivo_medico: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    },
                    minlength: 10
                },

                // ========== CERTIFICADO MÉDICO ==========
                file_certificado: {
                    required: function() {
                        let tipoMotivo = $('input[name="rbx_tipo_motivo"]:checked').val();
                        let tieneCertificadoGuardado = $('#txt_ruta_certificado_guardada').val();
                        return tipoMotivo === 'MOTIVO_MEDICO' && !tieneCertificadoGuardado;
                    }
                },

                // ========== ATENCIÓN MÉDICA ==========
                rbx_tipo_atencion: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    }
                },
                txt_lugar: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    }
                },
                txt_especialidad: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    }
                },
                txt_medico: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    }
                },
                txt_fecha_atencion: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    }
                },
                txt_hora_desde_atencion: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    }
                },
                txt_hora_hasta_atencion: {
                    required: function() {
                        return $('input[name="rbx_tipo_motivo"]:checked').val() === 'MOTIVO_MEDICO';
                    }
                },

                // ========== FECHAS DEL PERMISO ==========
                tipo_calculo: {
                    required: true
                },
                txt_fecha_desde: {
                    required: function() {
                        return $("#rbtn_fecha").is(":checked");
                    }
                },
                txt_fecha_hasta: {
                    required: function() {
                        return $("#rbtn_fecha").is(":checked");
                    }
                },
                txt_fecha_horas: {
                    required: function() {
                        return $("#rbtn_horas").is(":checked");
                    }
                },
                txt_hora_desde: {
                    required: function() {
                        return $("#rbtn_horas").is(":checked");
                    }
                },
                txt_hora_hasta: {
                    required: function() {
                        return $("#rbtn_horas").is(":checked");
                    }
                }
            },
            messages: {
                ddl_personas: "Seleccione una persona",
                tipo_asunto: "Seleccione el tipo de asunto",
                rbx_tipo_motivo: "Seleccione el tipo de motivo",
                ddl_motivo: "Seleccione el motivo del permiso",
                txt_detalle_motivo: {
                    required: "Describa el motivo del permiso",
                    minlength: "El detalle debe tener al menos 10 caracteres"
                },
                ddl_parentesco: "Seleccione el tipo de parentesco",
                ddl_familiar: "Seleccione un familiar",
                txt_fecha_nacimiento: "Ingrese la fecha de nacimiento",
                ddl_rango_edad: "Seleccione el rango de edad",
                ddl_otro: "Seleccione el tipo de cuidado",
                file_act_defuncion: "Debe adjuntar el Acta de Defunción",
                ddl_motivo_medico: "Seleccione el motivo médico",
                txt_detalle_motivo_medico: {
                    required: "Describa la observación médica",
                    minlength: "La observación debe tener al menos 10 caracteres"
                },
                file_certificado: "Debe adjuntar el certificado médico",
                rbx_tipo_atencion: "Seleccione el tipo de atención",
                txt_lugar: "Ingrese el lugar de atención",
                txt_especialidad: "Ingrese la especialidad médica",
                txt_medico: "Ingrese el nombre del médico",
                txt_fecha_atencion: "Ingrese la fecha de atención",
                txt_hora_desde_atencion: "Ingrese la hora de inicio",
                txt_hora_hasta_atencion: "Ingrese la hora de fin",
                tipo_calculo: "Seleccione un método de cálculo",
                txt_fecha_desde: "Ingrese la fecha de inicio",
                txt_fecha_hasta: "Ingrese la fecha de fin",
                txt_fecha_horas: "Ingrese la fecha del permiso",
                txt_hora_desde: "Ingrese la hora de inicio",
                txt_hora_hasta: "Ingrese la hora de fin"
            },
            errorElement: "div",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");

                if (element.prop("type") === "radio" || element.prop("type") === "checkbox") {
                    error.insertAfter(element.closest("div").parent());
                } else if (element.hasClass("select2-hidden-accessible")) {
                    error.insertAfter(element.next(".select2-container"));
                } else if (element.prop("type") === "file") {
                    error.insertAfter(element.next("small"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    $element.next(".select2-container").find(".select2-selection")
                        .removeClass("is-valid").addClass("is-invalid");
                } else if ($element.is(':radio') || $element.is(':checkbox')) {
                    $('input[name="' + $element.attr("name") + '"]')
                        .addClass("is-invalid").removeClass("is-valid");
                } else {
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },
            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    $element.next(".select2-container").find(".select2-selection")
                        .removeClass("is-invalid").addClass("is-valid");
                } else if ($element.is(':radio') || $element.is(':checkbox')) {
                    $('input[name="' + $element.attr("name") + '"]')
                        .removeClass("is-invalid").addClass("is-valid");
                } else {
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });

        // 4. Triggers para revalidar cuando cambian los radios/selects
        $('input[name="rbx_tipo_motivo"]').change(function() {
            // Limpiar validaciones de paneles que se ocultan
            if ($(this).val() === 'MOTIVO_PERSONAL') {
                limpiar_errores_panel('#pnl_motivo_medico');
                limpiar_errores_panel('#pnl_file_certificado');
                limpiar_errores_panel('#pnl_medico');
            } else {
                limpiar_errores_panel('#pnl_motivo_personal');
                limpiar_errores_panel('#pnl_info_adicional');
                limpiar_errores_panel('#pnl_acta_defuncion');
            }

            // Revalidar el formulario
            $('#form_solicitud').valid();
        });

        $('#ddl_motivo').change(function() {
            // Revalidar campos relacionados
            $('#txt_detalle_motivo').valid();
            $('#ddl_parentesco').valid();
            $('#ddl_familiar').valid();
            $('#txt_fecha_nacimiento').valid();
            $('#file_act_defuncion').valid();
        });

        $('#ddl_motivo_medico').change(function() {
            $('#txt_detalle_motivo_medico').valid();
            $('#file_certificado').valid();
            $('input[name="rbx_tipo_atencion"]').valid();
        });

        $('#ddl_parentesco').change(function() {
            $('#ddl_rango_edad').valid();
            $('#ddl_otro').valid();
        });

        $('input[name="tipo_calculo"]').change(function() {
            if ($(this).val() === 'fecha') {
                limpiar_errores_panel('#pnl_calculo_horas');
            } else {
                limpiar_errores_panel('#pnl_calculo_fecha');
            }
        });

        $('input[name="rbx_tipo_atencion"]').change(function() {
            $(this).valid();
        });

        $('input[name="tipo_asunto"]').change(function() {
            $(this).valid();
        });
    });

    // Función para limpiar validaciones de paneles ocultos
    function limpiar_errores_panel(panelId) {
        $(panelId).find('input, select, textarea').each(function() {
            $(this).removeClass('is-invalid is-valid');
            $(this).next('.invalid-feedback').remove();
        });
    }

    function validar_formulario() {

        console.log("desde la validacion");


        let formularioValido = $("#form_solicitud").valid();

        if (!formularioValido) {
            Swal.fire({
                icon: 'error',
                title: 'Formulario Incompleto',
                text: 'Por favor complete todos los campos requeridos',
                confirmButtonColor: '#3085d6'
            });

            // Hacer scroll al primer campo con error
            let primerError = $('.is-invalid:first');
            if (primerError.length) {
                $('html, body').animate({
                    scrollTop: primerError.offset().top - 100
                }, 500);
            }

            return false;
        }

        // Validaciones adicionales de coherencia
        let tipo_motivo = $('input[name="rbx_tipo_motivo"]:checked').val();

        if (tipo_motivo === 'MOTIVO_PERSONAL') {
            let motivo = $('#ddl_motivo').val();

            // Validar fallecimiento
            if (motivo === 'FALLECIMIENTO') {
                let tiene_acta_nueva = $('#file_act_defuncion')[0].files.length > 0;
                let tiene_acta_guardada = $('#txt_ruta_act_defuncion_guardada').val();

                if (!tiene_acta_nueva && !tiene_acta_guardada) {
                    Swal.fire('Error', 'Debe adjuntar el Acta de Defunción', 'error');
                    $('#file_act_defuncion').addClass('is-invalid');
                    return false;
                }
            }
        } else if (tipo_motivo === 'MOTIVO_MEDICO') {
            // Validar certificado médico
            let tiene_cert_nuevo = $('#file_certificado')[0].files.length > 0;
            let tiene_cert_guardado = $('#txt_ruta_certificado_guardada').val();

            if (!tiene_cert_nuevo && !tiene_cert_guardado) {
                Swal.fire('Error', 'Debe adjuntar el certificado médico', 'error');
                $('#file_certificado').addClass('is-invalid');
                return false;
            }
        }

        // Validar fechas
        if ($('#rbtn_fecha').is(':checked')) {
            let fechaDesde = new Date($('#txt_fecha_desde').val());
            let fechaHasta = new Date($('#txt_fecha_hasta').val());

            if (fechaHasta < fechaDesde) {
                Swal.fire('Error', 'La fecha hasta debe ser mayor que la fecha desde', 'error');
                $('#txt_fecha_hasta').addClass('is-invalid');
                return false;
            }
        } else if ($('#rbtn_horas').is(':checked')) {
            if ($('#txt_hora_desde').val() >= $('#txt_hora_hasta').val()) {
                Swal.fire('Error', 'La hora hasta debe ser mayor que la hora desde', 'error');
                $('#txt_hora_hasta').addClass('is-invalid');
                return false;
            }
        }

        return true;
    }

    // Validación de coherencia de fechas
    $("#txt_fecha_desde, #txt_fecha_hasta").on("blur", function() {
        let f1 = $("#txt_fecha_desde").val();
        let f2 = $("#txt_fecha_hasta").val();
        if (f1 && f2 && f1 > f2) {
            Swal.fire('Error', 'La fecha "Desde" no puede ser mayor a la fecha "Hasta"', 'error');
            $(this).addClass('is-invalid');
        }
    });
</script>