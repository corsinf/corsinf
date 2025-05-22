<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    //cargar_datos();
  });

  function cargar_tabla_logs(identificador) {
    // Referencia a la tabla
    let $tabla = $('#tbl_logs_carga');

    let tbl_logs_carga = $tabla.DataTable($.extend({}, configuracion_datatable('Logs de carga', 'logs de carga'), {
      destroy: true,
      responsive: true,
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
      },
      ajax: {
        url: '../controlador/ACTIVOS_FIJOS/cargar_datosC.php?log_activos=true',
        type: 'POST', // Usar POST para mayor seguridad
        data: function(d) {
          d.identificador = identificador; // Enviar el identificador
        },
        dataSrc: ''
      },
      columns: [{
          data: 'detalle'
        },
        {
          data: 'fecha'
        },
        {
          data: 'accion'
        },
        {
          data: 'intento'
        },
        {
          data: 'estado'
        },
        {
          data: 'usuario'
        }
      ],
      order: [
        [0, 'desc']
      ]
    }));

  }
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
        // Validar si la respuesta es un array con al menos un elemento
        if (Array.isArray(response) && response.length > 0) {
          let id = response[0].nuevo_id;
          let identificador = response[0].nombre_generado;

          // 'ID generado: ' + id +
          Swal.fire('Carga completa', 'Identificador: ' + identificador, 'success').then(function() {
            $('#modal_proceso').modal('hide');
            cargar_tabla_logs(identificador);
          });

        } else {
          Swal.fire(
            'No se pudo completar',
            'Asegúrese que los datos estén en los formatos correctos y sin (;) punto y comas ó revise la cantidad de items en el archivo',
            'error'
          ).then(function() {
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
    if (op != '') {
      $('#link_plantilla').css('display', 'initial');
    }

    switch (op) {
      case '1':
        url = '../descargas/FORMATOS/DEMO.xlsm';
        salida = 'ACTIVOS MACRO'
        break;
      case '2':
        url = '../descargas/FORMATOS/DEMO.xlsm';
        salida = 'CUSTODIO MACRO'
        break;
      case '3':
        url = '../descargas/FORMATOS/LOCALIZACION_MACRO.xlsm';
        salida = 'EMPLAZAMIENTO MACRO'
        break;
      case '4':
        url = '../descargas/FORMATOS/MARCA_MACRO.xlsm';
        salida = 'MARCAS MACRO'
        break;
      case '5':
        url = '../descargas/FORMATOS/ESTADO_MACRO.xlsm';
        salida = 'ESTADO MACRO'
        break;
      case '6':
        url = '../descargas/FORMATOS/GENERO_MACRO.xlsm';
        salida = 'GENEROS MACRO'
        break;
      case '7':
        url = '../descargas/FORMATOS/COLOR_MACRO.xlsm';
        salida = 'COLORES MACRO'
        break;
      case '8':
        // url = '../descargas/FORMATOS/PROYECTOS_PRUEBA.xlsm';
        // salida = 'PROYECTOS MACRO' 
        break;
      case '9':
        url = '../descargas/FORMATOS/MOVIMIENTO_MACRO.xlsm';
        salida = 'CLASE MOVIMIENTO MACRO'
        break;
      default:
        url = '#';
        url2 = '#';
        break;
    }

    $('#link_plantilla').attr('href', url);

    $('#link_plantilla')
      .html('<i class="bx bxs-download"></i>PLANTILLA ' + salida)
      .css({
        display: 'inline-flex',
        alignItems: 'center',
        gap: '6px'
      });


  }
</script>

<style>

</style>
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
            <li class="breadcrumb-item active" aria-current="page">Carga de datos</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
      <div class="col-xl-12">
        <div class="alert alert-primary border-0 bg-primary alert-dismissible fade show py-2" onclick="mostrar_modal_carga_datos();">
          <div class="d-flex align-items-center">
            <div class="font-35 text-white">
              <i class='bx bx-file-find'></i>
            </div>
            <div class="ms-3">
              <h6 class="mb-0 text-white">TUTORIAL DISPONIBLE</h6>
              <div class="text-white">Aprende paso a paso cómo realizar la carga detallada de datos.</div>
            </div>
          </div>
        </div>
      </div>


      <div class="col-xl-12 mx-auto">
        <hr>
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-4">
                <select class="form-control form-select" id="ddl_opcion" onchange="opcion_carga()">
                  <option disabled selected>Elige los datos que deseas cargar</option>
                  <option value="1">Cargar Artículos</option>
                  <option value="2">Cargar Custodios</option>
                  <option value="3">Cargar Localización</option>
                  <option value="4">Cargar Marcas</option>
                  <option value="5">Cargar Estado</option>
                  <option value="6">Cargar Género</option>
                  <option value="7">Cargar Color</option>
                  <!-- <option value="8">Cargar Proyectos</option> -->
                  <option value="9">Cargar Clase de Movimiento</option>
                  <!-- <option value="10">Actualizar Activos</option> -->
                </select>

                <div class="mt-2">
                  <a href="#" style="display: none;" id="link_plantilla" class="font-13" download><i class="bx bx-file me-0"></i> Descargar plantilla</a>
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
          <div class="text-center"><img src="../img/de_sistema/loader_sistema.gif" width="100" height="100">SUBIENDO DATOS</div>
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

<!-- Modal Bootstrap -->
<div class="modal fade" id="modal_carga_datos" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-primary" id="tituloModal">
          Convertir a .csv
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="alert alert-info">
          Nota: Se debe cumplir el
          <strong>orden de los pasos</strong> para completar con éxito el
          procedimiento
        </div>

        <h4 class="text-warning mb-4">Pasos para conversión</h4>

        <!-- Sección 1 -->
        <div class="mb-4 border-start border-secondary ps-3">
          <h5 class="fw-semibold">
            <i class="fa-regular fa-hand-pointer"></i> 1. Seleccionar el
            archivo
          </h5>
          <ul class="list-unstyled mt-2">
            <li>Clic en "Elige los datos que deseas cargar"</li>
            <li>Selecciona la opción correspondiente</li>
          </ul>
        </div>

        <!-- Sección 2 -->
        <div class="mb-4 border-start border-secondary ps-3">
          <h5 class="fw-semibold">
            <i class="fa-solid fa-download"></i> 2. Descargar archivo .xlsm
          </h5>
          <ul class="list-unstyled mt-2">
            <li>Clic sobre el enlace de la plantilla</li>
            <li>La descarga iniciará automaticamente</li>
          </ul>
        </div>

        <!-- Sección 3 -->
        <div class="mb-4 border-start border-secondary ps-3">
          <h5 class="fw-semibold">
            <i class="fa-solid fa-file-pen"></i> 3. Abrir archivo .xlsm
          </h5>
          <ul class="list-unstyled mt-2">
            <li>Clic en descargas</li>
            <li>Clic en "Mostrar en carpeta"</li>
            <li>Doble clic sobre el archivo .xlsm</li>
          </ul>
        </div>

        <!-- Sección 4 -->
        <div class="accordion" id="accordionCSV">
          <div class="accordion-item border-0">
            <!-- Botón personalizado del acordeón -->
            <div
              class="mb-2 border-start border-secondary ps-3 accordion-button collapsed d-block text-start"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#collapseCSV"
              aria-expanded="false"
              aria-controls="collapseCSV"
              style="cursor: pointer; background-color: #fafafa">
              <div
                class="d-flex justify-content-between align-items-center">
                <h5 class="fw-semibold mb-0">
                  <i class="fa-solid fa-floppy-disk me-2"></i> 4. Guardar en
                  formato .csv
                </h5>
                <i
                  class="fa-solid fa-chevron-down rotate-icon transition"></i>
              </div>

              <ul class="list-unstyled mt-2 mb-2">
                <li>Habilitar contenido de macros</li>
                <li>Clic en "Archivo"</li>
                <li>Clic en "Guargar como..."</li>
                <li>Clic en "Examinar"</li>
                <li>Selecciona la ubicación</li>
                <li>Ingresa el nombre del archivo</li>
                <li>
                  Selecciona el tipo: "CSV (delimitado por comas)(.csv)"
                </li>
                <li>Clic en "Guargar"</li>
              </ul>
            </div>

            <!-- Contenido oculto del acordeón (imagen) -->
            <div
              id="collapseCSV"
              class="accordion-collapse collapse"
              data-bs-parent="#accordionCSV">
              <div class="accordion-body ps-4 pt-0">
                <img src="../img/modulo_activos/carga_datos_guardar.png" alt="Ejemplo de guardado CSV" class="img-fluid rounded border" />
              </div>
            </div>
          </div>
        </div>

        <div class="alert alert-info">
          Nota: Si obtienes "RIESGO DE SEGURIDAD" al habilitar el contenido
          sigue los siguientes pasos:
        </div>

        <!-- Sección 4 -->
        <div class="accordion" id="accordionSeguridad">
          <div class="accordion-item border-0">
            <!-- Botón personalizado del acordeón -->
            <div
              class="mb-2 border-start border-warning ps-3 accordion-button collapsed d-block text-start"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#collapseSeguridad"
              aria-expanded="false"
              aria-controls="collapseSeguridad"
              style="cursor: pointer; background-color: #fafafa">
              <div
                class="d-flex justify-content-between align-items-center">
                <h5 class="fw-semibold mb-0">
                  <i class="fa-solid fa-unlock me-2"></i> 4.1 Desbloquear
                  seguridad
                </h5>
                <i
                  class="fa-solid fa-chevron-down rotate-icon transition"></i>
              </div>

              <ul class="list-unstyled mt-2 mb-2">
                <li>Ve a la ubicación del archivo .xlsm</li>
                <li>Clic derecho sobre el archivo .xlsm</li>
                <li>Clic en "Propiedades"</li>
                <li>En General, marca la casilla "Desbloquear"</li>
                <li>Clic en "Aplicar"</li>
                <li>Clic en "Aceptar"</li>
                <li>Doble clic sobre el archivo .xlsm</li>
                <li>Volver al paso 4</li>
              </ul>
            </div>

            <!-- Contenido oculto del acordeón (imagen) -->
            <div
              id="collapseSeguridad"
              class="accordion-collapse collapse"
              data-bs-parent="#accordionSeguridad">
              <div class="accordion-body ps-4 pt-0">
                <img src="../img/modulo_activos/carga_datos_desbloquear.jpg" alt="Ejemplo de desbloqueo" class="img-fluid rounded border" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer border-0">
        <button
          type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  function mostrar_modal_carga_datos() {
    $('#modal_carga_datos').modal('show');
  }
</script>