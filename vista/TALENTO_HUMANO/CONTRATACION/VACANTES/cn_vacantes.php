<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    const USER_DATA = {
        tipo: "<?= $_SESSION['INICIO']['TIPO'] ?>",
        tipo_tabla: "<?= $_SESSION['INICIO']['NO_CONCURENTE_TABLA'] ?>",
        id: "<?= (in_array($_SESSION['INICIO']['TIPO'], ['DBA', 'ADMINISTRADOR'])) ? '' : $_SESSION['INICIO']['NO_CONCURENTE'] ?>"
    };

    var pagina_actual = 1;
    var por_pagina = 10;
    var total_plazas = 0;
    var todas_plazas = [];
    var plazas_filtradas = [];
    var postulaciones_ids = []; // ← IDs de plazas donde ya está postulado

    $(document).ready(function() {
        // ← QUITAR cargar_plazas() de aquí
        datos_postulante(USER_DATA.id);

        $('#txt_buscar_plaza').on('keyup', function() {
            var q = $(this).val().toLowerCase().trim();
            plazas_filtradas = todas_plazas.filter(function(p) {
                return (p.cn_pla_titulo || '').toLowerCase().includes(q) ||
                    (p.cn_pla_descripcion || '').toLowerCase().includes(q) ||
                    (p.cn_pla_tipo || '').toLowerCase().includes(q);
            });
            pagina_actual = 1;
            renderizar_plazas();
        });
    });

    function datos_postulante(id) {
        if (USER_DATA.tipo === 'DBA' || USER_DATA.tipo === 'ADMINISTRADOR') {
            bloquear_vista();
            new bootstrap.Modal(document.getElementById('modalSinAcceso')).show();
            return;
        }

        const tablasPermitidas = ['_talentoh.th_personas', '_talentoh.th_postulantes'];
        if (!tablasPermitidas.includes(USER_DATA.tipo_tabla)) {
            bloquear_vista();
            new bootstrap.Modal(document.getElementById('modalSinAcceso')).show();
            return;
        }

        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_personas_rol=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                var datos = Array.isArray(response) ? response[0] : response;
                var pos_id = datos ? parseInt(datos.th_pos_id) : 0;

                if (!datos || isNaN(pos_id) || pos_id <= 0) {
                    bloquear_vista();
                    new bootstrap.Modal(document.getElementById('modalSinPostulante')).show();
                } else {
                    $('#txt_pos_id').val(pos_id);
                    cargar_plazas(); // ← solo aquí, cuando ya tenemos pos_id
                }
            },
            error: function() {
                bloquear_vista();
                new bootstrap.Modal(document.getElementById('modalSinPostulante')).show();
            }
        });
    }

    function bloquear_vista() {
        $('#pnl_plazas').html('');
        $('#pnl_paginacion').html('');
        $('#txt_buscar_plaza').prop('disabled', true);
    }

    function irACompletarCV() {
        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=index';
    }

    /* ── Carga postulaciones del postulante y luego las plazas ── */
    function cargar_plazas() {
        $('#pnl_plazas').html(
            '<div class="text-center py-5">' +
            '<div class="spinner-border text-primary" role="status"></div>' +
            '<p class="text-muted mt-3">Cargando plazas...</p></div>'
        );

        var th_pos_id = $('#txt_pos_id').val();

        // Primero obtener postulaciones del postulante
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_postulacionC.php?listar_postulante_plaza=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id: th_pos_id
            },
            success: function(postulaciones) {
                postulaciones_ids = [];
                if (postulaciones && postulaciones.length > 0) {
                    postulaciones.forEach(function(p) {
                        postulaciones_ids.push(String(p.cn_pla_id));
                    });
                }
                _cargar_plazas_ajax();
            },
            error: function() {
                postulaciones_ids = [];
                _cargar_plazas_ajax();
            }
        });
    }

    /* ── AJAX interno para traer el listado de plazas ── */
    function _cargar_plazas_ajax() {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?listar=true',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                todas_plazas = response || [];
                plazas_filtradas = todas_plazas;
                total_plazas = todas_plazas.length;
                $('#badge_total').text(total_plazas + ' plaza' + (total_plazas != 1 ? 's' : ''));
                pagina_actual = 1;
                renderizar_plazas();
            },
            error: function() {
                $('#pnl_plazas').html('<div class="alert alert-danger">Error al cargar las plazas.</div>');
            }
        });
    }

function renderizar_plazas() {
    var inicio = (pagina_actual - 1) * por_pagina;
    var fin = inicio + por_pagina;
    var pagina = plazas_filtradas.slice(inicio, fin);

    var html = '';
    pagina.forEach(function(item) {
        var ya_postulado = postulaciones_ids.includes(String(item._id));
        var hrefVer = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_plaza&_id_plaza=${item._id}`;
        
        html += `
        <div class="card plaza-card-interactive shadow-sm mb-2 rounded-3" 
             onclick="window.location.href='${hrefVer}'">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary-subtle text-primary border-0 text-xs px-2">
                                ${item.descripcion_departamento}
                            </span>
                            <span class="text-muted text-xs">• ${item.descripcion_tipo_seleccion}</span>
                        </div>
                        
                        <h6 class="fw-bold text-dark mb-2">${item.cn_pla_titulo}</h6>
                        
                        <div class="d-flex flex-wrap gap-3 text-muted small">
                            <span class="d-flex align-items-center"><i class="bx bx-wallet me-1"></i>$${item.cn_pla_salario_min} - $${item.cn_pla_salario_max}</span>
                            <span class="d-flex align-items-center"><i class="bx bx-calendar-event me-1"></i>Cierra: ${item.cn_pla_fecha_cierre}</span>
                        </div>
                    </div>

                    <div class="col-md-4 border-divider-custom ps-md-4 mt-3 mt-md-0">
                        <div class="d-flex align-items-center justify-content-between justify-content-md-end gap-4">
                            
                            <div class="text-center d-none d-sm-block">
                                <span class="fw-bold d-block h5 mb-0">${item.cn_pla_num_vacantes}</span>
                                <small class="text-muted text-xs">VACANTES</small>
                            </div>

                            <div class="stop-prop">
                                ${ya_postulado ? 
                                    `<button class="btn btn-success btn-sm rounded-pill px-4 disabled opacity-75 fw-bold">
                                        <i class="bx bx-check-double me-1"></i> Postulado
                                     </button>` : 
                                    `<button onclick="event.stopPropagation(); postular('${item._id}')" 
                                             class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                                        Postularme
                                     </button>`
                                }
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>`;
    });

    $('#pnl_plazas').html(html || '<div class="text-center py-5 opacity-50">No hay plazas disponibles.</div>');
    renderizar_paginacion();
}

    function renderizar_paginacion() {
        var total_paginas = Math.ceil(plazas_filtradas.length / por_pagina);
        if (total_paginas <= 1) {
            $('#pnl_paginacion').html('');
            return;
        }

        var html = '<nav><ul class="pagination pagination-sm justify-content-center mb-0">';

        html += `<li class="page-item ${pagina_actual === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="ir_pagina(${pagina_actual - 1});return false;">
                        <i class="bx bx-chevron-left"></i>
                    </a></li>`;

        for (var i = 1; i <= total_paginas; i++) {
            if (i === 1 || i === total_paginas || Math.abs(i - pagina_actual) <= 1) {
                html += `<li class="page-item ${i === pagina_actual ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="ir_pagina(${i});return false;">${i}</a>
                         </li>`;
            } else if (Math.abs(i - pagina_actual) === 2) {
                html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
            }
        }

        html += `<li class="page-item ${pagina_actual === total_paginas ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="ir_pagina(${pagina_actual + 1});return false;">
                        <i class="bx bx-chevron-right"></i>
                    </a></li>`;

        html += '</ul></nav>';
        $('#pnl_paginacion').html(html);
    }

    function ir_pagina(p) {
        var total_paginas = Math.ceil(plazas_filtradas.length / por_pagina);
        if (p < 1 || p > total_paginas) return;
        pagina_actual = p;
        renderizar_plazas();
        $('html, body').animate({
            scrollTop: $('#pnl_plazas').offset().top - 80
        }, 200);
    }

    function postular(cn_pla_id) {
        let th_pos_id = $('#txt_pos_id').val();
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_postulacionC.php?crear_postulacion=true',
            type: 'POST',
            dataType: 'json',
            data: {
                cn_pla_id: cn_pla_id,
                th_pos_id: th_pos_id
            },
            success: function(response) {
                if (response == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Postulación enviada!',
                        text: '¡Tu postulación fue registrada exitosamente!',
                        confirmButtonColor: '#0d6efd'
                    }).then(function() {
                        cargar_plazas(); // ← recargar para actualizar botones
                    });
                } else if (response == -1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No puede registrarse en esta plaza',
                        text: 'Ya estás postulado o no cumples los requisitos.',
                        confirmButtonColor: '#fd7e14'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar tu postulación',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }
</script>

<style>
    /* Efecto hover en el card */
    .hover-shadow:hover {
        background-color: #fcfdfe;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08) !important;
        transform: translateY(-1px);
    }

    /* Divisor vertical para separar acciones de contenido */
    @media (min-width: 768px) {
        .border-start-md {
            border-left: 1px solid #f0f0f0 !important;
        }
    }

    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    /* Estilo para los badges sutiles (compatibilidad Bootstrap 5) */
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }

    /* Resaltar título al pasar el mouse */
    .hover-primary:hover {
        color: #0d6efd !important;
    }
</style>

<style>
    /* Estilo base de la tarjeta */
    .plaza-card-interactive {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        border: 1px solid #e2e8f0 !important;
        /* Borde gris suave por defecto */
    }

    /* Cambio de color al hacer Hover */
    .plaza-card-interactive:hover {
        border-color: #0d6efd !important;
        /* El borde se pone azul */
        background-color: #f8fbff;
        /* Un fondo azul casi imperceptible */
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.08) !important;
    }

    /* Para que el botón de postular no dispare el click de la tarjeta */
    .stop-prop {
        position: relative;
        z-index: 10;
    }

    @media (min-width: 768px) {
        .border-divider-custom {
            border-left: 1px solid #edf2f7 !important;
        }
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
</style>

<div class="page-wrapper bg-light">
    <div class="page-content py-4">

        <div class="row g-4">
            <div class="col-lg-9">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-2">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0"><i class="bx bx-search text-primary"></i></span>
                            <input type="text" id="txt_buscar_plaza" class="form-control border-0 shadow-none" placeholder="Buscar cargo o área...">
                            <button class="btn btn-primary rounded-3 px-4">Buscar</button>
                        </div>
                    </div>
                </div>

                <div id="pnl_plazas" class="d-grid gap-2">
                </div>

                <div id="pnl_paginacion" class="mt-4"></div>
            </div>

            <div class="col-lg-3 d-none d-lg-block">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Resumen</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Plazas</span>
                            <span class="badge bg-primary-subtle text-primary rounded-pill" id="badge_total">0</span>
                        </div>
                        <hr class="my-2 opacity-25">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Postulaciones</span>
                            <span class="badge bg-success-subtle text-success rounded-pill">Activo</span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 bg-primary text-white shadow-sm">
                    <div class="card-body p-4 text-center">
                        <i class="bx bx-rocket mb-2 fs-1"></i>
                        <h6 class="fw-bold">Impulsa tu carrera</h6>
                        <p class="small opacity-75">Mantén tu perfil al 100% para recibir mejores ofertas.</p>
                        <button class="btn btn-light btn-sm w-100 rounded-pill fw-bold">Mi Perfil</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Sin perfil de postulante -->
<div class="modal fade" id="modalSinPostulante" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div style="height:6px;background:linear-gradient(90deg,#0d6efd,#6610f2);"></div>
            <div class="modal-body text-center px-5 py-4">
                <div class="mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:72px;height:72px;background:linear-gradient(135deg,#e8f0fe,#f3e8ff);">
                        <i class="bx bx-file-blank text-primary" style="font-size:2.2rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-2">¡Un paso antes de continuar!</h5>
                <p class="text-muted mb-1" style="line-height:1.6;">
                    Para postularte a cualquier plaza, primero necesitas
                    <strong class="text-dark">completar tu hoja de vida</strong>.
                </p>
                <p class="text-muted small mb-4" style="line-height:1.6;">
                    Es rápido y solo debes hacerlo una vez. Con tu CV registrado podrás aplicar
                    a todas las oportunidades disponibles. 🚀
                </p>
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <div class="text-center">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-1 fw-bold"
                            style="width:34px;height:34px;font-size:.85rem;">1</div>
                        <p class="mb-0 text-muted" style="font-size:.75rem;">Llena tu<br>hoja de vida</p>
                    </div>
                    <div class="d-flex align-items-center pb-3">
                        <i class="bx bx-chevron-right text-muted fs-4"></i>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-light border text-muted d-inline-flex align-items-center justify-content-center mb-1 fw-bold"
                            style="width:34px;height:34px;font-size:.85rem;">2</div>
                        <p class="mb-0 text-muted" style="font-size:.75rem;">Accede a<br>las plazas</p>
                    </div>
                    <div class="d-flex align-items-center pb-3">
                        <i class="bx bx-chevron-right text-muted fs-4"></i>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-light border text-muted d-inline-flex align-items-center justify-content-center mb-1 fw-bold"
                            style="width:34px;height:34px;font-size:.85rem;">3</div>
                        <p class="mb-0 text-muted" style="font-size:.75rem;">¡Postúlate<br>y listo!</p>
                    </div>
                </div>
                <button onclick="irACompletarCV()" class="btn btn-primary px-4 rounded-pill">
                    <i class="bx bx-edit me-2"></i>Completar mi hoja de vida
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Sin acceso -->
<div class="modal fade" id="modalSinAcceso" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div style="height:6px;background:linear-gradient(90deg,#dc3545,#fd7e14);"></div>
            <div class="modal-body text-center px-5 py-4">
                <div class="mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:72px;height:72px;background:linear-gradient(135deg,#fde8e8,#ffe8d6);">
                        <i class="bx bx-lock-alt text-danger" style="font-size:2.2rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-2">Acceso restringido</h5>
                <p class="text-muted mb-1" style="line-height:1.6;">
                    Este apartado es exclusivo para <strong class="text-dark">postulantes</strong>.
                </p>
                <p class="text-muted small mb-4" style="line-height:1.6;">
                    Tu rol actual no tiene permitido visualizar ni interactuar con las plazas de postulación. 🔒
                </p>
                <button onclick="history.back()" class="btn btn-danger px-4 rounded-pill">
                    <i class="bx bx-arrow-back me-2"></i>Regresar
                </button>
            </div>
        </div>
    </div>
</div>