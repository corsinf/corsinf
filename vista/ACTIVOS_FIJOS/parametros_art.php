<?php //include('../cabeceras/header.php'); 
?>
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
            <li class="breadcrumb-item active" aria-current="page">Parametros de articulos</li>
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
            <ul class="nav nav-tabs nav-danger" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#dangerhome" role="tab" aria-selected="true">
                  <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class="bx bx-detail font-18 me-1"></i>
                    </div>
                    <div class="tab-title">Marca</div>
                  </div>
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#dangerprofile" role="tab" aria-selected="false" tabindex="-1">
                  <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class="bx bx-adjust font-18 me-1"></i>
                    </div>
                    <div class="tab-title">Estado</div>
                  </div>
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#dangercontact" role="tab" aria-selected="false" tabindex="-1">
                  <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class="bx bx-receipt font-18 me-1"></i>
                    </div>
                    <div class="tab-title">Genero</div>
                  </div>
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#dangercolor" role="tab" aria-selected="false" tabindex="-1">
                  <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class="bx bx-palette font-18 me-1"></i>
                    </div>
                    <div class="tab-title">Colores</div>
                  </div>
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#dangerfamily" role="tab" aria-selected="false" tabindex="-1">
                  <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class="bx bx-git-merge font-18 me-1"></i>
                    </div>
                    <div class="tab-title">Familias / Sub familias</div>
                  </div>
                </a>
              </li>
            </ul>
            <div class="tab-content py-3">
              <div class="tab-pane fade show active" id="dangerhome" role="tabpanel">
                <div class="container-iframe">
                  <iframe class="responsive-iframe" style="width:100%;border: 0px; height: 500px;" src="ACTIVOS_FIJOS/marcas.php"></iframe>
                </div>
              </div>
              <div class="tab-pane fade" id="dangerprofile" role="tabpanel">
                <div class="container-iframe">
                  <iframe class="responsive-iframe" style="width:100%;border: 0px; height: 500px;" src="ACTIVOS_FIJOS/estado.php"></iframe>
                </div>
              </div>
              <div class="tab-pane fade" id="dangercontact" role="tabpanel">
                <div class="container-iframe">
                  <iframe class="responsive-iframe" style="width:100%;border: 0px; height: 500px;" src="ACTIVOS_FIJOS/genero.php"></iframe>
                </div>
              </div>
              <div class="tab-pane fade" id="dangercolor" role="tabpanel">
                <div class="container-iframe">
                  <iframe class="responsive-iframe" style="width:100%;border: 0px; height: 500px;" src="ACTIVOS_FIJOS/colores.php"></iframe>
                </div>
              </div>
              <div class="tab-pane fade" id="dangerfamily" role="tabpanel">
                <div class="container-iframe">
                  <iframe class="responsive-iframe" style="width:100%;border: 0px; height: 500px;" src="ACTIVOS_FIJOS/familias.php"></iframe>
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

<?php //include('../cabeceras/footer.php'); 
?>