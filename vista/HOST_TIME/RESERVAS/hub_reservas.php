<?php
$modulo_sistema = $_SESSION['INICIO']['MODULO_SISTEMA'];
?>

<script>
    $(document).ready(function() {
        cargar_selects_clientes();
    });

    function cargar_selects_clientes() {
        var url_clientesC = '../controlador/GENERAL/NO_CONCURRENTES/CLIENTESC.php?buscar_clientes=true';
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
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Hub · Espacios</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">

                        <!-- STEP BAR -->
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                            <span class="badge bg-primary px-3 py-2" id="st1"><i class='bx bx-map me-1'></i>Ubicación</span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st2"><i class='bx bx-layer me-1'></i>Piso</span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st3"><i class='bx bx-category me-1'></i>Tipo</span>
                            <i class='bx bx-chevron-right text-muted'></i>
                            <span class="badge bg-secondary px-3 py-2" id="st4"><i class='bx bx-door-open me-1'></i>Espacio</span>
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
                        <span class="badge ms-2" id="modal-tipo-badge" style="display:none;"></span>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4">

                            <!-- ── COLUMNA IZQUIERDA: Calendarios ── -->
                            <div class="col-md-7">

                                <!-- Vista: Por DÍA (un mes) -->
                                <div id="view-cal-dia">
                                    <p class="text-muted small fw-semibold text-uppercase mb-2">
                                        <i class='bx bx-calendar me-1'></i>Selecciona una fecha
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <button class="btn btn-sm btn-outline-secondary" id="btn-dia-prev">
                                            <i class='bx bx-chevron-left'></i>
                                        </button>
                                        <strong id="dia-month-label"></strong>
                                        <button class="btn btn-sm btn-outline-secondary" id="btn-dia-next">
                                            <i class='bx bx-chevron-right'></i>
                                        </button>
                                    </div>
                                    <div id="dia-cal-grid" class="hub-cal-grid"></div>

                                    <!-- Turnos del día -->
                                    <div id="turnos-dia" class="mt-3" style="display:none;">
                                        <p class="text-muted small fw-semibold text-uppercase mb-1">
                                            <i class='bx bx-time me-1'></i>Turnos disponibles
                                        </p>
                                        <div id="turnos-dia-list"></div>
                                    </div>
                                </div>

                                <!-- Vista: Por RANGO (multi-mes scrollable) -->
                                <div id="view-cal-rango" style="display:none;">
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">
                                        <i class='bx bx-calendar-alt me-1'></i>Selecciona fecha inicio y fin
                                    </p>
                                    <div id="rango-hint" class="small text-muted mb-2">
                                        <i class='bx bx-info-circle me-1'></i>
                                        Haz clic en el día de <strong>inicio</strong>
                                    </div>
                                    <div id="multi-month-scroll" class="hub-multi-month-scroll"></div>
                                </div>

                                <!-- Vista: OFICINA (calendario navegable + info rango) -->
                                <div id="view-cal-ofic" style="display:none;">
                                    <p class="text-muted small fw-semibold text-uppercase mb-2">
                                        <i class='bx bx-calendar me-1'></i>Selecciona fecha de inicio
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <button class="btn btn-sm btn-outline-secondary" id="btn-ofic-prev">
                                            <i class='bx bx-chevron-left'></i>
                                        </button>
                                        <strong id="ofic-month-label"></strong>
                                        <button class="btn btn-sm btn-outline-secondary" id="btn-ofic-next">
                                            <i class='bx bx-chevron-right'></i>
                                        </button>
                                    </div>
                                    <div id="ofic-cal-grid" class="hub-cal-grid"></div>
                                    <div id="ofic-range-info" class="mt-2" style="display:none;">
                                        <div class="alert alert-info py-2 small mb-0" id="ofic-range-text"></div>
                                    </div>
                                </div>

                            </div>

                            <!-- ── COLUMNA DERECHA: Opciones ── -->
                            <div class="col-md-5">

                                <p class="text-muted small fw-semibold text-uppercase mb-2">
                                    <i class='bx bx-slider-alt me-1'></i>Tipo de reserva
                                </p>

                                <!-- Radios SALA -->
                                <div id="radios-sala" class="mb-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="tipo_reserva"
                                            id="r_dia" value="dia" checked>
                                        <label class="form-check-label" for="r_dia">
                                            <i class='bx bx-calendar-check me-1 text-success'></i>Por un día
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_reserva"
                                            id="r_rango" value="rango">
                                        <label class="form-check-label" for="r_rango">
                                            <i class='bx bx-calendar-alt me-1 text-primary'></i>Por rango de fechas
                                        </label>
                                    </div>
                                </div>

                                <!-- Aviso OFICINA -->
                                <div id="radios-oficina" class="mb-3" style="display:none;">
                                    <div class="alert alert-info py-2 mb-0 small">
                                        <i class='bx bx-buildings me-1'></i>
                                        <strong>Oficina:</strong> Reserva por meses completos con contrato.
                                    </div>
                                </div>

                                <!-- Cliente -->
                                <div class="mb-3">
                                    <label for="ddl_clientes" class="form-label fw-bold">Persona</label>
                                    <select class="form-select form-select-sm select2-validation"
                                        id="ddl_clientes" name="ddl_clientes">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                </div>

                                <!-- ── Inputs: Por día ── -->
                                <div id="opt-dia">
                                    <label class="form-label small">Fecha seleccionada</label>
                                    <input type="date" class="form-control form-control-sm mb-2"
                                        id="txt_fecha_dia">
                                </div>

                                <!-- ── Inputs: Por rango ── -->
                                <div id="opt-rango" style="display:none;">
                                    <label class="form-label small">Fecha inicio</label>
                                    <input type="date" class="form-control form-control-sm mb-2"
                                        id="txt_fecha_inicio">
                                    <label class="form-label small">Fecha fin</label>
                                    <input type="date" class="form-control form-control-sm mb-2"
                                        id="txt_fecha_fin">
                                    <div id="rango-dias-label" class="small text-success fw-semibold mt-1"></div>
                                </div>

                                <!-- ── Inputs: Por meses (oficina) ── -->
                                <div id="opt-ofic" style="display:none;">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label small">Fecha de inicio</label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="inp-ofic-inicio" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold">
                                                Número de meses <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control form-control-sm"
                                                id="inp-ofic-meses" min="1" max="60"
                                                placeholder="Ej: 4">
                                            <div class="form-text">Ingresa cuántos meses deseas ocupar el espacio</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small">Fecha de fin (calculada)</label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="inp-ofic-fin" readonly>
                                        </div>
                                        <div class="col-12">
                                            <div id="ofic-calc-label" class="small text-success fw-semibold"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ── Sección contrato (solo oficina) ── -->
                                <div id="seccion-contrato" style="display:none;">
                                    <hr>
                                    <p class="text-muted small fw-semibold text-uppercase mb-2">
                                        <i class='bx bx-file me-1'></i>Datos del contrato
                                    </p>
                                    <div class="mb-2">
                                        <label class="form-label small">N° de contrato</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="inp-contrato-num" placeholder="Ej: CT-2025-001">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Fecha de firma</label>
                                        <input type="date" class="form-control form-control-sm"
                                            id="inp-contrato-fecha">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Monto mensual ($)</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm"
                                            id="inp-contrato-monto" placeholder="0.00">
                                    </div>
                                    <div class="alert alert-warning py-2 small mb-0">
                                        <i class='bx bx-lock-alt me-1'></i>
                                        El contrato físico debe entregarse antes de la ocupación.
                                    </div>
                                </div>

                                <hr>
                                <p class="text-muted small fw-semibold text-uppercase mb-1">
                                    <i class='bx bx-receipt me-1'></i>Resumen
                                </p>
                                <div id="resumen-reserva" class="small text-muted">
                                    Selecciona una fecha para ver el resumen.
                                </div>

                                <div class="d-grid mt-3">
                                    <button class="btn btn-success" id="btn-confirmar">
                                        <i class='bx bx-calendar-plus me-1'></i>Confirmar reserva
                                    </button>
                                </div>

                            </div><!-- /col-md-5 -->
                        </div><!-- /row -->
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /page-content -->
</div><!-- /page-wrapper -->


<!-- ══════════════════════════════════════
     ESTILOS
══════════════════════════════════════ -->
<style>
    /* ─── Calendario días ─── */
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
        user-select: none;
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

    /* Punto verde en días disponibles */
    .hub-cal-day.has-shift::after {
        content: '';
        display: block;
        width: 4px;
        height: 4px;
        background: #52b788;
        border-radius: 50%;
        margin: 2px auto 0;
    }

    /* Selección un día */
    .hub-cal-day.selected {
        background: #0d6efd !important;
        color: #fff !important;
        font-weight: 700;
    }

    .hub-cal-day.selected::after {
        display: none;
    }

    /* Rango */
    .hub-cal-day.range-start {
        background: #0d6efd !important;
        color: #fff !important;
        font-weight: 700;
        border-radius: 6px 0 0 6px;
    }

    .hub-cal-day.range-end {
        background: #0d6efd !important;
        color: #fff !important;
        font-weight: 700;
        border-radius: 0 6px 6px 0;
    }

    .hub-cal-day.range-start.range-end {
        border-radius: 6px;
    }

    .hub-cal-day.in-range {
        background: rgba(13, 110, 253, .15);
        color: #0d6efd;
        border-radius: 0;
    }

    .hub-cal-day.range-start::after,
    .hub-cal-day.range-end::after,
    .hub-cal-day.in-range::after {
        display: none;
    }

    /* ─── Multi-mes scroll (rango) ─── */
    .hub-multi-month-scroll {
        max-height: 370px;
        overflow-y: auto;
        padding-right: 6px;
        scrollbar-width: thin;
        scrollbar-color: #dee2e6 transparent;
    }

    .hub-multi-month-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .hub-multi-month-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .hub-multi-month-scroll::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 4px;
    }

    .hub-month-block {
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f0f0f0;
    }

    .hub-month-block:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .hub-month-block-title {
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #0d6efd;
        margin-bottom: 6px;
    }

    /* ─── Pisos sidebar ─── */
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

    /* ─── Location / space cards ─── */
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
     JAVASCRIPT — 100 % jQuery
══════════════════════════════════════ -->
<script>
    $(function() {

        /* ─── URLs de controladores ─── */
        var URL_UBICACIONES = '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php';
        var URL_ESPACIOS = '../controlador/HOST_TIME/ESPACIOS/espaciosC.php';
        var URL_TURNOS = '../controlador/HOST_TIME/ESPACIOS/hub_espacios_turnosC.php';

        /* ─── Mapa tipo de espacio ─── */
        var TIPO_ESPACIO_MAP = {
            1: 'sala',
            2: 'oficina',
            3: 'sala'
        };

        var MONTHS_LONG = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        var DOW_LABELS = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'];

        /* ─── Estado de navegación ─── */
        var selUbicacion = null,
            selPiso = null,
            selTipo = null;
        var activeEspacioId = null,
            activeTipoEspacio = 'sala';

        /* ─── Estado calendario POR DÍA ─── */
        var diaViewDate = new Date();
        var diaSelected = null; // Date

        /* ─── Estado calendario POR RANGO ─── */
        var rangoStart = null; // Date
        var rangoEnd = null; // Date

        /* ─── Estado calendario OFICINA ─── */
        var oficViewDate = new Date();
        var oficStart = null; // Date

        /* ══════════════════════════════
           HELPERS
        ══════════════════════════════ */
        function todayMidnight() {
            var t = new Date();
            t.setHours(0, 0, 0, 0);
            return t;
        }

        function pad2(n) {
            return String(n).padStart(2, '0');
        }

        function fmtISO(d) {
            return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate());
        }

        function fromISO(s) {
            var p = s.split('-');
            return new Date(+p[0], +p[1] - 1, +p[2]);
        }

        function fmtDisplay(d) {
            return pad2(d.getDate()) + ' de ' + MONTHS_LONG[d.getMonth()] + ' ' + d.getFullYear();
        }

        function diffDias(a, b) {
            if (!a || !b) return 0;

            // Validar fechas
            if (isNaN(a.getTime()) || isNaN(b.getTime())) return 0;

            // Normalizar horas
            var start = new Date(a.getFullYear(), a.getMonth(), a.getDate());
            var end = new Date(b.getFullYear(), b.getMonth(), b.getDate());

            var dias = Math.floor((end - start) / 86400000) + 1;

            // Detectar meses exactos (27/03 → 27/04)
            if (start.getDate() === end.getDate()) {
                var meses = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());

                if (meses >= 1) {
                    return meses * 30; // o puedes devolver meses si prefieres
                }
            }

            return dias;
        }
        $('#txt_fecha_dia').on('change', function() {
            var val = $(this).val();
            if (!val) return;

            diaSelected = fromISO(val);
            diaViewDate = new Date(diaSelected);
            diaViewDate.setDate(1);

            renderDiaCalendar();
            mostrarTurnosDia();
            updateResumen();
        });
        $('#txt_fecha_inicio').on('change', function() {
            var val = $(this).val();
            if (!val) return;

            rangoStart = fromISO(val);
            rangoEnd = null;

            $('#txt_fecha_fin').val('');
            renderRangoMultiMes();
            updateResumen();
        });

        $('#txt_fecha_fin').on('change', function() {
            var val = $(this).val();
            if (!val || !rangoStart) return;

            rangoEnd = fromISO(val);

            renderRangoMultiMes();
            updateResumen();
        });

        /* ══════════════════════════════
           STEP BAR
        ══════════════════════════════ */
        function setStep(n) {
            for (var i = 1; i <= 4; i++) {
                var $el = $('#st' + i);
                $el.removeClass('bg-primary bg-success bg-secondary');
                $el.addClass(i < n ? 'bg-success' : i === n ? 'bg-primary' : 'bg-secondary');
            }
        }

        /* ══════════════════════════════
           STEP 1 – UBICACIONES
        ══════════════════════════════ */
        function cargarUbicaciones() {
            $.post(URL_UBICACIONES + '?listar=true', {}, function(data) {
                var html = '';
                if (!data || data.length === 0) {
                    html = '<div class="col-12 text-center text-muted py-3">No hay ubicaciones registradas.</div>';
                } else {
                    $.each(data, function(_, u) {
                        html += '<div class="col-md-4 col-sm-6">' +
                            '<div class="loc-card" data-id="' + u._id + '">' +
                            '<h6 class="mb-1 fw-bold">' + u.nombre + '</h6>' +
                            '<div class="small text-muted">' +
                            '<div><i class="bx bx-map-pin me-1 text-success"></i>' + u.direccion + '</div>' +
                            '<div><i class="bx bx-phone me-1 text-success"></i>' + u.telefono + '</div>' +
                            '</div></div></div>';
                    });
                }
                $('#loc-cards').html(html);
            }, 'json').fail(function() {
                $('#loc-cards').html('<div class="col-12 alert alert-danger">Error al cargar ubicaciones.</div>');
            });
        }

        $(document).on('click', '.loc-card', function() {
            var id = $(this).data('id');
            selUbicacion = id;
            selPiso = null;
            selTipo = null;
            $('.loc-card').removeClass('selected');
            $(this).addClass('selected');
            setStep(2);
            cargarPisos(id);
        });

        /* ══════════════════════════════
           STEP 2 – PISOS
        ══════════════════════════════ */
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
                    if (!Array.isArray(data) || data.length === 0) {
                        html = '<div class="p-3 small text-muted text-center">Sin pisos.</div>';
                    } else {
                        $.each(data, function(_, p) {
                            html += '<div class="floor-item" data-id="' + p._id + '">' +
                                '<i class="bx bx-layer"></i><span>' + p.nombre_piso + '</span>' +
                                '<span class="badge bg-secondary floor-badge">–</span></div>';
                        });
                    }
                    $('#floor-list').html(html);
                    $('#sidebar-col').show();
                    $('#content-col').removeClass('col-md-12').addClass('col-md-10');
                    $('#view-locations').hide();
                    $('#view-spaces').show();
                },
                error: function() {
                    $('#floor-list').html('<div class="p-3 small text-danger">Error de conexión.</div>');
                }
            });
        }

        $(document).on('click', '.floor-item', function() {
            var id = $(this).data('id');
            selPiso = id;
            selTipo = null;
            $('.floor-item').removeClass('active');
            $(this).addClass('active');
            setStep(3);
            cargarTipos();
            renderEspacios();
        });

        /* ══════════════════════════════
           STEP 3 – TIPOS
        ══════════════════════════════ */
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
                    var html = '<div class="type-tab active" data-tipo="0"><i class="bx bx-grid-alt"></i> Todos</div>';
                    if (data && data.length > 0) {
                        $.each(data, function(_, t) {
                            html += '<div class="type-tab" data-tipo="' + t.id_tipo_espacio + '">' +
                                '<i class="bx bx-category"></i> ' + t.nombre + '</div>';
                        });
                    }
                    $('#type-tabs').html(html);
                }
            });
        }

        $(document).on('click', '.type-tab', function() {
            var id = $(this).data('tipo');
            selTipo = (id == 0) ? null : id;
            $('.type-tab').removeClass('active');
            $(this).addClass('active');
            setStep(4);
            renderEspacios();
        });

        /* ══════════════════════════════
           STEP 4 – ESPACIOS
        ══════════════════════════════ */
        function renderEspacios() {
            if (!selPiso) {
                $('#space-cards').html('');
                return;
            }
            $('#space-cards').html('<div class="col-12 text-center py-3 text-muted">' +
                '<div class="spinner-border spinner-border-sm me-2"></div> Cargando espacios...</div>');

            $.post(URL_ESPACIOS + '?listar=true', {}, function(data) {
                if (!data || data.length === 0) {
                    $('#space-cards').html('<div class="col-12 text-center text-muted py-4">No hay espacios.</div>');
                    return;
                }
                var lista = $.grep(data, function(e) {
                    return parseInt(e.id_numero_piso) === parseInt(selPiso);
                });
                if (selTipo) {
                    lista = $.grep(lista, function(e) {
                        return parseInt(e.id_tipo_espacio) === parseInt(selTipo);
                    });
                }
                if (lista.length === 0) {
                    $('#space-cards').html('<div class="col-12 text-center text-muted py-4">No hay espacios para estos filtros.</div>');
                    return;
                }
                var html = '';
                $.each(lista, function(_, e) {
                    var img = e.imagen ?
                        '<img src="' + e.imagen + '" alt="' + e.nombre + '" style="width:100%;height:130px;object-fit:cover;">' :
                        '<div class="space-img-placeholder"><i class="bx bx-building-house"></i></div>';

                    html += '<div class="col-md-4 col-sm-6">' +
                        '<div class="space-card" id="sc-' + e._id + '">' +
                        '<div class="space-card-hdr" data-id="' + e._id + '">' + img + '</div>' +
                        '<div class="p-3 space-card-hdr" data-id="' + e._id + '">' +
                        '<div class="fw-bold mb-1">' + e.nombre + '</div>' +
                        '<div class="small text-muted d-flex gap-3">' +
                        '<span><i class="bx bx-user me-1"></i>' + e.capacidad + ' personas</span>' +
                        '<span><i class="bx bx-hash me-1"></i>' + e.codigo + '</span>' +
                        '</div>' +
                        '<div class="small text-muted mt-1"><i class="bx bx-category me-1"></i>' + (e.nombre_tipo_espacio || '') + '</div>' +
                        '</div>' +
                        '<div class="space-detail" id="sd-' + e._id + '">' +
                        '<div class="p-3">' +
                        '<div class="d-flex gap-2 mb-2 flex-wrap">' +
                        '<span class="badge bg-success"><i class="bx bx-time-five me-1"></i>$' + parseFloat(e.tarifa_hora).toFixed(2) + '/hora</span>' +
                        '<span class="badge bg-primary"><i class="bx bx-sun me-1"></i>$' + parseFloat(e.tarifa_dia).toFixed(2) + '/día</span>' +
                        '</div>' +
                        '<div class="small text-muted mb-2">' +
                        '<i class="bx bx-map-pin me-1"></i>' + (e.nombre_ubicacion || '') + ' &nbsp;·&nbsp; ' +
                        '<i class="bx bx-layer me-1"></i>' + (e.descripcion_numero_piso || '') + '</div>' +
                        '<div class="d-grid">' +
                        '<button class="btn btn-success btn-sm btn-reservar"' +
                        ' data-id="' + e._id + '"' +
                        ' data-nombre="' + e.nombre + '"' +
                        ' data-codigo="' + e.codigo + '"' +
                        ' data-tipo="' + (e.id_tipo_espacio || 1) + '">' +
                        '<i class="bx bx-calendar-plus me-1"></i>Reservar este espacio</button>' +
                        '</div></div></div></div></div>';
                });
                $('#space-cards').html(html);
            }, 'json');
        }

        $(document).on('click', '.space-card-hdr', function() {
            var id = $(this).data('id');
            var $det = $('#sd-' + id),
                $card = $('#sc-' + id);
            var wasOpen = $det.hasClass('open');
            $('.space-detail.open').removeClass('open');
            $('.space-card.expanded').removeClass('expanded');
            if (!wasOpen) {
                $det.addClass('open');
                $card.addClass('expanded');
            }
        });

        window.goBackToLocations = function() {
            selPiso = null;
            selTipo = null;
            $('#view-spaces').hide();
            $('#view-locations').show();
            $('#sidebar-col').hide();
            $('#content-col').removeClass('col-md-10').addClass('col-md-12');
            setStep(1);
        };

        /* ══════════════════════════════
           ABRIR MODAL DE RESERVA
        ══════════════════════════════ */
        $(document).on('click', '.btn-reservar', function() {
            activeEspacioId = $(this).data('id');
            var nombre = $(this).data('nombre');
            var codigo = $(this).data('codigo');
            var idTipo = parseInt($(this).data('tipo')) || 1;

            activeTipoEspacio = TIPO_ESPACIO_MAP[idTipo] || 'sala';

            $('#modal-space-name').text(nombre);
            $('#modal-space-sub').text(codigo);

            var $badge = $('#modal-tipo-badge');
            if (activeTipoEspacio === 'oficina') {
                $badge.text('Oficina').removeClass('bg-success').addClass('bg-warning text-dark').show();
            } else {
                $badge.text('Sala').removeClass('bg-warning text-dark').addClass('bg-success').show();
            }

            resetModal();
            configurarModalPorTipo();

            $('#modalReserva').modal('show');
        });

        /* ── Reset completo del modal ── */
        function resetModal() {
            diaViewDate = new Date();
            diaViewDate.setHours(0, 0, 0, 0);
            diaSelected = null;
            rangoStart = null;
            rangoEnd = null;
            oficViewDate = new Date();
            oficViewDate.setHours(0, 0, 0, 0);
            oficStart = null;

            $('#txt_fecha_dia,#txt_fecha_inicio,#txt_fecha_fin,#inp-ofic-inicio,#inp-ofic-fin').val('');
            $('#inp-ofic-meses').val('');
            $('#inp-contrato-num,#inp-contrato-fecha,#inp-contrato-monto').val('');
            $('#turnos-dia').hide();
            $('#rango-dias-label,#ofic-calc-label').html('');
            $('#ofic-range-info').hide();
            $('#rango-hint').html('<i class="bx bx-info-circle me-1"></i>Haz clic en el día de <strong>inicio</strong>');
            $('#resumen-reserva').text('Selecciona una fecha para ver el resumen.');
            $('#r_dia').prop('checked', true);
        }

        /* ── Configurar modal según tipo de espacio ── */
        function configurarModalPorTipo() {
            if (activeTipoEspacio === 'oficina') {
                $('#radios-sala').hide();
                $('#radios-oficina').show();
                $('#view-cal-dia').hide();
                $('#view-cal-rango').hide();
                $('#view-cal-ofic').show();
                $('#opt-dia').hide();
                $('#opt-rango').hide();
                $('#opt-ofic').show();
                $('#seccion-contrato').show();
                renderOficCalendar();
            } else {
                $('#radios-oficina').hide();
                $('#radios-sala').show();
                $('#view-cal-ofic').hide();
                $('#view-cal-rango').hide();
                $('#view-cal-dia').show();
                $('#opt-ofic').hide();
                $('#opt-rango').hide();
                $('#opt-dia').show();
                $('#seccion-contrato').hide();
                renderDiaCalendar();
            }
        }

        /* ══════════════════════════════
           CAMBIO RADIO (día / rango)
        ══════════════════════════════ */
        $(document).on('change', 'input[name="tipo_reserva"]', function() {
            var tipo = $(this).val();
            if (tipo === 'dia') {
                $('#view-cal-rango').hide();
                $('#view-cal-dia').show();
                $('#opt-rango').hide();
                $('#opt-dia').show();
                rangoStart = null;
                rangoEnd = null;
                $('#txt_fecha_inicio,#txt_fecha_fin').val('');
                $('#rango-dias-label').html('');
                renderDiaCalendar();
            } else {
                $('#view-cal-dia').hide();
                $('#view-cal-rango').show();
                $('#opt-dia').hide();
                $('#opt-rango').show();
                diaSelected = null;
                $('#txt_fecha_dia').val('');
                $('#turnos-dia').hide();
                renderRangoMultiMes();
            }
            $('#resumen-reserva').text('Selecciona una fecha para ver el resumen.');
        });

        /* ══════════════════════════════
           FUNCIÓN COMPARTIDA: buildMonthGrid
           Genera HTML de cuadrícula mensual.
           selA = fecha inicio (Date|null)
           selB = fecha fin    (Date|null)
           isDia = true → modo selección única
        ══════════════════════════════ */
        function buildMonthGrid(year, month, selA, selB, isDia) {
            var t = todayMidnight();
            var html = '';

            /* Cabeceras días */
            $.each(DOW_LABELS, function(_, d) {
                html += '<div class="hub-cal-head">' + d + '</div>';
            });

            var startDow = (new Date(year, month, 1).getDay() + 6) % 7;
            var daysInMonth = new Date(year, month + 1, 0).getDate();

            /* Celdas vacías al inicio */
            for (var i = 0; i < startDow; i++) {
                html += '<div class="hub-cal-day empty"></div>';
            }

            for (var d = 1; d <= daysInMonth; d++) {
                var date = new Date(year, month, d);
                var dateStr = fmtISO(date);
                var cls = 'hub-cal-day';

                /* Días pasados → gris, no clickeable */
                if (date < t) {
                    cls += ' past';
                    html += '<div class="' + cls + '">' + d + '</div>';
                    continue;
                }

                /* Hoy */
                if (date.getTime() === t.getTime()) cls += ' today';

                /* Punto de disponibilidad */
                cls += ' has-shift';

                /* Selección */
                if (isDia) {
                    if (selA && date.getTime() === selA.getTime()) cls += ' selected';
                } else {
                    if (selA && selB) {
                        if (date.getTime() === selA.getTime()) cls += ' range-start';
                        else if (date.getTime() === selB.getTime()) cls += ' range-end';
                        else if (date > selA && date < selB) cls += ' in-range';
                    } else if (selA && date.getTime() === selA.getTime()) {
                        cls += ' range-start';
                    }
                }

                html += '<div class="' + cls + '" data-date="' + dateStr + '">' + d + '</div>';
            }
            return html;
        }

        /* ══════════════════════════════
           CALENDARIO POR DÍA
        ══════════════════════════════ */
        function renderDiaCalendar() {
            var yr = diaViewDate.getFullYear(),
                mo = diaViewDate.getMonth();
            $('#dia-month-label').text(MONTHS_LONG[mo] + ' ' + yr);
            $('#dia-cal-grid').html(buildMonthGrid(yr, mo, diaSelected, null, true));
        }

        $('#btn-dia-prev').on('click', function() {
            diaViewDate.setMonth(diaViewDate.getMonth() - 1);
            renderDiaCalendar();
        });
        $('#btn-dia-next').on('click', function() {
            diaViewDate.setMonth(diaViewDate.getMonth() + 1);
            renderDiaCalendar();
        });

        $(document).on('click', '#dia-cal-grid .hub-cal-day', function() {
            if ($(this).hasClass('past') || $(this).hasClass('empty')) return;
            var dateStr = $(this).data('date');
            if (!dateStr) return;

            diaSelected = fromISO(dateStr);
            $('#txt_fecha_dia').val(dateStr);
            mostrarTurnosDia();
            updateResumen();
            renderDiaCalendar();
        });

        /* ══════════════════════════════
           CALENDARIO POR RANGO (multi-mes)
        ══════════════════════════════ */
        function renderRangoMultiMes() {
            var scrollPos = $('#multi-month-scroll').scrollTop();
            var today = todayMidnight();
            /* Primer mes = mes actual */
            var curYear = today.getFullYear();
            var curMonth = today.getMonth();
            var html = '';

            /* Mostrar 6 meses desde hoy */
            for (var i = 0; i < 6; i++) {
                var yr = curYear,
                    mo = curMonth;
                html += '<div class="hub-month-block">' +
                    '<div class="hub-month-block-title">' +
                    '<i class="bx bx-calendar me-1"></i>' +
                    MONTHS_LONG[mo] + ' ' + yr +
                    '</div>' +
                    '<div class="hub-cal-grid rango-grid">' +
                    buildMonthGrid(yr, mo, rangoStart, rangoEnd, false) +
                    '</div></div>';

                curMonth++;
                if (curMonth > 11) {
                    curMonth = 0;
                    curYear++;
                }
            }

            $('#multi-month-scroll').html(html);
            $('#multi-month-scroll').scrollTop(scrollPos);
        }

        $(document).on('click', '.rango-grid .hub-cal-day', function() {
            if ($(this).hasClass('past') || $(this).hasClass('empty')) return;
            var dateStr = $(this).data('date');
            if (!dateStr) return;
            var d = fromISO(dateStr);

            if (!rangoStart || (rangoStart && rangoEnd)) {
                /* Primer clic → inicio */
                rangoStart = d;
                rangoEnd = null;
                $('#txt_fecha_inicio').val(dateStr);
                $('#txt_fecha_fin').val('');
                $('#rango-dias-label').html('');
                $('#rango-hint').html(
                    '<i class="bx bx-check-circle me-1 text-success"></i>' +
                    'Inicio: <strong>' + fmtDisplay(d) + '</strong>' +
                    ' · Ahora elige la fecha de <strong>fin</strong>'
                );
            } else {
                /* Segundo clic → fin */
                if (d.getTime() >= rangoStart.getTime()) {
                    rangoEnd = d;
                } else {
                    /* Clic antes del inicio → intercambiar */
                    rangoEnd = rangoStart;
                    rangoStart = d;
                }
                $('#txt_fecha_inicio').val(fmtISO(rangoStart));
                $('#txt_fecha_fin').val(fmtISO(rangoEnd));
                var dias = diffDias(rangoStart, rangoEnd);
                $('#rango-dias-label').html(
                    '<i class="bx bx-calendar-check me-1 text-success"></i>' +
                    '<strong>' + dias + '</strong> día' + (dias !== 1 ? 's' : '') +
                    ' seleccionado' + (dias !== 1 ? 's' : '')
                );
                $('#rango-hint').html(
                    '<i class="bx bx-check-double me-1 text-primary"></i>' +
                    '<strong>' + fmtDisplay(rangoStart) + '</strong>' +
                    ' &rarr; <strong>' + fmtDisplay(rangoEnd) + '</strong>'
                );
                updateResumen();
            }
            renderRangoMultiMes();
        });

        /* ══════════════════════════════
           CALENDARIO OFICINA
        ══════════════════════════════ */
        function renderOficCalendar() {
            var yr = oficViewDate.getFullYear(),
                mo = oficViewDate.getMonth();
            $('#ofic-month-label').text(MONTHS_LONG[mo] + ' ' + yr);

            /* Calcular fin para resaltar rango en el calendario */
            var oficEnd = null;
            var mesesVal = parseInt($('#inp-ofic-meses').val());
            if (oficStart && mesesVal > 0) {
                oficEnd = new Date(oficStart);
                oficEnd.setMonth(oficEnd.getMonth() + mesesVal);
                oficEnd.setDate(oficEnd.getDate() - 1);
            }
            $('#ofic-cal-grid').html(buildMonthGrid(yr, mo, oficStart, oficEnd, false));
        }

        $('#btn-ofic-prev').on('click', function() {
            oficViewDate.setMonth(oficViewDate.getMonth() - 1);
            renderOficCalendar();
        });
        $('#btn-ofic-next').on('click', function() {
            oficViewDate.setMonth(oficViewDate.getMonth() + 1);
            renderOficCalendar();
        });

        $(document).on('click', '#ofic-cal-grid .hub-cal-day', function() {
            if ($(this).hasClass('past') || $(this).hasClass('empty')) return;
            var dateStr = $(this).data('date');
            if (!dateStr) return;

            oficStart = fromISO(dateStr);
            $('#inp-ofic-inicio').val(dateStr);
            calcOficEnd();
            renderOficCalendar();
            updateResumen();
        });

        /* Al escribir el número de meses → recalcular automáticamente */
        $('#inp-ofic-meses').on('input change', function() {
            calcOficEnd();
            renderOficCalendar();
            updateResumen();
        });

        function calcOficEnd() {
            if (!oficStart) {
                $('#inp-ofic-fin').val('');
                $('#ofic-calc-label').html('');
                $('#ofic-range-info').hide();
                return;
            }
            var meses = parseInt($('#inp-ofic-meses').val());
            if (isNaN(meses) || meses < 1) {
                $('#inp-ofic-fin').val('');
                $('#ofic-calc-label').html('');
                $('#ofic-range-info').hide();
                return;
            }

            /* Fecha fin = inicio + N meses - 1 día */
            var endDate = new Date(oficStart);
            endDate.setMonth(endDate.getMonth() + meses);
            endDate.setDate(endDate.getDate() - 1);

            $('#inp-ofic-fin').val(fmtISO(endDate));

            var label = '<i class="bx bx-calendar-check me-1 text-success"></i>' +
                'Del <strong>' + fmtDisplay(oficStart) + '</strong>' +
                ' al <strong>' + fmtDisplay(endDate) + '</strong>' +
                ' &nbsp;·&nbsp; ' +
                '<strong>' + meses + ' mes' + (meses !== 1 ? 'es' : '') + '</strong>';

            $('#ofic-calc-label').html(label);
            $('#ofic-range-text').html(label);
            $('#ofic-range-info').show();
        }

        /* ══════════════════════════════
           TURNOS DEL DÍA
        ══════════════════════════════ */
        function mostrarTurnosDia() {
            if (!activeEspacioId) return;
            $.post(URL_TURNOS + '?listar=true', {
                _id: activeEspacioId
            }, function(data) {
                var html = '';
                if (!data || data.length === 0) {
                    html = '<span class="text-muted small">Sin turnos asignados.</span>';
                } else {
                    $.each(data, function(_, t) {
                        html += '<span class="badge me-1 mb-1" style="background:#2d6a4f;">' +
                            (t.nombre || t.hub_tur_id) + ': ' +
                            (t.entrada || '') + ' – ' + (t.salida || '') +
                            '</span>';
                    });
                }
                $('#turnos-dia-list').html(html);
                $('#turnos-dia').show();
            }, 'json').fail(function() {
                $('#turnos-dia').hide();
            });
        }

        /* ══════════════════════════════
           RESUMEN
        ══════════════════════════════ */
        function updateResumen() {
            var html = '';

            if (activeTipoEspacio === 'oficina') {
                if (oficStart && $('#inp-ofic-fin').val()) {
                    var meses = parseInt($('#inp-ofic-meses').val());
                    var finDate = fromISO($('#inp-ofic-fin').val());
                    html = '<strong>Inicio:</strong> ' + fmtDisplay(oficStart) + '<br>' +
                        '<strong>Fin:</strong> ' + fmtDisplay(finDate) + '<br>' +
                        '<strong>Duración:</strong> ' + meses + ' mes' + (meses !== 1 ? 'es' : '') + '<br>' +
                        '<i class="bx bx-file me-1"></i>Se generará contrato.';
                } else if (oficStart) {
                    html = '<strong>Inicio:</strong> ' + fmtDisplay(oficStart) + '<br>' +
                        '<span class="text-muted">Ingresa el número de meses.</span>';
                } else {
                    html = 'Selecciona la fecha de inicio en el calendario.';
                }
            } else {
                var tipo = $('input[name="tipo_reserva"]:checked').val();
                if (tipo === 'dia' && diaSelected) {
                    html = '<strong>Fecha:</strong> ' + fmtDisplay(diaSelected) + '<br>' +
                        'Elige un turno y confirma.';
                } else if (tipo === 'rango' && rangoStart && rangoEnd) {
                    var dias = diffDias(rangoStart, rangoEnd);
                    html = '<strong>Desde:</strong> ' + fmtDisplay(rangoStart) + '<br>' +
                        '<strong>Hasta:</strong> ' + fmtDisplay(rangoEnd) + '<br>' +
                        '<strong>Total:</strong> ' + dias + ' día' + (dias !== 1 ? 's' : '');
                } else {
                    html = 'Selecciona una fecha para ver el resumen.';
                }
            }
            $('#resumen-reserva').html(html);
        }

        /* ══════════════════════════════
           RE-RENDER al abrir modal
        ══════════════════════════════ */
        $('#modalReserva').on('shown.bs.modal', function() {
            if (activeTipoEspacio === 'oficina') {
                renderOficCalendar();
            } else if ($('#r_rango').is(':checked')) {
                renderRangoMultiMes();
            } else {
                renderDiaCalendar();
            }
        });

        /* ══════════════════════════════
           CONFIRMAR RESERVA
        ══════════════════════════════ */
        $('#btn-confirmar').on('click', function() {

            if (activeTipoEspacio === 'oficina') {
                /* ── Validaciones oficina ── */
                if (!oficStart) {
                    Swal.fire('', 'Selecciona la fecha de inicio en el calendario.', 'warning');
                    return;
                }
                var meses = parseInt($('#inp-ofic-meses').val());
                if (isNaN(meses) || meses < 1) {
                    Swal.fire('', 'Ingresa el número de meses (mínimo 1).', 'warning');
                    return;
                }
                if (!$('#inp-contrato-num').val().trim()) {
                    Swal.fire('', 'Ingresa el número de contrato.', 'warning');
                    return;
                }
                if (!$('#inp-contrato-fecha').val()) {
                    Swal.fire('', 'Ingresa la fecha de firma del contrato.', 'warning');
                    return;
                }

                var finDate = fromISO($('#inp-ofic-fin').val());
                Swal.fire({
                    icon: 'success',
                    title: '¡Oficina reservada!',
                    html: '<b>' + fmtDisplay(oficStart) + '</b>' +
                        ' &rarr; <b>' + fmtDisplay(finDate) + '</b><br>' +
                        meses + ' mes' + (meses !== 1 ? 'es' : '') + '<br>' +
                        'Contrato: ' + $('#inp-contrato-num').val()
                });

            } else {
                /* ── Validaciones sala ── */
                var tipo = $('input[name="tipo_reserva"]:checked').val();

                if (tipo === 'dia') {
                    if (!diaSelected) {
                        Swal.fire('', 'Selecciona una fecha en el calendario.', 'warning');
                        return;
                    }

                    var parametros = {
                        'id_espacio': activeEspacioId,
                        'th_per_id': $('#ddl_clientes').val(),
                        'inicio': $('#txt_fecha_dia').val(),
                        'fin': $('#txt_fecha_dia').val(),
                    };
                    $.ajax({
                        data: {
                            parametros: parametros
                        },
                        url: '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?crear_reserva=true',
                        type: 'post',
                        dataType: 'json',
                        success: function(r) {
                            let mensaje = r[0].message;
                            Swal.fire({
                                icon: 'success',
                                title: mensaje,
                                /*html: fmtDisplay(diaSelected)*/
                            });
                        }
                    });



                } else {
                    if (!rangoStart || !rangoEnd) {
                        Swal.fire('', 'Selecciona la fecha de inicio y fin en el calendario.', 'warning');
                        return;
                    }
                    var diasTotal = diffDias(rangoStart, rangoEnd);

                    var parametros = {
                        'id_espacio': activeEspacioId,
                        'th_per_id': $('#ddl_clientes').val(),
                        'inicio': $('#txt_fecha_inicio').val(),
                        'fin': $('#txt_fecha_fin').val(),
                    };
                    $.ajax({
                        data: {
                            parametros: parametros
                        },
                        url: '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?crear_reserva=true',
                        type: 'post',
                        dataType: 'json',
                        success: function(r) {
                            let mensaje = r[0].message;
                            Swal.fire({
                                icon: 'success',
                                title: mensaje,
                                /*html: '<b>' + fmtDisplay(rangoStart) + '</b>' +
                                    ' &rarr; <b>' + fmtDisplay(rangoEnd) + '</b><br>' +
                                    diasTotal + ' día' + (diasTotal !== 1 ? 's' : '')*/
                            });
                        }
                    });

                }
            }



            $('#modalReserva').modal('hide').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset(); // Limpia el formulario
                $('#ddl_clientes').val(null).trigger('change'); // Limpia Select2 si usas uno
            });
        });

        /* ══════════════════════════════
           INIT
        ══════════════════════════════ */
        setStep(1);
        cargarUbicaciones();
    });
</script>