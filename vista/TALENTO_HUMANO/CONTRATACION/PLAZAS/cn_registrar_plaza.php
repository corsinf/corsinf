<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

//No eliminar
$_id = ''; // Este es el id que se usa para cargo 

$_id_plaza = '';
if (isset($_GET['_id_plaza'])) {
    $_id_plaza = $_GET['_id_plaza'];
}


$es_plaza = true;

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>


<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Plaza</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Proceso</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <div class="card-title d-flex align-items-center">

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id_plaza == '') {
                                    echo 'Registrar Plaza';
                                } else {
                                    echo 'Modificar Plaza';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=cn_plazas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div id="smartwizard_plaza">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-1"> <strong>Paso 1</strong>
                                        <br>Plaza</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-2"> <strong>Paso 2</strong>
                                        <br>Requisitos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-3"> <strong>Paso 3</strong>
                                        <br>This is step description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-4"> <strong>Paso 4</strong>
                                        <br>This is step description</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tab_content_smart">
                                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1" data-step="0">

                                    <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/PLAZAS/WIZART_REGISTRAR_PLAZA/plaza_paso1.php'); ?>

                                </div>
                                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2" data-step="2">

                                    <?php include_once('../vista/TALENTO_HUMANO/CARGOS/seccion_aspectos_extrinsecos.php'); ?>

                                </div>
                                <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3" data-step="3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
                                <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4" data-step="4">
                                    <h3>Step 4 Content</h3>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Para los navs del menu -->
<link rel="stylesheet" href="../assets/css/css-navs-menus.css">

<style>
    /* Hace que la fila brille un poco al pasar el mouse */
    .table-hover tbody tr:hover {
        background-color: #fbfbfb !important;
    }

    /* Quita el borde superior de la primera fila para que encaje en el rounded */
    .table tbody tr:first-child td {
        border-top: 0;
    }
</style>