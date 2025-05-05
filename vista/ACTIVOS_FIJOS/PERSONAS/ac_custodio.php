<?php //include('../cabeceras/header.php'); 

/**
 * @todo Revisar este archivo
 * @note Actualmente se mantiene como respaldo
 * @warning No modificar este archivo sin autorización.
 */

?>
<script type="text/javascript">
  $(document).ready(function() {
    consultar_datos();
  });

  function consultar_datos(id = '') {
    var custodio = '';
    var custodio1 = '';
    var parametros = {
      'id': id,
      'pag': $('#txt_pag').val(),
      'buscar': $('#txt_buscar').val(),
    }

    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/custodioC.php?buscar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        // console.log(response);
        var pag = $('#txt_pag1').val().split('-');
        var pag2 = $('#txt_pag').val().split('-');

        var pagi = '<li class="page-item" onclick="guias_pag(\'-\')"><a class="page-link" href="#"> << </a></li>';
        if ($('#txt_numpag').val() == '') {
          $('#txt_numpag').val(response.cant / pag[1]);
        }
        if (response.cant > pag[1]) {
          var num = response.cant / pag[1];
          if (num > 10) {
            if (pag2[1] / pag[1] <= 10) {
              for (var i = 1; i < 11; i++) {
                var pos = pag[1] * i;
                var ini = pos - pag[1];
                var pa = ini + '-' + pos;
                if ($('#txt_pag').val() == pa) {
                  pagi += '<li class="page-item active" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                } else {
                  pagi += '<li class="page-item" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                }
              }
            } else {

              pagi += '<li class="page-item" onclick="paginacion(\'0-25\')"><a class="page-link" href="#">1</a></li>';
              for (var i = pag2[1] / 25; i < (pag2[1] / 25) + 10; i++) {
                var pos = pag[1] * i;
                var ini = pos - pag[1];
                var pa = ini + '-' + pos;
                if ($('#txt_pag').val() == pa) {
                  pagi += '<li class="page-item active" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                } else {
                  pagi += '<li class="page-item" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                }
              }
            }
            pagi += '<li class="page-item" onclick="guias_pag(\'+\')"><a class="page-link" href="#"> >> </a></li>'
          } else {

            for (var i = 1; i < num + 1; i++) {
              var pos = pag[1] * i;
              var ini = pag[1] - pos;
              var pa = ini + '-' + pos;
              if ($('#txt_pag').val() == pa) {
                pagi += '<li class="page-item active"  onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
              } else {
                pagi += '<li class="page-item"  onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
              }
            }
          }

          $('#pag').html(pagi);

        }


        $.each(response.datos, function(i, item) {
          // console.log(item);
          if (item.FOTO == '' || item.FOTO == null) {
            foto = '../img/sin_imagen.jpg';
          } else {
            foto = item.FOTO;
          }
          let puesto = '';
          if (item.PUESTO != '' && item.PUESTO != null) {
            puesto = item.PUESTO;
          }

          custodio += '<div class="col">' +
            '<div class="card radius-15">' +
            '<div class="card-body text-center">' +
            '<div class="p-4 border radius-15">' +
            '<img src="' + foto + '" width="110" height="110" class="rounded-circle shadow" alt="">' +
            '<h5 class="mb-0 mt-5">' + item.PERSON_NOM + '</h5>' +
            '<p class="mb-3">' + puesto + '</p>' +
            '<div class="d-grid"><a href="inicio.php?acc=custodio_detalle&id=' + item.ID_PERSON + '" class="btn btn-outline-primary radius-15"> Ver Perfil </a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';


        });
        $('#tbl_datos').html(custodio);
      }
    });
  }

  function datos_col(id) {
    $('#titulo').text('Editar custodio');
    $('#op').text('Editar');
    var custodio = '';

    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/ACTIVOS_FIJOS/custodioC.php?listar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        console.log(response);
        $('#txt_nombre').val(response[0].PERSON_NOM);
        $('#txt_per_no').val(response[0].PERSON_NO);
        $('#txt_ci').val(response[0].PERSON_CI);
        $('#txt_email').val(response[0].PERSON_CORREO);
        $('#txt_puesto').val(response[0].PUESTO);
        $('#txt_unidad').val(response[0].UNIDAD_ORG);
        $('#id').val(response[0].ID_PERSON);
      }
    });
  }

  function delete_datos(id) {
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

  function insertar(parametros) {
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/custodioC.php?insertar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          $('#myModal').modal('hide');
          Swal.fire(
            '',
            'Operacion realizada con exito.',
            'success'
          )
          consultar_datos();
        }

      }
    });

  }

  function limpiar() {
    $('#txt_nombre').val('');
    $('#txt_per_no').val('');
    $('#txt_ci').val('');
    $('#txt_email').val('');
    $('#txt_puesto').val('');
    $('#txt_unidad').val('');
    $('#id').val('');
    $('#titulo').text('Nuevo custodio');
    $('#op').text('Guardar');
  }

  function eliminar(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/ACTIVOS_FIJOS/custodioC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          Swal.fire(
            'Eliminado!',
            'Registro Eliminado.',
            'success'
          )
          consultar_datos();
        }

      }
    });

  }

  function editar_insertar() {
    var nom = $('#txt_nombre').val();
    var ci = $('#txt_ci').val();
    var email = $('#txt_email').val();
    var pue = $('#txt_puesto').val();
    var uni = $('#txt_unidad').val();
    var per = $('#txt_per_no').val();
    var id = $('#id').val();

    var parametros = {
      'nombre': nom,
      'ci': ci,
      'email': email,
      'puesto': pue,
      'unidad': uni,
      'id': id,
      'per': per,
    }
    if (id == '') {
      if (nom == '' || ci == '' || email == '' || pue == '' || uni == '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Asegurese de llenar todo los campos',
        })
      } else {
        insertar(parametros)
      }
    } else {
      if (nom == '' || ci == '' || email == '' || pue == '' || uni == '') {
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

  function paginacion(num) {
    $('#txt_pag').val(num);
    var pag = $('#txt_pag').val().split('-');
    var pos = pag[1] / 25;
    consultar_datos();
    // alert(pos);
  }

  function guias_pag(tipo) {

    var m1 = $('#txt_pag').val().split('-');
    var m = $('#txt_pag1').val().split('-');
    var pos = m1[1] / 25;
    if (tipo == '+') {
      if (pos >= 10) {
        var fin = m[1] * (pos + 1);
        var ini = fin - m[1];
        $('#txt_pag').val(ini + '-' + fin);
        consultar_datos();

      } else {
        var fin = m[1] * (pos + 1);
        var ini = fin - m[1];
        $('#txt_pag').val(ini + '-' + fin);
        consultar_datos();
      }

    } else {
      if (pos == 1) {
        alert('esta en el inicio');
      } else {
        var fin = m[1] * (pos - 1);
        var ini = fin - m[1];
        $('#txt_pag').val(ini + '-' + fin);
        consultar_datos();
      }
    }
  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Mantenimiento</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Custodio</li>
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
            <input type="hidden" id="txt_pag" name="" value="0-25">
            <input type="hidden" id="txt_pag1" name="" value="0-25">
            <input type="hidden" id="txt_numpag" name="">
            <div class="row">
              <div class="col-sm-12" id="btn_nuevo">
                <a href="inicio.php?acc=custodio_detalle" class="btn btn-primary btn-sm"><i class="bx bx-plus"></i>Nuevo</a>
                <a href="#" class="btn btn-outline-secondary btn-sm" id="excel_custodios" title="Informe en excel del total de custodios"><i class="bx bx-file"></i> Total custodios</a>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-8">
                <input type="" name="" id="txt_buscar" onkeyup="consultar_datos()" class="form-control form-control-sm" placeholder="Buscar por nombre">
              </div>
              <div class="col-sm-4 text-end">
                <nav aria-label="Contacts Page Navigation">
                  <ul class="pagination justify-content-center m-0 pagination-sm" id="pag">

                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row" id="tbl_datos">
        </div>
      </div>
    </div>
    <!--end row-->
  </div>
</div>


<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Custodios</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">

      <!--  <div class="row justify-content-end">
            <nav aria-label="Page navigation example">
              <ul class="pagination" id="pag1">
                
              </ul>
            </nav>           
          </div> -->

    </div>

    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="titulo">Nuevo custodio</h3>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="id" class="form-control">
            Codigo <br>
            <input type="input" name="txt_per_no" id="txt_per_no" class="form-control">
            Nombre <br>
            <input type="input" name="txt_nombre" id="txt_nombre" class="form-control">
            CI <br>
            <input type="input" name="txt_ci" id="txt_ci" class="form-control">
            Correo <br>
            <input type="input" name="txt_email" id="txt_email" class="form-control">
            Puesto <br>
            <input type="input" name="txt_puesto" id="txt_puesto" class="form-control">
            Unidad ORG <br>
            <input type="input" name="txt_unidad" id="txt_unidad" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="op" onclick="editar_insertar()">Guardar</button>
          </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </section>
</div>

<?php //include('../cabeceras/footer.php'); 
?>