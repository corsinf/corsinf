<?php
$modulo_sistema = $_SESSION['INICIO']['MODULO_SISTEMA'];
$id_usuario_sesion = $_SESSION['INICIO']['ID_USUARIO'] ?? 0;
?>

<script>
    $(document).ready(function() {
        cargar_selects_clientes();
    });

    function cargar_selects_clientes() {
        cargar_select2_url('ddl_clientes',
            '../controlador/GENERAL/NO_CONCURRENTES/CLIENTESC.php?buscar_clientes=true',
            '', '#modalReserva');
    }
</script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">RESERVAS</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active">Hub · Espacios</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">

                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas"
                            class="btn btn-outline-dark btn-sm mb-3">
                            <i class="bx bx-arrow-back"></i> Regresar
                        </a>

                        <!-- Step bar -->
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                            <span class="badge bg-primary px-3 py-2" id="st1"><i class='bx bx-map me-1'></i>Ubicación</span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st2"><i class='bx bx-layer me-1'></i>Piso</span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st3"><i class='bx bx-category me-1'></i>Tipo</span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st4"><i class='bx bx-door-open me-1'></i>Espacio</span>
                        </div>

                        <div class="row g-3">
                            <!-- Sidebar pisos -->
                            <div class="col-md-2" id="sidebar-col" style="display:none;">
                                <div class="card border mb-0 h-100">
                                    <div class="card-header py-2 bg-light">
                                        <small class="fw-bold text-muted text-uppercase">
                                            <i class='bx bx-layer me-1'></i>Pisos
                                        </small>
                                    </div>
                                    <div class="list-group list-group-flush" id="floor-list"></div>
                                </div>
                            </div>

                            <!-- Content area -->
                            <div id="content-col" class="col-md-12">

                                <!-- Step 1: Ubicaciones -->
                                <div id="view-locations">
                                    <p class="text-muted small fw-semibold text-uppercase mb-2">
                                        <i class='bx bx-map-pin me-1'></i>Selecciona una ubicación
                                    </p>
                                    <div class="row g-3" id="loc-cards">
                                        <div class="col-12 text-center py-4 text-muted">
                                            <div class="spinner-border spinner-border-sm me-2"></div>Cargando ubicaciones...
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2+: Espacios -->
                                <div id="view-spaces" style="display:none;">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="goBackToLocations()">
                                            <i class='bx bx-arrow-back'></i> Volver
                                        </button>
                                        <p class="text-muted small fw-semibold text-uppercase mb-0">
                                            <i class='bx bx-category me-1'></i>Tipo de espacio
                                        </p>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap mb-3" id="type-tabs"></div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-2">
                                        <i class='bx bx-grid-alt me-1'></i>Espacios disponibles
                                    </p>
                                    <div class="row g-3" id="space-cards"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- ════════════════════════════════════
     MODAL 1: GALERÍA
════════════════════════════════════ -->
<div class="modal fade" id="modalGaleria" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark text-white">

            <div class="modal-header border-secondary pb-0 px-4 pt-3">
                <div>
                    <h6 class="modal-title fw-bold text-white" id="gal-nombre"></h6>
                    <small class="text-secondary" id="gal-codigo"></small>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <ul class="nav nav-tabs px-4 pt-2 border-secondary" id="gal-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active text-secondary" id="tab-fotos-btn"
                            data-bs-toggle="tab" data-bs-target="#panel-fotos">
                            <i class='bx bx-image me-1'></i>Fotos
                            <span class="badge bg-secondary ms-1" id="gal-fotos-count">0</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-secondary" id="tab-videos-btn"
                            data-bs-toggle="tab" data-bs-target="#panel-videos">
                            <i class='bx bx-video me-1'></i>Videos
                            <span class="badge bg-secondary ms-1" id="gal-videos-count">0</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active p-3" id="panel-fotos">
                        <div id="carousel-fotos" class="carousel slide rounded overflow-hidden mb-3"
                            style="background:#000; height:380px;">
                            <div class="carousel-inner h-100" id="carousel-fotos-inner"></div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carousel-fotos" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carousel-fotos" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                            <div class="carousel-indicators" id="gal-indicators"></div>
                        </div>
                        <div class="d-flex gap-2 overflow-auto pb-1" id="gal-thumbs"></div>
                        <div id="gal-fotos-empty" class="text-center py-5 text-secondary" style="display:none;">
                            <i class='bx bx-image fs-1 d-block mb-2'></i>
                            <p class="small mb-0">Sin imágenes cargadas</p>
                        </div>
                    </div>
                    <div class="tab-pane fade p-3" id="panel-videos">
                        <div class="row g-3" id="gal-videos-list"></div>
                        <div id="gal-videos-empty" class="text-center py-5 text-secondary" style="display:none;">
                            <i class='bx bx-video-off fs-1 d-block mb-2'></i>
                            <p class="small mb-0">Sin videos cargados</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- ════════════════════════════════════
     MODAL 2: SELECCIÓN DE TARIFA
════════════════════════════════════ -->
<div class="modal fade" id="modalTarifas" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h6 class="modal-title fw-bold"><i class='bx bx-money me-1 text-primary'></i>Elige un plan</h6>
                    <small class="text-muted" id="tar-sub">Selecciona la tarifa para continuar</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-3 py-2">
                <div id="tar-loading" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm me-2"></div>Cargando planes...
                </div>
                <div id="tar-empty" class="text-center py-4" style="display:none;">
                    <i class='bx bx-info-circle fs-2 d-block mb-2 text-warning'></i>
                    <p class="small mb-0">Este espacio no tiene planes configurados.</p>
                    <p class="small text-muted">Contacta al administrador.</p>
                </div>
                <div id="tar-lista" class="list-group list-group-flush" style="display:none;"></div>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-sm btn-primary" id="btn-continuar-reserva" disabled>
                    Continuar <i class='bx bx-chevron-right'></i>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ════════════════════════════════════
     MODAL 3: FORMULARIO DE RESERVA
════════════════════════════════════ -->
<div class="modal fade" id="modalReserva" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header py-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="res-space-nombre">Reservar espacio</h6>
                        <small class="text-muted" id="res-space-codigo"></small>
                    </div>
                    <span class="badge bg-primary ms-1" id="res-tarifa-badge"></span>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <!-- Izquierda: Calendario -->
                    <div class="col-md-7">
                        <p class="text-muted small fw-semibold text-uppercase mb-2">
                            <i class='bx bx-calendar me-1'></i>
                            <span id="res-cal-label">Selecciona una fecha</span>
                        </p>

                        <!-- Fechas inicio y fin en la misma fila -->
                        <div class="d-flex align-items-start gap-2 mb-2 flex-wrap">
                            <div>
                                <label class="form-label small text-muted mb-1">
                                    <i class='bx bx-calendar-plus me-1'></i>Fecha inicio
                                </label>
                                <input type="date" class="form-control form-control-sm"
                                    id="inp-fecha-directa" style="max-width:170px;">
                                <div class="text-danger small mt-1" id="err-fecha-directa" style="min-height:16px;"></div>
                            </div>
                            <div id="grupo-fecha-fin" style="display:none;">
                                <label class="form-label small text-muted mb-1">
                                    <i class='bx bx-calendar-check me-1'></i>Fecha fin
                                </label>
                                <input type="date" class="form-control form-control-sm bg-light"
                                    id="inp-fecha-fin" style="max-width:170px;" disabled readonly>
                                <div class="text-muted small mt-1" id="lbl-fecha-fin-info" style="min-height:16px;"></div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <button class="btn btn-sm btn-outline-secondary" id="btn-res-prev">
                                <i class='bx bx-chevron-left'></i>
                            </button>
                            <strong id="res-month-label"></strong>
                            <button class="btn btn-sm btn-outline-secondary" id="btn-res-next">
                                <i class='bx bx-chevron-right'></i>
                            </button>
                        </div>
                        <div id="res-cal-grid" class="hub-cal-grid"></div>

                        <!-- Hora inicio (solo HORA o cuando hay turno) -->
                        <div id="seccion-hora" class="mt-3" style="display:none;">
                            <label class="form-label small fw-semibold">
                                <i class='bx bx-time me-1 text-primary'></i>Hora de inicio
                            </label>
                            <input type="time" class="form-control form-control-sm" id="inp-hora-inicio"
                                value="08:00" style="max-width:160px;">
                            <div id="label-hora-fin" class="mt-2 small"></div>
                        </div>

                        <!-- Turno del día seleccionado (solo tarifa por FECHA) -->
                        <div id="seccion-turno-dia" class="mt-3" style="display:none;">
                            <div class="card border-0 bg-primary bg-opacity-10 rounded-3">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                            id="turno-color-dot"
                                            style="width:34px;height:34px;background:#0d6efd;">
                                            <i class='bx bx-time text-white'></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small" id="turno-nombre-dia">—</div>
                                            <div class="small text-muted">
                                                <i class='bx bx-time-five me-1'></i>
                                                <span id="turno-horario-dia">—</span>
                                            </div>
                                        </div>
                                        <span class="ms-auto badge bg-success" id="turno-badge-dia">Turno activo</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted small mt-1">
                                <i class='bx bx-info-circle me-1'></i>Horario de atención para el día seleccionado
                            </div>
                        </div>

                        <!-- Mensaje cuando no hay turno para ese día -->
                        <div id="seccion-sin-turno" class="mt-3" style="display:none;">
                            <div class="alert alert-warning py-2 small mb-0">
                                <i class='bx bx-error-circle me-1'></i>
                                No hay turno asignado para el día seleccionado.
                            </div>
                        </div>
                    </div>

                    <!-- Derecha: Info tarifa + Cliente + Resumen -->
                    <div class="col-md-5">
                        <p class="text-muted small fw-semibold text-uppercase mb-2">
                            <i class='bx bx-receipt me-1'></i>Plan seleccionado
                        </p>
                        <div class="card border-primary mb-3">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold small" id="res-tarifa-nombre">—</div>
                                        <small class="text-muted" id="res-tarifa-detalle">—</small>
                                    </div>
                                    <span class="fs-6 fw-bold text-success" id="res-tarifa-precio">$0.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ddl_clientes" class="form-label small fw-semibold">
                                <i class='bx bx-user me-1'></i>Persona
                            </label>
                            <select class="form-select form-select-sm select2-validation"
                                id="ddl_clientes" name="ddl_clientes">
                                <option selected disabled>-- Seleccione --</option>
                            </select>
                        </div>

                        <div id="res-periodo" class="alert alert-info py-2 small mb-3" style="display:none;">
                            <i class='bx bx-calendar-check me-1'></i>
                            <span id="res-periodo-texto"></span>
                        </div>

                        <div id="res-total-box" class="d-flex justify-content-between align-items-center
                             rounded p-2 mb-3 border border-primary bg-opacity-10" style="display:none!important;">
                            <span class="small text-muted">Total</span>
                            <strong class="text-primary" id="res-total-valor">$0.00</strong>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-success" id="btn-confirmar">
                                <i class='bx bx-calendar-plus me-1'></i>Confirmar reserva
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ════════════════════════════════════
     ESTILOS
════════════════════════════════════ -->
<style>
    .hub-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px
    }

    .hub-cal-head {
        text-align: center;
        font-size: .7rem;
        font-weight: 600;
        color: #6c757d;
        padding: 4px 0;
        text-transform: uppercase
    }

    /* Domingo: color gris especial en cabecera */
    .hub-cal-head.domingo {
        color: #adb5bd;
    }

    .hub-cal-day {
        text-align: center;
        padding: 7px 2px;
        font-size: .82rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background .15s;
        user-select: none
    }

    .hub-cal-day:hover:not(.empty):not(.past):not(.domingo) {
        background: #f0f4ff
    }

    .hub-cal-day.empty,
    .hub-cal-day.past {
        color: #ccc;
        cursor: default
    }

    /* Domingo: visible pero no seleccionable */
    .hub-cal-day.domingo {
        color: #dee2e6;
        cursor: not-allowed;
        background: #f8f9fa;
    }

    .hub-cal-day.today {
        font-weight: 700;
        color: #0d6efd
    }

    .hub-cal-day.today.domingo {
        color: #adb5bd;
        font-weight: 600;
    }

    .hub-cal-day.has-shift::after {
        content: '';
        display: block;
        width: 4px;
        height: 4px;
        background: #52b788;
        border-radius: 50%;
        margin: 2px auto 0
    }

    .hub-cal-day.selected {
        background: #0d6efd !important;
        color: #fff !important;
        font-weight: 700
    }

    .hub-cal-day.selected::after {
        display: none
    }

    .hub-cal-day.in-range {
        background: rgba(13, 110, 253, .13) !important;
        color: #0d6efd;
        border-radius: 0
    }

    .hub-cal-day.in-range::after {
        display: none
    }

    .hub-cal-day.range-end {
        background: #0d6efd !important;
        color: #fff !important;
        font-weight: 700
    }

    .hub-cal-day.range-end::after {
        display: none
    }

    /* Thumbnails galería */
    .gal-thumb {
        width: 72px;
        height: 60px;
        border-radius: 6px;
        overflow: hidden;
        cursor: pointer;
        flex-shrink: 0;
        border: 2px solid transparent;
        transition: border-color .15s;
        opacity: .7
    }

    .gal-thumb:hover,
    .gal-thumb.active {
        border-color: #3b82f6;
        opacity: 1
    }

    .gal-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover
    }
</style>


<!-- ════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════ -->
<script>
    $(function() {

        /* ── Constante de sesión ── */
        var ID_USUARIO_SESION = <?= (int)$id_usuario_sesion ?>;

        var URL_UBICACIONES = '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php';
        var URL_ESPACIOS = '../controlador/HOST_TIME/ESPACIOS/espaciosC.php';
        var URL_MEDIA = '../controlador/HOST_TIME/ESPACIOS/hub_espacios_mediaC.php';
        var URL_TARIFAS = '../controlador/HOST_TIME/ESPACIOS/hub_espacios_tarifasC.php';
        var URL_RESERVAS = '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php';
        var URL_TURNOS_ESP = '../controlador/HOST_TIME/ESPACIOS/hub_espacios_turnosC.php';

        var MONTHS = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        /* Lu Ma Mi Ju Vi Sá Do  — índice 0=Lu … 6=Do */
        var DOW = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'];

        /* ── Estado global ── */
        var activeEspacioId = null;
        var activeEspacioNom = null;
        var activeEspacioCod = null;
        var activeTarifa = null;
        var resViewDate = new Date();
        var resSelected = null; // Date inicio
        var resEndDate = null; // Date fin calculada
        var selUbicacion = null;
        var selPiso = null;
        var selTipo = null;

        /* Turnos cargados para el espacio activo: array de {dia(0-6), nombre, hora_entrada(min), hora_salida(min), color} */
        var turnosEspacio = [];

        /* ════════════════════════════
           HELPERS
        ════════════════════════════ */
        function pad2(n) {
            return String(n).padStart(2, '0');
        }

        function fmtISO(d) {
            return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate());
        }

        function fmtDatetime(d, hh, mm, ss) {
            hh = hh !== undefined ? hh : 0;
            mm = mm !== undefined ? mm : 0;
            ss = ss !== undefined ? ss : 0;
            return fmtISO(d) + ' ' + pad2(hh) + ':' + pad2(mm) + ':' + pad2(ss);
        }

        function fromISO(s) {
            var p = s.split('-');
            return new Date(+p[0], +p[1] - 1, +p[2]);
        }

        function fmtHuman(d) {
            return pad2(d.getDate()) + ' de ' + MONTHS[d.getMonth()] + ' ' + d.getFullYear();
        }

        function todayMN() {
            var t = new Date();
            t.setHours(0, 0, 0, 0);
            return t;
        }

        /* Convierte minutos (como los guarda la BD) a "HH:MM" */
        function minToHora(min) {
            min = ((min % 1440) + 1440) % 1440;
            var h = Math.floor(min / 60);
            var m = min % 60;
            return pad2(h) + ':' + pad2(m);
        }

        /* Día JS (0=Dom … 6=Sáb) → índice de DOW (0=Lu … 6=Do) */
        function jsDayToIndex(jsDay) {
            return (jsDay + 6) % 7; // 0(Dom)→6, 1(Lu)→0, …, 6(Sáb)→5
        }

        /**
         * Calcula la fecha fin sumando 30 días hábiles (lunes-sábado) desde d.
         * El día de inicio cuenta como día 1.
         */
        function calcEndDate30Laborables(d) {
            var cur = new Date(d);
            var count = 1; // el día inicio es el día 1
            while (count < 30) {
                cur.setDate(cur.getDate() + 1);
                var dow = cur.getDay(); // 0=Dom
                if (dow !== 0) count++; // domingo no cuenta
            }
            return cur;
        }

        /**
         * Calcula fecha fin según tarifa (cantidad de meses * 30 días laborables).
         * Solo para unidad_tiempo !== 'HORA'.
         */
        function calcEndDate(d) {
            if (!activeTarifa || activeTarifa.unidad_tiempo === 'HORA') {
                return new Date(d); // Es el mismo día
            }

            var totalDias = activeTarifa.cantidad * 30;
            var cur = new Date(d);
            // Sumamos días directos (Calendario)
            cur.setDate(cur.getDate() + (totalDias - 1));
            return cur;
        }

        function escAttr(s) {
            return (s || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        /* ════════════════════════════
           STEP BAR
        ════════════════════════════ */
        function setStep(n) {
            for (var i = 1; i <= 4; i++) {
                $('#st' + i).removeClass('bg-primary bg-success bg-secondary')
                    .addClass(i < n ? 'bg-success' : i === n ? 'bg-primary' : 'bg-secondary');
            }
        }

        /* ════════════════════════════
           STEP 1: UBICACIONES
        ════════════════════════════ */
        function cargarUbicaciones() {
            $.post(URL_UBICACIONES + '?listar=true', {}, function(data) {
                var html = '';
                if (!data || !data.length) {
                    html = '<div class="col-12 text-center text-muted py-3">No hay ubicaciones.</div>';
                } else {
                    $.each(data, function(_, u) {
                        html +=
                            '<div class="col-md-4 col-sm-6">' +
                            '<div class="card h-100 border-start border-4 border-success border-top-0 border-end-0 border-bottom-0 loc-card shadow-sm" ' +
                            'style="cursor:pointer;transition:transform .2s,box-shadow .2s" data-id="' + u._id + '">' +
                            '<div class="card-body">' +
                            '<h6 class="fw-bold mb-2">' + u.nombre + '</h6>' +
                            '<div class="small text-muted mb-1"><i class="bx bx-map-pin me-1 text-success"></i>' + u.direccion + '</div>' +
                            '<div class="small text-muted"><i class="bx bx-phone me-1 text-success"></i>' + u.telefono + '</div>' +
                            '</div></div></div>';
                    });
                }
                $('#loc-cards').html(html);
            }, 'json');
        }

        $(document).on('mouseenter', '.loc-card', function() {
            $(this).css({
                'transform': 'translateY(-2px)',
                'box-shadow': '0 6px 18px rgba(0,0,0,.1)'
            });
        }).on('mouseleave', '.loc-card', function() {
            if (!$(this).hasClass('border-primary'))
                $(this).css({
                    'transform': '',
                    'box-shadow': ''
                });
        });

        $(document).on('click', '.loc-card', function() {
            $('.loc-card').removeClass('border-primary').addClass('border-success')
                .css({
                    'transform': '',
                    'box-shadow': ''
                });
            $(this).removeClass('border-success').addClass('border-primary')
                .css({
                    'transform': 'translateY(-2px)',
                    'box-shadow': '0 6px 18px rgba(13,110,253,.15)'
                });

            selUbicacion = $(this).data('id');
            selPiso = null;
            selTipo = null;
            resetearSeleccion();
            $('#space-cards').html('');
            $('#type-tabs').html('');
            setStep(2);
            cargarPisos(selUbicacion);
        });

        /* ════════════════════════════
           STEP 2: PISOS
        ════════════════════════════ */
        function cargarPisos(id_ubicacion) {
            $.ajax({
                url: URL_ESPACIOS + '?listar_pisos_por_ubicacion=true',
                type: 'post',
                dataType: 'json',
                data: {
                    id: id_ubicacion
                },
                success: function(data) {
                    var html = '';
                    if (!Array.isArray(data) || !data.length) {
                        html = '<div class="list-group-item text-muted small text-center">Sin pisos.</div>';
                    } else {
                        $.each(data, function(_, p) {
                            html +=
                                '<button type="button" class="list-group-item list-group-item-action d-flex align-items-center gap-2 floor-item" ' +
                                'data-id="' + p._id + '">' +
                                '<i class="bx bx-layer text-primary"></i><span>' + p.nombre_piso + '</span>' +
                                '</button>';
                        });
                    }
                    $('#floor-list').html(html);
                    $('#sidebar-col').show();
                    $('#content-col').removeClass('col-md-12').addClass('col-md-10');
                    $('#view-locations').hide();
                    $('#view-spaces').show();
                }
            });
        }

        $(document).on('click', '.floor-item', function() {
            $('.floor-item').removeClass('active');
            $(this).addClass('active');
            selPiso = $(this).data('id');
            selTipo = null;
            resetearSeleccion();
            $('#space-cards').html('');
            $('.type-tab').removeClass('active');
            setStep(3);
            cargarTipos();
            renderEspacios();
        });

        /* ════════════════════════════
           STEP 3: TIPOS
        ════════════════════════════ */
        function cargarTipos() {
            $.ajax({
                url: URL_ESPACIOS + '?listar_tipos_por_ubicacion_piso=true',
                type: 'post',
                dataType: 'json',
                data: {
                    id_ubicacion: selUbicacion,
                    id_piso: selPiso
                },
                success: function(data) {
                    var html =
                        '<button class="btn btn-primary btn-sm rounded-pill type-tab" data-tipo="0">' +
                        '<i class="bx bx-grid-alt me-1"></i>Todos</button>';
                    $.each(data || [], function(_, t) {
                        html +=
                            '<button class="btn btn-outline-secondary btn-sm rounded-pill type-tab" data-tipo="' + t.id_tipo_espacio + '">' +
                            '<i class="bx bx-category me-1"></i>' + t.nombre + '</button>';
                    });
                    $('#type-tabs').html(html);
                }
            });
        }

        $(document).on('click', '.type-tab', function() {
            selTipo = $(this).data('tipo') == 0 ? null : $(this).data('tipo');
            $('.type-tab').removeClass('btn-primary').addClass('btn-outline-secondary');
            $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
            setStep(4);
            renderEspacios();
        });

        /* ════════════════════════════
           STEP 4: ESPACIOS
        ════════════════════════════ */
        function renderEspacios() {
            if (!selPiso) {
                $('#space-cards').html('');
                return;
            }

            $('#space-cards').html(
                '<div class="col-12 text-center py-3 text-muted">' +
                '<div class="spinner-border spinner-border-sm me-2"></div>Cargando...</div>'
            );

            $.ajax({
                url: URL_ESPACIOS + '?listar_por_ubicacion_piso=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_ubicacion: selUbicacion,
                    id_piso: selPiso
                },
                success: function(data) {
                    var lista = data || [];
                    if (selTipo) {
                        lista = $.grep(lista, function(e) {
                            return parseInt(e.id_tipo_espacio) === parseInt(selTipo);
                        });
                    }
                    if (!lista.length) {
                        $('#space-cards').html('<div class="col-12 text-center text-muted py-4">No hay espacios disponibles.</div>');
                        return;
                    }
                    var html = '';
                    $.each(lista, function(_, e) {
                        var imgWrap = e.imagen ?
                            '<img src="' + e.imagen + '" alt="' + e.nombre + '" class="card-img-top" style="height:140px;object-fit:cover;" loading="lazy">' :
                            '<div class="d-flex align-items-center justify-content-center bg-success bg-opacity-25 text-success" style="height:140px;font-size:2.5rem;"><i class="bx bx-building-house"></i></div>';

                        html +=
                            '<div class="col-md-4 col-sm-6">' +
                            '<div class="card h-100 shadow-sm border">' +
                            imgWrap +
                            '<div class="card-body p-3">' +
                            '<div class="fw-bold mb-1">' + e.nombre + '</div>' +
                            '<div class="small text-muted mb-1">' +
                            '<i class="bx bx-category me-1"></i>' + (e.nombre_tipo_espacio || '—') +
                            ' &nbsp;·&nbsp; <i class="bx bx-hash me-1"></i>' + e.codigo +
                            '</div>' +
                            '<div class="small text-muted mb-3">' +
                            '<i class="bx bx-user me-1"></i>' + e.capacidad_minima + ' - ' + e.capacidad_maxima + ' personas' +
                            '</div>' +
                            '<div class="d-flex gap-2">' +
                            '<button class="btn btn-outline-secondary btn-sm flex-fill btn-galeria"' +
                            ' data-id="' + e._id + '" data-nombre="' + e.nombre + '" data-codigo="' + e.codigo + '">' +
                            '<i class="bx bx-images me-1"></i>Galería</button>' +
                            '<button class="btn btn-primary btn-sm flex-fill btn-pre-reservar"' +
                            ' data-id="' + e._id + '" data-nombre="' + e.nombre + '" data-codigo="' + e.codigo + '">' +
                            '<i class="bx bx-calendar-plus me-1"></i>Reservar</button>' +
                            '</div>' +
                            '</div></div></div>';
                    });
                    $('#space-cards').html(html);
                },
                error: function() {
                    $('#space-cards').html('<div class="col-12 text-center text-danger py-4">Error al cargar espacios.</div>');
                }
            });
        }

        window.goBackToLocations = function() {
            selPiso = null;
            selTipo = null;
            $('#view-spaces').hide();
            $('#view-locations').show();
            $('#sidebar-col').hide();
            $('#content-col').removeClass('col-md-10').addClass('col-md-12');
            setStep(1);
        };

        /* ════════════════════════════
           MODAL GALERÍA
        ════════════════════════════ */
        $(document).on('click', '.btn-galeria', function() {
            var id = $(this).data('id');
            $('#gal-nombre').text($(this).data('nombre'));
            $('#gal-codigo').text($(this).data('codigo'));
            $('#carousel-fotos-inner,#gal-indicators,#gal-thumbs,#gal-videos-list').html('');
            $('#gal-fotos-empty,#gal-videos-empty').hide();
            $('#gal-fotos-count,#gal-videos-count').text('0');
            $('#tab-fotos-btn').tab('show');

            $.ajax({
                url: URL_MEDIA + '?listar=true',
                type: 'post',
                dataType: 'json',
                data: {
                    id_espacio: id
                },
                success: function(data) {
                    var fotos = (data || []).filter(function(m) {
                        return m.tipo === 'imagen';
                    });
                    var videos = (data || []).filter(function(m) {
                        return m.tipo === 'video';
                    });
                    fotos.sort(function(a, b) {
                        return (b.es_principal || 0) - (a.es_principal || 0);
                    });

                    $('#gal-fotos-count').text(fotos.length);
                    $('#gal-videos-count').text(videos.length);

                    if (!fotos.length) {
                        $('#gal-fotos-empty').show();
                    } else {
                        var carHtml = '',
                            indHtml = '',
                            thumbHtml = '';
                        fotos.forEach(function(f, i) {
                            var act = i === 0 ? 'active' : '';
                            carHtml +=
                                '<div class="carousel-item h-100 ' + act + '">' +
                                '<img src="' + f.url_archivo + '" class="d-block w-100 h-100" style="object-fit:contain;" alt="' + escAttr(f.nombre_archivo) + '">' +
                                (f.es_principal == 1 ? '<div class="carousel-caption d-none d-md-block pb-1"><span class="badge bg-warning text-dark"><i class="bx bxs-star me-1"></i>Principal</span></div>' : '') +
                                '</div>';
                            indHtml +=
                                '<button type="button" data-bs-target="#carousel-fotos" data-bs-slide-to="' + i + '" class="' + act + '"' +
                                (i === 0 ? ' aria-current="true"' : '') + ' aria-label="Foto ' + i + '"></button>';
                            thumbHtml +=
                                '<div class="gal-thumb' + (i === 0 ? ' active' : '') + '" data-idx="' + i + '">' +
                                '<img src="' + f.url_archivo + '" alt="" loading="lazy"></div>';
                        });
                        $('#carousel-fotos-inner').html(carHtml);
                        $('#gal-indicators').html(indHtml);
                        $('#gal-thumbs').html(thumbHtml);
                    }

                    if (!videos.length) {
                        $('#gal-videos-empty').show();
                    } else {
                        var vidHtml = '';
                        videos.forEach(function(v) {
                            vidHtml +=
                                '<div class="col-md-6"><div class="card border">' +
                                '<div class="ratio ratio-16x9">' +
                                '<video controls preload="metadata" style="background:#000;object-fit:contain;">' +
                                '<source src="' + v.url_archivo + '" type="video/' + v.formato + '"></video></div>' +
                                '<div class="card-footer py-1 px-2"><small class="text-muted text-truncate d-block">' +
                                '<i class="bx bx-video me-1"></i>' + v.nombre_archivo + '</small></div>' +
                                '</div></div>';
                        });
                        $('#gal-videos-list').html(vidHtml);
                    }
                }
            });
            $('#modalGaleria').modal('show');
        });

        $(document).on('click', '.gal-thumb', function() {
            var idx = $(this).data('idx');
            $('#carousel-fotos').carousel(idx);
            $('.gal-thumb').removeClass('active');
            $(this).addClass('active');
        });
        $('#carousel-fotos').on('slid.bs.carousel', function(e) {
            $('.gal-thumb').removeClass('active');
            $('.gal-thumb[data-idx="' + e.to + '"]').addClass('active');
        });
        $('#modalGaleria').on('hide.bs.modal', function() {
            $(this).find('video').each(function() {
                this.pause();
                this.currentTime = 0;
            });
        });

        /* ════════════════════════════
           MODAL TARIFAS
        ════════════════════════════ */
        $(document).on('click', '.btn-pre-reservar', function() {
            activeEspacioId = $(this).data('id');
            activeEspacioNom = $(this).data('nombre');
            activeEspacioCod = $(this).data('codigo');
            activeTarifa = null;
            turnosEspacio = [];

            $('#tar-sub').text(activeEspacioNom);
            $('#tar-lista').html('').hide();
            $('#tar-empty').hide();
            $('#tar-loading').show();
            $('#btn-continuar-reserva').prop('disabled', true);

            $.ajax({
                url: URL_TARIFAS + '?listar=true',
                type: 'post',
                dataType: 'json',
                data: {
                    id_espacio: activeEspacioId
                },
                success: function(data) {
                    $('#tar-loading').hide();
                    if (!data || !data.length) {
                        $('#tar-empty').show();
                        return;
                    }
                    renderListaTarifas(data);
                    $('#tar-lista').show();
                },
                error: function() {
                    $('#tar-loading').hide();
                    $('#tar-empty').show();
                }
            });

            /* Cargar turnos del espacio en paralelo */
            cargarTurnosEspacio(activeEspacioId);

            $('#modalTarifas').modal('show');
        });

        /* ── Carga todos los turnos del espacio (todos los días) ── */
        function cargarTurnosEspacio(id_espacio) {
            $.ajax({
                url: URL_TURNOS_ESP + '?listar=true',
                type: 'post',
                dataType: 'json',
                data: {
                    id_espacio: id_espacio
                },
                success: function(data) {
                    turnosEspacio = data || [];
                }
            });
        }

        /**
         * Dado el día JS de una fecha (0=Dom…6=Sáb),
         * devuelve el turno asignado o null.
         * La BD guarda hub_tuh_dia con índice JS (0=Dom, 1=Lu…6=Sáb).
         */
        function getTurnoPorDia(jsDay) {
            if (!turnosEspacio.length) return null;
            var found = null;
            turnosEspacio.forEach(function(t) {
                if (parseInt(t.hub_tuh_dia) === jsDay) found = t;
            });
            return found;
        }

        function renderListaTarifas(tarifas) {
            var html = '';
            tarifas.forEach(function(t) {
                var unidad = t.unidad_tiempo === 'HORA' ? 'hora' : 'mes';
                var duracion = t.cantidad > 1 ? t.cantidad + ' ' + unidad + 's' : '1 ' + unidad;
                var icono = t.unidad_tiempo === 'HORA' ? 'bx-time' : 'bx-calendar';
                html +=
                    '<button type="button" class="list-group-item list-group-item-action tar-item py-2 px-3"' +
                    ' data-id="' + t._id + '" data-nombre="' + escAttr(t.nombre_plan) + '"' +
                    ' data-precio="' + t.precio + '" data-cantidad="' + t.cantidad + '" data-unidad="' + t.unidad_tiempo + '">' +
                    '<div class="d-flex align-items-center gap-3">' +
                    '<div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"' +
                    ' style="width:38px;height:38px;"><i class="bx ' + icono + ' text-primary"></i></div>' +
                    '<div class="flex-grow-1">' +
                    '<div class="fw-semibold small">' + t.nombre_plan + '</div>' +
                    '<small class="text-muted"><i class="bx bx-time-five me-1"></i>' + duracion + ' por reserva</small>' +
                    '</div>' +
                    '<span class="fw-bold text-success fs-6">$' + parseFloat(t.precio).toFixed(2) + '</span>' +
                    '</div></button>';
            });
            $('#tar-lista').html(html);
        }

        $(document).on('click', '.tar-item', function() {
            $('.tar-item').removeClass('active');
            $(this).addClass('active');
            activeTarifa = {
                _id: $(this).data('id'),
                nombre_plan: $(this).data('nombre'),
                precio: parseFloat($(this).data('precio')),
                cantidad: parseInt($(this).data('cantidad')),
                unidad_tiempo: $(this).data('unidad')
            };
            $('#btn-continuar-reserva').prop('disabled', false);
        });

        $('#btn-continuar-reserva').on('click', function() {
            $('#modalTarifas').modal('hide');
            abrirModalReserva();
        });

        /* ════════════════════════════
           MODAL RESERVA
        ════════════════════════════ */
        function abrirModalReserva() {
            resViewDate = new Date();
            resViewDate.setHours(0, 0, 0, 0);
            resSelected = null;
            resEndDate = null;

            $('#btn-confirmar').prop('disabled', true);
            $('#res-periodo').hide();
            $('#res-total-box').removeClass('d-flex').css('display', 'none');
            $('#label-hora-fin').html('');
            $('#inp-hora-inicio').val('08:00');
            $('#inp-fecha-directa').val('');
            $('#inp-fecha-fin').val('');
            $('#err-fecha-directa').text('');
            $('#grupo-fecha-fin').hide();
            $('#lbl-fecha-fin-info').text('');
            $('#seccion-turno-dia').hide();
            $('#seccion-sin-turno').hide();
            $('#ddl_clientes').val(null).trigger('change');

            $('#res-space-nombre').text(activeEspacioNom);
            $('#res-space-codigo').text(activeEspacioCod);

            var unidad = activeTarifa.unidad_tiempo === 'HORA' ? 'hora' : 'mes';
            var duracion = activeTarifa.cantidad > 1 ?
                activeTarifa.cantidad + ' ' + unidad + 's por reserva' :
                '1 ' + unidad + ' por reserva';

            $('#res-tarifa-badge').text(activeTarifa.nombre_plan);
            $('#res-tarifa-nombre').text(activeTarifa.nombre_plan);
            $('#res-tarifa-detalle').text(duracion);
            $('#res-tarifa-precio').text('$' + activeTarifa.precio.toFixed(2));

            if (activeTarifa.unidad_tiempo === 'HORA') {
                $('#res-cal-label').text('Selecciona el día');
                $('#seccion-hora').show();
                /* Para reservas por hora NO mostramos el campo fecha fin aquí;
                   se muestra el rango de hora en label-hora-fin */
                $('#grupo-fecha-fin').hide();
            } else {
                $('#res-cal-label').text('Selecciona la fecha de inicio');
                $('#seccion-hora').hide();
                /* fecha fin se mostrará al seleccionar fecha */
            }

            renderResCalendar();
            $('#modalReserva').modal('show');
        }

        /* ════════════════════════════
           CALENDARIO
        ════════════════════════════ */
        function renderResCalendar() {
            var yr = resViewDate.getFullYear();
            var mo = resViewDate.getMonth();
            $('#res-month-label').text(MONTHS[mo] + ' ' + yr);
            $('#res-cal-grid').html(buildMonthGrid(yr, mo));
        }

        $('#btn-res-prev').on('click', function() {
            resViewDate.setMonth(resViewDate.getMonth() - 1);
            renderResCalendar();
        });
        $('#btn-res-next').on('click', function() {
            resViewDate.setMonth(resViewDate.getMonth() + 1);
            renderResCalendar();
        });

        $(document).on('click', '#res-cal-grid .hub-cal-day', function() {
            /* No permitir past, empty ni domingo */
            if ($(this).hasClass('past') || $(this).hasClass('empty') || $(this).hasClass('domingo')) return;
            var ds = $(this).data('date');
            if (!ds) return;
            resSelected = fromISO(ds);
            $('#inp-fecha-directa').val(ds);
            $('#err-fecha-directa').text('');
            renderResCalendar();
            calcularPeriodo();
        });

        $('#inp-fecha-directa').on('change', function() {
            var val = $(this).val();
            var $err = $('#err-fecha-directa');
            if (!val) {
                resSelected = null;
                resEndDate = null;
                $err.text('');
                $('#inp-fecha-fin').val('');
                $('#grupo-fecha-fin').hide();
                ocultarTurnoDia();
                renderResCalendar();
                calcularPeriodo();
                return;
            }
            var d = fromISO(val);
            if (d < todayMN()) {
                $err.text('La fecha no puede ser anterior a hoy.');
                $(this).val('');
                resSelected = null;
                resEndDate = null;
                $('#inp-fecha-fin').val('');
                $('#grupo-fecha-fin').hide();
                ocultarTurnoDia();
                renderResCalendar();
                calcularPeriodo();
                return;
            }
            /* No permitir domingo */
            if (d.getDay() === 0) {
                $err.text('No se puede reservar en domingo.');
                $(this).val('');
                resSelected = null;
                resEndDate = null;
                $('#inp-fecha-fin').val('');
                $('#grupo-fecha-fin').hide();
                ocultarTurnoDia();
                renderResCalendar();
                calcularPeriodo();
                return;
            }
            $err.text('');
            resSelected = d;
            resViewDate = new Date(d.getFullYear(), d.getMonth(), 1);
            renderResCalendar();
            calcularPeriodo();
        });

        $('#inp-hora-inicio').on('change input', function() {
            calcularPeriodo();
        });

        /* ── Muestra/oculta la card del turno del día seleccionado ── */
        function mostrarTurnoDia(turno) {
            if (!turno) {
                ocultarTurnoDia();
                return;
            }
            var entrada = minToHora(turno.hora_entrada);
            var salida = minToHora(turno.hora_salida);
            var color = turno.color || '#0d6efd';

            $('#turno-color-dot').css('background', color);
            $('#turno-nombre-dia').text(turno.nombre);
            $('#turno-horario-dia').text(entrada + ' — ' + salida);
            $('#seccion-turno-dia').show();
            $('#seccion-sin-turno').hide();

            /* Para tarifa HORA: restringir el input de hora al rango del turno */
            if (activeTarifa && activeTarifa.unidad_tiempo === 'HORA') {
                $('#inp-hora-inicio').attr('min', entrada).attr('max', calcHoraMaxInicio(turno));
                /* Si la hora actual está fuera del rango, ajustar */
                var horaActual = $('#inp-hora-inicio').val() || '08:00';
                if (horaActual < entrada) $('#inp-hora-inicio').val(entrada);
                if (horaActual > calcHoraMaxInicio(turno)) $('#inp-hora-inicio').val(calcHoraMaxInicio(turno));
                calcularPeriodo();
            }
        }

        function ocultarTurnoDia() {
            $('#seccion-turno-dia').hide();
            $('#seccion-sin-turno').hide();
            if (activeTarifa && activeTarifa.unidad_tiempo === 'HORA') {
                $('#inp-hora-inicio').removeAttr('min').removeAttr('max');
            }
        }

        /**
         * Calcula la hora máxima de inicio para que quepa la duración del turno.
         * Ejemplo: turno 08:00-17:00, duración 2h → máx inicio 15:00
         */
        function calcHoraMaxInicio(turno) {
            if (!activeTarifa) return '23:00';
            var maxMin = turno.hora_salida - (activeTarifa.cantidad * 60);
            if (maxMin < turno.hora_entrada) maxMin = turno.hora_entrada;
            return minToHora(maxMin);
        }

        function calcularPeriodo() {
            if (!resSelected || !activeTarifa) {
                $('#res-periodo').hide();
                $('#res-total-box').css('display', 'none');
                $('#btn-confirmar').prop('disabled', true);
                $('#inp-fecha-fin').val('');
                $('#grupo-fecha-fin').hide();
                ocultarTurnoDia();
                return;
            }

            /* Obtener turno del día seleccionado */
            var turno = getTurnoPorDia(resSelected.getDay());

            var texto = '';
            var fechaIni = fmtHuman(resSelected);

            if (activeTarifa.unidad_tiempo === 'HORA') {
                mostrarTurnoDia(turno);
                if (turno === null) {
                    $('#seccion-sin-turno').show();
                }

                var horaIni = $('#inp-hora-inicio').val() || '08:00';
                var partes = horaIni.split(':');
                var totalMins = parseInt(partes[0]) * 60 + parseInt(partes[1]) + activeTarifa.cantidad * 60;
                var hFin = Math.floor(totalMins / 60) % 24;
                var mFin = totalMins % 60;
                var diasExtra = Math.floor(totalMins / 1440);
                var labelFin = '';
                if (diasExtra > 0) {
                    var dFin = new Date(resSelected);
                    dFin.setDate(dFin.getDate() + diasExtra);
                    labelFin = fmtHuman(dFin) + ' ';
                }
                var horaFinStr = pad2(hFin) + ':' + pad2(mFin);
                texto = '<strong>' + fechaIni + '</strong> de <strong>' + horaIni + '</strong> a <strong>' + labelFin + horaFinStr + '</strong>';
                $('#label-hora-fin').html(
                    '<span class="badge bg-light text-dark border">' +
                    '<i class="bx bx-time me-1 text-primary"></i>Fin: ' + (labelFin || '') + horaFinStr + '</span>'
                );
                /* Para tarifa hora, campo fecha fin muestra la misma fecha (o la siguiente si pasa medianoche) */
                var dFinHora = new Date(resSelected);
                if (diasExtra > 0) dFinHora.setDate(dFinHora.getDate() + diasExtra);
                $('#inp-fecha-fin').val(fmtISO(dFinHora));
                $('#grupo-fecha-fin').show();
                resEndDate = dFinHora;

            } else {
                /* Por FECHA (meses) */
                mostrarTurnoDia(turno);
                if (turno === null) {
                    $('#seccion-sin-turno').show();
                }

                resEndDate = calcEndDate(resSelected);
                var endISO = fmtISO(resEndDate);
                $('#inp-fecha-fin').val(endISO);
                $('#grupo-fecha-fin').show();
                $('#lbl-fecha-fin-info').html(
                    '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">' +
                    '<i class="bx bx-info-circle me-1"></i>' +
                    activeTarifa.cantidad + ' mes' + (activeTarifa.cantidad !== 1 ? 'es' : '') +
                    ' · 30 días lab. c/u' +
                    '</span>'
                );

                texto = 'Del <strong>' + fechaIni + '</strong> al <strong>' + fmtHuman(resEndDate) + '</strong>' +
                    ' &nbsp;·&nbsp; ' + activeTarifa.cantidad + ' mes' + (activeTarifa.cantidad !== 1 ? 'es' : '');
            }

            $('#res-periodo-texto').html(texto);
            $('#res-periodo').show();
            $('#res-total-valor').text('$' + activeTarifa.precio.toFixed(2));
            $('#res-total-box').css('display', 'flex');
            $('#btn-confirmar').prop('disabled', false);
        }

        function buildMonthGrid(year, month) {
            var t = todayMN();
            var endDate = (resSelected && activeTarifa && activeTarifa.unidad_tiempo !== 'HORA') ?
                calcEndDate(resSelected) : null;
            var html = '';

            /* Cabeceras: marcar domingo */
            DOW.forEach(function(d, idx) {
                var cls = idx === 6 ? ' domingo' : '';
                html += '<div class="hub-cal-head' + cls + '">' + d + '</div>';
            });

            /* startDow: (getDay()+6)%7 → Lu=0 … Do=6 */
            var startDow = (new Date(year, month, 1).getDay() + 6) % 7;
            var days = new Date(year, month + 1, 0).getDate();

            for (var i = 0; i < startDow; i++) html += '<div class="hub-cal-day empty"></div>';

            for (var d = 1; d <= days; d++) {
                var dt = new Date(year, month, d);
                var ds = fmtISO(dt);
                var dow = dt.getDay(); // 0=Dom … 6=Sáb
                var cls = 'hub-cal-day';

                /* Domingo: visible pero no seleccionable */
                if (dow === 0) {
                    var todayCls = (dt.getTime() === t.getTime()) ? ' today' : '';
                    html += '<div class="' + cls + ' domingo' + todayCls + '">' + d + '</div>';
                    continue;
                }

                if (dt < t) {
                    html += '<div class="' + cls + ' past">' + d + '</div>';
                    continue;
                }
                if (dt.getTime() === t.getTime()) cls += ' today';

                if (resSelected && dt.getTime() === resSelected.getTime()) {
                    cls += ' selected';
                } else if (endDate && resSelected) {
                    if (dt > resSelected && dt.getTime() === endDate.getTime()) cls += ' range-end';
                    else if (dt > resSelected && dt < endDate) cls += ' in-range';
                }

                html += '<div class="' + cls + '" data-date="' + ds + '">' + d + '</div>';
            }
            return html;
        }

        /* ════════════════════════════
           CONFIRMAR RESERVA
        ════════════════════════════ */
        $('#btn-confirmar').on('click', function() {
            if (!resSelected) {
                Swal.fire('', 'Selecciona una fecha.', 'warning');
                return;
            }
            if (!$('#ddl_clientes').val()) {
                Swal.fire('', 'Selecciona una persona.', 'warning');
                return;
            }
            if (!resEndDate) {
                Swal.fire('', 'No se pudo calcular la fecha de fin.', 'warning');
                return;
            }

            var fechaIniStr, fechaFinStr;

            if (activeTarifa.unidad_tiempo === 'HORA') {
                /* ── Por HORA ── */
                var horaIni = $('#inp-hora-inicio').val() || '08:00';
                var p = horaIni.split(':');
                var hIni = parseInt(p[0]);
                var mIni = parseInt(p[1]);
                var totalMins = hIni * 60 + mIni + activeTarifa.cantidad * 60;
                var diasExtra = Math.floor(totalMins / 1440);
                var hFin = Math.floor(totalMins / 60) % 24;
                var mFin = totalMins % 60;

                var dFin = new Date(resSelected);
                if (diasExtra > 0) dFin.setDate(dFin.getDate() + diasExtra);

                fechaIniStr = fmtDatetime(resSelected, hIni, mIni, 0);
                fechaFinStr = fmtDatetime(dFin, hFin, mFin, 0);

            } else {
                /* ── Por FECHA (meses) ── usa resEndDate ya calculado ── */
                /* Horario del turno del día de inicio; si no hay turno usa 08:00/17:00 */
                var turno = getTurnoPorDia(resSelected.getDay());
                var hIni, mIni, hFin, mFin;

                if (turno) {
                    var entMin = turno.hora_entrada;
                    var salMin = turno.hora_salida;
                    hIni = Math.floor(entMin / 60);
                    mIni = entMin % 60;
                    hFin = Math.floor(salMin / 60);
                    mFin = salMin % 60;
                } else {
                    hIni = 8;
                    mIni = 0;
                    hFin = 17;
                    mFin = 0;
                }

                fechaIniStr = fmtDatetime(resSelected, hIni, mIni, 0);
                fechaFinStr = fmtDatetime(resEndDate, hFin, mFin, 0);
            }

            var params = {
                id_espacio: activeEspacioId,
                th_per_id: $('#ddl_clientes').val(),
                inicio: fechaIniStr,
                fin: fechaFinStr,
                id_usuario: ID_USUARIO_SESION,
                observaciones: ''
            };

            $('#btn-confirmar').prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-1"></span>Guardando...');

            $.ajax({
                url: URL_RESERVAS + '?crear_reserva=true',
                type: 'post',
                dataType: 'json',
                data: {
                    parametros: params
                },
                success: function(r) {
                    $('#btn-confirmar').prop('disabled', false)
                        .html('<i class="bx bx-calendar-plus me-1"></i>Confirmar reserva');

                    var res = r && r[0] ? r[0] : {};
                    var code = parseInt(res.code);
                    var msg = res.message || 'Sin respuesta del servidor.';

                    if (code === 1) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Reserva creada!',
                            text: msg
                        });
                        $('#modalReserva').modal('hide');
                    } else {
                        Swal.fire({
                            icon: code === 0 ? 'warning' : 'error',
                            title: code === 0 ? 'No se pudo reservar' : 'Error del servidor',
                            text: msg
                        });
                    }
                },
                error: function() {
                    $('#btn-confirmar').prop('disabled', false)
                        .html('<i class="bx bx-calendar-plus me-1"></i>Confirmar reserva');
                    Swal.fire('', 'Error de comunicación con el servidor.', 'error');
                }
            });
        });

        /* ════════════════════════════
           RESET + INIT
        ════════════════════════════ */
        function resetearSeleccion() {
            resSelected = null;
            resEndDate = null;
            activeTarifa = null;
            activeEspacioId = null;
            activeEspacioNom = null;
            activeEspacioCod = null;
            turnosEspacio = [];
            resViewDate = new Date();
            resViewDate.setHours(0, 0, 0, 0);
        }

        setStep(1);
        cargarUbicaciones();
    });
</script>