<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

$_id_plaza = '';
if (isset($_GET['_id_plaza'])) {
    $_id_plaza = $_GET['_id_plaza'];
}

$_postulado = '';
if (isset($_GET['_postulado'])) {
    $_postulado = $_GET['_postulado'];
}

?>

<script src="TALENTO_HUMANO/CONTRATACION/VACANTES/js/cn_vacantes.js?v=1.0"></script>

<script>
    $(document).ready(function() {
        datos_postulante();
    });
</script>

<style>
    /* Contenedor Minimalista */
    .container-minimal {
        background-color: #f8f9fa;
        /* Gris muy claro y limpio */
        border: 1px solid #e0e0e0;
        /* Borde sutil */
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        /* Sombra casi imperceptible */
    }

    /* Ajuste para que el botón no ocupe todo el ancho */
    .header-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 15px;
    }
</style>

<input type="hidden" name="txt_pos_id" id="txt_pos_id" value="">

<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Vacantes</div>
            <div class="ps-3 d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Información Vacante</li>
                    </ol>
                </nav>

                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=cn_vacantes" class="btn btn-outline-secondary btn-sm shadow-sm ms-3">
                    <i class="bx bx-arrow-back"></i> Regresar
                </a>
            </div>
        </div>

        <div class="container container-minimal">

            <div class="header-actions">
                <?php if ($_postulado !== '1') { ?>
                    <a href="#" class="btn btn-sm btn-dark">
                        <i class="bx bx-send"></i> Postular
                    </a>
                <?php } ?>
            </div>

            <div class="content-body">
                <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/PLAZAS/TABS_INFORMACION_PLAZA/tab_info_plaza.php'); ?>
            </div>

        </div>

    </div>
</div>

<?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/VACANTES/cn_vacantes_modales.php'); ?>
