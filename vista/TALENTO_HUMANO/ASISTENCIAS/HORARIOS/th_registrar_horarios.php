<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />

<!-- Notificaciones -->
<link rel="stylesheet" href="../assets/plugins/notifications/css/lobibox.min.css" />


<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            datos_col(<?= $_id ?>);
        <?php } ?>

        cargar_turnos();
    });

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_horariosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_tipo').val(response[0].tipo);
                $('#txt_ciclos').val(response[0].ciclos);
                $('#txt_inicio').val(fecha_formateada(response[0].inicio));
            }
        });
    }

    //Varia global para guardar los eventos del calendario
    var arr_eventos_horario = [];

    function editar_insertar(eventos) {
        var txt_nombre = $('#txt_nombre').val();
        var txt_tipo = $('#txt_tipo').val();
        var txt_ciclos = $('#txt_ciclos').val();
        var txt_inicio = $('#txt_inicio').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': txt_nombre,
            'txt_tipo': txt_tipo,
            'txt_ciclos': txt_ciclos,
            'txt_inicio': txt_inicio,
            'arr_eventos_horario': eventos,
        };

        if ($("#form_horario").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }
        //console.log(parametros);

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_horariosC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_horarios';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del horario ya está en uso', 'warning');
                    $(txt_nombre).addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre del horario ya está en uso.');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
        });
    }

    function delete_datos() {
        var id = '<?= $_id ?>';
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                eliminar(id);
            }
        })
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_horariosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_horarios';
                    });
                }
            }
        });
    }

    // Para los turnos

    function cargar_turnos() {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_turnosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let html = '';

                response.forEach(evento => {
                    let turno_nocturno_msg = '';

                    if (evento.turno_nocturno == '1') {
                        turno_nocturno_msg = '(+1)';
                    }

                    html += `<div class="external-event" data-info='${JSON.stringify(evento)}' data-id="${evento._id}" data-title="${evento.nombre}" data-start="${minutos_formato_hora(evento.hora_entrada)}" data-end="${minutos_formato_hora(evento.hora_salida)}" style="background-color: ${evento.color};">`;
                    html += `<div class="event-title text-center">${evento.nombre}</div>`;
                    html += `<div class="event-body text-center">${minutos_formato_hora(evento.hora_entrada)} - ${minutos_formato_hora(evento.hora_salida)} ${turno_nocturno_msg}</div>`;
                    html += `</div>`;
                });
                $('#pnl_turnos').html(html); // Inserta el HTML generado en el contenedor

                //console.log(response);
                inicializar_draggable();
            }
        });
    }
</script>

<!-- Ajustes de css para la vista -->
<style>
    .fc-col-header-cell-cushion {
        text-transform: uppercase;
    }

    /* Estilo personalizado para los días de fin de semana */
    .fc-day-sat,
    .fc-day-sun {
        background-color: #f2f2f2;
        /* Color de fondo para sábado y domingo */
    }



    /* Estilo para el contenedor de eventos */
    .event-contenedor {
        display: flex;
        overflow-x: auto;
        /* Para el scroll horizontal */
        white-space: nowrap;
        /* Evitar que los elementos se rompan en línea */
        padding: 10px;
    }

    /* Estilo para los eventos arrastrables */
    .external-event {
        flex: 0 0 auto;
        /* Para que los eventos mantengan su tamaño y no se ajusten automáticamente */
        margin-right: 10px;
        /* Espacio entre eventos */
        padding: 2px;
        padding-left: 12px;
        padding-right: 12px;
        background-color: #3788d8;
        color: white;
        cursor: grab;
        border-radius: 2px;
    }

    .external-event:active {
        cursor: grabbing;
    }

    /* Estilo para el título del evento */
    .event-title {
        font-weight: bold;
        font-size: 0.9em;
        /* Aumenta el tamaño de la fuente del título */
    }

    /* Estilo para el cuerpo del evento */
    .event-body {
        margin-top: 1px;
        font-size: 0.7em;
        /* Un tamaño de fuente un poco más pequeño para el body */
        color: #e0e0e0;
        /* Un color ligeramente más claro para diferenciarlo */
    }

    /* Opcional: estilo para el scrollbar si deseas personalizarlo */
    .event-contenedor::-webkit-scrollbar {
        height: 8px;
        /* Altura del scrollbar */
    }

    .event-contenedor::-webkit-scrollbar-thumb {
        background-color: #888;
        /* Color de la barra */
        border-radius: 10px;
    }

    .event-contenedor::-webkit-scrollbar-thumb:hover {
        background-color: #555;
        /* Color de la barra al pasar el mouse */
    }
</style>

<style>
    /* Estilos para el contenedor del evento */
    .event-content {
        display: flex;
        flex-direction: column;
        /* Coloca los elementos en una columna */
        justify-content: space-between;
        /* Espacio uniforme entre los elementos */
        height: 100%;
        /* Asegúrate de que el contenido ocupe todo el alto del evento */
        overflow: hidden;
        /* Evita que el contenido desborde */
        padding: 2px;
        /* Establece el padding */
        margin: 0;
        /* Establece el margen a 0 */
    }
</style>

<!-- Configuracion del Calendario -->
<script>
    function inicializar_draggable() {
        var external_events = document.querySelectorAll('.external-event');
        external_events.forEach(function(eventEl) {
            var event_title = eventEl.getAttribute('data-title');
            var event_start = eventEl.getAttribute('data-start');
            var event_end = eventEl.getAttribute('data-end');
            var event_color = eventEl.style.backgroundColor; // Obtener el color del estilo
            var event_ID = eventEl.getAttribute('data-id');

            new FullCalendar.Draggable(eventEl, {
                eventData: {
                    title: event_title,
                    extendedProps: {
                        start_hour: event_start,
                        end_hour: event_end,
                        color: event_color,
                        id_turno: event_ID
                    }
                }
            });
        });
    }


    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: '2024-02-15',
            initialView: 'timeGridWeek',
            locale: 'es',
            headerToolbar: false,
            slotMinTime: '00:00:00',
            slotMaxTime: '24:00:00',
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
            slotDuration: '02:00:00',
            height: 'auto',
            //editable: true, // Hacer los eventos existentes editables
            droppable: true, // Permitir arrastrar eventos externos al calendario

            // events: [{
            //         title: 'Evento 1',
            //         start: '2024-02-15T00:00:00',
            //         end: '2024-02-15T16:00:00',
            //     },
            //     {
            //         title: 'Evento 2',
            //         start: '2024-02-16T14:00:00',
            //         allDay: false, // Evento con duración específica
            //     }
            // ],

            dayCellClassNames: function(arg) {
                if (arg.date.getDay() === 0 || arg.date.getDay() === 6) { // 0 = Domingo, 6 = Sábado
                    return ['fc-day-sat', 'fc-day-sun']; // Agregar clase personalizada a sábados y domingos
                }
            },

            eventReceive: function(info) {
                // Obtener las horas dinámicas desde los datos extendidos
                var titulo = info.event.title;
                var start_hour = info.draggedEl.getAttribute('data-start');
                var end_hour = info.draggedEl.getAttribute('data-end');
                var start_date = info.event.start;
                var id_turno = info.draggedEl.getAttribute('data-id');

                // Obtener el JSON desde data-info
                var info_json = info.draggedEl.getAttribute('data-info');
                var evento = JSON.parse(info_json);
                var turno_nocturno_drag = evento.turno_nocturno;

                // Aplicar horas dinámicas
                var start_time_array = start_hour.split(':');
                var end_time_array = end_hour.split(':');

                if (turno_nocturno_drag == 0) {
                    // Establecer la hora de inicio y fin según los valores dinámicos
                    info.event.setStart(new Date(start_date.setHours(start_time_array[0], start_time_array[1], 0)));
                    info.event.setEnd(new Date(start_date.setHours(end_time_array[0], end_time_array[1], 0)));
                } else {
                    // Establecer la hora de inicio y fin según los valores dinámicos, sumando un día al fin
                    info.event.setStart(new Date(start_date.setHours(start_time_array[0], start_time_array[1], 0)));

                    let end_date = new Date(start_date); // Copiar la fecha para no modificar `start_date`
                    end_date.setDate(end_date.getDate() + 1); // Sumar un día
                    info.event.setEnd(new Date(end_date.setHours(end_time_array[0], end_time_array[1], 0)));
                }

                //alert('Nuevo evento añadido: ' + info.event.title + ' con horario ' + start_hour + ' a ' + end_hour);

                var color = info.draggedEl.style.backgroundColor;
                info.event.setProp('backgroundColor', color);
                info.event.setProp('borderColor', color);

                /**
                 * Para validacion que no se repitan en las horas y dias
                 * 
                 */

                // Mostrar los valores de inicio y fin del evento en la consola
                // console.log(`Fecha de inicio evento: ${info.event.start}`);
                // console.log(`Fecha de fin evento: ${info.event.end}`);


                /////////////////////////////////////////////////////////////////////////
                // Para poner los limites de las horas relacionado con los turnos
                /////////////////////////////////////////////////////////////////////////

                var checkin_registro_inicio_drag = evento.checkin_registro_inicio;
                var checkout_salida_fin_drag = evento.checkout_salida_fin;

                // Convertir los minutos a horas y minutos
                var hora_inicio = Math.floor(checkin_registro_inicio_drag / 60); // Hora de inicio
                var minutos_inicio = checkin_registro_inicio_drag % 60; // Minutos de inicio

                var hora_fin = Math.floor(checkout_salida_fin_drag / 60); // Hora de fin
                var minutos_fin = checkout_salida_fin_drag % 60; // Minutos de fin

                // Copiar las fechas originales sin alterarlas
                var fecha_inicio_temporal = new Date(info.event.start); // Copiar la fecha de inicio
                var fecha_fin_temporal = new Date(info.event.end); // Copiar la fecha de fin

                // Ajustar las horas y minutos de las copias sin alterar las fechas originales
                fecha_inicio_temporal.setHours(hora_inicio, minutos_inicio, 0, 0); // Establece la hora de inicio
                fecha_fin_temporal.setHours(hora_fin, minutos_fin, 0, 0); // Establece la hora de fin


                /////////////////////////////////////////////////////////////////////////
                // Para no tomar en cuanta los limites
                /////////////////////////////////////////////////////////////////////////

                // var fecha_inicio_temporal = info.event.start;
                // var fecha_fin_temporal = info.event.end;

                /////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////

                var eventos_existentes = calendar.getEvents();

                if (eventos_existentes.length != 0) {

                    var eventos_coincidentes = eventos_existentes.filter(function(event) {
                        // Obtener el rango de fechas del evento existente
                        var inicio_evento_existente = event.start;
                        var fin_evento_existente = event.end;

                        // Validar si hay superposición de rangos
                        return (
                            (fecha_inicio_temporal >= inicio_evento_existente && fecha_inicio_temporal <= fin_evento_existente) || // Empieza dentro del rango
                            (fecha_fin_temporal >= inicio_evento_existente && fecha_fin_temporal <= fin_evento_existente) || // Termina dentro del rango
                            (fecha_inicio_temporal <= inicio_evento_existente && fecha_fin_temporal >= fin_evento_existente) // Contiene completamente al rango
                        );
                    });

                    // Contar cuántos eventos coinciden
                    var conteo_eventos = eventos_coincidentes.length;

                    if (conteo_eventos > 1) {
                        // Mostrar mensaje de error y remover el evento
                        error_notificacion('Tenga en cuenta que solo puede asignar un turno por día en este rango.');
                        info.event.remove();
                        return;
                    }
                }

                //Mostrar eventos
                // eventos_existentes.forEach(evento => {
                //     var fecha_inicio = evento.start; // Fecha de inicio
                //     var fecha_fin = evento.end; // Fecha de fin

                //     console.log(`Evento: ${evento.title || 'Sin título'}`);
                //     console.log(`Inicio: ${fecha_inicio}`);
                //     console.log(`Fin: ${fecha_fin}`);
                // });
            },

            eventContent: function(arg) {
                var fecha = arg.event.start;
                var start_time = fecha ?
                    fecha.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    }) :
                    "00:00";

                var end_time = arg.event.end ?
                    arg.event.end.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    }) :
                    "00:00";

                var id_turno_horario = arg.event.extendedProps.id_turno_horario;

                // Crear contenedor principal
                var contenedor = document.createElement('div');
                contenedor.className = 'event-content';

                // Crear etiqueta para el tiempo del evento
                var tiempo_evento = document.createElement('p');
                tiempo_evento.className = 'event-time';
                tiempo_evento.style.margin = '0';
                tiempo_evento.style.fontSize = '9px';
                tiempo_evento.textContent = `${start_time} - ${end_time}`;

                // Crear etiqueta para el título del evento
                var titulo_evento = document.createElement('p');
                titulo_evento.className = 'event-title';
                titulo_evento.style.margin = '0';
                titulo_evento.style.fontSize = '12px';
                titulo_evento.style.fontWeight = 'bold';
                titulo_evento.textContent = arg.event.title;

                // Botón de eliminar
                var eliminar_boton = document.createElement('a');
                eliminar_boton.title = 'Eliminar Turno';
                eliminar_boton.className = 'btn btn-dark';
                eliminar_boton.style.padding = '2px';
                eliminar_boton.style.fontSize = '10px';
                eliminar_boton.innerHTML = '<i class="bx bx-trash-alt me-0" style="font-size: 12px;"></i>';
                eliminar_boton.onclick = function() {
                    eliminar_evento(id_turno_horario, arg.event.title, arg.event);
                };

                // Botón de duplicar
                var duplicar_boton = document.createElement('a');
                duplicar_boton.title = 'Duplicar Turno';
                duplicar_boton.className = 'btn btn-secondary';
                duplicar_boton.style.padding = '2px';
                duplicar_boton.style.fontSize = '10px';
                duplicar_boton.innerHTML = '<i class="bx bxs-arrow-to-right me-0" style="font-size: 12px;"></i>';
                // Asignar la función de duplicar al botón
                duplicar_boton.onclick = function() {
                    // Obtener la fecha del evento actual
                    var fecha_actual = new Date(fecha); // Convertir a objeto Date
                    var siguiente_dia = new Date(fecha_actual); // Copiar la fecha actual
                    siguiente_dia.setDate(fecha_actual.getDate() + 1); // Incrementar en 1 día

                    // Formatear la fecha al formato necesario (si es requerido, por ejemplo 'YYYY-MM-DD')
                    var fecha_formateada = siguiente_dia.toISOString().split('T')[0];

                    // Llamar a la función con la nueva fecha
                    crear_turno_horario_igual(
                        arg.event.title,
                        start_time,
                        end_time,
                        fecha_formateada, // Pasar la fecha del día siguiente
                        arg.event.extendedProps.id_turno,
                        arg.event.backgroundColor
                    );
                }

                // Contenedor de botones
                var grupo_botones = document.createElement('div');
                grupo_botones.className = 'btn-group';
                grupo_botones.style.display = 'flex';
                grupo_botones.style.justifyContent = 'flex-end';
                grupo_botones.appendChild(eliminar_boton);
                grupo_botones.appendChild(duplicar_boton);

                // Ensamblar todo
                contenedor.appendChild(tiempo_evento);
                contenedor.appendChild(titulo_evento);
                contenedor.appendChild(grupo_botones);

                return {
                    domNodes: [contenedor]
                };
            }
        });

        //Validacion para cuando se va a hacer un registro o editar
        <?php if (isset($_GET['_id'])) { ?> cargar_turnos_horario(<?= $_id ?>);
        <?php } else { ?> calendar.render();
        <?php }  ?>

        // Manejar el clic en el botón - Funcion para guardar o editar todo el formulario 
        document.getElementById('btn_guardar').addEventListener('click', function() {
            var events = calendar.getEvents();

            if (events.length > 0) {
                arr_eventos_horario = [];
                events.forEach(function(event) {
                    //console.log("Dia: ", dia_numero(event.start), " - ID_turno: ", event.extendedProps.id_turno);

                    var eventos_datos = {
                        id_turno: event.extendedProps.id_turno,
                        dia: dia_numero(event.start),
                    };

                    arr_eventos_horario.push(eventos_datos);
                });

            } else {
                console.log("No hay eventos en el calendario.");
            }

            editar_insertar(arr_eventos_horario);
        });

    });

    //Cargar turnos - horario 
    function cargar_turnos_horario(id_horario) {

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_turnos_horarioC.php?listar=true',
            type: 'post',
            data: {
                id: id_horario,
            },
            dataType: 'json',

            success: function(response) {

                calendar.removeAllEvents();
                // Recorrer la respuesta y agregar eventos al arreglo events
                response.forEach(function(evento) {
                    //console.log(evento);

                    if (evento.dia == '1') {
                        fecha_dia_estatico = '2024-02-11';
                    } else if (evento.dia == '2') {
                        fecha_dia_estatico = '2024-02-12';
                    } else if (evento.dia == '3') {
                        fecha_dia_estatico = '2024-02-13';
                    } else if (evento.dia == '4') {
                        fecha_dia_estatico = '2024-02-14';
                    } else if (evento.dia == '5') {
                        fecha_dia_estatico = '2024-02-15';
                    } else if (evento.dia == '6') {
                        fecha_dia_estatico = '2024-02-16';
                    } else if (evento.dia == '7') {
                        fecha_dia_estatico = '2024-02-17';
                    }

                    calendar.addEvent({
                        //id: evento.id_turno,
                        title: (evento.nombre),
                        start: fecha_dia_estatico + 'T' + minutos_formato_hora(evento.hora_entrada),
                        end: fecha_dia_estatico + 'T' + minutos_formato_hora(evento.hora_salida),
                        extendedProps: {
                            id_turno_horario: evento._id,
                            id_turno: evento.id_turno,
                        },

                        color: evento.color

                    });
                });
                // Renderizar el calendario después de agregar los eventos
                calendar.render();

            }
        });

    }

    //Duplicar evento
    function crear_turno_horario_igual(title, start_time, end_time, fecha, id_turno, color) {
        // Combinar fecha y horas para crear el rango temporal del nuevo turno
        var fecha_inicio_temporal = new Date(`${fecha}T${start_time}`);
        var fecha_fin_temporal = new Date(`${fecha}T${end_time}`);

        // Obtener todos los eventos existentes del calendario
        var eventos_existentes = calendar.getEvents();

        // Filtrar los eventos que coincidan en el rango
        var eventos_coincidentes = eventos_existentes.filter(function(event) {
            // Obtener el rango de fechas del evento existente
            var inicio_evento_existente = new Date(event.start);
            var fin_evento_existente = new Date(event.end);

            // Validar si hay superposición de rangos
            return (
                (fecha_inicio_temporal >= inicio_evento_existente && fecha_inicio_temporal <= fin_evento_existente) || // Empieza dentro del rango
                (fecha_fin_temporal >= inicio_evento_existente && fecha_fin_temporal <= fin_evento_existente) || // Termina dentro del rango
                (fecha_inicio_temporal <= inicio_evento_existente && fecha_fin_temporal >= fin_evento_existente) // Contiene completamente al rango
            );
        });

        // Validar si hay eventos coincidentes
        if (eventos_coincidentes.length > 0) {
            // Mostrar mensaje de error
            error_notificacion('Tenga en cuenta que solo puede asignar un turno por día en este rango.');
            return; // Detener la ejecución si hay conflictos
        }

        // Si no hay conflictos, crear el nuevo turno
        var nuevo_turno = {
            title: title,
            start: `${fecha}T${start_time}`, // Combinar fecha y hora de inicio
            end: `${fecha}T${end_time}`, // Combinar fecha y hora de fin
            extendedProps: {
                id_turno: id_turno // Relacionar con el turno original
            },
            color: color
        };

        // Agregar el evento al calendario
        calendar.addEvent(nuevo_turno);
    }

    //Eliminar turnos - horario 
    function eliminar_evento(id_turno_horario, titulo, evento) {

        //alert(id_horarioD + ' - ' + titulo);

        Swal.fire({
            title: '¿Está seguro de eliminar el turno ' + titulo + '?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, estoy seguro.'
        }).then((result) => {
            if (result.isConfirmed) {
                if (id_turno_horario != null && id_turno_horario != '') {
                    $.ajax({
                        url: '../controlador/TALENTO_HUMANO/th_turnos_horarioC.php?eliminar=true',
                        type: 'POST',
                        data: {
                            id: id_turno_horario
                        },
                        success: function(response) {
                            Swal.fire('Éxito', 'La operación se realizó con éxito', 'success');
                        },
                        error: function() {
                            Swal.fire('Error', 'Hubo un error en la operación', 'error');
                        }
                    });
                }

                evento.remove();
            }
        });
    }

    //Eliminar todos los turnos - horario 
    function eliminar_todos_turnos_horarios(id_horario) {

        Swal.fire({
            title: '¿Está seguro de eliminar todos los turnos?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, estoy seguro.'
        }).then((result) => {
            if (result.isConfirmed) {
                if (id_horario != null && id_horario != '') {
                    $.ajax({
                        url: '../controlador/TALENTO_HUMANO/th_turnos_horarioC.php?eliminar_todos=true',
                        type: 'POST',
                        data: {
                            id_horario: id_horario
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire('Éxito', 'La operación se realizó con éxito', 'success');
                            } else {
                                Swal.fire('Error', 'Hubo un error en la operación', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Hubo un error en la operación', 'error');
                        }
                    });
                }
            }
        });

        calendar.removeAllEvents();
    }

    //Formatear la fecha de la libreria calendar
    function formatear_fecha_calendar(fecha) {
        var dia = fecha.getDate();
        var mes = fecha.getMonth() + 1;
        var anio = fecha.getFullYear(); // Obtener el año

        // Asegurarse de que el día y el mes tengan dos dígitos
        if (dia < 10) {
            dia = '0' + dia;
        }
        if (mes < 10) {
            mes = '0' + mes;
        }

        // Formatear la fecha como dd/mm/yyyy
        return dia + '/' + mes + '/' + anio;
    }

    function dia_numero(fecha) {
        var date = new Date(fecha);
        var dia_date = date.getDay();

        var ajustar_dia = dia_date === 0 ? 1 : dia_date + 1;
        return (ajustar_dia);
    }

    function error_notificacion(msg) {
        Lobibox.notify('error', {
            pauseDelayOnHover: true,
            size: 'mini',
            rounded: true,
            delayIndicator: false,
            icon: 'bx bx-x-circle',
            continueDelayOnInactiveTab: false,
            position: 'top right',
            msg: msg,
            sound: false,
        });
    }
</script>


<script>
    //Funciones adicionales 

    $(document).ready(function() {
        $('#txt_tipo').change(function() {
            var seleccionado = $(this).val();

            $('#pnl_tipo_diario').hide();

            if (seleccionado == '1') {
                $('#pnl_tipo_diario').show();
            } else {
                $('#pnl_tipo_diario').hide();
            }

            $(this).blur();
        });
    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Horarios</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Horario
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

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Horario';
                                } else {
                                    echo 'Modificar Horario';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_horarios" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_horario">

                            <div class="row pt-3 mb-col">

                                <div class="col-md-6">
                                    <label for="txt_nombre" class="form-label">Nombre </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="50">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_tipo" class="form-label">Tipo </label>
                                    <select class="form-select form-select-sm" id="txt_tipo" name="txt_tipo" disabled>
                                        <option value="1">Diario</option>
                                        <option selected value="7">Semanal</option>
                                        <option value="31">Mensual</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row mb-col" id="pnl_tipo_diario" style="display: none;">
                                <div class="col-md-6">
                                </div>

                                <div class="col-md-3">
                                    <label for="txt_ciclos" class="form-label">Ciclo </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_ciclos" name="txt_ciclos">
                                </div>

                                <div class="col-md-3">
                                    <label for="txt_inicio" class="form-label">Inicio </label>
                                    <input type="date" class="form-control form-control-sm no_caracteres" id="txt_inicio" name="txt_inicio">
                                </div>
                            </div>

                            <div class="row">
                                <b>Turnos</b>

                                <div class="row mb-col">
                                    <div class="col-md-12 col-12">
                                        <div class="event-contenedor border border-2 bg-secondary border-opacity-10 bg-opacity-10" id="pnl_turnos">
                                            <!-- <div class="external-event" data-title="Evento A" data-start="07:00:00" data-end="15:30:00">
                                                <div class="event-title text-center">Evento A</div>
                                                <div class="event-body text-center">07:00 - 15:30</div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-10 col-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-10 col-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm px-4 m-0" onclick="eliminar_todos_turnos_horarios(<?= $_id ?>);"><i class="bx bxs-trash pb-1 me-0"></i> Eliminar todos los eventos</button>
                                    </div>
                                </div>
                            </div>


                            <div class="row pt-3">
                                <div class="d-flex justify-content-start pt-2">

                                    <?php if ($_id == '') { ?>
                                        <button type="button" class="btn btn-success btn-sm px-4 m-0" id="btn_guardar" onclick=""><i class="bx bx-save"></i> Guardar</button>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar" onclick=""><i class="bx bx-save"></i> Editar</button>
                                        <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="" onclick="delete_datos()"><i class="bx bx-trash"></i> Eliminar</button>
                                    <?php } ?>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script>
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label

        agregar_asterisco_campo_obligatorio('ddl_modelo');
        agregar_asterisco_campo_obligatorio('txt_nombre');

        $("#form_horario").validate({
            rules: {

                txt_nombre: {
                    required: true,
                },

            },
            messages: {
                txt_nombre: {
                    required: "El campo 'Nombre' es obligatorio",
                },

            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

            }
        });
    });
</script>

<script src="../assets/plugins/fullcalendar/js/main.min.js"></script>
<script src="../assets/js/app-fullcalendar.js"></script>
<script src="../assets/plugins/notifications/js/lobibox.min.js"></script>