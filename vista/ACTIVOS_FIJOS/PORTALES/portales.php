<script type="text/javascript">
  $(document).ready(function() {
    cargar_datos();
    cargar_datos_controladora();
  })

  let intervalo;
  let intervalo2;

  function cargar_datos_controladora() {

    $.ajax({
      // data: {parametros: parametros},
      url: '../controlador/ACTIVOS_FIJOS/PORTALES/portalesC.php?lista=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        tr = '';
        response.forEach(function(item, i) {
          tr += `<option value="` + item.id + `">` + item.nombre + `</option>`
        })
        $('#dll_conroladoras').html(tr);
      }

    });
  }


  function comenzar_lectura() {
    controladora = $('#dll_conroladoras').val();
    parametros = {
      'id': controladora,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/PORTALES/portalesC.php?comenzar_lectura_log=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response.resp == '-1') {
          Swal.fire("No se pudo conectar", response.msj, 'error').then(function() {
            $('#modal_respuesta').modal('hide');
            detener();
          })

        }
      }
    });
  }

  function iniciar() {
    $('#dll_conroladoras').prop('disabled', true);
    $('#btn_iniciar').addClass('d-none');
    $('#btn_detener').removeClass('d-none');


    intervalo = setInterval(comenzar_lectura, 2000);
    intervalo2 = setInterval(cargar_datos, 2500);
    console.log("Ejecución iniciada.");
  }

  function detener() {
    clearInterval(intervalo);
    if (intervalo) { // Verificar si el intervalo está activo
      clearInterval(intervalo);
      clearInterval(intervalo2);
      intervalo = null; // Limpiar la variable
      intervalo2 = null;
      Swal.fire("Deteccion Detenida", "Podria existir un tiempo de latencia", "info")

      $('#dll_conroladoras').prop('disabled', false);
      $('#btn_iniciar').removeClass('d-none');
      $('#btn_detener').addClass('d-none');
    }
  }

  function cargar_datos() {

    $.ajax({
      // data: {parametros: parametros},
      url: '../controlador/ACTIVOS_FIJOS/PORTALES/portalesC.php?lista_log=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        console.log(response)
        tr = '';
        response.forEach(function(item, i) {
          color = '';
          if (item.descripcion != '' && item.descripcion != null) {
            color = 'style="background: #E0F2DF;"';
          }
          tr += `<tr ` + color + `>
              <td>` + (i + 1) + `</td>
              <td>` + item.rfid + `</td>
              <td>` + item.fecha + `</td>
              <td>` + item.antena + `</td>
              <td>` + item.controladora + `</td>
              <td>` + item.descripcion + `</td>
            </tr>`
        })
        $('#tbl_body').html(tr);
      }

    });
  }
</script>

<!-- Simulador para portales -->
<script>
  let intervalo_simulador;
  let intervalo2_simulador;

  function iniciar_simulador() {
    $('#dll_conroladoras').prop('disabled', true);
    $('#btn_iniciar_simulador').addClass('d-none');
    $('#btn_detener_simulador').removeClass('d-none');

    intervalo_simulador = setInterval(comenzar_lectura_simulador, 2000);
    intervalo2_simulador = setInterval(cargar_datos, 2500);
    console.log("Ejecución iniciada.");
  }

  function comenzar_lectura_simulador() {
    $.ajax({
      url: '../controlador/ACTIVOS_FIJOS/PORTALES/portalesC.php?simulador_portal=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response.resp == '-1') {
          Swal.fire("No se pudo conectar", response.msj, 'error').then(function() {
            $('#modal_respuesta').modal('hide');
            detener();
          })

        }
      }
    });
  }

  function detener_simulador() {
    clearInterval(intervalo_simulador);
    if (intervalo_simulador) { // Verificar si el intervalo está activo
      clearInterval(intervalo_simulador);
      clearInterval(intervalo2_simulador);
      intervalo_simulador = null; // Limpiar la variable
      intervalo2_simulador = null;
      Swal.fire("Deteccion Detenida", "Podria existir un tiempo de latencia", "info")

      $('#dll_conroladoras').prop('disabled', false);
      $('#btn_iniciar_simulador').removeClass('d-none');
      $('#btn_detener_simulador').addClass('d-none');
    }
  }
</script>


<div class="page-wrapper">
  <div class="page-content">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Portales</div>
      <?php
      // print_r($_SESSION['INICIO']);die();
      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Portales</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
      <div class="card">
        <div class="card-body">
          <div class="row">

            <div class="col-12 mb-3">
              <div class="card bg-light border shadow-sm">
                <div class="card-body">
                  <!-- Aviso simulador -->
                  <div class="alert alert-warning d-flex align-items-center mb-3 py-2" role="alert">
                    <i class="bx bx-info-circle me-2 fs-5"></i>
                    <div><b>Simulador</b> (solo para demos)</div>
                  </div>

                  <!-- Controles simulador -->
                  <div class="d-flex gap-2">
                    <button class="btn btn-success" onclick="iniciar_simulador()" id="btn_iniciar_simulador">
                      <i class="bx bx-play me-1"></i> Iniciar
                    </button>
                    <button class="btn btn-danger d-none" onclick="detener_simulador()" id="btn_detener_simulador">
                      <i class="bx bx-stop me-1"></i> Detener
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Controles -->
            <div class="col-12 mb-3">
              <div class="input-group">
                <select class="form-select" id="dll_conroladoras" onchange="detener()"></select>
                <button class="btn btn-primary" onclick="iniciar()" id="btn_iniciar">
                  <i class="bx bx-play"></i>
                </button>
                <button class="btn btn-danger d-none" onclick="detener()" id="btn_detener">
                  <i class="bx bx-stop"></i>
                </button>
              </div>
            </div>

            <!-- Lista -->
            <div class="col-12">
              <b>Lista de Etiquetas detectadas</b>
              <div class="table-responsive mt-2">
                <table class="table table-hover">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>RFID</th>
                      <th>Fecha</th>
                      <th>Antena</th>
                      <th>Controladora</th>
                      <th>Artículos</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_body">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>