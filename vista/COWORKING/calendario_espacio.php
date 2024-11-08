<head>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@5.9.0/main.min.js'></script>
</head>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Formulario</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Calendario espacios</li>
                    </ol>
                </nav>
            </div>
        </div>
  <div id="calendar"></div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      
      // Inicializar el calendario
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',  
        editable: true,  
        selectable: true,  
        events: 'load_events.php',  
        select: function(info) {

          var title = prompt('Título del Evento:');
          if (title) {
            var eventData = {
              title: title,
              start: info.startStr,
              end: info.endStr
            };
            calendar.addEvent(eventData);  // Añadir evento visualmente al calendario

            // Enviar evento al servidor para guardarlo
            fetch('save_event.php', {
              method: 'POST',
              body: JSON.stringify(eventData),
              headers: {
                'Content-Type': 'application/json'
              }
            }).then(response => response.json()).then(data => {
              if (data.success) {
                alert('Evento guardado con éxito.');
              } else {
                alert('Error al guardar el evento.');
              }
            });
          }
          calendar.unselect();  // Deseleccionar las fechas después de agregar el evento
        }
      });

      calendar.render();  // Renderizar el calendario
    });
  </script>

</body>
