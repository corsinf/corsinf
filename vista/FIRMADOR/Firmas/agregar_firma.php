<script type="text/javascript">
 $( document ).ready(function() {
  function validateForm() {
    var password = $("#Password").val();
    var confirmPassword = $("#ConfirmPassword").val();
    var fileInput = $("#UploadDocument") [0].files[0];
    var fileName = fileInput ? fileInput.name : '';

    if (password !== confirmPassword) {
      alert("Las contraseñas no coinciden.");
      return false;
    }

    var fileExtension = fileName.split('.').pop().toLowerCase();
    if (fileExtension !== 'p12') {
      alert("El archivo debe tener el formato .p12");
      return false;
    }

    return true;
  }

  $("#submitButton").click(function(event) {
    if (!validateForm()) {
      event.preventDefault();
    }
  });
 });
  
</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Forms</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Wizard</li>
              </ol>
            </nav>
          </div>
         
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <h6 class="mb-0 text-uppercase">Form Wizard</h6>
            <hr>
            <div class="card">
              <div class="card-body">
                <br>
                  <h1>Agregar Firma Electrónica</h1> 
                  <form>
                    <div class="mb-3">
                      <label for="PersonName" class="form-label">Nombre</label>
                      <input type="text", id="PersonName" class="form-control" placeholder="Escriba su nombre completo">
                    </div>
                    <div class="mb-3">
                      <label for="UploadDocument" class="form-label">Subir un documento</label>
                      <input type="file" id="UploadDocument" class="form-control">
                    </div>
                    <div class="mb-3">
                      <label for="ConfirmSignatureSave" class="form-check-label">¿Quiere guardar la firma?</label>
                      <input type="checkbox" id="ConfirmSignatureSave" class="form-check-input">
                    </div>
                    <div class="mb-3">
                      <label for="Password" class="form-label">Contraseña</label>
                      <input type="password" id="Password" class="form-control" placeholder="Ingrese una contraseña">
                    </div>
                    <div class="mb-3">
                      <label for="ConfirmPassword" class="form-label">Verificar Contraseña</label>
                      <input type="password" id="ConfirmPassword" class="form-control" placeholder="Ingrese nuevamente su contraseña">
                    </div>
                    <div class="mb-3">
                      <button type="submit" id="submitButton" class="btn btn-success">Agregar Firma</button>
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>

