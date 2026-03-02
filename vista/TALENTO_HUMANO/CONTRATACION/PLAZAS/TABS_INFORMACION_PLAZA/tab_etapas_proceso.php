<?php
$modulo_sistema = isset($_SESSION['INICIO']['MODULO_SISTEMA']) ? $_SESSION['INICIO']['MODULO_SISTEMA'] : '';
$_id_plaza      = isset($_GET['_id_plaza']) ? $_GET['_id_plaza'] : '';
?>

<script>
    var etapa_activa_id = null;
    var etapa_activa_orden = null;
    var etapa_requiere_puntaje = 0;
    var tabla_postulantes = null;
    var etapas_data = []; // ← guarda el response completo de etapas

    /* ── Carga tarjetas de etapas ── */
    function cargar_etapas_tarjetas(id_plaza) {
        var $p = $('#pnl_etapas_tarjetas');
        $p.html('<span class="text-muted fst-italic" style="font-size:13px;align-self:center;">' +
            '<span class="spinner-border spinner-border-sm me-1"></span> Cargando...</span>');

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?listar=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id_plaza: id_plaza
            },
            success: function(response) {
                var html = '';
                var colores = ['#3788d8', '#198754', '#e67e22', '#8e44ad', '#e74c3c', '#16a085', '#2c3e50', '#d35400'];

                etapas_data = response || []; // ← guardar para consultas posteriores

                if (response && response.length > 0) {
                    response.forEach(function(item, i) {
                        var esObl = (item.cn_plaet_obligatoria == '1' || item.cn_plaet_obligatoria === true);
                        var color = item.etapa_color || item.color || colores[i % colores.length];
                        var total = item.total_postulantes || 0;
                        var nombre = item.etapa_nombre;
                        var badgeObl = esObl ? '<span class="etapa-badge-obligatorio">OBLIGATORIO</span>' : '';

                        // total_pendientes viene del backend
                        var pendientes = parseInt(item.total_pendientes) || 0;
                        var evaluada = (total > 0 && pendientes === 0) ? '1' : '0';

                        html += '<div class="etapa-card"' +
                            ' data-id="' + item._id + '"' +
                            ' data-nombre="' + nombre + '"' +
                            ' data-color="' + color + '"' +
                            ' data-orden="' + item.cn_plaet_orden + '"' +
                            ' data-total="' + total + '"' +
                            ' data-pendientes="' + pendientes + '"' +
                            ' data-evaluada="' + evaluada + '"' +
                            ' data-requiere-puntaje="' + (item.etapa_requiere_puntaje == 1 ? 1 : 0) + '"' +
                            ' style="background-color:' + color + ';"' +
                            ' onclick="seleccionar_etapa(this)">' +
                            badgeObl +
                            '<div class="event-title text-center">' + nombre + '</div>' +
                            '<div class="event-body text-center">Etapa ' + item.cn_plaet_orden +
                            ' &bull; ' + total + ' postulante' + (total != 1 ? 's' : '') + '</div>' +
                            '</div>';
                    });
                } else {
                    html = '<span class="text-muted fst-italic" style="font-size:13px;align-self:center;">' +
                        '<i class="bx bx-info-circle me-1"></i>No hay etapas configuradas.</span>';
                }
                $p.html(html);

                if (etapa_activa_id) {
                    var $card = $('#pnl_etapas_tarjetas .etapa-card[data-id="' + etapa_activa_id + '"]');
                    if ($card.length) $card.addClass('activa');
                }
            },
            error: function() {
                $p.html('<span class="text-danger align-self-center">Error al cargar etapas.</span>');
            }
        });
    }

    /* ── Al hacer clic en una etapa ── */
    function seleccionar_etapa(el) {
        var id = $(el).data('id');
        var nombre = $(el).data('nombre');
        var color = $(el).data('color');
        var orden = $(el).data('orden');
        var reqPuntaje = parseInt($(el).data('requiere-puntaje')) || 0;

        $('.etapa-card').removeClass('activa');
        $(el).addClass('activa');

        etapa_activa_id = id;
        etapa_activa_orden = orden;
        etapa_requiere_puntaje = reqPuntaje;

        $('#etapa_dot_panel').css('background-color', color);
        $('#etapa_nombre_panel').text(nombre);

        if (etapa_requiere_puntaje) {
            $('#lbl_col_puntaje').html('Puntaje <span class="badge bg-danger ms-1" style="font-size:9px;">REQUERIDO</span>');
        } else {
            $('#lbl_col_puntaje').text('Puntaje');
        }

        $('#btn_verificar_etapa').text('Verificar Etapa ' + orden);
        $('#warn_etapa_anterior').remove();
        $('#btn_verificar_etapa').addClass('d-none'); // ocultar hasta que drawCallback decida

        $('#pnl_postulantes').addClass('visible');
        cargar_postulantes_etapas();
    }

    /* ── Carga la tabla de postulantes ── */
    function cargar_postulantes_etapas() {
        if (tabla_postulantes && $.fn.DataTable.isDataTable('#tbl_postulantes')) {
            tabla_postulantes.destroy();
            $('#tbl_postulantes tbody').empty();
        }

        tabla_postulantes = $('#tbl_postulantes').DataTable({
            responsive: true,
            dom: 'frtip',
            language: {
                url: '../assets/plugins/datatable/spanish.json',
                emptyTable: 'No hay postulantes en esta etapa.',
                zeroRecords: 'No se encontraron postulantes.'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_postulacionC.php?listar_por_etapa=true',
                type: 'POST',
                data: {
                    cn_plaet_id: etapa_activa_id
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    width: '35px',
                    render: function(d, t, item) {
                        return '<input type="checkbox" class="cbx-postulante form-check-input" value="' + (item._id || '') + '" />';
                    }
                },
                {
                    data: 'nombre_completo',
                    render: function(d) {
                        return '<strong>' + (d || 'Sin nombre') + '</strong>';
                    }
                },
                {
                    data: 'th_pos_cedula',
                    defaultContent: '<span class="text-muted">—</span>'
                },
                {
                    data: 'cn_pose_estado_proceso',
                    className: 'text-center',
                    render: function(d, t, item) {
                        var id = item.cn_post_id || '';
                        var valAprobado = (d === 'APROBADO') ? 'selected' : '';
                        var valReprobado = (d === 'REPROBADO') ? 'selected' : '';
                        var bloqueado = (d === 'APROBADO' || d === 'REPROBADO') ? 'disabled' : '';

                        return '<select class="form-select form-select-sm sel-resultado" data-id="' + id + '" style="min-width:130px;" ' + bloqueado + '>' +
                            '<option value="">-- Seleccionar --</option>' +
                            '<option value="APROBADO" ' + valAprobado + '>APROBADO</option>' +
                            '<option value="REPROBADO" ' + valReprobado + '>REPROBADO</option>' +
                            '</select>';
                    }
                },
                {
                    data: 'cn_pose_puntuacion',
                    className: 'text-center',
                    render: function(d, t, item) {
                        var valor = (d !== null && d !== undefined && d !== '') ? parseFloat(d).toFixed(2) : '';
                        var bloqueado = (item.cn_pose_estado_proceso === 'APROBADO' || item.cn_pose_estado_proceso === 'REPROBADO') ? 'disabled' : '';
                        var borderStyle = (!bloqueado && etapa_requiere_puntaje) ? 'border-color:#dc3545;border-width:1.5px;' : '';

                        return '<input type="number" ' +
                            'class="form-control form-control-sm inp-puntaje text-center" ' +
                            'data-id="' + item.cn_post_id + '" ' +
                            'min="0" max="100" step="0.01" ' +
                            'value="' + valor + '" ' +
                            'placeholder="0.00" ' +
                            'style="min-width:80px;' + borderStyle + '" ' +
                            bloqueado + ' />';
                    }
                }
            ],
            order: [
                [1, 'asc']
            ],
            drawCallback: function() {
                if (etapa_requiere_puntaje) {
                    $('#lbl_col_puntaje').html('Puntaje <span class="badge bg-danger ms-1" style="font-size:9px;">REQUERIDO</span>');
                } else {
                    $('#lbl_col_puntaje').text('Puntaje');
                }
                actualizar_visibilidad_boton_verificar();
            }
        });
    }

    /* ── Select-all ── */
    $(document).on('change', '#cbx_select_all_postulantes', function() {
        $('#tbl_postulantes tbody .cbx-postulante').prop('checked', $(this).is(':checked'));
    });

    $(document).on('change', '#tbl_postulantes tbody .cbx-postulante', function() {
        var total = $('#tbl_postulantes tbody .cbx-postulante').length;
        var marcados = $('#tbl_postulantes tbody .cbx-postulante:checked').length;
        $('#cbx_select_all_postulantes').prop('checked', total > 0 && total === marcados);
    });

    $(document).on('change', '#tbl_postulantes tbody .sel-resultado', function() {
        actualizar_visibilidad_boton_verificar();
    });

    /* ── variable para trackear etapas completas en memoria ── */
    var etapas_completas = {}; // { orden: true/false }

    /* ── Controla visibilidad del botón según pendientes y secuencia ── */
    function actualizar_visibilidad_boton_verificar() {
        $('#warn_etapa_anterior').remove();

        var $selects = $('#tbl_postulantes tbody .sel-resultado');
        var total = $selects.length;

        // Sin postulantes → ocultar
        if (total === 0) {
            $('#btn_verificar_etapa').addClass('d-none');
            return;
        }

        // Contar selects sin disabled = pendientes de evaluar en esta etapa
        var pendientes_actuales = $selects.filter(function() {
            return !$(this).prop('disabled');
        }).length;

        // Marcar en memoria si la etapa actual está completa (todos disabled)
        etapas_completas[etapa_activa_orden] = (pendientes_actuales === 0);

        // Si no hay pendientes → todos ya evaluados → ocultar botón
        if (pendientes_actuales === 0) {
            $('#btn_verificar_etapa').addClass('d-none');
            return;
        }

        // Etapa 1 → siempre puede evaluar
        if (etapa_activa_orden <= 1) {
            $('#btn_verificar_etapa').removeClass('d-none');
            return;
        }

        // Verificar que la etapa ANTERIOR esté completa en memoria
        var orden_anterior = etapa_activa_orden - 1;
        var anterior_completa = etapas_completas[orden_anterior] === true;

        if (anterior_completa) {
            $('#btn_verificar_etapa').removeClass('d-none');
        } else {
            $('#btn_verificar_etapa').addClass('d-none');
            var $warn = $('<div id="warn_etapa_anterior" class="alert alert-warning py-2 mt-2 mb-0" role="alert">' +
                '<i class="bx bx-error me-1"></i> Debes completar la evaluación de la ' +
                '<strong>Etapa ' + orden_anterior + '</strong> antes de poder verificar esta etapa.' +
                '</div>');
            $('#pnl_postulantes .card-body').prepend($warn);
        }
    }

    /* ── Verificar / Guardar evaluación de la etapa activa ── */
    function verificar_pasos() {
        if (!etapa_activa_id) {
            alert('Selecciona una etapa primero.');
            return;
        }

        var evaluaciones = [];
        var hay_error = false;

        $('#tbl_postulantes tbody tr').removeClass('table-danger');
        $('#tbl_postulantes tbody .sel-resultado').removeClass('is-invalid');
        $('#tbl_postulantes tbody .inp-puntaje').removeClass('is-invalid');

        $('#tbl_postulantes tbody tr').each(function() {
            var $fila = $(this);
            var $select = $fila.find('.sel-resultado');
            var $puntaje = $fila.find('.inp-puntaje');

            var cn_post_id = $select.data('id');
            if (!cn_post_id) return;
            if ($select.prop('disabled')) return; // ya evaluado → saltar

            var resultado = $select.val();
            var puntaje = parseFloat($puntaje.val());
            var fila_error = false;

            if (!resultado) {
                $select.addClass('is-invalid');
                fila_error = true;
            }

            if (etapa_requiere_puntaje) {
                if ($puntaje.val() === '' || isNaN(puntaje)) {
                    $puntaje.addClass('is-invalid');
                    fila_error = true;
                }
            }

            if (fila_error) {
                $fila.addClass('table-danger');
                hay_error = true;
                return;
            }

            var observacion = resultado === 'APROBADO' ?
                'Cumple con los criterios de evaluación.' :
                'No alcanza el puntaje mínimo requerido.';

            evaluaciones.push({
                cn_post_id: parseInt(cn_post_id),
                resultado: resultado,
                puntaje: etapa_requiere_puntaje ? puntaje : ($puntaje.val() !== '' && !isNaN(puntaje) ? puntaje : 0),
                observacion: observacion
            });
        });

        if (hay_error) {
            var $alerta = $('<div class="alert alert-danger alert-dismissible fade show mt-2 py-2" role="alert">' +
                '<i class="bx bx-error-circle me-1"></i> Completa los campos marcados en rojo antes de guardar.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            $('#pnl_postulantes .card-body').prepend($alerta);
            setTimeout(function() {
                $alerta.alert('close');
            }, 4000);
            return;
        }

        if (evaluaciones.length === 0) {
            alert('No hay postulantes para evaluar en esta etapa.');
            return;
        }

        if (!confirm('¿Guardar evaluaciones de la Etapa ' + etapa_activa_orden + '? (' + evaluaciones.length + ' postulante(s))')) {
            return;
        }

        var $btn = $('#btn_verificar_etapa');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?evaluar_etapas_completas=true',
            type: 'POST',
            dataType: 'json',
            data: {
                evaluaciones: JSON.stringify(evaluaciones)
            },
            success: function(response) {
                $btn.prop('disabled', false).text('Verificar Etapa ' + etapa_activa_orden);

                if (response && !response.error) {
                    var $ok = $('<div class="alert alert-success alert-dismissible fade show mt-2 py-2" role="alert">' +
                        '<i class="bx bx-check-circle me-1"></i> Evaluaciones guardadas correctamente.' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    $('#pnl_postulantes .card-body').prepend($ok);
                    setTimeout(function() {
                        $ok.alert('close');
                    }, 3500);

                    <?php if (!empty($_id_plaza)) { ?>
                        cargar_etapas_tarjetas(<?= (int)$_id_plaza ?>);
                    <?php } ?>

                    cargar_postulantes_etapas();
                } else {
                    alert('Error al guardar: ' + (response.error || 'Intenta nuevamente.'));
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Verificar Etapa ' + etapa_activa_orden);
                alert('Error de conexión al guardar las evaluaciones.');
            }
        });
    }

    /* ── Init ── */
    $(document).ready(function() {
        <?php if (!empty($_id_plaza)) { ?>
            cargar_etapas_tarjetas(<?= (int)$_id_plaza ?>);
        <?php } ?>
    });
</script>

<style>
    #pnl_etapas_tarjetas {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: flex-start !important;
        overflow-x: auto;
        padding: 10px;
        gap: 10px;
        border: 2px solid rgba(108, 117, 125, 0.2);
        background-color: rgba(108, 117, 125, 0.05);
        min-height: 62px;
    }

    #pnl_etapas_tarjetas::-webkit-scrollbar {
        height: 8px;
    }

    #pnl_etapas_tarjetas::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    #pnl_etapas_tarjetas::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .etapa-card {
        flex: 0 0 auto !important;
        width: auto !important;
        max-width: 170px !important;
        min-width: 100px !important;
        display: block !important;
        padding: 4px 12px !important;
        margin: 0 !important;
        color: #fff !important;
        cursor: pointer;
        border-radius: 3px;
        border: none !important;
        text-align: center;
        position: relative;
        user-select: none;
        white-space: normal;
        word-break: break-word;
        transition: opacity .15s, box-shadow .15s, transform .12s;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .18);
    }

    .etapa-card:hover {
        opacity: .88;
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(0, 0, 0, .28);
    }

    .etapa-card.activa {
        outline: 3px solid rgba(255, 255, 255, .9);
        outline-offset: 2px;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, .35);
    }

    .etapa-card .event-title {
        font-weight: bold !important;
        font-size: .9em !important;
        line-height: 1.25;
        color: #fff !important;
        white-space: normal;
    }

    .etapa-card .event-body {
        margin-top: 2px !important;
        font-size: .7em !important;
        color: rgba(255, 255, 255, .82) !important;
        white-space: normal;
    }

    .etapa-badge-obligatorio {
        position: absolute;
        top: -8px;
        right: -5px;
        background: #dc3545;
        color: #fff;
        font-size: 7px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 8px;
        letter-spacing: .4px;
        text-transform: uppercase;
        white-space: nowrap;
        z-index: 4;
        line-height: 1.7;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .3);
    }

    #pnl_postulantes {
        display: none;
    }

    #pnl_postulantes.visible {
        display: block;
        animation: fadeDown .22s ease;
    }

    @keyframes fadeDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .postulantes-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .etapa-dot {
        width: 13px;
        height: 13px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
        margin-right: 7px;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, .1);
    }

    .table-postulantes th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 600;
        color: #555;
    }

    .table-postulantes td {
        font-size: 13px;
        vertical-align: middle;
    }

    /* Input puntaje requerido — borde rojo suave de aviso */
    .inp-puntaje[style*="border-color: #dc3545"] {
        background-color: #fff8f8;
    }

    /* Al validar, marca roja estándar de Bootstrap */
    .inp-puntaje.is-invalid {
        border-color: #dc3545 !important;
    }

    .sel-resultado.is-invalid {
        border-color: #dc3545 !important;
    }
</style>

<!-- ALERTA INFORMATIVA -->
<div class="alert alert-info alert-dismissible fade show shadow-sm border-0" role="alert"
    style="border-left: 5px solid #0dcaf0 !important;">
    <div class="d-flex align-items-center">
        <i class="bx bx-info-circle fs-3 me-3" style="color:#0dcaf0;"></i>
        <div>
            <h6 class="alert-heading mb-1 fw-bold">Selecciona una etapa</h6>
            <p class="mb-0 small">
                Haz clic en cualquiera de las <strong>etapas del proceso</strong> para visualizar
                los postulantes asignados junto con su <strong>estado</strong> y <strong>puntaje</strong>
                correspondiente a esa etapa.
            </p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- PASOS DEL RECLUTAMIENTO -->
<div class="row mb-2">
    <div class="col-12">
        <strong>Pasos del Reclutamiento</strong>
    </div>
    <div class="col-12 mt-1">
        <div id="pnl_etapas_tarjetas"></div>
    </div>
</div>

<!-- PANEL DE POSTULANTES -->
<div id="pnl_postulantes" class="row mt-3">
    <div class="col-12">
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-4">

                <div class="postulantes-header mb-3">
                    <div class="d-flex align-items-center">
                        <span class="etapa-dot" id="etapa_dot_panel"></span>
                        <h6 class="mb-0 fw-bold text-dark">
                            Postulantes &mdash; <span id="etapa_nombre_panel" class="text-primary"></span>
                        </h6>
                    </div>

                    <button type="button"
                        id="btn_verificar_etapa"
                        class="btn btn-success btn-sm d-none"
                        onclick="verificar_pasos()">
                        <i class="bx bx-check-shield me-1"></i>
                        Verificar Etapa
                    </button>
                </div>

                <hr class="mt-0">

                <div class="table-responsive">
                    <table class="table table-striped table-postulantes" id="tbl_postulantes" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:35px">
                                    <input type="checkbox" id="cbx_select_all_postulantes" class="form-check-input" />
                                </th>
                                <th>Postulante</th>
                                <th>Cédula</th>
                                <th class="text-center">Resultado</th>
                                <th class="text-center" id="lbl_col_puntaje">Puntaje</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>