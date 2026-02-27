<?php
$modulo_sistema = isset($_SESSION['INICIO']['MODULO_SISTEMA']) ? $_SESSION['INICIO']['MODULO_SISTEMA'] : '';
$_id_plaza      = isset($_GET['_id_plaza']) ? $_GET['_id_plaza'] : '';
?>

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
        flex-grow: 0 !important;
        flex-shrink: 0 !important;
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
</style>

<script>
    var etapa_activa_id = null;
    var tabla_postulantes = null;

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

                if (response && response.length > 0) {
                    response.forEach(function(item, i) {
                        var esObl = (item.cn_plaet_obligatoria == '1' || item.cn_plaet_obligatoria === true);
                        var color = item.etapa_color || item.color || colores[i % colores.length];
                        var total = item.total_postulantes || 0;
                        var nombre = item.etapa_nombre;
                        var badgeObl = esObl ? '<span class="etapa-badge-obligatorio">OBLIGATORIO</span>' : '';

                        html += '<div class="etapa-card"' +
                            ' data-id="' + item._id + '"' +
                            ' data-nombre="' + nombre + '"' +
                            ' data-color="' + color + '"' +
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

        $('.etapa-card').removeClass('activa');
        $(el).addClass('activa');

        etapa_activa_id = id;
        $('#etapa_dot_panel').css('background-color', color);
        $('#etapa_nombre_panel').text(nombre);
        $('#pnl_postulantes').addClass('visible');

        cargar_postulantes_etapas();
    }

    /* ── Carga la tabla de postulantes (th_pos_estado=1, th_pos_contratado=0) ── */
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
                }, {
                    data: 'cn_pose_estado_proceso',
                    className: 'text-center',
                    render: function(d) {
                        return '<span class="badge bg-warning text-dark">Pendiente</span>';
                    }
                },
                {
                    data: 'cn_pose_puntuacion',
                    className: 'text-center',
                    render: function(d) {
                        return d !== null && d !== '' ? d : '<span class="text-muted">—</span>';
                    }
                }
            ],
            order: [
                [1, 'asc']
            ]
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

    /* ── Init ── */
    $(document).ready(function() {
        <?php if (!empty($_id_plaza)) { ?>
            cargar_etapas_tarjetas(<?= (int)$_id_plaza ?>);
        <?php } ?>
    });
</script>

<!-- PASOS DEL RECLUTAMIENTO -->
<div class="alert alert-custom shadow-sm border-0 fade show" role="alert" style="background: #f8f9fa; border-left: 5px solid #0dcaf0 !important;">
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <i class="bx bx-info-circle fs-3" style="color: #0dcaf0;"></i>
        </div>
        <div class="ms-3">
            <h6 class="alert-heading mb-1 fw-bold" style="color: #333;">Selecciona una etapa</h6>
            <p class="mb-0 small" style="color: #666; line-height: 1.4;">
                Haz clic en cualquiera de las <strong>etapas del proceso</strong> para visualizar
                los postulantes asignados junto con su <strong>estado</strong> y <strong>puntaje</strong>
                correspondiente a esa etapa.
            </p>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
    </div>
</div>
<div class="row">
    <b>Pasos del Reclutamiento</b>

    <div class="row mb-col mt-1">
        <div class="col-md-12 col-12">
            <div id="pnl_etapas_tarjetas"></div>
        </div>
    </div>
</div>

<button type="button" class="btn btn-outline-success btn-sm" onclick="verificar_pasos()">
    <i class="bx bx-plus"></i> Verificar Pasos
</button>
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
                                <th class="text-center">Estado</th>
                                <th class="text-center">Puntuación</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>