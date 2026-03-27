<?php
$modulo_sistema = $_SESSION['INICIO']['MODULO_SISTEMA'];
?>

<script>
    $(document).ready(function() {
        cargar_selects_clientes();
    });

    function cargar_selects_clientes() {
        url_clientesC = '../controlador/GENERAL/NO_CONCURRENTES/CLIENTESC.php?buscar_clientes=true';
        cargar_select2_url('ddl_clientes', url_clientesC, '', '#modalReserva');
    }
</script>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<div class="page-wrapper">
    <div class="page-content">

        <!-- breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">RESERVAS</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Hub · Espacios</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- end breadcrumb -->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">

                        <!-- STEP BAR -->
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                            <span class="badge bg-primary px-3 py-2" id="st1">
                                <i class='bx bx-map me-1'></i>Ubicación
                            </span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st2">
                                <i class='bx bx-layer me-1'></i>Piso
                            </span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st3">
                                <i class='bx bx-category me-1'></i>Tipo
                            </span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st4">
                                <i class='bx bx-door-open me-1'></i>Espacio
                            </span>
                        </div>

                        <!-- MAIN LAYOUT -->
                        <div class="row g-3">

                            <!-- SIDEBAR PISOS -->
                            <div class="col-md-2" id="sidebar-col" style="display:none;">
                                <div class="card border mb-0 h-100">
                                    <div class="card-header py-2 bg-light">
                                        <small class="fw-bold text-muted text-uppercase">
                                            <i class='bx bx-layer me-1'></i>Pisos
                                        </small>
                                    </div>
                                    <div class="card-body p-0" id="floor-list"></div>
                                </div>
                            </div>

                            <!-- CONTENT AREA -->
                            <div id="content-col" class="col-md-12">

                                <!-- STEP 1: UBICACIONES -->
                                <div id="view-locations">
                                    <p class="text-muted small fw-semibold text-uppercase mb-2">
                                        <i class='bx bx-map-pin me-1'></i>Selecciona una ubicación
                                    </p>
                                    <div class="row g-3" id="loc-cards">
                                        <div class="col-12 text-center py-4 text-muted">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Cargando ubicaciones...
                                        </div>
                                    </div>
                                </div>

                                <!-- STEP 2+: PISOS + TIPOS + ESPACIOS -->
                                <div id="view-spaces" style="display:none;">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="goBackToLocations()">
                                            <i class='bx bx-arrow-back'></i> Volver
                                        </button>
                                        <p class="text-muted small fw-semibold text-uppercase mb-0">
                                            <i class='bx bx-category me-1'></i>Tipo de espacio
                                        </p>
                                    </div>

                                    <!-- TABS DE TIPO -->
                                    <div class="d-flex gap-2 flex-wrap mb-3" id="type-tabs"></div>

                                    <p class="text-muted small fw-semibold text-uppercase mb-2">
                                        <i class='bx bx-grid-alt me-1'></i>Espacios disponibles
                                    </p>
                                    <div class="row g-3" id="space-cards"></div>
                                </div>

                            </div><!-- /content-col -->
                        </div><!-- /row -->

                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════
             MODAL RESERVA
        ══════════════════════════════════════ -->
        <div class="modal fade" id="modalReserva" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title" id="modal-space-name">Reservar espacio</h5>
                            <small class="text-muted" id="modal-space-sub"></small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4">

                            <!-- Calendario -->
                            <div class="col-md-7">
                                <p class="text-muted small fw-semibold text-uppercase mb-2">
                                    <i class='bx bx-calendar me-1'></i>Selecciona fecha(s)
                                </p>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <button class="btn btn-sm btn-outline-secondary" onclick="calPrev()">
                                        <i class='bx bx-chevron-left'></i>
                                    </button>
                                    <strong id="cal-month-label"></strong>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="calNext()">
                                        <i class='bx bx-chevron-right'></i>
                                    </button>
                                </div>
                                <div id="cal-grid" class="hub-cal-grid"></div>

                                <!-- Turnos del día -->
                                <div id="turnos-dia" class="mt-3" style="display:none;">
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">
                                        <i class='bx bx-time me-1'></i>Turnos disponibles
                                    </p>
                                    <div id="turnos-dia-list"></div>
                                </div>
                            </div>

                            <!-- Opciones -->
                            <div class="col-md-5">
                                <p class="text-muted small fw-semibold text-uppercase mb-2">
                                    <i class='bx bx-slider-alt me-1'></i>Tipo de reserva
                                </p>
                                <div class="mb-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="tipo_reserva"
                                            id="r_dia" value="dia" checked onchange="toggleTipoReserva()">
                                        <label class="form-check-label" for="r_dia">
                                            <i class='bx bx-calendar-check me-1 text-success'></i>Por un día
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_reserva"
                                            id="r_rango" value="rango" onchange="toggleTipoReserva()">
                                        <label class="form-check-label" for="r_rango">
                                            <i class='bx bx-calendar-alt me-1 text-primary'></i>Por rango de fechas
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="ddl_clientes" class="form-label fw-bold">Persona </label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_clientes" name="ddl_clientes">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                </div>

                                <!-- Por día -->
                                <div id="opt-dia">
                                    <label class="form-label small">Fecha</label>
                                    <input type="date" class="form-control form-control-sm mb-2" id="inp-fecha-dia">
                                </div>

                                <!-- Por rango -->
                                <div id="opt-rango" style="display:none;">
                                    <label class="form-label small">Fecha inicio</label>
                                    <input type="date" class="form-control form-control-sm mb-2"
                                        id="inp-fecha-ini" onchange="syncRange()">
                                    <label class="form-label small">Fecha fin</label>
                                    <input type="date" class="form-control form-control-sm mb-2"
                                        id="inp-fecha-fin" onchange="syncRange()">
                                </div>

                                <hr>
                                <p class="text-muted small fw-semibold text-uppercase mb-1">
                                    <i class='bx bx-receipt me-1'></i>Resumen
                                </p>
                                <div id="resumen-reserva" class="small text-muted">
                                    Selecciona una fecha para ver el resumen.
                                </div>

                                <div class="d-grid mt-3">
                                    <button class="btn btn-success" onclick="confirmarReserva()">
                                        <i class='bx bx-calendar-plus me-1'></i>Confirmar reserva
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /page-content -->
</div><!-- /page-wrapper -->


<!-- ══════════════════════════════════════
     ESTILOS MÍNIMOS (solo lo que Bootstrap no cubre)
══════════════════════════════════════ -->
<style>
    /* Calendario */
    .hub-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
    }

    .hub-cal-head {
        text-align: center;
        font-size: .7rem;
        font-weight: 600;
        color: #6c757d;
        padding: 4px 0;
        text-transform: uppercase;
    }

    .hub-cal-day {
        text-align: center;
        padding: 7px 2px;
        font-size: .82rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background .15s;
        position: relative;
    }

    .hub-cal-day:hover:not(.empty):not(.past) {
        background: #f0f0f0;
    }

    .hub-cal-day.empty,
    .hub-cal-day.past {
        color: #ccc;
        cursor: default;
    }

    .hub-cal-day.today {
        font-weight: 700;
        color: #0d6efd;
    }

    .hub-cal-day.has-shift::after {
        content: '';
        display: block;
        width: 5px;
        height: 5px;
        background: #52b788;
        border-radius: 50%;
        margin: 2px auto 0;
    }

    .hub-cal-day.selected {
        background: #0d6efd !important;
        color: #fff;
    }

    .hub-cal-day.in-range {
        background: rgba(13, 110, 253, .12);
    }

    .hub-cal-day.range-start,
    .hub-cal-day.range-end {
        background: #0d6efd;
        color: #fff;
    }

    .hub-cal-day.reserved {
        background: rgba(82, 183, 136, .15);
    }

    /* Pisos sidebar */
    .floor-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        cursor: pointer;
        border-left: 3px solid transparent;
        transition: all .15s;
        font-size: .88rem;
    }

    .floor-item:hover {
        background: rgba(0, 0, 0, .04);
    }

    .floor-item.active {
        border-left-color: #0d6efd;
        background: rgba(13, 110, 253, .07);
        color: #0d6efd;
        font-weight: 600;
    }

    .floor-badge {
        margin-left: auto;
        font-size: .7rem;
    }

    /* Location / space cards */
    .loc-card {
        border: 1.5px solid #dee2e6;
        border-radius: .5rem;
        background: #fff;
        padding: 18px 20px;
        cursor: pointer;
        transition: all .2s;
        border-left: 4px solid #dee2e6;
    }

    .loc-card:hover {
        border-left-color: #52b788;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
    }

    .loc-card.selected {
        border-left-color: #0d6efd;
        box-shadow: 0 4px 14px rgba(13, 110, 253, .12);
    }

    .space-card {
        border: 1.5px solid #dee2e6;
        border-radius: .5rem;
        background: #fff;
        overflow: hidden;
        transition: all .2s;
        cursor: pointer;
    }

    .space-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .09);
        border-color: #52b788;
    }

    .space-card.expanded {
        border-color: #0d6efd;
    }

    .space-img-placeholder {
        width: 100%;
        height: 130px;
        background: linear-gradient(135deg, #c5e1c8, #a8d5b5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: rgba(255, 255, 255, .7);
    }

    .space-detail {
        max-height: 0;
        overflow: hidden;
        transition: max-height .35s ease;
        border-top: 1px solid #dee2e6;
        background: #f8f9fa;
    }

    .space-detail.open {
        max-height: 400px;
    }

    .type-tab {
        padding: 6px 16px;
        border-radius: 30px;
        border: 1.5px solid #dee2e6;
        background: #fff;
        font-size: .84rem;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .type-tab:hover {
        border-color: #52b788;
    }

    .type-tab.active {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
</style>


<!-- ══════════════════════════════════════
     JAVASCRIPT – todo con jQuery
══════════════════════════════════════ -->
<script>
    $(function() {

        /* ── URLs de los controladores ── */
        const URL_UBICACIONES = '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php';
        const URL_PISOS = '../controlador/HOST_TIME/CATALOGOS/hub_catn_numero_pisoC.php';
        const URL_TIPOS = '../controlador/HOST_TIME/CATALOGOS/hub_catn_tipo_espacioC.php';
        const URL_ESPACIOS = '../controlador/HOST_TIME/ESPACIOS/espaciosC.php';
        const URL_TURNOS = '../controlador/HOST_TIME/ESPACIOS/hub_espacios_turnosC.php';

        /* ── Estado ── */
        let selUbicacion = null,
            selPiso = null,
            selTipo = null,
            activeEspacioId = null;
        let calDate = new Date(),
            calSelStart = null,
            calSelEnd = null;
        const RESERVAS = {};
        const MONTHS = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        /* ════════════════════════════════════════
           STEP BAR
        ════════════════════════════════════════ */
        function setStep(n) {
            for (let i = 1; i <= 4; i++) {
                const el = $('#st' + i);
                el.removeClass('bg-primary bg-success bg-secondary');
                if (i < n) el.addClass('bg-success');
                else if (i === n) el.addClass('bg-primary');
                else el.addClass('bg-secondary');
            }
        }

        /* ════════════════════════════════════════
           STEP 1 – UBICACIONES
        ════════════════════════════════════════ */
        function cargarUbicaciones() {
            $.post(URL_UBICACIONES + '?listar=true', {}, function(data) {
                let html = '';
                if (!data || data.length === 0) {
                    html = '<div class="col-12 text-center text-muted py-3">No hay ubicaciones registradas.</div>';
                } else {
                    data.forEach(function(u) {
                        html += `
                    <div class="col-md-4 col-sm-6">
                        <div class="loc-card" id="loc-${u._id}" onclick="window.seleccionarUbicacion(${u._id})">
                            <h6 class="mb-1 fw-bold">${u.nombre}</h6>
                            <div class="small text-muted">
                                <div><i class='bx bx-map-pin me-1 text-success'></i>${u.direccion}</div>
                                <div><i class='bx bx-phone me-1 text-success'></i>${u.telefono}</div>
                            </div>
                        </div>
                    </div>`;
                    });
                }
                $('#loc-cards').html(html);
            }, 'json').fail(function() {
                $('#loc-cards').html('<div class="col-12 alert alert-danger">Error al cargar ubicaciones.</div>');
            });
        }

        window.seleccionarUbicacion = function(id) {
            selUbicacion = id;
            selPiso = null;
            selTipo = null;
            $('.loc-card').removeClass('selected');
            $('#loc-' + id).addClass('selected');
            setStep(2);
            cargarPisos(id);
        };

        /* ════════════════════════════════════════
       STEP 2 – PISOS (sidebar)
    ════════════════════════════════════════ */
        function cargarPisos(id_ubicacion) {
            $.ajax({
                data: {
                    id: id_ubicacion // Se envía como 'id', asegúrate que el controlador lo reciba así
                },
                url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?listar_pisos_por_ubicacion=true',
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    let html = '';

                    // 1. Validar si hay datos
                    if (!Array.isArray(data) || data.length === 0) {
                        html = '<div class="p-3 small text-muted text-center">No hay pisos en esta ubicación.</div>';
                    } else {
                        data.forEach(function(p) {
                            // Usamos los alias definidos en tu consulta SQL (_id y nombre_piso)
                            html += `
                    <div class="floor-item" id="fl-${p._id}" onclick="seleccionarPiso(${p._id})">
                        <i class='bx bx-layer'></i>
                        <span>${p.nombre_piso}</span>
                        <span class="badge bg-secondary floor-badge">–</span>
                    </div>`;
                        });
                    }

                    // 2. Renderizar el HTML
                    $('#floor-list').html(html);

                    // 3. ¡IMPORTANTE! Mostrar la interfaz del sidebar y cambiar vistas
                    $('#sidebar-col').show();
                    $('#content-col').removeClass('col-md-12').addClass('col-md-10');

                    $('#view-locations').hide(); // Oculta la cuadrícula de edificios
                    $('#view-spaces').show(); // Muestra la cuadrícula de espacios/pisos
                },
                error: function(xhr) {
                    console.error("Error al cargar pisos:", xhr.responseText);
                    $('#floor-list').html('<div class="p-3 small text-danger">Error de conexión.</div>');
                }
            });
        }

        /* ════════════════════════════════════════
               SELECCIONAR PISO
            ════════════════════════════════════════ */
        window.seleccionarPiso = function(id) {
            selPiso = id;
            selTipo = null;

            $('.floor-item').removeClass('active');
            $('#fl-' + id).addClass('active');

            // Si tienes estas funciones definidas, se ejecutan para actualizar la vista
            if (typeof setStep === "function") setStep(3);
            if (typeof cargarTipos === "function") cargarTipos();
            if (typeof renderEspacios === "function") renderEspacios();
        };
        /* ════════════════════════════════════════
       STEP 3 – TIPOS DE ESPACIO (tabs)
   ════════════════════════════════════════ */
        function cargarTipos() {


            $.ajax({
                data: {
                    id_ubicacion: selUbicacion,
                    id_piso: selPiso
                },
                url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?listar_tipos_por_ubicacion_piso=true',
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    // Siempre incluimos la opción "Todos"
                    let html = `
                <div class="type-tab active" id="tt-0" onclick="seleccionarTipo(0)">
                    <i class='bx bx-grid-alt'></i> Todos
                </div>`;

                    if (data && data.length > 0) {
                        data.forEach(function(t) {
                            html += `
                    <div class="type-tab" id="tt-${t.id_tipo_espacio}" onclick="seleccionarTipo(${t.id_tipo_espacio})">
                        <i class='bx bx-category'></i> ${t.nombre}
                    </div>`;
                        });
                    }

                    $('#type-tabs').html(html);
                },
                error: function(xhr) {
                    console.error("Error al cargar tipos:", xhr.responseText);
                }
            });
        }

        window.seleccionarTipo = function(id) {
            selTipo = (id === 0) ? null : id;

            $('.type-tab').removeClass('active');
            $('#tt-' + id).addClass('active');

            if (typeof setStep === "function") setStep(4);

            // Aquí es donde finalmente se filtran los espacios
            renderEspacios();
        };

        /* ════════════════════════════════════════
           STEP 4 – ESPACIOS
        ════════════════════════════════════════ */
        function renderEspacios() {
            if (!selPiso) {
                $('#space-cards').html('');
                return;
            }
            $('#space-cards').html('<div class="col-12 text-center py-3 text-muted"><div class="spinner-border spinner-border-sm me-2"></div> Cargando espacios...</div>');

            $.post(URL_ESPACIOS + '?listar=true', {}, function(data) {
                if (!data || data.length === 0) {
                    $('#space-cards').html('<div class="col-12 text-center text-muted py-4"><i class="bx bx-folder-open fs-1"></i><p class="mt-2">No hay espacios disponibles.</p></div>');
                    return;
                }

                // Filtrar por piso y (si aplica) por tipo
                let lista = data.filter(function(e) {
                    return parseInt(e.id_numero_piso) === parseInt(selPiso);
                });
                if (selTipo) {
                    lista = lista.filter(function(e) {
                        return parseInt(e.id_tipo_espacio) === parseInt(selTipo);
                    });
                }

                if (lista.length === 0) {
                    $('#space-cards').html('<div class="col-12 text-center text-muted py-4"><i class="bx bx-folder-open fs-1"></i><p class="mt-2">No hay espacios para estos filtros.</p></div>');
                    return;
                }

                let html = '';
                lista.forEach(function(e) {
                    const img = e.imagen ?
                        `<img src="${e.imagen}" alt="${e.nombre}" style="width:100%;height:130px;object-fit:cover;">` :
                        `<div class="space-img-placeholder"><i class='bx bx-building-house'></i></div>`;

                    html += `
                <div class="col-md-4 col-sm-6">
                    <div class="space-card" id="sc-${e._id}">
                        <div onclick="window.toggleEspacio(${e._id})">${img}</div>
                        <div class="p-3" onclick="window.toggleEspacio(${e._id})">
                            <div class="fw-bold mb-1">${e.nombre}</div>
                            <div class="small text-muted d-flex gap-3">
                                <span><i class='bx bx-user me-1'></i>${e.capacidad} personas</span>
                                <span><i class='bx bx-hash me-1'></i>${e.codigo}</span>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class='bx bx-category me-1'></i>${e.nombre_tipo_espacio ?? ''}
                            </div>
                        </div>
                        <div class="space-detail" id="sd-${e._id}">
                            <div class="p-3">
                                <div class="d-flex gap-2 mb-2 flex-wrap">
                                    <span class="badge bg-success"><i class='bx bx-time-five me-1'></i>$${parseFloat(e.tarifa_hora).toFixed(2)}/hora</span>
                                    <span class="badge bg-primary"><i class='bx bx-sun me-1'></i>$${parseFloat(e.tarifa_dia).toFixed(2)}/día</span>
                                </div>
                                <div class="small text-muted mb-2">
                                    <i class='bx bx-map-pin me-1'></i>${e.nombre_ubicacion ?? ''} &nbsp;·&nbsp;
                                    <i class='bx bx-layer me-1'></i>${e.descripcion_numero_piso ?? ''}
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-success btn-sm"
                                        onclick="window.abrirReserva(${e._id}, '${e.nombre}', '${e.codigo}')">
                                        <i class='bx bx-calendar-plus me-1'></i>Reservar este espacio
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
                });
                $('#space-cards').html(html);
            }, 'json');
        }

        window.toggleEspacio = function(id) {
            const det = $('#sd-' + id);
            const card = $('#sc-' + id);
            const isOpen = det.hasClass('open');
            $('.space-detail.open').removeClass('open');
            $('.space-card.expanded').removeClass('expanded');
            if (!isOpen) {
                det.addClass('open');
                card.addClass('expanded');
            }
        };

        /* ════════════════════════════════════════
           VOLVER A UBICACIONES
        ════════════════════════════════════════ */
        window.goBackToLocations = function() {
            selPiso = null;
            selTipo = null;
            $('#view-spaces').hide();
            $('#view-locations').show();
            $('#sidebar-col').hide();
            $('#content-col').removeClass('col-md-10').addClass('col-md-12');
            setStep(1);
        };

        /* ════════════════════════════════════════
           CALENDARIO
        ════════════════════════════════════════ */
        window.abrirReserva = function(id, nombre, codigo) {
            activeEspacioId = id;
            $('#modal-space-name').text(nombre);
            $('#modal-space-sub').text(codigo);
            calDate = new Date();
            calSelStart = null;
            calSelEnd = null;
            renderCalendar();
            $('#inp-fecha-dia,#inp-fecha-ini,#inp-fecha-fin').val('');
            $('#resumen-reserva').text('Selecciona una fecha para ver el resumen.');
            $('#turnos-dia').hide();
            new bootstrap.Modal(document.getElementById('modalReserva')).show();
        };

        window.calPrev = function() {
            calDate.setMonth(calDate.getMonth() - 1);
            renderCalendar();
        };
        window.calNext = function() {
            calDate.setMonth(calDate.getMonth() + 1);
            renderCalendar();
        };

        function renderCalendar() {
            $('#cal-month-label').text(MONTHS[calDate.getMonth()] + ' ' + calDate.getFullYear());
            const grid = document.getElementById('cal-grid');
            const heads = Array.from(grid.querySelectorAll('.hub-cal-head'));
            grid.innerHTML = '';
            heads.forEach(h => grid.appendChild(h));

            // Reconstruir cabeceras si no existen
            if (!grid.querySelector('.hub-cal-head')) {
                ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'].forEach(function(d) {
                    const h = document.createElement('div');
                    h.className = 'hub-cal-head';
                    h.textContent = d;
                    grid.appendChild(h);
                });
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const year = calDate.getFullYear(),
                month = calDate.getMonth();
            let startDow = (new Date(year, month, 1).getDay() + 6) % 7;
            const days = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < startDow; i++) {
                const e = document.createElement('div');
                e.className = 'hub-cal-day empty';
                grid.appendChild(e);
            }

            for (let d = 1; d <= days; d++) {
                const date = new Date(year, month, d);
                const el = document.createElement('div');
                el.className = 'hub-cal-day';
                el.textContent = d;

                if (date < today) {
                    el.classList.add('past');
                } else {
                    if (date.getTime() === today.getTime()) el.classList.add('today');
                    el.classList.add('has-shift');
                    if (RESERVAS[fmtDate(date)]) el.classList.add('reserved');

                    if (calSelStart && calSelEnd) {
                        if (date >= calSelStart && date <= calSelEnd) el.classList.add('in-range');
                        if (date.getTime() === calSelStart.getTime()) {
                            el.classList.remove('in-range');
                            el.classList.add('range-start');
                        }
                        if (date.getTime() === calSelEnd.getTime()) {
                            el.classList.remove('in-range');
                            el.classList.add('range-end');
                        }
                    } else if (calSelStart && date.getTime() === calSelStart.getTime()) {
                        el.classList.add('selected');
                    }

                    el.onclick = function() {
                        selectCalDay(date);
                    };
                }
                grid.appendChild(el);
            }
        }

        // Asegurar cabeceras al abrir el modal
        $('#modalReserva').on('shown.bs.modal', function() {
            const grid = document.getElementById('cal-grid');
            if (!grid.querySelector('.hub-cal-head')) {
                ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'].forEach(function(d) {
                    const h = document.createElement('div');
                    h.className = 'hub-cal-head';
                    h.textContent = d;
                    grid.prepend(h);
                });
                renderCalendar();
            }
        });

        function selectCalDay(date) {
            const tipo = $('input[name="tipo_reserva"]:checked').val();
            if (tipo === 'dia') {
                calSelStart = date;
                calSelEnd = null;
                $('#inp-fecha-dia').val(fmtDate(date));
                mostrarTurnosDia(date);
                updateResumen();
            } else {
                if (!calSelStart || (calSelStart && calSelEnd)) {
                    calSelStart = date;
                    calSelEnd = null;
                    $('#inp-fecha-ini').val(fmtDate(date));
                    $('#inp-fecha-fin').val('');
                } else {
                    if (date >= calSelStart) {
                        calSelEnd = date;
                        $('#inp-fecha-fin').val(fmtDate(date));
                    } else {
                        calSelEnd = calSelStart;
                        calSelStart = date;
                        $('#inp-fecha-ini').val(fmtDate(date));
                        $('#inp-fecha-fin').val(fmtDate(calSelEnd));
                    }
                    updateResumen();
                }
            }
            renderCalendar();
        }

        function mostrarTurnosDia(date) {
            if (!activeEspacioId) return;
            $.post(URL_TURNOS + '?listar=true', {
                _id: activeEspacioId
            }, function(data) {
                let html = '';
                if (!data || data.length === 0) {
                    html = '<span class="text-muted small">Sin turnos asignados.</span>';
                } else {
                    data.forEach(function(t) {
                        html += `<span class="badge me-1 mb-1" style="background:#2d6a4f;">${t.nombre ?? t.hub_tur_id}: ${t.entrada ?? ''} – ${t.salida ?? ''}</span>`;
                    });
                }
                $('#turnos-dia-list').html(html);
                $('#turnos-dia').show();
            }, 'json').fail(function() {
                $('#turnos-dia').hide();
            });
        }

        function updateResumen() {
            const tipo = $('input[name="tipo_reserva"]:checked').val();
            if (tipo === 'dia' && calSelStart) {
                $('#resumen-reserva').html(
                    '<strong>Fecha:</strong> ' +
                    calSelStart.toLocaleDateString('es-EC', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) +
                    '<br>Elige un turno y confirma.'
                );
            } else if (tipo === 'rango' && calSelStart && calSelEnd) {
                const days = Math.round((calSelEnd - calSelStart) / (1000 * 60 * 60 * 24)) + 1;
                $('#resumen-reserva').html(
                    '<strong>Desde:</strong> ' + fmtDate(calSelStart) +
                    '<br><strong>Hasta:</strong> ' + fmtDate(calSelEnd) +
                    '<br><strong>Días:</strong> ' + days
                );
            }
        }

        window.syncRange = function() {
            const ini = $('#inp-fecha-ini').val(),
                fin = $('#inp-fecha-fin').val();
            if (ini) calSelStart = new Date(ini + 'T00:00:00');
            if (fin) calSelEnd = new Date(fin + 'T00:00:00');
            if (ini && fin) updateResumen();
            renderCalendar();
        };

        window.toggleTipoReserva = function() {
            const tipo = $('input[name="tipo_reserva"]:checked').val();
            $('#opt-dia').toggle(tipo === 'dia');
            $('#opt-rango').toggle(tipo === 'rango');
            calSelStart = null;
            calSelEnd = null;
            $('#turnos-dia').hide();
            renderCalendar();
        };

        window.confirmarReserva = function() {
            const tipo = $('input[name="tipo_reserva"]:checked').val();
            let ok = false;
            if (tipo === 'dia' && calSelStart) {
                RESERVAS[fmtDate(calSelStart)] = activeEspacioId;
                ok = true;
            } else if (tipo === 'rango' && calSelStart && calSelEnd) {
                let d = new Date(calSelStart);
                while (d <= calSelEnd) {
                    RESERVAS[fmtDate(d)] = activeEspacioId;
                    d.setDate(d.getDate() + 1);
                }
                ok = true;
            }
            if (!ok) {
                Swal.fire('', 'Selecciona una fecha primero.', 'warning');
                return;
            }
            renderCalendar();
            bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide();
            Swal.fire('', '¡Reserva registrada con éxito!', 'success');
        };

        function fmtDate(d) {
            return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
        }

        /* ════════════════════════════════════════
           INIT
        ════════════════════════════════════════ */
        setStep(1);
        cargarUbicaciones();
    });
</script>