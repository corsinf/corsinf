<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    tbl_logs_carga = $('#tbl_logs_carga').DataTable($.extend({}, configuracion_datatable('Logs de carga', 'logs de carga'), {
      reponsive: true,
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
      },
      ajax: {
        url: '../controlador/ACTIVOS_FIJOS/cargar_datosC.php?log_activos=true',
        data: function(d) {
          d.identificador = '';
        },
        dataSrc: ''
      },

      // 'id_log'
      // 'contador'
      columns: [{
          data: 'detalle'
        },
        {
          data: 'estado'
        },
        {
          data: 'fecha'
        },
        {
          data: 'intento'
        },
        {
          data: 'accion'
        },
        {
          data: 'usuario'
        },
      ],
      order: [
        [0, 'desc']
      ]
    }));
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_carga").on('click', function() {
      var id = $('#ddl_opcion').val();
      $('#txt_opcion').val(id);
      var fi = $('#file').val();

      if (id != '' && fi != '') {

        var formData = new FormData(document.getElementById("form_carga_datos"));

        $.ajax({
          url: '../controlador/ACTIVOS_FIJOS/cargar_datosC.php?subir_archivo_server=true',
          type: 'post',
          data: formData,
          contentType: false,
          processData: false,
          dataType: 'json',
          // beforeSend: function () {
          //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
          //     },
          success: function(response) {
            if (response == 1) {
              cargar_datos();
            } else {
              Swal.fire('Formato del archivo incorrecto', 'asegurese que el archivo sea (.cvs)', 'error');

            }
          }
        });
      } else {
        Swal.fire('', 'Destino o archivo no seleccionados', 'error');
      }
    });
  });
</script>

<script type="text/javascript">
  function cargar_datos() {
    var ddl_opcion = $('#ddl_opcion').val();
    var parametros = {
      'id': ddl_opcion,
      'tip': $('#rbl_primera').prop('checked'),
    };

    $('#modal_proceso').modal('show');

    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/cargar_datosC.php?ejecutar_sp=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        if (response == 1) {
          Swal.fire('Carga completa', '', 'success').then(function() {
            $('#modal_proceso').modal('hide');
            // console.log(id);                  
            // log_activos()
          });
        } else {
          Swal.fire('No se pudo completar', 'Asegurese que los datos esten en los formatos correctos y sin (;) punto y comas ó revise la cantidad de items en el archivo', 'error').then(function() {

            $('#modal_proceso').modal('hide');
          });
        }
      }

    });
  }

  function opcion_carga() {
    var op = $('#ddl_opcion').val();
    if (op == 1) {
      $('#lbl_check').css('display', 'none');
    } else {
      $('#lbl_check').css('display', 'block');
    }


    $('#link_plantilla').css('display', 'none');
    $('#link_ficha').css('display', 'none');
    if (op != '') {
      $('#link_plantilla').css('display', 'initial');
      $('#link_ficha').css('display', 'initial');
    }

    switch (op) {
      case '1':
        url = '../descargas/FORMATOS/ACTIVOS_PRUEBAS.csv';
        url2 = '../descargas/FORMATOS/FICHA TECNICA/ACTIVOS.xlsx';
        break;
      case '2':
        url = '../descargas/FORMATOS/CUSTODIO_PRUEBA.csv';
        url2 = '../descargas/FORMATOS/FICHA TECNICA/CUSTODIOS.xlsx';
        break;
      case '3':
        url = '../descargas/FORMATOS/EMPLAZAMIENTO_PRUEBA.csv';
        url2 = '../descargas/FORMATOS/FICHA TECNICA/EMPLAZAMIENTOS.xlsx';
        break;
      case '4':
        url = '../descargas/FORMATOS/MARCAS_PRUEBAS.csv';
        url2 = '../descargas/FORMATOS/FICHA TECNICA/MARCA.xlsx';
        break;
      case '5':
        url = '../descargas/FORMATOS/ESTADO_PRUEBA.csv';
        url2 = '../descargas/FORMATOS/FICHA TECNICA/ESTADO.xlsx';
        break;
      case '6':
        url = '../descargas/FORMATOS/GENEROS_PRUEBA.csv';
        url2 = '../descargas/FORMATOS/FICHA TECNICA/GENERO.xlsx';
        break;
      case '7':
        url = '../descargas/FORMATOS/COLORES_PRUEBA.csv';
        url2 = '../descargas/FORMATOS/FICHA TECNICA/COLORES.xlsx';
        break;
      case '8':
        url = '../descargas/FORMATOS/PROYECTOS_PRUEBA.csv';
        url2 = '../descargas/FORMATOS/FICHA TECNICA/PROYECTO.xlsx';
        break;
      case '9':
        url = '../descargas/FORMATOS/CLASE_MOVIMIENTO.csv';
        $('#link_ficha').css('display', 'none');
        break;
      default:
        url = '#';
        url2 = '#';
        break;
    }

    $('#link_plantilla').attr('href', url);
    $('#link_ficha').attr('href', url2);
  }

  function log_activos() {
    parametros = {
      'fecha': $('#txt_fecha').val(),
      'accion': $('#txt_accion').val(),
      'intento': $('#txt_intento').val(),
      'estado': $('input[name="rbl_estado"]:checked').val(),
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/cargar_datosC.php?log_activos=true',
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
        // $("#foto_alumno").attr('src',"../img/gif/proce.gif");
        $('#tbl_datos').html('<tr class="text-center"><td colspan="6"><img src="../img/de_sistema/loader_puce.gif" style="width:10%"></td></tr>');
      },
      success: function(response) {

        $('#tbl_datos').html(response);
        console.log(response);
      }

    });
  }

  function leer_datos() {
    $.ajax({
      // data:  {parametros:parametros},
      url: '../controlador/ACTIVOS_FIJOS/carga_datos/cargar_controlador.php?leer=true',
      type: 'post',
      dataType: 'json',
      // beforeSend: function () {
      //        // $("#foto_alumno").attr('src',"../img/gif/proce.gif");
      //   $('#tbl_datos').html('<tr class="text-center"><td colspan="6"><img src="../img/de_sistema/loader_puce.gif" style="width:10%"></td></tr>');
      // },
      success: function(response) {

        $('#tbl_datos').html(response);
        console.log(response);
      }

    });
  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Carga de datos</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">carga de datos</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <hr>
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-4">
                <select class="form-control form-select" id="ddl_opcion" onchange="opcion_carga()">
                  <option disabled selected>Elige los datos que deseas cargar</option>
                  <option value="1">Cargar Activos</option>
                  <option value="2">Cargar Custodios</option>
                  <option value="3">Cargar Emplazamientos</option>
                  <option value="4">Cargar Marcas</option>
                  <option value="5">Cargar Estado</option>
                  <option value="6">Cargar Género</option>
                  <option value="7">Cargar Color</option>
                  <option value="8">Cargar Proyectos</option>
                  <option value="9">Cargar Clase de Movimiento</option>
                  <option value="10">Actualizar Activos</option>
                </select>

                <div class="mt-2">
                  <a href="#" style="display: none;" id="link_plantilla" class="font-13" download><i class="bx bx-file me-0"></i> Descargar plantilla</a><br>
                  <a href="#" style="display: none;" id="link_ficha" class="font-13" download><i class="bx bx-file me-0"></i> Descargar ficha técnica</a>
                </div>
              </div>

              <div class="col-sm-6">
                <form enctype="multipart/form-data" id="form_carga_datos" method="post">
                  <input type="hidden" id="txt_opcion" name="txt_opcion">
                  <input type="file" name="file" id="file" class="form-control">
                  <p class="small mt-2 text-muted"><strong>Nota:</strong> El archivo debe tener un máximo de 10,000 ítems.</p>
                </form>
              </div>

              <div class="col-sm-2">
                <button class="btn btn-sm btn-primary w-100 mb-2" id="btn_carga">Actualizar archivos</button>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="rbl_primera" id="rbl_primera">
                  <label class="form-check-label" for="rbl_primera">Cómo primera vez</label>
                </div>
                <!-- <button class="btn btn-sm btn-primary" onclick="leer_datos()">Leer datos</button> -->
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-primary">
              <div class="card-body p-5">
                <div class="row">

                  <div class="col-12 col-md-6">
                    <div class="card-title d-flex align-items-center">

                      <h4 class="card-title">Logs de Carga</h4>

                    </div>
                  </div>

                  <div class="col-12 col-md-6 text-md-end text-start">
                    <div id="contenedor_botones"></div>
                  </div>

                </div>

                <hr>

                <section class="content pt-2">
                  <div class="container-fluid">
                    <div class="table-responsive">
                      <table class="table table-striped responsive " id="tbl_logs_carga" style="width:100%">
                        <thead>
                          <tr>
                            <th>Detalle log</th>
                            <th>Fecha</th>
                            <th>Intento</th>
                            <th>Acción</th>
                            <th>Estado</th>
                            <th>Usuario</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </section>

              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!--end row-->
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_proceso" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="cargar">
          <div class="text-center"><img src="../img/de_sistema/loader_puce.gif" width="100" height="100">SUBIENDO DATOS</div>
        </div>
        <div>
          <div class="progress-group" id="loader">
            <span class="progress-number" id="pro_partes"><b>1/?</b></span>
            <div class="progress sm">
              <div class="progress-bar progress-bar-aqua" style="width: 1%" id="loader_"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php //include('../cabeceras/footer.php'); 
?>