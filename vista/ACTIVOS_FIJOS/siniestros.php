<?php //include('../cabeceras/header.php'); 

/**
 * @deprecated Archivo dado de baja el 02/04/2025.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */

?>
<script type="text/javascript">
  $(document).ready(function() {
    lista_articulos();
    estado();
  });


  function historial(id) {
    var parametros = {
      'id': id,
    };
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/contratoC.php?historial=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // console.log(response);   
        $('#tbl_historial').html(response.tbl);
        if (response.pendiente == 1) {
          $('#btn_add_siniestro').css('display', 'none');
        } else {
          $('#btn_add_siniestro').css('display', 'initial');
        }
      }
    });
  }

  function estado() {
    var id = '';
    var estado = '<option value="">Seleccione Estado</option>';

    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/ACTIVOS_FIJOS/estadoC.php?lista=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          estado += "<option value='" + item.ID_ESTADO + "''>" + item.DESCRIPCION + "</option>";

          // console.log(item);
        });
        $('#ddl_estado').html(estado);
      }
    });
  }

  function cerrar_siniestro(id) {
    $('#myModal_siniestros').modal('show');
    var parametros = {
      'id': id,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/contratoC.php?detalle_siniestro=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        $('#txt_encargado').val(response[0].encargado);
        $('#txt_fecha_reg').val(response[0].fecha);
        $('#txt_fecha_sini').val(response[0].fecha_siniestro);
        $('#ddl_estado').append($('<option>', {
          value: response[0].estado,
          text: response[0].DESCRIPCION,
          selected: true
        }));;
        $('#txt_detalle_siniestro').val(response[0].detalle);
        $('#txt_alertado').val(response[0].fecha_alertado);
        $('#txt_respuesta').val(response[0].respuesta);
        $('#txt_evaluacion').val(response[0].evaluacion);
        $('#rbl_estado_proceso_' + response[0].estado_proceso).prop('checked', true);
        $('#txt_id_siniestro').val(response[0].id_deterioro);
        mostrar();

      }
    });
  }

  function lista_articulos() {
    $('#ddl_articulos').select2({
      placeholder: 'Seleccione articulo / activo para ver detalle de seguro',
      width: '100%',
      ajax: {
        url: '../controlador/ACTIVOS_FIJOS/contratoC.php?lista_articulos=true&tabla=ACTIVO',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          // console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function cargar_datos_seguro(id) {
    // console.log(id);false;
    var parametros = {
      'id': id,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/contratoC.php?cargar_datos_seguro_art=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        console.log(response)
        if (response.length > 0) {
          console.log('entra');
          $('#lbl_alerta').css('display', 'none');
          $('#div_datos').css('display', 'block');
          data = response[0];
          $("#lbl_proveedor").text(data.nombre);
          $("#lbl_seguro").text('FECHA INICIO:' + data.desde);
          $("#lbl_fin_seguro").text('FECHA FIN:' + data.desde);
          $("#lbl_cobertura").text(data.nombre_riesgo);
          $("#lbl_email").text(data.email_asesor);
          $("#lbl_telefono").text(data.telefono_asesor);
          $("#lbl_asesor").text(data.asesor);
          $("#lbl_valor_pagar").text('VALOR DE PAGO: ' + data.suma_asegurada);
          // $("#lbl_asegurado").val(data.);
          // console.log(response);
        } else {
          $('#div_datos').css('display', 'none');
          $('#lbl_alerta').css('display', 'block');

        }
      }
    });
  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Seguros</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Siniestros</li>
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
              <div class="col-sm-12">
                <b>Seleccione articulo / activo para ver detalle de seguro</b>
                <div class="input-group input-group-sm">
                  <select class="form-control form-control-sm" name="ddl_articulos" id="ddl_articulos" onchange="cargar_datos_seguro(this.value);historial(this.value)">
                    <option value="">Seleccione articulo</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2" id="lbl_alerta" style="display: none;">
          <div class="d-flex align-items-center">
            <div class="font-35 text-dark"><i class="bx bx-info-circle"></i>
            </div>
            <div class="ms-3">
              <h6 class="mb-0 text-dark">Sin seguro registrado</h6>
              <div class="text-dark">El activo seleccionado no tiene un seguro registrado!</div>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="card" id="div_datos" style="display: none;">
          <div class="card-body">
            <div id="invoice">
              <div class="invoice overflow-auto">
                <div>
                  <header>
                    <div class="row">
                      <div class="col">
                        <a href="javascript:;">
                          <img src="assets/images/logo-icon.png" width="80" alt="">
                        </a>
                      </div>
                      <div class="col company-details">
                        <h2 class="name">
                          <a href="javascript:;" id="lbl_asesor"></a>
                        </h2>
                        <div id="lbl_telefono"></div>
                        <div id="lbl_email"></div>
                      </div>
                    </div>
                  </header>
                  <main>
                    <div class="row contacts">
                      <div class="col invoice-to">
                        <div class="text-gray-light">COBERTURA:</div>
                        <h3 class="to" id="lbl_valor_pagar"></h3>
                        <div class="address" id="lbl_cobertura"></div>
                        <div class="email" id="lbl_siniestro"></div>
                      </div>
                      <div class="col invoice-details">
                        <h1 class="invoice-id" id="lbl_proveedor"></h1>
                        <div class="date" id="lbl_seguro"></div>
                        <div class="date" id="lbl_fin_seguro"></div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-6">
                        <h4>Historial de siniestros</h4>
                      </div>
                      <div class="col-sm-6 text-end">
                        <button class="btn btn-primary btn-sm" id="btn_add_siniestro" name="btn_add_siniestro" onclick="mostrar()">Agregar Siniestro</button>
                      </div>
                    </div>
                    <table>
                      <thead>
                        <tr>
                          <th></th>
                          <th>FECHA</th>
                          <th class="text-left">DETALLE</th>
                          <th class="text-right">ESTADO</th>
                          <th class="text-right">ALERTADO</th>
                          <th class="text-right">RESPUESTA</th>
                        </tr>
                      </thead>
                      <tbody id="tbl_historial">

                      </tbody>
                    </table>
                  </main>
                  <footer></footer>
                </div>
                <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                <div></div>
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>
    <!--end row-->
  </div>
</div>

<div class="modal fade" id="myModal_siniestros" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Datos de siniestro</h3>
      </div>
      <div class="modal-body">
        <!--  <div class="row">         
          <div class="col-sm-12" id="div_siniestro">    -->
        <div class="row">
          <input type="hidden" name="txt_id_siniestro" id="txt_id_siniestro" class="form-control form-control-sm">
          <div class="col-sm-12">
            <b>Encargado</b>
            <input type="text" name="txt_encargado" id="txt_encargado" class="form-control form-control-sm">
          </div>
          <div class="col-sm-4">
            <b>Fecha de siniestro</b>
            <input type="date" name="txt_fecha_sini" id="txt_fecha_sini" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-sm-4">
            <b>Fecha de registro</b>
            <input type="date" name="txt_fecha_reg" id="txt_fecha_reg" class="form-control form-control-sm" readonly value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-sm-4">
            <b>Estado Articulo</b>
            <select class="form-select form-select-sm" id="ddl_estado" name="ddl_estado">
              <option value="">Seleccione</option>
            </select>
          </div>
          <div class="col-sm-12">
            <b>Detalle de siniestro</b>
            <textarea class="form-control-sm form-control" style="resize:none;" rows="3" id="txt_detalle_siniestro" name="txt_detalle_siniestro"></textarea>
          </div>
          <div class="col-sm-4">
            <b>Fecha Notificacion</b>
            <input type="date" name="txt_alertado" id="txt_alertado" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-sm-8">
            <b>Respuesta</b>
            <textarea class="form-control-sm form-control" style="resize:none;" rows="3" id="txt_respuesta" name="txt_respuesta"></textarea>
          </div>
          <div class="col-sm-12">
            <b>Respuesta de evaluacion</b>
            <textarea class="form-control-sm form-control" style="resize:none;" rows="3" id="txt_evaluacion" name="txt_evaluacion"></textarea>
          </div>
          <br>
          <div class="col-sm-6">
            <label><i class="bx bx-lock-open"></i><input type="radio" name="rbl_estado_proceso" id="rbl_estado_proceso_0" checked value="0">Proceso pendiente</label>
          </div>
          <div class="col-sm-6">
            <label><i class="bx bx-lock"></i><input type="radio" name="rbl_estado_proceso" id="rbl_estado_proceso_1" value="1">Cerrar proceso</label>
          </div>
          <!--  </div>            
          </div>   -->
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" onclick="cancelar()">Cancelar</button>
        <button class="btn btn-primary" onclick="guardar()">Guardar</button>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
  function mostrar() {

    $('#myModal_siniestros').modal('show');
  }

  function cancelar() {
    $('#myModal_siniestros').modal('hide');
  }

  function guardar() {
    var enca = $('#txt_encargado').val();
    var fecha_sini = $('#txt_fecha_sini').val();
    var fecha_reg = $('#txt_fecha_reg').val();
    var estado = $('#ddl_estado').val();
    var detalle_sini = $('#txt_detalle_siniestro').val();
    var fecha_ale = $('#txt_alertado').val();
    var respueta = $('#txt_respuesta').val();
    var evaluacion = $('#txt_evaluacion').val();
    var proceso = $('input[name="rbl_estado_proceso"]:checked').val();
    var articulo = $('#ddl_articulos').val();
    var id = $('#txt_id_siniestro').val();
    if (articulo == '') {
      Swal.fire('', 'No se a seleccionado una articulo', 'info');
      return false;
    }

    var parametros = {
      'articulo': articulo,
      'encargado': enca,
      'fecha_si': fecha_sini,
      'fecha_re': fecha_reg,
      'estado': estado,
      'detalle': detalle_sini,
      'fecha_al': fecha_ale,
      'respuesta': respueta,
      'evaluacion': evaluacion,
      'proceso': proceso,
      'id': id,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/contratoC.php?guardar_datos_siniestro=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        console.log(response)
        if (response == 1) {
          historial(articulo);
          Swal.fire('', 'Guardado', 'success');
          $('#txt_encargado').val('');
          $('#txt_fecha_sini').val('');
          $('#txt_fecha_reg').val('');
          $('#ddl_estado').val('');
          $('#txt_detalle_siniestro').val('');
          $('#txt_alertado').val('');
          $('#txt_respuesta').val('');
          $('#txt_evaluacion').val('');
          $('#rbl_estado_proceso_0').prop('checked', true);
          $('#txt_id_siniestro').val('');
          cancelar();

        }
      }
    });
  }
</script>



<?php //include('../cabeceras/footer.php'); 
?>