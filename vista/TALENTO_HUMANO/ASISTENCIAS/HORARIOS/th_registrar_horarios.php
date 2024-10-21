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

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            datos_col(<?= $_id ?>);
        <?php } ?>

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
                $('#ddl_modelo').val(response[0].modelo);
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_host').val(response[0].host);
                $('#txt_puerto').val(response[0].port);
                $('#txt_serial').val(response[0].serial);
                $('#txt_usuario').val(response[0].usuario);
                $('#txt_pass').val(response[0].pass);
                $('#cbx_ssl').prop('checked', (response[0].ssl == 1));

            }
        });
    }

    function editar_insertar() {
        var ddl_modelo = $('#ddl_modelo').val();
        var txt_nombre = $('#txt_nombre').val();
        var txt_host = $('#txt_host').val();
        var txt_puerto = $('#txt_puerto').val();
        var txt_serial = $('#txt_serial').val();
        var txt_usuario = $('#txt_usuario').val();
        var txt_pass = $('#txt_pass').val();
        var cbx_ssl = $('#cbx_ssl').prop('checked') ? 1 : 0;

        var parametros = {
            '_id': '<?= $_id ?>',
            'ddl_modelo': ddl_modelo,
            'txt_nombre': txt_nombre,
            'txt_host': txt_host,
            'txt_puerto': txt_puerto,
            'txt_serial': txt_serial,
            'txt_usuario': txt_usuario,
            'txt_pass': txt_pass,
            'cbx_ssl': cbx_ssl,
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
</script>

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

    /* Estilo para los eventos arrastrables */
    .external-event {
        margin: 10px 0;
        padding: 8px;
        background-color: #3788d8;
        color: white;
        cursor: pointer;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        // Hacer los eventos externos arrastrables
        var externalEvents = document.querySelectorAll('.external-event');
        externalEvents.forEach(function(eventEl) {
            // Extraer datos dinámicos de los atributos data- del elemento
            var eventTitle = eventEl.getAttribute('data-title');
            var eventStart = eventEl.getAttribute('data-start');
            var eventEnd = eventEl.getAttribute('data-end');

            new FullCalendar.Draggable(eventEl, {
                eventData: {
                    title: eventTitle, // Título dinámico
                    extendedProps: {
                        startHour: eventStart, // Hora de inicio dinámica
                        endHour: eventEnd // Hora de fin dinámica
                    }
                }
            });
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
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
                var startHour = info.draggedEl.getAttribute('data-start');
                var endHour = info.draggedEl.getAttribute('data-end');
                var startDate = info.event.start;

                // Aplicar horas dinámicas
                var startTimeArray = startHour.split(':');
                var endTimeArray = endHour.split(':');

                // Establecer la hora de inicio y fin según los valores dinámicos
                info.event.setStart(new Date(startDate.setHours(startTimeArray[0], startTimeArray[1], 0)));
                info.event.setEnd(new Date(startDate.setHours(endTimeArray[0], endTimeArray[1], 0)));

                //alert('Nuevo evento añadido: ' + info.event.title + ' con horario ' + startHour + ' a ' + endHour);
            }

        });

        calendar.render();
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
                                
                                <div class="col-ms-6">
                                    <label for="txt_nombre" class="form-label">Nombre </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="50">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>

                                <div class="col-ms-6">
                                    <label for="ddl_modelo" class="form-label">Tipo </label>
                                    <select class="form-select form-select-sm" id="ddl_modelo" name="ddl_modelo">
                                        <option value="1">Diario</option>
                                        <option selected value="7">Semanal</option>
                                        <option value="31">Mensual</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row">
                                <b>Turnos</b>

                                <div class="row mb-col">
                                    <div class="col-ms-12">
                                        <div id="external-events">
                                            <div class="external-event" data-title="Evento A" data-start="07:00:00" data-end="15:30:00">Evento A</div>
                                            <div class="external-event" data-title="Evento B" data-start="08:00:00" data-end="16:00:00">Evento B</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-9 col-12">
                                    <div class="row">
                                        <div class="col-ms-12">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row pt-3">
                                <div class="d-flex justify-content-start pt-2">

                                    <?php if ($_id == '') { ?>
                                        <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                    <?php } else { ?>
                                        <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Editar</button>
                                        <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
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
                ddl_modelo: {
                    required: true,
                },
                txt_nombre: {
                    required: true,
                },
                txt_host: {

                },
                txt_puerto: {
                    digits: true,
                    maxlength: 4,
                    minlength: 1
                },
            },
            messages: {
                ddl_modelo: {
                    required: "El campo 'Modelo' es obligatorio",
                },
                txt_nombre: {
                    required: "El campo 'Nombre' es obligatorio",
                },
                txt_host: {
                    required: "El campo 'IP/Host' es obligatorio",
                },
                txt_puerto: {
                    digits: "El campo 'Puerto' permite solo números",
                }
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