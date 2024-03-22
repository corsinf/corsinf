<?php

$id = $_SESSION['INICIO']['NO_CONCURENTE'];

if ($id != null && $id != '') {
    $id_representante = $id;
}


?>

<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    let calendar;

    $(document).ready(function() {
        var id_representante = '<?php echo $id_representante; ?>';

        consultar_datos_estudiante_representante(id_representante);

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
                    <div class="">
                        <b>${startTime}</b> - <b>${endTime}</b> ${arg.event.title}
                        
                    </div>
                `;

                return {
                    html: buttonsHtml,
                };
            },

            eventClick: function(info) {
                var ac_horarioD_id = info.event.id;

                var ac_horarioD_estado = info.event.extendedProps.ac_horarioD_estado;
                var url = info.event.url;

                if (ac_horarioD_estado == 1) {
                    // Obtener el botón que abre el modal
                    var btnAbrirModal = document.getElementById('btn_modal_agendar_reunion');

                    // Si el botón existe, abrir el modal
                    if (btnAbrirModal) {
                        var modal = new bootstrap.Modal(document.getElementById('modal_agendar_reunion'));
                        $('#ac_horarioD_id').val(ac_horarioD_id);
                        modal.show();
                    }
                } else {
                    Swal.fire('', 'Turno ocupado.', 'error');
                }



                info.jsEvent.preventDefault();
            },
            allDaySlot: false,

        });

        // Función para cargar eventos desde AJAX
        cargar_horario_disponible_docente();
    });

    //Fecha que empieza el horario de clases es el 2024-02-12 como lunes
    function cargar_horario_disponible_docente() {

        id_docente = $('#ac_docente_id_hidden').val();
        //alert(id_docente)

        if (id_docente != '') {

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

                        var color = (evento.ac_horarioD_estado == 0) ? '#B63232' : '#3D94C9';

                        calendar.addEvent({
                            id: evento.ac_horarioD_id,
                            //title: (evento.ac_horarioD_materia).toUpperCase(),
                            start: (evento.ac_horarioD_fecha_disponible) + 'T' + obtener_hora_formateada(evento.ac_horarioD_inicio),
                            end: (evento.ac_horarioD_fecha_disponible) + 'T' + obtener_hora_formateada(evento.ac_horarioD_fin),
                            color: color,
                            url: '../vista/inicio.php?mod=7&acc=pacientes',
                            extendedProps: {
                                ac_horarioD_estado: evento.ac_horarioD_estado,
                            },

                        });

                        //console.log(fecha_nacimiento_formateada(evento.ac_horarioD_fecha_creacion) + '-- ' + obtener_hora_formateada(evento.ac_horarioD_inicio));
                    });
                    // Renderizar el calendario después de agregar los eventos
                    calendar.render();
                }
            });
        }
    }

    function buscar_agenda() {

        var ac_docente_id = $('#ac_docente_id').val();
        var sa_est_id = $('#sa_est_id').val();

        $('#ac_docente_id_hidden').val(ac_docente_id);

        cargar_datos_docente(ac_docente_id);

        if (ac_docente_id != null && sa_est_id != null) {

        } else {
            Swal.fire('', 'Falta llenar los campos.', 'error');
        }

        $('#modal_buscar_horario_disponible').modal('hide');
        cargar_horario_disponible_docente(); // Volver a cargar la tabla
    }

    //Para buscar el docente en base al paralelo en el que este el estudiante 
    function consultar_datos_docente_paralelo(id_paralelo = '') {
        //alert(id_paralelo);
        var select = '';
        select = '<option selected disabled>-- Seleccione un Docente --</option>'

        $.ajax({
            data: {
                id_paralelo: id_paralelo
            },
            url: '../controlador/docente_paraleloC.php?listar_estudiante_docente_paralelo=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                //console.log(response);
                $.each(response, function(i, item) {
                    //console.log(item);
                    select += '<option value="' + item.ac_docente_id + '">' + item.docente_nombres + ' / ' + item.sec_gra_par + '</option>';
                });

                $('#ac_docente_id').html(select);
            }
        });
    }

    function consultar_datos_estudiante_representante1(id_representante = '') {
        var estudiantes = '<option value="">-- Seleccione un Estudiante--</option>';

        $.ajax({
            data: {
                id_representante: id_representante,
            },
            url: '../controlador/estudiantesC.php?listar_estudiante_representante=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                $.each(response, function(i, item) {

                    nombres = item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre;

                    nombre_corto = response[0].sa_est_primer_apellido + ' ' + response[0].sa_est_primer_nombre;

                    estudiantes += '<option value="' + item.sa_par_id + '">' + nombres + '</option>';
                });

                $('#sa_est_id').html(estudiantes);
            }
        });
    }


    function consultar_datos_estudiante_representante(id_representante = '') {
        $('#sa_est_id').select2({
            placeholder: 'Selecciona una opción',
            dropdownParent: $('#modal_buscar_horario_disponible'),
            language: {
                inputTooShort: function() {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                },
                errorLoading: function() {
                    return "No se encontraron resultados";
                }
            },
            ajax: {
                url: '../controlador/estudiantesC.php?listar_estudiante_representante_get=true',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        id_representante: id_representante,
                    };
                },
                processResults: function(data, params) {
                    var options = data.map(function(item) {
                        var fullName = item['sa_est_primer_apellido'] + ' ' + item['sa_est_segundo_apellido'] + ' ' + item['sa_est_primer_nombre'] + ' ' + item['sa_est_segundo_nombre'];
                        var nombre_corto = item['sa_est_primer_apellido'] + ' ' + item['sa_est_primer_nombre'];
                        var sa_est_id = item['sa_est_id'];

                        return {
                            id: item['sa_par_id'],
                            text: fullName,
                            nombre_corto: nombre_corto,
                            sa_est_id: sa_est_id,
                        };
                    });

                    return {
                        results: options
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var nombre_corto = e.params.data.nombre_corto;
            var sa_est_id = e.params.data.sa_est_id;

            $('#ac_estudiante_id').val(sa_est_id);
            $('#ac_nombre_est').val(nombre_corto);
        });
    }

    function cargar_datos_docente(id_docente) {
        //alert(id_docente)
        $.ajax({
            data: {
                id: id_docente,
            },
            url: '../controlador/docentesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response);

                nombres = response[0].sa_doc_primer_apellido + ' ' + response[0].sa_doc_segundo_apellido + ' ' + response[0].sa_doc_primer_nombre + ' ' + response[0].sa_doc_segundo_nombre;

                salida = "<b><p class='text-success'>La agenda del docente " + nombres + " es la siguiente.</p></b>";

                $('#lbl_docente').html(salida);
            }
        });
    }

    function agengar_reunion() {
        var ac_horarioD_id = $('#ac_horarioD_id').val();
        var ac_representante_id = '<?php echo $id_representante; ?>';
        var ac_reunion_motivo = $('#ac_reunion_motivo').val();
        var ac_reunion_observacion = $('#ac_reunion_observacion').val();

        var ac_estudiante_id = $('#ac_estudiante_id').val();
        var ac_nombre_est = $('#ac_nombre_est').val();

        //alert(ac_horarioD_inicio + ' ' + ac_horarioD_fin);

        var parametros = {
            'ac_reunion_id': '',
            'ac_horarioD_id': ac_horarioD_id,
            'ac_representante_id': ac_representante_id,
            'ac_reunion_motivo': ac_reunion_motivo,
            'ac_reunion_observacion': ac_reunion_observacion,
            'ac_estudiante_id': ac_estudiante_id,
            'ac_nombre_est': ac_nombre_est,
        }

        //console.log(parametros);

        if (ac_horarioD_id != '' && ac_representante_id != '' && ac_reunion_motivo != '') {
            $.ajax({
                url: '../controlador/reunionesC.php?insertar=true',
                data: {
                    parametros: parametros
                },
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response)
                    Swal.fire('', 'Turno Registrado.', 'success').then(function() {
                        //location.href = '../vista/inicio.php?mod=7&acc=agendamiento';
                    })
                }
            });

        } else {
            Swal.fire('', 'Falta llenar los campos.', 'error');
        }

        $('#modal_agendar_reunion').modal('hide');
        cargar_horario_disponible_docente();
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

<input type="hidden" name="ac_docente_id_hidden" id="ac_docente_id_hidden">
<input type="hidden" name="ac_estudiante_id" id="ac_estudiante_id">
<input type="hidden" name="ac_nombre_est" id="ac_nombre_est">

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
                            Agendar Reunión con el Docente
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

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_buscar_horario_disponible"><i class='bx bx-search-alt'></i> Buscar Agenda del Docente</button>

                                    <button hidden id="btn_modal_agendar_reunion" type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_agendar_reunion"><i class='bx bx-search-alt'></i> Modal</button>

                                </div>
                            </div>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <p class="text-primary">*Para buscar la agenda del docente debe dar click en el botón.</p>

                                <p id="lbl_docente"></p>

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

<div class="modal" id="modal_buscar_horario_disponible" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5>Buscar Agenda del Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Estudiante <label class="text-danger">*</label></label>
                        <select name="sa_est_id" id="sa_est_id" class="form-select form-select-sm" onchange="consultar_datos_docente_paralelo(this.value);">
                            <option selected disabled>-- Seleccione un Estudiante --</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioC_dia">Profesor <label class="text-danger">*</label></label>
                        <select name="ac_docente_id" id="ac_docente_id" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione un Docente --</option>

                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="buscar_agenda()"><i class="bx bx-save"></i> Buscar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal_agendar_reunion" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5>Agendar Reunión con el Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Motivo de la Reunión <label class="text-danger">*</label></label>
                        <select name="ac_reunion_motivo" id="ac_reunion_motivo" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione un Estudiante --</option>
                            <option value="Faltas">Faltas</option>
                            <option value="Notas">Notas</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                </div>

                <div hidden class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Observación <label class="text-danger">*</label></label>
                        <input type="text" id="ac_reunion_observacion" name="ac_reunion_observacion" class="form-control form-control-sm">
                    </div>
                </div>

                <input type="hidden" name="ac_horarioD_id" id="ac_horarioD_id">

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="agengar_reunion()"><i class="bx bx-save"></i> Agendar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>