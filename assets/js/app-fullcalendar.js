// document.addEventListener('DOMContentLoaded', function () {
//     var calendarEl = document.getElementById('calendar');
//     var calendar = new FullCalendar.Calendar(calendarEl, {
//         headerToolbar: {
//             left: 'prev,next',
//             center: 'title',
//             right: 'dayGridMonth,timeGridDay'
//         },
//         locale: 'es',
//         initialView: 'dayGridMonth',
//         initialDate: new Date(),
//         navLinks: true,
//         selectable: true,
//         nowIndicator: true,
//         dayMaxEvents: true,
//         editable: true,
//         selectable: true,
//         businessHours: true,
//         dayMaxEvents: true,
//         events: []
//     });

//     // Función para cargar eventos desde AJAX
//     function cargar_citas() {
//         $.ajax({
//             url: '<?php echo $url_general ?>/controlador/agendamientoC.php?listar=true',
//             type: 'post',
//             dataType: 'json',
//             success: function(response) {
//                 // Recorrer la respuesta y agregar eventos al arreglo events
//                 response.forEach(function(evento) {
//                     calendar.addEvent(evento);
//                 });

//                 // Renderizar el calendario después de agregar los eventos
//                 calendar.render();
//             }
//         });
//     }

//     // Llamar a la función para cargar citas
//     cargar_citas();
// });
