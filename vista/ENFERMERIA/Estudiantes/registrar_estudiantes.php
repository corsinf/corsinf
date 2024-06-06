<?php

$id = '';
$id_seccion = '';
$id_grado = '';
$id_paralelo = '';
$id_representante = '';

if (isset($_POST['sa_est_id'])) {
  $id = $_POST['sa_est_id'];
}


if (isset($_POST['sa_sec_id'])) {
  $id_seccion = $_POST['sa_sec_id'];
}

if (isset($_POST['sa_gra_id'])) {
  $id_grado = $_POST['sa_gra_id'];
}

if (isset($_POST['sa_par_id'])) {
  $id_paralelo = $_POST['sa_par_id'];
}

if (isset($_POST['id_representante'])) {
  $id_representante = $_POST['id_representante'];
}

if (isset($_POST['id_representante_2'])) {
  $id_representante_2 = $_POST['id_representante_2'];
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>
<script src="../js/ENFERMERIA/estudiantes.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var id = '<?php echo $id; ?>';
    var id_seccion = '<?php echo $id_seccion; ?>';
    var id_grado = '<?php echo $id_grado; ?>';
    var id_paralelo = '<?php echo $id_paralelo; ?>';
    var id_representante = '<?php echo $id_representante; ?>';
    var id_representante_2 = '<?php echo $id_representante_2; ?>';

    //alert(id)

    if (id != '') {
      datos_col(id);
    }

    //Carga los datos para la edicion
    consultar_datos_seccion(id = '', id_seccion);
    consultar_datos_seccion_grado(id_grado, id_seccion);
    consultar_datos_grado_paralelo(id_grado, id_paralelo);
    consultar_representante(id_representante);
    consultar_representante_2(id_representante_2)

  });

  //Para cargar los datos en el select
  function consultar_datos_seccion(id = '', id_seccion) {
    var seccion = '';

    //console.log(id_seccion);
    seccion = '<option selected disabled>-- Seleccione --</option>'
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/seccionC.php?listar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        //console.log(response);

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

  function consultar_datos_seccion_grado(id_grado = '', id_seccion = '') {
    /*///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Para Buscar el Grado con la Seccion

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    id_sec_varia = ''

    if (id_seccion == '') {
      id_seccion = $("#sa_id_seccion").val();

      id_sec_varia = id_seccion;
    }

    if (id_grado == '') {
      id_grado = $("#sa_id_grado").val();
    }

    var grado = '';
    grado = '<option selected disabled>-- Seleccione --</option>'
    $.ajax({
      data: {
        "id_seccion": id_seccion
      },
      url: '../controlador/paraleloC.php?listar_seccion_grado=true',
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

        //$('#sa_id_paralelo').html('<option selected disabled>-- Seleccione --</option>');

        $('#sa_id_grado').html(grado);

      }
    });



  }

  function consultar_datos_grado_paralelo(id_grado = '', id_paralelo = '') {
    /*///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Para Buscar el Paralelo con la Grado

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    if (id_paralelo == '') {
      id_paralelo = $("#sa_id_paralelo").val();
    }

    if (id_grado == '') {
      id_grado = $("#sa_id_grado").val();
    }

    var grado = '';
    paralelo = '<option selected disabled>-- Seleccione --</option>'
    $.ajax({
      data: {
        "id_grado": id_grado
      },
      url: '../controlador/paraleloC.php?listar_grado_paralelo=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          //console.log(item);

          if (id_paralelo == item.sa_par_id) {
            // Marca la opción correspondiente con el atributo 'selected'
            paralelo += '<option value="' + item.sa_par_id + '" selected>' + item.sa_par_nombre + '</option>';
          } else {
            paralelo += '<option value="' + item.sa_par_id + '">' + item.sa_par_nombre + '</option>';
          }

        });

        $('#sa_id_paralelo').html(paralelo);

      }
    });
  }

  function consultar_representante(id = '') {

    if (id != '') {
      //alert(id)
      $.ajax({
        data: {
          "id": id
        },

        url: '../controlador/representantesC.php?listar=true',

        type: 'post', //método de envio
        dataType: 'json',
        success: function(dato) { //una vez que el archivo recibe el request lo procesa y lo devuelve         
          //console.log(dato);
          let etiquetas = "";
          dato.forEach(function(item, itema, items) {
            etiquetas += '<option value="' + item.sa_rep_id + '">' + item.sa_rep_cedula + ' - ' + item.sa_rep_primer_apellido + ' ' + item.sa_rep_segundo_apellido + ' ' + item.sa_rep_primer_nombre + ' ' + item.sa_rep_segundo_nombre + '</option>';
          })

          $("#sa_id_representante").html(etiquetas);
        }
      });
    }

    $('#sa_id_representante').select2({
      placeholder: 'Selecciona una opción',
      language: {
        inputTooShort: function() {
          return "Por favor ingresa 1 o más caracteres";
        },
        noResults: function() {
          return "No se encontraron resultados";
        },
        searching: function() {
          return "Buscando...";
        },
        errorLoading: function() {
          return "No se encontraron resultados";
        }
      },
      minimumInputLength: 1,
      ajax: {
        url: '../controlador/representantesC.php?listar_todo=true',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            searchTerm: params.term // Envía el término de búsqueda al servidor
          };
        },
        processResults: function(data, params) { // Agrega 'params' como parámetro
          var searchTerm = params.term.toLowerCase();

          var options = data.reduce(function(filtered, item) {

            var fullName = item['sa_rep_cedula'] + " - " + item['sa_rep_primer_apellido'] + " " + item['sa_rep_segundo_apellido'] + " " + item['sa_rep_primer_nombre'] + " " + item['sa_rep_segundo_nombre'];

            if (fullName.toLowerCase().includes(searchTerm)) {
              filtered.push({
                id: item['sa_rep_id'],
                text: fullName
              });
            }

            return filtered;
          }, []);

          return {
            results: options
          };
        },
        cache: true
      }
    });
  }

  function consultar_representante_2(id = '') {

    if (id != '') {
      //alert(id)
      $.ajax({
        data: {
          "id": id
        },

        url: '../controlador/representantesC.php?listar=true',

        type: 'post', //método de envio
        dataType: 'json',
        success: function(dato) { //una vez que el archivo recibe el request lo procesa y lo devuelve         
          //console.log(dato);
          let etiquetas = "";
          dato.forEach(function(item, itema, items) {
            etiquetas += '<option value="' + item.sa_rep_id + '">' + item.sa_rep_cedula + ' - ' + item.sa_rep_primer_apellido + ' ' + item.sa_rep_segundo_apellido + ' ' + item.sa_rep_primer_nombre + ' ' + item.sa_rep_segundo_nombre + '</option>';
          })

          $("#sa_id_representante_2").html(etiquetas);
        }
      });
    }

    $('#sa_id_representante_2').select2({
      placeholder: 'Selecciona una opción',
      language: {
        inputTooShort: function() {
          return "Por favor ingresa 1 o más caracteres";
        },
        noResults: function() {
          return "No se encontraron resultados";
        },
        searching: function() {
          return "Buscando...";
        },
        errorLoading: function() {
          return "No se encontraron resultados";
        }
      },
      minimumInputLength: 1,
      ajax: {
        url: '../controlador/representantesC.php?listar_todo=true',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            searchTerm: params.term // Envía el término de búsqueda al servidor
          };
        },
        processResults: function(data, params) { // Agrega 'params' como parámetro
          var searchTerm = params.term.toLowerCase();

          var options = data.reduce(function(filtered, item) {

            var fullName = item['sa_rep_cedula'] + " - " + item['sa_rep_primer_apellido'] + " " + item['sa_rep_segundo_apellido'] + " " + item['sa_rep_primer_nombre'] + " " + item['sa_rep_segundo_nombre'];

            if (fullName.toLowerCase().includes(searchTerm)) {
              filtered.push({
                id: item['sa_rep_id'],
                text: fullName
              });
            }

            return filtered;
          }, []);

          return {
            results: options
          };
        },
        cache: true
      }
    });
  }

  function datos_col(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/estudiantesC.php?listar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {

        $('#sa_est_id').val(response[0].sa_est_id);
        $('#sa_est_primer_apellido').val(response[0].sa_est_primer_apellido);
        $('#sa_est_segundo_apellido').val(response[0].sa_est_segundo_apellido);
        $('#sa_est_primer_nombre').val(response[0].sa_est_primer_nombre);
        $('#sa_est_segundo_nombre').val(response[0].sa_est_segundo_nombre);

        $('#sa_est_cedula').val(response[0].sa_est_cedula);

        select_genero(response[0].sa_est_sexo, '#sa_est_sexo');

        $('#sa_est_fecha_nacimiento').val((response[0].sa_est_fecha_nacimiento));
        $('#sa_est_edad').val(calcular_edad_fecha_nacimiento(response[0].sa_est_fecha_nacimiento));
        ///////////////////////////////////////////////////////////////////////////////////////////

        $('#sa_est_correo').val(response[0].sa_est_correo);
        //$('#sa_id_representante').val(response[0].sa_id_representante);

        select_parentesco(response[0].sa_est_rep_parentesco, '#sa_est_rep_parentesco');

        //$('#sa_id_representante_2').val(response[0].sa_id_representante_2);

        select_parentesco(response[0].sa_est_rep_parentesco_2, '#sa_est_rep_parentesco_2');

        $('#sa_est_direccion').val(response[0].sa_est_direccion);

        //$('#sa_id_seccion').val(response[0].sa_id_seccion);
        //$('#sa_id_grado').val(response[0].sa_id_grado);
        //$('#sa_id_paralelo').val(response[0].sa_id_paralelo);

        $('#sa_sec_id').val(response[0].sa_sec_id);
        $('#sa_gra_id').val(response[0].sa_gra_id);
        $('#sa_par_id').val(response[0].sa_par_id);

      }
    });
  }

  function editar_insertar() {

    var sa_est_id = $('#sa_est_id').val();
    var sa_est_primer_apellido = $('#sa_est_primer_apellido').val();
    var sa_est_segundo_apellido = $('#sa_est_segundo_apellido').val();
    var sa_est_primer_nombre = $('#sa_est_primer_nombre').val();
    var sa_est_segundo_nombre = $('#sa_est_segundo_nombre').val();
    var sa_est_cedula = $('#sa_est_cedula').val();
    var sa_est_sexo = $('#sa_est_sexo').val();
    var sa_est_fecha_nacimiento = $('#sa_est_fecha_nacimiento').val();
    var sa_id_seccion = $('#sa_id_seccion').val();
    var sa_id_grado = $('#sa_id_grado').val();
    var sa_id_paralelo = $('#sa_id_paralelo').val();
    var sa_id_representante = $('#sa_id_representante').val();
    var sa_est_rep_parentesco = $('#sa_est_rep_parentesco').val();
    var sa_est_correo = $('#sa_est_correo').val();

    var sa_id_representante_2 = $('#sa_id_representante_2').val();
    var sa_est_rep_parentesco_2 = $('#sa_est_rep_parentesco_2').val();
    var sa_est_direccion = $('#sa_est_direccion').val();

    var parametros = {

      'sa_est_id': sa_est_id,
      'sa_est_primer_apellido': sa_est_primer_apellido,
      'sa_est_segundo_apellido': sa_est_segundo_apellido,
      'sa_est_primer_nombre': sa_est_primer_nombre,
      'sa_est_segundo_nombre': sa_est_segundo_nombre,
      'sa_est_cedula': sa_est_cedula,
      'sa_est_sexo': sa_est_sexo,
      'sa_est_fecha_nacimiento': sa_est_fecha_nacimiento,
      'sa_id_seccion': sa_id_seccion,
      'sa_id_grado': sa_id_grado,
      'sa_id_paralelo': sa_id_paralelo,
      'sa_id_representante': sa_id_representante,
      'sa_est_rep_parentesco': sa_est_rep_parentesco,
      'sa_est_correo': sa_est_correo,
      'sa_id_representante_2': sa_id_representante_2,
      'sa_est_rep_parentesco_2': sa_est_rep_parentesco_2,
      'sa_est_direccion': sa_est_direccion,
    };

    //alert(validar_email(sa_est_correo));
    console.log(parametros);

    if (sa_est_id == '') {
      if (
        sa_est_primer_apellido === '' ||
        sa_est_segundo_apellido === '' ||
        sa_est_primer_nombre === '' ||
        sa_est_segundo_nombre === '' ||
        sa_est_cedula === '' ||
        sa_est_sexo == null ||
        sa_est_fecha_nacimiento === '' ||
        sa_id_seccion == null ||
        sa_id_grado == null ||
        sa_id_paralelo == null ||
        validar_email(sa_est_correo) == false ||
        sa_id_representante == null ||
        sa_est_rep_parentesco == null

      ) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todos los campos',
        })

      } else {
        //console.log(parametros);
        insertar(parametros)
      }
    } else {
      if (
        sa_est_primer_apellido === '' ||
        sa_est_segundo_apellido === '' ||
        sa_est_primer_nombre === '' ||
        sa_est_segundo_nombre === '' ||
        sa_est_cedula === '' ||
        sa_est_sexo == null ||
        sa_est_fecha_nacimiento === '' ||
        sa_id_seccion == null ||
        sa_id_grado == null ||
        sa_id_paralelo == null ||
        validar_email(sa_est_correo) == false ||
        sa_id_representante == null ||
        sa_est_rep_parentesco == null
      ) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todos los campos',
        })
      } else {
        //console.log(parametros);
        insertar(parametros);
      }
    }
  }

  function insertar(parametros) {
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/estudiantesC.php?insertar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=7&acc=estudiantes';
          });
        } else if (response == -2) {
          Swal.fire('', 'Cédula ya registrada.', 'warning');
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
      url: '../controlador/estudiantesC.php?eliminar=true',
      type: 'post',
      dataType: 'json',

      success: function(response) {
        if (response == 1) {
          Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
            location.href = '../vista/inicio.php?mod=7&acc=estudiantes';
          });
        }
      }
    });
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////

  function edad_normal(fecha_nacimiento) {
    $('#sa_est_edad').val(calcular_edad_fecha_nacimiento(fecha_nacimiento));
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
                  <a href="../vista/inicio.php?mod=7&acc=estudiantes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                </div>
              </div>
            </div>

            <hr>

            <form action="" method="post">

              <input type="hidden" id="sa_est_id" name="sa_est_id">
              <input type="hidden" id="sa_sec_id" name="sa_sec_id">
              <input type="hidden" id="sa_gra_id" name="sa_gra_id">
              <input type="hidden" id="sa_par_id" name="sa_par_id">

              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">Primer Apellido <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" id="sa_est_primer_apellido" name="sa_est_primer_apellido">
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Segundo Apellido <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" id="sa_est_segundo_apellido" name="sa_est_segundo_apellido">
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Primer Nombre <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" id="sa_est_primer_nombre" name="sa_est_primer_nombre">
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Segundo Nombre <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" id="sa_est_segundo_nombre" name="sa_est_segundo_nombre">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">Cédula de Identidad <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" id="sa_est_cedula" name="sa_est_cedula" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <div class="col-md-2">
                  <label for="" class="form-label">Sexo <label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_est_sexo" name="sa_est_sexo">
                    <option selected disabled>-- Seleccione --</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino">Masculino</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Fecha de Nacimiento <label style="color: red;">*</label> </label>
                  <input type="date" class="form-control form-control-sm" id="sa_est_fecha_nacimiento" name="sa_est_fecha_nacimiento" onchange="edad_normal(this.value);">
                </div>

                <div class="col-md-1">
                  <label for="" class="form-label">Edad <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" id="sa_est_edad" name="sa_est_edad" readonly>
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Correo <label style="color: red;">*</label> </label>
                  <input type="email" class="form-control form-control-sm" id="sa_est_correo" name="sa_est_correo">
                </div>

              </div>

              <div class="row pt-4">

                <div class="col-md-4">
                  <label for="" class="form-label">Sección <label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_id_seccion" name="sa_id_seccion" onchange="consultar_datos_seccion_grado()">

                  </select>
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Grado <label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_id_grado" name="sa_id_grado" onchange="consultar_datos_grado_paralelo();">
                    <option selected disabled>-- Seleccione --</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Paralelo <label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_id_paralelo" name="sa_id_paralelo">
                    <option selected disabled>-- Seleccione --</option>
                  </select>
                </div>

              </div>


              <div class="row pt-4">
                <div class="col-md-8">
                  <label for="" class="form-label">Representante <label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_id_representante" name="sa_id_representante">
                    <option selected disabled>-- Seleccione --</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Parentesco <label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_est_rep_parentesco" name="sa_est_rep_parentesco">
                    <option selected disabled>-- Seleccione --</option>
                    <option value="Padre">Padre</option>
                    <option value="Madre">Madre</option>
                    <option value="Hermano">Hermano/a</option>
                    <option value="Tio">Tío/a</option>
                    <option value="Primo">Primo/a</option>
                    <option value="Abuelo/a">Abuelo/a</option>
                    <option value="Otro">Otro/a</option>
                  </select>
                </div>
              </div>

              <div class="row pt-4">
                <div class="col-md-8">
                  <label for="" class="form-label">Representante 2<label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_id_representante_2" name="sa_id_representante_2">
                    <option selected disabled>-- Seleccione --</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Parentesco <label style="color: red;">*</label> </label>
                  <select class="form-select form-select-sm" id="sa_est_rep_parentesco_2" name="sa_est_rep_parentesco_2">
                    <option selected disabled>-- Seleccione --</option>
                    <option value="Padre">Padre</option>
                    <option value="Madre">Madre</option>
                    <option value="Hermano">Hermano/a</option>
                    <option value="Tio">Tío/a</option>
                    <option value="Primo">Primo/a</option>
                    <option value="Abuelo/a">Abuelo/a</option>
                    <option value="Otro">Otro/a</option>
                  </select>
                </div>
              </div>


              <div class="row pt-4">
                <div class="col-md-12">
                  <label for="" class="form-label">Dirección <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control form-control-sm" id="sa_est_direccion" name="sa_est_direccion">
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