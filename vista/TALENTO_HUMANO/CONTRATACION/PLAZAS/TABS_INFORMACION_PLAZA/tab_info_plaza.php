<script>
    $(document).ready(function() {
        cargar_plaza_detalle_completo('<?= $_id_plaza ?>');
    });
</script>

<script>
    function cargar_plaza_detalle_completo(id_plaza) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?listar_plaza_detalle_completo=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id_plaza: id_plaza
            },
            success: function(response) {

                const p = response.plaza;
                const cargo = response.cargo;

                // ==========================
                // HEADER Y DATOS GENERALES
                // ==========================
                $('#txt_header_titulo').text(p.cn_pla_titulo);
                $('#txt_cargo_nombre_oficial').text(cargo.nombre);
                $('#txt_header_departamento').text(p.departamento.th_dep_nombre);
                $('#txt_responsable').text(p.per_nombre_completo);
                $('#txt_vacantes').text(p.cn_pla_num_vacantes);
                $('#txt_nomina').text(p.descripcion_nomina);
                $('#txt_tipo_seleccion').text(p.tipo_seleccion.descripcion);

                $('#txt_fecha_inicio').text(new Date(p.cn_pla_fecha_publicacion).toLocaleDateString());
                $('#txt_fecha_cierre').text(new Date(p.cn_pla_fecha_cierre).toLocaleDateString());
                $('#txt_header_salario').text(`$${p.cn_pla_salario_min} - $${p.cn_pla_salario_max}`);

                // Prioridad
                if (p.cn_pla_req_prioridad_interna == 1) {
                    $('#badge_prioridad').html('<span class="badge-custom badge-warning"><i class="fas fa-bolt me-1"></i> PRIORIDAD INTERNA</span>');
                } else {
                    $('#badge_prioridad').html('<span class="badge-custom badge-light">SELECCIÓN ABIERTA</span>');
                }

                // ==========================
                // DESCRIPCIÓN
                // ==========================
                $('#txt_puesto_descripcion').text(
                    p.cn_pla_descripcion || cargo.descripcion || 'Sin descripción.'
                );

                // ==========================
                // RESPONSABILIDADES (PLAZA + CARGO)
                // ==========================
                const responsabilidades = [
                    ...(cargo.responsabilidades_cargo || []),
                    ...(p.responsabilidades_plaza || [])
                ];

                const respHTML = responsabilidades.map(r => `
                <div class="resp-item">
                    <i class="fas fa-check-circle text-success me-2"></i> ${r.descripcion}
                </div>
            `).join('');

                $('#lista_puesto_responsabilidades').html(
                    respHTML || '<p class="text-muted">No definidas</p>'
                );

                // ==========================
                // APTITUDES (PLAZA + CARGO)
                // ==========================
                const aptitudes = [
                    ...(cargo.aptitudes_cargo || []),
                    ...(p.aptitudes_plaza || [])
                ];

                const aptitudesHTML = aptitudes.map(a => `
                <span class="badge-pill ${a.tipo_habilidad === 'Blanda' ? 'pill-purple' : 'pill-blue'}">
                    ${a.habilidad}
                </span>
            `).join('');

                $('#cont_puesto_aptitudes').html(
                    aptitudesHTML || '<span class="text-muted small">No definidas</span>'
                );

                // ==========================
                // PERFIL ACADÉMICO
                // ==========================
                const instruccion = [
                    ...(cargo.instruccion_cargo || []),
                    ...(p.requisitos_instruccion_plaza || [])
                ];

                $('#lista_req_instruccion').html(
                    instruccion.map(i => `
                    <div class="fw-bold mb-1 text-dark">
                        <i class="fas fa-certificate me-2 text-primary"></i>${i.nivel_academico}
                    </div>
                `).join('') || 'N/A'
                );

                const areasEstudio = [
                    ...(cargo.areas_estudio_cargo || []),
                    ...(p.areas_estudio_plaza || [])
                ];

                $('#badge_req_areas_estudio').html(
                    areasEstudio.map(a => `
                    <span class="badge-pill pill-gray">${a.area_estudio}</span>
                `).join('') || 'N/A'
                );

                // ==========================
                // EXPERIENCIA (PLAZA + CARGO)
                // ==========================
                const experiencia = [
                    ...(cargo.experiencia_cargo || []),
                    ...(p.experiencia_plaza || [])
                ];

                const expHTML = experiencia.map(e => `
                <div class="item-row mb-2">
                    <span class="small fw-bold text-dark text-truncate" style="max-width:140px">
                        ${e.nombre}
                    </span>
                    <span class="badge-pill pill-blue ms-auto">
                        ${e.min_anios_exp}-${e.max_anios_exp} años
                    </span>
                </div>
            `).join('');

                $('#lista_req_experiencia').html(
                    expHTML || '<span class="text-muted small">Sin experiencia requerida</span>'
                );

                // ==========================
                // IDIOMAS (PLAZA + CARGO)
                // ==========================
                const idiomas = [
                    ...(cargo.idiomas_cargo || []),
                    ...(p.idiomas_plaza || [])
                ];

                const idiomasHTML = idiomas.map(i => `
                <div class="item-row mb-2">
                    <span class="small fw-bold">${i.idioma}</span>
                    <span class="badge-pill pill-success ms-auto">${i.nivel}</span>
                </div>
            `).join('');

                $('#lista_req_idiomas').html(
                    idiomasHTML || '<span class="text-muted small">Sin requerimientos</span>'
                );

                // ==========================
                // REQUISITOS FÍSICOS (PLAZA + CARGO)
                // ==========================
                const requisitosFisicos = [
                    ...(cargo.requisitos_fisicos_cargo || []),
                    ...(p.requisitos_fisicos_plaza || [])
                ];

                const fisicosHTML = requisitosFisicos.map(f => `
                <div class="item-row small mb-2">
                    <i class="fas fa-running me-2 text-primary"></i>${f.descripcion}
                </div>
            `).join('');

                $('#lista_entorno_fisico').html(
                    fisicosHTML || '<span class="text-muted small">Sin requerimientos físicos</span>'
                );

                // ==========================
                // RIESGOS Y CONDICIONES (PLAZA + CARGO)
                // ==========================
                const riesgos = [
                    ...(cargo.riesgos_cargo || []),
                    ...(p.riesgos_plaza || [])
                ];

                const condiciones = [
                    ...(cargo.condiciones_trabajo_cargo || []),
                    ...(p.condiciones_trabajo_plaza || [])
                ];

                const riesgosHTML = riesgos.map(r => `
                <div class="item-row small mb-2 text-danger fw-medium">
                    <i class="fas fa-skull-crossbones me-2"></i>${r.descripcion}
                </div>
            `).join('');

                const condicionesHTML = condiciones.map(ct => `
                <div class="item-row small mb-2 text-muted">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>${ct.descripcion}
                </div>
            `).join('');

                $('#lista_entorno_riesgos').html(
                    (riesgosHTML + condicionesHTML) ||
                    '<span class="text-muted small">Entorno seguro</span>'
                );

            }
        });
    }
</script>

<div id="contenedor_detalle_completo" class="job-detail-container">
    <header class="glass-card header-main mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge-custom badge-purple" id="txt_tipo_seleccion"></span>
                    <div id="badge_prioridad"></div>
                </div>
                <h1 class="job-title" id="txt_header_titulo"></h1>
                <div class="job-subtitle-wrapper">
                    <span class="label-tag">CARGO</span>
                    <h2 class="job-subtitle" id="txt_cargo_nombre_oficial"></h2>
                </div>
                <div class="info-pill-container">
                    <div class="info-pill"><i class="fas fa-layer-group"></i> <span id="txt_header_departamento"></span></div>
                    <div class="info-pill"><i class="fas fa-users"></i> Vacantes: <b id="txt_vacantes"></b></div>
                    <div class="info-pill"><i class="fas fa-user-tie"></i> <span id="txt_responsable"></span></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-label">Rango Salarial</span>
                        <div class="stat-value text-primary" id="txt_header_salario"></div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Tipo de Nómina</span>
                        <div class="stat-value small" id="txt_nomina"></div>
                    </div>
                    <div class="stat-card timeline-card">
                        <div class="date-item">
                            <small>PUBLICACIÓN</small>
                            <span id="txt_fecha_inicio"></span>
                        </div>
                        <div class="date-divider"></div>
                        <div class="date-item text-danger">
                            <small>CIERRE</small>
                            <span id="txt_fecha_cierre" class="fw-bold"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass-card border-top-primary p-4 mb-4">
                <h6 class="section-title"><i class="fas fa-align-left"></i> Descripción del Puesto</h6>
                <p id="txt_puesto_descripcion" class="description-text"></p>

                <h6 class="section-title mt-5"><i class="fas fa-list-check"></i> Responsabilidades Clave</h6>
                <div id="lista_puesto_responsabilidades" class="custom-list"></div>

                <h6 class="section-title mt-5"><i class="fas fa-bolt"></i> Competencias y Aptitudes</h6>
                <div id="cont_puesto_aptitudes" class="d-flex flex-wrap gap-2"></div>

                <h6 class="section-title mt-5"><i class="fas fa-shield-heart"></i> Salud y Entorno</h6>
                <div class="health-grid">
                    <div class="health-item border-start-primary">
                        <span class="health-label">Condiciones Físicas</span>
                        <div id="lista_entorno_fisico"></div>
                    </div>
                    <div class="health-item border-start-warning">
                        <span class="health-label">Riesgos y Seguridad</span>
                        <div id="lista_entorno_riesgos"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-card border-top-purple p-4 mb-4">
                <h6 class="section-title small-title"><i class="fas fa-graduation-cap"></i> Perfil Académico</h6>
                <div id="lista_req_instruccion" class="mb-3"></div>
                <small class="text-muted d-block mb-2 uppercase-xs">Especialidades</small>
                <div id="badge_req_areas_estudio" class="d-flex flex-wrap gap-1"></div>
            </div>

            <div class="glass-card border-top-success p-4">
                <h6 class="section-title small-title"><i class="fas fa-briefcase"></i> Experiencia e Idiomas</h6>
                <div id="lista_req_experiencia" class="mb-4 border-bottom pb-3"></div>
                <div id="lista_req_idiomas"></div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #2563eb;
        --purple: #7e22ce;
        --success: #059669;
        --warning: #d97706;
        --border: #e2e8f0;
        --text-main: #334155;
    }

    /* Contenedor Limpio */
    .job-detail-container {
        font-family: 'Inter', system-ui, sans-serif;
        color: var(--text-main);
    }

    /* Cards con Bordes de Color */
    .glass-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .border-top-primary {
        border-top: 4px solid var(--primary);
    }

    .border-top-purple {
        border-top: 4px solid var(--purple);
    }

    .border-top-success {
        border-top: 4px solid var(--success);
    }

    .header-main {
        padding: 2rem;
        border-left: 5px solid var(--primary);
    }

    /* Tipografía */
    .job-title {
        font-size: 1.7rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .job-subtitle {
        font-size: 1.1rem;
        color: #64748b;
        font-weight: 600;
        margin: 0;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.25rem;
    }

    .section-title::after {
        content: "";
        height: 1px;
        flex-grow: 1;
        background: var(--border);
    }

    /* Badges y Pills */
    .badge-custom {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .badge-purple {
        background: #f5f3ff;
        color: var(--purple);
        border-color: #ddd6fe;
    }

    .badge-warning {
        background: #fffbeb;
        color: var(--warning);
        border-color: #fde68a;
    }

    .badge-light {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }

    .badge-pill {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .pill-purple {
        background: #f3e8ff;
        color: var(--purple);
    }

    .pill-blue {
        background: #e0f2fe;
        color: #0369a1;
    }

    .pill-success {
        background: #d1fae5;
        color: #065f46;
    }

    .pill-gray {
        background: #f8fafc;
        color: #64748b;
        border: 1px solid var(--border);
    }

    /* Estructuras Internas */
    .info-pill-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .info-pill {
        padding: 6px 12px;
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .resp-item {
        padding: 10px 15px;
        background: #eff6ff;
        border-radius: 8px;
        margin-bottom: 8px;
        border-left: 3px solid var(--primary);
        font-size: 0.9rem;
    }

    .item-row {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        width: 100%;
    }

    /* Stats y Salud */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .stat-card {
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 10px;
    }

    .stat-label {
        font-size: 0.6rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        display: block;
    }

    .timeline-card {
        grid-column: span 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .date-divider {
        width: 1px;
        height: 20px;
        background: var(--border);
    }

    .health-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .health-item {
        padding: 15px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #fff;
        text-align: left;
    }

    .border-start-primary {
        border-left: 4px solid var(--primary);
    }

    .border-start-warning {
        border-left: 4px solid var(--warning);
    }

    .health-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
        color: #64748b;
    }

    .uppercase-xs {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    @media (max-width: 768px) {
        .health-grid {
            grid-template-columns: 1fr;
        }
    }
</style>