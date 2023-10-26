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
            <li class="breadcrumb-item active" aria-current="page">Registrar Estudiante</li>
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
              <h5 class="mb-0 text-primary">Registrar Estudiante</h5>
            </div>
            <hr>

            <form action="" method="post">

              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">Primer Apellido: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>
                <div class="col-md-3">
                  <label for="" class="form-label">Segundo Apellido: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>
                <div class="col-md-3">
                  <label for="" class="form-label">Primer Nombre: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>
                <div class="col-md-3">
                  <label for="" class="form-label">Segundo Nombre: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">Cédula de Identidad <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
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
                  <input type="date" class="form-control" id="" name="">
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Edad: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>

              </div>

              <div class="row pt-3">
                <div class="col-md-6">
                  <label for="" class="form-label">Curso: <label style="color: red;">*</label> </label>
                  <select class="form-select" id="" name="">
                    <option selected>-- Seleccione --</option>
                    <option value="">Educacion General Basica - Primer Grado - A</option>
                    <option value="">Educacion General Basica - Primer Grado - B</option>
                  </select>
                </div>
              </div>

              <div class="col-12 pt-4">
                <button type="submit" class="btn btn-primary px-5">Guardar</button>
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