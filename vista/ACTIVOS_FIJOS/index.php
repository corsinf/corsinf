<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$redireccionar_vista = 'index';

//Para obtener el id de la persona que solicita la firma (No concurrente)
// $_id = isset($_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : null;

// if (empty($_id)) {
//     $_id = '';
// }

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
  function redireccionar(url_redireccion) {
    url_click = "inicio.php?mod=<?= $modulo_sistema ?>&acc=" + url_redireccion;
    window.location.href = url_click;
  }
</script>

<?php if (
  $_SESSION['INICIO']['TIPO'] == 'DBA' ||
  $_SESSION['INICIO']['TIPO'] == 'ADMINISTRADOR'
) { ?>

  <script>
    $(document).ready(function() {
      listar_articulos_tipo();
      contar_custodios();
      contar_localizacion();
    });


    function listar_articulos_tipo() {
      $.ajax({
        url: '../controlador/ACTIVOS_FIJOS/indexC.php?lista_articulos_tipo=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          pie_articulos(response);

          let total_articulos = 0;

          response.forEach(function(item) {
            let total = parseInt(item.TOTAL_ARTICULOS) || 0;
            total_articulos += total;
          });

          let html = `
                      <div class="col-6 col-sm-6 col-md-4" id="pnl_solicitudes" onclick="redireccionar('articulos_cr');">
                        <div class="card radius-10 shadow-card">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">TOTAL</p>
                                <h4 class="my-1" id="lbl_pacientes">${total_articulos}</h4>
                              </div>
                              <div class="widgets-icons text-white ms-auto" style="background: #204697;">
                                <i class='bx bx-purchase-tag'></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    `;

          $('#pnl_articulos_tipo').empty();


          // Recorrer los elementos de la respuesta
          response.forEach(function(item) {
            html += `
                    <div class="col-6 col-sm-6 col-md-4" id="pnl_solicitudes" onclick="redireccionar('articulos_cr');">
                      <div class="card radius-10 shadow-card">
                        <div class="card-body">
                          <div class="d-flex align-items-center">
                            <div>
                              <p class="mb-0 text-secondary">${item.DESCRIPCION}</p>
                              <h4 class="my-1" id="lbl_pacientes">${item.TOTAL_ARTICULOS}</h4>
                            </div>
                            <div class="widgets-icons text-primary ms-auto" style="background: ${item.COLOR};">
                              <i class='bx bx-purchase-tag'></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  `;
          });
          $('#pnl_articulos_tipo').append(html);
        },
        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });

    }

    function contar_custodios() {
      $.ajax({
        url: '../controlador/ACTIVOS_FIJOS/indexC.php?contar_custodios=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          $('#lbl_count_custodios').html(response[0].TOTAL_CUSTODIOS);
        },

        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });
    }

    function contar_localizacion() {
      $.ajax({
        url: '../controlador/ACTIVOS_FIJOS/indexC.php?contar_localizacion=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          $('#lbl_count_localizacion').html(response[0].TOTAL_LOCALIZACION);
        },

        error: function(xhr, status, error) {
          console.log('Status: ' + status);
          console.log('Error: ' + error);
          console.log('XHR Response: ' + xhr.responseText);
          Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
      });

    }

    function pie_articulos(response) {
      let labels = [];
      let data = [];
      let backgroundColor = [];

      response.forEach(function(item) {
        if (item.DESCRIPCION !== 'TOTAL') {
          labels.push(item.DESCRIPCION);
          data.push(parseInt(item.TOTAL_ARTICULOS) || 0);
          backgroundColor.push(item.COLOR || '#cccccc'); // Color por defecto si falta
        }
      });

      let donutData = {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: backgroundColor,
        }]
      };

      let pieChartCanvas = $('#pieChart').get(0).getContext('2d');
      let pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
      };

      new Chart(pieChartCanvas, {
        type: 'pie',
        data: donutData,
        options: pieOptions
      });
    }
  </script>

<?php } ?>

</style>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Inicio</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"></li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
      <div class="col-xl-12 mx-auto">

        <?php if (
          $_SESSION['INICIO']['TIPO'] == 'DBA' ||
          $_SESSION['INICIO']['TIPO'] == 'NO CONCURRENTE'
        ) { ?>

          <h6 class="mb-0 text-uppercase">DASHBOARD</h6>
          <hr>

          <div class="row">
            <div class="col-4">
              <div class="card card-danger">
                <div class="card-body">
                  <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
            <div class="col-8">
              <div class="row" id="pnl_articulos_tipo">

              </div>
            </div>
          </div>

          <h6 class="mb-0 text-uppercase">ADICIONAL</h6>
          <hr>

          <div class="row">
            <div class="col-6 col-sm-6 col-md-4" id="pnl_solicitudes" onclick="redireccionar('ge_personas');">
              <div class="card radius-10 shadow-card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div>
                      <p class="mb-0 text-secondary">CUSTODIOS</p>
                      <h4 class="my-1" id="lbl_count_custodios">0</h4>
                    </div>
                    <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-user'></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-6 col-sm-6 col-md-4" id="pnl_solicitudes" onclick="redireccionar('localizacion');">
              <div class="card radius-10 shadow-card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div>
                      <p class="mb-0 text-secondary">LOCALIZACIÓN</p>
                      <h4 class="my-1" id="lbl_count_localizacion">0</h4>
                    </div>
                    <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-current-location'></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-6 col-sm-6 col-md-4" id="pnl_solicitudes" onclick="redireccionar('parametros_art');">
              <div class="card radius-10 shadow-card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div>
                      <p class="mb-0 text-secondary">PARÁMETROS</p>
                      <h4 class="my-1" id="">-</h4>
                    </div>
                    <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-spreadsheet'></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>



        <?php } ?>

      </div>
    </div>
  </div>
</div>



<!-- Estilos para redireccionar -->
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
    transition: background-color 0.3s, box-shadow 0.3s;
  }

  .card.hoverEffect {
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
    background-color: rgba(45, 216, 34, 0.1);
  }

  .card.clickedEffect {
    background-color: rgba(128, 224, 122, 0.5);
  }
</style>

<script>

</script>