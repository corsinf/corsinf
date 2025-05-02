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
      url: '../controlador/ACTIVOS_FIJOS/clase_movimientoC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // console.log(response);
        $('#codigo').val(response[0].CODIGO);
        $('#descripcion').val(response[0].DESCRIPCION);
        $('#id').val(response[0].ID_MOVIMIENTO);
      }
    });
  }

  function editar_insertar() {
    var codigo = $('#codigo').val();
    var descri = $('#descripcion').val();
    var id = $('#id').val();

    var parametros = {
      'cod': codigo,
      'des': descri,
      'id': id,
    }

    if ($("#form_clase_movimiento").valid()) {
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
      url: '../controlador/ACTIVOS_FIJOS/clase_movimientoC.php?insertar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=clase_movimiento';
          });
        } else if (response == -2) {
          //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
          $(txt_deno).addClass('is-invalid');
          $('#error_codigo').text('El nombre ya está en uso.');
        }
      },

      error: function(xhr, status, error) {
        console.log('Status: ' + status);
        console.log('Error: ' + error);
        console.log('XHR Response: ' + xhr.responseText);

        Swal.fire('', 'Error: ' + xhr.responseText, 'error');
      }
    });

    $('#codigo').on('input', function() {
      $('#error_codigo').text('');
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
      url: '../controlador/ACTIVOS_FIJOS/clase_movimientoC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=clase_movimiento';
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
      <div class="breadcrumb-title pe-3">Clase de Movimiento</div>
      <?php
      //print_r($_SESSION['INICIO']);die(); 

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Agregar Clase de Movimiento
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
                  echo 'Registrar Clase de Movimiento';
                } else {
                  echo 'Modificar Clase de Movimiento';
                }
                ?>
              </h5>

              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=clase_movimiento" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>
            <hr>

            <form id="form_clase_movimiento">

              <input type="hidden" name="id" id="id">

              <div class="row pt-3 mb-col">
                <div class="col-md-12">
                  <label for="codigo" class="form-label">Código </label>
                  <input type="text" class="form-control form-control-sm" id="codigo" name="codigo" maxlength="50">
                  <span id="error_codigo" class="text-danger"></span>
                </div>
              </div>

              <div class="row mb-col">
                <div class="col-md-12">
                  <label for="descripcion" class="form-label">Descripción </label>
                  <input type="text" class="form-control form-control-sm no_caracteres" id="descripcion" name="descripcion" maxlength="50">
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

    agregar_asterisco_campo_obligatorio('codigo');
    agregar_asterisco_campo_obligatorio('descripcion');

    $("#form_clase_movimiento").validate({
      rules: {
        codigo: {
          required: true,
        },
        descripcion: {
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