<!-- CSS requerido -->
<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../assets/plugins/notifications/css/lobibox.min.css" />

<style>
    .card-header-calendar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }

    .card-header-calendar .titles {
        text-align: center;
        flex: 1 1 auto;
        min-width: 220px;
    }

    .card-header-calendar h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e3a8a;
        letter-spacing: 0.5px;
    }

    .card-header-calendar p {
        margin: 0;
        font-size: 0.85rem;
        color: #475569;
    }

    #calendar_persona {
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        padding: 10px;
    }

    /* Estilos para eventos compactos */
    .fc .fc-event {
        border: none;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
        border-radius: 6px;
        padding: 6px 8px;
        font-size: 0.85rem;
        line-height: 1.3;
    }

    .fc .fc-event .fc-event-title {
        font-weight: 700;
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .fc .fc-event .fc-event-time {
        font-weight: 500;
        opacity: 0.9;
    }

    /* Estilos para fin de semana */
    .fc .fc-day-sat,
    .fc .fc-day-sun {
        background-color: #f8f9fa;
    }

    .small-controls {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        white-space: nowrap;
    }

    /* Panel de turnos disponibles */
    .turnos-disponibles {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 20px;
    }

    .turno-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 6px;
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        margin: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .turno-badge .turno-nombre {
        display: block;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .turno-badge .turno-horario {
        display: block;
        font-size: 0.75rem;
        opacity: 0.9;
        margin-top: 2px;
    }

    /* responsive */
    @media (max-width: 768px) {
        .card-header-calendar {
            gap: 0.5rem;
            flex-direction: column;
        }

        .card-header-calendar .titles h3 {
            font-size: 0.95rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-4">
            <div class="card-header-calendar">
                <div style="flex: 0 0 250px;">
                    <label for="ddl_departamentos" class="form-label fw-bold">
                        <i class="bx bxs-building"></i> Departamento
                    </label>
                    <select id="ddl_departamentos" class="form-select form-select-sm">
                        <option value="">-- Seleccione Departamento --</option>
                    </select>
                </div>

                <div class="titles" style="flex: 1;">
                    <h3>📅 Horarios del Personal</h3>
                    <p id="subtitle-center">Vista de horarios asignados</p>
                </div>

                <div class="small-controls">
                </div>
            </div>

            <!-- Panel de horarios - Se muestra cuando hay departamento seleccionado -->
            <div id="pnl_horarios_persona" style="display:none;">
                <!-- Turnos disponibles -->
                <div class="turnos-disponibles" id="pnl_turnos_disponibles" style="display:none;">
                    <h6 class="mb-2">
                        <i class="bx bx-time-five"></i> Turnos Configurados
                    </h6>
                    <div id="lista_turnos_badges"></div>
                </div>

                <!-- Calendario -->
                <div id="calendar_persona"></div>
            </div>

            <!-- Hidden input para ID -->
            <input id="id_perdep" type="hidden" value="" />
        </div>
    </div>
</div>

<div class="modal fade" id="modal_programar_horarios" tabindex="-1" aria-labelledby="modal_programar_horarios_label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_programar_horarios_label">Programar Horario - Personas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form_programar_horarios">

                    <div class="mb-col">
                        <label class="form-label" for="lbl_programar">Programar Horario </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cbx_programar" id="cbx_programar_persona" checked>
                            <label class="form-check-label" for="cbx_programar_persona">Personas</label>
                        </div>
                        <!-- quitamos departamentos: no se mostrará -->
                        <label class="error" style="display: none;" for="cbx_programar"></label>
                    </div>

                    <div class="row pt-3 mb-col" id="pnl_personas">
                        <div class="col-md-8">
                            <label for="ddl_personas" class="form-label">Personas </label>
                            <select class="form-select form-select-sm select2-validation" id="ddl_personas" name="ddl_personas">
                                <option selected disabled>-- Seleccione --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_personas"></label>
                        </div>
                    </div>

                    <!-- Asignar Horario -->
                    <div class="mb-col pt-3">
                        <label class="form-label" for="lbl_asignar_horario">Asignar Horario </label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cbx_horario" id="cbx_horario_con" value="1">
                            <label class="form-check-label" for="cbx_horario_con">Con Horario </label>
                        </div>

                        <div id="pnl_horarios" style="display: none;">
                            <div class="row mb-col">
                                <div class="col-md-8">
                                    <label for="ddl_horarios" class="form-label">Horarios </label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_horarios" name="ddl_horarios">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display: none;" for="ddl_horarios"></label>
                                </div>
                            </div>

                            <div class="form-check ms-4">
                                <input class="form-check-input" type="radio" name="cbx_horario_detalle" id="cbx_horario_detalle_1" value="1">
                                <label class="form-check-label" for="cbx_horario_detalle_1">Tomar en cuenta los intervalos </label>
                            </div>
                            <div class="form-check ms-4">
                                <input class="form-check-input" type="radio" name="cbx_horario_detalle" id="cbx_horario_detalle_2" value="2">
                                <label class="form-check-label" for="cbx_horario_detalle_2">Sin tomar en cuenta los intervalos </label>
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cbx_horario" id="cbx_horario_sin" value="2">
                            <label class="form-check-label" for="cbx_horario_sin">Sin Horario</label>
                        </div>
                        <label class="error" style="display: none;" for="cbx_horario"></label>

                    </div>

                    <div class="row pt-3 mb-col">
                        <label class="form-label" for="lbl_programar">Fechas del periodo</label>
                        <div class="col-md-4">
                            <label for="txt_fecha_inicio" class="form-label">Fecha Inicial </label>
                            <input type="date" class="form-control form-control-sm" id="txt_fecha_inicio" name="txt_fecha_inicio" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="txt_fecha_fin" class="form-label">Fecha Final </label>
                            <input type="date" class="form-control form-control-sm" id="txt_fecha_fin" name="txt_fecha_fin" maxlength="50">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pt-2">
                        <!-- botón guardar (usa la variable PHP $_id que ya tenías) -->
                        <?php if ($_id == '') { ?>
                            <button class="btn btn-success btn-sm px-4 m-0" id="btn_guardar_programacion" type="button"><i class="bx bx-save"></i> Guardar</button>
                        <?php } else { ?>
                            <button class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_programacion" type="button"><i class="bx bx-save"></i> Editar</button>
                            <button class="btn class=" btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_programacion" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                        <?php } ?>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS requerido -->
<script src="../assets/plugins/fullcalendar/js/main.min.js"></script>
<script src="../assets/plugins/notifications/js/lobibox.min.js"></script>

<script>
    // Variable global para el calendario
    var calendar_persona;

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar_persona');

        // Inicializar calendario con FullCalendar
        calendar_persona = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            initialDate: '2024-02-15', // Semana base para mostrar los turnos semanales
            locale: 'es',
            height: 'auto',
            slotMinTime: '00:00:00',
            slotMaxTime: '24:00:00',
            slotDuration: '02:00:00',
            allDaySlot: false,
            editable: false, // Solo visualización
            droppable: false,
            nowIndicator: false, // No mostrar indicador de hora actual
            headerToolbar: false, // Sin navegación de fechas (es vista semanal fija)

            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },

            dayHeaderFormat: {
                weekday: 'long'
            },

            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                hour12: false
            },

            // Aplicar clases especiales a fin de semana
            dayCellClassNames: function(arg) {
                if (arg.date.getDay() === 0 || arg.date.getDay() === 6) {
                    return ['fc-day-weekend'];
                }
                return [];
            },

            // Personalizar renderizado de eventos
            eventDidMount: function(info) {
                const event = info.event;

                // Agregar tooltip con información adicional
                $(info.el).tooltip({
                    title: `${event.title}\n${event.extendedProps.hora_inicio} - ${event.extendedProps.hora_fin}`,
                    placement: 'top',
                    trigger: 'hover'
                });
            },

            eventContent: function(arg) {
                const hora_inicio = arg.event.extendedProps.hora_inicio || '';
                const hora_fin = arg.event.extendedProps.hora_fin || '';

                return {
                    html: `
                        <div style="padding: 4px;">
                            <div class="fc-event-time" style="font-size: 0.7rem; opacity: 0.9;">
                                ${hora_inicio} - ${hora_fin}
                            </div>
                            <div class="fc-event-title" style="font-size: 0.85rem; font-weight: 700; margin-top: 2px;">
                                ${arg.event.title}
                            </div>
                        </div>
                    `
                };
            },

            // Click en evento (opcional - solo para mostrar info)
            eventClick: function(info) {
                const event = info.event;
                Swal.fire({
                    title: event.title,
                    html: `
                        <div class="text-start">
                            <p><strong>Horario:</strong> ${event.extendedProps.hora_inicio} - ${event.extendedProps.hora_fin}</p>
                            <p><strong>Día:</strong> ${obtenerNombreDia(event.extendedProps.dia)}</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Cerrar'
                });
            }
        });

        calendar_persona.render();

        
    });

    // Función para cargar horario de la persona desde el documento principal
    function cargar_persona_horario(id_persona) {
        $.ajax({
            data: {
                id: id_persona
            },
            url: '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?listar_persona_horario=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    const id_horario = response[0].id_horario;
                    cargar_turnos_horario(id_horario);
                } else {
                    Swal.fire('', 'Esta persona no tiene horarios asignados', 'info');
                    calendar_persona.removeAllEvents();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar horario de persona:', error);
                Swal.fire('', 'Error al cargar los horarios', 'error');
            }
        });
    }

    // Función para cargar los turnos del horario
    function cargar_turnos_horario(id_horario) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_turnos_horarioC.php?listar=true',
            type: 'post',
            data: {
                id: id_horario
            },
            dataType: 'json',
            success: function(response) {
                console.log('Turnos recibidos:', response);

                // Limpiar eventos anteriores
                calendar_persona.removeAllEvents();

                // Limpiar badges de turnos
                $('#lista_turnos_badges').empty();

                if (!response || response.length === 0) {
                    Swal.fire('', 'No hay turnos configurados para este horario', 'info');
                    return;
                }

                // Mostrar el panel de horarios
                $('#pnl_horarios_persona').show();
                $('#pnl_turnos_disponibles').show();

                // Objeto para agrupar turnos únicos (para los badges)
                const turnosUnicos = {};

                // Recorrer la respuesta y agregar eventos al calendario
                response.forEach(function(evento) {
                    // Mapear día numérico a fecha fija de la semana
                    let fecha_dia_estatico;

                    switch (evento.dia) {
                        case '1':
                            fecha_dia_estatico = '2024-02-11';
                            break; // Domingo
                        case '2':
                            fecha_dia_estatico = '2024-02-12';
                            break; // Lunes
                        case '3':
                            fecha_dia_estatico = '2024-02-13';
                            break; // Martes
                        case '4':
                            fecha_dia_estatico = '2024-02-14';
                            break; // Miércoles
                        case '5':
                            fecha_dia_estatico = '2024-02-15';
                            break; // Jueves
                        case '6':
                            fecha_dia_estatico = '2024-02-16';
                            break; // Viernes
                        case '7':
                            fecha_dia_estatico = '2024-02-17';
                            break; // Sábado
                        default:
                            fecha_dia_estatico = '2024-02-15';
                    }

                    const hora_inicio_str = minutos_formato_hora(evento.hora_entrada);
                    const hora_fin_str = minutos_formato_hora(evento.hora_salida);

                    // Agregar evento al calendario
                    calendar_persona.addEvent({
                        title: evento.nombre,
                        start: fecha_dia_estatico + 'T' + hora_inicio_str,
                        end: fecha_dia_estatico + 'T' + hora_fin_str,
                        backgroundColor: evento.color || '#2196F3',
                        borderColor: evento.color || '#2196F3',
                        extendedProps: {
                            id_turno_horario: evento._id,
                            id_turno: evento.id_turno,
                            dia: evento.dia,
                            hora_inicio: hora_inicio_str,
                            hora_fin: hora_fin_str
                        }
                    });

                    // Agregar a turnos únicos para los badges
                    if (!turnosUnicos[evento.id_turno]) {
                        turnosUnicos[evento.id_turno] = {
                            nombre: evento.nombre,
                            hora_entrada: hora_inicio_str,
                            hora_salida: hora_fin_str,
                            color: evento.color || '#2196F3'
                        };
                    }
                });

                // Crear badges de turnos únicos
                let badgesHTML = '';
                Object.values(turnosUnicos).forEach(function(turno) {
                    badgesHTML += `
                        <div class="turno-badge" style="background-color: ${turno.color};">
                            <span class="turno-nombre">${turno.nombre}</span>
                            <span class="turno-horario">${turno.hora_entrada} - ${turno.hora_salida}</span>
                        </div>
                    `;
                });
                $('#lista_turnos_badges').html(badgesHTML);

                // Renderizar el calendario
                calendar_persona.render();

                // Notificación de éxito
                Lobibox.notify('success', {
                    size: 'mini',
                    rounded: true,
                    delayIndicator: false,
                    sound: false,
                    position: 'top right',
                    msg: 'Horarios cargados correctamente'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar turnos:', error);
                Swal.fire('', 'Error al cargar los turnos del horario', 'error');
            }
        });
    }

    // Función auxiliar para obtener nombre del día
    function obtenerNombreDia(numeroDia) {
        const dias = {
            '1': 'Domingo',
            '2': 'Lunes',
            '3': 'Martes',
            '4': 'Miércoles',
            '5': 'Jueves',
            '6': 'Viernes',
            '7': 'Sábado'
        };
        return dias[numeroDia] || 'Desconocido';
    }

    // Esta función debe estar disponible desde tu archivo de funciones generales
    // Si no existe, aquí está la implementación
    function minutos_formato_hora(minutos) {
        const horas = Math.floor(minutos / 60);
        const mins = minutos % 60;
        return horas.toString().padStart(2, '0') + ':' + mins.toString().padStart(2, '0') + ':00';
    }
</script>

