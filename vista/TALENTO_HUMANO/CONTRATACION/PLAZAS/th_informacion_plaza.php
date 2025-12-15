<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    <?php if (isset($_GET['_id'])) { ?>
    cargar_resumen_plaza(<?= $_id ?>);
    <?php } ?>

    function cargar_resumen_plaza(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?resumen_plaza=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) {
                    Swal.fire('', 'No se encontró información de la plaza', 'warning');
                    return;
                }
                var plaza = response[0];

                $('#titulo_plaza').text(plaza.th_pla_titulo || 'Sin título');

                if (plaza.th_pla_tipo) {
                    var tipo = plaza.th_pla_tipo.toLowerCase();
                    var color = tipo === 'interna' ? 'info' : tipo === 'externa' ? 'success' :
                        'warning';
                    $('#badge_tipo').html(
                        `<span class="badge bg-${color} fs-6 px-3 py-2">${plaza.th_pla_tipo}</span>`
                    );
                }

                $('#stat_vacantes').text(plaza.th_pla_num_vacantes || '0');

                var salario = 'No especificado';
                if (plaza.th_pla_salario_min || plaza.th_pla_salario_max) {
                    var min = parseFloat(plaza.th_pla_salario_min) || 0;
                    var max = parseFloat(plaza.th_pla_salario_max) || 0;
                    if (min === max) {
                        salario = '$' + min.toFixed(2);
                    } else {
                        salario = '$' + min.toFixed(2) + ' - $' + max.toFixed(2);
                    }
                }
                $('#stat_salario').text(salario);
                $('#stat_contrato').text(plaza.th_pla_tiempo_contrato || 'Indefinido');

                $('#fecha_publicacion').text(formatDate(plaza.th_pla_fecha_publicacion));
                $('#fecha_cierre').text(formatDate(plaza.th_pla_fecha_cierre));
                $('#descripcion_plaza').text(plaza.th_pla_descripcion ||
                    'Sin descripción disponible');

                var configs = [];
                if (plaza.th_pla_prioridad_interna == 1) {
                    configs.push(
                        '<span class="badge bg-info me-2 mb-2"><i class="bx bx-star me-1"></i>Prioridad Interna</span>'
                    );
                }
                if (plaza.th_pla_requiere_documentos == 1) {
                    configs.push(
                        '<span class="badge bg-warning text-dark me-2 mb-2"><i class="bx bx-file me-1"></i>Requiere Documentos</span>'
                    );
                }
                if (plaza.th_pla_responsable_persona_id) {
                    configs.push(
                        `<span class="badge bg-secondary me-2 mb-2"><i class="bx bx-user-check me-1"></i>Responsable ID: ${plaza.th_pla_responsable_persona_id}</span>`
                    );
                }
                $('#configuraciones').html(configs.join('') ||
                    '<small class="text-muted">No hay configuraciones adicionales</small>');

                if (plaza.cargos_resumen) {
                    var cargos = plaza.cargos_resumen.split(', ');
                    $('#lista_cargos').html(cargos.map(c =>
                        `<span class="badge  bg-opacity-10 text-primary border border-primary me-2 mb-2"><i class="bx bx-briefcase-alt me-1"></i>${c}</span>`
                    ).join(''));
                } else {
                    $('#lista_cargos').html(
                        '<small class="text-muted">No hay cargos asignados</small>');
                }

                if (plaza.requisitos_resumen) {
                    var requisitos = plaza.requisitos_resumen.split(', ');
                    var html = requisitos.map(req => {
                        var obligatorio = req.includes('(Obligatorio)');
                        var icon = obligatorio ? 'bx-check-shield text-danger' :
                            'bx-check-circle text-success';
                        var bgClass = obligatorio ?
                            'bg-opacity-10 border-danger' :
                            'bg-success bg-opacity-10 border-success';
                        return `<div class="d-flex align-items-start border ${bgClass} rounded p-3 mb-2">
                                    <i class="bx ${icon} fs-4 me-2"></i>
                                    <span class="flex-grow-1">${req}</span>
                                </div>`;
                    }).join('');
                    $('#lista_requisitos').html(html);
                } else {
                    $('#lista_requisitos').html(
                        '<small class="text-muted">No hay requisitos especificados</small>');
                }

                if (plaza.etapas_resumen) {
                    var etapas = plaza.etapas_resumen.split(' -> ');
                    var html = etapas.map((etapa, i) => {
                        var limpio = etapa.replace(/^\d+\.\s*/, '');
                        return `
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 40px; height: 40px;">
                                    ${i + 1}
                                </div>
                                <div class="bg-light border rounded px-3 py-2 flex-grow-1">
                                    <strong>${limpio}</strong>
                                </div>
                            </div>
                        `;
                    }).join(
                        '<div class="text-center text-primary fs-3 my-2"><i class="bx bx-chevron-down"></i></div>'
                    );
                    $('#proceso_etapas').html(html);
                } else {
                    $('#proceso_etapas').html(
                        '<small class="text-muted">No hay etapas definidas</small>');
                }

                $('#observaciones_content').text(plaza.th_pla_observaciones ||
                    'Sin observaciones adicionales');
                $('#btn_postulaciones').attr('href',
                    `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_postulados&_id=${id}`
                );
                $('#btn_gestionar_etapas').attr('href',
                    `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_etapas&_id=${id}`
                );
            },
            error: function(err) {
                Swal.fire('Error', 'No se pudo cargar la información', 'error');
            }
        });
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        try {
            return new Date(dateStr).toLocaleDateString('es-EC', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        } catch (e) {
            return dateStr;
        }
    }
});
</script>

<style>

</style>

<div class="page-wrapper bg-light">
    <div class="page-content p-0">

        <!-- Header Section -->
        <div class="text-white p-4 shadow-sm">
            <div class="container-fluid">
                <div class="row align-items-center g-3">
                    <div class="col-lg-6">
                        <h2 class="fw-bold mb-2" id="titulo_plaza">Cargando...</h2>
                        <div id="badge_tipo"></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas"
                                class="btn btn-light">
                                <i class="bx bx-arrow-back me-1"></i> Regresar
                            </a>
                            <a href="#" id="btn_gestionar_etapas" class="btn btn-primary">
                                <i class="bx bx-list-ol me-1"></i> Gestionar Etapas
                            </a>
                            <a href="#" id="btn_postulaciones" class="btn btn-success">
                                <i class="bx bx-group me-1"></i> Ver Postulaciones
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4">

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                    <i class="bx bx-user-plus text-primary fs-2"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted text-uppercase small mb-1">Vacantes Disponibles</h6>
                                    <h3 class="mb-0 fw-bold" id="stat_vacantes">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                    <i class="bx bx-dollar-circle text-success fs-2"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted text-uppercase small mb-1">Rango Salarial</h6>
                                    <h3 class="mb-0 fw-bold" id="stat_salario">$0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                                    <i class="bx bx-time-five text-warning fs-2"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted text-uppercase small mb-1">Tipo de Contrato</h6>
                                    <h4 class="mb-0 fw-bold" id="stat_contrato">-</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="row g-3">

                <!-- Main Column -->
                <div class="col-lg-8">
                    <!-- Description -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                    <i class="bx bx-file-blank text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">Descripción de la Plaza</h5>
                            </div>
                            <p class="text-secondary lh-lg text-justify mb-0" id="descripcion_plaza">Cargando
                                información...</p>
                        </div>
                    </div>

                    <!-- Selection Process -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                    <i class="bx bx-git-branch text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">Proceso de Selección</h5>
                            </div>
                            <div id="proceso_etapas">
                                Cargando etapas...
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                    <i class="bx bx-list-check text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">Requisitos del Puesto</h5>
                            </div>
                            <div id="lista_requisitos">
                                Cargando requisitos...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    <!-- Dates -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center border-bottom pb-2 mb-3">
                                <i class="bx bx-calendar text-primary fs-5 me-2"></i>
                                <h6 class="mb-0 fw-bold">Fechas Importantes</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted text-uppercase d-block mb-1 fw-semibold">Fecha de
                                    Publicación</small>
                                <div class="fw-bold" id="fecha_publicacion">-</div>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase d-block mb-1 fw-semibold">Fecha de
                                    Cierre</small>
                                <div class="fw-bold" id="fecha_cierre">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuration -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center border-bottom pb-2 mb-3">
                                <i class="bx bx-cog text-primary fs-5 me-2"></i>
                                <h6 class="mb-0 fw-bold">Configuración</h6>
                            </div>
                            <div id="configuraciones">
                                Cargando configuración...
                            </div>
                        </div>
                    </div>

                    <!-- Positions -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center border-bottom pb-2 mb-3">
                                <i class="bx bx-briefcase-alt-2 text-primary fs-5 me-2"></i>
                                <h6 class="mb-0 fw-bold">Cargos Relacionados</h6>
                            </div>
                            <div id="lista_cargos" class="d-flex flex-wrap">
                                Cargando cargos...
                            </div>
                        </div>
                    </div>

                    <!-- Observations -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center border-bottom pb-2 mb-3">
                                <i class="bx bx-message-square-detail text-primary fs-5 me-2"></i>
                                <h6 class="mb-0 fw-bold">Observaciones</h6>
                            </div>
                            <p class="text-muted fst-italic small mb-0 lh-base" id="observaciones_content">
                                Cargando observaciones...
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>