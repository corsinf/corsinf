<script src="../lib/jquery_validation/jquery.validate.js"></script>
<style>
    label.error {
        color: red;
        /* Cambia "red" por el color que desees */

    }
</style>
<script type="text/javascript">
  $(document).ready(function() {

    $('.noMostrar').each(function() {
      var texto_original = $(this).text();
      var texto_oculto = texto_original.replace(/./g, '*');
      $(this).text(texto_oculto);
    })

    $("#seccionClave").hide() && $("#seccionValidar").hide();

    $(document).on('change', '#cbx_guardarClave', function() {
      if ($(this).is(':checked')) {
        $("#seccionClave").show() && $("#seccionValidar").show();
        $("#txt_clave").prop('required', true) && $("#txt_validarClave").prop('required', true);
      } else {
        $("#seccionClave").hide() && $("#seccionValidar").hide();
      }
    });

    $('#tbl_firmas').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
      },
      responsive: true,
      order: []
    });

  });

  function validar_form() {
    var clave = $("#txt_clave").val();
    var confirmar_clave = $("#txt_validarClave").val();

    if ($('#cbx_guardarClave').is(':checked')) {
      if ($("#txt_clave").prop('required', true) && $("#txt_validarClave").prop('required', true)) {
        if (clave !== confirmar_clave) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Las contraseñas no coinciden.",
          });
          return false;
        }
      }
    }
    return true;
  }

  function insertar_editar() {
    var txt_nombrePersona = $('#txt_nombrePersona').val();
    var txt_nombreFirma = $('#txt_nombreFirma').val();
    var txt_ruc = $('#txt_ruc').val();
    var doc_subirDocumento = $('#doc_subirDocumento').val();
    var cbx_guardarClave = $('#cbx_guardarClave').val();
    var txt_clave = $('#txt_clave').val();
    var txt_validarClave = $('#txt_validarClave').val();

    var parametros = {
      'txt_nombrePersona': txt_nombrePersona,
      'txt_nombreFirma': txt_nombreFirma,
      'txt_ruc': txt_ruc,
      'doc_subirDocumento': doc_subirDocumento,
      'cbx_guardarClave': cbx_guardarClave,
      'txt_clave': txt_clave,
      'txt_validarClave': txt_validarClave,
    };

    if ($("#registrar_firma").valid()) {
      // Si es válido, puedes proceder a enviar los datos por AJAX
      console.log(parametros);
      validar_form();
    }
  }
</script>
<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Facturación</div>
      <?php
      // print_r($_SESSION['INICIO']);die();

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Firmador
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

              <h5 class="mb-0 text-primary"></h5>

              <div class="row mx-0">
                <div class="col-sm-12" id="btn_nuevo">

                  <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_firma"><i class="bx bx-plus"></i>Agregar Nueva Firma</button>

                </div>
              </div>
            </div>


            <section class="content pt-2">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table class="table table-striped responsive " id="tbl_firmas" style="width:100%">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Nombre de la firma</th>
                        <th>RUC</th>
                        <th>Clave</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de vencimiento</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Adrian Acuña</td>
                        <td>Firma Principal</td>
                        <td>1726621871001</td>
                        <td class="noMostrar">12345</td>
                        <td>2024-07-16</td>
                        <td>2025-07-16</td>
                      </tr>
                      <tr>
                        <td>Samuel Estrada</td>
                        <td>Firma Secundaria</td>
                        <td>1758493871001</td>
                        <td class="noMostrar">cualquiercosa!123</td>
                        <td>2024-07-16</td>
                        <td>2027-02-01</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div><!-- /.container-fluid -->
            </section>
          </div>
        </div>
      </div>
    </div>
    <!--end row-->
       
  </div>
</div>

<div class="modal" id="modal_firma" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5><small class="text-body-secondary">Ingrese los datos</small></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <form id="registrar_firma">
          <div class="mb-3">
            <label for="txt_nombrePersona" class="form-label form-label-sm">Nombre <label style="color: red;">*</label></label>
            <input type="text" class="form-control form-control-sm" name="txt_nombrePersona" id="txt_nombrePersona" value="" placeholder="Escriba su nombre completo">
          </div>
          <div class="mb-3">
            <label for="txt_nombreFirma" class="form-label form-label-sm">Nombre de la firma <label style="color: red;">*</label></label>
            <input type="text" class="form-control form-control-sm" name="txt_nombreFirma" id="txt_nombreFirma" value="" placeholder="Escriba el nombre de la firma">
          </div>
          <div class="mb-3">
            <label for="txt_ruc" class="form-label form-label-sm">RUC <label style="color: red;">*</label></label>
            <input type="text" class="form-control form-control-sm" name="txt_ruc" id="txt_ruc" value="" placeholder="Escriba su RUC">
          </div>
          <div class="mb-3">
            <label for="doc_subirDocumento" class="form-label form-label-sm">Subir un documento <label style="color: red;">*</label></label>
            <input type="file" class="form-control form-control-sm" name="doc_subirDocumento" id="doc_subirDocumento" value="">
          </div>
          <div class="mb-3">
            <label for="cbx_guardarClave" class="form-check-label">¿Quiere guardar su contraseña?</label>
            <input type="checkbox" class="form-check-input form-check-input-sm" name="cbx_guardarClave" id="cbx_guardarClave" value="">
          </div>
          <div class="mb-3" id="seccionClave">
            <label for="txt_clave" class="form-label form-label-sm">Contraseña <label style="color: red;">*</label></label>
            <input type="password" class="form-control form-control-sm" name="txt_clave" id="txt_clave" value="" placeholder="Ingrese una contraseña">
          </div>
          <div class="mb-3" id="seccionValidar">
            <label for="txt_validarClave" class="form-label form-label-sm">Verificar Contraseña <label style="color: red;">*</label></label>
            <input type="password" class="form-control form-control-sm" name="txt_validarClave" id="txt_validarClave" value="" placeholder="Ingrese nuevamente su contraseña">
          </div>
          <div class="mb-3">
            <button type="button" class="btn btn-primary btn-sm" id="btn_enviar" onclick="insertar_editar();">Agregar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  //Validacion de formulario
  $(document).ready(function() {
    $("#registrar_firma").validate({
      rules: {
        txt_nombrePersona:{
          required: true,
        },
        txt_nombreFirma: {
          required: true,
        },
        txt_ruc: {
          required: true,
        },
        doc_subirDocumento: {
          required: true,
        },
        txt_clave: {
          required: true,
        },
        txt_validarClave: {
          required: true,
        },
      },
      messages: {
        txt_nombrePersona: {
          required: "Por favor ingresa tu nombre",
        },
        txt_nombreFirma: {
          required: "Por favor ingresa el nombre de la firma",
        },
        txt_ruc: {
          required: "Por favor ingresa un RUC",
        },
        doc_subirDocumento: {
          required: "Por favor sube un documento",
        },
        txt_clave: {
          required: "Por favor ingresa una clave",
        },
        txt_validarClave: {
          required: "Por favor ingrese la misma clave",
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