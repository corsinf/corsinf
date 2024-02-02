<?php

$id_docente = '2';

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
            slotMinTime: '06:00:00', // Hora de inicio
            slotMaxTime: '18:00:00', // Hora de finalización
            dayHeaderFormat: {
                weekday: 'long' // Muestra solo el nombre del día
            },
        });



        // Función para cargar eventos desde AJAX
        cargar_horario_clases();

    });

    //Fecha que empieza el horario de clases es el 2024-02-12 como lunes
    function cargar_horario_clases() {
        /* var eventos = [{
                 ac_horarioC_inicio: {
                     date: '2024-02-13',
                     time: '06:00'
                 },
                 ac_horarioC_fin: {
                     date: '2024-02-13',
                     time: '07:45'
                 },

                 ac_horarioC_dia: 'lunes',
                 ac_horarioC_materia: 'Matematicas',
             },
             {
                 ac_horarioC_inicio: {
                     date: '2024-02-14',
                     time: '08:00:00'
                 },
                 ac_horarioC_fin: {
                     date: '2024-02-14',
                     time: '10:00:00'
                 },

                 ac_horarioC_dia: 'lunes',
                 ac_horarioC_materia: 'Matematicas',
             },
             {
                 ac_horarioC_inicio: {
                     date: '2024-02-15',
                     time: '08:00:00'
                 },
                 ac_horarioC_fin: {
                     date: '2024-02-15',
                     time: '10:00:00'
                 },

                 ac_horarioC_dia: 'lunes',
                 ac_horarioC_materia: 'Estudios Sociales',
             },
             {
                 ac_horarioC_inicio: {
                     date: '2024-02-16',
                     time: '08:00:00'
                 },
                 ac_horarioC_fin: {
                     date: '2024-02-16',
                     time: '10:00:00'
                 },

                 ac_horarioC_dia: 'lunes',
                 ac_horarioC_materia: 'Estudios Sociales',
             },
             {
                 ac_horarioC_inicio: {
                     date: '2024-02-17',
                     time: '10:00:00'
                 },
                 ac_horarioC_fin: {
                     date: '2024-02-17',
                     time: '12:00:00'
                 },

                 ac_horarioC_dia: 'lunes',
                 ac_horarioC_materia: 'Estudios Sociales',
             },
             // Agrega más eventos según sea necesario
         ];*/

        //console.log(eventos)


        $.ajax({
            url: '../controlador/horario_clasesC.php?listar_todo=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                calendar.removeAllEvents();
                // Recorrer la respuesta y agregar eventos al arreglo events
                response.forEach(function(evento) {
                    //console.log(evento);
                    calendar.addEvent({
                        title: (evento.ac_horarioC_materia).toUpperCase(),
                        start: fecha_nacimiento_formateada(evento.ac_horarioC_fecha_creacion.date) + 'T' + obtener_hora_formateada(evento.ac_horarioC_inicio.date),
                        end: fecha_nacimiento_formateada(evento.ac_horarioC_fecha_creacion.date) + 'T' + obtener_hora_formateada(evento.ac_horarioC_fin.date),
                    });

                    console.log(fecha_nacimiento_formateada(evento.ac_horarioC_fecha_creacion.date) + '-- ' + obtener_hora_formateada(evento.ac_horarioC_inicio.date));
                });

                // Renderizar el calendario después de agregar los eventos
                calendar.render();


            }
        });


        /*calendar.removeAllEvents();

        eventos.forEach(function(evento) {
            calendar.addEvent({
                title: evento.ac_horarioC_materia.toUpperCase(),
                start: formatoDate(evento.ac_horarioC_inicio.date) + 'T' + evento.ac_horarioC_inicio.time,
                end: formatoDate(evento.ac_horarioC_fin.date) + 'T' + evento.ac_horarioC_fin.time,
            });
        });

        calendar.render();*/
    }

    function agregar_clase() {
        var ac_docente_id = '<?php echo $id_docente; ?>';

        var ac_horarioC_inicio = $('#ac_horarioC_inicio').val();
        var ac_horarioC_fin = $('#ac_horarioC_fin').val();
        var ac_horarioC_dia = $('#ac_horarioC_dia').val();
        var ac_horarioC_materia = $('#ac_horarioC_materia').val();

        //alert(ac_par_id + ' ' + ac_doc_id);

        var parametros = {
            'ac_horarioC_id': '',
            'ac_docente_id': ac_docente_id,
            'ac_horarioC_inicio': ac_horarioC_inicio,
            'ac_horarioC_fin': ac_horarioC_fin,
            'ac_horarioC_dia': ac_horarioC_dia,
            'ac_horarioC_materia': ac_horarioC_materia,
        }

        //console.log(parametros)

        if (ac_horarioC_inicio || ac_horarioC_fin || ac_horarioC_dia == null || ac_horarioC_materia == null) {
            $.ajax({
                url: '../controlador/horario_clasesC.php?insertar=true',
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




        $('#modal_paralelo').modal('hide');
        cargar_horario_clases(); // Volver a cargar la tabla
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
                            Horario de Clases
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

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_horario_clases"><i class="bx bx-plus"></i> Agregar Asignatura</button>



                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-10">
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
                <h5>Agregar Asignatura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Materia: <label class="text-danger">*</label></label>
                        <select name="ac_horarioC_materia" id="ac_horarioC_materia" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione una Materia --</option>
                            <option value="matematicas">Matemáticas</option>
                            <option value="lengua">Lengua y Literatura</option>
                            <option value="ciencias">Ciencias Naturales</option>
                            <option value="historia">Historia</option>
                            <option value="geografia">Geografía</option>
                            <option value="educacion-fisica">Educación Física</option>
                            <option value="arte">Arte</option>
                            <option value="musica">Música</option>
                            <option value="tecnologia">Tecnología</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioC_dia">Día: <label class="text-danger">*</label></label>
                        <select name="ac_horarioC_dia" id="ac_horarioC_dia" class="form-select form-select-sm">
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
                        <label for="ac_horarioC_inicio">Inicio de la Clase: <label class="text-danger">*</label></label>
                        <input type="time" name="ac_horarioC_inicio" id="ac_horarioC_inicio" class="form-control form-control-sm">
                    </div>

                    <div class="col-4">
                        <label for="ac_horarioC_fin">Fin de la Clase: <label class="text-danger">*</label></label>
                        <input type="time" name="ac_horarioC_fin" id="ac_horarioC_fin" class="form-control form-control-sm">
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