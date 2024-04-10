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


        $('#ac_horarioD_inicio').blur(function() {
            horaDesde = $(this).val();
            $('#ac_horarioD_fin').val('')
        });

        /*$('#ac_horarioD_fin').change(function() {
            // Vaciar el input
            $(this).val('');
        });*/

        $('#ac_horarioD_fin').blur(function() {
            horaHasta = $(this).val();
            calcular_diferencia_hora();
        });

        cargar_est_rep_doc_par()

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
            slotMinTime: '07:00:00',
            slotMaxTime: '15:00:00',
            slotDuration: '00:30:00',
            slotLabelInterval: {
                hours: 0.5
            },

            eventDisplay: 'block',
            displayEventTime: true,

            eventClick: function(info) {
                // Obtener información del evento
                var id_horario_clases = info.event.extendedProps.id;

                var id_horario_clases = info.event.extendedProps.id_horario_clases ?? '';
                var id_horario_disponible = info.event.extendedProps.id_horario_disponible ?? '';
                var ac_horarioD_estado = info.event.extendedProps.ac_horarioD_estado ?? '';
                

                //${arg.event.id}', '${arg.event.title}', '${startTime}', '${endTime}

                //console.log(ac_horarioD_estado)

                if (id_horario_disponible != '' && ac_horarioD_estado == 1) {
                    eliminar_evento(id_horario_disponible)
                }

                info.jsEvent.preventDefault();

            },

            viewDidMount: function(view) {
                $('.fc-col-header-cell-cushion').css('text-transform', 'uppercase');
            },

            allDaySlot: false,

        });

        calendar.setOption('dateClick', function(info) {

            var fecha_actual = new Date(); // Obtiene la fecha y hora actuales
            var fecha_ayer = new Date(fecha_actual); // Crea una copia de la fecha actual
            fecha_ayer.setDate(fecha_actual.getDate() - 1);

            // Validar si la fecha seleccionada es anterior a la fecha actual
            if (info.date < fecha_ayer) {
                Swal.fire('', 'No puedes seleccionar una fecha anterior al día actual.', 'error');

            } else {
                obtenerHoraInicio(info.date, info.jsEvent);
            }

        });

        // Función para cargar eventos desde AJAX
        cargar_horarios();



    });

    // Función para obtener la hora de inicio basada en los eventos existentes dentro del cuadro de hora específico
    function obtenerHoraInicio(fecha, eventoJS) {
        // Obtenemos la hora específica del cuadro de hora en el que se hizo clic
        var hora_inicio_cuadro = fecha; // La fecha seleccionada en el calendario, que incluye la hora específica del cuadro de hora

        // Filtramos los eventos dentro del intervalo de tiempo del cuadro de hora específico
        var eventos_en_cuadro = calendar.getEvents().filter(function(evento) {
            return evento.start <= hora_inicio_cuadro && evento.end > hora_inicio_cuadro;
        });

        // Si hay eventos dentro del cuadro de hora, encontramos la hora de finalización del último evento
        if (eventos_en_cuadro.length > 0) {
            eventos_en_cuadro.sort(function(a, b) {
                return b.end - a.end; // Ordenamos los eventos por hora de finalización descendente para obtener el último evento
            });
            var hora_fin_ultimo_evento = eventos_en_cuadro[0].end;

            // Llamar a la función abrirModal con la fecha y la hora de finalización del último evento
            abrirModal(fecha, hora_fin_ultimo_evento);
        } else {
            // Si no hay eventos dentro del cuadro de hora, utilizamos la hora de inicio del cuadro de hora
            abrirModal(fecha, hora_inicio_cuadro);
        }
    }

    function abrirModal(fecha, hora_inicio) {
        // Abre el modal (Bootstrap Modal en este ejemplo)
        $('#modal_horario_clases').modal('show');

        // Actualiza el contenido del modal con la fecha seleccionada
        var year = fecha.getFullYear(); // Obtener el año (por ejemplo, 2023)
        var month = fecha.getMonth() + 1; // Obtener el mes (0-11, sumar 1 para obtener el formato 1-12)
        var day = fecha.getDate(); // Obtener el día del mes

        // Formatear la fecha como una cadena YYYY-MM-DD
        var fechaFormateada = year + "-" + (month < 10 ? "0" : "") + month + "-" + (day < 10 ? "0" : "") + day;

        // Formatear la hora de inicio
        var hora_inicio_formateada = (hora_inicio.getHours() < 10 ? '0' : '') + hora_inicio.getHours() + ':' + (hora_inicio.getMinutes() < 10 ? '0' : '') + hora_inicio.getMinutes();

        // Obtener el siguiente evento si existe
        var siguienteEvento = obtenerSiguienteEvento(fecha, hora_inicio)

        // Si hay un siguiente evento, obtener su hora de inicio
        // Obtener la hora de fin formateada
        var hora_fin_formateada = '';
        if (siguienteEvento) {
            // Si el siguiente evento es 07:00, establecer la hora de fin en 15:00
            if (siguienteEvento.getHours() === 7 && siguienteEvento.getMinutes() === 0) {
                hora_fin_formateada = '15:00';
            } else {
                // Formatear la hora de fin normalmente
                hora_fin_formateada = (siguienteEvento.getHours() < 10 ? '0' : '') + siguienteEvento.getHours() + ':' + (siguienteEvento.getMinutes() < 10 ? '0' : '') + siguienteEvento.getMinutes();
            }
        } else {
            // Si no hay eventos, establecer la hora de fin en 15:00
            hora_fin_formateada = '15:00';
        }

        // Actualizar los campos de entrada con la fecha y la hora de inicio y fin del nuevo evento
        $('input[name="ac_horarioD_fecha_disponible"]').val(fechaFormateada);
        $('input[name="ac_horarioD_inicio"]').val(hora_inicio_formateada);
        $('input[name="ac_horarioD_fin"]').val(hora_fin_formateada);
    }

    // Función para obtener la hora de inicio del siguiente evento si existe
    function obtenerSiguienteEvento(fecha, hora_actual) {
        // Filtrar los eventos para encontrar el siguiente evento después de la hora actual
        var siguienteEvento = null;
        var eventos_en_fecha = calendar.getEvents().filter(function(evento) {
            return evento.start.getDate() === fecha.getDate() && evento.start > hora_actual;
        });

        // Ordenar los eventos por hora de inicio
        eventos_en_fecha.sort(function(a, b) {
            return a.start - b.start;
        });

        // Si hay eventos después de la hora actual, obtener el primer evento
        if (eventos_en_fecha.length > 0) {
            siguienteEvento = eventos_en_fecha[0].start;
        }

        return siguienteEvento;
    }

    function eliminar_evento(id_horarioD) {

        //alert(id_horarioD + ' - ' + titulo);

        Swal.fire({
            title: '¿Estás seguro de eliminar esta hora disponible?',
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
                        cargar_horarios();
                    },
                    error: function() {
                        Swal.fire('Error', 'Hubo un error en la operación', 'error');
                    }
                });
            }
        });
    }

    function cargar_horarios() {
        var fechaInicioClases = new Date(); // Obtener la fecha actual
        var id_docente = <?= $id_docente ?>;

        // Calcular la fecha de finalización para tres semanas más
        var fechaFinClases = new Date(fechaInicioClases);
        fechaFinClases.setDate(fechaFinClases.getDate() + 21);

        // Promesa para la solicitud de horarios de clases
        var promesaHorariosClases = new Promise(function(resolve, reject) {
            $.ajax({
                url: '../controlador/horario_clasesC.php?listar=true',
                type: 'get',
                data: {
                    id_docente: id_docente,
                    id_paralelo: '',
                },
                dataType: 'json',
                success: function(response) {
                    var eventos = [];
                    // Recorrer cada semana del mes
                    var fecha = new Date(fechaInicioClases);
                    var fechaLimite = new Date(fechaInicioClases);
                    fechaLimite.setDate(fechaLimite.getDate() + 21); // Establecer la fecha límite en tres semanas más
                    //alert(fechaLimite)
                    while (fecha <= fechaLimite) {
                        // Iterar sobre cada día de la semana
                        for (var i = 0; i < 7; i++) {
                            var fechaDia = new Date(fecha);
                            fechaDia.setDate(fechaDia.getDate() + i);
                            // Verificar si el día coincide con alguno de los días de la semana de la base de datos
                            response.forEach(function(evento) {
                                var fechaEvento = obtenerFechaEvento(fechaDia, evento.ac_horarioC_dia);
                                if (fechaEvento) {
                                    eventos.push({
                                        id: evento.ac_horarioC_id,
                                        title: (evento.ac_horarioC_materia).toUpperCase(),
                                        start: fechaEvento + 'T' + obtener_hora_formateada(evento.ac_horarioC_inicio),
                                        end: fechaEvento + 'T' + obtener_hora_formateada(evento.ac_horarioC_fin),
                                        extendedProps: {
                                            descripcion: ' (' + evento.sa_sec_nombre + ' / ' + evento.sa_gra_nombre + ' / ' + evento.sa_par_nombre + ')',
                                            id_horario_clases: evento.ac_horarioC_id,
                                            estado: evento.ac_horarioD_estado
                                        },
                                        color: '#ffc107'
                                    });
                                }
                            });
                        }
                        // Pasar a la siguiente semana
                        fecha.setDate(fecha.getDate() + 7);
                    }
                    resolve(eventos);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    reject(errorThrown);
                }
            });
        });

        // Promesa para la solicitud de horarios disponibles
        var promesaHorariosDisponibles = new Promise(function(resolve, reject) {
            $.ajax({
                url: '../controlador/horario_disponibleC.php?listar=true',
                type: 'get',
                data: {
                    id_docente: id_docente
                },
                dataType: 'json',
                success: function(response) {
                    var eventos = [];
                    // Recorrer la respuesta y agregar eventos al arreglo events
                    response.forEach(function(evento) {
                        var color = (evento.ac_horarioD_estado == 0) ? '#B63232' : '#3D94C9';
                        eventos.push({
                            id: evento.ac_horarioD_id,
                            title: evento.ac_cubiculo_nombre,
                            start: (evento.ac_horarioD_fecha_disponible) + 'T' + obtener_hora_formateada(evento.ac_horarioD_inicio),
                            end: (evento.ac_horarioD_fecha_disponible) + 'T' + obtener_hora_formateada(evento.ac_horarioD_fin),
                            color: color,
                            extendedProps: {
                                ac_horarioD_ubicacion: evento.ac_horarioD_ubicacion,
                                ac_horarioD_estado: evento.ac_horarioD_estado,
                                id_horario_disponible: evento.ac_horarioD_id,
                            }
                        });
                        //console.log((evento.ac_horarioD_fecha_creacion) + '-- ' + obtener_hora_formateada(evento.ac_horarioD_inicio));
                    });
                    resolve(eventos);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    reject(errorThrown);
                }
            });
        });

        // Ejecutar ambas promesas y renderizar el calendario cuando ambas se completen
        Promise.all([promesaHorariosClases, promesaHorariosDisponibles]).then(function(resultados) {
            var eventos = resultados[0].concat(resultados[1]); // Concatenar los eventos de ambas solicitudes
            calendar.removeAllEvents();
            eventos.forEach(function(evento) {
                calendar.addEvent(evento);
                //console.log(evento)
            });
            calendar.render();
        }).catch(function(error) {
            console.error('Error al cargar los horarios:', error);
        });
    }

    // Función para obtener la fecha del evento basada en el día de la semana
    function obtenerFechaEvento(fecha, diaSemana) {
        var diasSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sábado'];
        var diaEvento = diasSemana.indexOf(diaSemana);
        if (fecha.getDay() === diaEvento) {
            return fecha.toISOString().slice(0, 10); // Formato YYYY-MM-DD
        }
        return null;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function agregar_turno() {

        var errores = [];

        var ac_docente_id = '<?php echo $id_docente; ?>';

        var ac_horarioD_inicio = $('#ac_horarioD_inicio').val();
        var ac_horarioD_fin = $('#ac_horarioD_fin').val();
        var ac_horarioD_fecha_disponible = $('#ac_horarioD_fecha_disponible').val();
        var ac_horarioD_materia = $('#ac_horarioD_materia').val();
        var ac_horarioD_ubicacion = $('#ac_horarioD_ubicacion').val();

        //alert(ac_horarioD_inicio + ' ' + ac_horarioD_fin);

        var parametros = {
            'ac_horarioD_id': '',
            'ac_docente_id': ac_docente_id,
            'ac_horarioD_inicio': ac_horarioD_inicio,
            'ac_horarioD_fin': ac_horarioD_fin,
            'ac_horarioD_fecha_disponible': ac_horarioD_fecha_disponible,
            'ac_horarioD_materia': ac_horarioD_materia,
            'ac_horarioD_ubicacion': ac_horarioD_ubicacion,
        }

        var eventos = calendar.getEvents();
        // Crear la fecha completa combinando la fecha actual y la hora ingresada
        //var hoy = new Date(ac_horarioD_fecha_disponible); // Obtener la fecha actual

        // Obtener la fecha actual
        var partesFecha = ac_horarioD_fecha_disponible.split('-'); // Dividir la fecha en partes
        var hoy = new Date(partesFecha[0], partesFecha[1] - 1, partesFecha[2]); // Crear el objeto Date

        var nueva_inicio = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate(), ac_horarioD_inicio.split(':')[0], ac_horarioD_inicio.split(':')[1]);
        var nueva_fin = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate(), ac_horarioD_fin.split(':')[0], ac_horarioD_fin.split(':')[1]);

        //console.log(ac_horarioD_fecha_disponible)
        //console.log(hoy)

        if (ac_horarioD_inicio != '' && ac_horarioD_fin != '' && ac_horarioD_fecha_disponible != '' && ac_horarioD_materia != null && ac_horarioD_ubicacion != null) {
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

                    chx_turno_rep = $('#chx_turno_rep').prop('checked');
                    console.log(chx_turno_rep);

                    if (chx_turno_rep == true) {

                        //var ac_horarioD_id = $('#ac_horarioD_id').val();
                        var ac_representante_id = $('#sa_id_representante').val();
                        var ac_reunion_motivo = $('#ac_reunion_motivo').val();
                        //var ac_reunion_observacion = $('#ac_reunion_observacion').val();
                        var ac_estudiante_id = $('#txt_estudiante').val();
                        var ac_nombre_est = $('#ac_nombre_est').val();
                        var ac_reunion_descripcion = $('#ac_reunion_descripcion').val();


                        var parametros_turno_rep = {
                            'ac_representante_id': ac_representante_id,
                            'ac_reunion_motivo': ac_reunion_motivo,
                            'ac_estudiante_id': ac_estudiante_id,
                            'ac_nombre_est': ac_nombre_est,
                            'chx_turno_rep': chx_turno_rep,
                            'ac_reunion_descripcion': ac_reunion_descripcion
                        }

                        if (ac_estudiante_id != null && ac_reunion_motivo != null && ac_reunion_descripcion != '') {

                            parametros.parametros_turno_rep = parametros_turno_rep;

                            $.ajax({
                                url: '../controlador/horario_disponibleC.php?insertar=true',
                                data: {
                                    parametros: parametros
                                },
                                type: 'post',
                                dataType: 'json',
                                success: function(response) {
                                    //console.log(response)
                                    Swal.fire('', 'Turno Agregado Con Representante.', 'success').then(function() {
                                        $('#modal_horario_clases').modal('hide');
                                        restablecerModal()
                                    })
                                }
                            });

                        } else {

                            if (ac_estudiante_id == null) {
                                errores.push('Falta seleccionar un Estudiante.');
                            }
                            if (ac_reunion_descripcion == '') {
                                errores.push('Falta Escribir una Descripción del Motivo.');
                            }
                            if (ac_reunion_motivo == null) {
                                errores.push('Falta seleccionar un motivo para la reunión.');
                            }
                        }

                    } else {
                        $.ajax({
                            url: '../controlador/horario_disponibleC.php?insertar=true',
                            data: {
                                parametros: parametros
                            },
                            type: 'post',
                            dataType: 'json',
                            success: function(response) {
                                //console.log(response)
                                Swal.fire('', 'Turno Agregado.', 'success').then(function() {
                                    $('#modal_horario_clases').modal('hide');
                                    restablecerModal()
                                })
                            }
                        });
                    }
                    cargar_horarios(); // Volver a cargar la tabla
                }
            }
        } else {

            if (ac_horarioD_inicio == '') {
                errores.push('Falta seleccionar la hora de inicio.');
            }
            if (ac_horarioD_fin == '') {
                errores.push('Falta seleccionar la hora de finalización.');
            }
            if (ac_horarioD_fecha_disponible == '') {
                errores.push('Falta seleccionar una fecha disponible.');
            }
            if (ac_horarioD_materia == null) {
                errores.push('Falta disponibilidad');
            }
            if (ac_horarioD_ubicacion == null) {
                errores.push('Falta seleccionar un cubículo.');
            }
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

        //////////////////////////////////
    }

    function cargar_cubiculos() {

        $('#btn_buscar_cubiculo').hide();
        $('#btn_agregar_turno').show();
        $('#pnl_select_cubiculo').show();

        $('#ac_horarioD_inicio').prop('readonly', true);
        $('#ac_horarioD_fin').prop('readonly', true);

        var ac_horarioD_inicio = $('#ac_horarioD_inicio').val();
        var ac_horarioD_fin = $('#ac_horarioD_fin').val();
        var ac_horarioD_fecha_disponible = $('#ac_horarioD_fecha_disponible').val();

        select = '<option selected disabled>-- Seleccione un Cubículo --</option>';
        $.ajax({
            url: '../controlador/cat_cubiculoC.php?listar=true',
            type: 'POST',
            data: {
                hora_inicio: ac_horarioD_inicio,
                hora_fin: ac_horarioD_fin,
                fecha_disponible: ac_horarioD_fecha_disponible,
            },
            success: function(response) {
                //console.log(response);
                try {
                    var jsonResponse = JSON.parse(response);

                    // Recorre cada elemento en el arreglo JSON
                    jsonResponse.forEach(function(item) {
                        // Realiza cualquier acción con cada elemento
                        select += '<option value="' + item.ac_cubiculo_id + '">' + item.ac_cubiculo_nombre + '</option>';

                    });

                    $('#ac_horarioD_ubicacion').html(select);

                } catch (error) {
                    console.error('Error al analizar la respuesta JSON:', error);
                }
            },
            error: function() {
                Swal.fire('Error', 'Hubo un error en la operación', 'error');
            }
        });
    }

    function cargar_est_rep_doc_par() {
        var id_docente = '<?php echo $id_docente; ?>';

        $('#txt_estudiante').select2({
            placeholder: '-- Seleccione un Estudiante --',
            dropdownParent: $('#modal_horario_clases'),
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
            minimumInputLength: 1,
            ajax: {
                url: '../controlador/docente_paraleloC.php?lista_est_rep_doc_par=true',
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function(params) {
                    return {
                        id_docente: id_docente,
                        searchTerm: params.term // Envía el término de búsqueda al servidor

                    };
                },
                processResults: function(data, params) { // Agrega 'params' como parámetro
                    var searchTerm = params.term.toLowerCase();

                    var options = data.reduce(function(filtered, item) {

                        var fullName = item['sa_est_cedula'] + " - " + item['estudiante_nombres'];

                        if (fullName.toLowerCase().includes(searchTerm)) {
                            filtered.push({
                                id: item['sa_est_id'],
                                text: fullName,
                                representante_nombres: item.representante_nombres + ' - ' + item.sa_est_rep_parentesco,
                                sa_id_representante: item.sa_id_representante,
                                ac_nombre_est: item.estudiante_nombres,
                            });
                        }

                        return filtered;
                    }, []);

                    return {
                        results: options
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var representante_nombres = e.params.data.representante_nombres;
            $('#lbl_representante_nombres').html(representante_nombres);
            $('#lbl_title_rep_nom').html('Representante');

            /////////////////////////////////////////////////////////////////

            var sa_id_representante = e.params.data.sa_id_representante;
            var ac_nombre_est = e.params.data.ac_nombre_est;

            $('#sa_id_representante').val(sa_id_representante);
            $('#ac_nombre_est').val(ac_nombre_est);

        });
    }

    function restablecerModal() {
        $('#btn_buscar_cubiculo').show();
        $('#btn_agregar_turno').hide();
        $('#pnl_select_cubiculo').hide();

        $('#ac_horarioD_inicio').prop('readonly', false);
        $('#ac_horarioD_fin').prop('readonly', false);

        restablecerDatosRepModal();
    }

    function restablecerDatosRepModal() {
        $('#pnl_select_estudiante').hide();

        $('#chx_turno_rep').prop('checked', false);

        $('#ac_reunion_descripcion').val('');

        $('#txt_estudiante').val(null).trigger('change');

        $('#lbl_title_rep_nom').html('');
        $('#lbl_representante_nombres').html('');
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

<!--Datos para asignar una reunion a un representante -->

<input type="hidden" name="sa_id_representante" id="sa_id_representante">
<input type="hidden" name="ac_nombre_est" id="ac_nombre_est">

<!------------------------------------------------------>

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


                        <section class="content pt-2">
                            <div class="container-fluid">


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
                <h5>Agregar Turno</h5>
                <button type="button" class="btn-close" id="btn_cerrar_mhc" onclick="restablecerModal();" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row" hidden>
                    <div class="col-12">
                        <label for="ac_horarioD_materia">Disponibilidad <label class="text-danger">*</label></label>
                        <input type="text" name="ac_horarioD_materia" id="ac_horarioD_materia" value="Disponible" class="form-control form-control-sm" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="ac_horarioD_fecha_disponible">Día <label class="text-danger">*</label></label>
                        <input type="date" name="ac_horarioD_fecha_disponible" id="ac_horarioD_fecha_disponible" class="form-control form-control-sm" min="" required readonly>
                        <script>
                            var fechaActual = new Date().toISOString().split('T')[0];
                            document.getElementById('ac_horarioD_fecha_disponible').min = fechaActual;
                        </script>
                    </div>
                </div>

                <div class="row pt-3">

                    <div class="col-6">
                        <label for="ac_horarioD_inicio">Inicio de la Hora de Turno <label class="text-danger">*</label></label>
                        <input type="time" name="ac_horarioD_inicio" id="ac_horarioD_inicio" class="form-control form-control-sm">
                    </div>

                    <div class="col-6">
                        <label for="ac_horarioD_fin">Fin de la Hora de Turno <label class="text-danger">*</label></label>
                        <input type="time" name="ac_horarioD_fin" id="ac_horarioD_fin" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="row pt-3" id="btn_buscar_cubiculo">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary btn-sm" onclick="cargar_cubiculos()"><i class="bx bx-search"></i> Buscar Cubículo</button>
                    </div>
                </div>

                <div class="row pt-3" id="pnl_select_cubiculo" style="display: none;">
                    <div class="col-12">
                        <label for="ac_horarioD_fecha_disponible">Cubículo <label class="text-danger">*</label></label>
                        <select name="ac_horarioD_ubicacion" id="ac_horarioD_ubicacion" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione un Cubículo --</option>
                        </select>
                    </div>

                    <div class="col-12 pt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="chx_turno_rep">
                            <label class="form-check-label text-secondary" for="chx_turno_rep">Asignar Turno a Representante</label>
                        </div>
                    </div>
                </div>

                <div class="row pt-2" id="pnl_select_estudiante" style="display: none;">
                    <div class="row m-0">
                        <div class="col-12">
                            <label for="ac_horarioC_materia">Motivo de la Reunión <label class="text-danger">*</label></label>
                            <select name="ac_reunion_motivo" id="ac_reunion_motivo" class="form-select form-select-sm" disabled>
                                <option selected value="Solicitado">Solicitado al Representante</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-0 pt-3">
                        <div class="col-12">
                            <label for="ac_horarioC_materia">Descripción del Motivo<label class="text-danger">*</label></label>
                            <textarea name="ac_reunion_descripcion" id="ac_reunion_descripcion" cols="30" rows="2" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>

                    <div class="row m-0 pt-3">
                        <div class="col-12">
                            <label for="txt_estudiante">Estudiante <label class="text-danger">*</label></label>
                            <select name="txt_estudiante" id="txt_estudiante" class="form-select form-select-sm">
                                <option selected disabled>-- Seleccione un Estudiante --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-12 pt-2">
                            <label id="lbl_title_rep_nom"></label>
                            <br>
                            <label class="text-primary fw-bold" id="lbl_representante_nombres"> </label>
                        </div>
                    </div>

                </div>



                <div class="row pt-3">
                    <div class="col-12 text-end">

                        <button style="display: none;" type="button" id="btn_agregar_turno" class="btn btn-success btn-sm" onclick="agregar_turno()"><i class="bx bx-save"></i> Agregar</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        validarHoraInicioFin('ac_horarioD_inicio');
        validarHoraInicioFin('ac_horarioD_fin');

        $('#chx_turno_rep').click(function() {
            // Verificar si el checkbox está marcado
            if ($(this).is(':checked')) {
                // Mostrar el div si el checkbox está marcado
                $('#pnl_select_estudiante').show();
            } else {
                // Ocultar el div si el checkbox no está marcado
                $('#pnl_select_estudiante').hide();
                restablecerDatosRepModal()
            }
        });
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

<script>
    function calcular_diferencia_hora() {
        var horaDesde = $('#ac_horarioD_inicio').val();
        var horaHasta = $('#ac_horarioD_fin').val();

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
                $('#ac_horarioD_fin').val('');
            }
        } else {
            Swal.fire('', 'El fin de la hora de turno no puede ser menor a la del incio', 'info');
            $('#ac_horarioD_fin').val('');
        }
    }
</script>