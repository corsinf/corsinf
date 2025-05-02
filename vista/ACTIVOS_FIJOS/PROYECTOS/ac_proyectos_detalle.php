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
      url: '../controlador/ACTIVOS_FIJOS/proyectosC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // console.log(response);
        $('#codigo').val(response[0].id);
        $('#txt_fin').val(response[0].pro);
        $('#txt_enti').val(response[0].enti);
        $('#txt_deno').val(response[0].deno);
        $('#txt_descri').val(response[0].desc);
        $('#txt_valde').val(response[0].valde);
        $('#txt_vala').val(response[0].vala);
        $('#txt_expi').val(response[0].exp);
      }
    });
  }

  function editar_insertar() {
    var id = $('#codigo').val();
    var fin = $('#txt_fin').val();
    var ent = $('#txt_enti').val();
    var den = $('#txt_deno').val();
    var des = $('#txt_descri').val();
    var val = $('#txt_valde').val();
    var vla = $('#txt_vala').val();
    var exp = $('#txt_expi').val();

    var parametros = {
      'id': id,
      'fin': fin,
      'ent': ent,
      'den': den,
      'des': des,
      'val': val,
      'vla': vla,
      'exp': exp,
    }

    if ($("#form_proyectos").valid()) {
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
      url: '../controlador/ACTIVOS_FIJOS/proyectosC.php?insertar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=proyectos';
          });
        } else if (response == -2) {
          //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
          $(txt_fin).addClass('is-invalid');
          $('#error_txt_fin').text('El nombre ya está en uso.');
        }
      },

      error: function(xhr, status, error) {
        console.log('Status: ' + status);
        console.log('Error: ' + error);
        console.log('XHR Response: ' + xhr.responseText);

        Swal.fire('', 'Error: ' + xhr.responseText, 'error');
      }
    });

    $('#txt_fin').on('input', function() {
      $('#error_txt_fin').text('');
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
      url: '../controlador/ACTIVOS_FIJOS/proyectosC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=proyectos';
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
      <div class="breadcrumb-title pe-3">Proyectos</div>
      <?php
      //print_r($_SESSION['INICIO']);die(); 

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Agregar Proyectos
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
                  echo 'Registrar Proyectos';
                } else {
                  echo 'Modificar Proyectos';
                }
                ?>
              </h5>

              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=proyectos" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>

            <hr>

            <form id="form_proyectos">

              <input type="hidden" name="codigo" id="codigo">

              <div class="row pt-3 mb-col">
                <div class="col-md-3">
                  <label for="txt_fin" class="form-label">Financiación </label>
                  <input type="text" class="form-control form-control-sm no_caracteres" id="txt_fin" name="txt_fin" maxlength="50">
                  <span id="error_txt_fin" class="text-danger"></span>
                </div>

                <div class="col-md-9">
                  <label for="txt_enti" class="form-label">Entidad </label>
                  <input type="text" class="form-control form-control-sm no_caracteres" id="txt_enti" name="txt_enti" maxlength="50">
                </div>
              </div>

              <div class="row mb-col">
                <div class="col-md-6">
                  <label for="txt_deno" class="form-label">Denominación </label>
                  <input type="text" class="form-control form-control-sm no_caracteres" id="txt_deno" name="txt_deno" maxlength="50">
                </div>

                <div class="col-md-6">
                  <label for="txt_descri" class="form-label">Descripción </label>
                  <input type="text" class="form-control form-control-sm no_caracteres" id="txt_descri" name="txt_descri" maxlength="50">
                </div>
              </div>

              <div class="row mb-col">
                <div class="col-md-4">
                  <label for="txt_valde" class="form-label">Validez de </label>
                  <input type="date" class="form-control form-control-sm" id="txt_valde" name="txt_valde" maxlength="50">
                </div>

                <div class="col-md-4">
                  <label for="txt_vala" class="form-label">Validez a </label>
                  <input type="date" class="form-control form-control-sm" id="txt_vala" name="txt_vala" maxlength="50">
                </div>

                <div class="col-md-4">
                  <label for="txt_expi" class="form-label">Expiracion </label>
                  <input type="date" class="form-control form-control-sm" id="txt_expi" name="txt_expi" maxlength="50">
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

    agregar_asterisco_campo_obligatorio('txt_fin');
    agregar_asterisco_campo_obligatorio('txt_enti');
    agregar_asterisco_campo_obligatorio('txt_deno');
    agregar_asterisco_campo_obligatorio('txt_descri');
    agregar_asterisco_campo_obligatorio('txt_valde');
    agregar_asterisco_campo_obligatorio('txt_vala');
    agregar_asterisco_campo_obligatorio('txt_expi');

    $("#form_proyectos").validate({
      rules: {
        txt_fin: {
          required: true,
        },
        txt_enti: {
          required: true,
        },
        txt_deno: {
          required: true,
        },
        txt_descri: {
          required: true,
        },
        txt_valde: {
          required: true,
        },
        txt_vala: {
          required: true,
        },
        txt_expi: {
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