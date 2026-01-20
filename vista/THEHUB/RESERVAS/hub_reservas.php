<?php //include('../cabeceras/header.php');

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../js/ACTIVOS_FIJOS/avaluos.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    let tbl_reservas;
    let reservasData = []; // Almacenar datos globalmente

    $(document).ready(function() {

        tbl_reservas = $('#tbl_reservas').DataTable($.extend({}, configuracion_datatable('Nombre', 'cuidad', 'telefono'), {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/XPACE_CUBE/reservasC.php?listar=true',
                dataSrc: function(json) {
                    reservasData = json; // Guardar datos globalmente
                    console.log('Datos cargados:', json); // Para debug
                    return json;
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_registrar_reserva&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre_miembro}</u></a>`;
                    }
                },
                {
                    data: 'nombre_espacio'
                },
                {
                    render: function(data, type, item) {
                        const porcentaje = Math.round((item.numero_personas / item.capacidad_espacio) * 100);
                        const colorBadge = porcentaje >= 80 ? 'danger' : porcentaje >= 50 ? 'warning' : 'success';
                        return `
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-${colorBadge}">${item.numero_personas} / ${item.capacidad_espacio}</span>
                            <small class="text-muted">(${porcentaje}%)</small>
                        </div>
                    `;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        const inicio = new Date(item.inicio).toLocaleString('es-EC', {
                            dateStyle: 'short',
                            timeStyle: 'short'
                        });
                        const fin = new Date(item.fin).toLocaleString('es-EC', {
                            dateStyle: 'short',
                            timeStyle: 'short'
                        });

                        return `
                        <div class="d-flex gap-1 flex-wrap">
                            <span class="badge text-dark">
                                ${inicio}
                            </span>
                            <span class="badge text-dark">
                                 ${fin}
                            </span>
                        </div>
                    `;
                    }

                },
                {
                    data: 'notas',
                    render: function(data, type, item) {
                        if (!data) return '<span class="text-muted">Sin notas</span>';
                        const notaCorta = data.length > 50 ? data.substring(0, 50) + '...' : data;
                        return `<small>${notaCorta}</small>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `
                        <button class="btn btn-sm btn-primary" 
                                onclick="verHorariosReserva('${item._id}', '${item.nombre_espacio}', '${item.nombre_miembro}', '${item.fecha_inicio}', '${item.fecha_fin}', '${item.numero_personas}', '${item.capacidad_espacio}')">
                            <i class="bx bx-time-five"></i> Horarios
                        </button>
                    `;
                    }
                }
            ],
            order: [
                [1, 'asc']
            ]
        }));

        // Eventos de filtros - TODOS aplican el filtro combinado
        $('#filtro_tipo').change(function() {
            establecerFechaActual();
            aplicarFiltros();
        });
        $('#filtro_fecha').change(aplicarFiltros);
        $('#filtro_espacio').on('keyup', aplicarFiltros);

        // Establecer fecha actual al cargar la página
        establecerFechaActual();
    });

    // Función para establecer la fecha actual en el input
    function establecerFechaActual() {
        const hoy = new Date();
        const año = hoy.getFullYear();
        const mes = String(hoy.getMonth() + 1).padStart(2, '0');
        const dia = String(hoy.getDate()).padStart(2, '0');
        const fechaActual = `${año}-${mes}-${dia}`;

        $('#filtro_fecha').val(fechaActual);
        console.log('Fecha actual establecida:', fechaActual);
    }

    // Función mejorada para parsear fechas con año corto (25) o largo (2025)
    function parseFechaCadena(fechaStr) {
        if (!fechaStr) return null;

        // Separar parte fecha y parte hora
        const parts = fechaStr.split(',');
        const datePart = parts[0].trim();
        const timePart = parts[1] ? parts[1].trim().toLowerCase() : '';

        // Parsear día/mes/año (soporta formato dd/mm/yy o dd/mm/yyyy)
        const dateMatch = datePart.match(/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$/);
        if (!dateMatch) {
            console.warn('Formato de fecha no válido:', fechaStr);
            return null;
        }

        let day = parseInt(dateMatch[1], 10);
        let month = parseInt(dateMatch[2], 10) - 1; // JavaScript usa 0-11 para meses
        let year = parseInt(dateMatch[3], 10);

        // Convertir año corto a año completo (25 -> 2025)
        if (year < 100) {
            year += 2000;
        }

        let hour = 0,
            minute = 0;

        // Parsear hora si existe
        if (timePart) {
            let tp = timePart.replace(/\./g, '').replace(/\s+/g, ' ').trim();
            const ampmMatch = tp.match(/(a m|am|p m|pm)$/);
            let isPM = false,
                isAM = false;

            if (ampmMatch) {
                const token = ampmMatch[0];
                if (token.indexOf('p') !== -1) isPM = true;
                if (token.indexOf('a') !== -1) isAM = true;
                tp = tp.replace(/(a m|am|p m|pm)$/, '').trim();
            }

            const timeMatch = tp.match(/(\d{1,2})(:(\d{2}))?/);
            if (timeMatch) {
                hour = parseInt(timeMatch[1], 10);
                minute = timeMatch[3] ? parseInt(timeMatch[3], 10) : 0;
                if (isPM && hour < 12) hour += 12;
                if (isAM && hour === 12) hour = 0;
            }
        }

        const fecha = new Date(year, month, day, hour, minute, 0, 0);
        console.log(`Parseado: "${fechaStr}" -> ${fecha.toISOString()}`);
        return fecha;
    }

    // Función para normalizar fechas a solo día (sin hora)
    function soloFecha(fecha) {
        return new Date(fecha.getFullYear(), fecha.getMonth(), fecha.getDate());
    }

    function aplicarFiltros() {
        const tipoFiltro = $('#filtro_tipo').val();
        const fechaSeleccionada = $('#filtro_fecha').val(); // formato: YYYY-MM-DD
        const espacioBusqueda = $('#filtro_espacio').val().toLowerCase();

        console.log('Aplicando filtros:', {
            tipoFiltro,
            fechaSeleccionada,
            espacioBusqueda
        });

        // Limpiar filtros previos
        $.fn.dataTable.ext.search = [];

        // Añadir filtro combinado
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const item = reservasData[dataIndex];

            if (!item) {
                console.warn('Item no encontrado en índice:', dataIndex);
                return false;
            }

            // ========== FILTRO DE ESPACIO ==========
            if (espacioBusqueda) {
                const nombreEspacio = (item.nombre_espacio || '').toLowerCase();
                if (!nombreEspacio.includes(espacioBusqueda)) {
                    return false;
                }
            }

            // ========== FILTRO DE FECHA ==========
            if (fechaSeleccionada) {
                // Parsear la fecha del input (formato YYYY-MM-DD)
                const [year, month, day] = fechaSeleccionada.split('-').map(Number);
                const fechaBusqueda = new Date(year, month - 1, day); // month - 1 porque JS usa 0-11

                if (isNaN(fechaBusqueda.getTime())) {
                    console.warn('Fecha de búsqueda inválida:', fechaSeleccionada);
                    return true;
                }

                // Parsear fechas de inicio y fin de la reserva
                let fechaInicioDate = null;
                let fechaFinDate = null;

                // Intentar obtener desde item.inicio e item.fin primero (si son Date objects)
                if (item.inicio) {
                    fechaInicioDate = new Date(item.inicio);
                }
                if (item.fin) {
                    fechaFinDate = new Date(item.fin);
                }

                // Si no están disponibles, parsear desde las cadenas fecha_inicio y fecha_fin
                if (!fechaInicioDate || isNaN(fechaInicioDate.getTime())) {
                    if (item.fecha_inicio) {
                        fechaInicioDate = parseFechaCadena(item.fecha_inicio);
                    }
                }
                if (!fechaFinDate || isNaN(fechaFinDate.getTime())) {
                    if (item.fecha_fin) {
                        fechaFinDate = parseFechaCadena(item.fecha_fin);
                    }
                }

                // Si no hay fecha de inicio válida, excluir esta reserva
                if (!fechaInicioDate || isNaN(fechaInicioDate.getTime())) {
                    console.warn('Fecha de inicio inválida para item:', item);
                    return false;
                }

                // Si no hay fecha fin, asumir que es igual a la de inicio
                if (!fechaFinDate || isNaN(fechaFinDate.getTime())) {
                    fechaFinDate = fechaInicioDate;
                }

                // Normalizar fechas a solo día (sin hora) para comparación
                const fechaBusquedaSolo = soloFecha(fechaBusqueda);
                const fechaInicioSolo = soloFecha(fechaInicioDate);
                const fechaFinSolo = soloFecha(fechaFinDate);

                console.log('Comparando fechas:', {
                    busqueda: fechaBusquedaSolo.toISOString().split('T')[0],
                    inicio: fechaInicioSolo.toISOString().split('T')[0],
                    fin: fechaFinSolo.toISOString().split('T')[0],
                    tipo: tipoFiltro
                });

                // Aplicar filtro según el tipo seleccionado
                if (tipoFiltro === 'dia') {
                    // La fecha buscada debe estar dentro del rango de la reserva
                    if (fechaBusquedaSolo < fechaInicioSolo || fechaBusquedaSolo > fechaFinSolo) {
                        return false;
                    }
                } else if (tipoFiltro === 'mes') {
                    // La reserva debe iniciar en el mismo mes y año
                    if (fechaBusqueda.getMonth() !== fechaInicioDate.getMonth() ||
                        fechaBusqueda.getFullYear() !== fechaInicioDate.getFullYear()) {
                        return false;
                    }
                } else if (tipoFiltro === 'anio') {
                    // La reserva debe iniciar en el mismo año
                    if (fechaBusqueda.getFullYear() !== fechaInicioDate.getFullYear()) {
                        return false;
                    }
                }
            }

            // Si pasa todos los filtros activos, mostrar la fila
            return true;
        });

        tbl_reservas.draw();
    }

    function limpiarFiltros() {
        $('#filtro_tipo').val('dia');
        $('#filtro_fecha').val('');
        $('#filtro_espacio').val('');
        $.fn.dataTable.ext.search = [];
        tbl_reservas.draw();
        console.log('Filtros limpiados');
    }

    function verHorariosReserva(id, espacio, miembro, fechaInicio, fechaFin, personas, capacidad) {
        $('#modal_horarios_espacio').text(espacio);
        $('#modal_horarios_miembro').text(miembro);

        // Generar horarios
        const horariosHTML = generarVistaHorarios(fechaInicio, fechaFin, personas, capacidad, espacio);
        $('#horarios_container').html(horariosHTML);

        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modal_horarios'));
        modal.show();
    }

    function generarVistaHorarios(inicio, fin, personas, capacidad, espacio) {
        const fechaInicio = inicio !== 'N/A' ? new Date(inicio) : new Date();
        const fechaFin = fin !== 'N/A' && fin ? new Date(fin) : fechaInicio;

        const porcentajeOcupacion = Math.round((personas / capacidad) * 100);
        const colorOcupacion = porcentajeOcupacion >= 80 ? 'danger' : porcentajeOcupacion >= 50 ? 'warning' : 'success';

        let html = `
            <!-- Información General -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-start border-primary border-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary bg-gradient d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px;">
                                        <i class="bx bx-calendar text-white fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-primary mb-0">Fecha Inicio</h6>
                                    <p class="mb-0 fw-bold">${fechaInicio.toLocaleDateString('es-ES')}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-start border-success border-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-success bg-gradient d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px;">
                                        <i class="bx bx-calendar-check text-white fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-success mb-0">Fecha Fin</h6>
                                    <p class="mb-0 fw-bold">${fechaFin.toLocaleDateString('es-ES')}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-start border-${colorOcupacion} border-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-${colorOcupacion} bg-gradient d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px;">
                                        <i class="bx bx-user text-white fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-${colorOcupacion} mb-0">Ocupación</h6>
                                    <p class="mb-0 fw-bold">${personas}/${capacidad} (${porcentajeOcupacion}%)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline de Horarios -->
            <div class="card shadow-sm">
                <div class="card-header bg-gradient bg-primary text-white">
                    <h6 class="mb-0"><i class="bx bx-time"></i> Cronograma de Horarios</h6>
                </div>
                <div class="card-body">
        `;

        // Generar horarios del día (puedes ajustar según tus necesidades)
        const horarios = [{
                hora: '08:00 - 09:00',
                estado: 'ocupado',
                actividad: 'Reunión de equipo',
                icon: 'bx-group'
            },
            {
                hora: '09:00 - 10:00',
                estado: 'libre',
                actividad: '',
                icon: 'bx-check-circle'
            },
            {
                hora: '10:00 - 11:00',
                estado: 'ocupado',
                actividad: 'Presentación cliente',
                icon: 'bx-presentation'
            },
            {
                hora: '11:00 - 12:00',
                estado: 'ocupado',
                actividad: 'Capacitación interna',
                icon: 'bx-book'
            },
            {
                hora: '12:00 - 13:00',
                estado: 'libre',
                actividad: '',
                icon: 'bx-check-circle'
            },
            {
                hora: '13:00 - 14:00',
                estado: 'libre',
                actividad: '',
                icon: 'bx-check-circle'
            },
            {
                hora: '14:00 - 15:00',
                estado: 'ocupado',
                actividad: 'Trabajo remoto todo el día',
                icon: 'bx-laptop'
            },
            {
                hora: '15:00 - 16:00',
                estado: 'ocupado',
                actividad: 'Desarrollo de proyecto',
                icon: 'bx-code'
            },
            {
                hora: '16:00 - 17:00',
                estado: 'libre',
                actividad: '',
                icon: 'bx-check-circle'
            },
            {
                hora: '17:00 - 18:00',
                estado: 'ocupado',
                actividad: 'Cierre de actividades',
                icon: 'bx-task'
            }
        ];

        horarios.forEach((h, index) => {
            const estadoClass = h.estado === 'ocupado' ? 'danger' : 'success';
            const estadoIcon = h.estado === 'ocupado' ? 'bx-x-circle' : 'bx-check-circle';
            const estadoTexto = h.estado === 'ocupado' ? 'Ocupado' : 'Disponible';
            const bgColor = h.estado === 'ocupado' ? 'bg-danger-subtle' : 'bg-success-subtle';

            html += `
                <div class="d-flex align-items-center mb-3 p-3 rounded-3 border-start border-${estadoClass} border-4 ${bgColor} position-relative">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-${estadoClass} bg-gradient d-flex align-items-center justify-content-center shadow" 
                             style="width: 56px; height: 56px;">
                            <i class="bx ${h.icon} text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold">${h.hora}</h6>
                            <span class="badge bg-${estadoClass} rounded-pill">${estadoTexto}</span>
                        </div>
                        ${h.actividad ? `
                            <p class="mb-0 text-muted">
                                <i class="bx bx-info-circle"></i> ${h.actividad}
                            </p>
                        ` : `
                            <p class="mb-0 text-success">
                                <i class="bx bx-check"></i> Espacio disponible para reservar
                            </p>
                        `}
                    </div>
                </div>
            `;
        });

        html += `
                </div>
            </div>

            <!-- Estadísticas del día -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm bg-danger-subtle">
                        <div class="card-body text-center">
                            <i class="bx bx-time-five text-danger fs-1"></i>
                            <h4 class="mt-2 mb-1 text-danger">6 horas</h4>
                            <p class="mb-0 text-muted">Tiempo Ocupado</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm bg-success-subtle">
                        <div class="card-body text-center">
                            <i class="bx bx-time text-success fs-1"></i>
                            <h4 class="mt-2 mb-1 text-success">4 horas</h4>
                            <p class="mb-0 text-muted">Tiempo Disponible</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        return html;
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">
                <i class="bx bx-calendar-event"></i> Reservaciones
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Todas las reservaciones</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary shadow">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center justify-content-between mb-4">
                            <h5 class="mb-0 text-primary">
                                <i class="bx bx-list-ul"></i> Gestión de Reservaciones
                            </h5>
                            <div class="" id="btn_nuevo">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_registrar_reserva"
                                    type="button" class="btn btn-success btn-sm shadow-sm">
                                    <i class="bx bx-plus me-1"></i> Nueva Reserva
                                </a>
                            </div>
                        </div>

                        <!-- Panel de Filtros -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-gradient bg-light border-bottom">
                                <h6 class="mb-0 text-dark">
                                    <i class="bx bx-filter-alt"></i> Filtros de Búsqueda
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            <i class="bx bx-calendar-alt"></i> Tipo de Periodo
                                        </label>
                                        <select id="filtro_tipo" class="form-select">
                                             <option value="seleccione">Seleccione un opción</option>
                                            <option value="dia">Filtrar por Día</option>
                                            <option value="mes">Filtrar por Mes</option>
                                            <option value="anio">Filtrar por Año</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            <i class="bx bx-calendar"></i> Seleccionar Fecha
                                        </label>
                                        <input type="date" id="filtro_fecha" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="bx bx-buildings"></i> Buscar por Espacio
                                        </label>
                                        <input type="text" id="filtro_espacio" class="form-control" placeholder="Ej: Sala de juntas, Hot Desk...">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                                            <i class="bx bx-refresh"></i> Limpiar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped responsive align-middle" id="tbl_reservas" style="width:100%">
                                        <thead class="table-primary">
                                            <tr>
                                                <th><i class="bx bx-user"></i> Miembro</th>
                                                <th><i class="bx bx-buildings"></i> Espacio</th>
                                                <th><i class="bx bx-group"></i> Ocupación</th>
                                                <th><i class="bx bx-calendar"></i> Fecha inicio - Fecha fin</th>
                                                <th><i class="bx bx-note"></i> Notas</th>
                                                <th><i class="bx bx-cog"></i> Acciones</th>
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

<!-- Modal de Horarios -->
<div class="modal fade" id="modal_horarios" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-gradient bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bx bx-time-five"></i> Cronograma de Horarios
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <!-- Información de la reserva -->
                <div class="alert alert-info border-0 shadow-sm mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-info-circle fs-3 me-3"></i>
                        <div>
                            <h6 class="mb-1">
                                <strong>Espacio:</strong> <span id="modal_horarios_espacio"></span>
                            </h6>
                            <p class="mb-0">
                                <strong>Reservado por:</strong> <span id="modal_horarios_miembro"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contenedor de horarios -->
                <div id="horarios_container"></div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Blank (conservado del original) -->
<div class="modal" id="modal_blank" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="">Tipo de <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm" onchange="">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Blank <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick="">
                            <i class="bx bx-save"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>