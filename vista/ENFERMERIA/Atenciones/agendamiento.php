<link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />

<script type="text/javascript">
	let calendar;
	 $(document).ready(function() {
	 	autocoplete_estudiante()
    });
 
   document.addEventListener('DOMContentLoaded', function () {
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
            	console.log(evento);
            	 calendar.addEvent({
			        title: evento.sa_conp_tipo_consulta+ ' - ' +evento.sa_conp_nombres,
			        start: formatoDate(evento.sa_conp_fecha_ingreso.date),
			        end: formatoDate(evento.sa_conp_fecha_ingreso.date),
			      });
            });

            // Renderizar el calendario después de agregar los eventos
            calendar.render();
        }
    });
}
function autocoplete_estudiante(){
  $('#ddl_pacientes').select2({
    placeholder: 'Seleccione estudiante',
    dropdownParent: $('#myModal'),
    ajax: {
      url: '<?php echo $url_general ?>/controlador/agendamientoC.php?buscar=true',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        // console.log(data);
        return {
          results: data
        };
      },
      cache: true
    }
  }); 
}

function agendar()
{
	var parametros = {
		'estudiante':$('#ddl_pacientes').val(),
		'tipo':$('#txt_tipo_consulta').val(),
		'fecha':$('#txt_fecha_consulta').val(),
	}
	 $.ajax({
	    url: '<?php echo $url_general ?>/controlador/agendamientoC.php?add_agenda=true',
     	data: { parametros: parametros},
	    type: 'post',
	    dataType: 'json',
	    success: function(response) {
	    	Swal.fire('','cita agendada','success').then(function(){
	    		$('#myModal').modal('hide');
	    		cargar_citas();
	    	})
	    }
	 });

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
        <h4 class="modal-title">Nueva consulta</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<div class="row">
      		<div class="col-sm-12">
      			<b>Paciente</b>
      			<select class="form-select form-select-sm" id="ddl_pacientes" name="ddl_pacientes">
      				<option value="">Seleccione paciente</option>
      			</select>
      		</div>
      		<div class="col-sm-4">
      			<b>Fecha de atencion</b>
      			<input type="date" name="txt_fecha_consulta" id="txt_fecha_consulta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
      		</div>
      		<div class="col-sm-8">
      			<b>Tipo de consulta</b>
      			<select class="form-select form-select-sm" id="txt_tipo_consulta" name="txt_tipo_consulta">
      				<option value="">Seleccione tipo de consulta</option>
      				<option value="Consulta">Consulta General</option>
      				<option value="Certificados">Validar certificados</option>
      			</select>
      		</div>
      	</div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="agendar()">Agendar</button>
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>