<?php
$modulo_sistema = isset($_SESSION['INICIO']['MODULO_SISTEMA']) ? $_SESSION['INICIO']['MODULO_SISTEMA'] : '';
$_id_plaza      = isset($_GET['_id_plaza']) ? $_GET['_id_plaza'] : '';
?>

<script>
    var etapa_activa_id = null;
    var etapa_activa_orden = null;
    var etapa_requiere_puntaje = 0;
    var tabla_postulantes = null;
    var etapas_data = [];
    var etapas_completas = {};
    var _permite_evaluacion = false;

    /* ── Llamar desde la página principal al cargar datos de la plaza ── */
    function verificar_acciones_etapas(plaza_estado) {
        if (plaza_estado) {
            var id_estado = parseInt(plaza_estado.id_plaza_estados) || 0;
            _permite_evaluacion = (id_estado === 5 || id_estado === 6);
        } else {
            _permite_evaluacion = false;
        }
        if (etapa_activa_id) {
            actualizar_visibilidad_boton_verificar();
        }
    }

    /* ── Carga tabs de etapas ── */
    function cargar_etapas_tarjetas(id_plaza) {
        var $nav = $('#pnl_etapas_nav');
        var $content = $('#pnl_etapas_content');

        $nav.html('<span class="text-muted fst-italic p-3" style="font-size:13px;">' +
            '<span class="spinner-border spinner-border-sm me-1"></span> Cargando...</span>');
        $content.html('');

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?listar=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id_plaza: id_plaza
            },
            success: function(response) {
                etapas_data = response || [];
                var navHtml = '';
                var contentHtml = '';
                var colores = ['#3788d8', '#198754', '#e67e22', '#8e44ad', '#e74c3c', '#16a085', '#2c3e50', '#d35400'];

                if (response && response.length > 0) {
                    response.forEach(function(item, i) {
                        var esObl = (item.cn_plaet_obligatoria == '1' || item.cn_plaet_obligatoria === true);
                        var color = item.etapa_color || item.color || colores[i % colores.length];
                        var total = item.total_postulantes || 0;
                        var nombre = item.etapa_nombre;
                        var pendientes = parseInt(item.total_pendientes) || 0;
                        var evaluada = (total > 0 && pendientes === 0) ? '1' : '0';
                        var isFirst = (i === 0);

                        var badgeObl = esObl ?
                            '<span class="badge ms-1" style="font-size:9px;background:#dc3545;">OBLIGATORIA</span>' :
                            '';

                        navHtml += `
                        <button class="nav-link etapa-tab-btn ${isFirst ? 'active' : ''}"
                            data-id="${item._id}"
                            data-nombre="${nombre}"
                            data-color="${color}"
                            data-orden="${item.cn_plaet_orden}"
                            data-total="${total}"
                            data-pendientes="${pendientes}"
                            data-evaluada="${evaluada}"
                            data-requiere-puntaje="${item.etapa_requiere_puntaje == 1 ? 1 : 0}"
                            type="button" role="tab"
                            onclick="seleccionar_etapa(this)">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span style="width:9px;height:9px;border-radius:50%;background:${color};flex-shrink:0;display:inline-block;"></span>
                                <span class="fw-semibold" style="font-size:12px;">Etapa ${item.cn_plaet_orden}</span>
                                ${badgeObl}
                            </div>
                            <div style="font-size:11px;color:#666;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">${nombre}</div>
                            <div style="font-size:10px;color:#999;">${total} postulante${total != 1 ? 's' : ''}</div>
                        </button>`;

                        contentHtml += `
                        <div class="tab-pane fade ${isFirst ? 'show active' : ''}" id="etapa_pane_${item._id}" role="tabpanel">
                        </div>`;
                    });
                } else {
                    navHtml = '<span class="text-muted fst-italic p-3" style="font-size:13px;">' +
                        '<i class="bx bx-info-circle me-1"></i>No hay etapas configuradas.</span>';
                }

                $nav.html(navHtml);
                $content.html(contentHtml);

                if (response && response.length > 0) {
                    var $primer = $nav.find('.etapa-tab-btn').first();
                    seleccionar_etapa($primer[0]);
                }

                if (etapa_activa_id) {
                    var $btn = $nav.find('.etapa-tab-btn[data-id="' + etapa_activa_id + '"]');
                    if ($btn.length) {
                        $('#pnl_etapas_nav .etapa-tab-btn').removeClass('active');
                        $btn.addClass('active');
                    }
                }
            },
            error: function() {
                $nav.html('<span class="text-danger p-3">Error al cargar etapas.</span>');
            }
        });
    }

    /* ── Al hacer clic en una etapa ── */
    function seleccionar_etapa(el) {
        var $el = $(el);

        $('#pnl_etapas_nav .etapa-tab-btn').removeClass('active');
        $el.addClass('active');

        var id = $el.data('id');
        var nombre = $el.data('nombre');
        var color = $el.data('color');
        var orden = $el.data('orden');
        var reqPuntaje = parseInt($el.data('requiere-puntaje')) || 0;

        etapa_activa_id = id;
        etapa_activa_orden = orden;
        etapa_requiere_puntaje = reqPuntaje;

        $('#etapa_dot_panel').css('background-color', color);
        $('#etapa_nombre_panel').text(nombre);

        if (etapa_requiere_puntaje) {
            $('#col_puntaje_th').show();
            $('#lbl_col_puntaje').html('Puntaje <span class="badge bg-danger ms-1" style="font-size:9px;">REQUERIDO</span>');
        } else {
            $('#col_puntaje_th').hide();
            $('#lbl_col_puntaje').text('Puntaje');
        }

        $('#btn_verificar_etapa').text('Verificar Etapa ' + orden);
        $('#warn_etapa_anterior').remove();
        $('#btn_verificar_etapa').addClass('d-none');

        $('#pnl_postulantes').addClass('visible');
        cargar_postulantes_etapas();
    }

    /* ── Carga la tabla de postulantes ── */
    function cargar_postulantes_etapas() {
        if (tabla_postulantes && $.fn.DataTable.isDataTable('#tbl_postulantes')) {
            tabla_postulantes.destroy();
            $('#tbl_postulantes tbody').empty();
        }

        // ← Limpiar thead para evitar columnas duplicadas
        $('#tbl_postulantes thead tr th#col_puntaje_th').hide();

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
                    data: 'cn_pose_puntuacion',
                    className: 'text-center col-puntaje-td',
                    // ← NO usar visible:false aquí, controlamos con column().visible() después
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
                }
            ],
            order: [
                [1, 'asc']
            ],
            drawCallback: function() {
                // ← Usar setTimeout para esperar que el DOM de DataTables esté listo
                setTimeout(function() {
                    toggle_columna_puntaje();
                    actualizar_visibilidad_boton_verificar();
                }, 0);
            }
        });
    }

    function toggle_columna_puntaje() {
        try {
            if (tabla_postulantes && $.fn.DataTable.isDataTable('#tbl_postulantes')) {
                var col = tabla_postulantes.column(3);
                if (col) {
                    col.visible(etapa_requiere_puntaje ? true : false);
                }
            }
        } catch (e) {
            // Silenciar error si la columna aún no está lista
        }

        if (etapa_requiere_puntaje) {
            $('#col_puntaje_th').show();
            $('#lbl_col_puntaje').html('Puntaje <span class="badge bg-danger ms-1" style="font-size:9px;">REQUERIDO</span>');
        } else {
            $('#col_puntaje_th').hide();
            $('#lbl_col_puntaje').text('Puntaje');
        }
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

    /* ── Controla visibilidad del botón verificar ── */
    function actualizar_visibilidad_boton_verificar() {
        $('#warn_etapa_anterior').remove();

        // Bloqueo principal: solo estados 5 (PUBLICADA) y 6 (EN_EVALUACION)
        if (!_permite_evaluacion) {
            $('#btn_verificar_etapa').addClass('d-none');
            return;
        }

        var $selects = $('#tbl_postulantes tbody .sel-resultado');
        var total = $selects.length;

        if (total === 0) {
            $('#btn_verificar_etapa').addClass('d-none');
            return;
        }

        var pendientes_actuales = $selects.filter(function() {
            return !$(this).prop('disabled');
        }).length;

        etapas_completas[etapa_activa_orden] = (pendientes_actuales === 0);

        if (pendientes_actuales === 0) {
            $('#btn_verificar_etapa').addClass('d-none');
            return;
        }

        if (etapa_activa_orden <= 1) {
            $('#btn_verificar_etapa').removeClass('d-none');
            return;
        }

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

    /* ── Verificar / Guardar evaluación ── */
    function verificar_pasos() {
        if (!etapa_activa_id) {
            Swal.fire('Atención', 'Selecciona una etapa primero.', 'warning');
            return;
        }

        if (!_permite_evaluacion) {
            Swal.fire('Sin permiso', 'La plaza no está habilitada para evaluación.', 'warning');
            return;
        }

        var evaluaciones = [];
        var hay_error = false;

        $('#tbl_postulantes tbody tr').removeClass('table-danger');
        $('#tbl_postulantes tbody .sel-resultado, #tbl_postulantes tbody .inp-puntaje').removeClass('is-invalid');

        $('#tbl_postulantes tbody tr').each(function() {
            var $fila = $(this);
            var $select = $fila.find('.sel-resultado');
            var $puntaje = $fila.find('.inp-puntaje');

            var cn_post_id = $select.data('id');
            if (!cn_post_id || $select.prop('disabled')) return;

            var resultado = $select.val();
            var puntaje = parseFloat($puntaje.val());
            var fila_error = false;

            if (!resultado) {
                $select.addClass('is-invalid');
                fila_error = true;
            }

            if (etapa_requiere_puntaje && ($puntaje.val() === '' || isNaN(puntaje))) {
                $puntaje.addClass('is-invalid');
                fila_error = true;
            }

            if (fila_error) {
                $fila.addClass('table-danger');
                hay_error = true;
            } else {
                evaluaciones.push({
                    cn_post_id: parseInt(cn_post_id),
                    resultado: resultado,
                    puntaje: etapa_requiere_puntaje ? puntaje : ($puntaje.val() !== '' && !isNaN(puntaje) ? puntaje : 0),
                    observacion: resultado === 'APROBADO' ?
                        'Cumple con los criterios de evaluación.' : 'No alcanza el puntaje mínimo requerido.'
                });
            }
        });

        if (hay_error) {
            Swal.fire({
                title: 'Campos incompletos',
                text: 'Completa los campos marcados en rojo antes de continuar.',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        if (evaluaciones.length === 0) {
            Swal.fire('Sin datos', 'No hay postulantes pendientes de evaluación en esta etapa.', 'info');
            return;
        }

        Swal.fire({
            title: '¿Guardar evaluaciones?',
            text: 'Vas a registrar la Etapa ' + etapa_activa_orden + ' para ' + evaluaciones.length + ' postulante(s).',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) ejecutar_guardado_etapa(evaluaciones);
        });
    }

    function ejecutar_guardado_etapa(evaluaciones) {
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
                    Swal.fire({
                        title: '¡Logrado!',
                        text: 'Las evaluaciones se han guardado correctamente.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    <?php if (!empty($_id_plaza)) { ?>
                        cargar_etapas_tarjetas(<?= (int)$_id_plaza ?>);
                    <?php } ?>

                    var id_plaza_estado = parseInt($('#txt_id_plaza_estados').val()) || 0;
                    if (id_plaza_estado == 5) Evaluar_plaza();

                    cargar_postulantes_etapas();
                } else {
                    Swal.fire('Error', response.error || 'No se pudieron guardar los cambios.', 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Verificar Etapa ' + etapa_activa_orden);
                Swal.fire('Error de red', 'No se pudo conectar con el servidor.', 'error');
            }
        });
    }

    function Evaluar_plaza() {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?cambiar_estado_plaza=true',
            type: 'POST',
            dataType: 'json',
            data: {
                parametros: {
                    '_id': '<?= $_id_plaza ?>',
                    'id_plaza_estados': 6,
                    'accion': 'Evaluación de la plaza'
                }
            },
            success: function() {
                cargar_plaza_historial('<?= $_id_plaza ?>');
                actualizar_boton_postulante(false);
            },
            error: function(xhr) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
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
    .etapas-col-izq {
        border-right: 1px solid #dee2e6;
        padding: 0 !important;
    }

    .etapas-col-header {
        background: #f8f9fa;
        padding: 10px 12px;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 0 0 0;
    }

    #pnl_etapas_nav_scroll {
        height: 270px;
        overflow-y: scroll;
        overflow-x: hidden;
    }

    #pnl_etapas_nav_scroll::-webkit-scrollbar {
        width: 5px;
    }

    #pnl_etapas_nav_scroll::-webkit-scrollbar-thumb {
        background: #c0c0c0;
        border-radius: 4px;
    }

    #pnl_etapas_nav_scroll::-webkit-scrollbar-thumb:hover {
        background: #888;
    }

    #pnl_etapas_nav {
        display: flex !important;
        flex-direction: column !important;
    }

    .etapa-tab-btn {
        display: block;
        width: 100%;
        background: none;
        border: none !important;
        border-bottom: 1px solid #dee2e6 !important;
        border-radius: 0 !important;
        color: #444;
        transition: background .15s, color .15s;
        text-align: left;
        padding: 12px 14px;
        cursor: pointer;
    }

    .etapa-tab-btn:hover {
        background: #f0f4ff;
        color: #0d6efd;
    }

    .etapa-tab-btn.active {
        background: #e8f0fe !important;
        color: #0d6efd !important;
        border-right: 3px solid #0d6efd !important;
        font-weight: 600;
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

    .inp-puntaje.is-invalid {
        border-color: #dc3545 !important;
    }

    .sel-resultado.is-invalid {
        border-color: #dc3545 !important;
    }
</style>

<!-- LAYOUT PRINCIPAL -->
<div class="row g-0 shadow-sm border rounded-3 mb-4">

    <div class="col-md-3 bg-light rounded-start etapas-col-izq">
        <div class="etapas-col-header">
            <strong class="text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;">
                <i class="bx bx-list-ol me-1"></i> Etapas del Proceso
            </strong>
        </div>
        <div id="pnl_etapas_nav_scroll">
            <div class="nav flex-column nav-pills" id="pnl_etapas_nav" role="tablist">
                <!-- se llena dinámicamente -->
            </div>
        </div>
    </div>

    <div class="col-md-9 bg-white rounded-end">
        <div class="tab-content" id="pnl_etapas_content"></div>

        <div id="pnl_postulantes">
            <div class="card border-0">
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
                                    <th class="text-center" id="col_puntaje_th" style="display:none;">
                                        <span id="lbl_col_puntaje">Puntaje</span>
                                    </th>
                                    <th class="text-center">Resultado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>