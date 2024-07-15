<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Firmador</div>
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

        <div>
            <?php //print_r($_SESSION) ?>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_pacientes"><i class="bx bx-plus"></i> Nuevo Paciente</button>

                                    <input type="checkbox" name="">
                                    <input type="radio" name="">

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