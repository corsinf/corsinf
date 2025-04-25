<?php /*include('../cabeceras/header.php');*/ $id = '';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} ?>
<script type="text/javascript">
  $(document).ready(function() {
    var id = '<?php echo $id; ?>';
    if (id != '') {
      datos_col(id);
    }

  });

  function datos_col(id) {
    $('#titulo').text('Editar clase_movimiento');
    $('#op').text('Editar');
    var clase_movimiento = '';

    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/ACTIVOS_FIJOS/clase_movimientoC.php?lista=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
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
    if (id == '') {
      if (codigo == '' || descri == '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todo los campos',
        })
      } else {
        insertar(parametros)
      }
    } else {
      if (codigo == '' || descri == '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todo los campos',
        })
      } else {
        insertar(parametros);
      }
    }
  }

  function insertar(parametros) {
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/clase_movimientoC.php?insertar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = 'inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>&acc=clase_movimiento';
          });
        } else if (response == -2) {
          Swal.fire('', 'codigo ya regitrado', 'info');
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
      url: '../controlador/ACTIVOS_FIJOS/clase_movimientoC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = 'inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>&acc=clase_movimiento';
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
      <div class="breadcrumb-title pe-3">Clase de movimiento</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">detalle Clase de movimeinto</li>
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
            <div class="container-fluid">
              <div class="row">
                <div class="col-sm-12">
                  <a href="inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>&acc=clase_movimiento" class="btn btn-outline-secondary btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <input type="hidden" name="id" id="id" class="form-control" hidden="">
                  Codigo clase_movimiento<br>
                  <input type="input" name="codigo" id="codigo" class="form-control form-control-sm">
                  Descripcion clase_movimiento<br>
                  <input type="input" name="descripcion" id="descripcion" class="form-control form-control-sm">

                </div>
                <div class="col-sm-6">


                </div>
              </div>
              <br>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary btn-sm" onclick="editar_insertar()" type="button" id="btn_editar"><i class="bx bx-save"></i> Guardar</button>
              <button class="btn btn-danger btn-sm" onclick="delete_datos()" type="button" id="btn_eliminar"><i class="bx bx-trash"></i> Eliminar</button>
            </div>


          </div>
        </div>
      </div>
    </div>
    <!--end row-->
  </div>
</div>

<?php //include('../cabeceras/footer.php'); 
?>