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
                console.log(response);
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
        console.log(parametros);

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
            // data: {
            //     id: id
            // },
            url: '../controlador/TALENTO_HUMANO/th_turnosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let html = '';
                response.forEach(evento => {
                    html += `<div class="external-event" data-id="${evento._id}" data-title="${evento.nombre}" data-start="${minutos_formato_hora(evento.hora_entrada)}" data-end="${minutos_formato_hora(evento.hora_salida)}" style="background-color: ${evento.color};">`;
                    html += `<div class="event-title text-center">${evento.nombre}</div>`;
                    html += `<div class="event-body text-center">${minutos_formato_hora(evento.hora_entrada)} - ${minutos_formato_hora(evento.hora_salida)}</div>`;
                    html += `</div>`;
                });
                $('#pnl_turnos').html(html); // Inserta el HTML generado en el contenedor

                console.log(response);
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
    .event-container {
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
    .event-container::-webkit-scrollbar {
        height: 8px;
        /* Altura del scrollbar */
    }

    .event-container::-webkit-scrollbar-thumb {
        background-color: #888;
        /* Color de la barra */
        border-radius: 10px;
    }

    .event-container::-webkit-scrollbar-thumb:hover {
        background-color: #555;
        /* Color de la barra al pasar el mouse */
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

                // Aplicar horas dinámicas
                var startTimeArray = start_hour.split(':');
                var endTimeArray = end_hour.split(':');

                // Establecer la hora de inicio y fin según los valores dinámicos
                info.event.setStart(new Date(start_date.setHours(startTimeArray[0], startTimeArray[1], 0)));
                info.event.setEnd(new Date(start_date.setHours(endTimeArray[0], endTimeArray[1], 0)));

                //alert('Nuevo evento añadido: ' + info.event.title + ' con horario ' + start_hour + ' a ' + end_hour);

                var color = info.draggedEl.style.backgroundColor;
                info.event.setProp('backgroundColor', color);
                info.event.setProp('borderColor', color);

                /**
                 * Para validacion que no se repitan
                 * 
                 */

                var eventos_existentes = calendar.getEvents();
                var fecha_calendario_arrastrable = formatear_fecha_calendar(start_date);

                var eventosCoincidentes = eventos_existentes.filter(function(event) {
                    fecha_calendario_existente = formatear_fecha_calendar(event.start);
                    return fecha_calendario_existente === fecha_calendario_arrastrable;
                });

                // Contar cuántos eventos coinciden
                var conteoEventos = eventosCoincidentes.length;

                if (conteoEventos > 1) {
                    error_notificacion('Tenga en cuenta que solo puede asignar un turno por día.')
                    info.event.remove();
                    return;
                }
            }

        });

        <?php if (isset($_GET['_id'])) { ?>
            cargar_turnos_horario(<?= $_id ?>);
        <?php } else { ?>
            calendar.render();
        <?php }  ?>

        // Manejar el clic en el botón
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
                            id_turno: evento._id,
                        },

                        color: evento.color

                    });
                });
                // Renderizar el calendario después de agregar los eventos
                calendar.render();

            }
        });

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
                                    <select class="form-select form-select-sm" id="txt_tipo" name="txt_tipo">
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
                                    <label for="txt_ciclo" class="form-label">Ciclo </label>
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
                                        <div class="event-container border border-2 bg-secondary border-opacity-10 bg-opacity-10" id="pnl_turnos">
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