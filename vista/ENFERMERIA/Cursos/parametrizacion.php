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
                        <li class="breadcrumb-item active" aria-current="page">
                            Parametrización
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12 col-lg-3">

                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                Parametrización
                            </h5>
                        </div>

                        <label class="menu-label">Cursos</label>
                        <div class="fm-menu">
                            <div class="list-group list-group-flush">
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=seccion" class="list-group-item py-1"><i class='bx bx-file me-2'></i><span>Sección</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=grado" class="list-group-item py-1"><i class='bx bx-file me-2'></i><span>Grado</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=paralelo" class="list-group-item py-1"><i class='bx bx-file me-2'></i><span>Paralelo</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>