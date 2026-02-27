<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

$_id_plaza = '';
if (isset($_GET['_id_plaza'])) {
    $_id_plaza = $_GET['_id_plaza'];
}

?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10 border-top border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 text-muted">
                            <i class="bx bx-buildings me-1"></i><span id="lbl_departamento" class="me-3">---</span>
                            <i class="bx bx-briefcase me-1"></i><span id="lbl_cargo">---</span>
                        </p>
                    </div>
                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=cn_plazas" class="btn btn-outline-secondary btn-sm shadow-sm">
                        <i class="bx bx-arrow-back"></i> Regresar
                    </a>
                </div>
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_info">
                            <i class='bx bx-info-square me-1'></i> Resumen General
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_etapas_proceso">
                            <i class='bx bx-list-ol me-1'></i> Etapas del Proceso
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_pla_postulantes">
                            <i class='bx bx-group me-1'></i> Postulantes
                        </a>
                    </li>
                </ul>

                <div class="tab-content py-3">
                    <div class="tab-pane fade show active" id="tab_info" role="tabpanel">
                        <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/PLAZAS/TABS_INFORMACION_PLAZA/tab_info_plaza.php'); ?>
                    </div>

                    <div class="tab-pane fade" id="tab_etapas_proceso" role="tabpanel">
                        <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/PLAZAS/TABS_INFORMACION_PLAZA/tab_etapas_proceso.php'); ?>
                    </div>

                    <div class="tab-pane fade" id="tab_pla_postulantes" role="tabpanel">
                        <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/PLAZAS/TABS_INFORMACION_PLAZA/tab_postulantes.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>