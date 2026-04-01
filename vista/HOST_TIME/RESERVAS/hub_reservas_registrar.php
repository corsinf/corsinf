<?php
$modulo_sistema = $_SESSION['INICIO']['MODULO_SISTEMA'];
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

        <!-- Breadcrumb -->
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

                        <!-- Step bar -->
                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas"
                            class="btn btn-outline-dark btn-sm mb-3">
                            <i class="bx bx-arrow-back"></i> Regresar
                        </a>
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
                                        <small class="fw-bold text-muted text-uppercase"><i class='bx bx-layer me-1'></i>Pisos</small>
                                    </div>
                                    <div class="card-body p-0" id="floor-list"></div>
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

    </div><!-- /page-content -->
</div><!-- /page-wrapper -->


<!-- ════════════════════════════════════════
     MODAL 1: GALERÍA DE IMÁGENES Y VIDEOS
════════════════════════════════════════ -->
<div class="modal fade" id="modalGaleria" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background:#111827;">

            <div class="modal-header border-0 pb-0 px-4 pt-3">
                <div>
                    <h6 class="modal-title fw-bold text-white" id="gal-nombre"></h6>
                    <small class="text-secondary" id="gal-codigo"></small>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <!-- Tabs -->
                <ul class="nav nav-tabs px-4 pt-2 border-0" id="gal-tabs" role="tablist"
                    style="border-bottom:1px solid #374151!important;">
                    <li class="nav-item">
                        <button class="nav-link active" id="tab-fotos-btn"
                            data-bs-toggle="tab" data-bs-target="#panel-fotos"
                            style="color:#9ca3af; background:transparent; border:none; border-bottom:2px solid transparent;">
                            <i class='bx bx-image me-1'></i>Fotos
                            <span class="badge bg-secondary ms-1" id="gal-fotos-count">0</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="tab-videos-btn"
                            data-bs-toggle="tab" data-bs-target="#panel-videos"
                            style="color:#9ca3af; background:transparent; border:none; border-bottom:2px solid transparent;">
                            <i class='bx bx-video me-1'></i>Videos
                            <span class="badge bg-secondary ms-1" id="gal-videos-count">0</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Panel Fotos -->
                    <div class="tab-pane fade show active p-3" id="panel-fotos">
                        <!-- Carousel principal -->
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
                        <!-- Thumbnails -->
                        <div class="d-flex gap-2 overflow-auto pb-1" id="gal-thumbs"
                            style="scrollbar-width:thin; scrollbar-color:#374151 transparent;"></div>
                        <!-- Vacío fotos -->
                        <div id="gal-fotos-empty" class="text-center py-5" style="display:none; color:#6b7280;">
                            <i class='bx bx-image fs-1 d-block mb-2'></i>
                            <p class="small mb-0">Sin imágenes cargadas</p>
                        </div>
                    </div>

                    <!-- Panel Videos -->
                    <div class="tab-pane fade p-3" id="panel-videos">
                        <div class="row g-3" id="gal-videos-list"></div>
                        <div id="gal-videos-empty" class="text-center py-5" style="display:none; color:#6b7280;">
                            <i class='bx bx-video-off fs-1 d-block mb-2'></i>
                            <p class="small mb-0">Sin videos cargados</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- ════════════════════════════════════════
     MODAL 2: SELECCIÓN DE TARIFA (pre-reserva)
════════════════════════════════════════ -->
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
                <!-- Loading -->
                <div id="tar-loading" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm me-2"></div>Cargando planes...
                </div>
                <!-- Vacío -->
                <div id="tar-empty" class="text-center py-4 text-muted" style="display:none;">
                    <i class='bx bx-info-circle fs-2 d-block mb-2 text-warning'></i>
                    <p class="small mb-0">Este espacio no tiene planes configurados.</p>
                    <p class="small text-muted">Contacta al administrador.</p>
                </div>
                <!-- Lista de tarifas -->
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


<!-- ════════════════════════════════════════
     MODAL 3: FORMULARIO DE RESERVA
════════════════════════════════════════ -->
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

                    <!-- Columna Izquierda: Calendario -->
                    <div class="col-md-7">
                        <p class="text-muted small fw-semibold text-uppercase mb-2">
                            <i class='bx bx-calendar me-1'></i>
                            <span id="res-cal-label">Selecciona una fecha</span>
                        </p>

                        <!-- INPUT FECHA DIRECTA -->
                        <div class="mb-2">
                            <input type="date" class="form-control form-control-sm"
                                id="inp-fecha-directa"
                                style="max-width:200px;">
                            <div class="text-danger small mt-1" id="err-fecha-directa" style="min-height:16px;"></div>
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

                        <!-- Hora inicio (solo para tarifas HORA) -->
                        <div id="seccion-hora" class="mt-3" style="display:none;">
                            <label class="form-label small fw-semibold">
                                <i class='bx bx-time me-1 text-primary'></i>Hora de inicio
                            </label>
                            <input type="time" class="form-control form-control-sm" id="inp-hora-inicio"
                                value="08:00" style="max-width:160px;">
                            <div id="label-hora-fin" class="mt-2 small"></div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Tarifa info + Cliente + Resumen -->
                    <div class="col-md-5">

                        <!-- Info tarifa seleccionada -->
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

                        <!-- Cliente -->
                        <div class="mb-3">
                            <label for="ddl_clientes" class="form-label small fw-semibold">
                                <i class='bx bx-user me-1'></i>Persona
                            </label>
                            <select class="form-select form-select-sm select2-validation"
                                id="ddl_clientes" name="ddl_clientes">
                                <option selected disabled>-- Seleccione --</option>
                            </select>
                        </div>

                        <!-- Período calculado -->
                        <div id="res-periodo" class="alert alert-info py-2 small mb-3" style="display:none;">
                            <i class='bx bx-calendar-check me-1'></i>
                            <span id="res-periodo-texto"></span>
                        </div>

                        <!-- Total -->
                        <div id="res-total-box" class="d-flex justify-content-between align-items-center
                             rounded p-2 mb-3" style="display:none!important;
                             background:rgba(13,110,253,.07); border:1px solid rgba(13,110,253,.2);">
                            <span class="small text-muted">Total</span>
                            <strong class="text-primary" id="res-total-valor">$0.00</strong>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-success" id="btn-confirmar" disabled>
                                <i class='bx bx-calendar-plus me-1'></i>Confirmar reserva
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ════════════════════════════════════════
     ESTILOS
════════════════════════════════════════ -->
<style>
    /* Calendarios */
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

    .hub-cal-day {
        text-align: center;
        padding: 7px 2px;
        font-size: .82rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background .15s;
        user-select: none
    }

    .hub-cal-day:hover:not(.empty):not(.past) {
        background: #f0f0f0
    }

    .hub-cal-day.empty,
    .hub-cal-day.past {
        color: #ccc;
        cursor: default
    }

    .hub-cal-day.today {
        font-weight: 700;
        color: #0d6efd
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

    /* DÍA SELECCIONADO (inicio) */
    .hub-cal-day.selected {
        background: #0d6efd !important;
        color: #fff !important;
        font-weight: 700;
        border-radius: 6px
    }

    .hub-cal-day.selected::after {
        display: none
    }

    /* RANGO INTERMEDIO (solo tarifas MES) */
    .hub-cal-day.in-range {
        background: rgba(13, 110, 253, .13) !important;
        color: #0d6efd;
        border-radius: 0
    }

    .hub-cal-day.in-range::after {
        display: none
    }

    /* DÍA FINAL DEL RANGO (solo tarifas MES) */
    .hub-cal-day.range-end {
        background: #0d6efd !important;
        color: #fff !important;
        font-weight: 700;
        border-radius: 6px
    }

    .hub-cal-day.range-end::after {
        display: none
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
        font-size: .88rem
    }

    .floor-item:hover {
        background: rgba(0, 0, 0, .04)
    }

    .floor-item.active {
        border-left-color: #0d6efd;
        background: rgba(13, 110, 253, .07);
        color: #0d6efd;
        font-weight: 600
    }

    /* Location cards */
    .loc-card {
        border: 1.5px solid #dee2e6;
        border-radius: .5rem;
        background: #fff;
        padding: 18px 20px;
        cursor: pointer;
        transition: all .2s;
        border-left: 4px solid #dee2e6
    }

    .loc-card:hover {
        border-left-color: #52b788;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, .08)
    }

    .loc-card.selected {
        border-left-color: #0d6efd;
        box-shadow: 0 4px 14px rgba(13, 110, 253, .12)
    }

    /* Space cards */
    .space-card {
        border: 1.5px solid #dee2e6;
        border-radius: .5rem;
        background: #fff;
        overflow: hidden;
        transition: all .2s
    }

    .space-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .09);
        border-color: #52b788
    }

    .space-img-wrap {
        width: 100%;
        height: 140px;
        overflow: hidden;
        background: #e9ecef
    }

    .space-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover
    }

    .space-img-placeholder {
        width: 100%;
        height: 140px;
        background: linear-gradient(135deg, #c5e1c8, #a8d5b5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: rgba(255, 255, 255, .7)
    }

    /* Type tabs */
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
        gap: 5px
    }

    .type-tab:hover {
        border-color: #52b788
    }

    .type-tab.active {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd
    }

    /* Galería thumbnails */
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

    /* Tarifa item */
    .tar-item.active,
    .tar-item:active {
        background: rgba(13, 110, 253, .08) !important;
        border-left: 3px solid #0d6efd !important
    }

    /* Total box show */
    #res-total-box.visible {
        display: flex !important
    }

    /* Nav tabs galería */
    #gal-tabs .nav-link.active {
        color: #fff !important;
        border-bottom: 2px solid #3b82f6 !important
    }
</style>


<!-- ════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════ -->
<script>
    $(function() {

        var URL_UBICACIONES = '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php';
        var URL_ESPACIOS = '../controlador/HOST_TIME/ESPACIOS/espaciosC.php';
        var URL_TURNOS = '../controlador/HOST_TIME/ESPACIOS/hub_espacios_turnosC.php';
        var URL_MEDIA = '../controlador/HOST_TIME/ESPACIOS/hub_espacios_mediaC.php';
        var URL_TARIFAS = '../controlador/HOST_TIME/ESPACIOS/hub_espacios_tarifasC.php';
        var URL_RESERVAS = '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php';

        var MONTHS = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        var DOW = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'];

        /* ── Estado global ── */
        var activeEspacioId = null;
        var activeEspacioNom = null;
        var activeEspacioCod = null;
        var activeTarifa = null;
        var resViewDate = new Date();
        var resSelected = null;

        /* ════════════════════════════
           HELPERS
        ════════════════════════════ */
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

        function fmtHuman(d) {
            return pad2(d.getDate()) + ' de ' + MONTHS[d.getMonth()] + ' ' + d.getFullYear();
        }

        function todayMN() {
            var t = new Date();
            t.setHours(0, 0, 0, 0);
            return t;
        }

        /* Calcula fecha fin del rango según tarifa */
        function calcEndDate(d) {
            if (!activeTarifa || activeTarifa.unidad_tiempo === 'HORA') return d;
            var e = new Date(d);
            e.setMonth(e.getMonth() + activeTarifa.cantidad);
            e.setDate(e.getDate() - 1);
            return e;
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
            }, 'json');
        }

        $(document).on('click', '.loc-card', function() {
            $('.loc-card').removeClass('selected');
            $(this).addClass('selected');
            selUbicacion = $(this).data('id');
            selPiso = null;
            selTipo = null;
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
                        html = '<div class="p-3 small text-muted text-center">Sin pisos.</div>';
                    } else {
                        $.each(data, function(_, p) {
                            html += '<div class="floor-item" data-id="' + p._id + '">' +
                                '<i class="bx bx-layer"></i><span>' + p.nombre_piso + '</span></div>';
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

        var selPiso = null;
        var selUbicacion = null;
        var selTipo = null;

        $(document).on('click', '.floor-item', function() {
            $('.floor-item').removeClass('active');
            $(this).addClass('active');
            selPiso = $(this).data('id');
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
                    var html = '<div class="type-tab active" data-tipo="0"><i class="bx bx-grid-alt"></i> Todos</div>';
                    $.each(data || [], function(_, t) {
                        html += '<div class="type-tab" data-tipo="' + t.id_tipo_espacio + '">' +
                            '<i class="bx bx-category"></i> ' + t.nombre + '</div>';
                    });
                    $('#type-tabs').html(html);
                }
            });
        }

        $(document).on('click', '.type-tab', function() {
            selTipo = $(this).data('tipo') == 0 ? null : $(this).data('tipo');
            $('.type-tab').removeClass('active');
            $(this).addClass('active');
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

                    // Filtro de tipo solo en cliente (ya viene filtrado por ubicación+piso desde el servidor)
                    if (selTipo) {
                        lista = $.grep(lista, function(e) {
                            return parseInt(e.id_tipo_espacio) === parseInt(selTipo);
                        });
                    }

                    if (!lista.length) {
                        $('#space-cards').html(
                            '<div class="col-12 text-center text-muted py-4">No hay espacios.</div>'
                        );
                        return;
                    }

                    var html = '';
                    $.each(lista, function(_, e) {
                        var imgWrap = e.imagen ?
                            '<div class="space-img-wrap"><img src="' + e.imagen + '" alt="' + e.nombre + '" loading="lazy"></div>' :
                            '<div class="space-img-placeholder"><i class="bx bx-building-house"></i></div>';

                        html +=
                            '<div class="col-md-4 col-sm-6">' +
                            '<div class="space-card">' +
                            imgWrap +
                            '<div class="p-3">' +
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
                    $('#space-cards').html(
                        '<div class="col-12 text-center text-danger py-4">Error al cargar espacios.</div>'
                    );
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
            $('#carousel-fotos-inner').html('');
            $('#gal-indicators').html('');
            $('#gal-thumbs').html('');
            $('#gal-videos-list').html('');
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
                            var actCar = i === 0 ? 'active' : '';
                            var actInd = i === 0 ? 'active' : '';
                            carHtml +=
                                '<div class="carousel-item h-100 ' + actCar + '">' +
                                '<img src="' + f.url_archivo + '" class="d-block w-100 h-100"' +
                                ' style="object-fit:contain;" alt="' + escAttr(f.nombre_archivo) + '">' +
                                (f.es_principal == 1 ?
                                    '<div class="carousel-caption d-none d-md-block pb-1">' +
                                    '<span class="badge bg-warning text-dark"><i class="bx bxs-star me-1"></i>Principal</span></div>' :
                                    '') +
                                '</div>';
                            indHtml +=
                                '<button type="button" data-bs-target="#carousel-fotos"' +
                                ' data-bs-slide-to="' + i + '" class="' + actInd + '"' +
                                (i === 0 ? ' aria-current="true"' : '') + ' aria-label="Foto ' + i + '"></button>';
                            thumbHtml +=
                                '<div class="gal-thumb' + (i === 0 ? ' active' : '') + '" data-idx="' + i + '">' +
                                '<img src="' + f.url_archivo + '" alt="" loading="lazy">' +
                                '</div>';
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
                                '<div class="col-md-6">' +
                                '<div class="card border">' +
                                '<div class="ratio ratio-16x9">' +
                                '<video controls preload="metadata" class="rounded-top"' +
                                ' style="background:#000; object-fit:contain;">' +
                                '<source src="' + v.url_archivo + '" type="video/' + v.formato + '">' +
                                '</video></div>' +
                                '<div class="card-footer py-1 px-2">' +
                                '<small class="text-muted text-truncate d-block">' +
                                '<i class="bx bx-video me-1"></i>' + v.nombre_archivo +
                                '</small></div></div></div>';
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
           MODAL TARIFAS (pre-reserva)
        ════════════════════════════ */
        $(document).on('click', '.btn-pre-reservar', function() {
            activeEspacioId = $(this).data('id');
            activeEspacioNom = $(this).data('nombre');
            activeEspacioCod = $(this).data('codigo');
            activeTarifa = null;

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
            $('#modalTarifas').modal('show');
        });

        function renderListaTarifas(tarifas) {
            var html = '';
            tarifas.forEach(function(t) {
                var unidad = t.unidad_tiempo === 'HORA' ? 'hora' : 'mes';
                var duracion = t.cantidad > 1 ? t.cantidad + ' ' + unidad + 's' : '1 ' + unidad;
                var icono = t.unidad_tiempo === 'HORA' ? 'bx-time' : 'bx-calendar';
                html +=
                    '<button type="button" class="list-group-item list-group-item-action tar-item py-2 px-3"' +
                    ' data-id="' + t._id + '" data-nombre="' + escAttr(t.nombre_plan) + '"' +
                    ' data-precio="' + t.precio + '" data-cantidad="' + t.cantidad + '"' +
                    ' data-unidad="' + t.unidad_tiempo + '">' +
                    '<div class="d-flex align-items-center gap-3">' +
                    '<div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"' +
                    ' style="width:38px;height:38px;">' +
                    '<i class="bx ' + icono + ' text-primary"></i></div>' +
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

            $('#btn-confirmar').prop('disabled', true);
            $('#res-periodo').hide();
            $('#res-total-box').removeClass('visible');
            $('#label-hora-fin').html('');
            $('#inp-hora-inicio').val('08:00');
            $('#inp-fecha-directa').val('');
            $('#err-fecha-directa').text('');
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
            } else {
                $('#res-cal-label').text('Selecciona la fecha de inicio');
                $('#seccion-hora').hide();
            }

            renderResCalendar();
            $('#modalReserva').modal('show');
        }

        /* ════════════════════════════
           CALENDARIO — render
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

        /* ════════════════════════════
           CLICK DÍA EN CALENDARIO
        ════════════════════════════ */
        $(document).on('click', '#res-cal-grid .hub-cal-day', function() {
            if ($(this).hasClass('past') || $(this).hasClass('empty')) return;
            var ds = $(this).data('date');
            if (!ds) return;
            resSelected = fromISO(ds);
            // Sincronizar el input de texto
            $('#inp-fecha-directa').val(ds);
            $('#err-fecha-directa').text('');
            renderResCalendar();
            calcularPeriodo();
        });

        /* ════════════════════════════
           INPUT FECHA DIRECTA
        ════════════════════════════ */
        $('#inp-fecha-directa').on('change', function() {
            var val = $(this).val();
            var $err = $('#err-fecha-directa');

            if (!val) {
                resSelected = null;
                $err.text('');
                renderResCalendar();
                calcularPeriodo();
                return;
            }

            var d = fromISO(val);
            var t = todayMN();

            if (d < t) {
                $err.text('La fecha no puede ser anterior a hoy.');
                $(this).val('');
                resSelected = null;
                renderResCalendar();
                calcularPeriodo();
                return;
            }

            $err.text('');
            resSelected = d;
            // Navegar el calendario al mes de la fecha escrita
            resViewDate = new Date(d.getFullYear(), d.getMonth(), 1);
            renderResCalendar();
            calcularPeriodo();
        });

        /* ════════════════════════════
           RECALCULAR AL CAMBIAR HORA
        ════════════════════════════ */
        $('#inp-hora-inicio').on('change input', function() {
            calcularPeriodo();
        });

        /* ════════════════════════════
           CALCULAR PERÍODO
        ════════════════════════════ */
        function calcularPeriodo() {
            if (!resSelected || !activeTarifa) {
                $('#res-periodo').hide();
                $('#res-total-box').removeClass('visible');
                $('#btn-confirmar').prop('disabled', true);
                return;
            }

            var texto = '';
            var fechaIni = fmtHuman(resSelected);

            if (activeTarifa.unidad_tiempo === 'HORA') {
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
                    '<i class="bx bx-time me-1 text-primary"></i>' +
                    'Fin: ' + (labelFin || '') + horaFinStr + '</span>');
            } else {
                var endDate = calcEndDate(resSelected);
                var fechaFin = fmtHuman(endDate);
                texto = 'Del <strong>' + fechaIni + '</strong> al <strong>' + fechaFin + '</strong>' +
                    ' &nbsp;·&nbsp; ' + activeTarifa.cantidad + ' mes' + (activeTarifa.cantidad !== 1 ? 'es' : '');
            }

            $('#res-periodo-texto').html(texto);
            $('#res-periodo').show();
            $('#res-total-valor').text('$' + activeTarifa.precio.toFixed(2));
            $('#res-total-box').addClass('visible');
            $('#btn-confirmar').prop('disabled', false);
        }

        /* ════════════════════════════
           BUILD MONTH GRID (corregido)
        ════════════════════════════ */
        function buildMonthGrid(year, month) {
            var t = todayMN();
            var endDate = (resSelected && activeTarifa && activeTarifa.unidad_tiempo !== 'HORA') ?
                calcEndDate(resSelected) : null;

            var html = '';
            DOW.forEach(function(d) {
                html += '<div class="hub-cal-head">' + d + '</div>';
            });

            var startDow = (new Date(year, month, 1).getDay() + 6) % 7;
            var days = new Date(year, month + 1, 0).getDate();

            for (var i = 0; i < startDow; i++) html += '<div class="hub-cal-day empty"></div>';

            for (var d = 1; d <= days; d++) {
                var dt = new Date(year, month, d);
                var ds = fmtISO(dt);
                var cls = 'hub-cal-day';

                if (dt < t) {
                    cls += ' past';
                    html += '<div class="' + cls + '">' + d + '</div>';
                    continue;
                }

                if (dt.getTime() === t.getTime()) cls += ' today';

                if (resSelected && dt.getTime() === resSelected.getTime()) {
                    // Día de inicio seleccionado
                    cls += ' selected';
                } else if (endDate && resSelected) {
                    if (dt > resSelected && dt.getTime() === endDate.getTime()) {
                        // Día final del rango
                        cls += ' range-end';
                    } else if (dt > resSelected && dt < endDate) {
                        // Días intermedios del rango
                        cls += ' in-range';
                    }
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

            var fechaIni = fmtISO(resSelected);
            var fechaFin = fechaIni;

            if (activeTarifa.unidad_tiempo === 'HORA') {
                var horaIni = $('#inp-hora-inicio').val() || '08:00';
                var p = horaIni.split(':');
                var totalMins = parseInt(p[0]) * 60 + parseInt(p[1]) + activeTarifa.cantidad * 60;
                var diasExtra = Math.floor(totalMins / 1440);
                if (diasExtra > 0) {
                    var dFin2 = new Date(resSelected);
                    dFin2.setDate(dFin2.getDate() + diasExtra);
                    fechaFin = fmtISO(dFin2);
                }
            } else {
                var endDate2 = calcEndDate(resSelected);
                fechaFin = fmtISO(endDate2);
            }

            var params = {
                id_espacio: activeEspacioId,
                th_per_id: $('#ddl_clientes').val(),
                inicio: fechaIni,
                fin: fechaFin,
                id_tarifa: activeTarifa._id,
                costo_total: activeTarifa.precio
            };

            $.ajax({
                url: URL_RESERVAS + '?crear_reserva=true',
                type: 'post',
                dataType: 'json',
                data: {
                    parametros: params
                },
                success: function(r) {
                    var msg = (r && r[0] && r[0].message) ? r[0].message : 'Reserva registrada.';
                    Swal.fire({
                        icon: 'success',
                        title: msg
                    });
                    $('#modalReserva').modal('hide');
                },
                error: function() {
                    Swal.fire('', 'Error al guardar la reserva.', 'error');
                }
            });
        });

        /* ════════════════════════════
           HELPER
        ════════════════════════════ */
        function escAttr(s) {
            return (s || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        function resetearSeleccion() {
            resSelected = null;
            activeTarifa = null;
            activeEspacioId = null;
            activeEspacioNom = null;
            activeEspacioCod = null;
            resViewDate = new Date();
            resViewDate.setHours(0, 0, 0, 0);
        }
        $(document).on('click', '.loc-card', function() {
            $('.loc-card').removeClass('selected');
            $(this).addClass('selected');
            selUbicacion = $(this).data('id');
            selPiso = null;
            selTipo = null;

            // ── Limpiar espacios y selección
            resetearSeleccion();
            $('#space-cards').html('');
            $('#type-tabs').html('');
            $('#floor-list .floor-item').removeClass('active');

            setStep(2);
            cargarPisos(selUbicacion);
        });
        $(document).on('click', '.floor-item', function() {
            $('.floor-item').removeClass('active');
            $(this).addClass('active');
            selPiso = $(this).data('id');
            selTipo = null;

            // ── Limpiar espacios y selección
            resetearSeleccion();
            $('#space-cards').html('');
            $('.type-tab').removeClass('active');

            setStep(3);
            cargarTipos();
            renderEspacios();
        });

        /* ════════════════════════════
           INIT
        ════════════════════════════ */
        setStep(1);
        cargarUbicaciones();
    });
</script>