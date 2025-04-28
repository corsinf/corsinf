<?php
include(dirname(__DIR__, 3) . '/cabeceras/header2.php');

$id = '';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

?>
<script type="text/javascript">
  $(document).ready(function() {
    var id = '<?php echo $id; ?>';
    if (id != '') {
      datos_col(id);
    }

  });

  function datos_col(id) {
    var parametros = {
      'id': id,
      'query': '',
    }

    $.ajax({
      data: {
        parametros: parametros
      },
      url: 'controlador/ACTIVOS_FIJOS/familiasC.php?lista=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        $('#descripcion').val(response[0].detalle_familia);
        $('#id').val(response[0].id_familia);
      }
    });
  }

  function editar_insertar() {
    // var codigo = $('#codigo').val();
    var descri = $('#descripcion').val();
    var id = $('#id').val();

    var parametros = {
      // 'cod':codigo,
      'des': descri,
      'id': id,
    }

    if ($("#form_familias").valid()) {
      // Si es válido, puedes proceder a enviar los datos por AJAX
      insertar(parametros);
    }
  }

  function insertar(parametros) {
    $.ajax({
      data: {
        parametros: parametros
      },
      url: 'controlador/ACTIVOS_FIJOS/familiasC.php?insertar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = 'vista/ACTIVOS_FIJOS/FAMILIAS/ac_familias.php';
          });
        } else if (response == -2) {
          Swal.fire('', 'Código ya registrado.', 'warning');
        }

      }
    });

  }

  function delete_datos() {
    var id = '<?php echo $id; ?>';
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
      url: 'controlador/ACTIVOS_FIJOS/familiasC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = 'vista/ACTIVOS_FIJOS/FAMILIAS/ac_familias.php';
          });
        }

      }
    });

  }
</script>

<div class="row">
  <div class="col-xl-12 mx-auto">
    <div class="card">
      <div class="card-body">
        <div class="container-fluid">

          <div class="row">
            <div class="col-sm-12">
              <a href="vista/ACTIVOS_FIJOS/FAMILIAS/ac_familias.php" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
            </div>
          </div>

          <form id="form_familias">

            <input type="hidden" name="id" id="id" class="form-control" hidden="">

            <div class="row mb-col pt-3">
              <div class="col-md-6">
                <label for="descripcion" class="form-label">Descripción </label>
                <input type="text" class="form-control form-control-sm no_caracteres" id="descripcion" name="descripcion" maxlength="50">
              </div>
            </div>

            <div class="row mb-col">
              <div class="col-md-6 text-end">
                <?php if ($id == '') { ?>
                  <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                <?php } else { ?>
                  <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Editar</button>
                  <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                <?php } ?>
              </div>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  //Validacion de formulario
  $(document).ready(function() {
    agregar_asterisco_campo_obligatorio('descripcion');

    $("#form_familias").validate({
      rules: {
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