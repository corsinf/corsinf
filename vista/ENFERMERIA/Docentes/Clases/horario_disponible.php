<?php

$id_docente = '1';

if (isset($_POST['id_docente'])) {
    $id_docente = $_POST['id_docente'];
}

?>

<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>


<script type="text/javascript">
    let calendar;

    $(document).ready(function() {
        var id_docente = '<?php echo $id_docente; ?>';

    });

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                start: 'prev,next today', // Botones de navegación: anterior, siguiente y hoy
                center: 'title', // Muestra el título del calendario en el centro
                end: 'dayGridMonth,timeGridWeek', // Botones para cambiar de vista: mes y semana
            },
            buttonText: {
                today: 'Hoy',
                week: 'Semana',
                month: 'Mes',
            },
            locale: 'es',
            initialView: 'timeGridWeek',
            //initialDate: '2024-02-15',
            duration: {
                weeks: 1
            },
            navLinks: false,
            selectable: true,
            editable: false,
            nowIndicator: true,
            dayMaxEvents: true,
            selectable: true,
            businessHours: {
                daysOfWeek: [1, 2, 3, 4, 5, 6],
                startTime: '06:00',
                endTime: '18:00'
            },
            hiddenDays: [0, 7],
            events: [],
            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                hour12: false
            },
            slotMinTime: '05:00:00',
            slotMaxTime: '20:00:00',
            slotDuration: '00:30:00',
            slotLabelInterval: {
                hours: 0.5
            },

            eventDisplay: 'block',
            displayEventTime: true,

            eventContent: function(arg) {
                const fecha = arg.event.start;
                const startTime = arg.event.start.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const endTime = arg.event.end.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const buttonsHtml = `
                    <div class="d-flex justify-content-between align-items-center">
                        <b>${startTime}</b> - <b>${endTime}</b> ${arg.event.title}
                        <div class="btn-group">
                            <button class="btn btn-outline-white btn-sm" style="font-size: 4px;"  onclick="eliminar_evento('${arg.event.id}', '${arg.event.title}', '${startTime}', '${endTime}')"><i class='bx bx-trash-alt me-0' style="font-size: 14px;"></i></button>
                            <button class="btn btn-outline-white btn-sm" style="font-size: 4px;" onclick="crear_horarioD_igual('${arg.event.id}', '${arg.event.title}', '${startTime}', '${endTime}', '${fecha}')"><i class='bx bxs-arrow-to-right' style="font-size: 14px;"></i></button>
                        </div>
                    </div>
                `;

                return {
                    html: buttonsHtml,
                };
            },

            viewDidMount: function(view) {
                $('.fc-col-header-cell-cushion').css('text-transform', 'uppercase');
            },
            allDaySlot: false,

        });

        // Función para cargar eventos desde AJAX
        cargar_horario_disponible();

    });

    function crear_horarioD_igual(id_horarioD, titulo, hora_inicio, hora_fin, fecha) {
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Para replicar las horas de las reuniones para cuando sea de lunes a sabado
        // Obtener la fecha formateada
        var fecha_formateada = fecha_nacimiento_formateada(fecha);

        // Dividir la cadena de fecha en año, mes y día
        var [año, mes, dia] = fecha_formateada.split('-');

        // Crear un objeto Date con la fecha formateada
        var fecha_inicial = new Date(Number(año), Number(mes) - 1, Number(dia));

        // Obtener el día de la semana (0 para domingo, 1 para lunes, ..., 6 para sábado)
        var diaSemana = fecha_inicial.getDay();

        // Determinar cuántos días sumar en función del día de la semana
        var diasASumar = (diaSemana >= 1 && diaSemana <= 5) ? 1 : 2;

        // Sumar los días
        fecha_inicial.setDate(fecha_inicial.getDate() + diasASumar);

        // Obtener la nueva fecha formateada
        var nuevo_dia = fecha_inicial.getDate();
        var nuevo_mes = fecha_inicial.getMonth() + 1;
        var nuevo_año = fecha_inicial.getFullYear();

        // Asegurarse de que el día y el mes tengan dos dígitos
        nuevo_dia = (nuevo_dia < 10) ? '0' + nuevo_dia : nuevo_dia;
        nuevo_mes = (nuevo_mes < 10) ? '0' + nuevo_mes : nuevo_mes;

        // Nueva fecha formateada
        var nueva_fecha = nuevo_año + '-' + nuevo_mes + '-' + nuevo_dia;

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        var ac_docente_id = '<?php echo $id_docente; ?>';

        var ac_horarioD_inicio = hora_inicio;
        var ac_horarioD_fin = hora_fin;
        var ac_horarioD_fecha_disponible = nueva_fecha;
        var ac_horarioD_materia = 'Disponible';

        //alert(ac_horarioD_inicio + ' ' + ac_horarioD_fin);

        var parametros = {
            'ac_horarioD_id': '',
            'ac_docente_id': ac_docente_id,
            'ac_horarioD_inicio': ac_horarioD_inicio,
            'ac_horarioD_fin': ac_horarioD_fin,
            'ac_horarioD_fecha_disponible': ac_horarioD_fecha_disponible,
            'ac_horarioD_materia': ac_horarioD_materia,
        }

        console.log(parametros);

        $.ajax({
            url: '../controlador/horario_disponibleC.php?insertar=true',
            data: {
                parametros: parametros
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response)

            }
        });

        cargar_horario_disponible();

    }

    function eliminar_evento(id_horarioD, titulo, hora_inicio, hora_fin) {

        //alert(id_horarioD + ' - ' + titulo);

        Swal.fire({
            title: '¿Estás seguro de eliminar esta hora disponible ' + hora_inicio + ' -  ' + hora_fin + '?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, estoy seguro.'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controlador/horario_disponibleC.php?eliminar=true',
                    type: 'POST',
                    data: {
                        id: id_horarioD
                    },
                    success: function(response) {
                        Swal.fire('Éxito', 'La operación se realizó con éxito', 'success');
                        cargar_horario_disponible();
                    },
                    error: function() {
                        Swal.fire('Error', 'Hubo un error en la operación', 'error');
                    }
                });
            }
        });
    }

    //Fecha que empieza el horario de clases es el 2024-02-12 como lunes
    function cargar_horario_disponible() {
        id_docente = <?= $id_docente ?>;
        fecha_dia_estatico = '';

        $.ajax({
            url: '../controlador/horario_disponibleC.php?listar=true',
            type: 'get',
            data: {
                id_docente: id_docente
            },
            dataType: 'json',
            success: function(response) {
                calendar.removeAllEvents();
                // Recorrer la respuesta y agregar eventos al arreglo events
                response.forEach(function(evento) {
                    //console.log(evento);


                    calendar.addEvent({
                        id: evento.ac_horarioD_id,
                        //title: (evento.ac_horarioD_materia).toUpperCase(),
                        start: fecha_nacimiento_formateada(evento.ac_horarioD_fecha_disponible.date) + 'T' + obtener_hora_formateada(evento.ac_horarioD_inicio.date),
                        end: fecha_nacimiento_formateada(evento.ac_horarioD_fecha_disponible.date) + 'T' + obtener_hora_formateada(evento.ac_horarioD_fin.date),
                        color: 'green'

                    });

                    console.log(fecha_nacimiento_formateada(evento.ac_horarioD_fecha_creacion.date) + '-- ' + obtener_hora_formateada(evento.ac_horarioD_inicio.date));
                });
                // Renderizar el calendario después de agregar los eventos
                calendar.render();
            }
        });


        /*calendar.removeAllEvents();

        eventos.forEach(function(evento) {
            calendar.addEvent({
                title: evento.ac_horarioD_materia.toUpperCase(),
                start: formatoDate(evento.ac_horarioD_inicio.date) + 'T' + evento.ac_horarioD_inicio.time,
                end: formatoDate(evento.ac_horarioD_fin.date) + 'T' + evento.ac_horarioD_fin.time,
            });
        });

        calendar.render();*/
    }

    function agregar_clase() {
        var ac_docente_id = '<?php echo $id_docente; ?>';

        var ac_horarioD_inicio = $('#ac_horarioD_inicio').val();
        var ac_horarioD_fin = $('#ac_horarioD_fin').val();
        var ac_horarioD_fecha_disponible = $('#ac_horarioD_fecha_disponible').val();
        var ac_horarioD_materia = $('#ac_horarioD_materia').val();

        //alert(ac_horarioD_inicio + ' ' + ac_horarioD_fin);

        var parametros = {
            'ac_horarioD_id': '',
            'ac_docente_id': ac_docente_id,
            'ac_horarioD_inicio': ac_horarioD_inicio,
            'ac_horarioD_fin': ac_horarioD_fin,
            'ac_horarioD_fecha_disponible': ac_horarioD_fecha_disponible,
            'ac_horarioD_materia': ac_horarioD_materia,
        }

        console.log(parametros)

        if (ac_horarioD_inicio != '' && ac_horarioD_fin != '' && ac_horarioD_fecha_disponible != '' && ac_horarioD_materia != null) {
            $.ajax({
                url: '../controlador/horario_disponibleC.php?insertar=true',
                data: {
                    parametros: parametros
                },
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response)
                    Swal.fire('', 'Curso Asignado.', 'success').then(function() {
                        //location.href = '../vista/inicio.php?mod=7&acc=agendamiento';
                    })
                }
            });
        } else {
            Swal.fire('', 'Falta llenar los campos.', 'error');
        }




        $('#modal_horario_clases').modal('hide');
        cargar_horario_disponible(); // Volver a cargar la tabla
    }
</script>

<style>
    /* Ajusta el tamaño de las ranuras de tiempo */
    .fc-timegrid-slot,
    .fc-timegrid-slot-lane,
    .fc-timegrid-slot.fc-timegrid-slot-label,
    .fc-scrollgrid-shrink {
        height: 40px;
        /* Ajusta este valor según tus preferencias */
        line-height: 40px;
        /* Ajusta este valor según tus preferencias */
    }
</style>


<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Accesos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Horario Disponible
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

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_horario_clases"><i class="bx bx-plus"></i> Agregar Hora Disponible</button>


                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <p class="text-danger">*Para eliminar un registro, haga clic en el evento previamente asignado en el cuadro azul.</p>

                                <div class="row">
                                    <div class="col-12">
                                        <div id='calendar'></div>
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

<script src="../assets/plugins/fullcalendar/js/main.min.js"></script>
<script src="../assets/js/app-fullcalendar.js"></script>

<div class="modal" id="modal_horario_clases" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5>Agregar Hora Disponible</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="ac_horarioD_materia">Disponibilidad: <label class="text-danger">*</label></label>
                        <input type="text" name="ac_horarioD_materia" id="ac_horarioD_materia" value="Disponible" class="form-control form-control-sm" readonly>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioD_fecha_disponible">Día: <label class="text-danger">*</label></label>
                        <input type="date" name="ac_horarioD_fecha_disponible" id="ac_horarioD_fecha_disponible" class="form-control form-control-sm" min="" required>
                        <script>
                            var fechaActual = new Date().toISOString().split('T')[0];
                            document.getElementById('ac_horarioD_fecha_disponible').min = fechaActual;
                        </script>
                    </div>
                </div>

                <div class="row pt-3">

                    <div class="col-4">
                        <label for="ac_horarioD_inicio">Inicio de la Clase: <label class="text-danger">*</label></label>
                        <input type="time" name="ac_horarioD_inicio" id="ac_horarioD_inicio" class="form-control form-control-sm">
                    </div>

                    <div class="col-4">
                        <label for="ac_horarioD_fin">Fin de la Clase: <label class="text-danger">*</label></label>
                        <input type="time" name="ac_horarioD_fin" id="ac_horarioD_fin" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="agregar_clase()"><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>