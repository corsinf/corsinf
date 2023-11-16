<?php //include('../../../../cabeceras/header.php');

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

  //listo
  function datos_col(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '<?= $url_general ?>/controlador/seccionC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        $('#sa_sec_id').val(response[0].sa_sec_id);
        $('#sa_sec_nombre').val(response[0].sa_sec_nombre);
        //$('#sa_sec_estado').val(response[0].sa_sec_estado);
      }
    });
  }

  function editar_insertar() {
    var sa_sec_id = $('#sa_sec_id').val();
    var sa_sec_nombre = $('#sa_sec_nombre').val();

    var parametros = {
      'sa_sec_id': sa_sec_id,
      'sa_sec_nombre': sa_sec_nombre,
    }

    if (sa_sec_id == '') {
      if (sa_sec_nombre == '') {
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
      if (sa_sec_nombre == '') {
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

    /*console.log(parametros);
    insertar(parametros);*/

  }

  function insertar(parametros) {
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '<?= $url_general ?>/controlador/seccionC.php?insertar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
              location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=seccion';
          });
          //location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=seccion';
        } else if (response == -2) {
          //Swal.fire('', 'codigo ya regitrado', 'success');
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

    //eliminar(id);

  }

  function eliminar(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '<?= $url_general ?>/controlador/seccionC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },
      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=seccion';
          });
          //location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=seccion';
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
                echo 'Registrar Sección';
              } else {
                echo 'Modificar Sección';
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
                  echo 'Registrar Sección';
                } else {
                  echo 'Modificar Sección';
                }
                ?>
              </h5>
              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=seccion" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>
            <hr>

            <form action="" method="post">
              <input type="hidden" id="sa_sec_id" name="sa_sec_id">

              <div class="row pt-3">
                <div class="col-md-12">
                  <label for="" class="form-label">Sección: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" id="sa_sec_nombre" name="sa_sec_nombre">
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