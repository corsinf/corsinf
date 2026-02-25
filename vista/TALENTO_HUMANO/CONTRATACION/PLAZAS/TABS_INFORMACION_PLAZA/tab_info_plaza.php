 <script>
    $(document).ready(function() {
        cargar_plaza('<?= $_id_plaza ?>');
        cargar_resumen_plaza('<?= $_id_plaza ?>');
    });

    function cargar_plaza(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) return;
                var r = response[0];

                // --- HEADER & TÍTULO ---
                $('#titulo_plaza').text(r.cn_pla_titulo);
                $('#lbl_departamento').text("Departamento: "+r.descripcion_departamento);
                $('#lbl_cargo').text("Cargo: "+r.descripcion_cargo);

                // --- TAB INFO: COLUMNA IZQUIERDA (Textos) ---
                $('#descripcion_plaza').text(r.cn_pla_descripcion || 'Sin descripción disponible.');
                $('#observaciones_content').text(r.cn_pla_observaciones || 'Sin observaciones adicionales.');

                // --- TAB INFO: COLUMNA DERECHA (Stats) ---
                $('#stat_vacantes').text(r.cn_pla_num_vacantes);
                $('#stat_contrato').text(r.descripcion_nomina || 'No asignada');
                $('#stat_tipo_seleccion').text(r.descripcion_tipo_seleccion || 'Estándar');

                let salario = (r.cn_pla_salario_min && r.cn_pla_salario_max) ?
                    `<span class="text-success fw-bold">$${r.cn_pla_salario_min} - $${r.cn_pla_salario_max}</span>` :
                    '<span class="text-muted fst-italic">No especificado</span>';
                $('#stat_salario').html(salario);

                $('#fecha_publicacion').text(formatDate(r.cn_pla_fecha_publicacion));
                $('#fecha_cierre').text(formatDate(r.cn_pla_fecha_cierre));

                // --- CONFIGURACIONES (Badges) ---
                let configHtml = '';
                if (boolVal(r.cn_pla_req_prioridad_interna)) {
                    configHtml += `<div class="badge bg-light-info text-info border border-info w-100 mb-2 p-2 text-start">
                                <i class="bx bxs-star me-2"></i> Prioridad Interna</div>`;
                }
                if (boolVal(r.cn_pla_req_disponibilidad)) {
                    configHtml += `<div class="badge bg-light-warning text-warning border border-warning w-100 mb-2 p-2 text-start">
                                <i class="bx bxs-time me-2"></i> Requiere Disponibilidad</div>`;
                }
                if (boolVal(r.cn_pla_req_documentos)) {
                    configHtml += `<div class="badge bg-light-secondary text-secondary border border-secondary w-100 mb-2 p-2 text-start">
                                <i class="bx bxs-file-doc me-2"></i> Validación de Documentos</div>`;
                }

                // Responsable con foto/icono
                if (r.per_nombre_completo) {
                    configHtml += `<div class="p-2 border rounded bg-white mt-2">
                                <small class="text-muted d-block fw-bold" style="font-size:10px;">RESPONSABLE</small>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="bx bx-user-circle fs-4 me-2 text-primary"></i>
                                    <span class="small fw-bold">${r.per_nombre_completo}</span>
                                </div>
                               </div>`;
                }
                $('#configuraciones').html(configHtml);

                $('#btn_postulaciones').attr('href', `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_postulados&_id_plaza=${id}`);
            }
        });
    }

    function boolVal(val) {
        return val === 1 || val === "1" || val === true || val === "true";
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        let date = new Date(dateStr);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }
    
</script>
<script>
    function cargar_resumen_plaza(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?listar_resumen=true',
            type: 'POST',
            dataType: 'json',
            data: { id: id },
            success: function(response) {
                if (!response || !response[0]) return;
                var r = response[0];
                renderizar_resumen_plaza(r);
            }
        });
    }

    function renderizar_resumen_plaza(r) {
        var html = '<div class="rs-wrap">';

        /* ── STATS GENERALES ── */
        html += sec('bx-info-circle', 'Información General');
        html += '<div class="rs-stats">';
        if (r.descripcion_cargo)          html += stat('Cargo',           r.descripcion_cargo,          '');
        if (r.descripcion_departamento)   html += stat('Departamento',    r.descripcion_departamento,   'g');
        if (r.cn_pla_num_vacantes)        html += stat('Vacantes',        r.cn_pla_num_vacantes,        'o');
        if (r.descripcion_tipo_seleccion) html += stat('Tipo Selección',  r.descripcion_tipo_seleccion, 'p');
        if (r.descripcion_nomina)         html += stat('Nómina',          r.descripcion_nomina,         't');
        if (r.cn_pla_req_disponibilidad)  html += stat('Disponibilidad',  r.cn_pla_req_disponibilidad,  'r');
        if (r.cn_pla_fecha_publicacion)   html += stat('Publicación',     formatDate(r.cn_pla_fecha_publicacion), 'y');
        if (r.cn_pla_fecha_cierre)        html += stat('Cierre',          formatDate(r.cn_pla_fecha_cierre),      'r');

        /* Salario */
        if (r.cn_pla_salario_min || r.cn_pla_salario_max) {
            var sMin = r.cn_pla_salario_min ? '$' + parseFloat(r.cn_pla_salario_min).toFixed(2) : '—';
            var sMax = r.cn_pla_salario_max ? '$' + parseFloat(r.cn_pla_salario_max).toFixed(2) : '—';
            html += '<div class="rs-stat y"><div class="rs-stat-lbl">Salario</div>'
                  + '<div class="rs-salario">' + sMin + '<span class="sep">–</span>' + sMax + '</div></div>';
        }
        html += '</div>'; /* /rs-stats */

        /* ── DESCRIPCIÓN ── */
        if (r.cn_pla_descripcion) {
            html += sec('bx-file-blank', 'Descripción del Cargo');
            html += '<div class="rs-desc">' + escHtml(r.cn_pla_descripcion) + '</div>';
        }

        /* ── RESPONSABLE ── */
        if (r.per_nombre_completo) {
            html += sec('bx-user-check', 'Responsable');
            var ini = r.per_nombre_completo.split(' ').slice(0,2).map(function(w){ return w[0]; }).join('').toUpperCase();
            html += '<div class="rs-resp">'
                  + '<div class="rs-resp-av">' + ini + '</div>'
                  + '<div><div class="rs-resp-name">' + escHtml(r.per_nombre_completo) + '</div>'
                  + '<div class="rs-resp-sub">' + (r.per_cedula || '') + '</div></div>'
                  + '</div>';
        }

        /* ── RESPONSABILIDADES ── */
        if (r.responsabilidades) {
            html += sec('bx-task', 'Responsabilidades');
            html += '<div class="rs-tags">';
            r.responsabilidades.split('||').forEach(function(v) {
                if (v) html += tag(v, 'tb', 'bx-check');
            });
            html += '</div>';
        }

        /* ── FORMACIÓN ACADÉMICA ── */
        var tieneInstruccion = r.instruccion && r.instruccion.trim();
        var tieneArea        = r.area_estudio && r.area_estudio.trim();
        if (tieneInstruccion || tieneArea) {
            html += sec('bx-book-open', 'Formación Académica');
            html += '<div class="row g-2">';
            html += '<div class="col-md-6"><div class="rs-hab-box">'
                  + '<div class="rs-hab-tit"><i class="bx bx-graduation" style="color:#8e44ad;"></i> Nivel de Instrucción</div>'
                  + '<div class="rs-tags">';
            if (tieneInstruccion) {
                r.instruccion.split('||').forEach(function(v){ if(v) html += tag(v,'tp','bx-graduation'); });
            } else {
                html += '<span class="rs-empty">Sin especificar</span>';
            }
            html += '</div></div></div>';

            html += '<div class="col-md-6"><div class="rs-hab-box">'
                  + '<div class="rs-hab-tit"><i class="bx bx-book" style="color:#16a085;"></i> Áreas de Estudio</div>'
                  + '<div class="rs-tags">';
            if (tieneArea) {
                r.area_estudio.split('||').forEach(function(v){ if(v) html += tag(v,'tt','bx-book'); });
            } else {
                html += '<span class="rs-empty">Sin especificar</span>';
            }
            html += '</div></div></div>';
            html += '</div>';
        }

        /* ── EXPERIENCIA ── */
        if (r.experiencia && r.experiencia.trim()) {
            html += sec('bx-briefcase', 'Experiencia Requerida');
            html += '<div class="row g-2">';
            r.experiencia.split('||').forEach(function(v) {
                if (!v) return;
                var p = v.split('|');
                var nombre = p[0] || '';
                var min    = p[1] || '';
                var max    = p[2] || '';
                var years  = (min && max) ? min + ' – ' + max + ' años'
                           : (min ? 'Mín. ' + min + ' años' : '');
                html += '<div class="col-md-4 col-6"><div class="rs-exp">'
                      + '<div class="rs-exp-nombre">' + escHtml(nombre) + '</div>'
                      + (years ? '<div class="rs-exp-years"><i class="bx bx-time-five me-1"></i>' + years + '</div>' : '')
                      + '</div></div>';
            });
            html += '</div>';
        }

        /* ── IDIOMAS ── */
        if (r.idiomas && r.idiomas.trim()) {
            html += sec('bx-world', 'Idiomas');
            html += '<div class="rs-tags">';
            r.idiomas.split('||').forEach(function(v) {
                if (!v) return;
                var p     = v.split('|');
                var idioma = p[0] || '';
                var nivel  = p[1] || '';
                html += '<span class="rs-idioma"><i class="bx bx-globe"></i>' + escHtml(idioma)
                      + (nivel ? '<span class="rs-idioma-nivel">' + escHtml(nivel) + '</span>' : '')
                      + '</span>';
            });
            html += '</div>';
        }

        /* ── HABILIDADES ── */
        var tieneTec = r.habilidades_tecnicas && r.habilidades_tecnicas.trim();
        var tieneBla = r.habilidades_blandas  && r.habilidades_blandas.trim();
        if (tieneTec || tieneBla) {
            html += sec('bx-star', 'Habilidades y Aptitudes');
            html += '<div class="rs-hab-grid">';
            html += '<div class="rs-hab-box"><div class="rs-hab-tit"><i class="bx bx-code-alt" style="color:#0d6efd;"></i> Técnicas</div><div class="rs-tags">';
            if (tieneTec) {
                r.habilidades_tecnicas.split('||').forEach(function(v){ if(v) html += tag(v,'tb','bx-code-alt'); });
            } else { html += '<span class="rs-empty">Sin especificar</span>'; }
            html += '</div></div>';

            html += '<div class="rs-hab-box"><div class="rs-hab-tit"><i class="bx bx-heart" style="color:#e74c3c;"></i> Blandas</div><div class="rs-tags">';
            if (tieneBla) {
                r.habilidades_blandas.split('||').forEach(function(v){ if(v) html += tag(v,'tr','bx-heart'); });
            } else { html += '<span class="rs-empty">Sin especificar</span>'; }
            html += '</div></div>';
            html += '</div>';
        }

        /* ── INICIATIVAS ── */
        if (r.iniciativas && r.iniciativas.trim()) {
            html += sec('bx-bulb', 'Iniciativas');
            html += '<div class="rs-tags">';
            r.iniciativas.split('||').forEach(function(v){ if(v) html += tag(v,'ty','bx-bulb'); });
            html += '</div>';
        }

        /* ── CONDICIONES DE TRABAJO ── */
        if (r.condiciones_trabajo && r.condiciones_trabajo.trim()) {
            html += sec('bx-hard-hat', 'Condiciones de Trabajo');
            html += '<div class="rs-tags">';
            r.condiciones_trabajo.split('||').forEach(function(v){ if(v) html += tag(v,'to','bx-hard-hat'); });
            html += '</div>';
        }

        /* ── RIESGOS ── */
        if (r.riesgos && r.riesgos.trim()) {
            html += sec('bx-error-alt', 'Riesgos');
            html += '<div class="rs-tags">';
            r.riesgos.split('||').forEach(function(v){ if(v) html += tag(v,'tr','bx-error-alt'); });
            html += '</div>';
        }

        /* ── REQUISITOS FÍSICOS ── */
        if (r.requisitos_fisicos && r.requisitos_fisicos.trim()) {
            html += sec('bx-run', 'Requisitos Físicos');
            var grupos = {};
            r.requisitos_fisicos.split('||').forEach(function(v) {
                if (!v) return;
                var p   = v.split('|');
                var cat = p[0] || 'General';
                var det = p[1] || '';
                if (!grupos[cat]) grupos[cat] = [];
                grupos[cat].push(det);
            });
            Object.keys(grupos).forEach(function(cat) {
                html += '<div class="rs-fis-grupo"><div class="rs-fis-cat"><i class="bx bx-run me-1"></i>' + escHtml(cat) + '</div><div class="rs-tags">';
                grupos[cat].forEach(function(d){ if(d) html += tag(d,'tgr',''); });
                html += '</div></div>';
            });
        }

        /* ── OBSERVACIONES ── */
        if (r.cn_pla_observaciones) {
            html += sec('bx-comment-detail', 'Observaciones Internas');
            html += '<div class="rs-obs">' + escHtml(r.cn_pla_observaciones) + '</div>';
        }

        /* ── INDICADORES ── */
        html += sec('bx-flag', 'Indicadores');
        html += '<div class="d-flex flex-wrap gap-2">';
        html += flag('Prioridad Interna',      boolVal(r.cn_pla_req_prioridad_interna), 'bx-flag');
        html += flag('Requiere Documentos',    boolVal(r.cn_pla_req_documentos),        'bx-file');
        html += flag('Requiere Disponibilidad',boolVal(r.cn_pla_req_disponibilidad),    'bx-time');
        html += '</div>';

        html += '</div>'; /* /rs-wrap */
        $('#pnl_resumen_plaza').html(html);
    }

    function sec(icon, label) {
        return '<div class="rs-sec-title mt-3"><i class="bx ' + icon + '"></i>' + label + '</div>';
    }
    function stat(label, value, cls) {
        return '<div class="rs-stat ' + cls + '"><div class="rs-stat-lbl">' + label + '</div><div class="rs-stat-val">' + escHtml(String(value)) + '</div></div>';
    }
    function tag(text, cls, icon) {
        var ic = icon ? '<i class="bx ' + icon + '"></i>' : '';
        return '<span class="rs-tag ' + cls + '">' + ic + escHtml(text) + '</span>';
    }
    function flag(label, activo, icon) {
        return '<span class="rs-flag ' + (activo ? 'rf-si' : 'rf-no') + '"><i class="bx ' + icon + '"></i>' + label + ': ' + (activo ? 'Sí' : 'No') + '</span>';
    }
    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function boolVal(val) {
        return val === 1 || val === '1' || val === true || val === 'true';
    }
    function formatDate(dateStr) {
        if (!dateStr) return '—';
        var d = new Date(dateStr);
        return isNaN(d) ? dateStr : d.toLocaleDateString('es-ES', { day:'2-digit', month:'long', year:'numeric' });
    }
</script>

<!-- Contenedor donde se inyecta el resumen -->
 
 <div class="row g-3">
                            <div class="col-md-8">
                                <h6 class="fw-bold text-dark"><i class="bx bx-align-left me-1"></i>Descripción del Puesto</h6>
                                <p id="descripcion_plaza" class="text-secondary lh-base text-justify p-2 bg-light rounded"></p>

                                <h6 class="fw-bold text-dark mt-4"><i class="bx bx-message-detail me-1"></i>Observaciones Internas</h6>
                                <p id="observaciones_content" class="text-muted fst-italic small p-2 border-start border-3"></p>
                            </div>

                            <div class="col-md-4 border-start">
                                <div class="bg-light p-3 rounded border shadow-sm">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Vacantes:</span>
                                        <span id="stat_vacantes" class="badge bg-primary"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Salario:</span>
                                        <span id="stat_salario"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Tipo Selección:</span>
                                        <span id="stat_tipo_seleccion" class="fw-bold small text-primary"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Nómina:</span>
                                        <span id="stat_contrato" class="text-uppercase fw-bold small"></span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="small mb-1"><i class="bx bx-calendar-event text-primary me-1"></i>Publicación: <span id="fecha_publicacion" class="float-end fw-bold"></span></div>
                                    <div class="small"><i class="bx bx-calendar-x text-danger me-1"></i>Fecha Cierre: <span id="fecha_cierre" class="float-end fw-bold text-danger"></span></div>
                                </div>
                                <div id="configuraciones" class="mt-3"></div>
<div id="pnl_resumen_plaza"></div>

                            </div>
                        </div>