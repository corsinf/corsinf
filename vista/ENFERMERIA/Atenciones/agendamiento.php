<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />

<script type="text/javascript">
  let calendar;
  $(document).ready(function() {

  });

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'dayGridMonth,timeGridDay'
      },
      locale: 'es',
      initialView: 'dayGridMonth',
      initialDate: new Date(),
      navLinks: true,
      selectable: true,
      editable: false,
      nowIndicator: true,
      dayMaxEvents: true,
      selectable: true,
      businessHours: true,
      dayMaxEvents: true,
      events: [],
      eventClick: function(info) {
        // Obtener información del evento
        var tipo_consulta = info.event.id;
        var url = info.event.url;

        //alert(info.event.tabla)
        //console.log(info.event)

        //Redirigir a la página deseada al hacer clic en el evento
        if (tipo_consulta === 'certificado') {
          window.location.href = url;
        } else {
          Swal.fire('', 'No puede realizar consultas.', 'error');
        }

        info.jsEvent.preventDefault();

      }


    });

    calendar.setOption('dateClick', function(info) {
      //abrirModal(info.date);

      var fecha_actual = new Date(); // Obtiene la fecha y hora actuales
      var fecha_ayer = new Date(fecha_actual); // Crea una copia de la fecha actual
      fecha_ayer.setDate(fecha_actual.getDate() - 1);

      // Validar si la fecha seleccionada es anterior a la fecha actual
      if (info.date < fecha_ayer) {
        Swal.fire('', 'No puedes seleccionar una fecha anterior al día actual.', 'error');

      } else {
        abrirModal(info.date);
      }
    });

    // Función para cargar eventos desde AJAX
    cargar_citas();

  });

  function cargar_citas() {
    $.ajax({
      url: '../controlador/agendamientoC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        calendar.removeAllEvents();
        // Recorrer la respuesta y agregar eventos al arreglo events
        response.forEach(function(evento) {
          //console.log(evento);
          calendar.addEvent({
            id: evento.sa_conp_tipo_consulta,
            title: evento.sa_conp_tipo_consulta.toUpperCase() + ' - ' + evento.nombres,
            start: formatoDate(evento.sa_conp_fecha_ingreso.date),
            end: formatoDate(evento.sa_conp_fecha_ingreso.date),
            url: '../vista/inicio.php?mod=7&acc=registrar_consulta_paciente&id_consulta=' + evento.sa_conp_id + '&tipo_consulta=' + evento.sa_conp_tipo_consulta + '&id_ficha=' + evento.sa_fice_id + '&id_paciente=' + evento.sa_pac_id,
          });
        });

        // Renderizar el calendario después de agregar los eventos
        calendar.render();
      }
    });
  }

  function abrirModal(fecha) {
    // Abre el modal (Bootstrap Modal en este ejemplo)
    $('#myModal').modal('show');

    // Actualiza el contenido del modal con la fecha seleccionada
    var fechaString = fecha;
    var fechaObjeto = new Date(fechaString);

    var year = fechaObjeto.getFullYear(); // Obtener el año (por ejemplo, 2023)
    var month = fechaObjeto.getMonth() + 1; // Obtener el mes (0-11, sumar 1 para obtener el formato 1-12)
    var day = fechaObjeto.getDate(); // Obtener el día del mes

    // También puedes formatear la fecha como una cadena YYYY-MM-DD
    var fechaFormateada = year + "-" + (month < 10 ? "0" : "") + month + "-" + (day < 10 ? "0" : "") + day;

    $('input[name="txt_fecha_consulta"]').val(fechaFormateada);

  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->

    <!--end breadcrumb-->
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <div id='calendar'></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../assets/plugins/fullcalendar/js/main.min.js"></script>
<script src="../assets/js/app-fullcalendar.js"></script>

<div class="modal" id="myModal" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row justify-content-center" id="btn_nuevo">

          <div class="col-auto">

            <!-- Unifica ambos formularios dentro de uno -->
            <form action="../vista/inicio.php?mod=7&acc=agendamiento_asistente" method="post">

              <input type="hidden" name="tipo_consulta" id="tipo_consulta" value="consulta">
              <input type="hidden" name="txt_fecha_consulta" id="txt_fecha_consulta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" readonly>

              <button type="submit" class="btn btn-primary btn-lg m-4"><i class='bx bx-file-find'></i> Consulta&nbsp;&nbsp;&nbsp;</button>

            </form>

          </div>

          <div class="col-auto">

            <form action="../vista/inicio.php?mod=7&acc=agendamiento_asistente" method="post">

              <input type="hidden" name="tipo_consulta" id="tipo_consulta" value="certificado">
              <input type="hidden" name="txt_fecha_consulta" id="txt_fecha_consulta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" readonly>

              <button type="submit" class="btn btn-primary btn-lg m-4"><i class='bx bx-file-blank'></i> Certificado</button>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>