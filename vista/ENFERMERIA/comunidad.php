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
                            Comunidad Educativa
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
                                Comunidad Educativa
                            </h5>
                        </div>

                        <div class="fm-menu">
                            <div class="list-group list-group-flush">
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=estudiantes" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Estudiantes</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=representantes" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Representantes</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Docente</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Administrativo</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <h3>Seleccione un Item de Comunidad Educativa</h3>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>