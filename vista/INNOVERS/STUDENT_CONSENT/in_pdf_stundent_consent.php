<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        ver_pdf('<?= $_id ?>');
    });

    function ver_pdf(_id) {
        $('#ifr_pdf_student_consent').prop('src', '../controlador/INNOVERS/in_student_consentC.php?pdf_studentconsent=true&id=' + _id);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">INNOVERS</div>
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

            <div class="col-12">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Formulario</h5>


                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_personas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>


                        </div>

                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->

                            <div class="col">
                                <h6 class="mb-0 text-uppercase">Documentos</h6>
                                <hr />


                                <section class="content">
                                    <div class="container-fluid">

                                        <p>Abrir solo <a href="../controlador/INNOVERS/in_student_consentC.php?pdf_studentconsent=true&id=<?= $_id; ?>" TARGET="_BLANK">PDF</a></p>

                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="panel">
                                                <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px">
                                                    <iframe class="embed-responsive-item" id="ifr_pdf_student_consent" width="90%" height="1000" src="">



                                                    </iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.container-fluid -->
                                </section>

                            </div>


                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>