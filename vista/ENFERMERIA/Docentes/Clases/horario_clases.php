<?php

$id = $_SESSION['INICIO']['NO_CONCURENTE'];

if ($id != null && $id != '') {
    $id_docente = $id;
}

?>

<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>


<script type="text/javascript">
    let calendar;

    $(document).ready(function() {
        var id_docente = '<?php echo $id_docente; ?>';

        cargar_clases();
        cargar_docente_paralelo();

        $('#ac_horarioC_inicio').blur(function() {
            horaDesde = $(this).val();
            $('#ac_horarioC_fin').val('--:--')
        });

        $('#ac_horarioC_fin').blur(function() {
            horaHasta = $(this).val();
            calcular_diferencia_hora();
        });

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
            slotMinTime: '07:00:00', // Hora de inicio
            slotMaxTime: '15:00:00', // Hora de finalización
            slotDuration: '00:30:00',
            slotLabelInterval: {
                hours: 0.1
            },

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
                            url: '../controlador/SALUD_INTEGRAL/horario_clasesC.php?eliminar=true',
                            type: 'POST',
                            data: {
                                id: id_horario_clases
                            },
                            success: function(response) {
                                // Maneja la respuesta exitosa
                                Swal.fire('Éxito', 'La operación se realizó con éxito', 'success');
                                cargar_horario_clases();
                            },
                            error: function() {
                                // Maneja el error
                                Swal.fire('Error', 'Hubo un error en la operación', 'error');
                            }
                        });
                    }
                });

                info.jsEvent.preventDefault();

            },

            eventMouseEnter: function(info) {
                // Al pasar el ratón, muestra la información completa del evento
                var tooltip = document.createElement('div');
                tooltip.className = 'custom-tooltip';
                tooltip.innerHTML = '<strong>' + info.event.title + info.event.extendedProps.descripcion + '</strong><br>'

                document.body.appendChild(tooltip);
                $(tooltip).css({
                    top: info.el.getBoundingClientRect().top + window.scrollY - tooltip.offsetHeight - 10 + 'px',
                    left: info.el.getBoundingClientRect().left + window.scrollX + 'px',
                });
            },
            eventMouseLeave: function() {
                // Al salir del ratón, oculta la información completa del evento
                $('.custom-tooltip').remove();
            },
        });



        // Función para cargar eventos desde AJAX
        cargar_horario_clases();

    });

    //Fecha que empieza el horario de clases es el 2024-02-12 como lunes
    function cargar_horario_clases() {
        id_docente = <?= $id_docente ?>;
        fecha_dia_estatico = '';

        id_paralelo = $('#ac_paralelo_id_busqueda').val();

        $.ajax({
            url: '../controlador/SALUD_INTEGRAL/horario_clasesC.php?listar=true',
            type: 'get',
            data: {
                id_docente: id_docente,
                id_paralelo: id_paralelo
            },
            dataType: 'json',
            success: function(response) {
                calendar.removeAllEvents();
                // Recorrer la respuesta y agregar eventos al arreglo events
                response.forEach(function(evento) {
                    //console.log(evento);
                    if (evento.ac_horarioC_dia == 'lunes') {
                        fecha_dia_estatico = '2024-02-12';
                    } else if (evento.ac_horarioC_dia == 'martes') {
                        fecha_dia_estatico = '2024-02-13';
                    } else if (evento.ac_horarioC_dia == 'miercoles') {
                        fecha_dia_estatico = '2024-02-14';
                    } else if (evento.ac_horarioC_dia == 'jueves') {
                        fecha_dia_estatico = '2024-02-15';
                    } else if (evento.ac_horarioC_dia == 'viernes') {
                        fecha_dia_estatico = '2024-02-16';
                    } else if (evento.ac_horarioC_dia == 'sabado') {
                        fecha_dia_estatico = '2024-02-17';
                    }

                    calendar.addEvent({
                        id: evento.ac_horarioC_id,
                        title: (evento.ac_horarioC_materia).toUpperCase(),
                        start: fecha_dia_estatico + 'T' + obtener_hora_formateada(evento.ac_horarioC_inicio),
                        end: fecha_dia_estatico + 'T' + obtener_hora_formateada(evento.ac_horarioC_fin),
                        extendedProps: {
                            descripcion: ' (' + evento.sa_sec_nombre + ' / ' + evento.sa_gra_nombre + ' / ' + evento.sa_par_nombre + ')',
                        },

                    });

                    //console.log(fecha_nacimiento_formateada(evento.ac_horarioC_fecha_creacion) + '-- ' + obtener_hora_formateada(evento.ac_horarioC_inicio));
                });
                // Renderizar el calendario después de agregar los eventos
                calendar.render();
            }
        });

    }

    function agregar_clase() {

        var ac_docente_id = '<?php echo $id_docente; ?>';

        var ac_paralelo_id = $('#ac_paralelo_id').val();

        var ac_horarioC_inicio = $('#ac_horarioC_inicio').val();
        var ac_horarioC_fin = $('#ac_horarioC_fin').val();
        var ac_horarioC_dia = $('#ac_horarioC_dia').val();
        var ac_horarioC_materia = $('#ac_horarioC_materia').val();

        var parametros = {
            'ac_horarioC_id': '',
            'ac_docente_id': ac_docente_id,
            'ac_paralelo_id': ac_paralelo_id,
            'ac_horarioC_inicio': ac_horarioC_inicio,
            'ac_horarioC_fin': ac_horarioC_fin,
            'ac_horarioC_dia': ac_horarioC_dia,
            'ac_horarioC_materia': ac_horarioC_materia,
        }

        if (ac_horarioC_inicio != '' && ac_horarioC_fin != '' && ac_horarioC_dia != null && ac_horarioC_materia != null && ac_paralelo_id != null) {

            // Obtener las clases existentes del calendario
            var eventos = calendar.getEvents();

            // Convertir las horas de inicio y fin de la nueva clase a objetos Date
            var nueva_inicio;
            var nueva_fin;

            //Día seleccionado a la fecha correspondiente
            var fecha_dia;
            switch (ac_horarioC_dia) {
                case 'lunes':
                    fecha_dia = '2024-02-12'; // Lunes
                    break;
                case 'martes':
                    fecha_dia = '2024-02-13'; // Martes
                    break;
                case 'miercoles':
                    fecha_dia = '2024-02-14'; // Miércoles
                    break;
                case 'jueves':
                    fecha_dia = '2024-02-15'; // Jueves
                    break;
                case 'viernes':
                    fecha_dia = '2024-02-16'; // Viernes
                    break;
                case 'sabado':
                    fecha_dia = '2024-02-17'; // Sábado
                    break;
                default:
                    //Swal.fire('', 'Día de la semana no válido.', 'error');
                    return;
            }

            // Crear la fecha completa combinando la fecha del día seleccionado y la hora ingresada
            nueva_inicio = new Date(fecha_dia + 'T' + ac_horarioC_inicio);
            nueva_fin = new Date(fecha_dia + 'T' + ac_horarioC_fin);

            // Validar que la hora de finalización sea mayor que la hora de inicio
            if (nueva_inicio >= nueva_fin) {
                Swal.fire('', 'La hora de finalización debe ser mayor que la hora de inicio.', 'error');
            } else {

                // Verificar si la nueva clase se superpone con alguna clase existente
                var seSuperpone = eventos.some(function(evento) {
                    var inicio_evento = new Date(evento.start);
                    var fin_evento = new Date(evento.end);

                    // Comprobar si la nueva clase comienza después de que el evento existente haya terminado
                    var superpone_Inicio = nueva_inicio >= fin_evento;

                    // Comprobar si la nueva clase termina antes de que el evento existente comience
                    var superpone_fin = nueva_fin <= inicio_evento;

                    // La nueva clase se superpone si ambas condiciones son falsas
                    return !(superpone_Inicio || superpone_fin);
                });

                // Mostrar mensaje si la nueva clase se superpone
                if (seSuperpone) {
                    Swal.fire('', 'No se puede agregar la clase porque se superpone con otra clase existente.', 'error');
                } else {

                    $.ajax({
                        url: '../controlador/SALUD_INTEGRAL/horario_clasesC.php?insertar=true',
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


                    $('#modal_horario_clases').modal('hide');
                    cargar_horario_clases(); // Volver a cargar la tabla
                }
            }

        } else {
            var errores = [];

            if (ac_horarioC_materia == null) {
                errores.push('Falta seleccionar la materia.');
            }
            if (ac_horarioC_dia == null) {
                errores.push('Falta seleccionar el día de la semana.');
            }
            if (ac_paralelo_id == null) {
                errores.push('Falta seleccionar una clase.');
            }
            if (ac_horarioC_inicio == '') {
                errores.push('Falta seleccionar la hora de inicio.');
            }
            if (ac_horarioC_fin == '') {
                errores.push('Falta seleccionar la hora de finalización.');
            }

            // Verificar si hay errores y mostrarlos en una lista
            if (errores.length > 0) {
                var mensajeError = '';
                errores.forEach(function(error) {
                    mensajeError += '<br>' + error + '</br>';
                });
                mensajeError += '';

                Swal.fire('', mensajeError, 'error');
                return; // Detener la ejecución si hay errores
            }
        }
    }

    function cargar_clases() {
        var ac_docente_id = '<?php echo $id_docente; ?>';
        var select = '';

        select = '<option selected disabled>-- Seleccione --</option>'
        $.ajax({
            data: {
                id_docente: ac_docente_id
            },
            url: '../controlador/SALUD_INTEGRAL/docente_paraleloC.php?listar=true',
            type: 'get',
            dataType: 'json',

            success: function(response) {
                //console.log(response);
                $.each(response, function(i, item) {
                    //console.log(item);
                    select += '<option value="' + item.sa_par_id + '">' + item.sa_sec_nombre + ' / ' + item.sa_gra_nombre + ' / ' + item.sa_par_nombre + '</option>';
                });

                $('#ac_paralelo_id').html(select);
            }
        });
    }

    function cargar_docente_paralelo() {

        var ac_docente_id = '<?php echo $id_docente; ?>';
        var select = '<option value="">Todos</option>';

        $.ajax({
            url: '../controlador/SALUD_INTEGRAL/docente_paraleloC.php?listar=true',
            data: {
                id_docente: ac_docente_id
            },
            type: 'get',
            dataType: 'json',
            success: function(response) {
                //console.log(response)

                $.each(response, function(i, item) {
                    //console.log(item);
                    select += '<option value="' + item.sa_par_id + '">' + item.sa_sec_nombre + ' / ' + item.sa_gra_nombre + ' / ' + item.sa_par_nombre + '</option>';
                });

                $('#ac_paralelo_id_busqueda').html(select);

            }
        });
    }

    function cargar_solo_paralelos_seleccionados() {
        id_paralelo = $('#ac_paralelo_id_busqueda').val();
        //alert(id_paralelo)
        cargar_horario_clases();
    }
</script>

<style>
    .custom-tooltip {
        position: absolute;
        background-color: white;
        border: 1px solid #ccc;
        padding: 5px;
        z-index: 1000;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Ajusta el tamaño de las ranuras de tiempo */
    .fc-timegrid-slot,
    .fc-timegrid-slot-lane,
    .fc-timegrid-slot.fc-timegrid-slot-label,
    .fc-scrollgrid-shrink {
        height: 30px;
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
                                <p class="text-danger">*Para eliminar un registro, haga clic en el evento previamente asignado con una materia en el cuadro azul.</p>

                                <div class="row pt-3">
                                    <div class="col-6">
                                        <label for="ac_horarioD_fecha_disponible">Curso <label class="text-danger">*</label></label>
                                        <select name="ac_paralelo_id_busqueda" id="ac_paralelo_id_busqueda" class="form-select form-select-sm" onchange="cargar_solo_paralelos_seleccionados();">
                                        </select>
                                    </div>
                                </div>

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
                <h5>Agregar Asignatura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Materia <label class="text-danger">*</label></label>
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
                        <label for="ac_horarioC_dia">Día <label class="text-danger">*</label></label>
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
                    <div class="col-12">
                        <label for="ac_horarioC_dia">Clase <label class="text-danger">*</label></label>
                        <select name="ac_paralelo_id" id="ac_paralelo_id" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione una Clase --</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">

                    <div class="col-4">
                        <label for="ac_horarioC_inicio">Inicio de la Clase <label class="text-danger">*</label></label>
                        <input type="time" name="ac_horarioC_inicio" id="ac_horarioC_inicio" class="form-control form-control-sm" min="09:00" max="18:00">
                    </div>

                    <div class="col-4">
                        <label for="ac_horarioC_fin">Fin de la Clase <label class="text-danger">*</label></label>
                        <input type="time" name="ac_horarioC_fin" id="ac_horarioC_fin" class="form-control form-control-sm" min="09:00" max="18:00">
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="agregar_clase();"><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function calcular_diferencia_hora() {
        var horaDesde = $('#ac_horarioC_inicio').val();
        var horaHasta = $('#ac_horarioC_fin').val();

        var fechaBase = new Date('2000-01-01');
        var fechaDesde = new Date(fechaBase.toDateString() + ' ' + horaDesde);
        var fechaHasta = new Date(fechaBase.toDateString() + ' ' + horaHasta);

        var diferenciaEnMs = fechaHasta - fechaDesde;

        if (diferenciaEnMs >= 0) {
            var diferenciaEnMinutos = Math.floor(diferenciaEnMs / (1000 * 60));

            if (diferenciaEnMinutos >= 15) {
                //alert(diferenciaEnMinutos);
            } else {
                Swal.fire('', 'La diferencia de tiempo debe ser mayor o igual a 15 minutos', 'info');
                $('#ac_horarioC_fin').val('--:--');
            }
        } else {
            Swal.fire('', 'La hora final de la clase no puede ser menor', 'info');
            $('#ac_horarioC_fin').val('--:--');
        }
    }
</script>

<script>
    $(document).ready(function() {
        validarHoraInicioFin('ac_horarioC_inicio');
        validarHoraInicioFin('ac_horarioC_fin');
    });

    function validarHoraInicioFin(datos) {
        $('#' + datos).on("change", function() {
            // Obtener la hora seleccionada
            var horaSeleccionada = $(this).val();

            // Extraer las horas de la fecha de inicio y fin
            var horaInicio = 7; // 7:00 AM
            var horaFin = 15; // 3:00 PM

            // Obtener la hora seleccionada (convertirla a número)
            var horaSeleccionadaNum = parseInt(horaSeleccionada.split(':')[0]);

            // Verificar si la hora seleccionada está dentro del rango permitido
            if (horaSeleccionadaNum < horaInicio || horaSeleccionadaNum > horaFin) {
                // Restablecer la hora seleccionada a un valor vacío
                $(this).val("");
                // Mostrar un mensaje de error
                Swal.fire('', 'Por favor, selecciona una hora entre las 07:00 y las 15:00.', 'info');
            }
        });
    }
</script>