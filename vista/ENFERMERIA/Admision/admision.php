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
            <li class="breadcrumb-item active" aria-current="page">Registro de Ficha del Estudiante</li>
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
              <h5 class="mb-0 text-primary">Registro de Ficha del Estudiante</h5>
            </div>
            <hr>

            <form action="" method="post">

              <h5>I. DATOS GENERALES DEL ESTUDIANTE</h5>
              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">Apellido Paterno: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>
                <div class="col-md-3">
                  <label for="" class="form-label">Apellido Materno: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>
                <div class="col-md-6">
                  <label for="" class="form-label">Nombres: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">Fecha de Nacimiento: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label"> Grupo Sanguíneo y Factor Rh: <label style="color: red;">*</label> </label>
                  <select class="form-select" id="" name="">
                    <option selected>-- Seleccione --</option>
                    <option value="">B</option>
                    <option value="">A</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Dirección del Domicilio: <label style="color: red;">*</label> </label>
                  <input type="text" class="form-control" id="" name="">
                </div>
              </div>

              <div class="row pt-3">
                <div class="col-md-3">
                  <label for="" class="form-label">¿El estudiante posee seguro médico?: <label style="color: red;">*</label> </label>
                  <select class="form-select" id="" name="">
                    <option selected>-- Seleccione --</option>
                    <option value="">Si</option>
                    <option value="">No</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Nombre del seguro: <label style="color: red;">*</label> </label>
                  <select class="form-select" id="" name="">
                    <option selected>-- Seleccione --</option>
                    <option value="">IESS</option>
                    <option value="">ISSFA</option>
                  </select>
                </div>
              </div>

              <hr>
              <h5>Representante</h5>

              <p style="color: red;">*En caso de urgencia llamar a (orden de importancia), Indique obligatoriamente al menos un número fijo de contacto</p>

              <div>
                <div class="row pt-2">
                  <div class="col-md-12">
                    <label for="" class="form-label">Nombre del Representante o Familiar Responsable: <label style="color: red;">*</label> </label>
                    <input type="text" class="form-control" id="" name="">
                  </div>
                </div>

                <div class="row pt-3">
                  <div class="col-md-4">
                    <label for="" class="form-label">Parentesco: <label style="color: red;">*</label> </label>
                    <select class="form-select" id="" name="">
                      <option selected>-- Seleccione --</option>
                      <option value="">Padre</option>
                      <option value="">Primo</option>
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label for="" class="form-label">Teléfono Fijo: <label style="color: red;">*</label> </label>
                    <input type="text" class="form-control" id="" name="">
                  </div>

                  <div class="col-md-4">
                    <label for="" class="form-label">Teléfono Celular: <label style="color: red;">*</label> </label>
                    <input type="text" class="form-control" id="" name="">
                  </div>
                </div>
              </div>

              <div>
                <div class="row pt-3">
                  <div class="col-md-12">
                    <label for="" class="form-label">Nombre del Representante o Familiar Responsable: <label style="color: red;">*</label> </label>
                    <input type="text" class="form-control" id="" name="">
                  </div>
                </div>

                <div class="row pt-3">
                  <div class="col-md-4">
                    <label for="" class="form-label">Parentesco: <label style="color: red;">*</label> </label>
                    <select class="form-select" id="" name="">
                      <option selected>-- Seleccione --</option>
                      <option value="">Padre</option>
                      <option value="">Primo</option>
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label for="" class="form-label">Teléfono Fijo: <label style="color: red;">*</label> </label>
                    <input type="text" class="form-control" id="" name="">
                  </div>

                  <div class="col-md-4">
                    <label for="" class="form-label">Teléfono Celular: <label style="color: red;">*</label> </label>
                    <input type="text" class="form-control" id="" name="">
                  </div>
                </div>
              </div>

              <hr>

              <h5>II. INFORMACIÓN IMPORTANTE</h5>

              <p style="color: red;">*Si usted considera que existe alguna condición médica importante en el estudiante. Mencionar, por favor explíquelo a continuación.</p>

              <div class="row pt-2">

                <div class="col-md-12">
                  <label for="" class="form-label">1.- ¿Ha sido diagnosticado con alguna enfermedad?: <label style="color: red;">* OBLIGATORIO</label> </label>
                  <div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="pregunta1" id="pregunta1_1">
                      <label class="form-check-label" for="flexRadioDefault1">SI</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="pregunta1" id="pregunta1_2">
                      <label class="form-check-label" for="flexRadioDefault2">NO</label>
                    </div>
                    <input type="text" class="form-control" id="" name="" placeholder="¿Cúal?">
                  </div>
                </div>

                <div class="col-md-12 pt-4">
                  <label for="" class="form-label">2.- ¿Tiene algún antecedente familiar de importancia?: <label style="color: red;">* PADRES – HERMANOS – ABUELOS - TIOS </label> </label>
                  <div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="pregunta2" id="pregunta2_1">
                      <label class="form-check-label" for="flexRadioDefault1">SI</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="pregunta2" id="pregunta2_2">
                      <label class="form-check-label" for="flexRadioDefault2">NO</label>
                    </div>
                    <input type="text" class="form-control" id="" name="" placeholder="¿Cúal?">
                  </div>
                </div>

                <div class="col-md-12 pt-4">
                  <label for="" class="form-label">3.- ¿Ha sido sometido a cirugías previas?: <label style="color: red;">* OBLIGATORIO </label> </label>
                  <div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="pregunta2" id="pregunta2_1">
                      <label class="form-check-label" for="flexRadioDefault1">SI</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="pregunta2" id="pregunta2_2">
                      <label class="form-check-label" for="flexRadioDefault2">NO</label>
                    </div>
                    <input type="text" class="form-control" id="" name="" placeholder="¿Cúal?">
                  </div>
                </div>

                <div class="col-md-12 pt-4">
                  <label for="" class="form-label">4.- ¿Tiene alergias?: <label style="color: red;">* OBLIGATORIO </label> </label>
                  <div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="pregunta2" id="pregunta2_1">
                      <label class="form-check-label" for="flexRadioDefault1">SI</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="pregunta2" id="pregunta2_2">
                      <label class="form-check-label" for="flexRadioDefault2">NO</label>
                    </div>
                    <input type="text" class="form-control" id="" name="" placeholder="¿Cúal?">
                  </div>
                </div>

                <div class="col-md-12 pt-4">

                  <label for="" class="form-label">5.- ¿Qué medicamentos usa?: <label style="color: red;">*</label> </label>
                  <p style="color: red;">*Si el estudiante requiere algún tratamiento específico durante el horario escolar, el representante deberá enviar el medicamento con la indicación médica correspondiente por agenda a través del docente tutor</p>

                  <div>

                    <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                  </div>
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