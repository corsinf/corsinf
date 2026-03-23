<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$id_espacio = isset($_GET['id_espacio']) ? intval($_GET['id_espacio']) : '';
?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    const DIAS = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const id_espacio_url = '<?= $id_espacio ?>';

    let tbl_horarios, datos_horarios = [],
        selected_id = null;

    $(document).ready(function() {
        cargar_select2_url('ddl_filtro_espacio', '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?buscar=true');

        <?php if ($id_espacio): ?>
            $('#ddl_filtro_espacio').append($('<option>', {
                value: '<?= $id_espacio ?>',
                text: 'Espacio #<?= $id_espacio ?>',
                selected: true
            })).trigger('change');
            cargar_tabla('<?= $id_espacio ?>');
        <?php endif; ?>

        $('#ddl_filtro_espacio').on('change', function() {
            let id = $(this).val();
            selected_id = null;
            if (id) cargar_tabla(id);
            else {
                tbl_horarios.clear().draw();
                $('#contenedor_timeline').hide();
            }
        });

        $('#ddl_filtro_dia').on('change', function() {
            selected_id = null;
            renderizar_timeline($(this).val(), null);
            actualizar_estado_btn_todos();
        });

        $('#btn_ver_todos').on('click', function() {
            selected_id = null;
            renderizar_timeline($('#ddl_filtro_dia').val(), null);
            actualizar_estado_btn_todos();
        });

        tbl_horarios = $('#tbl_horarios').DataTable($.extend({}, configuracion_datatable('Día', 'Hora inicio', 'Hora fin', 'Activo'), {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return DIAS[item.dia_semana] ?? item.dia_semana;
                    }
                },
                {
                    data: 'hora_inicio'
                },
                {
                    data: 'hora_fin'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return item.activo == 1 ?
                            '<span class="badge bg-success">Activo</span>' :
                            '<span class="badge bg-secondary">Inactivo</span>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        let href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_registrar_horario&_id=${item._id}`;
                        return `
                            <div class="d-flex gap-1">
                                <a href="${href}" class="btn btn-outline-primary btn-xs" title="Ver detalle">
                                    <i class="bx bx-show"></i>
                                </a>
                                <button class="btn btn-outline-info btn-xs btn-tl" data-id="${item._id}" title="Ver en timeline">
                                    <i class="bx bx-time-five"></i>
                                </button>
                            </div>`;
                    }
                }
            ],
            order: [
                [0, 'asc']
            ]
        }));

        /* Click en botón timeline de fila */
        $('#tbl_horarios').on('click', '.btn-tl', function() {
            let id = $(this).data('id');
            let rec = datos_horarios.find(h => h._id == id);
            if (!rec) return;

            selected_id = (selected_id == id) ? null : id;
            if (selected_id) $('#ddl_filtro_dia').val(rec.dia_semana);
            renderizar_timeline($('#ddl_filtro_dia').val(), selected_id);
            actualizar_estado_btn_todos();

            if (selected_id) $('html, body').animate({
                scrollTop: $('#contenedor_timeline').offset().top - 80
            }, 300);
        });
    });

    /* ─── Carga tabla ─── */

    function cargar_tabla(id) {
        $.ajax({
            data: {
                id_espacio: id
            },
            url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                datos_horarios = response;
                selected_id = null;
                tbl_horarios.clear().rows.add(response).draw();
                $('#badge_total').text(response.length + ' horario(s)');

                if (response.length > 0) {
                    $('#contenedor_timeline').show();
                    let dia = new Date().getDay();
                    $('#ddl_filtro_dia').val(dia);
                    renderizar_timeline(dia, null);
                } else {
                    $('#contenedor_timeline').hide();
                }

                actualizar_estado_btn_todos();
            }
        });
    }

    /* ─── Timeline ─── */

    function t_a_min(hora) {
        let p = hora.split(':');
        return parseInt(p[0]) * 60 + parseInt(p[1]);
    }

    function renderizar_timeline(dia, filtro_id) {
        let $tl = $('#timeline_24h');
        $tl.empty();

        /* Marcas de horas */
        for (let h = 0; h < 24; h++) {
            let pct = (h / 24) * 100;
            $tl.append(
                `<div style="position:absolute;left:${pct}%;top:0;bottom:0;border-left:1px solid #dee2e6;"></div>` +
                `<span style="position:absolute;left:calc(${pct}% + 2px);top:2px;font-size:10px;color:#6c757d;">${String(h).padStart(2, '0')}:00</span>`
            );
        }

        let horarios_dia = datos_horarios.filter(h => h.dia_semana == dia);
        let horarios_render = filtro_id !== null ?
            horarios_dia.filter(h => h._id == filtro_id) :
            horarios_dia;

        if (horarios_render.length === 0) {
            $tl.append(`
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                    <span class="text-muted small">Sin horarios para este día</span>
                </div>`);
            return;
        }

        horarios_render.forEach(function(h) {
            let ini = t_a_min(h.hora_inicio);
            let fin = t_a_min(h.hora_fin);
            let pi = (ini / 1440) * 100;
            let pw = ((fin - ini) / 1440) * 100;
            let esSeleccionado = filtro_id !== null && h._id == filtro_id;
            let color = h.activo == 1 ? '#0d6efd' : '#6c757d';
            let opacity = esSeleccionado ? '1' : '0.72';
            let borde = esSeleccionado ? `border:2px solid #fff;box-shadow:0 0 0 2px ${color};` : '';

            $tl.append(`
                <div class="barra-tl"
                    data-id="${h._id}"
                    data-dia="${h.dia_semana}"
                    style="position:absolute;left:${pi}%;width:${pw}%;top:24px;bottom:4px;
                           background:${color};opacity:${opacity};border-radius:4px;
                           cursor:pointer;${borde}"
                    title="${h.hora_inicio.substring(0,5)} – ${h.hora_fin.substring(0,5)} | ${h.activo==1?'Activo':'Inactivo'}"
                    data-bs-toggle="tooltip">
                    <span style="font-size:10px;color:#fff;padding:2px 6px;white-space:nowrap;overflow:hidden;display:block;">
                        ${h.hora_inicio.substring(0,5)} – ${h.hora_fin.substring(0,5)}
                    </span>
                </div>`);
        });

        /* Click en barra */
        $tl.find('.barra-tl').on('click', function() {
            let id = $(this).data('id');
            let dia = $(this).data('dia');
            selected_id = (selected_id == id) ? null : id;
            if (selected_id) $('#ddl_filtro_dia').val(dia);
            renderizar_timeline($('#ddl_filtro_dia').val(), selected_id);
            actualizar_estado_btn_todos();
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    function actualizar_estado_btn_todos() {
        if (selected_id !== null) {
            $('#btn_ver_todos').removeClass('btn-outline-secondary').addClass('btn-secondary');
        } else {
            $('#btn_ver_todos').removeClass('btn-secondary').addClass('btn-outline-secondary');
        }
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Horarios</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Horarios</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Espacio</label>
                                <select id="ddl_filtro_espacio" class="form-select form-select-sm">
                                    <option value="">-- Seleccione un espacio --</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end justify-content-between">
                                <span id="badge_total" class="badge bg-primary fs-6"></span>
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_registrar_horario" class="btn btn-success btn-sm">
                                    <i class="bx bx-plus pb-1"></i> Nuevo
                                </a>
                            </div>
                        </div>

                        <!-- Timeline filtrable -->
                        <div id="contenedor_timeline" style="display:none;">
                            <div class="card border mb-4">
                                <div class="card-header d-flex align-items-center gap-2 flex-wrap">
                                    <strong class="me-1">Ocupación</strong>
                                    <select id="ddl_filtro_dia" class="form-select form-select-sm w-auto">
                                        <option value="0">Domingo</option>
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miércoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sábado</option>
                                    </select>
                                    <button id="btn_ver_todos" class="btn btn-outline-secondary btn-sm" title="Mostrar todos">
                                        <i class="bx bx-list-ul me-1"></i> Ver todos
                                    </button>
                                    <div class="d-flex gap-3 ms-auto small text-muted">
                                        <span><span class="badge bg-primary">&nbsp;</span> Activo</span>
                                        <span><span class="badge bg-secondary">&nbsp;</span> Inactivo</span>
                                        <span class="text-muted fst-italic">Clic en barra para filtrar</span>
                                    </div>
                                </div>
                                <div class="card-body py-3">
                                    <div id="timeline_24h"
                                        style="position:relative;height:60px;background:#f8f9fa;border-radius:4px;overflow:hidden;border:1px solid #dee2e6;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_horarios" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Día</th>
                                                <th>Hora inicio</th>
                                                <th>Hora fin</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>