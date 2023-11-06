<?php //include('../../../../cabeceras/header.php');

$dominio = $_SERVER['SERVER_NAME'];
$url_general = 'http://' . $dominio . '/corsinf';

$id = '';
$id_seccion = '';
$id_grado = '';
$id_paralelo = '';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

if (isset($_GET['id_seccion'])) {
  $id_seccion = $_GET['id_seccion'];
}

if (isset($_GET['id_grado'])) {
  $id_grado = $_GET['id_grado'];
}

if (isset($_GET['id_paralelo'])) {
  $id_paralelo = $_GET['id_paralelo'];
}


?>

<script type="text/javascript">
  $(document).ready(function() {
    var id = '<?php echo $id; ?>';
    var id_seccion = '<?php echo $id_seccion; ?>';
    var id_grado = '<?php echo $id_grado; ?>';
    var id_paralelo = '<?php echo $id_paralelo; ?>';

    if (id != '') {
      datos_col(id);
    }

    consultar_datos_seccion(id = '', id_seccion);
    //consultar_datos_seccion_grado();

  });

  //Para cargar los datos en el select
  function consultar_datos_seccion(id = '', id_seccion = '') {
    var seccion = '';
    //console.log(id_seccion);
    seccion = '<option selected disabled>-- Seleccione --</option>'
    $.ajax({
      data: {
        id: id
      },
      url: '<?php echo $url_general ?>/controlador/seccionC.php?listar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          //console.log(item);

          if (id_seccion == item.sa_sec_id) {
            // Marca la opción correspondiente con el atributo 'selected'
            seccion += '<option value="' + item.sa_sec_id + '" selected>' + item.sa_sec_nombre + '</option>';
          } else {
            seccion += '<option value="' + item.sa_sec_id + '">' + item.sa_sec_nombre + '</option>';
          }

        });

        $('#sa_id_seccion').html(seccion);

        // Marca la opción correspondiente si el ID coincide

      }
    });
  }


  function consultar_datos_seccion_grado() {
    /*///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Para Buscar el Grado con la Seccion

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

    var id_grado = $("#sa_id_seccion").val();
    var id = '';

    var grado = '';
    grado = '<option selected disabled>-- Seleccione --</option>'
    $.ajax({
      data: {
        "id_grado": id_grado
      },
      url: '<?php echo $url_general ?>/controlador/estudiantesC.php?listar_seccion_grado=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          //console.log(item);

          if (id_grado == item.sa_gra_id) {
            // Marca la opción correspondiente con el atributo 'selected'
            grado += '<option value="' + item.sa_gra_id + '" selected>' + item.sa_gra_nombre + '</option>';
          } else {
            grado += '<option value="' + item.sa_gra_id + '">' + item.sa_gra_nombre + '</option>';
          }

        });

        $('#sa_id_grado').html(grado);

      }
    });

    //alert(id_grado)

  }

  function datos_col(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '<?= $url_general ?>/controlador/estudiantesC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {

        $('#sa_est_id').val(response[0].sa_est_id);
        $('#sa_est_primer_apellido').val(response[0].sa_est_primer_apellido);
        $('#sa_est_segundo_apellido').val(response[0].sa_est_segundo_apellido);
        $('#sa_est_primer_nombre').val(response[0].sa_est_primer_nombre);
        $('#sa_est_segundo_nombre').val(response[0].sa_est_segundo_nombre);

        $('#sa_est_cedula').val(response[0].sa_est_cedula);
        $('#sa_est_sexo').val(response[0].sa_est_sexo); //p
        $('#sa_est_fecha_nacimiento').val(response[0].sa_est_fecha_nacimiento);
        $('#sa_est_correo').val(response[0].sa_est_correo);
        $('#sa_id_representante').val(response[0].sa_id_representante);

        $('#sa_id_seccion').val(response[0].sa_id_seccion);
        $('#sa_id_grado').val(response[0].sa_id_grado);
        $('#sa_id_paralelo').val(response[0].sa_id_paralelo);


        $('#sa_sec_id').val(response[0].cs.sa_sec_id);
        $('#sa_sec_nombre').val(response[0].cs.sa_sec_nombre);
        $('#sa_gra_id').val(response[0].cg.sa_gra_id);
        $('#sa_gra_nombre').val(response[0].cg.sa_gra_nombre);
        $('#sa_par_id').val(response[0].pr.sa_par_id);
        $('#sa_par_nombre').val(response[0].pr.sa_par_nombre);

      }
    });
  }

  function editar_insertar() {
    var sa_par_id = $('#sa_par_id').val();
    var sa_par_nombre = $('#sa_par_nombre').val();
    var sa_id_seccion = $('#sa_id_seccion').val();
    var sa_id_grado = $('#sa_id_grado').val();

    var parametros = {
      'sa_par_id': sa_par_id,
      'sa_par_nombre': sa_par_nombre,
      'sa_id_seccion': sa_id_seccion,
      'sa_id_grado': sa_id_grado,
    }

    //alert(sa_id_seccion)

    if (sa_par_id == '') {
      if (sa_par_nombre == '' || sa_id_seccion == null || sa_id_grado == null) {
        /*Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todo los campos',
        })*/
        alert('error');
      } else {
        insertar(parametros)
      }
    } else {
      if (sa_par_nombre == '' || sa_id_seccion == null || sa_id_grado == null) {
        /*Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todo los campos',
        })*/
        alert('error');
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
      url: '<?= $url_general ?>/controlador/estudiantesC.php?insertar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          /*wal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=estudiantes';
          });*/
          location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=estudiantes';
        } else if (response == -2) {
          //Swal.fire('', 'codigo ya regitrado', 'success');
        }
      }
    });
  }

  function delete_datos() {
    var id = '<?php echo $id; ?>';
    /*Swal.fire({
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
    })*/

    eliminar(id);

  }

  function eliminar(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '<?= $url_general ?>/controlador/estudiantesC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
        var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'
        $('#tabla_').html(spiner);
      },
      success: function(response) {
        if (response == 1) {
          /*Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=estudiantes';
          });*/
          location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=estudiantes';
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
                echo 'Registrar Estudiantes';
              } else {
                echo 'Modificar Estudiantes';
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
                  echo 'Registrar Estudiantes';
                } else {
                  echo 'Modificar Estudiantes';
                }
                ?>
              </h5>
              <div class="row m-2">
                <div class="col-sm-12">
                  <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=estudiantes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>
            <hr>

            <form action="" method="post">

              <input type="hidden" id="sa_est_id" name="sa_est_id">

              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">Primer Apellido: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="sa_est_primer_apellido" name="sa_est_primer_apellido">
                </div>
                <div class="col-md-3">
                  <label for="" class="form-label">Segundo Apellido: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="sa_est_segundo_apellido" name="sa_est_segundo_apellido">
                </div>
                <div class="col-md-3">
                  <label for="" class="form-label">Primer Nombre: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="sa_est_primer_nombre" name="sa_est_primer_nombre">
                </div>
                <div class="col-md-3">
                  <label for="" class="form-label">Segundo Nombre: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="sa_est_segundo_nombre" name="sa_est_segundo_nombre">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">Cédula de Identidad <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="sa_est_cedula" name="sa_est_cedula">
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Sexo: <label style="color: red;">*</label> </label>
                  <select class="form-select" id="" name="">
                    <option selected>-- Seleccione --</option>
                    <option value="">Femenino</option>
                    <option value="">Masculino</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Fecha de Nacimiento: <label style="color: red;">*</label> </label>
                  <input type="date" class="form-control" id="sa_est_fecha_nacimiento" name="sa_est_fecha_nacimiento">
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Edad: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>

              </div>


              <div class="row pt-3">

                <div class="col-md-6">
                  <label for="" class="form-label">Sección: <label style="color: red;">*</label> </label>
                  <select class="form-select" id="sa_id_seccion" name="sa_id_seccion" onclick="consultar_datos_seccion_grado()">

                  </select>
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-md-6">
                  <label for="" class="form-label">Grado: <label style="color: red;">*</label> </label>
                  <select class="form-select" id="sa_id_grado" name="sa_id_grado">
                    <option selected disabled>-- Seleccione --</option>
                  </select>
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-md-6">
                  <label for="" class="form-label">Paralelo: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="sa_par_nombre" name="sa_par_nombre">
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