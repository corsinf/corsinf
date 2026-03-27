    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ── TOP BAR ── */
        .topbar {
            background: var(--ink);
            color: #fff;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar .logo {
            font-family: 'DM Serif Display', serif;
            font-size: 1.35rem;
            letter-spacing: .5px;
        }

        .topbar .breadcrumb-bar {
            font-size: .82rem;
            color: #aaa;
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .breadcrumb-bar .sep {
            color: #555;
        }

        /* ── STEP INDICATOR ── */
        .step-bar {
            background: var(--sand);
            border-bottom: 1px solid var(--border);
            padding: 10px 28px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .82rem;
            color: var(--muted);
            flex-wrap: wrap;
        }

        .step-pill {
            padding: 4px 14px;
            border-radius: 20px;
            border: 1px solid var(--border);
            background: #fff;
            cursor: default;
            transition: all .2s;
            white-space: nowrap;
        }

        .step-pill.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .step-pill.done {
            background: var(--accent2);
            color: #fff;
            border-color: var(--accent2);
        }

        .step-arrow {
            color: var(--border);
        }

        /* ── MAIN LAYOUT ── */
        .main-wrap {
            display: flex;
            min-height: calc(100vh - 94px);
        }

        /* LEFT SIDEBAR – pisos */
        .sidebar {
            width: 220px;
            min-width: 220px;
            background: var(--sand);
            border-right: 1px solid var(--border);
            padding: 20px 0;
            transition: width .3s, opacity .3s;
            overflow: hidden;
        }

        .sidebar.hidden {
            width: 0;
            min-width: 0;
            opacity: 0;
            padding: 0;
        }

        .sidebar-title {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--muted);
            padding: 0 20px 10px;
        }

        .floor-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: all .15s;
            font-size: .88rem;
        }

        .floor-item:hover {
            background: rgba(0, 0, 0, .04);
        }

        .floor-item.active {
            border-left-color: var(--accent);
            background: rgba(45, 106, 79, .07);
            color: var(--accent);
            font-weight: 600;
        }

        .floor-badge {
            margin-left: auto;
            background: var(--border);
            color: var(--muted);
            font-size: .7rem;
            padding: 1px 7px;
            border-radius: 10px;
        }

        .floor-item.active .floor-badge {
            background: var(--accent2);
            color: #fff;
        }

        /* CONTENT AREA */
        .content-area {
            flex: 1;
            padding: 28px;
            overflow-y: auto;
        }

        /* LOCATION CARDS */
        .loc-card {
            border: 1.5px solid var(--border);
            border-radius: var(--card-r);
            background: #fff;
            padding: 22px 24px;
            cursor: pointer;
            transition: all .2s;
            position: relative;
            overflow: hidden;
        }

        .loc-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--border);
            transition: background .2s;
        }

        .loc-card:hover {
            border-color: var(--accent2);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .07);
        }

        .loc-card:hover::before {
            background: var(--accent2);
        }

        .loc-card.selected {
            border-color: var(--accent);
            box-shadow: 0 6px 24px rgba(45, 106, 79, .12);
        }

        .loc-card.selected::before {
            background: var(--accent);
        }

        .loc-card .loc-name {
            font-family: 'DM Serif Display', serif;
            font-size: 1.15rem;
            margin-bottom: 6px;
        }

        .loc-card .loc-meta {
            font-size: .82rem;
            color: var(--muted);
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .loc-card .loc-meta i {
            color: var(--accent2);
            margin-right: 5px;
        }

        .loc-card .loc-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            background: var(--sand);
            font-size: .7rem;
            color: var(--muted);
            padding: 2px 10px;
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        /* SPACE TYPE TABS */
        .type-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .type-tab {
            padding: 7px 18px;
            border-radius: 30px;
            border: 1.5px solid var(--border);
            background: #fff;
            font-size: .84rem;
            cursor: pointer;
            transition: all .15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .type-tab:hover {
            border-color: var(--accent2);
            color: var(--accent);
        }

        .type-tab.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        /* SPACE CARDS */
        .space-card {
            border: 1.5px solid var(--border);
            border-radius: var(--card-r);
            background: #fff;
            overflow: hidden;
            transition: all .2s;
            cursor: pointer;
        }

        .space-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0, 0, 0, .09);
            border-color: var(--accent2);
        }

        .space-card.expanded {
            border-color: var(--accent);
            box-shadow: 0 8px 28px rgba(45, 106, 79, .13);
        }

        .space-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            background: linear-gradient(135deg, #c5e1c8, #a8d5b5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: rgba(255, 255, 255, .6);
        }

        .space-img img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .space-body {
            padding: 14px 16px;
        }

        .space-name {
            font-family: 'DM Serif Display', serif;
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .space-meta {
            font-size: .78rem;
            color: var(--muted);
            display: flex;
            gap: 12px;
        }

        .space-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* SPACE DETAIL EXPAND */
        .space-detail {
            max-height: 0;
            overflow: hidden;
            transition: max-height .35s ease;
            background: var(--sand);
            border-top: 1px solid var(--border);
        }

        .space-detail.open {
            max-height: 400px;
        }

        .space-detail-inner {
            padding: 14px 16px;
        }

        .amenity-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 4px 10px;
            font-size: .78rem;
            margin: 3px 3px 3px 0;
        }

        .amenity-tag i {
            color: var(--accent2);
        }

        /* TARIFA */
        .tarifa-row {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .tarifa-chip {
            background: var(--accent);
            color: #fff;
            border-radius: 8px;
            padding: 4px 12px;
            font-size: .78rem;
            font-weight: 600;
        }

        .tarifa-chip.day {
            background: var(--accent2);
        }

        /* CALENDAR MODAL */
        .modal-content {
            border-radius: 16px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 18px 24px;
        }

        .modal-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.2rem;
        }

        .modal-body {
            padding: 20px 24px;
        }

        /* MINI CALENDAR */
        .cal-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .cal-nav .cal-month {
            font-family: 'DM Serif Display', serif;
            font-size: 1rem;
        }

        .cal-nav button {
            background: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cal-nav button:hover {
            background: var(--sand);
        }

        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
        }

        .cal-day-head {
            text-align: center;
            font-size: .7rem;
            font-weight: 600;
            color: var(--muted);
            padding: 4px 0;
            text-transform: uppercase;
        }

        .cal-day {
            text-align: center;
            padding: 6px 2px;
            font-size: .82rem;
            border-radius: 8px;
            cursor: pointer;
            position: relative;
            transition: background .15s;
        }

        .cal-day:hover:not(.empty):not(.past) {
            background: var(--sand);
        }

        .cal-day.empty {
            cursor: default;
        }

        .cal-day.past {
            color: #ccc;
            cursor: default;
        }

        .cal-day.today {
            font-weight: 700;
            color: var(--accent);
        }

        .cal-day.has-shift::after {
            content: '';
            display: block;
            width: 5px;
            height: 5px;
            background: var(--accent2);
            border-radius: 50%;
            margin: 2px auto 0;
        }

        .cal-day.selected {
            background: var(--accent);
            color: #fff;
        }

        .cal-day.in-range {
            background: rgba(45, 106, 79, .12);
        }

        .cal-day.range-start,
        .cal-day.range-end {
            background: var(--accent);
            color: #fff;
        }

        .cal-day.reserved {
            background: rgba(82, 183, 136, .15);
        }

        /* TURNOS EN DÍA */
        .turno-chip {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: .74rem;
            color: #fff;
            margin: 2px;
        }

        .reserve-option label {
            font-size: .88rem;
        }

        .section-label {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 12px;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--border);
            margin-bottom: 12px;
        }
    </style>

    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">RESERVAS</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Todos las reservas
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">

                                <h5 class="mb-0 text-primary"></h5>
                                <!--
                                <div class="row mx-0">

                                    <div class="" id="btn_nuevo">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_registrar_espacio"
                                            type="button" class="btn btn-success btn-sm ">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </a>
                                    </div>
                                </div>
-->
                            </div>


                            <section class="content pt-2">
                                <div class="container-fluid">
                                    <div class="topbar">
                                        <span class="logo"><i class='bx bx-building-house me-2'></i>Hub · Espacios</span>
                                        <div class="breadcrumb-bar">
                                            <span id="bc-loc">Ubicaciones</span>
                                            <span class="sep" id="bc-sep1" style="display:none">›</span>
                                            <span id="bc-floor" style="display:none"></span>
                                            <span class="sep" id="bc-sep2" style="display:none">›</span>
                                            <span id="bc-type" style="display:none"></span>
                                        </div>
                                    </div>
                                    <!-- STEP BAR -->
                                    <div class="step-bar">
                                        <span class="step-pill active" id="st1"><i class='bx bx-map me-1'></i>Ubicación</span>
                                        <span class="step-arrow">›</span>
                                        <span class="step-pill" id="st2"><i class='bx bx-layer me-1'></i>Piso</span>
                                        <span class="step-arrow">›</span>
                                        <span class="step-pill" id="st3"><i class='bx bx-category me-1'></i>Tipo</span>
                                        <span class="step-arrow">›</span>
                                        <span class="step-pill" id="st4"><i class='bx bx-door-open me-1'></i>Espacio</span>
                                    </div>

                                    <!-- MAIN -->
                                    <div class="main-wrap">

                                        <!-- SIDEBAR PISOS -->
                                        <div class="sidebar hidden" id="sidebar">
                                            <div class="sidebar-title"><i class='bx bx-layer me-1'></i>Pisos</div>
                                            <div id="floor-list"></div>
                                        </div>

                                        <!-- CONTENT -->
                                        <div class="content-area" id="content-area">

                                            <!-- STEP 1: UBICACIONES -->
                                            <div id="view-locations">
                                                <div class="section-label mb-3"><i class='bx bx-map-pin me-1'></i>Selecciona una ubicación</div>
                                                <div class="row g-3" id="loc-cards"></div>
                                            </div>

                                            <!-- STEP 3: TIPOS + ESPACIOS (hidden initially) -->
                                            <div id="view-spaces" style="display:none;">
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="goBackToLocations()">
                                                        <i class='bx bx-arrow-back'></i>
                                                    </button>
                                                    <span class="section-label mb-0"><i class='bx bx-category me-1'></i>Tipo de espacio</span>
                                                </div>
                                                <div class="type-tabs" id="type-tabs"></div>
                                                <div class="section-label"><i class='bx bx-grid-alt me-1'></i>Espacios disponibles</div>
                                                <div class="row g-3" id="space-cards"></div>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- MODAL RESERVA -->
                                    <div class="modal fade" id="modalReserva" tabindex="-1" data-bs-backdrop="static">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div>
                                                        <div class="modal-title" id="modal-space-name">Reservar espacio</div>
                                                        <div class="text-muted" style="font-size:.8rem;" id="modal-space-sub"></div>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-4">

                                                        <!-- LEFT: calendario -->
                                                        <div class="col-md-7">
                                                            <div class="section-label"><i class='bx bx-calendar me-1'></i>Selecciona fecha(s)</div>
                                                            <div class="cal-nav">
                                                                <button onclick="calPrev()"><i class='bx bx-chevron-left'></i></button>
                                                                <span class="cal-month" id="cal-month-label"></span>
                                                                <button onclick="calNext()"><i class='bx bx-chevron-right'></i></button>
                                                            </div>
                                                            <div class="cal-grid" id="cal-grid">
                                                                <div class="cal-day-head">Lu</div>
                                                                <div class="cal-day-head">Ma</div>
                                                                <div class="cal-day-head">Mi</div>
                                                                <div class="cal-day-head">Ju</div>
                                                                <div class="cal-day-head">Vi</div>
                                                                <div class="cal-day-head">Sá</div>
                                                                <div class="cal-day-head">Do</div>
                                                            </div>
                                                            <!-- Turnos del día seleccionado -->
                                                            <div id="turnos-dia" class="mt-3" style="display:none;">
                                                                <div class="section-label"><i class='bx bx-time me-1'></i>Turnos disponibles ese día</div>
                                                                <div id="turnos-dia-list"></div>
                                                            </div>
                                                        </div>

                                                        <!-- RIGHT: opciones -->
                                                        <div class="col-md-5">
                                                            <div class="section-label"><i class='bx bx-slider-alt me-1'></i>Tipo de reserva</div>
                                                            <div class="mb-3">
                                                                <div class="form-check reserve-option mb-2">
                                                                    <input class="form-check-input" type="radio" name="tipo_reserva" id="r_dia" value="dia" checked onchange="toggleTipoReserva()">
                                                                    <label class="form-check-label" for="r_dia"><i class='bx bx-calendar-check me-1 text-success'></i>Por un día</label>
                                                                </div>
                                                                <div class="form-check reserve-option">
                                                                    <input class="form-check-input" type="radio" name="tipo_reserva" id="r_rango" value="rango" onchange="toggleTipoReserva()">
                                                                    <label class="form-check-label" for="r_rango"><i class='bx bx-calendar-alt me-1 text-primary'></i>Por rango de fechas</label>
                                                                </div>
                                                            </div>

                                                            <!-- Por día: fecha única -->
                                                            <div id="opt-dia">
                                                                <label class="form-label" style="font-size:.84rem;">Fecha</label>
                                                                <input type="date" class="form-control form-control-sm mb-2" id="inp-fecha-dia">
                                                            </div>

                                                            <!-- Por rango -->
                                                            <div id="opt-rango" style="display:none;">
                                                                <label class="form-label" style="font-size:.84rem;">Fecha inicio</label>
                                                                <input type="date" class="form-control form-control-sm mb-2" id="inp-fecha-ini" onchange="syncRange()">
                                                                <label class="form-label" style="font-size:.84rem;">Fecha fin</label>
                                                                <input type="date" class="form-control form-control-sm mb-2" id="inp-fecha-fin" onchange="syncRange()">
                                                            </div>

                                                            <hr class="my-3">
                                                            <div class="section-label"><i class='bx bx-receipt me-1'></i>Resumen</div>
                                                            <div id="resumen-reserva" class="small text-muted">Selecciona una fecha para ver el resumen.</div>

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
                                </div><!-- /.container-fluid -->
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>



    <script>
        const UBICACIONES = [{
                id: 1,
                nombre: 'Sede Norte',
                direccion: 'Av. Atahualpa 1200 y Av. El Maestro',
                telefono: '(02) 234-5678',
                pisos: 3
            },
            {
                id: 2,
                nombre: 'Sede Centro',
                direccion: 'Calle Bolívar 540 y García Moreno',
                telefono: '(02) 222-1100',
                pisos: 4
            },
            {
                id: 3,
                nombre: 'Sede Sur',
                direccion: 'Av. Napo km 3, Parque Industrial',
                telefono: '(02) 298-7654',
                pisos: 2
            },
        ];

        const PISOS = {
            1: [{
                id: 1,
                desc: 'Planta Baja',
                espacios: 4
            }, {
                id: 2,
                desc: 'Piso 1',
                espacios: 3
            }, {
                id: 3,
                desc: 'Piso 2',
                espacios: 5
            }],
            2: [{
                id: 4,
                desc: 'Planta Baja',
                espacios: 6
            }, {
                id: 5,
                desc: 'Piso 1',
                espacios: 4
            }, {
                id: 6,
                desc: 'Piso 2',
                espacios: 3
            }, {
                id: 7,
                desc: 'Piso 3',
                espacios: 2
            }],
            3: [{
                id: 8,
                desc: 'Planta Baja',
                espacios: 5
            }, {
                id: 9,
                desc: 'Piso 1',
                espacios: 3
            }],
        };

        const TIPOS = [{
                id: 1,
                nombre: 'Sala de reuniones',
                icon: 'bx-group'
            },
            {
                id: 2,
                nombre: 'Oficina privada',
                icon: 'bx-door-open'
            },
            {
                id: 3,
                nombre: 'Auditorio',
                icon: 'bx-microphone'
            },
            {
                id: 4,
                nombre: 'Coworking',
                icon: 'bx-laptop'
            },
        ];

        const AMENITIES = {
            1: ['40 sillas', 'Proyector HD', 'Pizarrón inteligente', 'Aire acondicionado', 'Wi-Fi 200Mbps', 'Cafetera'],
            2: ['12 sillas', 'TV 65"', 'Videoconferencia', 'Wi-Fi dedicado', 'Impresora'],
            3: ['Podio + micrófonos', '80 sillas', 'Sistema de audio', 'Proyector 4K', 'Cabina de traducción', 'Catering disponible'],
            4: ['20 escritorios', 'Salas de enfoque', 'Wi-Fi 500Mbps', 'Cocina equipada', 'Casilleros', 'Sala de descanso'],
        };

        // Espacios por ubicación+piso+tipo (simulado)
        function getEspacios(ubid, pisoid, tipoid) {
            const nombres = ['Espacio Alpha', 'Espacio Beta', 'Sala Quito', 'Sala Cuenca', 'Suite Azul', 'Suite Verde', 'Hub Central'];
            const codigos = ['ESP-01', 'ESP-02', 'ESP-03', 'ESP-04', 'ESP-05', 'ESP-06', 'ESP-07'];
            const cap = [10, 15, 8, 20, 5, 12, 30];
            const th = [8.50, 10, 7, 15, 5, 9, 12];
            const td = [65, 80, 55, 120, 40, 72, 95];
            // generate 2-4 spaces
            const n = 2 + ((ubid + pisoid + tipoid) % 3);
            return Array.from({
                length: n
            }, (_, i) => ({
                id: ubid * 100 + pisoid * 10 + tipoid * 1 + i,
                nombre: nombres[(ubid + pisoid + i) % nombres.length],
                codigo: codigos[i],
                capacidad: cap[(ubid + i) % cap.length],
                tarifa_hora: th[(pisoid + i) % th.length],
                tarifa_dia: td[(tipoid + i) % td.length],
                id_tipo: tipoid,
                imagen: null,
            }));
        }

        // Turnos mock
        const TURNOS = [{
                id: 1,
                nombre: 'Mañana',
                entrada: '07:00',
                salida: '12:00',
                color: '#2d6a4f'
            },
            {
                id: 2,
                nombre: 'Tarde',
                entrada: '13:00',
                salida: '18:00',
                color: '#1d3557'
            },
            {
                id: 3,
                nombre: 'Full Day',
                entrada: '07:00',
                salida: '18:00',
                color: '#c77dff'
            },
        ];

        // Reservas estáticas mock
        const RESERVAS = {};

        /* ════════════════════════════════════════
           STATE
        ════════════════════════════════════════ */
        let selUbicacion = null,
            selPiso = null,
            selTipo = null,
            selEspacio = null;

        /* ════════════════════════════════════════
           INIT
        ════════════════════════════════════════ */
        renderUbicaciones();

        function renderUbicaciones() {
            const cont = document.getElementById('loc-cards');
            cont.innerHTML = UBICACIONES.map(u => `
    <div class="col-md-4 col-sm-6">
      <div class="loc-card" id="loc-${u.id}" onclick="selectUbicacion(${u.id})">
        <span class="loc-badge">${PISOS[u.id].length} pisos</span>
        <div class="loc-name">${u.nombre}</div>
        <div class="loc-meta">
          <span><i class='bx bx-map-pin'></i>${u.direccion}</span>
          <span><i class='bx bx-phone'></i>${u.telefono}</span>
        </div>
      </div>
    </div>`).join('');
        }

        function selectUbicacion(id) {
            selUbicacion = id;
            selPiso = null;
            selTipo = null;
            selEspacio = null;

            document.querySelectorAll('.loc-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('loc-' + id).classList.add('selected');

            // render floors
            const sidebar = document.getElementById('sidebar');
            const fl = document.getElementById('floor-list');
            fl.innerHTML = PISOS[id].map(p => `
    <div class="floor-item" id="fl-${p.id}" onclick="selectPiso(${p.id})">
      <i class='bx bx-layer'></i> ${p.desc}
      <span class="floor-badge">${p.espacios}</span>
    </div>`).join('');
            sidebar.classList.remove('hidden');

            // update breadcrumb
            const u = UBICACIONES.find(x => x.id === id);
            document.getElementById('bc-loc').textContent = u.nombre;

            setStep(2);
        }

        function selectPiso(id) {
            selPiso = id;
            selTipo = null;

            document.querySelectorAll('.floor-item').forEach(f => f.classList.remove('active'));
            document.getElementById('fl-' + id).classList.add('active');

            const piso = Object.values(PISOS).flat().find(p => p.id === id);
            document.getElementById('bc-floor').textContent = piso.desc;
            document.getElementById('bc-sep1').style.display = '';
            document.getElementById('bc-floor').style.display = '';

            setStep(3);
            renderViewSpaces();
        }

        function renderViewSpaces() {
            document.getElementById('view-locations').style.display = 'none';
            document.getElementById('view-spaces').style.display = 'block';

            // type tabs
            const tabs = document.getElementById('type-tabs');
            tabs.innerHTML = TIPOS.map(t => `
    <div class="type-tab ${selTipo===t.id?'active':''}" id="tt-${t.id}" onclick="selectTipo(${t.id})">
      <i class='bx ${t.icon}'></i> ${t.nombre}
    </div>`).join('');

            renderEspacios();
        }

        function selectTipo(id) {
            selTipo = id;
            document.querySelectorAll('.type-tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tt-' + id).classList.add('active');

            const tipo = TIPOS.find(t => t.id === id);
            document.getElementById('bc-type').textContent = tipo.nombre;
            document.getElementById('bc-sep2').style.display = '';
            document.getElementById('bc-type').style.display = '';

            setStep(4);
            renderEspacios();
        }

        function renderEspacios() {
            const cont = document.getElementById('space-cards');
            if (!selPiso) {
                cont.innerHTML = '';
                return;
            }
            const espacios = selTipo ? getEspacios(selUbicacion, selPiso, selTipo) : getEspacios(selUbicacion, selPiso, 1).concat(getEspacios(selUbicacion, selPiso, 2));

            if (!espacios.length) {
                cont.innerHTML = `<div class="col-12 empty-state"><i class='bx bx-folder-open'></i><p>No hay espacios disponibles</p></div>`;
                return;
            }

            cont.innerHTML = espacios.map(e => {
                const amenidades = AMENITIES[e.id_tipo] || AMENITIES[1];
                return `
    <div class="col-md-4 col-sm-6">
      <div class="space-card" id="sc-${e.id}">
        <div class="space-img" onclick="toggleEspacio(${e.id})">
          <i class='bx bx-building-house'></i>
        </div>
        <div class="space-body" onclick="toggleEspacio(${e.id})">
          <div class="space-name">${e.nombre}</div>
          <div class="space-meta">
            <span><i class='bx bx-user'></i>${e.capacidad} personas</span>
            <span><i class='bx bx-hash'></i>${e.codigo}</span>
          </div>
        </div>
        <div class="space-detail" id="sd-${e.id}">
          <div class="space-detail-inner">
            <div class="section-label mb-2"><i class='bx bx-star me-1'></i>Servicios incluidos</div>
            ${amenidades.map(a=>`<span class="amenity-tag"><i class='bx bx-check-circle'></i>${a}</span>`).join('')}
            <div class="tarifa-row mt-2">
              <span class="tarifa-chip"><i class='bx bx-time-five me-1'></i>$${e.tarifa_hora.toFixed(2)}/hora</span>
              <span class="tarifa-chip day"><i class='bx bx-sun me-1'></i>$${e.tarifa_dia.toFixed(2)}/día</span>
            </div>
            <div class="d-grid mt-3">
              <button class="btn btn-success btn-sm" onclick="abrirReserva(${e.id}, '${e.nombre}', '${e.codigo}')">
                <i class='bx bx-calendar-plus me-1'></i>Reservar este espacio
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>`;
            }).join('');
        }

        function toggleEspacio(id) {
            const card = document.getElementById('sc-' + id);
            const det = document.getElementById('sd-' + id);
            const isOpen = det.classList.contains('open');
            document.querySelectorAll('.space-detail.open').forEach(d => {
                d.classList.remove('open');
                d.closest('.space-card').classList.remove('expanded');
            });
            if (!isOpen) {
                det.classList.add('open');
                card.classList.add('expanded');
            }
        }

        function goBackToLocations() {
            document.getElementById('view-locations').style.display = 'block';
            document.getElementById('view-spaces').style.display = 'none';
            document.getElementById('sidebar').classList.add('hidden');
            selPiso = null;
            selTipo = null;
            document.getElementById('bc-sep1').style.display = 'none';
            document.getElementById('bc-floor').style.display = 'none';
            document.getElementById('bc-sep2').style.display = 'none';
            document.getElementById('bc-type').style.display = 'none';
            document.getElementById('bc-loc').textContent = 'Ubicaciones';
            setStep(1);
        }

        /* ════════════════════════════════════════
           STEPS
        ════════════════════════════════════════ */
        function setStep(n) {
            for (let i = 1; i <= 4; i++) {
                const el = document.getElementById('st' + i);
                el.className = 'step-pill ' + (i < n ? 'done' : i === n ? 'active' : '');
            }
        }

        /* ════════════════════════════════════════
           CALENDAR
        ════════════════════════════════════════ */
        let calDate = new Date();
        let calSelStart = null,
            calSelEnd = null;
        let activeEspacioId = null;

        const MONTHS = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        function abrirReserva(id, nombre, codigo) {
            activeEspacioId = id;
            document.getElementById('modal-space-name').textContent = nombre;
            document.getElementById('modal-space-sub').textContent = codigo;
            calDate = new Date();
            calSelStart = null;
            calSelEnd = null;
            renderCalendar();
            document.getElementById('inp-fecha-dia').value = '';
            document.getElementById('inp-fecha-ini').value = '';
            document.getElementById('inp-fecha-fin').value = '';
            document.getElementById('resumen-reserva').textContent = 'Selecciona una fecha para ver el resumen.';
            document.getElementById('turnos-dia').style.display = 'none';
            new bootstrap.Modal(document.getElementById('modalReserva')).show();
        }

        function calPrev() {
            calDate.setMonth(calDate.getMonth() - 1);
            renderCalendar();
        }

        function calNext() {
            calDate.setMonth(calDate.getMonth() + 1);
            renderCalendar();
        }

        function renderCalendar() {
            document.getElementById('cal-month-label').textContent = MONTHS[calDate.getMonth()] + ' ' + calDate.getFullYear();
            const grid = document.getElementById('cal-grid');
            // keep headers
            const headers = Array.from(grid.querySelectorAll('.cal-day-head'));
            grid.innerHTML = '';
            headers.forEach(h => grid.appendChild(h));

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const year = calDate.getFullYear(),
                month = calDate.getMonth();
            const first = new Date(year, month, 1);
            let startDow = first.getDay(); // 0=Sun
            // shift to Mon-based: Mon=0
            startDow = (startDow + 6) % 7;

            for (let i = 0; i < startDow; i++) {
                const e = document.createElement('div');
                e.className = 'cal-day empty';
                grid.appendChild(e);
            }

            const days = new Date(year, month + 1, 0).getDate();
            for (let d = 1; d <= days; d++) {
                const date = new Date(year, month, d);
                const key = fmtDate(date);
                const el = document.createElement('div');
                el.className = 'cal-day';
                el.textContent = d;

                if (date < today) el.classList.add('past');
                else {
                    if (date.getTime() === today.getTime()) el.classList.add('today');
                    // has shift: every day has shifts (mock)
                    el.classList.add('has-shift');
                    // reserved
                    if (RESERVAS[key]) el.classList.add('reserved');
                    // selected range
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

                    el.onclick = () => selectCalDay(date);
                }
                grid.appendChild(el);
            }
        }

        function selectCalDay(date) {
            const tipo = document.querySelector('input[name="tipo_reserva"]:checked').value;
            if (tipo === 'dia') {
                calSelStart = date;
                calSelEnd = null;
                document.getElementById('inp-fecha-dia').value = fmtDate(date);
                mostrarTurnosDia(date);
                updateResumen();
            } else {
                if (!calSelStart || (calSelStart && calSelEnd)) {
                    calSelStart = date;
                    calSelEnd = null;
                    document.getElementById('inp-fecha-ini').value = fmtDate(date);
                    document.getElementById('inp-fecha-fin').value = '';
                } else {
                    if (date >= calSelStart) {
                        calSelEnd = date;
                        document.getElementById('inp-fecha-fin').value = fmtDate(date);
                    } else {
                        calSelEnd = calSelStart;
                        calSelStart = date;
                        document.getElementById('inp-fecha-ini').value = fmtDate(date);
                        document.getElementById('inp-fecha-fin').value = fmtDate(calSelEnd);
                    }
                    updateResumen();
                }
            }
            renderCalendar();
        }

        function mostrarTurnosDia(date) {
            const dow = date.getDay(); // 0-6
            const cont = document.getElementById('turnos-dia');
            const list = document.getElementById('turnos-dia-list');
            // show all shifts (mock: all shifts available every day)
            list.innerHTML = TURNOS.map(t => `
    <span class="turno-chip" style="background:${t.color}">${t.nombre}: ${t.entrada} – ${t.salida}</span>`).join('');
            cont.style.display = '';
        }

        function updateResumen() {
            const tipo = document.querySelector('input[name="tipo_reserva"]:checked').value;
            const res = document.getElementById('resumen-reserva');
            if (tipo === 'dia' && calSelStart) {
                res.innerHTML = `<strong>Fecha:</strong> ${calSelStart.toLocaleDateString('es-EC',{weekday:'long',year:'numeric',month:'long',day:'numeric'})}<br>Elige un turno y confirma.`;
            } else if (tipo === 'rango' && calSelStart && calSelEnd) {
                const days = Math.round((calSelEnd - calSelStart) / (1000 * 60 * 60 * 24)) + 1;
                res.innerHTML = `<strong>Desde:</strong> ${fmtDate(calSelStart)}<br><strong>Hasta:</strong> ${fmtDate(calSelEnd)}<br><strong>Días:</strong> ${days}`;
            }
        }

        function syncRange() {
            const ini = document.getElementById('inp-fecha-ini').value;
            const fin = document.getElementById('inp-fecha-fin').value;
            if (ini) {
                calSelStart = new Date(ini + 'T00:00:00');
            }
            if (fin) {
                calSelEnd = new Date(fin + 'T00:00:00');
            }
            if (ini && fin) updateResumen();
            renderCalendar();
        }

        function toggleTipoReserva() {
            const tipo = document.querySelector('input[name="tipo_reserva"]:checked').value;
            document.getElementById('opt-dia').style.display = tipo === 'dia' ? '' : 'none';
            document.getElementById('opt-rango').style.display = tipo === 'rango' ? '' : 'none';
            calSelStart = null;
            calSelEnd = null;
            document.getElementById('turnos-dia').style.display = 'none';
            renderCalendar();
        }

        function confirmarReserva() {
            const tipo = document.querySelector('input[name="tipo_reserva"]:checked').value;
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
                alert('Selecciona una fecha primero.');
                return;
            }
            renderCalendar();
            bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide();

            // small toast
            const toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#2d6a4f;color:#fff;padding:12px 20px;border-radius:10px;font-size:.88rem;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,.2)';
            toast.innerHTML = '<i class="bx bx-check-circle me-2"></i>¡Reserva registrada con éxito!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function fmtDate(d) {
            return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
        }
    </script>