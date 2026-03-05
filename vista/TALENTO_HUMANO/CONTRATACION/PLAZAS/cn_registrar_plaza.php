<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';
$_id_plaza = '';
if (isset($_GET['_id_plaza'])) {
    $_id_plaza = $_GET['_id_plaza'];
}

$es_plaza = true;
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    $(document).ready(function() {
        smartwizard_cargar_plaza();
    });

    $(window).on('load', function() {
        $(document).ajaxStop(function() {
            ajustarAlturaContenedor();
        });
    });

    // ─── Wizard ────────────────────────────────────────────────────────────────
    function smartwizard_cargar_plaza() {

        var btnSiguiente = $('<button></button>')
            .text('Siguiente')
            .addClass('btn btn-info')
            .attr('id', 'btn_siguiente_plaza')
            .on('click', function() {
                var wizard = $('#smartwizard_plaza');
                var pasoActual = wizard.smartWizard("getStepIndex");
                var form = obtener_formulario_paso(pasoActual);
                var valido = true;

                if (form !== null) {
                    form.find(':input').each(function() {
                        if (!form.validate().element(this)) valido = false;
                    });
                    if (!valido) return;
                }

                if (pasoActual === 0) {
                    if (!validarFechas() || !validarSalarios()) return;
                    insertar_plaza();
                    wizard.smartWizard("next");
                    return;
                }

                wizard.smartWizard("next");
            });

        var btnAtras = $('<button></button>')
            .text('Atrás')
            .addClass('btn btn-info')
            .attr('id', 'btn_atras_plaza')
            .on('click', function() {
                $('#smartwizard_plaza').smartWizard("prev");
            });

        var btnGuardar = $('<button></button>')
            .text('Guardar')
            .addClass('btn btn-secondary d-none') // ← inicia deshabilitado visualmente
            .attr('id', 'btn_guardar_plaza')
            .prop('disabled', true) // ← inicia deshabilitado
            .on('click', function() {
                guardar_paso3();
            });

        // ── Mostrar/ocultar botones según el paso ──
        $("#smartwizard_plaza").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {

            $("#prev-btn").removeClass('disabled');
            $("#next-btn").removeClass('disabled');

            if (stepPosition === 'first') {
                $("#prev-btn").addClass('disabled');
            }

            if (stepPosition === 'last') {
                $('#btn_siguiente_plaza').addClass('d-none');
                $('#btn_guardar_plaza').removeClass('d-none');
                $("#next-btn").addClass('disabled');

                // ← Verificar etapas cada vez que llega al paso 3
                verificar_etapas_al_llegar();
            } else {
                $('#btn_siguiente_plaza').removeClass('d-none');
                $('#btn_guardar_plaza').addClass('d-none');
            }
        });

        $('#smartwizard_plaza').smartWizard({
            selected: 0,
            theme: 'arrows',
            transition: {
                animation: 'slide-horizontal',
            },
            toolbarSettings: {
                toolbarPosition: '',
                toolbarExtraButtons: [btnAtras, btnSiguiente, btnGuardar],
                showNextButton: false,
                showPreviousButton: false,
            },
        });
    }

    // ─── Verifica etapas al llegar al paso 3 ───────────────────────────────────
    function verificar_etapas_al_llegar() {
        var _id_plaza = '<?= $_id_plaza ?>';
        if (!_id_plaza) {
            validar_etapas_para_guardar(false);
            return;
        }

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?listar=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id_plaza: _id_plaza
            },
            success: function(response) {
                validar_etapas_para_guardar(response && response.length > 0);
            },
            error: function() {
                validar_etapas_para_guardar(false);
            }
        });
    }

    // ─── Habilitar / deshabilitar botón Guardar ────────────────────────────────
    // Esta función también es llamada desde plaza_paso3.php (notificar_estado_etapas)
    function validar_etapas_para_guardar(hay_etapas) {
        if (hay_etapas) {
            $('#btn_guardar_plaza')
                .prop('disabled', false)
                .removeClass('btn-secondary')
                .addClass('btn-success');
        } else {
            $('#btn_guardar_plaza')
                .prop('disabled', true)
                .removeClass('btn-success')
                .addClass('btn-secondary');
        }
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────
    function obtener_formulario_paso(pasoActual) {
        switch (pasoActual) {
            case 0:
                return $('#form_plaza');
            default:
                return null;
        }
    }

    function ajustarAlturaContenedor() {
        $('#tab_content_smart').css('height', 'auto');
    }

    // ─── Guardar paso 3 ────────────────────────────────────────────────────────
    function guardar_paso3() {
        var _id_plaza = '<?= $_id_plaza ?>';

        if (!_id_plaza) {
            Swal.fire('', 'Primero debes completar el Paso 1 para registrar la plaza.', 'warning');
            return;
        }

        Swal.fire({
            title: '¿Guardar plaza?',
            text: '¿Deseas finalizar y guardar el proceso de la plaza?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?cambiar_estado_plaza=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    parametros: {
                        '_id': _id_plaza,
                        'id_plaza_estados': 1,
                        'accion': 'Insertar Borrador',
                    }
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Plaza Guardada',
                        html: `
                            <div class="text-start small">
                                <p>La plaza se ha guardado correctamente, pero <b>todavía se encuentra en estado de Borrador</b>.</p>
                                <p>Para que la plaza sea publicada, debe pasar a la siguiente etapa de revisión donde será:</p>
                                <ul>
                                    <li><span class="badge bg-success">Aprobada</span></li>
                                    <li><span class="badge bg-danger">Rechazada</span></li>
                                    <li><span class="badge bg-warning text-dark">Pendiente</span></li>
                                </ul>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Entendido'
                    }).then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=cn_plazas';
                    });
                },
                error: function(xhr) {
                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });
        });
    }
</script>

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
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php echo $_id_plaza == '' ? 'Registrar Plaza' : 'Modificar Plaza'; ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=cn_plazas"
                                        class="btn btn-outline-dark btn-sm">
                                        <i class="bx bx-arrow-back"></i> Regresar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div id="smartwizard_plaza">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-1">
                                        <strong>Paso 1</strong><br>Plaza
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-2">
                                        <strong>Paso 2</strong><br>Requisitos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-3">
                                        <strong>Paso 3</strong><br>Etapas del Proceso
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" id="tab_content_smart">
                                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1" data-step="0">
                                    <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/PLAZAS/WIZART_REGISTRAR_PLAZA/plaza_paso1.php'); ?>
                                </div>
                                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2" data-step="2">
                                    <?php include_once('../vista/TALENTO_HUMANO/CARGOS/seccion_aspectos_extrinsecos.php'); ?>
                                </div>
                                <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3" data-step="3">
                                    <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/PLAZAS/WIZART_REGISTRAR_PLAZA/plaza_paso3.php'); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<link rel="stylesheet" href="../assets/css/css-navs-menus.css">

<style>
    .table-hover tbody tr:hover {
        background-color: #fbfbfb !important;
    }

    .table tbody tr:first-child td {
        border-top: 0;
    }
</style>