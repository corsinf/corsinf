<?php

$id_estudiante = '';
$id_representante = '';
$id_ficha = '';



if (isset($_GET['id_estudiante'])) {
  $id_estudiante = $_GET['id_estudiante'];
}

if (isset($_GET['id_representante'])) {
  $id_representante = $_GET['id_representante'];
}

if (isset($_GET['id_ficha'])) {
  $id_ficha = $_GET['id_ficha'];
}


?>

<script type="text/javascript">
  $(document).ready(function() {

    var id_estudiante = '<?php echo $id_estudiante; ?>';
    var id_representante = '<?php echo $id_representante; ?>';
    var id_ficha = '<?php echo $id_ficha; ?>';

    if (id_ficha != '') {
      consultar_datos(id_ficha);
    }
  });

  function fecha_formateada(fecha) {
    fechaYHora = fecha;
    fecha = new Date(fechaYHora);
    año = fecha.getFullYear();
    mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Añade un 0 si es necesario
    dia = fecha.getDate().toString().padStart(2, '0'); // Añade un 0 si es necesario
    fechaFormateada = `${año}-${mes}-${dia}`;

    var salida = '';
    salida = fechaFormateada;

    return salida;

  }

  function obtener_hora_formateada(hora) {
    var fechaActual = new Date(hora);
    var hora = fechaActual.getHours();
    var minutos = fechaActual.getMinutes();
    var segundos = fechaActual.getSeconds();

    // Formatear la hora como una cadena
    var horaFormateada = (hora < 10 ? '0' : '') + hora + ':' +
      (minutos < 10 ? '0' : '') + minutos;

    return horaFormateada;
  }

  function consultar_datos(id_estudiante = '') {
    var consulta = '';
    var cont = 1;
    $.ajax({
      data: {
        id: id_estudiante
      },
      url: '<?php echo $url_general ?>/controlador/consultasC.php?listar=true',
      type: 'post',
      dataType: 'json',
      //Para el id representante tomar los datos con los de session
      success: function(response) {
        console.log(response);
        $.each(response, function(i, item) {
          //console.log(response);

          consulta +=
            '<tr>' +
            '<td>' + cont + '</td>' +
            '<td>' + fecha_formateada(item.sa_conp_fecha_ingreso.date) + '</td>' +
            '<td>' + obtener_hora_formateada(item.sa_conp_desde_hora.date) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora.date) + '</td>' +
            '<td><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_consulta_estudiante&id_ficha=' + item.sa_fice_id + '&id_estudiante=' + <?= $id_estudiante ?> + '&id_representante=' + <?= $id_representante ?> + '&id_consulta=' + item.sa_conp_id + '"><u>' + item.sa_conp_nombres + '</u></a></td>' +
            '<td>' + item.sa_conp_tipo_consulta + '</td>' +
            '<td><a class="btn btn-primary btn-sm"  title="Enviar Mensaje" href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=mensaje_atencion&id_ficha=' + item.sa_fice_id + '&id_estudiante=' + <?= $id_estudiante ?> + '&id_representante=' + <?= $id_representante ?> + '&id_consulta=' + item.sa_conp_id + '">' + '<i class="bx bx-mail-send"></i>' + '</a></td>' +
            '</tr>';

          cont++;
        });

        $('#tbl_datos').html(consulta);
      }
    });
  }

  function buscar(buscar) {
    var consulta = '';
    var cont = 1;
    $.ajax({
      data: {
        buscar: buscar
      },
      url: '<?= $url_general ?>/controlador/consultasC.php?buscar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          //console.log(item);

          consulta +=
            '<tr>' +
            '<td>' + cont + '</td>' +
            '<td>' + fecha_formateada(item.sa_conp_fecha_ingreso.date) + '</td>' +
            '<td>' + obtener_hora_formateada(item.sa_conp_desde_hora.date) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora.date) + '</td>' +
            '<td><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_consulta_estudiante&id_ficha=' + item.sa_fice_id + '&id_estudiante=' + <?= $id_estudiante ?> + '&id_representante=' + <?= $id_representante ?> + '&id_consulta=' + item.sa_conp_id + '"><u>' + item.sa_conp_nombres + '</u></a></td>' +
            '<td>' + item.sa_conp_tipo_consulta + '</td>' +
            '<td><a class="btn btn-primary btn-sm"  title="Enviar Mensaje" href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=mensaje_atencion&id_ficha=' + item.sa_fice_id + '&id_estudiante=' + <?= $id_estudiante ?> + '&id_representante=' + <?= $id_representante ?> + '&id_consulta=' + item.sa_conp_id + '">' + '<i class="bx bx-mail-send"></i>' + '</a></td>' +
            '</tr>';

          cont++;
        });

        $('#tbl_datos').html(consulta);
      }

    });
  }

  function limpiar() {
    $('#codigo').val('');
    $('#descripcion').val('');
    $('#id').val('');
    $('#titulo').text('Nueva Sección');
    $('#op').text('Guardar');
  }
</script>

<div class="page-wrapper">
  <div class="page-content">

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Enfermería </div>
      <?php
      // print_r($_SESSION['INICIO']);die();

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Consultas del Estudiante</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
          <div class="card-body p-5">
            <div class="card-title d-flex align-items-center">
              <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
              </div>
              <h5 class="mb-0 text-primary">Consultas del Estudiante</h5>

              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=ficha_estudiante&id_estudiante=<?= $id_estudiante ?>&id_representante=<?= $id_representante ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>
            <hr>

            <div class="content">
              <!-- Content Header (Page header) -->
              <br>

              <section class="content">
                <div class="container-fluid">

                  <div class="row">
                    <div class="col-sm-12" id="btn_nuevo">
                      <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_consulta_estudiante&id_estudiante=<?= $id_estudiante ?>&id_representante=<?= $id_representante ?>&id_ficha=<?= $id_ficha ?>" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                      <a href="#" class="btn btn-outline-secondary btn-sm" id="excel_estudiantes" title="Informe en excel del total de Consultas del Estudiante"><i class="bx bx-file"></i> Total Consultas del Estudiante</a>
                    </div>
                  </div>

                  <div>
                    <div class="col-sm-8 pt-3">
                      <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar Consultas del Estudiante">
                    </div>
                  </div>
                  <br>

                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Fecha de creación</th>
                          <th>Hora Desde/Hasta</th>
                          <th>Estudiante</th>
                          <th>Tipo de Atención</th>
                          <th>Acción</th>
                        </tr>
                      </thead>
                      <tbody id="tbl_datos">

                      </tbody>
                    </table>
                  </div>
                </div><!-- /.container-fluid -->
              </section>
              <!-- /.content -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>