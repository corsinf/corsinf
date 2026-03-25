<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<?php if ($_id != ''): ?>
    <link href="../assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/plugins/notifications/css/lobibox.min.css" />
<?php endif; ?>



<!-- HTML -->
<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Espacios</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registros</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-building me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?= ($_id == '') ? 'Registrar Espacio' : 'Modificar Espacio' ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_espacios"
                                        class="btn btn-outline-dark btn-sm">
                                        <i class="bx bx-arrow-back"></i> Regresar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <!-- TABS -->
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_datos" role="tab">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bxs-building font-18 me-1"></i>
                                        <span>Espacio</span>
                                    </div>
                                </a>
                            </li>
                            <?php if ($_id != ''): ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_turnos" role="tab">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bxs-time font-18 me-1"></i>
                                            <span>Horarios</span>
                                        </div>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <div class="tab-content py-3">

                            <!-- TAB 1: DATOS -->
                            <div class="tab-pane fade show active" id="tab_datos" role="tabpanel">
                                 <?php include_once('../vista/HOST_TIME/ESPACIOS/TABS_ESPACIOS/tab_formulario_espacios.php'); ?>
                            </div>

                            <!-- TAB 2: TURNOS -->
                            <?php if ($_id != ''): ?>
                                <div class="tab-pane fade" id="tab_turnos" role="tabpanel">
                                 <?php include_once('../vista/HOST_TIME/ESPACIOS/TABS_ESPACIOS/tab_calentario_espacio_turno.php'); ?>
                                </div>
                            <?php endif; ?>

                        </div><!-- /tab-content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($_id != ''): ?>
    <script src="../assets/plugins/fullcalendar/js/main.min.js"></script>
    <script src="../assets/plugins/notifications/js/lobibox.min.js"></script>
<?php endif; ?>

