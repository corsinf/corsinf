<style>
    .event-contenedor {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        padding: 10px;
        min-height: 60px;
    }

    .event-contenedor::-webkit-scrollbar {
        height: 8px;
    }

    .event-contenedor::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 10px;
    }

    .event-contenedor::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }

    .external-event {
        flex: 0 0 auto;
        margin-right: 10px;
        padding: 4px 12px;
        color: white;
        cursor: grab;
        border-radius: 4px;
        user-select: none;
    }

    .external-event:active {
        cursor: grabbing;
    }

    .event-title {
        font-weight: bold;
        font-size: 0.9em;
    }

    .event-body {
        margin-top: 1px;
        font-size: 0.75em;
        color: #e0e0e0;
    }

    .fc-col-header-cell-cushion {
        text-transform: uppercase;
    }

    .fc-day-sat,
    .fc-day-sun {
        background-color: #f2f2f2;
    }

    .event-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        overflow: hidden;
        padding: 2px;
        margin: 0;
    }

    .fc .fc-timegrid-slot {
        height: 45px !important;
        min-height: 45px !important;
    }

    .fc .fc-timegrid-slot-label {
        font-size: 0.75rem;
    }

    .event-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        overflow: hidden;
        padding: 4px;
        /* era 2px */
        margin: 0;
        min-height: 60px;
        /* agregar */
    }
</style>



<?php if ($_id != ''): ?>
    <!-- JS TAB TURNOS -->
    <script type="text/javascript">
        const FECHA_BASE = {
            0: '2024-02-11',
            1: '2024-02-12',
            2: '2024-02-13',
            3: '2024-02-14',
            4: '2024-02-15',
            5: '2024-02-16',
            6: '2024-02-17'
        };

        var calendar;
        var calendar_listo = false;

        function minutos_a_hora(min) {
            min = ((min % 1440) + 1440) % 1440;
            var h = Math.floor(min / 60);
            var m = min % 60;
            return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
        }

        document.addEventListener('DOMContentLoaded', function() {
            var tabTurnos = document.querySelector('a[href="#tab_turnos"]');
            if (tabTurnos) {
                tabTurnos.addEventListener('shown.bs.tab', function() {
                    if (!calendar_listo) {
                        inicializar_calendar();
                    } else {
                        calendar.updateSize();
                    }
                });
            }
        });

        function inicializar_calendar() {
            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialDate: '2024-02-12',
                initialView: 'timeGridWeek',
                locale: 'es',
                headerToolbar: false,
                hiddenDays: [0],
                slotMinTime: '7:00:00',
                slotMaxTime: '20:00:00',
                allDaySlot: false,
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    hour12: false
                },
                dayHeaderFormat: {
                    weekday: 'long'
                },
                slotDuration: '01:00:00',
                slotMinTime: '07:00:00',
                slotMaxTime: '20:00:00',
                expandRows: true,
                height: 650,
                slotLabelInterval: '01:00:00',

                dayCellClassNames: function(arg) {
                    var d = arg.date.getDay();
                    if (d === 0 || d === 6) return ['fc-day-sat', 'fc-day-sun'];
                },

                eventReceive: function(info) {
                    var id_espacio = '<?= $_id ?>';
                    var hub_tur_id = info.event.extendedProps.hub_tur_id;
                    var entrada = info.event.extendedProps.entrada;
                    var salida = info.event.extendedProps.salida;
                    var color = info.event.extendedProps.color;
                    var fecha = info.event.start;
                    var dia = fecha.getDay();

                    var hIni = entrada.split(':');
                    var hFin = salida.split(':');
                    info.event.setStart(new Date(new Date(fecha).setHours(parseInt(hIni[0]), parseInt(hIni[1]), 0)));
                    info.event.setEnd(new Date(new Date(fecha).setHours(parseInt(hFin[0]), parseInt(hFin[1]), 0)));
                    info.event.setProp('backgroundColor', color);
                    info.event.setProp('borderColor', color);

                    var ini_nuevo = info.event.start;
                    var fin_nuevo = info.event.end;
                    var solapado = calendar.getEvents().filter(function(ev) {
                        if (ev === info.event) return false;
                        if (ev.start.getDay() !== dia) return false;
                        return ini_nuevo < ev.end && fin_nuevo > ev.start;
                    });

                    if (solapado.length > 1) {
                        error_notificacion('Ya existe un turno en ese rango para este dia.');
                        info.event.remove();
                        return;
                    }

                    $.ajax({
                        data: {
                            parametros: {
                                id_espacio: id_espacio,
                                hub_tur_id: hub_tur_id,
                                dia: dia
                            }
                        },
                        url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_turnosC.php?insertar=true',
                        type: 'post',
                        dataType: 'json',
                        success: function(response) {
                            if (response == -2) {
                                error_notificacion('Este turno ya está asignado a este espacio en ese día.');
                                info.event.remove();
                            } else if (response == -3) {
                                error_notificacion('El horario se choca con otro turno asignado en ese día.');
                                info.event.remove();
                            } else if (parseInt(response) > 0) {
                                info.event.setExtendedProp('_id', parseInt(response));
                            } else {
                                error_notificacion('Error al guardar la asignación.');
                                info.event.remove();
                            }
                        },
                        error: function() {
                            error_notificacion('Error de conexion.');
                            info.event.remove();
                        }
                    });
                },

                eventContent: function(arg) {
                    var start_time = arg.event.start ? arg.event.start.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    }) : '--:--';
                    var end_time = arg.event.end ? arg.event.end.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    }) : '--:--';
                    var _id = arg.event.extendedProps._id;

                    var contenedor = document.createElement('div');
                    contenedor.className = 'event-content';

                    var tiempo = document.createElement('p');
                    tiempo.style.cssText = 'margin:0;font-size:9px;';
                    tiempo.textContent = start_time + ' - ' + end_time;

                    var titulo = document.createElement('p');
                    titulo.style.cssText = 'margin:0;font-size:12px;font-weight:bold;';
                    titulo.textContent = arg.event.title;

                    var btnEliminar = document.createElement('a');
                    btnEliminar.title = 'Eliminar';
                    btnEliminar.className = 'btn btn-dark';
                    btnEliminar.style.cssText = 'padding:2px;font-size:10px;';
                    btnEliminar.innerHTML = '<i class="bx bx-trash-alt me-0" style="font-size:12px;"></i>';
                    btnEliminar.onclick = function() {
                        eliminar_asignacion(_id, arg.event.title, arg.event);
                    };

                    var grupo = document.createElement('div');
                    grupo.className = 'btn-group';
                    grupo.style.cssText = 'display:flex;justify-content:flex-end;';
                    grupo.appendChild(btnEliminar);

                    contenedor.appendChild(tiempo);
                    contenedor.appendChild(titulo);
                    contenedor.appendChild(grupo);
                    return {
                        domNodes: [contenedor]
                    };
                }
            });

            calendar.render();
            calendar_listo = true;
            cargar_turnos_panel();
            cargar_asignaciones('<?= $_id ?>');
        }

        function cargar_turnos_panel() {
            $.ajax({
                url: '../controlador/HOST_TIME/TURNOS/hub_turnosC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    var html = '';
                    response.forEach(function(t) {
                        var entrada = minutos_a_hora(t.hora_entrada);
                        var salida = minutos_a_hora(t.hora_salida);
                        var color = t.color || '#0d6efd';
                        html += '<div class="external-event text-center"' +
                            ' data-hub-tur-id="' + t._id + '"' +
                            ' data-nombre="' + t.nombre + '"' +
                            ' data-entrada="' + entrada + '"' +
                            ' data-salida="' + salida + '"' +
                            ' data-color="' + color + '"' +
                            ' style="background-color:' + color + ';">' +
                            '<div class="event-title">' + t.nombre + '</div>' +
                            '<div class="event-body">' + entrada + ' - ' + salida + '</div>' +
                            '</div>';
                    });
                    $('#pnl_turnos').html(html);
                    inicializar_draggable();
                }
            });
        }

        function cargar_asignaciones(id_espacio) {
            $.ajax({
                data: {
                    id_espacio: id_espacio
                },
                url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_turnosC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    calendar.removeAllEvents();
                    response.forEach(function(a) {
                        var fecha = FECHA_BASE[a.hub_tuh_dia];
                        var entrada = minutos_a_hora(a.hora_entrada);
                        var salida = minutos_a_hora(a.hora_salida);
                        calendar.addEvent({
                            title: a.nombre,
                            start: fecha + 'T' + entrada,
                            end: fecha + 'T' + salida,
                            backgroundColor: a.color || '#0d6efd',
                            borderColor: a.color || '#0d6efd',
                            extendedProps: {
                                _id: parseInt(a._id),
                                hub_tur_id: a.hub_tur_id,
                                dia: a.hub_tuh_dia
                            }
                        });
                    });
                }
            });
        }

        function inicializar_draggable() {
            document.querySelectorAll('.external-event').forEach(function(el) {
                new FullCalendar.Draggable(el, {
                    eventData: {
                        title: el.dataset.nombre,
                        backgroundColor: el.dataset.color,
                        borderColor: el.dataset.color,
                        extendedProps: {
                            hub_tur_id: el.dataset.hubTurId,
                            entrada: el.dataset.entrada,
                            salida: el.dataset.salida,
                            color: el.dataset.color,
                        }
                    }
                });
            });
        }

        function eliminar_asignacion(_id, titulo, fcEvent) {
            Swal.fire({
                title: 'Eliminar turno ' + titulo + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                if (_id) {
                    $.ajax({
                        data: {
                            id: _id
                        },
                        url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_turnosC.php?eliminar=true',
                        type: 'post',
                        dataType: 'json',
                        success: function(r) {
                            if (r == 1) fcEvent.remove();
                        }
                    });
                } else {
                    fcEvent.remove();
                }
            });
        }

        function error_notificacion(msg) {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: msg,
                sound: false,
            });
        }
    </script>
<?php endif; ?>


<div class="row mb-3">
    <div class="col-12">
        <label class="form-label fw-bold">Turnos disponibles</label>
        <div class="event-contenedor border border-2 bg-secondary border-opacity-10 bg-opacity-10"
            id="pnl_turnos"></div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-12">
        <div id="calendar"></div>
    </div>
</div>