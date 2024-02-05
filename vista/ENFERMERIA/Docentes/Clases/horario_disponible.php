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
                start: '', // Oculta los botones de navegación
                center: '', // Oculta el título
                end: '' // Oculta el botón de cambio de vista
            },
            locale: 'es',
            initialView: 'timeGridWeek',
            initialDate: '2024-02-15', // Fecha de inicio de la semana deseada
            duration: {
                weeks: 1
            }, // Duración de una semana
            navLinks: false, // Desactiva los enlaces de navegación
            selectable: true,
            editable: false,
            nowIndicator: true,
            dayMaxEvents: true,
            selectable: true,
            businessHours: {
                daysOfWeek: [1, 2, 3, 4, 5, 6], // Lunes (1) a Sábado (6)
                startTime: '06:00', // Hora de inicio
                endTime: '18:00' // Hora de finalización
            },
            hiddenDays: [0, 7], // Domingo (0) y Domingo (7) estarán ocultos
            events: [],
            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                hour12: false
            },
            slotMinTime: '00:00:00', // Hora de inicio
            slotMaxTime: '24:00:00', // Hora de finalización

            dayHeaderFormat: {
                weekday: 'long', // Muestra solo el nombre del día

            },
            viewDidMount: function(view) {
                // Modifica los encabezados de día para que estén en mayúsculas
                $('.fc-col-header-cell-cushion').css('text-transform', 'uppercase');
            },

            allDaySlot: false,
            
            eventClick: function(info) {
                // Obtener información del evento
                var id_horario_clases = info.event.id;
                var title = info.event.title;

                //alert(id_horario_clases)

                Swal.fire({
                    title: '¿Estás seguro de eliminar esta asignatura ' + title + '?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, estoy seguro.'
                }).then((result) => {
                    // Si el usuario hace clic en "Sí"
                    if (result.isConfirmed) {
                        // Ejecuta la solicitud AJAX
                        $.ajax({
                            url: '../controlador/horario_disponibleC.php?eliminar=true',
                            type: 'POST',
                            data: {
                                id: id_horario_clases
                            },
                            success: function(response) {
                                // Maneja la respuesta exitosa
                                Swal.fire('Éxito', 'La operación se realizó con éxito', 'success');
                                cargar_horario_disponible();
                            },
                            error: function() {
                                // Maneja el error
                                Swal.fire('Error', 'Hubo un error en la operación', 'error');
                            }
                        });
                    }
                });

                info.jsEvent.preventDefault();

            }
        });



        // Función para cargar eventos desde AJAX
        cargar_horario_disponible();

    });

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
                    if (evento.ac_horarioD_dia == 'lunes') {
                        fecha_dia_estatico = '2024-02-12';
                    } else if (evento.ac_horarioD_dia == 'martes') {
                        fecha_dia_estatico = '2024-02-13';
                    } else if (evento.ac_horarioD_dia == 'miercoles') {
                        fecha_dia_estatico = '2024-02-14';
                    } else if (evento.ac_horarioD_dia == 'jueves') {
                        fecha_dia_estatico = '2024-02-15';
                    } else if (evento.ac_horarioD_dia == 'viernes') {
                        fecha_dia_estatico = '2024-02-16';
                    } else if (evento.ac_horarioD_dia == 'sabado') {
                        fecha_dia_estatico = '2024-02-17';
                    }

                    calendar.addEvent({
                        id: evento.ac_horarioD_id,
                        title: (evento.ac_horarioD_materia).toUpperCase(),
                        start: fecha_dia_estatico + 'T' + obtener_hora_formateada(evento.ac_horarioD_inicio.date),
                        end: fecha_dia_estatico + 'T' + obtener_hora_formateada(evento.ac_horarioD_fin.date),

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
        var ac_horarioD_dia = $('#ac_horarioD_dia').val();
        var ac_horarioD_materia = $('#ac_horarioD_materia').val();

        //alert(ac_horarioD_inicio + ' ' + ac_horarioD_fin);

        var parametros = {
            'ac_horarioD_id': '',
            'ac_docente_id': ac_docente_id,
            'ac_horarioD_inicio': ac_horarioD_inicio,
            'ac_horarioD_fin': ac_horarioD_fin,
            'ac_horarioD_dia': ac_horarioD_dia,
            'ac_horarioD_materia': ac_horarioD_materia,
        }

        //console.log(parametros)

        if (ac_horarioD_inicio != '' && ac_horarioD_fin != '' && ac_horarioD_dia != null && ac_horarioD_materia != null) {
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
                        <label for="ac_horarioD_dia">Día: <label class="text-danger">*</label></label>
                        <select name="ac_horarioD_dia" id="ac_horarioD_dia" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione un Día --</option>
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miercoles">Miércoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                        </select>
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