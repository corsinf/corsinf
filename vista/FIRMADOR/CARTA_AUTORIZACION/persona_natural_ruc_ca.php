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
                            Formulario
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

                                    <!-- Para agregar botones -->

                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">

                                <div class="row">
                                    <div class="col-6">
                                        <div>
                                            <div class="card-title d-flex align-items-center">
                                                <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                                                <h5 class="mb-0 text-primary">Formulario Persona Natural</h5>
                                            </div>
                                            <hr />
                                            <div>
                                                <div class="row mb-3">
                                                    <label for="txt_nombre_completo" class="col-sm-4 col-form-label">Nombres Completos</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_nombre_completo" placeholder="Nombres Completos">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_cedula" class="col-sm-4 col-form-label">Número de Cedula</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_cedula" placeholder="Número de Cedula">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_direccion_domicilio" class="col-sm-4 col-form-label">Dirección Domicilio</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_direccion_domicilio" placeholder="Dirección Domicilio">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_provincia" class="col-sm-4 col-form-label">Provincia</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_provincia" placeholder="Provincia">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_ciudad" class="col-sm-4 col-form-label">Ciudad</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_ciudad" placeholder="Ciudad">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_correo" class="col-sm-4 col-form-label">Correo Electrónico</label>
                                                    <div class="col-sm-8">
                                                        <input type="email" class="form-control form-control-sm" id="txt_correo" placeholder="Correo Electrónico">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_celular" class="col-sm-4 col-form-label">No. Celular</label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" class="form-control form-control-sm" id="txt_celular" placeholder="No. Celular (Poner código de país)">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_fijo" class="col-sm-4 col-form-label">No. Fijo</label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" class="form-control form-control-sm" id="txt_fijo" placeholder="No. Fijo (Poner código de país)">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-8">
                                                        <button type="button" class="btn btn-success btn-sm px-5">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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