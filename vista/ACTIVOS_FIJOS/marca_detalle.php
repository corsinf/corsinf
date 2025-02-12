<?php include('../../cabeceras/header2.php');
$id = '';
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
    $('#titulo').text('Editar marca');
    $('#op').text('Editar');
    var marcas = '';

    $.ajax({
      data: {
        id: id
      },
      url: '../../controlador/ACTIVOS_FIJOS/marcasC.php?lista=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        // console.log(response.datos);
        $('#codigo').val(response.datos[0].CODIGO);
        $('#descripcion').val(response.datos[0].DESCRIPCION);
        $('#id').val(response.datos[0].ID_MARCA);
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
      url: '../../controlador/ACTIVOS_FIJOS/marcasC.php?insertar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operaciopn realizada con exito.', 'success').then(function() {
            location.href = 'marcas.php';
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
      url: '../../controlador/ACTIVOS_FIJOS/marcasC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = 'marcas.php';
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
              <a href="marcas.php" class="btn btn-outline-secondary btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <input type="hidden" name="id" id="id" class="form-control" hidden="">
              Codigo de marca <br>
              <input type="input" name="codigo" id="codigo" class="form-control form-control-sm">
              Descripcion de marca<br>
              <input type="input" name="descripcion" id="descripcion" class="form-control form-control-sm">

            </div>
            <div class="col-sm-6">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary btn-sm" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
          <button class="btn btn-danger btn-sm" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>

        </div>
      </div>
    </div>
  </div>
</div>