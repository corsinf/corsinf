<?php
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
    let tbl_servicios;
    let reservasData = []; // Almacenar datos globalmente

    $(document).ready(function() {
        tbl_servicios = $('#tbl_servicios').DataTable($.extend({}, configuracion_datatable('Nombre', 'Descripcion', 'Precio'), {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/XPACE_CUBE/serviciosC.php?listar=true',
                dataSrc: '',
                dataSrc: function(json) {
                    reservasData = json; // Guardar datos
                    return json;
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_registrar_servicio&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre}</u></a>`;
                    }
                },
                {
                    data: 'descripcion'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `
                            <div class="d-flex gap-2">
                                <span class="badge bg-info text-dark">${item.fecha_inicio || 'N/A'}</span>
                                <span class="badge bg-warning text-dark">${item.fecha_fin || 'N/A'}</span>
                            </div>
                        `;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `
                            <button class="btn btn-sm btn-primary" onclick="verHorariosReserva('${item._id}', '${item.nombre}', '${item.fecha_inicio}', '${item.fecha_fin}')">
                                <i class="bx bx-time-five"></i> Ver Horarios
                            </button>
                        `;
                    }
                }
            ],
            order: [[1, 'asc']]
        }));

        // Eventos de filtros
        $('#filtro_tipo').change(aplicarFiltros);
        $('#filtro_fecha').change(aplicarFiltros);
    });

    function aplicarFiltros() {
        const tipoFiltro = $('#filtro_tipo').val();
        const fechaSeleccionada = $('#filtro_fecha').val();

        if (!fechaSeleccionada) {
            tbl_servicios.search('').draw();
            return;
        }

        const fecha = new Date(fechaSeleccionada);
        
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const item = reservasData[dataIndex];
            if (!item.fecha_inicio) return true;

            const fechaInicio = new Date(item.fecha_inicio);
            const fechaFin = new Date(item.fecha_fin || item.fecha_inicio);

            if (tipoFiltro === 'dia') {
                const fechaStr = fecha.toISOString().split('T')[0];
                const inicioStr = fechaInicio.toISOString().split('T')[0];
                const finStr = fechaFin.toISOString().split('T')[0];
                return fechaStr >= inicioStr && fechaStr <= finStr;
            } else if (tipoFiltro === 'mes') {
                return fecha.getMonth() === fechaInicio.getMonth() && 
                       fecha.getFullYear() === fechaInicio.getFullYear();
            } else if (tipoFiltro === 'anio') {
                return fecha.getFullYear() === fechaInicio.getFullYear();
            }
            
            return true;
        });

        tbl_servicios.draw();
        $.fn.dataTable.ext.search.pop();
    }

    function limpiarFiltros() {
        $('#filtro_tipo').val('dia');
        $('#filtro_fecha').val('');
        tbl_servicios.search('').draw();
    }

    function verHorariosReserva(id, nombre, fechaInicio, fechaFin) {
        $('#modal_horarios_titulo').text(nombre);
        
        // Generar horarios (esto debería venir del servidor)
        const horariosHTML = generarVistaHorarios(fechaInicio, fechaFin);
        $('#horarios_container').html(horariosHTML);
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modal_horarios'));
        modal.show();
    }

    function generarVistaHorarios(inicio, fin) {
        const fechaInicio = new Date(inicio);
        const fechaFin = new Date(fin || inicio);
        
        let html = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card border-start border-primary border-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-1"><i class="bx bx-calendar"></i> Fecha Inicio</h6>
                            <p class="mb-0 fs-5">${fechaInicio.toLocaleDateString('es-ES', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-start border-success border-3">
                        <div class="card-body">
                            <h6 class="text-success mb-1"><i class="bx bx-calendar-check"></i> Fecha Fin</h6>
                            <p class="mb-0 fs-5">${fechaFin.toLocaleDateString('es-ES', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Generar timeline de horas (ejemplo)
        html += `
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bx bx-time"></i> Horarios Asignados</h6>
                    <div class="timeline-wrapper">
        `;

        // Horarios de ejemplo (deberías obtenerlos del servidor)
        const horarios = [
            { hora: '08:00 - 09:00', estado: 'ocupado', detalle: 'Reunión con equipo' },
            { hora: '09:00 - 10:00', estado: 'libre', detalle: '' },
            { hora: '10:00 - 11:00', estado: 'ocupado', detalle: 'Presentación cliente' },
            { hora: '11:00 - 12:00', estado: 'ocupado', detalle: 'Capacitación' },
            { hora: '12:00 - 13:00', estado: 'libre', detalle: '' },
            { hora: '13:00 - 14:00', estado: 'libre', detalle: '' },
            { hora: '14:00 - 15:00', estado: 'ocupado', detalle: 'Trabajo remoto' },
            { hora: '15:00 - 16:00', estado: 'ocupado', detalle: 'Desarrollo' },
            { hora: '16:00 - 17:00', estado: 'libre', detalle: '' }
        ];

        horarios.forEach((h, index) => {
            const estadoClass = h.estado === 'ocupado' ? 'danger' : 'success';
            const estadoIcon = h.estado === 'ocupado' ? 'bx-x-circle' : 'bx-check-circle';
            const estadoTexto = h.estado === 'ocupado' ? 'Ocupado' : 'Disponible';

            html += `
                <div class="d-flex align-items-center mb-3 p-3 rounded-3 shadow-sm border-start border-${estadoClass} border-3 bg-light">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-${estadoClass} bg-gradient d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="bx ${estadoIcon} text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">${h.hora}</h6>
                        <span class="badge bg-${estadoClass}">${estadoTexto}</span>
                        ${h.detalle ? `<p class="mb-0 mt-1 text-muted small">${h.detalle}</p>` : ''}
                    </div>
                </div>
            `;
        });

        html += `
                    </div>
                </div>
            </div>
        `;

        return html;
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reservas</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Todas las reservas</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center justify-content-between mb-4">
                            <h5 class="mb-0 text-primary">
                                <i class="bx bx-calendar-event"></i> Gestión de Reservas
                            </h5>
                            <div class="d-flex gap-2">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_registrar_servicio"
                                   class="btn btn-success btn-sm">
                                    <i class="bx bx-plus me-1"></i> Nueva Reserva
                                </a>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body bg-light">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="bx bx-filter"></i> Tipo de Filtro
                                        </label>
                                        <select id="filtro_tipo" class="form-select form-select-sm">
                                            <option value="dia">Por Día</option>
                                            <option value="mes">Por Mes</option>
                                            <option value="anio">Por Año</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold">
                                            <i class="bx bx-calendar"></i> Fecha
                                        </label>
                                        <input type="date" id="filtro_fecha" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-secondary btn-sm w-100" onclick="limpiarFiltros()">
                                            <i class="bx bx-refresh"></i> Limpiar Filtros
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive" id="tbl_servicios" style="width:100%">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Fechas</th>
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

<!-- Modal de Horarios -->
<div class="modal fade" id="modal_horarios" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bx bx-time-five"></i> Horarios - <span id="modal_horarios_titulo"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="horarios_container"></div>
            </div>
            <div class="modal-footer">
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