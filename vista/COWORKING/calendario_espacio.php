
<head>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css" rel="stylesheet" />
</head>
<body>
    <div class="page-wrapper">
        <div class="page-content">
            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Formulario</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Calendario espacios</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- Botón para detalles -->
            <a href="http://localhost/corsinf/vista/inicio.php?mod=1010&acc=crear_mienbrosdos" class="btn btn-info">
                Detalles Espacios
            </a>

            <!-- Calendario -->
            <div id="calendar" style="max-width: 1400px; margin: 0 auto;"></div>

            <!-- Modal para seleccionar espacio -->
            <div class="modal fade" id="modalEspacios" tabindex="-1" aria-labelledby="modalEspaciosLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEspaciosLabel">Crear Evento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEvento">
                                <div class="mb-3">
                                    <label for="selectEspacio" class="form-label">Espacios disponibles</label>
                                    <select id="selectEspacio" class="form-select" required>
                                        <!-- Opciones cargadas dinámicamente -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tituloEvento" class="form-label">Título del Evento</label>
                                    <input type="text" id="tituloEvento" class="form-control" placeholder="Ingresa el título" required>
                                </div>
                                <div class="mb-3">
                                    <label for="detalleEvento" class="form-label">Detalle del Evento</label>
                                    <textarea id="detalleEvento" class="form-control" rows="3" placeholder="Ingresa una descripción breve"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="contactoEvento" class="form-label">Contacto</label>
                                    <input type="email" id="contactoEvento" class="form-control" placeholder="Correo electrónico del contacto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="responsableEvento" class="form-label">Responsable</label>
                                    <input type="text" id="responsableEvento" class="form-control" placeholder="Nombre del responsable" required>
                                </div>
                                <div class="mb-3">
                                    <label for="estadoPagoEvento" class="form-label">Estado de Pago</label>
                                    <select id="estadoPagoEvento" class="form-select">
                                        <option value="1">Pagado</option>
                                        <option value="0">Pendiente</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="confirmarEspacio">Guardar Evento</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>

    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

      // Inicializar el calendario
const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    editable: true,
    selectable: true,
    events: 'load_events.php', 
    select: function (info) {
        const modal = new bootstrap.Modal(document.getElementById('modalEspacios'));
        modal.show();

        // Realizar la solicitud fetch para listar los espacios
        fetch('../controlador/COWORKING/crear_oficinaC.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'accion=listarEspacios'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json(); // Asegúrate de que la respuesta sea JSON válido
        })
        .then(data => {
            const select = document.getElementById('selectEspacio');
            select.innerHTML = ''; // Limpia las opciones actuales

            if (data.success) {
                // Agrega las opciones al select
                data.data.forEach(espacio => {
                    const option = document.createElement('option');
                    option.value = espacio.id;
                    option.textContent = espacio.nombre;
                    select.appendChild(option);
                });
            } else {
                alert('No se pudieron cargar los espacios.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo cargar la lista de espacios. Verifica la consola para más detalles.');
        });

            document.getElementById('confirmarEspacio').onclick = function () {
                const titulo = document.getElementById('tituloEvento').value;
                const detalle = document.getElementById('detalleEvento').value;
                const contacto = document.getElementById('contactoEvento').value;
                const responsable = document.getElementById('responsableEvento').value;
                const estadoPago = document.getElementById('estadoPagoEvento').value;
                const selectedSpace = document.getElementById('selectEspacio').value;

                if (titulo && selectedSpace) {
                    const eventData = {
                        titulo: titulo,
                        detalle: detalle,
                        id_espacio: selectedSpace,
                        fechaInicio: info.startStr,
                        fechaFin: info.endStr,
                        estado_pago: estadoPago,
                        contacto: contacto,
                        responsable: responsable,
                    };

                    // Enviar datos al servidor para guardar el evento
                    fetch('../controlador/COWORKING/crear_oficinaC.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            accion: 'guardarEvento',
                            ...eventData
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mostrar alerta de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Evento guardado!',
                                text: 'El evento se guardó correctamente.',
                                confirmButtonText: 'Aceptar'
                            });

                            // Agregar el evento al calendario
                            calendar.addEvent({
                                title: titulo,
                                start: info.startStr,
                                end: info.endStr,
                                extendedProps: { id: selectedSpace }
                            });

                            modal.hide(); // Cerrar el modal
                        } else {
                            // Mostrar alerta de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'No se pudo guardar el evento.',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Mostrar alerta de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al guardar el evento. Verifica la consola para más detalles.',
                            confirmButtonText: 'Aceptar'
                        });
                    });
                } else {
                    // Mostrar alerta si faltan datos
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, completa todos los campos antes de guardar.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            };
        }
    });

    calendar.render();
});


    </script>
</body>
