<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
  $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    <?php if (isset($_GET['_id'])) { ?>
      datos_col(<?= $_id ?>);
    <?php } ?>

  });

  function datos_col(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/ACTIVOS_FIJOS/localizacionC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // console.log(response);
        $('#txt_centro').val(response[0].CENTRO);
        $('#txt_empla').val(response[0].EMPLAZAMIENTO);
        $('#txt_deno').val(response[0].DENOMINACION);
        $('#id').val(response[0]._id);
      }
    });
  }

  function editar_insertar() {
    var cen = $('#txt_centro').val();
    var emp = $('#txt_empla').val();
    var den = $('#txt_deno').val();
    var id = $('#id').val();

    var parametros = {
      'id': id,
      'centro': cen,
      'empla': emp,
      'deno': den,
    }

    if ($("#form_localizacion").valid()) {
      // Si es válido, puedes proceder a enviar los datos por AJAX
      insertar(parametros);
    }
    //console.log(parametros);

  }

  function insertar(parametros) {
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/localizacionC.php?insertar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=localizacion';
          });
        } else if (response == -2) {
          //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
          $(txt_deno).addClass('is-invalid');
          $('#error_txt_deno').text('El nombre ya está en uso.');
        }
      },

      error: function(xhr, status, error) {
        console.log('Status: ' + status);
        console.log('Error: ' + error);
        console.log('XHR Response: ' + xhr.responseText);

        Swal.fire('', 'Error: ' + xhr.responseText, 'error');
      }
    });

    $('#txt_deno').on('input', function() {
      $('#error_txt_deno').text('');
    });
  }

  function delete_datos() {
    var id = '<?= $_id ?>';
    Swal.fire({
      title: 'Eliminar Registro?',
      text: "Esta seguro de eliminar este registro?",
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
  }

  function eliminar(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/ACTIVOS_FIJOS/localizacionC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=localizacion';
          });
        }
      }
    });
  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Localización</div>
      <?php
      //print_r($_SESSION['INICIO']);die(); 

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Agregar Localización
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
                if ($_id == '') {
                  echo 'Registrar Localización';
                } else {
                  echo 'Modificar Localización';
                }
                ?>
              </h5>

              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=localizacion" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>
            <hr>

            <form id="form_localizacion">

              <input type="hidden" name="id" id="id">

              <div class="row pt-3 mb-col">
                <div class="col-md-12">
                  <label for="txt_deno" class="form-label">Denominación </label>
                  <input type="text" class="form-control form-control-sm" id="txt_deno" name="txt_deno" maxlength="50">
                  <span id="error_txt_deno" class="text-danger"></span>
                </div>
              </div>

              <div class="row mb-col">
                <div class="col-md-6">
                  <label for="txt_empla" class="form-label">Emplazamiento </label>
                  <input type="text" class="form-control form-control-sm no_caracteres" id="txt_empla" name="txt_empla" maxlength="50">
                </div>

                <div class="col-md-6">
                  <label for="txt_centro" class="form-label">Centro </label>
                  <input type="text" class="form-control form-control-sm no_caracteres" id="txt_centro" name="txt_centro" maxlength="50">
                </div>
              </div>

              <div class="d-flex justify-content-end pt-2">

                <?php if ($_id == '') { ?>
                  <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                <?php } else { ?>
                  <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Editar</button>
                  <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                <?php } ?>
              </div>


            </form>

          </div>
        </div>
      </div>
    </div>
    <!--end row-->
  </div>
</div>

<script>
  //Validacion de formulario
  $(document).ready(function() {
    // Selecciona el label existente y añade el nuevo label

    agregar_asterisco_campo_obligatorio('txt_deno');
    agregar_asterisco_campo_obligatorio('txt_empla');
    agregar_asterisco_campo_obligatorio('txt_centro');

    $("#form_localizacion").validate({
      rules: {
        txt_deno: {
          required: true,
        },
        txt_empla: {
          required: true,
        },
        txt_centro: {
          required: true,
        },
      },

      highlight: function(element) {
        // Agrega la clase 'is-invalid' al input que falla la validación
        $(element).addClass('is-invalid');
        $(element).removeClass('is-valid');
      },
      unhighlight: function(element) {
        // Elimina la clase 'is-invalid' si la validación pasa
        $(element).removeClass('is-invalid');
        $(element).addClass('is-valid');

      }
    });
  });
</script>