<?php

$id_estudiante = '';
$paralelo = '';

if (isset($_GET['id_estudiante'])) {
    $id_estudiante = $_GET['id_estudiante'];
}

if (isset($_GET['paralelo'])) {
    $paralelo = $_GET['paralelo'];
}

?>
<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var id_estudiante = '<?php echo $id_estudiante; ?>';
        existe_paciente();
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
                            url: '../controlador/horario_clasesC.php?eliminar=true',
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

        var paralelo = '<?php echo $paralelo; ?>';

        $.ajax({
            url: '../controlador/index_saludC.php?horario_clases=true',
            type: 'get',
            data: {
                id_paralelo: paralelo
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

    function existe_paciente() {
        //alert('')
        var id_estudiante = '<?php echo $id_estudiante; ?>';

        $.ajax({
            data: {
                sa_pac_id_comunidad: id_estudiante,
                sa_pac_tabla: 'estudiantes',
            },
            url: '../controlador/pacientesC.php?existe_paciente=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response == null) {
                    $('.alerta-salud').show();
                } else {
                    datos_col_ficha_medica(response.sa_pac_id);
                    cargar_datos_paciente(response.sa_pac_id);
                    cargar_datos_consultas(response.sa_pac_id);
                    mostrar_salud();
                }

            }
        });
    }

    function mostrar_salud() {
        $('#li_ficha_medica').show();
        $('#li_consulta_medica').show();
        $('#pnl_ficha_medica').show();
        $('#pnl_consulta_medica').show();
    }

    //Datos del paciente
    function cargar_datos_paciente(sa_pac_id) {
        $.ajax({
            data: {
                sa_pac_id: sa_pac_id
            },
            url: '../controlador/pacientesC.php?obtener_info_paciente=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                ///  Para la tabla de inicio /////////////////////////////////////////////////////////////////////////////////////////////////////////
                $('#txt_ci').html(response[0].sa_pac_temp_cedula + " <i class='bx bxs-id-card'></i>");
                nombres = response[0].sa_pac_temp_primer_nombre + ' ' + response[0].sa_pac_temp_segundo_nombre;
                apellidos = response[0].sa_pac_temp_primer_apellido + ' ' + response[0].sa_pac_temp_segundo_apellido;

                $('#txt_nombres').html(apellidos + " " + nombres);
                $('#title_paciente').html(apellidos + " " + nombres);

                sexo_paciente = '';
                if (response[0].sa_pac_temp_sexo === 'Masculino') {
                    sexo_paciente = "Masculino <i class='bx bx-male'></i>";
                } else if (response[0].sa_pac_temp_sexo === 'Femenino') {
                    sexo_paciente = "Famenino <i class='bx bx-female'></i>";
                }
                $('#txt_sexo').html(sexo_paciente);
                $('#txt_fecha_nacimiento').html((response[0].sa_pac_temp_fecha_nacimiento) + ' (' + calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento) + ' años)');

                curso = response[0].sa_pac_temp_sec_nombre + '/' + response[0].sa_pac_temp_gra_nombre + '/' + response[0].sa_pac_temp_par_nombre;
                $('#txt_curso').html(curso);
                $('#sa_conp_nivel').val(response[0].sa_pac_temp_gra_nombre);
                $('#sa_conp_paralelo').val(response[0].sa_pac_temp_par_nombre);
                $('#sa_id_paralelo').val(response[0].sa_pac_temp_paralelo);

                $('#sa_pac_temp_rep_id').val(response[0].sa_pac_temp_rep_id);

            }
        });
    }

    //Datos de la ficha medica
    function datos_col_ficha_medica(sa_pac_id) {
        //alert(id_ficha)
        $.ajax({
            data: {
                sa_pac_id: sa_pac_id
            },
            url: '../controlador/ficha_MedicaC.php?listar_paciente_ficha=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                // Preguntas
                $('#txt_sa_fice_pac_grupo_sangre').html(response[0].sa_fice_pac_grupo_sangre);
                $('#txt_sa_fice_pregunta_1_obs').html(response[0].sa_fice_pregunta_1_obs);
                $('#txt_sa_fice_pregunta_2_obs').html(response[0].sa_fice_pregunta_2_obs);
                $('#txt_sa_fice_pregunta_3_obs').html(response[0].sa_fice_pregunta_3_obs);
                $('#txt_sa_fice_pregunta_4_obs').html(response[0].sa_fice_pregunta_4_obs);
                $('#txt_sa_fice_pregunta_5_obs').html(response[0].sa_fice_pregunta_5_obs);
            }
        });
    }


    function cargar_datos_consultas(id_paciente = '') {
        var id_estudiante = '<?php echo $id_estudiante; ?>';

        var consulta = '';
        $.ajax({
            data: {
                id_paciente: id_paciente
            },
            url: '../controlador/pacientesC.php?obtener_idFicha_paciente=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //Primer ajax
                //console.log(response.sa_fice_id);

                //$('input[name="id_ficha"]').val(response.sa_fice_id);

                $.ajax({
                    data: {
                        id_ficha: response.sa_fice_id
                    },
                    url: '../controlador/consultasC.php?listar_consulta_ficha=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(responseConsultas) {
                        //Segundo ajax
                        $('#tbl_consultas').DataTable({
                            destroy: true,
                            data: responseConsultas,
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                            },
                            responsive: true, // Datos de las consultas médicas
                            columns: [
                                // Definir las columnas
                                {
                                    data: null,
                                    render: function(data, type, item) {

                                        botones = '';
                                        botones += '<div class="d-inline">';
                                        botones += '<button type="button" class="btn btn-primary btn-sm m-1" title="Detalles de la Consulta" onclick="ver_pdf(' + item.sa_conp_id + ', \'' + item.sa_conp_tipo_consulta + '\', ' + id_estudiante + ')"> <i class="bx bx-file me-0"></i></button>';
                                        botones += '</div>';

                                        return botones;
                                    }
                                },
                                {
                                    data: null,
                                    render: function(data, type, item) {
                                        if (item.sa_conp_desde_hora == null || item.sa_conp_fecha_ingreso == null) {
                                            return '';
                                        } else {
                                            return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion) + ' / ' + obtener_hora_formateada_arr(item.sa_conp_fecha_creacion);
                                        }
                                    }
                                },
                                {
                                    data: null,
                                    render: function(data, type, item) {
                                        if (item.sa_conp_desde_hora == null || item.sa_conp_hasta_hora == null) {
                                            return '';
                                        } else {
                                            return (item.sa_conp_fecha_ingreso) + ' / ' + obtener_hora_formateada(item.sa_conp_desde_hora) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora);
                                        }
                                    }
                                },
                                {
                                    data: 'sa_conp_permiso_salida',
                                },
                                {
                                    data: null,
                                    render: function(data, type, item) {
                                        if (item.sa_conp_tipo_consulta == 'consulta') {
                                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + ('Atención médica') + '</div>';
                                        } else {
                                            return '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
                                        }
                                    }
                                },
                                {
                                    data: null,
                                    render: function(data, type, item) {
                                        if (item.sa_conp_estado_revision == 0) {
                                            return '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' + 'Creado' + '</div>';
                                        } else if (item.sa_conp_estado_revision == 1) {
                                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + 'Finalizado' + '</div>';
                                        } else if (item.sa_conp_estado_revision == 2) {
                                            return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">' + 'En Proceso' + '</div>';
                                        }
                                    }
                                },
                            ],
                            order: [
                                [1, 'desc'] // Ordenar por la segunda columna (índice 1) en orden ascendente
                            ]
                        });

                    }
                });
            }
        });
    }

    function ver_pdf(id_consulta, tipo_consulta, id_estudiante) {
        //console.log(id_consulta);
        window.location.href = '../vista/inicio.php?mod=7&acc=detalle_consulta&pdf_consulta=true&id_consulta=' + id_consulta + '&id_estudiante=' + id_estudiante + '&btn_regresar=represententes_consulta' + '&tipo_consulta=' + tipo_consulta;
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
                            Perfil
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">

            <div class="col-12">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Perfil del Estudiante - <b id="title_paciente" class="text-success"></b></h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="inicio.php?mod=7&acc=index" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="content">
                            <div class="col">



                                <ul class="nav nav-tabs nav-success" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_horario_clases" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-receipt font-18 me-1'></i></i>
                                                </div>
                                                <div class="tab-title">Horario de Clases</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" id="li_ficha_medica" style="display: none;">
                                        <a class="nav-link" data-bs-toggle="tab" href="#tab_ficha_medica" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-file font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Ficha Médica</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" id="li_consulta_medica" style="display: none;">
                                        <a class="nav-link" data-bs-toggle="tab" href="#tab_consulta_medica" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-file font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Consultas Médicas</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade show active" id="tab_horario_clases" role="tabpanel">

                                        <section class="content">
                                            <div class="container-fluid">

                                                <div class="row" id="pnl_horario_clases">
                                                    <div class="col-12">
                                                        <div id='calendar'></div>
                                                    </div>

                                                </div>

                                            </div><!-- /.container-fluid -->
                                        </section>
                                    </div>

                                    <div class="tab-pane fade" id="tab_ficha_medica" role="tabpanel">

                                        <section class="content">
                                            <div class="container-fluid">

                                                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2 alerta-salud" style="display: none;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 text-white">No hay registros</h6>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row pt-3" id="pnl_ficha_medica" style="display: none;">
                                                    <div class="col-12">
                                                        <div class="">
                                                            <table class="table mb-0" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 30%;"></th>
                                                                        <th style="width: 25%;"></th>
                                                                        <th style="width: 25%;"></th>
                                                                        <th style="width: 25%;"></th>
                                                                    </tr>

                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="table-primary text-end">Cédula:</th>
                                                                        <td id="txt_ci"></td>

                                                                        <th class="table-primary text-end">Sexo:</th>
                                                                        <td id="txt_sexo"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="table-primary text-end">Nombres:</th>
                                                                        <td id="txt_nombres" colspan="3"></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="table-primary text-end">Fecha de Nacimiento:</th>
                                                                        <td id="txt_fecha_nacimiento"></td>

                                                                        <th class="table-primary text-end" id="variable_paciente">Grupo Sanguíneo:</th>
                                                                        <td id="txt_sa_fice_pac_grupo_sangre"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="table-primary text-end" id="variable_paciente">Curso:</th>
                                                                        <td id="txt_curso" colspan="3"></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="table-primary text-end" id="variable_paciente">1.- ¿Ha sido diagnosticado con alguna enfermedad?:</th>
                                                                        <td id="txt_sa_fice_pregunta_1_obs" colspan="3"></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="table-primary text-end" id="variable_paciente">2.- ¿Tiene algún antecedente familiar de importancia?:</th>
                                                                        <td id="txt_sa_fice_pregunta_2_obs" colspan="3"></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="table-primary text-end" id="variable_paciente">3.- ¿Ha sido sometido a cirugías previas?:</th>
                                                                        <td id="txt_sa_fice_pregunta_3_obs" colspan="3"></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="table-primary text-end" id="variable_paciente">4.- ¿Tiene alergias?:</th>
                                                                        <td id="txt_sa_fice_pregunta_4_obs" colspan="3"></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="table-primary text-end" id="variable_paciente">5.- ¿Qué medicamentos usa?:</th>
                                                                        <td id="txt_sa_fice_pregunta_5_obs" colspan="3"></td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div><!-- /.container-fluid -->
                                        </section>
                                    </div>

                                    <div class="tab-pane fade" id="tab_consulta_medica" role="tabpanel">

                                        <section class="content">
                                            <div class="container-fluid">

                                                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2 alerta-salud" style="display: none;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 text-white">No hay registros</h6>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row pt-3" id="pnl_consulta_medica" style="display: none;">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped responsive" id="tbl_consultas" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%">Revisar</th>
                                                                    <th>Fecha de creación</th>
                                                                    <th>Fecha Agenda / Hora Desde/Hasta</th>
                                                                    <th>Permiso de Salida</th>
                                                                    <th>Tipo de Atención</th>
                                                                    <th width="10px">Estado</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div><!-- /.container-fluid -->
                                        </section>
                                    </div>

                                </div>


                            </div>


                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script src="../assets/plugins/fullcalendar/js/main.min.js"></script>
<script src="../assets/js/app-fullcalendar.js"></script>