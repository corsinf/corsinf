<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
$_per_id = (isset($_GET['_per_id'])) ? $_GET['_per_id'] : '';
$_id_sol = (isset($_GET['_id_sol'])) ? $_GET['_id_sol'] : '';
?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script>
    $(document).ready(function() {

        // Si existe ID cargamos solicitud para editar
        <?php if ($_id != '') { ?>
            cargar_solicitud_medica(<?= $_id ?>);
        <?php } ?>
        <?php if ($_id_sol != '') { ?>
            abrirResumenSolicitud(<?= $_id_sol ?>);
        <?php } ?>
        carga_fecha_hora_cita_medica();
        cargar_selects2();

        function cargar_selects2() {
            url_IDG_C = '../controlador/TALENTO_HUMANO/CATALOGOS/sa_cat_cie_10C.php?buscar=true';
            cargar_select2_url('ddl_IDG', url_IDG_C, '-- Seleccione --', null, 3);
        }


        // TIPO DE SOLICITUD: Reposo o Permiso
        $('#cbx_reposo').change(function() {
            if ($(this).is(':checked')) {
                $('#cbx_permiso').prop('checked', false);
                $('#pnl_tipo_enfermedad').slideDown();
            }
        });

        $('#cbx_permiso').change(function() {
            if ($(this).is(':checked')) {
                $('#cbx_reposo').prop('checked', false);
                $('#pnl_tipo_enfermedad').slideDown();
            }
        });

        // TIPO DE ENFERMEDAD
        $('input[name="tipo_enfermedad"]').change(function() {
            // Solo mostrar el panel cuando hay algo seleccionado
        });

        // CERTIFICADOS
        $('input[name="cert_medico"]').change(function() {
            // Lógica adicional si es necesaria
        });

        $('input[name="cert_asistencia"]').change(function() {
            // Lógica adicional si es necesaria
        });

        // TIPO DE CÁLCULO: Fecha o Horas
        $('input[name="tipo_calculo"]').change(function() {
            if ($('#rbtn_fecha').is(':checked')) {
                $('#pnl_calculo_fecha').slideDown();
                $('#pnl_calculo_horas').slideUp();
            } else if ($('#rbtn_horas').is(':checked')) {
                $('#pnl_calculo_fecha').slideUp();
                $('#pnl_calculo_horas').slideDown();
            }
        });

        // CALCULAR DÍAS (para modo fecha)
        $('#txt_fecha_desde, #txt_fecha_hasta').change(function() {
            calcularDiasFecha();
        });

        // CALCULAR HORAS (para modo horas)
        $('#txt_fecha_horas, #txt_hora_desde, #txt_hora_hasta').change(function() {
            calcularTotalHoras();
        });

        // Inicializar con el modo fecha por defecto
        $('#rbtn_fecha').prop('checked', true).trigger('change');
    });

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
                $('#txt_total_horas').val(0);
                return;
            }

            let desde = new Date('2000-01-01 ' + horaDesde);
            let hasta = new Date('2000-01-01 ' + horaHasta);

            let diff = (hasta - desde) / (1000 * 60 * 60); // diferencia en horas
            $('#txt_total_horas').val(diff.toFixed(2));
        }
    }

    function toDateInput(val) {
        if (!val || val == null || val == 'null' || val.startsWith('1900')) return '';
        let dateStr = val.split(' ')[0];
        if (dateStr && dateStr.includes('-')) {
            return dateStr;
        }
        return '';
    }

    function toTimeInput(val) {
        if (!val || val == null || val == 'null');

        let strVal = val.toString().trim();

        if (strVal.includes(' ')) {
            let parts = strVal.split(' ');
            return parts[1].substring(0, 5);
        }

        if (strVal.includes(':')) {
            return strVal.substring(0, 5);
        }

        return '';
    }

    function cargar_solicitud_medica(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_solicitud_permiso_medicoC.php?listar_solicitud_medico=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                var r = (Array.isArray(response) && response.length > 0) ? response[0] : response;
                if (!r) return;

                $('#ddl_IDG').empty().append($('<option>', {
                    value: r.id_idg,
                    text: r.codigo_idg + ' - ' + r.descripcion_idg,
                    selected: true
                })).trigger('change');


                // Datos básicos
                $("#txt_id").val(r._id || '');
                $("#txt_sol_per_id").val(r.id_solicitud || '');

                // Tipo de solicitud
                let esReposo = (r.reposo == '1' || r.reposo == 1);
                let esPermiso = (r.permiso_consulta == '1' || r.permiso_consulta == 1);

                $('#cbx_reposo').prop('checked', esReposo);
                $('#cbx_permiso').prop('checked', esPermiso);

                if (esReposo || esPermiso) {
                    $('#pnl_tipo_enfermedad').slideDown();
                }

                // Tipo de enfermedad
                if (r.tipo_enfermedad) {
                    $('input[name="tipo_enfermedad"][value="' + r.tipo_enfermedad + '"]').prop('checked', true);
                }

                // IDG
                $("#txt_codigo_idg").val(r.codigo_idg || '');

                // Certificados
                let certMedico = (r.presenta_cert_medico == '1' || r.presenta_cert_medico == 1);
                let certAsistencia = (r.presenta_cert_asistencia == '1' || r.presenta_cert_asistencia == 1);

                if (certMedico) {
                    $('input[name="cert_medico"][value="si"]').prop('checked', true);
                } else {
                    $('input[name="cert_medico"][value="no"]').prop('checked', true);
                }

                if (certAsistencia) {
                    $('input[name="cert_asistencia"][value="si"]').prop('checked', true);
                } else {
                    $('input[name="cert_asistencia"][value="no"]').prop('checked', true);
                }

                // Motivo
                $("#txt_motivo").val(r.motivo || '');

                // ===== FECHAS DEL DEPARTAMENTO MÉDICO =====


                $("#txt_fecha_hasta_medico").val(toTimeInput(r.hasta));


                // ===== FECHAS DEL PERMISO (calculadas) =====
                let tipoCalculo = r.tipo_calculo || 'fecha';

                if (tipoCalculo === 'fecha') {
                    $('#rbtn_fecha').prop('checked', true).trigger('change');
                    $("#txt_fecha_desde").val(toDateInput(r.fecha_desde_permiso));
                    $("#txt_fecha_hasta").val(toDateInput(r.fecha_hasta_permiso));
                    $("#txt_total_dias").val(r.total_dias_permiso || 0);
                } else {
                    $('#rbtn_horas').prop('checked', true).trigger('change');
                    $("#txt_fecha_horas").val(toDateInput(r.fecha_principal_permiso));
                    $("#txt_hora_desde").val(toTimeInput(r.fecha_desde_permiso));
                    $("#txt_hora_hasta").val(toTimeInput(r.fecha_hasta_permiso));
                    $("#txt_total_horas").val(r.total_horas_permiso || 0);
                }

                // Observaciones y estado
                let estadoSolicitud = r.estado_solicitud || '0';
                $("#ddl_estado_solicitud").val(estadoSolicitud.toString());
            },
            error: function(xhr) {
                console.error('Error cargar_solicitud_medica:', xhr.responseText);
                Swal.fire('Error', 'No se pudo cargar la solicitud médica.', 'error');
            }
        });
    }

    function carga_fecha_hora_cita_medica() {
        let ahora = new Date();
        let anio = ahora.getFullYear();
        let mes = String(ahora.getMonth() + 1).padStart(2, '0'); // Los meses van de 0 a 11
        let dia = String(ahora.getDate()).padStart(2, '0');
        let fechaHoy = `${anio}-${mes}-${dia}`;
        let horaActual = String(ahora.getHours()).padStart(2, '0') + ":" +
            String(ahora.getMinutes()).padStart(2, '0');

        $("#txt_fecha_principal").val(fechaHoy);
        $("#txt_fecha_desde_medico").val(horaActual);
    }

    function validar_formulario() {

        if ($('#pnl_departamento_medico').is(':visible')) {
            if ($("#form_permiso_medico").valid()) {

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

    function insertar_actualizar() {
        if (!validar_formulario()) return;



        let esReposo = $('#cbx_reposo').is(':checked') ? 1 : 0;
        let esPermiso = $('#cbx_permiso').is(':checked') ? 1 : 0;
        let tipoEnfermedad = $('input[name="tipo_enfermedad"]:checked').val();
        let certMedico = $('input[name="cert_medico"]:checked').val() === 'si' ? 1 : 0;
        let certAsistencia = $('input[name="cert_asistencia"]:checked').val() === 'si' ? 1 : 0;

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
            totalHoras = parseFloat($('#txt_total_horas').val()) || 0;
        }

        let ahora = new Date();
        let horaActual = String(ahora.getHours()).padStart(2, '0') + ":" +
            String(ahora.getMinutes()).padStart(2, '0');

        let idMedico = $("#txt_id").val();

        let parametros = {
            '_id': idMedico || '',
            'id_solicitud': '<?= $_id_sol ?>',
            'cedula_persona': $("#txt_cedula_persona").val() || '',
            'reposo': esReposo,
            'permiso_consulta': esPermiso,
            'tipo_enfermedad': tipoEnfermedad,
            'codigo_idg': $("#txt_codigo_idg").val(),
            'presenta_cert_medico': certMedico,
            'presenta_cert_asistencia': certAsistencia,
            'motivo': $("#txt_motivo").val(),

            // FECHAS DEL DEPARTAMENTO MÉDICO
            'fecha_medico': $("#txt_fecha_principal").val(),
            'desde_medico': $("#txt_fecha_desde_medico").val(),
            'hasta_medico': horaActual,

            // FECHAS DEL PERMISO (calculadas)
            'tipo_calculo': tipoCalculo,
            'fecha_principal_permiso': fechaPrincipalPermiso,
            'desde_permiso': desdePermiso,
            'hasta_permiso': hastaPermiso,
            'total_dias': totalDias,
            'total_horas': totalHoras,
            'id_idg': $("#ddl_IDG").val() ?? '',

            'estado_solicitud': $("#ddl_estado_solicitud").val() || '0'
        };

        console.log('Parámetros a enviar:', parametros);

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_solicitud_permiso_medicoC.php?insertar_editar=true',
            type: 'post',
            data: {
                parametros: parametros
            },
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire('Éxito', 'Solicitud médica guardada correctamente.', 'success')
                        .then(() => location.href = "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitudes_personas&_id=<?= $_per_id ?>");
                } else {
                    Swal.fire('Error', res.msg || 'No se pudo guardar la solicitud', 'error');
                }
            },
            error: function(xhr) {
                console.error('Error al guardar:', xhr.responseText);
                Swal.fire('Error', 'Error del servidor', 'error');
            }
        });
    }

    function eliminar() {
        var id = $("#txt_id").val() || '';
        if (!id) {
            Swal.fire('', 'ID no encontrado para eliminar', 'warning');
            return;
        }

        Swal.fire({
            title: '¿Eliminar Solicitud Médica?',
            text: "¿Está seguro de eliminar esta solicitud de permiso médico?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controlador/TALENTO_HUMANO/th_solicitud_permiso_medicoC.php?eliminar=true',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res == 1) {
                            Swal.fire('Eliminado', 'Solicitud médica eliminada correctamente.', 'success')
                                .then(() => location.href = "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_aprobacion_solicitudes");
                        } else {
                            Swal.fire('Error', res.msg || 'No se pudo eliminar.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error eliminar:', xhr.responseText);
                        Swal.fire('Error', 'Ocurrió un error al eliminar.', 'error');
                    }
                });
            }
        });
    }
</script>
<script>
    function abrir_modal_cargar_resumen() {
        $('#modalResumenSolicitud').modal('show');
        abrirResumenSolicitud(<?= $_id_sol ?>);
    }


    function abrirResumenSolicitud(id_sol) {

        const idSolicitud = id_sol;

        if (!idSolicitud || idSolicitud === '') {
            Swal.fire('Advertencia', 'No se encontró la solicitud principal', 'warning');
            return;
        }

        // Mostrar loader
        $('#loaderResumen').show();
        $('#datosResumen').hide();

        // Cargar datos de la solicitud
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_solicitud_permisoC.php?listar=true',
            type: 'post',
            data: {
                id: idSolicitud
            },
            dataType: 'json',
            success: function(response) {
                var r = (Array.isArray(response) && response.length > 0) ? response[0] : response;

                if (r.motivo == "PERSONAL" || r.motivo == "CALAMIDAD" || r.motivo == "FALLECIMIENTO" || r.motivo == "FAMILIAR") {
                    $("#pnl_departamento_medico").hide();
                } else {
                    $('#pnl_departamento_medico').slideDown();
                }

                if (!r) {
                    Swal.fire('Error', 'No se pudo cargar la información', 'error');
                    $('#modalResumenSolicitud').modal('hide');
                    return;
                }
                llenarResumen(r);

                // Ocultar loader y mostrar contenido
                $('#loaderResumen').hide();
                $('#datosResumen').fadeIn();
            },
            error: function(xhr) {
                console.error('Error al cargar resumen:', xhr.responseText);
                Swal.fire('Error', 'Error al cargar la información', 'error');
                $('#modalResumenSolicitud').modal('hide');
            }
        });
    }

    function llenarResumen(data) {
        // Información del empleado
        $('#res_nombre_completo').text(data.nombre_completo || 'N/A');
        $('#res_cedula').text(data.cedula || 'N/A');
        $('#txt_cedula_persona').val(data.cedula);
        $('#res_estado_civil').text(data.estado_civil || 'N/A');
        $('#res_genero').text(data.sexo || 'N/A');

        // Tipo de solicitud
        $('#res_tipo_asunto').text(data.tipo_solicitud || 'N/A');
        $('#res_motivo').text(data.motivo || 'N/A');
        $('#res_estado').text(data.estado == 1 ? 'ACTIVO' : 'INACTIVO');

        if (data.detalle) {
            $('#pnl_detalle_motivo').show();
            $('#res_detalle_motivo').text(data.detalle);
        }

        let tipoCalculo = data.tipo_calculo || 'fecha';

        if (tipoCalculo === 'fecha') {
            $('#rbtn_fecha').prop('checked', true).trigger('change');
            $("#txt_fecha_desde").val(toDateInput(data.fecha_desde_permiso));
            $("#txt_fecha_hasta").val(toDateInput(data.fecha_hasta_permiso));
            $("#txt_total_dias").val(data.total_dias_permiso || 0);
        } else {
            $('#rbtn_horas').prop('checked', true).trigger('change');
            $("#txt_fecha_horas").val(toDateInput(data.fecha_principal_permiso));
            $("#txt_hora_desde").val(toTimeInput(data.fecha_desde_permiso));
            $("#txt_hora_hasta").val(toTimeInput(data.fecha_hasta_permiso));
            $("#txt_total_horas").val(data.total_horas_permiso || 0);
        }

        // Información familiar
        if (data.fam_hijos_adultos || data.fecha_nacimiento) {
            $('#pnl_info_familiar').show();
            $('#res_parentesco').text(data.fam_hijos_adultos || 'N/A');

            if (data.fam_hijos_adultos === 'HIJO' && data.rango_edad) {
                $('#col_rango_edad').show();
                $('#res_rango_edad').text(data.rango_edad + ' años');
            }

            if (data.fam_hijos_adultos === 'OTRO' && data.tipo_cuidado) {
                $('#col_tipo_cuidado').show();
                $('#res_tipo_cuidado').text(data.tipo_cuidado);
            }

            if (data.fecha_nacimiento && data.fecha_nacimiento !== '1900-01-01 00:00:00') {
                const fechaNac = formatearFecha(data.fecha_nacimiento);
                $('#res_fecha_nacimiento').text(fechaNac);
                const edad = calcularEdadDesdeString(data.fecha_nacimiento);
                $('#res_edad').text(edad);
            }
        }

        // Certificados
        let certificados = [];
        if (data.maternidad_paternidad == 1) {
            certificados.push({
                icono: 'bx-baby-carriage',
                nombre: 'Certificado de Maternidad/Paternidad',
                color: 'primary'
            });
        }
        if (data.enfermedad == 1) {
            certificados.push({
                icono: 'bx-first-aid',
                nombre: 'Certificado de Enfermedad',
                color: 'danger'
            });
        }
        if (data.cita_medica == 1) {
            certificados.push({
                icono: 'bx-calendar-heart',
                nombre: 'Certificado de Cita Médica',
                color: 'info'
            });
        }

        if (certificados.length > 0) {
            $('#pnl_certificados').show();
            let htmlCerts = '';
            certificados.forEach(cert => {
                htmlCerts += `
                <div class="col-md-4 mb-2">
                    <div class="alert alert-${cert.color} mb-0">
                        <i class="bx ${cert.icono} me-2"></i>
                        ${cert.nombre}
                    </div>
                </div>
            `;
            });
            $('#lista_certificados').html(htmlCerts);
        }

        // Información médica
        if (data.tipo_atencion || data.lugar) {
            $('#pnl_info_medica').show();
            $('#res_tipo_atencion').text(data.tipo_atencion || 'N/A');
            $('#res_lugar').text(data.lugar || 'N/A');
            $('#res_especialidad').text(data.especialidad || 'N/A');
            $('#res_medico').text(data.medico || 'N/A');

            if (data.hora_desde && data.hora_hasta) {
                const horario = formatearHora(data.hora_desde) + ' - ' + formatearHora(data.hora_hasta);
                $('#res_horario_atencion').text(horario);
            }
        }

        // Fechas y duración
        $('#res_fecha_desde').text(formatearFecha(data.fecha_desde) || 'N/A');
        $('#res_fecha_hasta').text(formatearFecha(data.fecha_hasta) || 'N/A');

        let duracion = '';
        if (data.total_dias > 0) {
            duracion = data.total_dias + ' día(s)';
        } else if (data.total_horas > 0) {
            duracion = data.total_horas + ' hora(s)';
        }
        $('#res_duracion').text(duracion || 'N/A');

        // Limpiar la lista y ocultar el panel por defecto al iniciar la carga
        $('#lista_documentos').empty();
        $('#pnl_documentos').hide();

        let documentos = [];

        // Validación de rutas (evita strings 'null' o vacíos)
        const agregarDoc = (nombre, ruta) => {
            if (ruta && ruta !== 'null' && ruta !== '') {
                documentos.push({
                    nombre,
                    ruta
                });
            }
        };

        agregarDoc('Acta de Defunción', data.ruta_act_defuncion);
        agregarDoc('Certificado', data.ruta_certificado);

        if (documentos.length > 0) {
            $('#pnl_documentos').show();
            let htmlDocs = '';

            documentos.forEach(doc => {
                htmlDocs += `
        <div class="list-group-item list-group-item-action">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bx bx-file-blank fs-4 text-primary me-3"></i>
                    <div>
                        <span class="fw-bold d-block">${doc.nombre}</span>
                        <small class="text-muted">Archivo adjunto</small>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="${doc.ruta}" target="_blank" class="btn btn-sm btn-outline-primary" title="Ver archivo">
                        <i class="bx bx-show"></i> Ver
                    </a>
                    <a href="${doc.ruta}" download="${doc.nombre}" class="btn btn-sm btn-outline-success" title="Descargar">
                        <i class="bx bx-download"></i>
                    </a>
                </div>
            </div>
        </div>`;
            });

            $('#lista_documentos').html(htmlDocs);
        }
    }




    function formatearFecha(fecha) {
        if (!fecha || fecha === '1900-01-01 00:00:00') return '';
        const date = new Date(fecha);
        const opciones = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return date.toLocaleDateString('es-ES', opciones);
    }

    function formatearHora(fecha) {
        if (!fecha) return '';
        const parts = fecha.split(' ');
        if (parts.length > 1) {
            return parts[1].substring(0, 5);
        }
        return '';
    }

    function calcularEdadDesdeString(fecha) {
        if (!fecha || fecha === '1900-01-01 00:00:00') return 0;
        const f = new Date(fecha);
        const h = new Date();
        let edad = h.getFullYear() - f.getFullYear();
        const m = h.getMonth() - f.getMonth();
        if (m < 0 || (m === 0 && h.getDate() < f.getDate())) {
            edad--;
        }
        return edad;
    }

    function mostrarVistaPrevia(rutaArchivo, nombreDoc) {
        if (!rutaArchivo || rutaArchivo === 'null') return;

        // Mostrar el panel principal si estaba oculto
        $('#pnl_documentos').show();

        // Crear el item de la lista
        const nuevoItem = `
        <div class="list-group-item list-group-item-action">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-3">
                        <span class="avatar-title rounded bg-soft-primary text-primary">
                            <i class="bx bx-file fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="mb-0 text-truncate" style="max-width: 250px;">${nombreDoc}</h6>
                        <small class="text-muted text-uppercase" style="font-size: 10px;">Documento Guardado</small>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="${rutaArchivo}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-show-alt"></i> Ver
                    </a>
                    <a href="${rutaArchivo}" download="${nombreDoc}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-download"></i>
                    </a>
                </div>
            </div>
        </div>
    `;

        // Añadir a la lista
        $('#lista_documentos').append(nuevoItem);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="card border-primary border-3">
            <div class="card-body p-4">

                <div class="card-title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div><i class="bx bx-plus-medical me-2 font-22 text-primary"></i></div>
                        <h5 class="mb-0 text-primary">
                            <?= ($_id == '') ? 'Registrar Permiso Médico' : 'Modificar Permiso Médico' ?>
                        </h5>
                    </div>

                    <div>
                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitudes_personas&_id=<?= $_per_id ?>"
                            class="btn btn-outline-dark btn-sm">
                            <i class="bx bx-arrow-back"></i> Regresar
                        </a>
                        <?php if ($_id_sol != '') { ?>
                            <!-- BOTÓN PARA VER RESUMEN -->
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="abrir_modal_cargar_resumen()">
                                <i class="bx bx-receipt"></i> Ver Resumen de Solicitud Principal
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <hr>
                <form id="form_permiso_medico">

                    <input type="hidden" id="txt_id" name="txt_id">
                    <input type="hidden" id="txt_sol_per_id" name="txt_sol_per_id">
                    <input type="hidden" id="txt_cedula_persona" name="txt_cedula_persona">

                    <!-- ESPACIO EXCLUSIVO DEPARTAMENTO MÉDICO -->
                    <div id="pnl_departamento_medico" class="card bg-light mb-3" style="display: none;">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">ESPACIO EXCLUSIVO DEPARTAMENTO MÉDICO:</h6>
                        </div>
                        <div class="card-body">

                            <!-- Certifico que el/la Trabajador/a requiere de: -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="fw-bold">Certifico que el/la Trabajador/a requiere de:</label>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="checkbox" id="cbx_reposo" name="cbx_reposo"> <label for="cbx_reposo">REPOSO</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="checkbox" id="cbx_permiso" name="cbx_reposo"> <label for="cbx_permiso">PERMISO</label>
                                </div>
                            </div>

                            <!-- Tipo de enfermedad/consulta -->
                            <div id="pnl_tipo_enfermedad" style="display:none">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label>por:</label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input type="radio" name="tipo_enfermedad" value="enfermedad_general" id="rbtn_enfermedad">
                                        <label for="rbtn_enfermedad">Enfermedad General</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="tipo_enfermedad" value="asistencia_consulta" id="rbtn_asistencia">
                                        <label for="rbtn_asistencia">Asistencia a Consulta</label>
                                    </div>
                                </div>
                            </div>

                            <!-- IDG -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="ddl_IDG" class="form-label fw-bold">IDG</label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_IDG" name="ddl_IDG">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <!-- OBSERVACIONES -->
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label class="fw-bold">Observaciones:</label>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>PRESENTA CERTIFICADO MÉDICO</label>
                                    <div>
                                        <input type="radio" name="cert_medico" value="si" id="cert_med_si">
                                        <label for="cert_med_si">SI</label>
                                        &nbsp;&nbsp;
                                        <input type="radio" name="cert_medico" value="no" id="cert_med_no">
                                        <label for="cert_med_no">NO</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>PRESENTA CERTIFICADO DE ASISTENCIA</label>
                                    <div>
                                        <input type="radio" name="cert_asistencia" value="si" id="cert_asist_si">
                                        <label for="cert_asist_si">SI</label>
                                        &nbsp;&nbsp;
                                        <input type="radio" name="cert_asistencia" value="no" id="cert_asist_no">
                                        <label for="cert_asist_no">NO</label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- MOTIVO -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="txt_motivo">(Motivo)</label>
                                    <input type="text" class="form-control form-control-sm" name="txt_motivo" id="txt_motivo" placeholder="Describa el motivo">
                                </div>
                            </div>

                            <!-- FECHA PRINCIPAL -->
                            <div id="calculos_atencion_medico" class="row mb-3" style="display: none;">
                                <div class="col-md-4">
                                    <label for="txt_fecha_principal">Fecha:</label>
                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_principal" disabled>
                                </div>

                                <div class="col-md-4">
                                    <label for="txt_fecha_desde_medico">Fecha Desde:</label>
                                    <input type="time" class="form-control form-control-sm" id="txt_fecha_desde_medico" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="txt_fecha_hasta_medico">Fecha Hasta:</label>
                                    <input type="time" class="form-control form-control-sm" id="txt_fecha_hasta_medico" disabled>
                                </div>
                            </div>



                        </div>
                    </div>

                    <!-- FECHA Y HORA DEL PERMISO -->
                    <div class="card bg-light mb-3">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">FECHA Y HORA DEL PERMISO:</h6>
                        </div>
                        <div class="card-body">

                            <!-- Radio buttons para elegir tipo de cálculo -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <input type="radio" name="tipo_calculo" id="rbtn_fecha" value="fecha">
                                    <label for="rbtn_fecha">Por Fecha</label>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="tipo_calculo" id="rbtn_horas" value="horas">
                                    <label for="rbtn_horas">Por Horas</label>
                                </div>
                            </div>

                            <!-- PANEL CÁLCULO POR FECHA -->
                            <div id="pnl_calculo_fecha" style="display:none">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="txt_fecha_desde">DESDE: (fecha)</label>
                                        <input type="date" class="form-control form-control-sm" id="txt_fecha_desde" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="txt_fecha_hasta">HASTA: (fecha)</label>
                                        <input type="date" class="form-control form-control-sm" id="txt_fecha_hasta" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="txt_total_dias">TOTAL DÍAS</label>
                                        <input type="number" class="form-control form-control-sm" id="txt_total_dias" readonly disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- PANEL CÁLCULO POR HORAS -->
                            <div id="pnl_calculo_horas" style="display:none">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="txt_fecha_horas">DESDE: (fecha)</label>
                                        <input type="date" class="form-control form-control-sm" id="txt_fecha_horas" disabled>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="txt_hora_desde">DESDE: (Hora)</label>
                                        <input type="time" class="form-control form-control-sm" id="txt_hora_desde" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="txt_hora_hasta">HASTA: (Hora)</label>
                                        <input type="time" class="form-control form-control-sm" id="txt_hora_hasta" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="txt_total_horas">TOTAL HORAS</label>
                                        <input type="number" class="form-control form-control-sm" id="txt_total_horas" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ESTADO DE LA SOLICITUD -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Estado de la Solicitud</label>
                            <select class="form-control form-control-sm" id="ddl_estado_solicitud">
                                <option value="0">Pendiente</option>
                                <option value="1">Aprobada</option>
                                <option value="2">Rechazada</option>
                            </select>
                        </div>
                    </div>

                    <!-- BOTONES -->
                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-success btn-sm" onclick="insertar_actualizar()">
                                <i class="bx bx-save"></i> Guardar
                            </button>
                            <?php if ($_id != '') { ?>
                                <button type="button" class="btn btn-danger btn-sm" onclick="eliminar()">
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


<div class="modal fade" id="modalResumenSolicitud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="bx bx-file-find me-2"></i>
                    Resumen de Solicitud de Permiso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="contenidoResumen">
                <!-- Loader mientras carga -->
                <div class="text-center py-5" id="loaderResumen">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando información...</p>
                </div>

                <!-- Contenido del resumen -->
                <div id="datosResumen" style="display:none;">

                    <!-- Información del Empleado -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bx bx-user text-primary me-2"></i>
                                Información del Empleado
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Nombre:</strong>
                                        <span id="res_nombre_completo" class="text-primary"></span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Cédula:</strong>
                                        <span id="res_cedula"></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Estado Civil:</strong>
                                        <span id="res_estado_civil"></span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Género:</strong>
                                        <span id="res_genero"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de Solicitud -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bx bx-list-check text-success me-2"></i>
                                Tipo de Solicitud
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="badge-container p-3 rounded" style="background: #e7f3ff;">
                                        <span class="badge bg-primary mb-2">Asunto</span>
                                        <p class="mb-0 fw-bold" id="res_tipo_asunto"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="badge-container p-3 rounded" style="background: #fff3e0;">
                                        <span class="badge bg-warning mb-2">Motivo</span>
                                        <p class="mb-0 fw-bold" id="res_motivo"></p>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-3" id="pnl_detalle_motivo" style="display:none;">
                                <div class="col-md-12">
                                    <p class="mb-0">
                                        <strong>Detalle:</strong>
                                        <span id="res_detalle_motivo" class="text-muted"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Familiar (si aplica) -->
                    <div class="card mb-3 shadow-sm" id="pnl_info_familiar" style="display:none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bx bx-group text-info me-2"></i>
                                Información Familiar
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-2">
                                        <strong>Parentesco:</strong>
                                        <span id="res_parentesco"></span>
                                    </p>
                                </div>
                                <div class="col-md-4" id="col_rango_edad" style="display:none;">
                                    <p class="mb-2">
                                        <strong>Rango de Edad:</strong>
                                        <span id="res_rango_edad"></span>
                                    </p>
                                </div>
                                <div class="col-md-4" id="col_tipo_cuidado" style="display:none;">
                                    <p class="mb-2">
                                        <strong>Tipo de Cuidado:</strong>
                                        <span id="res_tipo_cuidado"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Fecha de Nacimiento:</strong>
                                        <span id="res_fecha_nacimiento"></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Edad:</strong>
                                        <span id="res_edad" class="badge bg-info"></span> años
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificados Presentados -->
                    <div class="card mb-3 shadow-sm" id="pnl_certificados" style="display:none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bx bx-folder-open text-warning me-2"></i>
                                Certificados Presentados
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row" id="lista_certificados">
                                <!-- Se llenarán dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Información Médica (si aplica) -->
                    <div class="card mb-3 shadow-sm" id="pnl_info_medica" style="display:none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bx bx-plus-medical text-danger me-2"></i>
                                Información de Atención Médica
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <p class="mb-2">
                                        <strong>Tipo de Atención:</strong>
                                        <span id="res_tipo_atencion" class="badge bg-primary"></span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-2">
                                        <strong>Lugar:</strong>
                                        <span id="res_lugar"></span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-2">
                                        <strong>Especialidad:</strong>
                                        <span id="res_especialidad"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <p class="mb-2">
                                        <strong>Médico:</strong>
                                        <span id="res_medico"></span>
                                    </p>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-2">
                                        <strong>Horario de Atención:</strong>
                                        <span id="res_horario_atencion"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Documentos Adjuntos -->
                    <div class="card mb-3 shadow-sm" id="pnl_documentos" style="display:none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bx bx-file text-secondary me-2"></i>
                                Documentos Adjuntos
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group" id="lista_documentos">
                                <!-- Se llenarán dinámicamente -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ... tu código existente ...

        // Agregar método personalizado para validar que al menos uno esté marcado
        $.validator.addMethod("requiereReposoOPermiso", function(value, element) {
            return $('#cbx_reposo').is(':checked') || $('#cbx_permiso').is(':checked');
        }, "Debe seleccionar REPOSO o PERMISO");

        // Configurar validación del formulario
        $("#form_permiso_medico").validate({
            ignore: [], // No ignorar campos ocultos
            rules: {
                // Validación para checkboxes de REPOSO/PERMISO
                cbx_reposo: {
                    requiereReposoOPermiso: function() {
                        return $('#pnl_departamento_medico').is(':visible');
                    }
                },
                cbx_permiso: {
                    requiereReposoOPermiso: function() {
                        return $('#pnl_departamento_medico').is(':visible');
                    }
                },

                // Campos del panel médico (solo cuando está visible)
                ddl_IDG: {
                    required: function() {
                        return $('#pnl_departamento_medico').is(':visible');
                    }
                },
                txt_motivo: {
                    required: function() {
                        return $('#pnl_departamento_medico').is(':visible');
                    },
                    minlength: 10
                },
                tipo_enfermedad: {
                    required: function() {
                        return $('#pnl_tipo_enfermedad').is(':visible') && $('#pnl_departamento_medico').is(':visible');
                    }
                },
                cert_medico: {
                    required: function() {
                        return $('#pnl_departamento_medico').is(':visible');
                    }
                },
                cert_asistencia: {
                    required: function() {
                        return $('#pnl_departamento_medico').is(':visible');
                    }
                },

                // Campos de cálculo por fecha
                txt_fecha_desde: {
                    required: function() {
                        return $('#rbtn_fecha').is(':checked');
                    }
                },
                txt_fecha_hasta: {
                    required: function() {
                        return $('#rbtn_fecha').is(':checked');
                    }
                },

                // Campos de cálculo por horas
                txt_fecha_horas: {
                    required: function() {
                        return $('#rbtn_horas').is(':checked');
                    }
                },
                txt_hora_desde: {
                    required: function() {
                        return $('#rbtn_horas').is(':checked');
                    }
                },
                txt_hora_hasta: {
                    required: function() {
                        return $('#rbtn_horas').is(':checked');
                    }
                }
            },
            messages: {
                cbx_reposo: "Debe seleccionar REPOSO o PERMISO",
                cbx_permiso: "Debe seleccionar REPOSO o PERMISO",
                ddl_IDG: "Seleccione un código IDG",
                txt_motivo: {
                    required: "Describa el motivo del permiso médico",
                    minlength: "El motivo debe tener al menos 10 caracteres"
                },
                tipo_enfermedad: "Seleccione el tipo de enfermedad",
                cert_medico: "Indique si presenta certificado médico",
                cert_asistencia: "Indique si presenta certificado de asistencia",
                txt_fecha_desde: "Ingrese la fecha inicial",
                txt_fecha_hasta: "Ingrese la fecha final",
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

        // Validación para select2
        $(".select2-validation").on("select2:select", function() {
            $(this).valid(); // Trigger validation
        });

        // Cuando cambian los checkboxes REPOSO/PERMISO
        $('#cbx_reposo, #cbx_permiso').change(function() {
            // Validar ambos checkboxes
            $('#cbx_reposo').valid();
            $('#cbx_permiso').valid();

            // Si está visible el panel, validar tipo de enfermedad
            if ($('#pnl_departamento_medico').is(':visible') && $('#pnl_tipo_enfermedad').is(':visible')) {
                $('#form_permiso_medico').validate().element('input[name="tipo_enfermedad"]');
            }
        });

        $('input[name="tipo_enfermedad"]').change(function() {
            $(this).valid();
        });

        $('input[name="cert_medico"], input[name="cert_asistencia"]').change(function() {
            $(this).valid();
        });

        // Cuando cambia el tipo de cálculo, validar campos correspondientes
        $('input[name="tipo_calculo"]').change(function() {
            // Limpiar validaciones previas
            $('#txt_fecha_desde, #txt_fecha_hasta').removeClass('is-valid is-invalid');
            $('#txt_fecha_horas, #txt_hora_desde, #txt_hora_hasta').removeClass('is-valid is-invalid');

            // Remover mensajes de error
            $('#txt_fecha_desde, #txt_fecha_hasta').next('.invalid-feedback').remove();
            $('#txt_fecha_horas, #txt_hora_desde, #txt_hora_hasta').next('.invalid-feedback').remove();
        });

        // Marcar campos obligatorios al cargar
        marcarCamposObligatorios();
    });

    function validar_formulario() {
        // Primero validar el formulario completo
        let formularioValido = $("#form_permiso_medico").valid();

        if (!formularioValido) {
            Swal.fire('Error', 'Por favor complete todos los campos requeridos', 'error');

            // Hacer scroll al primer campo con error
            let primerError = $('.is-invalid:first');
            if (primerError.length) {
                $('html, body').animate({
                    scrollTop: primerError.offset().top - 100
                }, 500);
            }

            return false;
        }

        // Validaciones adicionales específicas
        if ($('#rbtn_fecha').is(':checked')) {
            if (!$('#txt_fecha_desde').val() || !$('#txt_fecha_hasta').val()) {
                Swal.fire('Error', 'Complete todas las fechas del rango', 'error');
                return false;
            }

            // Validar que fecha hasta sea mayor que fecha desde
            let fechaDesde = new Date($('#txt_fecha_desde').val());
            let fechaHasta = new Date($('#txt_fecha_hasta').val());

            if (fechaHasta < fechaDesde) {
                Swal.fire('Error', 'La fecha hasta debe ser mayor que la fecha desde', 'error');
                $('#txt_fecha_hasta').addClass('is-invalid');
                return false;
            }
        } else if ($('#rbtn_horas').is(':checked')) {
            if (!$('#txt_fecha_horas').val() || !$('#txt_hora_desde').val() || !$('#txt_hora_hasta').val()) {
                Swal.fire('Error', 'Complete la fecha y el horario (Desde/Hasta)', 'error');
                return false;
            }

            // Validar que hora hasta sea mayor que hora desde
            if ($('#txt_hora_desde').val() >= $('#txt_hora_hasta').val()) {
                Swal.fire('Error', 'La hora hasta debe ser mayor que la hora desde', 'error');
                $('#txt_hora_hasta').addClass('is-invalid');
                return false;
            }
        }

        return true;
    }

    // Agregar asteriscos a campos obligatorios
    function marcarCamposObligatorios() {
        // Campos del panel médico
        if ($('#pnl_departamento_medico').is(':visible')) {
            agregar_asterisco_campo_obligatorio('ddl_IDG');
            agregar_asterisco_campo_obligatorio('txt_motivo');
        }

        // Campos de fecha/hora según selección
        if ($('#rbtn_fecha').is(':checked')) {
            agregar_asterisco_campo_obligatorio('txt_fecha_desde');
            agregar_asterisco_campo_obligatorio('txt_fecha_hasta');
        } else if ($('#rbtn_horas').is(':checked')) {
            agregar_asterisco_campo_obligatorio('txt_fecha_horas');
            agregar_asterisco_campo_obligatorio('txt_hora_desde');
            agregar_asterisco_campo_obligatorio('txt_hora_hasta');
        }
    }

    // Función auxiliar para agregar asterisco (si no la tienes)
    function agregar_asterisco_campo_obligatorio(id_campo) {
        let label = $('label[for="' + id_campo + '"]');
        if (label.length && label.find('.text-danger').length === 0) {
            label.append(' <span class="text-danger">*</span>');
        }
    }
</script>