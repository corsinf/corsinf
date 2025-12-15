<script type="text/javascript">
  $(document).ready(function() {
    cargar_tablas();
    lista_no_concurente();
    lista_tipo_usuario_drop_pagina();

  })

  function cargar_tablas() {

    $.ajax({
      // data:  {parametros:parametros},
      url: '../controlador/no_concurenteC.php?tabla_no_concurente=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        // console.log(response);
        var op = '<option value="">Seleccione Tabla</option>';
        response.forEach(function(item, i) {
          op += '<option value="' + item.TABLE_NAME + '">' + item.TABLE_NAME + '</option>';
        })
        $('#ddl_tablas').html(op);
      }
    });
  }


  function campos_tabla_noconcurente() {
    var parametros = {
      'tabla': $('#ddl_tablas').val(),
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/no_concurenteC.php?campos_tabla_noconcurente=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        // console.log(response);
        var op = '<option value="">Seleccione Tabla</option>';
        response.forEach(function(item, i) {
          op += '<option value="' + item.campo + '">' + item.campo + '</option>';
        })
        $('#ddl_usuario').html(op);
        $('#ddl_pass').html(op);
        $('#ddl_campo_img').html(op);
      }
    });
  }

  function lista_no_concurente() {

    $.ajax({
      // data:  {parametros:parametros},
      url: '../controlador/no_concurenteC.php?lista_no_concurente=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        console.log(response);
        var op = '';
        response.forEach(function(item, i) {
          op += '<tr><td>' + item.Total + '</td><td>' + item.Tabla + '</td><td>' + item.Campo_usuario + '</td><td>' + item.Campo_pass + '</td><td>' + item.perfil + '</td>' +
            '<td>' +
            '<button type="button" class="btn btn-danger btn-sm" onclick="eliminar_no_concurente(\'' + item.Tabla + '\')"><i class="bx bx-trash me-0"></i></button>' +
            '</td>' +
            '</tr>';
        })

        $('#tbl_lista_no_concurentes').html(op);
      }
    });
  }

  function add_no_concurente() {

    if ($('#ddl_tablas').val() == '' || $("#ddl_perfil").val() == '' || $('#ddl_usuario').val() == '' || $('#ddl_pass').val() == '' || $('#ddl_campo_img').val() == '') {
      Swal.fire('', 'Seleccione todos los campos', 'info');
      return false;
    }

    // if ($('#ddl_usuario').val() == $('#ddl_pass').val()) {
    //   Swal.fire('', 'Asegurese que los campos de usuario y password sean distintos', 'info');
    //   return false;
    // }

    var parametros = {
      'tabla': $('#ddl_tablas').val(),
      'usuario': $('#ddl_usuario').val(),
      'pass_usu': $('#ddl_pass_usu').val() ?? '',
      'chk_pass': $('#chk_claves_aleatorias').is(':checked') ? 1 : 0,
      'perfil_usu': $("#ddl_perfil").val(),
      'foto': $('#ddl_campo_img').val(),
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/no_concurenteC.php?add_no_concurente=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == -2) {
          Swal.fire('', 'Tabla ya asignada a no concurentes', 'error');
        } else if (response == 1) {
          Swal.fire('', 'Agregado a no concurrentes', 'success');
          lista_no_concurente()
        } else if (response == -3) {
          Swal.fire('', 'La tabla asociada no tiede datos', 'error');
        } else {
          Swal.fire('No se puedo guardar', 'Comuniquese con el Administrador del sistema (key no encontrado)', 'error');
        }
        // console.log(response);
      }
    });
  }


  function eliminar_no_concurente(tabla) {
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
        eliminar(tabla);
      }
    })
  }

  function eliminar(tabla) {
    var parametros = {
      'tabla': tabla,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/no_concurenteC.php?delete_no_concurente=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'eliminado de no concurentes', 'success');
          lista_no_concurente();
        }
      }
    });

  }


  function lista_tipo_usuario_drop_pagina() {
    $.ajax({
      // data:  {parametros:parametros},
      url: '../controlador/tipo_usuarioC.php?lista_usuarios_drop=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response) {

          response = '<option value="">Seleccione perfil</option>' + response;
          $('#ddl_perfil').html(response);
        }
      }

    });
  }
</script>

<div class="page-wrapper">
  <div class="page-content">

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Usuarios no concurrentes</div>
    </div>

    <div class="row">
      <div class="col-xl-12 mx-auto">
        <hr>

        <div class="card shadow-sm border-0">
          <div class="card-body">

            <div class="row g-3 mb-4">

              <div class="col-sm-3">
                <label class="fw-bold mb-1">Tablas asociadas</label>
                <select class="form-select form-select-sm" id="ddl_tablas" name="ddl_tablas" onchange="campos_tabla_noconcurente()">
                  <option value="">Seleccione tabla</option>
                </select>
              </div>

              <div class="col-sm-3">
                <label class="fw-bold mb-1">Validar Usuario con</label>
                <select class="form-select form-select-sm" id="ddl_usuario" name="ddl_usuario">
                  <option value="">Seleccione Usuario</option>
                </select>
              </div>

              <div class="col-sm-3">
                <label class="fw-bold mb-1">Perfil Asignado</label>
                <select class="form-select form-select-sm" id="ddl_perfil" name="ddl_perfil" onchange="buscar_usuario_perfil();">
                  <option value="">Seleccione perfil de usuario</option>
                </select>
              </div>

              <div class="col-sm-3">
                <label class="fw-bold mb-1">Campo Foto perfil</label>
                <select class="form-select form-select-sm" id="ddl_campo_img" name="ddl_campo_img">
                  <option value="">Seleccione campo</option>
                </select>
              </div>

            </div>

            <div class="row align-items-center g-3 mb-3">
              <div class="col-sm-2 d-flex align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="chk_claves_aleatorias" class="form-check-input" onchange="claves_aleatorias()" checked disabled>

                  <label class="form-check-label fw-semibold" for="chk_claves_aleatorias">
                    Claves aleatorias
                  </label>
                </div>
              </div>

              <div class="col-sm-4" id="container_ddl_pass" style="display: none;">
                <label class="fw-bold mb-1">Validar Password con</label>
                <select class="form-select form-select-sm" id="ddl_pass_usu" name="ddl_pass_usu">
                  <option value="">Seleccione password</option>
                </select>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-12 col-sm-3">
                <button type="button" class="btn btn-primary btn-sm w-100" onclick="add_no_concurente()">Agregar</button>
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="table-responsive">

                <table class="table table-hover table-striped align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Total Asociados</th>
                      <th>Tabla</th>
                      <th>Campo Usuario</th>
                      <th>Campo Password</th>
                      <th>Perfil Asignado</th>
                      <th class="text-end">Opciones</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_lista_no_concurentes">
                    <!-- Aquí se cargan las filas dinámicamente -->
                  </tbody>
                </table>

              </div>
            </div>

          </div>
        </div>

      </div>
    </div>


  </div>
</div>

<script>
  function claves_aleatorias() {
    var chk = document.getElementById('chk_claves_aleatorias');
    var container = document.getElementById('container_ddl_pass');

    if (!chk || !container) return;

    if (chk.checked) {
      container.style.display = 'none';
      document.getElementById('ddl_pass').value = '';
    } else {
      container.style.display = '';
    }
  }
</script>