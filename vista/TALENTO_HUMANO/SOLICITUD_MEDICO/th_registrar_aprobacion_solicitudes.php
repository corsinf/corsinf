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

    /*
    cargar_selects2();

    function cargar_selects2() {
        url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true';
        cargar_select2_url('ddl_personas', url_personasC);
    }*/

    $('#cbx_reposo').change(function() {
        if ($(this).is(':checked')) {
            $('#pnl_reposo').slideDown();
            $('#cbx_permiso_consulta').prop('checked', false);
            $('#pnl_consulta').slideUp();
        } else {
            $('#pnl_reposo').slideUp();
            limpiar_campos_reposo();
        }
    });

    $('#cbx_permiso_consulta').change(function() {
        if ($(this).is(':checked')) {
            $('#pnl_consulta').slideDown();
            $('#cbx_reposo').prop('checked', false);
            $('#pnl_reposo').slideUp();
        } else {
            $('#pnl_consulta').slideUp();
            limpiar_campos_consulta();
        }
    });

    $('#txt_fecha_reposo_desde, #txt_fecha_reposo_hasta').change(function() {
        calcularDiasReposo();
    });

    $('#cbx_presenta_cert_medico').change(function() {
        if ($(this).is(':checked')) {
            $('#pnl_datos_certificado').slideDown();
        } else {
            $('#pnl_datos_certificado').slideUp();
        }
    });

});

function calcularDiasReposo() {
    let desde = $('#txt_fecha_reposo_desde').val();
    let hasta = $('#txt_fecha_reposo_hasta').val();

    if (desde && hasta) {
        let f1 = new Date(desde);
        let f2 = new Date(hasta);

        if (f2 >= f1) {
            let diff = Math.ceil((f2 - f1) / (1000 * 60 * 60 * 24)) + 1;
            $('#txt_dias_reposo').val(diff);
        } else {
            $('#txt_dias_reposo').val(0);
            Swal.fire('Advertencia', 'La fecha hasta debe ser mayor o igual a la fecha desde', 'warning');
        }
    }
}

function limpiar_campos_reposo() {
    $('#txt_fecha_reposo_desde').val('');
    $('#txt_fecha_reposo_hasta').val('');
    $('#txt_dias_reposo').val('');
}

function limpiar_campos_consulta() {
    $('#txt_fecha_consulta').val('');
    $('#txt_hora_consulta_desde').val('');
    $('#txt_hora_consulta_hasta').val('');
}

function toDateInput(val) {
    if (!val || val.startsWith('1900')) return '';
    return val.split(' ')[0];
}

function toTimeInput(val) {
    if (!val || val.startsWith('1900')) return '';
    let parts = val.split(' ');
    if (parts.length > 1) {
        return parts[1].substring(0, 5);
    }
    return '';
}

function combinarFechaHora(fecha, hora) {
    if (!fecha) return null;
    if (!hora) return fecha + ' 00:00:00';
    return fecha + ' ' + hora + ':00';
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

            console.log('Datos recibidos:', r); // Debug

            // Datos básicos - usar los alias correctos
            $("#txt_id").val(r._id || '');
            $("#txt_sol_per_id").val(r.id_solicitud || '');

            // Tipo de solicitud - convertir a booleano
            let esReposo = (r.reposo == '1' || r.reposo == 1 || r.reposo == true);
            let esConsulta = (r.permiso_consulta == '1' || r.permiso_consulta == 1 || r.permiso_consulta ==
                true);

            if (esReposo) {
                $('#cbx_reposo').prop('checked', true).trigger('change');
                $("#txt_fecha_reposo_desde").val(toDateInput(r.desde));
                $("#txt_fecha_reposo_hasta").val(toDateInput(r.hasta));
                calcularDiasReposo();
            }

            if (esConsulta) {
                $('#cbx_permiso_consulta').prop('checked', true).trigger('change');
                $("#txt_fecha_consulta").val(toDateInput(r.fecha));
                $("#txt_hora_consulta_desde").val(toTimeInput(r.desde));
                $("#txt_hora_consulta_hasta").val(toTimeInput(r.hasta));
            }

            // Certificados
            let presentaCertMedico = (r.presenta_cert_medico == '1' || r.presenta_cert_medico == 1);
            let presentaCertAsistencia = (r.presenta_cert_asistencia == '1' || r.presenta_cert_asistencia ==
                1);

            $('#cbx_presenta_cert_medico').prop('checked', presentaCertMedico);
            $('#cbx_presenta_cert_asistencia').prop('checked', presentaCertAsistencia);

            if (presentaCertMedico) {
                $('#pnl_datos_certificado').slideDown();
            }

            // Datos del médico y diagnóstico
            $("#txt_codigo_idg").val(r.codigo_idg || '');
            $("#txt_nombre_medico").val(r.nombre_medico || '');
            $("#txt_motivo").val(r.motivo || '');
            $("#txt_observaciones").val(r.observaciones || '');

            // Estado - convertir a string
            let estadoSolicitud = r.estado_solicitud || '0';
            $("#ddl_estado_solicitud").val(estadoSolicitud.toString());
        },
        error: function(xhr) {
            console.error('Error cargar_solicitud_medica:', xhr.responseText);
            Swal.fire('Error', 'No se pudo cargar la solicitud médica.', 'error');
        }
    });
}

// Funciones auxiliares mejoradas
function toDateInput(val) {
    if (!val || val == null || val == 'null' || val.startsWith('1900')) return '';

    // Si es una fecha válida, extraer solo la parte de fecha
    let dateStr = val.split(' ')[0];

    // Validar formato de fecha
    if (dateStr && dateStr.includes('-')) {
        return dateStr;
    }

    return '';
}

function toTimeInput(val) {
    if (!val || val == null || val == 'null' || val.startsWith('1900')) return '';

    let parts = val.split(' ');
    if (parts.length > 1) {
        // Extraer HH:MM de HH:MM:SS
        return parts[1].substring(0, 5);
    }

    return '';
}

// ================== VALIDAR FORMULARIO ==================
function validar_formulario() {

    // Validar que se seleccione un tipo
    if (!$('#cbx_reposo').is(':checked') && !$('#cbx_permiso_consulta').is(':checked')) {
        Swal.fire('Error', 'Debe seleccionar el tipo de permiso médico (Reposo o Permiso para Consulta)', 'error');
        return false;
    }

    // Validar reposo
    if ($('#cbx_reposo').is(':checked')) {
        let desde = $('#txt_fecha_reposo_desde').val();
        let hasta = $('#txt_fecha_reposo_hasta').val();

        if (!desde || !hasta) {
            Swal.fire('Error', 'Complete las fechas de reposo', 'error');
            return false;
        }

        if (new Date(desde) > new Date(hasta)) {
            Swal.fire('Error', 'La fecha desde no puede ser mayor que la fecha hasta', 'error');
            return false;
        }
    }

    // Validar permiso consulta
    if ($('#cbx_permiso_consulta').is(':checked')) {
        let fecha = $('#txt_fecha_consulta').val();
        let desde = $('#txt_hora_consulta_desde').val();
        let hasta = $('#txt_hora_consulta_hasta').val();

        if (!fecha || !desde || !hasta) {
            Swal.fire('Error', 'Complete la fecha y horas de la consulta', 'error');
            return false;
        }

        if (desde >= hasta) {
            Swal.fire('Error', 'La hora desde debe ser menor que la hora hasta', 'error');
            return false;
        }
    }

    // Validar certificado médico
    if ($('#cbx_presenta_cert_medico').is(':checked')) {
        if (!$('#txt_codigo_idg').val() || !$('#txt_nombre_medico').val()) {
            Swal.fire('Error', 'Complete los datos del certificado médico', 'error');
            return false;
        }
    }

    // Validar motivo
    if (!$('#txt_motivo').val()) {
        Swal.fire('Error', 'Ingrese el motivo de la solicitud', 'error');
        return false;
    }

    return true;
}

// ================== GUARDAR/ACTUALIZAR ==================
function insertar_actualizar() {
    if (!validar_formulario()) return;

    let esReposo = $('#cbx_reposo').is(':checked');
    let esConsulta = $('#cbx_permiso_consulta').is(':checked');

    let fechaDesde = null;
    let fechaHasta = null;
    let fechaSolicitud = null;

    if (esReposo) {
        fechaDesde = $('#txt_fecha_reposo_desde').val();
        fechaHasta = $('#txt_fecha_reposo_hasta').val();
        fechaSolicitud = fechaDesde;
    } else if (esConsulta) {
        let fecha = $('#txt_fecha_consulta').val();
        fechaDesde = combinarFechaHora(fecha, $('#txt_hora_consulta_desde').val());
        fechaHasta = combinarFechaHora(fecha, $('#txt_hora_consulta_hasta').val());
        fechaSolicitud = fecha;
    }

    // Obtener el ID correctamente
    let idMedico = $("#txt_id").val();

    let parametros = {
        '_id': idMedico || '', // Si está vacío, es inserción
        'id_solicitud': '<?= $_id_sol ?>', // ID de la solicitud de persona
        'reposo': esReposo ? 1 : 0,
        'permiso_consulta': esConsulta ? 1 : 0,
        'codigo_idg': $("#txt_codigo_idg").val() || null,
        'presenta_cert_medico': $('#cbx_presenta_cert_medico').is(':checked') ? 1 : 0,
        'presenta_cert_asistencia': $('#cbx_presenta_cert_asistencia').is(':checked') ? 1 : 0,
        'motivo': $("#txt_motivo").val(),
        'observaciones': $("#txt_observaciones").val() || null,
        'fecha': fechaSolicitud,
        'desde': fechaDesde,
        'hasta': fechaHasta,
        'nombre_medico': $("#txt_nombre_medico").val() || null,
        'estado_solicitud': $("#ddl_estado_solicitud").val() || '0'
    };

    console.log('Parámetros a enviar:', parametros); // Debug

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
                    .then(() => location.href =
                        "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitudes_personas&_id=<?= $_per_id ?>"
                    );
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

// ================== ELIMINAR ==================
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
                        Swal.fire('Eliminado', 'Solicitud médica eliminada correctamente.',
                                'success')
                            .then(() => location.href =
                                "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_aprobacion_solicitudes"
                            );
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
                    </div>
                </div>

                <hr>
                <form id="form_permiso_medico">

                    <input type="hidden" id="txt_id" name="txt_id">
                    <input type="hidden" id="txt_sol_per_id" name="txt_sol_per_id">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="fw-bold">
                                <i class="bi bi-clipboard-pulse"></i> Tipo de Permiso Médico
                            </label><br>
                            <input type="checkbox" id="cbx_reposo"> Reposo Médico
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" id="cbx_permiso_consulta"> Permiso para Consulta Médica
                        </div>
                    </div>

                    <!-- ================== PANEL REPOSO MÉDICO ================== -->
                    <div id="pnl_reposo" style="display:none">
                        <h6 class="text-primary">
                            <i class="bi bi-file-medical"></i> Datos del Reposo Médico
                        </h6>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label>Fecha Desde</label>
                                <input type="date" class="form-control form-control-sm" id="txt_fecha_reposo_desde">
                            </div>

                            <div class="col-md-4">
                                <label>Fecha Hasta</label>
                                <input type="date" class="form-control form-control-sm" id="txt_fecha_reposo_hasta">
                            </div>

                            <div class="col-md-4">
                                <label>Total Días de Reposo</label>
                                <input type="number" class="form-control form-control-sm" id="txt_dias_reposo" readonly>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!-- ================== PANEL CONSULTA MÉDICA ================== -->
                    <div id="pnl_consulta" style="display:none">
                        <h6 class="text-primary">
                            <i class="bi bi-calendar-check"></i> Datos de la Consulta Médica
                        </h6>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label>Fecha de Consulta</label>
                                <input type="date" class="form-control form-control-sm" id="txt_fecha_consulta">
                            </div>

                            <div class="col-md-4">
                                <label>Hora Desde</label>
                                <input type="time" class="form-control form-control-sm" id="txt_hora_consulta_desde">
                            </div>

                            <div class="col-md-4">
                                <label>Hora Hasta</label>
                                <input type="time" class="form-control form-control-sm" id="txt_hora_consulta_hasta">
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!-- ================== CERTIFICADOS Y DOCUMENTACIÓN ================== -->
                    <h6 class="text-primary">
                        <i class="bi bi-file-earmark-text"></i> Documentación
                    </h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="checkbox" id="cbx_presenta_cert_medico"> Presenta Certificado Médico
                        </div>
                        <div class="col-md-6">
                            <input type="checkbox" id="cbx_presenta_cert_asistencia"> Presenta Certificado de Asistencia
                        </div>
                    </div>

                    <!-- ================== DATOS DEL CERTIFICADO ================== -->
                    <div id="pnl_datos_certificado" style="display:none">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>Código IDG (CIE-10)</label>
                                <input type="text" class="form-control form-control-sm" id="txt_codigo_idg"
                                    placeholder="Ej: J06.9">
                            </div>

                            <div class="col-md-6">
                                <label>Nombre del Médico</label>
                                <input type="text" class="form-control form-control-sm" id="txt_nombre_medico">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- ================== MOTIVO Y OBSERVACIONES ================== -->
                    <h6 class="text-primary">
                        <i class="bi bi-chat-left-text"></i> Detalles de la Solicitud
                    </h6>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Motivo <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" id="txt_motivo" rows="2"
                                placeholder="Describa el motivo de la solicitud"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Observaciones</label>
                            <textarea class="form-control form-control-sm" id="txt_observaciones" rows="2"
                                placeholder="Observaciones adicionales (opcional)"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Estado de la Solicitud</label>
                            <select class="form-control form-control-sm" id="ddl_estado_solicitud">
                                <option value="0">Pendiente</option>
                                <option value="1">Aprobada</option>
                            </select>
                        </div>
                    </div>
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