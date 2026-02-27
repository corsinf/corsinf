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

    console.log(<?= json_encode($_SESSION['INICIO']['NO_CONCURENTE_TABLA']) ?>);

    var pagina_actual = 1;
    var por_pagina = 10;
    var total_plazas = 0;
    var todas_plazas = [];
    var plazas_filtradas = [];

    $(document).ready(function() {
        cargar_plazas();
        datos_postulante(USER_DATA.id);

        // Búsqueda en tiempo real
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

        // Si es DBA u ADMINISTRADOR, bloquear de inmediato
        if (USER_DATA.tipo === 'DBA' || USER_DATA.tipo === 'ADMINISTRADOR') {
            bloquear_vista();
            var modal = new bootstrap.Modal(document.getElementById('modalSinAcceso'));
            modal.show();
            return; // No hace la petición Ajax
        }
        const tablasPermitidas = ['_talentoh.th_personas', '_talentoh.th_postulantes'];

        if (!tablasPermitidas.includes(USER_DATA.tipo_tabla)) {
            bloquear_vista();
            var modal = new bootstrap.Modal(document.getElementById('modalSinAcceso'));
            modal.show();
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
                    var modal = new bootstrap.Modal(document.getElementById('modalSinPostulante'));
                    modal.show();
                } else {
                    $('#txt_pos_id').val(pos_id);
                }
            },
            error: function() {
                bloquear_vista();
                var modal = new bootstrap.Modal(document.getElementById('modalSinPostulante'));
                modal.show();
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

    function cargar_plazas() {
        $('#pnl_plazas').html(
            '<div class="text-center py-5">' +
            '<div class="spinner-border text-primary" role="status"></div>' +
            '<p class="text-muted mt-3">Cargando plazas...</p></div>'
        );

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
                $('#pnl_plazas').html(
                    '<div class="alert alert-danger">Error al cargar las plazas.</div>'
                );
            }
        });
    }

    function renderizar_plazas() {
        var inicio = (pagina_actual - 1) * por_pagina;
        var fin = inicio + por_pagina;
        var pagina = plazas_filtradas.slice(inicio, fin);

        if (plazas_filtradas.length === 0) {
            $('#pnl_plazas').html(
                '<div class="text-center py-5 text-muted">' +
                '<i class="bx bx-search-alt fs-1 d-block mb-2"></i>' +
                'No se encontraron plazas.</div>'
            );
            $('#pnl_paginacion').html('');
            return;
        }

        var html = '';
        pagina.forEach(function(item) {
            var tipo = item.cn_pla_tipo || 'Externa';
            var vacantes = item.cn_pla_num_vacantes || 1;
            var modalidad = item.cn_pla_modalidad || 'Presencial';
            var ciudad = item.cn_pla_ciudad || '';
            var desc = item.cn_pla_descripcion || '';
            var descCorta = desc.length > 120 ? desc.substring(0, 120) + '...' : desc;
            var fechaPublic = item.cn_pla_fecha || item.created_at || '';

            // Badge tipo
            var colorTipo = tipo.toLowerCase() === 'interna' ? 'primary' :
                tipo.toLowerCase() === 'mixta' ? 'purple' : 'success';
            var iconTipo = tipo.toLowerCase() === 'interna' ? 'bx-building' :
                tipo.toLowerCase() === 'mixta' ? 'bx-transfer' : 'bx-globe';

            // Badge vacantes
            var badgeVac = parseInt(vacantes) > 1 ?
                '<span class="badge bg-warning text-dark ms-1"><i class="bx bx-group me-1"></i>Múltiples vacantes</span>' :
                '';

            var hrefVer = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_plaza&_id_plaza=${item._id}`;
            var hrefEditar = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=cn_registrar_plaza&_id_plaza=${item._id}`;

            html += `
            <div class="card mb-3 border shadow-sm rounded-3 plaza-card" style="border-left: 4px solid #0d6efd !important; transition: box-shadow .2s;">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-start g-2">

                        <!-- Ícono / Letra inicial -->
                        <div class="col-auto d-none d-md-flex">
                            <div class="rounded-3 d-flex align-items-center justify-content-center fw-bold text-white fs-4"
                                 style="width:52px;height:52px;background:linear-gradient(135deg,#0d6efd,#6610f2);flex-shrink:0;">
                                ${(item.cn_pla_titulo || 'P').charAt(0).toUpperCase()}
                            </div>
                        </div>

                        <!-- Contenido principal -->
                        <div class="col">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <a href="${hrefVer}" class="fw-bold fs-6 text-dark text-decoration-none stretched-link-custom"
                                   style="line-height:1.3;">${item.cn_pla_titulo}</a>
                                ${badgeVac}
                            </div>

                            <p class="text-muted small mb-2" style="line-height:1.5;">${descCorta || '<em>Sin descripción</em>'}</p>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                ${ciudad ? `<span class="badge bg-light text-secondary border"><i class="bx bx-map me-1"></i>${ciudad}</span>` : ''}
                                <span class="badge bg-light text-secondary border"><i class="bx bx-buildings me-1"></i>${modalidad}</span>
                                <span class="badge bg-light text-secondary border"><i class="bx bx-user me-1"></i>${vacantes} vacante${vacantes != 1 ? 's' : ''}</span>
                                <span class="badge bg-light text-${colorTipo} border border-${colorTipo}">
                                    <i class="bx ${iconTipo} me-1"></i>${tipo}
                                </span>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="col-12 col-md-auto d-flex flex-row flex-md-column align-items-md-end justify-content-between gap-2 mt-2 mt-md-0">
                            ${fechaPublic ? `<small class="text-muted d-block text-md-end"><i class="bx bx-calendar me-1"></i>${fechaPublic}</small>` : '<span></span>'}
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="${hrefVer}" class="btn btn-info btn-xs" title="Ver plaza">
                                    <i class="bx bx-show fs-7 me-0 fw-bold"></i>
                                </a>
                                <a class="btn btn-success btn-xs" title="Postular" onclick="postular('${item._id}')">
                                    <i class="bx bx-send fs-7 me-0 fw-bold"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>`;
        });

        $('#pnl_plazas').html(html);

        // Hover effect sin CSS extra
        $('.plaza-card').hover(
            function() {
                $(this).css('box-shadow', '0 4px 20px rgba(13,110,253,.15)');
            },
            function() {
                $(this).css('box-shadow', '');
            }
        );

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
                        text: response.mensaje,
                        confirmButtonColor: '#0d6efd'
                    });
                } else if (response == -1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No puede registrarse en esta plaza',
                        text: response.mensaje,
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

<div class="page-wrapper">
    <div class="page-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Plazas</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Todas las plazas</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-10 col-lg-11 mx-auto">

                <!-- Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <!-- Buscador -->
                    <div class="mb-4">
                        <div class="input-group input-group-sm" style="max-width:320px;">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bx bx-search text-muted"></i>
                            </span>
                            <input type="text" id="txt_buscar_plaza" class="form-control border-start-0 ps-0"
                                placeholder="Buscar por título, tipo...">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="txt_pos_id" id="txt_pos_id" value="">

                <!-- Listado de plazas -->
                <div id="pnl_plazas">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted mt-3">Cargando plazas...</p>
                    </div>
                </div>

                <!-- Paginación -->
                <div id="pnl_paginacion" class="mt-3"></div>

            </div>
        </div>

    </div>
</div>

<!-- Modal: Sin perfil de postulante -->
<div class="modal fade" id="modalSinPostulante" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- Franja superior decorativa -->
            <div style="height:6px;background:linear-gradient(90deg,#0d6efd,#6610f2);"></div>

            <div class="modal-body text-center px-5 py-4">

                <!-- Ícono animado -->
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

                <!-- Pasos visuales -->
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

<!-- Modal: DBA sin acceso -->
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