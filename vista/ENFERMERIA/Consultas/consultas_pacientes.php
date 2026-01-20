<?php

$sa_pac_id = '';

if (isset($_GET['pac_id'])) {
  $sa_pac_id = $_GET['pac_id'];
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

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
    // Mostrar el spinner usando SweetAlert2
    Swal.fire({
      title: 'Por favor, espere',
      text: 'Procesando la solicitud...',
      allowOutsideClick: false,
      onOpen: () => {
        Swal.showLoading();
      }
    });

    //alert('Estudiante')
    $.ajax({
      data: {
        sa_pac_id: sa_pac_id

      },
      url: '../controlador/SALUD_INTEGRAL/pacientesC.php?obtener_info_paciente=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        Swal.close();

        //Para el encabezado
        nombres = response[0].sa_pac_temp_primer_nombre + ' ' + response[0].sa_pac_temp_segundo_nombre;
        apellidos = response[0].sa_pac_temp_primer_apellido + ' ' + response[0].sa_pac_temp_segundo_apellido;
        tabla = response[0].sa_pac_tabla;

        if (tabla == 'estudiantes') {
          $('#pnl_segumiento_personal').hide();
        } else {
          $('#pnl_segumiento_personal').show();

          $('#pnl_seguimiento_li').show();
          $('#pnl_seguimiento').show();

          cargar_datos_seguimiento(sa_pac_id)
        }

        //$('#title_paciente').html(apellidos + " " + nombres);
        $('b[name="title_paciente"]').html(apellidos + " " + nombres);

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
      url: '../controlador/SALUD_INTEGRAL/pacientesC.php?obtener_idFicha_paciente=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        //Primer ajax
        //console.log(response.sa_fice_id);

        $('input[name="id_ficha"]').val(response.sa_fice_id);

        $.ajax({
          data: {
            id_ficha: response.sa_fice_id
          },
          url: '../controlador/SALUD_INTEGRAL/consultasC.php?listar_consulta_ficha=true',
          type: 'post',
          dataType: 'json',
          success: function(responseConsultas) {
            //console.log(responseConsultas);
            //console.log(responseConsultas[0]['sa_conp_fecha_ingreso']);

            //Segundo ajax
            $('#tbl_consultas').DataTable({
              destroy: true, // Destruir la tabla existente antes de recrearla
              data: responseConsultas,
              language: {
                url: '../assets/plugins/datatable/spanish.json'
              },
              responsive: true, // Datos de las consultas médicas
              columns: [
                // Definir las columnas
                {
                  data: null,
                  render: function(data, type, item) {

                    botones = '';
                    botones += '<div class="d-inline">';

                    //botones += '<button type="button" class="btn btn-primary btn-sm m-1" title="Detalles de la Consulta" onclick="ver_pdf(' + item.sa_conp_id + ')"> <i class="bx bx-file me-0"></i></button>';

                    botones += '<button type="button" class="btn btn-primary btn-sm m-1" title="Detalles de la Consulta" onclick="ver_pdf(' + item.sa_conp_id + ', \'' + item.sa_conp_tipo_consulta + '\')"> <i class="bx bx-file me-0"></i></button>';



                    if (item.sa_conp_estado_revision == 0 || item.sa_conp_estado_revision == 2) {
                      //botones += '<a href="../vista/inicio.php?mod=7&acc=registrar_consulta_paciente&id_consulta=' + item.sa_conp_id + '&tipo_consulta=' + item.sa_conp_tipo_consulta + '&id_ficha=' + item.sa_fice_id + '&id_paciente=' + item.sa_pac_id + '" class="btn btn-warning btn-sm m-0" title="Detalles de la Consulta"><i class="bx bx-edit me-0" ></i></a>';
                    }

                    botones += '</div>';

                    return botones;


                  }
                },
                {
                  data: null,
                  render: function(data, type, item) {
                    if (item.sa_conp_desde_hora == null || item.sa_conp_fecha_ingreso == null) {
                      return '';
                    } else {
                      //Fecha de creacion para saber el dia en el que se creo
                      return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion) + ' / ' + obtener_hora_formateada_arr(item.sa_conp_fecha_creacion);
                    }
                  }
                },
                {
                  data: null,
                  render: function(data, type, item) {
                    if (item.sa_conp_desde_hora == null || item.sa_conp_hasta_hora == null) {
                      return '';
                    } else {
                      return (item.sa_conp_fecha_ingreso) + ' / ' + obtener_hora_formateada(item.sa_conp_desde_hora) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora);
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
                      return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + 'Atención médica' + '</div>';
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

  function ver_pdf(id_consulta, tipo_consulta) {
    //console.log(id_consulta);
    window.open('../vista/inicio.php?mod=7&acc=detalle_consulta&pdf_consulta=true&id_consulta=' + id_consulta + '&id_paciente=' + <?= $sa_pac_id; ?> + '&btn_regresar=admin' + '&tipo_consulta=' + tipo_consulta, '_blank');
  }

  function seguimiento() {
    var sa_pac_id = <?= ($sa_pac_id); ?>;
    var url = '../vista/inicio.php?mod=7&acc=seguimientos_personal&id_paciente=' + sa_pac_id + '&btn_regresar=admin';
    window.location.href = url;
  }

  function cargar_datos_seguimiento(id_paciente) {
    // Hacer una llamada AJAX para obtener los datos de seguimiento
    $.ajax({
      url: '../controlador/SALUD_INTEGRAL/seguimiento_personalC.php?listar_seguimiento=true',
      type: 'post',
      data: {
        id: id_paciente
      },
      dataType: 'json',
      success: function(response) {
        // Inicializar DataTable con los datos recibidos
        //console.log(response)

        $('#tbl_seguimiento').DataTable({
          destroy: true, // Destruir la tabla existente antes de recrearla
          data: response, // Usar la respuesta del AJAX como datos
          language: {
            url: '../assets/plugins/datatable/spanish.json'
          },
          responsive: true, // Datos de las consultas médicas
          columns: [
            // Definir las columnas
            {
              data: null,
              render: function(data, type, item) {
                if (item.sa_sep_fecha_creacion == null) {
                  return '';
                } else {
                  return fecha_nacimiento_formateada(item.sa_sep_fecha_creacion) + ' / ' + obtener_hora_formateada_arr(item.sa_sep_fecha_creacion);
                }
              }
            },
            {
              data: 'sa_sep_observacion',
            },
          ],
          order: [
            [0, 'desc'] // Ordenar por la segunda columna (índice 1) en orden descendente
          ]
        });

      },
      error: function() {
        Swal.fire('Error', 'No se pudieron cargar los datos de seguimiento', 'error');
      }
    });
  }

  function modal_datos_adicionales_agregar() {
    $('#modal_datos_adicionales_agregar').modal('show');
  }

  function insertar_datos_adicionales() {
    var sa_pac_id = <?= ($sa_pac_id); ?>;
    var sa_pacda_peso = $('#sa_pacda_peso').val();
    var sa_pacda_altura = $('#sa_pacda_altura').val();

    var parametros = {
      'sa_pac_id': sa_pac_id,
      'sa_pacda_peso': sa_pacda_peso,
      'sa_pacda_altura': sa_pacda_altura,
    };

    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/SALUD_INTEGRAL/paciente_datos_adicionalesC.php?insertar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success');

          $('#modal_datos_adicionales_agregar').modal('hide');
          $('#sa_pacda_peso').val('');
          $('#sa_pacda_altura').val('');

        } else if (response == -2) {
          Swal.fire('', 'Error', 'error');
        }
      }
    });
  }

  function modal_datos_adicionales_ver() {
    $('#modal_datos_adicionales_ver').modal('show');

    id_paciente = '<?= $sa_pac_id ?>';

    $.ajax({
      data: {
        id: id_paciente
      },
      url: '../controlador/SALUD_INTEGRAL/paciente_datos_adicionalesC.php?listar_paciente=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response && response.length > 0) {
          let salida = '';

          response.forEach(function(item) {
            salida +=
              `<tr>
                <th>${fecha_nacimiento_formateada(item.sa_pacda_fecha_creacion)}</th>
                <td>${item.sa_pacda_peso}</td>
                <td>${item.sa_pacda_altura}</td>
              </tr>`;
          });

          $('#tbl_datos_adicionales').html(salida);

        } else {
          Swal.fire('', 'No tiene registros', 'warning');
          $('#modal_datos_adicionales_ver').modal('hide');
        }
      },
      error: function(xhr, status, error) {
        // Cerrar el spinner en caso de error también
        Swal.fire('Error', 'Ocurrió un error en la solicitud: ' + error, 'error');
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
            <li class="breadcrumb-item active" aria-current="page">Historial de Atenciones Médicas del Paciente</li>
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
              <div class="col-9">
                <div class="card-title d-flex align-items-center">
                  <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                  </div>

                  <h5 class="mb-0 text-primary">Historial de Atenciones Médicas del Paciente: <b id="title_paciente" name="title_paciente" class="text-success"></b></h5>

                  <?php //print_r($_SESSION)//['INICIO']['USUARIO'])  //TIPO 
                  ?>

                </div>
              </div>
              <div class="col-3 text-end">
                <a href="../vista/inicio.php?mod=7&acc=pacientes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
              </div>
            </div>

            <div class="row m-0 pt-2">
              <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
                <div class="container-fluid"> <a class="navbar-brand" href="#"></a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
                  </button>

                  <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class='bx bxs-file-plus fs-6'></i> Datos Adicionales</a>
                        <ul class="dropdown-menu">
                          <li>
                            <a class="dropdown-item" onclick="modal_datos_adicionales_ver();">Ver</a>
                          </li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a type="button" class="dropdown-item" onclick="modal_datos_adicionales_agregar();">Agregar</a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </div>
                </div>
              </nav>
            </div>




            <div class="content">
              <!-- Content Header (Page header) -->
              <br>

              <section class="content">
                <div class="container-fluid">

                  <div class="row justify-content-center" id="btn_nuevo">

                    <div class="col-auto pt-2">
                      <form action="../vista/inicio.php?mod=7&acc=registrar_consulta_paciente" method="post">
                        <input type="hidden" name="id_ficha" id="id_ficha">
                        <input type="hidden" name="id_paciente" id="id_paciente">
                        <input type="hidden" name="tipo_consulta" id="tipo_consulta" value="consulta">

                        <button type="submit" class="btn btn-primary btn-lg m-0 p-5">Atención Médica</button>
                      </form>
                    </div>

                    <div class="col-auto pt-2">
                      <form action="../vista/inicio.php?mod=7&acc=registrar_consulta_paciente" method="post">
                        <input type="hidden" name="id_ficha" id="id_ficha">
                        <input type="hidden" name="id_paciente" id="id_paciente">
                        <input type="hidden" name="tipo_consulta" id="tipo_consulta" value="certificado">

                        <button type="submit" class="btn btn-primary btn-lg m-0 p-5"> Certificado</button>
                      </form>
                    </div>

                    <div class="col-auto  pt-2" id="pnl_segumiento_personal" style="display: none;">
                      <button type="button" class="btn btn-primary btn-lg m-0 p-5" onclick="seguimiento()"> Seguimiento</button>
                    </div>
                  </div>

                  <br>



                  <ul class="nav nav-tabs nav-success" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" data-bs-toggle="tab" href="#pnl_am" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                          <div class="tab-icon"><i class='bx bx-file-find font-18 me-1'></i>
                          </div>
                          <div class="tab-title">Atenciones Médicas</div>
                        </div>
                      </a>
                    </li>
                    <li class="nav-item" role="presentation" id="pnl_seguimiento_li" style="display: none;">
                      <a class="nav-link" data-bs-toggle="tab" href="#pnl_seguimiento" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                          <div class="tab-icon"><i class='bx bx-file-find font-18 me-1'></i>
                          </div>
                          <div class="tab-title">Seguimiento</div>
                        </div>
                      </a>
                    </li>
                  </ul>

                  <div class="tab-content py-3">
                    <div class="tab-pane fade show active" id="pnl_am" role="tabpanel">

                      <div class="row">
                        <div class="table-responsive">
                          <table class="table table-striped responsive" id="tbl_consultas" style="width:100%">
                            <thead>
                              <tr>
                                <th width="5%">Revisar</th>
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
                    </div>

                    <div class="tab-pane fade" id="pnl_seguimiento" role="tabpanel" style="display: none;">

                      <div class="row">
                        <div class="table-responsive">
                          <table class="table table-striped responsive" id="tbl_seguimiento" style="width:100%">
                            <thead>
                              <tr>
                                <th width="10%">Fecha de creación</th>
                                <th>Observación</th>
                              </tr>
                            </thead>
                            <tbody>

                            </tbody>
                          </table>
                        </div>
                      </div>

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


<div class="modal" id="modal_datos_adicionales_agregar" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <b id="title_paciente" name="title_paciente" class="text-success">sfsdf</b>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <div class="row">
          <div class="col-12">
            <label for="sa_pac_tabla">Peso <label class="text-danger">*</label></label>
            <input type="text" class="form-control form-control-sm solo_numeros" id="sa_pacda_peso" name="sa_pacda_peso">
          </div>
        </div>

        <div class="row pt-3">
          <div class="col-12">
            <label for="sa_pac_id_comunidad">Altura <label class="text-danger">*</label></label>
            <input type="text" class="form-control form-control-sm solo_numeros" id="sa_pacda_altura" name="sa_pacda_altura">
          </div>
        </div>

        <div class="row pt-3">
          <div class="col-12 text-end">
            <button type="submit" class="btn btn-success btn-sm" onclick="insertar_datos_adicionales();"><i class="bx bx-save"></i> Agregar</button>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal" id="modal_datos_adicionales_ver" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <b id="title_paciente" name="title_paciente" class="text-success">sfsdf</b>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <div class="row">
          <div class="col-12">
            <table class="table table-hover">
              <thead class="thead-dark">
                <tr>
                  <th>Fecha Creación</th>
                  <th>Peso</th>
                  <th>Altura</th>
                </tr>
              </thead>
              <tbody id="tbl_datos_adicionales">

              </tbody>

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>