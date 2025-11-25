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
                    var color = tipo === 'interna' ? 'primary' : tipo === 'externa' ? 'success' :
                        'warning';
                    $('#badge_tipo').html(
                        `<span class="badge bg-${color}">${plaza.th_pla_tipo}</span>`);
                }

                $('#stat_vacantes').text(plaza.th_pla_num_vacantes || '0');

                var salario = 'No especificado';
                if (plaza.th_pla_salario_min || plaza.th_pla_salario_max) {
                    salario = ' + (parseFloat(plaza.th_pla_salario_min) || 0).toFixed(2) + ' - +(
                        parseFloat(plaza.th_pla_salario_max) || 0).toFixed(2);
                }
                $('#stat_salario').text(salario);
                $('#stat_contrato').text(plaza.th_pla_tiempo_contrato || 'Indefinido');

                $('#fecha_publicacion').text(formatDate(plaza.th_pla_fecha_publicacion));
                $('#fecha_cierre').text(formatDate(plaza.th_pla_fecha_cierre));
                $('#descripcion_plaza').text(plaza.th_pla_descripcion || 'Sin descripción');

                var configs = [];
                if (plaza.th_pla_prioridad_interna == 1) {
                    configs.push(
                        '<span class="badge bg-info"><i class="bx bx-star"></i> Prioridad Interna</span>'
                        );
                }
                if (plaza.th_pla_requiere_documentos == 1) {
                    configs.push(
                        '<span class="badge bg-warning text-dark"><i class="bx bx-file"></i> Requiere Docs</span>'
                        );
                }
                if (plaza.th_pla_responsable_persona_id) {
                    configs.push(
                        `<span class="badge bg-secondary"><i class="bx bx-user-check"></i> Resp: ${plaza.th_pla_responsable_persona_id}</span>`
                        );
                }
                $('#configuraciones').html(configs.join(' ') ||
                    '<small class="text-muted">Sin configuraciones</small>');

                if (plaza.cargos_resumen) {
                    var cargos = plaza.cargos_resumen.split(', ');
                    $('#lista_cargos').html(cargos.map(c =>
                        `<span class="badge bg-light text-dark border"><i class="bx bx-briefcase-alt"></i> ${c}</span>`
                        ).join(' '));
                } else {
                    $('#lista_cargos').html('<small class="text-muted">Sin cargos</small>');
                }

                if (plaza.requisitos_resumen) {
                    var requisitos = plaza.requisitos_resumen.split(', ');
                    var html = requisitos.map(req => {
                        var obligatorio = req.includes('(Obligatorio)');
                        var icon = obligatorio ? 'bx-error-circle text-danger' :
                            'bx-check-circle text-success';
                        return `<li class="small"><i class="bx ${icon}"></i> ${req}</li>`;
                    }).join('');
                    $('#lista_requisitos').html(`<ul class="mb-0 ps-3">${html}</ul>`);
                } else {
                    $('#lista_requisitos').html('<small class="text-muted">Sin requisitos</small>');
                }

                if (plaza.etapas_resumen) {
                    var etapas = plaza.etapas_resumen.split(' -> ');
                    var html = etapas.map((etapa, i) => {
                        var limpio = etapa.replace(/^\d+\.\s*/, '');
                        return `<span class="badge bg-primary">${i + 1}. ${limpio}</span>`;
                    }).join(' <i class="bx bx-right-arrow-alt text-primary"></i> ');
                    $('#proceso_etapas').html(html);
                } else {
                    $('#proceso_etapas').html('<small class="text-muted">Sin etapas</small>');
                }

                $('#observaciones_content').text(plaza.th_pla_observaciones || 'Sin observaciones');
                $('#btn_postulaciones').attr('href',
                    `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_postulados&_id=${id}`
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
                month: 'short',
                day: 'numeric'
            });
        } catch (e) {
            return dateStr;
        }
    }
});
</script>

<style>
.stat-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    border-left: 3px solid;
}

.stat-box.primary {
    border-left-color: #4776E6;
}

.stat-box.success {
    border-left-color: #28a745;
}

.stat-box.warning {
    border-left-color: #ffc107;
}

.section-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.section-title i {
    color: #4776E6;
    font-size: 1.2rem;
}

.header-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 8px;
    color: white;
    margin-bottom: 20px;
}
</style>

<div class="page-wrapper">
    <div class="page-content p-3">

        <div class="header-bar">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="mb-1 fw-bold" id="titulo_plaza">Cargando...</h5>
                    <div id="badge_tipo"></div>
                </div>
                <div>
                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas"
                        class="btn btn-light btn-sm me-2">
                        <i class="bx bx-arrow-back"></i> Regresar
                    </a>
                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_registro&_id=<?= $_id ?>"
                        class="btn btn-warning btn-sm me-2">
                        <i class="bx bx-edit"></i> Editar
                    </a>
                    <a href="#" id="btn_postulaciones" class="btn btn-success btn-sm">
                        <i class="bx bx-briefcase"></i> Postulaciones
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-9">

                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <div class="stat-box primary">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-user-plus fs-4 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Vacantes</small>
                                    <strong class="fs-5" id="stat_vacantes">0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box success">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-dollar-circle fs-4 text-success"></i>
                                <div>
                                    <small class="text-muted d-block">Salario</small>
                                    <strong class="fs-6" id="stat_salario">$0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box warning">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-time-five fs-4 text-warning"></i>
                                <div>
                                    <small class="text-muted d-block">Contrato</small>
                                    <strong class="fs-6" id="stat_contrato">-</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-title"><i class="bx bx-file-blank"></i> Descripción</div>
                    <p class="mb-0 small text-secondary" id="descripcion_plaza">Cargando...</p>
                </div>

                <div class="section-card">
                    <div class="section-title"><i class="bx bx-git-branch"></i> Proceso de Selección</div>
                    <div id="proceso_etapas" class="d-flex flex-wrap gap-2 align-items-center">Cargando...</div>
                </div>

                <div class="section-card">
                    <div class="section-title"><i class="bx bx-list-check"></i> Requisitos</div>
                    <div id="lista_requisitos">Cargando...</div>
                </div>

            </div>

            <div class="col-lg-3">

                <div class="section-card">
                    <div class="section-title"><i class="bx bx-calendar"></i> Fechas</div>
                    <div class="small">
                        <div class="mb-2">
                            <span class="text-muted">Publicación:</span><br>
                            <strong id="fecha_publicacion">-</strong>
                        </div>
                        <div>
                            <span class="text-muted">Cierre:</span><br>
                            <strong id="fecha_cierre">-</strong>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-title"><i class="bx bx-cog"></i> Configuración</div>
                    <div id="configuraciones" class="d-flex flex-wrap gap-2">Cargando...</div>
                </div>

                <div class="section-card">
                    <div class="section-title"><i class="bx bx-briefcase-alt-2"></i> Cargos</div>
                    <div id="lista_cargos" class="d-flex flex-wrap gap-2">Cargando...</div>
                </div>

                <div class="section-card">
                    <div class="section-title"><i class="bx bx-message-square-detail"></i> Observaciones</div>
                    <p class="mb-0 small text-muted fst-italic" id="observaciones_content">Cargando...</p>
                </div>

            </div>
        </div>

    </div>
</div>