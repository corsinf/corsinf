<?php //include('../../../../cabeceras/header.php');

$id = '';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var id = '<?php echo $id; ?>';

    inicializarInputs()

    //alert(id);

    if (id != '') {
      datos_col(id);
      $('#sa_cmed_stock').prop('disabled', true);
    }

  });

  //listo
  function datos_col(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/SALUD_INTEGRAL/medicamentosC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        $('#sa_cmed_id').val(response[0].sa_cmed_id);
        $('#sa_cmed_concentracion').val(response[0].sa_cmed_concentracion);
        $('#sa_cmed_presentacion').val(response[0].sa_cmed_presentacion);
        $('#sa_cmed_serie').val(response[0].sa_cmed_serie);
        $('#sa_cmed_lote').val(response[0].sa_cmed_lote);
        $('#sa_cmed_caducidad').val((response[0].sa_cmed_caducidad));
        $('#sa_cmed_minimos').val(response[0].sa_cmed_minimos);
        $('#sa_cmed_stock').val(response[0].sa_cmed_stock);
        $('#sa_cmed_movimiento').val(response[0].sa_cmed_movimiento);
        $('#sa_cmed_contraindicacion').val(response[0].sa_cmed_contraindicacion);
        $('#sa_cmed_dosis').val(response[0].sa_cmed_dosis);
        $('#sa_cmed_tratamientos').val(response[0].sa_cmed_tratamientos);
        $('#sa_cmed_uso').val(response[0].sa_cmed_uso);
        $('#sa_cmed_observaciones').val(response[0].sa_cmed_observaciones);
        $('#sa_cmed_nombre_comercial').val(response[0].sa_cmed_nombre_comercial);
        $('#sa_cmed_mg').val(response[0].sa_cmed_mg);
        $('#sa_cmed_formula').val(response[0].sa_cmed_formula);

        // Para autorizar al paciente recibir medicamentos 
        if (response[0].sa_cmed_es_jarabe == 1) {
          $('#cbx_jarabe').prop('checked', true);
          $('#pnl_formula_jarabes').show();
        } else {
          $('#cbx_jarabe').prop('checked', false);
        }

      }
    });
  }

  function editar_insertar() {
    var sa_cmed_id = $('#sa_cmed_id').val();
    var sa_cmed_concentracion = $('#sa_cmed_concentracion').val();
    var sa_cmed_presentacion = $('#sa_cmed_presentacion').val();
    var sa_cmed_serie = $('#sa_cmed_serie').val();
    var sa_cmed_lote = $('#sa_cmed_lote').val();
    var sa_cmed_caducidad = $('#sa_cmed_caducidad').val();
    var sa_cmed_minimos = $('#sa_cmed_minimos').val();
    var sa_cmed_stock = $('#sa_cmed_stock').val();
    var sa_cmed_movimiento = $('#sa_cmed_movimiento').val();
    var sa_cmed_contraindicacion = $('#sa_cmed_contraindicacion').val();
    var sa_cmed_dosis = $('#sa_cmed_dosis').val();
    var sa_cmed_tratamientos = $('#sa_cmed_tratamientos').val();
    var sa_cmed_uso = $('#sa_cmed_uso').val();
    var sa_cmed_observaciones = $('#sa_cmed_observaciones').val();
    var sa_cmed_nombre_comercial = $('#sa_cmed_nombre_comercial').val();

    var cbx_jarabe = $('#cbx_jarabe').is(':checked') ? 1 : 0;
    var sa_cmed_mg = $('#sa_cmed_mg').val();
    var sa_cmed_formula = $('#sa_cmed_formula').val();

    var parametros = {
      'sa_cmed_id': sa_cmed_id,
      'sa_cmed_concentracion': sa_cmed_concentracion,
      'sa_cmed_presentacion': sa_cmed_presentacion,
      'sa_cmed_serie': sa_cmed_serie,
      'sa_cmed_lote': sa_cmed_lote,
      'sa_cmed_caducidad': sa_cmed_caducidad,
      'sa_cmed_minimos': sa_cmed_minimos,
      'sa_cmed_stock': sa_cmed_stock,
      'sa_cmed_movimiento': sa_cmed_movimiento,
      'sa_cmed_contraindicacion': sa_cmed_contraindicacion,
      'sa_cmed_dosis': sa_cmed_dosis,
      'sa_cmed_tratamientos': sa_cmed_tratamientos,
      'sa_cmed_uso': sa_cmed_uso,
      'sa_cmed_observaciones': sa_cmed_observaciones,
      'sa_cmed_nombre_comercial': sa_cmed_nombre_comercial,
      'cbx_jarabe': cbx_jarabe,
      'sa_cmed_mg': sa_cmed_mg,
      'sa_cmed_formula': sa_cmed_formula,
    }

    if (sa_cmed_id == '') {
      if (sa_cmed_presentacion == '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todo los campos',
        })
        //alert('error');
      } else {
        insertar(parametros)
      }
    } else {
      if (sa_cmed_presentacion == '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todo los campos',
        })
        //alert('error');
      } else {
        insertar(parametros);
      }
    }

    //console.log(parametros);
    //insertar(parametros);

  }

  function insertar(parametros) {
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/SALUD_INTEGRAL/medicamentosC.php?insertar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=7&acc=medicamentos';
          });
        } else if (response == -2) {
          Swal.fire('', 'Código ya regitrado', 'error');
        }
      }
    });
  }

  function delete_datos() {
    var id = '<?php echo $id; ?>';
    Swal.fire({
      title: 'Eliminar Registro?',
      text: "¿Está seguro de eliminar este registro?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        eliminar(id);
      }
    })

    //eliminar(id);

  }

  function eliminar(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/SALUD_INTEGRAL/medicamentosC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
        var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'
        $('#tabla_').html(spiner);
      },
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=7&acc=medicamentos';
          });
        }
      }
    });
  }

  function inicializarInputs() {
    $.ajax({
      url: '../controlador/SALUD_INTEGRAL/v_med_insC.php?listar_v_medicamentos=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);

        // Verificar si la respuesta contiene datos
        if (response && response.length > 0) {
          response.forEach(function(medicamento) {

            if (medicamento.sa_vmi_estado == 1) {
              $('#' + medicamento.sa_vmi_id_input).show();
            } else {
              $('#' + medicamento.sa_vmi_id_input).hide();
            }

          });

        }
      },
    });
  }
</script>


<div class="page-wrapper">
  <div class="page-content">

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Enfermería</div>
      <?php
      // print_r($_SESSION['INICIO']);die();

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              <?php
              if ($id == '') {
                echo 'Registrar Medicamento';
              } else {
                echo 'Modificar Medicamento';
              }
              ?>
            </li>
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
              <h5 class="mb-0 text-primary">
                <?php
                if ($id == '') {
                  echo 'Registrar Medicamento';
                } else {
                  echo 'Modificar Medicamento';
                }
                ?>
              </h5>
              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="../vista/inicio.php?mod=7&acc=medicamentos" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>
            <hr>

            <form action="" method="post">

              <input type="hidden" name="sa_cmed_id" id="sa_cmed_id">



              <div class="row pt-3" id="sa_cmed_presentacion_inputs" style="display: none;">
                <div class="col-12">
                  <label for="" class="form-label">Presentación (Nombre del Medicamentos) <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cmed_presentacion" id="sa_cmed_presentacion">
                </div>
              </div>


              <div class="row pt-3" id="sa_cmed_nombre_comercial_inputs" style="display: none;">
                <div class="col-12">
                  <label for="" class="form-label">Nombre Comercial <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cmed_nombre_comercial" id="sa_cmed_nombre_comercial">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-3" id="sa_cmed_serie_inputs" style="display: none;">
                  <label for="" class="form-label">Serie <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cmed_serie" id="sa_cmed_serie">
                </div>
                <div class="col-3" id="sa_cmed_lote_inputs" style="display: none;">
                  <label for="" class="form-label">Lote <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cmed_lote" id="sa_cmed_lote">
                </div>
                <div class="col-3" id="sa_cmed_caducidad_inputs" style="display: none;">
                  <label for="" class="form-label">Caducidad <label style="color: red;">*</label> </label>
                  <input type="date" class="form-control form-control-sm" name="sa_cmed_caducidad" id="sa_cmed_caducidad">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-4" id="sa_cmed_minimos_inputs" style="display: none;">
                  <label for="" class="form-label">Mínimos <label style="color: red;">*</label> </label>
                  <input type="number" class="form-control form-control-sm" name="sa_cmed_minimos" id="sa_cmed_minimos">
                </div>
                <div class="col-4" id="sa_cmed_stock_inputs" style="display: none;">
                  <label for="" class="form-label">Stock <label style="color: red;">*</label> </label>
                  <input type="number" class="form-control form-control-sm" name="sa_cmed_stock" id="sa_cmed_stock">
                </div>
                <div class="col-4" id="sa_cmed_movimiento_inputs" style="display: none;">
                  <label for="" class="form-label">Movimiento <label style="color: red;">*</label> </label>
                  <input type="number" class="form-control form-control-sm" name="sa_cmed_movimiento" id="sa_cmed_movimiento">
                </div>
              </div>

              <div class="row pt-3" id="sa_cmed_contraindicacion_inputs" style="display: none;">
                <div class="col-12">
                  <label for="" class="form-label">Contraindicación <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cmed_contraindicacion" id="sa_cmed_contraindicacion">
                </div>
              </div>

              <div class="row pt-3" id="sa_cmed_tratamientos_inputs" style="display: none;">
                <div class="col-12">
                  <label for="" class="form-label">Tratamiento <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cmed_tratamientos" id="sa_cmed_tratamientos">
                </div>
              </div>

              <div class="row pt-3" id="sa_cmed_uso_inputs" style="display: none;">
                <div class="col-12">
                  <label for="" class="form-label">Uso <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cmed_uso" id="sa_cmed_uso">
                </div>
              </div>

              <div class="row pt-3" id="sa_cmed_observaciones_inputs" style="display: none;">
                <div class="col-12">
                  <label for="" class="form-label">Observaciones <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cmed_observaciones" id="sa_cmed_observaciones">
                </div>
              </div>

              <hr class="mt-4">

              <div class="row pt-2" id="sa_cmed_es_jarabe_inputs" style="display: none;">
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cbx_jarabe">
                    <label class="form-check-label" for="cbx_jarabe">Es jarabe?</label>
                  </div>
                </div>
              </div>

              <div class="row pt-3" id="pnl_formula_jarabes" style="display: none;">

                <div class="col-3" id="sa_cmed_concentracion_inputs" style="display: none;">
                  <label for="" class="form-label">Concentración (ml)<label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm solo_numeros_int" name="sa_cmed_concentracion" id="sa_cmed_concentracion">
                </div>

                <div class="col-2" id="sa_cmed_dosis_inputs" style="display: none;">
                  <label for="" class="form-label">Dosis <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm solo_numeros_int" name="sa_cmed_dosis" id="sa_cmed_dosis">
                </div>

                <div class="col-2" id="sa_cmed_mg_inputs" style="display: none;">
                  <label for="" class="form-label">Mg <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm solo_numeros_int" name="sa_cmed_mg" id="sa_cmed_mg">
                </div>

                <div class="col-5" id="sa_cmed_formula_inputs">
                  <label for="" class="form-label">Fórmula <label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_cmed_formula" name="sa_cmed_formula">
                    <option selected disabled>-- Seleccione --</option>
                    <option value="formula_ibuprofeno_paracetamol">Fórmula Ibuprofeno y Paracetamol</option>
                    <option value="formula_loratadina_levocetirizina">Fórmula Loratadina y Levocetirizina</option>
                  </select>
                </div>
              </div>

              <div class="modal-footer pt-4">

                <?php if ($id == '') { ?>
                  <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                <?php } else { ?>
                  <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                  <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                <?php } ?>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--plugins-->

<script>
  $(document).ready(function() {
    $('#cbx_jarabe').change(function() {
      if ($(this).is(':checked')) {
        $('#pnl_formula_jarabes').show(); // Muestra el div con un efecto deslizante
      } else {
        $('#pnl_formula_jarabes').hide(); // Oculta el div con un efecto deslizante

        $('#sa_cmed_concentracion').prop('disabled', true).val('');
        $('#sa_cmed_dosis').prop('disabled', true).val('');
        $('#sa_cmed_mg').prop('disabled', true).val('');
        $('#sa_cmed_formula').prop('selectedIndex', 0);
      }
    });

    $('#sa_cmed_formula').change(function() {
      var valor = $(this).val();

      if (valor === 'formula_loratadina_levocetirizina') {
        $('#sa_cmed_concentracion').prop('disabled', true).val('0');
        $('#sa_cmed_dosis').prop('disabled', true).val('0');
        $('#sa_cmed_mg').prop('disabled', true).val('0');
      } else {
        $('#sa_cmed_concentracion').prop('disabled', false).val('5');
        $('#sa_cmed_dosis').prop('disabled', false).val('10');
        $('#sa_cmed_mg').prop('disabled', false).val('150');
      }
    });

  });
</script>