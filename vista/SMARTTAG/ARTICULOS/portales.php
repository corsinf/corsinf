<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Portales</div>
      <?php
      // print_r($_SESSION['INICIO']);die();

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Portales
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
                    <a class="nav-link active" id="bodegas-tab" data-bs-toggle="pill" href="#bodegas" role="tab" aria-controls="bodegas" aria-selected="true">Portales</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="bodegas_ina-tab" data-bs-toggle="pill" href="#bodegas_ina" role="tab" aria-controls="bodegas_ina" aria-selected="false">Portales Inactivas</a>
                  </li>
                </ul>
              </div><!-- /.card-header -->

              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="bodegas" role="tabpanel" aria-labelledby="bodegas-tab">
                    
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