<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Inicio</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"></li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <h6 class="mb-0 text-uppercase">Activos</h6>
        <hr>

        <div class="row">
          <!-- <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
              <div class="info-box-content">
                <span class="info-box-text" title="Ultima actualizacion con SAP">Ultima actu. con SAP</span>
                <span class="info-box-number">
                   <?php echo date('Y-m-d H:i:s'); ?>
                </span>
              </div>
            </div>
          </div> -->
          <div class="col-3" onclick="location.href='articulos.php'">
            <div class="card radius-10">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Activos</p>
                    <h4 class="my-1" id="lbl_articulos">0</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-package"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-3" onclick="location.href='articulos.php'">
            <div class="card radius-10">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Bajas</p>
                    <h4 class="my-1" id="lbl_bajas">0</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-danger ms-auto"><i class="bx bx-package"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-3" onclick="location.href='articulos.php'">
            <div class="card radius-10">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Patrimoniales</p>
                    <h4 class="my-1" id="lbl_patrimoniales">0</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-warning ms-auto"><i class="bx bx-package"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-3" onclick="location.href='articulos.php'">
            <div class="card radius-10">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Terceros</p>
                    <h4 class="my-1" id="lbl_terceros">0</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-package"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <h6 class="mb-0 text-uppercase">Seguros</h6>
          <hr>

          <div class="row">
            <div class="col-md-12">
              <div class="card-body">
                <div class="row">

                  <div class="col-md-5">
                    <p class="text-center">
                      <strong>Porcentaje de articulos asegurados</strong>
                    </p>
                    <div class="card card-danger">
                      <div class="card-body">
                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                      </div>
                    </div>

                  </div>

                  <div class="col-md-7">
                    <div class="row">

                      <div class="col-6">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Seguros registrados</p>
                                <h4 class="my-1" id="lbl_num_seguros">0</h4>
                                <!-- <p class="mb-0 font-13 text-warning" id="lbl_porce"><i class="bx bxs-up-arrow align-middle"></i>0% </p> -->
                              </div>
                              <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class="bx bx-lock"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Total de Activos</p>
                                <h4 class="my-1" id="lbl_articulos2">0</h4>
                                <!-- <p class="mb-0 font-13 text-primary" id="lbl_porce_asegurados"><i class="bx bx-circle align-middle"></i>100% </p> -->
                              </div>
                              <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class="bx bx-package"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-6">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Articulos Asegurados</p>
                                <h4 class="my-1" id="lbl_asgurados">0</h4>
                                <p class="mb-0 font-13 text-success" id="lbl_porce_asegurados"><i class="bx bxs-up-arrow align-middle"></i>0% </p>
                              </div>
                              <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-lock"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Articulos sin seguro</p>
                                <h4 class="my-1" id="lbl_sin_seguro">0</h4>
                                <p class="mb-0 font-13 text-danger" id="lbl_porce_sin_seguro"><i class="bx bxs-up-arrow align-middle"></i>0% </p>
                              </div>
                              <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class="bx bx-lock-open"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--end row-->
</div>
</div>
