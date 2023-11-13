<?php

$dominio = $_SERVER['SERVER_NAME'];
$url_general = 'http://' . $dominio . '/corsinf';


$id_estudiante = '';
$id_representante = '';

if (isset($_GET['id_estudiante'])) {
  $id_estudiante = $_GET['id_representante'];
}

if (isset($_GET['id_representante'])) {
  $id_estudiante = $_GET['id_estudiante'];
}


?>

<script type="text/javascript">
  $(document).ready(function() {

    var id_estudiante = '<?php echo $id_estudiante; ?>';
    var id_representante = '<?php echo $id_representante; ?>';

    if (id_estudiante != '') {
      consultar_datos(id_estudiante);
    }
  });

  function consultar_datos(id_estudiante = '') {
    var ficha_estudiante = '';
    var cont = 1;
    $.ajax({
      data: {
        id: id_estudiante
      },
      url: '<?php echo $url_general ?>/controlador/fichas_EstudianteC.php?listar=true',
      type: 'post',
      dataType: 'json',
      //Para el id representante tomar los datos con los de session
      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          console.log(item);

          ficha_estudiante +=
            '<tr>' +
            '<td>' + cont + '</td>' +
            '<td>' + item.sa_fice_fecha_creacion.date + '</td>' +
            '<td><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_ficha_estudiante&id_ficha=' + item.sa_fice_id + '&id_estudiante=' + item.sa_fice_est_id + '&id_representante=' + item.sa_fice_rep_1_id + '"><u>' + item.sa_fice_est_primer_apellido + ' ' + item.sa_fice_est_segundo_apellido + ' ' + item.sa_fice_est_primer_nombre + ' ' + item.sa_fice_est_segundo_nombre + '</u></a></td>' +
            '<td>' + 'N' + '</td>' +
            '<td><a  class="btn btn-dark btn-sm" title="Ficha de Estudiante" href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=ficha_estudiante&id_estudiante=' + item.sa_fice_est_id + '&id_representante=' + item.sa_fice_rep_1_id + '">' + '<i class="bx bx-file-blank me-0" ></i>' + '</a></td>' +
            '</tr>';
            cont++;
        });

        $('#tbl_datos').html(ficha_estudiante);
      }
    });
  }

  function buscar(buscar) {
    var estudiantes = '';

    $.ajax({
      data: {
        buscar: buscar
      },
      url: '<?= $url_general ?>/controlador/fichas_EstudianteC.php?buscar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          console.log(item);

          estudiantes +=
            '<tr>' +
            '<td>' + item.sa_est_cedula + '</td>' +
            '<td><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_estudiantes&id=' + item.sa_est_id + '&id_seccion=' + item.sa_id_seccion + '&id_grado=' + item.sa_id_grado + '&id_paralelo=' + item.sa_id_paralelo + '"><u>' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</u></a></td>' +
            '<td>' + item.sa_sec_nombre + ' / ' + item.sa_gra_nombre + ' / ' + item.sa_par_nombre + '</td>' +
            '<td>' + edad_fecha_nacimiento(item.sa_est_fecha_nacimiento.date) + '</td>' +
            '</tr>';
        });

        $('#tbl_datos').html(estudiantes);
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
            <li class="breadcrumb-item active" aria-current="page">Fichas del Estudiante</li>
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
              <h5 class="mb-0 text-primary">Fichas del Estudiante</h5>

              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=estudiantes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
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
                      <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_ficha_estudiante" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                      <a href="#" class="btn btn-outline-secondary btn-sm" id="excel_estudiantes" title="Informe en excel del total de Fichas del Estudiante"><i class="bx bx-file"></i> Total Fichas del Estudiante</a>
                    </div>

                  </div>

                  <div>
                    <div class="col-sm-8 pt-3">
                      <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar Fichas del Estudiante">
                    </div>
                  </div>
                  <br>

                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Fecha de creación</th>
                          <th>Estudiante</th>
                          <th>Atenciones</th>
                          <th>Consultas</th>
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