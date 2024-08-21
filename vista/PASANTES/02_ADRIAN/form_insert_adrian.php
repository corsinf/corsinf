<script type="text/javascript">
    $(document).ready(function() {

    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pasantes</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Adrian
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

                                </div>
                            </div>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Primer Apellido <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control form-control-sm" id="txt_" name="txt_">
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Segundo Apellido <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control form-control-sm" id="txt_" name="txt_">
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Primer Nombre <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control form-control-sm" id="txt_" name="txt_">
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Segundo Nombre <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control form-control-sm" id="txt_" name="txt_">
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-success btn-sm px-4" onclick="editar_insertar()"><i class="bx bx-save"></i> Guardar</button>
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