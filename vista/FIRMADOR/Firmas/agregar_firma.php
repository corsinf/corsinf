<script type="text/javascript">
 $( document ).ready(function() {
  $('.notShow').each(function(){
    var originalText = $(this).text();
    var hiddenText = originalText.replace(/./g, '*');
    $(this).text(hiddenText);
  })
  $("#passwordSection").hide() && $("#validateSection").hide();
  function validateForm() {
    var password = $("#psw_password").val();
    var confirmPassword = $("#psw_validatePassword").val();
    var fileInput = $("#doc_uploadDocument") [0].files[0];
    var fileName = fileInput ? fileInput.name : '';

    if (password !== confirmPassword) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Las contraseñas no coinciden.",
      });
    }

    var fileExtension = fileName.split('.').pop().toLowerCase();
    if (fileExtension !== 'p12') {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "El archivo debe estar en formato .p12",
      });
    }

    return true;
  }

  $("#btn_submit").click(function(event) {
    if (!validateForm()) {
      event.preventDefault();
    }
  });

  $(document).on('change', '#cbx_passwordSave', function(){
    if ($(this).is(':checked')){
      $("#passwordSection").show() && $("#validateSection").show();
    } else {
      $("#passwordSection").hide() && $("#validateSection").hide();
    }
  });

 });
  
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
                                                <th>Fecha de vencimiento</th>                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                              <td>Adrian Acuña</td>
                                              <td>Firma Principal</td>
                                              <td>1726621871001</td>
                                              <td class="notShow">12345</td>
                                              <td>2024-07-16</td>
                                              <td>2025-07-16</td>
                                            </tr>
                                            <tr>
                                              <td>Samuel Estrada</td>
                                              <td>Firma Secundaria</td>
                                              <td>1758493871001</td>
                                              <td class="notShow">cualquiercosa!123</td>
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
        <div class="mb-3">
          <label for="txt_personName" class="form-label form-label-sm">Nombre</label>
          <input type="text" class="form-control form-control-sm" name="txt_personName" id="txt_personName" value="" placeholder="Escriba su nombre completo">
        </div>
        <div class="mb-3">
          <label for="txt_signatureName" class="form-label form-label-sm">Nombre de la firma</label>
          <input type="text" class="form-control form-control-sm" name="txt_signatureName" id="txt_signatureName" value="" placeholder="Escriba el nombre de la firma">
        </div>
        <div class="mb-3">
          <label for="txt_ruc" class="form-label form-label-sm">RUC</label>
          <input type="text" class="form-control form-control-sm" name="txt_ruc" id="txt_ruc" value="" placeholder="Escriba su RUC">
        </div>
        <div class="mb-3">
          <label for="doc_uploadDocument" class="form-label form-label-sm">Subir un documento</label>
          <input type="file" class="form-control form-control-sm" name="doc_uploadDocument" id="doc_uploadDocument" value="">
        </div>
        <div class="mb-3">
          <label for="cbx_passwordSave" class="form-check-label">¿Quiere guardar una contraseña?</label>
          <input type="checkbox" class="form-check-input form-check-input-sm" name="cbx_passwordSave" id="cbx_passwordSave" value="">
        </div>
        <div class="mb-3" id="passwordSection">
          <label for="psw_password" class="form-label form-label-sm">Contraseña</label>
          <input type="password" class="form-control form-control-sm" name="psw_password" id="psw_password" value="" placeholder="Ingrese una contraseña">
        </div>
        <div class="mb-3" id="validateSection">
          <label for="psw_validatePassword" class="form-label form-label-sm">Verificar Contraseña</label>
          <input type="password" class="form-control form-control-sm" name="psw_validatePassword" id="psw_validatePassword" value="" placeholder="Ingrese nuevamente su contraseña">
        </div>
        <div class="mb-3">
          <button type="button" class="btn btn-primary btn-sm" id="btn_submit">Agregar</button>
        </div>
      </div>
    </div>
  </div>
</div>