<?php

$sa_pac_id = '';

if (isset($_GET['pac_id'])) {
  $sa_pac_id = $_GET['pac_id'];
}

?>

<script src="<?= $url_general ?>/js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
  $(document).ready(function() {

    //obtener el id de la ficha por el paciente
    var sa_pac_id = '<?php echo $sa_pac_id; ?>';

    $('input[name="id_paciente"]').val(sa_pac_id);


    cargar_datos_paciente(sa_pac_id);

    //Proceso primero busca el id de la ficha en relacion al paciente
    cargar_datos_consultas(sa_pac_id);

  });

  function cargar_datos_paciente(sa_pac_id) {
    //alert('Estudiante')
    $.ajax({
      data: {
        sa_pac_id: sa_pac_id

      },
      url: '<?= $url_general ?>/controlador/pacientesC.php?obtener_info_paciente=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        ///  Para la tabla de inicio /////////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#txt_ci').html(response[0].sa_pac_temp_cedula + " <i class='bx bxs-id-card'></i>");
        nombres = response[0].sa_pac_temp_primer_nombre + ' ' + response[0].sa_pac_temp_segundo_nombre;
        $('#txt_nombre').html(nombres);
        apellidos = response[0].sa_pac_temp_primer_apellido + ' ' + response[0].sa_pac_temp_segundo_apellido;
        $('#txt_apellido').html(apellidos);

        $('#title_paciente').html(apellidos + " " + nombres);

        $('#tipo_paciente').html(response[0].sa_pac_tabla);


        sexo_paciente = '';
        if (response[0].sa_pac_temp_sexo === 'Masculino') {
          sexo_paciente = "Masculino <i class='bx bx-male'></i>";
        } else if (response[0].sa_pac_temp_sexo === 'Femenino') {
          sexo_paciente = "Famenino <i class='bx bx-female'></i>";
        }
        $('#txt_sexo').html(sexo_paciente);
        $('#txt_fecha_nacimiento').html(fecha_nacimiento_formateada(response[0].sa_pac_temp_fecha_nacimiento.date));
        $('#txt_edad').html(calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento.date) + ' años');
        $('#txt_email').html(response[0].sa_pac_temp_correo + " <i class='bx bx-envelope'></i>");


      }
    });
  }

  function cargar_datos_consultas(id_paciente = '') {
    //alert(id_paciente)
    var consulta = '';
    $.ajax({
      data: {
        id_paciente: id_paciente
      },
      url: '<?php echo $url_general ?>/controlador/pacientesC.php?obtener_idFicha_paciente=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        //Primer ajax
        console.log(response.sa_fice_id);

        $('input[name="id_ficha"]').val(response.sa_fice_id);

        $.ajax({
          data: {
            id_ficha: response.sa_fice_id
          },
          url: '<?php echo $url_general ?>/controlador/consultasC.php?listar_consulta_ficha=true',
          type: 'post',
          dataType: 'json',
          success: function(responseConsultas) {
            console.log(responseConsultas);
            console.log(responseConsultas[0]['sa_conp_fecha_ingreso']);


            //Segundo ajax
            $('#tbl_consultas').DataTable({
              destroy: true, // Destruir la tabla existente antes de recrearla
              data: responseConsultas,
              language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
              },
              responsive: true, // Datos de las consultas médicas
              columns: [
                // Definir las columnas
                {
                  data: null,
                  render: function(data, type, item) {
                    return '<div class="text-center"><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_consulta_paciente&id_consulta=' + item.sa_conp_id + '&tipo_consulta=' + item.sa_conp_tipo_consulta + '&id_ficha=' + item.sa_fice_id + '&id_paciente=' + item.sa_pac_id + '" class="btn btn-primary btn-sm " title="Detalles de la Consulta"><i class="bx bx-spreadsheet me-0"></i></a></div>';
                  }
                },
                {
                  data: null,
                  render: function(data, type, item) {
                    if (item.sa_conp_desde_hora.date == null || item.sa_conp_fecha_ingreso.date == null) {
                      return '';
                    } else {
                      //Fecha de creacion para saber el dia en el que se creo
                      return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion.date) + ' / ' + obtener_hora_formateada(item.sa_conp_fecha_creacion.date);
                    }
                  }
                },
                {
                  data: null,
                  render: function(data, type, item) {
                    if (item.sa_conp_desde_hora.date == null || item.sa_conp_hasta_hora.date == null) {
                      return '';
                    } else {
                      return fecha_nacimiento_formateada(item.sa_conp_fecha_ingreso.date) + ' / [' + obtener_hora_formateada(item.sa_conp_desde_hora.date) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora.date)+ ']';
                    }
                  }
                },
                {
                  data: 'sa_conp_permiso_salida',
                },
                {
                  data: null,
                  render: function(data, type, item) {
                    if (item.sa_conp_tipo_consulta == 'consulta') {
                      return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
                    } else {
                      return '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
                    }
                  }
                },
                {
                  data: null,
                  render: function(data, type, item) {
                    if (item.sa_conp_estado_revision == 0) {
                      return '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' + 'Creado' + '</div>';
                    } else if (item.sa_conp_estado_revision == 1) {
                      return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + 'Finalizado' + '</div>';
                    } else if (item.sa_conp_estado_revision == 2) {
                      return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">' + 'En Proceso' + '</div>';
                    }
                  }
                },
              ],
              order: [
                [1, 'desc'] // Ordenar por la segunda columna (índice 1) en orden ascendente
              ]
            });

          }
        });
      }
    });
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
            <li class="breadcrumb-item active" aria-current="page">Historial de Consultas del Paciente</li>
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
              <h5 class="mb-0 text-primary">Historial de Consultas del Paciente: <b id="title_paciente" class="text-success"></b></h5>

              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=pacientes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>
            <hr>

            <div class="content">
              <!-- Content Header (Page header) -->
              <br>

              <section class="content">
                <div class="container-fluid">

                  <div class="row justify-content-center" id="btn_nuevo">

                    <div class="col-auto">

                      <div class="card">
                        <div class="card-body bg-dark">
                          <form action="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_consulta_paciente" method="post">

                            <input type="hidden" name="id_ficha" id="id_ficha">
                            <input type="hidden" name="id_paciente" id="id_paciente">
                            <input type="hidden" name="tipo_consulta" id="tipo_consulta" value="consulta">

                            <button type="submit" class="btn btn-primary btn-lg m-4"><i class='bx bx-file-blank'></i> Consulta&nbsp;&nbsp;&nbsp;</button>
                          </form>
                        </div>
                      </div>

                    </div>

                    <div class="col-auto">

                      <div class="card">
                        <div class="card-body bg-dark">
                          <form action="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_consulta_paciente" method="post">

                            <input type="hidden" name="id_ficha" id="id_ficha">
                            <input type="hidden" name="id_paciente" id="id_paciente">
                            <input type="hidden" name="tipo_consulta" id="tipo_consulta" value="certificado">

                            <button type="submit" class="btn btn-primary btn-lg m-4"><i class='bx bx-file-blank'></i> Certificado</button>
                          </form>
                        </div>
                      </div>

                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="table-responsive">
                      <table class="table table-striped responsive" id="tbl_consultas" style="width:100%">
                        <thead>
                          <tr>
                            <th width="10px">Revisar</th>
                            <th>Fecha de creación</th>
                            <th>Fecha Agenda / Hora Desde/Hasta</th>
                            <th>Permiso de Salida</th>
                            <th>Tipo de Atención</th>
                            <th width="10px">Estado</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
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