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

    //alert(id);

    if (id != '') {
      datos_col(id);
    }

  });

  //listo
  function datos_col(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/insumosC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        $('#sa_cins_id').val(response[0].sa_cins_id);
        $('#sa_cins_codigo').val(response[0].sa_cins_codigo);
        $('#sa_cins_presentacion').val(response[0].sa_cins_presentacion);
        $('#sa_cins_lote').val(response[0].sa_cins_lote);
        $('#sa_cins_caducidad').val(fecha_nacimiento_formateada(response[0].sa_cins_caducidad.date));
        $('#sa_cins_minimos').val(response[0].sa_cins_minimos);
        $('#sa_cins_stock').val(response[0].sa_cins_stock);
        $('#sa_cins_movimiento').val(response[0].sa_cins_movimiento);
        $('#sa_cins_localizacion').val(response[0].sa_cins_localizacion);
        $('#sa_cins_uso').val(response[0].sa_cins_uso);
        $('#sa_cins_observaciones').val(response[0].sa_cins_observaciones);
      }
    });
  }

  function editar_insertar() {
    var sa_cins_id = $('#sa_cins_id').val();
    var sa_cins_codigo = $('#sa_cins_codigo').val();
    var sa_cins_presentacion = $('#sa_cins_presentacion').val();
    var sa_cins_lote = $('#sa_cins_lote').val();
    var sa_cins_caducidad = $('#sa_cins_caducidad').val();
    var sa_cins_minimos = $('#sa_cins_minimos').val();
    var sa_cins_stock = $('#sa_cins_stock').val();
    var sa_cins_movimiento = $('#sa_cins_movimiento').val();
    var sa_cins_localizacion = $('#sa_cins_localizacion').val();
    var sa_cins_uso = $('#sa_cins_uso').val();
    var sa_cins_observaciones = $('#sa_cins_observaciones').val();

    var parametros = {
      'sa_cins_id': sa_cins_id,
      'sa_cins_codigo': sa_cins_codigo,
      'sa_cins_presentacion': sa_cins_presentacion,
      'sa_cins_lote': sa_cins_lote,
      'sa_cins_caducidad': sa_cins_caducidad,
      'sa_cins_minimos': sa_cins_minimos,
      'sa_cins_stock': sa_cins_stock,
      'sa_cins_movimiento': sa_cins_movimiento,
      'sa_cins_localizacion': sa_cins_localizacion,
      'sa_cins_uso': sa_cins_uso,
      'sa_cins_observaciones': sa_cins_observaciones,
    }

    if (sa_cins_id == '') {
      if (sa_cins_presentacion == '') {
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
      if (sa_cins_presentacion == '') {
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
      url: '../controlador/insumosC.php?insertar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=7&acc=insumos';
          });
        } else if (response == -2) {
          Swal.fire('', 'Código ya regitrado', 'success');
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
      url: '../controlador/insumosC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
        var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'
        $('#tabla_').html(spiner);
      },
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=7&acc=insumos';
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
                echo 'Registrar Insumo';
              } else {
                echo 'Modificar Insumo';
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
                  echo 'Registrar Insumo';
                } else {
                  echo 'Modificar Insumo';
                }
                ?>
              </h5>
              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="../vista/inicio.php?mod=7&acc=insumos" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>
            <hr>

            <form action="" method="post">

              <input type="hidden" name="sa_cins_id" id="sa_cins_id">

              <div class="row pt-3">
                <div class="col-4">
                  <label for="" class="form-label">Código <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cins_codigo" id="sa_cins_codigo">
                </div>

                <div class="col-8">
                  <label for="" class="form-label">Presentación <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cins_presentacion" id="sa_cins_presentacion">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-3">
                  <label for="" class="form-label">Lote <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cins_lote" id="sa_cins_lote">
                </div>
                <div class="col-3">
                  <label for="" class="form-label">Caducidad <label style="color: red;">*</label> </label>
                  <input type="date" class="form-control form-control-sm" name="sa_cins_caducidad" id="sa_cins_caducidad">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-4">
                  <label for="" class="form-label">Mínimos <label style="color: red;">*</label> </label>
                  <input type="number" class="form-control form-control-sm" name="sa_cins_minimos" id="sa_cins_minimos">
                </div>
                <div class="col-4">
                  <label for="" class="form-label">Stock <label style="color: red;">*</label> </label>
                  <input type="number" class="form-control form-control-sm" name="sa_cins_stock" id="sa_cins_stock">
                </div>
                <div class="col-4">
                  <label for="" class="form-label">Movimiento <label style="color: red;">*</label> </label>
                  <input type="number" class="form-control form-control-sm" name="sa_cins_movimiento" id="sa_cins_movimiento">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-12">
                  <label for="" class="form-label">Localización <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cins_localizacion" id="sa_cins_localizacion">


                </div>
              </div>

              <div class="row pt-3">
                <div class="col-12">
                  <label for="" class="form-label">Uso <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cins_uso" id="sa_cins_uso">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-12">
                  <label for="" class="form-label">Observaciones <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" name="sa_cins_observaciones" id="sa_cins_observaciones">
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

<!--app JS-->
<!-- <script src="assets/js/app.js"></script> -->

<?php //include('../../../../cabeceras/footer.php'); 
?>