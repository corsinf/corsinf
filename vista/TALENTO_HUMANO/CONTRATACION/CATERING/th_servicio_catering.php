<script>
$(document).ready(function() {
    // Datos simulados
    const zonas = [{
            id: 1,
            nombre: 'Zona Norte',
            descripcion: 'Edificio Principal'
        },
        {
            id: 2,
            nombre: 'Zona Sur',
            descripcion: 'Almacén y Logística'
        },
        {
            id: 3,
            nombre: 'Zona Este',
            descripcion: 'Área Administrativa'
        },
        {
            id: 4,
            nombre: 'Zona Oeste',
            descripcion: 'Producción'
        }
    ];

    const departamentos = [{
            id: 1,
            nombre: 'Recursos Humanos',
            zona_id: 3,
            empleados: 15
        },
        {
            id: 2,
            nombre: 'Finanzas',
            zona_id: 3,
            empleados: 12
        },
        {
            id: 3,
            nombre: 'Producción',
            zona_id: 4,
            empleados: 45
        },
        {
            id: 4,
            nombre: 'Logística',
            zona_id: 2,
            empleados: 23
        },
        {
            id: 5,
            nombre: 'Tecnología',
            zona_id: 1,
            empleados: 18
        },
        {
            id: 6,
            nombre: 'Ventas',
            zona_id: 1,
            empleados: 28
        }
    ];

    const empleados = [{
            id: 1,
            nombre: 'Juan Pérez',
            cedula: '1234567890',
            departamento_id: 1,
            tiene_catering: true,
            descuento: true,
            ingreso_hoy: false
        },
        {
            id: 2,
            nombre: 'María González',
            cedula: '0987654321',
            departamento_id: 1,
            tiene_catering: true,
            descuento: true,
            ingreso_hoy: true
        },
        {
            id: 3,
            nombre: 'Carlos Rodríguez',
            cedula: '1122334455',
            departamento_id: 2,
            tiene_catering: false,
            descuento: false,
            ingreso_hoy: false
        },
        {
            id: 4,
            nombre: 'Ana Martínez',
            cedula: '5544332211',
            departamento_id: 3,
            tiene_catering: true,
            descuento: false,
            ingreso_hoy: true
        },
        {
            id: 5,
            nombre: 'Luis Torres',
            cedula: '9988776655',
            departamento_id: 4,
            tiene_catering: true,
            descuento: true,
            ingreso_hoy: false
        },
        {
            id: 6,
            nombre: 'Sofia Ramírez',
            cedula: '6677889900',
            departamento_id: 5,
            tiene_catering: true,
            descuento: true,
            ingreso_hoy: true
        }
    ];

    const serviciosExternos = [{
            id: 1,
            empresa: 'Catering Deluxe S.A.',
            contacto: 'Pedro Sánchez',
            telefono: '0998877665',
            tipo: 'Almuerzo Ejecutivo'
        },
        {
            id: 2,
            empresa: 'Sabor Gourmet',
            contacto: 'Laura Vargas',
            telefono: '0987766554',
            tipo: 'Desayuno Continental'
        },
        {
            id: 3,
            empresa: 'Fresh Food Corp',
            contacto: 'Miguel Ángel',
            telefono: '0976655443',
            tipo: 'Snacks y Bebidas'
        }
    ];

    let invitados = [{
            id: 1,
            nombre: 'Roberto Silva',
            empresa: 'TechCorp',
            responsable: 'Juan Pérez',
            qr_code: 'QR-INV-001',
            fecha: '2024-12-01',
            ingreso: false
        },
        {
            id: 2,
            nombre: 'Carmen López',
            empresa: 'Suministros SA',
            responsable: 'María González',
            qr_code: 'QR-INV-002',
            fecha: '2024-12-01',
            ingreso: true
        }
    ];

    // Cargar estadísticas
    function cargarEstadisticas() {
        $('#total_empleados').text(empleados.length);
        $('#con_catering').text(empleados.filter(e => e.tiene_catering).length);
        $('#ingresos_hoy').text(empleados.filter(e => e.ingreso_hoy).length + invitados.filter(i => i.ingreso)
            .length);
        $('#invitados_hoy').text(invitados.length);

        $('#reporte_ingresos').text(empleados.filter(e => e.ingreso_hoy).length + invitados.filter(i => i
            .ingreso).length);
        $('#reporte_catering').text(empleados.filter(e => e.tiene_catering).length);
        $('#reporte_descuentos').text(empleados.filter(e => e.descuento).length);
        $('#reporte_externos').text(serviciosExternos.length);
    }

    // Cargar zonas
    function cargarZonas() {
        let html = '';
        zonas.forEach(zona => {
            const numDepts = departamentos.filter(d => d.zona_id === zona.id).length;
            html += `
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${zona.nombre}</h6>
                        <span class="badge bg-primary rounded-pill">${numDepts} depts</span>
                    </div>
                    <p class="mb-0 text-muted small">${zona.descripcion}</p>
                </div>
            `;
        });
        $('#lista_zonas').html(html);
    }

    // Cargar departamentos
    function cargarDepartamentos() {
        let html = '';
        departamentos.forEach(dept => {
            const zona = zonas.find(z => z.id === dept.zona_id);
            html += `
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${dept.nombre}</h6>
                        <span class="badge bg-success rounded-pill">${dept.empleados} empl.</span>
                    </div>
                    <p class="mb-0 text-muted small">
                        <i class='bx bx-map'></i> ${zona.nombre}
                    </p>
                </div>
            `;
        });
        $('#lista_departamentos').html(html);
    }

    // Cargar empleados
    function cargarEmpleados() {
        let html = '';
        empleados.forEach(empleado => {
            const dept = departamentos.find(d => d.id === empleado.departamento_id);
            const disabledClass = !empleado.tiene_catering || empleado.ingreso_hoy ? 'disabled' : '';
            html += `
                <tr>
                    <td>${empleado.nombre}</td>
                    <td>${empleado.cedula}</td>
                    <td>${dept.nombre}</td>
                    <td>
                        ${empleado.tiene_catering ? 
                            '<span class="badge badge-custom bg-success"><i class="bx bx-check-circle"></i> Activo</span>' : 
                            '<span class="badge badge-custom bg-danger"><i class="bx bx-x-circle"></i> No activo</span>'
                        }
                    </td>
                    <td>
                        ${empleado.descuento ? 
                            '<span class="badge badge-custom bg-warning text-dark">Sí</span>' : 
                            '<span class="badge badge-custom bg-secondary">No</span>'
                        }
                    </td>
                    <td>
                        ${empleado.ingreso_hoy ? 
                            '<span class="text-success fw-bold">✓ Registrado</span>' : 
                            '<span class="text-muted">Pendiente</span>'
                        }
                    </td>
                    <td>
                        <button class="btn btn-primary btn-action ${disabledClass}" onclick="registrarIngreso(${empleado.id}, 'empleado')" ${disabledClass}>
                            <i class='bx bx-log-in'></i> Registrar
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody_empleados').html(html);
    }

    // Cargar servicios externos
    function cargarServicios() {
        let html = '';
        serviciosExternos.forEach(servicio => {
            html += `
                <tr>
                    <td class="fw-bold">${servicio.empresa}</td>
                    <td>${servicio.contacto}</td>
                    <td>${servicio.telefono}</td>
                    <td><span class="badge badge-custom bg-info">${servicio.tipo}</span></td>
                    <td><span class="badge badge-custom bg-success">Activo</span></td>
                </tr>
            `;
        });
        $('#tbody_externos').html(html);
    }

    // Cargar invitados
    function cargarInvitados() {
        let html = '';
        invitados.forEach(invitado => {
            html += `
                <tr>
                    <td>${invitado.nombre}</td>
                    <td>${invitado.empresa}</td>
                    <td>${invitado.responsable}</td>
                    <td><code>${invitado.qr_code}</code></td>
                    <td>${invitado.fecha}</td>
                    <td>
                        ${invitado.ingreso ? 
                            '<span class="badge badge-custom bg-success">Registrado</span>' : 
                            '<span class="badge badge-custom bg-warning text-dark">Pendiente</span>'
                        }
                    </td>
                    <td>
                        <button class="btn btn-outline-primary btn-action me-1" onclick="mostrarQR('${invitado.nombre}', '${invitado.qr_code}')">
                            <i class='bx bx-qr'></i>
                        </button>
                        <button class="btn btn-success btn-action ${invitado.ingreso ? 'disabled' : ''}" onclick="registrarIngresoInvitado(${invitado.id})" ${invitado.ingreso ? 'disabled' : ''}>
                            <i class='bx bx-log-in'></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody_invitados').html(html);
    }

    // Cargar responsables en select
    function cargarResponsables() {
        let html = '<option value="">Seleccione el responsable</option>';
        empleados.forEach(emp => {
            html += `<option value="${emp.id}">${emp.nombre}</option>`;
        });
        $('#inv_responsable').html(html);
    }

    // Inicializar todo
    cargarEstadisticas();
    cargarZonas();
    cargarDepartamentos();
    cargarEmpleados();
    cargarServicios();
    cargarInvitados();
    cargarResponsables();

    // Búsqueda de empleados
    $('#buscar_empleado').on('keyup', function() {
        const valor = $(this).val().toLowerCase();
        $('#tbody_empleados tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // Funciones globales
    window.registrarIngreso = function(id, tipo) {
        const empleado = empleados.find(e => e.id === id);
        if (empleado && !empleado.ingreso_hoy && empleado.tiene_catering) {
            empleado.ingreso_hoy = true;
            cargarEmpleados();
            cargarEstadisticas();
            mostrarAlerta('success', '✓ Ingreso registrado exitosamente');
        }
    };

    window.registrarIngresoInvitado = function(id) {
        const invitado = invitados.find(i => i.id === id);
        if (invitado && !invitado.ingreso) {
            invitado.ingreso = true;
            cargarInvitados();
            cargarEstadisticas();
            mostrarAlerta('success', '✓ Ingreso de invitado registrado');
        }
    };

    window.mostrarQR = function(nombre, codigo) {
        $('#qr_nombre').text(nombre);
        $('#qr_codigo').text(codigo);
        $('#modal_qr').modal('show');
    };

    window.agregarInvitado = function() {
        const nombre = $('#inv_nombre').val();
        const empresa = $('#inv_empresa').val();
        const responsable_id = $('#inv_responsable').val();
        const fecha = $('#inv_fecha').val();

        if (!nombre || !responsable_id || !fecha) {
            mostrarAlerta('warning', 'Por favor complete todos los campos obligatorios');
            return;
        }

        const responsable = empleados.find(e => e.id == responsable_id);
        const nuevoInvitado = {
            id: invitados.length + 1,
            nombre: nombre,
            empresa: empresa,
            responsable: responsable.nombre,
            qr_code: 'QR-INV-' + String(invitados.length + 1).padStart(3, '0'),
            fecha: fecha,
            ingreso: false
        };

        invitados.push(nuevoInvitado);
        cargarInvitados();
        cargarEstadisticas();
        $('#modal_invitado').modal('hide');

        // Limpiar formulario
        $('#inv_nombre').val('');
        $('#inv_empresa').val('');
        $('#inv_responsable').val('');
        $('#inv_fecha').val('');

        mostrarAlerta('success', '✓ Invitado agregado y QR generado exitosamente');
    };

    function mostrarAlerta(tipo, mensaje) {
        const iconos = {
            success: 'bx-check-circle',
            warning: 'bx-error',
            danger: 'bx-x-circle',
            info: 'bx-info-circle'
        };

        const alerta = `
            <div class="alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" 
                 style="z-index: 9999; min-width: 300px;" role="alert">
                <i class='bx ${iconos[tipo]}'></i> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        $('body').append(alerta);
        setTimeout(() => {
            $('.alert').alert('close');
        }, 3000);
    }
});
</script>
<style>
:root {
    --primary-color: #0d6efd;
    --success-color: #198754;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --info-color: #0dcaf0;
}

body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.navbar-brand {
    font-weight: 600;
    font-size: 1.3rem;
}

.stat-card {
    border-radius: 10px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    border: none;
    border-bottom: 3px solid transparent;
}

.nav-tabs .nav-link.active {
    color: var(--primary-color);
    background-color: transparent;
    border-bottom: 3px solid var(--primary-color);
}

.badge-custom {
    padding: 0.5em 0.8em;
    border-radius: 20px;
    font-weight: 500;
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

.btn-action {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.list-group-item {
    border-left: 4px solid transparent;
    transition: all 0.3s;
}

.list-group-item:hover {
    border-left-color: var(--primary-color);
    background-color: #f8f9fa;
}

.qr-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    border-radius: 15px;
    color: white;
}

.qr-code {
    background: white;
    padding: 20px;
    border-radius: 10px;
    display: inline-block;
}
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Blank</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blank
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

                            <h5 class="mb-0 text-primary"> <i class="fas fa-utensils me-2"></i>
                                Sistema de Gestión de Catering</h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modal_blank"><i class="bx bx-plus"></i> Nuevo</button>

                                </div>
                            </div>
                        </div>


                        <div class="container-fluid mt-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <div class="card stat-card border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                                    <i class='bx bx-user'></i>
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <p class="text-muted mb-1 small">Total Empleados</p>
                                                    <h3 class="mb-0" id="total_empleados">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stat-card border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="stat-icon bg-success bg-opacity-10 text-success">
                                                    <i class='bx bx-check-circle'></i>
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <p class="text-muted mb-1 small">Con Catering</p>
                                                    <h3 class="mb-0" id="con_catering">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stat-card border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="stat-icon bg-info bg-opacity-10 text-info">
                                                    <i class='bx bx-log-in'></i>
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <p class="text-muted mb-1 small">Ingresos Hoy</p>
                                                    <h3 class="mb-0" id="ingresos_hoy">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stat-card border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                                    <i class='bx bx-user-plus'></i>
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <p class="text-muted mb-1 small">Invitados Hoy</p>
                                                    <h3 class="mb-0" id="invitados_hoy">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabs -->
                            <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="zonas-tab" data-bs-toggle="tab"
                                        data-bs-target="#zonas" type="button">
                                        <i class='bx bx-map'></i> Zonas y Direcciones
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="empleados-tab" data-bs-toggle="tab"
                                        data-bs-target="#empleados" type="button">
                                        <i class='bx bx-user'></i> Empleados
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="externos-tab" data-bs-toggle="tab"
                                        data-bs-target="#externos" type="button">
                                        <i class='bx bx-building'></i> Servicios Externos
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="invitados-tab" data-bs-toggle="tab"
                                        data-bs-target="#invitados" type="button">
                                        <i class='bx bx-qr'></i> Invitados
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reportes-tab" data-bs-toggle="tab"
                                        data-bs-target="#reportes" type="button">
                                        <i class='bx bx-file'></i> Reportes
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="mainTabContent">

                                <!-- Zonas y Departamentos -->
                                <div class="tab-pane fade show active" id="zonas" role="tabpanel">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <h5 class="mb-4"><i class='bx bx-map text-primary'></i> Zonas y
                                                Departamentos</h5>
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <h6 class="text-primary mb-3">Zonas</h6>
                                                    <div class="list-group" id="lista_zonas">
                                                        <!-- Zonas dinámicas -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <h6 class="text-primary mb-3">Departamentos</h6>
                                                    <div class="list-group" id="lista_departamentos">
                                                        <!-- Departamentos dinámicos -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empleados -->
                                <div class="tab-pane fade" id="empleados" role="tabpanel">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0"><i class='bx bx-user text-primary'></i> Control de
                                                    Empleados</h5>
                                                <div class="input-group" style="max-width: 300px;">
                                                    <span class="input-group-text"><i class='bx bx-search'></i></span>
                                                    <input type="text" class="form-control" id="buscar_empleado"
                                                        placeholder="Buscar empleado...">
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="tbl_empleados">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Empleado</th>
                                                            <th>Cédula</th>
                                                            <th>Departamento</th>
                                                            <th>Catering</th>
                                                            <th>Descuento</th>
                                                            <th>Ingreso Hoy</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody_empleados">
                                                        <!-- Empleados dinámicos -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Servicios Externos -->
                                <div class="tab-pane fade" id="externos" role="tabpanel">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <h5 class="mb-3"><i class='bx bx-building text-primary'></i> Servicios de
                                                Catering Externos</h5>
                                            <div class="alert alert-info">
                                                <i class='bx bx-info-circle'></i> <strong>Nota:</strong> Los servicios
                                                externos deben reportar a Talento Humano
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Empresa</th>
                                                            <th>Contacto</th>
                                                            <th>Teléfono</th>
                                                            <th>Tipo de Servicio</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody_externos">
                                                        <!-- Servicios externos dinámicos -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Invitados -->
                                <div class="tab-pane fade" id="invitados" role="tabpanel">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0"><i class='bx bx-qr text-primary'></i> Gestión de
                                                    Invitados</h5>
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal_invitado">
                                                    <i class='bx bx-plus'></i> Agregar Invitado
                                                </button>
                                            </div>
                                            <div class="alert alert-warning">
                                                <i class='bx bx-error'></i> <strong>Importante:</strong> Los invitados
                                                requieren QR único y responsable autorizado
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Invitado</th>
                                                            <th>Empresa</th>
                                                            <th>Responsable</th>
                                                            <th>Código QR</th>
                                                            <th>Fecha</th>
                                                            <th>Ingreso</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody_invitados">
                                                        <!-- Invitados dinámicos -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reportes -->
                                <div class="tab-pane fade" id="reportes" role="tabpanel">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <h5 class="mb-4"><i class='bx bx-file text-primary'></i> Reportes para
                                                Talento Humano</h5>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-title">Reporte Diario de Ingresos</h6>
                                                            <p class="card-text text-muted small">Total de personas que
                                                                tomaron el servicio hoy</p>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <h3 class="mb-0 text-primary" id="reporte_ingresos">0
                                                                </h3>
                                                                <button class="btn btn-primary btn-sm"><i
                                                                        class='bx bx-download'></i> Descargar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-title">Empleados con Catering Activo</h6>
                                                            <p class="card-text text-muted small">Personal autorizado
                                                                para el servicio</p>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <h3 class="mb-0 text-success" id="reporte_catering">0
                                                                </h3>
                                                                <button class="btn btn-success btn-sm"><i
                                                                        class='bx bx-download'></i> Descargar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-title">Reporte de Descuentos</h6>
                                                            <p class="card-text text-muted small">Empleados con
                                                                descuento aplicado</p>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <h3 class="mb-0 text-warning" id="reporte_descuentos">0
                                                                </h3>
                                                                <button class="btn btn-warning btn-sm"><i
                                                                        class='bx bx-download'></i> Descargar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-title">Servicios Externos</h6>
                                                            <p class="card-text text-muted small">Reporte de proveedores
                                                                contratados</p>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <h3 class="mb-0 text-info" id="reporte_externos">0</h3>
                                                                <button class="btn btn-info btn-sm"><i
                                                                        class='bx bx-download'></i> Descargar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Modal Invitado -->
                        <div class="modal fade" id="modal_invitado" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><i class='bx bx-user-plus'></i> Agregar Invitado</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre Completo <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="inv_nombre"
                                                placeholder="Nombre del invitado">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Empresa</label>
                                            <input type="text" class="form-control" id="inv_empresa"
                                                placeholder="Empresa del invitado">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Responsable <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="inv_responsable">
                                                <option value="">Seleccione el responsable</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha de Visita <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="inv_fecha">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary" onclick="agregarInvitado()">
                                            <i class='bx bx-save'></i> Crear y Generar QR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal QR -->
                        <div class="modal fade" id="modal_qr" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><i class='bx bx-qr'></i> Código QR Generado</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="qr-container mb-3">
                                            <div class="qr-code">
                                                <i class='bx bx-qr' style="font-size: 150px; color: #000;"></i>
                                            </div>
                                            <h5 class="mt-3" id="qr_nombre">Nombre Invitado</h5>
                                            <p class="mb-0" id="qr_codigo">QR-INV-XXX</p>
                                        </div>
                                        <button class="btn btn-light"><i class='bx bx-download'></i> Descargar
                                            QR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
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
                        <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i>
                            Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>