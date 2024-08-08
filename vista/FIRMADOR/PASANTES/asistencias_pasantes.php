<!-- Comentario para saber si todo está bien -->
 <!-- Comentario para saber si todo está bien -->
  <!-- Comentario para saber si todo está bien -->
   <!-- Comentario para saber si todo está bien -->
    <!-- Comentario para saber si todo está bien -->
     <!-- Comentario para saber si todo está bien -->
      <!-- Comentario para saber si todo está bien -->
       <!-- Comentario para saber si todo está bien -->
        <!-- Comentario para saber si todo está bien -->
         <!-- Comentario para saber si todo está bien -->
          <!-- Comentario para saber si todo está bien -->
           <!-- Comentario para saber si todo está bien -->
            <!-- Comentario para saber si todo está bien -->
             <!-- Comentario para saber si todo está bien -->
<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        cargarDatos();
        $('#tbl_pasante').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/PASANTES/asistencias_pasantesC.php?listar=true',
                dataSrc: ''
            },
            dom: '<"top"Bfr>t<"bottom"lip>',
            buttons: [{
                    extend: 'excel',
                    text: '<i class="bx bxs-file-pdf me-0"></i> Exportar a Excel',
                    title: 'Título del archivo Excel',
                    filename: 'nombre_archivo_excel'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bx bxs-spreadsheet me-0"></i> Exportar a PDF',
                    title: 'Título del archivo PDF',
                    filename: 'nombre_archivo_PDF'
                }
            ],
            columns: [
                {
                    data: 'pas_nombre',
                    render: function(data, type, item) {
                        return item.pas_nombre;
                    }
                },
                {
                    data: 'pas_hora_llegada',
                    render: function(data, type, item) {
                        return extraerHoraMinutos(item.pas_hora_llegada) || 'No disponible';
                    }
                },
                {
                    data: 'pas_hora_salida',
                    render: function(data, type, item) {
                        return extraerHoraMinutos(item.pas_hora_salida) || 'No disponible';
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.pas_hora_llegada && item.pas_hora_salida) {
                            var totalHoras = calcular_diferencia_horas(item.pas_hora_llegada, item.pas_hora_salida);
                            return '<span>' + totalHoras.toFixed(2) + '</span>';
                        } else {
                            return 'No disponible';
                        }
                    }
                }
            ],
            order: [
                [0, 'asc']
            ],
            initComplete: function() {
                // Mover los botones al contenedor personalizado
                $('#contenedor_botones').append($('.dt-buttons'));
            }
        });
    });

    function extraerHoraMinutos(fechaHora) {
        var partesHora = fechaHora.split("T")[1].split(":");  // Usamos solo la parte de la hora y minutos
        return partesHora[0] + ':' + partesHora[1];  // Retornamos en formato HH:MM
    }

    function calcular_diferencia_horas(hora_llegada, hora_salida) {
        var llegada = extraerHoraMinutos(hora_llegada).split(":");
        var salida = extraerHoraMinutos(hora_salida).split(":");

        var horasLlegada = parseInt(llegada[0], 10);
        var minutosLlegada = parseInt(llegada[1], 10);

        var horasSalida = parseInt(salida[0], 10);
        var minutosSalida = parseInt(salida[1], 10);

        var minutosLlegadaTotales = horasLlegada * 60 + minutosLlegada;
        var minutosSalidaTotales = horasSalida * 60 + minutosSalida;

        var diferenciaMinutos = minutosSalidaTotales - minutosLlegadaTotales;

        if (diferenciaMinutos < 0) {
            diferenciaMinutos += 24 * 60; // Ajuste para horas pasadas de la medianoche
        }

        return diferenciaMinutos / 60; // Diferencia en horas
    }

    function cargarDatos() {
        $.ajax({
            url: '../controlador/PASANTES/asistencias_pasantesC.php?listar=true',
            type: 'post',
            // data: {
            //     id: 1
            // },
            dataType: 'json',
            success: function(response) {
                console.log(response);
            },
            // error: function(jqXHR, textStatus, errorThrown) {
            //     // Manejo de errores
            //     console.error('Error al cargar los configs:', textStatus, errorThrown);
            //     $('#pnl_config_general').append('<p>Error al cargar las configuraciones. Por favor, inténtalo de nuevo más tarde.</p>');
            // }
        });
    }

</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pasantias</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            CORSINF - Pasantes
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
                        <div class="row">
                            <div class="col-6">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                                    <h5 class="mb-0 text-primary">Pasantes</h5>
                                    <div class="row mx-1">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <a href="../vista/inicio.php?mod=7&acc=registrar_pasantes" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>
                        <hr>
                        <section class="content pt-4">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_pasante" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre Pasante</th>
                                                <th>Hora de llegada</th>
                                                <th>Hora de salida</th>
                                                <th>Total de Horas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Datos se llenan mediante AJAX -->
                                        </tbody>
                                    </table>
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