<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$id_espacio = isset($_GET['id_espacio']) ? intval($_GET['id_espacio']) : '';
?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    const DIAS = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const id_espacio = '<?= $id_espacio ?>';

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
            if (id) cargar_tabla(id);
            else {
                tbl_horarios.clear().draw();
                $('#contenedor_timeline').hide();
            }
        });

        $('#ddl_filtro_dia').on('change', function() {
            let dia = $(this).val();
            renderizar_timeline(dia);
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
                        return `<a href="${href}" class="btn btn-outline-primary btn-sm"><i class="bx bx-show"></i> Ver</a>`;
                    }
                }
            ],
            order: [
                [0, 'asc']
            ]
        }));
    });

    let datos_horarios = [];

    function cargar_tabla(id_espacio) {
        $.ajax({
            data: {
                id_espacio: id_espacio
            },
            url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                datos_horarios = response;
                tbl_horarios.clear().rows.add(response).draw();

                let total = response.length;
                $('#badge_total').text(total + ' horario(s)');

                if (total > 0) {
                    $('#contenedor_timeline').show();
                    let dia_actual = new Date().getDay();
                    $('#ddl_filtro_dia').val(dia_actual);
                    renderizar_timeline(dia_actual);
                } else {
                    $('#contenedor_timeline').hide();
                }
            }
        });
    }

    function renderizar_timeline(dia) {
        let horarios_dia = datos_horarios.filter(h => h.dia_semana == dia);
        let $timeline = $('#timeline_24h');
        $timeline.empty();

        for (let h = 0; h < 24; h++) {
            let pct = (h / 24) * 100;
            $timeline.append(`
                <div style="position:absolute;left:${pct}%;top:0;bottom:0;border-left:1px solid #dee2e6;"></div>
                <span style="position:absolute;left:calc(${pct}% + 2px);top:2px;font-size:10px;color:#6c757d;">${String(h).padStart(2,'0')}:00</span>
            `);
        }

        if (horarios_dia.length === 0) {
            $timeline.append(`
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                    <span class="text-muted small">Sin horarios para este día</span>
                </div>
            `);
        }

        horarios_dia.forEach(function(h) {
            let ini = tiempo_a_minutos(h.hora_inicio);
            let fin = tiempo_a_minutos(h.hora_fin);
            let pct_ini = (ini / 1440) * 100;
            let pct_ancho = ((fin - ini) / 1440) * 100;
            let color = h.activo == 1 ? '#0d6efd' : '#6c757d';

            $timeline.append(`
                <div style="position:absolute;left:${pct_ini}%;width:${pct_ancho}%;top:24px;bottom:4px;
                    background:${color};opacity:0.75;border-radius:4px;cursor:pointer;"
                    title="${h.hora_inicio} - ${h.hora_fin} | ${h.activo == 1 ? 'Activo' : 'Inactivo'}"
                    data-bs-toggle="tooltip">
                    <span style="font-size:10px;color:#fff;padding:2px 4px;white-space:nowrap;overflow:hidden;display:block;">
                        ${h.hora_inicio.substring(0,5)} - ${h.hora_fin.substring(0,5)}
                    </span>
                </div>
            `);
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    function tiempo_a_minutos(hora) {
        let partes = hora.split(':');
        return parseInt(partes[0]) * 60 + parseInt(partes[1]);
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
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_registrar_horario"
                                    class="btn btn-success btn-sm">
                                    <i class="bx bx-plus pb-1"></i> Nuevo
                                </a>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div id="contenedor_timeline" style="display:none;">
                            <div class="card border mb-4">
                                <div class="card-header d-flex align-items-center gap-3">
                                    <strong>Ocupación del día</strong>
                                    <select id="ddl_filtro_dia" class="form-select form-select-sm w-auto">
                                        <option value="0">Domingo</option>
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miércoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sábado</option>
                                    </select>
                                    <div class="d-flex gap-3 ms-auto small text-muted">
                                        <span><span class="badge bg-primary">&nbsp;</span> Activo</span>
                                        <span><span class="badge bg-secondary">&nbsp;</span> Inactivo</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="timeline_24h" style="position:relative;height:60px;background:#f8f9fa;border-radius:4px;overflow:hidden;border:1px solid #dee2e6;"></div>
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