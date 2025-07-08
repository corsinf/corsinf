<?php

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']) ?? '';

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>


<script>
  function redireccionar(url_redireccion) {
    url_click = "inicio.php?mod=<?= $modulo_sistema ?>&acc=" + url_redireccion;
    window.location.href = url_click;
  }

  function notificacionesAsistencia() {

    $.ajax({
      url: '../controlador/TALENTO_HUMANO/th_indexC.php?notificaciones_asistencia=true',
      type: 'get',
      dataType: 'json',
      success: function(response) {
        console.log(response);
      },
      error: function(xhr, status, error) {
        console.log('Status: ' + status);
        console.log('Error: ' + error);
        console.log('XHR Response: ' + xhr.responseText);
        Swal.fire('', 'Error: ' + xhr.responseText, 'error');
      }
    });

  }

  function mostrarNotificacionAsistencia(mensajes) {
    const $container = $('#notificationContainer');

    // Limpiar notificaciones anteriores
    $container.empty();

    if (mensajes.length === 1) {
      // Sin horario asignado
      mostrarAlertaSinHorario(mensajes[0]);
    } else if (mensajes.length >= 2) {
      // Notificaciones de asistencia
      mostrarAlertasAsistencia(mensajes);
    }

    // Actualizar contador
    $('#alertCount').text(mensajes.length);
  }

  function mostrarAlertaSinHorario(mensaje) {
    const alertHtml = `
                <div class="alert alert-danger alert-compact border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-start">
                        <div class="alert-icon bg-danger bg-opacity-25">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title text-danger">Sin Horario Asignado</div>
                            <div class="alert-message">${mensaje}</div>
                            <div class="alert-time">Ahora</div>
                            <div class="alert-actions">
                                <button class="btn btn-outline-danger btn-compact" onclick="contactarRRHH()">
                                    <i class="fas fa-phone me-1"></i>RRHH
                                </button>
                                <button class="btn btn-outline-secondary btn-compact" onclick="cerrarNotificacion(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

    $('#notificationContainer').html(alertHtml);
  }

  function mostrarAlertasAsistencia(mensajes) {
    const $container = $('#notificationContainer');
    let alertsHtml = '<div class="row g-3">';

    $.each(mensajes, function(index, mensaje) {
      const esEntrada = mensaje.toLowerCase().includes('entrada') || mensaje.toLowerCase().includes('desde las');
      const esSalida = mensaje.toLowerCase().includes('salida') || mensaje.toLowerCase().includes('falta tiempo');

      let borderClass = 'border-warning';
      let bgClass = 'bg-warning';
      let textClass = 'text-warning';
      let iconClass = 'fa-clock';
      let titulo = 'Notificación de Horario';
      let timeAgo = '';
      let badgeClass = 'bg-warning';

      if (esEntrada) {
        borderClass = 'border-danger';
        bgClass = 'bg-danger';
        textClass = 'text-danger';
        iconClass = 'fa-sign-in-alt';
        titulo = 'Entrada Tardía';
        timeAgo = 'Hace 5h 38m';
        badgeClass = 'bg-danger';
      } else if (esSalida) {
        borderClass = 'border-info';
        bgClass = 'bg-info';
        textClass = 'text-info';
        iconClass = 'fa-sign-out-alt';
        titulo = 'Próxima Salida';
        timeAgo = 'En 1h 52m';
        badgeClass = 'bg-info';
      }

      alertsHtml += `
            <div class="col-md-6">
                <div class="card h-400 ${borderClass} border-start border-4 shadow-sm">
                <div class="card-body p-3">
                    <!-- Header con icono y título -->
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-3">
                            <div class="rounded-circle ${bgClass} bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas ${iconClass} ${textClass} fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 ${textClass} fw-bold">${titulo}</h6>
                            <span class="badge ${badgeClass} bg-opacity-75 text-white small">${timeAgo}</span>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary border-0" onclick="cerrarNotificacion(this)" title="Cerrar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Mensaje principal -->
                    <div class="mb-3">
                        <p class="card-text text-dark mb-0 fw-medium">${mensaje}</p>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="d-flex gap-2 flex-wrap">
                        ${esEntrada ? `
                            <button class="btn btn-danger btn-sm flex-fill" onclick="marcarAsistencia()">
                                <i class="fas fa-fingerprint me-1"></i>
                                <span class="d-none d-sm-inline">Marcar</span>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm flex-fill" onclick="justificarRetraso()">
                                <i class="fas fa-edit me-1"></i>
                                <span class="d-none d-sm-inline">Justificar</span>
                            </button>
                        ` : esSalida ? `
                            <button class="btn btn-info btn-sm flex-fill" onclick="recordarSalida()">
                                <i class="fas fa-bell me-1"></i>
                                <span class="d-none d-sm-inline">Recordar</span>
                            </button>
                        ` : ''}
                    </div>
                </div>
                </div>
            </div>
        `;
    });

    alertsHtml += '</div>';
    $container.html(alertsHtml);
  }

  function mostrarNotificacionExito() {
    const alertHtml = `
                <div class="alert alert-success alert-compact border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-start">
                        <div class="alert-icon bg-success bg-opacity-25">
                            <i class="fas fa-check text-success"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title text-success">¡Todo en Orden!</div>
                            <div class="alert-message">Tu asistencia está al día.</div>
                            <div class="alert-time">Actualizado</div>
                        </div>
                    </div>
                </div>
            `;

    $('#notificationContainer').html(alertHtml);
    $('#alertCount').text('0');
  }

  // Funciones de acciones (mantienen la misma funcionalidad)
  function marcarAsistencia() {
    Swal.fire({
      title: 'Marcar Asistencia',
      text: '¿Deseas marcar tu asistencia ahora?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#198754',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<i class="fas fa-fingerprint me-1"></i>Marcar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire('¡Marcado!', 'Tu asistencia ha sido registrada.', 'success');
      }
    });
  }

  function justificarRetraso() {
    Swal.fire({
      title: 'Justificar Retraso',
      input: 'textarea',
      inputLabel: 'Motivo del retraso',
      inputPlaceholder: 'Describe el motivo de tu retraso...',
      showCancelButton: true,
      confirmButtonText: 'Enviar Justificación',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        Swal.fire('¡Enviado!', 'Tu justificación ha sido registrada.', 'success');
      }
    });
  }

  function contactarRRHH() {
    Swal.fire({
      title: 'Contactar RRHH',
      html: `
                    <div class="text-start">
                        <p><strong>Recursos Humanos</strong></p>
                        <p><i class="fas fa-phone text-primary me-2"></i>+593 99 123 4567</p>
                        <p><i class="fas fa-envelope text-primary me-2"></i>rrhh@empresa.com</p>
                        <p><i class="fas fa-clock text-primary me-2"></i>Horario: 8:00 AM - 5:00 PM</p>
                    </div>
                `,
      icon: 'info',
      confirmButtonText: 'Entendido'
    });
  }

  function recordarSalida() {
    Swal.fire({
      title: 'Recordatorio Configurado',
      text: 'Te notificaremos 10 minutos antes de tu hora de salida.',
      icon: 'success',
      timer: 2000,
      showConfirmButton: false
    });
  }

  function cerrarNotificacion(elemento) {
    const $alert = $(elemento).closest('.alert');
    $alert.css({
      'transition': 'opacity 0.3s ease',
      'opacity': '0'
    });

    setTimeout(function() {
      $alert.remove();
      // Actualizar contador
      const remainingAlerts = $('#notificationContainer .alert').length;
      $('#alertCount').text(Math.max(0, remainingAlerts));
    }, 300);
  }

  // Función principal que conecta con tu AJAX
  function notificacionesAsistencia() {
    $.ajax({
      url: '../controlador/TALENTO_HUMANO/th_indexC.php?notificaciones_asistencia=true',
      type: 'get',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        mostrarNotificacionAsistencia(response);
      },
      error: function(xhr, status, error) {
        console.log('Status: ' + status);
        console.log('Error: ' + error);
        console.log('XHR Response: ' + xhr.responseText);

        const alertHtml = `
                        <div class="alert alert-danger alert-compact border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-start">
                                <div class="alert-icon bg-danger bg-opacity-25">
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                </div>
                                <div class="alert-content">
                                    <div class="alert-title text-danger">Error de Conexión</div>
                                    <div class="alert-message">No se pudo obtener la información de asistencia.</div>
                                    <div class="alert-time">Ahora</div>
                                    <div class="alert-actions">
                                        <button class="btn btn-outline-danger btn-compact" onclick="cerrarNotificacion(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
        $('#notificationContainer').html(alertHtml);
      }
    });
  }

  // Cargar notificaciones automáticamente al cargar la página
  $(document).ready(function() {
    notificacionesAsistencia();

    // Opcional: Recargar cada 5 minutos
    setInterval(notificacionesAsistencia, 300000);
  });
</script>
<?php if (
  $_SESSION['INICIO']['TIPO'] == 'DBA' ||
  $_SESSION['INICIO']['TIPO'] == 'ADMINISTRADOR'
) { ?>
  <script>
    $(document).ready(function() {

      /*contar_dispositivos();
      contar_departamentos();
      contar_feriados();
      contar_reportes();
      listar_empleados_departamento();
      contar_postulantes();
      mostrar_estadisticas_adicionales();
      mostrar_alertas_sistema();*/

    });

    function mostrar_estadisticas_adicionales() {
      // Datos estáticos para estadísticas adicionales
      let estadisticas_rrhh = [{
          titulo: 'Empleados Activos',
          valor: 156,
          porcentaje: 92,
          color: '#28a745',
          icono: 'bx-user-check'
        },
        {
          titulo: 'Rotación Mensual',
          valor: '3.2%',
          porcentaje: 68,
          color: '#ffc107',
          icono: 'bx-transfer'
        },
        {
          titulo: 'Satisfacción Laboral',
          valor: '4.3/5',
          porcentaje: 86,
          color: '#17a2b8',
          icono: 'bx-happy'
        },
        {
          titulo: 'Productividad',
          valor: '87%',
          porcentaje: 87,
          color: '#6f42c1',
          icono: 'bx-trending-up'
        }
      ];

      let html_estadisticas = '';
      estadisticas_rrhh.forEach(function(item) {
        html_estadisticas += `
          <div class="col-6 col-md-3">
            <div class="card radius-10 shadow-card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="flex-grow-1">
                    <p class="mb-0 text-secondary">${item.titulo}</p>
                    <h5 class="my-1">${item.valor}</h5>
                    <div class="progress mt-2" style="height: 4px;">
                      <div class="progress-bar" role="progressbar" style="width: ${item.porcentaje}%; background-color: ${item.color};" aria-valuenow="${item.porcentaje}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <div class="widgets-icons text-white ms-auto" style="background: ${item.color};">
                    <i class='bx ${item.icono}'></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        `;
      });
      $('#pnl_estadisticas_rrhh').html(html_estadisticas);
    }

    function mostrar_alertas_sistema() {
      // Alertas y notificaciones importantes
      let alertas = [{
          tipo: 'warning',
          mensaje: '8 contratos vencen este mes',
          icono: 'bx-time-five',
          accion: 'contratos'
        },
        {
          tipo: 'info',
          mensaje: '12 evaluaciones pendientes',
          icono: 'bx-task',
          accion: 'evaluaciones'
        },
        {
          tipo: 'success',
          mensaje: '95% asistencia este mes',
          icono: 'bx-check-circle',
          accion: 'asistencias'
        }
      ];

      let html_alertas = '';
      alertas.forEach(function(alerta) {
        let colorClass = alerta.tipo === 'warning' ? 'alert-warning' :
          alerta.tipo === 'info' ? 'alert-info' : 'alert-success';
        html_alertas += `
          <div class="alert ${colorClass} border-0 alert-dismissible fade show" role="alert">
            <i class='bx ${alerta.icono} me-2'></i>
            ${alerta.mensaje}
            <button type="button" class="btn-sm btn-outline-secondary ms-auto" onclick="redireccionar('${alerta.accion}')">
              Ver más
            </button>
          </div>
        `;
      });
      $('#pnl_alertas').html(html_alertas);
    }

    function listar_empleados_departamento() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          let total_empleados = response.length;

          // Datos estáticos simulados para asistencias
          let datos_estaticos = [{
              DESCRIPCION: 'PUNTUALES',
              TOTAL: 45,
              COLOR: '#10b981',
              ICON: 'bx-check-circle'
            },
            {
              DESCRIPCION: 'ATRASADAS',
              TOTAL: 8,
              COLOR: '#f59e0b',
              ICON: 'bx-time'
            },
            {
              DESCRIPCION: 'FALTANTES',
              TOTAL: 3,
              COLOR: '#ef4444',
              ICON: 'bx-x-circle'
            },
            {
              DESCRIPCION: 'JUSTIFICACIÓN',
              TOTAL: 2,
              COLOR: '#3b82f6',
              ICON: 'bx-file-blank'
            }
          ];

          pie_empleados(datos_estaticos);

          let html = `
                      <div class="col-6 col-sm-6 col-md-4" id="pnl_solicitudes" onclick="redireccionar('empleados');">
                        <div class="card radius-10 shadow-card">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">TOTAL EMPLEADOS</p>
                                <h4 class="my-1" id="lbl_empleados">${total_empleados}</h4>
                                <small class="text-success"><i class="bx bx-up-arrow-alt"></i> +2 este mes</small>
                              </div>
                              <div class="widgets-icons text-white ms-auto" style="background: #28a745;">
                                <i class='bx bx-user'></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    `;

          $('#pnl_empleados_departamento').empty();

          datos_estaticos.forEach(function(item) {
            html += `
                    <div class="col-6 col-sm-6 col-md-4" id="pnl_solicitudes" onclick="redireccionar('asistencias');">
                      <div class="card radius-10 shadow-card">
                        <div class="card-body">
                          <div class="d-flex align-items-center">
                            <div>
                              <p class="mb-0 text-secondary">${item.DESCRIPCION}</p>
                              <h4 class="my-1" id="lbl_asistencias">${item.TOTAL}</h4>
                              <small class="text-muted">${((item.TOTAL/58)*100).toFixed(1)}% del total</small>
                            </div>
                            <div class="widgets-icons text-white ms-auto" style="background: ${item.COLOR};">
                              <i class='bx ${item.ICON}'></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  `;
          });
          $('#pnl_empleados_departamento').append(html);

          // Agregar gráfico de tendencia de asistencias
          mostrar_tendencia_asistencias();
        },
        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });
    }

    function mostrar_tendencia_asistencias() {
      // Datos simulados para tendencia semanal
      let datos_tendencia = {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie'],
        datasets: [{
          label: 'Asistencias',
          data: [54, 56, 53, 55, 52],
          borderColor: '#28a745',
          backgroundColor: 'rgba(40, 167, 69, 0.1)',
          tension: 0.4
        }]
      };

      let ctx = $('#trendChart').get(0).getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: datos_tendencia,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                display: false
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });
    }

    function contar_postulantes() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar_todo=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          let total_postulantes = response.length;
          $('#lbl_count_postulantes').html(total_postulantes);
        },
        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });
    }

    function contar_dispositivos() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listarAll=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          let total_dispositivos = response.length;
          $('#lbl_count_dispositivos').html(total_dispositivos);
        },
        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });
    }

    function contar_departamentos() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          let total_departamentos = response.length;
          $('#lbl_count_departamentos').html(total_departamentos);
        },
        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });
    }

    function contar_feriados() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_feriadosC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          let total_feriados = response.length;
          $('#lbl_count_feriado').html(total_feriados);
        },
        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });
    }

    function contar_reportes() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_reportesC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          let total_reportes = response.length;
          $('#lbl_count_reportes').html(total_reportes);
        },
        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });
    }

    function pie_empleados(datos_estaticos) {
      let labels = [];
      let data = [];
      let backgroundColor = [];

      datos_estaticos.forEach(function(item) {
        labels.push(item.DESCRIPCION);
        data.push(parseInt(item.TOTAL) || 0);
        backgroundColor.push(item.COLOR || '#cccccc');
      });

      let donutData = {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: backgroundColor,
          borderWidth: 2,
          borderColor: '#fff'
        }]
      };

      let pieChartCanvas = $('#pieChart').get(0).getContext('2d');
      let pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              usePointStyle: true
            }
          }
        }
      };

      new Chart(pieChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: pieOptions
      });
    }
  </script>

<?php } ?>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Inicio</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <div class="btn-group">
          <button type="button" class="btn btn-primary btn-sm" onclick="location.reload()">
            <i class="bx bx-refresh"></i> Actualizar
          </button>
        </div>
      </div>
    </div>
    <!--end breadcrumb-->
    <!-- Simulación de Dashboard -->
    <div class="container-fluid">

      <!-- Panel de Alertas Compacto -->
      <div class="col-12">
        <div class="alerts-sidebar">
          <h6 class="mb-0 text-uppercase">Notificaciones de Asitencia</h6>
          <hr>
          <!-- Contenedor de Notificaciones Compacto -->
          <div class="px-3" id="notificationContainer">
            <!-- Las alertas aparecerán aquí automáticamente -->
          </div>
        </div>
        <div class="notification-container" id="notificationContainer"></div>
      </div>

      <!-- Contenedor de Notificaciones -->

      <div class="row">
        <div class="col-xl-12 mx-auto">

          <?php if (
            $_SESSION['INICIO']['TIPO'] == 'DBA' ||
            $_SESSION['INICIO']['TIPO'] == 'NO CONCURRENTE'
          ) { ?>

            <!-- Alertas del Sistema -->
            <div class="row mb-3">
              <div class="col-12">
                <div id="pnl_alertas"></div>
              </div>
            </div>



            <!-- KPIs Principales -->
            <h6 class="mb-0 text-uppercase">INDICADORES CLAVE</h6>
            <hr>
            <div class="row" id="pnl_estadisticas_rrhh"></div>

            <!-- Dashboard Principal -->
            <h6 class="mb-0 text-uppercase">ASISTENCIAS HOY</h6>
            <hr>

            <div class="row">
              <div class="col-xl-4 col-lg-5">
                <div class="card">
                  <div class="card-header">
                    <h6 class="mb-0">Distribución de Asistencias</h6>
                  </div>
                  <div class="card-body">
                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-xl-8 col-lg-7">
                <div class="row" id="pnl_empleados_departamento">
                </div>
                <div class="row mt-3">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <h6 class="mb-0">Tendencia Semanal de Asistencias</h6>
                      </div>
                      <div class="card-body">
                        <canvas id="trendChart" style="height: 200px;"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Resumen de Próximos Eventos -->
            <div class="row mt-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="mb-0">Próximos Eventos</h6>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="d-flex align-items-center border-end">
                          <i class='bx bx-calendar text-primary me-2'></i>
                          <div>
                            <h6 class="mb-0">Próximo Feriado</h6>
                            <small class="text-muted">15 de Junio - Día del Padre</small>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="d-flex align-items-center border-end">
                          <i class='bx bx-user-plus text-success me-2'></i>
                          <div>
                            <h6 class="mb-0">Nuevos Ingresos</h6>
                            <small class="text-muted">3 empleados esta semana</small>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="d-flex align-items-center">
                          <i class='bx bx-task text-warning me-2'></i>
                          <div>
                            <h6 class="mb-0">Evaluaciones</h6>
                            <small class="text-muted">12 pendientes este mes</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Gestión Administrativa -->
            <h6 class="mb-0 text-uppercase">GESTIÓN ADMINISTRATIVA</h6>
            <hr>

            <div class="row">
              <div class="col-6 col-sm-6 col-md-4 col-lg-2" onclick="redireccionar('th_postulantes');">
                <div class="card radius-10 shadow-card">
                  <div class="card-body text-center">
                    <div class="widgets-icons bg-light-success text-success mx-auto mb-2">
                      <i class='bx bx-user'></i>
                    </div>
                    <h4 class="my-1" id="lbl_count_postulantes">0</h4>
                    <p class="mb-0 text-secondary">Postulantes</p>
                  </div>
                </div>
              </div>

              <div class="col-6 col-sm-6 col-md-4 col-lg-2" onclick="redireccionar('th_dispositivos');">
                <div class="card radius-10 shadow-card">
                  <div class="card-body text-center">
                    <div class="widgets-icons bg-light-info text-info mx-auto mb-2">
                      <i class='bx bx-devices'></i>
                    </div>
                    <h4 class="my-1" id="lbl_count_dispositivos">0</h4>
                    <p class="mb-0 text-secondary">Dispositivos</p>
                  </div>
                </div>
              </div>

              <div class="col-6 col-sm-6 col-md-4 col-lg-2" onclick="redireccionar('th_departamentos');">
                <div class="card radius-10 shadow-card">
                  <div class="card-body text-center">
                    <div class="widgets-icons bg-light-primary text-primary mx-auto mb-2">
                      <i class='bx bx-buildings'></i>
                    </div>
                    <h4 class="my-1" id="lbl_count_departamentos">0</h4>
                    <p class="mb-0 text-secondary">Departamentos</p>
                  </div>
                </div>
              </div>

              <div class="col-6 col-sm-6 col-md-4 col-lg-2" onclick="redireccionar('th_feriados');">
                <div class="card radius-10 shadow-card">
                  <div class="card-body text-center">
                    <div class="widgets-icons bg-light-warning text-warning mx-auto mb-2">
                      <i class='bx bx-calendar-event'></i>
                    </div>
                    <h4 class="my-1" id="lbl_count_feriado">0</h4>
                    <p class="mb-0 text-secondary">Feriados</p>
                  </div>
                </div>
              </div>

              <div class="col-6 col-sm-6 col-md-4 col-lg-2" onclick="redireccionar('th_reportes');">
                <div class="card radius-10 shadow-card">
                  <div class="card-body text-center">
                    <div class="widgets-icons bg-light-danger text-danger mx-auto mb-2">
                      <i class='bx bx-file'></i>
                    </div>
                    <h4 class="my-1" id="lbl_count_reportes">0</h4>
                    <p class="mb-0 text-secondary">Reportes</p>
                  </div>
                </div>
              </div>

              <div class="col-6 col-sm-6 col-md-4 col-lg-2" onclick="redireccionar('justificaciones');">
                <div class="card radius-10 shadow-card">
                  <div class="card-body text-center">
                    <div class="widgets-icons bg-light-secondary text-secondary mx-auto mb-2">
                      <i class='bx bx-file-blank'></i>
                    </div>
                    <h4 class="my-1">24</h4>
                    <p class="mb-0 text-secondary">Justificaciones</p>
                  </div>
                </div>
              </div>
            </div>

          <?php } ?>

        </div>
      </div>
    </div>
  </div>

  <!-- Estilos mejorados -->
  <script>
    $(document).ready(function() {
      $('.shadow-card').on('mouseover', function() {
        $(this).addClass('hoverEffect');
      });

      $('.shadow-card').on('mouseout', function() {
        $(this).removeClass('hoverEffect');
      });

      $('.shadow-card').on('click', function() {
        $(this).toggleClass('clickedEffect');
      });

      $(document).on('mouseout', '.shadow-card', function() {
        $(this).removeClass('clickedEffect');
      });
    });
  </script>

  <style>
    .card {
      cursor: pointer;
      transition: all 0.3s ease;
      border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card.hoverEffect {
      transform: translateY(-2px);
      box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
      background-color: rgba(45, 216, 34, 0.05);
    }

    .card.clickedEffect {
      background-color: rgba(128, 224, 122, 0.3);
    }

    .widgets-icons {
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      font-size: 1.2rem;
    }

    .progress {
      height: 4px;
      background-color: rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .progress-bar {
      border-radius: 10px;
    }

    .alert {
      border-radius: 10px;
      border: none;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .alert button {
      border: none;
      padding: 2px 8px;
      border-radius: 5px;
      font-size: 12px;
    }

    .card-header {
      background-color: transparent;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
      padding: 1rem 1.25rem 0.5rem;
    }

    .card-header h6 {
      color: #495057;
      font-weight: 600;
    }
  </style>