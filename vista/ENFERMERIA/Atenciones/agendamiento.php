<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />autocoplete_paciente

<script type="text/javascript">
  let calendar;
  $(document).ready(function() {
    autocoplete_paciente()
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
      events: []
    });

    // Función para cargar eventos desde AJAX
    cargar_citas();

  });

  function cargar_citas() {
    $.ajax({
      url: '<?php echo $url_general ?>/controlador/agendamientoC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        calendar.removeAllEvents();
        // Recorrer la respuesta y agregar eventos al arreglo events
        response.forEach(function(evento) {
          //console.log(evento);
          calendar.addEvent({
            title: evento.sa_conp_tipo_consulta + ' - ' + evento.nombres,
            start: formatoDate(evento.sa_conp_fecha_ingreso.date),
            end: formatoDate(evento.sa_conp_fecha_ingreso.date),
          });
        });

        // Renderizar el calendario después de agregar los eventos
        calendar.render();
      }
    });
  }

  function autocoplete_paciente() {
    $('#ddl_pacientes').select2({
      placeholder: '-- Seleccione Paciente --',
      dropdownParent: $('#myModal'),
      ajax: {
        url: '<?php echo $url_general ?>/controlador/agendamientoC.php?buscar=true',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          // console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function agendar() {
    var paciente = $('#ddl_pacientes').val();
    var tipoConsulta = $('#txt_tipo_consulta').val();
    var fechaConsulta = $('#txt_fecha_consulta').val();

    if (paciente && tipoConsulta && fechaConsulta) {
      // Todos los campos están llenos, puedes continuar con el envío de datos
      var parametros = {
        'paciente': paciente,
        'tipo': tipoConsulta,
        'fecha': fechaConsulta
      };

      $.ajax({
        url: '<?php echo $url_general ?>/controlador/agendamientoC.php?add_agenda=true',
        data: {
          parametros: parametros
        },
        type: 'post',
        dataType: 'json',
        success: function(response) {
          console.log(response)
          Swal.fire('', 'Cita Agendada', 'success').then(function() {
            $('#myModal').modal('hide');
            cargar_citas();
          })
        }
      });

    } else {
      Swal.fire('Oops...', 'Todos los campos son obligatorios. Por favor, completa la información.', 'error').then(function() {})
    }


  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-sm-3">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-plus"></i> Nueva consulta</button>
          </div>
        </div>

        <div class="row pt-3">
          <div class="col-sm-3">
            <a class="btn btn-sm btn-primary" href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=agendamiento_asistente"><i class="bx bx-plus"></i> Nueva Consulta</a>
          </div>
        </div>

      </div>
    </div>

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
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-success">Nueva Consulta</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <b>Pacientes: <label class="text-danger">*</label></b>
            <select class="form-select form-select-sm" id="ddl_pacientes" name="ddl_pacientes">
              <option value="">-- Seleccione Paciente --</option>
            </select>
          </div>

          <div class="row pt-3">
            <div class="col-sm-5">
              <b>Fecha de Atención: <label class="text-danger">*</label></b>
              <input type="date" name="txt_fecha_consulta" id="txt_fecha_consulta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="col-sm-7">
              <b>Tipo de Consulta: <label class="text-danger">*</label></b>
              <select class="form-select form-select-sm" id="txt_tipo_consulta" name="txt_tipo_consulta">
                <option value="">-- Seleccione Tipo de Consulta --</option>
                <option value="consulta">Consulta General</option>
                <option value="certificado">Validar Certificado</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="agendar()">Agendar</button>
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>