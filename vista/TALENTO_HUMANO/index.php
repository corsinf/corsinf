<?php

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']) ?? '';

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>



<?php if (
  $_SESSION['INICIO']['TIPO'] == 'DBA' ||
  $_SESSION['INICIO']['TIPO'] == 'ADMINISTRADOR'
) { ?>
  <script>
    $(document).ready(function() {
      cargar_estadisticas_departamentos();
      cargar_empleados_sin_turno();
      //cargar_grafico_asistencias_semanal();
      cargar_empleados_ausentes_hoy();
      cargar_justificaciones_pendientes();
      cargar_horas_extra_mes();



      cargar_resumen_asistencias();
      cargar_alertas_asistencia();

      control_acceso_datos();

      //cargar
      cargar_departamentos_selected();

    });

    function redireccionar(url_redireccion) {
      url_click = "inicio.php?mod=<?= $modulo_sistema ?>&acc=" + url_redireccion;
      window.location.href = url_click;
    }

    function cargar_resumen_asistencias() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_indexC.php?datos_generales=true',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#personas').text(data.total_personas);
          $('#departamentos').text(data.total_departamentos);
          $('#feriados').text(data.total_feriados);
          $('#reportes').text(data.total_reportes);
          $('#horarios').text(data.total_horarios);
          $('#turnos').text(data.total_turnos);
          $('#justificaciones').text(data.total_justificaciones);
          if (data.ListaFeriado && data.ListaFeriado.length > 0) {
            let fechaStr = data.ListaFeriado[0].fecha_inicio_feriado;
            let fechaObj = new Date(fechaStr);

            let opciones = {
              day: '2-digit',
              month: 'long',
              year: 'numeric'
            };
            let fechaFormateada = fechaObj.toLocaleDateString('es-ES', opciones);

            $('#titulo_feriado').text(data.ListaFeriado[0].nombre.trim());
            $('#fecha_feriado').text(fechaFormateada);
          } else {
            $('#titulo_feriado').text('No existen feriados');
            $('#fecha_feriado').text('No existen fechas');
          }
          if (data.ListaPersonas && data.ListaPersonas.length > 0) {
            let cantidad = data.ListaPersonas.length;

            // Obtener primeros nombres y apellidos
            let nombres = data.ListaPersonas.map(p => `${p.primer_nombre} ${p.primer_apellido}`).join(', ');

            // Texto dinámico
            let texto = `${cantidad} nuevo${cantidad > 1 ? 's' : ''} ingreso${cantidad > 1 ? 's' : ''} esta semana`;

            // Insertar en HTML
            $('.nuevos-ingresos h6').text('Nuevos Ingresos');
            $('.nuevos-ingresos small').text(texto + `: ${nombres}`);
          } else {
            $('.nuevos-ingresos h6').text('Nuevos Ingresos');
            $('.nuevos-ingresos small').text('Sin registros esta semana');
          }

        }
      });

    }

    function cargar_datos_marcaciones(callback) {
      const estados = ['PENDIENTE', 'APROBADO', 'RECHAZADO'];
      const resultados = {};

      estados.forEach(function(estado) {
        $.ajax({
          url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?listar=true',
          type: 'GET',
          dataType: 'json',
          data: {
            rbx_estado_aprobacion: estado
          },
          success: function(response) {
            resultados[estado] = response.length;

            if (Object.keys(resultados).length === estados.length) {
              callback(resultados); // Devolvemos el objeto con los conteos
            }
          },
          error: function(xhr, status, error) {
            console.error(`Error al cargar datos para ${estado}:`, error);
          }
        });
      });
    }

    function cargar_alertas_asistencia() {
      cargar_datos_marcaciones(function(conteos) {
        let alertas = [{
            mensaje: `${conteos.PENDIENTE} marcaciones pendientes`,
            icono: 'bx-time-five',
            accion: 'th_marcaciones_web'
          },
          {
            mensaje: `${conteos.APROBADO} marcaciones aprobadas`,
            icono: 'bx-check-circle',
            accion: 'th_marcaciones_web'
          },
          {
            mensaje: `${conteos.RECHAZADO} marcaciones rechazadas`,
            icono: 'bx-x-circle',
            accion: 'th_marcaciones_web'
          }
        ];

        let html = `<div class="row g-3">`; // Contenedor
        alertas.forEach(function(alerta) {
          html += `
        <div class="col-md-4">
          <div class="card bg-white text-dark shadow-sm h-100 border">
            <div class="card-body d-flex flex-column">
              <div class="d-flex align-items-center mb-3">
                <i class='bx ${alerta.icono} fs-2 me-2 text-dark'></i>
                <h6 class="card-title mb-0">Alerta</h6>
              </div>
              <p class="card-text flex-grow-1">${alerta.mensaje}</p>
              <button class="btn btn-outline-dark btn-sm mt-auto align-self-start" onclick="redireccionar('${alerta.accion}')">
                <i class='bx bx-right-arrow-alt me-1'></i> Ver más
              </button>
            </div>
          </div>
        </div>
      `;
        });
        html += `</div>`; // Cierra row

        $('#alertas_asistencia').html(html);
      });
    }





    function cargar_estadisticas_departamentos() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_indexC.php?estadisticas_departamentos=true',
        type: 'get',
        dataType: 'json',
        success: function(response) {
          let datos = response || [{
              departamento: 'DOCENTES',
              total: 89,
              presentes: 85,
              ausentes: 4,
              porcentaje_asistencia: 95.5
            },
            {
              departamento: 'ADMINISTRATIVOS',
              total: 32,
              presentes: 28,
              ausentes: 4,
              porcentaje_asistencia: 87.5
            },
            {
              departamento: 'SERVICIOS',
              total: 25,
              presentes: 19,
              ausentes: 6,
              porcentaje_asistencia: 76.0
            }
          ];

          let html = '';
          datos.forEach(function(dept) {
            html += `
              <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                  <div class="card-body">
                    <h6 class="card-title text-primary">${dept.departamento}</h6>
                    <div class="row text-center">
                      <div class="col-4">
                        <h4 class="text-success">${dept.presentes}</h4>
                        <small class="text-muted">Presentes</small>
                      </div>
                      <div class="col-4">
                        <h4 class="text-danger">${dept.ausentes}</h4>
                        <small class="text-muted">Ausentes</small>
                      </div>
                      <div class="col-4">
                        <h4 class="text-info">${dept.porcentaje_asistencia}%</h4>
                        <small class="text-muted">Asistencia</small>
                      </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                      <div class="progress-bar bg-success" style="width: ${dept.porcentaje_asistencia}%"></div>
                    </div>
                  </div>
                </div>
              </div>
            `;
          });
          $('#estadisticas_departamentos').html(html);
        },
        error: function() {
          // HTML de ejemplo en caso de error
          let html = `
            <div class="col-md-4">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <h6 class="card-title text-primary">DOCENTES</h6>
                  <div class="row text-center">
                    <div class="col-4"><h4 class="text-success">85</h4><small class="text-muted">Presentes</small></div>
                    <div class="col-4"><h4 class="text-danger">4</h4><small class="text-muted">Ausentes</small></div>
                    <div class="col-4"><h4 class="text-info">95.5%</h4><small class="text-muted">Asistencia</small></div>
                  </div>
                  <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 95.5%"></div>
                  </div>
                </div>
              </div>
            </div>
          `;
          $('#estadisticas_departamentos').html(html);
        }
      });
    }



    function cargar_empleados_sin_turno() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_indexC.php?empleados_sin_turno=true',
        type: 'get',
        dataType: 'json',
        success: function(response) {
          let empleados = response || [{
              nombre: 'SORIA MALDONADO JUAN FRANCISCO',
              departamento: 'DOCENTES',
              dias_sin_turno: 5
            },
            {
              nombre: 'GARCIA LOPEZ MARIA ELENA',
              departamento: 'ADMINISTRATIVOS',
              dias_sin_turno: 3
            }
          ];

          let html = '';
          if (empleados.length > 0) {
            empleados.forEach(function(emp) {
              html += `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                  <div>
                    <h6 class="mb-0">${emp.nombre}</h6>
                    <small class="text-muted">${emp.departamento}</small>
                  </div>
                  <span class="badge bg-danger">${emp.dias_sin_turno} días</span>
                </div>
              `;
            });
          } else {
            html = '<div class="text-center py-4"><i class="bx bx-check text-success fs-1"></i><p class="text-muted">Todos los empleados tienen turno asignado</p></div>';
          }
          $('#empleados_sin_turno').html(html);
        },
        error: function() {
          $('#empleados_sin_turno').html('<div class="text-center py-4 text-muted">Error al cargar datos</div>');
        }
      });
    }


    function cargar_empleados_ausentes_hoy() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_indexC.php?empleados_ausentes=true',
        type: 'get',
        dataType: 'json',
        success: function(response) {
          let ausentes = response || [{
              nombre: 'RODRIGUEZ PEREZ CARLOS',
              departamento: 'DOCENTES',
              tipo_ausencia: 'Sin justificar'
            },
            {
              nombre: 'MARTINEZ SILVA ANA',
              departamento: 'ADMINISTRATIVOS',
              tipo_ausencia: 'Permiso médico'
            }
          ];

          let html = '';
          ausentes.forEach(function(emp) {
            let badgeClass = emp.tipo_ausencia === 'Sin justificar' ? 'bg-danger' : 'bg-warning';
            html += `
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                  <h6 class="mb-0">${emp.nombre}</h6>
                  <small class="text-muted">${emp.departamento}</small>
                </div>
                <span class="badge ${badgeClass}">${emp.tipo_ausencia}</span>
              </div>
            `;
          });
          $('#empleados_ausentes_lista').html(html);
        },
        error: function() {
          $('#empleados_ausentes_lista').html('<div class="text-center py-4 text-muted">No hay ausentes registrados</div>');
        }
      });
    }

    function cargar_justificaciones_pendientes() {
      $('#justificaciones_pendientes_count').text('4');
      $('#justificaciones_pendientes_lista').html(`
        <div class="d-flex justify-content-between align-items-center py-2">
          <div>
            <small class="text-muted">Retraso - Cita médica</small>
            <h6 class="mb-0">LOPEZ GARCIA PEDRO</h6>
          </div>
          <span class="badge bg-warning">Pendiente</span>
        </div>
      `);
    }

    function cargar_horas_extra_mes() {
      $('#horas_extra_mes').text('248');
      $('#empleados_horas_extra').text('23');
      $('#promedio_horas_extra').text('10.8');
    }
  </script>

  <script>
    let graficoAsistencias; // 🟢 Guardar instancia del chart
    let ctx; // 🟢 Variable para el contexto del canvas

    // 🟢 Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
      // Obtener el contexto del canvas
      ctx = document.getElementById('grafico_asistencias_semanal').getContext('2d');
      // Event listener para el cambio de tipo de asistencia
      document.getElementById('tipoAsistencia').addEventListener('change', function() {
        actualizarGrafico();
      });
      // 🟢 Cargar datos iniciales - SIEMPRE datos generales al comenzar
      control_acceso_datos(); // Forzar carga de datos generales al inicio
    });

    $(document).ready(function() {
      // Cuando cambie el select de departamento, ejecuta la función
      $('#ddl_tipoDepartamento').on('change', function() {
        let valorSeleccionado = $(this).val();
        // 🟢 Pequeño delay para asegurar que el valor se haya actualizado
        setTimeout(function() {
          actualizarGrafico();
        }, 100);
      });

      // 🟢 Configurar el select con una opción por defecto si no existe
      const selectDepartamento = $('#ddl_tipoDepartamento');
      if (selectDepartamento.length > 0) {
        // Si no tiene valor seleccionado, establecer uno por defecto
        if (!selectDepartamento.val()) {
          // Buscar opción de "Todos" o similar
          if (selectDepartamento.find('option[value="TODOS"], option[value="GENERAL"], option[value=""], option[value="all"]').length > 0) {
            selectDepartamento.val(selectDepartamento.find('option[value="TODOS"], option[value="GENERAL"], option[value=""], option[value="all"]').first().val());
          }
        }
      }
    });

    // 🟢 Función unificada para decidir qué datos cargar
    function actualizarGrafico() {
      let departamento = $('#ddl_tipoDepartamento').val();
      // Si no hay departamento seleccionado, es "GENERAL", "TODOS" o valor vacío -> cargar datos generales
      if (!departamento || departamento === 'GENERAL' || departamento === 'TODOS' || departamento === '' || departamento === 'all') {
        control_acceso_datos(); // Cargar datos generales (todos los departamentos)
      } else {
        control_acceso_datos_departamento(); // Cargar datos por departamento específico
      }
    }

    // 🟢 Función reutilizable para cargar el gráfico (modificada para aceptar ambos formatos)
    function cargar_grafico_asistencias_semanal(datosServidor) {
      let datosProcesados;

      // 🟢 Detectar el formato de los datos y normalizarlos
      if (Array.isArray(datosServidor) && datosServidor.length > 0) {
        // Formato de departamento específico: [{departamento: "X", datos_por_fecha: {...}}]
        datosProcesados = datosServidor[0];
      } else if (datosServidor.datos_por_fecha) {
        // Formato general: {departamento: "GENERAL", datos_por_fecha: {...}}
        datosProcesados = datosServidor;
      } else {
        Swal.fire('', 'Error: Formato de datos no válido', 'error');
        return;
      }

      let datosPorFecha = datosProcesados.datos_por_fecha;
      let departamentoActual = datosProcesados.departamento;

      let fechas = Object.keys(datosPorFecha);
      fechas.sort((a, b) => new Date(a) - new Date(b)); // 🟢 Orden cronológico correcto
      let ultimasFechas = fechas.slice(-4); // 🟢 Tomar las últimas 4 fechas

      let dataset1 = [];
      let tipo = document.getElementById('tipoAsistencia').value;

      ultimasFechas.forEach(fecha => {
        let data = datosPorFecha[fecha];
        let valor = 0;

        switch (tipo) {
          case 'asistencias':
            // Asistencias = Total de personas que NO están ausentes
            valor = data.ausente.NO;
            break;

          case 'ausencias':
            // Ausencias = Personas que SÍ están ausentes
            valor = data.ausente.SI;
            break;

          case 'cumple':
            // Cumple jornada = Personas que SÍ cumplen jornada
            valor = data.cumple_jornada.SI;
            break;

          case 'tardanzas':
            // Tardanzas = Personas que NO cumplen jornada
            valor = data.cumple_jornada.NO;
            break;

          case 'atrasos':
            // Atrasos = Personas con salida ausente
            valor = data.salida_ausente.SI;
            break;

          default:
            valor = 0;
        }

        dataset1.push(valor);
      });

      // 🟢 Destruir gráfico anterior si existe
      if (graficoAsistencias) {
        graficoAsistencias.destroy();
      }

      // 🟢 Configurar colores según el tipo
      let backgroundColor = '#007bff';
      let borderColor = '#0056b3';

      switch (tipo) {
        case 'asistencias': // verde suave
          backgroundColor = '#a8e6a1';
          borderColor = '#7ac47a';
          break;
        case 'ausencias': // rojo suave
          backgroundColor = '#f8b4b4';
          borderColor = '#e98989';
          break;
        case 'tardanzas': // amarillo pastel
          backgroundColor = '#ffe8a1';
          borderColor = '#e6c87a';
          break;
        case 'atrasos': // naranja pastel
          backgroundColor = '#ffcc99';
          borderColor = '#e6a96b';
          break;
        case 'cumple': // celeste pastel
          backgroundColor = '#a7d8f0';
          borderColor = '#7ab8d6';
          break;
      }

      // 🟢 Formatear las fechas para mejor visualización
      let fechasFormateadas = ultimasFechas.map(fecha => {
        let fechaObj = new Date(fecha + 'T00:00:00');
        return fechaObj.toLocaleDateString('es-ES', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric'
        });
      });

      graficoAsistencias = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: fechasFormateadas,
          datasets: [{
            label: `${obtenerLabelTipo(tipo)} - ${departamentoActual === 'GENERAL' ? 'TODOS LOS DEPARTAMENTOS' : departamentoActual}`,
            data: dataset1,
            backgroundColor: backgroundColor,
            borderColor: borderColor,
            borderWidth: 2,
            borderRadius: 4,
            borderSkipped: false,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: `${obtenerLabelTipo(tipo)} - ${departamentoActual === 'GENERAL' ? 'TODOS LOS DEPARTAMENTOS' : departamentoActual}`,
              font: {
                size: 14,
                weight: 'bold'
              }
            },
            legend: {
              display: true,
              position: 'top'
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1,
                callback: function(value) {
                  if (Number.isInteger(value)) {
                    return value;
                  }
                }
              },
              title: {
                display: true,
                text: 'Cantidad de Personas'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Fecha (DD/MM/YYYY)'
              }
            }
          },
          animation: {
            duration: 800,
            easing: 'easeInOutQuart'
          }
        }
      });

      // 🟢 Actualizar información en la interfaz si existe
      const departamentoInfo = document.getElementById('departamentoInfo');
      if (departamentoInfo) {
        departamentoInfo.textContent = departamentoActual === 'GENERAL' ? 'TODOS LOS DEPARTAMENTOS' : departamentoActual;
      }
    }

    // 🟢 Función para obtener labels más descriptivos
    function obtenerLabelTipo(tipo) {
      const labels = {
        'asistencias': 'Asistencias',
        'ausencias': 'Ausencias',
        'cumple': 'Cumple Jornada',
        'tardanzas': 'Tardanzas',
        'atrasos': 'Atrasos'
      };
      return labels[tipo] || tipo;
    }

    // 🟢 Función para cargar datos generales del servidor
    function control_acceso_datos() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_indexC.php?control_acceso_datos=true',
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {},
        success: function(response) {

          if (response && response.datos_por_fecha) {
            cargar_grafico_asistencias_semanal(response);
          } else {
            console.error('Estructura de datos generales incorrecta:', response);
            Swal.fire('', 'Error: Estructura de datos generales incorrecta', 'error');
          }
        },
        error: function(xhr, status, error) {
          console.log('Error AJAX datos generales:', error);
          console.log('Estado:', status);
          console.log('Respuesta:', xhr.responseText);
          Swal.fire('', 'Error al cargar datos generales: ' + error, 'error');
        }
      });
    }

    // 🟢 Función para cargar datos por departamento específico
    function control_acceso_datos_departamento() {
      let departamento = $('#ddl_tipoDepartamento').val();

      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_indexC.php?control_acceso_datos_departamento=true',
        type: 'GET',
        dataType: 'json',
        data: {
          departamento: departamento
        },
        beforeSend: function() {},
        success: function(response) {

          if (response && Array.isArray(response) && response.length > 0 && response[0].datos_por_fecha) {
            // 🟢 Reutilizar la misma función de gráfico
            cargar_grafico_asistencias_semanal(response);
          } else {
            console.error('Estructura de datos por departamento incorrecta:', response);
            Swal.fire('', 'Error: No se encontraron datos para el departamento seleccionado', 'error');
          }
        },
        error: function(xhr, status, error) {
          console.log('Error AJAX datos departamento:', error);
          console.log('Estado:', status);
          console.log('Respuesta:', xhr.responseText);
          Swal.fire('', 'Error al cargar datos del departamento: ' + error, 'error');
        }
      });
    }


    function cargar_departamentos_selected() {
      $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?listar=true',
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {},
        success: function(response) {

          // Obtener el select existente
          let select = $('#ddl_tipoDepartamento');
          select.empty(); // Limpiar opciones previas

          // Opción "Todos"
          select.append('<option value="TODOS" selected>TODOS</option>');

          // Agregar departamentos del servidor
          response.forEach(function(departamento) {
            // Suponiendo que el JSON tiene: { id: 1, nombre: 'GENERAL' }
            select.append('<option value="' + departamento.nombre + '">' + departamento.nombre + '</option>');
          });
        },
        error: function(xhr, status, error) {
          console.log('Error AJAX:', error);
          console.log('Estado:', status);
          console.log('Respuesta:', xhr.responseText);
          Swal.fire('', 'Error al cargar datos: ' + error, 'error');
        }
      });
    }
  </script>
<?php } ?>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Control de Asistencias</div>
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

    <div class="container-fluid">
      <!-- Panel de Notificaciones de Asistencia -->
      <?php
      include('index_personas.php');
      ?>

      <?php if (
        $_SESSION['INICIO']['TIPO'] == 'DBA' ||
        $_SESSION['INICIO']['TIPO'] == 'ADMINISTRADOR'
      ) { ?>


        <!-- Resumen General de Asistencias -->
        <!-- Accesos Rápidos a Gestión -->
        <h6 class="mb-0 text-uppercase mt-4">GESTIÓN RÁPIDA</h6>
        <hr>
        <div class="row g-3"> <!-- Espacio entre columnas -->

          <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card radius-10 shadow-card flex-fill" onclick="redireccionar('th_personas');">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <p class="mb-0 text-secondary">Personas</p>
                  <h4 class="my-1" id="personas">0</h4>
                </div>
                <div class="widgets-icons text-white ms-auto" style="background: #28a745;">
                  <i class='bx bx-group'></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card radius-10 shadow-card flex-fill" onclick="redireccionar('th_departamentos');">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <p class="mb-0 text-secondary">Departamentos</p>
                  <h4 class="my-1" id="departamentos">0</h4>
                </div>
                <div class="widgets-icons text-white ms-auto" style="background: #28a745;">
                  <i class='bx bx-buildings'></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card radius-10 shadow-card flex-fill" onclick="redireccionar('th_reportes');">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <p class="mb-0 text-secondary">Reportes</p>
                  <h4 class="my-1" id="reportes">0</h4>
                </div>
                <div class="widgets-icons text-white ms-auto" style="background: #28a745;">
                  <i class='bx bx-line-chart'></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card radius-10 shadow-card flex-fill" onclick="redireccionar('th_turnos');">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <p class="mb-0 text-secondary">Turnos</p>
                  <h4 class="my-1" id="turnos">0</h4>
                </div>
                <div class="widgets-icons text-white ms-auto" style="background: #28a745;">
                  <i class='bx bx-fingerprint'></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card radius-10 shadow-card flex-fill" onclick="redireccionar('th_horarios');">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <p class="mb-0 text-secondary">Horarios</p>
                  <h4 class="my-1" id="horarios">0</h4>
                </div>
                <div class="widgets-icons text-white ms-auto" style="background: #28a745;">
                  <i class='bx bx-time-five'></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card radius-10 shadow-card flex-fill" onclick="redireccionar('th_justificaciones_tipo');">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <p class="mb-0 text-secondary">Justificar</p>
                  <h4 class="my-1" id="justificaciones">0</h4>
                </div>
                <div class="widgets-icons text-white ms-auto" style="background: #28a745;">
                  <i class='bx bx-edit'></i>
                </div>
              </div>
            </div>
          </div>





        </div>





        <!-- Alertas del Sistema -->
        <h6 class="mb-0 text-uppercase">ALERTAS DE MARCACIONES TEMPORALES</h6>
        <hr>
        <div class="row mb-3">
          <div class="col-12" id="alertas_asistencia">
            <!-- Las alertas aparecerán aquí -->
          </div>
        </div>

        <!-- Estadísticas por Departamento y Gráfico -->
        <div class="row">
          <div class="col-xl-12">
            <h6 class="mb-0 text-uppercase">ESTADÍSTICAS</h6>
            <hr>


            <!-- Gráfico de Tendencia Semanal -->

            <div class="card mt-3 shadow-sm">
              <div class="card-header">
                <h6 class="mb-0">
                  <i class="fas fa-chart-bar me-2"></i>
                  Control de asistencias
                </h6>
              </div>
              <div class="card-body">
                <!-- Selector de tipo de asistencia -->
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="tipoAsistencia" class="form-label fw-bold">
                      Tipo de Métrica:
                    </label>
                    <select id="tipoAsistencia" class="form-select" style="width:250px;">
                      <option value="asistencias" selected>📊 Asistencias</option>
                      <option value="ausencias">❌ Ausencias</option>
                      <option value="cumple">✅ Cumple Jornada</option>
                      <option value="tardanzas">⏰ Tardanzas</option>
                      <option value="atrasos">🏃‍♂️ Atrasos (Salida Temprana)</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="label_ddl_tipoDepartamento" class="form-label fw-bold">
                      Tipo de Departamento:
                    </label>
                    <select id="ddl_tipoDepartamento" class="form-select" style="width:250px;">
                      <option value="todos" selected>Todos</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                  <div class="text-muted small">
                    <strong>Departamento:</strong> <span id="departamentoInfo">Todos</span>
                  </div>
                </div>
              </div>

              <!-- Contenedor del gráfico -->
              <div class="position-relative" style="height:350px;">
                <canvas id="grafico_asistencias_semanal"></canvas>
              </div>

              <!-- Información adicional -->

              <div class="row mt-3 px-3 mb-3">
                <div class="col-12">
                  <div class="alert alert-info small mb-0">
                    <strong>💡 Información:</strong>
                    <ul class="mb-0 mt-1">
                      <li><strong>Asistencias:</strong> Personas que NO estuvieron ausentes</li>
                      <li><strong>Ausencias:</strong> Personas que SÍ estuvieron ausentes</li>
                      <li><strong>Cumple Jornada:</strong> Personas que cumplieron su jornada completa</li>
                      <li><strong>Tardanzas:</strong> Personas que NO cumplieron jornada (llegadas tarde)</li>
                      <li><strong>Atrasos:</strong> Personas con salida temprana</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="row mt-3">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h6 class="mb-0">Próximos Eventos</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="d-flex align-items-center border-end">
                      <i class='bx bx-calendar text-primary me-2'></i>
                      <div>
                        <h6 id="titulo_feriado" class="mb-0">Próximo Feriado</h6>
                        <small id="fecha_feriado" class="text-muted">15 de Junio - Día del Padre</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="d-flex align-items-center border-end nuevos-ingresos">
                      <i class='bx bx-user-plus text-success me-2 bx-tada'></i>
                      <div>
                        <h6 class="mb-0">Nuevos Ingresos</h6>
                        <small class="text-muted">Cargando...</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--
          <div class="col-xl-4">
             Empleados Sin Turno Asignado 
            <div class="card">
              <div class="card-header bg-danger text-white">
                <h6 class="mb-0">
                  <i class='bx bx-error-circle me-2'></i>
                  Empleados Sin Turno
                </h6>
              </div>
              <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <div id="empleados_sin_turno">
                  Lista de empleados sin turno 
                </div>
              </div>
            </div>

            Empleados Ausentes Hoy 
            <div class="card mt-3">
              <div class="card-header bg-warning text-white">
                <h6 class="mb-0">
                  <i class='bx bx-user-x me-2'></i>
                  Ausentes Hoy
                </h6>
              </div>
              <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                <div id="empleados_ausentes_lista">
                  Lista de empleados ausentes 
                </div>
              </div>
            </div>
          </div>
        </div>
        -->

        <!-- Información Adicional 
        <div class="row mt-4">
          <div class="col-xl-3 col-lg-6">
            <div class="card border-left-success">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="flex-grow-1">
                    <h6 class="text-success">JUSTIFICADOS HOY</h6>
                    <h4 id="justificados_hoy">0</h4>
                  </div>
                  <i class='bx bx-file-blank text-success fs-1'></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6">
            <div class="card border-left-info">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="flex-grow-1">
                    <h6 class="text-info">SIN MARCAR SALIDA</h6>
                    <h4 id="sin_marcar_salida">0</h4>
                  </div>
                  <i class='bx bx-log-out text-info fs-1'></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6">
            <div class="card border-left-primary">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="flex-grow-1">
                    <h6 class="text-primary">HORAS EXTRA HOY</h6>
                    <h4 id="horas_extra_dia">0</h4>
                  </div>
                  <i class='bx bx-time text-primary fs-1'></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6">
            <div class="card border-left-secondary">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="flex-grow-1">
                    <h6 class="text-secondary">PROMEDIO ASISTENCIA</h6>
                    <h4 id="promedio_asistencia">0%</h4>
                  </div>
                  <i class='bx bx-trending-up text-secondary fs-1'></i>
                </div>
              </div>
            </div>
          </div>
        </div>
-->
        <!-- Panel de Justificaciones y Horas Extra 
         
        <div class="row mt-4">
          <div class="col-xl-6">
            <div class="card">
              <div class="card-header">
                <h6 class="mb-0">
                  <i class='bx bx-file me-2'></i>
                  Justificaciones Pendientes
                  <span class="badge bg-warning ms-2" id="justificaciones_pendientes_count">0</span>
                </h6>
              </div>
              <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <div id="justificaciones_pendientes_lista">
                  Lista de justificaciones pendientes 
                </div>
                <div class="text-center mt-3">
                  <button class="btn btn-outline-primary btn-sm" onclick="redireccionar('justificaciones')">
                    Ver Todas las Justificaciones
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6">
            <div class="card">
              <div class="card-header">
                <h6 class="mb-0">
                  <i class='bx bx-time me-2'></i>
                  Resumen Horas Extra - Este Mes
                </h6>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-4">
                    <h3 class="text-primary" id="horas_extra_mes">0</h3>
                    <small class="text-muted">Total Horas</small>
                  </div>
                  <div class="col-4">
                    <h3 class="text-success" id="empleados_horas_extra">0</h3>
                    <small class="text-muted">Empleados</small>
                  </div>
                  <div class="col-4">
                    <h3 class="text-info" id="promedio_horas_extra">0</h3>
                    <small class="text-muted">Promedio</small>
                  </div>
                </div>
                <div class="text-center mt-3">
                  <button class="btn btn-outline-success btn-sm" onclick="redireccionar('horas_extra')">
                    Ver Reporte Completo
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

-->

      <?php } ?>

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