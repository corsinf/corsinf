<script type="text/javascript">
  $(document).ready(function() {
    bodegas();
    bodegas_inactivos();
    //  // restriccion();
    // Lista_clientes();
    // Lista_procesos();

  });

  function bodegas() {
    $.ajax({
      // data:  {id,id},
      url: '../controlador/bodegasC.php?lista=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // console.log(response);
        $('#tbl_body').html(response);
      }

    });
  }

  function bodegas_inactivos(query = '') {
    $.ajax({
      data: {
        query: query
      },
      url: '../controlador/bodegasC.php?inactivo=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response != "") {
          $('#bodegas_ina').html(response);
        }
      }

    });
  }

  function editar(id) {
    var nom = $('#txt_nombre_' + id).val();
    var check = $('input:radio[name=rbl_produccion_' + id + ']:checked').val();
    var parametros = {
      'id': id,
      'nom': nom,
      'pro': check,
    }
    $.ajax({
      data: {
        parametros,
        parametros
      },
      url: '../controlador/bodegasC.php?editar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Registro editado.', 'success');
          bodegas();
          bodegas_inactivos();
        } else {
          Swal.fire('', 'UPs Algo salio mal.', 'error');
        }
      }

    });

  }

  function eliminar(id) {
    $.ajax({
      data: {
        eliminar,
        eliminar
      },
      url: '../controlador/bodegasC.php?eliminar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          bodegas();
          bodegas_inactivos();
        }
      }

    });

  }

  function eliminar(id) {
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {

        $.ajax({
          data: {
            id,
            id
          },
          url: '../controlador/bodegasC.php?eliminar=true',
          type: 'post',
          dataType: 'json',
          success: function(response) {
            if (response == 1) {
              Swal.fire('', 'Bodega eliminado', 'success');
              bodegas();
              bodegas_inactivos();
            } else if (response == -2) {
              Swal.fire({
                title: 'Esta bodega esta asignada aun producto y no se podra eliminar',
                text: "Desea inhabilitado a esta bodega?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Si!',
              }).then((result) => {
                if (result.isConfirmed) {
                  inhabilitar_usuario(id);
                }
              })
              // Swal.fire('','El Usuario esta ligado a uno o varios registros y no se podra eliminar.','error')
            } else {
              Swal.fire('', 'No se pudo eliminar', 'info');
            }
          }

        });
      }
    });

  }

  function inhabilitar_usuario(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/bodegasC.php?estado=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          bodegas();
          bodegas_inactivos();
          Swal.fire('La Bodega  inhabilitado!', 'La bodega no podra ser seleccionado en el futuro', 'success');

        } else {
          Swal.fire('', 'UPs aparecio un problema', 'success');
        }

      }

    });

  }

  function Activar(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/bodegasC.php?activar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          bodegas();
          bodegas_inactivos();
          Swal.fire('Bodega  habilitada!', '', 'success');

        } else {
          Swal.fire('', 'UPs aparecio un problema', 'success');
        }

      }

    });

  }

  function add_categoria() {
    var check = $('input:radio[name=rbl_produccion]:checked').val()
    var nombre = $('#txt_nombre').val();

    if (nombre == '') {
      Swal.fire('', 'Llene el campo de nombre', 'info');
      return false;
    }
    var parametros = {
      'nombre': nombre,
      'pro': check,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/bodegasC.php?add=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response == 1) {
          bodegas();
          $('#txt_nombre').val('');
          bodegas_inactivos();
          Swal.fire('La Bodega  se a Registrado!', '', 'success');

        } else if (response == -2) {
          Swal.fire('', 'El nombre de la Bodega ya esta registrada', 'error');
        }

      }

    });

  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Bodegas</div>
      <?php
      // print_r($_SESSION['INICIO']);die();

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Bodega
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
          <div class="card-body p-5">
            <div class="card-title d-flex align-items-center">
              <h5 class="mb-0 text-primary"></h5>
            </div>
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item">
                    <a class="nav-link active" id="bodegas-tab" data-bs-toggle="pill" href="#bodegas" role="tab" aria-controls="bodegas" aria-selected="true">BODEGAS</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="bodegas_ina-tab" data-bs-toggle="pill" href="#bodegas_ina" role="tab" aria-controls="bodegas_ina" aria-selected="false">BODEGAS INACTIVAS</a>
                  </li>
                </ul>
              </div><!-- /.card-header -->

              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="bodegas" role="tabpanel" aria-labelledby="bodegas-tab">
                    <table class="table w-100">
                      <thead>
                        <tr>
                          <th class="w-50">Nombre de bodegas</th>
                          <th class="w-25 text-center">Para producción</th>
                          <th class="w-25 text-center">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input type="text" name="txt_nombre" id="txt_nombre" class="form-control form-control-sm w-100">
                          </td>
                          <td class="text-center">
                            <div class="form-check form-check-inline">
                              <input type="radio" class="form-check-input" name="rbl_produccion" id="rbl_no" value="0">
                              <label class="form-check-label" for="rbl_no">NO</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input type="radio" class="form-check-input" name="rbl_produccion" id="rbl_si" value="1">
                              <label class="form-check-label" for="rbl_si">SI</label>
                            </div>
                          </td>
                          <td class="text-center">
                            <button class="btn btn-primary btn-sm" type="button" onclick="add_categoria()">
                              <i class="fa fa-save"></i> Nuevo
                            </button>
                          </td>
                        </tr>
                      </tbody>
                      <tbody id="tbl_body">
                        <!-- Dynamic content goes here -->
                      </tbody>
                    </table>
                  </div><!-- /.tab-pane -->

                  <div class="tab-pane" id="bodegas_ina" role="tabpanel" aria-labelledby="bodegas_ina-tab">
                    <!-- Inactive bodegas content goes here -->
                  </div><!-- /.tab-pane -->

                </div><!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div><!-- /.card -->

          </div>
        </div>
      </div>
    </div>


    <!--end row-->
  </div>
</div>


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <div class="row">
          <div class="col-12">
            <label for="">Tipo de <label class="text-danger">*</label></label>
            <select name="" id="" class="form-select form-select-sm" onchange="">
              <option value="">Seleccione el </option>
            </select>
          </div>
        </div>

        <div class="row pt-3">
          <div class="col-12">
            <label for="">Blank <label class="text-danger">*</label></label>
            <select name="" id="" class="form-select form-select-sm">
              <option value="">Seleccione el </option>
            </select>
          </div>
        </div>

        <div class="row pt-3">
          <div class="col-12 text-end">
            <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i> Agregar</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>